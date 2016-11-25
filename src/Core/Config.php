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
    implements ICanStoreSelf
{

    /**
     * @var \Running\Core\ICanStoreSelf $__storage;
     */
    protected $__storage;

    /**
     * @param \Running\Core\ICanStoreSelf|iterable|null $arg
     * @throws \Running\Fs\Exception
     * @property $path string
     */
    public function __construct(/* ICanStoreSelf | iterable */$arg = null)
    {
        if ( (is_object($arg) && ($arg instanceof ICanStoreSelf)) ) {
            $this->setStorage($arg)->load();
        } else {
            parent::__construct($arg);
        }
    }

    /**
     * @param \Running\Core\ICanStoreSelf $storage
     * @return $this
     */
    public function setStorage(ICanStoreSelf $storage)
    {
        $this->__storage = $storage;
        return $this;
    }

    /**
     * @return \Running\Core\ICanStoreSelf|null
     */
    public function getStorage()/*: ICanStoreSelf? */
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

    protected function innerSet($key, $val)
    {
        if ('storage' == $key) {
            $this->__data['storage'] = $val;
        } else {
            parent::innerSet($key, $val);
        }
    }

    protected function innerGet($key)
    {
        if ('storage' == $key) {
            return $this->__data['storage'] ?? null;
        } else {
            return parent::innerGet($key);
        }
    }

    public function set($value)
    {
        throw new \BadMethodCallException();
    }

    public function isDeleted(): bool
    {
        return $this->__storage->isDeleted();
    }

    public function get()
    {
        throw new \BadMethodCallException();
    }

    public function isNew(): bool
    {
        return $this->__storage->isNew();
    }

    public function wasNew(): bool
    {
        return $this->__storage->wasNew();
    }

    public function isChanged(): bool
    {
        return $this->__storage->isChanged();
    }

    public function delete()
    {
        return $this->__storage->delete();
    }

}