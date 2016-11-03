<?php

namespace Running\Core;

/**
 * Object-as-collection interface
 *
 * Interface ICollection
 * @package Running\Core
 */
interface ICollection
    extends IObjectAsArray
{

    public function __construct(/* iterable */$data = null);

    public function add($value);
    public function prepend($value);
    public function append($value);
    public function merge(/* iterable */ $values);

    public function slice(int $offset, int $length = null);
    public function first();
    public function last();

    public function existsElement(array /* iterable */ $attributes);
    public function findAllByAttributes(array /* iterable */ $attributes);
    public function findByAttributes(array /* iterable */ $attributes);

    public function asort();
    public function ksort();
    public function uasort(callable $callback);
    public function uksort(callable $callback);
    public function natsort();
    public function natcasesort();
    public function sort(callable $callback);

    public function map(callable $callback);
    public function filter(callable $callback);
    public function reduce($start, callable $callback);

    public function collect($what);
    public function group($by);

    public function __call(string $method, array $params = []);

}