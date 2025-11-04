<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\model;

use InvalidArgumentException;
use UnexpectedValueException;


/**
 * HashMapObject abstraction
 */
class HashMapObject{


    /**
     * Sort mode that compares values as strings (alphabetically)
     */
    const SORT_METHOD_STRING = 'SORT_METHOD_STRING';


    /**
     * Sort mode that compares values as numbers (Avoid using it with non numeric values)
     */
    const SORT_METHOD_NUMERIC = 'SORT_METHOD_NUMERIC';


    /**
     * Defines that elements will be sorted upward
     */
    const SORT_ORDER_ASCENDING = 'SORT_ORDER_ASCENDING';


    /**
     * Defines that elements will be sorted downward
     */
    const SORT_ORDER_DESCENDING = 'SORT_ORDER_DESCENDING';


    /**
     * Structure that contains the HashMapObject data.
     * Note that php associative arrays are specifically designed as sorted hash tables,
     * so we use an associative array as the main data structure that contains the key / value
     * pairs that can be sorted by key or even by value
     *
     * @var array
     */
    protected $_data = [];


    /**
     * Stores the number of elements inside the HashMapObject
     * @var integer
     */
    protected $_length = 0;


    /**
     * An Object that defines a sorted collection of key/value pairs and all their related operations.
     *
     * @param array $data A value that will be used to initialize the HashMapObject. It can be an associative array
     * (where each key/value will be directly assigned to the HashMap), or a plain array in which case the keys will be
     * copied from each element numeric index
     */
    public function __construct(array $data = null){

        if(is_array($data) && ($dataCount = count($data)) > 0){

            $isAssociative = array_keys($data) !== range(0, $dataCount - 1);

            if($isAssociative){

                foreach ($data as $key => $value) {

                    $this->set((string)$key, $value);
                }

            }else{

                $i = 0;

                foreach ($data as $value) {

                    $this->set((string)$i, $value);

                    $i++;
                }
            }
        }
    }


    /**
     * Define a key / value pair and add it to the collection.
     * If the key already exists, value will be replaced.
     *
     * @param string $key A string that labels the provided value
     * @param mixed $value A value to be stored with the provided key
     *
     * @throws InvalidArgumentException If invalid key is provided
     * @return mixed The value after being stored to the collection
     */
    public function set($key, $value){

        // Check if key is a non empty string.
        // We use the same logic as StringUtils::isEmpty but with some simplification for better performance
        // This should be a call to $this->_validateKeyFormat but we inline it here to get a big performance improvement
        if(is_string($key) && str_replace([' ', "\n", "\r", "\t"], '', $key) !== ''){

            $this->_data[$key] = $value;

            $this->_length = count($this->_data);

            return $value;
        }

        throw new InvalidArgumentException('HashMapObject: key must be a non empty string');
    }


    /**
     * Get the number of key/value pairs that are currently stored on this HashMapObject instance
     *
     * @return integer The number of items inside the collection
     */
    public function length(){

        return $this->_length;
    }


    /**
     * Get the value that is associated to a key from an existing key/value pair
     *
     * @param string $key The key we are looking for
     *
     * @throws InvalidArgumentException If key does not exist or is invalid
     * @return mixed The value that is associated to the provided key
     */
    public function get(string $key){

        if(array_key_exists($key, $this->_data)){

            return $this->_data[$key];
        }

        throw new InvalidArgumentException('HashMapObject->get: key does not exist or is invalid');
    }


    /**
     * Get the value that is located at a certain position at the ordered list of key/pair values
     *
     * @param int $index The position we are looking for
     *
     * @throws InvalidArgumentException If index does not exist or is invalid
     * @return mixed The value that is located at the specified position
     */
    public function getAt($index){

        if(is_integer($index) && $index >= 0 && $index < $this->_length){

            return $this->_data[array_keys($this->_data)[$index]];
        }

        throw new InvalidArgumentException('HashMapObject->getAt: index does not exist or is invalid');
    }


    /**
     * Get a list with all the keys from the HashMapObject with the same order as they are stored.
     *
     * @return array List of strings containing all the HashMapObject sorted keys.
     */
    public function getKeys(){

        $result = [];
        $keys = array_keys($this->_data);

        // Keys must be strictly converted to strings
        foreach ($keys as $k) {

            $result[] = (string)$k;
        }

        return $result;
    }


    /**
     * Get a list with all the values from the HashMapObject with the same order as they are stored.
     *
     * @return array List of elements containing all the HashMapObject sorted values
     */
    public function getValues(){

        $result = [];
        $keys = array_keys($this->_data);

        // Keys must be strictly converted to strings
        foreach ($keys as $k) {

            $result[] = $this->_data[$k];
        }

        return $result;
    }


    /**
     * Tells if the provided value matches a key that's stored inside the HashMapObject
     *
     * @param mixed $key A value to find on the currently stored keys.
     *
     * @return boolean True if the provided value is a valid HashMap key, false in any other case
     */
    public function isKey($key){

        return is_string($key) && array_key_exists($key, $this->_data);
    }


