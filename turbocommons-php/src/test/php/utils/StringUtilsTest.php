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

use org\turbocommons\src\main\php\utils\StringUtils;
use org\turbocommons\src\main\php\utils\NumericUtils;
use Throwable;
use stdClass;
use Exception;
use PHPUnit\Framework\TestCase;
use org\turbotesting\src\main\php\utils\AssertUtils;


/**
 * Stringutils tests
 *
 * @return void
 */
class StringUtilsTest extends TestCase {


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
     * testIsString
     *
     * @return void
     */
    public function testIsString(){

        $this->assertTrue(StringUtils::isString(''));
        $this->assertTrue(StringUtils::isString('      '));
        $this->assertTrue(StringUtils::isString('1'));
        $this->assertTrue(StringUtils::isString('a'));
        $this->assertTrue(StringUtils::isString('hello'));
        $this->assertTrue(StringUtils::isString("hello\n\nguys"));

        $this->assertTrue(!StringUtils::isString(null));
        $this->assertTrue(!StringUtils::isString(0));
        $this->assertTrue(!StringUtils::isString(15));
        $this->assertTrue(!StringUtils::isString([]));
        $this->assertTrue(!StringUtils::isString([1]));
        $this->assertTrue(!StringUtils::isString(['a', 'cd']));
        $this->assertTrue(!StringUtils::isString(new stdClass()));
        $this->assertTrue(!StringUtils::isString(new Exception()));
    }


    /** test */
    public function testForceString(){

        StringUtils::forceString('');
        StringUtils::forceString('      ');
        StringUtils::forceString('1');
        StringUtils::forceString('a');
        StringUtils::forceString('hello');
        StringUtils::forceString("hello\n\nguys");

        AssertUtils::throwsException(function() { StringUtils::forceString(null, 'null'); }, '/null must be a string/');
        AssertUtils::throwsException(function() { StringUtils::forceString(0, '0'); }, '/0 must be a string/');
        AssertUtils::throwsException(function() { StringUtils::forceString(15, '15'); }, '/15 must be a string/');
        AssertUtils::throwsException(function() { StringUtils::forceString([], 'array'); }, '/array must be a string/');
        AssertUtils::throwsException(function() { StringUtils::forceString([1], 'array'); }, '/array must be a string/');
        AssertUtils::throwsException(function() { StringUtils::forceString(['a', 'cd'], "array"); }, "/array must be a string/");
        AssertUtils::throwsException(function() { StringUtils::forceString(new stdClass(), 'new stdClass'); }, '/new stdClass must be a string/');
        AssertUtils::throwsException(function() { StringUtils::forceString(new Exception(), 'new Exception'); }, '/new Exception must be a string/');
    }


