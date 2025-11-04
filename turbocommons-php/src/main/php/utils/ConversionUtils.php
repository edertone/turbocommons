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
 * The most common conversion utilities to change the data from a simple type to another one.
 * To convert complex classes or structures, use SerializationUtils class.
 */
class ConversionUtils {


    /**
     * Encode a string to base64 (Which contains only numbers, uppercase, lowercase, forward slash, plus and equal signs)
     *
     * @param string $string The input string to be converted
     *
     * @return string The input string as base 64
     */
    public static function stringToBase64($string){

        if($string === null){

            return '';
        }

        $validationManager = new ValidationManager();

        if(!$validationManager->isString($string)){

            throw new InvalidArgumentException('ConversionUtils->stringToBase64: value is not a string');
        }

        return base64_encode($string);
    }


    /**
     * Decode a string from base64 (Which contains only numbers, uppercase, lowercase, forward slash, plus and equal signs)
     *
     * @param string $string a base64 string
     *
     * @return string The base64 decoded as its original string
     */
    public static function base64ToString($string){

        if($string === null){

            return '';
        }

        $validationManager = new ValidationManager();

        if(!$validationManager->isString($string)){

            throw new InvalidArgumentException('ConversionUtils->base64ToString: value is not a string');
        }

        return base64_decode($string);
    }
}

?>