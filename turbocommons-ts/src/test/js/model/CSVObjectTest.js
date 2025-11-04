"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


QUnit.module("CSVObjectTest", {

    before : function(assert){

        window.NumericUtils = org_turbocommons.NumericUtils;
        window.StringUtils = org_turbocommons.StringUtils;
        window.ArrayUtils = org_turbocommons.ArrayUtils;
        window.CSVObject = org_turbocommons.CSVObject;
        window.JavaPropertiesObject = org_turbocommons.JavaPropertiesObject;
        
        window.basePath = './resources/model/csvObject/';

        // Load all the csv and properties files
        window.csvFiles = [];
        window.csvFilesData = [];
        window.propertiesFiles = [];
        window.propertiesFilesData = [];
        
        var httpManager = new org_turbocommons.HTTPManager();

        var done = assert.async();
        
        httpManager.loadResourcesFromList(basePath + '_folder-list.txt', basePath,
            function(filesList, filesData){
        
                for(var i = 0; i < filesList.length; i++){
                
                    var file = filesList[i];
                    
                    if(StringUtils.getPathExtension(file) === 'csv'){
    
                        window.csvFiles.push(file);
                        window.csvFilesData.push(filesData[i]);
                    }
                    
                    if(StringUtils.getPathExtension(file) === 'properties'){
                        
                        window.propertiesFiles.push(file);
                        window.propertiesFilesData.push(filesData[i]);
                    }
                }
    
                done();
                
            }, function(errorUrl, errorMsg, errorCode){
                
                assert.ok(false, 'Error loading file ' + errorUrl);
                done();
            });
    },

    beforeEach : function(){

        window.emptyValues = [null, [], {}, 0];
        window.emptyValuesCount = window.emptyValues.length;

        window.wrongValues = [123, [1, 2, 3], ['asdf'], new Error()];
        window.wrongValuesCount = window.wrongValues.length;
    },

    afterEach : function(){

        delete window.emptyValues;
        delete window.emptyValuesCount;

        delete window.wrongValues;
        delete window.wrongValuesCount;
    },

    after : function(){

        delete window.NumericUtils;
        delete window.StringUtils;
        delete window.ArrayUtils;
        delete window.CSVObject;
        delete window.JavaPropertiesObject;
        
        delete window.basePath;

        delete window.csvFiles;
        delete window.csvFilesData;
        delete window.propertiesFiles;
        delete window.propertiesFilesData;
    }
});


/**
 * testConstruct
 */
