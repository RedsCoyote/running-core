<?php

namespace Running\Core;

use Running\Fs\File;

/**
 * File-based config
 *
 * Class Config
 * @package Running\Core
 */
class Config
    extends Std
{

    /**
     * @var \Running\Fs\File $__file;
     */
    protected $__file;

    /**
     * @param \Running\Fs\File|iterable|null $arg
     * @throws \Running\Fs\Exception
     * @property $path string
     */
    public function __construct(/* File | iterable */$arg = null)
    {
        if ( (is_object($arg) && ($arg instanceof File)) ) {
            $this->load($arg);
        } else {
            parent::__construct($arg);
        }
    }

    /**
     * Loads config from file
     *
     * @param \Running\Fs\File $arg
     * @return $this
     * @throws \Running\Core\Exception
     * @throws \Running\Fs\Exception
     */
    public function load(File $arg)
    {
        if (is_object($arg) && ($arg instanceof File)) {
            $this->__file = $arg;
        } else {
            throw new Exception('Wrong config file!');
        }
        return $this->fromArray($this->__file->return());
    }

}