"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


QUnit.module("HashMapObjectTest", {
    beforeEach : function(){
        
        window.ArrayUtils = org_turbocommons.ArrayUtils;
        window.ObjectUtils = org_turbocommons.ObjectUtils;
        window.HashMapObject = org_turbocommons.HashMapObject;
        
        window.emptyValues = [null, '', [], {}, '     ', "\n\n\n", 0];
        window.emptyValuesCount = window.emptyValues.length;
        
        window.populatedHashMap = new HashMapObject();
        window.populatedHashMap.set('a', 1);
        window.populatedHashMap.set('b', 2);
        window.populatedHashMap.set('c', 3);
        window.populatedHashMap.set('d', 4);
        window.populatedHashMap.set('e', 5);
        window.populatedHashMap.set('f', 6);
        window.populatedHashMap.set('g', 7);
        window.populatedHashMap.set('string', 'myValue');
        window.populatedHashMap.set('array', [1, 2, 3, 4]);
    },

    afterEach : function(){
        
        delete window.ArrayUtils;
        delete window.ObjectUtils;
        delete window.HashMapObject;
        
        delete window.emptyValues;
        delete window.emptyValuesCount;
        delete window.populatedHashMap;
    }
});


/**
 * testConstruct
 */
QUnit.test("testConstruct", function(assert){

    // Test empty values
    var test = new HashMapObject();
    assert.ok(test.length() === 0);
    
    test = new HashMapObject(null);
    assert.ok(test.length() === 0);
    
    test = new HashMapObject([]);
    assert.ok(test.length() === 0);
    
    var exceptionEmptyValues = ['', '     ', "\n\n\n", 0];
    var exceptionEmptyValuesCount = exceptionEmptyValues.length;
    
    for (var i = 0; i < exceptionEmptyValuesCount; i++) {
    
        assert.throws(function() {
            test = new HashMapObject(exceptionEmptyValues[i]);
        });
    }
    
    // Test ok values
    test = new HashMapObject(['a']);
    assert.ok(test.length() === 1);
    assert.ok(test.get('0') === 'a');
    
    test = new HashMapObject({a: 1});
    assert.ok(test.length() === 1);
    assert.ok(test.get('a') === 1);
    
    test = new HashMapObject([1, 2, 3]);
    assert.ok(test.length() === 3);
    assert.ok(test.get('0') === 1);
    assert.ok(test.get('2') === 3);
    
    test = new HashMapObject(['a', 'b', 'c', 'd', 'e', 'f']);
    assert.ok(test.length() === 6);
    assert.ok(test.get('0') === 'a');
    assert.ok(test.get('3') === 'd');
    assert.ok(test.get('5') === 'f');
    
    test = new HashMapObject({1: 'a', 2: 'b', 3: 'c', 4: 'd', 5: 'e', 6: 'f'});
    assert.ok(test.length() === 6);
    assert.ok(test.get('1') === 'a');
    assert.ok(test.get('4') === 'd');
    assert.ok(test.get('6') === 'f');
    
    test = new HashMapObject({1: 'a', 2: 'b', 3: 'c', 4: 'd', 5: 'e', 6: 'f'});
    assert.ok(test.length() === 6);
    assert.ok(test.get('1') === 'a');
    assert.ok(test.get('4') === 'd');
    assert.ok(test.get('6') === 'f');
    
    // Test wrong values
    // Tested with exceptions
    
    // Test exceptions
    var exceptionValues = [123, 'hello', -1, 0.23];
    var exceptionValuesCount = exceptionValues.length;
    
    for (i = 0; i < exceptionValuesCount; i++) {
    
        assert.throws(function() {
            test = new HashMapObject(exceptionValues[i]);
        });
    }
});


/**
 * testSet
 */
