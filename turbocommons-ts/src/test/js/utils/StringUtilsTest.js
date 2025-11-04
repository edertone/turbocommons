"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

QUnit.module("StringUtilsTest", {
    beforeEach : function() {

        window.NumericUtils = org_turbocommons.NumericUtils;
        window.StringUtils = org_turbocommons.StringUtils;
        window.ArrayUtils = org_turbocommons.ArrayUtils;
        window.ObjectUtils = org_turbocommons.ObjectUtils;
        
        window.emptyValues = [undefined, null, '', [], {}, '     ', "\n\n\n", 0];
        window.emptyValuesCount = window.emptyValues.length;
    },

    afterEach : function() {

        delete window.NumericUtils;
        delete window.StringUtils;
        delete window.ArrayUtils;
        delete window.ObjectUtils;
        
        delete window.emptyValues;
        delete window.emptyValuesCount;
    }
});


/**
 * isString
 */
QUnit.test("isString", function(assert) {

    assert.ok(StringUtils.isString(''));
    assert.ok(StringUtils.isString('      '));
    assert.ok(StringUtils.isString('1'));
    assert.ok(StringUtils.isString('a'));
    assert.ok(StringUtils.isString('hello'));
    assert.ok(StringUtils.isString("hello\n\nguys"));

    assert.ok(!StringUtils.isString(undefined));
    assert.ok(!StringUtils.isString(null));
    assert.ok(!StringUtils.isString(0));
    assert.ok(!StringUtils.isString(15));
    assert.ok(!StringUtils.isString([]));
    assert.ok(!StringUtils.isString([1]));
    assert.ok(!StringUtils.isString(['a', 'cd']));
    assert.ok(!StringUtils.isString({}));
    assert.ok(!StringUtils.isString(new Error()));
});


/**
 * forceString
 */
QUnit.test("forceString", function(assert) {
    
    StringUtils.forceString('');
    StringUtils.forceString('      ');
    StringUtils.forceString('1');
    StringUtils.forceString('a');
    StringUtils.forceString('hello');
    StringUtils.forceString("hello\n\nguys");

    assert.throws(function() { StringUtils.forceString(null, 'null'); }, /null must be a string/);
    assert.throws(function() { StringUtils.forceString(0, '0'); }, /0 must be a string/);
    assert.throws(function() { StringUtils.forceString(15, '15'); }, /15 must be a string/);
    assert.throws(function() { StringUtils.forceString([], 'array'); }, /array must be a string/);
    assert.throws(function() { StringUtils.forceString([1], 'array'); }, /array must be a string/);
    assert.throws(function() { StringUtils.forceString(['a', 'cd'], "array"); }, /array must be a string/);
    assert.throws(function() { StringUtils.forceString({}, '{}'); }, /{} must be a string/);
    assert.throws(function() { StringUtils.forceString(new Error(), 'new Error'); }, /new Error must be a string/);
});


/**
 * isUrl
 */
QUnit.test("isUrl", function(assert) {

    // Wrong url cases
    assert.notOk(StringUtils.isUrl(''));
    assert.notOk(StringUtils.isUrl(null));
    assert.notOk(StringUtils.isUrl([]));
    assert.notOk(StringUtils.isUrl('    '));
    assert.notOk(StringUtils.isUrl('123f56ccaca'));
    assert.notOk(StringUtils.isUrl('8/%$144///(!(/"'));
    assert.notOk(StringUtils.isUrl('http'));
    assert.notOk(StringUtils.isUrl('x.y'));
    assert.notOk(StringUtils.isUrl('http://x.y'));
    assert.notOk(StringUtils.isUrl('google.com-'));
    assert.notOk(StringUtils.isUrl("\n   \t\n"));
    assert.notOk(StringUtils.isUrl('./test/file.js'));
    assert.notOk(StringUtils.isUrl('http:\\google.com'));
    assert.notOk(StringUtils.isUrl('_http://google.com'));
    assert.notOk(StringUtils.isUrl('http://www.example..com'));
    assert.notOk(StringUtils.isUrl('http://.com'));
    assert.notOk(StringUtils.isUrl('http://www.example.'));
    assert.notOk(StringUtils.isUrl('http:/www.example.com'));
    assert.notOk(StringUtils.isUrl('http://'));
    assert.notOk(StringUtils.isUrl('http://.'));
    assert.notOk(StringUtils.isUrl('http://??/'));
    assert.notOk(StringUtils.isUrl('http://foo.bar?q=Spaces should be encoded'));
    assert.notOk(StringUtils.isUrl('rdar://1234'));
    assert.notOk(StringUtils.isUrl('http://foo.bar/foo(bar)baz quux'));
    assert.notOk(StringUtils.isUrl('http://10.1.1.255'));
    assert.notOk(StringUtils.isUrl('http://.www.foo.bar./'));
    assert.notOk(StringUtils.isUrl('http://.www.foo.bar/'));
    assert.notOk(StringUtils.isUrl('ftp://user:password@host:port/path'));
    assert.notOk(StringUtils.isUrl('/nfs/an/disks/jj/home/dir/file.txt'));
    assert.notOk(StringUtils.isUrl('C:\\Program Files (x86)'));
    assert.notOk(StringUtils.isUrl('http://www.google.com\\test.html'));

    // good url cases
    assert.ok(StringUtils.isUrl('http://x.ye'));
    assert.ok(StringUtils.isUrl('http://google.com'));
    assert.ok(StringUtils.isUrl('ftp://mydomain.com'));
    assert.ok(StringUtils.isUrl('http://www.example.com:8800'));
    assert.ok(StringUtils.isUrl('http://www.example.com/a/b/c/d/e/f/g/h/i.html'));
    assert.ok(StringUtils.isUrl('http://www.test.com/do.html#A'));
    assert.ok(StringUtils.isUrl('https://subdomain.test.com/'));
    assert.ok(StringUtils.isUrl('https://test.com'));
    assert.ok(StringUtils.isUrl('http://foo.com/blah_blah/'));
    assert.ok(StringUtils.isUrl('https://www.example.com/foo/?bar=baz&inga=42&quux'));
    assert.ok(StringUtils.isUrl('http://userid@example.com:8080'));
    assert.ok(StringUtils.isUrl('http://➡.ws/䨹'));
    assert.ok(StringUtils.isUrl('http://⌘.ws/'));
    assert.ok(StringUtils.isUrl('http://foo.bar/?q=Test%20URL-encoded%20stuff'));
    assert.ok(StringUtils.isUrl('http://-.~_!$&\'()*+,;=:%40:80%2f::::::@example.com'));
    assert.ok(StringUtils.isUrl('http://223.255.255.254'));
    assert.ok(StringUtils.isUrl('ftp://user:password@host.com:8080/path'));
    assert.ok(StringUtils.isUrl('http://www.google.com/test.html?a=1'));
    assert.ok(StringUtils.isUrl('http://www.google.com/test.html?a=1&b=2'));
    assert.ok(StringUtils.isUrl('http://www.google.com/test.html?a=1&b=2?c=3'));
    assert.ok(StringUtils.isUrl('http://www.google.com/test.html?a=1&b=2?????'));
    assert.ok(StringUtils.isUrl('http://www.test.com?pageid=123&testid=1524'));
    
    // Test non string values throw exceptions
    assert.throws(function() {

        StringUtils.isUrl([12341]);
    });

    assert.throws(function() {

        StringUtils.isUrl(12341);
    });
});


/**
 * isEmpty
 */
QUnit.test("isEmpty", function(assert) {

    assert.ok(StringUtils.isEmpty(undefined));
    assert.ok(StringUtils.isEmpty(null));
    assert.ok(StringUtils.isEmpty(''));
    assert.ok(StringUtils.isEmpty([]));
    assert.ok(StringUtils.isEmpty('      '));
    assert.ok(StringUtils.isEmpty("\n\n  \n"));
    assert.ok(StringUtils.isEmpty("\t   \n     \r\r"));
    assert.ok(StringUtils.isEmpty('EMPTY', ['EMPTY']));
    assert.ok(StringUtils.isEmpty('EMPTY       void   hole    ', ['EMPTY', 'void', 'hole']));

    assert.ok(!StringUtils.isEmpty('adsadf'));
    assert.ok(!StringUtils.isEmpty('    sdfasdsf'));
    assert.ok(!StringUtils.isEmpty('EMPTY'));
    assert.ok(!StringUtils.isEmpty('EMPTY test', ['EMPTY']));
    assert.ok(!StringUtils.isEmpty('EMPTY       void   hole    XX', ['EMPTY', 'void', 'hole']));

    // Test non string value gives exception
    assert.throws(function() { StringUtils.isEmpty(123); }, /value is not a string/);
});


/**
 * isStartingWith
 */
QUnit.test("isStartingWith", function(assert) {

    // Test empty values
    assert.throws(function() { StringUtils.isStartingWith(null, null); }, /startingValues is not an array/);
    assert.throws(function() { StringUtils.isStartingWith(0, 0); }, /startingValues is not an array/);
    assert.notOk(StringUtils.isStartingWith('', []));
    assert.ok(StringUtils.isStartingWith('', ['']));
    assert.ok(StringUtils.isStartingWith('a', ['']));

    // Test ok values
    assert.ok(StringUtils.isStartingWith('a', ['a']));
    assert.ok(StringUtils.isStartingWith('abc', ['a']));
    assert.notOk(StringUtils.isStartingWith('abc', ['b']));
    assert.ok(StringUtils.isStartingWith('abcde', ['ab']));
    assert.ok(StringUtils.isStartingWith('abcde', ['abcd']));

    // Test wrong values
    // Not necessary

    // Test exceptions
    assert.throws(function() { StringUtils.isStartingWith([1, 2, 3, 4], 0); }, /startingValues is not an array/);
    assert.throws(function() { StringUtils.isStartingWith('', 0); }, /startingValues is not an array/);
    assert.throws(function() { StringUtils.isStartingWith('', ''); }, /startingValues is not an array/);
});


/**
 * isEndingWith
 */
QUnit.test("isEndingWith", function(assert) {

    // Test empty values
    assert.throws(function() { StringUtils.isEndingWith(null, null); }, /endingValues is not an array/);
    assert.throws(function() { StringUtils.isEndingWith(0, 0); }, /endingValues is not an array/);
    assert.notOk(StringUtils.isEndingWith('', []));
    assert.ok(StringUtils.isEndingWith('', ['']));
    assert.ok(StringUtils.isEndingWith('a', ['']));

    // Test ok values
    assert.ok(StringUtils.isEndingWith('a', ['a']));
    assert.ok(StringUtils.isEndingWith('abc', ['c']));
    assert.notOk(StringUtils.isEndingWith('abc', ['b']));
    assert.ok(StringUtils.isEndingWith('abcde', ['de']));
    assert.ok(StringUtils.isEndingWith('abcde', ['bcde']));

    // Test wrong values
    // Not necessary

    // Test exceptions
    assert.throws(function() { StringUtils.isEndingWith([1, 2, 3, 4], 0); }, /endingValues is not an array/);
    assert.throws(function() { StringUtils.isEndingWith('', 0); }, /endingValues is not an array/);
    assert.throws(function() { StringUtils.isEndingWith('', ''); }, /endingValues is not an array/);
});


/**
 * forceNonEmptyString
 */
QUnit.test("forceNonEmptyString", function(assert) {
    
    assert.throws(function() { StringUtils.forceNonEmptyString(null); }, /must be a non empty string/);
    assert.throws(function() { StringUtils.forceNonEmptyString(0); }, /must be a non empty string/);
    assert.throws(function() { StringUtils.forceNonEmptyString(''); }, /must be a non empty string/);
    assert.throws(function() { StringUtils.forceNonEmptyString([]); }, /must be a non empty string/);
    assert.throws(function() { StringUtils.forceNonEmptyString('      '); }, /must be a non empty string/);
    assert.throws(function() { StringUtils.forceNonEmptyString("\n\n  \n"); }, /must be a non empty string/);
    assert.throws(function() { StringUtils.forceNonEmptyString("\t   \n     \r\r"); }, /must be a non empty string/);

    StringUtils.forceNonEmptyString('adsadf');
    StringUtils.forceNonEmptyString('    sdfasdsf');
    StringUtils.forceNonEmptyString('EMPTY');

    // Test non string value gives exception
    assert.throws(function() { StringUtils.forceNonEmptyString(123); }, /must be a non empty string/);
});


/**
 * isCamelCase
 */
QUnit.todo("isCamelCase", function(assert) {

    // TODO: copy tests from PHP
});


/**
 * isSnakeCase
 */
QUnit.todo("isSnakeCase", function(assert) {

    // TODO: copy tests from PHP
});


/**
 * replace
 */
QUnit.test("replace", function(assert) {

    // Test empty values
    for(var i = 0; i < emptyValuesCount; i++){
        
        if(StringUtils.isString(emptyValues[i])){
            
            assert.strictEqual(StringUtils.replace(emptyValues[i], "a", "b"), emptyValues[i]);
            assert.strictEqual(StringUtils.replace("string", emptyValues[i], "b"), "string");
            assert.strictEqual(StringUtils.replace("string", "a", emptyValues[i]), "string");
            
            assert.throws(function() {
                StringUtils.replace("string", "a", "b", emptyValues[i]);
            }, /count must be a positive integer/);
            
        }else{
            
            assert.throws(function() {
                StringUtils.replace(emptyValues[i], "a", "b");
            }, /string is not valid/);
            
            if(ArrayUtils.isArray(emptyValues[i])){
                
                assert.strictEqual(StringUtils.replace("string", emptyValues[i], "b"), "string");
                assert.strictEqual(StringUtils.replace("string", "a", emptyValues[i]), "string");
                
            }else{
                
                assert.throws(function() {
                    StringUtils.replace("string", emptyValues[i], "b");
                }, /search is not a string or array/);
                
                assert.throws(function() {
                    StringUtils.replace("string", "a", emptyValues[i]);
                }, /replacement is not a string or array/);
            }            
        }
    }

    // Test ok values
    assert.strictEqual(StringUtils.replace("x", "", "xyz"), "x");
    assert.strictEqual(StringUtils.replace("x", "x", "xyz"), "xyz");
    assert.strictEqual(StringUtils.replace("x", "x", "$"), "$");
    assert.strictEqual(StringUtils.replace("x", "x", "$$"), "$$");
    assert.strictEqual(StringUtils.replace("string", "str", ""), "ing");
    assert.strictEqual(StringUtils.replace("string", "s", "b"), "btring");
    assert.strictEqual(StringUtils.replace("string", "st", "ab"), "abring");
    assert.strictEqual(StringUtils.replace("string", "string", ""), "");
    assert.strictEqual(StringUtils.replace("abababAb", "a", "X"), "XbXbXbAb");
    assert.strictEqual(StringUtils.replace("abababAb", "aba", "r"), "rbabAb");
    assert.strictEqual(StringUtils.replace("8888888888888", "8", ""), "");
    assert.strictEqual(StringUtils.replace("+$-/\\_", "$", "Q"), "+Q-/\\_");
    
    assert.strictEqual(StringUtils.replace("string", ["s"], "b"), "btring");
    assert.strictEqual(StringUtils.replace("string", ["s", "i", "g"], "b"), "btrbnb");
    assert.strictEqual(StringUtils.replace("string", ["s", "i"], ["b", " "]), "btr ng");
    assert.strictEqual(StringUtils.replace("Hello???", ["H", "E", "?"], ["h", "X", "!"]), "hello!!!");
    assert.strictEqual(StringUtils.replace("  k9 ", ['\\', ' '], ['\\\\', '\\ ']), "\\ \\ k9\\ ");
    assert.strictEqual(StringUtils.replace("yyyyyy", ["yy", "yyyy"], ["y", "y"]), "yyy");
    assert.strictEqual(StringUtils.replace("yyyyyy", ["yyyy", "yy"], ["y", "y"]), "yy");
    assert.strictEqual(StringUtils.replace("yyyyyyyy", ["yy", "yyyy"], ["y", "y"]), "y");
    
    // Test ok values with limited count
    assert.strictEqual(StringUtils.replace("x", "", "xyz", 0), "x");
    assert.strictEqual(StringUtils.replace("x", "", "xyz", 1), "x");
    assert.strictEqual(StringUtils.replace("x", "x", "xyz", 1), "xyz");
    assert.strictEqual(StringUtils.replace("xxx", "x", "xyz", 1), "xyzxx");
    assert.strictEqual(StringUtils.replace("abababAb", "a", "X", 0), "abababAb");
    assert.strictEqual(StringUtils.replace("abababAb", "a", "X", 2), "XbXbabAb");
    assert.strictEqual(StringUtils.replace("abababAb", "aba", "r", 3), "rbabAb");
    assert.strictEqual(StringUtils.replace("abababAbabaabaaba", "aba", "r", 3), "rbabAbrraba");
    assert.strictEqual(StringUtils.replace("+$-/$\\_", "$", "Q", 1), "+Q-/$\\_");
    assert.strictEqual(StringUtils.replace("8888888888888", "8", "", 5), "88888888");
    
    assert.strictEqual(StringUtils.replace("123123123", ["1", "2"], "A", 1), "AA3123123");
    assert.strictEqual(StringUtils.replace("123123123", ["1", "2"], "A", 2), "AA3AA3123");
    assert.strictEqual(StringUtils.replace("123123123", ["1", "2"], ["A", "B"], 4), "AB3AB3AB3");
    
    // Test wrong values
    // not necessary

    // Test exceptions
    assert.throws(function() { StringUtils.replace("string", "a", "b", 'a'); }, /count must be a positive integer or 0/);
    assert.throws(function() { StringUtils.replace("string", ["a"], ["b", "c"]); }, /search and replacement arrays must have the same length/);
    assert.throws(function() { StringUtils.replace("string", ["a", "b", "c"], ["b", "c"]); }, /search and replacement arrays must have the same length/);
});


