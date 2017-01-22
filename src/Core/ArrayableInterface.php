<?php

namespace Running\Core;

/**
 * Interface for objects which can be casted from array and be casted to array
 *
 * Interface ArrayableInterface
 * @package Running\Core
 *
 * @codeCoverageIgnore
 */
interface ArrayableInterface
{

    public function fromArray(/* iterable */ $data);

    public function toArray(): array;

    public function toArrayRecursive(): array;

}