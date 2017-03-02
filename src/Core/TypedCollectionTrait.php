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

    use CollectionTrait {
        append as protected collectionAppend;
        prepend as protected collectionPrepend;
        innerSet as protected collectionInnerSet;
    }

    protected function isValidContent($value):bool
    {
        $type = static::getType();
        $typeChecker = 'is_' . $type;
        switch (gettype($value)) {
            case 'object':
                return ($value instanceof $type);
            case 'boolean':
                if (!strncmp('bool', $type, 4)) {
                    return true;
                }
                break;
            default:
                if (function_exists($typeChecker)) {
                    return $typeChecker($value);
                }
        }
        return false;
    }

    public function append($value)
    {
        if (!$this->isValidContent($value)) {
            throw new Exception('Typed collection class mismatch');
        }
        return $this->collectionAppend($value);
    }

    public function prepend($value)
    {
        if (!$this->isValidContent($value)) {
            throw new Exception('Typed collection class mismatch');
        }
        return $this->collectionPrepend($value);
    }

    public function innerSet($key, $value)
    {
        if (!$this->isValidContent($value)) {
            throw new Exception('Typed collection class mismatch');
        }
        $this->collectionInnerSet($key, $value);
    }
}