/**
 * replaceMulti
 */
QUnit.test("replaceMulti", function(assert) {

    // Test empty values
    // Note that replace method is already testing most of the empty values, so we won't be doing redundant tests here
    assert.strictEqual(StringUtils.replaceMulti(null, "s", "b"), null);
    assert.strictEqual(StringUtils.replaceMulti("", "s", "b"), "");
    assert.ok(ArrayUtils.isEqualTo(StringUtils.replaceMulti([], "s", "b"), []));
    assert.ok(ObjectUtils.isEqualTo(StringUtils.replaceMulti({}, "s", "b"), {}));
    
    // Test ok values
    assert.strictEqual(StringUtils.replaceMulti("string", "s", "b"), "btring");
    assert.ok(ArrayUtils.isEqualTo(StringUtils.replaceMulti(["string", "string", "s", "ss", "sb"], "s", "b"), ["btring", "btring", "b", "bb", "bb"]));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.replaceMulti([1, "string", "s", 2, "sb"], "s", "b"), [1, "btring", "b", 2, "bb"]));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.replaceMulti([1, "string", "s", 2, {a: 1}], "s", "b"), [1, "btring", "b", 2, {a: 1}]));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.replaceMulti([1, "string", "s", 2, [2, 3, "as"]], "s", "b"), [1, "btring", "b", 2, [2, 3, "ab"]]));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.replaceMulti(["string", [2, "as"], {a: 1, b: "ss"}], "s", "b"), ["btring", [2, "ab"], {a: 1, b: "bb"}]));
    assert.ok(ObjectUtils.isEqualTo(StringUtils.replaceMulti({a: 1, b: "sss"}, "s", "b"), {a: 1, b: "bbb"}));
    assert.ok(ObjectUtils.isEqualTo(StringUtils.replaceMulti({a: 1, b: "sss", c: [1, 2, null, "sas"]}, "s", "b"), {a: 1, b: "bbb", c: [1, 2, null, "bab"]}));
    
    // Test wrong values
    assert.strictEqual(StringUtils.replaceMulti(1, "s", "b"), 1);

    // Test that original object is not modified
    let object1 = [{a: 1, b: "sss", c: [1, 2, null, "sas"]}, "somes", [1,2,3]];
    let object2 = ObjectUtils.clone(object1);
    StringUtils.replaceMulti(object1, "s", "b");
    assert.ok(ArrayUtils.isEqualTo(object1, object2));

    // Test exceptions
    // Already tested on replace method tests
});


/**
 * trim
 */
QUnit.test("trim", function(assert){

    // Test empty values
    assert.throws(function() {
        StringUtils.trim(undefined);
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trim(null);
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trim(0);
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trim([]);
    }, /value is not a string/);
    
    assert.strictEqual(StringUtils.trim(""), "");

    // Test ok values
    assert.strictEqual(StringUtils.trim("   "), "");
    assert.strictEqual(StringUtils.trim("\n\n\r\r"), "");
    assert.strictEqual(StringUtils.trim("  \n\n\r\r"), "");
    assert.strictEqual(StringUtils.trim("\n\n\r\r   "), "");
    assert.strictEqual(StringUtils.trim("   \n\n\r\r   "), "");
    assert.strictEqual(StringUtils.trim("hello"), "hello");
    assert.strictEqual(StringUtils.trim("hello\n"), "hello");
    assert.strictEqual(StringUtils.trim("hello\r\n"), "hello");
    assert.strictEqual(StringUtils.trim("\nhello\r\n"), "hello");
    assert.strictEqual(StringUtils.trim("   hello"), "hello");
    assert.strictEqual(StringUtils.trim("hello   "), "hello");
    assert.strictEqual(StringUtils.trim("  hello  "), "hello");
    
    assert.strictEqual(StringUtils.trim("helloxax", "xa"), "hello");
    assert.strictEqual(StringUtils.trim("XXXhello", "xlX"), "hello");
    assert.strictEqual(StringUtils.trim("XXXhelloxxx", "xX"), "hello");
    assert.strictEqual(StringUtils.trim("1|2", "123"), "|");
    assert.strictEqual(StringUtils.trim("1|2", ""), "1|2");
    assert.strictEqual(StringUtils.trim("1|2\n", "1"), "|2\n");

    // Test wrong values
    // Not necessary

    // Test exceptions
    assert.throws(function() {
        StringUtils.trim([1, 2, 3, 4]);
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trim(new Error());
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trim(123466);
    }, /value is not a string/);
});


/**
 * trimLeft
 */
QUnit.test("trimLeft", function(assert){

    // Test empty values
    assert.throws(function() { StringUtils.trimLeft(undefined); }, /value is not a string/);
    assert.throws(function() { StringUtils.trimLeft(null); }, /value is not a string/);
    assert.throws(function() { StringUtils.trimLeft(0); }, /value is not a string/);
    assert.throws(function() { StringUtils.trimLeft([]); }, /value is not a string/);
    
    assert.strictEqual(StringUtils.trimLeft(""), "");

    // Test ok values
    assert.strictEqual(StringUtils.trimLeft("   "), "");
    assert.strictEqual(StringUtils.trimLeft("\n\n\r\r"), "");
    assert.strictEqual(StringUtils.trimLeft("  \n\n\r\r"), "");
    assert.strictEqual(StringUtils.trimLeft("\n\n\r\r   "), "");
    assert.strictEqual(StringUtils.trimLeft("   \n\n\r\r   "), "");
    assert.strictEqual(StringUtils.trimLeft("hello"), "hello");
    assert.strictEqual(StringUtils.trimLeft("hello\n"), "hello\n");
    assert.strictEqual(StringUtils.trimLeft("hello\r\n"), "hello\r\n");
    assert.strictEqual(StringUtils.trimLeft("\nhello\r\n"), "hello\r\n");
    assert.strictEqual(StringUtils.trimLeft("   hello"), "hello");
    assert.strictEqual(StringUtils.trimLeft("hello   "), "hello   ");
    assert.strictEqual(StringUtils.trimLeft("  hello  "), "hello  ");
    
    assert.strictEqual(StringUtils.trimLeft("helloxax", "xa"), "helloxax");
    assert.strictEqual(StringUtils.trimLeft("XXXhello", "xlX"), "hello");
    assert.strictEqual(StringUtils.trimLeft("XXXhelloxxx", "xX"), "helloxxx");
    assert.strictEqual(StringUtils.trimLeft("1|2", "123"), "|2");
    assert.strictEqual(StringUtils.trimLeft("1|2", ""), "1|2");
    assert.strictEqual(StringUtils.trimLeft("1|2\n", "1"), "|2\n");

    // Test wrong values
    // Not necessary

    // Test exceptions
    assert.throws(function() { StringUtils.trimLeft([1, 2, 3, 4]); }, /value is not a string/);
    assert.throws(function() { StringUtils.trimLeft(new Error()); }, /value is not a string/);
    assert.throws(function() { StringUtils.trimLeft(123466); }, /value is not a string/);
});


/**
 * trimRight
 */
QUnit.test("trimRight", function(assert){

    // Test empty values
    assert.throws(function() {
        StringUtils.trimRight(undefined);
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trimRight(null);
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trimRight(0);
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trimRight([]);
    }, /value is not a string/);
    
    assert.strictEqual(StringUtils.trimRight(""), "");

    // Test ok values
    assert.strictEqual(StringUtils.trimRight("   "), "");
    assert.strictEqual(StringUtils.trimRight("\n\n\r\r"), "");
    assert.strictEqual(StringUtils.trimRight("  \n\n\r\r"), "");
    assert.strictEqual(StringUtils.trimRight("\n\n\r\r   "), "");
    assert.strictEqual(StringUtils.trimRight("   \n\n\r\r   "), "");
    assert.strictEqual(StringUtils.trimRight("hello"), "hello");
    assert.strictEqual(StringUtils.trimRight("hello\n"), "hello");
    assert.strictEqual(StringUtils.trimRight("hello\r\n"), "hello");
    assert.strictEqual(StringUtils.trimRight("\nhello\r\n"), "\nhello");
    assert.strictEqual(StringUtils.trimRight("   hello"), "   hello");
    assert.strictEqual(StringUtils.trimRight("hello   "), "hello");
    assert.strictEqual(StringUtils.trimRight("  hello  "), "  hello");
    
    assert.strictEqual(StringUtils.trimRight("helloxax", "xa"), "hello");
    assert.strictEqual(StringUtils.trimRight("XXXhello", "xlX"), "XXXhello");
    assert.strictEqual(StringUtils.trimRight("XXXhelloxxx", "xX"), "XXXhello");
    assert.strictEqual(StringUtils.trimRight("1|2", "123"), "1|");
    assert.strictEqual(StringUtils.trimRight("1|2", ""), "1|2");
    assert.strictEqual(StringUtils.trimRight("1|2\n", "1"), "1|2\n");

    // Test wrong values
    // Not necessary

    // Test exceptions
    assert.throws(function() {
        StringUtils.trimRight([1, 2, 3, 4]);
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trimRight(new Error());
    }, /value is not a string/);
    
    assert.throws(function() {
        StringUtils.trimRight(123466);
    }, /value is not a string/);
});


/**
 * pad
 */
QUnit.test("pad", function(assert){

    // Test empty values
    assert.throws(function() { StringUtils.pad(undefined, 0); }, /string and padString must be strings/);
    assert.throws(function() { StringUtils.pad(null, 0); }, /string and padString must be strings/);
    assert.throws(function() { StringUtils.pad(0, 0); }, /string and padString must be strings/);
    assert.throws(function() { StringUtils.pad([], 0); }, /string and padString must be strings/);
    assert.throws(function() { StringUtils.pad('', ''); }, /padLength is not an int/);
    assert.throws(function() { StringUtils.pad('', 0, '', ''); }, /string and padString must be strings/);
    assert.throws(function() { StringUtils.pad('', 0, '0', ''); }, /mode must be LEFT or RIGHT/);
    assert.strictEqual(StringUtils.pad('', 0), "");
    assert.strictEqual(StringUtils.pad('      ', 0), '      ');
    
    // Test ok values - Padding left
    assert.strictEqual(StringUtils.pad('', 1), '0');
    assert.strictEqual(StringUtils.pad('', 2), '00');
    assert.strictEqual(StringUtils.pad('', 4), '0000');
    assert.strictEqual(StringUtils.pad('hello', 4), 'hello');
    assert.strictEqual(StringUtils.pad('hello', 5), 'hello');
    assert.strictEqual(StringUtils.pad('hello', 6), '0hello');
    assert.strictEqual(StringUtils.pad('hello', 8), '000hello');
    assert.strictEqual(StringUtils.pad('hello', 10), '00000hello');
    assert.strictEqual(StringUtils.pad('hello', 10, 'X'), 'XXXXXhello');
    assert.strictEqual(StringUtils.pad('hello', 12, 'ab'), 'babababhello');
    assert.strictEqual(StringUtils.pad('hello', 15, 'ab'), 'abababababhello');
    assert.strictEqual(StringUtils.pad('XXX', 10, 'abc'), 'cabcabcXXX');
    
    // Test ok values - Padding right
    assert.strictEqual(StringUtils.pad('', 1, '0', 'RIGHT'), '0');
    assert.strictEqual(StringUtils.pad('', 2, '0', 'RIGHT'), '00');
    assert.strictEqual(StringUtils.pad('', 4, '0', 'RIGHT'), '0000');
    assert.strictEqual(StringUtils.pad('hello', 4, '0', 'RIGHT'), 'hello');
    assert.strictEqual(StringUtils.pad('hello', 5, '0', 'RIGHT'), 'hello');
    assert.strictEqual(StringUtils.pad('hello', 6, '0', 'RIGHT'), 'hello0');
    assert.strictEqual(StringUtils.pad('hello', 8, '0', 'RIGHT'), 'hello000');
    assert.strictEqual(StringUtils.pad('hello', 10, '0', 'RIGHT'), 'hello00000');
    assert.strictEqual(StringUtils.pad('hello', 10, 'X', 'RIGHT'), 'helloXXXXX');
    assert.strictEqual(StringUtils.pad('hello', 12, 'ab', 'RIGHT'), 'helloabababa');
    assert.strictEqual(StringUtils.pad('hello', 15, 'ab', 'RIGHT'), 'helloababababab');
    assert.strictEqual(StringUtils.pad('XXX', 10, 'abc', 'RIGHT'), 'XXXabcabca');
    
    // Test wrong values
    // Not necessary

    // Test exceptions
    assert.throws(function() { StringUtils.pad([1, 2, 3, 4], 0); }, /string and padString must be strings/);
    assert.throws(function() { StringUtils.pad(new Error(), 0); }, /string and padString must be strings/);
    assert.throws(function() { StringUtils.pad(12345, 0); }, /string and padString must be strings/);
    assert.throws(function() { StringUtils.pad('1213', 10, 12356); }, /string and padString must be strings/);
});


/**
 * countStringOccurences
 */
QUnit.test("countStringOccurences", function(assert) {

    assert.strictEqual(StringUtils.countStringOccurences('       ', ' '), 7);
    assert.strictEqual(StringUtils.countStringOccurences('hello', 'o'), 1);
    assert.strictEqual(StringUtils.countStringOccurences('hello baby', 'b'), 2);
    assert.strictEqual(StringUtils.countStringOccurences('hello baby', 'B'), 0);
    assert.strictEqual(StringUtils.countStringOccurences("tRy\nto\r\n\t\ngo\r\nUP", 'o'), 2);
    assert.strictEqual(StringUtils.countStringOccurences("     \n      \r\n", 'a'), 0);
    assert.strictEqual(StringUtils.countStringOccurences(" AEÉÜ    \n   1   \r\nÍË", 'É'), 1);
    assert.strictEqual(StringUtils.countStringOccurences("heLLo Baby\nhellÓ àgaiN and go\n\n\r\nUp!", 'a'), 3);
    assert.strictEqual(StringUtils.countStringOccurences("helló bàbÝ\n   whats Up Todäy? are you feeling better? GOOD!", 'T'), 1);

    // Test exceptions
    assert.throws(function() { StringUtils.countStringOccurences(null, null); }, /value is not a string/);
    assert.throws(function() { StringUtils.countStringOccurences('', ''); }, /cannot count empty string occurences/);
    assert.throws(function() { StringUtils.countStringOccurences('  ', ''); }, /cannot count empty string occurences/);
});


