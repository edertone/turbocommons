<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\managers;

use UnexpectedValueException;
use stdClass;
use org\turbocommons\src\main\php\model\JavaPropertiesObject;
use org\turbocommons\src\main\php\utils\ArrayUtils;
use org\turbocommons\src\main\php\utils\ObjectUtils;
use org\turbocommons\src\main\php\utils\NumericUtils;


/**
 * Contains methods that allow us to convert data from one complex data structure
 * format to another complex data structure format
 */
class SerializationManager{


    /**
     * When set to true, the structures that are passed as serialization sources must match the structures
     * that are passed as serialization targets: All keys or properties that are defined on the serialization sources
     * must exist on the serialization targets, otherwise an exception will be thrown
     */
    public $strictMode = true;


    /**
     * TODO - review from TS
     */
    public function classToJson(){

        // TODO - review from TS
    }


    /**
     * TODO - review from TS
     */
    public function classToObject(){

        // TODO - review from TS
    }


    /**
     * TODO - review from TS
     */
    public function hashMapObjectToClass(){

        // TODO - review from TS
    }


    /**
     * Convert a JavaPropertiesObject instance to a string that is valid so it can be saved to a .properties file.
     *
     * @param JavaPropertiesObject $javaProperties An instance of a JavaPropertiesObject
     *
     * @return string An ISO-8859-1 string containing valid properties data, ready to be stored as a .properties java format file.
     */
    public function javaPropertiesObjectToString(JavaPropertiesObject $javaProperties){

        return $javaProperties->toString();
    }


    /**
     * Copy data from a json string to a class instance. All class properties will be filled with the values from the json
     * For more information on how the conversion is performed, see this class objectToClass method
     *
     * @see SerializationManager::objectToClass
     *
     * @param string $string A string containing valid json data
     * @param mixed $classInstance A class instance that will be filled with all the json data (the instance is modified by this method and all values erased).
     *
     * @return mixed The provided class instance with all its properties filled with the corresponding json values
     */
    public function jsonToClass($string, $classInstance){

        return $this->objectToClass(json_decode($string, false), $classInstance);
    }


    /**
     * Copy data from an object instance to a class instance. All class properties will be filled with the values
     * from the object.
     *
     * If a property from the class instance contains a default value, it will be used as a reference to restrict
     * the value type. If the same key on the object has a different type value, an exception will happen.
     * Null values on the source object keys will leave the same destination class properties untouched.
     *
     * Typed arrays can be forced by setting a class property as an array with a single default item. That item type
     * will be used as the reference for all the array values on the object property.
     *
     * @param stdclass $object An object containing the source data to serialize
     * @param mixed $classInstance An empty class instance that will be filled with all the values from the object
     *
     * @return mixed The provided class instance with all its properties filled with the corresponding object values
     */
    public function objectToClass(stdclass $object, $classInstance){

        $objectKeys = ObjectUtils::getKeys($object);
        $classInstanceName = get_class($classInstance);
        $classInstanceKeys = ObjectUtils::getKeys($classInstance);

        // On strict mode, verify that both objects have the same number of keys
        if ($this->strictMode && count($objectKeys) !== count($classInstanceKeys)) {

            throw new UnexpectedValueException("(strict mode): [" . implode(',', $objectKeys) . "] keys do not match $classInstanceName props: [" . implode(',', $classInstanceKeys) . "]");
        }

        // Loop all the received object keys and store each value on the respective class property
        foreach ($objectKeys as $key) {

            // Check if key exists on class instance
            if (!property_exists($classInstance, $key)) {

                if ($this->strictMode) {

                    throw new UnexpectedValueException("(strict mode): <$key> not found in $classInstanceName");
                }

                continue;
            }

            $value = $object->$key;

            // A null key value will leave the property value untouched
            if ($value === null) {

                continue;
            }

            // If property has an explicit null or undefined default value, any type is allowed.
            if ($classInstance->$key !== null) {

                $typeErrorMessage = "<$classInstanceName.$key> was " . gettype($value) . " but expected to be ";

                if (ArrayUtils::isArray($classInstance->$key)) {

                    if (!ArrayUtils::isArray($value)) {

                        throw new UnexpectedValueException($typeErrorMessage . 'array');
                    }

                    if (count($classInstance->$key) > 0) {

                        if (count($classInstance->$key) !== 1) {

                            throw new UnexpectedValueException("To define a typed list, <$classInstanceName.$key> must contain only 1 default typed element");
                        }

                        $defaultElement = $classInstance->$key[0];
                        $isDefaultElementAClass = (ObjectUtils::isObject($defaultElement) && get_class($defaultElement) !== 'stdClass');

                        $classInstance->$key = [];

                        foreach ($value as $o) {

                            if ($isDefaultElementAClass) {

                                $classInstance->$key[] = $this->objectToClass($o, ObjectUtils::clone($defaultElement));

                            } else {

                                // Type of array elements must match the default value
                                if (gettype($o) !== gettype($defaultElement) &&
                                    !(NumericUtils::isNumeric($o, '.') && NumericUtils::isNumeric($defaultElement, '.'))) {

                                    throw new UnexpectedValueException("<$classInstanceName.$key> is defined as array of " . gettype($defaultElement) . " but received " .
                                        (NumericUtils::isNumeric($o, '.') ? 'number' : gettype($o)));
                                }

                                $classInstance->$key[] = $o;
                            }
                        }

                        continue;
                    }
                }

                if (ObjectUtils::isObject($classInstance->$key)) {

                    if (!ObjectUtils::isObject($value)) {

                        throw new UnexpectedValueException($typeErrorMessage . get_class($classInstance->$key));
                    }

                    if (get_class($classInstance->$key) !== 'stdClass') {

                        $value = $this->objectToClass($value, $classInstance->$key);
                    }
                }

                // Type of both object key and class property must match
                if (gettype($classInstance->$key) !== gettype($value) &&
                    !(NumericUtils::isNumeric($classInstance->$key, '.') && NumericUtils::isNumeric($value, '.'))) {

                        throw new UnexpectedValueException($typeErrorMessage .
                            (NumericUtils::isNumeric($classInstance->$key, '.') ? 'number' : gettype($classInstance->$key)));
                }
            }

            $classInstance->$key = $value;
        }

        return $classInstance;
    }


    /**
     * Convert a string containing the contents of a Java properties file to a JavaPropertiesObject instance
     * Note that the input string must be encoded with ISO-8859-1 and strictly follow the Java
     * properties file format (Otherwise results may not be correct).
     *
     * @param string $string String containing the contents of a .properties Java file
     *
     * @return JavaPropertiesObject The properties format parsed as an object
     */
    public function stringToJavaPropertiesObject($string){

        return new JavaPropertiesObject($string);
    }
}
