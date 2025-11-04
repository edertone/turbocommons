"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

QUnit.module("ObjectUtilsTest", {
    beforeEach : function() {

        window.ArrayUtils = org_turbocommons.ArrayUtils;
        window.ObjectUtils = org_turbocommons.ObjectUtils;
        window.StringUtils = org_turbocommons.StringUtils;
    },

    afterEach : function() {

        delete window.ArrayUtils;
        delete window.ObjectUtils;
        delete window.StringUtils;
    }
});


/**
 * isObject
 */
QUnit.test("isObject", function(assert) {

    // test empty values
    assert.ok(!ObjectUtils.isObject(null));
    assert.ok(!ObjectUtils.isObject(undefined));
    assert.ok(!ObjectUtils.isObject(''));
    assert.ok(!ObjectUtils.isObject([]));
    assert.ok(!ObjectUtils.isObject(0));
    assert.ok(ObjectUtils.isObject({}));

    // Test valid values
    assert.ok(ObjectUtils.isObject(new Error()));
    assert.ok(ObjectUtils.isObject({
        1 : 1
    }));
    assert.ok(ObjectUtils.isObject({
        a : 'hello'
    }));
    assert.ok(ObjectUtils.isObject({
        a : 1,
        b : 2,
        c : 3
    }));

    // Test invalid values
    assert.ok(!ObjectUtils.isObject(874));
    assert.ok(!ObjectUtils.isObject('hello'));
    assert.ok(!ObjectUtils.isObject([123]));
    assert.ok(!ObjectUtils.isObject([1, 'aaa']));
    assert.ok(!ObjectUtils.isObject(/someregex.*/));
});


/**
 * getKeys
 */
QUnit.test("getKeys", function(assert) {

    assert.ok(ArrayUtils.isEqualTo(ObjectUtils.getKeys({}), []));
    assert.ok(ArrayUtils.isEqualTo(ObjectUtils.getKeys({
        1 : 1
    }), ['1']));
    assert.ok(ArrayUtils.isEqualTo(ObjectUtils.getKeys({
        a : 1
    }), ['a']));
    assert.ok(ArrayUtils.isEqualTo(ObjectUtils.getKeys({
        a : 1,
        b : 2,
        c : 3
    }), ['a', 'b', 'c']));
    assert.ok(ArrayUtils.isEqualTo(ObjectUtils.getKeys({
        a : 1,
        b : {
            a : 9,
            x : 0
        },
        c : 3
    }), ['a', 'b', 'c']));

    // Test exceptions
    assert.throws(function() {

        ObjectUtils.getKeys(undefined);
    });

    assert.throws(function() {

        ObjectUtils.getKeys(null);
    });


    assert.throws(function() {

        ObjectUtils.getKeys([]);
    });

    assert.throws(function() {

        ObjectUtils.getKeys([1, 2, 3]);
    });
});


/**
 * isEqualTo
 */
QUnit.test("isEqualTo", function(assert) {

    // Test identic values
    assert.ok(ObjectUtils.isEqualTo({}, {}));
    assert.ok(ObjectUtils.isEqualTo({
        hello : 'home'
    }, {
        hello : 'home'
    }));
    assert.ok(ObjectUtils.isEqualTo({
        1 : 1
    }, {
        1 : 1
    }));
    assert.ok(ObjectUtils.isEqualTo({
        hello : 'home',
        number : 1
    }, {
        hello : 'home',
        number : 1
    }));
    assert.ok(ObjectUtils.isEqualTo({
        hello : 'home',
        number : 1,
        array : [1, 2, 3]
    }, {
        hello : 'home',
        number : 1,
        array : [1, 2, 3]
    }));
    assert.ok(ObjectUtils.isEqualTo({
        hello : 'home',
        array : {
            hello : 'home',
            number : 1
        }
    }, {
        hello : 'home',
        array : {
            hello : 'home',
            number : 1
        }
    }));
    
    // Test same values but with different key order
    assert.ok(ObjectUtils.isEqualTo({
        number : 1,
        hello : 'home',
        array : [1, 2, 3]
    }, {
        hello : 'home',
        number : 1,
        array : [1, 2, 3]
    }));
    assert.ok(ObjectUtils.isEqualTo({
        hello : 'home',
        array : {
            hello : 'home',
            number : 1
        }
    }, {
        array : {
            number : 1,
            hello : 'home'
        },
        hello : 'home'
    }));

    // Test different values
    assert.ok(!ObjectUtils.isEqualTo({}, {
            1 : 1
        }));
    assert.ok(!ObjectUtils.isEqualTo({
            1 : 1
        }, {
            1 : 2
        }));
    assert.ok(!ObjectUtils.isEqualTo({
            hello : 'guys'
        }, {
            1 : 2
        }));
    assert.ok(!ObjectUtils.isEqualTo({
            hello : 'guys'
        }, {
            hell : 'guys'
        }));
    assert.ok(!ObjectUtils.isEqualTo({
            hello : 'home',
            number : 1,
            array : [1, 3]
        }, {
            hello : 'home',
            number : 1,
            array : [1, 2, 3]
        }));

    // Test exceptions with non objects
    assert.throws(function() {

        ObjectUtils.isEqualTo(undefined, undefined);
    });

    assert.throws(function() {

        ObjectUtils.isEqualTo(null, null);
    });

    assert.throws(function() {

        ObjectUtils.isEqualTo([], []);
    });

    assert.throws(function() {

        ObjectUtils.isEqualTo("hello", "hello");
    });
});


