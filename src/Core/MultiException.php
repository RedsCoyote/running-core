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

    use TypedCollectionTrait {
        add as protected collectionAdd;
    }

    /**
     * @param $value
     * @return $this
     */
    public function add($value)
    {
        if ($value instanceof self) {
            foreach ($value as $v) {
                $this->collectionAdd($v);
            }
        } else {
            $this->collectionAdd($value);
        }
        return $this;
    }

    public static function getType()
    {
        return \Throwable::class;
    }

}