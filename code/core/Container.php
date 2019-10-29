<?php


namespace Core;

use Error;


class Container
{
    private $components = [];
    /* Links component to class */

    private $instances = [];
    /* Links class to instance */

    private $definitions = [];
    /* Links interface to class */

    private $bindings = [];
    /* Links class to callback */

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
        /*
         * Определяем имя для компонента
         *  */
    }

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

    private function resolve($key)
    {
        return NULL;
        /*
         * TODO сделать нормальный resolve();
         *
         * */
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