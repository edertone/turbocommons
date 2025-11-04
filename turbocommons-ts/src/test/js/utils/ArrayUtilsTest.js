"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

QUnit.module("ArrayUtilsTest", {
    beforeEach : function() {

        window.ArrayUtils = org_turbocommons.ArrayUtils;
        window.StringUtils = org_turbocommons.StringUtils;
    },

    afterEach : function() {

        delete window.ArrayUtils;
        delete window.StringUtils;
    }
});


/** test */
QUnit.test("isArray", function(assert) {

    // Test empty values
    assert.ok(!ArrayUtils.isArray(undefined));
    assert.ok(!ArrayUtils.isArray(null));
    assert.ok(ArrayUtils.isArray([]));
    assert.ok(!ArrayUtils.isArray(0));

    // Test correct values
    assert.ok(ArrayUtils.isArray([1]));
    assert.ok(ArrayUtils.isArray(["2"]));
    assert.ok(ArrayUtils.isArray(["q"]));
    assert.ok(ArrayUtils.isArray([true, false]));
    assert.ok(ArrayUtils.isArray([1, 4, "a"]));
    assert.ok(ArrayUtils.isArray([new Error(), 67]));

    // Test wrong values
    assert.ok(!ArrayUtils.isArray(1));
    assert.ok(!ArrayUtils.isArray("a"));
    assert.ok(!ArrayUtils.isArray(false));
    assert.ok(!ArrayUtils.isArray(new Error()));
    assert.ok(!ArrayUtils.isArray({
            a : 1
        }));
});


/**
 * isEqualTo
 */
QUnit.test("isEqualTo", function(assert) {

    // Test non array values must launch exception
    assert.throws(function() { ArrayUtils.isEqualTo(null, null); }, /parameters must be arrays/);
    assert.throws(function() { ArrayUtils.isEqualTo(1, 1); }, /parameters must be arrays/);
    assert.throws(function() { ArrayUtils.isEqualTo("asfasf1", "345345"); }, /parameters must be arrays/);

    // Test identic arrays
    assert.ok(ArrayUtils.isEqualTo([null], [null]));
    assert.ok(ArrayUtils.isEqualTo([], []));
    assert.ok(ArrayUtils.isEqualTo([[]], [[]]));
    assert.ok(ArrayUtils.isEqualTo([[1]], [[1]]));
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3], [1, 2, 3]));
    assert.ok(ArrayUtils.isEqualTo([1, 2, 1, 2], [1, 2, 1, 2]));
    assert.ok(ArrayUtils.isEqualTo([1, 2, [3, 4]], [1, 2, [3, 4]]));
    assert.ok(ArrayUtils.isEqualTo(["hello world"], ["hello world"]));

    // Test different arrays
    assert.ok(!ArrayUtils.isEqualTo([null], []));
    assert.ok(!ArrayUtils.isEqualTo([1], ["1"]));
    assert.ok(!ArrayUtils.isEqualTo([1, 2, 3], [1, 3, 2]));
    assert.ok(!ArrayUtils.isEqualTo([1, "2,3"], [1, 2, 3]));
    assert.ok(!ArrayUtils.isEqualTo([1, 2, [3, 4]], [1, 2, [3, 2]]));
    assert.ok(!ArrayUtils.isEqualTo([1, 2, [3, [4]]], [1, 2, [3, ["4"]]]));
    assert.ok(!ArrayUtils.isEqualTo(["hello world"], ["hello worl1d"]));
    // Associative arrays can't be created on js, so following tests are useless:
    // !ArrayUtils.isEqualTo(['0' => 0, '1' => 1], ['a' => 0, 'b' => 1]));
    // !ArrayUtils.isEqualTo(['0' => null, '1' => null], ['a' => 0, 'b' => 1]));
    // !ArrayUtils.isEqualTo(['a' => 0, 'b' => 1], ['a' => 0, 'b' => 2]));

    // Test identic objects
    assert.ok(ArrayUtils.isEqualTo([{
        a : 1
    }], [{
        a : 1
    }]));

    assert.ok(ArrayUtils.isEqualTo([{
        a : 1,
        b : [1, 2, 3],
        c : 'hello'
    }], [{
        a : 1,
        b : [1, 2, 3],
        c : 'hello'
    }]));

    // Test different objects
    assert.ok(!ArrayUtils.isEqualTo([{
            a : 1,
            b : [1, 4, 3],
            c : 'hello'
        }], [{
            a : 1,
            b : [1, 2, 3],
            c : 'hello'
        }]));
});


