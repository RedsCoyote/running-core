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

    public function set($value);
    public function get();

    public function load();
    public function reload();
    public function save();

    public function isNew(): bool;
    public function wasNew(): bool;
    public function isChanged(): bool;

}