<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\test\php\managers;

use PHPUnit\Framework\TestCase;
use stdClass;
use org\turbocommons\src\main\php\managers\SerializationManager;
use org\turbocommons\src\main\php\utils\ObjectUtils;
use org\turbotesting\src\main\php\utils\AssertUtils;


/**
 * SerializationManagerTest
 *
 * @return void
 */
class SerializationManagerTest extends TestCase {

    /**
     * @see TestCase::setUpBeforeClass()
     *
     * @return void
     */
    public static function setUpBeforeClass(){

        // Nothing necessary here
    }


    /**
     * @see TestCase::setUp()
     *
     * @return void
     */
    protected function setUp(){

        $this->emptyValues = [null, '', [], new stdClass(), '     ', "\n\n\n", 0];
        $this->emptyValuesCount = count($this->emptyValues);

        $this->sut = new SerializationManager();

        // *******************************************************************************************
        // Following classes are defined to be used on several of this tests to be serialized or deserialized.

        $this->SingleProp = (new class {
            public $oneProp = "hello";
        });

        $this->NonTypedProps = (new class {
            public $nullProp = null;
            public $undefinedProp = null;
        });

        $this->BasicTypeProps = (new class {
            public $boolean = false;
            public $number = 0;
            public $string = '';
            public $obj;
            public $someClass;
            public $arr = [];

            public function __construct() {
                $this->obj = new stdclass();
                $this->someClass = (new class {
                    public $nullProp = null;
                    public $undefinedProp = null;
                });
            }
        });

        $this->TypedArrayProps = (new class {
            public $nonTypedArray = [];
            public $boolArray = [false];
            public $numberArray = [0];
            public $stringArray = [""];
            public $objectArray;
            public $classArray;
            public $arrayArray = [[]];

            public function __construct() {
                $this->objectArray = [new stdclass()];
                $this->classArray = [(new class {
                    public $oneProp = "hello";
                })];
            }
        });

        //*******************************************************************************************
    }


    /**
     * @see TestCase::tearDown()
     *
     * @return void
     */
    protected function tearDown(){

        // Nothing necessary here
    }


    /**
     * @see TestCase::tearDownAfterClass()
     *
     * @return void
     */
    public static function tearDownAfterClass(){

        // Nothing necessary here
    }


