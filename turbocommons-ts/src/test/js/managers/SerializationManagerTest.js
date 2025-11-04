"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */



// *******************************************************************************************
// Following classes are defined to be used on several of this tests to be serialized or deserialized.
// They have been created by using the typescript playground online tool
// that automatically generates the javascript code from a typescript given 
// one. Original typescript source that generates these classes is found at:
// test/ts/resources/utils/serializationUtils/Typescript-source-test-classes.txt

// TODO - all these classes shoud be included in a sepparated file and added to index.html as resources

var org_turboCommons_test_serializationUtils;
(function (org_turboCommons_test_serializationUtils) {
    var SingleProp = /** @class */ (function () {
        function SingleProp() {
            this.oneProp = "hello";
        }
        return SingleProp;
    }());
    org_turboCommons_test_serializationUtils.SingleProp = SingleProp;
    var NonTypedProps = /** @class */ (function () {
        function NonTypedProps() {
            this.nullProp = null;
            this.undefinedProp = undefined;
        }
        return NonTypedProps;
    }());
    org_turboCommons_test_serializationUtils.NonTypedProps = NonTypedProps;
    var BasicTypeProps = /** @class */ (function () {
        function BasicTypeProps() {
            this.boolean = false;
            this.number = 0;
            this.string = '';
            this.obj = {};
            this.someClass = new NonTypedProps();
            this.arr = [];
        }
        return BasicTypeProps;
    }());
    org_turboCommons_test_serializationUtils.BasicTypeProps = BasicTypeProps;
    var TypedArrayProps = /** @class */ (function () {
        function TypedArrayProps() {
            this.nonTypedArray = [];
            this.boolArray = [false];
            this.numberArray = [0];
            this.stringArray = [""];
            this.objectArray = [{}];
            this.classArray = [new SingleProp()];
            this.arrayArray = [[]];
        }
        return TypedArrayProps;
    }());
    org_turboCommons_test_serializationUtils.TypedArrayProps = TypedArrayProps;
})(org_turboCommons_test_serializationUtils || (org_turboCommons_test_serializationUtils = {}));

//*******************************************************************************************



QUnit.module("SerializationManagerTest", {
    
    beforeEach : function(){

        window.emptyValues = [null, '', [], {}, '     ', "\n\n\n", 0];
        window.emptyValuesCount = window.emptyValues.length;     
        
        window.sut = new org_turbocommons.SerializationManager(); 
        window.ObjectUtils = org_turbocommons.ObjectUtils;
        
        window.SingleProp = org_turboCommons_test_serializationUtils.SingleProp;
        window.NonTypedProps = org_turboCommons_test_serializationUtils.NonTypedProps;
        window.BasicTypeProps = org_turboCommons_test_serializationUtils.BasicTypeProps;
        window.TypedArrayProps = org_turboCommons_test_serializationUtils.TypedArrayProps;
    },

    afterEach : function(){

        delete window.emptyValues;
        delete window.emptyValuesCount;
        
        delete window.sut;
        delete window.ObjectUtils;
        
        delete window.SingleProp;
        delete window.NonTypedProps;
        delete window.BasicTypeProps;
        delete window.TypedArrayProps;
    }
});


/**
 * classToJson
 */
QUnit.todo("classToJson", function(assert){
    
    // Test empty values
    // TODO

    // Test ok values
    // TODO

    // Test wrong values
    // TODO

    // Test exceptions
    // TODO
});


/**
 * classToObject
 */
QUnit.todo("classToObject", function(assert){
 
    // Test empty values
    // TODO

    // Test ok values
    // TODO

    // Test wrong values
    // TODO

    // Test exceptions
    // TODO
});


/**
 * hashMapObjectToClass
 */
QUnit.todo("hashMapObjectToClass", function(assert){
    
    // Test empty values
    // TODO

    // Test ok values
    // TODO

    // Test wrong values
    // TODO

    // Test exceptions
    // TODO
});


/**
 * javaPropertiesObjectToString
 */
QUnit.todo("javaPropertiesObjectToString", function(assert){

    // Test empty values
    // TODO
    
    // Test ok values
    // TODO
    
    // Test wrong values
    // TODO
    
    // Test exceptions
    // TODO
});


/**
 * jsonToClass
 */