/**
 * testCountByCase
 */
QUnit.test("testCountByCase", function(assert) {

    // Test empty values
    assert.throws(function(){ StringUtils.countByCase(null); }, /value is not a string/);
    assert.throws(function(){ StringUtils.countByCase(null, StringUtils.FORMAT_ALL_LOWER_CASE); }, /value is not a string/);

    assert.strictEqual(0, StringUtils.countByCase(''));
    assert.strictEqual(0, StringUtils.countByCase('  '));
    assert.strictEqual(0, StringUtils.countByCase('       '));
    assert.strictEqual(0, StringUtils.countByCase("     \n      \r\n"));

    assert.strictEqual(0, StringUtils.countByCase('', StringUtils.FORMAT_ALL_LOWER_CASE));
    assert.strictEqual(0, StringUtils.countByCase('  ', StringUtils.FORMAT_ALL_LOWER_CASE));
    assert.strictEqual(0, StringUtils.countByCase('       ', StringUtils.FORMAT_ALL_LOWER_CASE));
    assert.strictEqual(0, StringUtils.countByCase("     \n      \r\n", StringUtils.FORMAT_ALL_LOWER_CASE));

    // Test ok values
    assert.strictEqual(0, StringUtils.countByCase('hello'));
    assert.strictEqual(0, StringUtils.countByCase('hello baby'));
    assert.strictEqual(0, StringUtils.countByCase('123123123123123'));
    assert.strictEqual(3, StringUtils.countByCase("tRy\nto\r\n\t\ngo\r\nUP"));
    assert.strictEqual(6, StringUtils.countByCase(" AEÉÜ    \n   1   \r\nÍË"));
    assert.strictEqual(6, StringUtils.countByCase("heLLo Baby\nhellÓ agaiN and go\n\n\r\nUp!"));
    assert.strictEqual(6, StringUtils.countByCase("helló bàbÝ\n   whats Up todäy? are you feeling better? GOOD!"));
    assert.strictEqual(0, StringUtils.countByCase("形声字 / 形聲字"));
    assert.strictEqual(1, StringUtils.countByCase("形声字A形聲字"));
    assert.strictEqual(2, StringUtils.countByCase("A形声字A形聲字a"));
    assert.strictEqual(6, StringUtils.countByCase("AaÁáBéEéÉË"));
    assert.strictEqual(1, StringUtils.countByCase("1234A567"));
    assert.strictEqual(2, StringUtils.countByCase('1声A字43B45-_*[]', StringUtils.FORMAT_ALL_UPPER_CASE));

    assert.strictEqual(5, StringUtils.countByCase('hello', StringUtils.FORMAT_ALL_LOWER_CASE));
    assert.strictEqual(9, StringUtils.countByCase('hello baby', StringUtils.FORMAT_ALL_LOWER_CASE));
    assert.strictEqual(6, StringUtils.countByCase("tRy\nto\r\n\t\ngo\r\nUP", StringUtils.FORMAT_ALL_LOWER_CASE));
    assert.strictEqual(0, StringUtils.countByCase(" AEÉÜ    \n   1   \r\nÍË", StringUtils.FORMAT_ALL_LOWER_CASE));
    assert.strictEqual(20, StringUtils.countByCase("heLLo Baby\nhellÓ agaiN and go\n\n\r\nUp!", StringUtils.FORMAT_ALL_LOWER_CASE));
    assert.strictEqual(38, StringUtils.countByCase("helló bàbÝ\n   whats Up todäy? are you feeling better? GOOD!", StringUtils.FORMAT_ALL_LOWER_CASE));
    assert.strictEqual(0, StringUtils.countByCase("形声字 / 形聲字", StringUtils.FORMAT_ALL_LOWER_CASE));
    assert.strictEqual(0, StringUtils.countByCase("形声字A形聲字", StringUtils.FORMAT_ALL_LOWER_CASE));
    assert.strictEqual(1, StringUtils.countByCase("A形声字A形聲字a", StringUtils.FORMAT_ALL_LOWER_CASE));
    assert.strictEqual(5, StringUtils.countByCase("AaÁáBéEéÉë", StringUtils.FORMAT_ALL_LOWER_CASE));
    assert.strictEqual(0, StringUtils.countByCase('1声A字43B45-_*[]', StringUtils.FORMAT_ALL_LOWER_CASE));
    assert.strictEqual(1, StringUtils.countByCase('1声A字43B45a-_*[]', StringUtils.FORMAT_ALL_LOWER_CASE));

    // Test wrong values
    assert.throws(function(){ StringUtils.countByCase(123123); }, /value is not a string/);

    // Test exceptions
    assert.throws(function(){ StringUtils.countByCase("hello", 'invalid format'); }, /invalid case value/);
});


/**
 * countWords
 */
QUnit.test("countWords", function(assert) {

    assert.ok(StringUtils.countWords(null) == 0);
    assert.ok(StringUtils.countWords('') == 0);
    assert.ok(StringUtils.countWords('  ') == 0);
    assert.ok(StringUtils.countWords('       ') == 0);
    assert.ok(StringUtils.countWords('hello') == 1);
    assert.ok(StringUtils.countWords('hello baby') == 2);
    assert.ok(StringUtils.countWords('Üèó ï á étwer') == 4);
    assert.ok(StringUtils.countWords("try\nto\r\n\t\ngo\r\nup") == 4);
    assert.ok(StringUtils.countWords("     \n      \r\n") == 0);
    assert.ok(StringUtils.countWords("     \n   1   \r\n") == 1);
    assert.ok(StringUtils.countWords("hello baby\nhello again and go\n\n\r\nup!") == 7);
    assert.ok(StringUtils.countWords("hello baby\n   whats up today? are you feeling better? GOOD!") == 10);
});


/**
 * testCountPathElements
 */
QUnit.test("testCountPathElements", function(assert) {

    // Test empty values
    assert.throws(function() {
        StringUtils.countPathElements(null);
    }, /path must be a string/);
    
    assert.strictEqual(StringUtils.countPathElements(''), 0);
    assert.strictEqual(StringUtils.countPathElements('       '), 1);
    
    assert.throws(function() {
        StringUtils.countPathElements([]);
    }, /path must be a string/);

    // Test ok values
    assert.strictEqual(StringUtils.countPathElements('/'), 0);
    assert.strictEqual(StringUtils.countPathElements('///////'), 0);
    assert.strictEqual(StringUtils.countPathElements('\\'), 0);
    assert.strictEqual(StringUtils.countPathElements('c:/'), 1);
    assert.strictEqual(StringUtils.countPathElements('c:\\'), 1);
    assert.strictEqual(StringUtils.countPathElements('folder'), 1);
    assert.strictEqual(StringUtils.countPathElements('//folder'), 1);
    assert.strictEqual(StringUtils.countPathElements('C:\\Program Files\\CCleaner\\CCleaner64.exe'), 4);
    assert.strictEqual(StringUtils.countPathElements('\\Files/CCleaner/CCleaner64.exe'), 3);
    assert.strictEqual(StringUtils.countPathElements('//folder/folder2/folder3/file.txt'), 4);
    assert.strictEqual(StringUtils.countPathElements('CCleaner64.exe'), 1);
    assert.strictEqual(StringUtils.countPathElements('\\\\\\CCleaner64.exe'), 1);
    assert.strictEqual(StringUtils.countPathElements('\\some long path containing lots of spaces\\///CCleaner64.exe'), 2);
    assert.strictEqual(StringUtils.countPathElements("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe"), 2);
    assert.strictEqual(StringUtils.countPathElements("folder1\\\\folder2//folder3///\\\\folder4"), 4);
    assert.strictEqual(StringUtils.countPathElements('//folder/folder2/folder3/'), 3);
    assert.strictEqual(StringUtils.countPathElements('https://www.google.es'), 2);
    assert.strictEqual(StringUtils.countPathElements('https://www.google.es//////'), 2);
    assert.strictEqual(StringUtils.countPathElements('https://www.youtube.com/watch?v=bvOGIDiLzMk'), 3);
    assert.strictEqual(StringUtils.countPathElements('https://www.google.es/search?q=zero+latency'), 3);

    // Test wrong values
    // Not necessary

    // Test exceptions
    assert.throws(function() {
        StringUtils.countPathElements(['//folder/folder2/folder3/file.txt']);
    }, /path must be a string/);

    assert.throws(function() {
        StringUtils.countPathElements(125);
    }, /path must be a string/);

    assert.throws(function() {
        StringUtils.countPathElements({});
    }, /path must be a string/);
});


/**
 * limitLen
 */
QUnit.test("limitLen", function(assert) {

    assert.ok(StringUtils.limitLen(null, 10) === '');
    assert.ok(StringUtils.limitLen('', 10) === '');
    assert.ok(StringUtils.limitLen('hello dear how are you', 1) === ' ');
    assert.ok(StringUtils.limitLen('hello dear how are you', 2) === ' .');
    assert.ok(StringUtils.limitLen('hello dear how are you', 3) === ' ..');
    assert.ok(StringUtils.limitLen('hello dear how are you', 4) === ' ...');
    assert.ok(StringUtils.limitLen('hello dear how are you', 5) === 'h ...');
    assert.ok(StringUtils.limitLen('hello dear how are you', 18) === 'hello dear how ...');
    assert.ok(StringUtils.limitLen('hello dear how are you', 19) === 'hello dear how  ...');
    assert.ok(StringUtils.limitLen('hello dear how are you', 20) === 'hello dear how a ...');
    assert.ok(StringUtils.limitLen('hello dear how are you', 21) === 'hello dear how ar ...');
    assert.ok(StringUtils.limitLen('hello dear how are you', 22) === 'hello dear how are you');
    assert.ok(StringUtils.limitLen('hello dear how are you', 50) === 'hello dear how are you');

    // Test invalid values give exception
    assert.throws(function() {

        StringUtils.limitLen('', 0);
    });

    assert.throws(function() {

        StringUtils.limitLen('hello', [1, 2]);
    });

    assert.throws(function() {

        StringUtils.limitLen('hello', null);
    });
});


/**
 * getDomainFromUrl
 */
QUnit.test("getDomainFromUrl", function(assert) {

    assert.ok(StringUtils.getDomainFromUrl(null) === '');
    assert.ok(StringUtils.getDomainFromUrl(undefined) === '');
    assert.ok(StringUtils.getDomainFromUrl('') === '');
    assert.ok(StringUtils.getDomainFromUrl("aa....aa..") === '');
    assert.ok(StringUtils.getDomainFromUrl('google.com') === '');
    assert.ok(StringUtils.getDomainFromUrl('http://google.com') === 'google.com');
    assert.ok(StringUtils.getDomainFromUrl('http://www.google.com') === 'google.com');
    assert.ok(StringUtils.getDomainFromUrl('https://www.youtube.com/watch?v=Zs3im94FSpU') === 'youtube.com');
    assert.ok(StringUtils.getDomainFromUrl('https://developer.chrome.com/extensions/notifications#method-clear') === 'chrome.com');
    assert.ok(StringUtils.getDomainFromUrl('http://www.abc.es/internacional/abci-represion-mediatica-venezuela-periodistas-detenidos-y-expulsados-menos-24-horas-201609011727_noticia.html') === 'abc.es');
    assert.ok(StringUtils.getDomainFromUrl('file:///C:/Users/Jaume/Desktop/Extension%200.2.9/PopUp-Enable.html') === '');
    assert.ok(StringUtils.getDomainFromUrl(' asdfa sfdaewr 345 drtwertwert5324') === '');
    assert.ok(StringUtils.getDomainFromUrl("\n\t\n2i34.,.324 .,h we. h \n\n") === '');
    assert.ok(StringUtils.getDomainFromUrl('ftp://ftp.funet.fi/pub/standards/RFC/rfc959.txt') === 'funet.fi');
    assert.ok(StringUtils.getDomainFromUrl('ftp://jess12:bosox67@ftp.xyz.com') === 'xyz.com');
    assert.ok(StringUtils.getDomainFromUrl('ftp://jess12@xyz.com:bosox67@ftp.xyz.com') === 'xyz.com');
});


/**
 * getHostNameFromUrl
 */
QUnit.test("getHostNameFromUrl", function(assert) {

    assert.ok(StringUtils.getHostNameFromUrl(null) === '');
    assert.ok(StringUtils.getHostNameFromUrl(undefined) === '');
    assert.ok(StringUtils.getHostNameFromUrl('') === '');
    assert.ok(StringUtils.getHostNameFromUrl("aa....aa..") === '');
    assert.ok(StringUtils.getHostNameFromUrl('google.com') === '');
    assert.ok(StringUtils.getHostNameFromUrl('http://x.ye') === 'x.ye');
    assert.ok(StringUtils.getHostNameFromUrl('http://google.com') === 'google.com');
    assert.ok(StringUtils.getHostNameFromUrl('http://www.google.com') === 'www.google.com');
    assert.ok(StringUtils.getHostNameFromUrl('|%$)"·/%') === '');
    assert.ok(StringUtils.getHostNameFromUrl('http://|%$)"·/%') === '');
    assert.ok(StringUtils.getHostNameFromUrl('https://www.youtube.com/watch?v=Zs3im94FSpU') === 'www.youtube.com');
    assert.ok(StringUtils.getHostNameFromUrl('https://developer.chrome.com/extensions/notifications#method-clear') === 'developer.chrome.com');
    assert.ok(StringUtils.getHostNameFromUrl('http://www.abc.es/internacional/abci-represion-mediatica-venezuela-periodistas-detenidos-y-expulsados-menos-24-horas-201609011727_noticia.html') === 'www.abc.es');
    assert.ok(StringUtils.getHostNameFromUrl('file:///C:/Users/Jaume/Desktop/Extension%200.2.9/PopUp-Enable.html') === '');
    assert.ok(StringUtils.getHostNameFromUrl(' asdfa sfdaewr 345 drtwertwert5324') === '');
    assert.ok(StringUtils.getHostNameFromUrl("\n\t\n2i34.,.324 .,h we. h \n\n") === '');
    assert.ok(StringUtils.getHostNameFromUrl('ftp://ftp.funet.fi/pub/standards/RFC/rfc959.txt') === 'ftp.funet.fi');
    assert.ok(StringUtils.getHostNameFromUrl('ftp://jess12:bosox67@ftp.xyz.com') === 'ftp.xyz.com');
    assert.ok(StringUtils.getHostNameFromUrl('ftp://jess12@xyz.com:bosox67@ftp.xyz.com') === 'ftp.xyz.com');
});


/**
 * getLines
 */
