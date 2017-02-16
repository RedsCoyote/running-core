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
    implements CollectionInterface
{

    /*protected */const TYPE = \Exception::class;

    use CollectionTrait {
        __construct as protected collectionConstruct;
        append as protected collectionAppend;
        prepend as protected collectionPrepend;
        innerSet as protected collectionInnerSet;
    }

    public function __construct(/* iterable */$data = null)
    {
        $type = static::TYPE;
        if (!is_subclass_of($type, \Throwable::class)) {
            throw new Exception('MultiException invalid base class');
        }
        $this->collectionConstruct($data);
    }

    public function append($value)
    {
        $type = static::TYPE;
        if (!($value instanceof $type)) {
            throw new Exception('MultiException class mismatch');
        }
        return $this->collectionAppend($value);
    }

    public function prepend($value)
    {
        $type = static::TYPE;
        if (!($value instanceof $type)) {
            throw new Exception('MultiException class mismatch');
        }
        return $this->collectionPrepend($value);
    }

    public function innerSet($key, $value)
    {
        $type = static::TYPE;
        if (!($value instanceof $type)) {
            throw new Exception('MultiException class mismatch');
        }
        $this->collectionInnerSet($key, $value);
    }

}