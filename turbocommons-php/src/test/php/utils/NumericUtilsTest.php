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
use stdClass;
use PHPUnit\Framework\TestCase;
use org\turbocommons\src\main\php\utils\NumericUtils;
use org\turbotesting\src\main\php\utils\AssertUtils;


/**
 * NumericUtilsTest
 *
 * @return void
 */
class NumericUtilsTest extends TestCase {


    /**
     * @see TestCase::setUpBeforeClass()
     *
     * @return void
     */
    public static function setUpBeforeClass(){
    }


    /**
     * @see TestCase::setUp()
     *
     * @return void
     */
    protected function setUp(){

        $this->emptyValues = [null, '', [], new stdClass(), '     ', "\n\n\n", 0];
        $this->emptyValuesCount = count($this->emptyValues);
    }


    /**
     * @see TestCase::tearDown()
     *
     * @return void
     */
    protected function tearDown(){
    }


    /**
     * @see TestCase::tearDownAfterClass()
     *
     * @return void
     */
    public static function tearDownAfterClass(){
    }


    /**
     * test
     * @return void
     */
    public function testIsNumeric(){

        // Test empty values
        $this->assertFalse(NumericUtils::isNumeric(null));
        $this->assertFalse(NumericUtils::isNumeric(''));
        $this->assertFalse(NumericUtils::isNumeric('-'));
        $this->assertFalse(NumericUtils::isNumeric(' '));
        $this->assertFalse(NumericUtils::isNumeric([]));
        $this->assertFalse(NumericUtils::isNumeric(new stdClass()));
        $this->assertTrue(NumericUtils::isNumeric(0));
        $this->assertTrue(NumericUtils::isNumeric(-0));

        // Test ok values
        $this->assertTrue(NumericUtils::isNumeric(1));
        $this->assertTrue(NumericUtils::isNumeric(-1));
        $this->assertTrue(NumericUtils::isNumeric(.1));
        $this->assertTrue(NumericUtils::isNumeric(-.1));
        $this->assertTrue(NumericUtils::isNumeric(0.1));
        $this->assertTrue(NumericUtils::isNumeric(-0.1));
        $this->assertTrue(NumericUtils::isNumeric(1560));
        $this->assertTrue(NumericUtils::isNumeric(-1560));
        $this->assertTrue(NumericUtils::isNumeric(456.987));
        $this->assertTrue(NumericUtils::isNumeric(-456.987));
        $this->assertTrue(NumericUtils::isNumeric(0.00001));
        $this->assertTrue(NumericUtils::isNumeric(-0.00001));
        $this->assertTrue(NumericUtils::isNumeric(1560345346456));
        $this->assertTrue(NumericUtils::isNumeric(-1560345346456));
        $this->assertTrue(NumericUtils::isNumeric('1'));
        $this->assertTrue(NumericUtils::isNumeric('-1'));
        $this->assertTrue(NumericUtils::isNumeric('.1'));
        $this->assertTrue(NumericUtils::isNumeric('-.1'));
        $this->assertTrue(NumericUtils::isNumeric('0.1'));
        $this->assertTrue(NumericUtils::isNumeric('-0.1'));
        $this->assertTrue(NumericUtils::isNumeric('1560'));
        $this->assertTrue(NumericUtils::isNumeric('-1560'));
        $this->assertTrue(NumericUtils::isNumeric('456.987'));
        $this->assertTrue(NumericUtils::isNumeric('-456.987'));
        $this->assertTrue(NumericUtils::isNumeric('0.00001'));
        $this->assertTrue(NumericUtils::isNumeric('-0.00001'));
        $this->assertTrue(NumericUtils::isNumeric('1560345346456'));
        $this->assertTrue(NumericUtils::isNumeric('-1560345346456'));
        $this->assertTrue(NumericUtils::isNumeric(' 1'));
        $this->assertTrue(NumericUtils::isNumeric('1 '));
        $this->assertTrue(NumericUtils::isNumeric(' 1 '));
        $this->assertTrue(NumericUtils::isNumeric('    1     '));
        $this->assertTrue(NumericUtils::isNumeric("1     \n"));
        $this->assertTrue(NumericUtils::isNumeric('1.'));
        $this->assertTrue(NumericUtils::isNumeric('1,'));
        $this->assertTrue(NumericUtils::isNumeric('1,1'));
        $this->assertTrue(NumericUtils::isNumeric('11.'));
        $this->assertTrue(NumericUtils::isNumeric('111.'));
        $this->assertTrue(NumericUtils::isNumeric('.111'));
        $this->assertTrue(NumericUtils::isNumeric('12,345'));
        $this->assertTrue(NumericUtils::isNumeric('6000,5'));
        $this->assertTrue(NumericUtils::isNumeric('-6000,5'));
        $this->assertTrue(NumericUtils::isNumeric('10000000.'));
        $this->assertTrue(NumericUtils::isNumeric('6000000,5'));
        $this->assertTrue(NumericUtils::isNumeric('6000000.5'));
        $this->assertTrue(NumericUtils::isNumeric('-6000000.5'));
        $this->assertTrue(NumericUtils::isNumeric('1.001,01'));
        $this->assertTrue(NumericUtils::isNumeric('1,001.01'));
        $this->assertTrue(NumericUtils::isNumeric('-1,001.01'));
        $this->assertTrue(NumericUtils::isNumeric('1,001,000.01'));
        $this->assertTrue(NumericUtils::isNumeric('1.001.000.01'));
        $this->assertTrue(NumericUtils::isNumeric('1.001.000,01'));
        $this->assertTrue(NumericUtils::isNumeric('-1.001.000,01'));
        $this->assertTrue(NumericUtils::isNumeric('14.100.000.0'));
        $this->assertTrue(NumericUtils::isNumeric('14.100.000.02345'));
        $this->assertTrue(NumericUtils::isNumeric('14.100.000,02345'));
        $this->assertTrue(NumericUtils::isNumeric('14,100.000,02345'));
        $this->assertTrue(NumericUtils::isNumeric('14,100,000,02345'));
        $this->assertTrue(NumericUtils::isNumeric('-14,100,000,02345'));
        $this->assertTrue(NumericUtils::isNumeric('1236812738123877213'));
        $this->assertTrue(NumericUtils::isNumeric('-1236812738123877213'));
        $this->assertTrue(NumericUtils::isNumeric('- 1236812738123877213'));
        $this->assertTrue(NumericUtils::isNumeric('1', '.'));
        $this->assertTrue(NumericUtils::isNumeric('-1', '.'));
        $this->assertTrue(NumericUtils::isNumeric('0', '.'));
        $this->assertTrue(NumericUtils::isNumeric('1.1', '.'));
        $this->assertTrue(NumericUtils::isNumeric('1.', '.'));
        $this->assertTrue(NumericUtils::isNumeric('0.1', '.'));
        $this->assertTrue(NumericUtils::isNumeric('10.000', '.'));
        $this->assertTrue(NumericUtils::isNumeric('1265789', '.'));
        $this->assertTrue(NumericUtils::isNumeric('1', ','));
        $this->assertTrue(NumericUtils::isNumeric('-1', ','));
        $this->assertTrue(NumericUtils::isNumeric('0', ','));
        $this->assertTrue(NumericUtils::isNumeric('1,1', ','));
        $this->assertTrue(NumericUtils::isNumeric('1,', ','));
        $this->assertTrue(NumericUtils::isNumeric('0,1', ','));
        $this->assertTrue(NumericUtils::isNumeric('10,000', ','));
        $this->assertTrue(NumericUtils::isNumeric('1265789', ','));

        $objectThatMustNotBeAltered = ((object) ['value' => " 15  "]);
        $this->assertTrue(NumericUtils::isNumeric($objectThatMustNotBeAltered->value));
        $this->assertSame(" 15  ", $objectThatMustNotBeAltered->value);

        // Test wrong values
        $this->assertFalse(NumericUtils::isNumeric('abc'));
        $this->assertFalse(NumericUtils::isNumeric('col20'));
        $this->assertFalse(NumericUtils::isNumeric('1-'));
        $this->assertFalse(NumericUtils::isNumeric(' '));
        $this->assertFalse(NumericUtils::isNumeric('!.1'));
        $this->assertFalse(NumericUtils::isNumeric([1, 2, 3]));
        $this->assertFalse(NumericUtils::isNumeric(['hello']));
        $this->assertFalse(NumericUtils::isNumeric(new Exception()));
        $this->assertFalse(NumericUtils::isNumeric(((object) ['1' => 1])));
        $this->assertFalse(NumericUtils::isNumeric('1.0.'));
        $this->assertFalse(NumericUtils::isNumeric('1,0.'));
        $this->assertFalse(NumericUtils::isNumeric('1.0,'));
        $this->assertFalse(NumericUtils::isNumeric('1,0,'));
        $this->assertFalse(NumericUtils::isNumeric(',.0'));
        $this->assertFalse(NumericUtils::isNumeric('1..0'));
        $this->assertFalse(NumericUtils::isNumeric('1...0'));
        $this->assertFalse(NumericUtils::isNumeric('1....0'));
        $this->assertFalse(NumericUtils::isNumeric('1,,0'));
        $this->assertFalse(NumericUtils::isNumeric('1,,.,0'));
        $this->assertFalse(NumericUtils::isNumeric('1.,0'));
        $this->assertFalse(NumericUtils::isNumeric('-1.,0'));
        $this->assertFalse(NumericUtils::isNumeric('1,.0'));
        $this->assertFalse(NumericUtils::isNumeric('1000,0.0'));
        $this->assertFalse(NumericUtils::isNumeric('1,000,0.0'));
        $this->assertFalse(NumericUtils::isNumeric('10.00,0.0'));
        $this->assertFalse(NumericUtils::isNumeric('.1000,0.0'));
        $this->assertFalse(NumericUtils::isNumeric('.10.00,0.0'));
        $this->assertFalse(NumericUtils::isNumeric('1234.10.00,0.0'));
        $this->assertFalse(NumericUtils::isNumeric('1234.100.000.0'));
        $this->assertFalse(NumericUtils::isNumeric('10.000.000.'));
        $this->assertFalse(NumericUtils::isNumeric('-1000,0.0'));
        $this->assertFalse(NumericUtils::isNumeric('-10.000.000.'));
        $this->assertFalse(NumericUtils::isNumeric('12.34.56'));
        $this->assertFalse(NumericUtils::isNumeric('$50'));
        $this->assertFalse(NumericUtils::isNumeric('Infinity'));
        $this->assertFalse(NumericUtils::isNumeric('NaN'));
        $this->assertFalse(NumericUtils::isNumeric('1/2'));
        $this->assertFalse(NumericUtils::isNumeric('a', '.'));
        $this->assertFalse(NumericUtils::isNumeric('1/2', '.'));
        $this->assertFalse(NumericUtils::isNumeric('1..', '.'));
        $this->assertFalse(NumericUtils::isNumeric('1..1', '.'));
        $this->assertFalse(NumericUtils::isNumeric('1,,1', '.'));
        $this->assertFalse(NumericUtils::isNumeric('...', '.'));
        $this->assertFalse(NumericUtils::isNumeric('a', ','));
        $this->assertFalse(NumericUtils::isNumeric('1/2', ','));
        $this->assertFalse(NumericUtils::isNumeric('1,,', ','));
        $this->assertFalse(NumericUtils::isNumeric('1,,1', ','));
        $this->assertFalse(NumericUtils::isNumeric('1..1', ','));
        $this->assertFalse(NumericUtils::isNumeric('...', ','));
        $this->assertFalse(NumericUtils::isNumeric(',,,', ','));
    }