/**
 * isStringFound
 */
QUnit.test("isStringFound", function(assert) {
    
    // Test empty values
    let emptyValues = [null, undefined, 0, {}, '', '    ', "\n\n\n\n"];
    
    for(const element of emptyValues){
       
        assert.throws(function() {
            ArrayUtils.isStringFound(element, '');
        }, /parameter must be an array/);
        
        if(StringUtils.isString(element)){
            
            assert.notOk(ArrayUtils.isStringFound([], element));
        
        }else{
            
            assert.throws(function() {
                ArrayUtils.isStringFound([], element);
            }, /str is not a string/);
        }
    }

    // Test ok values
    assert.ok(ArrayUtils.isStringFound([''], ''));
    assert.ok(ArrayUtils.isStringFound(['hello'], ''));
    assert.ok(ArrayUtils.isStringFound(['hello'], 'hello'));
    assert.ok(ArrayUtils.isStringFound(['hello world'], 'hello'));
    assert.ok(ArrayUtils.isStringFound(['', 'hello world'], 'hello'));
    assert.ok(ArrayUtils.isStringFound(['string', 1000, 'hello world'], 'hello'));
    assert.ok(ArrayUtils.isStringFound(['string', 1000, [1,2,3], 'hello world'], 'hello'));
    assert.ok(ArrayUtils.isStringFound(['string', {a: 129, b: 'string2'}, [1,2,3], 'hello world'], 'world'));
    assert.ok(ArrayUtils.isStringFound(['string', {a: 129, b: 'string2'}, [1,2,3], 'hello world'], 'string2'));
    assert.ok(ArrayUtils.isStringFound(['string', 1000, [1,2,'found'], 'hello world'], 'found'));
    assert.ok(ArrayUtils.isStringFound(['string', 1000, [1,{a: 10, b: 'found'},3], 'hello world'], 'found'));
    assert.ok(ArrayUtils.isStringFound([1, 1000, [1,{a: 10, b: 'hello'},3], 'string'], 'string'));
    
    assert.notOk(ArrayUtils.isStringFound([''], ' '));
    assert.notOk(ArrayUtils.isStringFound([1234], ''));
    assert.notOk(ArrayUtils.isStringFound(['hello'], 'Hello'));
    assert.notOk(ArrayUtils.isStringFound(['hello world'], 'Hello'));
    assert.notOk(ArrayUtils.isStringFound(['', 'hello world'], 'Hello'));
    assert.notOk(ArrayUtils.isStringFound(['string', 1000, 'hello world'], 'Hello'));
    assert.notOk(ArrayUtils.isStringFound(['string', 1000, [1,2,3], 'hello world'], 'Hello'));
    assert.notOk(ArrayUtils.isStringFound(['string', 1000, [1,2,3], 'hello world'], 'notfound'));
    
    assert.ok(ArrayUtils.isStringFound([''], '', false));
    assert.ok(ArrayUtils.isStringFound(['hello'], 'hello', false));
    assert.ok(ArrayUtils.isStringFound(['hello world'], 'hello', false));
    assert.ok(ArrayUtils.isStringFound(['', 'hello world'], 'hello', false));
    assert.ok(ArrayUtils.isStringFound(['string', 1000, 'hello world'], 'hello', false));
    assert.ok(ArrayUtils.isStringFound(['string', 1000, [1,2,3], 'hello world'], 'hello', false));
    assert.notOk(ArrayUtils.isStringFound([''], ' ', false));
    assert.ok(ArrayUtils.isStringFound(['hello'], 'Hello', false));
    assert.ok(ArrayUtils.isStringFound(['hello world'], 'Hello', false));
    assert.ok(ArrayUtils.isStringFound(['', 'hello world'], 'Hello', false));
    assert.ok(ArrayUtils.isStringFound(['string', 1000, 'hello world'], 'Hello', false));
    assert.ok(ArrayUtils.isStringFound(['string', 1000, [1,2,3], 'hello world'], 'Hello', false));
    
    // Test wrong values
    // Test exceptions
    assert.throws(function() {
        ArrayUtils.isStringFound(1234, '');
    }, /parameter must be an array/);
    
    assert.throws(function() {
        ArrayUtils.isStringFound({a: ''}, '');
    }, /parameter must be an array/);
    
    assert.throws(function() {
        ArrayUtils.isStringFound([''], 123456);
    }, /str is not a string/);
    
    assert.throws(function() {
        ArrayUtils.isStringFound([''], [1,2]);
    }, /str is not a string/);    
});


