<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\test\php\utils;

use PHPUnit\Framework\TestCase;
use org\turbocommons\src\main\php\utils\ArrayUtils;
use org\turbocommons\src\main\php\utils\ObjectUtils;
use org\turbotesting\src\main\php\utils\AssertUtils;
use stdClass;
use Exception;
use Throwable;


/**
 * ObjectUtils tests
 *
 * @return void
 */
class ObjectUtilsTest extends TestCase {


    /**
     * testIsObject
     *
     * @return void
     */
    public function testIsObject(){

        // test empty values
        $this->assertTrue(!ObjectUtils::isObject(null));
        $this->assertTrue(!ObjectUtils::isObject(''));
        $this->assertTrue(!ObjectUtils::isObject([]));
        $this->assertTrue(!ObjectUtils::isObject(0));
        $this->assertTrue(ObjectUtils::isObject(new stdClass()));

        // Test valid values
        $this->assertTrue(ObjectUtils::isObject(new Exception()));
        $this->assertTrue(ObjectUtils::isObject(((object) [
            '1' => 1
        ])));
        $this->assertTrue(ObjectUtils::isObject(((object) [
            'a' => 'hello'
        ])));
        $this->assertTrue(ObjectUtils::isObject(((object) [
            'a' => 1,
            'b' => 2,
            'c' => 3
        ])));

        // Test invalid values
        $this->assertTrue(!ObjectUtils::isObject(874));
        $this->assertTrue(!ObjectUtils::isObject('hello'));
        $this->assertTrue(!ObjectUtils::isObject([123]));
        $this->assertTrue(!ObjectUtils::isObject([1, 'aaa']));
        $this->assertTrue(!ObjectUtils::isObject('/someregex.*/'));
    }


    /**
     * testGetKeys
     *
     * @return void
     */
    public function testGetKeys(){

        $this->assertTrue(ArrayUtils::isEqualTo(ObjectUtils::getKeys(new stdClass()), []));
        $this->assertTrue(ArrayUtils::isEqualTo(ObjectUtils::getKeys(((object) [
                '1' => 1
        ])), ['1']));
        $this->assertTrue(ArrayUtils::isEqualTo(ObjectUtils::getKeys(((object) [
            'a' => 1
        ])), ['a']));
        $this->assertTrue(ArrayUtils::isEqualTo(ObjectUtils::getKeys(((object) [
                'a' => 1,
                'b' => 2,
                'c' => 3
        ])), ['a', 'b', 'c']));
        $this->assertTrue(ArrayUtils::isEqualTo(ObjectUtils::getKeys(((object) [
                'a' => 1,
                'b' => (object) [
                        'a' => 1,
                        'x' => 0
                    ],
                'c' => 3
        ])), ['a', 'b', 'c']));

        // Test exceptions
        $exceptionMessage = '';

        try {
            ObjectUtils::getKeys(null);
            $exceptionMessage = 'null did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            ObjectUtils::getKeys([]);
            $exceptionMessage = '[] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            ObjectUtils::getKeys([1, 2, 3]);
            $exceptionMessage = '[1, 2, 3] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        if($exceptionMessage != ''){

            $this->fail($exceptionMessage);
        }
    }


    /**
     * testIsEqualTo
     *
     * @return void
     */
    public function testIsEqualTo(){

        // Test identic values
        $this->assertTrue(ObjectUtils::isEqualTo(new stdClass(), new stdClass()));
        $this->assertTrue(ObjectUtils::isEqualTo(((object) [
                'hello' => 'home'
        ]), ((object) [
                'hello' => 'home'
        ])));
        $this->assertTrue(ObjectUtils::isEqualTo(((object) [
            '1' => 1
        ]), ((object) [
            '1' => 1
        ])));
        $this->assertTrue(ObjectUtils::isEqualTo(((object) [
                'hello' => 'home',
                'number' => 1
        ]), ((object) [
                'hello' => 'home',
                'number' => 1
        ])));
        $this->assertTrue(ObjectUtils::isEqualTo(((object) [
                'hello' => 'home',
                'number' => 1,
                'array' => [1, 2, 3]
        ]), ((object) [
                'hello' => 'home',
                'number' => 1,
                'array' => [1, 2, 3]
        ])));
        $this->assertTrue(ObjectUtils::isEqualTo(((object) [
                'hello' => 'home',
                'array' => ((object) [
                        'hello' => 'home',
                        'number' => 1
                ])
        ]), ((object) [
                'hello' => 'home',
                'array' => ((object) [
                        'hello' => 'home',
                        'number' => 1
                ])
        ])));

        // Test same values but with different key order
        $this->assertTrue(ObjectUtils::isEqualTo(((object) [
            'number' => 1,
            'hello' => 'home',
            'array' => [1, 2, 3]
        ]), ((object) [
            'hello' => 'home',
            'number' => 1,
            'array' => [1, 2, 3]
        ])));
        $this->assertTrue(ObjectUtils::isEqualTo(((object) [
            'hello' => 'home',
            'array' => ((object) [
                'hello' => 'home',
                'number' => 1
            ])
        ]), ((object) [
            'array' => ((object) [
                'number' => 1,
                'hello' => 'home'
            ]),
            'hello' => 'home'
        ])));

        // Test different values
        $this->assertTrue(!ObjectUtils::isEqualTo(new stdClass(), ((object) [
                '1' => 1
        ])));
        $this->assertTrue(!ObjectUtils::isEqualTo(((object) [
                '1' => 1
        ]), ((object) [
                '1' => 2
        ])));
        $this->assertTrue(!ObjectUtils::isEqualTo(((object) [
                'hello' => 'guys'
        ]), ((object) [
                '1' => 2
        ])));
        $this->assertTrue(!ObjectUtils::isEqualTo(((object) [
                'hello' => 'guys'
        ]), ((object) [
                'hell' => 'guys'
        ])));
        $this->assertTrue(!ObjectUtils::isEqualTo(((object) [
                'hello' => 'home',
                'number' => 1,
                'array' => [1, 3]
        ]), ((object) [
                'hello' => 'home',
                'number' => 1,
                'array' => [1, 2, 3]
        ])));

        // Test exceptions with non objects
        $exceptionMessage = '';

        try {
            ObjectUtils::isEqualTo(null, null);
            $exceptionMessage = 'null did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            ObjectUtils::isEqualTo([], []);
            $exceptionMessage = '[] did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            ObjectUtils::isEqualTo('hello', 'hello');
            $exceptionMessage = 'hello did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        if($exceptionMessage != ''){

            $this->fail($exceptionMessage);
        }
    }