QUnit.test("testSet", function(assert){
    
    var h = new HashMapObject();

    // Test empty values
    for (var i = 0; i < window.emptyValuesCount; i++) {

        assert.throws(function() {
            h.set(window.emptyValues[i], null);
        });
    }

    // Test ok values
    assert.ok(h.set('a', null) === null);
    assert.ok(h.set('b', 1) === 1);
    assert.ok(h.length() === 2);
    assert.ok(h.set('c', '2') === '2');
    assert.ok(ArrayUtils.isEqualTo(h.set('d', [3]), [3]));
    assert.ok(h.length() === 4);
    assert.ok(ObjectUtils.isEqualTo(h.set('e', new HashMapObject()), new HashMapObject()));
    assert.ok(h.length() === 5);
    assert.ok(h.set('d', 4) === 4);
    assert.ok(ArrayUtils.isEqualTo(h.set('d', [6]), [6]));
    assert.ok(h.set('d', '10') === '10');
    assert.ok(h.length() === 5);

    // Test wrong values
    // Nothing to test

    // Test exceptions
    // Already tested on empty values
});


/**
 * testLength
 */
QUnit.test("testLength", function(assert){
    
    var h = new HashMapObject();

    // Test empty values
    // Not necessary

    // Test ok values
    assert.ok(h.length() === 0);
    h.set('a', null);
    assert.ok(h.length() === 1);
    h.set('c', '2');
    assert.ok(h.length() === 2);
    assert.ok(h.length() === 2);
    h.set('d', 4);
    assert.ok(h.length() === 3);

    // Test wrong values
    // Not necessary

    // Test exceptions
    // Not necessary
});


/**
 * testGet
 */
QUnit.test("testGet", function(assert){
    
    // Test empty values
    for (var i = 0; i < window.emptyValuesCount; i++) {

        assert.throws(function() {
            window.populatedHashMap.get(window.emptyValues[i]);
        });
    }

    // Test ok values
    assert.ok(window.populatedHashMap.get('a') === 1);
    assert.ok(window.populatedHashMap.get('c') === 3);
    assert.ok(window.populatedHashMap.get('e') === 5);
    assert.ok(window.populatedHashMap.get('g') === 7);
    assert.ok(window.populatedHashMap.get('string') === 'myValue');
    assert.ok(ArrayUtils.isEqualTo(window.populatedHashMap.get('array'), [1, 2, 3, 4]));

    // Test wrong values
    assert.notOk(window.populatedHashMap.get('a') === 11);
    assert.notOk(window.populatedHashMap.get('c') === 1);
    assert.notOk(window.populatedHashMap.get('e') === 3);
    assert.notOk(window.populatedHashMap.get('g') === 9);
    assert.notOk(window.populatedHashMap.get('string') === '-myValue');
    assert.notOk(ArrayUtils.isEqualTo(window.populatedHashMap.get('array'), [1, 2, 3, 4, 5]));

    // Test exceptions
    assert.throws(function() {
        window.populatedHashMap.get('J');
    });

    assert.throws(function() {
        window.populatedHashMap.get('undefined');
    });
});


/**
 * testGetAt
 */
QUnit.test("testGetAt", function(assert){
 
    // Test empty values
    for (var i = 0; i < window.emptyValuesCount; i++){

        if(window.emptyValues[i] === 0){

            assert.ok(window.populatedHashMap.getAt(0) === 1);

        }else{

            assert.throws(function() {
                window.populatedHashMap.getAt(window.emptyValues[i]);
            });
        }
    }

    assert.throws(function() {
        window.populatedHashMap.getAt('0');
    });

    assert.throws(function() {
        window.populatedHashMap.getAt('00');
    });

    // Test ok values
    assert.ok(window.populatedHashMap.getAt(1) === 2);
    assert.ok(window.populatedHashMap.getAt(2) === 3);
    assert.ok(window.populatedHashMap.getAt(4) === 5);
    assert.ok(window.populatedHashMap.getAt(6) === 7);
    assert.ok(window.populatedHashMap.getAt(7) === 'myValue');
    assert.ok(ArrayUtils.isEqualTo(window.populatedHashMap.getAt(8), [1, 2, 3, 4]));

    // Test wrong values
    assert.notOk(window.populatedHashMap.getAt(8) === 11);
    assert.notOk(window.populatedHashMap.getAt(5) === 1);
    assert.notOk(window.populatedHashMap.getAt(3) === 3);
    assert.notOk(window.populatedHashMap.getAt(2) === 9);
    assert.notOk(window.populatedHashMap.getAt(1) === '-myValue');
    assert.notOk(window.populatedHashMap.getAt(0) === [1, 2, 3, 4, 5]);

    // Test exceptions
    assert.throws(function() {
        window.populatedHashMap.getAt(-1);
    });

    assert.throws(function() {
        window.populatedHashMap.getAt(20);
    });

    assert.throws(function() {
        window.populatedHashMap.getAt(2.1);
    });

    assert.throws(function() {
        window.populatedHashMap.getAt('4');
    });

    assert.throws(function() {
        window.populatedHashMap.getAt('adfa');
    });
});


