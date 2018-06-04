<?php
/*
 * This code is part of the aesonus/storage package.
 * This software is licensed under the MIT License. Please see LICENSE for more details.
 */

namespace Aesonus\Storage;

/**
 * Allows for the storage array to be serialized. Note that anything put into
 * storage must be serializable. This also stores a runtime cache to prevent
 * multiple calls to the filesystem. You may also disable this in the constuctor.
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class FileStorage extends RuntimeStorage
{
    protected $runtime_cache;
    protected $filename;
    
    public function __construct($filename = null, $runtime_cache = true)
    {
        $this->runtime_cache = $runtime_cache;
        $this->filename($filename);
        //Load in the file
        if ($runtime_cache) {
            $this->readFile();
        }
    }
    
    /**
     * Getter, setter, and default cache file this class uses.
     * Defaults to the current directory and cache
     * @param string $filename
     * @return $this
     */
    public function filename($filename = NULL)
    {
        if (isset($filename)) {
            try {
                //Make sure that the file is good to use
                $file = new \SplFileObject($filename, 'c');
            } catch (\RuntimeException $ex) {
                throw new \RuntimeException(
                        sprintf("File at %s must be writable",
                            $filename));
            }
            $this->filename = $filename;
            return $this;
        } elseif (!isset($this->filename)) {
            $this->filename("cache");
        }
        return $this->filename;
    }
    
    // SETTING AND UNSETTING
    
    public function set($offset, $value)
    {
        parent::set($offset, $value);
        $this->writeFile();
        return $this;
    }
    
    public function offsetUnset($offset)
    {
        parent::offsetUnset($offset);
        $this->writeFile();
        return $this;
    }
    
    public function append($value)
    {
        parent::append($value);
        $this->writeFile();
        return $this;
    }
    
    // GETTING AND COUNTING
    
    public function count()
    {
        if (!$this->runtime_cache) {
            $this->readFile();
        }
        return parent::count();
    }
    
    public function get($offset, $default = null)
    {
        //Since the parent function calls has, this step
        //calls readFile if the runtime cache is turned off
        //so we don't need to in this method
        return parent::get($offset, $default);
    }
    
    public function has($offset)
    {
        if (!$this->runtime_cache) {
            $this->readFile();
        }
        return parent::has($offset);
    }
    
    public function all()
    {
        if (!$this->runtime_cache) {
            $this->readFile();
        }
        return parent::all();
    }
    
    //File operations
    
    protected function writeFile()
    {
        file_put_contents($this->filename(), serialize($this->storage));
    }
    
    protected function readFile()
    {
        if (($new_storage = unserialize(file_get_contents($this->filename())))) {
            $this->storage = $new_storage;
        } else {
            $this->storage = [];
        }
    }
}
