"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


QUnit.module("HTTPManagerTest", {
    beforeEach : function(){

        window.emptyValues = [null, '', [], {}, '     ', "\n\n\n", 0];
        window.emptyValuesCount = window.emptyValues.length;

        window.StringUtils = org_turbocommons.StringUtils;
        window.ArrayUtils = org_turbocommons.ArrayUtils;
        window.ObjectUtils = org_turbocommons.ObjectUtils;
        window.HashMapObject = org_turbocommons.HashMapObject;
        window.HTTPManager = org_turbocommons.HTTPManager;
        window.HTTPManagerGetRequest = org_turbocommons.HTTPManagerGetRequest;
        window.browserManager = new org_turbocommons.BrowserManager();
        window.sut = new org_turbocommons.HTTPManager();

        window.sut.timeout = 3000;
        
        window.basePath = './resources/managers/httpManager';
        window.existantUrl = browserManager.getCurrentUrl();
        window.nonExistantUrl = browserManager.getCurrentUrl() + '3453453454435dgdfg.html';
    },

    afterEach : function(){

        delete window.emptyValues;
        delete window.emptyValuesCount;
        
        delete window.StringUtils;
        delete window.ArrayUtils;
        delete window.ObjectUtils;
        delete window.HashMapObject;
        delete window.HTTPManager;
        delete window.HttpManagerGetRequest;
        delete window.browserManager;
        delete window.sut;
        
        delete window.basePath;
        delete window.nonExistantUrl;
    }
});


/**
 * testConstructor
 */
QUnit.test("testConstructor", function(assert){

    // Test empty values
    for (var i = 0; i < emptyValuesCount; i++) {
        
        assert.throws(function() { new HTTPManager(emptyValues[i]); }, /asynchronous is not boolean/);
    }

    // Test ok values
    sut = new HTTPManager(true);
    assert.strictEqual(sut.asynchronous, true);
    assert.strictEqual(sut.timeout, 0);
    
    sut = new HTTPManager(false);
    assert.strictEqual(sut.asynchronous, false);
    assert.strictEqual(sut.timeout, 0);
    assert.strictEqual(sut.countQueues(), 0);

    // Test wrong values
    // Already tested at empty values

    // Test exceptions
    // Already tested at empty values
});


/** test */
QUnit.test("setGlobalPostParam", function(assert){

    // Test empty values
    for (var i = 0; i < emptyValuesCount; i++) {
        
        assert.throws(function() { sut.setGlobalPostParam(emptyValues[i], emptyValues[i]); },
            /parameterName and value must be non empty strings|value is not a string/);
    }
    
    // Test ok values
    sut.setGlobalPostParam('someparameter', 'somevalue');
    sut.setGlobalPostParam('someparameter2', 'somevalue2');
    assert.strictEqual(sut.getGlobalPostParam('someparameter'), 'somevalue');
    assert.strictEqual(sut.getGlobalPostParam('someparameter2'), 'somevalue2');

    // Test wrong values
    // Test exceptions
    assert.throws(function() { sut.setGlobalPostParam(123123, 234234); }, /value is not a string/);
    assert.throws(function() { sut.setGlobalPostParam([1,2,3], 'hello'); }, /value is not a string/);
});


/** test */
QUnit.test("isGlobalPostParam", function(assert){

    // Test empty values
    for (var i = 0; i < emptyValuesCount; i++) {
        
        assert.throws(function() { sut.isGlobalPostParam(emptyValues[i]); },
            /parameterName must be a non empty string|value is not a string/);
    }
    
    // Test ok values
    sut.setGlobalPostParam('someparameter', '1');
    sut.setGlobalPostParam('someparameter2', '2');
    assert.ok(sut.isGlobalPostParam('someparameter'));
    assert.ok(sut.isGlobalPostParam('someparameter2'));
    assert.notOk(sut.isGlobalPostParam('nonexistant'));
    
    // Test wrong values
    // Test exceptions
    assert.throws(function() { sut.isGlobalPostParam(123123); }, /value is not a string/);
    assert.throws(function() { sut.isGlobalPostParam([1,2,3]); }, /value is not a string/);
});


/** test */
QUnit.test("getGlobalPostParam", function(assert){

    // Test empty values
    for (var i = 0; i < emptyValuesCount; i++) {
        
        assert.throws(function() { sut.getGlobalPostParam(emptyValues[i]); },
            /parameterName must be a non empty string|value is not a string/);
    }
    
    // Test ok values
    sut.setGlobalPostParam('someparameter', '1');
    sut.setGlobalPostParam('someparameter2', '2');
    assert.strictEqual(sut.getGlobalPostParam('someparameter'), '1');
    assert.strictEqual(sut.getGlobalPostParam('someparameter2'), '2');
    
    // Test wrong values
    // Test exceptions
    assert.throws(function() { sut.getGlobalPostParam(123123); }, /value is not a string/);
    assert.throws(function() { sut.getGlobalPostParam([1,2,3]); }, /value is not a string/);
    assert.throws(function() { sut.getGlobalPostParam('nonexistant'); }, /parameterName does not exist: nonexistant/);
});


