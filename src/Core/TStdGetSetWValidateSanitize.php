<?php

namespace Running\Core;

/**
 * Class TStdGetSetWValidateSanitize
 * @package Running\Core
 *
 * @implements \ArrayAccess
 * @implements \Countable
 * @implements \Iterator
 * @implements \Running\Core\IArrayable
 * @implements \Serializable
 * @implements \JsonSerializable
 * @implements \Running\Core\IObjectAsArray
 * @implements \Running\Core\IHasMagicGetSet
 *
 * @implements \Running\Core\IHasValidation
 * @implements \Running\Core\IHasSanitize
 */
trait TStdGetSetWValidateSanitize
    // implements IHasValidation, IHasSanitize
{

    use TStdGetSet;

    /**
     * Reload this method for validation and sanitizing
     * @param string $key
     * @param mixed $val
     * @throws \Running\Core\MultiException
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
                if ($validateResult instanceof \Generator) {
                    $errors = new MultiException();
                    foreach ($validateResult as $error) {
                        if ($error instanceof \Throwable) {
                            $errors[] = $error;
                        }
                    }
                    if (!$errors->isEmpty()) {
                        throw $errors;
                    }
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

}