<?php

namespace Running\Core;

/**
 * Interface for classes have "magic" get- set- methods
 *
 * Interface HasMagicGetSetInterface
 * @package Running\Core
 *
 * @codeCoverageIgnore
 */
interface HasMagicGetSetInterface
{

    public function __isset($key);

    public function __unset($key);

    public function __get($key);

    public function __set($key, $val);

}