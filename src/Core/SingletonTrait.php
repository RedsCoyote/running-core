<?php

namespace Running\Core;

/**
 * Trait SingletonTrait
 * @package Running\Core
 *
 * @implements \Running\Core\SingletonInterface
 */
trait SingletonTrait
    //implements SingletonInterface
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
    private function __clone()
    {
    }

    /**
     * @codeCoverageIgnore
     */
    private function __wakeup()
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