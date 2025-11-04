"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


QUnit.module("TableObjectTest", {
    beforeEach : function(){

        window.NumericUtils = org_turbocommons.NumericUtils;
        window.ArrayUtils = org_turbocommons.ArrayUtils;
        window.TableObject = org_turbocommons.TableObject;
        
        window.emptyValues = [null, '', [], {}, '     ', "\n\n\n"];
        window.emptyValuesCount = window.emptyValues.length;
    },

    afterEach : function(){

        delete window.NumericUtils;
        delete window.ArrayUtils;
        delete window.TableObject;
    }
});


/**
 * testConstruct
 */
QUnit.test("testConstruct", function(assert){

    // Test empty values
    var test = new TableObject();
    assert.ok(test.countCells() === 0);
    assert.ok(test.countRows() === 0);
    assert.ok(test.countColumns() === 0);

    var test = new TableObject(0, 0);
    assert.ok(test.countCells() === 0);
    assert.ok(test.countRows() === 0);
    assert.ok(test.countColumns() === 0);

    for (var i = 0; i < window.emptyValuesCount; i++) {

        for (var j = 0; j < window.emptyValuesCount; j++) {

            assert.throws(function() {
                new TableObject(window.emptyValues[i], window.emptyValues[j]);
            });
        }
    }

    // Test ok values
    for (var i = 1; i < 5000; i+=100) {

        for (var j = 1; j < 5000; j+=100) {

            var test = new TableObject(i, j);
            assert.ok(i * j === test.countCells());
            assert.ok(i === test.countRows());
            assert.ok(j === test.countColumns());
        }
    }

    var test = new TableObject(2, ['c1', 'c2', 'c3']);
    assert.ok(test.countCells() === 6);
    assert.ok(test.countRows() === 2);
    assert.ok(test.countColumns() === 3);

    // Test wrong values
    assert.throws(function() {
        new TableObject(0, NumericUtils.generateRandomInteger(10000000));
    });

    assert.throws(function() {
        new TableObject(NumericUtils.generateRandomInteger(10000000), 0);
    });

    assert.throws(function() {
        new TableObject(NumericUtils.generateRandomInteger(10000000) * -1, NumericUtils.generateRandomInteger(10000000) * -1);
    });

    assert.throws(function() {
        new TableObject('hello', 'hello');
    });

    assert.throws(function() {
        new TableObject([], []);
    });

    assert.throws(function() {
        new TableObject(NumericUtils.generateRandomInteger(10000000) * -1, NumericUtils.generateRandomInteger(10000000));
    });

    assert.throws(function() {
        new TableObject(NumericUtils.generateRandomInteger(10000000), NumericUtils.generateRandomInteger(10000000) * -1);
    });

    // Test exceptions
    // Tested with wrong values
});


/**
 * testSetColumnName
 */
QUnit.test("testSetColumnName", function(assert){
    
    // Test empty values
    var test = new TableObject(10, 10);

    assert.ok(test.setColumnName(0, ''));
    assert.ok(test.setColumnName(0, '    '));
    assert.ok(test.setColumnName(0, "\n\n\n"));

    window.emptyValues = [null, [], {}];
    window.emptyValuesCount = window.emptyValues.length;

    for (var i = 0; i < window.emptyValuesCount; i++) {

        for (var j = 0; j < 10; j++) {

            assert.throws(function() {
                test.setColumnName(j, window.emptyValues[i]);
            });
        }
    }

    // Test ok values
    var test = new TableObject(1, 1);
    assert.ok(test.setColumnName(0, 'column1'));
    assert.ok(ArrayUtils.isEqualTo([null], test.getColumn(0)));
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(0), test.getColumn('column1')));
    assert.ok(test.getColumnName(0) === 'column1');
    assert.ok(test.getColumnIndex('column1') === 0);

    var test = new TableObject(20, 20);
    assert.ok(test.setColumnName(11, 'column11'));
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(11), test.getColumn('column11')));
    assert.ok(test.getColumnName(11) === 'column11');
    assert.ok(test.getColumnIndex('column11') === 11);

    assert.ok(test.setColumnName(12, 'column12'));
    assert.ok(test.setColumnName(11, 'renamed'));
    assert.ok(test.getColumnName(12) === 'column12');
    assert.ok(test.getColumnName(11) === 'renamed');

    assert.ok(test.setColumnName('renamed', 're-renamed'));
    assert.ok(test.getColumnName(11) === 're-renamed');

    assert.ok(test.getColumnName(1) === '');
    assert.ok(test.getColumnName(19) === '');
    assert.ok(test.setColumnName(11, ''));
    assert.ok(test.setColumnName(12, '   '));
    assert.ok(test.setColumnName(13, "\n\n"));
    assert.ok(test.getColumnName(11) === '');
    assert.ok(test.getColumnName(12) === '   ');
    assert.ok(test.getColumnName(13) === "\n\n");

    // Test wrong values
    window.wrongValues = [null, [], {}, -1, 50];
    window.wrongValuesCount = window.wrongValues.length;

    for (i = 0; i < window.wrongValuesCount; i++) {

        assert.throws(function() {
            test.setColumnName(window.wrongValues[i], 'name');
        });
    }

    for (i = 0; i < window.wrongValuesCount; i++) {

        assert.throws(function() {
            test.setColumnName(1, window.wrongValues[i]);
        });
    }

    assert.throws(function() {
        test.setColumnName(40, 'name');
    });

    // Test exceptions
    // Already tested
});


