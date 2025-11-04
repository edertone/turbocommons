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

use PHPUnit\Framework\TestCase;
use Throwable;
use stdClass;
use org\turbocommons\src\main\php\model\TextTemplateObject;


/**
 * TextTemplateObjectTest
 *
 * @return void
 */
class TextTemplateObjectTest extends TestCase {


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

        $this->emptyValues = [null, [], new stdClass(), 0];
        $this->emptyValuesCount = count($this->emptyValues);
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
        try {
            $test = new TextTemplateObject();
            $this->exceptionMessage = 'No parameter did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        $test = new TextTemplateObject('');
        $this->assertEquals('', $test->getText());

        $test = new TextTemplateObject('     ');
        $this->assertEquals('     ', $test->getText());

        $test = new TextTemplateObject("\n\n\n");
        $this->assertEquals("\n\n\n", $test->getText());

        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                new TextTemplateObject($this->emptyValues[$i]);
                $this->exceptionMessage = $this->emptyValues[$i].' empty value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        // Test ok values
        $test = new TextTemplateObject('a');
        $this->assertEquals('a', $test->getText());

        $test = new TextTemplateObject('hello');
        $this->assertEquals('hello', $test->getText());

        $test = new TextTemplateObject("multi\n\nLine\nText");
        $this->assertEquals("multi\n\nLine\nText", $test->getText());

        $test = new TextTemplateObject('新 あたら しい 記事 きじ を 書 か こうという 気持 きも ちになるまで 長');
        $this->assertEquals('新 あたら しい 記事 きじ を 書 か こうという 気持 きも ちになるまで 長', $test->getText());

        // Test wrong values
        // Nothing to test

        // Test exceptions
        try {
            $test = new TextTemplateObject(11);
            $this->exceptionMessage = '11 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test = new TextTemplateObject([1,2]);
            $this->exceptionMessage = '[1,2] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test = new TextTemplateObject(new stdClass());
            $this->exceptionMessage = 'new stdClass() did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // TODO - review this test
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testReplace
     *
     * @return void
     */
    public function testReplace(){

        // Test empty values
        // TODO

        // Test ok values
        // TODO

        // Test wrong values
        // TODO

        // Test exceptions
        // TODO

        // TODO
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testGetText
     *
     * @return void
     */
    public function testGetText(){

        // Test empty values
        // TODO

        // Test ok values
        // TODO

        // Test wrong values
        // TODO

        // Test exceptions
        // TODO

        // TODO
        $this->markTestIncomplete('This test has not been implemented yet.');
    }
}

?>