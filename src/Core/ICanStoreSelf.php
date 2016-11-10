<?php

namespace Running\Core;

/**
 * Object can store itself
 *
 * Interface ICanStoreSelf
 * @package Running\Core
 *
 * @codeCoverageIgnore
 */
interface ICanStoreSelf
{

    public function load();
    public function reload();
    public function save();

}