/**
 * testSetColumnNames
 */
QUnit.test("testSetColumnNames", function(assert){
    
    // Test empty values
    var test = new TableObject(10, 10);

    for (var i = 0; i < window.emptyValuesCount; i++) {

        assert.throws(function() {
            test.setColumnNames(window.emptyValues[i]);
        });
    }

    // Test ok values
    var test = new TableObject(5, 3);
    assert.ok(ArrayUtils.isEqualTo(['', '  ', "\n\n\n"], test.setColumnNames(['', '  ', "\n\n\n"])));
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), ['', '  ', "\n\n\n"]));

    for (var i = 1; i < 50; i+=10) {

        for (var j = 1; j < 50; j+=10) {

            var test = new TableObject(i, j);

            var columns = [];

            for (var k = 0; k < j; k++) {

                columns.push( 'column' + k);
            }

            assert.ok(ArrayUtils.isEqualTo(columns, test.setColumnNames(columns)));
            assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), columns));
        }
    }

    // Test wrong values
    var test = new TableObject(0, 0);

    assert.throws(function() {
        test.setColumnNames(['column1']);
    });

    var test = new TableObject(2, 2);

    assert.throws(function() {
        test.setColumnNames(['column', 'column']);
    });

    assert.throws(function() {
        test.setColumnNames(['column', 1]);
    });

    // Test setting wrong number of column names
    for (var i = 1; i < 10; i++) {

        for (var j = 1; j < 10; j++) {

            var test = new TableObject(i, j);

            var columns = [];

            for (var k = 0; k < (j * 2); k++) {

                columns.push('column' + k);
            }

            assert.throws(function() {
                test.setColumnNames(columns);
            });
        }
    }

    // Test exceptions
    // Tested with wrong values    
});


/**
 * testGetColumnNames
 */
QUnit.test("testGetColumnNames", function(assert){

    // Test empty values
    var test = new TableObject();
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), []));

    // Test ok values
    test = new TableObject(1, 1);
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), ['']));
    test.setColumnNames(['column1']);
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), ['column1']));

    test = new TableObject(10, 4);
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), ['', '', '', '']));
    test.setColumnNames(['column1', 'column2', 'column3', 'column4']);
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), ['column1', 'column2', 'column3', 'column4']));

    test = new TableObject(10, 4);
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), ['', '', '', '']));
    test.setColumnName(0, 'col0');
    assert.ok(ArrayUtils.isEqualTo(['col0', '', '', ''], test.getColumnNames()));

    test = new TableObject(10, 4);
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), ['', '', '', '']));
    test.setColumnName(2, 'col2');
    assert.ok(ArrayUtils.isEqualTo(['', '', 'col2', ''], test.getColumnNames()));

    test = new TableObject(10, 4);
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), ['', '', '', '']));
    test.setColumnName(3, 'col3');
    assert.ok(ArrayUtils.isEqualTo(['', '', '', 'col3'], test.getColumnNames()));

    test = new TableObject(1, 8);
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), ['', '', '', '', '', '', '', '']));
    test.setColumnNames(['column1', 'column2', 'column3', 'column4', 'column5', 'column6', 'column7', 'column8']);
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), ['column1', 'column2', 'column3', 'column4', 'column5', 'column6', 'column7', 'column8']));

    // Test wrong values
    // Not necessary

    // Test exceptions
    // Already tested at constructor test
});


/**
 * testGetColumnName
 */
QUnit.test("testGetColumnName", function(assert){

    // Test empty values
    var test = new TableObject();

    assert.throws(function() {
        test.getColumnName(0);
    });

    for (var i = 0; i < window.emptyValuesCount; i++) {

        assert.throws(function() {
            test.getColumnName(window.emptyValues[i]);
        });
    }

    // Test ok values
    test = new TableObject(10, 10);
    assert.ok(test.getColumnName(1) === '');
    assert.ok(test.getColumnName(5) === '');
    assert.ok(test.getColumnName(7) === '');

    assert.ok(test.setColumnName(1, ''));
    assert.ok(test.setColumnName(2, '   '));
    assert.ok(test.setColumnName(3, "\n\n"));
    assert.ok(test.setColumnName(4, 'name 1'));
    assert.ok(test.setColumnName(5, '  name 2'));

    assert.ok(test.getColumnName(1) === '');
    assert.ok(test.getColumnName(2) === '   ');
    assert.ok(test.getColumnName(3) === "\n\n");
    assert.ok(test.getColumnName(4) === 'name 1');
    assert.ok(test.getColumnName(5) === '  name 2');

    assert.ok(test.setColumnName(1, 'newName'));
    assert.ok(test.setColumnName('   ', 'noEmpty'));
    assert.ok(test.setColumnName(3, 'newName2'));
    assert.ok(test.setColumnName('name 1', 'newName 4'));
    assert.ok(test.setColumnName(5, 'newName3'));

    assert.ok(test.getColumnName(1) === 'newName');
    assert.ok(test.getColumnName(2) === 'noEmpty');
    assert.ok(test.getColumnName(3) === 'newName2');
    assert.ok(test.getColumnName(4) === 'newName 4');
    assert.ok(test.getColumnName(5) === 'newName3');

    // Test wrong values
    assert.throws(function() {
        test.getColumnName(-1);
    });

    assert.throws(function() {
        test.getColumnName(111);
    });

    assert.throws(function() {
        test.getColumnName('nonexistantkey');
    });

    // Test exceptions
    // Already tested
});


