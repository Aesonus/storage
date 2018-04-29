<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Aesonus\Tests;

/**
 * Description of RuntimeStorageTest
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class RuntimeStorageTest extends BaseTestCase
{
    protected $storage;
    
    protected function setUp()
    {
        $this->storage = new \Aesonus\Storage\RuntimeStorage();
    }
    
    /**
     * @dataProvider setDataProvider
     **/
    public function testSet($offset, $value)
    {
        $this->storage->set($offset, $value);
        $this->assertEquals([$offset => $value], 
            $this->getPropertyValue($this->storage, 'storage'));
        return $this->storage;
    }

    public function setDataProvider()
    {
        return [
            ['string', 'teststring'],
            [0, 'testvalue'],
            [8, [3,5,6]]
        ];
    }
    
    public function testAppend()
    {
        $value1 = 'hi';
        $value2 = 'there';
        $expected = [$value1, $value2];
        
        $this->storage->append($value1);
        $this->storage->append($value2);
        $this->assertEquals($expected, $this->getPropertyValue($this->storage, 'storage'));
        return $this->storage;
    }
    
    /**
     * 
     * @depends testAppend
     */
    public function testHas(\Aesonus\Storage\RuntimeStorage $storage)
    {
        $this->assertTrue($storage->has(0));
        $this->assertTrue($storage->has(1));
        return $storage;
    }
    
    /**
     * 
     * @depends testHas
     */
    public function testGet(\Aesonus\Storage\RuntimeStorage $storage)
    {
        $this->assertEquals('hi', $storage->get(0));
        $this->assertEquals('there', $storage->get(1));
    }
    
    /**
     * 
     * @depends testAppend
     */
    public function testCount(\Aesonus\Storage\RuntimeStorage $storage)
    {
        $this->assertEquals(2, $storage->count());
    }
}
