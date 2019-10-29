<?php


namespace Core;

use Error;
use ReflectionClass;
use Core\Exceptions\ContainerException;

class Container
{
    /* Links component to class */
    private $components = [];

    /* Links class to instance */
    private $instances = [];

    /* Links interface to class */
    private $definitions = [];

    /* Links class to callback */
    private $bindings = [];

    public function __construct()
    {
        $services = require_once(CONF . '/' . 'services.php');
        if (!is_array($services)) throw new Error('Invalid configuration file');

        if (array_key_exists('definitions', $services)) {
            foreach ($services['definitions'] as $key => $value) {
                $this->define($key, $value);
            }
        }

        if (array_key_exists('singletons', $services)) {
            foreach($services['singletons'] as $key => $value) {
                $this->singleton($key, $value);
            }
        }

        if (array_key_exists('components', $services)) {
            foreach($services['components'] as $key => $value) {
                $this->setComponent($key, $value);
            }
        }

        if (array_key_exists('bindings', $services)) {
            foreach($services['bindings'] as $key => $value) {
                $this->bind($key, $value);
            }
        }
    }

    /* Component */
    public function getComponent(string $name)
    {
        if ($this->hasComponent($name)) {
            return $this->get($this->components[$name]);
        } else {
            throw new Error('No component defined named: ' . $name);
        }
    }

    public function setComponent(string $name, string $class)
    {
        $this->components[$name] = $class;
    }
    /* Component */

    public function get(string $class)
    {
        // looking for definition
        if ($this->hasDefinition($class)) $class = $this->definitions[$class];

        // looking for instance
        if ($this->hasInstance($class)) return $this->instances[$class];

        // or if we have binding already
        if ($this->hasBinding($class)) return $this->bindings[$class]($this);

        // if we don't have any definition, we try to instantiate class
        return $this->resolve($class);
    }

    private function guardClassExists($class) {
        if (!class_exists($class)) throw new ContainerException('No such class: ' . $class);
    }

    private function guardReflectorIsInstantiable(ReflectionClass $reflector) {
        if (!$reflector->isInstantiable()) throw new ContainerException('No definition for not instantiable ' . $class);
    }

    private function guardNoPrimitivesInConstructor(ReflectionClass $reflector) {
        $parameters = $reflector->getConstructor()->getParameters();
        $nonClassParams = array_filter($parameters, function ($parameter) {
            return !$parameter->getClass();
        });
        if (!empty($nonClassParams)) throw new ContainerException('Cannot instantiate class ' . $reflector->getName() . '. Primitive parameters in consturctor');
    }

    private function guardReflectionParameterNotRecursive(string $className, string $parameterClassName)
    {
        if ($className == $parameterClassName) throw new ContainerException('Recursive injection of '. $className);
    }

    private function getReflectorConstructorArguments(ReflectionClass $reflector)
    {
        $arguments = [];
        $parameters = $reflector->getConstructor()->getParameters();
        if (empty($parameters)) return $arguments;
        $className = $reflector->getName();
        foreach($parameters as $parameter) {
            $parameterClassName = $parameter->getClass()->getName();
            $this->guardReflectionParameterNotRecursive($className, $parameterClassName);
            $arguments[] = $this->get($parameterClassName);
        }
        return $arguments;
    }

    private function resolve($class)
    {
        $this->guardClassExists($class);

        $reflector = new ReflectionClass($class);
        $this->guardReflectorIsInstantiable($reflector);

        $constructor = $reflector->getConstructor();
        if ($constructor === null) return $reflector->newInstance();

        $parameters = $constructor->getParameters();
        if (empty($parameters)) return $reflector->newInstance();

        $this->guardNoPrimitivesInConstructor($reflector);
        $arguments = $this->getReflectorConstructorArguments($reflector);

        return $reflector->newInstanceArgs($arguments);
    }

    public function bind(string $class, Callable $closure)
    {
        $this->bindings[$class] = $closure;
    }

    public function singleton(string $class, Callable $closure)
    {
        $this->instances[$class] = $closure();
    }

    public function define(string $abstract, string $concrete)
    {
        $this->definitions[$abstract] = $concrete;
    }

    private function hasComponent(string $name)
    {
        return array_key_exists($name, $this->components);
    }

    private function hasDefinition(string $key)
    {
        return array_key_exists($key, $this->definitions);
    }

    private function hasInstance(string $key)
    {
        return array_key_exists($key, $this->instances);
    }

    private function hasBinding(string $key)
    {
        return array_key_exists($key, $this->bindings);
    }
}