/**
 * testGetColumnIndex
 */
QUnit.test("testGetColumnIndex", function(assert){
    
    // Test empty values
    var test = new TableObject();

    for (var i = 0; i < window.emptyValuesCount; i++) {

        assert.throws(function() {
            test.getColumnIndex(window.emptyValues[i]);
        });
    }

    // Test ok values
    var test = new TableObject(10, 10);

    assert.ok(test.setColumnName(2, '   '));
    assert.ok(test.setColumnName(3, "\n\n"));
    assert.ok(test.setColumnName(4, 'name 1'));
    assert.ok(test.setColumnName(5, '  name 2'));

    assert.ok(test.getColumnIndex('   ') === 2);
    assert.ok(test.getColumnIndex("\n\n") === 3);
    assert.ok(test.getColumnIndex('name 1') === 4);
    assert.ok(test.getColumnIndex('  name 2') === 5);

    // Test wrong values
    assert.throws(function() {
        test.getColumnIndex(123);
    });

    assert.throws(function() {
        test.getColumnIndex('non existant key');
    });

    // Test exceptions
    // Already tested
});


/**
 * testGetColumn
 */
QUnit.test("testGetColumn", function(assert){
    
 // Test empty values
    var test = new TableObject();

    assert.throws(function() {
        test.getColumn(0);
    });

    for (var i = 0; i < window.emptyValuesCount; i++) {

        assert.throws(function() {
            test.getColumn(window.emptyValues[i]);
        });
    }

    // Test ok values
    var test = new TableObject(1, 1);
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(0), [null]));

    var test = new TableObject(5, 5);
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(0), [null, null, null, null, null]));
    test.setColumn(2, [1, 2, 3, 4, 5]);
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(0), [null, null, null, null, null]));
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(2), [1, 2, 3, 4, 5]));
    test.setColumn(4, [1, 2, 3, 4, 5]);
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(3), [null, null, null, null, null]));
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(4), [1, 2, 3, 4, 5]));

    assert.ok(test.setColumnName(2, 'column2'));
    assert.ok(ArrayUtils.isEqualTo(test.getColumn('column2'), [1, 2, 3, 4, 5]));

    // Test wrong values
    assert.throws(function() {
        test.getColumn(-1);
    });

    assert.throws(function() {
        test.getColumn(11);
    });

    assert.throws(function() {
        test.getColumn('non existant');
    });

    // Test exceptions
    // Already tested
});


/**
 * testAddColumns
 */
QUnit.test("testAddColumns", function(assert){
    
    // Test empty values
    var test = new TableObject();

    assert.throws(function() {
        test.addColumns(0);
    });

    for (var i = 0; i < window.emptyValuesCount; i++) {

        assert.throws(function() {
            test.addColumns(window.emptyValues[i]);
        });

        assert.throws(function() {
            test.addColumns(1, ['col'], window.emptyValues[i]);
        });

        if(!ArrayUtils.isArray(window.emptyValues[i])){

            assert.throws(function() {
                test.addColumns(1, window.emptyValues[i]);
            });
        }
    }

    // Test ok values
    var test = new TableObject();
    assert.ok(test.addColumns(1));
    assert.ok(test.countColumns() === 1);
    assert.ok(ArrayUtils.isEqualTo(test.getColumnNames(), ['']));
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(0), []));

    test = new TableObject(4, 4);
    assert.ok(test.addColumns(2, ['col1', 'col2']));
    assert.ok(test.countColumns() === 6);
    assert.ok(ArrayUtils.isEqualTo(['', '', '', '', 'col1', 'col2'], test.getColumnNames()));
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(3), [null, null, null, null]));

    assert.ok(test.addColumns(1, ['col3']));
    assert.ok(test.countColumns() === 7);
    assert.ok(ArrayUtils.isEqualTo(['', '', '', '', 'col1', 'col2', 'col3'], test.getColumnNames()));

    assert.ok(test.addColumns(1, ['col4'], 2));
    assert.ok(test.countColumns() === 8);
    assert.ok(ArrayUtils.isEqualTo(['', '', 'col4', '', '', 'col1', 'col2', 'col3'], test.getColumnNames()));

    assert.ok(test.addColumns(1, [], 0));
    assert.ok(test.countColumns() === 9);
    assert.ok(ArrayUtils.isEqualTo(['', '', '', 'col4', '', '', 'col1', 'col2', 'col3'], test.getColumnNames()));

    var test = new TableObject(3, 1);
    test.setColumn(0, [1, 2, 3]);
    assert.ok(1 === test.countColumns());

    assert.ok(test.addColumns(1, [], 0));
    assert.ok(2 === test.countColumns());
    assert.ok(ArrayUtils.isEqualTo([null, null, null], test.getColumn(0)));
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3], test.getColumn(1)));
    assert.ok(test.addColumns(1, [], 0));
    assert.ok(ArrayUtils.isEqualTo([null, null, null], test.getColumn(0)));
    assert.ok(ArrayUtils.isEqualTo([null, null, null], test.getColumn(1)));
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3], test.getColumn(2)));
    assert.ok(3, test.countColumns());

    assert.ok(test.addColumns(1, ['col'], 2));
    assert.ok(ArrayUtils.isEqualTo([null, null, null], test.getColumn(0)));
    assert.ok(ArrayUtils.isEqualTo([null, null, null], test.getColumn(1)));
    assert.ok(ArrayUtils.isEqualTo([null, null, null], test.getColumn(2)));
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3], test.getColumn(3)));
    assert.ok(ArrayUtils.isEqualTo([null, null, null], test.getColumn('col')));
    assert.ok(test.countColumns() === 4);

    var test = new TableObject();
    test.addRows(3);
    test.addColumns(3, ['c0', 'c1', 'c2']);
    test.setColumn(0, [1, 2, 3]);
    test.setColumn(1, [4, 5, 6]);
    test.setColumn(2, [7, 8, 9]);
    assert.ok(test.countRows() === 3);
    assert.ok(test.countColumns() === 3);
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3], test.getColumn(0)));
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3], test.getColumn('c0')));
    assert.ok(ArrayUtils.isEqualTo([4, 5, 6], test.getColumn(1)));
    assert.ok(ArrayUtils.isEqualTo([4, 5, 6], test.getColumn('c1')));
    assert.ok(ArrayUtils.isEqualTo([7, 8, 9], test.getColumn(2)));
    assert.ok(ArrayUtils.isEqualTo([7, 8, 9], test.getColumn('c2')));
    test.addColumns(1, ['letters'], 2);
    test.setColumn(2, ['x', 'y', 'z']);
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3], test.getColumn(0)));
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3], test.getColumn('c0')));
    assert.ok(ArrayUtils.isEqualTo([4, 5, 6], test.getColumn(1)));
    assert.ok(ArrayUtils.isEqualTo([4, 5, 6], test.getColumn('c1')));
    assert.ok(ArrayUtils.isEqualTo(['x', 'y', 'z'], test.getColumn(2)));
    assert.ok(ArrayUtils.isEqualTo(['x', 'y', 'z'], test.getColumn('letters')));
    assert.ok(test.getColumnIndex('letters') === 2);
    assert.ok(ArrayUtils.isEqualTo([7, 8, 9], test.getColumn(3)));
    assert.ok(ArrayUtils.isEqualTo([7, 8, 9], test.getColumn('c2')));
    assert.ok(test.getColumnIndex('c2') === 3);
    assert.ok(test.countRows() === 3);
    assert.ok(test.countColumns() === 4);

    // Test wrong values
    var test = new TableObject(3, 3);

    window.wrongValues = [0, -1, 1.1, [1, 2, 3, 4]];
    window.wrongValuesCount = window.wrongValues.length;

    for (var i = 0; i < window.wrongValuesCount; i++) {

        assert.throws(function() {
            test.addColumns(window.wrongValues[i]);
        });
    }

    assert.throws(function() {
        test.addColumns(1, [], 4);
    });

    assert.throws(function() {
        test.addColumns(1, ['a', 'b']);
    });

    // Test exceptions
    // Already tested
});


