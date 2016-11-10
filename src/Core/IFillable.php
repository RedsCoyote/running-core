<?php

namespace Running\Core;

/**
 * Objects that can fill itself by iterable data
 *
 * Interface IFillable
 * @package Running\Core
 */
interface IFillable
{

    /**
     * @param \Running\Core\IArrayable|iterable $data
     * @return $this
     * @throws \Running\Core\MultiException
     */
    public function fill(/* iterable */ $data);

}