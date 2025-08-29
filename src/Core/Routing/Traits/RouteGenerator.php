<?php

namespace Core\Routing\Traits;

use Core\Contracts\HttpMethodAttributeInterface;
use Core\Exceptions\DuplicateHttpMethodAttributeException;

trait RouteGenerator
{
    /**
     * @return array
     */
    public static function generate(): array
    {
        $paths = [];
        $directoryIterator = new \RecursiveDirectoryIterator(CONTROLLER_PATH, \RecursiveDirectoryIterator::SKIP_DOTS);
        $filterIterator = new \RecursiveCallbackFilterIterator($directoryIterator, function (\SplFileInfo $item) {
            if ($item->isDir()) {
                return true;
            }

            if ($item->isFile() && $item->getExtension() == "php") {
                return true;
            }

            return false;
        });

        $files = new \RecursiveIteratorIterator($filterIterator);

        foreach ($files as $file) {
            $strpos = strpos($file->getRealPath(), self::CONTROLLER_DIR);
            $class = str_replace(".php", "", substr($file->getRealPath(), $strpos + strlen(self::CONTROLLER_PARENT)));
            $paths[] = str_replace("/", "\\", $class);
        }

        $result = [];

        foreach ($paths as $class) {
            $reflecionClass = new \ReflectionClass($class);
            $routeAttribute = $reflecionClass->getAttributes(\Core\Attributes\HttpRequest\Route::class);
            $path = null;
            try {
                $path = $routeAttribute[0]->getArguments()[0];
            } catch (\Exception $e) {
                //TODO Add Logging if implementing Logger
                continue;
            }
            $methods = [];
            foreach ($reflecionClass->getMethods() as $method) {
                $attributes = $method->getAttributes();
                foreach ($attributes as $httpMethod) {
                    if (!is_subclass_of($httpMethod->getName(), \Core\Contracts\HttpMethodAttributeInterface::class)) {
                        continue;
                    }
                    $name = $httpMethod->getName()::getMethod();
                    if (isset($methods[$name])) {
                        throw new DuplicateHttpMethodAttributeException(
                            "Duplicate HTTP method attribute: $name, in $class"
                        );
                    }
                    $methods[$name] = $method->getName();
                }
            }

            $result[$path] = [
                'class' => $class,
                'methods' => $methods
            ];
        }

        return $result;
    }
}
