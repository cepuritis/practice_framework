<?php

namespace Core\App;

class Application
{
    private static ?Application $instance = null;
    private array $instances = [];
    private array $bindings = [];
    private array $contextualBinding = [];
    private function __construct()
    {
    }

    public function flush(): void
    {
        $this->instances = [];
        $this->bindings = [];
        $this->contextualBinding = [];
    }
    /**
     * @return Application
     */
    public static function getInstance(): Application
    {
        if (!self::$instance) {
            self::$instance =new static();
        }

        return self::$instance;
    }

    public function bind(string $abstract, $concrete, $shared = true): void
    {
        $this->bindings[$abstract] = compact('concrete', 'shared');
    }

    public function bindWithContext(string $abstract, $concrete, string $context, $shared = true): void
    {
        $this->contextualBinding[$abstract][$context] = compact('concrete', 'shared');
    }

    /**
     * @param string $class
     * @param array $parameters
     * @param string|null $currentContext
     * @return mixed|object|string|null
     */
    public function make(string $class, array $parameters = [], string $currentContext = null)
    {
        $isShared = true;
        $concrete = $class;
        $useContextualBinding = false;
        $instance = null;

        if ($currentContext && isset($this->contextualBinding[$class][$currentContext])) {
            $binding = $this->contextualBinding[$class][$currentContext];
            $useContextualBinding = true;
        } elseif (isset($this->bindings[$class])) {
            $binding = $this->bindings[$class];
        }

        if (isset($binding)) {
            $isShared = $binding['shared'];
            $concrete = $binding['concrete'];
        }

        $key = $useContextualBinding ? $class . '@' . $currentContext : $class;

        $instance = $this->getInstanceIfExists($key, $concrete, $isShared, $useContextualBinding, $currentContext);

        $instance = $instance ?? $this->build($concrete, $parameters);

        if ($isShared) {
            $this->instances[$key] = $instance;
        }

        return $instance;
    }

    /**
     * @param $key
     * @param $concrete
     * @param $isShared
     * @param $useContextualBinding
     * @param $currentContext
     * @return mixed|null
     */
    private function getInstanceIfExists($key, $concrete, $isShared, $useContextualBinding, $currentContext)
    {
        if ($concrete === static::class) {
            return $this;
        }

        $instance = null;
        if (isset($this->instances[$key])) {
            return $this->instances[$key];
        } elseif ($isShared && $this->hasInstanceOf($concrete)) {
            //If the same context and shared instance has already been assigned to interface, reuse it
            foreach ($this->instances as $existingKey => $existingInstance) {
                if (get_class($existingInstance) === $concrete) {
                    if (str_contains($existingKey, '@')) {
                        if ($useContextualBinding) {
                            $contextPart = strstr('@', $existingKey, 2)[1];
                            if ($contextPart === $currentContext) {
                                $instance = $existingInstance;
                                break;
                            }
                        }
                        continue;
                    }
                    $instance = $existingInstance;
                    break;
                }
            }
        }

        return $instance;
    }

    /**
     * Use shared instance if already exists, if not create transient instance
     *
     * @param string $class
     * @param array $parameters
     * @param string|null $currentContext
     * @return mixed
     */
    public function makeTransient(string $class, array $parameters = [], string $currentContext = null): mixed
    {
        $concrete = $class;

        $useContextualBinding = false;
        if ($currentContext && isset($this->contextualBinding[$class][$currentContext])) {
            $useContextualBinding = true;
            $concrete = $this->contextualBinding[$class][$currentContext]['concrete'];
        } elseif (isset($this->bindings[$class])) {
            $concrete = $this->bindings[$class]['concrete'];
        }

        $key = $useContextualBinding ? $class . '@' . $currentContext : $class;

        if (isset($this->instances[$key])) {
            return $this->instances[$key];
        }

        $instance = $this->getInstanceIfExists($key, $concrete, true, $useContextualBinding, $currentContext);

        return $instance ?? $this->build($concrete, $parameters, false);
    }

    private function build(string | \Closure $concrete, array $parameters = [], bool $shared = true)
    {
        $instance = null;

        if ($concrete instanceof \Closure) {
            $instance = $concrete($this, $parameters);
        } else {
            $reflection = new \ReflectionClass($concrete);

            $getDependency = fn ($concrete) => $shared ? $this->make($concrete, [], $reflection->getName())
                : $this->makeTransient($concrete, [], $reflection->getName());

            $constructor = $reflection->getConstructor();
            $dependencies = [];

            if ($constructor) {
                foreach ($constructor->getParameters() as $parameter) {
                    $paramName = $parameter->getName();
                    $paramType = $parameter->getType();

                    if (array_key_exists($paramName, $parameters)) {
                        $paramValue = $parameters[$paramName];
                        //Try to instantiate only if param type is not primitive and value is string
                        if (is_string($paramValue)
                            && (!$paramType || !$paramType->isBuiltin())
                            && class_exists($paramValue)) {
                            $dependencies[] = $getDependency($paramValue);
                        } else {
                            $dependencies[] = $paramValue;
                        }
                    } elseif ($paramType && !$paramType->isBuiltin()) {
                        $dependencies[] = $getDependency($paramType->getName());
                    } elseif ($parameter->isDefaultValueAvailable()) {
                        $dependencies[] = $parameter->getDefaultValue();
                    } else {
                        throw new \RuntimeException(
                            sprintf("Cannot resolve parameter %s for class %s", $paramName, $reflection->getName())
                        );
                    }
                }
                $instance = $reflection->newInstanceArgs($dependencies);
            } else {
                $instance = $reflection->newInstance();
            }
        }

        return $instance;
    }

    /**
     * @throws \ReflectionException
     */
    public function get($class, $parameters = [])
    {
        return $this->make($class, $parameters);
    }

    /**
     * @param mixed $instance
     * @return mixed
     */
    public function hasInstanceOf(mixed $instance): mixed
    {
        return in_array($instance, array_values(array_map(fn ($instance) => get_class($instance), $this->instances)));
    }

    /**
     * @return void
     */
    public function getInstances()
    {
        foreach ($this->instances as $className => $object) {
            echo $className . " => (". spl_object_id($object) .")" . get_class($object) . "</br>";
        }
    }
}
