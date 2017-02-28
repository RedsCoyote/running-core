<?php

namespace Running\Storages;

/**
 * Interface SingleValueStorageAwareInterface
 * @package Running\Storages
 */
interface SingleValueStorageAwareInterface
{

    public function setStorage(SingleValueStorageInterface $storage);
    public function getStorage(): SingleValueStorageInterface;

}