<?php

namespace Phonghaw2\X;

use Phonghaw2\X\Console\Adapter;

class X
{
    /** @var Adapter */
    private $adapter;

    /**
     * X constructor
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Magic method that allows you to use methods in console
     * Triggered when invoking inaccessible methods in an object context.
     * @param string $method name of the accessor
     * @param array $arguments list of arguments
     *
     * @return mixed
     */
    public function __call(string $method, array $arguments)
    {
        if (method_exists($this->adapter, $method)) {
            return $this->adapter->{$method}();
        }

        return $this;
    }
}
