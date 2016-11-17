<?php

namespace Running\Core;

/**
 * Interface ISingleton
 * @package Running\Core
 *
 * @codeCoverageIgnore
 */
interface ISingleton
{

    public static function instance(...$args);

}