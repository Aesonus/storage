<?php
/*
 * This code is part of the aesonus/storage package.
 * This software is licensed under the MIT License. Please see LICENSE for more details.
 */

namespace Aesonus\Storage;

/**
 * Storage for a single script
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class RuntimeStorage implements Contracts\StorageInterface
{

    protected $storage = [];

    public function count()
    {
        return count($this->storage);
    }

    public function get($offset, $default = null)
    {
        if (!$this->has($offset)) {
            return $default;
        }
        return $this->storage[$offset];
    }

    public function has($offset)
    {
        return key_exists($offset, $this->storage) && $this->storage[$offset] !== NULL;
    }

    public function set($offset, $value)
    {
        $this->storage[$offset] = $value;
        return $this;
    }

    public function append($value)
    {
        $this->storage[] = $value;
        return $this;
    }

    public function offsetUnset($offset)
    {
        unset($this->storage[$offset]);
        return $this;
    }
}
