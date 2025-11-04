<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\test\php\utils;

use Exception;
use Throwable;
use stdClass;
use org\turbocommons\src\main\php\utils\ArrayUtils;
use org\turbotesting\src\main\php\utils\AssertUtils;
use PHPUnit\Framework\TestCase;


/**
 * ArrayUtils tests
 *
 * @return void
 */
class ArrayUtilsTest extends TestCase {


    /**
     * @see TestCase::setUpBeforeClass()
     *
     * @return void
     */
    public static function setUpBeforeClass(){

        // Nothing necessary here
    }


    /**
     * @see TestCase::setUp()
     *
     * @return void
     */
    protected function setUp(){

        $this->exceptionMessage = '';
    }


    /**
     * @see TestCase::tearDown()
     *
     * @return void
     */
    protected function tearDown(){

        if($this->exceptionMessage != ''){

            $this->fail($this->exceptionMessage);
        }
    }


    /**
     * @see TestCase::tearDownAfterClass()
     *
     * @return void
     */
    public static function tearDownAfterClass(){

        // Nothing necessary here
    }


    /** test */
    public function testIsArray(){

        // Test empty values
        $this->assertTrue(!ArrayUtils::isArray(null));
        $this->assertTrue(ArrayUtils::isArray([]));
        $this->assertTrue(!ArrayUtils::isArray(0));

        // Test correct values
        $this->assertTrue(ArrayUtils::isArray([1]));
        $this->assertTrue(ArrayUtils::isArray(['2']));
        $this->assertTrue(ArrayUtils::isArray(['q']));
        $this->assertTrue(ArrayUtils::isArray([true, false]));
        $this->assertTrue(ArrayUtils::isArray([1, 4, 'a']));
        $this->assertTrue(ArrayUtils::isArray([new Exception(), 67]));

        // Test wrong values
        $this->assertTrue(!ArrayUtils::isArray(1));
        $this->assertTrue(!ArrayUtils::isArray('a'));
        $this->assertTrue(!ArrayUtils::isArray(false));
        $this->assertTrue(!ArrayUtils::isArray(new Exception()));
        $this->assertTrue(!ArrayUtils::isArray((object) [
            'a' => 1
        ]));
    }


