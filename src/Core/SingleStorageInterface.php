<?php

namespace Running\Core;

/**
 * Object that can save some single value and load saved value
 *
 * Interface SingleStorageInterface
 * @package Running\Core
 *
 * @codeCoverageIgnore
 */
interface SingleStorageInterface
{

    public function load();
    public function save();

    public function get();
    public function set($contents);

}