/**
 * execute
 */
QUnit.todo("findMostRepeatedValue", function(assert){
    
    // TODO - translate from php
});


/** test */
QUnit.test("forceNonEmptyArray", function(assert) {

    assert.throws(function() { ArrayUtils.forceNonEmptyArray(null); }, /must be a non empty array/);
    assert.throws(function() { ArrayUtils.forceNonEmptyArray(0); }, /must be a non empty array/);
    assert.throws(function() { ArrayUtils.forceNonEmptyArray(''); }, /must be a non empty array/);
    assert.throws(function() { ArrayUtils.forceNonEmptyArray([]); }, /must be a non empty array/);
    assert.throws(function() { ArrayUtils.forceNonEmptyArray('      '); }, /must be a non empty array/);
    assert.throws(function() { ArrayUtils.forceNonEmptyArray("\n\n  \n"); }, /must be a non empty array/);
    assert.throws(function() { ArrayUtils.forceNonEmptyArray("\t   \n     \r\r"); }, /must be a non empty array/);

    ArrayUtils.forceNonEmptyArray(['adsadf']);
    ArrayUtils.forceNonEmptyArray([1,2,3]);
    ArrayUtils.forceNonEmptyArray([null]);

    // Test non string value gives exception
    assert.throws(function() { ArrayUtils.forceNonEmptyArray(123); }, /must be a non empty array/);
});


/**
 * removeElement
 */
QUnit.test("removeElement", function(assert) {

    // Test non array values must launch exception
    assert.throws(function() {

        ArrayUtils.removeElement(null, null);
    });

    assert.throws(function() {

        ArrayUtils.removeElement(1, 1);
    });

    assert.throws(function() {

        ArrayUtils.removeElement("asfasf1", "345345");
    });

    // Test several arrays
    assert.ok(ArrayUtils.isEqualTo(ArrayUtils.removeElement([], null), []));
    assert.ok(ArrayUtils.isEqualTo(ArrayUtils.removeElement([], 1), []));
    assert.ok(ArrayUtils.isEqualTo(ArrayUtils.removeElement([1], 1), []));
    assert.ok(ArrayUtils.isEqualTo(ArrayUtils.removeElement(["1"], 1), ["1"]));
    assert.ok(ArrayUtils.isEqualTo(ArrayUtils.removeElement(["1"], "1"), []));
    assert.ok(ArrayUtils.isEqualTo(ArrayUtils.removeElement([1, 2, 3, 4], 1), [2, 3, 4]));
    assert.ok(ArrayUtils.isEqualTo(ArrayUtils.removeElement([1, 2, 3, 4], 8), [1, 2, 3, 4]));
    assert.ok(ArrayUtils.isEqualTo(ArrayUtils.removeElement(["hello", "guys"], "guys"), ["hello"]));
    assert.ok(ArrayUtils.isEqualTo(ArrayUtils.removeElement(["hello", 1, ["test"]], 1), ["hello", ["test"]]));
    assert.ok(ArrayUtils.isEqualTo(ArrayUtils.removeElement(["hello", 1, ["test"]], ["test"]), ["hello", 1]));
    assert.ok(ArrayUtils.isEqualTo(ArrayUtils.removeElement(["hello", 1, ["test", "array"], ["test"]], ["test", "array"]), ["hello", 1, ["test"]]));

});