QUnit.test("jsonToClass", function(assert){

    // Test empty values on method parameters
    assert.ok(ObjectUtils.isEqualTo(sut.jsonToClass('{}', {}), {}));
    
    for (var i = 0; i < emptyValuesCount; i++) {
        
        assert.throws(function() { sut.jsonToClass(emptyValues[i], {}); });
        
        if(!ObjectUtils.isObject(emptyValues[i])){
            
            assert.throws(function() { sut.jsonToClass('{}', emptyValues[i]); });
        }
    }
    
    for(var i = 0; i < 2; i++){
         
        sut.strictMode = ((i === 0) ? false : true);
        
        // Test that null values on source json keys are assigned to destination properties
        assert.ok(ObjectUtils.isEqualTo(sut.jsonToClass(
                '{"boolean": null, "number": null, "string": null, "obj": null, "someClass": null, "arr": null}',
                new BasicTypeProps()),
                {boolean: false, number: 0, string: "", obj: {},
                    someClass: {nullProp: null, undefinedProp: undefined}, arr: []}));
    
        // Test that non typed properties accept being defined with any value
        if(!sut.strictMode){
            
            assert.ok(ObjectUtils.isEqualTo(sut.jsonToClass(
                    '{"undefinedProp": false}',
                    new NonTypedProps()),
                    {nullProp: null, undefinedProp: false}));
        }
        
        assert.ok(ObjectUtils.isEqualTo(sut.jsonToClass(
                '{"nullProp": 2, "undefinedProp": "hello"}',
                new NonTypedProps()),
                {nullProp: 2, undefinedProp: "hello"}));
        
        assert.ok(ObjectUtils.isEqualTo(sut.jsonToClass(
                '{"nullProp": [1, 2, 3], "undefinedProp": {"a": 1, "b": 2}}',
                new NonTypedProps()),
                {"nullProp": [1, 2, 3], "undefinedProp": {a: 1, b: 2}}));
          
        // Test that typed properties accept only values of their own type
        assert.ok(ObjectUtils.isEqualTo(sut.jsonToClass(
                '{"boolean": true, "number": 1230.1, "string": "hello", "obj": {"b": 2}, ' +
                '"someClass": {"nullProp": 1, "undefinedProp": 2}, "arr": [1,2,3,4]}',
                new BasicTypeProps()),
                {boolean: true, number: 1230.1, string: "hello", obj: {"b": 2},
                    someClass: {nullProp: 1, undefinedProp: 2}, arr: [1,2,3,4]}));
        
        if(!sut.strictMode){
        
            var value = sut.jsonToClass(
                    '{"boolean": false, "number": 25, "string": "h", "obj": {}, "someClass": {"noProp": 1}, "arr": ["a"]}',
                    new BasicTypeProps());
            
            assert.strictEqual(value.someClass.constructor.name, 'NonTypedProps');
                    
            assert.ok(ObjectUtils.isEqualTo(value,
                    {boolean: false, number: 25, string: "h", obj: {},
                        someClass: {nullProp: null, undefinedProp: undefined}, arr: ["a"]}));
        }
        
        assert.throws(function() {
            sut.jsonToClass(
                    '{"boolean": 1, "number": 1230.1, "string": "hello", "obj": {"b": 2}, ' +
                    '"someClass": {"nullProp": 1, "undefinedProp": 2}, "arr": [1,2,3,4]}',
                    new BasicTypeProps());
        }, /expected to be boolean/);
        
        assert.throws(function() {
            sut.jsonToClass(
                    '{"boolean": false, "number": true, "string": "hello", "obj": {"b": 2}, ' +
                    '"someClass": {"nullProp": 1, "undefinedProp": 2}, "arr": [1,2,3,4]}',
                    new BasicTypeProps());
        }, /expected to be number/);
        
        assert.throws(function() {
            sut.jsonToClass(
                    '{"boolean": false, "number": -10, "string": 1, "obj": {"b": 2}, ' +
                    '"someClass": {"nullProp": 1, "undefinedProp": 2}, "arr": [1,2,3,4]}',
                    new BasicTypeProps());
        }, /expected to be string/);
        
        assert.throws(function() {
            sut.jsonToClass(
                    '{"boolean": false, "number": -10, "string": "", "obj": true, ' +
                    '"someClass": {"nullProp": 1, "undefinedProp": 2}, "arr": [1,2,3,4]}',
                    new BasicTypeProps());
        }, /expected to be Object/);
        
        assert.throws(function() {
            sut.jsonToClass(
                    '{"boolean": false, "number": -10, "string": "", "obj": {}, ' +
                    '"someClass": 1, "arr": [1,2,3,4]}',
                    new BasicTypeProps());
        }, /expected to be NonTypedProps/);
        
        if(sut.strictMode){
        
            assert.throws(function() {
                sut.jsonToClass(
                        '{"boolean": false, "number": -10, "string": "", "obj": {}, ' +
                        '"someClass": {"prop": true}, "arr": [1,2,3,4]}',
                        new BasicTypeProps());
            }, /keys do not match NonTypedProps/);            
        }
        
        assert.throws(function() {
            sut.jsonToClass(
                    '{"boolean": false, "number": -10, "string": "", "obj": {}, ' +
                    '"someClass": {"nullProp": true, "undefinedProp": "a"}, "arr": "er"}',
                    new BasicTypeProps());
        }, /expected to be array/);
        
        // Test class and JSON with different keys and properties
        if(sut.strictMode){
            
            assert.throws(function() {
                sut.jsonToClass(
                        '{"foo1":"value", "oneProp":"value"}',
                        new SingleProp());
            }, /keys do not match/);
        
            assert.throws(function() {
                sut.jsonToClass(
                        '{"boolean":true, "string":"hello"}',
                        new BasicTypeProps());
            }, /keys do not match/);
            
            assert.throws(function() {
                sut.jsonToClass(
                        '{"foo1":"value"}',
                        new SingleProp());
            }, /<foo1> not found/);
        }
        
        if(!sut.strictMode){
        
            assert.ok(ObjectUtils.isEqualTo(sut.jsonToClass(
                '{"foo1":"value", "foo2":"value"}',
                new SingleProp()), 
            {oneProp:'hello'}));
        
            assert.ok(ObjectUtils.isEqualTo(sut.jsonToClass(
                    '{"foo1":"value"}',
                    new SingleProp()), 
                {oneProp:'hello'}));
            
            assert.ok(ObjectUtils.isEqualTo(sut.jsonToClass(
                    '{"oneProp":"value", "nonexistant":"value"}',
                    new SingleProp()), 
                {oneProp:'value'}));
        }
        
        // Test properties with typed and non typed array values
        var value = sut.jsonToClass(
                '{"nonTypedArray": [1,"a", null], "boolArray": [true,false], ' +
                '"numberArray": [1,3,5], "stringArray": ["hello","home"], ' +
                '"objectArray": [{"b": 2}], "classArray": [{"oneProp": "a"}, {"oneProp": "b"}], ' +
                '"arrayArray": [[1,2,3], ["a","b","c"]]}',
                new TypedArrayProps());
                
        assert.ok(ObjectUtils.isEqualTo(value,
                {nonTypedArray: [1,"a", null], boolArray: [true,false], 
                    numberArray: [1,3,5], stringArray: ["hello","home"],
                    objectArray: [{"b": 2}], classArray: [{oneProp: "a"}, {oneProp: "b"}],
                    arrayArray: [[1,2,3], ["a","b","c"]]}));
        
        assert.strictEqual(value.classArray[0].constructor.name, 'SingleProp');
        
        // Test that putting more than one value on the arrays at the destination class will throw a failure
        let invalidTypedArrayProps = new TypedArrayProps();
        invalidTypedArrayProps.boolArray = [false,false];
                
        assert.throws(function() {
            sut.jsonToClass('{"nonTypedArray": [1,"a", null], "boolArray": [true,false], ' +
                            '"numberArray": [1,3,5], "stringArray": ["hello","home"], ' +
                            '"objectArray": [{"b": 2}], "classArray": [{"oneProp": "a"}, {"oneProp": "b"}], ' +
                            '"arrayArray": [[1,2,3], ["a","b","c"]]}', invalidTypedArrayProps);
        }, /must contain only 1 default typed element/);
        
        if(!sut.strictMode){
            
            assert.throws(function() {
                sut.jsonToClass(
                        '{"boolArray": [true,false,0]}',
                        new TypedArrayProps());
            }, /but received number/);
            
            assert.throws(function() {
                sut.jsonToClass(
                        '{"numberArray": [1,2,"hello"]}',
                        new TypedArrayProps());
            }, /but received string/);
            
            assert.throws(function() {
                sut.jsonToClass(
                        '{"stringArray": [1,"string",5]}',
                        new TypedArrayProps());
            }, /but received number/);
            
            assert.throws(function() {
                sut.jsonToClass(
                        '{"objectArray": [{"a":1},"string"]}',
                        new TypedArrayProps());
            }, /but received string/);
            
            assert.throws(function() {
                sut.jsonToClass(
                        '{"arrayArray": ["string"]}',
                        new TypedArrayProps());
            }, /but received string/);
        }
        
        // TODO - Test serialization with classes that contain methods
        // What should be the behaviour when destination class contains properties but also methods!?!?!?
    }

    // Test exceptions caused by wrong type parameters
    assert.throws(function() {
        sut.jsonToClass('hello', {});
    });
    
    assert.throws(function() {
        sut.jsonToClass('{}', [1,2,3]);
    });
});


/**
 * objectToClass
 */
QUnit.todo("objectToClass", function(assert){

    // Test empty values
    // TODO

    // Test ok values
    // TODO

    // Test wrong values
    // TODO

    // Test exceptions
    // TODO
});


/**
 * stringToJavaPropertiesObject
 */
QUnit.todo("stringToJavaPropertiesObject", function(assert){

    // Test empty values
    // TODO

    // Test ok values
    // TODO

    // Test wrong values
    // TODO

    // Test exceptions
    // TODO
});