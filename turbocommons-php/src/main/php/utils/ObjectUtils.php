<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\utils;


use org\turbocommons\src\main\php\managers\ValidationManager;
use UnexpectedValueException;


/**
 * Utilities to perform common object operations
 */
class ObjectUtils {


    /**
     * Tells if the given value is an object or not
     *
     * @param mixed $value A value to check
     *
     * @return boolean true if the given value is an object, false otherwise
     */
    public static function isObject($value){

        return is_object($value);
    }


    /**
     * Get the list of literals for a given object. Notice that only 1rst depth keys are providen
     *
     * @param object $object A valid object
     *
     * @return array List of strings with the first level object key names in the same order as defined on the object instance
     */
    public static function getKeys($object){

        $res = [];

        if(!ObjectUtils::isObject($object)){

            throw new UnexpectedValueException('parameter must be an object');
        }

        foreach($object as $key => $value){

            $res[] = (string)$key;
        }

        return $res;
    }


    /**
     * Check if two provided objects are identical.
     * Notice that properties order does not alter the comparison. So if two objects
     * have the same properties with exactly the same values, but they appear in a different
     * order on both objects, this method will consider them as equal.
     *
     * @param object $object1 First object to compare
     * @param object $object2 Second object to compare
     *
     * @return boolean true if objects are exactly the same, false if not
     */
    public static function isEqualTo($object1, $object2){

        $validationManager = new ValidationManager();

        // Both provided values must be objects or an exception will be launched
        if(!$validationManager->isObject($object1) || !$validationManager->isObject($object2)){

            throw new UnexpectedValueException('parameters must be objects');
        }

        $keys1 = self::getKeys($object1);
        $keys2 = self::getKeys($object2);

        sort($keys1);
        sort($keys2);

        // Compare keys can save a lot of time
        if(!ArrayUtils::isEqualTo($keys1, $keys2)){

            return false;
        }

        // Loop all the keys and verify values are identical
        $keys1Len = count($keys1);

        for($i = 0; $i < $keys1Len; $i++){

            $o1 = (array)$object1;
            $o2 = (array)$object2;

            if(!$validationManager->isEqualTo($o1[$keys1[$i]], $o2[$keys2[$i]])){

                return false;
            }
        }

        return true;
    }


    // TODO - translate from TS
    public static function isStringFound(){

        // TODO - translate from TS
    }


    // TODO - translate from TS
    public static function merge(){

        // TODO - translate from TS
    }


    /**
     * Perform a deep copy of the given object.
     *
     * @param object $object Any language instance like numbers, strings, arrays, objects, etc.. that we want to duplicate.
     *
     * @returns object An exact independent copy of the received object, without any shared reference.
     */
    public static function clone($object){

        return ObjectUtils::apply($object, function($o) {

            return is_object($o) ? clone $o : $o;
        });
    }


    /**
     * Apply a given function to each value of the provided object (Recursively through all the object elements). It will also scan
     * inside arrays and sub objects.
     *
     * NOTICE: Original object is not modified
     *
     * @param mixed $object Any language instance like numbers, strings, arrays, objects, etc.. that we want to process.
     * @param callable $callableFunction A function that takes a single argument and returns a value. It must always return a value, cause
     *        it will be assigned to the original object
     *
     * @return mixed An exact independent copy of the received object, without any shared reference, where each value has been processed
     *         by the provided callable function.
     */
    public static function apply($object, callable $callableFunction){

        if(is_array($object)){

            $result = [];

            foreach ($object as $key => $value) {

                $result[$key] = ObjectUtils::apply($object[$key], $callableFunction);
            }

            return $result;
        }

        if(is_object($object)){

            $result = clone $object;

            foreach ($object as $key => $value) {

                $result->$key = ObjectUtils::apply($value, $callableFunction);
            }

            return $result;
        }

        return $callableFunction($object);
    }
}