QUnit.test("getLines", function(assert) {

    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines(null), []));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines(''), []));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines('          '), []));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines('single line'), ['single line']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\nline2\nline3"), ['line1', 'line2', 'line3']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\rline2\rline3"), ['line1', 'line2', 'line3']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\r\nline2\r\nline3"), ['line1', 'line2', 'line3']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\n        \nline2"), ['line1', 'line2']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\n\n\n\t\r       \nline2"), ['line1', 'line2']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\r\n   \r\nline2"), ['line1', 'line2']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\n 1  \nline2"), ['line1', ' 1  ', 'line2']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\r\n 1  \n\r\r\nline2"), ['line1', ' 1  ', 'line2']));

    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines('          ', []), ['          ']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\r   \rline2", []), ['line1', '   ', 'line2']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\n   \nline2", []), ['line1', '   ', 'line2']));
    assert.ok(ArrayUtils.isEqualTo(StringUtils.getLines("line1\r\n   \r\nline2", []), ['line1', '   ', 'line2']));
    
    assert.ok(ArrayUtils.isEqualTo(
        StringUtils.getLines("# com\n# com2\n\n\n! com\nweb = google\nlan = En\n# com4\n# com5\nmessage = Welcome", [/\s+/g, / *#.*| *!.*/g]),
        ['web = google', 'lan = En', 'message = Welcome'])
    );    
});


/**
 * getKeyWords
 */
QUnit.todo("getKeyWords", function(assert) {

    // TODO: copy tests from PHP
});


/**
 * getPath
 */
QUnit.test("getPath", function(assert) {
    
    // Test empty values
    assert.strictEqual(StringUtils.getPath(null), '');
    assert.strictEqual(StringUtils.getPath(''), '');
    assert.strictEqual(StringUtils.getPath('       '), '');
    assert.strictEqual(StringUtils.getPath([]), '');

    // Test ok values
    
    // With 0 elements removed
    assert.strictEqual(StringUtils.getPath('/', 0), '/');
    assert.strictEqual(StringUtils.getPath('///////', 0), '/');
    assert.strictEqual(StringUtils.getPath('\\', 0), '/');
    assert.strictEqual(StringUtils.getPath('c:/', 0), 'c:');
    assert.strictEqual(StringUtils.getPath('c:/', 0), 'c:');
    assert.strictEqual(StringUtils.getPath('c:\\', 0), 'c:');
    assert.strictEqual(StringUtils.getPath('folder', 0), 'folder');
    assert.strictEqual(StringUtils.getPath('//folder', 0), '/folder');
    assert.strictEqual(StringUtils.getPath('CCleaner64.exe', 0), 'CCleaner64.exe');
    assert.strictEqual(StringUtils.getPath('\\Files/CCleaner/CCleaner64.exe', 0), '/Files/CCleaner/CCleaner64.exe');
    assert.strictEqual(StringUtils.getPath("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe", 0), "MultiLine\n\n\r\n   and strange &%·Characters/CCleaner64.exe");
    assert.strictEqual(StringUtils.getPath('C:\\Program Files\\CCleaner\\CCleaner64.exe', 0), 'C:/Program Files/CCleaner/CCleaner64.exe');
    assert.strictEqual(StringUtils.getPath('https://www.google.es//////', 0), 'https:/www.google.es');
    assert.strictEqual(StringUtils.getPath('https://www.google.es/search?q=zero+latency', 0), 'https:/www.google.es/search?q=zero+latency');
    
    // With 1 element removed
    assert.strictEqual(StringUtils.getPath('/', 1), '/');
    assert.strictEqual(StringUtils.getPath('///////', 1), '/');
    assert.strictEqual(StringUtils.getPath('\\', 1), '/');
    assert.strictEqual(StringUtils.getPath('c:/', 1), '');
    assert.strictEqual(StringUtils.getPath('c:/', 1), '');
    assert.strictEqual(StringUtils.getPath('c:\\', 1), '');
    assert.strictEqual(StringUtils.getPath('folder', 1), '');
    assert.strictEqual(StringUtils.getPath('//folder', 1), '');
    assert.strictEqual(StringUtils.getPath('CCleaner64.exe', 1), '');
    assert.strictEqual(StringUtils.getPath('\\\\\\CCleaner64.exe', 1), '');
    assert.strictEqual(StringUtils.getPath('//folder/folder2/folder3/file.txt', 1), '/folder/folder2/folder3');
    assert.strictEqual(StringUtils.getPath("folder1\\\\folder2//folder3///\\\\folder4", 1), 'folder1/folder2/folder3');
    assert.strictEqual(StringUtils.getPath('\\Files/CCleaner/CCleaner64.exe', 1), '/Files/CCleaner');
    assert.strictEqual(StringUtils.getPath("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe", 1), "MultiLine\n\n\r\n   and strange &%·Characters");
    assert.strictEqual(StringUtils.getPath('\\some long path containing lots of spaces\\///CCleaner64.exe', 1), '/some long path containing lots of spaces');
    assert.strictEqual(StringUtils.getPath('C:\\Program Files\\CCleaner\\CCleaner64.exe', 1), 'C:/Program Files/CCleaner');
    assert.strictEqual(StringUtils.getPath('https://www.google.es', 1), 'https:');
    assert.strictEqual(StringUtils.getPath('https://www.google.es//////', 1), 'https:');
    assert.strictEqual(StringUtils.getPath('https://www.google.es/search?q=zero+latency', 1), 'https:/www.google.es');
    
    // With 2 element removed
    assert.strictEqual(StringUtils.getPath('/', 2), '/');
    assert.strictEqual(StringUtils.getPath('///////', 2), '/');
    assert.strictEqual(StringUtils.getPath('\\', 2), '/');
    assert.strictEqual(StringUtils.getPath('c:/', 2), '');
    assert.strictEqual(StringUtils.getPath('c:/', 2), '');
    assert.strictEqual(StringUtils.getPath('c:\\', 2), '');
    assert.strictEqual(StringUtils.getPath('folder', 2), '');
    assert.strictEqual(StringUtils.getPath('//folder', 2), '');
    assert.strictEqual(StringUtils.getPath('CCleaner64.exe', 2), '');
    assert.strictEqual(StringUtils.getPath('\\\\\\CCleaner64.exe', 2), '');
    assert.strictEqual(StringUtils.getPath('//folder/folder2/folder3/file.txt', 2), '/folder/folder2');
    assert.strictEqual(StringUtils.getPath("folder1\\\\folder2//folder3///\\\\folder4", 2), 'folder1/folder2');
    assert.strictEqual(StringUtils.getPath('\\Files/CCleaner/CCleaner64.exe', 2), '/Files');
    assert.strictEqual(StringUtils.getPath("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe", 2), "");
    assert.strictEqual(StringUtils.getPath('\\some long path containing lots of spaces\\///CCleaner64.exe', 2), '');
    assert.strictEqual(StringUtils.getPath('C:\\Program Files\\CCleaner\\CCleaner64.exe', 2), 'C:/Program Files');
    assert.strictEqual(StringUtils.getPath('https://www.google.es', 2), '');
    assert.strictEqual(StringUtils.getPath('https://www.google.es//////', 2), '');
    assert.strictEqual(StringUtils.getPath('https://www.google.es/search?q=zero+latency', 2), 'https:');

    // With many element removed
    assert.strictEqual(StringUtils.getPath('/', 3), '/');
    assert.strictEqual(StringUtils.getPath('///////', 4), '/');
    assert.strictEqual(StringUtils.getPath('\\', 5), '/');
    assert.strictEqual(StringUtils.getPath('c:/', 10), '');
    assert.strictEqual(StringUtils.getPath('c:/', 20), '');
    assert.strictEqual(StringUtils.getPath('c:\\', 40), '');
    assert.strictEqual(StringUtils.getPath('folder', 60), '');
    assert.strictEqual(StringUtils.getPath('//folder', 200), '');
    assert.strictEqual(StringUtils.getPath('CCleaner64.exe', 2000), '');
    assert.strictEqual(StringUtils.getPath('\\\\\\CCleaner64.exe', 9), '');
    assert.strictEqual(StringUtils.getPath('//folder/folder2/folder3/file.txt', 3), '/folder');
    assert.strictEqual(StringUtils.getPath("folder1\\\\folder2//folder3///\\\\folder4", 4), '');
    assert.strictEqual(StringUtils.getPath('\\Files/CCleaner/CCleaner64.exe', 300), '');
    assert.strictEqual(StringUtils.getPath("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe", 15), "");
    assert.strictEqual(StringUtils.getPath('\\some long path containing lots of spaces\\///CCleaner64.exe', 19), '');
    assert.strictEqual(StringUtils.getPath('C:\\Program Files\\CCleaner\\CCleaner64.exe', 3), 'C:');
    assert.strictEqual(StringUtils.getPath('https://www.google.es', 29), '');
    assert.strictEqual(StringUtils.getPath('https://www.google.es//////', 28), '');
    assert.strictEqual(StringUtils.getPath('https://www.google.es/search?q=zero+latency', 78), '');
    assert.strictEqual(StringUtils.getPath('1/2/3/4/5/6/7/8/9/10/11/12/13', 3), '1/2/3/4/5/6/7/8/9/10');
    assert.strictEqual(StringUtils.getPath('1/2/3/4/5/6/7/8/9/10/11/12/13', 5), '1/2/3/4/5/6/7/8');
    assert.strictEqual(StringUtils.getPath('1/2/3/4/5/6/7/8/9/10/11/12/13', 7), '1/2/3/4/5/6');
    assert.strictEqual(StringUtils.getPath('1/2/3/4/5/6/7/8/9/10/11/12/13', 10), '1/2/3');
    assert.strictEqual(StringUtils.getPath('1/2/3/4/5/6/7/8/9/10/11/12/13', 12), '1');
    assert.strictEqual(StringUtils.getPath('1/2/3/4/5/6/7/8/9/10/11/12/13', 25), '');

    // Test wrong values
    // Test exceptions
    assert.throws(function() {
        StringUtils.getPath(['//folder/folder2/folder3/file.txt']);
    }, /value is not a string/);

    assert.throws(function() {
        StringUtils.getPath(125);
    }, /value is not a string/);

    assert.throws(function() {
        StringUtils.getPath({});
    }, /value is not a string/);
});


/**
 * getPathElement
 */
QUnit.test("getPathElement", function(assert) {

    // Test empty values
    assert.strictEqual(StringUtils.getPathElement(null), '');
    assert.strictEqual(StringUtils.getPathElement(''), '');
    assert.strictEqual(StringUtils.getPathElement('       '), '');
    assert.strictEqual(StringUtils.getPathElement([]), '');

    // Test ok values
    assert.strictEqual(StringUtils.getPathElement('/'), '');
    assert.strictEqual(StringUtils.getPathElement('///////'), '');
    assert.strictEqual(StringUtils.getPathElement('\\'), '');
    assert.strictEqual(StringUtils.getPathElement('c:/'), 'c:');
    assert.strictEqual(StringUtils.getPathElement('c:\\'), 'c:');
    assert.strictEqual(StringUtils.getPathElement('folder'), 'folder');
    assert.strictEqual(StringUtils.getPathElement('//folder'), 'folder');
    assert.strictEqual(StringUtils.getPathElement('C:\\Program Files\\CCleaner\\CCleaner64.exe'), 'CCleaner64.exe');
    assert.strictEqual(StringUtils.getPathElement('\\Files/CCleaner/CCleaner64.exe'), 'CCleaner64.exe');
    assert.strictEqual(StringUtils.getPathElement('//folder/folder2/folder3/file.txt'), 'file.txt');
    assert.strictEqual(StringUtils.getPathElement('CCleaner64.exe'), 'CCleaner64.exe');
    assert.strictEqual(StringUtils.getPathElement('\\\\\\CCleaner64.exe'), 'CCleaner64.exe');
    assert.strictEqual(StringUtils.getPathElement('\\some long path containing lots of spaces\\///CCleaner64.exe'), 'CCleaner64.exe');
    assert.strictEqual(StringUtils.getPathElement("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe"), 'CCleaner64.exe');
    assert.strictEqual(StringUtils.getPathElement("folder1\\\\folder2//folder3///\\\\folder4"), 'folder4');
    assert.strictEqual(StringUtils.getPathElement('//folder/folder2/folder3/'), 'folder3');
    assert.strictEqual(StringUtils.getPathElement('https://www.google.es'), 'www.google.es');
    assert.strictEqual(StringUtils.getPathElement('https://www.google.es//////'), 'www.google.es');
    assert.strictEqual(StringUtils.getPathElement('https://www.youtube.com/watch?v=bvOGIDiLzMk'), 'watch?v=bvOGIDiLzMk');
    assert.strictEqual(StringUtils.getPathElement('https://www.google.es/search?q=zero+latency'), 'search?q=zero+latency');

    assert.strictEqual(StringUtils.getPathElement("folder1\\\\folder2//folder3///\\\\folder4", 0), 'folder1');
    assert.strictEqual(StringUtils.getPathElement("folder1\\\\folder2//folder3///\\\\folder4", 1), 'folder2');
    assert.strictEqual(StringUtils.getPathElement("folder1\\\\folder2//folder3///\\\\folder4", 2), 'folder3');
    assert.strictEqual(StringUtils.getPathElement("folder1\\\\folder2//folder3///\\\\folder4", 3), 'folder4');
    assert.strictEqual(StringUtils.getPathElement('//folder/folder2/folder3/file.txt', 0), 'folder');
    assert.strictEqual(StringUtils.getPathElement('//folder/folder2/folder3/file.txt', 1), 'folder2');
    assert.strictEqual(StringUtils.getPathElement('//folder/folder2/folder3/file.txt', 2), 'folder3');
    assert.strictEqual(StringUtils.getPathElement('//folder/folder2/folder3/file.txt', 3), 'file.txt');
    assert.strictEqual(StringUtils.getPathElement('https://www.google.es/search?q=zero+latency', 0), 'https:');
    assert.strictEqual(StringUtils.getPathElement('https://www.google.es/search?q=zero+latency', 1), 'www.google.es');
    assert.strictEqual(StringUtils.getPathElement('https://www.google.es/search?q=zero+latency', 2), 'search?q=zero+latency');
    assert.strictEqual(StringUtils.getPathElement('https:\\www.google.es/search?q=zero\\+latency', 0), 'https:');
    assert.strictEqual(StringUtils.getPathElement('https:\\www.google.es/search?q=zero\\+latency', 1), 'www.google.es');
    assert.strictEqual(StringUtils.getPathElement('https:\\www.google.es/search?q=zero\\+latency', 2), 'search?q=zero');
    assert.strictEqual(StringUtils.getPathElement('https:\\www.google.es/search?q=zero\\+latency', 3), '+latency');

    assert.strictEqual(StringUtils.getPathElement("folder1\\\\folder2//folder3///\\\\folder4", -1), 'folder4');
    assert.strictEqual(StringUtils.getPathElement("folder1\\\\folder2//folder3///\\\\folder4", -2), 'folder3');
    assert.strictEqual(StringUtils.getPathElement("folder1\\\\folder2//folder3///\\\\folder4", -3), 'folder2');
    assert.strictEqual(StringUtils.getPathElement("folder1\\\\folder2//folder3///\\\\folder4", -4), 'folder1');
    assert.strictEqual(StringUtils.getPathElement('//folder/folder2/folder3/file.txt', -1), 'file.txt');
    assert.strictEqual(StringUtils.getPathElement('//folder/folder2/folder3/file.txt', -2), 'folder3');
    assert.strictEqual(StringUtils.getPathElement('//folder/folder2/folder3/file.txt', -3), 'folder2');
    assert.strictEqual(StringUtils.getPathElement('//folder/folder2/folder3/file.txt', -4), 'folder');
    assert.strictEqual(StringUtils.getPathElement('https://www.google.es/search?q=zero+latency', -1), 'search?q=zero+latency');
    assert.strictEqual(StringUtils.getPathElement('https://www.google.es/search?q=zero+latency', -2), 'www.google.es');
    assert.strictEqual(StringUtils.getPathElement('https://www.google.es/search?q=zero+latency', -3), 'https:');
    assert.strictEqual(StringUtils.getPathElement('https:\\www.google.es/search?q=zero\\+latency', -1), '+latency');
    assert.strictEqual(StringUtils.getPathElement('https:\\www.google.es/search?q=zero\\+latency', -2), 'search?q=zero');
    assert.strictEqual(StringUtils.getPathElement('https:\\www.google.es/search?q=zero\\+latency', -3), 'www.google.es');
    assert.strictEqual(StringUtils.getPathElement('https:\\www.google.es/search?q=zero\\+latency', -4), 'https:');
        
    // Test wrong values
    assert.throws(function() {
        StringUtils.getPathElement('//folder/folder2/folder3/file.txt', 4);
    }, /Invalid position specified/);

    assert.throws(function() {
        StringUtils.getPathElement('//folder/folder2/folder3/file.txt', 100);
    }, /Invalid position specified/);
    
    assert.throws(function() {
        StringUtils.getPathElement('//folder/folder2/folder3/file.txt', -5);
    }, /Invalid position specified/);

    assert.throws(function() {
        StringUtils.getPathElement('//folder/folder2/folder3/file.txt', -10);
    }, /Invalid position specified/);
    
    assert.throws(function() {
        StringUtils.getPathElement('//folder/folder2/folder3/file.txt', -100);
    }, /Invalid position specified/);

    // Test exceptions
    assert.throws(function() {
        StringUtils.getPathElement(['//folder/folder2/folder3/file.txt']);
    }, /value is not a string/);

    assert.throws(function() {
        StringUtils.getPathElement(125);
    }, /value is not a string/);

    assert.throws(function() {
        StringUtils.getPathElement({});
    }, /value is not a string/);
});

    
/**
 * getPathElementWithoutExt
 */
