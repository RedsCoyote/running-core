<?php

namespace Running\Core;

/**
 * Config class
 *
 * Class Config
 * @package Running\Core
 */
class Config
    extends Std
    implements SingleStorageInterface
{

    /**
     * @var \Running\Core\SingleStorageInterface $storage;
     */
    protected $storage;

    /**
     * @param \Running\Core\SingleStorageInterface|iterable|null $arg
     * @throws \Running\Fs\Exception
     * @property $path string
     */
    public function __construct(/* StorageInterface | iterable */$arg = null)
    {
        if ( (is_object($arg) && ($arg instanceof SingleStorageInterface)) ) {
            $this->setStorage($arg)->load();
        } else {
            parent::__construct($arg);
        }
    }

    /**
     * @param \Running\Core\SingleStorageInterface $storage
     * @return $this
     */
    public function setStorage(SingleStorageInterface $storage)
    {
        $this->storage = $storage;
        return $this;
    }

    /**
     * @return \Running\Core\SingleStorageInterface|null
     */
    public function getStorage()/*: ?StorageInterface */
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
        $storage = $this->getStorage();
        if (empty($this->getStorage())) {
            throw new Exception('Empty config storage');
        }
        $storage->load();
        return $this->fromArray($storage->get());
    }

    /**
     * @return $this
     * @throws \Running\Core\Exception
     */
    public function save()
    {
        $storage = $this->getStorage();
        if (empty($storage)) {
            throw new Exception('Empty config storage');
        }
        $storage->set($this->toArray());
        $storage->save();
        return $this;
    }

    public function get()
    {
        throw new \BadMethodCallException();
    }

    public function set($contents)
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