/**
 * isStringFound
 */
QUnit.test("isStringFound", function(assert) {
    
    // Test empty values
    let emptyValues = [null, undefined, 0, [], '', '    ', "\n\n\n\n"];
    
    for(let i = 0; i < emptyValues.length; i++){
       
        assert.throws(function() {
            ObjectUtils.isStringFound(emptyValues[i], '');
        }, /parameter must be an object/);
        
        if(StringUtils.isString(emptyValues[i])){
            
            assert.notOk(ObjectUtils.isStringFound({}, emptyValues[i]));
        
        }else{
            
            assert.throws(function() {
                ObjectUtils.isStringFound({}, emptyValues[i]);
            }, /str is not a string/);
        }
    }

    // Test ok values
    assert.ok(ObjectUtils.isStringFound({a: ''}, ''));
    assert.ok(ObjectUtils.isStringFound({a: 'hello'}, ''));
    assert.ok(ObjectUtils.isStringFound({a: 'hello'}, 'hello'));
    assert.ok(ObjectUtils.isStringFound({a: 'hello world'}, 'hello'));
    assert.ok(ObjectUtils.isStringFound({a: '', b: 'hello world'}, 'hello'));
    assert.ok(ObjectUtils.isStringFound({a: 'string', b: 1000, c: 'hello world'}, 'hello'));
    assert.ok(ObjectUtils.isStringFound({a: 'string', b: 1000, c: [1,2,3], d: 'hello world'}, 'hello'));
    assert.ok(ObjectUtils.isStringFound({a: 'string', b: {a: 129, b: 'string2'}, c: [1,2,3], d: 'hello world'}, 'world'));
    assert.ok(ObjectUtils.isStringFound({a: 'string', b: {a: 129, b: 'string2'}, c: [1,2,3], d: 'hello world'}, 'string2'));
    assert.ok(ObjectUtils.isStringFound({a: 'string', b: 1000, c: [1,2,'found'], d: 'hello world'}, 'found'));
    assert.ok(ObjectUtils.isStringFound({a: 'string', b: 1000, c: [1,{a: 10, b: 'found'},3], d: 'hello world'}, 'found'));
    assert.ok(ObjectUtils.isStringFound({a: 1, b: 1000, c: [1,{a: 10, b: 'hello'},3], d: 'string'}, 'string'));
    
    assert.notOk(ObjectUtils.isStringFound({a: ''}, ' '));
    assert.notOk(ObjectUtils.isStringFound({a: 1234}, ''));
    assert.notOk(ObjectUtils.isStringFound({a: 'hello'}, 'Hello'));
    assert.notOk(ObjectUtils.isStringFound({a: 'hello world'}, 'Hello'));
    assert.notOk(ObjectUtils.isStringFound({a: '', b: 'hello world'}, 'Hello'));
    assert.notOk(ObjectUtils.isStringFound({a: 'string', b: 1000, c: 'hello world'}, 'Hello'));
    assert.notOk(ObjectUtils.isStringFound({a: 'string', b: 1000, c: [1,2,3], d: 'hello world'}, 'Hello'));
    assert.notOk(ObjectUtils.isStringFound({a: 'string', b: 1000, c: [1,2,3], d: 'hello world'}, 'notfound'));
    
    assert.ok(ObjectUtils.isStringFound({a: ''}, '', false));
    assert.ok(ObjectUtils.isStringFound({a: 'hello'}, 'hello', false));
    assert.ok(ObjectUtils.isStringFound({a: 'hello world'}, 'hello', false));
    assert.ok(ObjectUtils.isStringFound({a: '', b: 'hello world'}, 'hello', false));
    assert.ok(ObjectUtils.isStringFound({a: 'string', b: 1000, c: 'hello world'}, 'hello', false));
    assert.ok(ObjectUtils.isStringFound({a: 'string', b: 1000, c: [1,2,3], d: 'hello world'}, 'hello', false));
    assert.notOk(ObjectUtils.isStringFound({a: ''}, ' ', false));
    assert.ok(ObjectUtils.isStringFound({a: 'hello'}, 'Hello', false));
    assert.ok(ObjectUtils.isStringFound({a: 'hello world'}, 'Hello', false));
    assert.ok(ObjectUtils.isStringFound({a: '', b: 'hello world'}, 'Hello', false));
    assert.ok(ObjectUtils.isStringFound({a: 'string', b: 1000, c: 'hello world'}, 'Hello', false));
    assert.ok(ObjectUtils.isStringFound({a: 'string', b: 1000, c: [1,2,3], d: 'hello world'}, 'Hello', false));
    
    // Test wrong values
    // Test exceptions
    assert.throws(function() {
        ObjectUtils.isStringFound(1234, '');
    }, /parameter must be an object/);
    
    assert.throws(function() {
        ObjectUtils.isStringFound([1,2,3], '');
    }, /parameter must be an object/);
    
    assert.throws(function() {
        ObjectUtils.isStringFound({a: ''}, 123456);
    }, /str is not a string/);
    
    assert.throws(function() {
        ObjectUtils.isStringFound({a: ''}, [1,2]);
    }, /str is not a string/);
});
   

