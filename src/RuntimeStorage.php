<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Aesonus\Storage;

/**
 * Storage for a single script
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class RuntimeStorage implements Contracts\StorageInterface
{
    protected $storage;
    
    public function count()
    {
        return count($this->storage);
    }

    public function get($offset)
    {
        return $this->storage[$offset];
    }

    public function has($offset)
    {
        return key_exists($offset, $this->storage) && $this->storage[$offset] !== NULL;
    }

    public function set($offset, $value)
    {
        return $this->storage[$offset] = $value;
    }
    
    public function append($value)
    {
        $this->storage[] = $value;
        return $this;
    }
}