/**
 * testGetKeys
 */
QUnit.test("testGetKeys", function(assert){
    
    // Test empty values
    // Not necessary

    // Test ok values
    assert.ok(ArrayUtils.isEqualTo(window.populatedHashMap.getKeys(), ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'string', 'array']));

    var h = new HashMapObject();
    h.set('0', 0);
    h.set('00', 0);
    h.set('01', 1);
    h.set('002', 2);
    h.set('a', 'a');
    assert.ok(ArrayUtils.isEqualTo(h.getKeys(), ['0', '00', '01', '002', 'a']));

    // Test wrong values
    assert.notOk(ArrayUtils.isEqualTo(window.populatedHashMap.getKeys(), ['b', 'c', 'd', 'e', 'f', 'g', 'string', 'array']));
    assert.notOk(ArrayUtils.isEqualTo(window.populatedHashMap.getKeys(), ['b', 'a', 'c', 'd', 'e', 'f', 'g', 'string', 'array']));
    assert.notOk(ArrayUtils.isEqualTo(window.populatedHashMap.getKeys(), ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'string']));
    assert.notOk(ArrayUtils.isEqualTo(window.populatedHashMap.getKeys(), ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'string', 'array', null]));

    // Test exceptions
    // Not necessary
});


/**
 * testGetValues
 */
QUnit.test("testGetValues", function(assert){
    
    // Test empty values
    // Not necessary

    // Test ok values
    assert.ok(ArrayUtils.isEqualTo(window.populatedHashMap.getValues(), [1, 2, 3, 4, 5, 6, 7, 'myValue', [1, 2, 3, 4]]));

    var h = new HashMapObject();
    h.set('0', 0);
    h.set('01', 1);
    h.set('002', 2);
    h.set('a', 'a');
    assert.ok(ArrayUtils.isEqualTo([0, 1, 2, 'a'], h.getValues()));

    // Test wrong values
    assert.notOk(ArrayUtils.isEqualTo(window.populatedHashMap.getValues(), [1, 2, 3, 4, 5, 6, 7, 'myValu1e', [1, 2, 3, 4]]));
    assert.notOk(ArrayUtils.isEqualTo(window.populatedHashMap.getValues(), [1, 2, 3, 4, 5, 6, 1, 'myValue', [1, 2, 3, 4]]));
    assert.notOk(ArrayUtils.isEqualTo(window.populatedHashMap.getValues(), [1, 2, 3, 4, 5, 6, 7, 'myValue', [1, 3, 3, 4]]));
    assert.notOk(ArrayUtils.isEqualTo(window.populatedHashMap.getValues(), [1, 1, 2, 3, 4, 5, 6, 7, 'myValue', [1, 2, 3, 4]]));

    // Test exceptions
    // Not necessary   
});


/**
 * testIsKey
 */
QUnit.test("testIsKey", function(assert){
    
    // Test empty values
    for (var i = 0; i < window.emptyValuesCount; i++) {

        assert.notOk(window.populatedHashMap.isKey(window.emptyValues[i]));
    }

    // Test ok values
    assert.ok(window.populatedHashMap.isKey('a'));
    assert.ok(window.populatedHashMap.isKey('b'));
    assert.ok(window.populatedHashMap.isKey('c'));
    assert.ok(window.populatedHashMap.isKey('d'));
    assert.ok(window.populatedHashMap.isKey('e'));
    assert.ok(window.populatedHashMap.isKey('string'));
    assert.ok(window.populatedHashMap.isKey('array'));

    // Test wrong values
    assert.notOk(window.populatedHashMap.isKey('J'));
    assert.notOk(window.populatedHashMap.isKey('Q'));
    assert.notOk(window.populatedHashMap.isKey('1'));
    assert.notOk(window.populatedHashMap.isKey('unknown'));

    // Test exceptions
    // Tested with empty values    
});


