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

use Exception;
use stdClass;
use PHPUnit\Framework\TestCase;
use org\turbocommons\src\main\php\managers\BrowserManager;
use org\turbocommons\src\main\php\managers\HTTPManager;
use org\turbocommons\src\main\php\model\HashMapObject;
use org\turbocommons\src\main\php\utils\ArrayUtils;
use org\turbocommons\src\main\php\utils\StringUtils;
use org\turbocommons\src\main\php\managers\httpmanager\HTTPManagerGetRequest;
use org\turbotesting\src\main\php\utils\AssertUtils;


/**
 * HTTPManagerTest
 *
 * @return void
 */
class HTTPManagerTest extends TestCase {

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

        $this->emptyValues = [null, '', [], new stdClass(), '     ', "\n\n\n", 0];
        $this->emptyValuesCount = count($this->emptyValues);

        $this->browserManager = new BrowserManager();
        $this->sut = new HTTPManager();

        $this->sut->timeout = 3000;

        $this->basePath = 'https://raw.githubusercontent.com/edertone/TurboCommons/master/TurboCommons-Php/src/test/resources/managers/httpManager';
        $this->existantUrl = 'https://www.google.com';
        $this->nonExistantUrl = 'http://werwerwerwerwerwerwe.345345/3453453454435dgdfg.html';
    }


    /**
     * @see TestCase::tearDown()
     *
     * @return void
     */
    protected function tearDown(){

        // Nothing necessary here
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
     * testConstructor
     *
     * @return void
     */
    public function testConstructor(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            AssertUtils::throwsException(function() use ($i) { new HTTPManager($this->emptyValues[$i]); }, '/asynchronous is not boolean/');
        }

        // Test ok values
        $this->sut = new HTTPManager(true);
        $this->assertSame($this->sut->asynchronous, true);
        $this->assertSame($this->sut->timeout, 0);

        $this->sut = new HTTPManager(false);
        $this->assertSame($this->sut->asynchronous, false);
        $this->assertSame($this->sut->timeout, 0);
        $this->assertSame($this->sut->countQueues(), 0);

        // Test wrong values
        // Already tested at empty values

        // Test exceptions
        // Already tested at empty values
    }


    /**
     * test
     * @return void
     */
    public function testSetGlobalPostParam(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            AssertUtils::throwsException(function() use ($i) { $this->sut->setGlobalPostParam($this->emptyValues[$i], $this->emptyValues[$i]); },
                '/parameterName and value must be non empty strings|value is not a string/');
        }

        // Test ok values
        $this->sut->setGlobalPostParam('someparameter', 'somevalue');
        $this->sut->setGlobalPostParam('someparameter2', 'somevalue2');
        $this->assertSame($this->sut->getGlobalPostParam('someparameter'), 'somevalue');
        $this->assertSame($this->sut->getGlobalPostParam('someparameter2'), 'somevalue2');

        // Test wrong values
        // Test exceptions
        AssertUtils::throwsException(function() { $this->sut->setGlobalPostParam(123123, 234234); }, '/value is not a string/');
        AssertUtils::throwsException(function() { $this->sut->setGlobalPostParam([1,2,3], 'hello'); }, '/value is not a string/');
    }


    /**
     * test
     * @return void
     */
    public function testIsGlobalPostParam(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            AssertUtils::throwsException(function() use ($i) { $this->sut->isGlobalPostParam($this->emptyValues[$i]); },
                '/parameterName must be a non empty string|value is not a string/');
        }

        // Test ok values
        $this->sut->setGlobalPostParam('someparameter', '1');
        $this->sut->setGlobalPostParam('someparameter2', '2');
        $this->assertTrue($this->sut->isGlobalPostParam('someparameter'));
        $this->assertTrue($this->sut->isGlobalPostParam('someparameter2'));
        $this->assertFalse($this->sut->isGlobalPostParam('nonexistant'));

        // Test wrong values
        // Test exceptions
        AssertUtils::throwsException(function() { $this->sut->isGlobalPostParam(123123); }, '/value is not a string/');
        AssertUtils::throwsException(function() { $this->sut->isGlobalPostParam([1,2,3]); }, '/value is not a string/');
    }


    /**
     * test
     * @return void
     */
    public function testGetGlobalPostParam(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            AssertUtils::throwsException(function() use ($i) { $this->sut->getGlobalPostParam($this->emptyValues[$i]); },
                '/parameterName must be a non empty string|value is not a string/');
        }

        // Test ok values
        $this->sut->setGlobalPostParam('someparameter', '1');
        $this->sut->setGlobalPostParam('someparameter2', '2');
        $this->assertSame($this->sut->getGlobalPostParam('someparameter'), '1');
        $this->assertSame($this->sut->getGlobalPostParam('someparameter2'), '2');

        // Test wrong values
        // Test exceptions
        AssertUtils::throwsException(function() { $this->sut->getGlobalPostParam(123123); }, '/value is not a string/');
        AssertUtils::throwsException(function() { $this->sut->getGlobalPostParam([1,2,3]); }, '/value is not a string/');
        AssertUtils::throwsException(function() { $this->sut->getGlobalPostParam('nonexistant'); }, '/parameterName does not exist: nonexistant/');
    }


    /**
     * test
     * @return void
     */
    public function testDeleteGlobalPostParam(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            AssertUtils::throwsException(function() use ($i) { $this->sut->deleteGlobalPostParam($this->emptyValues[$i]); },
                '/parameterName must be a non empty string|value is not a string/');
        }

        // Test ok values
        $this->sut->setGlobalPostParam('someparameter', '1');
        $this->sut->setGlobalPostParam('someparameter2', '2');
        $this->assertSame($this->sut->getGlobalPostParam('someparameter'), '1');
        $this->assertSame($this->sut->getGlobalPostParam('someparameter2'), '2');
        $this->sut->deleteGlobalPostParam('someparameter');
        AssertUtils::throwsException(function() { $this->sut->deleteGlobalPostParam('someparameter');  }, '/parameterName does not exist: someparameter/');
        $this->assertSame($this->sut->getGlobalPostParam('someparameter2'), '2');
        $this->sut->deleteGlobalPostParam('someparameter2');
        AssertUtils::throwsException(function() { $this->sut->deleteGlobalPostParam('someparameter2');  }, '/parameterName does not exist: someparameter2/');

        // Test wrong values
        // Test exceptions
        AssertUtils::throwsException(function() { $this->sut->deleteGlobalPostParam(123123);  }, '/value is not a string/');
        AssertUtils::throwsException(function() { $this->sut->deleteGlobalPostParam([1,2,3]);  }, '/value is not a string/');
        AssertUtils::throwsException(function() { $this->sut->deleteGlobalPostParam('nonexistant');  }, '/parameterName does not exist: nonexistant/');
    }


    /**
     * test
     *
     * @return void
     */
    public function testCreateQueue(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            AssertUtils::throwsException(function() use ($i) { $this->sut->createQueue($this->emptyValues[$i]); }, '/name must be a non empty string|value is not a string/');
        }

        // Test ok values
        $this->assertSame($this->sut->countQueues(), 0);
        $this->sut->createQueue("first queue");
        $this->assertSame($this->sut->countQueues(), 1);
        $this->assertSame($this->sut->isQueueRunning("first queue"), false);

        $this->sut->createQueue("second queue");
        $this->assertSame($this->sut->countQueues(), 2);
        $this->assertSame($this->sut->isQueueRunning("second queue"), false);

        $this->sut->createQueue("third queue");
        $this->assertSame($this->sut->countQueues(), 3);
        $this->assertSame($this->sut->isQueueRunning("third queue"), false);

        // Test wrong values
        // Test exceptions
        AssertUtils::throwsException(function() { $this->sut->createQueue("first queue"); }, '/queue first queue already exists/');

        AssertUtils::throwsException(function() { $this->sut->createQueue("second queue"); }, '/queue second queue already exists/');

        AssertUtils::throwsException(function() { $this->sut->createQueue(13435); }, '/value is not a string/');

        AssertUtils::throwsException(function() { $this->sut->createQueue(['hello' => 1]); }, '/value is not a string/');

        AssertUtils::throwsException(function() { $this->sut->createQueue([1, 2, 3]); }, '/value is not a string/');
    }


    /**
     * testCountQueues
     *
     * @return void
     */
    public function testCountQueues(){

        // Test empty values
        // Not necessary

        // Test ok values
        $this->assertSame($this->sut->countQueues(), 0);

        for ($i = 0; $i < 20; $i++) {

            $this->sut->createQueue("queue ".$i);
            $this->assertSame($this->sut->countQueues(), $i + 1);
            $this->assertSame($this->sut->isQueueRunning("queue ".$i), false);
        }

        // Test wrong values
        // Not necessary

        // Test exceptions
        // Not necessary
    }


    /**
     * testIsQueueRunning
     *
     * @return void
     */
    public function testIsQueueRunning(){

        // TODO - translate from TS
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testDeleteQueue
     *
     * @return void
     */
    public function testDeleteQueue(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            AssertUtils::throwsException(function() use ($i) { $this->sut->deleteQueue($this->emptyValues[$i]); }, '/name must be a non empty string|value is not a string/');
        }

        // Test ok values
        $this->sut->createQueue("queue1");
        $this->sut->createQueue("queue2");
        $this->sut->createQueue("queue3");
        $this->assertSame($this->sut->countQueues(), 3);
        $this->sut->deleteQueue("queue1");
        $this->assertSame($this->sut->countQueues(), 2);

        AssertUtils::throwsException(function() { $this->sut->isQueueRunning("queue1"); }, '/queue queue1 does not exist/');

        $this->assertSame($this->sut->isQueueRunning("queue2"), false);
        $this->sut->deleteQueue("queue2");
        $this->assertSame($this->sut->countQueues(), 1);

        AssertUtils::throwsException(function() { $this->sut->isQueueRunning("queue2"); }, '/queue queue2 does not exist/');

        // Test wrong values
        AssertUtils::throwsException(function() { $this->sut->deleteQueue("non existant queue"); }, '/queue non existant queue does not exist/');

        // Test exceptions
        AssertUtils::throwsException(function() { $this->sut->deleteQueue(13435); }, '/value is not a string/');

        AssertUtils::throwsException(function() { $this->sut->deleteQueue(['hello' => 1]); }, '/value is not a string/');

        AssertUtils::throwsException(function() { $this->sut->deleteQueue([1, 2, 3]); }, '/value is not a string/');
    }


    /**
     * testGenerateUrlQueryString
     *
     * @return void
     */
    public function testGenerateUrlQueryString(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            AssertUtils::throwsException(function() use ($i) { $this->sut->generateUrlQueryString($this->emptyValues[$i]); }, '/keyValuePairs must be a HashMapObject or a non empty associative array/');
        }

        // Test ok values with objects
        $this->assertSame($this->sut->generateUrlQueryString(['a' => 1]), 'a=1');
        $this->assertSame($this->sut->generateUrlQueryString(['a' => 1, 'b' => 2]), 'a=1&b=2');
        $this->assertSame($this->sut->generateUrlQueryString(['a' => 1, 'b' => 2, 'c' => 3]), 'a=1&b=2&c=3');
        $this->assertSame($this->sut->generateUrlQueryString(['a' => "h&b", 'b' => '-_.*=']), 'a=h%26b&b=-_.*%3D');
        $this->assertSame($this->sut->generateUrlQueryString(['/&%$·#&=' => "1"]), '%2F%26%25%24%C2%B7%23%26%3D=1');
        $this->assertSame($this->sut->generateUrlQueryString(["%" => "%"]), '%25=%25');
        $this->assertSame($this->sut->generateUrlQueryString(['a' => 1, 'b' => [1,2,3]]), 'a=1&b=%5B1%2C2%2C3%5D');
        $this->assertSame($this->sut->generateUrlQueryString(['a' => 1, 'b' => (object)['c' => 1]]), 'a=1&b=%7B%22c%22%3A1%7D');

        // Test ok values with HashMapObjects
        $hashMapObject = new HashMapObject();
        $hashMapObject->set('/&%$·#&=', 1);
        $this->assertSame($this->sut->generateUrlQueryString($hashMapObject), '%2F%26%25%24%C2%B7%23%26%3D=1');

        $hashMapObject->set('b', 2);
        $this->assertSame($this->sut->generateUrlQueryString($hashMapObject), '%2F%26%25%24%C2%B7%23%26%3D=1&b=2');

        $hashMapObject->set('c', 3);
        $this->assertSame($this->sut->generateUrlQueryString($hashMapObject), '%2F%26%25%24%C2%B7%23%26%3D=1&b=2&c=3');

        $hashMapObject->set('d', 'he/&%$·#&=llo');
        $this->assertSame($this->sut->generateUrlQueryString($hashMapObject), '%2F%26%25%24%C2%B7%23%26%3D=1&b=2&c=3&d=he%2F%26%25%24%C2%B7%23%26%3Dllo');

        // Test wrong values
        // Tested with exceptions

        // Test exceptions
        AssertUtils::throwsException(function() { $this->sut->generateUrlQueryString("hello"); }, '/keyValuePairs must be a HashMapObject or a non empty associative array/');

        AssertUtils::throwsException(function() { $this->sut->generateUrlQueryString([1,2,3,4]); }, '/keyValuePairs must be a HashMapObject or a non empty associative array/');

        AssertUtils::throwsException(function() { $this->sut->generateUrlQueryString(new Exception()); }, '/keyValuePairs must be a HashMapObject or a non empty associative array/');

        AssertUtils::throwsException(function() { $this->sut->generateUrlQueryString(10); }, '/keyValuePairs must be a HashMapObject or a non empty associative array/');

        AssertUtils::throwsException(function() { $this->sut->generateUrlQueryString(true); }, '/keyValuePairs must be a HashMapObject or a non empty associative array/');
    }


    /**
     * testIsInternetAvailable
     *
     * @return void
     */
    public function testIsInternetAvailable(){

        // TODO - translate from TS
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testUrlExists
     *
     * @return void
     */
    public function testUrlExists(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            if(!StringUtils::isString($this->emptyValues[$i])){

                AssertUtils::throwsException(function() use ($i) { $this->sut->urlExists($this->emptyValues[$i], function(){}, function(){}); }, '/url must be a string/');
            }

            AssertUtils::throwsException(function() use ($i) { $this->sut->urlExists('https://www.google.com', $this->emptyValues[$i]); }, '/params must be functions|Too few arguments to function/');

            AssertUtils::throwsException(function() use ($i) { $this->sut->urlExists('https://www.google.com', function(){}, $this->emptyValues[$i]); }, '/params must be functions/');
        }

        // Test ok values
        AssertUtils::throwsException(function() { $this->sut->urlExists($this->nonExistantUrl, function(){}, function(){}); }, '/Non secure http requests are forbidden/');

        $this->sut->isOnlyHttps = false;

        $this->sut->urlExists($this->existantUrl, function(){

            $this->assertTrue(true);

        }, function(){

            $this->assertTrue(false);
        });

        // Test wrong values
        $this->sut->urlExists($this->nonExistantUrl, function(){

            $this->assertTrue(false);

        }, function(){

            $this->assertTrue(true);
        });

        // Test exceptions
        // Already tested by empty values
    }


    /**
     * testGetUrlHeaders
     *
     * @return void
     */
    public function testGetUrlHeaders(){

        // TODO - translate from TS
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * test
     *
     * @return void
     */
    public function testExecuteResultFormatSTRINGWorksAsExpected(){

        $this->sut->execute($this->basePath.'/file3.json', function($results, $anyError){

            $this->assertSame(false, $anyError, $results[0]['errorMsg']);
            $this->assertSame($results[0]['response'], "{\r\n\"a\": \"1\",\r\n\"b\": 2\r\n}");
            $this->assertSame($results[0]['isError'], false);
        });

        $request = new HTTPManagerGetRequest($this->basePath.'/file3.json');

        $this->sut->execute($request, function($results, $anyError){

            $this->assertSame($anyError, false);
            $this->assertSame($results[0]['response'], "{\r\n\"a\": \"1\",\r\n\"b\": 2\r\n}");
            $this->assertSame($results[0]['isError'], false);
        });
    }


    /**
     * test
     *
     * @return void
     */
    public function test_execute_check_that_resultFormat_JSON_works_as_expected(){

        $request = new HTTPManagerGetRequest($this->basePath.'/file3.json');

        $request->resultFormat = 'JSON';

        $this->sut->execute($request, function($results, $anyError){

            $this->assertSame(false, $anyError, $results[0]['errorMsg']);
            $this->assertTrue(ArrayUtils::isEqualTo($results[0]['response'], ['a' => "1", 'b' => 2]));
            $this->assertSame(false, $results[0]['isError']);
        });
    }


    /**
     * test
     *
     * @return void
     */
    public function test_execute_check_that_resultFormat_JSON_marks_the_request_as_having_error_when_a_non_parseable_result_is_returned(){

        $request = new HTTPManagerGetRequest($this->basePath.'/file1.txt');

        $request->resultFormat = 'JSON';

        $this->sut->execute($request, function($results, $anyError){

            $this->assertSame(true, $anyError, $results[0]['errorMsg']);
            $this->assertSame('text1', $results[0]['response']);
            $this->assertSame(true, $results[0]['isError']);
            $this->assertSame('Could not parse request result as a json string', $results[0]['errorMsg']);
        });
    }


    /**
     * test
     *
     * @return void
     */
    public function test_execute_check_that_resultFormat_JSON_returns_the_error_code_and_error_message_when_a_request_with_errors_is_performed(){

        $request = new HTTPManagerGetRequest('invalid url');

        $request->resultFormat = 'JSON';

        $this->sut->execute($request, function($results, $anyError){

            $this->assertSame(true, $anyError, $results[0]['errorMsg']);
            $this->assertSame($results[0]['url'], 'invalid url');
            $this->assertSame($results[0]['response'], '');
            $this->assertSame($results[0]['isError'], true);
            $this->assertTrue(strlen($results[0]['errorMsg']) > 3);
            $this->assertSame($results[0]['code'], 0);
            $this->assertNotSame($results[0]['errorMsg'], 'Could not parse request result as a json string');
        });
    }


    /**
     * testExecuteRequestsWithStringUrls
     *
     * @return void
     */
    public function testExecuteRequestsWithStringUrls(){

        // Test empty values
        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            $expectedError = 'Invalid requests value';

            if(ArrayUtils::isArray($this->emptyValues[$i]) && count($this->emptyValues[$i]) === 0){

                $expectedError = 'No requests to execute';
            }

            AssertUtils::throwsException(function() use ($i) { $this->sut->execute($this->emptyValues[$i]); }, '/'.$expectedError.'/');
        }

        // Test ok values

        // Single url with error
        $this->sut->execute('some invalid url', function($results, $anyError){

            $this->assertSame(true, $anyError, $results[0]['errorMsg']);
            $this->assertSame($results[0]['url'], 'some invalid url');
            $this->assertSame($results[0]['response'], '');
            $this->assertSame($results[0]['isError'], true);
            $this->assertTrue(strlen($results[0]['errorMsg']) > 3);
            $this->assertSame($results[0]['code'], 0);
        });

        // Single url without error
        $this->sut->isOnlyHttps = false;

        $this->sut->execute($this->existantUrl, function($results, $anyError){

            $this->assertSame(false, $anyError, $results[0]['errorMsg']);

            $this->assertSame($results[0]['url'], $this->existantUrl);
            $this->assertTrue(!StringUtils::isEmpty($results[0]['response']));
            $this->assertTrue(strlen($results[0]['response']) > 5);
            $this->assertSame($results[0]['isError'], false);
            $this->assertSame($results[0]['errorMsg'], '');
            $this->assertSame($results[0]['code'], 200);
        });

        // Multiple urls with errors
        $multiErrProgressCount = 0;

        $this->sut->execute(['invalidUrl1', 'invalidUrl2', 'invalidUrl3'], function($results, $anyError) use (&$multiErrProgressCount) {

            $this->assertSame($multiErrProgressCount, 3);
            $this->assertSame($anyError, true);

            for ($i = 0; $i < 3; $i++) {

                $this->assertSame($results[$i]['url'], 'invalidUrl'.($i + 1));
                $this->assertSame($results[$i]['response'], '');
                $this->assertSame($results[$i]['isError'], true);
                $this->assertTrue(strlen($results[$i]['errorMsg']) > 3);
                $this->assertSame($results[$i]['code'], 0);
            }

        }, function($completedUrl, $totalRequests) use (&$multiErrProgressCount) {

            $this->assertTrue(strlen($completedUrl) > 3);
            $this->assertSame($totalRequests, 3);
            $multiErrProgressCount ++;
        });

        $this->sut->execute([$this->existantUrl, 'invalidUrl2', $this->existantUrl], function($results, $anyError){

            $this->assertSame($anyError, true);

            $this->assertSame($results[0]['url'], $this->existantUrl);
            $this->assertTrue(!StringUtils::isEmpty($results[0]['response']));
            $this->assertTrue(strlen($results[0]['response']) > 5);
            $this->assertSame($results[0]['isError'], false);
            $this->assertSame($results[0]['errorMsg'], '');
            $this->assertSame($results[0]['code'], 200);

            $this->assertSame($results[1]['url'], 'invalidUrl2');
            $this->assertSame($results[1]['response'], '');
            $this->assertSame($results[1]['isError'], true);
            $this->assertTrue(strlen($results[1]['errorMsg']) > 3);
            $this->assertSame($results[1]['code'], 0);

            $this->assertSame($results[2]['url'], $this->existantUrl);
            $this->assertTrue(!StringUtils::isEmpty($results[2]['response']));
            $this->assertTrue(strlen($results[2]['response']) > 5);
            $this->assertSame($results[2]['isError'], false);
            $this->assertSame($results[2]['errorMsg'], '');
            $this->assertSame($results[2]['code'], 200);
        });

        // Multiple urls without errors
        $multiProgressCount = 0;

        $this->sut->execute([$this->basePath.'/file1.txt', $this->basePath.'/file2.xml', $this->basePath.'/file3.json'], function($results, $anyError) use (&$multiProgressCount){

            $this->assertSame($multiProgressCount, 3);
            $this->assertSame($anyError, false);

            for ($i = 0; $i < 3; $i++) {

                $this->assertTrue(!StringUtils::isEmpty($results[$i]['response']));
                $this->assertTrue(strlen($results[$i]['response']) > 4);
                $this->assertSame($results[$i]['isError'], false);
                $this->assertSame($results[$i]['errorMsg'], '');
                $this->assertSame($results[$i]['code'], 200);
            }

        }, function($completedUrl, $totalRequests) use (&$multiProgressCount){

            $this->assertTrue(strlen($completedUrl) > 3);
            $this->assertSame($totalRequests, 3);
            $multiProgressCount ++;
        });

        // Test wrong values
        // Not necessary

        // Test exceptions
        AssertUtils::throwsException(function() { $this->sut->execute($this->basePath.'/file1.txt', ['hello'], function () {}); }, '/finishedCallback and progressCallback must be functions/');

        AssertUtils::throwsException(function() { $this->sut->execute($this->basePath.'/file1.txt', function () {}, ['hello']); }, '/finishedCallback and progressCallback must be functions/');

        AssertUtils::throwsException(function() { $this->sut->execute([1, 2], function () {}, function () {}); }, '/url 0 must be a non empty string/');

        AssertUtils::throwsException(function() { $this->sut->execute(["1", 2], function () {}, function () {}); }, '/url 1 must be a non empty string/');
    }


    /**
     * testExecuteSingleHTTPManagerGetRequestWithErrors
     *
     * @return void
     */
    public function testExecuteSingleHTTPManagerGetRequestWithErrors(){

        $progressCount = 0;
        $successCalled = false;
        $errorCalled = false;
        $finallyCalled = false;

        $request = new HTTPManagerGetRequest('some invalid url');

        $request->successCallback = function ($response) use(&$successCalled) { $successCalled = true; };

        $request->errorCallback = function ($errorMsg, $errorCode, $response) use(&$errorCalled) {
            $this->assertTrue(strlen($errorMsg) > 3);
            $this->assertSame($errorCode, 0);
            $this->assertSame($response, '');
            $errorCalled = true;
        };

        $request->finallyCallback = function () use(&$finallyCalled) { $finallyCalled = true; };

        $this->sut->execute($request, function($results, $anyError) use(&$progressCount, &$successCalled, &$errorCalled, &$finallyCalled) {

            $this->assertSame($progressCount, 1);
            $this->assertSame($successCalled, false);
            $this->assertSame($errorCalled, true);
            $this->assertSame($finallyCalled, true);

            $this->assertSame($anyError, true);
            $this->assertSame($results[0]['url'], 'some invalid url');
            $this->assertSame($results[0]['response'], '');
            $this->assertSame($results[0]['isError'], true);
            $this->assertTrue(strlen($results[0]['errorMsg']) > 3);
            $this->assertTrue($results[0]['code'] === 0);

        }, function($completedUrl, $totalRequests) use(&$progressCount) {

            $this->assertSame($completedUrl, 'some invalid url');
            $this->assertSame($totalRequests, 1);
            $progressCount ++;
        });
    }


    /**
     * testExecuteSingleHTTPManagerGetRequestWithoutErrors
     *
     * @return void
     */
    public function testExecuteSingleHTTPManagerGetRequestWithoutErrors(){

        $progressCount = 0;
        $successCalled = false;
        $errorCalled = false;
        $finallyCalled = false;

        $request = new HTTPManagerGetRequest($this->basePath.'/file1.txt');

        $request->successCallback = function ($response) use (&$successCalled) {
            $this->assertSame($response, 'text1');
            $successCalled = true;
        };

        $request->errorCallback = function ($errorMsg, $errorCode, $response) use (&$errorCalled) { $errorCalled = true; };

        $request->finallyCallback = function () use (&$finallyCalled) { $finallyCalled = true; };

        $this->sut->execute($request, function($results, $anyError) use (&$progressCount, &$successCalled, &$errorCalled, &$finallyCalled) {

            $this->assertSame($progressCount, 1);
            $this->assertSame(true, $successCalled);
            $this->assertSame(false, $errorCalled);
            $this->assertSame(true, $finallyCalled);

            $this->assertSame(false, $anyError, $results[0]['errorMsg']);
            $this->assertSame($results[0]['url'], $this->basePath.'/file1.txt');
            $this->assertSame($results[0]['response'], 'text1');
            $this->assertSame($results[0]['isError'], false);
            $this->assertSame($results[0]['errorMsg'], '');
            $this->assertSame($results[0]['code'], 200);

        }, function($completedUrl, $totalRequests) use (&$progressCount) {

            $this->assertSame($completedUrl, $this->basePath.'/file1.txt');
            $this->assertSame($totalRequests, 1);
            $progressCount ++;
        });
    }


    /**
     * testExecuteSingleHTTPManagerGetRequestWithoutErrorsAndUsingBaseUrl
     *
     * @return void
     */
    public function testExecuteSingleHTTPManagerGetRequestWithoutErrorsAndUsingBaseUrl(){

        $successCalled = false;
        $errorCalled = false;
        $finallyCalled = false;

        $request = new HTTPManagerGetRequest('file1.txt');

        $request->successCallback = function ($response) use (&$successCalled) {
            $this->assertSame($response, 'text1');
            $successCalled = true;
        };

        $request->errorCallback = function ($errorMsg, $errorCode, $response) use (&$errorCalled) { $errorCalled = true; };

        $request->finallyCallback = function () use (&$finallyCalled) { $finallyCalled = true; };

        $this->sut->baseUrl = $this->basePath;

        $this->sut->execute($request, function($results, $anyError) use (&$successCalled, &$errorCalled, &$finallyCalled) {

            $this->assertSame($successCalled, true);
            $this->assertSame($errorCalled, false);
            $this->assertSame($finallyCalled, true);

            $this->assertSame(false, $anyError, $results[0]['errorMsg']);
            $this->assertSame($results[0]['url'], $this->basePath.'/file1.txt');
            $this->assertSame($results[0]['response'], 'text1');
            $this->assertSame($results[0]['isError'], false);
            $this->assertSame($results[0]['errorMsg'], '');
            $this->assertSame($results[0]['code'], 200);
        });
    }


    /**
     * testTODO
     *
     * @return void
     */
    public function testTODOImplementAllMissingTests(){

        // TODO - translate from TS
        $this->markTestIncomplete('This test has not been implemented yet.');
    }
}

?>