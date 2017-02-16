<?php

namespace Running\Core;

/**
 * MultiException base class
 *
 * Class MultiException
 * @package Running\Core
 */
class MultiException
    extends \Exception
    implements TypedCollectionInterface
{

    use TypedCollectionTrait;

    public static function getType()
    {
        return \Throwable::class;
    }

}