<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
