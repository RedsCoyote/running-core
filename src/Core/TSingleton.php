<?php

namespace Running\Core;

/**
 * Class TSingleton
 * @package Running\Core
 *
 * @implements \Running\Core\ISingleton
 */
trait TSingleton
    //implements ISingleton
{

    /**
     * @codeCoverageIgnore
     */
    protected function __construct()
    {
    }

    /**
     * @codeCoverageIgnore
     */
    protected function __clone()
    {
    }

    /**
     * @param array $args
     * @return static
     */
    public static function instance(...$args)
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static(...$args);
        }
        return $instance;
    }

}