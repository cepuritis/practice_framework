<?php

namespace Core\App;

class Application
{
    private static ?Application $instance = null;
    private array $instances = [];
    private array $bindings = [];
    private function __construct()
    {
    }

    public static function getInstance(): Application
    {
        if (!self::$instance) {
            self::$instance =new static();
        }

        return self::$instance;
    }

    public function bind(string $abstract, $concrete, $shared = true)
    {
        $this->bindings[$abstract] = compact('concrete', 'shared');
    }

    /**
     * @param string $class
     * @param $parameters
     * @return mixed|void
     * @throws \ReflectionException
     */
    public function make(string $class, $parameters = [])
    {
        $isShared = true;
        $concrete = $class;

        if (isset($this->instances[$class])) {
            return $this->instances[$class];
        }

        if (isset($this->bindings[$class])) {
            $isShared = $this->bindings[$class]['shared'];
            $concrete = $this->bindings[$class]['concrete'];
        }

        $instance = $this->build($concrete, $parameters);

        if ($isShared) {
            $this->instances[$class] = $instance;
        }

        return $instance;
    }

    /**
     * Use shared instance if already exists, if not create transient instance
     *
     * @param string $class
     * @param $parameters
     * @return mixed|object|string|null
     */
    public function makeTransient(string $class, $parameters = []): mixed
    {
        $concrete = $class;
        if (isset($this->instances[$class])) {
            return $this->instances[$class];
        }
        if (isset($this->bindings[$class])) {
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
                            $dependencies[] =$shared ? $this->make($paramValue) : $this->makeTransient($paramName);
                        } else {
                            $dependencies[] = $paramValue;
                        }
                    } elseif ($paramType && !$paramType->isBuiltin()) {
                        $dependencies[] = $this->make($paramType->getName());
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