/**
 * testRemove
 */
QUnit.test("testRemove", function(assert){
    
    // Test empty values
    for (var i = 0; i < window.emptyValuesCount; i++) {

        assert.throws(function() {
            window.populatedHashMap.remove(window.emptyValues[i]);
        });
    }

    // Test ok values
    var populatedHashMapKeys = window.populatedHashMap.getKeys();
    
    for(i = 0; i < populatedHashMapKeys.length; i++){
        
        var key = populatedHashMapKeys[i];

        var value = window.populatedHashMap.get(key);

        assert.ok(window.populatedHashMap.isKey(key));
        assert.ok(window.populatedHashMap.get(key) !== null);
        assert.ok(window.populatedHashMap.remove(key) === value);
        assert.notOk(window.populatedHashMap.isKey(key));

        assert.throws(function() {
            window.populatedHashMap.remove(key);
        });
        
        assert.throws(function() {
            window.populatedHashMap.get(key);
        });
    }

    assert.ok(window.populatedHashMap.length() === 0);

    // Test wrong values
    assert.throws(function() {
        window.populatedHashMap.remove('J');
    });

    assert.throws(function() {
        window.populatedHashMap.remove('undefinedKey');
    });

    // Test exceptions
    // Tested at empty values
});


/**
 * testRename
 */
QUnit.test("testRename", function(assert){
    
    // Test empty values
    for (var i = 0; i < window.emptyValuesCount; i++) {

        for (var j = 0; j < window.emptyValuesCount; j++) {

            assert.throws(function() {
                window.populatedHashMap.rename(window.emptyValues[i], window.emptyValues[j]);
            });
            
            assert.throws(function() {
                window.populatedHashMap.rename(window.emptyValues[i], 'a');
            });

            assert.throws(function() {
                window.populatedHashMap.rename('a', window.emptyValues[j]);
            });
        }
    }

    // Test ok values
    assert.ok(window.populatedHashMap.rename('a', 'a1'));
    assert.ok(window.populatedHashMap.get('a1') === 1);
    assert.ok(window.populatedHashMap.length() === 9);
    assert.ok(window.populatedHashMap.getValues().length === 9);

    assert.ok(window.populatedHashMap.rename('c', 'somekey'));
    assert.ok(window.populatedHashMap.get('somekey') === 3);
    assert.ok(window.populatedHashMap.length() === 9);
    assert.ok(ArrayUtils.isEqualTo(window.populatedHashMap.getKeys(), ['a1', 'b', 'somekey', 'd', 'e', 'f', 'g', 'string', 'array']));

    assert.throws(function() {
        window.populatedHashMap.get('a');
    });

    // Test wrong values
    assert.throws(function() {
        window.populatedHashMap.rename('unknown', 'b');
    });

    assert.throws(function() {
        window.populatedHashMap.rename('a1', 'b');
    });

    assert.throws(function() {
        window.populatedHashMap.rename('nonexistant', 'newkey');
    });

    // Test exceptions
    // Tested at empty values    
});


/**
 * testSwap
 */
QUnit.test("testSwap", function(assert){
    
    // Test empty values
    for (var i = 0; i < window.emptyValuesCount; i++) {

        for (var j = 0; j < window.emptyValuesCount; j++) {

            assert.throws(function() {
                window.populatedHashMap.swap(window.emptyValues[i], window.emptyValues[j]);
            });

            assert.throws(function() {
                window.populatedHashMap.swap(window.emptyValues[i], 'a');
            });

            assert.throws(function() {
                window.populatedHashMap.swap('a', window.emptyValues[j]);
            });
        }
    }

    // Test ok values
    assert.ok(window.populatedHashMap.swap('a', 'b'));
    assert.ok(window.populatedHashMap.get('a') === 1);
    assert.ok(window.populatedHashMap.get('b') === 2);
    assert.ok(ArrayUtils.isEqualTo(window.populatedHashMap.getKeys(), ['b', 'a', 'c', 'd', 'e', 'f', 'g', 'string', 'array']));
    assert.ok(window.populatedHashMap.swap('c', 'e'));
    assert.ok(window.populatedHashMap.get('c') === 3);
    assert.ok(window.populatedHashMap.get('e') === 5);
    assert.ok(ArrayUtils.isEqualTo(window.populatedHashMap.getKeys(), ['b', 'a', 'e', 'd', 'c', 'f', 'g', 'string', 'array']));
    assert.ok(window.populatedHashMap.swap('string', 'b'));
    assert.ok(window.populatedHashMap.get('string') === 'myValue');
    assert.ok(window.populatedHashMap.get('b') === 2);
    assert.ok(ArrayUtils.isEqualTo(window.populatedHashMap.getKeys(), ['string', 'a', 'e', 'd', 'c', 'f', 'g', 'b', 'array']));
    assert.ok(window.populatedHashMap.length() === 9);

    // Test wrong values
    assert.throws(function() {
        assert.notOk(window.populatedHashMap.swap('K', 'a'));
    });

    assert.throws(function() {
        assert.notOk(window.populatedHashMap.swap('no', 'string'));
    });

    assert.throws(function() {
        assert.notOk(window.populatedHashMap.swap('string', 'no'));
    });

    // Test exceptions
    // Tested at empty values    
});


