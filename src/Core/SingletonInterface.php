<?php

namespace Running\Core;

/**
 * Interface SingletonInterface
 * @package Running\Core
 *
 * @codeCoverageIgnore
 */
interface SingletonInterface
{

    public static function instance(...$args);

}