    /**
     * test
     * @return void
     */
    public function testIsInteger(){

        // Test empty values
        $this->assertFalse(NumericUtils::isInteger(null));
        $this->assertFalse(NumericUtils::isInteger(''));
        $this->assertFalse(NumericUtils::isInteger([]));
        $this->assertFalse(NumericUtils::isInteger(new stdClass()));
        $this->assertTrue(NumericUtils::isInteger(0));
        $this->assertTrue(NumericUtils::isInteger(-0));

        // Test ok values
        $this->assertTrue(NumericUtils::isInteger(1));
        $this->assertTrue(NumericUtils::isInteger(-1));
        $this->assertTrue(NumericUtils::isInteger(1560));
        $this->assertTrue(NumericUtils::isInteger(-1560));
        $this->assertTrue(NumericUtils::isInteger(1560345346456));
        $this->assertTrue(NumericUtils::isInteger(-1560345346456));
        $this->assertTrue(NumericUtils::isInteger('1'));
        $this->assertTrue(NumericUtils::isInteger('-1'));
        $this->assertTrue(NumericUtils::isInteger('1560'));
        $this->assertTrue(NumericUtils::isInteger('-1560'));
        $this->assertTrue(NumericUtils::isInteger('1560345346456'));
        $this->assertTrue(NumericUtils::isInteger('-1560345346456'));
        $this->assertTrue(NumericUtils::isInteger('1560345346456356456246235456'));
        $this->assertTrue(NumericUtils::isInteger('-15603453464564525123524565476546'));
        $this->assertTrue(NumericUtils::isInteger(' 1'));
        $this->assertTrue(NumericUtils::isInteger('1 '));
        $this->assertTrue(NumericUtils::isInteger(' 1 '));
        $this->assertTrue(NumericUtils::isInteger('    1     '));
        $this->assertTrue(NumericUtils::isInteger("1     \n"));

        // Test wrong values
        $this->assertFalse(NumericUtils::isInteger('.1'));
        $this->assertFalse(NumericUtils::isInteger('-.1'));
        $this->assertFalse(NumericUtils::isInteger('0.1'));
        $this->assertFalse(NumericUtils::isInteger('-0.1'));
        $this->assertFalse(NumericUtils::isInteger('456.987'));
        $this->assertFalse(NumericUtils::isInteger('-456.987'));
        $this->assertFalse(NumericUtils::isInteger('0.00001'));
        $this->assertFalse(NumericUtils::isInteger('-0.00001'));
        $this->assertFalse(NumericUtils::isInteger('abc'));
        $this->assertFalse(NumericUtils::isInteger('1-'));
        $this->assertFalse(NumericUtils::isInteger('1,1'));
        $this->assertFalse(NumericUtils::isInteger(' '));
        $this->assertFalse(NumericUtils::isInteger('!.1'));
        $this->assertFalse(NumericUtils::isInteger([1, 2, 3]));
        $this->assertFalse(NumericUtils::isInteger(['hello']));
        $this->assertFalse(NumericUtils::isInteger(new Exception()));
        $this->assertFalse(NumericUtils::isInteger(((object) ['1' => 1])));
    }