QUnit.test("testConstruct", function(assert){

    // Test empty values
    var sut = new CSVObject();
    assert.strictEqual(0, sut.countColumns());
    assert.strictEqual(0, sut.countRows());

    sut = new CSVObject('');
    assert.strictEqual(0, sut.countColumns());
    assert.strictEqual(0, sut.countRows());

    sut = new CSVObject('     ');
    assert.strictEqual(0, sut.countColumns());
    assert.strictEqual(0, sut.countRows());

    sut = new CSVObject("\n\n\n");
    assert.strictEqual(0, sut.countColumns());
    assert.strictEqual(0, sut.countRows());

    for (var i = 0; i < emptyValuesCount; i++) {

        assert.throws(function() {
            new CSVObject(emptyValues[i]);
        }, /constructor expects a string value/);
    }

    // Test ok values

    // Single value csv
    sut = new CSVObject('value');
    assert.strictEqual('value', sut.getCell(0, 0));
    assert.strictEqual(1, sut.countRows());
    assert.strictEqual(1, sut.countColumns());
    assert.strictEqual('value', sut.getCell(0, 0));
    assert.ok(sut.isEqualTo('value'));

    // Simple one row empty csv
    sut = new CSVObject(',"",');
    assert.strictEqual('', sut.getCell(0, 0));
    assert.strictEqual('', sut.getCell(0, 1));
    assert.strictEqual('', sut.getCell(0, 2));
    assert.ok(sut.isEqualTo(',,'));

    // Simple one row empty csv with headers
    sut = new CSVObject("c1,c2,c3\r\n,,", true);
    assert.strictEqual('', sut.getCell(0, 'c1'));
    assert.strictEqual('', sut.getCell(0, 'c2'));
    assert.strictEqual('', sut.getCell(0, 'c3'));
    assert.strictEqual('', sut.getCell(0, 0));
    assert.strictEqual('', sut.getCell(0, 1));
    assert.strictEqual('', sut.getCell(0, 2));
    assert.ok(sut.isEqualTo("c1,\"c2\",c3\n,,"));

    // Simple one row csv without headers
    sut = new CSVObject('a,b,c');
    assert.strictEqual('a', sut.getCell(0, 0));
    assert.strictEqual('b', sut.getCell(0, 1));
    assert.strictEqual('c', sut.getCell(0, 2));
    assert.ok(sut.isEqualTo('a,b,c'));

    // Simple one row csv with headers
    sut = new CSVObject("c1,c2,c3\n1,2,3", true);
    assert.strictEqual('1', sut.getCell(0, 'c1'));
    assert.strictEqual('2', sut.getCell(0, 'c2'));
    assert.strictEqual('3', sut.getCell(0, 'c3'));
    assert.strictEqual('1', sut.getCell(0, 0));
    assert.strictEqual('2', sut.getCell(0, 1));
    assert.strictEqual('3', sut.getCell(0, 2));
    assert.ok(sut.isEqualTo("c1,c2,c3\r\n1,2,3"));

    // Simple one row csv without headers and scaped fields
    sut = new CSVObject('"a","b","c"');
    assert.strictEqual('a', sut.getCell(0, 0));
    assert.strictEqual('b', sut.getCell(0, 1));
    assert.strictEqual('c', sut.getCell(0, 2));
    assert.ok(sut.isEqualTo('a,b,c'));

    // Simple one row csv with headers and scaped fields
    sut = new CSVObject("c1,c2,c3\r\"a\",\"b\",\"c\"", true);
    assert.strictEqual('a', sut.getCell(0, 'c1'));
    assert.strictEqual('b', sut.getCell(0, 'c2'));
    assert.strictEqual('c', sut.getCell(0, 'c3'));
    assert.strictEqual('a', sut.getCell(0, 0));
    assert.strictEqual('b', sut.getCell(0, 1));
    assert.strictEqual('c', sut.getCell(0, 2));
    assert.ok(sut.isEqualTo("c1,c2,c3\na,b,c"));

    // Simple csv without headers and edge cases
    sut = new CSVObject(' a ,b  ,c  ');
    assert.strictEqual(' a ', sut.getCell(0, 0));
    assert.strictEqual('b  ', sut.getCell(0, 1));
    assert.strictEqual('c  ', sut.getCell(0, 2));
    assert.ok(sut.isEqualTo(' a ,"b  ",c  '));

    // Multiple lines csv with different newline characters (windows: \r\n, Linux/Unix: \n, Mac: \r)
    sut = new CSVObject("1,2,3\na,b,c\r\n4,5,6\r");
    assert.strictEqual('1', sut.getCell(0, 0));
    assert.strictEqual('2', sut.getCell(0, 1));
    assert.strictEqual('3', sut.getCell(0, 2));
    assert.strictEqual('a', sut.getCell(1, 0));
    assert.strictEqual('b', sut.getCell(1, 1));
    assert.strictEqual('c', sut.getCell(1, 2));
    assert.strictEqual('4', sut.getCell(2, 0));
    assert.strictEqual('5', sut.getCell(2, 1));
    assert.strictEqual('6', sut.getCell(2, 2));
    assert.ok(sut.isEqualTo("1,2,3\na,b,c\r\r4,5,6\r\n"));
    assert.ok(sut.countColumns() === 3);
    assert.ok(sut.countRows() === 3);

    // Simple csv without headers and scaped fields and characters with edge cases
    sut = new CSVObject(' """"" 1",",,,2",    "3", "4,"   ,  "5 " ');
    assert.strictEqual('"" 1', sut.getCell(0, 0));
    assert.strictEqual(',,,2', sut.getCell(0, 1));
    assert.strictEqual('3', sut.getCell(0, 2));
    assert.strictEqual('4,', sut.getCell(0, 3));
    assert.strictEqual('5 ', sut.getCell(0, 4));
    assert.ok(sut.isEqualTo('""""" 1",",,,2","3","4,","5 "'));

    // Simple two row csv without headers and scaped fields and characters
    sut = new CSVObject("\"1\",\"2\",\"3\"\r\n\"a\"\"a\",\"b\",\"c\"");
    assert.strictEqual('1', sut.getCell(0, 0));
    assert.strictEqual('2', sut.getCell(0, 1));
    assert.strictEqual('3', sut.getCell(0, 2));
    assert.strictEqual('a"a', sut.getCell(1, 0));
    assert.strictEqual('b', sut.getCell(1, 1));
    assert.strictEqual('c', sut.getCell(1, 2));
    assert.ok(sut.isEqualTo("\"1\",\"2\",\"3\"\r\n\"a\"\"a\",\"b\",\"c\""));

    // Simple two row csv with headers and mixed scaped and non scaped fields and characters
    sut = new CSVObject("c1,\"c,\"\"2\",c3\r1,\"2\", 3 \r\n\"a \"\",a\",b,\"c\"", true);
    assert.ok(ArrayUtils.isEqualTo(['c1', 'c,"2', 'c3'], sut.getColumnNames()));
    assert.strictEqual('1', sut.getCell(0, 'c1'));
    assert.strictEqual('2', sut.getCell(0, 'c,"2'));
    assert.strictEqual(' 3 ', sut.getCell(0, 'c3'));
    assert.strictEqual('1', sut.getCell(0, 0));
    assert.strictEqual('2', sut.getCell(0, 1));
    assert.strictEqual(' 3 ', sut.getCell(0, 2));
    assert.strictEqual('a ",a', sut.getCell(1, 'c1'));
    assert.strictEqual('b', sut.getCell(1, 'c,"2'));
    assert.strictEqual('c', sut.getCell(1, 'c3'));
    assert.strictEqual('a ",a', sut.getCell(1, 0));
    assert.strictEqual('b', sut.getCell(1, 1));
    assert.strictEqual('c', sut.getCell(1, 2));
    assert.ok(sut.isEqualTo("c1,\"c,\"\"2\",c3\r1,\"2\", 3 \r\n\"a \"\",a\",b,\"c\""));

    // test csv files from resources
    // Assertion expected values are stored on a properties file for each one of the csv files.
    // It must have exactly the same name but with .properties extension
    for(var i = 0; i < csvFiles.length; i++){

        var csvFileName = csvFiles[i];
        var csvFileData = csvFilesData[i];

        sut = new CSVObject(csvFileData, StringUtils.countStringOccurences(csvFileName, 'WithHeader') === 1);

        var propertiesFileName = StringUtils.getPathElementWithoutExt(csvFileName) + '.properties';
        var csvFileAssertions = new JavaPropertiesObject(propertiesFilesData[propertiesFiles.indexOf(propertiesFileName)]);

        assert.strictEqual(Number(csvFileAssertions.get('rows')), sut.countRows(), 'File: ' + csvFileName);
        assert.strictEqual(Number(csvFileAssertions.get('cols')), sut.countColumns(), 'File: ' + csvFileName);

        for (var key of csvFileAssertions.getKeys()) {
        
            if(key !== 'rows' && key !== 'cols'){

                var rowCol = key.split('-');

                var columnFormatted = NumericUtils.isNumeric(rowCol[1]) ? Number(rowCol[1]) : rowCol[1];

                var expected = csvFileAssertions.get(key);
                var value = sut.getCell(Number(rowCol[0]), columnFormatted);

                assert.strictEqual(expected, value, 'File: ' + csvFileName + ' row and col: ' + key);
            }
        }
    }

    // Test wrong values
    for (var i = 0; i < wrongValuesCount; i++) {

        assert.throws(function() {
            new CSVObject(wrongValues[i]);
        }, /constructor expects a string value/);
    }

    // Test exceptions
    // Already tested
});


