"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

QUnit.module("EncodingUtilsTest", {
    beforeEach : function(){

        window.EncodingUtils = org_turbocommons.EncodingUtils;
    },

    afterEach : function(){

        delete window.EncodingUtils;
    }
});


/**
 * testUnicodeEscapedCharsToUtf8
 */
QUnit.test("testUnicodeEscapedCharsToUtf8", function(assert){

    // Test empty values
    assert.strictEqual(EncodingUtils.unicodeEscapedCharsToUtf8(''), '');
    assert.strictEqual(EncodingUtils.unicodeEscapedCharsToUtf8('   '), '   ');
    assert.strictEqual(EncodingUtils.unicodeEscapedCharsToUtf8("\n\r\n"), "\n\r\n");
    assert.strictEqual(EncodingUtils.unicodeEscapedCharsToUtf8("\n    \r\n   \r"), "\n    \r\n   \r");

    assert.throws(function() {
        EncodingUtils.unicodeEscapedCharsToUtf8(null);
    }, /Specified value must be a string/);

    assert.throws(function() {
        EncodingUtils.unicodeEscapedCharsToUtf8(0);
    }, /Specified value must be a string/);

    assert.throws(function() {
        EncodingUtils.unicodeEscapedCharsToUtf8([]);
    }, /Specified value must be a string/);
    
    assert.throws(function() {
        EncodingUtils.unicodeEscapedCharsToUtf8({});
    }, /Specified value must be a string/);

    // Test ok values
    assert.strictEqual(EncodingUtils.unicodeEscapedCharsToUtf8('1'), '1');
    assert.strictEqual(EncodingUtils.unicodeEscapedCharsToUtf8('.,_/}'), '.,_/}');
    assert.strictEqual(EncodingUtils.unicodeEscapedCharsToUtf8('hello'), 'hello');
    assert.strictEqual(EncodingUtils.unicodeEscapedCharsToUtf8('hello world'), 'hello world');
    assert.strictEqual(EncodingUtils.unicodeEscapedCharsToUtf8('hello\\ world\\'), 'hello\\ world\\');
    assert.strictEqual(EncodingUtils.unicodeEscapedCharsToUtf8('Eclipse Integration Commons 3.8.0 GA\\n\\'), 'Eclipse Integration Commons 3.8.0 GA\\n\\');
    assert.strictEqual(EncodingUtils.unicodeEscapedCharsToUtf8('Eclipse Inte\\u0024gration Commons 3.8.0 GA\\n\\'), 'Eclipse Inte$gration Commons 3.8.0 GA\\n\\');
    assert.strictEqual(EncodingUtils.unicodeEscapedCharsToUtf8('Bj\\u00F6rk'), 'Björk');
    assert.strictEqual(EncodingUtils.unicodeEscapedCharsToUtf8('Dodd\\u2013Frank'), 'Dodd–Frank');
    assert.strictEqual(EncodingUtils.unicodeEscapedCharsToUtf8('\\u0070\\u0075\\u0062\\u006c\\u0069\\u0063\\u007b\\u007d'), 'public{}');
    assert.strictEqual(EncodingUtils.unicodeEscapedCharsToUtf8('abc\\u79c1d\\u306e\\u5bb6\\u3078\\u306e\\u6b53\\u8fceef'), 'abc私dの家への歓迎ef');
    assert.strictEqual(EncodingUtils.unicodeEscapedCharsToUtf8('\\u79c1\\u306e\\u5bb6\\u3078\\u306e\\u6b53\\u8fce'), '私の家への歓迎');
    assert.strictEqual(EncodingUtils.unicodeEscapedCharsToUtf8("\r\n\\u79c1\\u306e\\u5bb6\\u3078\\u306e\\u6b53\\u8fce"), "\r\n私の家への歓迎");
    assert.strictEqual(EncodingUtils.unicodeEscapedCharsToUtf8("\r\n\\u79c1\\u306e\\u5bb6\\u3078\\u306e\\u6b53\r\n\\u8fce\\"), "\r\n私の家への歓\r\n迎\\");

    // Test wrong values
    // Not necessary

    // Test exceptions
    // Already tested
});


