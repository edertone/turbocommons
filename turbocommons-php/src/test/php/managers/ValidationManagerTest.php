<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\test\php\managers;

use org\turbocommons\src\main\php\managers\ValidationManager;
use PHPUnit\Framework\TestCase;
use Throwable;
use stdClass;
use Exception;


/**
 * ValidationManager tests
 *
 * @return void
 */
class ValidationManagerTest extends TestCase {


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

        $this->validationManager = new ValidationManager();
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

        unset($this->validationManager);
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
     * testGetStatus
     *
     * @return void
     */
    public function testGetStatus(){

        // Test empty values
        $this->assertSame($this->validationManager->getStatus(), ValidationManager::OK);
        $this->assertSame($this->validationManager->getStatus(null), ValidationManager::OK);
        $this->assertSame($this->validationManager->getStatus([]), ValidationManager::OK);
        $this->assertSame($this->validationManager->getStatus(''), ValidationManager::OK);

        $this->validationManager->reset();

        // Test ok values
        $this->assertTrue($this->validationManager->isTrue(true, 'error', 'tag1'));
        $this->assertSame($this->validationManager->getStatus(), ValidationManager::OK);
        $this->assertSame($this->validationManager->getStatus('tag1'), ValidationManager::OK);

        // Test wrong values
        $this->assertFalse($this->validationManager->isTrue(false, 'error', 'tag1'));
        $this->assertTrue($this->validationManager->isTrue(true, 'error', 'tag2'));
        $this->assertFalse($this->validationManager->isTrue(false, 'error', 'tag3', true));
        $this->assertSame($this->validationManager->getStatus(), ValidationManager::ERROR);
        $this->assertSame($this->validationManager->getStatus('tag1'), ValidationManager::ERROR);
        $this->assertSame($this->validationManager->getStatus('tag2'), ValidationManager::OK);
        $this->assertSame($this->validationManager->getStatus('tag3'), ValidationManager::WARNING);
        $this->assertSame($this->validationManager->getStatus(['tag2', 'tag3']), ValidationManager::WARNING);
        $this->assertSame($this->validationManager->getStatus(['tag1', 'tag2']), ValidationManager::ERROR);
        $this->assertSame($this->validationManager->getStatus(['tag1', 'tag3']), ValidationManager::ERROR);
    }


    /**
     * testOk
     *
     * @return void
     */
    public function testOk(){

        // Test empty values
        $this->assertSame($this->validationManager->ok(), true);
        $this->assertSame($this->validationManager->ok(null), true);
        $this->assertSame($this->validationManager->ok([]), true);
        $this->assertSame($this->validationManager->ok(''), true);

        $this->validationManager->reset();

        // Test ok values
        $this->assertTrue($this->validationManager->isTrue(true, 'error', 'tag1'));
        $this->assertSame($this->validationManager->ok(), true);
        $this->assertSame($this->validationManager->ok('tag1'), true);

        // Test wrong values
        $this->assertFalse($this->validationManager->isTrue(false, 'error', 'tag1'));
        $this->assertTrue($this->validationManager->isTrue(true, 'error', 'tag2'));
        $this->assertFalse($this->validationManager->isTrue(false, 'error', 'tag3', true));
        $this->assertSame($this->validationManager->ok(), false);
        $this->assertSame($this->validationManager->ok('tag1'), false);
        $this->assertSame($this->validationManager->ok('tag2'), true);
        $this->assertSame($this->validationManager->ok('tag3'), false);
        $this->assertSame($this->validationManager->ok(['tag2', 'tag3']), false);
        $this->assertSame($this->validationManager->ok(['tag1', 'tag2']), false);
        $this->assertSame($this->validationManager->ok(['tag1', 'tag3']), false);
    }


    /**
     * testNotOk
     *
     * @return void
     */
    public function testNotOk(){

        // Test empty values
        $this->assertSame($this->validationManager->notOk(), false);
        $this->assertSame($this->validationManager->notOk(null), false);
        $this->assertSame($this->validationManager->notOk([]), false);
        $this->assertSame($this->validationManager->notOk(''), false);

        $this->validationManager->reset();

        // Test ok values
        $this->assertTrue($this->validationManager->isTrue(true, 'error', 'tag1'));
        $this->assertSame($this->validationManager->notOk(), false);
        $this->assertSame($this->validationManager->notOk('tag1'), false);

        // Test wrong values
        $this->assertFalse($this->validationManager->isTrue(false, 'error', 'tag1'));
        $this->assertTrue($this->validationManager->isTrue(true, 'error', 'tag2'));
        $this->assertFalse($this->validationManager->isTrue(false, 'error', 'tag3', true));
        $this->assertSame($this->validationManager->notOk(), true);
        $this->assertSame($this->validationManager->notOk('tag1'), true);
        $this->assertSame($this->validationManager->notOk('tag2'), false);
        $this->assertSame($this->validationManager->notOk('tag3'), true);
        $this->assertSame($this->validationManager->notOk(['tag2', 'tag3']), true);
        $this->assertSame($this->validationManager->notOk(['tag1', 'tag2']), true);
        $this->assertSame($this->validationManager->notOk(['tag1', 'tag3']), true);
    }


