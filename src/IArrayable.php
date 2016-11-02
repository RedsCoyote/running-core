<?php

namespace Running\Core;

/**
 * Interface for objects which can be casted from array and be casted to array
 *
 * Interface IArrayable
 * @package Running\Core
 */
interface IArrayable
{

    public function fromArray(/* iterable */ $data);

    public function toArray() : array;

    public function toArrayRecursive() : array;

}