/**
 * testSetColumn
 */
QUnit.test("testSetColumn", function(assert){

    // Test empty values
    var test = new TableObject();

    assert.throws(function() {
        test.setColumn(0, []);
    });

    var test = new TableObject(4, 4);

    for (var i = 0; i < window.emptyValuesCount; i++) {

        assert.throws(function() {
            test.setColumn(window.emptyValues[i]);
        });

        assert.throws(function() {
            test.setColumn(0, window.emptyValues[i]);
        });
    }

    // Test ok values
    var test = new TableObject(1, 1);

    test.setColumn(0, ['a']);
    assert.ok(test.countRows() === 1);
    assert.ok(test.countColumns() === 1);
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(0), ['a']));

    var test = new TableObject(4, 4);

    test.setColumn(1, [1, 2, 3, 4]);
    test.setColumn(3, [1, 2, 3, 4]);
    assert.ok(test.countRows() === 4);
    assert.ok(test.countColumns() === 4);
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(0), [null, null, null, null]));
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(1), [1, 2, 3, 4]));
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(2), [null, null, null, null]));
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(3), [1, 2, 3, 4]));

    test.setColumn(1, ['a', 'b', 'c', 'd']);
    test.setColumn(2, [5, 6, 7, 8]);
    assert.ok(ArrayUtils.isEqualTo([null, null, null, null], test.getColumn(0)));
    assert.ok(ArrayUtils.isEqualTo(['a', 'b', 'c', 'd'], test.getColumn(1)));
    assert.ok(ArrayUtils.isEqualTo([5, 6, 7, 8], test.getColumn(2)));
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3, 4], test.getColumn(3)));

    // Test wrong values
    assert.throws(function() {
        test.setColumn(-1, ['a', 'b', 'c', 'd']);
    });

    assert.throws(function() {
        test.setColumn(4, ['a', 'b', 'c', 'd']);
    });

    assert.throws(function() {
        test.setColumn(5, ['a', 'b', 'c', 'd']);
    });

    assert.throws(function() {
        test.setColumn('ooo', ['a', 'b', 'c', 'd']);
    });
    
    assert.throws(function() {
        test.setColumn(0, ['a', 'b', 'c']);
    });

    assert.throws(function() {
        test.setColumn(0, ['a', 'b', 'c', 'd', 'e']);
    });

    // Test exceptions
    // already tested
});


/**
 * testRemoveColumn
 */