QUnit.test("getPathElementWithoutExt", function(assert) {

    // Test empty values
    assert.ok(StringUtils.getPathElementWithoutExt(null) === '');
    assert.ok(StringUtils.getPathElementWithoutExt('') === '');
    assert.ok(StringUtils.getPathElementWithoutExt('       ') === '');
    assert.ok(StringUtils.getPathElementWithoutExt([]) === '');

    // Test ok values
    assert.ok(StringUtils.getPathElementWithoutExt('C:\\Program Files\\CCleaner\\CCleaner64.exe') == 'CCleaner64');
    assert.ok(StringUtils.getPathElementWithoutExt('\\Files/CCleaner/CCleaner64.exe') == 'CCleaner64');
    assert.ok(StringUtils.getPathElementWithoutExt('//folder/folder2/folder3/file.txt') == 'file');
    assert.ok(StringUtils.getPathElementWithoutExt('CCleaner64.exe') == 'CCleaner64');
    assert.ok(StringUtils.getPathElementWithoutExt('\\\\\\CCleaner64.exe') == 'CCleaner64');
    assert.ok(StringUtils.getPathElementWithoutExt('\\some long path containing lots of spaces\\///CCleaner64.exe') == 'CCleaner64');
    assert.ok(StringUtils.getPathElementWithoutExt("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe") == 'CCleaner64');
    assert.ok(StringUtils.getPathElementWithoutExt('//folder/folder2/folder3/file.extension.txt') == 'file.extension');
    assert.ok(StringUtils.getPathElementWithoutExt('//folder/folder2.txt/folder3/file.extension.txt') == 'file.extension');

    assert.strictEqual(StringUtils.getPathElementWithoutExt("folder1.a.b.txt\\\\folder2//folder3///\\\\folder4", 0), 'folder1.a.b');
    assert.strictEqual(StringUtils.getPathElementWithoutExt("folder1\\\\folder2.jpg//folder3///\\\\folder4", 1), 'folder2');
    assert.strictEqual(StringUtils.getPathElementWithoutExt("folder1\\\\folder2//folder3///\\\\folder4", 2), 'folder3');
    assert.strictEqual(StringUtils.getPathElementWithoutExt("folder1\\\\folder2//folder3///\\\\folder4.txt", 3), 'folder4');
    assert.strictEqual(StringUtils.getPathElementWithoutExt("folder1\\\\folder2//folder3///\\\\folder4", 3), 'folder4');
    assert.strictEqual(StringUtils.getPathElementWithoutExt('//folder/folder2/folder3/file.txt', 0), 'folder');
    assert.strictEqual(StringUtils.getPathElementWithoutExt('//folder/folder2/folder3/file.txt', 3), 'file');
    assert.strictEqual(StringUtils.getPathElementWithoutExt('https://www.google.es/search?q=zero+latency', 0), 'https:');
    assert.strictEqual(StringUtils.getPathElementWithoutExt('https://www.google.es/search?q=zero+latency', 2), 'search?q=zero+latency');
    assert.strictEqual(StringUtils.getPathElementWithoutExt('https:\\www.google.es/search?q=zero\\+latency', 0), 'https:');
    assert.strictEqual(StringUtils.getPathElementWithoutExt('https:\\www.google.es/search?q=zero.html', 2), 'search?q=zero');

    assert.ok(StringUtils.getPathElementWithoutExt('//folder/folder2.txt/folder3/file-extension.txt', -1, '-') == 'file');
    assert.ok(StringUtils.getPathElementWithoutExt('//folder/folder2.txt/folder3/file-extension.txt', 1, '-') == 'folder2.txt');
    assert.ok(StringUtils.getPathElementWithoutExt('//folder/folder2.txt/folder3/file-extension.txt', 0, 'd') == 'fol');

    // Test wrong values
    assert.throws(function() {
        StringUtils.getPathElementWithoutExt('//folder/folder2/folder3/file.txt', 4);
    }, /Invalid position specified/);

    assert.throws(function() {
        StringUtils.getPathElementWithoutExt('//folder/folder2/folder3/file.txt', 100);
    }, /Invalid position specified/);

    assert.throws(function() {
        StringUtils.getPathElementWithoutExt('//folder/folder2/folder3/file.txt', -10);
    }, /Invalid position specified/);

    // Test exceptions
    assert.throws(function() {
        StringUtils.getPathElementWithoutExt(['//folder/folder2/folder3/file.txt']);
    }, /value is not a string/);

    assert.throws(function() {
        StringUtils.getPathElementWithoutExt(125);
    }, /value is not a string/);

    assert.throws(function() {
        StringUtils.getPathElementWithoutExt({});
    }, /value is not a string/);
});


/**
 * getPathExtension
 */
QUnit.test("getPathExtension", function(assert) {

    // Test empty values
    assert.ok(StringUtils.getPathExtension(null) === '');
    assert.ok(StringUtils.getPathExtension('') === '');
    assert.ok(StringUtils.getPathExtension('       ') === '');
    assert.ok(StringUtils.getPathExtension([]) === '');

    // Test ok values
    assert.ok(StringUtils.getPathExtension('/') === '');
    assert.ok(StringUtils.getPathExtension('////') === '');
    assert.ok(StringUtils.getPathExtension('/a') === '');
    assert.ok(StringUtils.getPathExtension('C:\Program Files\\CCleaner') == '');
    assert.ok(StringUtils.getPathExtension('C:\Program Files\\CCleaner\\CCleaner64.exe') == 'exe');
    assert.ok(StringUtils.getPathExtension('\\Files/CCleaner/CCleaner64.exe') == 'exe');
    assert.ok(StringUtils.getPathExtension('//folder/folder2/folder3/file.txt') == 'txt');
    assert.ok(StringUtils.getPathExtension('CCleaner64.exe') == 'exe');
    assert.ok(StringUtils.getPathExtension('\\\\\\CCleaner64.exe') == 'exe');
    assert.ok(StringUtils.getPathExtension('\\some long path containing lots of spaces\\///CCleaner64.exe') == 'exe');
    assert.ok(StringUtils.getPathExtension('CCleaner64.EXE') == 'EXE');
    assert.ok(StringUtils.getPathExtension('\\\\\\CCleaner64.eXEfile') == 'eXEfile');
    assert.ok(StringUtils.getPathExtension("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64.exe") == 'exe');
    assert.ok(StringUtils.getPathExtension("MultiLine\n\n\r\n   and strange &%·Characters\\CCleaner64") == '');

    assert.strictEqual(StringUtils.getPathExtension("folder1.a.b.txt\\\\folder2//folder3///\\\\folder4", 0), 'txt');
    assert.strictEqual(StringUtils.getPathExtension("folder1\\\\folder2.jpg//folder3///\\\\folder4", 1), 'jpg');
    assert.strictEqual(StringUtils.getPathExtension("folder1\\\\folder2//folder3///\\\\folder4", 2), '');
    assert.strictEqual(StringUtils.getPathExtension("folder1\\\\folder2//folder3///\\\\folder4.txt", 3), 'txt');
    assert.strictEqual(StringUtils.getPathExtension("folder1\\\\folder2//folder3///\\\\folder4", 3), '');
    assert.strictEqual(StringUtils.getPathExtension('//folder/folder2/folder3/file.txt', 0), '');
    assert.strictEqual(StringUtils.getPathExtension('//folder/folder2/folder3/file.txt', 3), 'txt');
    assert.strictEqual(StringUtils.getPathExtension('https://www.google.es/search?q=zero+latency', 0), '');
    assert.strictEqual(StringUtils.getPathExtension('https://www.google.es/search?q=zero+latency', 2), '');
    assert.strictEqual(StringUtils.getPathExtension('https:\\www.google.es/search?q=zero\\+latency', 0), '');
    assert.strictEqual(StringUtils.getPathExtension('https:\\www.google.es/search?q=zero.html', 2), 'html');

    assert.ok(StringUtils.getPathExtension('//folder/folder2.txt/folder3/file-extension.txt', -1, '-') == 'extension.txt');
    assert.ok(StringUtils.getPathExtension('//folder/folder2.txt/folder3/file-extension.txt', 1, '-') == '');
    assert.ok(StringUtils.getPathExtension('//folder/folder2.txt/folder3/file-extension.txt', 0, 'd') == 'er');

    // Test wrong values
    assert.throws(function() {
        StringUtils.getPathExtension('//folder/folder2/folder3/file.txt', 4);
    }, /Invalid position specified/);

    assert.throws(function() {
        StringUtils.getPathExtension('//folder/folder2/folder3/file.txt', 100);
    }, /Invalid position specified/);

    assert.throws(function() {
        StringUtils.getPathExtension('//folder/folder2/folder3/file.txt', -10);
    }, /Invalid position specified/);

    // Test exceptions
    assert.throws(function() {
        StringUtils.getPathExtension(['//folder/folder2/folder3/file.txt']);
    }, /value is not a string/);

    assert.throws(function() {
        StringUtils.getPathExtension(125);
    }, /value is not a string/);

    assert.throws(function() {
        StringUtils.getPathExtension({});
    }, /value is not a string/);
});


/**
 * getSchemeFromUrl
 */
QUnit.test("getSchemeFromUrl", function(assert) {

    // Invalid urls
    assert.ok(StringUtils.getSchemeFromUrl(null) === '');
    assert.ok(StringUtils.getSchemeFromUrl(undefined) === '');
    assert.ok(StringUtils.getSchemeFromUrl('') === '');
    assert.ok(StringUtils.getSchemeFromUrl('adfadsf') === '');
    assert.ok(StringUtils.getSchemeFromUrl('http://') === '');
    assert.ok(StringUtils.getSchemeFromUrl('||@#~@#~·$%') === '');
    assert.ok(StringUtils.getSchemeFromUrl('http://|@##~€#~€') === '');
    assert.ok(StringUtils.getSchemeFromUrl('http:/youtube.org') === '');
    assert.ok(StringUtils.getSchemeFromUrl('x.ye') === '');
    assert.ok(StringUtils.getSchemeFromUrl('google.com') === '');

    // Valid urls
    assert.ok(StringUtils.getSchemeFromUrl('http://youtube.org') === 'http');
    assert.ok(StringUtils.getSchemeFromUrl('https://yahoo.es') === 'https');
    assert.ok(StringUtils.getSchemeFromUrl('ftp://user:password@host.com:8080/path') === 'ftp');
    assert.ok(StringUtils.getSchemeFromUrl('http://www.example.com/a/b/c/d/e/f/g/h/i.html') === 'http');
    assert.ok(StringUtils.getSchemeFromUrl('http://➡.ws/䨹') === 'http');
    assert.ok(StringUtils.getSchemeFromUrl('http://-.~_!$&\'()*+,;=:%40:80%2f::::::@example.com') === 'http');
    assert.ok(StringUtils.getSchemeFromUrl('https://223.255.255.254') === 'https');

});


/**
 * formatCase
 */
