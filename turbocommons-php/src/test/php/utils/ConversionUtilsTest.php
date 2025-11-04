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
use org\turbocommons\src\main\php\utils\ConversionUtils;
use org\turbocommons\src\main\php\managers\ValidationManager;
use org\turbotesting\src\main\php\utils\AssertUtils;


/**
 * ConversionUtils tests
 *
 * @return void
 */
class ConversionUtilsTest extends TestCase {


    /**
     * testStringToBase64
     *
     * @return void
     */
    public function testStringToBase64(){

        $this->assertSame('', ConversionUtils::stringToBase64(null));
        $this->assertSame('', ConversionUtils::stringToBase64(''));

        // Try correct values
        $this->assertSame('Zg==', ConversionUtils::stringToBase64('f'));
        $this->assertSame('Zm8=', ConversionUtils::stringToBase64('fo'));
        $this->assertSame('Zm9v', ConversionUtils::stringToBase64('foo'));
        $this->assertSame('Zm9vYg==', ConversionUtils::stringToBase64('foob'));
        $this->assertSame('Zm9vYmE=', ConversionUtils::stringToBase64('fooba'));
        $this->assertSame('Zm9vYmFy', ConversionUtils::stringToBase64('foobar'));
        $this->assertSame('w4Bpw5N1dSEvKCk=', ConversionUtils::stringToBase64('ÀiÓuu!/()'));
        $this->assertSame('5L2g5aW95LiW55WM', ConversionUtils::stringToBase64('你好世界'));
        $this->assertSame('bGluZTEKbGluZTIKbGluZTM=', ConversionUtils::stringToBase64("line1\nline2\nline3"));
        $this->assertSame('eyAwLCAxLCAyLCAzLCA0LCA1LCA2LCA3LCA4LCA5IH0=', ConversionUtils::stringToBase64('{ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 }'));
        $this->assertSame('QWFCYkNjRGRFZUZmR2dIaElpSmpLa0xsTW1Obk9vUHBRcVJyU3NUdFV1VnZXd1h4WXlaeg==', ConversionUtils::stringToBase64('AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz'));

        AssertUtils::throwsException(function() { ConversionUtils::stringToBase64([]); }, '/value is not a string/');
        AssertUtils::throwsException(function() { ConversionUtils::stringToBase64(98345); }, '/value is not a string/');
        AssertUtils::throwsException(function() { ConversionUtils::stringToBase64(new ValidationManager()); }, '/value is not a string/');
    }


    /**
     * testBase64ToString
     *
     * @return void
     */
    public function testBase64ToString(){

        $this->assertSame('', ConversionUtils::base64ToString(null));
        $this->assertSame('', ConversionUtils::base64ToString(''));

        // Try correct values
        $this->assertSame('f', ConversionUtils::base64ToString('Zg=='));
        $this->assertSame('fo', ConversionUtils::base64ToString('Zm8='));
        $this->assertSame('foo', ConversionUtils::base64ToString('Zm9v'));
        $this->assertSame('foob', ConversionUtils::base64ToString('Zm9vYg=='));
        $this->assertSame('fooba', ConversionUtils::base64ToString('Zm9vYmE='));
        $this->assertSame('foobar', ConversionUtils::base64ToString('Zm9vYmFy'));
        $this->assertSame('ÀiÓuu!/()', ConversionUtils::base64ToString('w4Bpw5N1dSEvKCk='));
        $this->assertSame('你好世界', ConversionUtils::base64ToString('5L2g5aW95LiW55WM'));

        // Try some random values encoded with stringToBase64
        for ($i = 0; $i < 50; $i++) {

            $s = substr(sha1(rand()), 0, 20);

            $this->assertSame($s, ConversionUtils::base64ToString(ConversionUtils::stringToBase64($s)));
        }

        // Try some wrong values
        AssertUtils::throwsException(function() { ConversionUtils::base64ToString([]); }, '/value is not a string/');
        AssertUtils::throwsException(function() { ConversionUtils::base64ToString(98345); }, '/value is not a string/');
        AssertUtils::throwsException(function() { ConversionUtils::base64ToString(new ValidationManager()); }, '/value is not a string/');
    }
}