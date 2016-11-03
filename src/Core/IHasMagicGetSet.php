<?php

namespace Running\Core;

/**
 * Interface for classes have "magic" get- set- methods
 *
 * Interface IHasMagicGetSet
 * @package Running\Core
 */
interface IHasMagicGetSet
{

    public function __isset($key);

    public function __unset($key);

    public function __get($key);

    public function __set($key, $val);

}