    /**
     * testIsEqualTo
     *
     * @return void
     */
    public function testIsEqualTo(){

        // Test non array values must launch exception
        AssertUtils::throwsException(function() { ArrayUtils::isEqualTo(null, null); }, '/must be of the type array, null given/');
        AssertUtils::throwsException(function() { ArrayUtils::isEqualTo(1, 1); }, '/must be of the type array, int given/');
        AssertUtils::throwsException(function() { ArrayUtils::isEqualTo('asfasf1', '345345'); }, '/must be of the type array, string given/');

        // Test identic arrays
        $this->assertTrue(ArrayUtils::isEqualTo([null], [null]));
        $this->assertTrue(ArrayUtils::isEqualTo([], []));
        $this->assertTrue(ArrayUtils::isEqualTo([[]], [[]]));
        $this->assertTrue(ArrayUtils::isEqualTo([[1]], [[1]]));
        $this->assertTrue(ArrayUtils::isEqualTo([1, 2, 3], [1, 2, 3]));
        $this->assertTrue(ArrayUtils::isEqualTo([1, 2, 1, 2], [1, 2, 1, 2]));
        $this->assertTrue(ArrayUtils::isEqualTo([1, 2, [3, 4]], [1, 2, [3, 4]]));
        $this->assertTrue(ArrayUtils::isEqualTo(['hello world'], ['hello world']));

        // Test different arrays
        $this->assertTrue(!ArrayUtils::isEqualTo([null], []));
        $this->assertTrue(!ArrayUtils::isEqualTo([1], ['1']));
        $this->assertTrue(!ArrayUtils::isEqualTo([1, 2, 3], [1, 3, 2]));
        $this->assertTrue(!ArrayUtils::isEqualTo([1, '2,3'], [1, 2, 3]));
        $this->assertTrue(!ArrayUtils::isEqualTo([1, 2, [3, 4]], [1, 2, [3, 2]]));
        $this->assertTrue(!ArrayUtils::isEqualTo([1, 2, [3, [4]]], [1, 2, [3, ['4']]]));
        $this->assertTrue(!ArrayUtils::isEqualTo(['hello world'], ['hello worl1d']));
        $this->assertTrue(!ArrayUtils::isEqualTo([0, 1], ['a' => 0, 'b' => 1]));
        $this->assertTrue(!ArrayUtils::isEqualTo(['0' => 0, '1' => 1], ['a' => 0, 'b' => 1]));
        $this->assertTrue(!ArrayUtils::isEqualTo(['0' => null, '1' => null], ['a' => 0, 'b' => 1]));
        $this->assertTrue(!ArrayUtils::isEqualTo(['a' => 0, 'b' => 1], ['a' => 0, 'b' => 2]));

        // Test identic objects
        $this->assertTrue(ArrayUtils::isEqualTo([(object) [
                'a' => 1
        ]], [(object) [
                'a' => 1
        ]]));

        $this->assertTrue(ArrayUtils::isEqualTo([(object) [
                'a' => 1,
                'b' => [1, 2, 3],
                'c' => 'hello'
        ]], [(object) [
                'a' => 1,
                'b' => [1, 2, 3],
                'c' => 'hello'
        ]]));

        // Test different objects
        $this->assertTrue(!ArrayUtils::isEqualTo([(object) [
                'a' => 1,
                'b' => [1, 4, 3],
                'c' => 'hello'
        ]], [(object) [
                'a' => 1,
                'b' => [1, 2, 3],
                'c' => 'hello'
        ]]));
    }