QUnit.test("formatCase", function(assert) {

    /** Defines the list of string case available formats */
    var caseFormats = ['',
            StringUtils.FORMAT_SENTENCE_CASE,
            StringUtils.FORMAT_START_CASE,
            StringUtils.FORMAT_ALL_UPPER_CASE,
            StringUtils.FORMAT_ALL_LOWER_CASE,
            StringUtils.FORMAT_FIRST_UPPER_REST_LOWER,
            StringUtils.FORMAT_CAMEL_CASE,
            StringUtils.FORMAT_UPPER_CAMEL_CASE,
            StringUtils.FORMAT_LOWER_CAMEL_CASE,
            StringUtils.FORMAT_SNAKE_CASE,
            StringUtils.FORMAT_UPPER_SNAKE_CASE,
            StringUtils.FORMAT_LOWER_SNAKE_CASE
    ];
    
    // test empty cases on all possible formats
    for (let caseFormat of caseFormats) {
        
        assert.throws(function() {

            StringUtils.formatCase(null, caseFormat);
        }, /value is not a string/);
        assert.throws(function() {

            StringUtils.formatCase([], caseFormat);
        }, /value is not a string/);
        assert.ok(StringUtils.formatCase('', caseFormat) === '');
        assert.ok(StringUtils.formatCase('       ', caseFormat) === '       ');
        assert.ok(StringUtils.formatCase("\n\n\n", caseFormat) === "\n\n\n");
    }
    
    // Test FORMAT_SENTENCE_CASE values
    // TODO - translate from PHP
    
    // Test FORMAT_START_CASE values
    assert.strictEqual(StringUtils.formatCase('h', StringUtils.FORMAT_START_CASE), 'H');
    assert.strictEqual(StringUtils.formatCase('HI', StringUtils.FORMAT_START_CASE), 'Hi');
    assert.strictEqual(StringUtils.formatCase('hello', StringUtils.FORMAT_START_CASE), 'Hello');
    assert.strictEqual(StringUtils.formatCase('helló. únder Ü??', StringUtils.FORMAT_START_CASE), 'Helló. Únder Ü??');
    assert.strictEqual(StringUtils.formatCase('óyeà!!! üst??', StringUtils.FORMAT_START_CASE), 'Óyeà!!! Üst??');
    assert.strictEqual(StringUtils.formatCase('Hello people', StringUtils.FORMAT_START_CASE), 'Hello People');
    assert.strictEqual(StringUtils.formatCase('Hello pEOPLE', StringUtils.FORMAT_START_CASE), 'Hello People');
    assert.strictEqual(StringUtils.formatCase("över! còmpléx.   \n\n\n\t\t   ís test! is? for!?!? you.!  ", StringUtils.FORMAT_START_CASE), "Över! Còmpléx.   \n\n\n\t\t   Ís Test! Is? For!?!? You.!  ");
    assert.strictEqual(StringUtils.formatCase('形声字 / 形聲字', StringUtils.FORMAT_START_CASE), '形声字 / 形聲字');
    
    // Test FORMAT_ALL_UPPER_CASE values
    assert.ok(StringUtils.formatCase('h', StringUtils.FORMAT_ALL_UPPER_CASE) === 'H');
    assert.ok(StringUtils.formatCase('HI', StringUtils.FORMAT_ALL_UPPER_CASE) === 'HI');
    assert.ok(StringUtils.formatCase('hello', StringUtils.FORMAT_ALL_UPPER_CASE) === 'HELLO');
    assert.ok(StringUtils.formatCase('helló. únder Ü??', StringUtils.FORMAT_ALL_UPPER_CASE) === 'HELLÓ. ÚNDER Ü??');
    assert.ok(StringUtils.formatCase('óyeà!!! üst??', StringUtils.FORMAT_ALL_UPPER_CASE) === 'ÓYEÀ!!! ÜST??');
    assert.ok(StringUtils.formatCase('Hello people', StringUtils.FORMAT_ALL_UPPER_CASE) === 'HELLO PEOPLE');
    assert.ok(StringUtils.formatCase('Hello pEOPLE', StringUtils.FORMAT_ALL_UPPER_CASE) === 'HELLO PEOPLE');
    assert.ok(StringUtils.formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils.FORMAT_ALL_UPPER_CASE) === "ÖVER! CÒMPLÉX.   \n\n\n\t\t   ÍS TEST!IS?FOR!?!? YOU.!  ");

    // Test FORMAT_ALL_LOWER_CASE values
    assert.ok(StringUtils.formatCase('h', StringUtils.FORMAT_ALL_LOWER_CASE) === 'h');
    assert.ok(StringUtils.formatCase('HI', StringUtils.FORMAT_ALL_LOWER_CASE) === 'hi');
    assert.ok(StringUtils.formatCase('hello', StringUtils.FORMAT_ALL_LOWER_CASE) === 'hello');
    assert.ok(StringUtils.formatCase('helló. únder Ü??', StringUtils.FORMAT_ALL_LOWER_CASE) === 'helló. únder ü??');
    assert.ok(StringUtils.formatCase('óyeà!!! üst??', StringUtils.FORMAT_ALL_LOWER_CASE) === 'óyeà!!! üst??');
    assert.ok(StringUtils.formatCase('Hello people', StringUtils.FORMAT_ALL_LOWER_CASE) === 'hello people');
    assert.ok(StringUtils.formatCase('Hello pEOPLE', StringUtils.FORMAT_ALL_LOWER_CASE) === 'hello people');
    assert.ok(StringUtils.formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils.FORMAT_ALL_LOWER_CASE) === "över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ");

    // Test FORMAT_FIRST_UPPER_REST_LOWER values
    assert.ok(StringUtils.formatCase('h', StringUtils.FORMAT_FIRST_UPPER_REST_LOWER) === 'H');
    assert.ok(StringUtils.formatCase('HI', StringUtils.FORMAT_FIRST_UPPER_REST_LOWER) === 'Hi');
    assert.ok(StringUtils.formatCase('hello', StringUtils.FORMAT_FIRST_UPPER_REST_LOWER) === 'Hello');
    assert.ok(StringUtils.formatCase('helló. únder Ü??', StringUtils.FORMAT_FIRST_UPPER_REST_LOWER) === 'Helló. únder ü??');
    assert.ok(StringUtils.formatCase('óyeà!!! üst??', StringUtils.FORMAT_FIRST_UPPER_REST_LOWER) === 'Óyeà!!! üst??');
    assert.ok(StringUtils.formatCase('Hello people', StringUtils.FORMAT_FIRST_UPPER_REST_LOWER) === 'Hello people');
    assert.ok(StringUtils.formatCase('Hello pEOPLE', StringUtils.FORMAT_FIRST_UPPER_REST_LOWER) === 'Hello people');
    assert.ok(StringUtils.formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils.FORMAT_FIRST_UPPER_REST_LOWER) === "Över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ");

    // Test FORMAT_SNAKE_CASE values
    // TODO - translate from PHP
    
    // Test FORMAT_UPPER_SNAKE_CASE values
    // TODO - translate from PHP
    
    // Test FORMAT_LOWER_SNAKE_CASE values
    // TODO - translate from PHP
    
    // Test FORMAT_CAMEL_CASE values
    assert.ok(StringUtils.formatCase('h', StringUtils.FORMAT_CAMEL_CASE) === 'h');
    assert.ok(StringUtils.formatCase('0', StringUtils.FORMAT_CAMEL_CASE) === '0');
    assert.ok(StringUtils.formatCase('ü', StringUtils.FORMAT_CAMEL_CASE) === 'u');
    assert.ok(StringUtils.formatCase('hI', StringUtils.FORMAT_CAMEL_CASE) === 'hI');
    assert.ok(StringUtils.formatCase('HI', StringUtils.FORMAT_CAMEL_CASE) === 'HI');
    assert.ok(StringUtils.formatCase('Ü        ', StringUtils.FORMAT_CAMEL_CASE) === 'U');
    assert.ok(StringUtils.formatCase('CamelCase', StringUtils.FORMAT_CAMEL_CASE) === 'CamelCase');
    assert.ok(StringUtils.formatCase('camelCase', StringUtils.FORMAT_CAMEL_CASE) === 'camelCase');
    assert.ok(StringUtils.formatCase('camelCaSE', StringUtils.FORMAT_CAMEL_CASE) === 'camelCaSE');
    assert.ok(StringUtils.formatCase('camel CaSE', StringUtils.FORMAT_CAMEL_CASE) === 'camelCaSE');
    assert.ok(StringUtils.formatCase('Camel Case', StringUtils.FORMAT_CAMEL_CASE) === 'CamelCase');
    assert.ok(StringUtils.formatCase('HTTP   Connection', StringUtils.FORMAT_CAMEL_CASE) === 'HTTPConnection');
    assert.ok(StringUtils.formatCase('sNake_Case', StringUtils.FORMAT_CAMEL_CASE) === 'sNakeCase');
    assert.ok(StringUtils.formatCase('Ibs Release Test Verification Regression Suite', StringUtils.FORMAT_CAMEL_CASE) === 'IbsReleaseTestVerificationRegressionSuite');
    assert.ok(StringUtils.formatCase('üéllò World', StringUtils.FORMAT_CAMEL_CASE) === 'uelloWorld');
    assert.ok(StringUtils.formatCase('óyeà!!! üst??', StringUtils.FORMAT_CAMEL_CASE) === 'oyeaUst');
    assert.ok(StringUtils.formatCase('this is some random text', StringUtils.FORMAT_CAMEL_CASE) === 'thisIsSomeRandomText');
    assert.ok(StringUtils.formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils.FORMAT_CAMEL_CASE) === 'overComplexIsTestIsForYou');

    // Test FORMAT_UPPER_CAMEL_CASE values
    assert.ok(StringUtils.formatCase('h', StringUtils.FORMAT_UPPER_CAMEL_CASE) === 'H');
    assert.ok(StringUtils.formatCase('0', StringUtils.FORMAT_UPPER_CAMEL_CASE) === '0');
    assert.ok(StringUtils.formatCase('ü', StringUtils.FORMAT_UPPER_CAMEL_CASE) === 'U');
    assert.ok(StringUtils.formatCase('hI', StringUtils.FORMAT_UPPER_CAMEL_CASE) === 'HI');
    assert.ok(StringUtils.formatCase('HI', StringUtils.FORMAT_UPPER_CAMEL_CASE) === 'HI');
    assert.ok(StringUtils.formatCase('Ü        ', StringUtils.FORMAT_UPPER_CAMEL_CASE) === 'U');
    assert.ok(StringUtils.formatCase('CamelCase', StringUtils.FORMAT_UPPER_CAMEL_CASE) === 'CamelCase');
    assert.ok(StringUtils.formatCase('camelCase', StringUtils.FORMAT_UPPER_CAMEL_CASE) === 'CamelCase');
    assert.ok(StringUtils.formatCase('camelCaSE', StringUtils.FORMAT_UPPER_CAMEL_CASE) === 'CamelCaSE');
    assert.ok(StringUtils.formatCase('camel CaSE', StringUtils.FORMAT_UPPER_CAMEL_CASE) === 'CamelCaSE');
    assert.ok(StringUtils.formatCase('Camel Case', StringUtils.FORMAT_UPPER_CAMEL_CASE) === 'CamelCase');
    assert.ok(StringUtils.formatCase('HTTP   Connection', StringUtils.FORMAT_UPPER_CAMEL_CASE) === 'HTTPConnection');
    assert.ok(StringUtils.formatCase('sNake_Case', StringUtils.FORMAT_UPPER_CAMEL_CASE) === 'SNakeCase');
    assert.ok(StringUtils.formatCase('Ibs Release Test Verification Regression Suite', StringUtils.FORMAT_UPPER_CAMEL_CASE) === 'IbsReleaseTestVerificationRegressionSuite');
    assert.ok(StringUtils.formatCase('üéllò World', StringUtils.FORMAT_UPPER_CAMEL_CASE) === 'UelloWorld');
    assert.ok(StringUtils.formatCase('óyeà!!! üst??', StringUtils.FORMAT_UPPER_CAMEL_CASE) === 'OyeaUst');
    assert.ok(StringUtils.formatCase('this is some random text', StringUtils.FORMAT_UPPER_CAMEL_CASE) === 'ThisIsSomeRandomText');
    assert.ok(StringUtils.formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils.FORMAT_UPPER_CAMEL_CASE) === 'OverComplexIsTestIsForYou');

    // Test FORMAT_LOWER_CAMEL_CASE values
    assert.ok(StringUtils.formatCase('h', StringUtils.FORMAT_LOWER_CAMEL_CASE) === 'h');
    assert.ok(StringUtils.formatCase('0', StringUtils.FORMAT_LOWER_CAMEL_CASE) === '0');
    assert.ok(StringUtils.formatCase('ü', StringUtils.FORMAT_LOWER_CAMEL_CASE) === 'u');
    assert.ok(StringUtils.formatCase('hI', StringUtils.FORMAT_LOWER_CAMEL_CASE) === 'hI');
    assert.ok(StringUtils.formatCase('HI', StringUtils.FORMAT_LOWER_CAMEL_CASE) === 'hI');
    assert.ok(StringUtils.formatCase('Ü        ', StringUtils.FORMAT_LOWER_CAMEL_CASE) === 'u');
    assert.ok(StringUtils.formatCase('CamelCase', StringUtils.FORMAT_LOWER_CAMEL_CASE) === 'camelCase');
    assert.ok(StringUtils.formatCase('camelCase', StringUtils.FORMAT_LOWER_CAMEL_CASE) === 'camelCase');
    assert.ok(StringUtils.formatCase('camelCaSE', StringUtils.FORMAT_LOWER_CAMEL_CASE) === 'camelCaSE');
    assert.ok(StringUtils.formatCase('camel CaSE', StringUtils.FORMAT_LOWER_CAMEL_CASE) === 'camelCaSE');
    assert.ok(StringUtils.formatCase('Camel Case', StringUtils.FORMAT_LOWER_CAMEL_CASE) === 'camelCase');
    assert.ok(StringUtils.formatCase('HTTP   Connection', StringUtils.FORMAT_LOWER_CAMEL_CASE) === 'hTTPConnection');
    assert.ok(StringUtils.formatCase('sNake_Case', StringUtils.FORMAT_LOWER_CAMEL_CASE) === 'sNakeCase');
    assert.ok(StringUtils.formatCase('Ibs Release Test Verification Regression Suite', StringUtils.FORMAT_LOWER_CAMEL_CASE) === 'ibsReleaseTestVerificationRegressionSuite');
    assert.ok(StringUtils.formatCase('üéllò World', StringUtils.FORMAT_LOWER_CAMEL_CASE) === 'uelloWorld');
    assert.ok(StringUtils.formatCase('óyeà!!! üst??', StringUtils.FORMAT_LOWER_CAMEL_CASE) === 'oyeaUst');
    assert.ok(StringUtils.formatCase('this is some random text', StringUtils.FORMAT_LOWER_CAMEL_CASE) === 'thisIsSomeRandomText');
    assert.ok(StringUtils.formatCase("över! còmpléx.   \n\n\n\t\t   ís test!is?for!?!? you.!  ", StringUtils.FORMAT_LOWER_CAMEL_CASE) === 'overComplexIsTestIsForYou');

    
    // Test exception cases
    assert.throws(function() {

        StringUtils.formatCase('helloWorld', '');
    }, /Unknown format specified/);

    assert.throws(function() {

        StringUtils.formatCase(1, StringUtils.FORMAT_SENTENCE_CASE);
    }, /value is not a string/);

    assert.throws(function() {

        StringUtils.formatCase([1,2,3], StringUtils.FORMAT_SENTENCE_CASE);
    }, /value is not a string/);

    assert.throws(function() {

        StringUtils.formatCase('Hello', 'invalidformat');
    }, /Unknown format specified/);
});


/**
 * formatPath
 */
QUnit.test("formatPath", function(assert) {

    // Test empty values
    assert.throws(function() {
        StringUtils.formatPath(null);
    }, /path must be a string/);
    
    assert.strictEqual(StringUtils.formatPath(''), '');
    assert.strictEqual(StringUtils.formatPath('       '), '       ');
    assert.strictEqual(StringUtils.formatPath("\n\n\n\n"), "\n\n\n\n");
    
    assert.throws(function() {
        StringUtils.formatPath([]);
    }, /path must be a string/);
    
    assert.throws(function() {
        StringUtils.formatPath('somepath', null);
    }, /separator must be a slash or backslash/);

    assert.throws(function() {
        StringUtils.formatPath('somepath', '');
    }, /separator must be a slash or backslash/);

    assert.throws(function() {
        StringUtils.formatPath('somepath', '     ');
    }, /separator must be a slash or backslash/);

    assert.throws(function() {
        StringUtils.formatPath('somepath', "\n\n\n\n");
    }, /separator must be a slash or backslash/);

    // Test ok values
    assert.strictEqual('/', StringUtils.formatPath('/'));
    assert.strictEqual('/a', StringUtils.formatPath('/a'));
    assert.strictEqual('/', StringUtils.formatPath('///////'));
    assert.strictEqual(StringUtils.formatPath('test//test/'), 'test/test');
    assert.strictEqual(StringUtils.formatPath('////test//////test////'), '/test/test');
    assert.strictEqual(StringUtils.formatPath('\\\\////test//test/'), '/test/test');
    assert.strictEqual(StringUtils.formatPath('test\\test/hello\\\\'), 'test/test/hello');
    assert.strictEqual(StringUtils.formatPath('someutf8_//转注字\\\\轉注/字'), 'someutf8_/转注字/轉注/字');

    assert.strictEqual(StringUtils.formatPath('test//test', '/'), 'test/test');
    assert.strictEqual(StringUtils.formatPath('////test//////test', '/'), '/test/test');
    assert.strictEqual(StringUtils.formatPath('\\\\////test//test', '/'), '/test/test');
    assert.strictEqual(StringUtils.formatPath('C:\\Users///someuser\\git\\\\project/newProject', '/'), 'C:/Users/someuser/git/project/newProject');
    assert.strictEqual(StringUtils.formatPath('someutf8_//转注字\\\\轉注/字', '/'), 'someutf8_/转注字/轉注/字');

    assert.strictEqual(StringUtils.formatPath('test//test/', '\\'), 'test\\test');
    assert.strictEqual(StringUtils.formatPath('////test//////test////', '\\'), '\\test\\test');
    assert.strictEqual(StringUtils.formatPath('\\\\////test//test/', '\\'), '\\test\\test');
    assert.strictEqual(StringUtils.formatPath('C:\\Users///someuser\\git\\\\project/newProject', '\\'), 'C:\\Users\\someuser\\git\\project\\newProject');
    assert.strictEqual(StringUtils.formatPath('someutf8_//转注字\\\\轉注/字', '\\'), 'someutf8_\\转注字\\轉注\\字');

    assert.strictEqual('\\', StringUtils.formatPath('/', '\\'));
    assert.strictEqual('\\a', StringUtils.formatPath('/a', '\\'));
    assert.strictEqual('\\', StringUtils.formatPath('///////', '\\'));
    assert.strictEqual('test\\test', StringUtils.formatPath('test//test/', '\\'));
    assert.strictEqual('\\test\\test', StringUtils.formatPath('\\\\////test//test/', '\\'));
    assert.strictEqual('test\\test\\hello', StringUtils.formatPath('test\\test/hello\\\\', '\\'));
    assert.strictEqual('someutf8_\\转注字\\轉注\\字', StringUtils.formatPath('someutf8_//转注字\\\\轉注/字', '\\'));

    // Test wrong values
    assert.strictEqual(StringUtils.formatPath('!"(&%"·$|||'), '!"(&%"·$|||');
    assert.strictEqual(StringUtils.formatPath("\n\ntest//wrong", '/'), "\n\ntest/wrong");
    assert.strictEqual(StringUtils.formatPath("_____"), "_____");

    // Test exceptions
    assert.throws(function() {
        StringUtils.formatPath(['1']);
    }, /path must be a string/);

    assert.throws(function() {
        StringUtils.formatPath(1);
    }, /path must be a string/);

    assert.throws(function() {
        StringUtils.formatPath('path/path', 'W');
    }, /separator must be a slash or backslash/);

    assert.throws(function() {
        StringUtils.formatPath('path/path', 9);
    }, /separator must be a slash or backslash/);
});


