<?php

namespace Running\Core;

/**
 * Full object-as-array access interface
 *
 * Interface IArrayAccess
 * @package Running\Core
 *
 * @codeCoverageIgnore
 */
interface ObjectAsArrayInterface
    extends \ArrayAccess, \Countable, \Iterator, ArrayableInterface, \Serializable, \JsonSerializable
{

    public function isEmpty(): bool;

}