    /**
     * testIsUrl
     *
     * @return void
     */
    public function testIsUrl(){

        // Wrong url cases
        $this->assertFalse(StringUtils::isUrl(''));
        $this->assertFalse(StringUtils::isUrl(null));
        $this->assertFalse(StringUtils::isUrl([]));
        $this->assertFalse(StringUtils::isUrl('    '));
        $this->assertFalse(StringUtils::isUrl('123f56ccaca'));
        $this->assertFalse(StringUtils::isUrl('8/%$144///(!(/"'));
        $this->assertFalse(StringUtils::isUrl('http'));
        $this->assertFalse(StringUtils::isUrl('x.y'));
        $this->assertFalse(StringUtils::isUrl('http://x.y'));
        $this->assertFalse(StringUtils::isUrl('google.com-'));
        $this->assertFalse(StringUtils::isUrl("\n   \t\n"));
        $this->assertFalse(StringUtils::isUrl('./test/file.js'));
        $this->assertFalse(StringUtils::isUrl('http:\\google.com'));
        $this->assertFalse(StringUtils::isUrl('_http://google.com'));
        $this->assertFalse(StringUtils::isUrl('http://www.example..com'));
        $this->assertFalse(StringUtils::isUrl('http://.com'));
        $this->assertFalse(StringUtils::isUrl('http://www.example.'));
        $this->assertFalse(StringUtils::isUrl('http:/www.example.com'));
        $this->assertFalse(StringUtils::isUrl('http://'));
        $this->assertFalse(StringUtils::isUrl('http://.'));
        $this->assertFalse(StringUtils::isUrl('http://??/'));
        $this->assertFalse(StringUtils::isUrl('http://foo.bar?q=Spaces should be encoded'));
        $this->assertFalse(StringUtils::isUrl('rdar://1234'));
        $this->assertFalse(StringUtils::isUrl('http://foo.bar/foo(bar)baz quux'));
        $this->assertFalse(StringUtils::isUrl('http://10.1.1.255'));
        $this->assertFalse(StringUtils::isUrl('http://.www.foo.bar./'));
        $this->assertFalse(StringUtils::isUrl('http://.www.foo.bar/'));
        $this->assertFalse(StringUtils::isUrl('ftp://user:password@host:port/path'));
        $this->assertFalse(StringUtils::isUrl('/nfs/an/disks/jj/home/dir/file.txt'));
        $this->assertFalse(StringUtils::isUrl('C:\\Program Files (x86)'));
        $this->assertFalse(StringUtils::isUrl('http://www.google.com\\test.html'));

        // good url cases
        $this->assertTrue(StringUtils::isUrl('http://x.ye'));
        $this->assertTrue(StringUtils::isUrl('http://google.com'));
        $this->assertTrue(StringUtils::isUrl('ftp://mydomain.com'));
        $this->assertTrue(StringUtils::isUrl('http://www.example.com:8800'));
        $this->assertTrue(StringUtils::isUrl('http://www.example.com/a/b/c/d/e/f/g/h/i.html'));
        $this->assertTrue(StringUtils::isUrl('http://www.test.com/do.html#A'));
        $this->assertTrue(StringUtils::isUrl('https://subdomain.test.com/'));
        $this->assertTrue(StringUtils::isUrl('https://test.com'));
        $this->assertTrue(StringUtils::isUrl('http://foo.com/blah_blah/'));
        $this->assertTrue(StringUtils::isUrl('https://www.example.com/foo/?bar=baz&inga=42&quux'));
        $this->assertTrue(StringUtils::isUrl('http://userid@example.com:8080'));
        $this->assertTrue(StringUtils::isUrl('http://➡.ws/䨹'));
        $this->assertTrue(StringUtils::isUrl('http://⌘.ws/'));
        $this->assertTrue(StringUtils::isUrl('http://foo.bar/?q=Test%20URL-encoded%20stuff'));
        $this->assertTrue(StringUtils::isUrl('http://-.~_!$&\'()*+,;=:%40:80%2f::::::@example.com'));
        $this->assertTrue(StringUtils::isUrl('http://223.255.255.254'));
        $this->assertTrue(StringUtils::isUrl('ftp://user:password@host.com:8080/path'));
        $this->assertTrue(StringUtils::isUrl('http://www.google.com/test.html?a=1'));
        $this->assertTrue(StringUtils::isUrl('http://www.google.com/test.html?a=1&b=2'));
        $this->assertTrue(StringUtils::isUrl('http://www.google.com/test.html?a=1&b=2?c=3'));
        $this->assertTrue(StringUtils::isUrl('http://www.google.com/test.html?a=1&b=2?????'));
        // TODO - this test does not pass, but it does pass in JS. We should look for another regex in PHP that passes it also
        // $this->assertTrue(StringUtils::isUrl('http://www.test.com?pageid=123&testid=1524'));

        // Test non string values throw exceptions
        try {
            StringUtils::isUrl([12341]);
            $this->exceptionMessage = '[12341] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::isUrl(12341);
            $this->exceptionMessage = '12341 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }
    }


    /**
     * testIsEmpty
     *
     * @return void
     */
    public function testIsEmpty(){

        $this->assertTrue(StringUtils::isEmpty(null));
        $this->assertTrue(StringUtils::isEmpty(0));
        $this->assertTrue(StringUtils::isEmpty(''));
        $this->assertTrue(StringUtils::isEmpty([]));
        $this->assertTrue(StringUtils::isEmpty('      '));
        $this->assertTrue(StringUtils::isEmpty("\n\n  \n"));
        $this->assertTrue(StringUtils::isEmpty("\t   \n     \r\r"));
        $this->assertTrue(StringUtils::isEmpty('EMPTY', ['EMPTY']));
        $this->assertTrue(StringUtils::isEmpty('EMPTY       void   hole    ', ['EMPTY', 'void', 'hole']));

        $this->assertTrue(!StringUtils::isEmpty('adsadf'));
        $this->assertTrue(!StringUtils::isEmpty('    sdfasdsf'));
        $this->assertTrue(!StringUtils::isEmpty('EMPTY'));
        $this->assertTrue(!StringUtils::isEmpty('EMPTY test', ['EMPTY']));
        $this->assertTrue(!StringUtils::isEmpty('EMPTY       void   hole    XX', ['EMPTY', 'void', 'hole']));

        // Test non string value gives exception
        AssertUtils::throwsException(function() { StringUtils::isEmpty(123); }, '/value is not a string/');
    }


    /**
     * isStartingWith
     *
     * @return void
     */
    public function testIsStartingWith(){

        // Test empty values
        AssertUtils::throwsException(function() { StringUtils::isStartingWith(null, null); }, '/must be of the type array, null given/');
        AssertUtils::throwsException(function() { StringUtils::isStartingWith(0, 0); }, '/must be of the type array, int given/');
        $this->assertFalse(StringUtils::isStartingWith('', []));
        $this->assertTrue(StringUtils::isStartingWith('', ['']));
        $this->assertTrue(StringUtils::isStartingWith('a', ['']));

        // Test ok values
        $this->assertTrue(StringUtils::isStartingWith('a', ['a']));
        $this->assertTrue(StringUtils::isStartingWith('abc', ['a']));
        $this->assertFalse(StringUtils::isStartingWith('abc', ['b']));
        $this->assertTrue(StringUtils::isStartingWith('abcde', ['ab']));
        $this->assertTrue(StringUtils::isStartingWith('abcde', ['abcd']));

        // Test wrong values
        // Not necessary

        // Test exceptions
        AssertUtils::throwsException(function() { StringUtils::isStartingWith([1, 2, 3, 4], 0); }, '/must be of the type array, int given/');
        AssertUtils::throwsException(function() { StringUtils::isStartingWith('', 0); }, '/must be of the type array, int given/');
        AssertUtils::throwsException(function() { StringUtils::isStartingWith('', ''); }, '/must be of the type array, string given/');
    }


    /**
     * isEndingWith
     *
     * @return void
     */
    public function testIsEndingWith(){

        // Test empty values
        AssertUtils::throwsException(function() { StringUtils::isEndingWith(null, null); }, '/must be of the type array, null given/');
        AssertUtils::throwsException(function() { StringUtils::isEndingWith(0, 0); }, '/must be of the type array, int given/');
        $this->assertFalse(StringUtils::isEndingWith('', []));
        $this->assertTrue(StringUtils::isEndingWith('', ['']));
        $this->assertTrue(StringUtils::isEndingWith('a', ['']));

        // Test ok values
        $this->assertTrue(StringUtils::isEndingWith('a', ['a']));
        $this->assertTrue(StringUtils::isEndingWith('abc', ['c']));
        $this->assertFalse(StringUtils::isEndingWith('abc', ['b']));
        $this->assertTrue(StringUtils::isEndingWith('abcde', ['de']));
        $this->assertTrue(StringUtils::isEndingWith('abcde', ['bcde']));

        // Test wrong values
        // Not necessary

        // Test exceptions
        AssertUtils::throwsException(function() { StringUtils::isEndingWith([1, 2, 3, 4], 0); }, '/must be of the type array, int given/');
        AssertUtils::throwsException(function() { StringUtils::isEndingWith('', 0); }, '/must be of the type array, int given/');
        AssertUtils::throwsException(function() { StringUtils::isEndingWith('', ''); }, '/must be of the type array, string given/');
    }


    /** test */
    public function testForceNonEmptyString(){

        AssertUtils::throwsException(function() { StringUtils::forceNonEmptyString(null); }, '/must be a non empty string/');
        AssertUtils::throwsException(function() { StringUtils::forceNonEmptyString(0); }, '/must be a non empty string/');
        AssertUtils::throwsException(function() { StringUtils::forceNonEmptyString(''); }, '/must be a non empty string/');
        AssertUtils::throwsException(function() { StringUtils::forceNonEmptyString([]); }, '/must be a non empty string/');
        AssertUtils::throwsException(function() { StringUtils::forceNonEmptyString('      '); }, '/must be a non empty string/');
        AssertUtils::throwsException(function() { StringUtils::forceNonEmptyString("\n\n  \n"); }, '/must be a non empty string/');
        AssertUtils::throwsException(function() { StringUtils::forceNonEmptyString("\t   \n     \r\r"); }, '/must be a non empty string/');

        StringUtils::forceNonEmptyString('adsadf');
        StringUtils::forceNonEmptyString('    sdfasdsf');
        StringUtils::forceNonEmptyString('EMPTY');

        // Test non string value gives exception
        AssertUtils::throwsException(function() { StringUtils::forceNonEmptyString(123); }, '/must be a non empty string/');
    }


    /**
     * testIsCamelCase
     *
     * @return void
     */
    public function testIsCamelCase(){

        // test empty cases
        $this->assertTrue(!StringUtils::isCamelCase(null));
        $this->assertTrue(!StringUtils::isCamelCase(''));
        $this->assertTrue(!StringUtils::isCamelCase([]));
        $this->assertTrue(!StringUtils::isCamelCase('       '));
        $this->assertTrue(!StringUtils::isCamelCase("\n\n\n"));

        // Test correct FORMAT_CAMEL_CASE strings
        $this->assertTrue(StringUtils::isCamelCase('c'));
        $this->assertTrue(StringUtils::isCamelCase('camel'));
        $this->assertTrue(StringUtils::isCamelCase('Hello'));
        $this->assertTrue(StringUtils::isCamelCase('CamelCase'));
        $this->assertTrue(StringUtils::isCamelCase('camelCase'));
        $this->assertTrue(StringUtils::isCamelCase('CamelCCase'));
        $this->assertTrue(StringUtils::isCamelCase('CamelCA'));
        $this->assertTrue(StringUtils::isCamelCase('IFoo'));
        $this->assertTrue(StringUtils::isCamelCase('HTTPConnection'));
        $this->assertTrue(StringUtils::isCamelCase('CAMELcA'));
        $this->assertTrue(StringUtils::isCamelCase('FooBarFizzBuzz'));
        $this->assertTrue(StringUtils::isCamelCase('Camel01C'));
        $this->assertTrue(StringUtils::isCamelCase('Camel01C01'));
        $this->assertTrue(StringUtils::isCamelCase('Camel0a1C1'));
        $this->assertTrue(StringUtils::isCamelCase('Camel0ac1b1C1'));
        $this->assertTrue(StringUtils::isCamelCase('CamelC'));
        $this->assertTrue(StringUtils::isCamelCase('CamelC1'));
        $this->assertTrue(StringUtils::isCamelCase('CamelCa1'));
        $this->assertTrue(StringUtils::isCamelCase('IbsReleaseTestVerificationRegressionSuite'));
        $this->assertTrue(StringUtils::isCamelCase('IbsReleaseTestVerificationRegressioN'));

        // Test incorrect FORMAT_CAMEL_CASE strings
        $this->assertTrue(!StringUtils::isCamelCase('0'));
        $this->assertTrue(!StringUtils::isCamelCase('Camel01.CC01'));
        $this->assertTrue(!StringUtils::isCamelCase('Camel0a1c1'));
        $this->assertTrue(!StringUtils::isCamelCase('Camel_Case'));
        $this->assertTrue(!StringUtils::isCamelCase('CAMELCASE'));
        $this->assertTrue(!StringUtils::isCamelCase('CCC'));
        $this->assertTrue(!StringUtils::isCamelCase('123123'));
        $this->assertTrue(!StringUtils::isCamelCase('_=CA'));
        $this->assertTrue(!StringUtils::isCamelCase('Camel01c01'));
        $this->assertTrue(!StringUtils::isCamelCase('001Camel'));
        $this->assertTrue(!StringUtils::isCamelCase('.CamelCase'));
        $this->assertTrue(!StringUtils::isCamelCase('CamelCase.'));
        $this->assertTrue(!StringUtils::isCamelCase('CamelCa_1'));
        $this->assertTrue(!StringUtils::isCamelCase('cámel.Case'));
        $this->assertTrue(!StringUtils::isCamelCase('ü'));
        $this->assertTrue(!StringUtils::isCamelCase('ÚMEL'));

        // Test correct FORMAT_UPPER_CAMEL_CASE strings
        $this->assertTrue(StringUtils::isCamelCase('CamelCase', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('CamelCASE', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('CamelCCase', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('IFoo', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('HTTPConnection', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('CAMELcA', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('FooBarFizzBuzz', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('Camel01C', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('Camel01C01', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('Camel0a1C1', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('Camel0ac1b1C1', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('CamelC', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('CamelC1', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('CamelCa1', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('IbsReleaseTestVerificationRegressionSuite', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('IbsReleaseTestVerificationRegressioN', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('CamelCA', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('Camel', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('C', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('Ú', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('Úmel', StringUtils::FORMAT_UPPER_CAMEL_CASE));

        // Test incorrect FORMAT_UPPER_CAMEL_CASE strings
        $this->assertTrue(!StringUtils::isCamelCase('camelCase', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('camelcase', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('CAMELCASE', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('123123', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('9/yth1', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('Camel01.CC01', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('Camel0a1c1', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('Camel_Case', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('CCC', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('_=CA', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('Camel01c01', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('001Camel', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('.CamelCase', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('CamelCase.', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('CamelCa_1', StringUtils::FORMAT_UPPER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('cümelCasè', StringUtils::FORMAT_UPPER_CAMEL_CASE));


        // Test correct FORMAT_LOWER_CAMEL_CASE strings
        $this->assertTrue(StringUtils::isCamelCase('camelCase', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('camelCASE', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('camelCCase', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('iFoo', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('hTTPConnection', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('cAMELcA', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('fooBarFizzBuzz', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('camel01C', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('camel01C01', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('camel0a1C1', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('camel0ac1b1C1', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('camelC', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('camelC1', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('camelCa1', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('ibsReleaseTestVerificationRegressionSuite', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('ibsReleaseTestVerificationRegressioN', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('camelCA', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(StringUtils::isCamelCase('camel', StringUtils::FORMAT_LOWER_CAMEL_CASE));

        // Test incorrect FORMAT_LOWER_CAMEL_CASE strings
        $this->assertTrue(!StringUtils::isCamelCase('CamelCase', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('___', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('3456346', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('****', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('_cAMEL', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('HTTPConnection', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('C', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('CAMELCASE', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('123123', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('9/yth1', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('Camel01.CC01', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('Camel0a1c1', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('Camel_Case', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('CCC', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('_=CA', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('Camel01c01', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('001Camel', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('.CamelCase', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('CamelCase.', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('CamelCa_1', StringUtils::FORMAT_LOWER_CAMEL_CASE));
        $this->assertTrue(!StringUtils::isCamelCase('Úamel', StringUtils::FORMAT_LOWER_CAMEL_CASE));

        // Test exceptions
        AssertUtils::throwsException(function() { StringUtils::isCamelCase(123); }, '/value is not a string/');
        AssertUtils::throwsException(function() { StringUtils::isCamelCase([1,5,8]); }, '/value is not a string/');
        AssertUtils::throwsException(function() { StringUtils::isCamelCase(new Exception()); }, '/value is not a string/');
        AssertUtils::throwsException(function() { StringUtils::isCamelCase('CamelCase', 67); }, '/Unknown type specified/');
    }


    /**
     * testIsSnakeCase
     *
     * @return void
     */
    public function testIsSnakeCase(){

        // test empty cases
        $this->assertTrue(!StringUtils::isSnakeCase(null));
        $this->assertTrue(!StringUtils::isSnakeCase(''));
        $this->assertTrue(!StringUtils::isSnakeCase([]));
        $this->assertTrue(!StringUtils::isSnakeCase('       '));
        $this->assertTrue(!StringUtils::isSnakeCase("\n\n\n"));

        // Test correct FORMAT_SNAKE_CASE strings
        $this->assertTrue(StringUtils::isSnakeCase('snake'));
        $this->assertTrue(StringUtils::isSnakeCase('snake_case'));
        $this->assertTrue(StringUtils::isSnakeCase('Snake'));
        $this->assertTrue(StringUtils::isSnakeCase('SNAKE'));
        $this->assertTrue(StringUtils::isSnakeCase('Snake_case'));
        $this->assertTrue(StringUtils::isSnakeCase('SNAKE_case'));
        $this->assertTrue(StringUtils::isSnakeCase('SNAKE12_case4'));
        $this->assertTrue(StringUtils::isSnakeCase('c_s'));
        $this->assertTrue(StringUtils::isSnakeCase('c'));
        $this->assertTrue(StringUtils::isSnakeCase('C12'));
        $this->assertTrue(StringUtils::isSnakeCase('1A_2'));

        // Test incorrect FORMAT_SNAKE_CASE strings
        $this->assertTrue(!StringUtils::isSnakeCase('_'));
        $this->assertTrue(!StringUtils::isSnakeCase('___'));
        $this->assertTrue(!StringUtils::isSnakeCase('!"(·'));
        $this->assertTrue(!StringUtils::isSnakeCase('1212'));
        $this->assertTrue(!StringUtils::isSnakeCase('_te'));
        $this->assertTrue(!StringUtils::isSnakeCase('_SNAKE'));
        $this->assertTrue(!StringUtils::isSnakeCase('snake_'));
        $this->assertTrue(!StringUtils::isSnakeCase('snake-case'));
        $this->assertTrue(!StringUtils::isSnakeCase('snake_case.'));
        $this->assertTrue(!StringUtils::isSnakeCase('1_2'));
        $this->assertTrue(!StringUtils::isSnakeCase('snake__case'));

        // Test correct FORMAT_UPPER_SNAKE_CASE strings
        $this->assertTrue(StringUtils::isSnakeCase('SNAKE', StringUtils::FORMAT_UPPER_SNAKE_CASE));
        $this->assertTrue(StringUtils::isSnakeCase('SNAKE_CASE', StringUtils::FORMAT_UPPER_SNAKE_CASE));
        $this->assertTrue(StringUtils::isSnakeCase('SNAKE12_CASE4', StringUtils::FORMAT_UPPER_SNAKE_CASE));
        $this->assertTrue(StringUtils::isSnakeCase('C_S', StringUtils::FORMAT_UPPER_SNAKE_CASE));
        $this->assertTrue(StringUtils::isSnakeCase('C', StringUtils::FORMAT_UPPER_SNAKE_CASE));
        $this->assertTrue(StringUtils::isSnakeCase('C12', StringUtils::FORMAT_UPPER_SNAKE_CASE));
        $this->assertTrue(StringUtils::isSnakeCase('1A_2', StringUtils::FORMAT_UPPER_SNAKE_CASE));

        // Test incorrect FORMAT_UPPER_SNAKE_CASE strings
        $this->assertTrue(!StringUtils::isSnakeCase('_', StringUtils::FORMAT_UPPER_SNAKE_CASE));
        $this->assertTrue(!StringUtils::isSnakeCase('___', StringUtils::FORMAT_UPPER_SNAKE_CASE));
        $this->assertTrue(!StringUtils::isSnakeCase('!"(·', StringUtils::FORMAT_UPPER_SNAKE_CASE));
        $this->assertTrue(!StringUtils::isSnakeCase('1212', StringUtils::FORMAT_UPPER_SNAKE_CASE));
        $this->assertTrue(!StringUtils::isSnakeCase('_te', StringUtils::FORMAT_UPPER_SNAKE_CASE));
        $this->assertTrue(!StringUtils::isSnakeCase('_SNAKE', StringUtils::FORMAT_UPPER_SNAKE_CASE));
        $this->assertTrue(!StringUtils::isSnakeCase('SNAKE_', StringUtils::FORMAT_UPPER_SNAKE_CASE));
        $this->assertTrue(!StringUtils::isSnakeCase('SNAKE-CASE', StringUtils::FORMAT_UPPER_SNAKE_CASE));
        $this->assertTrue(!StringUtils::isSnakeCase('SNAKE_CASE.', StringUtils::FORMAT_UPPER_SNAKE_CASE));
        $this->assertTrue(!StringUtils::isSnakeCase('1_2', StringUtils::FORMAT_UPPER_SNAKE_CASE));
        $this->assertTrue(!StringUtils::isSnakeCase('SNAKE__CASE', StringUtils::FORMAT_UPPER_SNAKE_CASE));

        // Test correct FORMAT_LOWER_SNAKE_CASE strings
        $this->assertTrue(StringUtils::isSnakeCase('snake', StringUtils::FORMAT_LOWER_SNAKE_CASE));
        $this->assertTrue(StringUtils::isSnakeCase('snake_case', StringUtils::FORMAT_LOWER_SNAKE_CASE));
        $this->assertTrue(StringUtils::isSnakeCase('snake12_case4', StringUtils::FORMAT_LOWER_SNAKE_CASE));
        $this->assertTrue(StringUtils::isSnakeCase('c_s', StringUtils::FORMAT_LOWER_SNAKE_CASE));
        $this->assertTrue(StringUtils::isSnakeCase('c', StringUtils::FORMAT_LOWER_SNAKE_CASE));
        $this->assertTrue(StringUtils::isSnakeCase('c12', StringUtils::FORMAT_LOWER_SNAKE_CASE));
        $this->assertTrue(StringUtils::isSnakeCase('1a_2', StringUtils::FORMAT_LOWER_SNAKE_CASE));

        // Test imcorrect FORMAT_LOWER_SNAKE_CASE strings
        $this->assertTrue(!StringUtils::isSnakeCase('_', StringUtils::FORMAT_LOWER_SNAKE_CASE));
        $this->assertTrue(!StringUtils::isSnakeCase('___', StringUtils::FORMAT_LOWER_SNAKE_CASE));
        $this->assertTrue(!StringUtils::isSnakeCase('!"(·', StringUtils::FORMAT_LOWER_SNAKE_CASE));
        $this->assertTrue(!StringUtils::isSnakeCase('1212', StringUtils::FORMAT_LOWER_SNAKE_CASE));
        $this->assertTrue(!StringUtils::isSnakeCase('_te', StringUtils::FORMAT_LOWER_SNAKE_CASE));
        $this->assertTrue(!StringUtils::isSnakeCase('_snake', StringUtils::FORMAT_LOWER_SNAKE_CASE));
        $this->assertTrue(!StringUtils::isSnakeCase('snake_', StringUtils::FORMAT_LOWER_SNAKE_CASE));
        $this->assertTrue(!StringUtils::isSnakeCase('snake-case', StringUtils::FORMAT_LOWER_SNAKE_CASE));
        $this->assertTrue(!StringUtils::isSnakeCase('snake_case.', StringUtils::FORMAT_LOWER_SNAKE_CASE));
        $this->assertTrue(!StringUtils::isSnakeCase('1_2', StringUtils::FORMAT_LOWER_SNAKE_CASE));
        $this->assertTrue(!StringUtils::isSnakeCase('snake__case', StringUtils::FORMAT_LOWER_SNAKE_CASE));

        // Test exceptions
        try {
            StringUtils::isSnakeCase(123);
            $this->exceptionMessage = '123 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::isSnakeCase([1,5,8]);
            $this->exceptionMessage = '[1,5,8] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::isSnakeCase(new Exception());
            $this->exceptionMessage = 'new Exception did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::isSnakeCase('SnakeCase', 67);
            $this->exceptionMessage = '67 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }
    }


    /**
     * testReplace
     *
     * @return void
     */
    public function testReplace(){

        // TODO - translate from TS
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testReplaceMulti
     *
     * @return void
     */
    public function testReplaceMulti(){

        // TODO - translate from TS
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testTrim
     *
     * @return void
     */
    public function testTrim(){

        // Test empty values
        AssertUtils::throwsException(function() { StringUtils::trim(null); }, '/value is not a string/');
        AssertUtils::throwsException(function() { StringUtils::trim(0); }, '/value is not a string/');
        AssertUtils::throwsException(function() { StringUtils::trim([]); }, '/value is not a string/');

        $this->assertSame(StringUtils::trim(""), "");

        // Test ok values
        $this->assertSame(StringUtils::trim("   "), "");
        $this->assertSame(StringUtils::trim("\n\n\r\r"), "");
        $this->assertSame(StringUtils::trim("  \n\n\r\r"), "");
        $this->assertSame(StringUtils::trim("\n\n\r\r   "), "");
        $this->assertSame(StringUtils::trim("   \n\n\r\r   "), "");
        $this->assertSame(StringUtils::trim("hello"), "hello");
        $this->assertSame(StringUtils::trim("hello\n"), "hello");
        $this->assertSame(StringUtils::trim("hello\r\n"), "hello");
        $this->assertSame(StringUtils::trim("\nhello\r\n"), "hello");
        $this->assertSame(StringUtils::trim("   hello"), "hello");
        $this->assertSame(StringUtils::trim("hello   "), "hello");
        $this->assertSame(StringUtils::trim("  hello  "), "hello");

        $this->assertSame(StringUtils::trim("helloxax", "xa"), "hello");
        $this->assertSame(StringUtils::trim("XXXhello", "xlX"), "hello");
        $this->assertSame(StringUtils::trim("XXXhelloxxx", "xX"), "hello");
        $this->assertSame(StringUtils::trim("1|2", "123"), "|");
        $this->assertSame(StringUtils::trim("1|2", ""), "1|2");
        $this->assertSame(StringUtils::trim("1|2\n", "1"), "|2\n");

        // Test wrong values
        // Not necessary

        // Test exceptions
        AssertUtils::throwsException(function() { StringUtils::trim([1, 2, 3, 4]); }, '/value is not a string/');
        AssertUtils::throwsException(function() { StringUtils::trim(new Exception()); }, '/value is not a string/');
        AssertUtils::throwsException(function() { StringUtils::trim(123466); }, '/value is not a string/');
    }


    /**
     * testTrimLeft
     *
     * @return void
     */
    public function testTrimLeft(){

        // Test empty values
        AssertUtils::throwsException(function() { StringUtils::trimLeft(null); }, '/value is not a string/');
        AssertUtils::throwsException(function() { StringUtils::trimLeft(0); }, '/value is not a string/');
        AssertUtils::throwsException(function() { StringUtils::trimLeft([]); }, '/value is not a string/');

        $this->assertSame(StringUtils::trimLeft(""), "");

        // Test ok values
        $this->assertSame(StringUtils::trimLeft("   "), "");
        $this->assertSame(StringUtils::trimLeft("\n\n\r\r"), "");
        $this->assertSame(StringUtils::trimLeft("  \n\n\r\r"), "");
        $this->assertSame(StringUtils::trimLeft("\n\n\r\r   "), "");
        $this->assertSame(StringUtils::trimLeft("   \n\n\r\r   "), "");
        $this->assertSame(StringUtils::trimLeft("hello"), "hello");
        $this->assertSame(StringUtils::trimLeft("hello\n"), "hello\n");
        $this->assertSame(StringUtils::trimLeft("hello\r\n"), "hello\r\n");
        $this->assertSame(StringUtils::trimLeft("\nhello\r\n"), "hello\r\n");
        $this->assertSame(StringUtils::trimLeft("   hello"), "hello");
        $this->assertSame(StringUtils::trimLeft("hello   "), "hello   ");
        $this->assertSame(StringUtils::trimLeft("  hello  "), "hello  ");

        $this->assertSame(StringUtils::trimLeft("helloxax", "xa"), "helloxax");
        $this->assertSame(StringUtils::trimLeft("XXXhello", "xlX"), "hello");
        $this->assertSame(StringUtils::trimLeft("XXXhelloxxx", "xX"), "helloxxx");
        $this->assertSame(StringUtils::trimLeft("1|2", "123"), "|2");
        $this->assertSame(StringUtils::trimLeft("1|2", ""), "1|2");
        $this->assertSame(StringUtils::trimLeft("1|2\n", "1"), "|2\n");

        // Test wrong values
        // Not necessary

        // Test exceptions
        AssertUtils::throwsException(function() { StringUtils::trimLeft([1, 2, 3, 4]); }, '/value is not a string/');
        AssertUtils::throwsException(function() { StringUtils::trimLeft(new Exception()); }, '/value is not a string/');
        AssertUtils::throwsException(function() { StringUtils::trimLeft(123466); }, '/value is not a string/');
    }


    /**
     * testTrimRight
     *
     * @return void
     */
    public function testTrimRight(){

        // Test empty values
        AssertUtils::throwsException(function() { StringUtils::trim(null); }, '/value is not a string/');
        AssertUtils::throwsException(function() { StringUtils::trim(0); }, '/value is not a string/');
        AssertUtils::throwsException(function() { StringUtils::trim([]); }, '/value is not a string/');

        $this->assertSame(StringUtils::trimRight(""), "");

        // Test ok values
        $this->assertSame(StringUtils::trimRight("   "), "");
        $this->assertSame(StringUtils::trimRight("\n\n\r\r"), "");
        $this->assertSame(StringUtils::trimRight("  \n\n\r\r"), "");
        $this->assertSame(StringUtils::trimRight("\n\n\r\r   "), "");
        $this->assertSame(StringUtils::trimRight("   \n\n\r\r   "), "");
        $this->assertSame(StringUtils::trimRight("hello"), "hello");
        $this->assertSame(StringUtils::trimRight("hello\n"), "hello");
        $this->assertSame(StringUtils::trimRight("hello\r\n"), "hello");
        $this->assertSame(StringUtils::trimRight("\nhello\r\n"), "\nhello");
        $this->assertSame(StringUtils::trimRight("   hello"), "   hello");
        $this->assertSame(StringUtils::trimRight("hello   "), "hello");
        $this->assertSame(StringUtils::trimRight("  hello  "), "  hello");

        $this->assertSame(StringUtils::trimRight("helloxax", "xa"), "hello");
        $this->assertSame(StringUtils::trimRight("XXXhello", "xlX"), "XXXhello");
        $this->assertSame(StringUtils::trimRight("XXXhelloxxx", "xX"), "XXXhello");
        $this->assertSame(StringUtils::trimRight("1|2", "123"), "1|");
        $this->assertSame(StringUtils::trimRight("1|2", ""), "1|2");
        $this->assertSame(StringUtils::trimRight("1|2\n", "1"), "1|2\n");

        // Test wrong values
        // Not necessary

        // Test exceptions
        AssertUtils::throwsException(function() { StringUtils::trim([1, 2, 3, 4]); }, '/value is not a string/');
        AssertUtils::throwsException(function() { StringUtils::trim(new Exception()); }, '/value is not a string/');
        AssertUtils::throwsException(function() { StringUtils::trim(123466); }, '/value is not a string/');
    }


    /**
     * testPad
     *
     * @return void
     */
    public function testPad(){

        // Test empty values
        AssertUtils::throwsException(function() { StringUtils::pad(null, 0); }, '/string and padString must be strings/');
        AssertUtils::throwsException(function() { StringUtils::pad(0, 0); }, '/string and padString must be strings/');
        AssertUtils::throwsException(function() { StringUtils::pad([], 0); }, '/string and padString must be strings/');
        AssertUtils::throwsException(function() { StringUtils::pad('', ''); }, '/padLength is not an int/');
        AssertUtils::throwsException(function() { StringUtils::pad('', 0, '', ''); }, '/string and padString must be strings/');
        AssertUtils::throwsException(function() { StringUtils::pad('', 0, '0', ''); }, '/mode must be LEFT or RIGHT/');
        $this->assertSame(StringUtils::pad('', 0), "");
        $this->assertSame(StringUtils::pad('      ', 0), '      ');

        // Test ok values - Padding left
        $this->assertSame('0', StringUtils::pad('', 1));
        $this->assertSame('00', StringUtils::pad('', 2));
        $this->assertSame('0000', StringUtils::pad('', 4));
        $this->assertSame('hello', StringUtils::pad('hello', 4));
        $this->assertSame('hello', StringUtils::pad('hello', 5));
        $this->assertSame('0hello', StringUtils::pad('hello', 6));
        $this->assertSame('000hello', StringUtils::pad('hello', 8));
        $this->assertSame('00000hello', StringUtils::pad('hello', 10));
        $this->assertSame('XXXXXhello', StringUtils::pad('hello', 10, 'X'));
        $this->assertSame('babababhello', StringUtils::pad('hello', 12, 'ab'));
        $this->assertSame('abababababhello', StringUtils::pad('hello', 15, 'ab'));
        $this->assertSame('cabcabcXXX', StringUtils::pad('XXX', 10, 'abc'));

        // Test ok values - Padding right
        $this->assertSame('0', StringUtils::pad('', 1, '0', 'RIGHT'));
        $this->assertSame('00', StringUtils::pad('', 2, '0', 'RIGHT'));
        $this->assertSame('0000', StringUtils::pad('', 4, '0', 'RIGHT'));
        $this->assertSame('hello', StringUtils::pad('hello', 4, '0', 'RIGHT'));
        $this->assertSame('hello', StringUtils::pad('hello', 5, '0', 'RIGHT'));
        $this->assertSame('hello0', StringUtils::pad('hello', 6, '0', 'RIGHT'));
        $this->assertSame('hello000', StringUtils::pad('hello', 8, '0', 'RIGHT'));
        $this->assertSame('hello00000', StringUtils::pad('hello', 10, '0', 'RIGHT'));
        $this->assertSame('helloXXXXX', StringUtils::pad('hello', 10, 'X', 'RIGHT'));
        $this->assertSame('helloabababa', StringUtils::pad('hello', 12, 'ab', 'RIGHT'));
        $this->assertSame('helloababababab', StringUtils::pad('hello', 15, 'ab', 'RIGHT'));
        $this->assertSame('XXXabcabca', StringUtils::pad('XXX', 10, 'abc', 'RIGHT'));

        // Test wrong values
        // Not necessary

        // Test exceptions
        AssertUtils::throwsException(function() { StringUtils::pad([1, 2, 3, 4], 0); }, '/string and padString must be strings/');
        AssertUtils::throwsException(function() { StringUtils::pad(new stdclass(), 0); }, '/string and padString must be strings/');
        AssertUtils::throwsException(function() { StringUtils::pad(12345, 0); }, '/string and padString must be strings/');
        AssertUtils::throwsException(function() { StringUtils::pad('1213', 10, 12356); }, '/string and padString must be strings/');
    }


    /**
     * testCountStringOccurences
     *
     * @return void
     */
    public function testCountStringOccurences(){

        $this->assertSame(StringUtils::countStringOccurences('       ', ' '), 7);
        $this->assertSame(StringUtils::countStringOccurences('hello', 'o'), 1);
        $this->assertSame(StringUtils::countStringOccurences('hello baby', 'b'), 2);
        $this->assertSame(StringUtils::countStringOccurences('hello baby', 'B'), 0);
        $this->assertSame(StringUtils::countStringOccurences("tRy\nto\r\n\t\ngo\r\nUP", 'o'), 2);
        $this->assertSame(StringUtils::countStringOccurences("     \n      \r\n", 'a'), 0);
        $this->assertSame(StringUtils::countStringOccurences(" AEÉÜ    \n   1   \r\nÍË", 'É'), 1);
        $this->assertSame(StringUtils::countStringOccurences("heLLo Baby\nhellÓ àgaiN and go\n\n\r\nUp!", 'a'), 3);
        $this->assertSame(StringUtils::countStringOccurences("helló bàbÝ\n   whats Up Todäy? are you feeling better? GOOD!", 'T'), 1);

        // Test exceptions
        AssertUtils::throwsException(function(){ StringUtils::countStringOccurences(null, null); }, '/value is not a string/');
        AssertUtils::throwsException(function(){ StringUtils::countStringOccurences('', ''); }, '/cannot count empty string occurences/');
        AssertUtils::throwsException(function(){ StringUtils::countStringOccurences('  ', ''); }, '/cannot count empty string occurences/');
    }


    /**
     * testCountByCase
     *
     * @return void
     */
    public function testCountByCase(){

        // Test empty values
        AssertUtils::throwsException(function(){ StringUtils::countByCase(null); }, '/value is not a string/');
        AssertUtils::throwsException(function(){ StringUtils::countByCase(null, StringUtils::FORMAT_ALL_LOWER_CASE); }, '/value is not a string/');

        $this->assertSame(0, StringUtils::countByCase(''));
        $this->assertSame(0, StringUtils::countByCase('  '));
        $this->assertSame(0, StringUtils::countByCase('       '));
        $this->assertSame(0, StringUtils::countByCase("     \n      \r\n"));

        $this->assertSame(0, StringUtils::countByCase('', StringUtils::FORMAT_ALL_LOWER_CASE));
        $this->assertSame(0, StringUtils::countByCase('  ', StringUtils::FORMAT_ALL_LOWER_CASE));
        $this->assertSame(0, StringUtils::countByCase('       ', StringUtils::FORMAT_ALL_LOWER_CASE));
        $this->assertSame(0, StringUtils::countByCase("     \n      \r\n", StringUtils::FORMAT_ALL_LOWER_CASE));

        // Test ok values
        $this->assertSame(0, StringUtils::countByCase('hello'));
        $this->assertSame(0, StringUtils::countByCase('hello baby'));
        $this->assertSame(0, StringUtils::countByCase('123123123123123'));
        $this->assertSame(3, StringUtils::countByCase("tRy\nto\r\n\t\ngo\r\nUP"));
        $this->assertSame(6, StringUtils::countByCase(" AEÉÜ    \n   1   \r\nÍË"));
        $this->assertSame(6, StringUtils::countByCase("heLLo Baby\nhellÓ agaiN and go\n\n\r\nUp!"));
        $this->assertSame(6, StringUtils::countByCase("helló bàbÝ\n   whats Up todäy? are you feeling better? GOOD!"));
        $this->assertSame(0, StringUtils::countByCase("形声字 / 形聲字"));
        $this->assertSame(1, StringUtils::countByCase("形声字A形聲字"));
        $this->assertSame(2, StringUtils::countByCase("A形声字A形聲字a"));
        $this->assertSame(6, StringUtils::countByCase("AaÁáBéEéÉË"));
        $this->assertSame(1, StringUtils::countByCase("1234A567"));
        $this->assertSame(2, StringUtils::countByCase('1声A字43B45-_*[]', StringUtils::FORMAT_ALL_UPPER_CASE));

        $this->assertSame(5, StringUtils::countByCase('hello', StringUtils::FORMAT_ALL_LOWER_CASE));
        $this->assertSame(9, StringUtils::countByCase('hello baby', StringUtils::FORMAT_ALL_LOWER_CASE));
        $this->assertSame(6, StringUtils::countByCase("tRy\nto\r\n\t\ngo\r\nUP", StringUtils::FORMAT_ALL_LOWER_CASE));
        $this->assertSame(0, StringUtils::countByCase(" AEÉÜ    \n   1   \r\nÍË", StringUtils::FORMAT_ALL_LOWER_CASE));
        $this->assertSame(20, StringUtils::countByCase("heLLo Baby\nhellÓ agaiN and go\n\n\r\nUp!", StringUtils::FORMAT_ALL_LOWER_CASE));
        $this->assertSame(38, StringUtils::countByCase("helló bàbÝ\n   whats Up todäy? are you feeling better? GOOD!", StringUtils::FORMAT_ALL_LOWER_CASE));
        $this->assertSame(0, StringUtils::countByCase("形声字 / 形聲字", StringUtils::FORMAT_ALL_LOWER_CASE));
        $this->assertSame(0, StringUtils::countByCase("形声字A形聲字", StringUtils::FORMAT_ALL_LOWER_CASE));
        $this->assertSame(1, StringUtils::countByCase("A形声字A形聲字a", StringUtils::FORMAT_ALL_LOWER_CASE));
        $this->assertSame(5, StringUtils::countByCase("AaÁáBéEéÉë", StringUtils::FORMAT_ALL_LOWER_CASE));
        $this->assertSame(0, StringUtils::countByCase('1声A字43B45-_*[]', StringUtils::FORMAT_ALL_LOWER_CASE));
        $this->assertSame(1, StringUtils::countByCase('1声A字43B45a-_*[]', StringUtils::FORMAT_ALL_LOWER_CASE));

        // Test wrong values
        AssertUtils::throwsException(function(){ StringUtils::countByCase(123123); }, '/value is not a string/');

        // Test exceptions
        AssertUtils::throwsException(function(){ StringUtils::countByCase("hello", 'invalid format'); }, '/invalid case value/');
    }


    /**
     * testCountWords
     *
     * @return void
     */
    public function testCountWords(){

        $this->assertTrue(StringUtils::countWords(null) == 0);
        $this->assertTrue(StringUtils::countWords('') == 0);
        $this->assertTrue(StringUtils::countWords('  ') == 0);
        $this->assertTrue(StringUtils::countWords('       ') == 0);
        $this->assertTrue(StringUtils::countWords('hello') == 1);
        $this->assertTrue(StringUtils::countWords('hello baby') == 2);
        $this->assertTrue(StringUtils::countWords('Üèó ï á étwer') == 4);
        $this->assertTrue(StringUtils::countWords("try\nto\r\n\t\ngo\r\nup") == 4);
        $this->assertTrue(StringUtils::countWords("     \n      \r\n") == 0);
        $this->assertTrue(StringUtils::countWords("     \n   1   \r\n") == 1);
        $this->assertTrue(StringUtils::countWords("hello baby\nhello again and go\n\n\r\nup!") == 7);
        $this->assertTrue(StringUtils::countWords("hello baby\n   whats up today? are you feeling better? GOOD!") == 10);
    }


    /**
     * testCountPathElements
     *
     * @return void
     */
    public function testCountPathElements(){

        // Test empty values
        try {
            StringUtils::countPathElements(null);
            $this->exceptionMessage = 'null did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/path must be a string/', $e->getMessage());
        }

        $this->assertSame(StringUtils::countPathElements(''), 0);
        $this->assertSame(StringUtils::countPathElements('       '), 1);

        try {
            StringUtils::countPathElements([]);
            $this->exceptionMessage = '[] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test ok values
        $this->assertSame(StringUtils::countPathElements('/'), 0);
        $this->assertSame(StringUtils::countPathElements('///////'), 0);
        $this->assertSame(StringUtils::countPathElements('\\'), 0);
        $this->assertSame(StringUtils::countPathElements('c:/'), 1);
        $this->assertSame(StringUtils::countPathElements('c:\\'), 1);
        $this->assertSame(StringUtils::countPathElements('folder'), 1);
        $this->assertSame(StringUtils::countPathElements('//folder'), 1);
        $this->assertSame(StringUtils::countPathElements('C:\\Program Files\\CCleaner\\CCleaner64.exe'), 4);
        $this->assertSame(StringUtils::countPathElements('\\Files/CCleaner/CCleaner64.exe'), 3);
        $this->assertSame(StringUtils::countPathElements('//folder/folder2/folder3/file.txt'), 4);
        $this->assertSame(StringUtils::countPathElements('CCleaner64.exe'), 1);
        $this->assertSame(StringUtils::countPathElements('\\\\\\CCleaner64.exe'), 1);
        $this->assertSame(StringUtils::countPathElements('\\some long path containing lots of spaces\\///CCleaner64.exe'), 2);
        $this->assertSame(StringUtils::countPathElements("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe"), 2);
        $this->assertSame(StringUtils::countPathElements("folder1\\\\folder2//folder3///\\\\folder4"), 4);
        $this->assertSame(StringUtils::countPathElements('//folder/folder2/folder3/'), 3);
        $this->assertSame(StringUtils::countPathElements('https://www.google.es'), 2);
        $this->assertSame(StringUtils::countPathElements('https://www.google.es//////'), 2);
        $this->assertSame(StringUtils::countPathElements('https://www.youtube.com/watch?v=bvOGIDiLzMk'), 3);
        $this->assertSame(StringUtils::countPathElements('https://www.google.es/search?q=zero+latency'), 3);

        // Test wrong values
        // Not necessary

        // Test exceptions
        try {
            StringUtils::countPathElements(['//folder/folder2/folder3/file.txt']);
            $this->exceptionMessage = 'array did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::countPathElements(125);
            $this->exceptionMessage = '125 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::countPathElements(new stdClass());
            $this->exceptionMessage = 'new stdClass() did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }
    }


    /**
     * testLimitLen
     *
     * @return void
     */
    public function testLimitLen(){

        $this->assertTrue(StringUtils::limitLen(null, 10) === '');
        $this->assertTrue(StringUtils::limitLen('', 10) === '');
        $this->assertTrue(StringUtils::limitLen('hello dear how are you', 1) === ' ');
        $this->assertTrue(StringUtils::limitLen('hello dear how are you', 2) === ' .');
        $this->assertTrue(StringUtils::limitLen('hello dear how are you', 3) === ' ..');
        $this->assertTrue(StringUtils::limitLen('hello dear how are you', 4) === ' ...');
        $this->assertTrue(StringUtils::limitLen('hello dear how are you', 5) === 'h ...');
        $this->assertTrue(StringUtils::limitLen('hello dear how are you', 18) === 'hello dear how ...');
        $this->assertTrue(StringUtils::limitLen('hello dear how are you', 19) === 'hello dear how  ...');
        $this->assertTrue(StringUtils::limitLen('hello dear how are you', 20) === 'hello dear how a ...');
        $this->assertTrue(StringUtils::limitLen('hello dear how are you', 21) === 'hello dear how ar ...');
        $this->assertTrue(StringUtils::limitLen('hello dear how are you', 22) === 'hello dear how are you');
        $this->assertTrue(StringUtils::limitLen('hello dear how are you', 50) === 'hello dear how are you');

        // Test invalid values give exception
        try {
            StringUtils::limitLen('', 0);
            $this->exceptionMessage = '0 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::limitLen('hello', [1, 2]);
            $this->exceptionMessage = '[1, 2] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::limitLen('hello', null);
            $this->exceptionMessage = 'hello did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }
    }


    /**
     * testGetDomainFromUrl
     *
     * @return void
     */
    public function testGetDomainFromUrl(){

        // TODO - copy tests from js
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testGetHostNameFromUrl
     *
     * @return void
     */
    public function testGetHostNameFromUrl(){

        // TODO - copy tests from js
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testGetLines
     *
     * @return void
     */
    public function testGetLines(){

        $this->assertTrue(StringUtils::getLines(null) === []);
        $this->assertTrue(StringUtils::getLines('') === []);
        $this->assertTrue(StringUtils::getLines('          ') === []);
        $this->assertTrue(StringUtils::getLines('single line') === ['single line']);
        $this->assertTrue(StringUtils::getLines("line1\nline2\nline3") === ['line1', 'line2', 'line3']);
        $this->assertTrue(StringUtils::getLines("line1\rline2\rline3") === ['line1', 'line2', 'line3']);
        $this->assertTrue(StringUtils::getLines("line1\r\nline2\r\nline3") === ['line1', 'line2', 'line3']);
        $this->assertTrue(StringUtils::getLines("line1\n        \nline2") === ['line1', 'line2']);
        $this->assertTrue(StringUtils::getLines("line1\n\n\n\t\r       \nline2") === ['line1', 'line2']);
        $this->assertTrue(StringUtils::getLines("line1\r\n   \r\nline2") === ['line1', 'line2']);
        $this->assertTrue(StringUtils::getLines("line1\n 1  \nline2") === ['line1', ' 1  ', 'line2']);
        $this->assertTrue(StringUtils::getLines("line1\r\n 1  \n\r\r\nline2") === ['line1', ' 1  ', 'line2']);

        $this->assertTrue(StringUtils::getLines('          ', []) === ['          ']);
        $this->assertTrue(StringUtils::getLines("line1\r   \rline2", []) === ['line1', '   ', 'line2']);
        $this->assertTrue(StringUtils::getLines("line1\n   \nline2", []) === ['line1', '   ', 'line2']);
        $this->assertTrue(StringUtils::getLines("line1\r\n   \r\nline2", []) === ['line1', '   ', 'line2']);
        $this->assertTrue(StringUtils::getLines("line1\n\n\n\t\r       \nline2", []) === ['line1', "\t", '       ', 'line2']);

        $this->assertTrue(
            StringUtils::getLines("# com\n# com2\n\n\n! com\nweb = google\nlan = En\n# com4\n# com5\nmessage = Welcome", ['/\s+/', '/ *#.*| *!.*/']) ===
            ['web = google', 'lan = En', 'message = Welcome']
        );
    }


    /**
     * testGetKeyWords
     *
     * @return void
     */
    public function testGetKeyWords(){

        $this->assertTrue(StringUtils::getKeyWords(null) === []);
        $this->assertTrue(StringUtils::getKeyWords('') === []);
        $this->assertTrue(StringUtils::getKeyWords('hello') === ['hello']);

        // TODO: add lot more tests
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testGetPath
     *
     * @return void
     */
    public function testGetPath(){

        // Test empty values
        $this->assertSame(StringUtils::getPath(null), '');
        $this->assertSame(StringUtils::getPath(''), '');
        $this->assertSame(StringUtils::getPath('       '), '');
        $this->assertSame(StringUtils::getPath([]), '');

        // Test ok values

        // With 0 elements removed
        $this->assertSame(StringUtils::getPath('/', 0), '/');
        $this->assertSame(StringUtils::getPath('///////', 0), '/');
        $this->assertSame(StringUtils::getPath('\\', 0), '/');
        $this->assertSame(StringUtils::getPath('c:/', 0), 'c:');
        $this->assertSame(StringUtils::getPath('c:/', 0), 'c:');
        $this->assertSame(StringUtils::getPath('c:\\', 0), 'c:');
        $this->assertSame(StringUtils::getPath('folder', 0), 'folder');
        $this->assertSame(StringUtils::getPath('//folder', 0), '/folder');
        $this->assertSame(StringUtils::getPath('CCleaner64.exe', 0), 'CCleaner64.exe');
        $this->assertSame(StringUtils::getPath('\\Files/CCleaner/CCleaner64.exe', 0), '/Files/CCleaner/CCleaner64.exe');
        $this->assertSame(StringUtils::getPath("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe", 0), "MultiLine\n\n\r\n   and strange &%·Characters/CCleaner64.exe");
        $this->assertSame(StringUtils::getPath('C:\\Program Files\\CCleaner\\CCleaner64.exe', 0), 'C:/Program Files/CCleaner/CCleaner64.exe');
        $this->assertSame(StringUtils::getPath('https://www.google.es//////', 0), 'https:/www.google.es');
        $this->assertSame(StringUtils::getPath('https://www.google.es/search?q=zero+latency', 0), 'https:/www.google.es/search?q=zero+latency');

        // With 1 element removed
        $this->assertSame(StringUtils::getPath('/', 1), '/');
        $this->assertSame(StringUtils::getPath('///////', 1), '/');
        $this->assertSame(StringUtils::getPath('\\', 1), '/');
        $this->assertSame(StringUtils::getPath('c:/', 1), '');
        $this->assertSame(StringUtils::getPath('c:/', 1), '');
        $this->assertSame(StringUtils::getPath('c:\\', 1), '');
        $this->assertSame(StringUtils::getPath('folder', 1), '');
        $this->assertSame(StringUtils::getPath('//folder', 1), '');
        $this->assertSame(StringUtils::getPath('CCleaner64.exe', 1), '');
        $this->assertSame(StringUtils::getPath('\\\\\\CCleaner64.exe', 1), '');
        $this->assertSame(StringUtils::getPath('//folder/folder2/folder3/file.txt', 1), '/folder/folder2/folder3');
        $this->assertSame(StringUtils::getPath("folder1\\\\folder2//folder3///\\\\folder4", 1), 'folder1/folder2/folder3');
        $this->assertSame(StringUtils::getPath('\\Files/CCleaner/CCleaner64.exe', 1), '/Files/CCleaner');
        $this->assertSame(StringUtils::getPath("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe", 1), "MultiLine\n\n\r\n   and strange &%·Characters");
        $this->assertSame(StringUtils::getPath('\\some long path containing lots of spaces\\///CCleaner64.exe', 1), '/some long path containing lots of spaces');
        $this->assertSame(StringUtils::getPath('C:\\Program Files\\CCleaner\\CCleaner64.exe', 1), 'C:/Program Files/CCleaner');
        $this->assertSame(StringUtils::getPath('https://www.google.es', 1), 'https:');
        $this->assertSame(StringUtils::getPath('https://www.google.es//////', 1), 'https:');
        $this->assertSame(StringUtils::getPath('https://www.google.es/search?q=zero+latency', 1), 'https:/www.google.es');

        // With 2 element removed
        $this->assertSame(StringUtils::getPath('/', 2), '/');
        $this->assertSame(StringUtils::getPath('///////', 2), '/');
        $this->assertSame(StringUtils::getPath('\\', 2), '/');
        $this->assertSame(StringUtils::getPath('c:/', 2), '');
        $this->assertSame(StringUtils::getPath('c:/', 2), '');
        $this->assertSame(StringUtils::getPath('c:\\', 2), '');
        $this->assertSame(StringUtils::getPath('folder', 2), '');
        $this->assertSame(StringUtils::getPath('//folder', 2), '');
        $this->assertSame(StringUtils::getPath('CCleaner64.exe', 2), '');
        $this->assertSame(StringUtils::getPath('\\\\\\CCleaner64.exe', 2), '');
        $this->assertSame(StringUtils::getPath('//folder/folder2/folder3/file.txt', 2), '/folder/folder2');
        $this->assertSame(StringUtils::getPath("folder1\\\\folder2//folder3///\\\\folder4", 2), 'folder1/folder2');
        $this->assertSame(StringUtils::getPath('\\Files/CCleaner/CCleaner64.exe', 2), '/Files');
        $this->assertSame(StringUtils::getPath("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe", 2), "");
        $this->assertSame(StringUtils::getPath('\\some long path containing lots of spaces\\///CCleaner64.exe', 2), '');
        $this->assertSame(StringUtils::getPath('C:\\Program Files\\CCleaner\\CCleaner64.exe', 2), 'C:/Program Files');
        $this->assertSame(StringUtils::getPath('https://www.google.es', 2), '');
        $this->assertSame(StringUtils::getPath('https://www.google.es//////', 2), '');
        $this->assertSame(StringUtils::getPath('https://www.google.es/search?q=zero+latency', 2), 'https:');

        // With many element removed
        $this->assertSame(StringUtils::getPath('/', 3), '/');
        $this->assertSame(StringUtils::getPath('///////', 4), '/');
        $this->assertSame(StringUtils::getPath('\\', 5), '/');
        $this->assertSame(StringUtils::getPath('c:/', 10), '');
        $this->assertSame(StringUtils::getPath('c:/', 20), '');
        $this->assertSame(StringUtils::getPath('c:\\', 40), '');
        $this->assertSame(StringUtils::getPath('folder', 60), '');
        $this->assertSame(StringUtils::getPath('//folder', 200), '');
        $this->assertSame(StringUtils::getPath('CCleaner64.exe', 2000), '');
        $this->assertSame(StringUtils::getPath('\\\\\\CCleaner64.exe', 9), '');
        $this->assertSame(StringUtils::getPath('//folder/folder2/folder3/file.txt', 3), '/folder');
        $this->assertSame(StringUtils::getPath("folder1\\\\folder2//folder3///\\\\folder4", 4), '');
        $this->assertSame(StringUtils::getPath('\\Files/CCleaner/CCleaner64.exe', 300), '');
        $this->assertSame(StringUtils::getPath("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe", 15), "");
        $this->assertSame(StringUtils::getPath('\\some long path containing lots of spaces\\///CCleaner64.exe', 19), '');
        $this->assertSame(StringUtils::getPath('C:\\Program Files\\CCleaner\\CCleaner64.exe', 3), 'C:');
        $this->assertSame(StringUtils::getPath('https://www.google.es', 29), '');
        $this->assertSame(StringUtils::getPath('https://www.google.es//////', 28), '');
        $this->assertSame(StringUtils::getPath('https://www.google.es/search?q=zero+latency', 78), '');
        $this->assertSame(StringUtils::getPath('1/2/3/4/5/6/7/8/9/10/11/12/13', 3), '1/2/3/4/5/6/7/8/9/10');
        $this->assertSame(StringUtils::getPath('1/2/3/4/5/6/7/8/9/10/11/12/13', 5), '1/2/3/4/5/6/7/8');
        $this->assertSame(StringUtils::getPath('1/2/3/4/5/6/7/8/9/10/11/12/13', 7), '1/2/3/4/5/6');
        $this->assertSame(StringUtils::getPath('1/2/3/4/5/6/7/8/9/10/11/12/13', 10), '1/2/3');
        $this->assertSame(StringUtils::getPath('1/2/3/4/5/6/7/8/9/10/11/12/13', 12), '1');
        $this->assertSame(StringUtils::getPath('1/2/3/4/5/6/7/8/9/10/11/12/13', 25), '');

        // Test wrong values
        // Test exceptions
        try {
            StringUtils::getPath(['//folder/folder2/folder3/file.txt']);
            $this->exceptionMessage = '//folder/ did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::getPath(125);
            $this->exceptionMessage = '125 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::getPath(new stdClass());
            $this->exceptionMessage = 'stdClass did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }
    }


    /**
     * testGetPathElement
     *
     * @return void
     */
    public function testGetPathElement(){

        // Test empty values
        $this->assertSame(StringUtils::getPathElement(null), '');
        $this->assertSame(StringUtils::getPathElement(''), '');
        $this->assertSame(StringUtils::getPathElement('       '), '');
        $this->assertSame(StringUtils::getPathElement([]), '');

        // Test ok values
        $this->assertSame(StringUtils::getPathElement('/'), '');
        $this->assertSame(StringUtils::getPathElement('///////'), '');
        $this->assertSame(StringUtils::getPathElement('\\'), '');
        $this->assertSame(StringUtils::getPathElement('c:/'), 'c:');
        $this->assertSame(StringUtils::getPathElement('c:\\'), 'c:');
        $this->assertSame(StringUtils::getPathElement('folder'), 'folder');
        $this->assertSame(StringUtils::getPathElement('//folder'), 'folder');
        $this->assertSame(StringUtils::getPathElement('C:\\Program Files\\CCleaner\\CCleaner64.exe'), 'CCleaner64.exe');
        $this->assertSame(StringUtils::getPathElement('\\Files/CCleaner/CCleaner64.exe'), 'CCleaner64.exe');
        $this->assertSame(StringUtils::getPathElement('//folder/folder2/folder3/file.txt'), 'file.txt');
        $this->assertSame(StringUtils::getPathElement('CCleaner64.exe'), 'CCleaner64.exe');
        $this->assertSame(StringUtils::getPathElement('\\\\\\CCleaner64.exe'), 'CCleaner64.exe');
        $this->assertSame(StringUtils::getPathElement('\\some long path containing lots of spaces\\///CCleaner64.exe'), 'CCleaner64.exe');
        $this->assertSame(StringUtils::getPathElement("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe"), 'CCleaner64.exe');
        $this->assertSame(StringUtils::getPathElement("folder1\\\\folder2//folder3///\\\\folder4"), 'folder4');
        $this->assertSame(StringUtils::getPathElement('//folder/folder2/folder3/'), 'folder3');
        $this->assertSame(StringUtils::getPathElement('https://www.google.es'), 'www.google.es');
        $this->assertSame(StringUtils::getPathElement('https://www.google.es//////'), 'www.google.es');
        $this->assertSame(StringUtils::getPathElement('https://www.youtube.com/watch?v=bvOGIDiLzMk'), 'watch?v=bvOGIDiLzMk');
        $this->assertSame(StringUtils::getPathElement('https://www.google.es/search?q=zero+latency'), 'search?q=zero+latency');

        $this->assertSame(StringUtils::getPathElement("folder1\\\\folder2//folder3///\\\\folder4", 0), 'folder1');
        $this->assertSame(StringUtils::getPathElement("folder1\\\\folder2//folder3///\\\\folder4", 1), 'folder2');
        $this->assertSame(StringUtils::getPathElement("folder1\\\\folder2//folder3///\\\\folder4", 2), 'folder3');
        $this->assertSame(StringUtils::getPathElement("folder1\\\\folder2//folder3///\\\\folder4", 3), 'folder4');
        $this->assertSame(StringUtils::getPathElement('//folder/folder2/folder3/file.txt', 0), 'folder');
        $this->assertSame(StringUtils::getPathElement('//folder/folder2/folder3/file.txt', 1), 'folder2');
        $this->assertSame(StringUtils::getPathElement('//folder/folder2/folder3/file.txt', 2), 'folder3');
        $this->assertSame(StringUtils::getPathElement('//folder/folder2/folder3/file.txt', 3), 'file.txt');
        $this->assertSame(StringUtils::getPathElement('https://www.google.es/search?q=zero+latency', 0), 'https:');
        $this->assertSame(StringUtils::getPathElement('https://www.google.es/search?q=zero+latency', 1), 'www.google.es');
        $this->assertSame(StringUtils::getPathElement('https://www.google.es/search?q=zero+latency', 2), 'search?q=zero+latency');
        $this->assertSame(StringUtils::getPathElement('https:\\www.google.es/search?q=zero\\+latency', 0), 'https:');
        $this->assertSame(StringUtils::getPathElement('https:\\www.google.es/search?q=zero\\+latency', 1), 'www.google.es');
        $this->assertSame(StringUtils::getPathElement('https:\\www.google.es/search?q=zero\\+latency', 2), 'search?q=zero');
        $this->assertSame(StringUtils::getPathElement('https:\\www.google.es/search?q=zero\\+latency', 3), '+latency');

        $this->assertSame(StringUtils::getPathElement("folder1\\\\folder2//folder3///\\\\folder4", -1), 'folder4');
        $this->assertSame(StringUtils::getPathElement("folder1\\\\folder2//folder3///\\\\folder4", -2), 'folder3');
        $this->assertSame(StringUtils::getPathElement("folder1\\\\folder2//folder3///\\\\folder4", -3), 'folder2');
        $this->assertSame(StringUtils::getPathElement("folder1\\\\folder2//folder3///\\\\folder4", -4), 'folder1');
        $this->assertSame(StringUtils::getPathElement('//folder/folder2/folder3/file.txt', -1), 'file.txt');
        $this->assertSame(StringUtils::getPathElement('//folder/folder2/folder3/file.txt', -2), 'folder3');
        $this->assertSame(StringUtils::getPathElement('//folder/folder2/folder3/file.txt', -3), 'folder2');
        $this->assertSame(StringUtils::getPathElement('//folder/folder2/folder3/file.txt', -4), 'folder');
        $this->assertSame(StringUtils::getPathElement('https://www.google.es/search?q=zero+latency', -1), 'search?q=zero+latency');
        $this->assertSame(StringUtils::getPathElement('https://www.google.es/search?q=zero+latency', -2), 'www.google.es');
        $this->assertSame(StringUtils::getPathElement('https://www.google.es/search?q=zero+latency', -3), 'https:');
        $this->assertSame(StringUtils::getPathElement('https:\\www.google.es/search?q=zero\\+latency', -1), '+latency');
        $this->assertSame(StringUtils::getPathElement('https:\\www.google.es/search?q=zero\\+latency', -2), 'search?q=zero');
        $this->assertSame(StringUtils::getPathElement('https:\\www.google.es/search?q=zero\\+latency', -3), 'www.google.es');
        $this->assertSame(StringUtils::getPathElement('https:\\www.google.es/search?q=zero\\+latency', -4), 'https:');

        // Test wrong values
        try {
            StringUtils::getPathElement('//folder/folder2/folder3/file.txt', 4);
            $this->exceptionMessage = 'index 4 did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/Invalid position specified/', $e->getMessage());
        }

        try {
            StringUtils::getPathElement('//folder/folder2/folder3/file.txt', 100);
            $this->exceptionMessage = 'index 100 did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/Invalid position specified/', $e->getMessage());
        }

        try {
            StringUtils::getPathElement('//folder/folder2/folder3/file.txt', -5);
            $this->exceptionMessage = 'index -5 did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/Invalid position specified/', $e->getMessage());
        }

        try {
            StringUtils::getPathElement('//folder/folder2/folder3/file.txt', -10);
            $this->exceptionMessage = 'index -10 did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/Invalid position specified/', $e->getMessage());
        }

        try {
            StringUtils::getPathElement('//folder/folder2/folder3/file.txt', -100);
            $this->exceptionMessage = 'index -100 did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/Invalid position specified/', $e->getMessage());
        }

        // Test exceptions
        try {
            StringUtils::getPathElement(['//folder/folder2/folder3/file.txt']);
            $this->exceptionMessage = 'array did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/value is not a string/', $e->getMessage());
        }

        try {
            StringUtils::getPathElement(125);
            $this->exceptionMessage = '125 did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/value is not a string/', $e->getMessage());
        }

        try {
            StringUtils::getPathElement(new stdClass());
            $this->exceptionMessage = 'new stdClass() did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/value is not a string/', $e->getMessage());
        }
    }


    /**
     * testGetPathElementWithoutExt
     *
     * @return void
     */
    public function testGetPathElementWithoutExt(){

        // Test empty values
        $this->assertTrue(StringUtils::getPathElementWithoutExt(null) === '');
        $this->assertTrue(StringUtils::getPathElementWithoutExt('') === '');
        $this->assertTrue(StringUtils::getPathElementWithoutExt('       ') === '');
        $this->assertTrue(StringUtils::getPathElementWithoutExt([]) === '');

        // Test ok values
        $this->assertTrue(StringUtils::getPathElementWithoutExt('C:\\Program Files\\CCleaner\\CCleaner64.exe') == 'CCleaner64');
        $this->assertTrue(StringUtils::getPathElementWithoutExt('\\Files/CCleaner/CCleaner64.exe') == 'CCleaner64');
        $this->assertTrue(StringUtils::getPathElementWithoutExt('//folder/folder2/folder3/file.txt') == 'file');
        $this->assertTrue(StringUtils::getPathElementWithoutExt('CCleaner64.exe') == 'CCleaner64');
        $this->assertTrue(StringUtils::getPathElementWithoutExt('\\\\\\CCleaner64.exe') == 'CCleaner64');
        $this->assertTrue(StringUtils::getPathElementWithoutExt('\\some long path containing lots of spaces\\///CCleaner64.exe') == 'CCleaner64');
        $this->assertTrue(StringUtils::getPathElementWithoutExt("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe") == 'CCleaner64');
        $this->assertTrue(StringUtils::getPathElementWithoutExt('//folder/folder2/folder3/file.extension.txt') == 'file.extension');
        $this->assertTrue(StringUtils::getPathElementWithoutExt('//folder/folder2.txt/folder3/file.extension.txt') == 'file.extension');

        $this->assertSame(StringUtils::getPathElementWithoutExt("folder1.a.b.txt\\\\folder2//folder3///\\\\folder4", 0), 'folder1.a.b');
        $this->assertSame(StringUtils::getPathElementWithoutExt("folder1\\\\folder2.jpg//folder3///\\\\folder4", 1), 'folder2');
        $this->assertSame(StringUtils::getPathElementWithoutExt("folder1\\\\folder2//folder3///\\\\folder4", 2), 'folder3');
        $this->assertSame(StringUtils::getPathElementWithoutExt("folder1\\\\folder2//folder3///\\\\folder4.txt", 3), 'folder4');
        $this->assertSame(StringUtils::getPathElementWithoutExt("folder1\\\\folder2//folder3///\\\\folder4", 3), 'folder4');
        $this->assertSame(StringUtils::getPathElementWithoutExt('//folder/folder2/folder3/file.txt', 0), 'folder');
        $this->assertSame(StringUtils::getPathElementWithoutExt('//folder/folder2/folder3/file.txt', 3), 'file');
        $this->assertSame(StringUtils::getPathElementWithoutExt('https://www.google.es/search?q=zero+latency', 0), 'https:');
        $this->assertSame(StringUtils::getPathElementWithoutExt('https://www.google.es/search?q=zero+latency', 2), 'search?q=zero+latency');
        $this->assertSame(StringUtils::getPathElementWithoutExt('https:\\www.google.es/search?q=zero\\+latency', 0), 'https:');
        $this->assertSame(StringUtils::getPathElementWithoutExt('https:\\www.google.es/search?q=zero.html', 2), 'search?q=zero');

        $this->assertTrue(StringUtils::getPathElementWithoutExt('//folder/folder2.txt/folder3/file-extension.txt', -1, '-') == 'file');
        $this->assertTrue(StringUtils::getPathElementWithoutExt('//folder/folder2.txt/folder3/file-extension.txt', 1, '-') == 'folder2.txt');
        $this->assertTrue(StringUtils::getPathElementWithoutExt('//folder/folder2.txt/folder3/file-extension.txt', 0, 'd') == 'fol');

        // Test wrong values
        try {
            StringUtils::getPathElementWithoutExt('//folder/folder2/folder3/file.txt', 4);
            $this->exceptionMessage = 'index 4 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::getPathElementWithoutExt('//folder/folder2/folder3/file.txt', 100);
            $this->exceptionMessage = 'index 100 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::getPathElementWithoutExt('//folder/folder2/folder3/file.txt', -10);
            $this->exceptionMessage = 'index -10 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test exceptions
        try {
            StringUtils::getPathElementWithoutExt(['//folder/folder2/folder3/file.txt']);
            $this->exceptionMessage = 'array did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::getPathElementWithoutExt(125);
            $this->exceptionMessage = '125 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::getPathElementWithoutExt(new stdClass());
            $this->exceptionMessage = 'new stdClass() did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }
    }


    /**
     * testGetPathExtension
     *
     * @return void
     */
    public function testGetPathExtension(){

        // Test empty values
        $this->assertTrue(StringUtils::getPathExtension(null) === '');
        $this->assertTrue(StringUtils::getPathExtension('') === '');
        $this->assertTrue(StringUtils::getPathExtension('       ') === '');
        $this->assertTrue(StringUtils::getPathExtension([]) === '');

        // Test ok values
        $this->assertTrue(StringUtils::getPathExtension('/') === '');
        $this->assertTrue(StringUtils::getPathExtension('////') === '');
        $this->assertTrue(StringUtils::getPathExtension('/a') === '');
        $this->assertTrue(StringUtils::getPathExtension('C:\Program Files\\CCleaner') == '');
        $this->assertTrue(StringUtils::getPathExtension('C:\Program Files\\CCleaner\\CCleaner64.exe') == 'exe');
        $this->assertTrue(StringUtils::getPathExtension('\\Files/CCleaner/CCleaner64.exe') == 'exe');
        $this->assertTrue(StringUtils::getPathExtension('//folder/folder2/folder3/file.txt') == 'txt');
        $this->assertTrue(StringUtils::getPathExtension('CCleaner64.exe') == 'exe');
        $this->assertTrue(StringUtils::getPathExtension('\\\\\\CCleaner64.exe') == 'exe');
        $this->assertTrue(StringUtils::getPathExtension('\\some long path containing lots of spaces\\///CCleaner64.exe') == 'exe');
        $this->assertTrue(StringUtils::getPathExtension('CCleaner64.EXE') == 'EXE');
        $this->assertTrue(StringUtils::getPathExtension('\\\\\\CCleaner64.eXEfile') == 'eXEfile');
        $this->assertTrue(StringUtils::getPathExtension("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe") == 'exe');
        $this->assertTrue(StringUtils::getPathExtension("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64") == '');

        $this->assertSame(StringUtils::getPathExtension("folder1.a.b.txt\\\\folder2//folder3///\\\\folder4", 0), 'txt');
        $this->assertSame(StringUtils::getPathExtension("folder1\\\\folder2.jpg//folder3///\\\\folder4", 1), 'jpg');
        $this->assertSame(StringUtils::getPathExtension("folder1\\\\folder2//folder3///\\\\folder4", 2), '');
        $this->assertSame(StringUtils::getPathExtension("folder1\\\\folder2//folder3///\\\\folder4.txt", 3), 'txt');
        $this->assertSame(StringUtils::getPathExtension("folder1\\\\folder2//folder3///\\\\folder4", 3), '');
        $this->assertSame(StringUtils::getPathExtension('//folder/folder2/folder3/file.txt', 0), '');
        $this->assertSame(StringUtils::getPathExtension('//folder/folder2/folder3/file.txt', 3), 'txt');
        $this->assertSame(StringUtils::getPathExtension('https://www.google.es/search?q=zero+latency', 0), '');
        $this->assertSame(StringUtils::getPathExtension('https://www.google.es/search?q=zero+latency', 2), '');
        $this->assertSame(StringUtils::getPathExtension('https:\\www.google.es/search?q=zero\\+latency', 0), '');
        $this->assertSame(StringUtils::getPathExtension('https:\\www.google.es/search?q=zero.html', 2), 'html');

        $this->assertTrue(StringUtils::getPathExtension('//folder/folder2.txt/folder3/file-extension.txt', -1, '-') == 'extension.txt');
        $this->assertTrue(StringUtils::getPathExtension('//folder/folder2.txt/folder3/file-extension.txt', 1, '-') == '');
        $this->assertTrue(StringUtils::getPathExtension('//folder/folder2.txt/folder3/file-extension.txt', 0, 'd') == 'er');

        // Test wrong values
        try {
            StringUtils::getPathExtension('//folder/folder2/folder3/file.txt', 4);
            $this->exceptionMessage = 'index 4 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::getPathExtension('//folder/folder2/folder3/file.txt', 100);
            $this->exceptionMessage = 'index 100 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::getPathExtension('//folder/folder2/folder3/file.txt', -10);
            $this->exceptionMessage = 'index -10 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test exceptions
        try {
            StringUtils::getPathExtension(['//folder/folder2/folder3/file.txt']);
            $this->exceptionMessage = 'array did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::getPathExtension(125);
            $this->exceptionMessage = '125 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::getPathExtension(new stdClass());
            $this->exceptionMessage = 'new stdClass() did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }
    }


    /**
     * testGetSchemeFromUrl
     *
     * @return void
     */
    public function testGetSchemeFromUrl(){

        // TODO - copy from js!!
        $this->markTestIncomplete('This test has not been implemented yet.');

    }


    /**
     * testFormatCase
     *
     * @return void
     */
    public function testFormatCase(){

        /** Defines the list of string case available formats */
        $caseFormats = ['',
                StringUtils::FORMAT_SENTENCE_CASE,
                StringUtils::FORMAT_START_CASE,
                StringUtils::FORMAT_ALL_UPPER_CASE,
                StringUtils::FORMAT_ALL_LOWER_CASE,
                StringUtils::FORMAT_FIRST_UPPER_REST_LOWER,
                StringUtils::FORMAT_CAMEL_CASE,
                StringUtils::FORMAT_UPPER_CAMEL_CASE,
                StringUtils::FORMAT_LOWER_CAMEL_CASE,
                StringUtils::FORMAT_SNAKE_CASE,
                StringUtils::FORMAT_UPPER_SNAKE_CASE,
                StringUtils::FORMAT_LOWER_SNAKE_CASE
        ];

        // test empty cases on all possible formats
        foreach ($caseFormats as $caseFormat) {

            try {
                StringUtils::formatCase(null, $caseFormat);
                $this->exceptionMessage = 'null did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
            try {
                StringUtils::formatCase([], $caseFormat);
                $this->exceptionMessage = '[] did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
            $this->assertTrue(StringUtils::formatCase('', $caseFormat) === '');
            $this->assertTrue(StringUtils::formatCase('       ', $caseFormat) === '       ');
            $this->assertTrue(StringUtils::formatCase("\n\n\n", $caseFormat) === "\n\n\n");
        }

        // Test FORMAT_SENTENCE_CASE values
        $this->assertTrue(StringUtils::formatCase('h', StringUtils::FORMAT_SENTENCE_CASE) === 'H');
        $this->assertTrue(StringUtils::formatCase('HI', StringUtils::FORMAT_SENTENCE_CASE) === 'HI');
        $this->assertTrue(StringUtils::formatCase('hello', StringUtils::FORMAT_SENTENCE_CASE) === 'Hello');
        $this->assertTrue(StringUtils::formatCase('Helló. únder Ü??', StringUtils::FORMAT_SENTENCE_CASE) === 'Helló. Únder Ü??');
        $this->assertTrue(StringUtils::formatCase('óyeà!!!üst??', StringUtils::FORMAT_SENTENCE_CASE) === 'Óyeà!!!Üst??');
        $this->assertTrue(StringUtils::formatCase('Hello. people', StringUtils::FORMAT_SENTENCE_CASE) === 'Hello. People');
        $this->assertTrue(StringUtils::formatCase('Hello. pEPOLE', StringUtils::FORMAT_SENTENCE_CASE) === 'Hello. PEPOLE');
        $this->assertTrue(StringUtils::formatCase('Hello  .  p !!', StringUtils::FORMAT_SENTENCE_CASE) === 'Hello  .  P !!');
        $this->assertTrue(StringUtils::formatCase('hellO.   hOw, are YOU? today!, , and ??', StringUtils::FORMAT_SENTENCE_CASE) === 'HellO.   HOw, are YOU? Today!, , And ??');
        $this->assertTrue(StringUtils::formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!?you.!  ", StringUtils::FORMAT_SENTENCE_CASE) === "Över! Còmpléx.   \n\n\n\t\t   Ís test!Is?For!?!?You.!  ");
        $this->assertTrue(StringUtils::formatCase('this is a sentence. this is another sentence. wow! what?', StringUtils::FORMAT_SENTENCE_CASE) === 'This is a sentence. This is another sentence. Wow! What?');
        $this->assertTrue(StringUtils::formatCase("some sentence,test. we will check.\n\n if this works! now", StringUtils::FORMAT_SENTENCE_CASE) === "Some sentence,test. We will check.\n\n If this works! Now");
        $this->assertTrue(StringUtils::formatCase("heLLo.\n\npEoPle\t\t.no!way!!", StringUtils::FORMAT_SENTENCE_CASE) === "HeLLo.\n\nPEoPle\t\t.No!Way!!");

        // Test FORMAT_START_CASE values
        $this->assertSame('H', StringUtils::formatCase('h', StringUtils::FORMAT_START_CASE));
        $this->assertSame('Hi', StringUtils::formatCase('HI', StringUtils::FORMAT_START_CASE));
        $this->assertSame('Hello', StringUtils::formatCase('hello', StringUtils::FORMAT_START_CASE));
        $this->assertSame('Helló. Únder Ü??', StringUtils::formatCase('helló. únder Ü??', StringUtils::FORMAT_START_CASE));
        $this->assertSame('Óyeà!!! Üst??', StringUtils::formatCase('óyeà!!! üst??', StringUtils::FORMAT_START_CASE));
        $this->assertSame('Hello People', StringUtils::formatCase('Hello people', StringUtils::FORMAT_START_CASE));
        $this->assertSame('Hello People', StringUtils::formatCase('Hello pEOPLE', StringUtils::FORMAT_START_CASE));
        $this->assertSame("Över! Còmpléx.   \n\n\n\t\t   Ís Test! Is? For!?!? You.!  ", StringUtils::formatCase("över! còmpléx.   \n\n\n\t\t   ís test! is? for!?!? you.!  ", StringUtils::FORMAT_START_CASE));
        $this->assertSame('形声字 / 形聲字', StringUtils::formatCase('形声字 / 形聲字', StringUtils::FORMAT_START_CASE));

        // Test FORMAT_ALL_UPPER_CASE values
        $this->assertTrue(StringUtils::formatCase('h', StringUtils::FORMAT_ALL_UPPER_CASE) === 'H');
        $this->assertTrue(StringUtils::formatCase('HI', StringUtils::FORMAT_ALL_UPPER_CASE) === 'HI');
        $this->assertTrue(StringUtils::formatCase('hello', StringUtils::FORMAT_ALL_UPPER_CASE) === 'HELLO');
        $this->assertTrue(StringUtils::formatCase('helló. únder Ü??', StringUtils::FORMAT_ALL_UPPER_CASE) === 'HELLÓ. ÚNDER Ü??');
        $this->assertTrue(StringUtils::formatCase('óyeà!!! üst??', StringUtils::FORMAT_ALL_UPPER_CASE) === 'ÓYEÀ!!! ÜST??');
        $this->assertTrue(StringUtils::formatCase('Hello people', StringUtils::FORMAT_ALL_UPPER_CASE) === 'HELLO PEOPLE');
        $this->assertTrue(StringUtils::formatCase('Hello pEOPLE', StringUtils::FORMAT_ALL_UPPER_CASE) === 'HELLO PEOPLE');
        $this->assertTrue(StringUtils::formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils::FORMAT_ALL_UPPER_CASE) === "ÖVER! CÒMPLÉX.   \n\n\n\t\t   ÍS TEST!IS?FOR!?!? YOU.!  ");

        // Test FORMAT_ALL_LOWER_CASE values
        $this->assertTrue(StringUtils::formatCase('h', StringUtils::FORMAT_ALL_LOWER_CASE) === 'h');
        $this->assertTrue(StringUtils::formatCase('HI', StringUtils::FORMAT_ALL_LOWER_CASE) === 'hi');
        $this->assertTrue(StringUtils::formatCase('hello', StringUtils::FORMAT_ALL_LOWER_CASE) === 'hello');
        $this->assertTrue(StringUtils::formatCase('helló. únder Ü??', StringUtils::FORMAT_ALL_LOWER_CASE) === 'helló. únder ü??');
        $this->assertTrue(StringUtils::formatCase('óyeà!!! üst??', StringUtils::FORMAT_ALL_LOWER_CASE) === 'óyeà!!! üst??');
        $this->assertTrue(StringUtils::formatCase('Hello people', StringUtils::FORMAT_ALL_LOWER_CASE) === 'hello people');
        $this->assertTrue(StringUtils::formatCase('Hello pEOPLE', StringUtils::FORMAT_ALL_LOWER_CASE) === 'hello people');
        $this->assertTrue(StringUtils::formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils::FORMAT_ALL_LOWER_CASE) === "över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ");

        // Test FORMAT_FIRST_UPPER_REST_LOWER values
        $this->assertTrue(StringUtils::formatCase('h', StringUtils::FORMAT_FIRST_UPPER_REST_LOWER) === 'H');
        $this->assertTrue(StringUtils::formatCase('HI', StringUtils::FORMAT_FIRST_UPPER_REST_LOWER) === 'Hi');
        $this->assertTrue(StringUtils::formatCase('hello', StringUtils::FORMAT_FIRST_UPPER_REST_LOWER) === 'Hello');
        $this->assertTrue(StringUtils::formatCase('helló. únder Ü??', StringUtils::FORMAT_FIRST_UPPER_REST_LOWER) === 'Helló. únder ü??');
        $this->assertTrue(StringUtils::formatCase('óyeà!!! üst??', StringUtils::FORMAT_FIRST_UPPER_REST_LOWER) === 'Óyeà!!! üst??');
        $this->assertTrue(StringUtils::formatCase('Hello people', StringUtils::FORMAT_FIRST_UPPER_REST_LOWER) === 'Hello people');
        $this->assertTrue(StringUtils::formatCase('Hello pEOPLE', StringUtils::FORMAT_FIRST_UPPER_REST_LOWER) === 'Hello people');
        $this->assertTrue(StringUtils::formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils::FORMAT_FIRST_UPPER_REST_LOWER) === "Över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ");

        // Test FORMAT_SNAKE_CASE values
        $this->assertTrue(StringUtils::formatCase('h', StringUtils::FORMAT_SNAKE_CASE) === 'h');
        $this->assertTrue(StringUtils::formatCase('0', StringUtils::FORMAT_SNAKE_CASE) === '0');
        $this->assertTrue(StringUtils::formatCase('ü', StringUtils::FORMAT_SNAKE_CASE) === 'ü');
        $this->assertTrue(StringUtils::formatCase('HI', StringUtils::FORMAT_SNAKE_CASE) === 'HI');
        $this->assertTrue(StringUtils::formatCase('CamelCase', StringUtils::FORMAT_SNAKE_CASE) === 'Camel_Case');
        $this->assertTrue(StringUtils::formatCase('CamelCCase', StringUtils::FORMAT_SNAKE_CASE) === 'Camel_C_Case');
        $this->assertTrue(StringUtils::formatCase('cámel.Case', StringUtils::FORMAT_SNAKE_CASE) === 'cámel.Case');
        $this->assertTrue(StringUtils::formatCase('Camel01C01', StringUtils::FORMAT_SNAKE_CASE) === 'Camel01_C01');
        $this->assertTrue(StringUtils::formatCase('HTTPConnection', StringUtils::FORMAT_SNAKE_CASE) === 'HTTP_Connection');
        $this->assertTrue(StringUtils::formatCase('sNake_Case', StringUtils::FORMAT_SNAKE_CASE) === 'sNake_Case');
        $this->assertTrue(StringUtils::formatCase('IbsReleaseTestVerificationRegressionSuite', StringUtils::FORMAT_SNAKE_CASE) === 'Ibs_Release_Test_Verification_Regression_Suite');
        $this->assertTrue(StringUtils::formatCase('helloWorld', StringUtils::FORMAT_SNAKE_CASE) === 'hello_World');
        $this->assertTrue(StringUtils::formatCase('üéllòWorld', StringUtils::FORMAT_SNAKE_CASE) === 'üéllòWorld');
        $this->assertTrue(StringUtils::formatCase('óyeà!!! üst??', StringUtils::FORMAT_SNAKE_CASE) === 'óyeà!!!_üst??');
        $this->assertTrue(StringUtils::formatCase('this is some random text', StringUtils::FORMAT_SNAKE_CASE) === 'this_is_some_random_text');
        $this->assertTrue(StringUtils::formatCase('óTher Randóm tëxt', StringUtils::FORMAT_SNAKE_CASE) === 'óTher_Randóm_tëxt');
        $this->assertTrue(StringUtils::formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils::FORMAT_SNAKE_CASE) === "över!_còmpléx.___\n\n\n\t\t___ís_test!is?for!?!?_you.!__");

        // Test FORMAT_UPPER_SNAKE_CASE values
        $this->assertTrue(StringUtils::formatCase('h', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'H');
        $this->assertTrue(StringUtils::formatCase('0', StringUtils::FORMAT_UPPER_SNAKE_CASE) === '0');
        $this->assertTrue(StringUtils::formatCase('ü', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'Ü');
        $this->assertTrue(StringUtils::formatCase('HI', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'HI');
        $this->assertTrue(StringUtils::formatCase('CamelCase', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'CAMEL_CASE');
        $this->assertTrue(StringUtils::formatCase('CamelCCase', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'CAMEL_C_CASE');
        $this->assertTrue(StringUtils::formatCase('cámel.Case', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'CÁMEL.CASE');
        $this->assertTrue(StringUtils::formatCase('Camel01C01', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'CAMEL01_C01');
        $this->assertTrue(StringUtils::formatCase('HTTPConnection', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'HTTP_CONNECTION');
        $this->assertTrue(StringUtils::formatCase('sNake_Case', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'SNAKE_CASE');
        $this->assertTrue(StringUtils::formatCase('IbsReleaseTestVerificationRegressionSuite', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'IBS_RELEASE_TEST_VERIFICATION_REGRESSION_SUITE');
        $this->assertTrue(StringUtils::formatCase('helloWorld', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'HELLO_WORLD');
        $this->assertTrue(StringUtils::formatCase('üéllòWorld', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'ÜÉLLÒWORLD');
        $this->assertTrue(StringUtils::formatCase('óyeà!!! üst??', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'ÓYEÀ!!!_ÜST??');
        $this->assertTrue(StringUtils::formatCase('this is some random text', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'THIS_IS_SOME_RANDOM_TEXT');
        $this->assertTrue(StringUtils::formatCase('óTher Randóm tëxt', StringUtils::FORMAT_UPPER_SNAKE_CASE) === 'ÓTHER_RANDÓM_TËXT');
        $this->assertTrue(StringUtils::formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils::FORMAT_UPPER_SNAKE_CASE) === "ÖVER!_CÒMPLÉX.___\n\n\n\t\t___ÍS_TEST!IS?FOR!?!?_YOU.!__");

        // Test FORMAT_LOWER_SNAKE_CASE values
        $this->assertTrue(StringUtils::formatCase('h', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'h');
        $this->assertTrue(StringUtils::formatCase('0', StringUtils::FORMAT_LOWER_SNAKE_CASE) === '0');
        $this->assertTrue(StringUtils::formatCase('ü', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'ü');
        $this->assertTrue(StringUtils::formatCase('HI', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'hi');
        $this->assertTrue(StringUtils::formatCase('CamelCase', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'camel_case');
        $this->assertTrue(StringUtils::formatCase('CamelCCase', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'camel_c_case');
        $this->assertTrue(StringUtils::formatCase('cámel.Case', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'cámel.case');
        $this->assertTrue(StringUtils::formatCase('Camel01C01', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'camel01_c01');
        $this->assertTrue(StringUtils::formatCase('HTTPConnection', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'http_connection');
        $this->assertTrue(StringUtils::formatCase('sNake_Case', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'snake_case');
        $this->assertTrue(StringUtils::formatCase('IbsReleaseTestVerificationRegressionSuite', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'ibs_release_test_verification_regression_suite');
        $this->assertTrue(StringUtils::formatCase('helloWorld', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'hello_world');
        $this->assertTrue(StringUtils::formatCase('üéllòWorld', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'üéllòworld');
        $this->assertTrue(StringUtils::formatCase('óyeà!!! üst??', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'óyeà!!!_üst??');
        $this->assertTrue(StringUtils::formatCase('this is some random text', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'this_is_some_random_text');
        $this->assertTrue(StringUtils::formatCase('óTher Randóm tëxt', StringUtils::FORMAT_LOWER_SNAKE_CASE) === 'óther_randóm_tëxt');
        $this->assertTrue(StringUtils::formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils::FORMAT_LOWER_SNAKE_CASE) === "över!_còmpléx.___\n\n\n\t\t___ís_test!is?for!?!?_you.!__");

        // Test FORMAT_CAMEL_CASE values
        $this->assertTrue(StringUtils::formatCase('h', StringUtils::FORMAT_CAMEL_CASE) === 'h');
        $this->assertTrue(StringUtils::formatCase('0', StringUtils::FORMAT_CAMEL_CASE) === '0');
        $this->assertTrue(StringUtils::formatCase('ü', StringUtils::FORMAT_CAMEL_CASE) === 'u');
        $this->assertTrue(StringUtils::formatCase('hI', StringUtils::FORMAT_CAMEL_CASE) === 'hI');
        $this->assertTrue(StringUtils::formatCase('HI', StringUtils::FORMAT_CAMEL_CASE) === 'HI');
        $this->assertTrue(StringUtils::formatCase('Ü        ', StringUtils::FORMAT_CAMEL_CASE) === 'U');
        $this->assertTrue(StringUtils::formatCase('CamelCase', StringUtils::FORMAT_CAMEL_CASE) === 'CamelCase');
        $this->assertTrue(StringUtils::formatCase('camelCase', StringUtils::FORMAT_CAMEL_CASE) === 'camelCase');
        $this->assertTrue(StringUtils::formatCase('camelCaSE', StringUtils::FORMAT_CAMEL_CASE) === 'camelCaSE');
        $this->assertTrue(StringUtils::formatCase('camel CaSE', StringUtils::FORMAT_CAMEL_CASE) === 'camelCaSE');
        $this->assertTrue(StringUtils::formatCase('Camel Case', StringUtils::FORMAT_CAMEL_CASE) === 'CamelCase');
        $this->assertTrue(StringUtils::formatCase('HTTP   Connection', StringUtils::FORMAT_CAMEL_CASE) === 'HTTPConnection');
        $this->assertTrue(StringUtils::formatCase('sNake_Case', StringUtils::FORMAT_CAMEL_CASE) === 'sNakeCase');
        $this->assertTrue(StringUtils::formatCase('Ibs Release Test Verification Regression Suite', StringUtils::FORMAT_CAMEL_CASE) === 'IbsReleaseTestVerificationRegressionSuite');
        $this->assertTrue(StringUtils::formatCase('üéllò World', StringUtils::FORMAT_CAMEL_CASE) === 'uelloWorld');
        $this->assertTrue(StringUtils::formatCase('óyeà!!! üst??', StringUtils::FORMAT_CAMEL_CASE) === 'oyeaUst');
        $this->assertTrue(StringUtils::formatCase('this is some random text', StringUtils::FORMAT_CAMEL_CASE) === 'thisIsSomeRandomText');
        $this->assertTrue(StringUtils::formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils::FORMAT_CAMEL_CASE) === 'overComplexIsTestIsForYou');

        // Test FORMAT_UPPER_CAMEL_CASE values
        $this->assertTrue(StringUtils::formatCase('h', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'H');
        $this->assertTrue(StringUtils::formatCase('0', StringUtils::FORMAT_UPPER_CAMEL_CASE) === '0');
        $this->assertTrue(StringUtils::formatCase('ü', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'U');
        $this->assertTrue(StringUtils::formatCase('hI', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'HI');
        $this->assertTrue(StringUtils::formatCase('HI', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'HI');
        $this->assertTrue(StringUtils::formatCase('Ü        ', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'U');
        $this->assertTrue(StringUtils::formatCase('CamelCase', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'CamelCase');
        $this->assertTrue(StringUtils::formatCase('camelCase', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'CamelCase');
        $this->assertTrue(StringUtils::formatCase('camelCaSE', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'CamelCaSE');
        $this->assertTrue(StringUtils::formatCase('camel CaSE', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'CamelCaSE');
        $this->assertTrue(StringUtils::formatCase('Camel Case', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'CamelCase');
        $this->assertTrue(StringUtils::formatCase('HTTP   Connection', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'HTTPConnection');
        $this->assertTrue(StringUtils::formatCase('sNake_Case', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'SNakeCase');
        $this->assertTrue(StringUtils::formatCase('Ibs Release Test Verification Regression Suite', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'IbsReleaseTestVerificationRegressionSuite');
        $this->assertTrue(StringUtils::formatCase('üéllò World', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'UelloWorld');
        $this->assertTrue(StringUtils::formatCase('óyeà!!! üst??', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'OyeaUst');
        $this->assertTrue(StringUtils::formatCase('this is some random text', StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'ThisIsSomeRandomText');
        $this->assertTrue(StringUtils::formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils::FORMAT_UPPER_CAMEL_CASE) === 'OverComplexIsTestIsForYou');

        // Test FORMAT_LOWER_CAMEL_CASE values
        $this->assertTrue(StringUtils::formatCase('h', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'h');
        $this->assertTrue(StringUtils::formatCase('0', StringUtils::FORMAT_LOWER_CAMEL_CASE) === '0');
        $this->assertTrue(StringUtils::formatCase('ü', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'u');
        $this->assertTrue(StringUtils::formatCase('hI', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'hI');
        $this->assertTrue(StringUtils::formatCase('HI', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'hI');
        $this->assertTrue(StringUtils::formatCase('Ü        ', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'u');
        $this->assertTrue(StringUtils::formatCase('CamelCase', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'camelCase');
        $this->assertTrue(StringUtils::formatCase('camelCase', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'camelCase');
        $this->assertTrue(StringUtils::formatCase('camelCaSE', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'camelCaSE');
        $this->assertTrue(StringUtils::formatCase('camel CaSE', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'camelCaSE');
        $this->assertTrue(StringUtils::formatCase('Camel Case', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'camelCase');
        $this->assertTrue(StringUtils::formatCase('HTTP   Connection', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'hTTPConnection');
        $this->assertTrue(StringUtils::formatCase('sNake_Case', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'sNakeCase');
        $this->assertTrue(StringUtils::formatCase('Ibs Release Test Verification Regression Suite', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'ibsReleaseTestVerificationRegressionSuite');
        $this->assertTrue(StringUtils::formatCase('üéllò World', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'uelloWorld');
        $this->assertTrue(StringUtils::formatCase('óyeà!!! üst??', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'oyeaUst');
        $this->assertTrue(StringUtils::formatCase('this is some random text', StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'thisIsSomeRandomText');
        $this->assertTrue(StringUtils::formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils::FORMAT_LOWER_CAMEL_CASE) === 'overComplexIsTestIsForYou');

        // Test exception cases
        try {
            StringUtils::formatCase('helloWorld', '');
            $this->exceptionMessage = 'empty string did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::formatCase(1, StringUtils::FORMAT_SENTENCE_CASE);
            $this->exceptionMessage = '1 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::formatCase([1,2,3], StringUtils::FORMAT_SENTENCE_CASE);
            $this->exceptionMessage = '[1,2,3] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::formatCase('Hello', 'invalidformat');
            $this->exceptionMessage = 'invalidformat did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }
    }


    /**
     * TestFormatPath
     *
     * @return void
     */
    public function testFormatPath(){

        // Test empty values
        try {
            StringUtils::formatPath(null);
            $this->exceptionMessage = 'null did not cause exception';
        } catch (Throwable $e) {
            $this->assertRegExp('/path must be a string/', $e->getMessage());
        }

        $this->assertSame(StringUtils::formatPath(''), '');
        $this->assertSame(StringUtils::formatPath('       '), '       ');
        $this->assertSame(StringUtils::formatPath("\n\n\n\n"), "\n\n\n\n");

        try {
            StringUtils::formatPath([]);
            $this->exceptionMessage = '[] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::formatPath('somepath', null);
            $this->exceptionMessage = 'null did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::formatPath('somepath', '');
            $this->exceptionMessage = '"" did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::formatPath('somepath', '     ');
            $this->exceptionMessage = '"     " did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::formatPath('somepath', "\n\n\n\n");
            $this->exceptionMessage = '"\n\n\n\n" did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test ok values
        $this->assertSame('/', StringUtils::formatPath('/'));
        $this->assertSame('/a', StringUtils::formatPath('/a'));
        $this->assertSame('/', StringUtils::formatPath('///////'));
        $this->assertSame(StringUtils::formatPath('test//test/'), 'test/test');
        $this->assertSame(StringUtils::formatPath('////test//////test////'), '/test/test');
        $this->assertSame(StringUtils::formatPath('\\\\////test//test/'), '/test/test');
        $this->assertSame(StringUtils::formatPath('test\\test/hello\\\\'), 'test/test/hello');
        $this->assertSame(StringUtils::formatPath('someutf8_//转注字\\\\轉注/字'), 'someutf8_/转注字/轉注/字');

        $this->assertSame(StringUtils::formatPath('test//test', '/'), 'test/test');
        $this->assertSame(StringUtils::formatPath('////test//////test', '/'), '/test/test');
        $this->assertSame(StringUtils::formatPath('\\\\////test//test', '/'), '/test/test');
        $this->assertSame(StringUtils::formatPath('C:\\Users///someuser\\git\\\\project/newProject', '/'), 'C:/Users/someuser/git/project/newProject');
        $this->assertSame(StringUtils::formatPath('someutf8_//转注字\\\\轉注/字', '/'), 'someutf8_/转注字/轉注/字');

        $this->assertSame(StringUtils::formatPath('test//test/', '\\'), 'test\\test');
        $this->assertSame(StringUtils::formatPath('////test//////test////', '\\'), '\\test\\test');
        $this->assertSame(StringUtils::formatPath('\\\\////test//test/', '\\'), '\\test\\test');
        $this->assertSame(StringUtils::formatPath('C:\\Users///someuser\\git\\\\project/newProject', '\\'), 'C:\\Users\\someuser\\git\\project\\newProject');
        $this->assertSame(StringUtils::formatPath('someutf8_//转注字\\\\轉注/字', '\\'), 'someutf8_\\转注字\\轉注\\字');

        $this->assertSame('\\', StringUtils::formatPath('/', '\\'));
        $this->assertSame('\\a', StringUtils::formatPath('/a', '\\'));
        $this->assertSame('\\', StringUtils::formatPath('///////', '\\'));
        $this->assertSame('test\\test', StringUtils::formatPath('test//test/', '\\'));
        $this->assertSame('\\test\\test', StringUtils::formatPath('\\\\////test//test/', '\\'));
        $this->assertSame('test\\test\\hello', StringUtils::formatPath('test\\test/hello\\\\', '\\'));
        $this->assertSame('someutf8_\\转注字\\轉注\\字', StringUtils::formatPath('someutf8_//转注字\\\\轉注/字', '\\'));

        // Test wrong values
        $this->assertSame(StringUtils::formatPath('!"(&%"·$|||'), '!"(&%"·$|||');
        $this->assertSame(StringUtils::formatPath("\n\ntest//wrong", '/'), "\n\ntest/wrong");
        $this->assertSame(StringUtils::formatPath("_____"), "_____");

        // Test exceptions
        try {
            StringUtils::formatPath(['1']);
            $this->exceptionMessage = '[1] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::formatPath(1);
            $this->exceptionMessage = '1 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::formatPath('path/path', 'W');
            $this->exceptionMessage = 'W did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::formatPath('path/path', 9);
            $this->exceptionMessage = '9 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }
    }


    /**
     * testFormatUrl
     *
     * @return void
     */
    public function testFormatUrl(){

        // TODO copy from js
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testFormatForFullTextSearch
     *
     * @return void
     */
    public function testFormatForFullTextSearch(){

        // Test empty values
        AssertUtils::throwsException(function(){ StringUtils::formatForFullTextSearch(null); }, '/value is not a string/');
        AssertUtils::throwsException(function(){ StringUtils::formatForFullTextSearch([]); }, '/value is not a string/');
        $this->assertSame('', StringUtils::formatForFullTextSearch(''));

        // Test ok values
        $this->assertSame('', StringUtils::formatForFullTextSearch('          '));
        $this->assertSame('a', StringUtils::formatForFullTextSearch('a'));
        $this->assertSame('a', StringUtils::formatForFullTextSearch('A'));
        $this->assertSame('aa', StringUtils::formatForFullTextSearch('aA'));
        $this->assertSame('a', StringUtils::formatForFullTextSearch('  A'));
        $this->assertSame('ab', StringUtils::formatForFullTextSearch('  A    B   '));
        $this->assertSame('123345345', StringUtils::formatForFullTextSearch('123   345345'));
        $this->assertSame('cassicaccentsooo', StringUtils::formatForFullTextSearch(' cássíc àccents óOÖ'));
        $this->assertSame('word1word2word3', StringUtils::formatForFullTextSearch('  word 1 word2 W-oRD3'));
        $this->assertSame('1', StringUtils::formatForFullTextSearch('   &1%&!/&$!/"&%/!"    '));
        $this->assertSame('hello', StringUtils::formatForFullTextSearch('   &%&!/&$!/"&heLLO%/!"    '));
        $this->assertSame('形声字形聲字', StringUtils::formatForFullTextSearch('形声字   /   形聲字'));
        $this->assertSame('ahfty1237aa形聲字αβγδδεzζhηaiioo', StringUtils::formatForFullTextSearch('AhFTY$%!&"$·/1237aA   形...//(()聲()?---字αβγΔδεZζHηÁíÏÖÒ'));

        $this->assertNotFalse(strpos(StringUtils::formatForFullTextSearch('  A'), StringUtils::formatForFullTextSearch('a')));
        $this->assertNotFalse(strpos(StringUtils::formatForFullTextSearch('  A'), StringUtils::formatForFullTextSearch('  a')));
        $this->assertNotFalse(strpos(StringUtils::formatForFullTextSearch('  A'), StringUtils::formatForFullTextSearch('      a')));
        $this->assertNotFalse(strpos(StringUtils::formatForFullTextSearch('  word 1 word2 W-oR=)D3'), StringUtils::formatForFullTextSearch('WORD2')));
        $this->assertNotFalse(strpos(StringUtils::formatForFullTextSearch('  word 1 word2 W-oR.D3'), StringUtils::formatForFullTextSearch('WORD3')));
        $this->assertNotFalse(strpos(StringUtils::formatForFullTextSearch('  word 1 word2 W-oR.D3'), StringUtils::formatForFullTextSearch('word1')));
        $this->assertNotFalse(strpos(StringUtils::formatForFullTextSearch('  word 1 word2 W-oR.D3'), StringUtils::formatForFullTextSearch('word1word2')));
        $this->assertNotFalse(strpos(StringUtils::formatForFullTextSearch('  word 1 word2 W-oR.D3'), StringUtils::formatForFullTextSearch('word1 word2')));
        $this->assertNotFalse(strpos(StringUtils::formatForFullTextSearch('  word 1 word2 W-oR.D3'), StringUtils::formatForFullTextSearch('Word1word2WORD3')));
        $this->assertNotFalse(strpos(StringUtils::formatForFullTextSearch('  word 1 word2 W-!""·$$%/oR.D3'), StringUtils::formatForFullTextSearch('   word1  word2     WORD3')));
        $this->assertNotFalse(strpos(StringUtils::formatForFullTextSearch('AhFTY$%!&"$·/1237aA   形...//(()聲()?---字αβγΔδεZζHηÁíÏÖÒ'), StringUtils::formatForFullTextSearch('形聲字')));

        // Test wrong values
        // Not necessary

        // Test exceptions
        AssertUtils::throwsException(function(){ StringUtils::formatForFullTextSearch(12234); }, '/value is not a string/');
        AssertUtils::throwsException(function(){ StringUtils::formatForFullTextSearch([1,2,3]); }, '/value is not a string/');
        AssertUtils::throwsException(function(){ StringUtils::formatForFullTextSearch(new Exception()); }, '/value is not a string/');
    }


    /**
     * testCompareByLevenshtein
     *
     * @return void
     */
    public function testCompareByLevenshtein(){

        // Test empty values
        AssertUtils::throwsException(function(){ StringUtils::compareByLevenshtein(null, null); }, '/string1 and string2 must be strings/');
        AssertUtils::throwsException(function(){ StringUtils::compareByLevenshtein([], []); }, '/string1 and string2 must be strings/');

        $this->assertSame(0, StringUtils::compareByLevenshtein("", ""));
        $this->assertSame(0, StringUtils::compareByLevenshtein("   ", "   "));

        // Test ok values
        $this->assertSame(1, StringUtils::compareByLevenshtein("a", ""));
        $this->assertSame(1, StringUtils::compareByLevenshtein("", "a"));
        $this->assertSame(3, StringUtils::compareByLevenshtein("abc", ""));
        $this->assertSame(3, StringUtils::compareByLevenshtein("", "abc"));

        $this->assertSame(0, StringUtils::compareByLevenshtein("", ""));
        $this->assertSame(0, StringUtils::compareByLevenshtein("a", "a"));
        $this->assertSame(0, StringUtils::compareByLevenshtein("abc", "abc"));

        $this->assertSame(1, StringUtils::compareByLevenshtein("", "a"));
        $this->assertSame(1, StringUtils::compareByLevenshtein("a", "ab"));
        $this->assertSame(1, StringUtils::compareByLevenshtein("b", "ab"));
        $this->assertSame(1, StringUtils::compareByLevenshtein("ac", "abc"));
        $this->assertSame(6, StringUtils::compareByLevenshtein("abcdefg", "xabxcdxxefxgx"));

        $this->assertSame(1, StringUtils::compareByLevenshtein("a", ""));
        $this->assertSame(1, StringUtils::compareByLevenshtein("ab", "a"));
        $this->assertSame(1, StringUtils::compareByLevenshtein("ab", "b"));
        $this->assertSame(1, StringUtils::compareByLevenshtein("abc", "ac"));
        $this->assertSame(6, StringUtils::compareByLevenshtein("xabxcdxxefxgx", "abcdefg"));

        $this->assertSame(1, StringUtils::compareByLevenshtein("a", "b"));
        $this->assertSame(1, StringUtils::compareByLevenshtein("ab", "ac"));
        $this->assertSame(1, StringUtils::compareByLevenshtein("ac", "bc"));
        $this->assertSame(1, StringUtils::compareByLevenshtein("abc", "axc"));
        $this->assertSame(6, StringUtils::compareByLevenshtein("xabxcdxxefxgx", "1ab2cd34ef5g6"));

        $this->assertSame(3, StringUtils::compareByLevenshtein("example", "samples"));
        $this->assertSame(6, StringUtils::compareByLevenshtein("sturgeon", "urgently"));
        $this->assertSame(6, StringUtils::compareByLevenshtein("levenshtein", "frankenstein"));
        $this->assertSame(5, StringUtils::compareByLevenshtein("distance", "difference"));
        $this->assertSame(7, StringUtils::compareByLevenshtein("java was neat", "scala is great"));

        $this->assertSame(1, StringUtils::compareByLevenshtein("èéöÖU", "eéöÖU"));
        $this->assertSame(2, StringUtils::compareByLevenshtein("èéöÖU", "eéöOU"));
        $this->assertSame(3, StringUtils::compareByLevenshtein("èéöÖU", "eéöOu"));
        $this->assertSame(4, StringUtils::compareByLevenshtein("èéöÖU", "eèöOu"));
        $this->assertSame(5, StringUtils::compareByLevenshtein("èéöÖU", "eèöOu "));
        $this->assertSame(4, StringUtils::compareByLevenshtein("èéöÖ", "eèöOu"));

        $this->assertSame(3, StringUtils::compareByLevenshtein("HONDA", "HYUNDAI"));
        $this->assertSame(1, StringUtils::compareByLevenshtein("Honda", "honda"));
        $this->assertSame(5, StringUtils::compareByLevenshtein("honda", "HONDA"));
        $this->assertSame(3, StringUtils::compareByLevenshtein("kitten", "sitting"));

        $this->assertSame(1, StringUtils::compareByLevenshtein("形声字 / 形聲字", "形声字 A 形聲字"));
        $this->assertSame(3, StringUtils::compareByLevenshtein("形声字 / 形聲字", "1声字 A 形聲"));
        $this->assertSame(5, StringUtils::compareByLevenshtein("形声字 / 形聲字", "13字 A 形A"));
        $this->assertSame(9, StringUtils::compareByLevenshtein("形声字 / 形聲字", "sitting"));

        // Test wrong values
        // Not necessary

        // Test exceptions
        AssertUtils::throwsException(function(){ StringUtils::compareByLevenshtein(1234, 345345); }, '/string1 and string2 must be strings/');
        AssertUtils::throwsException(function(){ StringUtils::compareByLevenshtein([1, 2, 3, 4], [2, 4, 5, 6]); }, '/string1 and string2 must be strings/');
        AssertUtils::throwsException(function(){ StringUtils::compareByLevenshtein(new Exception(), new Exception()); }, '/string1 and string2 must be strings/');
    }


    /**
     * testCompareSimilarityPercent
     *
     * @return void
     */
    public function testCompareSimilarityPercent(){

        // Test empty values
        try {
            StringUtils::compareSimilarityPercent(null, null);
            $this->exceptionMessage = 'null did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::compareSimilarityPercent(null, "");
            $this->exceptionMessage = '"" did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::compareSimilarityPercent([], []);
            $this->exceptionMessage = '[] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        $this->assertSame(100, StringUtils::compareSimilarityPercent("", ""));
        $this->assertSame(0, StringUtils::compareSimilarityPercent("", "    "));
        $this->assertSame(0, StringUtils::compareSimilarityPercent("    ", ""));
        $this->assertSame(100, StringUtils::compareSimilarityPercent("   ", "   "));

        // Test ok values
        $this->assertSame(0, StringUtils::compareSimilarityPercent("a", "b"));
        $this->assertSame(25.0, StringUtils::compareSimilarityPercent("aaaa", "anUy"));
        $this->assertSame(50.0, StringUtils::compareSimilarityPercent("aa", "ab"));
        $this->assertSame(50.0, StringUtils::compareSimilarityPercent("aaaa", "aaXx"));
        $this->assertSame(75.0, StringUtils::compareSimilarityPercent("aaaa", "aaaQ"));
        $this->assertSame(80.0, StringUtils::compareSimilarityPercent("abcde", "abcd"));
        $this->assertSame(83.33333333333334, StringUtils::compareSimilarityPercent("aiuygb", "aiUygb"));
        $this->assertSame(94.44444444444444, StringUtils::compareSimilarityPercent("形声字 A 形聲字形声字 / 形聲字", "形声字 / 形聲字形声字 / 形聲字"));
        $this->assertSame(100, StringUtils::compareSimilarityPercent("形声字 / 形聲字形声字 / 形聲字", "形声字 / 形聲字形声字 / 形聲字"));

        // Test wrong values
        // Not necessary

        // Test exceptions
        try {
            StringUtils::compareSimilarityPercent(1234, 345345);
            $this->exceptionMessage = '1234 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::compareSimilarityPercent([1, 2, 3, 4], [2, 4, 5, 6]);
            $this->exceptionMessage = '[1, 2, 3, 4] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::compareSimilarityPercent(new Exception(), new Exception());
            $this->exceptionMessage = 'new Exception() did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }
    }


    /**
     * generatePath
     *
     * @return void
     */
    public function testGeneratePath(){

        // Test empty values
        AssertUtils::throwsException(function() { StringUtils::generatePath(null, null); }, '/must be of the type array, null given/');
        AssertUtils::throwsException(function() { StringUtils::generatePath(0, 0); }, '/must be of the type array, int given/');
        AssertUtils::throwsException(function() { StringUtils::generatePath([], []); }, '/must be of the type string, array given/');
        AssertUtils::throwsException(function() { StringUtils::generatePath('', []); }, '/must be of the type array, string given/');
        $this->assertEquals('', StringUtils::generatePath([], ''));
        $this->assertEquals('', StringUtils::generatePath([''], ''));
        $this->assertEquals('a', StringUtils::generatePath(['a'], ''));

        // Test ok values
        $this->assertEquals('a', StringUtils::generatePath(['a'], '/'));
        $this->assertEquals('a/b', StringUtils::generatePath(['a', 'b'], '/'));
        $this->assertEquals('a\\b', StringUtils::generatePath(['a', 'b'], '\\'));
        $this->assertEquals('a'.DIRECTORY_SEPARATOR.'b'.DIRECTORY_SEPARATOR.'c', StringUtils::generatePath(['a', 'b', 'c']));

        // Test wrong values
        // Not necessary

        // Test exceptions
        AssertUtils::throwsException(function() { StringUtils::generatePath(['a'], 'a'); }, '/separator must be a slash or backslash/');
        AssertUtils::throwsException(function() { StringUtils::generatePath([1, 2, 3, 4], 0); }, '/separator must be a slash or backslash/');
        AssertUtils::throwsException(function() { StringUtils::generatePath(123); }, '/must be of the type array, int given/');
    }


    /**
     * testGenerateRandom
     *
     * @return void
     */
    public function testGenerateRandom(){

        // Test empty values
        $this->assertTrue(StringUtils::generateRandom(0, 0) === '');

        try {
            StringUtils::generateRandom(null, null);
            $this->exceptionMessage = 'null did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::generateRandom(1, 1, null);
            $this->exceptionMessage = '[null] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::generateRandom(1, 1, ['']);
            $this->exceptionMessage = "[''] did not cause exception";
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test ok values
        $this->assertTrue(StringUtils::generateRandom(1, 1, ['T']) === 'T');
        $this->assertTrue(StringUtils::generateRandom(3, 3, ['0']) === '000');
        $this->assertTrue(StringUtils::generateRandom(5, 5, ['a']) === 'aaaaa');

        for ($i = 1; $i < 30; $i++) {

            $this->assertTrue(strlen(StringUtils::generateRandom($i, $i)) === $i);

            // test only numeric string
            $s = StringUtils::generateRandom($i, $i*2, ['0-9']);
            $this->assertTrue(NumericUtils::isNumeric($s));
            $this->assertTrue(strlen($s) >= $i && strlen($s) <= $i*2);

            $s = StringUtils::generateRandom($i, $i+1, ['3-6']);
            $this->assertTrue(NumericUtils::isNumeric($s));
            $this->assertTrue(strlen($s) >= $i && strlen($s) <= $i+1);

            for ($j = 0; $j < strlen($s); $j++) {

                $this->assertTrue(strpos('012', substr($s, $j, 1)) === false);
                $this->assertTrue(strpos('789', substr($s, $j, 1)) === false);
                $this->assertTrue(strpos('3456', substr($s, $j, 1)) !== false);
            }

            // test only lowercase alphabetic strings
            $s = StringUtils::generateRandom($i, $i*2, ['a-z']);
            $this->assertTrue(StringUtils::isString($s));
            $this->assertTrue(strlen($s) >= $i && strlen($s) <= $i*2);

            $s = StringUtils::generateRandom($i, $i+1, ['g-r']);
            $this->assertTrue(StringUtils::isString($s));
            $this->assertTrue(strlen($s) >= $i && strlen($s) <= $i+1);

            for ($j = 0; $j < strlen($s); $j++) {

                $this->assertTrue(strtolower(substr($s, $j, 1)) === substr($s, $j, 1));
                $this->assertTrue(strpos('abcdef', substr($s, $j, 1)) === false);
                $this->assertTrue(strpos('stuvwxyz', substr($s, $j, 1)) === false);
                $this->assertTrue(strpos('ghijkmnopqr', substr($s, $j, 1)) !== false);
            }

            // test only uppercase alphabetic strings
            $s = StringUtils::generateRandom($i, $i*2, ['A-Z']);
            $this->assertTrue(StringUtils::isString($s));
            $this->assertTrue(strlen($s) >= $i && strlen($s) <= $i*2);

            $s = StringUtils::generateRandom($i, $i+1, ['I-M']);
            $this->assertTrue(StringUtils::isString($s));
            $this->assertTrue(strlen($s) >= $i && strlen($s) <= $i+1);

            for ($j = 0; $j < strlen($s); $j++) {

                $this->assertTrue(strtoupper(substr($s, $j, 1)) === substr($s, $j, 1));
                $this->assertTrue(strpos('ABCDEFGH', substr($s, $j, 1)) === false);
                $this->assertTrue(strpos('NOPQRSTUVWXYZ', substr($s, $j, 1)) === false);
                $this->assertTrue(strpos('IJKM', substr($s, $j, 1)) !== false);
            }

            // Test numbers and upper case and lower case letters
            $s = StringUtils::generateRandom($i, $i*2, ['0-9', 'a-z', 'A-Z']);
            $this->assertTrue(StringUtils::isString($s));
            $this->assertTrue(strlen($s) >= $i && strlen($s) <= $i*2);

            for ($j = 0; $j < strlen($s); $j++) {

                $this->assertTrue(strpos('0123456789', substr($s, $j, 1)) !== false ||
                    strpos('abcdefghijkmnopqrstuvwxyz', substr($s, $j, 1)) !== false ||
                    strpos('ABCDEFGHIJKMNOPQRSTUVWXYZ', substr($s, $j, 1)) !== false);
            }

            // Test fixed characters set
            $s = StringUtils::generateRandom($i, $i*2, ['97hjrfHNgbf71']);
            $this->assertTrue(StringUtils::isString($s));
            $this->assertTrue(strlen($s) >= $i && strlen($s) <= $i*2);

            for ($j = 0; $j < strlen($s); $j++) {

                $this->assertTrue(strpos('97hjrfHNgbf71', substr($s, $j, 1)) !== false);
            }

            $s = StringUtils::generateRandom(1, 500, ['&/$hb\\-81679Ç+\\-']);
            $this->assertTrue(StringUtils::isString($s));
            $this->assertTrue(strlen($s) >= 1 && strlen($s) <= 500);

            for ($j = 0; $j < strlen($s); $j++) {

                $this->assertTrue(strpos('&/$hb-81679Ç+-', substr($s, $j, 1)) !== false);
            }
        }

        // Test wrong values
        // not necessary

        try {
            StringUtils::generateRandom(-1, 1);
            $this->exceptionMessage = "-1 1 did not cause exception";
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::generateRandom(1, -1);
            $this->exceptionMessage = "1, -1 did not cause exception";
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::generateRandom('some string', 1);
            $this->exceptionMessage = "some string, 1 did not cause exception";
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::generateRandom(1, 'some string');
            $this->exceptionMessage = "1, some string did not cause exception";
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::generateRandom(1, 2, 'ertr');
            $this->exceptionMessage = "1, 2, ertr did not cause exception";
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::generateRandom(1, 2, new stdClass());
            $this->exceptionMessage = "1, 2, new stdClass() did not cause exception";
        } catch (Throwable $e) {
            // We expect an exception to happen
        }
    }


    /**
     * testFindMostSimilarString
     *
     * @return void
     */
    public function testFindMostSimilarString(){

        // Test empty values
        AssertUtils::throwsException(function(){ StringUtils::findMostSimilarString(null, []); }, '/expected a string/');
        AssertUtils::throwsException(function(){ StringUtils::findMostSimilarString('', null); }, '/must be of the type array, null given/');
        AssertUtils::throwsException(function(){ StringUtils::findMostSimilarString('', []); }, '/listOfStrings is empty/');

        $this->assertSame("   ", StringUtils::findMostSimilarString("   ", ["   "]));

        // Test ok values

        $this->assertSame("", StringUtils::findMostSimilarString("a", [""]));
        $this->assertSame("", StringUtils::findMostSimilarString("a", ["", ""]));
        $this->assertSame("", StringUtils::findMostSimilarString("a", ["", "  "]));
        $this->assertSame("a", StringUtils::findMostSimilarString("a", ["", "  ", "a"]));
        $this->assertSame("b", StringUtils::findMostSimilarString("", ["b", "c", "a"]));
        $this->assertSame("a", StringUtils::findMostSimilarString("a", ["b", "c", "a"]));
        $this->assertSame("a", StringUtils::findMostSimilarString("a", ["a", "b", "c"]));
        $this->assertSame("a", StringUtils::findMostSimilarString("abc", ["a", "b", "c"]));
        $this->assertSame("b", StringUtils::findMostSimilarString("abc", ["b", "a", "c"]));
        $this->assertSame("hello", StringUtils::findMostSimilarString("hello", ["b", "a", "c", "hello"]));
        $this->assertSame("hello", StringUtils::findMostSimilarString("hello", ["a", "he", "hell", "hello"]));
        $this->assertSame("hellow", StringUtils::findMostSimilarString("hello", ["hallo", "hillo", "hell", "hellow"]));
        $this->assertSame("hell", StringUtils::findMostSimilarString("hell", ["hallo", "hillo", "hell", "hellow"]));
        $this->assertSame("xabcdxxefxgx", StringUtils::findMostSimilarString("abcdefg", ["xabxcxefxgx", "xabcdxxefxgx", "xabxcefxgx", "xabxefxgx"]));
        $this->assertSame("examplified", StringUtils::findMostSimilarString("example", ["xabxcxefxgx", "samples", "xabxcefxgx", "examplified"]));
        $this->assertSame("èéöÖU", StringUtils::findMostSimilarString("èéöÖU", ["xabxcxefxgx", "eéöU", "èéöÖU", "eeoOU"]));
        $this->assertSame("èéöU", StringUtils::findMostSimilarString("èéöÖU", ["xabxcxefxgx", "eöÖU", "èéöU", "eeoOU"]));
        $this->assertSame("compare-similarity-between-two-strings", StringUtils::findMostSimilarString("compare-similar", ["convert-text-to-camel-case", "compare-similarity-between-two-strings"]));

        // Test wrong values
        // Test exceptions

        AssertUtils::throwsException(function(){ StringUtils::findMostSimilarString(1234, 1234); }, '/must be of the type array, int(eger)? given/');
        AssertUtils::throwsException(function(){ StringUtils::findMostSimilarString("", 1234); }, '/must be of the type array, int(eger)? given/');
        AssertUtils::throwsException(function(){ StringUtils::findMostSimilarString([1, 2, 3, 4], [2, 4, 5, 6]); }, '/expected a string/');
        AssertUtils::throwsException(function(){ StringUtils::findMostSimilarString(new Exception(), new Exception()); }, '/must be of the type array, object given/');
    }


    /**
     * testFindMostSimilarStringIndex
     *
     * @return void
     */
    public function testFindMostSimilarStringIndex(){

        // Test empty values
        AssertUtils::throwsException(function(){ StringUtils::findMostSimilarStringIndex(null, []); }, '/expected a string/');
        AssertUtils::throwsException(function(){ StringUtils::findMostSimilarStringIndex('', null); }, '/must be of the type array, null given/');
        AssertUtils::throwsException(function(){ StringUtils::findMostSimilarStringIndex('', []); }, '/listOfStrings is empty/');

        $this->assertSame(0, StringUtils::findMostSimilarStringIndex("   ", ["   "]));

        // Test ok values

        $this->assertSame(0, StringUtils::findMostSimilarStringIndex("a", [""]));
        $this->assertSame(0, StringUtils::findMostSimilarStringIndex("a", ["", ""]));
        $this->assertSame(0, StringUtils::findMostSimilarStringIndex("a", ["", "  "]));
        $this->assertSame(2, StringUtils::findMostSimilarStringIndex("a", ["", "  ", "a"]));
        $this->assertSame(0, StringUtils::findMostSimilarStringIndex("", ["b", "c", "a"]));
        $this->assertSame(2, StringUtils::findMostSimilarStringIndex("a", ["b", "c", "a"]));
        $this->assertSame(0, StringUtils::findMostSimilarStringIndex("a", ["a", "b", "c"]));
        $this->assertSame(0, StringUtils::findMostSimilarStringIndex("abc", ["a", "b", "c"]));
        $this->assertSame(0, StringUtils::findMostSimilarStringIndex("abc", ["b", "a", "c"]));
        $this->assertSame(3, StringUtils::findMostSimilarStringIndex("hello", ["b", "a", "c", "hello"]));
        $this->assertSame(3, StringUtils::findMostSimilarStringIndex("hello", ["a", "he", "hell", "hello"]));
        $this->assertSame(3, StringUtils::findMostSimilarStringIndex("hello", ["hallo", "hillo", "hell", "hellow"]));
        $this->assertSame(2, StringUtils::findMostSimilarStringIndex("hell", ["hallo", "hillo", "hell", "hellow"]));
        $this->assertSame(1, StringUtils::findMostSimilarStringIndex("abcdefg", ["xabxcxefxgx", "xabcdxxefxgx", "xabxcefxgx", "xabxefxgx"]));
        $this->assertSame(3, StringUtils::findMostSimilarStringIndex("example", ["xabxcxefxgx", "samples", "xabxcefxgx", "examplified"]));
        $this->assertSame(2, StringUtils::findMostSimilarStringIndex("èéöÖU", ["xabxcxefxgx", "eéöU", "èéöÖU", "eeoOU"]));
        $this->assertSame(2, StringUtils::findMostSimilarStringIndex("èéöÖU", ["xabxcxefxgx", "eöÖU", "èéöU", "eeoOU"]));
        $this->assertSame(1, StringUtils::findMostSimilarStringIndex("compare-similar", ["convert-text-to-camel-case", "compare-similarity-between-two-strings"]));

        // Test wrong values
        // Test exceptions

        AssertUtils::throwsException(function(){ StringUtils::findMostSimilarStringIndex(1234, 1234); }, '/must be of the type array, int given/');
        AssertUtils::throwsException(function(){ StringUtils::findMostSimilarStringIndex("", 1234); }, '/must be of the type array, int given/');
        AssertUtils::throwsException(function(){ StringUtils::findMostSimilarStringIndex([1, 2, 3, 4], [2, 4, 5, 6]); }, '/expected a string/');
        AssertUtils::throwsException(function(){ StringUtils::findMostSimilarStringIndex(new Exception(), new Exception()); }, '/must be of the type array, object given/');
    }


    /**
     * testRemoveNewLineCharacters
     *
     * @return void
     */
    public function testRemoveNewLineCharacters(){

        // Test empty values
        $this->assertTrue(StringUtils::removeNewLineCharacters(null) === '');
        $this->assertTrue(StringUtils::removeNewLineCharacters('') === '');
        $this->assertTrue(StringUtils::removeNewLineCharacters('     ') === '     ');

        // Test ok values
        $this->assertTrue(StringUtils::removeNewLineCharacters("\n") === '');
        $this->assertTrue(StringUtils::removeNewLineCharacters("\n\n") === '');
        $this->assertTrue(StringUtils::removeNewLineCharacters("\r\n") === '');
        $this->assertTrue(StringUtils::removeNewLineCharacters("\n\r\r") === '');
        $this->assertTrue(StringUtils::removeNewLineCharacters("a\nb") === 'ab');
        $this->assertTrue(StringUtils::removeNewLineCharacters("a\nb\rc") === 'abc');
        $this->assertTrue(StringUtils::removeNewLineCharacters("a\nb\rc\n\nd") === 'abcd');

        // Test wrong values
        $this->assertTrue(StringUtils::removeNewLineCharacters('heollo') === 'heollo');
        $this->assertTrue(StringUtils::removeNewLineCharacters('string    and spaces') === 'string    and spaces');
        $this->assertTrue(StringUtils::removeNewLineCharacters('\t\t\t') === '\t\t\t');
        $this->assertTrue(StringUtils::removeNewLineCharacters('a\t\t') === 'a\t\t');

        // Test exceptions
        try {
            StringUtils::removeNewLineCharacters(1);
            $this->exceptionMessage = '1 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::removeNewLineCharacters([]);
            $this->exceptionMessage = '[] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::removeNewLineCharacters(new stdClass());
            $this->exceptionMessage = 'new stdClass() did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            StringUtils::removeNewLineCharacters([1,2,3,4]);
            $this->exceptionMessage = '[1,2,3,4] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }
    }


    /**
     * testRemoveAccents
     *
     * @return void
     */
    public function testRemoveAccents(){

        // Test empty values
        AssertUtils::throwsException(function(){ StringUtils::removeAccents(null); }, '/value is not a string/');

        $this->assertTrue(StringUtils::removeAccents('') === '');
        $this->assertTrue(StringUtils::removeAccents('        ') === '        ');

        // Test ok values
        $this->assertTrue(StringUtils::removeAccents('Fó Bår') === 'Fo Bar');
        $this->assertTrue(StringUtils::removeAccents("|!€%'''") === "|!€%'''");
        $this->assertTrue(StringUtils::removeAccents('hiweury asb fsuyr weqr') === 'hiweury asb fsuyr weqr');
        $this->assertTrue(StringUtils::removeAccents('!iYgh65541tGY%$$73267yt') === '!iYgh65541tGY%$$73267yt');
        $this->assertTrue(StringUtils::removeAccents('hello 12786,.123123') === 'hello 12786,.123123');
        $this->assertTrue(StringUtils::removeAccents('check this `^+*´--_{}[]') === 'check this `^+*´--_{}[]');
        $this->assertTrue(StringUtils::removeAccents('hellóóóóóí´ 12786,.123123"') === 'helloooooi´ 12786,.123123"');
        $this->assertTrue(StringUtils::removeAccents("hello\nbaby\r\ntest it well !!!!!") === "hello\nbaby\r\ntest it well !!!!!");
        $this->assertTrue(StringUtils::removeAccents('óíéàùú hello') === 'oieauu hello');
        $this->assertTrue(StringUtils::removeAccents("óóó èèè\núùúùioler    \r\noughúíééanh hello") === "ooo eee\nuuuuioler    \r\noughuieeanh hello");
        $this->assertTrue(StringUtils::removeAccents('öïüíúóèà go!!.;') === 'oiuiuoea go!!.;');

        // Test wrong values
        // Test exceptions
    }


    /**
     * testRemoveWordsShorterThan
     *
     * @return void
     */
    public function testRemoveWordsShorterThan(){

        $this->assertSame('', StringUtils::removeWordsShorterThan(null));
        $this->assertSame('', StringUtils::removeWordsShorterThan(''));
        $this->assertSame('', StringUtils::removeWordsShorterThan('', 0));
        $this->assertSame('hello', StringUtils::removeWordsShorterThan('hello', 0));
        $this->assertSame('hello', StringUtils::removeWordsShorterThan('hello', 2));
        $this->assertSame('hello', StringUtils::removeWordsShorterThan('hello', 5));
        $this->assertSame('', StringUtils::removeWordsShorterThan('hello', 6));
        $this->assertSame('', StringUtils::removeWordsShorterThan('hello by today', 6));
        $this->assertSame('hello today', StringUtils::removeWordsShorterThan('hello by today', 5));
        $this->assertSame('hello by today', StringUtils::removeWordsShorterThan('hello by today', 1));
        $this->assertSame("Line1\nline2\r\nline3  \nline4", StringUtils::removeWordsShorterThan("Line1\nline2\r\nline3 and  \nline4", 4));
        $this->assertSame("Pad string certain length   with    ", StringUtils::removeWordsShorterThan("Pad a string to a certain length   with    ", 3));

        // TODO: multi line strings do not work!
        // $this->assertTrue(StringUtils::removeWordsShorterThan("Line1 line12\nline2\r\nline3 and  \nline4", 6) === " line12\n\r\n   \n");
        // TODO: add more multi line tests
    }


    /**
     * testRemoveWordsLongerThan
     *
     * @return void
     */
    public function testRemoveWordsLongerThan(){

        $this->assertSame('', StringUtils::removeWordsLongerThan(null));
        $this->assertSame('', StringUtils::removeWordsLongerThan(''));
        $this->assertSame('', StringUtils::removeWordsLongerThan('', 0));
        $this->assertSame('', StringUtils::removeWordsLongerThan('hello', 0));
        $this->assertSame('', StringUtils::removeWordsLongerThan('hello', 2));
        $this->assertSame('hello', StringUtils::removeWordsLongerThan('hello', 5));
        $this->assertSame('hello', StringUtils::removeWordsLongerThan('hello', 6));
        $this->assertSame('hello by today', StringUtils::removeWordsLongerThan('hello by today', 6));
        $this->assertSame('hello by today', StringUtils::removeWordsLongerThan('hello by today', 5));
        $this->assertSame('hello by today   go   to ITWE', StringUtils::removeWordsLongerThan('hello by today   123456 go   to ITWE', 5));
        $this->assertSame('', StringUtils::removeWordsLongerThan('hello by today', 1));
        $this->assertSame("Pad a to a      ", StringUtils::removeWordsLongerThan("Pad a string to a certain length   with    ", 3));

        // TODO: multi line strings do not work!
        // $this->assertTrue(StringUtils::removeWordsLongerThan("Line1\nline2\r\nline3 and  \nline4", 4) === "\n\r\n and  \n");
        // $this->assertTrue(StringUtils::removeWordsLongerThan("Line1 line12\nline2\r\nline3 and  \nline4", 6) === " line12\n\r\n   \n");
        // TODO: add more multi line tests
    }


    /**
     * testRemoveUrls
     *
     * @return void
     */
    public function testRemoveUrls(){

        // TODO!!
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testRemoveHtmlCode
     *
     * @return void
     */
    public function testRemoveHtmlCode(){

        // TODO!!
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testRemoveSameConsecutive
     *
     * @return void
     */
    public function testRemoveSameConsecutive(){

        // Test empty values
        $this->assertSame('', StringUtils::removeSameConsecutive(null));
        $this->assertSame('', StringUtils::removeSameConsecutive(''));
        $this->assertSame(' ', StringUtils::removeSameConsecutive('     '));
        $this->assertSame("\n", StringUtils::removeSameConsecutive("\n\n\n\n"));
        $this->assertSame("\r", StringUtils::removeSameConsecutive("\r\r\r\r"));
        $this->assertSame("\r\n", StringUtils::removeSameConsecutive("\r\n\r\n\r\n\r\n\r\n"));

        AssertUtils::throwsException(function(){ StringUtils::removeSameConsecutive([]); }, '/string must be a string/');

        // Test ok values - ALL CHARACTERS
        $this->assertSame('a', StringUtils::removeSameConsecutive('a'));
        $this->assertSame('abc', StringUtils::removeSameConsecutive('abc'));
        $this->assertSame('a', StringUtils::removeSameConsecutive('aa'));
        $this->assertSame('abcabc', StringUtils::removeSameConsecutive('abcabc'));
        $this->assertSame('abc', StringUtils::removeSameConsecutive('aabbcc'));
        $this->assertSame("ab c\nd", StringUtils::removeSameConsecutive("abbbbbbbbbb    cccccc\n\n\nddd"));
        $this->assertSame(".8ynbvER745bhbvFGFfCc \n\r Ww?o?-", StringUtils::removeSameConsecutive("..88ynbvER745bhbvFFGFfCc   \n\n\r\r  Www?o??--"));
        $this->assertSame('形声字 / 形聲字', StringUtils::removeSameConsecutive('形声字 / 形聲字'));
        $this->assertSame('形声字 / 形聲字', StringUtils::removeSameConsecutive('形形形形声字 / 形聲字'));
        $this->assertSame("形声字形聲字 \n形声字形聲字\r ", StringUtils::removeSameConsecutive("形声字形聲字  \n\n形声字字字形聲字\r\r\r\r    "));

        // Test ok values - A SET OF CHARACTERS
        $this->assertSame(' ', StringUtils::removeSameConsecutive('       ', [' ']));
        $this->assertSame(' asdf asdf', StringUtils::removeSameConsecutive(' asdf  asdf', [' ']));
        $this->assertSame('abuu77abc7 ', StringUtils::removeSameConsecutive('aabbuu77abc7     ', ['a', 'b', ' ']));
        $this->assertSame('abu77abc7 ', StringUtils::removeSameConsecutive('aabbuu77abc7     ', ['a', 'b', ' ', 'u']));
        $this->assertSame("ab\n\r\r\n bbbbaaa", StringUtils::removeSameConsecutive("ab\n\n\r\r\n    bbbbaaa", [" ", "\n"]));
        $this->assertSame("ab\n\r\r\n    ba", StringUtils::removeSameConsecutive("ab\n\n\r\r\n    bbbbaaa", ["a", "b", "\n"]));
        $this->assertSame("ab\n\r\n    ba", StringUtils::removeSameConsecutive("ab\n\n\r\r\n    bbbbaaa",  ["a", "b", "\r", "\n"]));
        $this->assertSame("ab ab a b a b", StringUtils::removeSameConsecutive("ab abababab a b a b",  ["ab"]));
        $this->assertSame("ab abababab a b ", StringUtils::removeSameConsecutive("ab abababab a b a b ",  ["a b "]));
        $this->assertSame('形声字形形形形聲字   ', StringUtils::removeSameConsecutive('形声字字字形形形形聲字   ', ['字']));
        $this->assertSame('形声字形聲字   ', StringUtils::removeSameConsecutive('形声字字字形形形形聲字   ', ['字', '形']));
        $this->assertSame('形声字形聲字 ', StringUtils::removeSameConsecutive('形声字字字形形形形聲字   ', ['字', '形', ' ']));
        $this->assertSame("形声字形聲聲聲聲聲聲聲字 \n\r\n\r\n\r\n 聲聲聲聲", StringUtils::removeSameConsecutive("形声字字字形形形形聲聲聲聲聲聲聲字     \n\n\n\r\n\r\n\r\n     聲聲聲聲", ['字', '形', ' ', "\n"]));
        $this->assertSame("形声字形聲聲聲聲聲聲聲字 \n\r\n 聲聲聲聲", StringUtils::removeSameConsecutive("形声字字字形形形形聲聲聲聲聲聲聲字     \n\n\n\r\n\r\n\r\n     聲聲聲聲", ['字', '形', ' ', "\n", "\r\n"]));
        $this->assertSame("形声字形聲字 \n\r\n 聲", StringUtils::removeSameConsecutive("形声字字字形形形形聲聲聲聲聲聲聲字     \n\n\n\r\n\r\n\r\n     聲聲聲聲", ['字', '形', ' ', "\n", "\r\n", "聲"]));
        $this->assertSame('hello hello hello    ', StringUtils::removeSameConsecutive('hellohellohello hello hello    ', ['hello']));
        $this->assertSame('hello hello hello ', StringUtils::removeSameConsecutive('hellohellohello hello hello    ', ['hello', ' ']));

        // Test wrong values
        // Test exceptions
        AssertUtils::throwsException(function(){ StringUtils::removeSameConsecutive(123123); }, '/string must be a string/');
        AssertUtils::throwsException(function(){ StringUtils::removeSameConsecutive('abc', 'hello'); }, '/must be of the type array/');
    }
}
