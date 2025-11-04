<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\test\php\model;

use org\turbocommons\src\test\resources\model\baseDependantClass\DependantClass2;
use org\turbocommons\src\test\resources\model\baseDependantClass\DependantClass;
use PHPUnit\Framework\TestCase;
use Throwable;


/**
 * BaseDependantClass tests
 *
 * @return void
 */
class BaseDependantClassTest extends TestCase {


    /**
     * testSetDependencies
     *
     * @return void
     */
    public function testSetDependencies(){

        // Create a dependant class
        $dependantClass = new DependantClass();

        // test setting wrong values cause exceptions
        $exceptionMessage = '';

        try {
            $dependantClass->setDependencies(null);
            $exceptionMessage = 'null did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $dependantClass->setDependencies([]);
            $exceptionMessage = '[] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $dependantClass->setDependencies(['']);
            $exceptionMessage = '"" did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $dependantClass->setDependencies(['a', 'b', 'c']);
            $exceptionMessage = 'array did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $dependantClass->setDependencies(['ertert']);
            $exceptionMessage = 'string ertert did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        //Test setting values to the dependencies
        $dependantClass->setDependencies(['_property1' => new DependantClass()]);
        $dependantClass->setDependencies(['_property1' => new DependantClass()]);
        $dependantClass->setDependencies(['_property2' => new DependantClass2()]);

        // Test changing dependency type throws exception
        try {
            $dependantClass->setDependencies(['_property1' => null]);
            $exceptionMessage = 'null did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $dependantClass->setDependencies(['_property1' => 235]);
            $exceptionMessage = '235 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $dependantClass->setDependencies(['_property1' => 'string']);
            $exceptionMessage = 'string did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $dependantClass->setDependencies(['_property1' => new DependantClass2()]);
            $exceptionMessage = 'DependantClass2 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test non existing property throw exception
        try {
            $dependantClass->setDependencies(['' => null]);
            $exceptionMessage = '"" property did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $dependantClass->setDependencies(['test1' => null]);
            $exceptionMessage = 'test1 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test setting a public property as dependency throws exception
        try {
            $dependantClass->setDependencies(['publicProp' => 123]);
            $exceptionMessage = 'publicProp did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        if($exceptionMessage != ''){

            $this->fail($exceptionMessage);
        }

        // Set multiple dependencies on a single call
        $dependantClass->setDependencies(['_property1' => new DependantClass(), '_property2' => new DependantClass2()]);

        $this->markTestIncomplete('This test has not been implemented yet.');
    }
}

?>