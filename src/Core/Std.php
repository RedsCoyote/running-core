<?php

namespace Running\Core;

/**
 * Standard class
 *
 * Class Std
 * @package Running\Core
 */
class Std
    implements ObjectAsArrayInterface, HasMagicGetSetInterface, HasValidationInterface, HasSanitizingInterface, FillableInterface
{
    use
        TStdGetSetWValidateSanitize;

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
     * @param \Running\Core\ArrayableInterface|iterable $obj
     * @return $this
     */
    public function merge(/* ArrayableInterface|iterable */ $obj)
    {
        if ($obj instanceof ArrayableInterface) {
            $obj = $obj->toArray();
        }
        foreach ($obj as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }

    /**
     * @param \Running\Core\ArrayableInterface|iterable $data
     * @return $this
     * @throws \Running\Core\MultiException
     */
    public function fill(/* ArrayableInterface|iterable */$data)
    {
        if ($data instanceof ArrayableInterface) {
            $data = $data->toArray();
        }

        $errors = new MultiException();

        foreach ($data as $key => $value) {
            try {
                $this->$key = $value;
            } catch (\Throwable $e) {
                if ($e instanceof MultiException) {
                    $errors->merge($e);
                } else {
                    $errors->add($e);
                }
            }
        }

        if (!$errors->isEmpty()) {
            throw $errors;
        }

        return $this;
    }

}