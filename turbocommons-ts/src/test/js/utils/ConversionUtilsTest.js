"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

QUnit.module("ConversionUtilsTest", {
    beforeEach : function() {

        window.ConversionUtils = org_turbocommons.ConversionUtils;
    },

    afterEach : function() {

        delete window.ConversionUtils;
    }
});


/**
 * stringToBase64
 */
QUnit.test("stringToBase64", function(assert){

    assert.strictEqual('', ConversionUtils.stringToBase64(undefined));
    assert.strictEqual('', ConversionUtils.stringToBase64(null));
    assert.strictEqual('', ConversionUtils.stringToBase64(''));

    // Try correct values
    assert.strictEqual('Zg==', ConversionUtils.stringToBase64('f'));
    assert.strictEqual('Zm8=', ConversionUtils.stringToBase64('fo'));
    assert.strictEqual('Zm9v', ConversionUtils.stringToBase64('foo'));
    assert.strictEqual('Zm9vYg==', ConversionUtils.stringToBase64('foob'));
    assert.strictEqual('Zm9vYmE=', ConversionUtils.stringToBase64('fooba'));
    assert.strictEqual('Zm9vYmFy', ConversionUtils.stringToBase64('foobar'));
    assert.strictEqual('w4Bpw5N1dSEvKCk=', ConversionUtils.stringToBase64('ÀiÓuu!/()'));
    assert.strictEqual('5L2g5aW95LiW55WM', ConversionUtils.stringToBase64('你好世界'));
    assert.strictEqual('bGluZTEKbGluZTIKbGluZTM=', ConversionUtils.stringToBase64("line1\nline2\nline3"));
    assert.strictEqual('eyAwLCAxLCAyLCAzLCA0LCA1LCA2LCA3LCA4LCA5IH0=', ConversionUtils.stringToBase64('{ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 }'));
    assert.strictEqual('QWFCYkNjRGRFZUZmR2dIaElpSmpLa0xsTW1Obk9vUHBRcVJyU3NUdFV1VnZXd1h4WXlaeg==', ConversionUtils.stringToBase64('AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz'));

    // Try some wrong values
    assert.throws(function(){ ConversionUtils.stringToBase64([]); }, '/value is not a string/');
    assert.throws(function(){ ConversionUtils.stringToBase64(98345); }, '/value is not a string/');
    assert.throws(function(){ ConversionUtils.stringToBase64(Qunit); }, '/value is not a string/');
});


/**
 * base64ToString
 */
QUnit.test("base64ToString", function(assert){

    assert.strictEqual('', ConversionUtils.base64ToString(undefined));
    assert.strictEqual('', ConversionUtils.base64ToString(null));
    assert.strictEqual('', ConversionUtils.base64ToString(''));

    // Try correct values
    assert.strictEqual('f', ConversionUtils.base64ToString('Zg=='));
    assert.strictEqual('fo', ConversionUtils.base64ToString('Zm8='));
    assert.strictEqual('foo', ConversionUtils.base64ToString('Zm9v'));
    assert.strictEqual('foob', ConversionUtils.base64ToString('Zm9vYg=='));
    assert.strictEqual('fooba', ConversionUtils.base64ToString('Zm9vYmE='));
    assert.strictEqual('foobar', ConversionUtils.base64ToString('Zm9vYmFy'));
    assert.strictEqual('ÀiÓuu!/()', ConversionUtils.base64ToString('w4Bpw5N1dSEvKCk='));
    assert.strictEqual('你好世界', ConversionUtils.base64ToString('5L2g5aW95LiW55WM'));

    // Try some random values encoded with stringToBase64
    for(let i = 0; i < 50; i++){

        let s = Math.random().toString(36).substring(20);

        assert.strictEqual(s, ConversionUtils.base64ToString(ConversionUtils.stringToBase64(s)));
    }

    // Try some wrong values
    assert.throws(function(){ ConversionUtils.base64ToString([]); });
    assert.throws(function(){ ConversionUtils.base64ToString(98345); });
    assert.throws(function(){ ConversionUtils.base64ToString(Qunit); });
});