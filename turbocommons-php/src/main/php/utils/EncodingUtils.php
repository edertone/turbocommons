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


/**
 * Utilities related to string and text character encoding,
 * converting between formats, and perform common encoding operations.
 */
class EncodingUtils{


    /**
     * Convert a string with unicode escaped sequence of characters (\u00ed, \u0110, ....) to an utf8 string.
     *
     * @param string $string A string containing unicode escaped characters.
     *
     * @return string An utf8 string conversion of the unicode encoded input.
     */
    public static function unicodeEscapedCharsToUtf8($string){

        if(is_string($string)){

            return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
                return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
            }, $string);
        }

        throw new InvalidArgumentException('Specified value must be a string');
    }


    /**
     * Convert a utf8 string to a string with unicode escaped sequence of characters (\u00ed, \u0110, ...).
     *
     * @param string $string A string containing an utf8 valid sequence.
     *
     * @return string A string containing escaped sequences for all the original utf8 characters
     */
    public static function utf8ToUnicodeEscapedChars($string){

        if(!is_string($string)){

            throw new InvalidArgumentException('Specified value must be a string');
        }

        if(StringUtils::isEmpty($string)){

            return $string;
        }

        $result = trim(json_encode($string, JSON_UNESCAPED_SLASHES + JSON_HEX_QUOT), '"');

        return str_replace('\\\\', '\\', $result);
    }
}

?>