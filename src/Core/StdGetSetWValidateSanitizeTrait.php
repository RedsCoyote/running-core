<?php

namespace Running\Core;

/**
 * Class StdGetSetWValidateSanitizeTrait
 * @package Running\Core
 *
 * @implements \ArrayAccess
 * @implements \Countable
 * @implements \Iterator
 * @implements \Running\Core\ArrayableInterface
 * @implements \Serializable
 * @implements \JsonSerializable
 * @implements \Running\Core\ObjectAsArrayInterface
 * @implements \Running\Core\HasMagicGetSetInterface
 *
 * @implements \Running\Core\HasValidationInterface
 * @implements \Running\Core\HasSanitizingInterface
 */
trait StdGetSetWValidateSanitizeTrait
    // implements HasValidationInterface, HasSanitizingInterface
{

    use StdGetSetTrait;

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
                            $errors->add($error);
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