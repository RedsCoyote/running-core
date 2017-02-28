<?php

namespace Running\Storages;

/**
 * Interface KeyValueStorageAwareInterface
 * @package Running\Storages
 */
interface KeyValueStorageAwareInterface
{

    public function setStorage(KeyValueStorageInterface $storage);
    public function getStorage(): KeyValueStorageInterface;

}