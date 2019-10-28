<?php

namespace Core;

class Params
{
    use SingletonTrait;

    private $props = [];

    public function get($key)
    {
        $this->props[$key] ?: null;
    }

    public function set($key, $val)
    {
        $this->props[$key] = $val;
    }

    public function all()
    {
        return $this->props;
    }
}