/**
 * testSortByKey
 */
QUnit.test("testSortByKey", function(assert){

    // Test empty values
    for (var i = 0; i < window.emptyValuesCount; i++) {

        assert.throws(function() {
            window.populatedHashMap.sortByKey(window.emptyValues[i]);
        });

        assert.throws(function() {
            window.populatedHashMap.sortByKey(HashMapObject.SORT_METHOD_NUMERIC, window.emptyValues[i]);
        });
    }

    var h = new HashMapObject();
    assert.ok(h.length() === 0);
    assert.ok(h.sortByKey());
    assert.ok(h.length() === 0);

    // Test ok values
    h = new HashMapObject();
    h.set('b', 1);
    h.set('d', 2);
    h.set('a', 3);
    h.set('c', 4);
    h.set('0', 5);
    assert.ok(h.length() === 5);
    assert.ok(h.sortByKey(HashMapObject.SORT_METHOD_STRING));
    assert.ok(h.length() === 5);
    assert.ok(ArrayUtils.isEqualTo(h.getKeys(), ['0', 'a', 'b', 'c', 'd']));
    assert.ok(h.get('b') === 1);

    assert.ok(h.sortByKey(HashMapObject.SORT_METHOD_STRING, HashMapObject.SORT_ORDER_DESCENDING));
    assert.ok(ArrayUtils.isEqualTo(['d', 'c', 'b', 'a', '0'], h.getKeys()));

    h = new HashMapObject();
    h.set('6', 6);
    h.set('3', 3);
    h.set('5', 5);
    h.set('2', 2);
    h.set('40', 4);
    assert.ok(h.length() === 5);
    assert.ok(h.sortByKey(HashMapObject.SORT_METHOD_STRING));
    assert.ok(h.length() === 5);
    assert.ok(ArrayUtils.isEqualTo(h.getKeys(), ['2', '3', '40', '5', '6']));

    assert.ok(h.sortByKey(HashMapObject.SORT_METHOD_STRING, HashMapObject.SORT_ORDER_DESCENDING));
    assert.ok(ArrayUtils.isEqualTo(h.getKeys(), ['6', '5', '40', '3', '2']));

    assert.ok(h.sortByKey(HashMapObject.SORT_METHOD_NUMERIC));
    assert.ok(h.length() === 5);
    assert.ok(ArrayUtils.isEqualTo(h.getKeys(), ['2', '3', '5', '6', '40']));

    assert.ok(h.sortByKey(HashMapObject.SORT_METHOD_NUMERIC, HashMapObject.SORT_ORDER_DESCENDING));
    assert.ok(ArrayUtils.isEqualTo(h.getKeys(), ['40', '6', '5', '3', '2']));
    assert.ok(h.length() === 5);

    // Test wrong values
    // Not necessary

    // Test exceptions
    // Tested at empty values      
});


/**
 * testShift
 */