/**
 * merge
 */
QUnit.test("merge", function(assert) {
    
    // Test empty values
    let emptyValues = [null, undefined, 0, [], '', '    ', "\n\n\n\n"];
    
    for(let i = 0; i < emptyValues.length; i++){
       
        assert.throws(function() {

            ObjectUtils.merge(emptyValues[i], {});
        }, /destination and source must objects/);
        
        assert.throws(function() {

            ObjectUtils.merge({}, emptyValues[i]);
        }, /destination and source must objects/);
    }
    
    assert.ok(ObjectUtils.isEqualTo(ObjectUtils.merge({}, {}), {}));
    
    // Test ok values
    let destination = {a:1, b:2};
    let source = {a:2};
    assert.ok(ObjectUtils.isEqualTo(ObjectUtils.merge(destination, source), {a:2, b:2}));
    assert.ok(ObjectUtils.isEqualTo(destination, {a:2, b:2}));
    
    destination = {a:1, b:2, c:{c1: 1, c2: 2}};
    source = {a:2, c: 1};
    assert.ok(ObjectUtils.isEqualTo(ObjectUtils.merge(destination, source), {a:2, b:2, c:1}));
    assert.ok(ObjectUtils.isEqualTo(destination, {a:2, b:2, c:1}));
    
    destination = {a:1, b:2, c:{c1: 1, c2: 2}};
    source = {a:2, c:{c1: 1, c2: 3}};
    assert.ok(ObjectUtils.isEqualTo(ObjectUtils.merge(destination, source), {a:2, b:2, c:{c1: 1, c2: 3}}));
    assert.ok(ObjectUtils.isEqualTo(destination, {a:2, b:2, c:{c1: 1, c2: 3}}));
    
    destination = {a:1, b:{c:2, d:3, e:{f:4, g:6}}};
    source = {b:{e:{f:5}}, h:9};
    assert.ok(ObjectUtils.isEqualTo(ObjectUtils.merge(destination, source), {a:1, b:{c:2, d:3, e:{f:5, g:6}}, h:9}));
    assert.ok(ObjectUtils.isEqualTo(destination, {a:1, b:{c:2, d:3, e:{f:5, g:6}}, h:9}));
    
    destination = {a:1, b:"hello", c:[1, 2, 3]};
    source = {a:2, b:"goodbye", c:[4, 5, 6], d:8};
    assert.ok(ObjectUtils.isEqualTo(ObjectUtils.merge(destination, source), {a:2, b:"goodbye", c:[4, 5, 6], d:8}));
    assert.ok(ObjectUtils.isEqualTo(destination, {a:2, b:"goodbye", c:[4, 5, 6], d:8}));
    
    destination = {a:1, c:"hello", e:[1, 2, 3]};
    source = {b:2, d:["a", "b"]};
    assert.ok(ObjectUtils.isEqualTo(ObjectUtils.merge(destination, source), {a:1, b:2, c:"hello", d:["a", "b"], e:[1, 2, 3]}));
    assert.ok(ObjectUtils.isEqualTo(destination, {a:1, b:2, c:"hello", d:["a", "b"], e:[1, 2, 3]}));
    
    destination = {a:1, b:{c:"hello", d:{e:[1,2]}}};
    source = {b:{a:"go", d:1}, d:{a:1}};
    assert.ok(ObjectUtils.isEqualTo(ObjectUtils.merge(destination, source), {a:1, b:{a:"go", c:"hello", d:1}, d:{a:1}}));
    assert.ok(ObjectUtils.isEqualTo(destination, {a:1, b:{a:"go", c:"hello", d:1}, d:{a:1}}));
        
    // Test wrong values
    // not necessary

    // Test exceptions
    assert.throws(function() {

        ObjectUtils.merge({}, 12);
    }, /destination and source must objects/);
    
    assert.throws(function() {

        ObjectUtils.merge([12], {});
    }, /destination and source must objects/);
    
    assert.throws(function() {

        ObjectUtils.merge("hello", {});
    }, /destination and source must objects/);
    
    assert.throws(function() {

        ObjectUtils.merge({}, "bye");
    }, /destination and source must objects/);
});


