<?php
/*
 * This code is part of the aesonus/storage package.
 * This software is licensed under the MIT License. Please see LICENSE for more details.
 */

namespace Aesonus\Tests;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\visitor\vfsStreamStructureVisitor;

/**
 * Tests the file storage
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
class FileStorageTest extends \Aesonus\TestLib\BaseTestCase
{
    protected $storageMockBuilder;
    protected $storage;
    protected $testFilename;
    
    protected function setUp()
    {
        $this->storageMockBuilder = $this->getMockBuilder(\Aesonus\Storage\FileStorage::class);
        $this->testFilename = vfsStream::setup()->url() . '/cache';
        parent::setUp();
    }
    
    public function testConstructor()
    {
        $storage = $this->storageMockBuilder->
            disableOriginalConstructor()->
            setMethods(['filename', 'readFile'])
            ->getMock();
        $storage->expects($this->once())->method('filename')
            ->with($this->testFilename);
        $storage->expects($this->once())->method('readFile')
            ->with();
        $this->invokeConstructor($storage, [$this->testFilename]);
    }
    
    public function testConstructorWithNoCache()
    {
        $storage = $this->storageMockBuilder->
            disableOriginalConstructor()->
            setMethods(['filename', 'readFile'])
            ->getMock();
        $storage->expects($this->once())->method('filename')
            ->with($this->testFilename);
        $storage->expects($this->never())->method('readFile')
            ->with();
        $this->invokeConstructor($storage, [$this->testFilename, FALSE]);
    }
    
    private function getStorageWithFilenameMethod()
    {
        $storage = $this->storageMockBuilder
            ->disableOriginalConstructor()
            ->setMethodsExcept(['filename'])
            ->getMock();
        return $storage;
    }
    
    public function testFilenameSetter()
    {
        $storage = $this->getStorageWithFilenameMethod();
        $storage->filename($this->testFilename);
        $this->assertEquals($this->testFilename, $this->getPropertyValue($storage, 'filename'));
    
        return $storage;
    }
    
    /**
     * @depends testFilenameSetter
     */
    public function testFilenameGetter($storage)
    {
        $this->assertEquals($this->testFilename, $storage->filename());
    }
    
    public function testFilenameDefault()
    {
        $storage = $this->getStorageWithFilenameMethod();
        $this->assertEquals('cache', $storage->filename());
    }
    
    
    public function testUnwritableFile()
    {
        vfsStream::newFile('cache', 0000)->at(vfsStream::setup());
        $storage = $this->getStorageWithFilenameMethod();
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("must be writable");
        $storage->filename($this->testFilename);
    }
    
    private function getStorageWithAllMethods()
    {
        return new \Aesonus\Storage\FileStorage($this->testFilename);
    }
    
    /**
     * @dataProvider readWriteFileDataProvider
     */
    public function testWriteFile($testData)
    {
        $storage = $this->getStorageWithAllMethods();
        $this->setPropertyValue($storage, 'storage', $testData);
        $this->invokeMethod($storage, 'writeFile');
        $this->assertEquals(serialize($testData), file_get_contents($storage->filename()));
    }
    
    /**
     * @dataProvider readWriteFileDataProvider
     */
    public function testReadFile($testData)
    {
        file_put_contents($this->testFilename, serialize($testData));
        $storage = $this->getStorageWithAllMethods();
        $this->invokeMethod($storage, 'readFile');
        $this->assertEquals($testData, $this->getPropertyValue($storage, 'storage'));
    }
    
    public function readWriteFileDataProvider()
    {
        return [
            [[5]],
            [["this" => "string", 4 => new \stdClass()]],
            [[[],6,"string"]]
        ];
    }
    
    public function testReadEmptyFile()
    {
        $storage = $this->getStorageWithAllMethods();
        $this->invokeMethod($storage, 'readFile');
        $this->assertEquals([], $this->getPropertyValue($storage, 'storage'));
    }
    
    public function testSet()
    {
        $storage = $this->getStorageWithAllMethods();
        $storage->set(0, 'test');
        $this->assertEquals('test', $this->getPropertyValue($storage, 'storage')[0]);
        $this->assertEquals(['test'], unserialize(file_get_contents($storage->filename())));
        return $storage;
    }
    
    /**
     * @depends testSet
     */
    public function testUnset($storage)
    {
        $storage->offsetUnset(0);
        $this->assertEmpty($this->getPropertyValue($storage, 'storage'));
        $this->assertEquals([], unserialize(file_get_contents($storage->filename())));
    }
    
    public function testAppend()
    {
        $storage = $this->getStorageWithAllMethods();
        $storage->append('test');
        $this->assertEquals('test', $this->getPropertyValue($storage, 'storage')[0]);
        $this->assertEquals(['test'], unserialize(file_get_contents($storage->filename())));
    }
    
    private function getStorageForGettingAndCountingNoCache()
    {
        $storage = $this->storageMockBuilder
            ->setConstructorArgs([$this->testFilename, FALSE])
            ->setMethods(['readFile'])
            ->getMock();
        return $storage;
    }
    
    public function testCountWithNoCache()
    {
        $storage = $this->getStorageForGettingAndCountingNoCache();
        $storage->set(0,"test");
        $storage->expects($this->exactly(1))->method('readFile');
        $this->assertEquals(1, $storage->count());
    }
    
    public function testGetWithNoCache()
    {
        $storage = $this->getStorageForGettingAndCountingNoCache();
        $storage->set(0,"test");
        $storage->expects($this->exactly(1))->method('readFile');
        $this->assertEquals("test", $storage->get(0));
    }
    
    public function testHasWithNoCache()
    {
        $storage = $this->getStorageForGettingAndCountingNoCache();
        $storage->set(0,"test");
        $storage->expects($this->exactly(1))->method('readFile');
        $this->assertEquals(true, $storage->has(0));
    }
    
    private function getStorageForGettingAndCountingWithCache()
    {
        $storage = $this->storageMockBuilder
            ->setConstructorArgs([$this->testFilename])
            ->setMethods(['readFile'])
            ->getMock();
        return $storage;
    }
    
    public function testCountWithCache()
    {
        $storage = $this->getStorageForGettingAndCountingWithCache();
        $storage->set(0,"test");
        $storage->expects($this->exactly(0))->method('readFile');
        $this->assertEquals(1, $storage->count());
    }
    
    public function testGetWithCache()
    {
        $storage = $this->getStorageForGettingAndCountingWithCache();
        $storage->set(0,"test");
        $storage->expects($this->exactly(0))->method('readFile');
        $this->assertEquals("test", $storage->get(0));
    }
    
    public function testHasWithCache()
    {
        $storage = $this->getStorageForGettingAndCountingWithCache();
        $storage->set(0,"test");
        $storage->expects($this->exactly(0))->method('readFile');
        $this->assertEquals(true, $storage->has(0));
    }
    
}