    /**
     * testGetFirstMessage
     *
     * @return void
     */
    public function testGetFirstMessage(){

        // Test empty values
        $this->assertSame($this->validationManager->getFirstMessage(), '');
        $this->assertSame($this->validationManager->getFirstMessage(null), '');
        $this->assertSame($this->validationManager->getFirstMessage([]), '');
        $this->assertSame($this->validationManager->getFirstMessage(''), '');

        $this->assertFalse($this->validationManager->isTrue(false, 'error'));

        $this->assertSame($this->validationManager->getFirstMessage(), 'error');
        $this->assertSame($this->validationManager->getFirstMessage(null), 'error');
        $this->assertSame($this->validationManager->getFirstMessage([]), 'error');
        $this->assertSame($this->validationManager->getFirstMessage(''), 'error');

        $this->validationManager->reset();

        // Test ok values
        $this->assertTrue($this->validationManager->isTrue(true, 'error1', 'tag1'));
        $this->assertSame($this->validationManager->getFirstMessage(), '');
        $this->assertSame($this->validationManager->getFirstMessage('tag1'), '');

        // Test wrong values
        $this->assertFalse($this->validationManager->isTrue(false, 'error1', 'tag1'));
        $this->assertTrue($this->validationManager->isTrue(true, 'error2', 'tag2'));
        $this->assertFalse($this->validationManager->isTrue(false, 'warning3', 'tag3', true));
        $this->assertSame($this->validationManager->getFirstMessage(), 'error1');
        $this->assertSame($this->validationManager->getFirstMessage('tag1'), 'error1');
        $this->assertSame($this->validationManager->getFirstMessage('tag2'), '');
        $this->assertSame($this->validationManager->getFirstMessage('tag3'), 'warning3');
        $this->assertSame($this->validationManager->getFirstMessage(['tag2', 'tag3']), 'warning3');
        $this->assertSame($this->validationManager->getFirstMessage(['tag1', 'tag2']), 'error1');
        $this->assertSame($this->validationManager->getFirstMessage(['tag1', 'tag3']), 'error1');
        $this->assertSame($this->validationManager->getFirstMessage(['tag3', 'tag1']), 'error1');
    }


    /**
     * testGetLastMessage
     *
     * @return void
     */
    public function testGetLastMessage(){

        // Test empty values
        $this->assertSame($this->validationManager->getLastMessage(), '');
        $this->assertSame($this->validationManager->getLastMessage(null), '');
        $this->assertSame($this->validationManager->getLastMessage([]), '');
        $this->assertSame($this->validationManager->getLastMessage(''), '');

        $this->assertFalse($this->validationManager->isTrue(false, 'error1'));
        $this->assertFalse($this->validationManager->isTrue(false, 'error2'));
        $this->assertFalse($this->validationManager->isTrue(false, 'error3'));

        $this->assertSame($this->validationManager->getLastMessage(), 'error3');
        $this->assertSame($this->validationManager->getLastMessage(null), 'error3');
        $this->assertSame($this->validationManager->getLastMessage([]), 'error3');
        $this->assertSame($this->validationManager->getLastMessage(''), 'error3');

        $this->validationManager->reset();

        // Test ok values
        $this->assertTrue($this->validationManager->isTrue(true, 'error1', 'tag1'));
        $this->assertSame($this->validationManager->getLastMessage(), '');
        $this->assertSame($this->validationManager->getLastMessage('tag1'), '');

        // Test wrong values
        $this->assertFalse($this->validationManager->isTrue(false, 'error1', 'tag1'));
        $this->assertTrue($this->validationManager->isTrue(true, 'error2', 'tag2'));
        $this->assertFalse($this->validationManager->isTrue(false, 'warning3', 'tag3', true));
        $this->assertSame($this->validationManager->getLastMessage(), 'warning3');
        $this->assertSame($this->validationManager->getLastMessage('tag1'), 'error1');
        $this->assertSame($this->validationManager->getLastMessage('tag2'), '');
        $this->assertSame($this->validationManager->getLastMessage('tag3'), 'warning3');
        $this->assertSame($this->validationManager->getLastMessage(['tag2', 'tag3']), 'warning3');
        $this->assertSame($this->validationManager->getLastMessage(['tag1', 'tag2']), 'error1');
        $this->assertSame($this->validationManager->getLastMessage(['tag1', 'tag3']), 'warning3');
        $this->assertSame($this->validationManager->getLastMessage(['tag3', 'tag1']), 'warning3');
    }


