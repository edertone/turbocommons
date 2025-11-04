"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


QUnit.module("ModelHistoryManagerTest", {
    beforeEach : function(){

        window.sut = new org_turbocommons.ModelHistoryManager({a:0, b:0});
        window.ModelHistoryManager = org_turbocommons.ModelHistoryManager;
        
        window.StringUtils = org_turbocommons.StringUtils;
        window.ArrayUtils = org_turbocommons.ArrayUtils;
        window.ObjectUtils = org_turbocommons.ObjectUtils;
        
        window.emptyValues = [null, '', [], {}, '     ', "\n\n\n", 0];
        window.emptyValuesCount = window.emptyValues.length;
    },

    afterEach : function(){

        delete window.sut;
        delete window.ModelHistoryManager;

        delete window.StringUtils;
        delete window.ArrayUtils;
        delete window.ObjectUtils;
        
        delete window.emptyValues;
        delete window.emptyValuesCount;
    }
});


/**
 * constructor
 */
QUnit.test("constructor", function(assert){

    // Nothing to test
    assert.ok(true);
});


/**
 * setInitialState
 */
QUnit.test("setInitialState", function(assert){

    // Test empty values
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:0, b:0}));
    sut.setInitialState();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:0, b:0}));

    // Test ok values    
    sut.get.a = 2;
    sut.get.b = 3;
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:2, b:3}));

    sut.setInitialState();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:2, b:3}));
    
    sut.get.a = 4;
    sut.get.b = 5;
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:4, b:5}));
    
    assert.ok(sut.undo());
    assert.notOk(sut.undo());
    assert.notOk(sut.undo());
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:2, b:3}));
    
    sut.get.a = 4;
    sut.get.b = 5;
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:4, b:5}));
    
    sut.get.a = 6;
    sut.get.b = 7;
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:6, b:7}));
    
    assert.ok(sut.undoAll());
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:2, b:3}));
    
    // Test wrong values
    // Not necessary

    // Test exceptions
    // Tested by empty values
});


/**
 * get
 */
QUnit.test("get", function(assert){

    // Test empty values
    // Not necessary

    // Test ok values
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:0, b:0}));
    
    sut.get.a = 1;
    sut.get.b = 2;
    sut.setInitialState();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:2}));
    
    sut.get.a = 3;
    sut.get.b = 4;
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:3, b:4}));
    
    sut.saveSnapshot();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:3, b:4}));
    
    sut.get.a = 5;
    sut.get.b = 6;
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:5, b:6}));
    
    sut.undo();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:3, b:4}));
    
    sut.undo();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:2}));
    
    sut.undo();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:2}));
    
    // Test wrong values
    // Not necessary

    // Test exceptions
    // Not necessary
});


/**
 * tags
 */
QUnit.test("tags", function(assert){

    // Test empty values
    assert.ok(ArrayUtils.isEqualTo(sut.tags, []));

    // Test ok values
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:0, b:0}));
    
    sut.get.a = 1;
    sut.get.b = 2;
    sut.setInitialState();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:2}));
    
    sut.saveSnapshot();
    sut.saveSnapshot();
    sut.saveSnapshot();
    assert.ok(sut.tags.length === 0);
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:2}));
    
    sut.get.a = 3;
    assert.ok(sut.tags.length === 0);
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:3, b:2}));
    sut.saveSnapshot();
    assert.ok(ArrayUtils.isEqualTo(sut.tags, ['']));
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:3, b:2}));
    
    sut.get.a = 4;
    assert.ok(sut.tags.length === 1);
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:4, b:2}));
    sut.saveSnapshot('tag-1');
    sut.saveSnapshot();
    assert.ok(ArrayUtils.isEqualTo(sut.tags, ['', 'tag-1', '']));
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:4, b:2}));
    
    sut.get.b = 5;
    assert.ok(ArrayUtils.isEqualTo(sut.tags, ['', 'tag-1', '']));
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:4, b:5}));
    sut.saveSnapshot('tag-2');
    assert.ok(ArrayUtils.isEqualTo(sut.tags, ['', 'tag-1', '', 'tag-2']));
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:4, b:5}));
    sut.saveSnapshot();
    assert.ok(ArrayUtils.isEqualTo(sut.tags, ['', 'tag-1', '', 'tag-2', '']));
    
    sut.undo();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:4, b:2}));
    assert.ok(ArrayUtils.isEqualTo(sut.tags, ['', 'tag-1', '']));
    sut.saveSnapshot();
    assert.ok(ArrayUtils.isEqualTo(sut.tags, ['', 'tag-1', '']));
    
    sut.get.b = 6;
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:4, b:6}));
    sut.saveSnapshot();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:4, b:6}));
    assert.ok(ArrayUtils.isEqualTo(sut.tags, ['', 'tag-1', '', '']));
    
    sut.undoAll();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:2}));
    assert.ok(ArrayUtils.isEqualTo(sut.tags, []));

    // Test wrong values
    // Not necessary

    // Test exceptions
    // Not necessary
});


