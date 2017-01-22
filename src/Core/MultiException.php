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

    protected $class = \Exception::class;

    use TCollection {
        append as protected collectionAppend;
        prepend as protected collectionPrepend;
    }

    public function __construct($class = \Exception::class)
    {
        if ( !is_a($class, \Throwable::class, true) ) {
            throw new Exception('Invalid MultiException base class');
        }
        $this->class = $class;
    }

    public function append($value)
    {
        if (!($value instanceof $this->class)) {
            throw new Exception('MultiException class mismatch');
        }
        return $this->collectionAppend($value);
    }

    public function prepend($value)
    {
        if (!($value instanceof $this->class)) {
            throw new Exception('MultiException class mismatch');
        }
        return $this->collectionPrepend($value);
    }

}