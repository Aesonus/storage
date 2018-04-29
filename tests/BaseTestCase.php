<?php
/*
 * Copyright 3/27/18 Cory Laughlin
 * This project may make use of open source software. This code is governed by its own license.
 * All other code is copyright. All rights reserved. May not be reproduced with express written consent of Cory Laughlin.
 */

namespace Aesonus\Tests;

/**
 * Description of TestCase
 *
 * @author Aesonus <corylcomposinger at gmail.com>
 */
abstract class BaseTestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }
    
    /**
     * Get protected and private properties of an object
     * @param \StdClass $object
     * @param string $propertyName
     * @return mixed
     */
    public function getPropertyValue(&$object, $propertyName)
    {
        $reflection = new \ReflectionProperty(get_class($object), $propertyName);
        $reflection->setAccessible(true);
        return $reflection->getValue($object);
    }
}
