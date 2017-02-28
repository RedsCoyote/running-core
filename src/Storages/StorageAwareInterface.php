<?php

namespace Running\Storages;

/**
 * Interface StorageAwareInterface
 * @package Running\Storages
 */
interface StorageAwareInterface
{

    public function setStorage(StorageInterface $storage);
    public function getStorage(): StorageInterface;

}