/**
 * snapshots
 */
QUnit.test("snapshots", function(assert){

    // Test empty values
    assert.ok(ArrayUtils.isEqualTo(sut.snapshots, []));

    // Test ok values
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:0, b:0}));
    
    sut.get.a = 1;
    sut.get.b = 2;
    sut.setInitialState();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:2}));
    
    sut.saveSnapshot();
    sut.saveSnapshot();
    sut.saveSnapshot();
    assert.ok(sut.snapshots.length === 0);
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:2}));
    
    sut.get.a = 3;
    assert.ok(sut.snapshots.length === 0);
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:3, b:2}));
    sut.saveSnapshot();
    assert.ok(sut.snapshots.length === 1);
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:3, b:2}));
    
    sut.get.a = 4;
    assert.ok(sut.snapshots.length === 1);
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:4, b:2}));
    sut.saveSnapshot();
    sut.saveSnapshot();
    sut.saveSnapshot();
    assert.ok(sut.snapshots.length === 2);
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:4, b:2}));
    
    sut.get.b = 5;
    assert.ok(sut.snapshots.length === 2);
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:4, b:5}));
    sut.saveSnapshot();
    assert.ok(sut.snapshots.length === 3);
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:4, b:5}));
    sut.saveSnapshot();
    assert.ok(sut.snapshots.length === 3);
    
    sut.undo();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:4, b:2}));
    assert.ok(sut.snapshots.length === 2);
    
    sut.get.b = 6;
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:4, b:6}));
    sut.saveSnapshot();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:4, b:6}));
    assert.ok(sut.snapshots.length === 3);
    
    sut.get.b = 7;
    sut.undo();
    assert.ok(sut.snapshots.length === 3);
    
    sut.undo();
    assert.ok(sut.snapshots.length === 2);
    
    sut.undoAll();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:2}));
    assert.ok(sut.snapshots.length === 0);

    // Test wrong values
    // Not necessary

    // Test exceptions
    // Not necessary
});


/**
 * getSnapshotsByTag
 */
QUnit.test("getSnapshotsByTag", function(assert){

    // Test empty values
    assert.strictEqual(sut.getSnapshotsByTag(['']).length, 0);
    
    sut.get.a = 1;
    sut.get.b = 2;
    sut.setInitialState();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:2}));
    
    for (var i = 0; i < window.emptyValuesCount; i++) {
        
        assert.throws(function() {
            sut.getSnapshotsByTag(window.emptyValues[i]);
        }, /tags must be a non empty string array/);
    }
    
    assert.strictEqual(sut.getSnapshotsByTag(['']).length, 0);
    
    sut.get.b = 5;
    sut.saveSnapshot();
    assert.strictEqual(sut.getSnapshotsByTag(['']).length, 1);

    // Test ok values
    sut.undoAll();
    assert.strictEqual(sut.getSnapshotsByTag(['']).length, 0);
    assert.strictEqual(sut.getSnapshotsByTag(['nonexistanttag']).length, 0);
    
    sut.get.b = 3;
    sut.saveSnapshot();
    assert.strictEqual(sut.getSnapshotsByTag(['']).length, 1);
    
    sut.get.b = 4;
    sut.saveSnapshot('4-2');
    
    sut.get.b = 5;
    sut.saveSnapshot('5-2');
    sut.saveSnapshot('4-2');
    
    sut.get.b = 4;
    sut.saveSnapshot('4-2');
    sut.saveSnapshot('4-2');
    sut.saveSnapshot('X-2');
    
    sut.get.b = 9;
    assert.strictEqual(sut.snapshots.length, 6);
    assert.strictEqual(sut.getSnapshotsByTag(['']).length, 1);
    assert.strictEqual(sut.getSnapshotsByTag(['X-2']).length, 1);
    assert.strictEqual(sut.getSnapshotsByTag(['4-2']).length, 3);
    assert.strictEqual(sut.getSnapshotsByTag(['5-2']).length, 1);
    
    sut.undo();
    assert.strictEqual(sut.snapshots.length, 6);
    assert.strictEqual(sut.getSnapshotsByTag(['']).length, 1);
    assert.strictEqual(sut.getSnapshotsByTag(['X-2']).length, 1);
    assert.strictEqual(sut.getSnapshotsByTag(['4-2']).length, 3);
    assert.strictEqual(sut.getSnapshotsByTag(['5-2']).length, 1);
    
    sut.undo();
    assert.strictEqual(sut.snapshots.length, 4);
    assert.strictEqual(sut.getSnapshotsByTag(['']).length, 1);
    assert.strictEqual(sut.getSnapshotsByTag(['X-2']).length, 0);
    assert.strictEqual(sut.getSnapshotsByTag(['4-2']).length, 2);
    assert.strictEqual(sut.getSnapshotsByTag(['5-2']).length, 1);
    
    sut.undo();
    assert.strictEqual(sut.snapshots.length, 2);
    assert.strictEqual(sut.getSnapshotsByTag(['']).length, 1);
    assert.strictEqual(sut.getSnapshotsByTag(['X-2']).length, 0);
    assert.strictEqual(sut.getSnapshotsByTag(['4-2']).length, 1);
    assert.strictEqual(sut.getSnapshotsByTag(['5-2']).length, 0);
    
    sut.undo();
    assert.strictEqual(sut.snapshots.length, 1);
    assert.strictEqual(sut.getSnapshotsByTag(['']).length, 1);
    assert.strictEqual(sut.getSnapshotsByTag(['X-2']).length, 0);
    assert.strictEqual(sut.getSnapshotsByTag(['4-2']).length, 0);
    assert.strictEqual(sut.getSnapshotsByTag(['5-2']).length, 0);

    // Test wrong values
    // Not necessary

    // Test exceptions
    assert.throws(function() {
        sut.getSnapshotsByTag('hello');
    }, /tags must be a non empty string array/);
    
    assert.throws(function() {
        sut.getSnapshotsByTag(12345);
    }, /tags must be a non empty string array/);
    
    assert.throws(function() {
        sut.getSnapshotsByTag(new Error());
    }, /tags must be a non empty string array/);
});


