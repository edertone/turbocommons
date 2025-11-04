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

use Throwable;
use UnexpectedValueException;
use org\turbocommons\src\main\php\managers\ValidationManager;
use org\turbocommons\src\main\php\utils\ArrayUtils;
use org\turbocommons\src\main\php\utils\EncodingUtils;
use org\turbocommons\src\main\php\utils\StringUtils;


/**
 * Object that stores java properties file format data
 */
class JavaPropertiesObject extends HashMapObject {


    /**
     * Create a JavaPropertiesObject instance. Java properties is a text file format that stores data
     * into text files with information that is arranged as key/value pairs.
     * For example: tag1=value1
     *
     * @param string $string String containing the contents of a .properties Java file.
     * Note that string must be encoded with ISO-8859-1 and strictly follow the Java
     * properties file format (Otherwise results won't be correct).
     *
     * @see HashMapObject
     * @return JavaPropertiesObject The java properties object with data accessible as key/value pairs.
     */
    public function __construct($string = ''){

        if(!StringUtils::isString($string)){

            throw new UnexpectedValueException('value must be a string');
        }

        if($string === ''){

            return;
        }

        // Validate received string
        if(strlen($string) < 2 || substr($string, 0, 1) === '=' || (strpos($string, '=') === false && strpos($string, ':') === false)){

            throw new UnexpectedValueException('invalid properties format');
        }

        $key = '';
        $value = '';
        $isWaitingOtherLine = false;

        // Generate an array with the properties lines, ignoring blank lines and comments
        $lines = StringUtils::getLines($string, ['/\s+/', '/ *#.*| *!.*/']);

        foreach($lines as $line) {

            // Remove all blank spaces at the beginning of the line
            $line = ltrim($line);

            if($isWaitingOtherLine) {

                $value .= EncodingUtils::unicodeEscapedCharsToUtf8($line);

            }else{

                // Find the key/value divider index
                $tmpLine = str_replace(['\\=', '\\:'], 'xx', $line);
                $keyDividerIndex = min([stripos($tmpLine.'=', '='), stripos($tmpLine.':', ':')]);

                // Extract the key from the line
                $key = trim(substr($line, 0, $keyDividerIndex));

                // Add a space to the end if the last character is a \
                if(substr($key, strlen($key) - 1, 1) === '\\'){

                    $key .= ' ';
                }

                $key = str_replace(['\\\\', '\\ ', '\\#', '\\!', '\\=', '\\:'], ['\\', ' ', '#', '!', '=', ':'], $key);

                // Extract the value from the line
                $value = ltrim(substr($line, $keyDividerIndex + 1, strlen($line)));
            }

            // Unescape escaped slashes and spaces on the value
            $value = str_replace(['\\\\', '\\ ', '\\r\\n', '\\n', '\\t'], ['\\u005C', ' ', "\r\n", "\n", "\t"], $value);

            // Check if ends with single '\'
            if(substr($value, -1) == '\\'){

                // Remove trailing backslash
                $value = substr_replace($value, '', -1);

                $isWaitingOtherLine = true;

            }else{

                $isWaitingOtherLine = false;

                // Decode unicode characters
                $value = EncodingUtils::unicodeEscapedCharsToUtf8($value);
            }

            $this->_data[$key] = $value;
        }

        $this->_length = count($this->_data);
    }


    /**
     * Tells if the given value contains valid Java Properties data information or not
     *
     * @param mixed $value A value to check (a string or a JavaPropertiesObject instance)
     *
     * @return boolean true if the given value contains valid Java Properties data, false otherwise
     */
    public static function isJavaProperties($value){

        // test that received string contains valid properties info
        try {

            $p = new JavaPropertiesObject($value);

            return $p->length() >= 0;

        } catch (Throwable $e) {

            try {

                return ($value != null) && (get_class($value) === 'org\\turbocommons\\src\\main\\php\\model\\JavaPropertiesObject');

            } catch (Throwable $e) {

                return false;
            }
        }
    }


    /**
     * Check if the provided java properties is identical to this instance
     * Only data is compared: Any comment that is found on both provided properties will be ignored.
     *
     * @param mixed $properties java properties value to compare (a string or a JavaPropertiesObject instance)
     * @param boolean $strictOrder If set to true, both properties elements must have the same keys with the same order. Otherwise differences in key sorting will be accepted
     *
     * @return boolean true if both java properties data is exactly the same, false if not
     */
    public function isEqualTo($properties, bool $strictOrder = false){

        $objectToCompare = null;

        try {

            $objectToCompare = new JavaPropertiesObject($properties);

        } catch (Throwable $e) {

            try {

                if(get_class($properties) === 'org\\turbocommons\\src\\main\\php\\model\\JavaPropertiesObject'){

                    $objectToCompare = $properties;
                }

            } catch (Throwable $e) {

                // Nothing to do
            }
        }

        if($objectToCompare == null){

            throw new UnexpectedValueException('properties does not contain valid java properties data');
        }

        $thisKeys = $this->getKeys();
        $keysToCompare = $objectToCompare->getKeys();

        if(count($thisKeys) != count($keysToCompare) || ($strictOrder && !ArrayUtils::isEqualTo($thisKeys, $keysToCompare))){

            return false;
        }

        $validationManager = new ValidationManager();

        foreach ($thisKeys as $key) {

            if(!$strictOrder && !$objectToCompare->isKey($key)){

                return false;
            }

            if(!$validationManager->isEqualTo($this->get($key), $objectToCompare->get($key))){

                return false;
            }
        }

        return true;
    }


    /**
     * Generate the textual representation for the java properties data stored on this object.
     * The output of this method is ready to be stored on a physical .properties file.
     *
     * @return string A valid Java .properties string ready to be stored on a .properties file
     */
    public function toString(){

        $result = [];
        $keys = $this->getKeys();
        $keysCount = count($keys);

        for ($i = 0; $i < $keysCount; $i++) {

            $key = str_replace(['\\', ' ', '#', '!', '=', ':'], ['\\\\', '\\ ', '\\#', '\\!', '\\=', '\\:'], $keys[$i]);

            $value = str_replace(['\\', ' ', "\r\n", "\n", "\t"], ['\\\\', '\\ ', '\\r\\n', '\\n', '\\t'], $this->get($keys[$i]));

            $result[] = $key.'='.EncodingUtils::utf8ToUnicodeEscapedChars($value);
        }

        return implode("\r\n", $result);
    }
}

?>