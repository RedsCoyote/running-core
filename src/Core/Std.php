<?php

namespace Running\Core;

/**
 * Standard class
 *
 * Class Std
 * @package Running\Core
 */
class Std
    implements IObjectAsArray, IHasValidation, IHasSanitize
{
    use
        TStdGetSet;

    /**
     * Std constructor.
     * @param iterable|null $data
     */
    public function __construct(/* iterable */ $data = null)
    {
        if (null !== $data) {
            $this->fromArray($data);
        }
    }

    /**
     * Reload this method for validation and sanitizing
     * @param string $key
     * @param mixed $val
     */
    protected function innerSet($key, $val)
    {
        $setMethod = 'set' . ucfirst($key);
        if (method_exists($this, $setMethod)) {
            $this->$setMethod($val);
        } else {

            $validateMethod = 'validate' . ucfirst($key);
            if (method_exists($this, $validateMethod)) {
                $validateResult = $this->$validateMethod($val);
                if (false === $validateResult) {
                    return;
                }
            }

            $sanitizeMethod = 'sanitize' . ucfirst($key);
            if (method_exists($this, $sanitizeMethod)) {
                $val = $this->$sanitizeMethod($val);
            }

            if ('' == $key) {
                $this->__data[] = $val;
            } else {
                $this->__data[$key] = $val;
            }
        }
    }

    /**
     * @param \Running\Core\IArrayable|iterable $obj
     * @return \Running\Core\Std $this
     */
    public function merge(/* iterable */ $obj)
    {
        if ($obj instanceof IArrayable) {
            $obj = $obj->toArray();
        }
        foreach ($obj as $key => $value)
            $this->$key = $value;
        return $this;
    }

}