QUnit.test("testRemoveColumn", function(assert){
    
    // Test empty values
    var test = new TableObject();

    assert.throws(function() {
        test.removeColumn(0);
    });

    var test = new TableObject(4, 4);

    for (var i = 0; i < window.emptyValuesCount; i++) {

        assert.throws(function() {
            test.removeColumn(window.emptyValues[i]);
        });
    }

    // Test ok values
    var test = new TableObject(1, 1);
    test.setColumn(0, [1]);
    test.removeColumn(0);
    assert.ok(test.countRows() === 0);
    assert.ok(test.countColumns() === 0);
    assert.ok(test.countCells() === 0);

    var test = new TableObject(50, 50);

    for (var i = test.countColumns() - 1; i >= 0; i--) {

        test.removeColumn(i);

        assert.ok(test.countRows() === (i == 0 ? 0 : 50));
        assert.ok(test.countColumns() === i);
        assert.ok(test.countCells() === 50 * i);
    }

    var test = new TableObject(4, 4);
    test.setColumnNames(['c1', 'c2', 'c3', 'c4']);
    test.removeColumn('c2');
    assert.ok(test.countRows() === 4);
    assert.ok(test.countColumns() === 3);
    assert.ok(test.countCells() === 12);
    test.removeColumn('c4');
    assert.ok(test.countRows() === 4);
    assert.ok(test.countColumns() === 2);
    assert.ok(test.countCells() === 8);
    test.removeColumn('c3');
    assert.ok(test.countRows() === 4);
    assert.ok(test.countColumns() === 1);
    assert.ok(test.countCells() === 4);

    var test = new TableObject(3, 3);
    test.setColumnNames(['c1', 'c2', 'c3']);
    test.setColumn(0, ['1', '2', '3']);
    test.setColumn(1, [4, 5, 6]);
    test.setColumn(2, ['7', 8, '9']);
    test.removeColumn(1);
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(0), ['1', '2', '3']));
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(1), ['7', 8, '9']));
    assert.ok(test.countRows() === 3);
    assert.ok(test.countColumns() === 2);
    assert.ok(test.countCells() === 6);
    test.removeColumn(1);
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(0), ['1', '2', '3']));
    assert.ok(test.countRows() === 3);
    assert.ok(test.countColumns() === 1);
    assert.ok(test.countCells() === 3);
    test.removeColumn(0);
    assert.ok(test.countRows() === 0);
    assert.ok(test.countColumns() === 0);
    assert.ok(test.countCells() === 0);

    // Test wrong values
    var test = new TableObject(50, 50);

    assert.throws(function() {
        test.removeColumn(-1);
    });

    assert.throws(function() {
        test.removeColumn(50);
    });

    assert.throws(function() {
        test.removeColumn('col');
    });

    // Test exceptions
    // Already tested
});


/**
 * testGetCell
 */
QUnit.test("testGetCell", function(assert){

    // Test empty values
    var test = new TableObject();

    assert.throws(function() {
        test.getCell(0, 0);
    });

    var test = new TableObject(4, 4);

    for (var i = 0; i < window.emptyValuesCount; i++) {

        assert.throws(function() {
            test.getCell(window.emptyValues[i], 0);
        });

        assert.throws(function() {
            test.getCell(0, window.emptyValues[i]);
        });
    }

    // Test ok values
    test = new TableObject(3, 3);
    test.setColumnNames(['c1', 'c2', 'c3']);
    test.setColumn(0, ['1', '2', '3']);
    test.setColumn(1, [4, 5, 6]);
    test.setColumn(2, ['7', 8, '9']);
    assert.ok(test.getCell(0, 0) === '1');
    assert.ok(test.getCell(0, 'c1') === '1');
    assert.ok(test.getCell(2, 0) === '3');
    assert.ok(test.getCell(2, 'c1') === '3');
    assert.ok(test.getCell(1, 1) === 5);
    assert.ok(test.getCell(1, 'c2') === 5);
    assert.ok(test.getCell(0, 2) === '7');
    assert.ok(test.getCell(0, 'c3') === '7');
    assert.ok(test.getCell(2, 2) === '9');
    assert.ok(test.getCell(2, 'c3') === '9');

    test.removeColumn('c2');
    assert.ok(test.getCell(0, 0) === '1');
    assert.ok(test.getCell(0, 'c1') === '1');
    assert.ok(test.getCell(2, 0) === '3');
    assert.ok(test.getCell(2, 'c1') === '3');
    assert.ok(test.getCell(1, 1) === 8);
    assert.ok(test.getCell(1, 'c3') === 8);
    assert.ok(test.getCell(0, 1) === '7');
    assert.ok(test.getCell(0, 'c3') === '7');

    test.removeRow(0);
    assert.ok(test.getCell(0, 0) === '2');
    assert.ok(test.getCell(0, 'c1') === '2');
    assert.ok(test.getCell(1, 1) === '9');
    assert.ok(test.getCell(1, 'c3') === '9');
    assert.ok(test.countRows() === 2);
    assert.ok(test.countColumns() === 2);
    assert.ok(test.countCells() === 4);

    test.removeRow(0);
    assert.ok(test.getCell(0, 0) === '3');
    assert.ok(test.getCell(0, 'c1') === '3');
    assert.ok(test.getCell(0, 1) === '9');
    assert.ok(test.getCell(0, 'c3') === '9');
    assert.ok(test.countRows() === 1);
    assert.ok(test.countColumns() === 2);
    assert.ok(test.countCells() === 2);

    // Test wrong values
    assert.throws(function() {
        test.getCell(0, -1);
    });

    assert.throws(function() {
        test.getCell(-1, 0);
    });

    assert.throws(function() {
        test.getCell(0, 3);
    });

    assert.throws(function() {
        test.getCell(3, 0);
    });

    // Test exceptions
    // Already tested
});


