<?php
namespace Cli;

use Core\Contracts\RouterInterface;

class GenerateRoutes implements CommandInterface
{
    private array $routers = [];

    /**
     * @param array $args
     * @return void
     */
    public function execute(array $args): void
    {
        $routerNamespace = "Core\\Routing\\Routers\\";
        foreach (glob(__DIR__ . "/../src/Core/Routing/Routers/*.php") as $file) {
            /**
             * @var RouterInterface|string $class
             */
            $class = $routerNamespace . pathinfo($file, PATHINFO_FILENAME);
            if (class_exists($class) && in_array(RouterInterface::class, class_implements($class))) {
                $this->routers[$class] = $class::generate();
            }
        }

        $phpCode = "<?php\n\nreturn " . str_replace(['array (', ')'], ['[', ']'], var_export($this->routers, true)) . ";\n";

// Write to file
        file_put_contents(CONFIG_PATH . '/generated/routes.php', $phpCode);
    }
}