/**
 * removeDuplicateElements
 */
QUnit.test("removeDuplicateElements", function(assert) {
    
    // Test empty values
    assert.throws(function() {        
        ArrayUtils.removeDuplicateElements(null);
    });

    assert.throws(function() {
        ArrayUtils.removeDuplicateElements('');
    });

    assert.throws(function() {
        ArrayUtils.removeDuplicateElements({});
    });

    assert.ok(ArrayUtils.isEqualTo([], ArrayUtils.removeDuplicateElements([])));
    assert.ok(ArrayUtils.isEqualTo([null], ArrayUtils.removeDuplicateElements([null])));
    assert.ok(ArrayUtils.isEqualTo([null], ArrayUtils.removeDuplicateElements([null, null])));

    // Test ok values
    assert.ok(ArrayUtils.isEqualTo([1], ArrayUtils.removeDuplicateElements([1, 1])));
    assert.ok(ArrayUtils.isEqualTo(['1'], ArrayUtils.removeDuplicateElements(['1', '1'])));
    assert.ok(ArrayUtils.isEqualTo([1, 0], ArrayUtils.removeDuplicateElements([1, 0, 1])));
    assert.ok(ArrayUtils.isEqualTo(['1', '0'], ArrayUtils.removeDuplicateElements(['1', '0', '1'])));
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3, 4], ArrayUtils.removeDuplicateElements([1, 2, 3, 4, 2])));
    assert.ok(ArrayUtils.isEqualTo(['hello', 'go'], ArrayUtils.removeDuplicateElements(['hello', 'go', 'hello'])));
    assert.ok(ArrayUtils.isEqualTo([new Error(), 'go', 'hello'], ArrayUtils.removeDuplicateElements([new Error(), 'go', 'hello', new Error()])));

    // Test wrong values
    assert.ok(ArrayUtils.isEqualTo([1], ArrayUtils.removeDuplicateElements([1])));
    assert.ok(ArrayUtils.isEqualTo([1, 2], ArrayUtils.removeDuplicateElements([1, 2])));
    assert.ok(ArrayUtils.isEqualTo(['1', '2'], ArrayUtils.removeDuplicateElements(['1', '2'])));
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3, 4, 5, 6], ArrayUtils.removeDuplicateElements([1, 2, 3, 4, 5, 6])));
    assert.ok(ArrayUtils.isEqualTo(['1', 1], ArrayUtils.removeDuplicateElements(['1', 1])));
    assert.ok(ArrayUtils.isEqualTo([new Error(), 'go', 'hello'], ArrayUtils.removeDuplicateElements([new Error(), 'go', 'hello'])));

    // Test exceptions
    // Already tested with empty values
});


/**
 * hasDuplicateElements
 */