/** test */
QUnit.test("deleteGlobalPostParam", function(assert){

    // Test empty values
    for (var i = 0; i < emptyValuesCount; i++) {
        
        assert.throws(function() { sut.deleteGlobalPostParam(emptyValues[i]); },
            /parameterName must be a non empty string|value is not a string/);
    }
    
    // Test ok values
    sut.setGlobalPostParam('someparameter', '1');
    sut.setGlobalPostParam('someparameter2', '2');
    assert.strictEqual(sut.getGlobalPostParam('someparameter'), '1');
    assert.strictEqual(sut.getGlobalPostParam('someparameter2'), '2');
    sut.deleteGlobalPostParam('someparameter');
    assert.throws(function() { sut.deleteGlobalPostParam('someparameter'); }, /parameterName does not exist: someparameter/);
    assert.strictEqual(sut.getGlobalPostParam('someparameter2'), '2');
    sut.deleteGlobalPostParam('someparameter2');
    assert.throws(function() { sut.deleteGlobalPostParam('someparameter2'); }, /parameterName does not exist: someparameter2/);
    
    // Test wrong values
    // Test exceptions
    assert.throws(function() { sut.deleteGlobalPostParam(123123); }, /value is not a string/);
    assert.throws(function() { sut.deleteGlobalPostParam([1,2,3]); }, /value is not a string/);
    assert.throws(function() { sut.deleteGlobalPostParam('nonexistant'); }, /parameterName does not exist: nonexistant/);
});


/** test */
QUnit.test("createQueue", function(assert){

    // Test empty values
    for (var i = 0; i < emptyValuesCount; i++) {
        
        assert.throws(function() { sut.createQueue(emptyValues[i]); }, /name must be a non empty string|value is not a string/);
    }

    // Test ok values
    assert.strictEqual(sut.countQueues(), 0);
    sut.createQueue("first queue");
    assert.strictEqual(sut.countQueues(), 1);
    assert.strictEqual(sut.isQueueRunning("first queue"), false);
    
    sut.createQueue("second queue");
    assert.strictEqual(sut.countQueues(), 2);
    assert.strictEqual(sut.isQueueRunning("second queue"), false);
    
    sut.createQueue("third queue");
    assert.strictEqual(sut.countQueues(), 3);
    assert.strictEqual(sut.isQueueRunning("third queue"), false);
    
    // Test wrong values
    // Test exceptions
    assert.throws(function() { sut.createQueue("first queue"); }, /queue first queue already exists/);

    assert.throws(function() { sut.createQueue("second queue"); }, /queue second queue already exists/);

    assert.throws(function() { sut.createQueue(13435); }, /value is not a string/);
    
    assert.throws(function() { sut.createQueue({hello: 1}); }, /value is not a string/);
    
    assert.throws(function() { sut.createQueue([1, 2, 3]); }, /value is not a string/);
});


/**
 * countQueues
 */
QUnit.test("countQueues", function(assert){

    // Test empty values
    // Not necessary

    // Test ok values
    assert.strictEqual(sut.countQueues(), 0);
    
    for (var i = 0; i < 20; i++) {
        
        sut.createQueue("queue " + i);
        assert.strictEqual(sut.countQueues(), i + 1);
        assert.strictEqual(sut.isQueueRunning("queue " + i), false);
    }

    // Test wrong values
    // Not necessary

    // Test exceptions
    // Not necessary
});


/**
 * isQueueRunning
 */
QUnit.test("isQueueRunning", function(assert){

    // Test empty values
    for (var i = 0; i < emptyValuesCount; i++) {
        
        assert.throws(function() { sut.isQueueRunning(emptyValues[i]); }, /name must be a non empty string|value is not a string/);
    }

    // Test ok values
    sut.createQueue("queue1");
    sut.createQueue("queue2");
    assert.strictEqual(sut.countQueues(), 2);
    assert.strictEqual(sut.isQueueRunning("queue1"), false);
    assert.strictEqual(sut.isQueueRunning("queue2"), false);
    
    var done = assert.async(2);
    
    sut.queue('some invalid url', 'queue1', function(){

        assert.strictEqual(sut.isQueueRunning("queue1"), false);
        done();        
    });
    
    assert.strictEqual(sut.isQueueRunning("queue1"), true);

    sut.queue(basePath + '/file1.txt', 'queue2', function(){

        assert.strictEqual(sut.isQueueRunning("queue2"), false);
        done();        
    });
    
    assert.strictEqual(sut.isQueueRunning("queue2"), true);

    // Test wrong values
    // Not necessary

    // Test exceptions
    assert.throws(function() { sut.isQueueRunning(13435); }, /value is not a string/);
    
    assert.throws(function() { sut.isQueueRunning({hello: 1}); }, /value is not a string/);
    
    assert.throws(function() { sut.isQueueRunning([1, 2, 3]); }, /value is not a string/);
});


/**
 * deleteQueue
 */
