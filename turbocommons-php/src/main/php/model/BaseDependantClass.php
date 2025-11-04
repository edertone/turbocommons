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

use ReflectionClass;
use UnexpectedValueException;


/**
 * A base class that implements easy dependency injection management
 */
abstract class BaseDependantClass extends BaseStrictClass {


    /**
     * This class is used to perform all reflection operations on this class
     */
    private $_reflectionClass;


    /**
     * Class constructor initializes required objects
     */
    public function __construct() {

        $this->_reflectionClass = new ReflectionClass($this);
    }


    /**
     * Injects the specified instances as dependencies to this class on the specified properties.
     * Once an instance is injected into a property, only instances of the same type will be allowed to modify the assignment.
     *
     * @param string $instances An associative array with key / value pairs, where key is the name of a private property where the value instance will be injected.
     *
     * @return void
     */
    public function setDependencies(array $instances) {

        // Array must contain values
        if(count($instances) <= 0){

            throw new UnexpectedValueException('BaseDependantClass->setDependencies expects an array containing at least one instance');
        }

        // Get all the current class properties
        $thisProperties = $this->_reflectionClass->getProperties();

        // Find the requested property
        foreach ($instances as $key=>$value) {

            $propFound = false;

            foreach ($thisProperties as $prop) {

                if($key == $prop->getName()){

                    // Specified dependency property must be private
                    if(!$prop->isPrivate()){

                        throw new UnexpectedValueException(get_class($this).' '.$key.' must be private');
                    }

                    // To prevent mistakes, specified property can only be assigned with object instances
                    if($value !== null && !is_object($value)){

                        throw new UnexpectedValueException(get_class($this).' '.$key.' must be an object');
                    }

                    $prop->setAccessible(true);

                    $propValue = $prop->getValue($this);

                    // Specified property can be assigned if not defined yet or is the same class of the previous value
                    if($propValue === null || get_class($propValue) === get_class($value)){

                        $prop->setValue($this, $value);
                        $propFound = true;
                        break;
                    }

                    throw new UnexpectedValueException(get_class($this).' '.$key.' type does not match the specified value');
                }
            }

            // If property is not found, an exception will be thrown
            if(!$propFound){

                throw new UnexpectedValueException(get_class($this).' '.$key.' does not exist');
            }
        }
    }
}

?>