/**
 * clone
 */
QUnit.test("clone", function(assert) {
    
    // Test empty values
    assert.strictEqual(ObjectUtils.clone(null), null);
    assert.strictEqual(ObjectUtils.clone(undefined), undefined);
    assert.strictEqual(ObjectUtils.clone(0), 0);
    assert.strictEqual(ObjectUtils.clone(''), '');
    assert.ok(ArrayUtils.isEqualTo(ObjectUtils.clone([]), []));
    assert.ok(ObjectUtils.isEqualTo(ObjectUtils.clone({}), {}));
    assert.strictEqual(ObjectUtils.clone('    '), '    ');

    // Test ok values. Verify modified clones do not affect original one
    let value = -145;    
    let clonedValue = ObjectUtils.clone(value);    
    assert.strictEqual(clonedValue, value);
    clonedValue = clonedValue + 100;
    assert.strictEqual(clonedValue, -45);
    assert.strictEqual(value, -145);
    
    value = 'hello';    
    clonedValue = ObjectUtils.clone(value);    
    assert.strictEqual(clonedValue, value);
    assert.strictEqual(value, 'hello');
    
    value = [1,2,3,4,5];    
    clonedValue = ObjectUtils.clone(value);
    assert.ok(ArrayUtils.isEqualTo(clonedValue, value));
    clonedValue.push(6);
    assert.ok(ArrayUtils.isEqualTo(value, [1,2,3,4,5]));
    
    value = [1,2,3,{a:1, b:2, c:{d:1}},5];
    clonedValue = ObjectUtils.clone(value);
    assert.ok(ArrayUtils.isEqualTo(clonedValue, value));
    clonedValue[3].a = 5;
    clonedValue[3].c.d = 6;
    assert.ok(ArrayUtils.isEqualTo(clonedValue, [1,2,3,{a:5, b:2, c:{d:6}},5]));
    assert.ok(ArrayUtils.isEqualTo(value, [1,2,3,{a:1, b:2, c:{d:1}},5]));
    
    value = {a:1, b:2, c:[3,4,5,{d:6,e:{f:7}}]};
    clonedValue = ObjectUtils.clone(value);
    assert.ok(ObjectUtils.isEqualTo(clonedValue, value));
    clonedValue.a = 5;
    clonedValue.c[0] = 9;
    clonedValue.c[3].e = null;
    assert.ok(ObjectUtils.isEqualTo(clonedValue, {a:5, b:2, c:[9,4,5,{d:6,e:null}]}));
    assert.ok(ObjectUtils.isEqualTo(value, {a:1, b:2, c:[3,4,5,{d:6,e:{f:7}}]}));
    
    // Test an object containing references to other objects
    let reference = {ref:1};
    value = {a:1, b:reference};
    clonedValue = ObjectUtils.clone(value);
    assert.ok(ObjectUtils.isEqualTo(clonedValue, value));
    reference.ref = 2;
    assert.ok(ObjectUtils.isEqualTo(clonedValue, {a:1, b:{ref:1}}));
    assert.ok(ObjectUtils.isEqualTo(value, {a:1, b:{ref:2}}));
    
    // Test an object containing a function
    value = {a:1, b:function(a) { return a + 2 }};
    clonedValue = ObjectUtils.clone(value);
    assert.ok(ObjectUtils.isEqualTo(clonedValue, value));
    
    assert.strictEqual(value.a, 1);
    assert.strictEqual(value.b(4), 6);
    assert.strictEqual(clonedValue.a, 1);
    assert.strictEqual(clonedValue.b(4), 6);
    assert.strictEqual(clonedValue.b(6), 8);
    
    // Test an object containing a regex
    value = {a:1, b:/someregex.*/};
    clonedValue = ObjectUtils.clone(value);
    assert.ok(ObjectUtils.isEqualTo(clonedValue, value));
    
    assert.strictEqual(value.a, 1);
    assert.strictEqual(value.b.toString(), /someregex.*/.toString());
    assert.strictEqual(clonedValue.a, 1);
    assert.strictEqual(clonedValue.b.toString(), /someregex.*/.toString());
    
    // Test an object containing an associative array
    value = {"a": 1, "b": {"key1": 1, "key2": "value"}};
    clonedValue = ObjectUtils.clone(value);
    assert.ok(ObjectUtils.isEqualTo(clonedValue, value));

    // Test wrong values
    // not necessary

    // Test exceptions
    // no exceptions are thrown by this method
});


