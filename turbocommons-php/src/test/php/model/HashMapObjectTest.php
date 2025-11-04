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

use Exception;
use Throwable;
use PHPUnit\Framework\TestCase;
use stdClass;
use org\turbocommons\src\main\php\model\HashMapObject;
use org\turbocommons\src\main\php\utils\ArrayUtils;
use org\turbocommons\src\main\php\utils\ObjectUtils;


/**
 * HashMapObjectTest
 *
 * @return void
 */
class HashMapObjectTest extends TestCase {


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

        $this->emptyValues = [null, '', [], new stdClass(), '     ', "\n\n\n", 0];
        $this->emptyValuesCount = count($this->emptyValues);

        $this->populatedHashMap = new HashMapObject();
        $this->populatedHashMap->set('a', 1);
        $this->populatedHashMap->set('b', 2);
        $this->populatedHashMap->set('c', 3);
        $this->populatedHashMap->set('d', 4);
        $this->populatedHashMap->set('e', 5);
        $this->populatedHashMap->set('f', 6);
        $this->populatedHashMap->set('g', 7);
        $this->populatedHashMap->set('string', 'myValue');
        $this->populatedHashMap->set('array', [1, 2, 3, 4]);
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


    /**
     * testConstruct
     *
     * @return void
     */
    public function testConstruct(){

        // Test empty values
        $test = new HashMapObject();
        $this->assertTrue($test->length() === 0);

        $test = new HashMapObject(null);
        $this->assertTrue($test->length() === 0);

        $test = new HashMapObject([]);
        $this->assertTrue($test->length() === 0);

        $exceptionEmptyValues = ['', new stdClass(), '     ', "\n\n\n", 0];
        $exceptionEmptyValuesCount = count($exceptionEmptyValues);

        for ($i = 0; $i < $exceptionEmptyValuesCount; $i++) {

            try {
                $test = new HashMapObject($exceptionEmptyValues[$i]);
                $this->exceptionMessage = 'empty value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        // Test ok values
        $test = new HashMapObject(['a']);
        $this->assertTrue($test->length() === 1);
        $this->assertTrue($test->get('0') === 'a');

        $test = new HashMapObject(['a' => 1]);
        $this->assertTrue($test->length() === 1);
        $this->assertTrue($test->get('a') === 1);

        $test = new HashMapObject([1, 2, 3]);
        $this->assertTrue($test->length() === 3);
        $this->assertTrue($test->get('0') === 1);
        $this->assertTrue($test->get('2') === 3);

        $test = new HashMapObject(['a', 'b', 'c', 'd', 'e', 'f']);
        $this->assertTrue($test->length() === 6);
        $this->assertTrue($test->get('0') === 'a');
        $this->assertTrue($test->get('3') === 'd');
        $this->assertTrue($test->get('5') === 'f');

        $test = new HashMapObject(['1' => 'a', '2' => 'b', '3' => 'c', '4' => 'd', '5' => 'e', '6' => 'f']);
        $this->assertTrue($test->length() === 6);
        $this->assertTrue($test->get('1') === 'a');
        $this->assertTrue($test->get('4') === 'd');
        $this->assertTrue($test->get('6') === 'f');

        $test = new HashMapObject([1 => 'a', 2 => 'b', 3 => 'c', 4 => 'd', 5 => 'e', 6 => 'f']);
        $this->assertTrue($test->length() === 6);
        $this->assertTrue($test->get('1') === 'a');
        $this->assertTrue($test->get('4') === 'd');
        $this->assertTrue($test->get('6') === 'f');

        // Test wrong values
        // Tested with exceptions

        // Test exceptions
        $exceptionValues = [123, 'hello', new Exception(), -1, 0.23];
        $exceptionValuesCount = count($exceptionValues);

        for ($i = 0; $i < $exceptionValuesCount; $i++) {

            try {
                $test = new HashMapObject($exceptionValues[$i]);
                $this->exceptionMessage = 'exception value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }
    }


    /**
     * testSet
     *
     * @return void
     */
    public function testSet(){

        $h = new HashMapObject();

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                $h->set($this->emptyValues[$i], null);
                $this->exceptionMessage = 'empty value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        // Test ok values
        $this->assertTrue($h->set('a', null) === null);
        $this->assertTrue($h->set('b', 1) === 1);
        $this->assertTrue($h->length() === 2);
        $this->assertTrue($h->set('c', '2') === '2');
        $this->assertTrue($h->set('d', [3]) === [3]);
        $this->assertTrue($h->length() === 4);
        $this->assertTrue(ObjectUtils::isEqualTo($h->set('e', new HashMapObject()), new HashMapObject()));
        $this->assertTrue($h->length() === 5);
        $this->assertTrue($h->set('d', 4) === 4);
        $this->assertTrue($h->set('d', [6]) === [6]);
        $this->assertTrue($h->set('d', '10') === '10');
        $this->assertTrue($h->length() === 5);

        // Test wrong values
        // Nothing to test

        // Test exceptions
        // Already tested on empty values
    }


    /**
     * testLength
     *
     * @return void
     */
    public function testLength(){

        $h = new HashMapObject();

        // Test empty values
        // Not necessary

        // Test ok values
        $this->assertTrue($h->length() === 0);
        $h->set('a', null);
        $this->assertTrue($h->length() === 1);
        $h->set('c', '2');
        $this->assertTrue($h->length() === 2);
        $this->assertTrue($h->length() === 2);
        $h->set('d', 4);
        $this->assertTrue($h->length() === 3);

        // Test wrong values
        // Not necessary

        // Test exceptions
        // Not necessary
    }


    /**
     * testGet
     *
     * @return void
     */
    public function testGet(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                $this->populatedHashMap->get($this->emptyValues[$i]);
                $this->exceptionMessage = 'empty value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        // Test ok values
        $this->assertTrue($this->populatedHashMap->get('a') === 1);
        $this->assertTrue($this->populatedHashMap->get('c') === 3);
        $this->assertTrue($this->populatedHashMap->get('e') === 5);
        $this->assertTrue($this->populatedHashMap->get('g') === 7);
        $this->assertTrue($this->populatedHashMap->get('string') === 'myValue');
        $this->assertTrue($this->populatedHashMap->get('array') === [1, 2, 3, 4]);

        // Test wrong values
        $this->assertFalse($this->populatedHashMap->get('a') === 11);
        $this->assertFalse($this->populatedHashMap->get('c') === 1);
        $this->assertFalse($this->populatedHashMap->get('e') === 3);
        $this->assertFalse($this->populatedHashMap->get('g') === 9);
        $this->assertFalse($this->populatedHashMap->get('string') === '-myValue');
        $this->assertFalse($this->populatedHashMap->get('array') === [1, 2, 3, 4, 5]);

        // Test exceptions
        try {
            $this->populatedHashMap->get('J');
            $this->exceptionMessage = 'J value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->populatedHashMap->get('undefined');
            $this->exceptionMessage = 'undefined value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }
    }


    /**
     * testGetAt
     *
     * @return void
     */
    public function testGetAt(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            if($this->emptyValues[$i] === 0){

                $this->assertTrue($this->populatedHashMap->getAt(0) === 1);

            }else{

                try {
                    $this->populatedHashMap->getAt($this->emptyValues[$i]);
                    $this->exceptionMessage = 'empty value did not cause exception';
                } catch (Throwable $e) {
                    // We expect an exception to happen
                }
            }
        }

        try {
            $this->populatedHashMap->getAt('0');
            $this->exceptionMessage = '0 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->populatedHashMap->getAt('00');
            $this->exceptionMessage = '00 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test ok values
        $this->assertTrue($this->populatedHashMap->getAt(1) === 2);
        $this->assertTrue($this->populatedHashMap->getAt(2) === 3);
        $this->assertTrue($this->populatedHashMap->getAt(4) === 5);
        $this->assertTrue($this->populatedHashMap->getAt(6) === 7);
        $this->assertTrue($this->populatedHashMap->getAt(7) === 'myValue');
        $this->assertTrue($this->populatedHashMap->getAt(8) === [1, 2, 3, 4]);

        // Test wrong values
        $this->assertFalse($this->populatedHashMap->getAt(8) === 11);
        $this->assertFalse($this->populatedHashMap->getAt(5) === 1);
        $this->assertFalse($this->populatedHashMap->getAt(3) === 3);
        $this->assertFalse($this->populatedHashMap->getAt(2) === 9);
        $this->assertFalse($this->populatedHashMap->getAt(1) === '-myValue');
        $this->assertFalse($this->populatedHashMap->getAt(0) === [1, 2, 3, 4, 5]);

        // Test exceptions
        try {
            $this->populatedHashMap->getAt(-1);
            $this->exceptionMessage = '-1 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->populatedHashMap->getAt(20);
            $this->exceptionMessage = '20 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->populatedHashMap->getAt(2.1);
            $this->exceptionMessage = '2.1 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->populatedHashMap->getAt('4');
            $this->exceptionMessage = '4 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->populatedHashMap->getAt('adfa');
            $this->exceptionMessage = 'adfa value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }
    }


    /**
     * testGetKeys
     *
     * @return void
     */
    public function testGetKeys(){

        // Test empty values
        // Not necessary

        // Test ok values
        $this->assertTrue($this->populatedHashMap->getKeys() === ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'string', 'array']);

        $h = new HashMapObject();
        $h->set('0', 0);
        $h->set('00', 0);
        $h->set('01', 1);
        $h->set('002', 2);
        $h->set('a', 'a');
        $this->assertTrue($h->getKeys() === ['0', '00', '01', '002', 'a']);

        // Test wrong values
        $this->assertFalse($this->populatedHashMap->getKeys() === ['b', 'c', 'd', 'e', 'f', 'g', 'string', 'array']);
        $this->assertFalse($this->populatedHashMap->getKeys() === ['b', 'a', 'c', 'd', 'e', 'f', 'g', 'string', 'array']);
        $this->assertFalse($this->populatedHashMap->getKeys() === ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'string']);
        $this->assertFalse($this->populatedHashMap->getKeys() === ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'string', 'array', null]);

        // Test exceptions
        // Not necessary
    }


    /**
     * testGetValues
     *
     * @return void
     */
    public function testGetValues(){

        // Test empty values
        // Not necessary

        // Test ok values
        $this->assertTrue($this->populatedHashMap->getValues() === [1, 2, 3, 4, 5, 6, 7, 'myValue', [1, 2, 3, 4]]);

        $h = new HashMapObject();
        $h->set('0', 0);
        $h->set('01', 1);
        $h->set('002', 2);
        $h->set('a', 'a');
        $this->assertEquals([0, 1, 2, 'a'], $h->getValues());

        // Test wrong values
        $this->assertFalse($this->populatedHashMap->getValues() === [1, 2, 3, 4, 5, 6, 7, 'myValu1e', [1, 2, 3, 4]]);
        $this->assertFalse($this->populatedHashMap->getValues() === [1, 2, 3, 4, 5, 6, 1, 'myValue', [1, 2, 3, 4]]);
        $this->assertFalse($this->populatedHashMap->getValues() === [1, 2, 3, 4, 5, 6, 7, 'myValue', [1, 3, 3, 4]]);
        $this->assertFalse($this->populatedHashMap->getValues() === [1, 1, 2, 3, 4, 5, 6, 7, 'myValue', [1, 2, 3, 4]]);

        // Test exceptions
        // Not necessary
    }


    /**
     * testIsKey
     *
     * @return void
     */
    public function testIsKey(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            $this->assertFalse($this->populatedHashMap->isKey($this->emptyValues[$i]));
        }

        // Test ok values
        $this->assertTrue($this->populatedHashMap->isKey('a'));
        $this->assertTrue($this->populatedHashMap->isKey('b'));
        $this->assertTrue($this->populatedHashMap->isKey('c'));
        $this->assertTrue($this->populatedHashMap->isKey('d'));
        $this->assertTrue($this->populatedHashMap->isKey('e'));
        $this->assertTrue($this->populatedHashMap->isKey('string'));
        $this->assertTrue($this->populatedHashMap->isKey('array'));

        // Test wrong values
        $this->assertFalse($this->populatedHashMap->isKey('J'));
        $this->assertFalse($this->populatedHashMap->isKey('Q'));
        $this->assertFalse($this->populatedHashMap->isKey('1'));
        $this->assertFalse($this->populatedHashMap->isKey('unknown'));

        // Test exceptions
        // Tested with empty values
    }


    /**
     * testRemove
     *
     * @return void
     */
    public function testRemove(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                $this->populatedHashMap->remove($this->emptyValues[$i]);
                $this->exceptionMessage = 'empty value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        // Test ok values
        foreach ($this->populatedHashMap->getKeys() as $key) {

            $value = $this->populatedHashMap->get($key);

            $this->assertTrue($this->populatedHashMap->isKey($key));
            $this->assertTrue($this->populatedHashMap->get($key) !== null);
            $this->assertTrue($this->populatedHashMap->remove($key) === $value);
            $this->assertFalse($this->populatedHashMap->isKey($key));

            try {
                $this->populatedHashMap->remove($key);
                $this->exceptionMessage = $key.' value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->populatedHashMap->get($key);
                $this->exceptionMessage = $key.' value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        $this->assertTrue($this->populatedHashMap->length() === 0);

        // Test wrong values
        try {
            $this->populatedHashMap->remove('J');
            $this->exceptionMessage = 'J did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->populatedHashMap->remove('undefinedKey');
            $this->exceptionMessage = 'undefinedKey did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test exceptions
        // Tested at empty values
    }


    /**
     * testRename
     *
     * @return void
     */
    public function testRename(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            for ($j = 0; $j < $this->emptyValuesCount; $j++) {

                try {
                    $this->populatedHashMap->rename($this->emptyValues[$i], $this->emptyValues[$j]);
                    $this->exceptionMessage = 'empty value did not cause exception';
                } catch (Throwable $e) {
                    // We expect an exception to happen
                }

                try {
                    $this->populatedHashMap->rename($this->emptyValues[$i], 'a');
                    $this->exceptionMessage = 'empty value did not cause exception';
                } catch (Throwable $e) {
                    // We expect an exception to happen
                }

                try {
                    $this->populatedHashMap->rename('a', $this->emptyValues[$j]);
                    $this->exceptionMessage = 'empty value did not cause exception';
                } catch (Throwable $e) {
                    // We expect an exception to happen
                }
            }
        }

        // Test ok values
        $this->assertTrue($this->populatedHashMap->rename('a', 'a1'));
        $this->assertTrue($this->populatedHashMap->get('a1') === 1);
        $this->assertTrue($this->populatedHashMap->length() === 9);
        $this->assertTrue(count($this->populatedHashMap->getValues()) === 9);

        $this->assertTrue($this->populatedHashMap->rename('c', 'somekey'));
        $this->assertTrue($this->populatedHashMap->get('somekey') === 3);
        $this->assertTrue($this->populatedHashMap->length() === 9);
        $this->assertTrue(ArrayUtils::isEqualTo($this->populatedHashMap->getKeys(), ['a1', 'b', 'somekey', 'd', 'e', 'f', 'g', 'string', 'array']));

        try {
            $this->populatedHashMap->get('a');
            $this->exceptionMessage = 'empty value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test wrong values
        try {
            $this->populatedHashMap->rename('unknown', 'b');
            $this->exceptionMessage = 'unknown value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->populatedHashMap->rename('a1', 'b');
            $this->exceptionMessage = 'a1 for existing b key did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->populatedHashMap->rename('nonexistant', 'newkey');
            $this->exceptionMessage = 'nonexistant did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test exceptions
        // Tested at empty values
    }


    /**
     * testSwap
     *
     * @return void
     */
    public function testSwap(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            for ($j = 0; $j < $this->emptyValuesCount; $j++) {

                try {
                    $this->populatedHashMap->swap($this->emptyValues[$i], $this->emptyValues[$j]);
                    $this->exceptionMessage = 'empty value did not cause exception';
                } catch (Throwable $e) {
                    // We expect an exception to happen
                }

                try {
                    $this->populatedHashMap->swap($this->emptyValues[$i], 'a');
                    $this->exceptionMessage = 'empty value did not cause exception';
                } catch (Throwable $e) {
                    // We expect an exception to happen
                }

                try {
                    $this->populatedHashMap->swap('a', $this->emptyValues[$j]);
                    $this->exceptionMessage = 'empty value did not cause exception';
                } catch (Throwable $e) {
                    // We expect an exception to happen
                }
            }
        }

        // Test ok values
        $this->assertTrue($this->populatedHashMap->swap('a', 'b'));
        $this->assertTrue($this->populatedHashMap->get('a') === 1);
        $this->assertTrue($this->populatedHashMap->get('b') === 2);
        $this->assertTrue(ArrayUtils::isEqualTo($this->populatedHashMap->getKeys(), ['b', 'a', 'c', 'd', 'e', 'f', 'g', 'string', 'array']));
        $this->assertTrue($this->populatedHashMap->swap('c', 'e'));
        $this->assertTrue($this->populatedHashMap->get('c') === 3);
        $this->assertTrue($this->populatedHashMap->get('e') === 5);
        $this->assertTrue(ArrayUtils::isEqualTo($this->populatedHashMap->getKeys(), ['b', 'a', 'e', 'd', 'c', 'f', 'g', 'string', 'array']));
        $this->assertTrue($this->populatedHashMap->swap('string', 'b'));
        $this->assertTrue($this->populatedHashMap->get('string') === 'myValue');
        $this->assertTrue($this->populatedHashMap->get('b') === 2);
        $this->assertTrue(ArrayUtils::isEqualTo($this->populatedHashMap->getKeys(), ['string', 'a', 'e', 'd', 'c', 'f', 'g', 'b', 'array']));
        $this->assertTrue($this->populatedHashMap->length() === 9);

        // Test wrong values
        try {

            $this->assertFalse($this->populatedHashMap->swap('K', 'a'));
            $this->exceptionMessage = 'K value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {

            $this->assertFalse($this->populatedHashMap->swap('no', 'string'));
            $this->exceptionMessage = 'no value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {

            $this->assertFalse($this->populatedHashMap->swap('string', 'no'));
            $this->exceptionMessage = 'no value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test exceptions
        // Tested at empty values
    }


    /**
     * testSortByKey
     *
     * @return void
     */
    public function testSortByKey(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                $this->populatedHashMap->sortByKey($this->emptyValues[$i]);
                $this->exceptionMessage = 'empty value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $this->populatedHashMap->sortByKey(HashMapObject::SORT_METHOD_NUMERIC, $this->emptyValues[$i]);
                $this->exceptionMessage = 'empty param 2 did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        $h = new HashMapObject();
        $this->assertTrue($h->length() === 0);
        $this->assertTrue($h->sortByKey());
        $this->assertTrue($h->length() === 0);

        // Test ok values
        $h = new HashMapObject();
        $h->set('b', 1);
        $h->set('d', 2);
        $h->set('a', 3);
        $h->set('c', 4);
        $h->set('0', 5);
        $this->assertTrue($h->length() === 5);
        $this->assertTrue($h->sortByKey(HashMapObject::SORT_METHOD_STRING));
        $this->assertTrue($h->length() === 5);
        $this->assertTrue(ArrayUtils::isEqualTo($h->getKeys(), ['0', 'a', 'b', 'c', 'd']));
        $this->assertTrue($h->get('b') === 1);

        $this->assertTrue($h->sortByKey(HashMapObject::SORT_METHOD_STRING, HashMapObject::SORT_ORDER_DESCENDING));
        $this->assertEquals(['d', 'c', 'b', 'a', '0'], $h->getKeys());

        $h = new HashMapObject();
        $h->set('6', 6);
        $h->set('3', 3);
        $h->set('5', 5);
        $h->set('2', 2);
        $h->set('40', 4);
        $this->assertTrue($h->length() === 5);
        $this->assertTrue($h->sortByKey(HashMapObject::SORT_METHOD_STRING));
        $this->assertTrue($h->length() === 5);
        $this->assertTrue(ArrayUtils::isEqualTo($h->getKeys(), ['2', '3', '40', '5', '6']));

        $this->assertTrue($h->sortByKey(HashMapObject::SORT_METHOD_STRING, HashMapObject::SORT_ORDER_DESCENDING));
        $this->assertTrue(ArrayUtils::isEqualTo($h->getKeys(), ['6', '5', '40', '3', '2']));

        $this->assertTrue($h->sortByKey(HashMapObject::SORT_METHOD_NUMERIC));
        $this->assertTrue($h->length() === 5);
        $this->assertTrue(ArrayUtils::isEqualTo($h->getKeys(), ['2', '3', '5', '6', '40']));

        $this->assertTrue($h->sortByKey(HashMapObject::SORT_METHOD_NUMERIC, HashMapObject::SORT_ORDER_DESCENDING));
        $this->assertTrue(ArrayUtils::isEqualTo($h->getKeys(), ['40', '6', '5', '3', '2']));
        $this->assertTrue($h->length() === 5);

        // Test wrong values
        // Not necessary

        // Test exceptions
        // Tested at empty values
    }


    /**
     * testShift
     *
     * @return void
     */
    public function testShift(){

        // Test empty values
        // Not necessary

        // Test ok values
        $this->assertTrue($this->populatedHashMap->shift() === 1);
        $this->assertTrue($this->populatedHashMap->length() === 8);
        $this->assertTrue(ArrayUtils::isEqualTo($this->populatedHashMap->getKeys(), ['b', 'c', 'd', 'e', 'f', 'g', 'string', 'array']));
        $this->assertTrue($this->populatedHashMap->shift() === 2);
        $this->assertTrue($this->populatedHashMap->length() === 7);
        $this->assertTrue(ArrayUtils::isEqualTo($this->populatedHashMap->getKeys(), ['c', 'd', 'e', 'f', 'g', 'string', 'array']));
        $this->assertTrue($this->populatedHashMap->shift() === 3);
        $this->assertTrue($this->populatedHashMap->length() === 6);
        $this->assertTrue(ArrayUtils::isEqualTo($this->populatedHashMap->getKeys(), ['d', 'e', 'f', 'g', 'string', 'array']));
        $this->assertTrue($this->populatedHashMap->shift() === 4);
        $this->assertTrue($this->populatedHashMap->shift() === 5);
        $this->assertTrue($this->populatedHashMap->shift() === 6);
        $this->assertTrue($this->populatedHashMap->length() === 3);
        $this->assertTrue(ArrayUtils::isEqualTo($this->populatedHashMap->getKeys(), ['g', 'string', 'array']));
        $this->assertTrue($this->populatedHashMap->shift() === 7);
        $this->assertTrue($this->populatedHashMap->shift() === 'myValue');
        $this->assertTrue($this->populatedHashMap->shift() === [1, 2, 3, 4]);
        $this->assertTrue($this->populatedHashMap->length() === 0);
        $this->assertTrue(ArrayUtils::isEqualTo($this->populatedHashMap->getKeys(), []));

        // Test wrong values
        // Not necessary

        // Test exceptions
        $h = new HashMapObject();

        try {

            $h->shift();
            $this->exceptionMessage = 'shift() did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }
    }


    /**
     * testPop
     *
     * @return void
     */
    public function testPop(){

        // Test empty values
        // Not necessary

        // Test ok values
        $this->assertTrue(ArrayUtils::isEqualTo($this->populatedHashMap->pop(), [1, 2, 3, 4]));
        $this->assertTrue($this->populatedHashMap->length() === 8);
        $this->assertTrue(ArrayUtils::isEqualTo($this->populatedHashMap->getKeys(), ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'string']));
        $this->assertTrue($this->populatedHashMap->pop() === 'myValue');
        $this->assertTrue($this->populatedHashMap->length() === 7);
        $this->assertTrue(ArrayUtils::isEqualTo($this->populatedHashMap->getKeys(), ['a', 'b', 'c', 'd', 'e', 'f', 'g']));
        $this->assertTrue($this->populatedHashMap->pop() === 7);
        $this->assertTrue($this->populatedHashMap->length() === 6);
        $this->assertTrue(ArrayUtils::isEqualTo($this->populatedHashMap->getKeys(), ['a', 'b', 'c', 'd', 'e', 'f']));
        $this->assertTrue($this->populatedHashMap->pop() === 6);
        $this->assertTrue($this->populatedHashMap->pop() === 5);
        $this->assertTrue($this->populatedHashMap->pop() === 4);
        $this->assertTrue($this->populatedHashMap->length() === 3);
        $this->assertTrue(ArrayUtils::isEqualTo($this->populatedHashMap->getKeys(), ['a', 'b', 'c']));
        $this->assertTrue($this->populatedHashMap->pop() === 3);
        $this->assertTrue($this->populatedHashMap->pop() === 2);
        $this->assertTrue($this->populatedHashMap->pop() === 1);
        $this->assertTrue($this->populatedHashMap->length() === 0);
        $this->assertTrue(ArrayUtils::isEqualTo($this->populatedHashMap->getKeys(), []));

        // Test wrong values
        // Not necessary

        // Test exceptions
        $h = new HashMapObject();

        try {

            $h->pop();
            $this->exceptionMessage = 'pop() did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }
    }


    /**
     * testReverse
     *
     * @return void
     */
    public function testReverse(){

        // Test empty values
        $h = new HashMapObject();
        $this->assertTrue($h->length() === 0);
        $h->reverse();
        $this->assertTrue($h->length() === 0);

        // Test ok values
        $this->assertTrue($this->populatedHashMap->length() === 9);
        $this->assertTrue($this->populatedHashMap->reverse());
        $this->assertTrue(ArrayUtils::isEqualTo($this->populatedHashMap->getKeys(), ['array', 'string', 'g', 'f', 'e', 'd', 'c', 'b', 'a']));
        $this->assertTrue($this->populatedHashMap->length() === 9);

        // Test wrong values
        // Not necessary

        // Test exceptions
        // Not necessary
    }
}

?>