/**
 * formatUrl
 */
QUnit.test("formatUrl", function(assert) {

    assert.throws(function() {
        StringUtils.formatUrl(null);
    }, /url must be a string/);
    
    assert.throws(function() {
        StringUtils.formatUrl(undefined);
    }, /url must be a string/);
    
    assert.ok(StringUtils.formatUrl('') === '');

    // Format correct urls
    assert.ok(StringUtils.formatUrl('google.com') === 'http://google.com');
    assert.ok(StringUtils.formatUrl('www.google.com') === 'http://www.google.com');
    assert.ok(StringUtils.formatUrl('   google.es') === 'http://google.es');
    assert.ok(StringUtils.formatUrl('google.es   ') === 'http://google.es');
    assert.ok(StringUtils.formatUrl('   google.es   ') === 'http://google.es');
    assert.ok(StringUtils.formatUrl('http://www.example.com:8800') === 'http://www.example.com:8800');
    assert.ok(StringUtils.formatUrl('www.example.com:8800') === 'http://www.example.com:8800');
    assert.ok(StringUtils.formatUrl('foo.com/blah_blah/') === 'http://foo.com/blah_blah/');
    assert.ok(StringUtils.formatUrl('ftp://test.com   ') === 'ftp://test.com');
    assert.ok(StringUtils.formatUrl('http:\\\\test.com') === 'http://test.com');
    assert.ok(StringUtils.formatUrl('https://angular.io/guide/http') === 'https://angular.io/guide/http');
    assert.ok(StringUtils.formatUrl('https://angular.io\\guide/http') === 'https://angular.io/guide/http');
    assert.ok(StringUtils.formatUrl('https://angular.io\\guide/////http') === 'https://angular.io/guide/http');
    assert.ok(StringUtils.formatUrl('https://angular.io/api/common/http/HttpErrorResponse') === 'https://angular.io/api/common/http/HttpErrorResponse');
    assert.ok(StringUtils.formatUrl('https://www.youtube.com/watch?v=dp5hsDgENLk&feature=youtu.be') === 'https://www.youtube.com/watch?v=dp5hsDgENLk&feature=youtu.be');
    assert.ok(StringUtils.formatUrl('https://www.ovh.com/auth/?action=gotomanager&from=https://www.ovh.es/') === 'https://www.ovh.com/auth/?action=gotomanager&from=https://www.ovh.es/');
    assert.ok(StringUtils.formatUrl('https://stackoverflow.com/questions/10161177/url-with-multiple-forward-slashes-does-it-break-anything') === 'https://stackoverflow.com/questions/10161177/url-with-multiple-forward-slashes-does-it-break-anything');

    // Format incorrect urls
    assert.ok(StringUtils.formatUrl('        ') === '        ');
    assert.ok(StringUtils.formatUrl('123f56ccaca') === '123f56ccaca');
    assert.ok(StringUtils.formatUrl('!$%&ERTdg4547') === '!$%&ERTdg4547');
    assert.ok(StringUtils.formatUrl('http://.32') === 'http://.32');
    assert.ok(StringUtils.formatUrl('http://10.1.1.255') === 'http://10.1.1.255');
    assert.ok(StringUtils.formatUrl('ftp:/google.es') === 'ftp:/google.es');
});


/**
 * formatForFullTextSearch
 */
QUnit.test("formatForFullTextSearch", function(assert) {

    // Test empty values
    assert.throws(function() { StringUtils.formatForFullTextSearch(null); }, /value is not a string/);
    assert.throws(function() { StringUtils.formatForFullTextSearch([]); }, /value is not a string/);
    assert.strictEqual(StringUtils.formatForFullTextSearch(''), '');

    // Test ok values
    assert.strictEqual(StringUtils.formatForFullTextSearch('          '), '');
    assert.strictEqual(StringUtils.formatForFullTextSearch('a'), 'a');
    assert.strictEqual(StringUtils.formatForFullTextSearch('A'), 'a');
    assert.strictEqual(StringUtils.formatForFullTextSearch('aA'), 'aa');
    assert.strictEqual(StringUtils.formatForFullTextSearch('  A'), 'a');
    assert.strictEqual(StringUtils.formatForFullTextSearch('  A    B   '), 'ab');
    assert.strictEqual(StringUtils.formatForFullTextSearch('123   345345'), '123345345');
    assert.strictEqual(StringUtils.formatForFullTextSearch(' cássíc àccents óOÖ'), 'cassicaccentsooo');
    assert.strictEqual(StringUtils.formatForFullTextSearch('  word 1 word2 W-oRD3'), 'word1word2word3');
    assert.strictEqual(StringUtils.formatForFullTextSearch('   &1%&!/&$!/"&%/!"    '), '1');
    assert.strictEqual(StringUtils.formatForFullTextSearch('   &%&!/&$!/"&heLLO%/!"    '), 'hello');
    assert.strictEqual(StringUtils.formatForFullTextSearch('形声字   /   形聲字'), '形声字形聲字');
    assert.strictEqual(StringUtils.formatForFullTextSearch('AhFTY$%!&"$·/1237aA   形...//(()聲()?---字αβγΔδεZζHηÁíÏÖÒ'), 'ahfty1237aa形聲字αβγδδεzζhηaiioo');

    assert.ok(StringUtils.formatForFullTextSearch('  A').includes(StringUtils.formatForFullTextSearch('a')));
    assert.ok(StringUtils.formatForFullTextSearch('  A').includes(StringUtils.formatForFullTextSearch('  a')));
    assert.ok(StringUtils.formatForFullTextSearch('  A').includes(StringUtils.formatForFullTextSearch('      a')));
    assert.ok(StringUtils.formatForFullTextSearch('  word 1 word2 W-oR=)D3').includes(StringUtils.formatForFullTextSearch('WORD2')));
    assert.ok(StringUtils.formatForFullTextSearch('  word 1 word2 W-oR.D3').includes(StringUtils.formatForFullTextSearch('WORD3')));
    assert.ok(StringUtils.formatForFullTextSearch('  word 1 word2 W-oR.D3').includes(StringUtils.formatForFullTextSearch('word1')));
    assert.ok(StringUtils.formatForFullTextSearch('  word 1 word2 W-oR.D3').includes(StringUtils.formatForFullTextSearch('word1word2')));
    assert.ok(StringUtils.formatForFullTextSearch('  word 1 word2 W-oR.D3').includes(StringUtils.formatForFullTextSearch('word1 word2')));
    assert.ok(StringUtils.formatForFullTextSearch('  word 1 word2 W-oR.D3').includes(StringUtils.formatForFullTextSearch('Word1word2WORD3')));
    assert.ok(StringUtils.formatForFullTextSearch('  word 1 word2 W-!""·$$%/oR.D3').includes(StringUtils.formatForFullTextSearch('   word1  word2     WORD3')));
    assert.ok(StringUtils.formatForFullTextSearch('AhFTY$%!&"$·/1237aA   形...//(()聲()?---字αβγΔδεZζHηÁíÏÖÒ').includes(StringUtils.formatForFullTextSearch('形聲字')));

    // Test wrong values
    // Not necessary

    // Test exceptions
    assert.throws(function() { StringUtils.formatForFullTextSearch(12234); }, /value is not a string/);
    assert.throws(function() { StringUtils.formatForFullTextSearch([1,2,3]); }, /value is not a string/);
    assert.throws(function() { StringUtils.formatForFullTextSearch(new Error()); }, /value is not a string/);
});


/**
 * compareByLevenshtein
 */
QUnit.test("compareByLevenshtein", function(assert) {

    // Test empty values
    assert.throws(function() {
        StringUtils.compareByLevenshtein(null, null);
    }, /string1 and string2 must be strings/);

    assert.throws(function() {
        StringUtils.compareByLevenshtein([], []);
    }, /string1 and string2 must be strings/);

    assert.strictEqual(0, StringUtils.compareByLevenshtein("", ""));
    assert.strictEqual(0, StringUtils.compareByLevenshtein("   ", "   "));

    // Test ok values
    assert.strictEqual(1, StringUtils.compareByLevenshtein("a", ""));
    assert.strictEqual(1, StringUtils.compareByLevenshtein("", "a"));
    assert.strictEqual(3, StringUtils.compareByLevenshtein("abc", ""));
    assert.strictEqual(3, StringUtils.compareByLevenshtein("", "abc"));

    assert.strictEqual(0, StringUtils.compareByLevenshtein("", ""));
    assert.strictEqual(0, StringUtils.compareByLevenshtein("a", "a"));
    assert.strictEqual(0, StringUtils.compareByLevenshtein("abc", "abc"));

    assert.strictEqual(1, StringUtils.compareByLevenshtein("", "a"));
    assert.strictEqual(1, StringUtils.compareByLevenshtein("a", "ab"));
    assert.strictEqual(1, StringUtils.compareByLevenshtein("b", "ab"));
    assert.strictEqual(1, StringUtils.compareByLevenshtein("ac", "abc"));
    assert.strictEqual(6, StringUtils.compareByLevenshtein("abcdefg", "xabxcdxxefxgx"));

    assert.strictEqual(1, StringUtils.compareByLevenshtein("a", ""));
    assert.strictEqual(1, StringUtils.compareByLevenshtein("ab", "a"));
    assert.strictEqual(1, StringUtils.compareByLevenshtein("ab", "b"));
    assert.strictEqual(1, StringUtils.compareByLevenshtein("abc", "ac"));
    assert.strictEqual(6, StringUtils.compareByLevenshtein("xabxcdxxefxgx", "abcdefg"));

    assert.strictEqual(1, StringUtils.compareByLevenshtein("a", "b"));
    assert.strictEqual(1, StringUtils.compareByLevenshtein("ab", "ac"));
    assert.strictEqual(1, StringUtils.compareByLevenshtein("ac", "bc"));
    assert.strictEqual(1, StringUtils.compareByLevenshtein("abc", "axc"));
    assert.strictEqual(6, StringUtils.compareByLevenshtein("xabxcdxxefxgx", "1ab2cd34ef5g6"));

    assert.strictEqual(3, StringUtils.compareByLevenshtein("example", "samples"));
    assert.strictEqual(6, StringUtils.compareByLevenshtein("sturgeon", "urgently"));
    assert.strictEqual(6, StringUtils.compareByLevenshtein("levenshtein", "frankenstein"));
    assert.strictEqual(5, StringUtils.compareByLevenshtein("distance", "difference"));
    assert.strictEqual(7, StringUtils.compareByLevenshtein("java was neat", "scala is great"));

    assert.strictEqual(1, StringUtils.compareByLevenshtein("èéöÖU", "eéöÖU"));
    assert.strictEqual(2, StringUtils.compareByLevenshtein("èéöÖU", "eéöOU"));
    assert.strictEqual(3, StringUtils.compareByLevenshtein("èéöÖU", "eéöOu"));
    assert.strictEqual(4, StringUtils.compareByLevenshtein("èéöÖU", "eèöOu"));
    assert.strictEqual(5, StringUtils.compareByLevenshtein("èéöÖU", "eèöOu "));
    assert.strictEqual(4, StringUtils.compareByLevenshtein("èéöÖ", "eèöOu"));

    assert.strictEqual(3, StringUtils.compareByLevenshtein("HONDA", "HYUNDAI"));
    assert.strictEqual(1, StringUtils.compareByLevenshtein("Honda", "honda"));
    assert.strictEqual(5, StringUtils.compareByLevenshtein("honda", "HONDA"));
    assert.strictEqual(3, StringUtils.compareByLevenshtein("kitten", "sitting"));

    assert.strictEqual(1, StringUtils.compareByLevenshtein("形声字 / 形聲字", "形声字 A 形聲字"));
    assert.strictEqual(3, StringUtils.compareByLevenshtein("形声字 / 形聲字", "1声字 A 形聲"));
    assert.strictEqual(5, StringUtils.compareByLevenshtein("形声字 / 形聲字", "13字 A 形A"));
    assert.strictEqual(9, StringUtils.compareByLevenshtein("形声字 / 形聲字", "sitting"));

    // Test wrong values
    // Not necessary

    // Test exceptions
    assert.throws(function() {
        StringUtils.compareByLevenshtein(1234, 345345);
    }, /string1 and string2 must be strings/);

    assert.throws(function() {
        StringUtils.compareByLevenshtein([1, 2, 3, 4], [2, 4, 5, 6]);
    }, /string1 and string2 must be strings/);

    assert.throws(function() {
        StringUtils.compareByLevenshtein(new Error(), new Error());
    }, /string1 and string2 must be strings/);
});


/**
 * compareSimilarityPercent
 */
QUnit.todo("compareSimilarityPercent", function(assert) {

    // Test empty values
    assert.throws(function() {
        StringUtils.compareSimilarityPercent(null, null);
    }, /string1 and string2 must be strings/);

    assert.throws(function() {
        StringUtils.compareSimilarityPercent(null, "");
    }, /string1 and string2 must be strings/);

    assert.throws(function() {
        StringUtils.compareSimilarityPercent([], []);
    }, /string1 and string2 must be strings/);

    assert.strictEqual(100, StringUtils.compareSimilarityPercent("", ""));
    assert.strictEqual(0, StringUtils.compareSimilarityPercent("", "    "));
    assert.strictEqual(0, StringUtils.compareSimilarityPercent("    ", ""));
    assert.strictEqual(100, StringUtils.compareSimilarityPercent("   ", "   "));

    // Test ok values
    assert.strictEqual(0, StringUtils.compareSimilarityPercent("a", "b"));
    assert.strictEqual(25.0, StringUtils.compareSimilarityPercent("aaaa", "anUy"));
    assert.strictEqual(50.0, StringUtils.compareSimilarityPercent("aa", "ab"));
    assert.strictEqual(50.0, StringUtils.compareSimilarityPercent("aaaa", "aaXx"));
    assert.strictEqual(75.0, StringUtils.compareSimilarityPercent("aaaa", "aaaQ"));
    assert.strictEqual(80.0, StringUtils.compareSimilarityPercent("abcde", "abcd"));
    assert.strictEqual(83.33333333333334, StringUtils.compareSimilarityPercent("aiuygb", "aiUygb"));
    assert.strictEqual(94.44444444444444, StringUtils.compareSimilarityPercent("形声字 A 形聲字形声字 / 形聲字", "形声字 / 形聲字形声字 / 形聲字"));
    assert.strictEqual(100, StringUtils.compareSimilarityPercent("形声字 / 形聲字形声字 / 形聲字", "形声字 / 形聲字形声字 / 形聲字"));

    // Test wrong values
    // Not necessary

    // Test exceptions
    assert.throws(function() {
        StringUtils.compareSimilarityPercent(1234, 345345);
    }, /string1 and string2 must be strings/);

    assert.throws(function() {
        StringUtils.compareSimilarityPercent([1, 2, 3, 4], [2, 4, 5, 6]);
    }, /string1 and string2 must be strings/);

    assert.throws(function() {
        StringUtils.compareSimilarityPercent(new Exception(), new Exception());
    }, /string1 and string2 must be strings/);
});