/**
 * saveSnapShot
 */
QUnit.test("saveSnapShot", function(assert){

    // Test empty values
    sut.get.a = 1;
    sut.get.b = 2;
    sut.setInitialState();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:2}));
    
    for (var i = 0; i < window.emptyValuesCount; i++) {
    
        if(StringUtils.isString(window.emptyValues[i])){
            
            assert.notOk(sut.saveSnapshot(window.emptyValues[i]));
            
        }else{
            
            assert.throws(function() {
                sut.saveSnapshot(window.emptyValues[i]);
            }, /tag must be a string/);
        }
    }

    sut.get.a = 3;
    assert.ok(sut.saveSnapshot(''));
    assert.strictEqual(sut.snapshots.length, 1);
    
    // Test ok values
    sut.setInitialState();
    assert.strictEqual(sut.snapshots.length, 0);
    assert.notOk(sut.saveSnapshot('snap1'));
    
    sut.get.a = 3;
    sut.get.b = 4;
    assert.ok(sut.saveSnapshot('snap1'));
    
    sut.get.a = 5;
    sut.get.b = 6;
    assert.ok(sut.saveSnapshot('snap1'));
    assert.notOk(sut.saveSnapshot('snap1'));
    assert.ok(sut.saveSnapshot('snap2'));
    assert.notOk(sut.saveSnapshot('snap2'));
    
    sut.get.a = 7;
    sut.get.b = 8;
    assert.ok(sut.saveSnapshot('snap2'));
    assert.notOk(sut.saveSnapshot('snap2'));
    assert.notOk(sut.saveSnapshot('snap2'));
    
    assert.strictEqual(sut.getSnapshotsByTag(['snap1']).length, 2);
    assert.strictEqual(sut.getSnapshotsByTag(['snap2']).length, 2);
    
    // Test wrong values
    // Not necessary

    // Test exceptions
    assert.throws(function() {
        sut.saveSnapshot(['hello']);
    }, /tag must be a string/);
    
    assert.throws(function() {
        sut.saveSnapshot(123456);
    }, /tag must be a string/);
    
    assert.throws(function() {
        sut.saveSnapshot(new Error());
    }, /tag must be a string/);
});


/**
 * isUndoPossible
 */