QUnit.test("testShift", function(assert){

    // Test empty values
    // Not necessary

    // Test ok values
    assert.ok(window.populatedHashMap.shift() === 1);
    assert.ok(window.populatedHashMap.length() === 8);
    assert.ok(ArrayUtils.isEqualTo(window.populatedHashMap.getKeys(), ['b', 'c', 'd', 'e', 'f', 'g', 'string', 'array']));
    assert.ok(window.populatedHashMap.shift() === 2);
    assert.ok(window.populatedHashMap.length() === 7);
    assert.ok(ArrayUtils.isEqualTo(window.populatedHashMap.getKeys(), ['c', 'd', 'e', 'f', 'g', 'string', 'array']));
    assert.ok(window.populatedHashMap.shift() === 3);
    assert.ok(window.populatedHashMap.length() === 6);
    assert.ok(ArrayUtils.isEqualTo(window.populatedHashMap.getKeys(), ['d', 'e', 'f', 'g', 'string', 'array']));
    assert.ok(window.populatedHashMap.shift() === 4);
    assert.ok(window.populatedHashMap.shift() === 5);
    assert.ok(window.populatedHashMap.shift() === 6);
    assert.ok(window.populatedHashMap.length() === 3);
    assert.ok(ArrayUtils.isEqualTo(window.populatedHashMap.getKeys(), ['g', 'string', 'array']));
    assert.ok(window.populatedHashMap.shift() === 7);
    assert.ok(window.populatedHashMap.shift() === 'myValue');
    assert.ok(ArrayUtils.isEqualTo(window.populatedHashMap.shift(), [1, 2, 3, 4]));
    assert.ok(window.populatedHashMap.length() === 0);
    assert.ok(ArrayUtils.isEqualTo(window.populatedHashMap.getKeys(), []));

    // Test wrong values
    // Not necessary

    // Test exceptions
    var h = new HashMapObject();

    assert.throws(function() {
        h.shift();
    }); 
});


/**
 * testPop
 */
QUnit.test("testPop", function(assert){
    
    // Test empty values
    // Not necessary

    // Test ok values
    assert.ok(ArrayUtils.isEqualTo(window.populatedHashMap.pop(), [1, 2, 3, 4]));
    assert.ok(window.populatedHashMap.length() === 8);
    assert.ok(ArrayUtils.isEqualTo(window.populatedHashMap.getKeys(), ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'string']));
    assert.ok(window.populatedHashMap.pop() === 'myValue');
    assert.ok(window.populatedHashMap.length() === 7);
    assert.ok(ArrayUtils.isEqualTo(window.populatedHashMap.getKeys(), ['a', 'b', 'c', 'd', 'e', 'f', 'g']));
    assert.ok(window.populatedHashMap.pop() === 7);
    assert.ok(window.populatedHashMap.length() === 6);
    assert.ok(ArrayUtils.isEqualTo(window.populatedHashMap.getKeys(), ['a', 'b', 'c', 'd', 'e', 'f']));
    assert.ok(window.populatedHashMap.pop() === 6);
    assert.ok(window.populatedHashMap.pop() === 5);
    assert.ok(window.populatedHashMap.pop() === 4);
    assert.ok(window.populatedHashMap.length() === 3);
    assert.ok(ArrayUtils.isEqualTo(window.populatedHashMap.getKeys(), ['a', 'b', 'c']));
    assert.ok(window.populatedHashMap.pop() === 3);
    assert.ok(window.populatedHashMap.pop() === 2);
    assert.ok(window.populatedHashMap.pop() === 1);
    assert.ok(window.populatedHashMap.length() === 0);
    assert.ok(ArrayUtils.isEqualTo(window.populatedHashMap.getKeys(), []));

    // Test wrong values
    // Not necessary

    // Test exceptions
    var h = new HashMapObject();

    assert.throws(function() {
        h.pop();
    });
});


/**
 * testReverse
 */
QUnit.test("testReverse", function(assert){
    
    // Test empty values
    var h = new HashMapObject();
    assert.ok(h.length() === 0);
    h.reverse();
    assert.ok(h.length() === 0);

    // Test ok values
    assert.ok(window.populatedHashMap.length() === 9);
    assert.ok(window.populatedHashMap.reverse());
    assert.ok(ArrayUtils.isEqualTo(window.populatedHashMap.getKeys(), ['array', 'string', 'g', 'f', 'e', 'd', 'c', 'b', 'a']));
    assert.ok(window.populatedHashMap.length() === 9);

    // Test wrong values
    // Not necessary

    // Test exceptions
    // Not necessary
});