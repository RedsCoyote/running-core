<?php

namespace Running\Core;

/**
 * Trait TCollection
 * @package Running\Core
 *
 * @implements \Running\Core\IObjectAsArray
 *
 * @implements \Running\Core\ICollection
 */
trait TCollection
    // implements ICollection
{
    use TObjectAsArray;

    /**
     * @param iterable $data
     * @return $this
     */
    public function fromArray(/* iterable */ $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $this->innerSet($key, (new static)->fromArray($value));
            } else {
                $this->innerSet($key, $value);
            }
        }
        return $this;
    }

    public function __construct(/* iterable */$data = null)
    {
        if (null !== $data) {
            $this->fromArray($data);
        }
    }

    /**
     * @param $value
     * @return $this
     */
    public function add($value)
    {
        return $this->append($value);
    }

    /**
     * @param $value
     * @return $this
     */
    public function append($value)
    {
        $this->__data = array_merge($this->__data, [$value]);
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function prepend($value)
    {
        $this->__data = array_merge([$value], $this->__data);
        return $this;
    }

    /**
     * @param \Running\Core\IArrayable|iterable $values
     * @return $this
     */
    public function merge(/* iterable */$values)
    {
        if ($values instanceof IArrayable) {
            $values = $values->toArray();
        }
        $vals = [];
        /** iterable values iteration (for \Traversable by example) */
        foreach ($values as $value) {
            $vals[] = $value;
        }
        $this->__data = array_merge($this->__data, $vals);
        return $this;
    }

    public function slice(int $offset, int $length = null)
    {
        return new static(array_slice($this->__data, $offset, $length));
    }

    public function first()
    {
        return $this->slice(0, 1)[0];
    }

    public function last()
    {
        return $this->slice(-1, 1)[0];
    }

    public function existsElement(array $attributes)
    {
        if (empty($attributes)) {
            return false;
        }
        foreach ($this as $element) {
            $elementAttributes = [];
            if (!is_array($element) && !(is_object($element) && $element instanceof \Traversable)) {
                continue;
            }
            foreach ($element as $key => $val) {
                if (array_key_exists($key, $attributes))
                    $elementAttributes[$key] = $val;
            }
            if ($attributes == $elementAttributes)
                return true;
        }
        return false;
    }

    public function findAllByAttributes(array $attributes)
    {
        return $this->filter(function ($x) use ($attributes) {
            if (!is_array($x) && !(is_object($x) && $x instanceof \Traversable)) {
                return false;
            }
            $elementAttributes = [];
            foreach ($x as $key => $value) {
                if (array_key_exists($key, $attributes)) {
                    $elementAttributes[$key] = $value;
                }
            }
            return $elementAttributes == $attributes;
        });
    }

    public function findByAttributes(array $attributes)
    {
        $all = $this->findAllByAttributes($attributes);
        return $all->isEmpty() ? null : $all[0];
    }

    /**
     * @return static
     */
    public function asort()
    {
        $copy = $this->toArray();
        asort($copy);
        return new static($copy);
    }

    /**
     * @return static
     */
    public function ksort()
    {
        $copy = $this->toArray();
        ksort($copy);
        return new static($copy);
    }

    /**
     * @param callable $callback
     * @return static
     */
    public function uasort(callable $callback) {
        $copy = $this->toArray();
        uasort($copy, $callback);
        return new static($copy);
    }

    /**
     * @param callable $callback
     * @return static
     */
    public function uksort(callable $callback) {
        $copy = $this->toArray();
        uksort($copy, $callback);
        return new static($copy);
    }

    /**
     * @return static
     */
    public function natsort() {
        $copy = $this->toArray();
        natsort($copy);
        return new static($copy);
    }

    /**
     * @return static
     */
    public function natcasesort() {
        $copy = $this->toArray();
        natcasesort($copy);
        return new static($copy);
    }

    /**
     * @param callable $callback
     * @return static
     */
    public function sort(callable $callback)
    {
        return $this->uasort($callback);
    }

    /**
     * @return static
     */
    public function reverse() {
        $reversed = array_reverse($this->toArray(), true);
        return new static($reversed);
    }


    /**
     * @param callable $callback
     * @return static
     */
    public function map(callable $callback)
    {
        return new static(array_values(array_map($callback, $this->toArray())));
    }

    /**
     * @param callable $callback
     * @return static
     */
    public function filter(callable $callback)
    {
        return new static(array_values(array_filter($this->toArray(), $callback)));
    }

    /**
     * @param mixed $start
     * @param callable $callback
     * @return mixed
     */
    public function reduce($start, callable $callback)
    {
        return array_reduce($this->toArray(), $callback, $start);
    }

    /**
     * @param mixed $what
     * @return array
     */
    public function collect($what)
    {
        $ret = [];
        foreach ($this as $element) {
            if ($what instanceof \Closure) {
                $ret[] = $what($element);
            } elseif (is_array($element) || ($element instanceof IObjectAsArray)) {
                $ret[] = $element[$what];
            } elseif (is_object($element)) {
                $ret[] = $element->$what;
            }
        }
        return $ret;
    }

    /**
     * @param $by
     * @return array|static[]
     */
    public function group($by)
    {
        $ret = [];
        foreach ($this as $element) {
            if ($by instanceof \Closure) {
                $key = $by($element);
            } elseif (is_array($element) || ($element instanceof IObjectAsArray)) {
                $key = $element[$by];
            } elseif (is_object($element)) {
                $key = $element->$by;
            }
            if (!isset($ret[$key])) {
                $ret[$key] = new static;
            }
            $ret[$key]->add($element);
        }
        return $ret;
    }

    public function __call(string $method, array $params = [])
    {
        foreach ($this as $element) {
            call_user_func_array([$element, $method], $params);
        }
    }

}