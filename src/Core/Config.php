<?php

namespace Running\Core;

use Running\Storages\SingleValueStorageAwareInterface;
use Running\Storages\SingleValueStorageInterface;

/**
 * Config class
 *
 * Class Config
 * @package Running\Core
 */
class Config
    extends Std
    implements SingleValueStorageInterface, SingleValueStorageAwareInterface
{

    /**
     * @var \Running\Storages\SingleValueStorageInterface|null $storage;
     */
    protected $storage;

    /**
     * @param \Running\Storages\SingleValueStorageInterface|iterable|null $arg
     */
    public function __construct(/* SingleValueStorageInterface | iterable */$arg = null)
    {
        if ( (is_object($arg) && ($arg instanceof SingleValueStorageInterface)) ) {
            $this->setStorage($arg)->load();
        } else {
            parent::__construct($arg);
        }
    }

    /**
     * @param \Running\Storages\SingleValueStorageInterface $storage
     * @return $this
     */
    public function setStorage(SingleValueStorageInterface $storage)
    {
        $this->storage = $storage;
        return $this;
    }

    /**
     * @return \Running\Storages\SingleValueStorageInterface|null
     */
    public function getStorage(): /*?*/SingleValueStorageInterface
    {
        return $this->storage;
    }

    /**
     * Loads config from storage
     *
     * @return $this
     * @throws \Running\Core\Exception
     */
    public function load()
    {
        if (empty($this->storage)) {
            throw new Exception('Empty config storage');
        }
        $storage = $this->getStorage();
        $storage->load();
        return $this->fromArray($storage->get());
    }

    /**
     * @return $this
     * @throws \Running\Core\Exception
     */
    public function save()
    {
        if (empty($this->storage)) {
            throw new Exception('Empty config storage');
        }
        $storage = $this->getStorage();
        $storage->set($this->toArray());
        $storage->save();
        return $this;
    }

    public function get()
    {
        throw new \BadMethodCallException();
    }

    public function set($value)
    {
        throw new \BadMethodCallException();
    }

    protected function innerSet($key, $val)
    {
        if ('storage' === $key) {
            $this->__data['storage'] = $val;
        } else {
            parent::innerSet($key, $val);
        }
    }

    protected function innerGet($key)
    {
        if ('storage' === $key) {
            return $this->__data['storage'] ?? null;
        } else {
            return parent::innerGet($key);
        }
    }

}