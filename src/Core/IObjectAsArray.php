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
interface IObjectAsArray
    extends \ArrayAccess, \Countable, \Iterator, IArrayable, \Serializable, \JsonSerializable
{

    public function isEmpty(): bool;

}