/**
 * testUtf8ToUnicodeEscapedChars
 */
QUnit.test("testUtf8ToUnicodeEscapedChars", function(assert){

    // Test empty values
    assert.strictEqual(EncodingUtils.utf8ToUnicodeEscapedChars(''), '');
    assert.strictEqual(EncodingUtils.utf8ToUnicodeEscapedChars('   '), '   ');
    assert.strictEqual(EncodingUtils.utf8ToUnicodeEscapedChars("\n\r\n"), "\n\r\n");
    assert.strictEqual(EncodingUtils.utf8ToUnicodeEscapedChars("\n    \r\n   \r"), "\n    \r\n   \r");

    assert.throws(function() {
        EncodingUtils.utf8ToUnicodeEscapedChars(null);
    });

    assert.throws(function() {
        EncodingUtils.utf8ToUnicodeEscapedChars(0);
    });

    assert.throws(function() {
        EncodingUtils.utf8ToUnicodeEscapedChars([]);
    });

    assert.throws(function() {
        EncodingUtils.utf8ToUnicodeEscapedChars(new stdClass());
    });

    // Test ok values
    assert.strictEqual(EncodingUtils.utf8ToUnicodeEscapedChars('1'), '1');
    assert.strictEqual(EncodingUtils.utf8ToUnicodeEscapedChars('.,_/}'), '.,_/}');
    assert.strictEqual(EncodingUtils.utf8ToUnicodeEscapedChars('hello'), 'hello');
    assert.strictEqual(EncodingUtils.utf8ToUnicodeEscapedChars('hello world'), 'hello world');
    assert.strictEqual(EncodingUtils.utf8ToUnicodeEscapedChars('hello\\ world\\'), 'hello\\ world\\');
    assert.strictEqual(EncodingUtils.utf8ToUnicodeEscapedChars('Eclipse Integration Commons 3.8.0 GA\\n\\'), 'Eclipse Integration Commons 3.8.0 GA\\n\\');
    assert.strictEqual(EncodingUtils.utf8ToUnicodeEscapedChars('Eclipse Inteögration Commons 3.8.0 GA\\n\\'), 'Eclipse Inte\\u00f6gration Commons 3.8.0 GA\\n\\');
    assert.strictEqual(EncodingUtils.utf8ToUnicodeEscapedChars('Björk'), 'Bj\\u00f6rk');
    assert.strictEqual(EncodingUtils.utf8ToUnicodeEscapedChars('Dodd–Frank'), 'Dodd\\u2013Frank');
    assert.strictEqual(EncodingUtils.utf8ToUnicodeEscapedChars('public{}'), 'public{}');
    assert.strictEqual(EncodingUtils.utf8ToUnicodeEscapedChars('abc私dの家への歓迎ef'), 'abc\\u79c1d\\u306e\\u5bb6\\u3078\\u306e\\u6b53\\u8fceef');
    assert.strictEqual(EncodingUtils.utf8ToUnicodeEscapedChars('私の家への歓迎'), '\\u79c1\\u306e\\u5bb6\\u3078\\u306e\\u6b53\\u8fce');
    assert.strictEqual(EncodingUtils.utf8ToUnicodeEscapedChars("\r\n私の家への歓迎"), "\r\n\\u79c1\\u306e\\u5bb6\\u3078\\u306e\\u6b53\\u8fce");
    assert.strictEqual(EncodingUtils.utf8ToUnicodeEscapedChars("\r\n私の家への歓\r\n迎\\"), "\r\n\\u79c1\\u306e\\u5bb6\\u3078\\u306e\\u6b53\r\n\\u8fce\\");

    // Test wrong values
    // Not necessary

    // Test exceptions
    // Already tested
});