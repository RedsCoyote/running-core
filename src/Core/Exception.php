<?php

namespace Running\Core;

/**
 * Base Exception class
 *
 * Class Exception
 * @package Running\Core
 *
 * @codeCoverageIgnore
 */
class Exception
    extends \Exception
    implements \JsonSerializable
{

    function jsonSerialize()
    {
        return ['code' => $this->getCode(), 'message' => $this->getMessage()];
    }

}