/**
 * testSetCell
 */
QUnit.test("testSetCell", function(assert){
    
    // Test empty values
    var test = new TableObject();

    assert.throws(function() {
        test.setCell(0, 0, null);
    });

    var test = new TableObject(4, 4);

    for (var i = 0; i < window.emptyValuesCount; i++) {

        assert.throws(function() {
            test.setCell(window.emptyValues[i], 0, null);
        });

        assert.throws(function() {
            test.setCell(0, window.emptyValues[i], null);
        });

        assert.ok(test.setCell(0, 0, window.emptyValues[i]) === window.emptyValues[i]);
        assert.ok(test.getCell(0, 0) === window.emptyValues[i]);
    }

    // Test ok values
    var test = new TableObject(10, 10);
    assert.ok(test.setCell(0, 0, 'value') === 'value');
    assert.ok(test.getCell(0, 0) === 'value');
    assert.ok(test.setCell(5, 5, 'value5') === 'value5');
    assert.ok(test.getCell(5, 5) === 'value5');
    assert.ok(ArrayUtils.isEqualTo([null, null, null, null, null, 'value5', null, null, null, null], test.getColumn(5)));

    assert.ok(test.setCell(0, 9, '1') === '1');
    assert.ok(test.setCell(1, 9, '2') === '2');
    assert.ok(test.setCell(2, 9, '3') === '3');
    assert.ok(test.setCell(3, 9, '4') === '4');
    assert.ok(test.setCell(4, 9, '5') === '5');
    assert.ok(ArrayUtils.isEqualTo(['1', '2', '3', '4', '5', null, null, null, null, null], test.getColumn(9)));

    test.setColumnName(7, 'col7');
    assert.ok(test.setCell(3, 'col7', '3 value') === '3 value');
    assert.ok(test.setCell(8, 'col7', '8 value') === '8 value');
    assert.ok(test.getCell(3, 7) === '3 value');
    assert.ok(test.getCell(3, 'col7') === '3 value');
    assert.ok(test.getCell(8, 7) === '8 value');
    assert.ok(test.getCell(8, 'col7') === '8 value');
    assert.ok(ArrayUtils.isEqualTo([null, null, null, '3 value', null, null, null, null, '8 value', null], test.getColumn(7)));
    assert.ok(ArrayUtils.isEqualTo([null, null, null, '3 value', null, null, null, null, '8 value', null], test.getColumn('col7')));

    // Test wrong values
    assert.throws(function() {
        test.setCell(-1, 0, 'v');
    });

    assert.throws(function() {
        test.setCell(11, 0, 'v');
    });

    assert.throws(function() {
        test.setCell(0, -1, 'v');
    });

    assert.throws(function() {
        test.setCell(0, 11, 'v');
    });

    assert.throws(function() {
        test.setCell(0, 'no', 'v');
    });

    assert.throws(function() {
        test.setCell('no', 0, 'v');
    });

    // Test exceptions
    // Already tested
});


/**
 * testGetRow
 */
QUnit.test("testGetRow", function(assert){
    
    // Test empty values
    var test = new TableObject();

    assert.throws(function() {
        test.getRow(0);
    });

    for (var i = 0; i < window.emptyValuesCount; i++) {

        assert.throws(function() {
            test.getRow(window.emptyValues[i]);
        });
    }

    // Test ok values
    var test = new TableObject(1, 1);
    assert.ok(ArrayUtils.isEqualTo(test.getRow(0), [null]));

    var test = new TableObject(5, 5);
    test.setCell(1, 0, 1);
    test.setCell(1, 1, 2);
    test.setCell(1, 2, 3);
    assert.ok(ArrayUtils.isEqualTo(test.getRow(0), [null, null, null, null, null]));
    assert.ok(ArrayUtils.isEqualTo(test.getRow(1), [1, 2, 3, null, null]));

    var test = new TableObject(5, 5);
    assert.ok(ArrayUtils.isEqualTo(test.getRow(0), [null, null, null, null, null]));
    test.setRow(2, [1, 2, 3, 4, 5]);
    assert.ok(ArrayUtils.isEqualTo(test.getRow(0), [null, null, null, null, null]));
    assert.ok(ArrayUtils.isEqualTo(test.getRow(2), [1, 2, 3, 4, 5]));
    test.setRow(4, [1, 2, 3, 4, 5]);
    assert.ok(ArrayUtils.isEqualTo(test.getRow(3), [null, null, null, null, null]));
    assert.ok(ArrayUtils.isEqualTo(test.getRow(4), [1, 2, 3, 4, 5]));

    // Test wrong values
    assert.throws(function() {
        test.getRow(-1);
    });

    assert.throws(function() {
        test.getRow(11);
    });

    assert.throws(function() {
        test.getRow('string');
    });

    // Test exceptions
    // Already tested
});


/**
 * testAddRows
 */