QUnit.test("deleteQueue", function(assert){

    // Test empty values
    for (var i = 0; i < emptyValuesCount; i++) {
        
        assert.throws(function() { sut.deleteQueue(emptyValues[i]); }, /name must be a non empty string|value is not a string/);
    }

    // Test ok values
    sut.createQueue("queue1");
    sut.createQueue("queue2");
    sut.createQueue("queue3");
    assert.strictEqual(sut.countQueues(), 3);
    sut.deleteQueue("queue1");
    assert.strictEqual(sut.countQueues(), 2);
    
    assert.throws(function() { sut.isQueueRunning("queue1"); }, /queue queue1 does not exist/);
    
    assert.strictEqual(sut.isQueueRunning("queue2"), false);
    sut.deleteQueue("queue2");
    assert.strictEqual(sut.countQueues(), 1);
    
    assert.throws(function() { sut.isQueueRunning("queue2"); }, /queue queue2 does not exist/);

    // Test wrong values
    assert.throws(function() { sut.deleteQueue("non existant queue"); }, /queue non existant queue does not exist/);

    // Test exceptions
    assert.throws(function() { sut.deleteQueue(13435); }, /value is not a string/);
    
    assert.throws(function() { sut.deleteQueue({hello: 1}); }, /value is not a string/);
    
    assert.throws(function() { sut.deleteQueue([1, 2, 3]); }, /value is not a string/);
});


/**
 * generateUrlQueryString
 */
QUnit.test("generateUrlQueryString", function(assert){

    // Test empty values
    for (var i = 0; i < emptyValuesCount; i++) {
          
        assert.throws(function() { sut.generateUrlQueryString(emptyValues[i]); }, /keyValuePairs must be a HashMapObject or a non empty Object/);
    }

    // Test ok values with objects
    assert.strictEqual(sut.generateUrlQueryString({a:1}), 'a=1');
    assert.strictEqual(sut.generateUrlQueryString({a:1,b:2}), 'a=1&b=2');
    assert.strictEqual(sut.generateUrlQueryString({a:1,b:2,c:3}), 'a=1&b=2&c=3');
    assert.strictEqual(sut.generateUrlQueryString({a:"h&b",b:"-_.*="}), 'a=h%26b&b=-_.*%3D');
    assert.strictEqual(sut.generateUrlQueryString({"/&%$·#&=":"1"}), '%2F%26%25%24%C2%B7%23%26%3D=1');
    assert.strictEqual(sut.generateUrlQueryString({"%":"%"}), '%25=%25');
    assert.strictEqual(sut.generateUrlQueryString({a:1,b:[1,2,3]}), 'a=1&b=%5B1%2C2%2C3%5D');
    assert.strictEqual(sut.generateUrlQueryString({a:1,b:{c:1}}), 'a=1&b=%7B%22c%22%3A1%7D');
    
    // Test ok values with HashMapObjects
    var hashMapObject = new HashMapObject();
    hashMapObject.set('/&%$·#&=', 1);
    assert.strictEqual(sut.generateUrlQueryString(hashMapObject), '%2F%26%25%24%C2%B7%23%26%3D=1');
    
    hashMapObject.set('b', 2);
    assert.strictEqual(sut.generateUrlQueryString(hashMapObject), '%2F%26%25%24%C2%B7%23%26%3D=1&b=2');
    
    hashMapObject.set('c', 3);
    assert.strictEqual(sut.generateUrlQueryString(hashMapObject), '%2F%26%25%24%C2%B7%23%26%3D=1&b=2&c=3');
    
    hashMapObject.set('d', 'he/&%$·#&=llo');
    assert.strictEqual(sut.generateUrlQueryString(hashMapObject), '%2F%26%25%24%C2%B7%23%26%3D=1&b=2&c=3&d=he%2F%26%25%24%C2%B7%23%26%3Dllo');
    
    // Test wrong values
    // Tested with exceptions

    // Test exceptions
    assert.throws(function() { sut.generateUrlQueryString("hello"); }, /keyValuePairs must be a HashMapObject or a non empty Object/);
    
    assert.throws(function() { sut.generateUrlQueryString([1,2,3,4]); }, /keyValuePairs must be a HashMapObject or a non empty Object/);
    
    assert.throws(function() { sut.generateUrlQueryString(new Error()); }, /keyValuePairs must be a HashMapObject or a non empty Object/);
    
    assert.throws(function() { sut.generateUrlQueryString(10); }, /keyValuePairs must be a HashMapObject or a non empty Object/);
    
    assert.throws(function() { sut.generateUrlQueryString(true); }, /keyValuePairs must be a HashMapObject or a non empty Object/);
});


/**
 * isInternetAvailable
 */
QUnit.test("isInternetAvailable", function(assert){

    // Test empty values
    for (var i = 0; i < emptyValuesCount; i++) {
        
        assert.throws(function() { sut.isInternetAvailable(emptyValues[i]); });
        
        assert.throws(function() { sut.isInternetAvailable(emptyValues[i], emptyValues[i]); });
    }

    // Test ok values    
    var done = assert.async();
    
    sut.isOnlyHttps = false;
    
    sut.isInternetAvailable(function(){
        
        assert.ok(sut.internetCheckLocations.length === 3);
        
        sut.internetCheckLocations = [browserManager.getCurrentUrl()];
        
        sut.isInternetAvailable(function(){
            
            assert.ok(sut.internetCheckLocations.length === 1);
            
            // Test exceptions
            sut.internetCheckLocations = ['hello bad url'];
            
            assert.throws(function() { sut.isInternetAvailable(function(){}, function(){}); }, /invalid check url : hello bad url/);
            
            sut.internetCheckLocations = [];
            
            assert.throws(function() { sut.isInternetAvailable(function(){}, function(){}); }, /no check locations specified/);
            
            done();
            
        }, function(){
            
            assert.ok(false, 'Internet is not available');
            done();
        });
        
    }, function(){
        
        assert.ok(false, 'Internet is not available');
        done();
    });
    
    // Test wrong values
    // We cannot force offline state, so this cannot be tested
});