    /**
     * Delete a key/value pair from the HashMapObject, given it's key.
     *
     * @param string $key The key for the key/value pair we want to delete
     *
     * @throws InvalidArgumentException
     * @return mixed The value from the key/value pair that's been deleted.
     */
    public function remove($key){

        if(array_key_exists($key, $this->_data)){

            $value = $this->_data[$key];

            unset($this->_data[$key]);

            $this->_length --;

            return $value;
        }

        $this->_validateKeyFormat($key);

        throw new InvalidArgumentException('HashMapObject->rename: key does not exist '.$key);
    }


    /**
     * Change the name for an existing key
     *
     * @param string $key The name we want to change
     * @param string $newKey The new name that will replace the previous one
     *
     * @throws InvalidArgumentException
     * @return boolean True if rename was successful
     */
    public function rename($key, $newKey){

        $this->_validateKeyFormat($key);
        $this->_validateKeyFormat($newKey);

        if($this->isKey($newKey)){

            throw new InvalidArgumentException('HashMapObject->rename: newKey '.$newKey.' already exists');
        }

        if($this->isKey($key)){

            $result = [];
            $keys = $this->getKeys();

            foreach ($keys as $k) {

                if($k == $key){

                    $result[$newKey] = $this->_data[$key];

                }else{

                    $result[$k] = $this->_data[$k];
                }
            }

            $this->_data = $result;

            return true;

        }else{

            throw new InvalidArgumentException('HashMapObject->rename: key does not exist '.$key);
        }
    }


    /**
     * Exchange the positions for two key/value pairs on the HashMapObject sorted elements list
     *
     * @param string $key1 The first key to exchange
     * @param string $key2 The second key to exchange
     *
     * @return boolean True if the two key/value pairs positions were correctly exchanged
     * @throws InvalidArgumentException If any of the two provided keys does not exist or is invalid
     */
    public function swap($key1, $key2){

        $this->_validateKeyFormat($key1);
        $this->_validateKeyFormat($key2);

        if(!$this->isKey($key1)){

            throw new InvalidArgumentException('HashMapObject->swap: key1 does not exist '.$key1);
        }

        if(!$this->isKey($key2)){

            throw new InvalidArgumentException('HashMapObject->swap: key2 does not exist '.$key2);
        }

        $result = [];
        $keys = $this->getKeys();
        $key1Value = $this->get($key1);
        $key2Value = $this->get($key2);

        foreach ($keys as $k) {

            switch ($k) {

                case $key1:
                    $result[$key2] = $key2Value;
                    break;

                case $key2:
                    $result[$key1] = $key1Value;
                    break;

                default:
                    $result[$k] = $this->_data[$k];
                    break;
            }
        }

        $this->_data = $result;

        return true;
    }


    /**
     * Sort the key/value pairs inside the HashMapObject by their key values.
     *
     * @param string $method Defines sort mode: HashMapObject::SORT_STRING or HashMapObject::SORT_NUMERIC
     * @param string $order Defines the order for the sorted elements: self::SORT_ORDER_ASCENDING (default) or self::SORT_ORDER_DESCENDING
     *
     * @throws InvalidArgumentException
     * @return boolean True if sort was successful false on failure
     */
    public function sortByKey(string $method = self::SORT_METHOD_STRING, string $order = self::SORT_ORDER_ASCENDING){

        switch ($method.$order) {

            case self::SORT_METHOD_STRING.self::SORT_ORDER_ASCENDING:
                ksort($this->_data, SORT_STRING);
                break;

            case self::SORT_METHOD_STRING.self::SORT_ORDER_DESCENDING:
                krsort($this->_data, SORT_STRING);
                break;

            case self::SORT_METHOD_NUMERIC.self::SORT_ORDER_ASCENDING:
                ksort($this->_data, SORT_NUMERIC);
                break;

            case self::SORT_METHOD_NUMERIC.self::SORT_ORDER_DESCENDING:
                krsort($this->_data, SORT_NUMERIC);
                break;

            default:
                throw new InvalidArgumentException('HashMapObject->sortByKey: Unknown sort method or order');
        }

        return true;
    }


    /**
     * Remove and get the first element value from the HashMapObject sorted list
     *
     * @throws UnexpectedValueException If the HashMapObject is empty
     * @return mixed The value on the first element of the list
     */
    public function shift(){

        if($this->_length <= 0){

            throw new UnexpectedValueException('HashMapObject->shift: No elements');
        }

        $this->_length --;

        return array_shift($this->_data);
    }


    /**
     * Remove and get the last element value from the HashMapObject sorted list
     *
     * @throws UnexpectedValueException If the HashMapObject is empty
     * @return mixed The value on the last element of the list
     */
    public function pop(){

        if($this->_length <= 0){

            throw new UnexpectedValueException('HashMapObject->pop: No elements');
        }

        $this->_length --;

        return array_pop($this->_data);
    }


    /**
     * Reverse the order of the HashMapObject elements
     *
     * @return void
     */
    public function reverse(){

        $this->_data = array_reverse($this->_data);

        return true;
    }


    /**
     * Checks that specified key value has a valid format (Non empty string)
     *
     * @param mixed $key The key value to test
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    private function _validateKeyFormat($key){

        // Check if key is a non empty string.
        // We use the same logic as StringUtils::isEmpty but with some simplification for better performance
        if(!is_string($key) || str_replace([' ', "\n", "\r", "\t"], '', $key) == ''){

            throw new InvalidArgumentException('HashMapObject: key must be a non empty string');
        }
    }
}

?>