    /**
     * test
     * @return void
     */
    public function testForceNumeric(){

        // Test empty values
        $this->assertNull(NumericUtils::forceNumeric(0));
        AssertUtils::throwsException(function() { NumericUtils::forceNumeric(null, 'somenull'); }, '/somenull must be numeric/');
        AssertUtils::throwsException(function() { NumericUtils::forceNumeric([], 'somearray'); }, '/somearray must be numeric/');
        AssertUtils::throwsException(function() { NumericUtils::forceNumeric('', 'somestring', 'some error message'); }, '/somestring some error message/');

        // Test ok values
        $this->assertNull(NumericUtils::forceNumeric(12123));
        $this->assertNull(NumericUtils::forceNumeric(-123123));
        $this->assertNull(NumericUtils::forceNumeric('123123'));
        $this->assertNull(NumericUtils::forceNumeric('123.11'));
        $this->assertNull(NumericUtils::forceNumeric('-123123'));

        // Test wrong values
        // Test exceptions
        AssertUtils::throwsException(function() { NumericUtils::forceNumeric('asdf'); }, '/must be numeric/');
        AssertUtils::throwsException(function() { NumericUtils::forceNumeric([1,2,3,4]); }, '/must be numeric/');
        AssertUtils::throwsException(function() { NumericUtils::forceNumeric(new stdClass()); }, '/must be numeric/');
    }