/**
 * apply
 */
QUnit.test("apply", function(assert) {

    // Test empty values
    assert.throws(function() { ObjectUtils.apply(null, null); }, /callableFunction is not a function/);
    assert.throws(function() { ObjectUtils.apply(0, 0); }, /callableFunction is not a function/);
    assert.throws(function() { ObjectUtils.apply('', ''); }, /callableFunction is not a function/);
    assert.throws(function() { ObjectUtils.apply('    ', '     '); }, /callableFunction is not a function/);
    assert.throws(function() { ObjectUtils.apply(undefined, undefined); }, /callableFunction is not a function/);
    assert.strictEqual(ObjectUtils.apply(null, function (v) { return v;}), null);
    assert.strictEqual(ObjectUtils.apply(0, function (v) { return v;}), 0);
    assert.strictEqual(ObjectUtils.apply("", function (v) { return v;}), "");
    assert.ok(ArrayUtils.isEqualTo(ObjectUtils.apply([], function (v) { return v;}), []));

    // Test ok values
    let value = 145;
    assert.strictEqual(value, ObjectUtils.apply(value, function(v) { return v; }));

    value = 145;
    assert.strictEqual(146, ObjectUtils.apply(value, function(v) { return v + 1; }));

    value = "abcde";
    assert.strictEqual(value, ObjectUtils.apply(value, function(v) { return v; }));

    value = "abcde";
    assert.strictEqual('abcdef', ObjectUtils.apply(value, function(v) { return v + 'f'; }));

    value = [1, 2, 3, 4];
    assert.ok(ArrayUtils.isEqualTo(value, ObjectUtils.apply(value, function(v) { return v; })));

    value = [1, 2, 3, 4];
    assert.ok(ArrayUtils.isEqualTo([2, 3, 4, 5], ObjectUtils.apply(value, function(v) { return v + 1; })));

    value = [1, [1, 2, [4, 5]], 4];
    assert.ok(ArrayUtils.isEqualTo([1, [1, 2, [4, 5]], 4], ObjectUtils.apply(value, function(v) { return v; })));

    value = [1, [1, 2, [4, 5]], 4];
    assert.ok(ArrayUtils.isEqualTo([2, [2, 3, [5, 6]], 5], ObjectUtils.apply(value, function(v) { return v + 1; })));

    value = [1, [1, "a", [4, "b"]], 4];
    let applied = ObjectUtils.apply(value, function(v) { return StringUtils.isString(v) ? v + 'c' : v+1; });
    assert.ok(ArrayUtils.isEqualTo([2, [2, "ac", [5, "bc"]], 5], applied));

    value = {"a": 1, "b": [1,2,"a"]};
    applied = ObjectUtils.apply(value, function(v) { return StringUtils.isString(v) ? v + 'c' : v; });
    assert.ok(ObjectUtils.isEqualTo(applied, {"a": 1, "b": [1,2,"ac"]}));

    // Test wrong values
    // Test exceptions
    // not necessary    
});