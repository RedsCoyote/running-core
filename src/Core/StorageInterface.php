<?php

namespace Running\Core;

/**
 * Object that can save some data and load saved data
 *
 * Interface StorageInterface
 * @package Running\Core
 *
 * @codeCoverageIgnore
 */
interface StorageInterface
{

    public function set($value);
    public function get();

    public function load();
    public function reload();
    public function save();
    public function delete();

    public function isNew(): bool;
    public function wasNew(): bool;
    public function isChanged(): bool;
    public function isDeleted(): bool;

}