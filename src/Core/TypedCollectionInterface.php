<?php

namespace Running\Core;

/**
 * Interface for typed collections
 *
 * Interface TypedCollectionInterface
 * @package Running\Core
 */
interface TypedCollectionInterface
    extends CollectionInterface
{
    public static function getType();
}