    /**
     * test
     * @return void
     */
    public function testForcePositiveInteger(){

        // Test empty values
        AssertUtils::throwsException(function() { NumericUtils::forcePositiveInteger(0); }, '/must be a positive integer/');
        AssertUtils::throwsException(function() { NumericUtils::forcePositiveInteger(null, 'somenull'); }, '/somenull must be a positive integer/');
        AssertUtils::throwsException(function() { NumericUtils::forcePositiveInteger([], 'somearray'); }, '/somearray must be a positive integer/');
        AssertUtils::throwsException(function() { NumericUtils::forcePositiveInteger('', 'somestring', 'some error message'); }, '/somestring some error message/');

        // Test ok values
        $this->assertNull(NumericUtils::forcePositiveInteger(1));
        $this->assertNull(NumericUtils::forcePositiveInteger(10));
        $this->assertNull(NumericUtils::forcePositiveInteger(1000));
        $this->assertNull(NumericUtils::forcePositiveInteger(12341234));
        $this->assertNull(NumericUtils::forcePositiveInteger(13453452345));
        $this->assertNull(NumericUtils::forcePositiveInteger('1'));
        $this->assertNull(NumericUtils::forcePositiveInteger('123'));

        // Test wrong values
        // Test exceptions
        AssertUtils::throwsException(function() { NumericUtils::forcePositiveInteger([1,2,3,4]); }, '/must be a positive integer/');
        AssertUtils::throwsException(function() { NumericUtils::forcePositiveInteger(new stdClass()); }, '/must be a positive integer/');
        AssertUtils::throwsException(function() { NumericUtils::forcePositiveInteger('erterwt'); }, '/must be a positive integer/');
        AssertUtils::throwsException(function() { NumericUtils::forcePositiveInteger(-100); }, '/must be a positive integer/');
        AssertUtils::throwsException(function() { NumericUtils::forcePositiveInteger(-10000); }, '/must be a positive integer/');
        AssertUtils::throwsException(function() { NumericUtils::forcePositiveInteger(10.56); }, '/must be a positive integer/');
        AssertUtils::throwsException(function() { NumericUtils::forcePositiveInteger(-10.56); }, '/must be a positive integer/');
    }


