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

use InvalidArgumentException;
use org\turbocommons\src\main\php\managers\ValidationManager;


/**
 * Utilities to perform common array operations
 */
class ArrayUtils {


    /**
     * Tells if the given value is an array or not
     *
     * @param mixed $value A value to check
     *
     * @return boolean true if the given value is an array, false otherwise
     */
    public static function isArray($value){

        return is_array($value);
    }


    /**
     * Check if two provided arrays are identical (have exactly the same elements and in the same order).
     *
     * @param array $array1 First array to compare
     * @param array $array2 Second array to compare
     *
     * @return boolean true if arrays are exactly the same, false if not
     */
    public static function isEqualTo(array $array1, array $array2){

        // Compare lengths can save a lot of time
        if(count($array1) != count($array2) ||
           count(array_keys($array1)) !== count(array_keys($array2))){

            return false;
        }

        // Note that php version of this method uses key and value to compare the arrays, cause php also supports associative arrays
        foreach ($array1 as $key => $value) {

            // Check if we have nested arrays
            if(self::isArray($value) && self::isArray($array2[$key])){

                if(!self::isEqualTo($value, $array2[$key])){

                    return false;
                }

            }else{

                if(ObjectUtils::isObject($value) && ObjectUtils::isObject($array2[$key])){

                    if(!ObjectUtils::isEqualTo($value, $array2[$key])){

                        return false;
                    }

                }elseif(!array_key_exists($key, $array2) || $value !== $array2[$key]){

                    return false;
                }
            }
        }

        return true;
    }


    // TODO - translate from TS
    public static function isStringFound(){

        // TODO - translate from TS
    }


    /**
     * Finds the most frequent (most repeated) value in an array.
     * If there are multiple values with the same highest frequency, it returns the first one encountered.
     *
     * @param array $array An array with raw values
     *
     * @example
     * Given the array [1, 2, 2, 3, 3, 3, 4, 4, 4, 4] this method will return 4
     *
     * @return mixed The value that is repeated more times inside the array
     */
    public static function findMostRepeatedValue(array $array){

        if(empty($array)){

            throw new InvalidArgumentException('Received empty array');
        }

        // Use an associative array with serialized values as keys to track counts
        $frequency = [];

        foreach ($array as $value) {

            // Serialize the value to preserve its type
            $key = serialize($value);
            $frequency[$key] = ($frequency[$key] ?? 0) + 1;
        }

        // Find the serialized value with the highest count
        $mostRepeatedKey = array_search(max($frequency), $frequency);

        // Unserialize the key to get the original value
        return unserialize($mostRepeatedKey);
    }


    /**
     * Strictly check that the provided value is a non empty array or throw an exception
     *
     * @param mixed $value A value to check
     * @param string $valueName The name of the value to be shown at the beginning of the exception message
     * @param string $errorMessage The rest of the exception message
     *
     * @throws InvalidArgumentException If the check fails
     *
     * @return void
     */
    public static function forceNonEmptyArray($value, string $valueName = '', string $errorMessage = 'must be a non empty array'){

        if(!is_array($value) || count($value) <= 0){

            throw new InvalidArgumentException($valueName.' '.$errorMessage);
        }
    }


    /**
     * Remove the specified item from an array
     *
     * @param array $array An array (it will not be modified by this method)
     * @param mixed $element The element that must be removed from the given array
     *
     * @returns array The provided array but without the specified element (if found). Note that originally received array is not modified by this method
     */
    public static function removeElement(array $array, $element){

        $res = [];
        $arrayCount = count($array);

        if(self::isArray($element)){

            for($i = 0; $i < $arrayCount; $i++){

                if(!self::isArray($array[$i])){

                    array_push($res, $array[$i]);

                }else{

                    if(!self::isEqualTo($element, $array[$i])){

                        array_push($res, $array[$i]);
                    }
                }
            }

        }else{

            for($j = 0; $j < $arrayCount; $j++){

                if($element !== $array[$j]){

                    array_push($res, $array[$j]);
                }
            }
        }

        return $res;
    }


    /**
     * remove all the duplicate values on the provided array
     * Duplicate values with different data types won't be considered as equal ('1', 1 will not be removed)
     *
     * @param array $array An array with possible duplicate values
     *
     * @return array The same provided array but without duplicate elements
     */
    public static function removeDuplicateElements(array $array){

        $result = [];
        $numElements = count($array);

        $validationManager = new ValidationManager();

        for ($i = 0; $i < $numElements; $i++) {

            $found = false;

            $resultCount = count($result);

            for ($j = 0; $j < $resultCount; $j++) {

                if($validationManager->isEqualTo($array[$i], $result[$j])){

                    $found = true;
                    break;
                }
            }

            if(!$found){

                $result[] = $array[$i];
            }
        }

        return $result;
    }


    /**
     * Check if the given array contains duplicate values or not.
     * Duplicate values with different data types won't be considered as equal ('1', 1 will return false)
     *
     * @param array $array An array containing some elements to test
     *
     * @return boolean True if there are duplicate values, false otherwise
     */
    public static function hasDuplicateElements(array $array){

        $numElements = count($array);

        $validationManager = new ValidationManager();

        for ($i = 0; $i < $numElements; $i++) {

            for ($j = $i + 1; $j < $numElements; $j++) {

                if($validationManager->isEqualTo($array[$i], $array[$j])){

                    return true;
                }
            }
        }

        return false;
    }


    /**
     * Get all the duplicate values on the provided array
     * Duplicate values with different data types won't be considered as equal ('1', 1 will return false)
     *
     * @param array $array An array containing some elements to test
     *
     * @return array list with all the elements that are duplicated on the provided array
     */
    public static function getDuplicateElements(array $array){

        $result = [];
        $numElements = count($array);

        $validationManager = new ValidationManager();

        for ($i = 0; $i < $numElements; $i++) {

            for ($j = $i + 1; $j < $numElements; $j++) {

                if($validationManager->isEqualTo($array[$i], $array[$j])){

                    $result[] = $array[$i];
                }
            }
        }

        return self::removeDuplicateElements($result);
    }
}

?>