    /**
     * testIsStringFound
     *
     * @return void
     */
    public function testIsStringFound(){

        // Test empty values
        // TODO - translate from ts

        // Test ok values
        // TODO - translate from ts

        // Test wrong values
        // TODO - translate from ts

        // Test exceptions
        // TODO - translate from ts
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testFindMostRepeatedValue
     *
     * @return void
     */
    public function testFindMostRepeatedValue(){

        AssertUtils::throwsException(function() { ArrayUtils::findMostRepeatedValue(null); }, '/must be of the type array, null given/');
        AssertUtils::throwsException(function() { ArrayUtils::findMostRepeatedValue(0); }, '/must be of the type array, int given/');
        AssertUtils::throwsException(function() { ArrayUtils::findMostRepeatedValue(''); }, '/must be of the type array, string given/');
        AssertUtils::throwsException(function() { ArrayUtils::findMostRepeatedValue([]); }, '/Received empty array/');

        // Test ok values
        $this->assertSame(1, ArrayUtils::findMostRepeatedValue([1]));
        $this->assertSame(1, ArrayUtils::findMostRepeatedValue([1, 1]));
        $this->assertSame(1, ArrayUtils::findMostRepeatedValue([1, 2]));
        $this->assertSame(1, ArrayUtils::findMostRepeatedValue([1, 2, 1]));
        $this->assertSame(1, ArrayUtils::findMostRepeatedValue([1, 2, 3]));
        $this->assertSame(1, ArrayUtils::findMostRepeatedValue([1, 2, 1, 2]));
        $this->assertSame(2, ArrayUtils::findMostRepeatedValue([1, 2, 2, 2]));
        $this->assertSame("1", ArrayUtils::findMostRepeatedValue(["1"]));
        $this->assertSame("1", ArrayUtils::findMostRepeatedValue(["1", "1"]));
        $this->assertSame("1", ArrayUtils::findMostRepeatedValue(["1", "2"]));
        $this->assertSame("1", ArrayUtils::findMostRepeatedValue(["1", "2", "1"]));
        $this->assertSame("1", ArrayUtils::findMostRepeatedValue(["1", "2", "1", "2"]));
        $this->assertSame("2", ArrayUtils::findMostRepeatedValue(["1", "2", "2", "2"]));
        $this->assertSame(1, ArrayUtils::findMostRepeatedValue([1, "1", 1, "1"]));
        $this->assertSame("2", ArrayUtils::findMostRepeatedValue(["1", "2", "2", 2]));
        $this->assertSame(1, ArrayUtils::findMostRepeatedValue([1, 1, 1, "2", "2", 2, 2]));
        $this->assertSame("a", ArrayUtils::findMostRepeatedValue(["a", "A", "a"]));
        $this->assertSame(true, ArrayUtils::findMostRepeatedValue([true, false, true]));
        $this->assertSame(1.1, ArrayUtils::findMostRepeatedValue([1.1, 2.2, 1.1, 2.2, 1.1]));
        $this->assertSame("a", ArrayUtils::findMostRepeatedValue(["a", "b", "a", "!"]));
        $this->assertSame(null, ArrayUtils::findMostRepeatedValue([null, null, 1]));
        $this->assertSame(-1, ArrayUtils::findMostRepeatedValue([-1, -1, 2, 3]));
        $this->assertSame(9999999999, ArrayUtils::findMostRepeatedValue([9999999999, 9999999999, 1]));

        // Test wrong values
        // Test exceptions
        AssertUtils::throwsException(function() { ArrayUtils::findMostRepeatedValue("hello"); }, '/must be of the type array, string given/');

    }

    /** test */
    public function testForceNonEmptyArray(){

        AssertUtils::throwsException(function() { ArrayUtils::forceNonEmptyArray(null); }, '/must be a non empty array/');
        AssertUtils::throwsException(function() { ArrayUtils::forceNonEmptyArray(0); }, '/must be a non empty array/');
        AssertUtils::throwsException(function() { ArrayUtils::forceNonEmptyArray(''); }, '/must be a non empty array/');
        AssertUtils::throwsException(function() { ArrayUtils::forceNonEmptyArray([]); }, '/must be a non empty array/');
        AssertUtils::throwsException(function() { ArrayUtils::forceNonEmptyArray('      '); }, '/must be a non empty array/');
        AssertUtils::throwsException(function() { ArrayUtils::forceNonEmptyArray("\n\n  \n"); }, '/must be a non empty array/');
        AssertUtils::throwsException(function() { ArrayUtils::forceNonEmptyArray("\t   \n     \r\r"); }, '/must be a non empty array/');

        ArrayUtils::forceNonEmptyArray(['adsadf']);
        ArrayUtils::forceNonEmptyArray([1,2,3]);
        ArrayUtils::forceNonEmptyArray([null]);

        // Test non string value gives exception
        AssertUtils::throwsException(function() { ArrayUtils::forceNonEmptyArray(123); }, '/must be a non empty array/');
    }


    /**
     * testRemoveElement
     *
     * @return void
     */
    public function testRemoveElement(){

        // Test non array values must launch exception
        try {
            ArrayUtils::removeElement(null, null);
            $this->exceptionMessage = 'null did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            ArrayUtils::removeElement(1, 1);
            $this->exceptionMessage = '1 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            ArrayUtils::removeElement("asfasf1", "345345");
            $this->exceptionMessage = 'asfasf1 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test several arrays
        $this->assertEquals(ArrayUtils::removeElement([], null), []);
        $this->assertEquals(ArrayUtils::removeElement([], 1), []);
        $this->assertEquals(ArrayUtils::removeElement([1], 1), []);
        $this->assertEquals(ArrayUtils::removeElement(["1"], 1), ["1"]);
        $this->assertEquals(ArrayUtils::removeElement(["1"], "1"), []);
        $this->assertEquals(ArrayUtils::removeElement([1, 2, 3, 4], 1), [2, 3, 4]);
        $this->assertEquals(ArrayUtils::removeElement([1, 2, 3, 4], 8), [1, 2, 3, 4]);
        $this->assertEquals(ArrayUtils::removeElement(["hello", "guys"], "guys"), ["hello"]);
        $this->assertEquals(ArrayUtils::removeElement(["hello", 1, ["test"]], 1), ["hello", ["test"]]);
        $this->assertEquals(ArrayUtils::removeElement(["hello", 1, ["test"]], ["test"]), ["hello", 1]);
        $this->assertEquals(ArrayUtils::removeElement(["hello", 1, ["test", "array"], ["test"]], ["test", "array"]), ["hello", 1, ["test"]]);
    }


    /**
     * testRemoveDuplicateElements
     *
     * @return void
     */
    public function testRemoveDuplicateElements(){

        // Test empty values
        $this->exceptionMessage = '';

        try {
            ArrayUtils::removeDuplicateElements(null);
            $this->exceptionMessage = 'null did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            ArrayUtils::removeDuplicateElements('');
            $this->exceptionMessage = '"" did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            ArrayUtils::removeDuplicateElements(new stdClass());
            $this->exceptionMessage = 'new stdClass() did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        $this->assertEquals([], ArrayUtils::removeDuplicateElements([]));
        $this->assertEquals([null], ArrayUtils::removeDuplicateElements([null]));
        $this->assertEquals([null], ArrayUtils::removeDuplicateElements([null, null]));

        // Test ok values
        $this->assertEquals([1], ArrayUtils::removeDuplicateElements([1, 1]));
        $this->assertEquals(['1'], ArrayUtils::removeDuplicateElements(['1', '1']));
        $this->assertEquals([1, 0], ArrayUtils::removeDuplicateElements([1, 0, 1]));
        $this->assertEquals(['1', '0'], ArrayUtils::removeDuplicateElements(['1', '0', '1']));
        $this->assertEquals([1, 2, 3, 4], ArrayUtils::removeDuplicateElements([1, 2, 3, 4, 2]));
        $this->assertEquals(['hello', 'go'], ArrayUtils::removeDuplicateElements(['hello', 'go', 'hello']));
        $this->assertEquals([new Exception(), 'go', 'hello'], ArrayUtils::removeDuplicateElements([new Exception(), 'go', 'hello', new Exception()]));

        // Test wrong values
        $this->assertEquals([1], ArrayUtils::removeDuplicateElements([1]));
        $this->assertEquals([1, 2], ArrayUtils::removeDuplicateElements([1, 2]));
        $this->assertEquals(['1', '2'], ArrayUtils::removeDuplicateElements(['1', '2']));
        $this->assertEquals([1, 2, 3, 4, 5, 6], ArrayUtils::removeDuplicateElements([1, 2, 3, 4, 5, 6]));
        $this->assertEquals(['1', 1], ArrayUtils::removeDuplicateElements(['1', 1]));
        $this->assertEquals([new Exception(), 'go', 'hello'], ArrayUtils::removeDuplicateElements([new Exception(), 'go', 'hello']));

        // Test exceptions
        // Already tested with empty values
    }


    /**
     * testHasDuplicateElements
     *
     * @return void
     */
    public function testHasDuplicateElements(){

        // Test empty values
        $this->exceptionMessage = '';

        try {
            ArrayUtils::hasDuplicateElements(null);
            $this->exceptionMessage = 'null did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            ArrayUtils::hasDuplicateElements('');
            $this->exceptionMessage = '"" did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            ArrayUtils::hasDuplicateElements(new stdClass());
            $this->exceptionMessage = 'new stdClass() did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        $this->assertFalse(ArrayUtils::hasDuplicateElements([]));
        $this->assertFalse(ArrayUtils::hasDuplicateElements([null]));

        // Test ok values
        $this->assertTrue(ArrayUtils::hasDuplicateElements([1, 1]));
        $this->assertTrue(ArrayUtils::hasDuplicateElements(['1', '1']));
        $this->assertTrue(ArrayUtils::hasDuplicateElements([1, 0, 1]));
        $this->assertTrue(ArrayUtils::hasDuplicateElements(['1', '0', '1']));
        $this->assertTrue(ArrayUtils::hasDuplicateElements([1, 2, 3, 4, 2]));
        $this->assertTrue(ArrayUtils::hasDuplicateElements(['hello', 'go', 'hello']));
        $this->assertTrue(ArrayUtils::hasDuplicateElements([new Exception(), 'go', 'hello', new Exception()]));

        $array = [];

        for ($i = 0; $i < 100; $i++) {

            for ($j = 0; $j < 100; $j++) {

                $array[] = $j;
            }

            $array[] = $i;

            $this->assertTrue(ArrayUtils::hasDuplicateElements($array));
        }

        // Test wrong values
        $this->assertFalse(ArrayUtils::hasDuplicateElements([1]));
        $this->assertFalse(ArrayUtils::hasDuplicateElements([1, 2]));
        $this->assertFalse(ArrayUtils::hasDuplicateElements(['1', '2']));
        $this->assertFalse(ArrayUtils::hasDuplicateElements([1, 2, 3, 4, 5, 6]));
        $this->assertFalse(ArrayUtils::hasDuplicateElements(['1', 1]));
        $this->assertFalse(ArrayUtils::hasDuplicateElements([new Exception(), 'go', 'hello']));

        // Test exceptions
        // Already tested with empty values
    }


    /**
     * testGetDuplicateElements
     *
     * @return void
     */
    public function testGetDuplicateElements(){

        // Test empty values
        $this->exceptionMessage = '';

        try {
            ArrayUtils::getDuplicateElements(null);
            $this->exceptionMessage = 'null did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            ArrayUtils::getDuplicateElements('');
            $this->exceptionMessage = '"" did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            ArrayUtils::getDuplicateElements(new stdClass());
            $this->exceptionMessage = 'new stdClass() did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        $this->assertEquals([], ArrayUtils::getDuplicateElements([]));
        $this->assertEquals([], ArrayUtils::getDuplicateElements([null]));

        // Test ok values
        $this->assertEquals([1], ArrayUtils::getDuplicateElements([1, 1]));
        $this->assertEquals(['1'], ArrayUtils::getDuplicateElements(['1', '1']));
        $this->assertEquals([1], ArrayUtils::getDuplicateElements([1, 0, 1]));
        $this->assertEquals(['1'], ArrayUtils::getDuplicateElements(['1', '0', '1']));
        $this->assertEquals([2], ArrayUtils::getDuplicateElements([1, 2, 3, 4, 2]));
        $this->assertEquals([2, 3], ArrayUtils::getDuplicateElements([1, 2, 3, 4, 2, 3, 3, 3]));
        $this->assertEquals(['hello'], ArrayUtils::getDuplicateElements(['hello', 'go', 'hello']));
        $this->assertEquals([new Exception()], ArrayUtils::getDuplicateElements([new Exception(), 'go', 'hello', new Exception()]));

        // Test wrong values
        $this->assertEquals([], ArrayUtils::getDuplicateElements([1]));
        $this->assertEquals([], ArrayUtils::getDuplicateElements([1, 2]));
        $this->assertEquals([], ArrayUtils::getDuplicateElements(['1', '2']));
        $this->assertEquals([], ArrayUtils::getDuplicateElements([1, 2, 3, 4, 5, 6]));
        $this->assertEquals([], ArrayUtils::getDuplicateElements(['1', 1]));
        $this->assertEquals([], ArrayUtils::getDuplicateElements([new Exception(), 'go', 'hello']));

        // Test exceptions
        // Already tested with empty values
    }
}

?>