/**
 * urlExists
 */
QUnit.test("urlExists", function(assert){

    // Test empty values
    for (var i = 0; i < emptyValuesCount; i++) {
        
        if(!StringUtils.isString(emptyValues[i])){
            
            assert.throws(function() { sut.urlExists(emptyValues[i], function(){}, function(){}); }, /url must be a string/);            
        }

        assert.throws(function() { sut.urlExists('https://www.google.com', emptyValues[i]); }, /params must be functions/);
        
        assert.throws(function() { sut.urlExists('https://www.google.com', function(){}, emptyValues[i]); }, /params must be functions/);
    }

    // Test ok values    
    var done = assert.async(2);
    
    assert.throws(function() { sut.urlExists(nonExistantUrl, function(){}, function(){}); }, /Non secure http requests are forbidden/);
    
    sut.isOnlyHttps = false;
    
    sut.urlExists(existantUrl, function(){
        
        assert.ok(true);
        done();
        
    }, function(){
        
        assert.ok(false);
        done();
    });

    // Test wrong values
    sut.urlExists(nonExistantUrl, function(){
        
        assert.ok(false);
        done();
        
    }, function(){
        
        assert.ok(true);
        done();
    });

    // Test exceptions
    // Already tested by empty values
});


/**
 * getUrlHeaders
 */
QUnit.test("getUrlHeaders", function(assert){
    
    // Test empty values
    for (var i = 0; i < emptyValuesCount; i++) {
        
        assert.throws(function() { sut.getUrlHeaders(emptyValues[i], function(){}, function(){}); });
        
        assert.throws(function() { sut.getUrlHeaders('https://www.google.com', emptyValues[i]); }, /params must be functions/);
        
        assert.throws(function() { sut.getUrlHeaders('https://www.google.com', function(){}, emptyValues[i]); }, /params must be functions/);
    }

    // Test ok values
    var done = assert.async(2);
    
    assert.throws(function() { sut.getUrlHeaders(browserManager.getCurrentUrl(), function(data){}, function(){}); }, /Non secure http requests are forbidden/);
    
    sut.isOnlyHttps = false;
    
    sut.getUrlHeaders(browserManager.getCurrentUrl(), function(data){
        
        assert.ok(data.length > 0);
        done();
        
    }, function(){
        
        assert.ok(false);
        done();
    });

    // Test wrong values
    sut.getUrlHeaders('https://www.google.com', function(){
        
        assert.ok(false, "Browser was able to read google.com headers but it shouldn't due to CORS restrictions. Make sure your browser is correctly blocking CORS and run tests again");
        done();
        
    }, function(msg, code){
        
        assert.ok(StringUtils.isString(msg));
        done();
    });

    // Test exceptions
    // Already tested by empty values
});


/**
 * execute
 */
QUnit.test("execute - check that resultFormat STRING works as expected", function(assert){

    var done = assert.async(2);
    
    sut.execute(basePath + '/file3.json', function(results, anyError){

        assert.strictEqual(anyError, false);
        assert.strictEqual(results[0].response, '{\r\n"a": "1",\r\n"b": 2\r\n}');
        assert.strictEqual(results[0].isError, false);
        done();
    });
    
    var request = new HTTPManagerGetRequest(basePath + '/file3.json');
    
    sut.execute(request, function(results, anyError){

        assert.strictEqual(anyError, false);
        assert.strictEqual(results[0].response, '{\r\n"a": "1",\r\n"b": 2\r\n}');
        assert.strictEqual(results[0].isError, false);
        done();
    });
});


/**
 * execute
 */
QUnit.test("execute - check that resultFormat JSON works as expected", function(assert){

    var done = assert.async(1);
    
    var request = new HTTPManagerGetRequest(basePath + '/file3.json');
    
    request.resultFormat = 'JSON';
    
    sut.execute(request, function(results, anyError){

        assert.strictEqual(anyError, false);
        assert.ok(ObjectUtils.isEqualTo(results[0].response, {a: "1",b: 2}));
        assert.strictEqual(results[0].isError, false);
        done();
    });
});


/**
 * execute
 */
QUnit.test("execute - check that resultFormat JSON marks the request as having error when a non parseable result is returned", function(assert){

    var done = assert.async(1);
    
    var request = new HTTPManagerGetRequest(basePath + '/file1.txt');
    
    request.resultFormat = 'JSON';
    
    sut.execute(request, function(results, anyError){

        assert.strictEqual(anyError, true);
        assert.strictEqual(results[0].response, 'text1');
        assert.strictEqual(results[0].isError, true);
        assert.strictEqual(results[0].errorMsg, 'Could not parse request result as a json string');
        done();
    });
});


/**
 * execute
 */
QUnit.test("execute - check that resultFormat JSON returns the error code and error message when a request with errors is performed", function(assert){

    var done = assert.async(1);
    
    var request = new HTTPManagerGetRequest('invalid url');
    
    request.resultFormat = 'JSON';
    
    sut.execute(request, function(results, anyError){

        assert.strictEqual(anyError, true);
        assert.strictEqual(results[0].url, 'invalid url');
        assert.strictEqual(results[0].response, '');
        assert.strictEqual(results[0].isError, true);
        assert.ok(results[0].errorMsg.length > 3);
        assert.ok(results[0].code > 300);
        assert.notStrictEqual(results[0].errorMsg, 'Could not parse request result as a json string');
        done();
    });
});


