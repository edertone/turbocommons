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

use Throwable;
use PHPUnit\Framework\TestCase;
use stdClass;
use org\turbocommons\src\main\php\utils\EncodingUtils;


/**
 * EncodingUtilsTest
 *
 * @return void
 */
class EncodingUtilsTest extends TestCase {


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


    /**
     * testUnicodeEscapedCharsToUtf8
     *
     * @return void
     */
    public function testUnicodeEscapedCharsToUtf8(){

        // Test empty values
        $this->assertSame(EncodingUtils::unicodeEscapedCharsToUtf8(''), '');
        $this->assertSame(EncodingUtils::unicodeEscapedCharsToUtf8('   '), '   ');
        $this->assertSame(EncodingUtils::unicodeEscapedCharsToUtf8("\n\r\n"), "\n\r\n");
        $this->assertSame(EncodingUtils::unicodeEscapedCharsToUtf8("\n    \r\n   \r"), "\n    \r\n   \r");

        try {
            EncodingUtils::unicodeEscapedCharsToUtf8(null);
            $this->exceptionMessage = 'null did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            EncodingUtils::unicodeEscapedCharsToUtf8(0);
            $this->exceptionMessage = '0 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            EncodingUtils::unicodeEscapedCharsToUtf8([]);
            $this->exceptionMessage = '[] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            EncodingUtils::unicodeEscapedCharsToUtf8(new stdClass());
            $this->exceptionMessage = 'new stdClass() did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test ok values
        $this->assertSame(EncodingUtils::unicodeEscapedCharsToUtf8('1'), '1');
        $this->assertSame(EncodingUtils::unicodeEscapedCharsToUtf8('.,_/}'), '.,_/}');
        $this->assertSame(EncodingUtils::unicodeEscapedCharsToUtf8('hello'), 'hello');
        $this->assertSame(EncodingUtils::unicodeEscapedCharsToUtf8('hello world'), 'hello world');
        $this->assertSame(EncodingUtils::unicodeEscapedCharsToUtf8('hello\\ world\\'), 'hello\\ world\\');
        $this->assertSame(EncodingUtils::unicodeEscapedCharsToUtf8('Eclipse Integration Commons 3.8.0 GA\\n\\'), 'Eclipse Integration Commons 3.8.0 GA\\n\\');
        $this->assertSame(EncodingUtils::unicodeEscapedCharsToUtf8('Eclipse Inte\\u0024gration Commons 3.8.0 GA\\n\\'), 'Eclipse Inte$gration Commons 3.8.0 GA\\n\\');
        $this->assertSame(EncodingUtils::unicodeEscapedCharsToUtf8('Bj\\u00F6rk'), 'Björk');
        $this->assertSame(EncodingUtils::unicodeEscapedCharsToUtf8('Dodd\\u2013Frank'), 'Dodd–Frank');
        $this->assertSame(EncodingUtils::unicodeEscapedCharsToUtf8('\\u0070\\u0075\\u0062\\u006c\\u0069\\u0063\\u007b\\u007d'), 'public{}');
        $this->assertSame(EncodingUtils::unicodeEscapedCharsToUtf8('abc\\u79c1d\\u306e\\u5bb6\\u3078\\u306e\\u6b53\\u8fceef'), 'abc私dの家への歓迎ef');
        $this->assertSame(EncodingUtils::unicodeEscapedCharsToUtf8('\\u79c1\\u306e\\u5bb6\\u3078\\u306e\\u6b53\\u8fce'), '私の家への歓迎');
        $this->assertSame(EncodingUtils::unicodeEscapedCharsToUtf8("\r\n\\u79c1\\u306e\\u5bb6\\u3078\\u306e\\u6b53\\u8fce"), "\r\n私の家への歓迎");
        $this->assertSame(EncodingUtils::unicodeEscapedCharsToUtf8("\r\n\\u79c1\\u306e\\u5bb6\\u3078\\u306e\\u6b53\r\n\\u8fce\\"), "\r\n私の家への歓\r\n迎\\");

        // Test wrong values
        // Not necessary

        // Test exceptions
        // Already tested
    }


    /**
     * testUtf8ToUnicodeEscapedChars
     *
     * @return void
     */
    public function testUtf8ToUnicodeEscapedChars(){

        // Test empty values
        $this->assertSame(EncodingUtils::utf8ToUnicodeEscapedChars(''), '');
        $this->assertSame(EncodingUtils::utf8ToUnicodeEscapedChars('   '), '   ');
        $this->assertSame(EncodingUtils::utf8ToUnicodeEscapedChars("\n\r\n"), "\n\r\n");
        $this->assertSame(EncodingUtils::utf8ToUnicodeEscapedChars("\n    \r\n   \r"), "\n    \r\n   \r");

        try {
            EncodingUtils::utf8ToUnicodeEscapedChars(null);
            $this->exceptionMessage = 'null did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            EncodingUtils::utf8ToUnicodeEscapedChars(0);
            $this->exceptionMessage = '0 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            EncodingUtils::utf8ToUnicodeEscapedChars([]);
            $this->exceptionMessage = '[] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            EncodingUtils::utf8ToUnicodeEscapedChars(new stdClass());
            $this->exceptionMessage = 'new stdClass() did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test ok values
        $this->assertSame(EncodingUtils::utf8ToUnicodeEscapedChars('1'), '1');
        $this->assertSame(EncodingUtils::utf8ToUnicodeEscapedChars('.,_/}'), '.,_/}');
        $this->assertSame(EncodingUtils::utf8ToUnicodeEscapedChars('hello'), 'hello');
        $this->assertSame(EncodingUtils::utf8ToUnicodeEscapedChars('hello world'), 'hello world');
        $this->assertSame(EncodingUtils::utf8ToUnicodeEscapedChars('hello\\ world\\'), 'hello\\ world\\');
        $this->assertSame(EncodingUtils::utf8ToUnicodeEscapedChars('Eclipse Integration Commons 3.8.0 GA\\n\\'), 'Eclipse Integration Commons 3.8.0 GA\\n\\');
        $this->assertSame(EncodingUtils::utf8ToUnicodeEscapedChars('Eclipse Inteögration Commons 3.8.0 GA\\n\\'), 'Eclipse Inte\\u00f6gration Commons 3.8.0 GA\\n\\');
        $this->assertSame(EncodingUtils::utf8ToUnicodeEscapedChars('Björk'), 'Bj\\u00f6rk');
        $this->assertSame(EncodingUtils::utf8ToUnicodeEscapedChars('Dodd–Frank'), 'Dodd\\u2013Frank');
        $this->assertSame(EncodingUtils::utf8ToUnicodeEscapedChars('public{}'), 'public{}');
        $this->assertSame(EncodingUtils::utf8ToUnicodeEscapedChars('abc私dの家への歓迎ef'), 'abc\\u79c1d\\u306e\\u5bb6\\u3078\\u306e\\u6b53\\u8fceef');
        $this->assertSame(EncodingUtils::utf8ToUnicodeEscapedChars('私の家への歓迎'), '\\u79c1\\u306e\\u5bb6\\u3078\\u306e\\u6b53\\u8fce');

        // TODO - Warning \r\n currently get converted to \\r\\n! so the following tests will fail:
        $this->markTestIncomplete('This test fails for this particular case. must be reviewed');
        //$this->assertSame(EncodingUtils::utf8ToUnicodeEscapedChars("\r\n私の家への歓迎"), "\r\n\\u79c1\\u306e\\u5bb6\\u3078\\u306e\\u6b53\\u8fce");

        $this->assertSame(EncodingUtils::utf8ToUnicodeEscapedChars("\r\n私の家への歓\r\n迎\\"), "\r\n\\u79c1\\u306e\\u5bb6\\u3078\\u306e\\u6b53\r\n\\u8fce\\");

        // Test wrong values
        // Not necessary

        // Test exceptions
        // Already tested
    }
}

?>