QUnit.test("testAddRows", function(assert){
    
    // Test empty values
    var test = new TableObject();

    assert.throws(function() {
        test.addRows(0);
    });

    for (var i = 0; i < window.emptyValuesCount; i++) {

        assert.throws(function() {
            test.addRows(window.emptyValues[i]);
        });

        if(ArrayUtils.isArray(window.emptyValues[i])){

            assert.throws(function() {
                test.addRows(1, window.emptyValues[i]);
            });
        }
    }

    // Test ok values
    var test = new TableObject();
    assert.ok(test.addRows(1));
    assert.ok(test.countRows() === 1);
    assert.ok(ArrayUtils.isEqualTo(test.getRow(0), []));

    var test = new TableObject(4, 4);
    assert.ok(test.addRows(2));
    assert.ok(test.countRows() === 6);
    assert.ok(ArrayUtils.isEqualTo(test.getRow(3), [null, null, null, null]));

    assert.ok(test.addRows(1));
    assert.ok(test.countRows() === 7);

    assert.ok(test.addRows(1, 2));
    assert.ok(test.countRows() === 8);

    assert.ok(test.addRows(1, 0));
    assert.ok(test.countRows() === 9);

    var test = new TableObject(1, 3);
    test.setRow(0, [1, 2, 3]);
    assert.ok(1 ===test.countRows());

    assert.ok(test.addRows(1, 0));
    assert.ok(2 === test.countRows());
    assert.ok(ArrayUtils.isEqualTo([null, null, null], test.getRow(0)));
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3], test.getRow(1)));
    assert.ok(test.addRows(1, 0));
    assert.ok(ArrayUtils.isEqualTo([null, null, null], test.getRow(0)));
    assert.ok(ArrayUtils.isEqualTo([null, null, null], test.getRow(1)));
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3], test.getRow(2)));
    assert.ok(3 === test.countRows());

    assert.ok(test.addRows(1, 2));
    assert.ok(ArrayUtils.isEqualTo([null, null, null], test.getRow(0)));
    assert.ok(ArrayUtils.isEqualTo([null, null, null], test.getRow(1)));
    assert.ok(ArrayUtils.isEqualTo([null, null, null], test.getRow(2)));
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3], test.getRow(3)));
    assert.ok(test.countRows() === 4);

    var test = new TableObject();
    test.addRows(3);
    test.addColumns(3);
    test.setRow(0, [1, 2, 3]);
    test.setRow(1, [4, 5, 6]);
    test.setRow(2, [7, 8, 9]);
    assert.ok(test.countRows() === 3);
    assert.ok(test.countColumns() === 3);
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3], test.getRow(0)));
    assert.ok(ArrayUtils.isEqualTo([4, 5, 6], test.getRow(1)));
    assert.ok(ArrayUtils.isEqualTo([7, 8, 9], test.getRow(2)));
    test.addRows(1, 2);
    test.setRow(2, ['x', 'y', 'z']);
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3], test.getRow(0)));
    assert.ok(ArrayUtils.isEqualTo([4, 5, 6], test.getRow(1)));
    assert.ok(ArrayUtils.isEqualTo(['x', 'y', 'z'], test.getRow(2)));
    assert.ok(ArrayUtils.isEqualTo([7, 8, 9], test.getRow(3)));
    assert.ok(test.countRows() === 4);
    assert.ok(test.countColumns() === 3);

    // Test wrong values
    var test = new TableObject(3, 3);

    window.wrongValues = [0, -1, 1.1, [1, 2, 3, 4]];
    window.wrongValuesCount = window.wrongValues.length;

    for (var i = 0; i < window.wrongValuesCount; i++) {

        assert.throws(function() {
            test.addRows(window.wrongValues[i]);
        });
    }

    assert.throws(function() {
        test.addRows(1, 4);
    });

    // Test exceptions
    // Already tested
});


/**
 * testSetRow
 */
QUnit.test("testSetRow", function(assert){
    
    // Test empty values
    test = new TableObject();

    assert.throws(function() {
        test.setRow(0, []);
    });

    var test = new TableObject(4, 4);

    for (var i = 0; i < window.emptyValuesCount; i++) {

        assert.throws(function() {
            test.setRow(window.emptyValues[i]);
        });

        assert.throws(function() {
            test.setRow(0, window.emptyValues[i]);
        });
    }

    // Test ok values
    var test = new TableObject(1, 1);

    test.setRow(0, ['a']);
    assert.ok(test.countRows() === 1);
    assert.ok(test.countColumns() === 1);
    assert.ok(ArrayUtils.isEqualTo(test.getRow(0), ['a']));
    assert.ok(ArrayUtils.isEqualTo(test.getColumn(0), ['a']));

    var test = new TableObject(4, 4);

    test.setRow(1, [1, 2, 3, 4]);
    test.setRow(3, [1, 2, 3, 4]);
    assert.ok(test.countRows() === 4);
    assert.ok(test.countColumns() === 4);
    assert.ok(ArrayUtils.isEqualTo(test.getRow(0), [null, null, null, null]));
    assert.ok(ArrayUtils.isEqualTo(test.getRow(1), [1, 2, 3, 4]));
    assert.ok(ArrayUtils.isEqualTo(test.getRow(2), [null, null, null, null]));
    assert.ok(ArrayUtils.isEqualTo(test.getRow(3), [1, 2, 3, 4]));

    test.setRow(1, ['a', 'b', 'c', 'd']);
    test.setRow(2, [5, 6, 7, 8]);
    assert.ok(ArrayUtils.isEqualTo([null, null, null, null], test.getRow(0)));
    assert.ok(ArrayUtils.isEqualTo(['a', 'b', 'c', 'd'], test.getRow(1)));
    assert.ok(ArrayUtils.isEqualTo([5, 6, 7, 8], test.getRow(2)));
    assert.ok(ArrayUtils.isEqualTo([1, 2, 3, 4], test.getRow(3)));

    // Test wrong values
    assert.throws(function() {
        test.setRow(-1, ['a', 'b', 'c', 'd']);
    });

    assert.throws(function() {
        test.setRow(4, ['a', 'b', 'c', 'd']);
    });

    assert.throws(function() {
        test.setRow(5, ['a', 'b', 'c', 'd']);
    });

    assert.throws(function() {
        test.setRow('ooo', ['a', 'b', 'c', 'd']);
    });

    assert.throws(function() {
        test.setRow(0, ['a', 'b', 'c']);
    });

    assert.throws(function() {
        test.setRow(0, ['a', 'b', 'c', 'd', 'e']);
    });

    // Test exceptions
    // already tested
});