QUnit.test("isUndoPossible", function(assert){

    // Test empty values
    // Not necessary

    // Test ok values
    assert.ok(!sut.isUndoPossible);
    
    sut.get.a = 1;
    sut.get.b = 2;
    sut.setInitialState();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:2}));
    
    sut.saveSnapshot();
    sut.saveSnapshot();
    assert.ok(!sut.isUndoPossible);

    sut.get.a = 3;
    assert.ok(sut.isUndoPossible);
    sut.saveSnapshot();
    assert.ok(sut.isUndoPossible);

    sut.get.a = 4;
    assert.ok(sut.isUndoPossible);
    sut.saveSnapshot();
    assert.ok(sut.isUndoPossible);
    assert.ok(sut.snapshots.length === 2);
    
    sut.undoAll();
    assert.ok(!sut.isUndoPossible);
    assert.ok(sut.snapshots.length === 0);
    
    sut.saveSnapshot();
    assert.ok(!sut.isUndoPossible);
    
    sut.get.b = 3;
    assert.ok(sut.isUndoPossible);
    
    sut.saveSnapshot();
    assert.ok(sut.isUndoPossible);
    
    sut.undo();
    assert.ok(!sut.isUndoPossible);
    
    sut.get.b = 5;
    assert.ok(sut.isUndoPossible);
    
    sut.saveSnapshot();
    assert.ok(sut.isUndoPossible);
    
    // Test wrong values
    // Not necessary

    // Test exceptions
    // Not necessary
});


/**
 * undo
 */
QUnit.test("undo", function(assert){

    // Test empty values
    assert.notOk(sut.undo());
    
    // Test ok values
    sut.get.a = 1;
    sut.get.b = 2;
    sut.setInitialState();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:2}));
    
    assert.notOk(sut.undo());
    assert.notOk(sut.undo());
    
    sut.get.b = 3;
    assert.ok(sut.undo());
    assert.strictEqual(sut.get.b, 2);
    
    sut.get.b = 3;
    sut.saveSnapshot();
    
    sut.get.b = 4;
    sut.saveSnapshot();
    
    sut.get.b = 5;
    sut.saveSnapshot();
    
    sut.get.b = 6;
    assert.ok(sut.undo());
    assert.strictEqual(sut.get.b, 5);
    
    assert.ok(sut.undo());
    assert.strictEqual(sut.get.b, 4);
    
    assert.ok(sut.undo());
    assert.strictEqual(sut.get.b, 3);
    
    assert.ok(sut.undo());
    assert.strictEqual(sut.get.b, 2);
    
    assert.notOk(sut.undo());
    assert.notOk(sut.undoAll());
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:2}));
    
    sut.get.b = 3;
    sut.saveSnapshot('a');
    
    sut.get.b = 4;
    sut.saveSnapshot('b');
    
    sut.get.b = 5;
    sut.saveSnapshot('a');
    
    sut.get.b = 6;
    sut.saveSnapshot('a');
    
    assert.ok(sut.undo(['b']));
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:4}));
    
    assert.ok(sut.undo(['a']));
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:3}));
    
    assert.ok(sut.undoAll());
    assert.notOk(sut.undo(['b']));
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:2}));
    
    sut.get.b = 3;
    sut.saveSnapshot('a');
    
    sut.get.b = 4;
    sut.saveSnapshot('b');
    
    sut.get.b = 5;
    sut.saveSnapshot('a');
    
    // A non existing tag will lead us to a total undo
    assert.ok(sut.undo(['c']));
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:2}));
    assert.ok(sut.snapshots.length === 0);
    
    // Test wrong values
    // Not necessary

    // Test exceptions
    // Not necessary
});


/**
 * undoAll
 */
QUnit.test("undoAll", function(assert){

    // Test empty values
    assert.notOk(sut.undoAll());

    // Test ok values
    sut.get.a = 1;
    sut.get.b = 2;
    sut.setInitialState();
    assert.ok(ObjectUtils.isEqualTo(sut.get, {a:1, b:2}));
    
    assert.notOk(sut.undoAll());
    assert.notOk(sut.undoAll());
    
    sut.get.b = 3;
    assert.ok(sut.undoAll());
    assert.strictEqual(sut.get.b, 2);
    
    sut.get.b = 3;
    sut.saveSnapshot();
    
    sut.get.b = 4;
    sut.saveSnapshot();
    
    sut.get.b = 5;
    sut.saveSnapshot();
    
    sut.get.b = 6;
    assert.ok(sut.undoAll());
    assert.strictEqual(sut.get.b, 2);
    
    assert.notOk(sut.undoAll());

    // Test wrong values
    // Not necessary

    // Test exceptions
    // Not necessary
});


/**
 * redo
 */
QUnit.todo("redo", function(assert){

    // Test empty values
    // TODO

    // Test ok values
    // TODO

    // Test wrong values
    // TODO

    // Test exceptions
    // TODO
});