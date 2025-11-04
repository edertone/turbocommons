/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */
 
 
import { StringUtils } from './StringUtils';

    
/**
 * Common operations and tools related with numbers
 */ 
export class NumericUtils {
    
    
    /**
     * Defines the error message for an exception when a non-numeric value is detected.
     */
    static readonly NON_NUMERIC_ERROR = 'value is not numeric';
    
    
    /**
     * Checks if the given value is numeric.
     *
     * @param value A value to check.
     * @param decimalDivider The decimal divider to use. Possible values are '.' and ','. If not provided, it will be auto-detected.
     *
     * @return true if the given value is numeric, false otherwise.
     */
    public static isNumeric(value:any, decimalDivider = '') {
        
        try {

            NumericUtils._formatNumericString(value, decimalDivider);

        } catch (error) {

            return false;
        }

        return true;
    }
    
    
    /**
     * Tells if the given value is a numeric integer or not
     *
     * @param value A value to check
     *
     * @return true if the given value is a numeric integer or represents a a numeric integer value, false otherwise
     */
    public static isInteger(value:any) {
        
        if(!NumericUtils.isNumeric(value)){

            return false;
        }

        return String(this._formatNumericString(value)).indexOf('.') < 0;
    }


    /**
     * Strictly check that the provided value is numeric or throw an exception
     *
     * @param value A value to check
     * @param valueName The name of the value to be shown at the beginning of the exception message
     * @param errorMessage The rest of the exception message
     *
     * @throws Error If the check fails
     *
     * @return void
     */
    public static forceNumeric(value:any, valueName = '', errorMessage = 'must be numeric'){

        if(!this.isNumeric(value)){

            throw new Error(valueName + ' ' + errorMessage);
        }
    }


    /**
     * Strictly check that the provided value is a positive integer or throw an exception
     *
     * @param value A value to check
     * @param valueName The name of the value to be shown at the beginning of the exception message
     * @param errorMessage The rest of the exception message
     *
     * @throws Error If the check fails
     *
     * @return void
     */
    public static forcePositiveInteger(value:any, valueName = '', errorMessage = 'must be a positive integer'){

        if(!this.isInteger(value) || value <= 0){

            throw new Error(valueName + ' ' + errorMessage);
        }
    }
    
    
    /**
     * Get the number represented by the given value
     *
     * @param value A value to convert to a number
     * @param decimalDivider The decimal divider to use. Possible values are '.' and ','. If not provided, it will be auto-detected.
     *
     * @return The numeric type representation from the given value. For example, a string '0001' will return 1
     */
    public static getNumeric(value:any, decimalDivider = '') {
    
        return Number(NumericUtils._formatNumericString(value, decimalDivider));
    }
    
    
    /**
     * Generate a random integer between the specified range (both extremes are included).
     *
     * @param min lowest possible value (negative values are allowed)
     * @param max highest possible value (negative values are allowed)
     * 
     * @throws Exception if max is equal or less than min.
     *
     * @return A random integer value between min and max
     */
    public static generateRandomInteger(min:number, max:number) {
        
        if(!NumericUtils.isInteger(max) || !NumericUtils.isInteger(min)){

            throw new Error('max and min must be integers');
        }
        
        if(max <= min){

            throw new Error('max must be higher than min');
        }
        
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
    
    
    /**
     * Format a given value to a numeric string. If the conversion is not possible, an exception will be thrown
     *
     * @param value A value to format
     * @param decimalDivider The decimal divider to use. possible values are '.' and ','. It will be auto detected If set to empty string
     *
     * @return The formatted numeric string.
     *
     * @throws Error If the value is not numeric or if the decimal divider is invalid.
     */
    private static _formatNumericString(value:any, decimalDivider = ''){

        if(decimalDivider !== '' && decimalDivider !== '.' && decimalDivider !== ','){

            throw new Error('Invalid decimal divider');
        }

        if(StringUtils.isString(value)){

            value = value.trim().replace(/\s/g, '');
            let decimalDividerPosition = -1;
            let comaLastPosition = value.lastIndexOf(",");
            let dotLastPosition = value.lastIndexOf(".");

            switch (decimalDivider) {

                case '.':
                    // No comas are allowed after a dot
                    if(StringUtils.countStringOccurences(value, '.') > 1 ||
                       (comaLastPosition >= 0 && dotLastPosition >= 0 && comaLastPosition > dotLastPosition)){

                        throw new Error(NumericUtils.NON_NUMERIC_ERROR);
                    }

                    if(dotLastPosition > 0){

                        decimalDividerPosition = dotLastPosition;
                    }
                    break;

                case ',':
                    // No dots are allowed after a coma
                    if(StringUtils.countStringOccurences(value, ',') > 1 ||
                       (comaLastPosition >= 0 && dotLastPosition >= 0 && dotLastPosition > comaLastPosition)){

                        throw new Error(NumericUtils.NON_NUMERIC_ERROR);
                    }

                    if(comaLastPosition > 0){

                        decimalDividerPosition = comaLastPosition;
                    }
                    break;

                default:
                    decimalDividerPosition = Math.max(comaLastPosition, dotLastPosition);
            }

            value = value.replace(/,/g, '.');
            let valueExploded = value.split('.');
            let valueExplodedCount = valueExploded.length;

            // Ending dot or coma is allowed if there is only one
            if(value.slice(-1) === '.' && StringUtils.countStringOccurences(value, '.') > 1){

                throw new Error(NumericUtils.NON_NUMERIC_ERROR);
            }

            // Dot symbols must split 3 consecutive digits except the decimal divider one
            if(valueExplodedCount > 2){

                if(valueExploded[0].split('-').join('').length > 3){

                    throw new Error(NumericUtils.NON_NUMERIC_ERROR);
                }

                for (let i = 1; i < valueExplodedCount - 1; i++) {

                    if(valueExploded[i].length !== 3){

                        throw new Error(NumericUtils.NON_NUMERIC_ERROR);
                    }
                }
            }

            // Remove all dots except the one at the decimal divider position
            value = (decimalDividerPosition < 0) ?
                StringUtils.replace(value, '.', '') :
                StringUtils.replace(value, '.', '', StringUtils.countStringOccurences(value, '.') - 1);
        }
        
        if(!(!isNaN(parseFloat(value)) && isFinite(value))){

            throw new Error(NumericUtils.NON_NUMERIC_ERROR);
        }

        return String(value);
    }
}