    /**
     * testIsTrue
     *
     * @return void
     */
    public function testIsTrue(){

        // Test empty values
        $this->assertTrue(!$this->validationManager->isTrue(null));
        $this->assertTrue(!$this->validationManager->isTrue([]));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);

        $this->validationManager->reset();

        // Test ok values
        $this->assertTrue($this->validationManager->isTrue(true));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::OK);

        // Test wrong values
        $this->assertTrue(!$this->validationManager->isTrue(false));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);
        $this->assertTrue(!$this->validationManager->isTrue('121212'));
        $this->assertTrue(!$this->validationManager->isTrue([1, 78]));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);

        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::OK);

        // Test mixed ok and wrong
        $this->assertTrue(!$this->validationManager->isTrue(false, 'false error', '', true));
        $this->assertTrue($this->validationManager->getLastMessage() === 'false error');
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::WARNING);
        $this->assertTrue($this->validationManager->isTrue(true, 'no error'));
        $this->assertTrue(!$this->validationManager->isTrue(false, 'false error 2'));
        $this->assertTrue($this->validationManager->getLastMessage() === 'false error 2');
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);

        // Test valid values on different tags
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isTrue(true, 'no error', 'tag1'));
        $this->assertTrue($this->validationManager->isTrue(true, 'no error', 'tag2'));
        $this->assertTrue($this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->ok('tag2'));

        // Test invalid values on different tags
        $this->validationManager->reset();
        $this->assertTrue(!$this->validationManager->isTrue(false, 'error', 'tag1'));
        $this->assertTrue(!$this->validationManager->isTrue(false, 'error', 'tag2'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue(!$this->validationManager->ok('tag1'));
        $this->assertTrue(!$this->validationManager->ok('tag2'));

        // Test valid and invalid values on different tags
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isTrue(true, 'no error', 'tag1'));
        $this->assertTrue(!$this->validationManager->isTrue(false, 'error', 'tag2'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue(!$this->validationManager->ok('tag2'));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::OK);
        $this->assertTrue($this->validationManager->getStatus('tag2') === ValidationManager::ERROR);

        // Test valid and invalid sequentially on the same tag
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isTrue(true, 'no error', 'tag1'));
        $this->assertTrue($this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::OK);
        $this->assertTrue(!$this->validationManager->isTrue(false, 'error', 'tag1'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue(!$this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::ERROR);
    }


    /**
     * testIsBoolean
     *
     * @return void
     */
    public function testIsBoolean(){

        // Test empty values
        $this->assertTrue(!$this->validationManager->isBoolean(null));
        $this->assertTrue(!$this->validationManager->isBoolean(''));
        $this->assertTrue(!$this->validationManager->isBoolean([]));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);

        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->getLastMessage() === '');
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::OK);

        // Test ok values
        $this->assertTrue($this->validationManager->isBoolean(true));
        $this->assertTrue($this->validationManager->isBoolean(false));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::OK);

        // Test wrong values
        $this->assertTrue(!$this->validationManager->isBoolean('hello'));
        $this->assertTrue(!$this->validationManager->isBoolean(['go', 12]));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);
        $this->assertTrue(!$this->validationManager->isBoolean(45));
        $this->assertTrue(!$this->validationManager->isBoolean(new Exception(), 'custom error'));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);
        $this->assertTrue($this->validationManager->getLastMessage() === 'custom error');

        $this->validationManager->reset();

        // Test mixed ok and wrong values
        $this->assertTrue(!$this->validationManager->isBoolean([12], 'error', '', true));
        $this->assertTrue($this->validationManager->getLastMessage() === 'error');
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::WARNING);
        $this->assertTrue($this->validationManager->isBoolean(true, 'no error'));
        $this->assertTrue(!$this->validationManager->isBoolean('asdf', 'error 2'));
        $this->assertTrue($this->validationManager->getLastMessage() === 'error 2');
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);

        // Test valid values on different tags
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isBoolean(true, 'no error', 'tag1'));
        $this->assertTrue($this->validationManager->isBoolean(false, 'no error', 'tag2'));
        $this->assertTrue($this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->ok('tag2'));

        // Test invalid values on different tags
        $this->validationManager->reset();
        $this->assertTrue(!$this->validationManager->isBoolean('hello', 'error', 'tag1'));
        $this->assertTrue(!$this->validationManager->isBoolean('hello', 'error', 'tag2'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue(!$this->validationManager->ok('tag1'));
        $this->assertTrue(!$this->validationManager->ok('tag2'));

        // Test valid and invalid values on different tags
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isBoolean(true, 'no error', 'tag1'));
        $this->assertTrue(!$this->validationManager->isBoolean('hello', 'error', 'tag2'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue(!$this->validationManager->ok('tag2'));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::OK);
        $this->assertTrue($this->validationManager->getStatus('tag2') === ValidationManager::ERROR);

        // Test valid and invalid sequentially on the same tag
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isBoolean(true, 'no error', 'tag1'));
        $this->assertTrue($this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::OK);
        $this->assertTrue(!$this->validationManager->isBoolean('hello', 'error', 'tag1'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue(!$this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::ERROR);
    }


    /**
     * testIsNumeric
     *
     * @return void
     */
    public function testIsNumeric(){

        // Test empty values
        $this->assertFalse($this->validationManager->isNumeric(null));
        $this->assertFalse($this->validationManager->isNumeric(''));
        $this->assertFalse($this->validationManager->isNumeric([]));
        $this->assertTrue($this->validationManager->isNumeric(0));

        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->getLastMessage() === '');
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::OK);

        // Test ok values
        $this->assertTrue($this->validationManager->isNumeric(1));
        $this->assertTrue($this->validationManager->isNumeric(-1));
        $this->assertTrue($this->validationManager->isNumeric(145646));
        $this->assertTrue($this->validationManager->isNumeric(-3453451));
        $this->assertTrue($this->validationManager->isNumeric(1.34435));
        $this->assertTrue($this->validationManager->isNumeric(-1.56567));
        $this->assertTrue($this->validationManager->isNumeric('1'));
        $this->assertTrue($this->validationManager->isNumeric('-1'));
        $this->assertTrue($this->validationManager->isNumeric('1.4545645'));
        $this->assertTrue($this->validationManager->isNumeric('-1.345'));
        $this->assertTrue($this->validationManager->isNumeric('345341'));
        $this->assertTrue($this->validationManager->isNumeric('-345341'));
        $this->assertTrue($this->validationManager->isNumeric('1,4356'));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::OK);

        // Test wrong values
        $this->assertFalse($this->validationManager->isNumeric([12, 'b']));
        $this->assertFalse($this->validationManager->isNumeric(new ValidationManager()));
        $this->assertTrue($this->validationManager->getLastMessage() === 'value is not a number');
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);
        $this->assertFalse($this->validationManager->isNumeric('hello', 'numeric error'));
        $this->assertTrue($this->validationManager->getLastMessage() === 'numeric error');
        $this->assertFalse($this->validationManager->isNumeric('1,4.4545', 'numeric error'));
        $this->assertFalse($this->validationManager->isNumeric('--345', 'numeric error'));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);

        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->getLastMessage() === '');
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::OK);

        // Test mixed ok and wrong values
        $this->assertFalse($this->validationManager->isNumeric('hello', 'numeric error', '', true));
        $this->assertTrue($this->validationManager->getLastMessage() === 'numeric error');
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::WARNING);
        $this->assertFalse($this->validationManager->isNumeric('hello', 'numeric error 2'));
        $this->assertTrue($this->validationManager->getLastMessage() === 'numeric error 2');
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);

        // Test valid values on different tags
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isNumeric(1, 'no error', 'tag1'));
        $this->assertTrue($this->validationManager->isNumeric('1', 'no error', 'tag2'));
        $this->assertTrue($this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->ok('tag2'));

        // Test invalid values on different tags
        $this->validationManager->reset();
        $this->assertTrue(!$this->validationManager->isNumeric('hello', 'error', 'tag1'));
        $this->assertTrue(!$this->validationManager->isNumeric(['a'], 'error', 'tag2'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue(!$this->validationManager->ok('tag1'));
        $this->assertTrue(!$this->validationManager->ok('tag2'));

        // Test valid and invalid values on different tags
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isNumeric(4.4, 'no error', 'tag1'));
        $this->assertTrue(!$this->validationManager->isNumeric('hello', 'error', 'tag2'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue(!$this->validationManager->ok('tag2'));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::OK);
        $this->assertTrue($this->validationManager->getStatus('tag2') === ValidationManager::ERROR);

        // Test valid and invalid sequentially on the same tag
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isNumeric(1, 'no error', 'tag1'));
        $this->assertTrue($this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::OK);
        $this->assertTrue(!$this->validationManager->isNumeric('hello', 'error', 'tag1'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue(!$this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::ERROR);
    }


    /**
     * testIsNumericBetween
     *
     * @return void
     */
    public function testIsNumericBetween(){

        // Test empty values
        // TODO

        // Test ok values
        // TODO

        // Test wrong values
        // TODO

        // Test exceptions
        // TODO
        $this->markTestIncomplete('This test is incomplete.');
    }


    /**
     * testIsString
     *
     * @return void
     */
    public function testIsString(){

        $this->assertTrue($this->validationManager->isString(''));
        $this->assertTrue($this->validationManager->isString('sfadf'));
        $this->assertTrue($this->validationManager->isString('3453515 532'));
        $this->assertTrue($this->validationManager->isString("\n\n$!"));
        $this->assertTrue($this->validationManager->isString('hello baby how are you'));
        $this->assertTrue($this->validationManager->isString("hello\n\nbably\r\ntest"));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::OK);

        $this->assertTrue(!$this->validationManager->isString(null, '', '', true));
        $this->assertTrue(!$this->validationManager->isString(123, '', '', true));
        $this->assertTrue(!$this->validationManager->isString(4.879, '', '', true));
        $this->assertTrue(!$this->validationManager->isString(new ValidationManager(), '', '', true));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::WARNING);
        $this->assertTrue(!$this->validationManager->isString([]));
        $this->assertTrue(!$this->validationManager->isString(-978));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);

        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::OK);

        // Test valid values on different tags
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isString('a', 'no error', 'tag1'));
        $this->assertTrue($this->validationManager->isString('1', 'no error', 'tag2'));
        $this->assertTrue($this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->ok('tag2'));

        // Test invalid values on different tags
        $this->validationManager->reset();
        $this->assertTrue(!$this->validationManager->isString(1, 'error', 'tag1'));
        $this->assertTrue(!$this->validationManager->isString(['a'], 'error', 'tag2'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue(!$this->validationManager->ok('tag1'));
        $this->assertTrue(!$this->validationManager->ok('tag2'));

        // Test valid and invalid values on different tags
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isString('1', 'no error', 'tag1'));
        $this->assertTrue(!$this->validationManager->isString(0, 'error', 'tag2'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue(!$this->validationManager->ok('tag2'));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::OK);
        $this->assertTrue($this->validationManager->getStatus('tag2') === ValidationManager::ERROR);

        // Test valid and invalid sequentially on the same tag
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isString('1', 'no error', 'tag1'));
        $this->assertTrue($this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::OK);
        $this->assertTrue(!$this->validationManager->isString([1], 'error', 'tag1'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue(!$this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::ERROR);

        // Test mixed validations with several tags
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isTrue(true, 'no error', 'tag1'));
        $this->assertTrue($this->validationManager->isString('string', 'no error', 'tag1'));
        $this->assertTrue($this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue(!$this->validationManager->isNumeric('a', 'non numeric error', 'tag1'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue(!$this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::ERROR);
        $this->assertTrue($this->validationManager->isTrue(true, 'no error', 'tag1'));
        $this->assertTrue($this->validationManager->isString('string', 'no error', 'tag1'));
    }


    /**
     * testIsUrl
     *
     * @return void
     */
    public function testIsUrl(){

        // Wrong url cases
        $this->assertTrue(!$this->validationManager->isUrl(''));
        $this->assertTrue(!$this->validationManager->isUrl(null));
        $this->assertTrue(!$this->validationManager->isUrl([]));
        $this->assertTrue(!$this->validationManager->isUrl('    '));
        $this->assertTrue(!$this->validationManager->isUrl("\n   \t\n"));
        $this->assertTrue(!$this->validationManager->isUrl('ftp://user:password@host:port/path'));
        $this->assertTrue(!$this->validationManager->isUrl('/nfs/an/disks/jj/home/dir/file.txt'));
        $this->assertTrue(!$this->validationManager->isUrl('C:\\Program Files (x86)'));

        // good url cases
        $this->assertTrue($this->validationManager->isUrl('http://x.ye'));
        $this->assertTrue($this->validationManager->isUrl('http://google.com'));
        $this->assertTrue($this->validationManager->isUrl('ftp://mydomain.com'));
        $this->assertTrue($this->validationManager->isUrl('http://www.example.com:8800'));
        $this->assertTrue($this->validationManager->isUrl('http://www.example.com/a/b/c/d/e/f/g/h/i.html'));
        $this->assertTrue($this->validationManager->isUrl('ftp://user:password@host.com:8080/path'));

        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::OK);

        // Test non string values throw exceptions
        try {
            $this->validationManager->isUrl([12341]);
            $this->exceptionMessage = '[12341] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->validationManager->isUrl(12341);
            $this->exceptionMessage = '12341 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test valid values on different tags
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isUrl('http://google.com', 'no error', 'tag1'));
        $this->assertTrue($this->validationManager->isUrl('http://google.com', 'no error', 'tag2'));
        $this->assertTrue($this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->ok('tag2'));

        // Test invalid values on different tags
        $this->validationManager->reset();
        $this->assertTrue(!$this->validationManager->isUrl('1', 'error', 'tag1'));
        $this->assertTrue(!$this->validationManager->isUrl('a', 'error', 'tag2'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue(!$this->validationManager->ok('tag1'));
        $this->assertTrue(!$this->validationManager->ok('tag2'));

        // Test valid and invalid values on different tags
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isUrl('http://google.com', 'no error', 'tag1'));
        $this->assertTrue(!$this->validationManager->isUrl('0', 'error', 'tag2'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue(!$this->validationManager->ok('tag2'));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::OK);
        $this->assertTrue($this->validationManager->getStatus('tag2') === ValidationManager::ERROR);

        // Test valid and invalid sequentially on the same tag
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isUrl('http://google.com', 'no error', 'tag1'));
        $this->assertTrue($this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::OK);
        $this->assertTrue(!$this->validationManager->isUrl('1', 'error', 'tag1'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue(!$this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::ERROR);
    }


    /**
     * testIsArray
     *
     * @return void
     */
    public function testIsArray(){

        $this->assertTrue($this->validationManager->isArray([]));
        $this->assertTrue($this->validationManager->isArray([1]));
        $this->assertTrue($this->validationManager->isArray(['1']));
        $this->assertTrue($this->validationManager->isArray(['1', 5, []]));
        $this->assertTrue($this->validationManager->isArray([null]));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::OK);

        $this->assertTrue(!$this->validationManager->isArray(null, '', '', true));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::WARNING);
        $this->assertTrue(!$this->validationManager->isArray(1));
        $this->assertTrue(!$this->validationManager->isArray(''));
        $this->assertTrue(!$this->validationManager->isArray(new ValidationManager()));
        $this->assertTrue(!$this->validationManager->isArray('hello'));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);

        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::OK);

        // Test valid values on different tags
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isArray(['a', 1], 'no error', 'tag1'));
        $this->assertTrue($this->validationManager->isArray(['a', 1], 'no error', 'tag2'));
        $this->assertTrue($this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->ok('tag2'));

        // Test invalid values on different tags
        $this->validationManager->reset();
        $this->assertTrue(!$this->validationManager->isArray(1, 'error', 'tag1'));
        $this->assertTrue(!$this->validationManager->isArray('a', 'error', 'tag2'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue(!$this->validationManager->ok('tag1'));
        $this->assertTrue(!$this->validationManager->ok('tag2'));

        // Test valid and invalid values on different tags
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isArray(['a', 1], 'no error', 'tag1'));
        $this->assertTrue(!$this->validationManager->isArray('0', 'error', 'tag2'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue(!$this->validationManager->ok('tag2'));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::OK);
        $this->assertTrue($this->validationManager->getStatus('tag2') === ValidationManager::ERROR);

        // Test valid and invalid sequentially on the same tag
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isArray(['a', 1], 'no error', 'tag1'));
        $this->assertTrue($this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::OK);
        $this->assertTrue(!$this->validationManager->isArray(1, 'error', 'tag1'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue(!$this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::ERROR);
    }


    /**
     * testIsObject
     *
     * @return void
     */
    public function testIsObject(){

        $this->assertTrue($this->validationManager->isObject(new stdClass()));

        $this->assertTrue($this->validationManager->isObject((object) [
            '1' => 1
        ]));

        $this->assertTrue($this->validationManager->isObject((object) [
            '1' => '1'
        ]));

        $this->assertTrue($this->validationManager->isObject((object) [
                '1' => '1',
                '5' => 5,
                'array' => []
        ]));

        $this->assertTrue($this->validationManager->isObject((object) [
                'novalue' => null
        ]));

        $this->assertTrue($this->validationManager->isObject(new ValidationManager()));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::OK);


        $this->assertTrue(!$this->validationManager->isObject(null, '', '', true));
        $this->assertTrue(!$this->validationManager->isObject([], '', '', true));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::WARNING);

        $this->assertTrue(!$this->validationManager->isObject(1));
        $this->assertTrue(!$this->validationManager->isObject(''));
        $this->assertTrue(!$this->validationManager->isObject('hello'));
        $this->assertTrue(!$this->validationManager->isObject([1, 4, 5]));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);

        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::OK);

        // Test valid values on different tags
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isObject((object)['a' => 1], 'no error', 'tag1'));
        $this->assertTrue($this->validationManager->isObject((object)['a' => 2], 'no error', 'tag2'));
        $this->assertTrue($this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->ok('tag2'));

        // Test invalid values on different tags
        $this->validationManager->reset();
        $this->assertTrue(!$this->validationManager->isObject(1, 'error', 'tag1'));
        $this->assertTrue(!$this->validationManager->isObject('a', 'error', 'tag2'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue(!$this->validationManager->ok('tag1'));
        $this->assertTrue(!$this->validationManager->ok('tag2'));

        // Test valid and invalid values on different tags
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isObject((object)['a' => 1], 'no error', 'tag1'));
        $this->assertTrue(!$this->validationManager->isObject('0', 'error', 'tag2'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue(!$this->validationManager->ok('tag2'));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::OK);
        $this->assertTrue($this->validationManager->getStatus('tag2') === ValidationManager::ERROR);

        // Test valid and invalid sequentially on the same tag
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isObject((object)['a' => 1], 'no error', 'tag1'));
        $this->assertTrue($this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::OK);
        $this->assertTrue(!$this->validationManager->isObject(1, 'error', 'tag1'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue(!$this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::ERROR);
    }


    /**
     * testIsFilledIn
     *
     * @return void
     */
    public function testIsFilledIn(){

        // Test empty values
        $this->assertFalse($this->validationManager->isFilledIn(null));
        $this->assertFalse($this->validationManager->isFilledIn(''));
        $this->assertFalse($this->validationManager->isFilledIn([]));
        $this->assertTrue(!$this->validationManager->isFilledIn(null, [], '', '', true));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);

        $this->validationManager->reset();

        // Test ok values
        $this->assertTrue($this->validationManager->isFilledIn('adsadf'));
        $this->assertTrue($this->validationManager->isFilledIn('    sdfasdsf'));
        $this->assertTrue($this->validationManager->isFilledIn('EMPTY'));
        $this->assertTrue($this->validationManager->isFilledIn('EMPTY test', ['EMPTY']));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::OK);

        // Test wrong values
        $this->assertFalse($this->validationManager->isFilledIn('      ', [], '', '', true));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::WARNING);
        $this->assertFalse($this->validationManager->isFilledIn("\n\n  \n"));
        $this->assertFalse($this->validationManager->isFilledIn("\t   \n     \r\r"));
        $this->assertFalse($this->validationManager->isFilledIn('EMPTY', ['EMPTY']));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);
        $this->assertFalse($this->validationManager->isFilledIn('EMPTY           ', ['EMPTY']));
        $this->assertFalse($this->validationManager->isFilledIn('EMPTY       void   hole    ', ['EMPTY', 'void', 'hole']));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);

        // Test exceptions
        try {
            $this->validationManager->isFilledIn(125);
            $this->exceptionMessage = '125 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->validationManager->isFilledIn([125]);
            $this->exceptionMessage = '[125] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $this->validationManager->isFilledIn(new Exception());
            $this->exceptionMessage = 'new Exception() did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test valid values on different tags
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isFilledIn('hello', [], 'no error', 'tag1'));
        $this->assertTrue($this->validationManager->isFilledIn('hello', [], 'no error', 'tag2'));
        $this->assertTrue($this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->ok('tag2'));

        // Test invalid values on different tags
        $this->validationManager->reset();
        $this->assertTrue(!$this->validationManager->isFilledIn(' ', [], 'error', 'tag1'));
        $this->assertTrue(!$this->validationManager->isFilledIn('  ', [], 'error', 'tag2'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue(!$this->validationManager->ok('tag1'));
        $this->assertTrue(!$this->validationManager->ok('tag2'));

        // Test valid and invalid values on different tags
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isFilledIn('hello', [], 'no error', 'tag1'));
        $this->assertTrue(!$this->validationManager->isFilledIn('  ', [], 'error', 'tag2'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue(!$this->validationManager->ok('tag2'));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::OK);
        $this->assertTrue($this->validationManager->getStatus('tag2') === ValidationManager::ERROR);

        // Test valid and invalid sequentially on the same tag
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isFilledIn('hello', [], 'no error', 'tag1'));
        $this->assertTrue($this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::OK);
        $this->assertTrue(!$this->validationManager->isFilledIn('         ', [], 'error', 'tag1'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue(!$this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::ERROR);
    }


    /**
     * testIsObjectWithValidProperties
     *
     * @return void
     */
    public function testIsObjectWithValidProperties(){

        // Test empty values
        // TODO

        // Test ok values
        // TODO

        // Test wrong values
        // TODO

        // Test exceptions
        // TODO
        $this->markTestIncomplete('This test is incomplete.');
    }


    /**
     * testIsDate
     *
     * @return void
     */
    public function testIsDate(){

        // TODO - copy from js
        $this->markTestIncomplete('This test is incomplete.');
    }


    /**
     * testIsMail
     *
     * @return void
     */
    public function testIsMail(){

        // TODO - copy from js
        $this->markTestIncomplete('This test is incomplete.');
    }


    /**
     * testIsEqualTo
     *
     * @return void
     */
    public function testIsEqualTo(){

        $this->assertTrue($this->validationManager->isEqualTo(null, null));
        $this->assertTrue($this->validationManager->isEqualTo('', ''));
        $this->assertTrue($this->validationManager->isEqualTo(123, 123));
        $this->assertTrue($this->validationManager->isEqualTo(1.56, 1.56));
        $this->assertTrue($this->validationManager->isEqualTo([], []));
        $this->assertTrue($this->validationManager->isEqualTo('hello', 'hello'));
        $this->assertTrue($this->validationManager->isEqualTo(new ValidationManager(), new ValidationManager()));
        $this->assertTrue($this->validationManager->isEqualTo([1, 6, 8, 4], [1, 6, 8, 4]));

        $this->assertTrue(!$this->validationManager->isEqualTo(null, []));
        $this->assertTrue(!$this->validationManager->isEqualTo('', 'hello'));
        $this->assertTrue(!$this->validationManager->isEqualTo(124, 12454));
        $this->assertTrue(!$this->validationManager->isEqualTo(1.45, 1));
        $this->assertTrue(!$this->validationManager->isEqualTo([], new stdClass()));
        $this->assertTrue(!$this->validationManager->isEqualTo('gobaby', 'hello'));
        $this->assertTrue(!$this->validationManager->isEqualTo('hello', new ValidationManager()));
        $this->assertTrue(!$this->validationManager->isEqualTo([5, 2, 8, 5], [1, 6, 9, 5]));

        $this->assertTrue(!$this->validationManager->isEqualTo(((object) [
                'a' => 1,
                'b' => 2
        ]), ((object) [
                'c' => 1,
                'b' => 3
        ])));

        // Test valid values on different tags
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isEqualTo('hello', 'hello', 'no error', 'tag1'));
        $this->assertTrue($this->validationManager->isEqualTo('hello', 'hello', 'no error', 'tag2'));
        $this->assertTrue($this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->ok('tag2'));

        // Test invalid values on different tags
        $this->validationManager->reset();
        $this->assertTrue(!$this->validationManager->isEqualTo('hello', 'hello1', 'error', 'tag1'));
        $this->assertTrue(!$this->validationManager->isEqualTo('hello', 'hello2', 'error', 'tag2'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue(!$this->validationManager->ok('tag1'));
        $this->assertTrue(!$this->validationManager->ok('tag2'));

        // Test valid and invalid values on different tags
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isEqualTo('hello', 'hello', 'no error', 'tag1'));
        $this->assertTrue(!$this->validationManager->isEqualTo('hello', 'hello1', 'error', 'tag2'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue(!$this->validationManager->ok('tag2'));
        $this->assertTrue($this->validationManager->getStatus() === ValidationManager::ERROR);
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::OK);
        $this->assertTrue($this->validationManager->getStatus('tag2') === ValidationManager::ERROR);

        // Test valid and invalid sequentially on the same tag
        $this->validationManager->reset();
        $this->assertTrue($this->validationManager->isEqualTo('hello', 'hello', 'no error', 'tag1'));
        $this->assertTrue($this->validationManager->ok());
        $this->assertTrue($this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::OK);
        $this->assertTrue(!$this->validationManager->isEqualTo('hello', 'hello2', 'error', 'tag1'));
        $this->assertTrue(!$this->validationManager->ok());
        $this->assertTrue(!$this->validationManager->ok('tag1'));
        $this->assertTrue($this->validationManager->getStatus('tag1') === ValidationManager::ERROR);
    }


    //TODO - Add all missing tests
}
?>