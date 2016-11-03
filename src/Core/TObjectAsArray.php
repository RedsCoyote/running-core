<?php

namespace Running\Core;

/**
 * Trait TObjectAsArray
 * @package Running\Core
 *
 * @implements \Running\Core\IObjectAsArray
 * @implements \ArrayAccess
 * @implements \Countable
 * @implements \Iterator
 * @implements \Running\Core\IArrayable
 */
trait TObjectAsArray
 // implements IObjectAsArray
{

    /** @var array $__data */
    protected $__data = [];

    public function getData()
    {
        $ret = [];
        foreach (array_keys($this->__data) as $key) {
            $ret[$key] = $this->innerGet($key);
        }
        return $ret;
    }

    protected function innerIsSet($key)
    {
        return array_key_exists($key, $this->__data) || method_exists($this, 'get' . ucfirst($key));
    }

    protected function innerUnSet($key)
    {
        unset($this->__data[$key]);
    }

    protected function innerGet($key)
    {
        $method = 'get' . ucfirst($key);
        if (method_exists($this, $method) && 'data' != $key) {
            return $this->$method();
        }
        return isset($this->__data[$key]) ? $this->__data[$key] : null;
    }

    protected function innerSet($key, $val)
    {
        $setMethod = 'set' . ucfirst($key);
        if (method_exists($this, $setMethod)) {
            $this->$setMethod($val);
        } else {
            if ('' == $key) {
                $this->__data[] = $val;
            } else {
                $this->__data[$key] = $val;
            }
        }
    }

    /**
     * \ArrayAccess implementation
     */
    public function offsetExists($offset)
    {
        return $this->innerIsSet($offset);
    }

    public function offsetUnset($offset)
    {
        $this->innerUnSet($offset);
    }

    public function offsetGet($offset)
    {
        return $this->innerGet($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->innerSet($offset, $value);
    }

    /**
     * \Countable implementation
     */
    public function count()
    {
        return count($this->__data);
    }

    /**
     * \Iterator implementation
     */
    public function current()
    {
        return $this->innerGet(key($this->__data));
    }

    public function next()
    {
        next($this->__data);
    }

    public function key()
    {
        return key($this->__data);
    }

    public function valid()
    {
        return null !== key($this->__data);
    }

    public function rewind()
    {
        reset($this->__data);
    }

    /**
     * \Running\Core\IArrayable implementation
     */

    /**
     * @param iterable $data
     * @return $this
     */
    public function fromArray(/* iterable */ $data)
    {
        foreach ($data as $key => $value) {
            if (is_null($value) || is_scalar($value) || $value instanceof \Closure) {
                $this->innerSet($key, $value);
            } else {
                $this->innerSet($key, (new static)->fromArray($value));
            }
        }
        return $this;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        $data = [];
        foreach (array_keys($this->__data) as $key) {
            $value = $this->innerGet($key);
            if ($value instanceof self) {
                $data[$key] = $value->toArray();
            } else {
                $data[$key] = $value;
            }
        }
        return $data;
    }
    /**
     * @return array
     */
    public function toArrayRecursive() : array
    {
        $data = [];
        foreach (array_keys($this->__data) as $key) {
            $value = $this->innerGet($key);
            if ($value instanceof IArrayable) {
                $data[$key] = $value->toArrayRecursive();
            } else {
                $data[$key] = $value;
            }
        }
        return $data;
    }

}