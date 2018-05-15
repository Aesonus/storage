<?php
/*
 * This software is licensed under the MIT License. Please see LICENSE for more details.
 */

namespace Aesonus\Storage\Contracts;

/**
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
interface StorageInterface extends \Countable
{
    public function get($offset);
    
    public function set($offset, $value);
    
    public function has($offset);
    
    public function append($value);
}
