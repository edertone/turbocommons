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

use Throwable;
use UnexpectedValueException;
use InvalidArgumentException;


/**
 * Common operations and tools related with numbers
 */
class NumericUtils {


    /**
     * Defines the error message for an exception when a non-numeric value is detected.
     * @constant string
     */
    const NON_NUMERIC_ERROR = 'value is not numeric';


    /**
     * Checks if the given value is numeric.
     *
     * @param mixed $value A value to check.
     * @param string $decimalDivider The decimal divider to use. Possible values are '.' and ','. If not provided, it will be auto-detected.
     *
     * @return bool true if the given value is numeric, false otherwise.
     */
    public static function isNumeric($value, $decimalDivider = ''){

        try {

            self::_formatNumericString($value, $decimalDivider);

        } catch (Throwable $e) {

            return false;
        }

        return true;
    }


    /**
     * Tells if the given value is a numeric integer or not
     *
     * @param mixed $value A value to check
     *
     * @return boolean true if the given value is a numeric integer or represents a numeric integer value, false otherwise
     */
    public static function isInteger($value){

        if(!self::isNumeric($value)){

            return false;
        }

        return strpos(self::_formatNumericString($value), '.') === false;
    }


    /**
     * Strictly check that the provided value is numeric or throw an exception
     *
     * @param mixed $value A value to check
     * @param string $valueName The name of the value to be shown at the beginning of the exception message
     * @param string $errorMessage The rest of the exception message
     *
     * @throws InvalidArgumentException If the check fails
     *
     * @return void
     */
    public static function forceNumeric($value, string $valueName = '', string $errorMessage = 'must be numeric'){

        if(!self::isNumeric($value)){

            throw new InvalidArgumentException($valueName.' '.$errorMessage);
        }
    }


    /**
     * Strictly check that the provided value is a positive integer or throw an exception
     *
     * @param mixed $value A value to check
     * @param string $valueName The name of the value to be shown at the beginning of the exception message
     * @param string $errorMessage The rest of the exception message
     *
     * @throws InvalidArgumentException If the check fails
     *
     * @return void
     */
    public static function forcePositiveInteger($value, string $valueName = '', string $errorMessage = 'must be a positive integer'){

        if(!self::isInteger($value) || $value <= 0){

            throw new InvalidArgumentException($valueName.' '.$errorMessage);
        }
    }


    /**
     * Get the number represented by the given value
     *
     * @param mixed $value A value to convert to a number
     * @param string $decimalDivider The decimal divider to use. Possible values are '.' and ','. If not provided, it will be auto-detected.
     *
     * @return number The numeric type representation from the given value. For example, a string '0001' will return 1
     */
    public static function getNumeric($value, $decimalDivider = ''){

        return self::_formatNumericString($value, $decimalDivider) + 0;
    }


    /**
     * Generate a random integer between the specified range (both extremes are included).
     *
     * @param int $min lowest possible value (negative values are allowed)
     * @param int $max highest possible value (negative values are allowed)
     *
     * @throws UnexpectedValueException if max is equal or less than min.
     *
     * @return int A random integer value between min and max
     */
    public static function generateRandomInteger($min, $max){

        if(!self::isInteger($max) || !self::isInteger($min)){

            throw new UnexpectedValueException('max and min must be integers');
        }

        if($max <= $min){

            throw new UnexpectedValueException('max must be higher than min');
        }

        return floor((mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax()) * ($max - $min + 1)) + $min;
    }


    /**
     * Format a given value to a numeric string. If the conversion is not possible, an exception will be thrown
     *
     * @param mixed $value A value to format
     * @param string $decimalDivider The decimal divider to use. possible values are '.' and ','. It will be auto detected If set to empty string
     *
     * @return string The formatted numeric string.
     *
     * @throws UnexpectedValueException If the value is not numeric or if the decimal divider is invalid.
     */
    private static function _formatNumericString($value, $decimalDivider = ''){

        if($decimalDivider !== '' && $decimalDivider !== '.' && $decimalDivider !== ','){

            throw new UnexpectedValueException('Invalid decimal divider');
        }

        if(is_string($value)){

            $value = str_replace(' ', '', trim($value));
            $decimalDividerPosition = -1;
            $comaLastPosition = strrpos($value, ",");
            $dotLastPosition = strrpos($value, ".");

            switch ($decimalDivider) {

                case '.':
                    // No comas are allowed after a dot
                    if(substr_count($value, '.') > 1 ||
                       ($comaLastPosition && $dotLastPosition && $comaLastPosition > $dotLastPosition)){

                        throw new UnexpectedValueException(self::NON_NUMERIC_ERROR);
                    }

                    if($dotLastPosition > 0){

                        $decimalDividerPosition = $dotLastPosition;
                    }
                    break;

                case ',':
                    // No dots are allowed after a coma
                    if(substr_count($value, ',') > 1 ||
                       ($comaLastPosition && $dotLastPosition && $dotLastPosition > $comaLastPosition)){

                        throw new UnexpectedValueException(self::NON_NUMERIC_ERROR);
                    }

                    if($comaLastPosition > 0){

                        $decimalDividerPosition = $comaLastPosition;
                    }
                    break;

                default:
                    $decimalDividerPosition = max($comaLastPosition === false ? -1 : $comaLastPosition, $dotLastPosition === false ? -1 : $dotLastPosition);
            }

            $value = str_replace(',', '.', $value);
            $valueExploded = explode('.', $value);
            $valueExplodedCount = count($valueExploded);

            // Ending dot or coma is allowed if there is only one
            if(substr($value, -1) === '.' && substr_count($value, '.') > 1){

                throw new UnexpectedValueException(self::NON_NUMERIC_ERROR);
            }

            // Dot symbols must split 3 consecutive digits except the decimal divider one
            if($valueExplodedCount > 2){

                if(strlen(str_replace('-', '', $valueExploded[0])) > 3){

                    throw new UnexpectedValueException(self::NON_NUMERIC_ERROR);
                }

                for ($i = 1; $i < $valueExplodedCount - 1; $i++) {

                    if(strlen($valueExploded[$i]) !== 3){

                        throw new UnexpectedValueException(self::NON_NUMERIC_ERROR);
                    }
                }
            }

            // Remove all dots except the one at the decimal divider position
            $value = ($decimalDividerPosition < 0) ?
                str_replace('.', '', $value) :
                preg_replace('/\./', '', $value, substr_count($value, '.') - 1);
        }

        if(!is_numeric($value)){

            throw new UnexpectedValueException(self::NON_NUMERIC_ERROR);
        }

        return strval($value);
    }
}