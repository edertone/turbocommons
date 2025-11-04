/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */
    
    
import { ObjectUtils } from './ObjectUtils';
import { StringUtils } from "./StringUtils";
import { ValidationManager } from "../managers/ValidationManager";


/**
 * Utilities to perform common array operations
 */
export class ArrayUtils {


    /**
     * Tells if the given value is an array or not
     *
     * @param value A value to check
     *
     * @returns true if the given value is an array, false otherwise
     */
    public static isArray(value:any):boolean {
        
       return Object.prototype.toString.call(value) === '[object Array]'; 
    }
    

    /**
     * Check if two provided arrays are identical (have exactly the same elements and in the same order).
     * 
     * @param array1 First array to compare
     * @param array2 Second array to compare
     *
     * @returns true if arrays are exactly the same, false if not
     */
    public static isEqualTo(array1:any[], array2:any[]):boolean{

        // Both provided values must be arrays or an exception will be launched
        if(!ArrayUtils.isArray(array1) || !ArrayUtils.isArray(array2)){

            throw new Error('parameters must be arrays');
        }

        // Compare lengths can save a lot of time 
        if(array1.length != array2.length){

            return false;
        }

        for(let i = 0, l = array1.length; i < l; i++){

            // Check if we have nested arrays
            if(ArrayUtils.isArray(array1[i]) && ArrayUtils.isArray(array2[i])){

                if(!ArrayUtils.isEqualTo(array1[i], array2[i])){

                    return false;
                }

            }else{

                if(ObjectUtils.isObject(array1[i]) && ObjectUtils.isObject(array2[i])){

                    if(!ObjectUtils.isEqualTo(array1[i], array2[i])){

                        return false;
                    }

                }else if(array1[i] !== array2[i]){

                    return false;
                }
            }
        }

        return true;
    }
    
    
    /**
     * Check if the provided string is found inside the provided array structure.
     * This method will recursively search inside all the provided array elements and test if the provided string is found.
     * Search will be performed inside any array structures like arrays or other objects. Result will be positive even if 
     * any string on the array contains the searched text as a substring.
     * 
     * @param array The array where the string will be looked for
     * @param str The string that will be searched on the array
     * @param caseSensitive True (default) to perform a case sensitive search, false otherwise
     * 
     * @returns True if the string is found anywhere inside the provided array, false otherwise 
     */
    public static isStringFound(array:any[], str:string, caseSensitive = true):boolean{
        
        if(!ArrayUtils.isArray(array)){

            throw new Error("parameter must be an array");
        }
        
        if(!StringUtils.isString(str)){

            throw new Error("str is not a string");
        }
        
        for (const element of array) {
    
            if (StringUtils.isString(element) &&
                ((caseSensitive && element.indexOf(str) >= 0) ||
                (!caseSensitive && element.toLowerCase().indexOf(str.toLowerCase()) >= 0))) {

                return true;
            }
            
            if(ArrayUtils.isArray(element) && ArrayUtils.isStringFound(element, str, caseSensitive)){
                
                return true;
            }
            
            if(ObjectUtils.isObject(element) && ObjectUtils.isStringFound(element, str, caseSensitive)){
                
                return true;
            }
        }

        return false;
    }
    
    
    // TODO - translate from PHP
    public static findMostRepeatedValue(){

        // TODO - translate from PHP
    }
    
    
    /**
     * Strictly check that the provided value is a non empty array or throw an exception
     *
     * @param value A value to check
     * @param valueName The name of the value to be shown at the beginning of the exception message
     * @param errorMessage The rest of the exception message
     *
     * @throws Error If the check fails
     *
     * @return void
     */
    public static forceNonEmptyArray(value:any, valueName = '', errorMessage = 'must be a non empty array'){

        if(!this.isArray(value) || value.length <= 0){

            throw new Error(valueName + ' ' + errorMessage);
        }
    }


    /**
     * Remove the specified item from an array
     * 
     * @param array An array (it will not be modified by this method)
     * @param element The element that must be removed from the given array
     *
     * @returns The provided array but without the specified element (if found). Note that originally received array is not modified by this method
     */
    public static removeElement(array:any[], element:any){

        // Provided array must be an array
        if(!ArrayUtils.isArray(array)){

            throw new Error('parameter must be an array');
        }

        var res:any[] = [];

        if(ArrayUtils.isArray(element)){

            for(var i:number = 0; i < array.length; i++){

                if(!ArrayUtils.isArray(array[i])){

                    res.push(array[i]);

                }else{

                    if(!ArrayUtils.isEqualTo(element, array[i])){

                        res.push(array[i]);
                    }
                }
            }

        }else{

            for(var j:number = 0; j < array.length; j++){

                if(element !== array[j]){

                    res.push(array[j]);
                }
            }
        }

        return res;
    }
    
    
    /**
     * remove all the duplicate values on the provided array
     * Duplicate values with different data types won't be considered as equal ('1', 1 will not be removed)
     *
     * @param array An array with possible duplicate values
     *
     * @return The same provided array but without duplicate elements
     */
    public static removeDuplicateElements(array: any[]){

        // Provided array must be an array
        if(!ArrayUtils.isArray(array)){

            throw new Error('parameter must be an array');
        }
        
        let result = [];
        let numElements = array.length;

        let validationManager = new ValidationManager();

        for (let i = 0; i < numElements; i++) {

            let found = false;

            let resultCount = result.length;

            for (let j = 0; j < resultCount; j++) {

                if(validationManager.isEqualTo(array[i], result[j])){

                    found = true;
                    break;
                }
            }

            if(!found){

                result.push(array[i]);
            }
        }

        return result;
    }
    
    
    /**
     * Check if the given array contains duplicate values or not.
     * Duplicate values with different data types won't be considered as equal ('1', 1 will return false)
     *
     * @param array An array containing some elements to test
     *
     * @return True if there are duplicate values, false otherwise
     */
    public static hasDuplicateElements(array: any[]){

        // Provided array must be an array
        if(!ArrayUtils.isArray(array)){

            throw new Error('parameter must be an array');
        }
        
        let numElements = array.length;

        let validationManager = new ValidationManager();

        for (let i = 0; i < numElements; i++) {

            for (let j = i + 1; j < numElements; j++) {

                if(validationManager.isEqualTo(array[i], array[j])){

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
     * @param array An array containing some elements to test
     *
     * @return list with all the elements that are duplicated on the provided array
     */
    public static getDuplicateElements(array: any[]){

        // Provided array must be an array
        if(!ArrayUtils.isArray(array)){

            throw new Error('parameter must be an array');
        }
        
        let result = [];
        let numElements = array.length;

        let validationManager = new ValidationManager();

        for (let i = 0; i < numElements; i++) {

            for (let j = i + 1; j < numElements; j++) {

                if(validationManager.isEqualTo(array[i], array[j])){

                    result.push(array[i]);
                }
            }
        }

        return ArrayUtils.removeDuplicateElements(result);
    }
}