/**
 * testSetCell
 */
QUnit.test("testSetCell", function(assert){

    // Test empty values
    var sut = new CSVObject();
    sut.addColumns(5);
    sut.addRows(5);

    assert.ok(sut.getCell(0, 0) === '');
    assert.ok(sut.setCell(0, 0, '') === '');
    assert.ok(sut.getCell(0, 0) === '');

    for (var i = 0; i < emptyValuesCount; i++) {

        assert.throws(function() {
            sut.setCell(0, 0, emptyValues[i]);
        }, /value must be a string/);
    }

    // Test ok values
    assert.ok(sut.getCell(0, 2) === '');
    assert.ok(sut.setCell(0, 2, 'somevalue') === 'somevalue');
    assert.ok(sut.getCell(0, 2) === 'somevalue');

    assert.ok(sut.getCell(0, 4) === '');
    assert.ok(sut.setCell(0, 4, 'somevalue4') === 'somevalue4');
    assert.ok(sut.getCell(0, 4) === 'somevalue4');

    assert.ok(sut.getCell(2, 0) === '');
    assert.ok(sut.setCell(2, 0, '2-0') === '2-0');
    assert.ok(sut.getCell(2, 0) === '2-0');

    assert.ok(sut.getCell(2, 2) === '');
    assert.ok(sut.setCell(2, 2, '2-2') === '2-2');
    assert.ok(sut.getCell(2, 2) === '2-2');

    assert.ok(sut.getCell(4, 4) === '');
    assert.ok(sut.setCell(4, 4, '4-4') === '4-4');
    assert.ok(sut.getCell(4, 4) === '4-4');

    // Test wrong values
    assert.throws(function() {
        sut.setCell(-1, 0, '');
    }, /Invalid row value/);

    assert.throws(function() {
        sut.setCell(10, 0, '');
    }, /Invalid row value/);

    assert.throws(function() {
        sut.setCell(0, -1, '');
    }, /Invalid column value/);

    assert.throws(function() {
        sut.setCell(0, 10, '');
    }, /Invalid column value/);

    assert.throws(function() {
        sut.setCell(0, 0, 10);
    }, /value must be a string/);

    assert.throws(function() {
        sut.setCell(0, 0, {});
    }, /value must be a string/);

    // Test exceptions
    // Already tested
});