    /**
     * testClassToJson
     *
     * @return void
     */
    public function testClassToJson(){

        // Test empty values
        // TODO - review from ts

        // Test ok values
        // TODO - review from ts

        // Test wrong values
        // TODO - review from ts

        // Test exceptions
        // TODO - review from ts

        // TODO
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testClassToObject
     *
     * @return void
     */
    public function testClassToObject(){

        // Test empty values
        // TODO - review from ts

        // Test ok values
        // TODO - review from ts

        // Test wrong values
        // TODO - review from ts

        // Test exceptions
        // TODO - review from ts

        // TODO
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testHashMapObjectToClass
     *
     * @return void
     */
    public function testHashMapObjectToClass(){

        // Test empty values
        // TODO

        // Test ok values
        // TODO

        // Test wrong values
        // TODO

        // Test exceptions
        // TODO

        // TODO
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testJavaPropertiesObjectToString
     *
     * @return void
     */
    public function testJavaPropertiesObjectToString(){

        // Test empty values
        // TODO

        // Test ok values
        // TODO

        // Test wrong values
        // TODO

        // Test exceptions
        // TODO

        // TODO
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testJsonToClass
     *
     * @return void
     */
    public function testJsonToClass(){

        // Test empty values on method parameters
        $this->assertTrue(ObjectUtils::isEqualTo($this->sut->jsonToClass('{}', new stdClass()), new stdClass()));

        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            AssertUtils::throwsException(function() use ($i) { $this->sut->jsonToClass($this->emptyValues[$i], new stdClass()); });

            if(!ObjectUtils::isObject($this->emptyValues[$i])){

                AssertUtils::throwsException(function() use ($i) { $this->sut->jsonToClass('{}', $this->emptyValues[$i]); });
            }
        }

        for ($i = 0; $i < 2; $i++) {

            $this->sut->strictMode = (($i === 0) ? false : true);

            // Test that null values on source json keys are assigned to destination properties
            $this->assertTrue(ObjectUtils::isEqualTo($this->sut->jsonToClass(
                '{"boolean": null, "number": null, "string": null, "obj": null, "someClass": null, "arr": null}',
                new $this->BasicTypeProps()),
                (object) ['boolean' => false, 'number' => 0, 'string' => "", 'obj' => new stdclass(),
                    'someClass' => (object) ['nullProp' => null, 'undefinedProp' => null], 'arr' => []]));

            // Test that non typed properties accept being defined with any value
            if(!$this->sut->strictMode){

                $this->assertTrue(ObjectUtils::isEqualTo($this->sut->jsonToClass(
                    '{"undefinedProp": false}',
                    new $this->NonTypedProps()),
                    (object) ['nullProp' => null, 'undefinedProp' => false]));
            }

            $this->assertTrue(ObjectUtils::isEqualTo($this->sut->jsonToClass(
                '{"nullProp": 2, "undefinedProp": "hello"}',
                new $this->NonTypedProps()),
                (object) ['nullProp' => 2, 'undefinedProp' => "hello"]));

            $this->assertTrue(ObjectUtils::isEqualTo($this->sut->jsonToClass(
                '{"nullProp": [1, 2, 3], "undefinedProp": {"a": 1, "b": 2}}',
                new $this->NonTypedProps()),
                (object) ["nullProp" => [1, 2, 3], "undefinedProp" => (object) ['a' => 1, 'b' => 2]]));

            // Test that typed properties accept only values of their own type
            $this->assertTrue(ObjectUtils::isEqualTo($this->sut->jsonToClass(
                '{"boolean": true, "number": 1230.1, "string": "hello", "obj": {"b": 2}, ' .
                '"someClass": {"nullProp": 1, "undefinedProp": 2}, "arr": [1,2,3,4]}',
                new $this->BasicTypeProps()),
                (object) ["boolean" => true, "number" => 1230.1, "string" => "hello", "obj" => (object) ["b" => 2],
                "someClass" => (object) ["nullProp" => 1, "undefinedProp" => 2], "arr" => [1,2,3,4]]));

            if(!$this->sut->strictMode){

                $value = $this->sut->jsonToClass(
                    '{"boolean": false, "number": 25, "string": "h", "obj": {}, "someClass": {"noProp": 1}, "arr": ["a"]}',
                    new $this->BasicTypeProps());

                $this->assertTrue(property_exists($value->someClass, 'undefinedProp'));

                $this->assertTrue(ObjectUtils::isEqualTo($value,
                    (object) ["boolean" => false, "number" => 25, "string" => "h", "obj" => new stdClass(),
                        "someClass" => (object) ["nullProp" => null, "undefinedProp" => null], "arr" => ["a"]]));
            }

            AssertUtils::throwsException(function() {
                $this->sut->jsonToClass(
                    '{"boolean": 1, "number": 1230.1, "string": "hello", "obj": {"b": 2}, ' .
                    '"someClass": {"nullProp": 1, "undefinedProp": 2}, "arr": [1,2,3,4]}',
                    new $this->BasicTypeProps());
            }, '/expected to be boolean/');

            AssertUtils::throwsException(function() {
                $this->sut->jsonToClass(
                    '{"boolean": false, "number": true, "string": "hello", "obj": {"b": 2}, ' .
                    '"someClass": {"nullProp": 1, "undefinedProp": 2}, "arr": [1,2,3,4]}',
                    new $this->BasicTypeProps());
            }, '/expected to be number/');

            AssertUtils::throwsException(function() {
                $this->sut->jsonToClass(
                    '{"boolean": false, "number": -10, "string": 1, "obj": {"b": 2}, ' .
                    '"someClass": {"nullProp": 1, "undefinedProp": 2}, "arr": [1,2,3,4]}',
                    new $this->BasicTypeProps());
            }, '/expected to be string/');

            AssertUtils::throwsException(function() {
                $this->sut->jsonToClass(
                    '{"boolean": false, "number": -10, "string": "", "obj": true, ' .
                    '"someClass": {"nullProp": 1, "undefinedProp": 2}, "arr": [1,2,3,4]}',
                    new $this->BasicTypeProps());
            }, '/expected to be stdClass/');

            AssertUtils::throwsException(function() {
                $this->sut->jsonToClass(
                    '{"boolean": false, "number": -10, "string": "", "obj": {}, ' .
                    '"someClass": 1, "arr": [1,2,3,4]}',
                    new $this->BasicTypeProps());
            }, '/expected to be class/');

            if($this->sut->strictMode){

                AssertUtils::throwsException(function() {
                    $this->sut->jsonToClass(
                        '{"boolean": false, "number": -10, "string": "", "obj": {}, ' .
                        '"someClass": {"prop": true}, "arr": [1,2,3,4]}',
                        new $this->BasicTypeProps());
                }, '/keys do not match class/');
            }

            AssertUtils::throwsException(function() {
                $this->sut->jsonToClass(
                    '{"boolean": false, "number": -10, "string": "", "obj": {}, ' .
                    '"someClass": {"nullProp": true, "undefinedProp": "a"}, "arr": "er"}',
                    new $this->BasicTypeProps());
            }, '/expected to be array/');

            // Test class and JSON with different keys and properties
            if($this->sut->strictMode){

                AssertUtils::throwsException(function() {
                    $this->sut->jsonToClass(
                        '{"foo1":"value", "oneProp":"value"}',
                        new $this->SingleProp());
                }, '/keys do not match/');

                AssertUtils::throwsException(function() {
                    $this->sut->jsonToClass(
                        '{"boolean":true, "string":"hello"}',
                        new $this->BasicTypeProps());
                }, '/keys do not match/');

                AssertUtils::throwsException(function() {
                    $this->sut->jsonToClass(
                        '{"foo1":"value"}',
                        new $this->SingleProp());
                }, '/<foo1> not found/');
            }

            if(!$this->sut->strictMode){

                $this->assertTrue(ObjectUtils::isEqualTo($this->sut->jsonToClass(
                    '{"foo1":"value", "foo2":"value"}',
                    new $this->SingleProp()),
                    (object) ["oneProp" => 'hello']));

                $this->assertTrue(ObjectUtils::isEqualTo($this->sut->jsonToClass(
                    '{"foo1":"value"}',
                    new $this->SingleProp()),
                    (object) ["oneProp" => 'hello']));

                $this->assertTrue(ObjectUtils::isEqualTo($this->sut->jsonToClass(
                    '{"oneProp":"value", "nonexistant":"value"}',
                    new $this->SingleProp()),
                    (object) ["oneProp" => 'value']));
            }

            // Test properties with typed and non typed array values
            $value = $this->sut->jsonToClass(
                '{"nonTypedArray": [1,"a", null], "boolArray": [true,false], ' .
                '"numberArray": [1,3,5], "stringArray": ["hello","home"], ' .
                '"objectArray": [{"b": 2}], "classArray": [{"oneProp": "a"}, {"oneProp": "b"}], ' .
                '"arrayArray": [[1,2,3], ["a","b","c"]]}',
                new $this->TypedArrayProps());

            $this->assertTrue(ObjectUtils::isEqualTo($value,
                (object) ["nonTypedArray" => [1,"a", null], "boolArray" => [true,false],
                    "numberArray" => [1,3,5], "stringArray" => ["hello","home"],
                    "objectArray" => [(object) ["b" => 2]], "classArray" => [(object) ["oneProp" => "a"], (object) ["oneProp" => "b"]],
                    "arrayArray" => [[1,2,3], ["a","b","c"]]]));

            $this->assertTrue(property_exists($value->classArray[0], 'oneProp'));

            // Test that putting more than one value on the arrays at the destination class will throw a failure
            $invalidTypedArrayProps = new $this->TypedArrayProps();
            $invalidTypedArrayProps->boolArray = [false,false];

            AssertUtils::throwsException(function() use ($invalidTypedArrayProps){
                $this->sut->jsonToClass('{"nonTypedArray": [1,"a", null], "boolArray": [true,false], ' .
                    '"numberArray": [1,3,5], "stringArray": ["hello","home"], ' .
                    '"objectArray": [{"b": 2}], "classArray": [{"oneProp": "a"}, {"oneProp": "b"}], ' .
                    '"arrayArray": [[1,2,3], ["a","b","c"]]}', $invalidTypedArrayProps);
            }, '/must contain only 1 default typed element/');

            if(!$this->sut->strictMode){

                AssertUtils::throwsException(function() {
                    $this->sut->jsonToClass(
                        '{"boolArray": [true,false,0]}',
                        new $this->TypedArrayProps());
                }, '/but received number/');

                AssertUtils::throwsException(function() {
                    $this->sut->jsonToClass(
                        '{"numberArray": [1,2,"hello"]}',
                        new $this->TypedArrayProps());
                }, '/but received string/');

                AssertUtils::throwsException(function() {
                    $this->sut->jsonToClass(
                        '{"stringArray": [1,"string",5]}',
                        new $this->TypedArrayProps());
                }, '/but received number/');

                AssertUtils::throwsException(function() {
                    $this->sut->jsonToClass(
                        '{"objectArray": [{"a":1},"string"]}',
                        new $this->TypedArrayProps());
                }, '/but received string/');

                AssertUtils::throwsException(function() {
                    $this->sut->jsonToClass(
                        '{"arrayArray": ["string"]}',
                        new $this->TypedArrayProps());
                }, '/but received string/');
            }

            // TODO - Test serialization with classes that contain methods
            // What should be the behaviour when destination class contains properties but also methods!?!?!?
        }

        // Test exceptions caused by wrong type parameters
        AssertUtils::throwsException(function() {
            $this->sut->jsonToClass('hello', new stdclass());
        });

        AssertUtils::throwsException(function() {
            $this->sut->jsonToClass('{}', [1,2,3]);
        });
    }


    /**
     * testObjectToClass
     *
     * @return void
     */
    public function testObjectToClass(){

        // Test empty values
        // TODO

        // Test ok values
        // TODO

        // Test wrong values
        // TODO

        // Test exceptions
        // TODO

        // TODO
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testStringToJavaPropertiesObject
     *
     * @return void
     */
    public function testStringToJavaPropertiesObject(){

        // Test empty values
        // TODO

        // Test ok values
        // TODO

        // Test wrong values
        // TODO

        // Test exceptions
        // TODO

        // TODO
        $this->markTestIncomplete('This test has not been implemented yet.');
    }
}

?>