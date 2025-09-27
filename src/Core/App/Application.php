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

        if ($currentContext && isset($this->contextualBinding[$class][$currentContext])) {
            $binding = $this->contextualBinding[$class][$currentContext];
            $isShared = $binding['shared'];
            $concrete = $binding['concrete'];
        } elseif (isset($this->bindings[$class])) {
            $isShared = $this->bindings[$class]['shared'];
            $concrete = $this->bindings[$class]['concrete'];
        }

        $key = $currentContext ? $class . '@' . $currentContext : $class;

        if (isset($this->instances[$key])) {
            return $this->instances[$key];
        }

        $instance = $this->build($concrete, $parameters);

        if ($isShared) {
            $this->instances[$key] = $instance;
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

        $key = $currentContext ? $class . '@' . $currentContext : $class;

        if (isset($this->instances[$key])) {
            return $this->instances[$key];
        }
        if ($currentContext && isset($this->contextualBinding[$class][$currentContext])) {
            $concrete = $this->contextualBinding[$class][$currentContext]['concrete'];
        } elseif (isset($this->bindings[$class])) {
            $concrete = $this->bindings[$class]['concrete'];
        }

        return $this->build($concrete, $parameters, false);
    }

    private function build(string | \Closure $concrete, array $parameters = [], bool $shared = true)
    {
        $instance = null;

        if ($concrete instanceof \Closure) {
            $instance = $concrete($this, $parameters);
        } else {
            $reflection = new \ReflectionClass($concrete);

            $getDependency = fn($concrete) => $shared ? $this->make($concrete, [], $reflection->getName())
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
}
