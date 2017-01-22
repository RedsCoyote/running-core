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
    implements StorageInterface
{

    /**
     * @var \Running\Core\StorageInterface $__storage;
     */
    protected $__storage;

    /**
     * @param \Running\Core\StorageInterface|iterable|null $arg
     * @throws \Running\Fs\Exception
     * @property $path string
     */
    public function __construct(/* StorageInterface | iterable */$arg = null)
    {
        if ( (is_object($arg) && ($arg instanceof StorageInterface)) ) {
            $this->setStorage($arg)->load();
        } else {
            parent::__construct($arg);
        }
    }

    /**
     * @param \Running\Core\StorageInterface $storage
     * @return $this
     */
    public function setStorage(StorageInterface $storage)
    {
        $this->__storage = $storage;
        return $this;
    }

    /**
     * @return \Running\Core\StorageInterface|null
     */
    public function getStorage()/*: ?StorageInterface */
    {
        return $this->__storage;
    }

    /**
     * Loads config from storage
     *
     * @return $this
     * @throws \Running\Core\Exception
     */
    public function load()
    {
        if (empty($this->__storage)) {
            throw new Exception('Wrong config storage!');
        }
        $this->__storage->load();
        return $this->fromArray($this->__storage->get());
    }

    /**
     * @return $this
     */
    public function reload()
    {
        return $this->load();
    }

    /**
     * @return $this
     * @throws \Running\Core\Exception
     */
    public function save()
    {
        if (empty($this->__storage)) {
            throw new Exception('Wrong config storage!');
        }
        $this->__storage->set($this->toArray());
        $this->__storage->save();
        return $this;
    }

    public function delete()
    {
        return $this->__storage->delete();
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

    public function set($value)
    {
        throw new \BadMethodCallException();
    }

    public function get()
    {
        throw new \BadMethodCallException();
    }

    public function isNew(): bool
    {
        return $this->__storage ? $this->__storage->isNew() : true;
    }

    public function wasNew(): bool
    {
        return $this->__storage ? $this->__storage->wasNew() : true;
    }

    public function isChanged(): bool
    {
        if (!empty($this->__storage && !empty($this->__data))) {
            return $this->__storage->get() != $this->toArray();
        } elseif (!empty($this->__storage)) {
            return $this->__storage->isChanged();
        }
        return false;
    }

    public function isDeleted(): bool
    {
        return $this->__storage ? $this->__storage->isDeleted() : false;
    }

}