/**
 * testIsCSV
 */
QUnit.test("testIsCSV", function(assert){
    
    // Test empty values
    assert.notOk(CSVObject.isCSV(null));
    assert.ok(CSVObject.isCSV(''));
    assert.notOk(CSVObject.isCSV(0));
    assert.notOk(CSVObject.isCSV([]));
    assert.notOk(CSVObject.isCSV({}));
    assert.ok(CSVObject.isCSV('     '));
    assert.ok(CSVObject.isCSV("\n\n\n"));

    // Test ok values
    assert.ok(CSVObject.isCSV('value'));
    assert.ok(CSVObject.isCSV(',,'));
    assert.ok(CSVObject.isCSV("c1,c2,c3\r\n,,"));
    assert.ok(CSVObject.isCSV('a,b,c'));
    assert.ok(CSVObject.isCSV("c1,c2,c3\n1,2,3"));
    assert.ok(CSVObject.isCSV('"a","b","c"'));
    assert.ok(CSVObject.isCSV("c1,c2,c3\r\"a\",\"b\",\"c\""));
    assert.ok(CSVObject.isCSV(' a ,b  ,c  '));
    assert.ok(CSVObject.isCSV("1,2,3\na,b,c\r\n4,5,6\r"));
    assert.ok(CSVObject.isCSV(' """"" 1",",,,2",    "3", "4,"   ,  "5 " '));
    assert.ok(CSVObject.isCSV("\"1\",\"2\",\"3\"\r\n\"a\"\"a\",\"b\",\"c\""));
    assert.ok(CSVObject.isCSV("c1,\"c,\"\"2\",c3\r1,\"2\", 3 \r\n\"a \"\",a\",b,\"c\""));

    for (var i = 0; i < csvFiles.length; i++) {

        assert.ok(CSVObject.isCSV(csvFilesData[i]));
    }

    // Test wrong values
    assert.notOk(CSVObject.isCSV(12));
    assert.notOk(CSVObject.isCSV([1,4,5,6]));
    assert.notOk(CSVObject.isCSV(['  ']));
    assert.notOk(CSVObject.isCSV(new Error()));
    assert.notOk(CSVObject.isCSV(-1909));

    // Test exceptions
    // Not necessary
});


/**
 * testIsEqualTo
 */
QUnit.test("testIsEqualTo", function(assert){

    // Test empty values
    var sut = new CSVObject();

    assert.ok(sut.isEqualTo(''));
    assert.ok(sut.isEqualTo(new CSVObject()));

    assert.throws(function() {
        sut.isEqualTo(null);
    }, /csv does not contain valid csv data/);

    assert.throws(function() {
        sut.isEqualTo([]);
    }, /csv does not contain valid csv data/);

    assert.throws(function() {
        sut.isEqualTo({});
    }, /csv does not contain valid csv data/);

    assert.throws(function() {
        sut.isEqualTo(0);
    }, /csv does not contain valid csv data/);

    // Test ok and wrong values
    for (var i = 1; i < csvFiles.length; i++) {

        var fileData = '';
        
        if(i == 1){

            var previousFileData = csvFilesData[i-1];
            var previousSut = new CSVObject(previousFileData, StringUtils.countStringOccurences(csvFiles[i-1], 'WithHeader') === 1);

        }else{

            var previousFileData = fileData;
            var previousSut = sut;
        }

        fileData = csvFilesData[i];
        sut = new CSVObject(fileData, StringUtils.countStringOccurences(csvFiles[i], 'WithHeader') === 1);

        // TODO - This is added for performance reasons. If performance is improved on
        // isEqualTo method, this constraint can be removed
        if(sut.countRows() < 1000 && previousSut.countRows() < 1000){

            assert.ok(sut.isEqualTo(fileData));
            assert.ok(sut.isEqualTo(sut));

            assert.notOk(sut.isEqualTo(previousFileData));
            assert.notOk(sut.isEqualTo(previousSut));
        }
    }

    // Test exceptions
    assert.throws(function() {
        sut.isEqualTo(123234);
    }, /csv does not contain valid csv data/);

    assert.throws(function() {
        sut.isEqualTo([1,'dfgdfg']);
    }, /csv does not contain valid csv data/);

    assert.throws(function() {
        sut.isEqualTo(new Error());
    }, /csv does not contain valid csv data/);
});