/**
 * execute
 */
QUnit.test("execute - requests with string urls", function(assert){
    
    // Test empty values
    for (var i = 0; i < emptyValuesCount; i++) {
        
        var expectedError = /Invalid requests value/;
        
        if(ArrayUtils.isArray(emptyValues[i]) && emptyValues[i].length === 0){
           
            expectedError = /No requests to execute/;
        }
        
        assert.throws(function() { sut.execute(emptyValues[i]); }, expectedError);
    }

    // Test ok values
    var done = assert.async(5);
    
    // Single url with error
    sut.execute('some invalid url', function(results, anyError){

        assert.strictEqual(anyError, true);
        assert.strictEqual(results[0].url, 'some invalid url');
        assert.strictEqual(results[0].response, '');
        assert.strictEqual(results[0].isError, true);
        assert.ok(results[0].errorMsg.length > 3);
        assert.strictEqual(results[0].code, 404);
        done();
    });
    
    // Single url without error
    sut.isOnlyHttps = false;
    
    sut.execute(existantUrl, function(results, anyError){

        assert.strictEqual(anyError, false);

        assert.strictEqual(results[0].url, existantUrl);
        assert.ok(!StringUtils.isEmpty(results[0].response));
        assert.ok(results[0].response.length > 5);        
        assert.strictEqual(results[0].isError, false);
        assert.strictEqual(results[0].errorMsg, '');
        assert.strictEqual(results[0].code, 200);
        done();
    });
    
    // Multiple urls with errors
    var multiErrProgressCount = 0;
    
    sut.execute(['invalidUrl1', 'invalidUrl2', 'invalidUrl3'], function(results, anyError){

        assert.strictEqual(multiErrProgressCount, 3);
        assert.strictEqual(anyError, true);
        
        for (var i = 0; i < 3; i++) {
        
            assert.strictEqual(results[i].url, 'invalidUrl' + String(i + 1));
            assert.strictEqual(results[i].response, '');
            assert.strictEqual(results[i].isError, true);
            assert.ok(results[i].errorMsg.length > 3);
            assert.strictEqual(results[i].code, 404);
        }
        
        done();
        
    }, function(completedUrl, totalRequests) {
        
        assert.ok(completedUrl.length > 3);
        assert.strictEqual(totalRequests, 3);
        multiErrProgressCount ++;
    });
    
    sut.execute([existantUrl, 'invalidUrl2', existantUrl], function(results, anyError){

        assert.strictEqual(anyError, true);
        
        assert.strictEqual(results[0].url, existantUrl);
        assert.ok(!StringUtils.isEmpty(results[0].response));
        assert.ok(results[0].response.length > 5);        
        assert.strictEqual(results[0].isError, false);
        assert.strictEqual(results[0].errorMsg, '');
        assert.strictEqual(results[0].code, 200);
                                
        assert.strictEqual(results[1].url, 'invalidUrl2');
        assert.strictEqual(results[1].response, '');
        assert.strictEqual(results[1].isError, true);
        assert.ok(results[1].errorMsg.length > 3);
        assert.strictEqual(results[1].code, 404);
        
        assert.strictEqual(results[2].url, existantUrl);
        assert.ok(!StringUtils.isEmpty(results[2].response));
        assert.ok(results[2].response.length > 5);        
        assert.strictEqual(results[2].isError, false);
        assert.strictEqual(results[2].errorMsg, '');
        assert.strictEqual(results[2].code, 200);
        
        done();
    });
    
    // Multiple urls without errors
    var multiProgressCount = 0;
    
    sut.execute([basePath + '/file1.txt', basePath + '/file2.xml', basePath + '/file3.json'], function(results, anyError){

        assert.strictEqual(multiProgressCount, 3);
        assert.strictEqual(anyError, false);
        
        for (var i = 0; i < 3; i++) {

            assert.ok(!StringUtils.isEmpty(results[i].response));
            assert.ok(results[i].response.length > 4);        
            assert.strictEqual(results[i].isError, false);
            assert.strictEqual(results[i].errorMsg, '');
            assert.strictEqual(results[i].code, 200);    
        }
        
        done();
        
    }, function(completedUrl, totalRequests) {
        
        assert.ok(completedUrl.length > 3);
        assert.strictEqual(totalRequests, 3);
        multiProgressCount ++;
    });
    
    // Test wrong values
    // Not necessary

    // Test exceptions
    assert.throws(function() { sut.execute(basePath + '/file1.txt', ['hello'], () => {}); }, /finishedCallback and progressCallback must be functions/);
    
    assert.throws(function() { sut.execute(basePath + '/file1.txt', () => {}, ['hello']); }, /finishedCallback and progressCallback must be functions/);
    
    assert.throws(function() { sut.execute([1, 2], () => {}, () => {}); }, /url 0 must be a non empty string/);
    
    assert.throws(function() { sut.execute(["1", 2], () => {}, () => {}); }, /url 1 must be a non empty string/);
});


/**
 * execute
 */
