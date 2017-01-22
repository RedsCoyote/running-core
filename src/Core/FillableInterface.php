<?php

namespace Running\Core;

/**
 * Objects that can fill itself by iterable data
 *
 * Interface FillableInterface
 * @package Running\Core
 */
interface FillableInterface
{

    /**
     * @param \Running\Core\ArrayableInterface|iterable $data
     * @return $this
     * @throws \Running\Core\MultiException
     */
    public function fill(/* iterable */ $data);

}