    /**
     * test
     * @return void
     */
    public function testGetNumeric(){

        // Test empty values
        $this->assertSame(0, NumericUtils::getNumeric(0));
        $this->assertSame(0, NumericUtils::getNumeric('0'));

        AssertUtils::throwsException(function() { NumericUtils::getNumeric(null); }, '/value is not numeric/');
        AssertUtils::throwsException(function() { NumericUtils::getNumeric(''); }, '/value is not numeric/');
        AssertUtils::throwsException(function() { NumericUtils::getNumeric([]); }, '/value is not numeric/');

        // Test ok values
        $this->assertSame(1, NumericUtils::getNumeric(1));
        $this->assertSame(10, NumericUtils::getNumeric(10));
        $this->assertSame(1123134, NumericUtils::getNumeric(1123134));
        $this->assertSame(1.1, NumericUtils::getNumeric(1.1));
        $this->assertSame(.1, NumericUtils::getNumeric(.1));
        $this->assertSame(0.00001, NumericUtils::getNumeric(0.00001));
        $this->assertSame(1.000001, NumericUtils::getNumeric(1.000001));
        $this->assertSame(-1, NumericUtils::getNumeric(-1));
        $this->assertSame(-10, NumericUtils::getNumeric(-10));
        $this->assertSame(-1123134, NumericUtils::getNumeric(-1123134));
        $this->assertSame(-1.1, NumericUtils::getNumeric(-1.1));
        $this->assertSame(-.1, NumericUtils::getNumeric(-.1));
        $this->assertSame(-0.00001, NumericUtils::getNumeric(-0.00001));
        $this->assertSame(-1.000001, NumericUtils::getNumeric(-1.000001));
        $this->assertSame(1, NumericUtils::getNumeric('1'));
        $this->assertSame(10, NumericUtils::getNumeric('10'));
        $this->assertSame(1123134, NumericUtils::getNumeric('1123134'));
        $this->assertSame(1.1, NumericUtils::getNumeric('1.1'));
        $this->assertSame(.1, NumericUtils::getNumeric('.1'));
        $this->assertSame(0.00001, NumericUtils::getNumeric('0.00001'));
        $this->assertSame(1.000001, NumericUtils::getNumeric('1.000001'));
        $this->assertSame(-1, NumericUtils::getNumeric('-1'));
        $this->assertSame(-10, NumericUtils::getNumeric('-10'));
        $this->assertSame(-1123134, NumericUtils::getNumeric('-1123134'));
        $this->assertSame(-1.1, NumericUtils::getNumeric('-1.1'));
        $this->assertSame(-.1, NumericUtils::getNumeric('-.1'));
        $this->assertSame(-0.00001, NumericUtils::getNumeric('-0.00001'));
        $this->assertSame(-1.000001, NumericUtils::getNumeric('-1.000001'));
        $this->assertSame(1, NumericUtils::getNumeric('  1 '));
        $this->assertSame(0.1, NumericUtils::getNumeric('  .1 '));
        $this->assertSame(-1, NumericUtils::getNumeric('  -1 '));
        $this->assertSame(6.5, NumericUtils::getNumeric('6,5'));
        $this->assertSame(6000.5, NumericUtils::getNumeric('6000,5'));
        $this->assertSame(6000.5, NumericUtils::getNumeric('6000.5'));
        $this->assertSame(6000.5, NumericUtils::getNumeric('6.000,5'));
        $this->assertSame(6000.5, NumericUtils::getNumeric('6,000.5'));
        $this->assertSame(1.4356, NumericUtils::getNumeric('1,4356'));
        $this->assertSame(12.345, NumericUtils::getNumeric('12,345'));
        $this->assertSame(12.345, NumericUtils::getNumeric('12.345'));
        $this->assertSame(12345.987, NumericUtils::getNumeric('12.345,987'));
        $this->assertSame(12345.987, NumericUtils::getNumeric('12.345.987'));
        $this->assertSame(12345.987, NumericUtils::getNumeric('12,345.987'));
        $this->assertSame(12345.987, NumericUtils::getNumeric('12345.987'));
        $this->assertSame(12345.987, NumericUtils::getNumeric('12345.987', '.'));
        $this->assertSame(12345987, NumericUtils::getNumeric('12345.987', ','));
        $this->assertSame(12345.987, NumericUtils::getNumeric('12,345.987', '.'));
        $this->assertSame(12345987, NumericUtils::getNumeric('12.345.987', ','));
        $this->assertSame(12345.987, NumericUtils::getNumeric('12.345,987', ','));
        $this->assertSame(1, NumericUtils::getNumeric('1', ','));
        $this->assertSame(-1, NumericUtils::getNumeric('-1', ','));
        $this->assertSame(0, NumericUtils::getNumeric('0', ','));
        $this->assertSame(1.1, NumericUtils::getNumeric('1,1', ','));
        $this->assertSame(0.1, NumericUtils::getNumeric('0,1', ','));
        $this->assertSame(10.000, NumericUtils::getNumeric('10,000', ','));
        $this->assertSame(10000, NumericUtils::getNumeric('10,000', '.'));

        // Test wrong values
        AssertUtils::throwsException(function() { NumericUtils::getNumeric('abc'); }, '/value is not numeric/');
        AssertUtils::throwsException(function() { NumericUtils::getNumeric('1-'); }, '/value is not numeric/');
        AssertUtils::throwsException(function() { NumericUtils::getNumeric('1-1'); }, '/value is not numeric/');
        AssertUtils::throwsException(function() { NumericUtils::getNumeric(['hello']); }, '/value is not numeric/');
        AssertUtils::throwsException(function() { NumericUtils::getNumeric('12.345,987', '.'); }, '/value is not numeric/');
        AssertUtils::throwsException(function() { NumericUtils::getNumeric('12.345.987', '.'); }, '/value is not numeric/');
        AssertUtils::throwsException(function() { NumericUtils::getNumeric('12,345,987', ','); }, '/value is not numeric/');
        AssertUtils::throwsException(function() { NumericUtils::getNumeric('12,345.987', ','); }, '/value is not numeric/');
        AssertUtils::throwsException(function() { NumericUtils::getNumeric('12,345.987', 'a'); }, '/Invalid decimal divider/');
    }