    /**
     * testIsStringFound
     *
     * @return void
     */
    public function testIsStringFound(){

        // Test empty values
        // TODO - translate from ts

        // Test ok values
        // TODO - translate from ts

        // Test wrong values
        // TODO - translate from ts

        // Test exceptions
        // TODO - translate from ts
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testMerge
     *
     * @return void
     */
    public function testMerge(){

        // Test empty values
        // TODO - translate from ts

        // Test ok values
        // TODO - translate from ts

        // Test wrong values
        // TODO - translate from ts

        // Test exceptions
        // TODO - translate from ts
        $this->markTestIncomplete('This test has not been implemented yet.');
    }


    /**
     * testClone
     *
     * @return void
     */
    public function testClone(){

        // Test empty values
        $this->assertSame(null, ObjectUtils::clone(null));
        $this->assertSame(null, ObjectUtils::clone(null));
        $this->assertSame(0, ObjectUtils::clone(0));
        $this->assertSame('', ObjectUtils::clone(''));
        $this->assertTrue(ArrayUtils::isEqualTo(ObjectUtils::clone([]), []));
        $this->assertTrue(ObjectUtils::isEqualTo(ObjectUtils::clone(new stdclass()), new stdclass()));
        $this->assertSame('    ', ObjectUtils::clone('    '));

        // Test ok values. Verify modified clones do not affect original one
        $value = -145;
        $clonedValue = ObjectUtils::clone($value);
        $this->assertSame($clonedValue, $value);
        $clonedValue = $clonedValue + 100;
        $this->assertSame($clonedValue, -45);
        $this->assertSame($value, -145);

        $value = 'hello';
        $clonedValue = ObjectUtils::clone($value);
        $this->assertSame($clonedValue, $value);
        $this->assertSame('hello', $value);

        $value = [1,2,3,4,5];
        $clonedValue = ObjectUtils::clone($value);
        $this->assertTrue(ArrayUtils::isEqualTo($clonedValue, $value));
        $clonedValue[] = 6;
        $this->assertTrue(ArrayUtils::isEqualTo($value, [1,2,3,4,5]));

        $value = [1,2,3,(object) ["a" => 1, "b" => 2, "c" => (object) ["d" => 1]],5];
        $clonedValue = ObjectUtils::clone($value);
        $this->assertTrue(ArrayUtils::isEqualTo($clonedValue, $value));
        $clonedValue[3]->a = 5;
        $clonedValue[3]->c->d = 6;
        $this->assertTrue(ArrayUtils::isEqualTo($clonedValue, [1,2,3,(object) ["a" => 5, "b" => 2, "c" => (object) ["d" => 6]],5]));
        $this->assertTrue(ArrayUtils::isEqualTo($value, [1,2,3,(object) ["a" => 1, "b" => 2, "c" => (object) ["d" => 1]],5]));

        $value = (object) ["a" => 1, "b" => 2, "c" => [3,4,5,(object) ["d" => 6,"e" => (object) ["f" => 7]]]];
        $clonedValue = ObjectUtils::clone($value);
        $this->assertTrue(ObjectUtils::isEqualTo($clonedValue, $value));
        $clonedValue->a = 5;
        $clonedValue->c[0] = 9;
        $clonedValue->c[3]->e = null;
        $this->assertTrue(ObjectUtils::isEqualTo($clonedValue, (object) ["a" => 5, "b" => 2, "c" => [9,4,5,(object) ["d" => 6,"e" => null]]]));
        $this->assertTrue(ObjectUtils::isEqualTo($value, (object) ["a" => 1, "b" => 2, "c" => [3,4,5,(object) ["d" => 6,"e" => (object) ["f" => 7]]]]));

        // Test an object containing references to other objects
        $reference = (object) ["ref" => 1];
        $value = (object) ["a" => 1, "b" => $reference];
        $clonedValue = ObjectUtils::clone($value);
        $this->assertTrue(ObjectUtils::isEqualTo($clonedValue, $value));
        $reference->ref = 2;
        $this->assertTrue(ObjectUtils::isEqualTo($clonedValue, (object) ["a" => 1, "b" => (object) ["ref" => 1]]));
        $this->assertTrue(ObjectUtils::isEqualTo($value, (object) ["a" => 1, "b" => (object) ["ref" => 2]]));

        // Test an object containing a function
        $value = (object) ["a" => 1, "b" => function($a) { return $a + 2; }];
        $clonedValue = ObjectUtils::clone($value);
        $this->assertTrue(ObjectUtils::isEqualTo($clonedValue, $value));

        $this->assertSame(1, $value->a);
        $this->assertSame(6, ($value->b)(4));
        $this->assertSame(1, $clonedValue->a);
        $this->assertSame(6, ($clonedValue->b)(4));
        $this->assertSame(8, ($clonedValue->b)(6));

        // Test an object containing a regex
        $value = (object) ["a" => 1, "b" => '/someregex.*/'];
        $clonedValue = ObjectUtils::clone($value);
        $this->assertTrue(ObjectUtils::isEqualTo($clonedValue, $value));

        $this->assertSame(1, $value->a);
        $this->assertSame((string) $value->b, (string) '/someregex.*/');
        $this->assertSame(1, $clonedValue->a);
        $this->assertSame((string) $clonedValue->b, (string) '/someregex.*/');

        // Test an object containing an associative array
        $value = (object) ["a" => 1, "b" => ["key1" => 1, "key2" => "value"]];
        $clonedValue = ObjectUtils::clone($value);
        $this->assertTrue(ObjectUtils::isEqualTo($clonedValue, $value));

        // Test wrong values
        // not necessary

        // Test exceptions
        // no exceptions are thrown by this method
    }


    /**
     * testApply
     *
     * @return void
     */
    public function testApply(){

        // Test empty values
        AssertUtils::throwsException(function() { ObjectUtils::apply(null, null); }, '/must be callable/');
        AssertUtils::throwsException(function() { ObjectUtils::apply(0, 0); }, '/must be callable/');
        AssertUtils::throwsException(function() { ObjectUtils::apply('', ''); }, '/must be callable/');
        AssertUtils::throwsException(function() { ObjectUtils::apply('    ', '     '); }, '/must be callable/');
        AssertUtils::throwsException(function() { ObjectUtils::apply(new stdclass(), new stdclass()); }, '/must be callable/');
        $this->assertSame(null, ObjectUtils::apply(null, function ($v) { return $v;}));
        $this->assertSame(0, ObjectUtils::apply(0, function ($v) { return $v;}));
        $this->assertSame("", ObjectUtils::apply("", function ($v) { return $v;}));
        $this->assertSame([], ObjectUtils::apply([], function ($v) { return $v;}));

        // Test ok values
        $value = 145;
        $this->assertSame($value, ObjectUtils::apply($value, function($v) { return $v; }));

        $value = 145;
        $this->assertSame(146, ObjectUtils::apply($value, function($v) { return $v + 1; }));

        $value = "abcde";
        $this->assertSame($value, ObjectUtils::apply($value, function($v) { return $v; }));

        $value = "abcde";
        $this->assertSame('abcdef', ObjectUtils::apply($value, function($v) { return $v.'f'; }));

        $value = [1, 2, 3, 4];
        $this->assertSame($value, ObjectUtils::apply($value, function($v) { return $v; }));

        $value = [1, 2, 3, 4];
        $this->assertSame([2, 3, 4, 5], ObjectUtils::apply($value, function($v) { return $v + 1; }));

        $value = [1, [1, 2, [4, 5]], 4];
        $this->assertSame([1, [1, 2, [4, 5]], 4], ObjectUtils::apply($value, function($v) { return $v; }));

        $value = [1, [1, 2, [4, 5]], 4];
        $this->assertSame([2, [2, 3, [5, 6]], 5], ObjectUtils::apply($value, function($v) { return $v + 1; }));

        $value = [1, [1, "a", [4, "b"]], 4];
        $applied = ObjectUtils::apply($value, function($v) { return is_string($v) ? $v.'c' : $v+1; });
        $this->assertSame([2, [2, "ac", [5, "bc"]], 5], $applied);

        $value = (object) ["a" => 1, "b" => [1,2,"a"]];
        $applied = ObjectUtils::apply($value, function($v) { return is_string($v) ? $v.'c' : $v; });
        $this->assertTrue(ObjectUtils::isEqualTo($applied, (object) ["a" => 1, "b" => [1,2,"ac"]]));

        // Test wrong values
        // Test exceptions
        // not necessary
    }
}

?>