/**
 * testRemoveRow
 */
QUnit.test("testRemoveRow", function(assert){
    
    // Test empty values
    var test = new TableObject();

    assert.throws(function() {
        test.removeRow(0);
    });

    var test = new TableObject(4, 4);

    for (var i = 0; i < window.emptyValuesCount; i++) {

        assert.throws(function() {
            test.removeRow(window.emptyValues[i]);
        });
    }

    // Test ok values
    var test = new TableObject(1, 1);
    test.setRow(0, [1]);
    test.removeRow(0);
    assert.ok(test.countRows() === 0);
    assert.ok(test.countColumns() === 0);
    assert.ok(test.countCells() === 0);

    var test = new TableObject(50, 50);

    for (var i = test.countRows() - 1; i >= 0; i--) {

        test.removeRow(i);

        assert.ok(test.countColumns() === (i == 0 ? 0 : 50));
        assert.ok(test.countRows() === i);
        assert.ok(test.countCells() === 50 * i);
    }

    var test = new TableObject(3, 3);
    test.setRow(0, ['1', '2', '3']);
    test.setRow(1, [4, 5, 6]);
    test.setRow(2, ['7', 8, '9']);
    test.removeRow(1);
    assert.ok(ArrayUtils.isEqualTo(test.getRow(0), ['1', '2', '3']));
    assert.ok(ArrayUtils.isEqualTo(test.getRow(1), ['7', 8, '9']));
    assert.ok(test.countRows() === 2);
    assert.ok(test.countColumns() === 3);
    assert.ok(test.countCells() === 6);
    test.removeRow(1);
    assert.ok(ArrayUtils.isEqualTo(test.getRow(0), ['1', '2', '3']));
    assert.ok(test.countRows() === 1);
    assert.ok(test.countColumns() === 3);
    assert.ok(test.countCells() === 3);
    test.removeRow(0);
    assert.ok(test.countRows() === 0);
    assert.ok(test.countColumns() === 0);
    assert.ok(test.countCells() === 0);

    // Test wrong values
    var test = new TableObject(50, 50);

    assert.throws(function() {
        test.removeRow(-1);
    });

    assert.throws(function() {
        test.removeRow(50);
    });

    assert.throws(function() {
        test.removeRow('col');
    });

    // Test exceptions
    // Already tested
});


/**
 * testCountRows
 */
QUnit.test("testCountRows", function(assert){
    
    // Test empty values
    var test = new TableObject();
    assert.ok(test.countRows() === 0);

    var test = new TableObject(0, 0);
    assert.ok(test.countRows() === 0);

    // Test ok values
    var test = new TableObject();
    test.addColumns(4);
    test.addRows(4);
    assert.ok(test.countRows() === 4);
    test.removeRow(0);
    assert.ok(test.countRows() === 3);
    test.removeRow(1);
    test.removeColumn(1);
    assert.ok(test.countRows() === 2);
    test.addRows(5, 1);
    assert.ok(test.countRows() === 7);
    test.removeRow(6);
    test.removeColumn(2);
    assert.ok(test.countRows() === 6);

    // Test wrong values
    // Not necessary

    // Test exceptions
    // Not necessary
});


/**
 * testCountColumns
 */
QUnit.test("testCountColumns", function(assert){
    
 // Test empty values
    var test = new TableObject();
    assert.ok(test.countColumns() === 0);

    var test = new TableObject(0, 0);
    assert.ok(test.countColumns() === 0);

    // Test ok values
    test = new TableObject();
    test.addColumns(4);
    test.addRows(4);
    assert.ok(test.countColumns() === 4);
    test.removeColumn(0);
    assert.ok(test.countColumns() === 3);
    test.removeRow(1);
    test.removeColumn(1);
    assert.ok(test.countColumns() === 2);
    test.addColumns(5, [], 1);
    assert.ok(test.countColumns() === 7);
    test.removeRow(2);
    test.removeColumn(6);
    assert.ok(test.countColumns() === 6);

    // Test wrong values
    // Not necessary

    // Test exceptions
    // Not necessary
});


/**
 * testCountCells
 */
QUnit.test("testCountCells", function(assert){
    
    // Test empty values
    var test = new TableObject();
    assert.ok(test.countCells() === 0);

    var test = new TableObject(0, 0);
    assert.ok(test.countCells() === 0);

    // Test ok values
    var test = new TableObject();
    test.addColumns(4);
    test.addRows(4);
    assert.ok(test.countCells() === 16);
    test.removeColumn(0);
    assert.ok(test.countCells() === 12);
    test.removeRow(1);
    test.removeColumn(1);
    assert.ok(test.countCells() === 6);
    test.addColumns(5, [], 1);
    assert.ok(test.countCells() === 21);
    test.removeRow(2);
    test.removeColumn(6);
    assert.ok(test.countCells() === 12);

    // Test wrong values
    // Not necessary

    // Test exceptions
    // Not necessary
});