/**
 * testToString
 */
QUnit.test("testToString", function(assert){
    
    // Test empty values
    var sut = new CSVObject();
    assert.ok(sut.toString() === '');

    sut = new CSVObject('');
    assert.ok(sut.toString() === '');

    sut = new CSVObject('      ');
    assert.ok(sut.toString() === '');

    sut = new CSVObject("\n\n\n\n");
    assert.ok(sut.toString() === '');

    sut = new CSVObject("\r\n\r\n\r\n\r\n");
    assert.ok(sut.toString() === '');

    // Test ok values

    // Single value csv
    sut = new CSVObject('value');
    assert.strictEqual('value', sut.toString());

    // Simple one row empty csv
    sut = new CSVObject(',,');
    assert.strictEqual(',,', sut.toString());

    // Simple one row empty csv with headers
    sut = new CSVObject("c1,c2,c3\r\n,,", true);
    assert.strictEqual("c1,c2,c3\r\n,,", sut.toString());

    // Simple one row csv without headers
    sut = new CSVObject('a,b,c');
    assert.strictEqual('a,b,c', sut.toString());

    // Simple one row csv with headers
    sut = new CSVObject("c1,c2,c3\n1,2,3", true);
    assert.strictEqual("c1,c2,c3\r\n1,2,3", sut.toString());

    // Simple one row csv without headers and scaped fields
    sut = new CSVObject('"a","b","c"');
    assert.strictEqual('a,b,c', sut.toString());

    // Simple one row csv with headers and scaped fields
    sut = new CSVObject("c1,c2,c3\r\"a\",\"b\",\"c\"", true);
    assert.strictEqual("c1,c2,c3\r\na,b,c", sut.toString());

    // Simple csv without headers and edge cases
    sut = new CSVObject(' a ,b  ,c  ');
    assert.strictEqual(' a ,b  ,c  ', sut.toString());

    // Multiple lines csv with different newline characters (windows: \r\n, Linux/Unix: \n, Mac: \r)
    sut = new CSVObject("1,2,3\na,b,c\r\n4,5,6\r");
    assert.strictEqual("1,2,3\r\na,b,c\r\n4,5,6", sut.toString());

    // Simple csv without headers and scaped fields and characters with edge cases
    sut = new CSVObject(' """"" 1",",,,2",    "3", "4,"   ,  "5 " ');
    assert.strictEqual('""""" 1",",,,2",3,"4,",5 ', sut.toString());

    // Simple two row csv without headers and scaped fields and characters
    sut = new CSVObject("\"1\",\"2\",\"3\"\r\n\"a\"\"a\",\"b\",\"c\"");
    assert.strictEqual("1,2,3\r\n\"a\"\"a\",b,c", sut.toString());

    // Simple two row csv with headers and mixed scaped and non scaped fields and characters
    sut = new CSVObject("c1,\"c,\"\"2\",c3\r1,\"2\", 3 \r\n\"a \"\",a\",b,\"c\"", true);
    assert.strictEqual("c1,\"c,\"\"2\",c3\r\n1,2, 3 \r\n\"a \"\",a\",b,c", sut.toString());

    for (var i = 0; i < csvFiles.length; i++) {

        sut = new CSVObject(csvFilesData[i], StringUtils.countStringOccurences(csvFiles[i], 'WithHeader') === 1);

        // TODO - This is added for performance reasons. If performance is improved on
        // isEqualTo method, this constraint can be removed
        if(sut.countRows() < 1000){

            assert.ok(sut.isEqualTo(sut.toString()), csvFiles[i] + ' has a problem');
            assert.ok(sut.isEqualTo(sut), csvFiles[i] + ' has a problem');
        }
    }

    // Test wrong values
    // Already tested at constructor test

    // Test exceptions
    // Already tested at constructor test
});