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
    implements ICanStoreSelf
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
            $this->setFile($arg)->load();
        } else {
            parent::__construct($arg);
        }
    }

    /**
     * @param \Running\Fs\File $file
     * @return $this
     */
    public function setFile(File $file)
    {
        $this->__file = $file;
        return $this;
    }

    /**
     * @return \Running\Fs\File|null
     */
    public function getFile()/*: File? */
    {
        return $this->__file;
    }

    /**
     * Loads config from file
     *
     * @return $this
     * @throws \Running\Core\Exception
     * @throws \Running\Fs\Exception
     */
    public function load()
    {
        if (empty($this->__file)) {
            throw new Exception('Wrong config file!');
        }
        return $this->fromArray($this->__file->return());
    }

    /**
     * @return $this
     */
    public function reload()
    {
        return $this->load();
    }

    /**
     * @return $this
     */
    public function save()
    {
        $str = preg_replace(['~^(\s*)array\s*\($~im', '~^(\s*)\)(\,?)$~im', '~\s+$~im'], ['$1[', '$1]$2', ''], var_export($this->toArray(), true));
        $this->__file
            ->setContents('<?php' . PHP_EOL . PHP_EOL . 'return ' . $str . ';')
            ->save();
        return $this;
    }

    protected function innerSet($key, $val)
    {
        if ('file' == $key) {
            $this->__data['file'] = $val;
        } else {
            parent::innerSet($key, $val);
        }
    }

    protected function innerGet($key)
    {
        if ('file' == $key) {
            return $this->__data['file'] ?? null;
        } else {
            return parent::innerGet($key);
        }
    }

}