QUnit.test("hasDuplicateElements", function(assert) {
    
    // Test empty values
    assert.throws(function() {
        ArrayUtils.hasDuplicateElements(null);
    });

    assert.throws(function() {
        ArrayUtils.hasDuplicateElements('');
    });

    assert.throws(function() {
        ArrayUtils.hasDuplicateElements({});
    });

    assert.notOk(ArrayUtils.hasDuplicateElements([]));
    assert.notOk(ArrayUtils.hasDuplicateElements([null]));

    // Test ok values
    assert.ok(ArrayUtils.hasDuplicateElements([1, 1]));
    assert.ok(ArrayUtils.hasDuplicateElements(['1', '1']));
    assert.ok(ArrayUtils.hasDuplicateElements([1, 0, 1]));
    assert.ok(ArrayUtils.hasDuplicateElements(['1', '0', '1']));
    assert.ok(ArrayUtils.hasDuplicateElements([1, 2, 3, 4, 2]));
    assert.ok(ArrayUtils.hasDuplicateElements(['hello', 'go', 'hello']));
    assert.ok(ArrayUtils.hasDuplicateElements([new Error(), 'go', 'hello', new Error()]));

    var array = [];

    for (var i = 0; i < 100; i++) {

        for (var j = 0; j < 100; j++) {

            array.push(j);
        }

        array.push(i);

        assert.ok(ArrayUtils.hasDuplicateElements(array));
    }

    // Test wrong values
    assert.notOk(ArrayUtils.hasDuplicateElements([1]));
    assert.notOk(ArrayUtils.hasDuplicateElements([1, 2]));
    assert.notOk(ArrayUtils.hasDuplicateElements(['1', '2']));
    assert.notOk(ArrayUtils.hasDuplicateElements([1, 2, 3, 4, 5, 6]));
    assert.notOk(ArrayUtils.hasDuplicateElements(['1', 1]));
    assert.notOk(ArrayUtils.hasDuplicateElements([new Error(), 'go', 'hello']));

    // Test exceptions
    // Already tested with empty values
});


/**
 * getDuplicateElements
 */
QUnit.test("getDuplicateElements", function(assert) {
    
    // Test empty values
    assert.throws(function() {
        ArrayUtils.getDuplicateElements(null);
    });

    assert.throws(function() {
        ArrayUtils.getDuplicateElements('');
    });

    assert.throws(function() {
        ArrayUtils.getDuplicateElements({});
    });

    assert.ok(ArrayUtils.isEqualTo([], ArrayUtils.getDuplicateElements([])));
    assert.ok(ArrayUtils.isEqualTo([], ArrayUtils.getDuplicateElements([null])));

    // Test ok values
    assert.ok(ArrayUtils.isEqualTo([1], ArrayUtils.getDuplicateElements([1, 1])));
    assert.ok(ArrayUtils.isEqualTo(['1'], ArrayUtils.getDuplicateElements(['1', '1'])));
    assert.ok(ArrayUtils.isEqualTo([1], ArrayUtils.getDuplicateElements([1, 0, 1])));
    assert.ok(ArrayUtils.isEqualTo(['1'], ArrayUtils.getDuplicateElements(['1', '0', '1'])));
    assert.ok(ArrayUtils.isEqualTo([2], ArrayUtils.getDuplicateElements([1, 2, 3, 4, 2])));
    assert.ok(ArrayUtils.isEqualTo([2, 3], ArrayUtils.getDuplicateElements([1, 2, 3, 4, 2, 3, 3, 3])));
    assert.ok(ArrayUtils.isEqualTo(['hello'], ArrayUtils.getDuplicateElements(['hello', 'go', 'hello'])));
    assert.ok(ArrayUtils.isEqualTo([new Error()], ArrayUtils.getDuplicateElements([new Error(), 'go', 'hello', new Error()])));

    // Test wrong values
    assert.ok(ArrayUtils.isEqualTo([], ArrayUtils.getDuplicateElements([1])));
    assert.ok(ArrayUtils.isEqualTo([], ArrayUtils.getDuplicateElements([1, 2])));
    assert.ok(ArrayUtils.isEqualTo([], ArrayUtils.getDuplicateElements(['1', '2'])));
    assert.ok(ArrayUtils.isEqualTo([], ArrayUtils.getDuplicateElements([1, 2, 3, 4, 5, 6])));
    assert.ok(ArrayUtils.isEqualTo([], ArrayUtils.getDuplicateElements(['1', 1])));
    assert.ok(ArrayUtils.isEqualTo([], ArrayUtils.getDuplicateElements([new Error(), 'go', 'hello'])));

    // Test exceptions
    // Already tested with empty values
});