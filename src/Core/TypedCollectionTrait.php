<?php

namespace Running\Core;

/**
 * Class TypedCollectionTrait
 * @package Running\Core
 *
 * @implements \Running\Core\TypedCollectionInterface
 */
trait TypedCollectionTrait
    /*implements TypedCollectionInterface*/
{

    /**
     * @codeCoverageIgnore
     */
    public static function getType()
    {
        return null;
    }

    use CollectionTrait {
        append as protected collectionAppend;
        prepend as protected collectionPrepend;
        innerSet as protected collectionInnerSet;
    }

    public function append($value)
    {
        $type = static::getType();
        if (!empty($type) && !($value instanceof $type)) {
            throw new Exception('Typed collection class mismatch');
        }
        return $this->collectionAppend($value);
    }

    public function prepend($value)
    {
        $type = static::getType();
        if (!empty($type) && !($value instanceof $type)) {
            throw new Exception('Typed collection class mismatch');
        }
        return $this->collectionPrepend($value);
    }

    public function innerSet($key, $value)
    {
        $type = static::getType();
        if (!empty($type) && !($value instanceof $type)) {
            throw new Exception('Typed collection class mismatch');
        }
        $this->collectionInnerSet($key, $value);
    }

}