QUnit.test("execute - single HTTPManagerGetRequest with errors", function(assert){

    var done = assert.async();
    
    var progressCount = 0;
    var successCalled = false;
    var errorCalled = false;
    var finallyCalled = false;
    
    var request = new HTTPManagerGetRequest('some invalid url');
    
    request.successCallback = (response) => successCalled = true;

    request.errorCallback = (errorMsg, errorCode, response) => {
        assert.ok(errorMsg.length > 3);
        assert.strictEqual(errorCode, 404);
        assert.strictEqual(response, '');
        errorCalled = true;
    };
    
    request.finallyCallback = () => finallyCalled = true;
    
    sut.execute(request, function(results, anyError){

        assert.strictEqual(progressCount, 1);
        assert.strictEqual(successCalled, false);
        assert.strictEqual(errorCalled, true);
        assert.strictEqual(finallyCalled, true);
        
        assert.strictEqual(anyError, true);
        assert.strictEqual(results[0].url, 'some invalid url');
        assert.strictEqual(results[0].response, '');
        assert.strictEqual(results[0].isError, true);
        assert.ok(results[0].errorMsg.length > 3);
        assert.ok(results[0].code > 300);
        done();
        
    }, function(completedUrl, totalRequests) {
        
        assert.strictEqual(completedUrl, 'some invalid url');
        assert.strictEqual(totalRequests, 1);
        progressCount ++;
    });
});


/**
 * execute
 */
QUnit.test("execute - single HTTPManagerGetRequest without errors", function(assert){

    var done = assert.async();
    
    var progressCount = 0;
    var successCalled = false;
    var errorCalled = false;
    var finallyCalled = false;
    
    var request = new HTTPManagerGetRequest(basePath + '/file1.txt');
    
    request.successCallback = (response) => {
        assert.strictEqual(response, 'text1');
        successCalled = true;
    }

    request.errorCallback = (errorMsg, errorCode, response) => errorCalled = true;
    
    request.finallyCallback = () => finallyCalled = true;
    
    sut.execute(request, function(results, anyError){

        assert.strictEqual(progressCount, 1);
        assert.strictEqual(successCalled, true);
        assert.strictEqual(errorCalled, false);
        assert.strictEqual(finallyCalled, true);
        
        assert.strictEqual(anyError, false);
        assert.strictEqual(results[0].url, basePath + '/file1.txt');
        assert.strictEqual(results[0].response, 'text1');
        assert.strictEqual(results[0].isError, false);
        assert.strictEqual(results[0].errorMsg, '');
        assert.strictEqual(results[0].code, 200);
        done();
    
    }, function(completedUrl, totalRequests) {
        
        assert.strictEqual(completedUrl, basePath + '/file1.txt');
        assert.strictEqual(totalRequests, 1);
        progressCount ++;
    });
});


/**
 * execute
 */
QUnit.test("execute - single HTTPManagerGetRequest without errors and using baseUrl", function(assert){

    var done = assert.async();
    
    var successCalled = false;
    var errorCalled = false;
    var finallyCalled = false;
    
    var request = new HTTPManagerGetRequest('file1.txt');
    
    request.successCallback = (response) => {
        assert.strictEqual(response, 'text1');
        successCalled = true;
    }

    request.errorCallback = (errorMsg, errorCode, response) => errorCalled = true;
    
    request.finallyCallback = () => finallyCalled = true;
    
    sut.baseUrl = basePath;
    
    sut.execute(request, function(results, anyError){

        assert.strictEqual(successCalled, true);
        assert.strictEqual(errorCalled, false);
        assert.strictEqual(finallyCalled, true);
        
        assert.strictEqual(anyError, false);
        assert.strictEqual(results[0].url, basePath + '/file1.txt');
        assert.strictEqual(results[0].response, 'text1');
        assert.strictEqual(results[0].isError, false);
        assert.strictEqual(results[0].errorMsg, '');
        assert.strictEqual(results[0].code, 200);
        done();
    
    });
});


/**
 * execute
 */
QUnit.test("execute - multiple HTTPManagerGetRequest with errors", function(assert){
    
    var done = assert.async();
    
    var progressCount = 0;
    var successCalledCount = 0;
    var errorCalledCount = 0;
    var finallyCalledCount = 0;
    
    // Declare first request
    var request1 = new HTTPManagerGetRequest(basePath + '/file1.txt');
    
    request1.successCallback = (response) => {
        assert.strictEqual(response, 'text1');
        successCalledCount ++;
    }

    request1.errorCallback = (errorMsg, errorCode, response) => errorCalledCount ++;
    
    request1.finallyCallback = () => finallyCalledCount ++;
    
    // Declare second request
    var request2 = new HTTPManagerGetRequest('invalid url 1');
    
    request2.successCallback = (response) => successCalledCount ++;

    request2.errorCallback = (errorMsg, errorCode, response) => {
        assert.ok(errorMsg.length > 3);
        assert.strictEqual(errorCode, 404);
        errorCalledCount ++;
    };
    
    request2.finallyCallback = () => finallyCalledCount ++;
    
    // Declare third request
    var request3 = new HTTPManagerGetRequest('invalid url 2');
    
    request3.successCallback = (response) => successCalledCount ++;

    request3.errorCallback = (errorMsg, errorCode, response) => {
        assert.ok(errorMsg.length > 3);
        assert.strictEqual(errorCode, 404);
        errorCalledCount ++;
    };
    
    request3.finallyCallback = () => finallyCalledCount ++;
    
    // Launch the 3 requests and process the results
    sut.execute([request1, request2, request3], function(results, anyError){

        assert.strictEqual(progressCount, 3);
        assert.strictEqual(successCalledCount, 1);
        assert.strictEqual(errorCalledCount, 2);
        assert.strictEqual(finallyCalledCount, 3);
        
        assert.strictEqual(anyError, true);
        
        assert.strictEqual(results[0].url, basePath + '/file1.txt');
        
        for(var i = 1; i < 3; i++){
            
            assert.strictEqual(results[i].url, 'invalid url ' + i);
            assert.strictEqual(results[i].response, '');
            assert.strictEqual(results[i].isError, true);
            assert.ok(results[i].errorMsg.length > 3);
            assert.ok(results[i].code > 300);
        }
        
        done();
        
    }, function(completedUrl, totalRequests) {
        
        assert.strictEqual(totalRequests, 3);
        progressCount ++;
    });
});


