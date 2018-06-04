<?php
/*
 * This code is part of the aesonus/storage package.
 * This software is licensed under the MIT License. Please see LICENSE for more details.
 */

namespace Aesonus\Storage\Contracts;

/**
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface StorageInterface extends \Countable
{
    /**
     * MUST return a value from storage at $offset
     * @param string $offset MUST be string
     * @param mixed $default MUST be returned if no offset exists
     */
    public function get($offset, $default = NULL);
    
    public function set($offset, $value);
    
    public function offsetUnset($offset);
    
    public function has($offset);
    
    public function append($value);
    
    public function all();
}