/**
 * generatePath
 */
QUnit.test("generatePath", function(assert) {

    // Test empty values
    assert.throws(function() { StringUtils.generatePath(null, null); }, /elements is not an array/);
    assert.throws(function() { StringUtils.generatePath(0, 0); }, /elements is not an array/);
    assert.throws(function() { StringUtils.generatePath([], []); }, /separator must be a slash or backslash/);
    assert.throws(function() { StringUtils.generatePath('', []); }, /elements is not an array/);
    assert.strictEqual('', StringUtils.generatePath([], ''));
    assert.strictEqual('', StringUtils.generatePath([''], ''));
    assert.strictEqual('a', StringUtils.generatePath(['a'], ''));

    // Test ok values
    assert.strictEqual('a', StringUtils.generatePath(['a'], '/'));
    assert.strictEqual('a/b', StringUtils.generatePath(['a', 'b'], '/'));
    assert.strictEqual('a\\b', StringUtils.generatePath(['a', 'b'], '\\'));
    assert.strictEqual('a/b/c', StringUtils.generatePath(['a', 'b', 'c']));

    // Test wrong values
    // Not necessary

    // Test exceptions
    assert.throws(function() { StringUtils.generatePath(['a'], 'a'); }, /separator must be a slash or backslash/);
    assert.throws(function() { StringUtils.generatePath([1, 2, 3, 4], 0); }, /separator must be a slash or backslash/);
    assert.throws(function() { StringUtils.generatePath(123); }, /elements is not an array/);
});


/**
 * generateRandom
 */
QUnit.test("generateRandom", function(assert) {

    // Test empty values
    assert.ok(StringUtils.generateRandom(0, 0) === '');

    assert.throws(function() {

        StringUtils.generateRandom(undefined, undefined);
    }, /minLength and maxLength must be positive numbers/);
    
    assert.throws(function() {

        StringUtils.generateRandom(null, null);
    }, /minLength and maxLength must be positive numbers/);
    
    assert.throws(function() {

        StringUtils.generateRandom(1, 1, null);
    }, /invalid charset/);
    
    assert.throws(function() {

        StringUtils.generateRandom(1, 1, ['']);
    }, /invalid charset/);
    
    // Test ok values
    assert.ok(StringUtils.generateRandom(1, 1, ['T']) === 'T');
    assert.ok(StringUtils.generateRandom(3, 3, ['0']) === '000');
    assert.ok(StringUtils.generateRandom(5, 5, ['a']) === 'aaaaa');
    
    for (var i = 1; i < 30; i++) {

        assert.ok(StringUtils.generateRandom(i, i).length === i);
        
        // test only numeric string
        var s = StringUtils.generateRandom(i, i*2, ['0-9']);
        assert.ok(NumericUtils.isNumeric(s));
        assert.ok(s.length >= i && s.length <= i*2);
        
        var s = StringUtils.generateRandom(i, i+1, ['3-6']);
        assert.ok(NumericUtils.isNumeric(s));
        assert.ok(s.length >= i && s.length <= i+1);
        
        for (var j = 0; j < s.length; j++) {
            
            assert.ok('012'.indexOf(s.charAt(j)) < 0);
            assert.ok('789'.indexOf(s.charAt(j)) < 0);
            assert.ok('3456'.indexOf(s.charAt(j)) >= 0);
        }
        
        // test only lowercase alphabetic strings
        var s = StringUtils.generateRandom(i, i*2, ['a-z']);
        assert.ok(StringUtils.isString(s));
        assert.ok(s.length >= i && s.length <= i*2);
        
        var s = StringUtils.generateRandom(i, i+1, ['g-r']);
        assert.ok(StringUtils.isString(s));
        assert.ok(s.length >= i && s.length <= i+1);
        
        for (var j = 0; j < s.length; j++) {
            
            assert.ok(s.charAt(j).toLowerCase() === s.charAt(j));
            assert.ok('abcdef'.indexOf(s.charAt(j)) < 0);
            assert.ok('stuvwxyz'.indexOf(s.charAt(j)) < 0);
            assert.ok('ghijkmnopqr'.indexOf(s.charAt(j)) >= 0);
        }
        
        // test only uppercase alphabetic strings
        var s = StringUtils.generateRandom(i, i*2, ['A-Z']);
        assert.ok(StringUtils.isString(s));
        assert.ok(s.length >= i && s.length <= i*2);
        
        var s = StringUtils.generateRandom(i, i+1, ['I-M']);
        assert.ok(StringUtils.isString(s));
        assert.ok(s.length >= i && s.length <= i+1);
        
        for (var j = 0; j < s.length; j++) {
            
            assert.ok(s.charAt(j).toUpperCase() === s.charAt(j));
            assert.ok('ABCDEFGH'.indexOf(s.charAt(j)) < 0);
            assert.ok('NOPQRSTUVWXYZ'.indexOf(s.charAt(j)) < 0);
            assert.ok('IJKM'.indexOf(s.charAt(j)) >= 0);
        }
        
        // Test numbers and upper case and lower case letters
        var s = StringUtils.generateRandom(i, i*2, ['0-9', 'a-z', 'A-Z']);
        assert.ok(StringUtils.isString(s));
        assert.ok(s.length >= i && s.length <= i*2);
        
        for (var j = 0; j < s.length; j++) {
            
            assert.ok('0123456789'.indexOf(s.charAt(j)) >= 0 ||
                    'abcdefghijkmnopqrstuvwxyz'.indexOf(s.charAt(j)) >= 0 ||
                    'ABCDEFGHIJKMNOPQRSTUVWXYZ'.indexOf(s.charAt(j)) >= 0);
        }
        
        // Test fixed characters set
        var s = StringUtils.generateRandom(i, i*2, ['97hjrfHNgbf71']);
        assert.ok(StringUtils.isString(s));
        assert.ok(s.length >= i && s.length <= i*2);
        
        for (var j = 0; j < s.length; j++) {
            
            assert.ok('97hjrfHNgbf71'.indexOf(s.charAt(j)) >= 0);
        }
        
        var s = StringUtils.generateRandom(1, 500, ['&/$hb\\-81679Ç+\\-']);
        assert.ok(StringUtils.isString(s));
        assert.ok(s.length >= 1 && s.length <= 500);
        
        for (var j = 0; j < s.length; j++) {
            
            assert.ok('&/$hb-81679Ç+-'.indexOf(s.charAt(j)) >= 0);
        }
    }
    
    // Test wrong values
    // not necessary

    // Test exceptions
    assert.throws(function() {
        StringUtils.generateRandom(-1, 1);
    }, /minLength and maxLength must be positive numbers/);
    
    assert.throws(function() {
        StringUtils.generateRandom(1, -1);
    }, /minLength and maxLength must be positive numbers/);

    assert.throws(function() {
        StringUtils.generateRandom('some string', 1);
    }, /minLength and maxLength must be positive numbers/);
    
    assert.throws(function() {
        StringUtils.generateRandom(1, 'some string');
    }, /minLength and maxLength must be positive numbers/);
    
    assert.throws(function() {
        StringUtils.generateRandom(1, 2, 'ertr');
    }, /invalid charset/);
    
    assert.throws(function() {
        StringUtils.generateRandom(1, 2, {});
    }, /invalid charset/);
});


/**
 * testFindMostSimilarString
 */
QUnit.todo("testFindMostSimilarString", function(assert) {

    // TODO: copy tests from PHP
});


/**
 * testFindMostSimilarStringIndex
 */
QUnit.todo("testFindMostSimilarStringIndex", function(assert) {

    // TODO: copy tests from PHP
});


/**
 * testRemoveNewLineCharacters
 */
QUnit.todo("testRemoveNewLineCharacters", function(assert) {

    // TODO: copy tests from PHP
});


/**
 * removeAccents
 */
QUnit.test("removeAccents", function(assert) {

    // Test empty values
    assert.throws(function(){ StringUtils.removeAccents(null); }, /value is not a string/);

    assert.ok(StringUtils.removeAccents('') === '');
    assert.ok(StringUtils.removeAccents('        ') === '        ');

    // Test ok values
    assert.ok(StringUtils.removeAccents('Fó Bår') === 'Fo Bar');
    assert.ok(StringUtils.removeAccents("|!€%'''") === "|!€%'''");
    assert.ok(StringUtils.removeAccents('hiweury asb fsuyr weqr') === 'hiweury asb fsuyr weqr');
    assert.ok(StringUtils.removeAccents('!iYgh65541tGY%$$73267yt') === '!iYgh65541tGY%$$73267yt');
    assert.ok(StringUtils.removeAccents('hello 12786,.123123') === 'hello 12786,.123123');
    assert.ok(StringUtils.removeAccents('check this `^+*´--_{}[]') === 'check this `^+*´--_{}[]');
    assert.ok(StringUtils.removeAccents('hellóóóóóí´ 12786,.123123"') === 'helloooooi´ 12786,.123123"');
    assert.ok(StringUtils.removeAccents("hello\nbaby\r\ntest it well !!!!!") === "hello\nbaby\r\ntest it well !!!!!");
    assert.ok(StringUtils.removeAccents('óíéàùú hello') === 'oieauu hello');
    assert.ok(StringUtils.removeAccents("óóó èèè\núùúùioler    \r\noughúíééanh hello") === "ooo eee\nuuuuioler    \r\noughuieeanh hello");
    assert.ok(StringUtils.removeAccents('öïüíúóèà go!!.;') === 'oiuiuoea go!!.;');

    // Test wrong values
    // Test exceptions
});


/**
 * removeWordsShorterThan
 */
QUnit.todo("removeWordsShorterThan", function(assert) {

    // TODO: copy tests from PHP
});


/**
 * removeWordsLongerThan
 */
QUnit.todo("removeWordsLongerThan", function(assert) {

    // TODO: copy tests from PHP
});


/**
 * removeUrls
 */
QUnit.todo("removeUrls", function(assert) {

    // TODO: copy tests from PHP
});


/**
 * removeHtmlCode
 */
QUnit.todo("removeHtmlCode", function(assert) {

    // TODO: copy tests from PHP
});


/**
 * removeSameConsecutive
 */
QUnit.test("removeSameConsecutive", function(assert) {

    // Test empty values
    assert.strictEqual(StringUtils.removeSameConsecutive(null), '');
    assert.strictEqual(StringUtils.removeSameConsecutive(''), '');
    assert.strictEqual(StringUtils.removeSameConsecutive('     '), ' ');
    assert.strictEqual(StringUtils.removeSameConsecutive("\n\n\n\n"), "\n");
    assert.strictEqual(StringUtils.removeSameConsecutive("\r\r\r\r"), "\r");
    assert.strictEqual(StringUtils.removeSameConsecutive("\r\n\r\n\r\n\r\n\r\n"), "\r\n");

    assert.throws(function(){ StringUtils.removeSameConsecutive([]); }, /string must be a string/);

    // Test ok values - ALL CHARACTERS
    assert.strictEqual(StringUtils.removeSameConsecutive('a'), 'a');
    assert.strictEqual(StringUtils.removeSameConsecutive('abc'), 'abc');
    assert.strictEqual(StringUtils.removeSameConsecutive('aa'), 'a');
    assert.strictEqual(StringUtils.removeSameConsecutive('abcabc'), 'abcabc');
    assert.strictEqual(StringUtils.removeSameConsecutive('aabbcc'), 'abc');
    assert.strictEqual(StringUtils.removeSameConsecutive("abbbbbbbbbb    cccccc\n\n\nddd"), "ab c\nd");
    assert.strictEqual(StringUtils.removeSameConsecutive("..88ynbvER745bhbvFFGFfCc   \n\n\r\r  Www?o??--"), ".8ynbvER745bhbvFGFfCc \n\r Ww?o?-");
    assert.strictEqual(StringUtils.removeSameConsecutive('形声字 / 形聲字'), '形声字 / 形聲字');
    assert.strictEqual(StringUtils.removeSameConsecutive('形形形形声字 / 形聲字'), '形声字 / 形聲字');
    assert.strictEqual(StringUtils.removeSameConsecutive("形声字形聲字  \n\n形声字字字形聲字\r\r\r\r    "), "形声字形聲字 \n形声字形聲字\r ");

    // Test ok values - A SET OF CHARACTERS
    assert.strictEqual(StringUtils.removeSameConsecutive('       ', [' ']), ' ');
    assert.strictEqual(StringUtils.removeSameConsecutive(' asdf  asdf', [' ']), ' asdf asdf');
    assert.strictEqual(StringUtils.removeSameConsecutive('aabbuu77abc7     ', ['a', 'b', ' ']), 'abuu77abc7 ');
    assert.strictEqual(StringUtils.removeSameConsecutive('aabbuu77abc7     ', ['a', 'b', ' ', 'u']), 'abu77abc7 ');
    assert.strictEqual(StringUtils.removeSameConsecutive("ab\n\n\r\r\n    bbbbaaa", [" ", "\n"]), "ab\n\r\r\n bbbbaaa");
    assert.strictEqual(StringUtils.removeSameConsecutive("ab\n\n\r\r\n    bbbbaaa", ["a", "b", "\n"]), "ab\n\r\r\n    ba");
    assert.strictEqual(StringUtils.removeSameConsecutive("ab\n\n\r\r\n    bbbbaaa",  ["a", "b", "\r", "\n"]), "ab\n\r\n    ba");
    assert.strictEqual(StringUtils.removeSameConsecutive("ab abababab a b a b",  ["ab"]), "ab ab a b a b");
    assert.strictEqual(StringUtils.removeSameConsecutive("ab abababab a b a b ",  ["a b "]), "ab abababab a b ");
    assert.strictEqual(StringUtils.removeSameConsecutive('形声字字字形形形形聲字   ', ['字']), '形声字形形形形聲字   ');
    assert.strictEqual(StringUtils.removeSameConsecutive('形声字字字形形形形聲字   ', ['字', '形']), '形声字形聲字   ');
    assert.strictEqual(StringUtils.removeSameConsecutive('形声字字字形形形形聲字   ', ['字', '形', ' ']), '形声字形聲字 ');
    assert.strictEqual(StringUtils.removeSameConsecutive("形声字字字形形形形聲聲聲聲聲聲聲字     \n\n\n\r\n\r\n\r\n     聲聲聲聲", ['字', '形', ' ', "\n"]), "形声字形聲聲聲聲聲聲聲字 \n\r\n\r\n\r\n 聲聲聲聲");
    assert.strictEqual(StringUtils.removeSameConsecutive("形声字字字形形形形聲聲聲聲聲聲聲字     \n\n\n\r\n\r\n\r\n     聲聲聲聲", ['字', '形', ' ', "\n", "\r\n"]), "形声字形聲聲聲聲聲聲聲字 \n\r\n 聲聲聲聲");
    assert.strictEqual(StringUtils.removeSameConsecutive("形声字字字形形形形聲聲聲聲聲聲聲字     \n\n\n\r\n\r\n\r\n     聲聲聲聲", ['字', '形', ' ', "\n", "\r\n", "聲"]), "形声字形聲字 \n\r\n 聲");
    assert.strictEqual(StringUtils.removeSameConsecutive('hellohellohello hello hello    ', ['hello']), 'hello hello hello    ');
    assert.strictEqual(StringUtils.removeSameConsecutive('hellohellohello hello hello    ', ['hello', ' ']), 'hello hello hello ');

    // Test wrong values
    // Test exceptions
    assert.throws(function(){ StringUtils.removeSameConsecutive(123123); }, /string must be a string/);
    assert.throws(function(){ StringUtils.removeSameConsecutive('abc', 'hello'); }, /must be of the type array/);
});