/**
 * execute
 */
QUnit.test("execute - multiple HTTPManagerGetRequest without errors", function(assert){
    
    var done = assert.async();
    
    var progressCount = 0;
    var successCalledCount = 0;
    var errorCalledCount = 0;
    var finallyCalledCount = 0;
    
    // Declare first request
    var request1 = new HTTPManagerGetRequest(basePath + '/file1.txt');
    
    request1.successCallback = (response) => {
        assert.strictEqual(response, 'text1');
        successCalledCount ++;
    }

    request1.errorCallback = (errorMsg, errorCode, response) => errorCalledCount ++;
    
    request1.finallyCallback = () => finallyCalledCount ++;
    
    // Declare second request
    var request2 = new HTTPManagerGetRequest(basePath + '/file2.xml');
    
    request2.successCallback = (response) => {
        assert.strictEqual(response, "<test>\r\n    hello\r\n</test>");
        successCalledCount ++;
    }

    request2.errorCallback = (errorMsg, errorCode, response) => errorCalledCount ++;
    
    request2.finallyCallback = () => finallyCalledCount ++;
    
    // Declare third request
    var request3 = new HTTPManagerGetRequest(basePath + '/file3.json');
    
    request3.successCallback = (response) => {
        assert.strictEqual(response, '{\r\n"a": "1",\r\n"b": 2\r\n}');
        successCalledCount ++;
    }

    request3.errorCallback = (errorMsg, errorCode, response) => errorCalledCount ++;
    
    request3.finallyCallback = () => finallyCalledCount ++;
    
    // Launch the 3 requests and process the results
    sut.execute([request1, request2, request3], function(results, anyError){

        assert.strictEqual(progressCount, 3);
        assert.strictEqual(successCalledCount, 3);
        assert.strictEqual(errorCalledCount, 0);
        assert.strictEqual(finallyCalledCount, 3);
        
        assert.strictEqual(anyError, false);
        
        assert.strictEqual(results[0].url, basePath + '/file1.txt');
        assert.strictEqual(results[0].response, 'text1');
        assert.strictEqual(results[1].url, basePath + '/file2.xml');
        assert.strictEqual(results[1].response, "<test>\r\n    hello\r\n</test>");
        assert.strictEqual(results[2].url, basePath + '/file3.json');
        assert.strictEqual(results[2].response, '{\r\n"a": "1",\r\n"b": 2\r\n}');
        
        for(var i = 0; i < 3; i++){
            
            assert.strictEqual(results[i].isError, false);
            assert.strictEqual(results[i].errorMsg, '');
            assert.strictEqual(results[i].code, 200);
        }
        
        done();
        
    }, function(completedUrl, totalRequests) {
        
        assert.strictEqual(totalRequests, 3);
        progressCount ++;
    });
});


/**
 * execute
 */
QUnit.test("execute - single HTTPManagerGetRequest with bad request 400 error", function(assert){
    
    var done = assert.async();
    
    var progressCount = 0;
    var successCalledCount = 0;
    var errorCalledCount = 0;
    var finallyCalledCount = 0;
    
    // This request will generate a 400 bad request due to the ending %.
    var request = new HTTPManagerGetRequest('/%');
    
    request.successCallback = (response) => successCalledCount ++;

    request.errorCallback = (errorMsg, errorCode, response) => {
        
        assert.ok(errorMsg.toLowerCase().indexOf('bad request') >= 0);
        assert.strictEqual(errorCode, 400);
        assert.ok(response.toLowerCase().indexOf('bad request') >= 0);
        errorCalledCount ++;
    }
    
    request.finallyCallback = () => finallyCalledCount ++;
    
    // Launch the request and process the results
    sut.execute(request, function(results, anyError){

        assert.strictEqual(progressCount, 1);
        assert.strictEqual(successCalledCount, 0);
        assert.strictEqual(errorCalledCount, 1);
        assert.strictEqual(finallyCalledCount, 1);
        
        assert.strictEqual(anyError, true);
        
        assert.strictEqual(results[0].url, '/%');
        assert.ok(results[0].response.toLowerCase().indexOf('bad request') >= 0);
        assert.strictEqual(results[0].isError, true);
        assert.ok(results[0].errorMsg.toLowerCase().indexOf('bad request') >= 0);
        assert.strictEqual(results[0].code, 400);
                
        done();
        
    }, function(completedUrl, totalRequests) {
        
        assert.strictEqual(totalRequests, 1);
        progressCount ++;
    });
});