    /**
     * test
     * @return void
     */
    public function testGenerateRandomInteger(){

        // Test empty values
        AssertUtils::throwsException(function() { NumericUtils::generateRandomInteger(0, 0); }, '/max must be higher than min/');

        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            for ($j = 0; $j < $this->emptyValuesCount; $j++) {

                if($this->emptyValues[$i] !== 0 || $this->emptyValues[$j] !== 0){

                    AssertUtils::throwsException(function() use ($i, $j) {
                        NumericUtils::generateRandomInteger($this->emptyValues[$i], $this->emptyValues[$j]);
                    }, '/max and min must be integers/');
                }
            }
        }

        // Test ok values
        for ($i = 0; $i < 1000; $i+=100) {

            // Both positive
            $min = $i;
            $max = $i * 2 + 1;

            $val = NumericUtils::generateRandomInteger($min, $max);
            $this->assertTrue($val >= $min && $val <= $max);
            $this->assertTrue(NumericUtils::isInteger($val));

            // Both negative
            $min = - NumericUtils::generateRandomInteger($min, $max);
            $max = $min + NumericUtils::generateRandomInteger($i + 1, $i * 10 + 2);

            $val = NumericUtils::generateRandomInteger($min, $max);
            $this->assertTrue($val >= $min && $val <= $max);
            $this->assertTrue(NumericUtils::isInteger($val));

            // Negative min, positive max
            $min = -$i - 1;
            $max = $i * 2 + 1;

            $val = NumericUtils::generateRandomInteger($min, $max);
            $this->assertTrue($val >= $min && $val <= $max);
            $this->assertTrue(NumericUtils::isInteger($val));
        }

        // Test wrong values
        AssertUtils::throwsException(function() { NumericUtils::generateRandomInteger(10, 0); }, '/max must be higher than min/');
        AssertUtils::throwsException(function() { NumericUtils::generateRandomInteger(10, 10); }, '/max must be higher than min/');
        AssertUtils::throwsException(function() { NumericUtils::generateRandomInteger(-10, -20); }, '/max must be higher than min/');

        // Test exceptions
        $exceptionValues = [new Exception(), 'hello', .1, 1.1, [1, 2, 3, 4]];

        for ($i = 0; $i < count($exceptionValues); $i++) {

            for ($j = 0; $j < count($exceptionValues); $j++) {

                AssertUtils::throwsException(function() use ($exceptionValues, $i, $j) {
                    NumericUtils::getNumeric(NumericUtils::generateRandomInteger($exceptionValues[$i], $exceptionValues[$j]));
                }, '/max and min must be integers/');
            }
        }
    }
}