/**
 * execute
 */
QUnit.todo("execute - single HTTPManagerPostRequest with errors", function(assert){
    
    // TODO
});


/**
 * execute
 */
QUnit.todo("execute - single HTTPManagerPostRequest without errors", function(assert){
    
    // TODO
});


/**
 * execute
 */
QUnit.todo("execute - multiple HTTPManagerPostRequest with errors", function(assert){
    
    // TODO
});


/**
 * execute
 */
QUnit.todo("execute - multiple HTTPManagerPostRequest without errors", function(assert){
    
    // TODO
});


/**
 * execute
 */
QUnit.todo("execute - single HTTPManagerPostRequest with bad request 400 error", function(assert){
    
    // TODO
});


/**
 * execute
 */
QUnit.todo("execute - multiple string, HTTPManagerGetRequest and HTTPManagerPostRequest with and without errors", function(assert){
    
    // TODO - the most complex call possible: strings, HTTPManagerGetRequest, HTTPManagerPostRequest some failing and some not
});


/**
 * execute
 */
QUnit.todo("execute - HTTPManagerPostRequest with global post parameters", function(assert){
    
    // TODO - perform a test to make sure that global post parameters do work correctly and are always sent as part of the request
});


/**
 * queue
 */
QUnit.todo("queue - TODO all cases", function(assert){

    // TODO - extensive tests must be written the same way as the execute method
    // has been tested:
    // Test single and multiple string url calls
    // Test single and multiple HTTPManagerGetRequest calls
    // Test single and multiple HTTPManagerPostRequest calls
    // Test multiple mixed requests n the same method call: strings, HTTPManagerGetRequest, HTTPManagerPostRequest
});


/**
 * loadResourcesFromList
 */
QUnit.test("loadResourcesFromList", function(assert){

    // Test empty values
    for (var i = 0; i < emptyValuesCount; i++) {
        
        assert.throws(function() { sut.loadResourcesFromList(emptyValues[i], 'somepath'); }, /urlToListOfResources must be a non empty string/);
        
        assert.throws(function() { sut.loadResourcesFromList('somepath', emptyValues[i]); }, /baseUrl must be a non empty string/);
    } 

    // Test ok values
    var done = assert.async(3);

    sut.isOnlyHttps = false;
    
    sut.loadResourcesFromList(basePath + '/files-list.txt', basePath, function(resourcesList, resourcesData){

        assert.strictEqual(resourcesList.length, 3);
        assert.strictEqual(resourcesList[0], 'file1.txt');
        assert.strictEqual(resourcesList[1], 'file2.xml');
        assert.strictEqual(resourcesList[2], 'file3.json');
        assert.strictEqual(resourcesData[0], 'text1');
        assert.strictEqual(resourcesData[1], "<test>\r\n    hello\r\n</test>");
        assert.strictEqual(resourcesData[2], '{\r\n"a": "1",\r\n"b": 2\r\n}');
        done();
        
    }, function(errorUrl, errorMsg, errorCode){
        
        assert.ok(false, errorUrl + ' ' + errorMsg + ' ' + errorCode);
        done();
    });
    
    // test ok values with resourceLoadedCallback
    var progressCalls = 0;
    
    sut.loadResourcesFromList(basePath + '/files-list.txt', basePath + '/', function(resourcesList, resourcesData){

        assert.strictEqual(resourcesList.length, 3);
        assert.strictEqual(resourcesList[0], 'file1.txt');
        assert.strictEqual(resourcesList[1], 'file2.xml');
        assert.strictEqual(resourcesList[2], 'file3.json');
        assert.strictEqual(resourcesData[0], 'text1');
        assert.strictEqual(resourcesData[1], "<test>\r\n    hello\r\n</test>");
        assert.strictEqual(resourcesData[2], '{\r\n"a": "1",\r\n"b": 2\r\n}');
        assert.strictEqual(progressCalls, 3);        
        done();
        
    }, function(errorUrl, errorMsg, errorCode){
        
        assert.ok(false, errorUrl + ' ' + errorMsg + ' ' + errorCode);
        done();
        
    }, function(completedUrl){
        
        progressCalls ++;
    });

    // Test wrong values
    sut.loadResourcesFromList(nonExistantUrl, basePath, function(result){

        assert.ok(false);
        done();
        
    }, function(errorUrl, errorMsg, errorCode){
        
        assert.strictEqual(errorUrl, nonExistantUrl);
        assert.ok(StringUtils.isString(errorMsg));
        assert.ok(errorMsg.length > 5);
        assert.strictEqual(errorCode, 404);
        done();
    });

    // Test exceptions
    // not necessary
});


/**
 * loadResourcesFromList
 */
QUnit.test("loadResourcesFromList-one-resource-is-missing", function(assert){
    
    var done = assert.async(1);
    
    sut.loadResourcesFromList(basePath + '/files-list-with-one-missing.txt', basePath, function(result){

        assert.ok(false);
        done();
        
    }, function(errorUrl, errorMsg, errorCode){
        
        assert.strictEqual(errorUrl, basePath + '/this-is-missing.txt');
        assert.ok(StringUtils.isString(errorMsg));
        assert.ok(errorMsg.length > 5);
        assert.strictEqual(errorCode, 404);
        done();
    });
});