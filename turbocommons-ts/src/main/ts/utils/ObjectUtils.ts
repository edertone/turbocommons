/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


import { ArrayUtils } from './ArrayUtils';
import { StringUtils } from "./StringUtils";
import { ValidationManager } from '../managers/ValidationManager';


/**
 * Utilities to perform common object operations
 * 
 * @class
 */
export class ObjectUtils {


    /**
     * Tells if the given value is an object or not
     *
     * @param value A value to check
     *
     * @returns true if the given value is an object, false otherwise
     */
    public static isObject(value:any):boolean{
    
        return !(ArrayUtils.isArray(value) || value === null || value instanceof RegExp || typeof value !== 'object');
    }
    

    /**
     * Get the list of literals for a given object. Notice that only 1rst depth keys are providen
     * 
     * @param object A valid object
     *
     * @returns List of strings with the first level object key names in the same order as defined on the object instance
     */
    public static getKeys(object:any):string[]{

        if(!ObjectUtils.isObject(object)){

            throw new Error("parameter must be an object");
        }

        return Object.keys(object);
    }


    /**
     * Check if two provided objects are identical.
     * Notice that properties order does not alter the comparison. So if two objects 
     * have the same properties with exactly the same values, but they appear in a different
     * order on both objects, this method will consider them as equal.
     * 
     * @param object1 First object to compare
     * @param object2 Second object to compare
     *
     * @returns true if objects are exactly the same, false if not
     */
    public static isEqualTo(object1:any, object2:any):boolean{

         let validationManager = new ValidationManager();

        // Both provided values must be objects or an exception will be launched
        if(!ObjectUtils.isObject(object1) || !ObjectUtils.isObject(object2)){

            throw new Error("parameters must be objects");
        }

        let keys1:string[] = ObjectUtils.getKeys(object1).sort();
        let keys2:string[] = ObjectUtils.getKeys(object2).sort();

        // Compare keys can save a lot of time 
        if(!ArrayUtils.isEqualTo(keys1, keys2)){

            return false;
        }

        // Loop all the keys and verify values are identical
        for(let i:number = 0; i < keys1.length; i++){

            if(!validationManager.isEqualTo(object1[keys1[i]], object2[keys2[i]])){

                return false;
            }
        }

        return true;
    }
    
    
    /**
     * Check if the provided string is found inside the provided object structure.
     * This method will recursively search inside all the provided object properties and test if the provided string is found.
     * Search will be performed inside any object structures like arrays or other objects. Result will be positive even if 
     * any string on the object contains the searched text as a substring.
     * 
     * @param object The object where the string will be looked for
     * @param str The string that will be searched on the object
     * @param caseSensitive True (default) to perform a case sensitive search, false otherwise
     * 
     * @returns True if the string is found anywhere inside the provided object, false otherwise 
     */
    public static isStringFound(object:any, str:string, caseSensitive = true):boolean{
        
        if(!ObjectUtils.isObject(object)){

            throw new Error("parameter must be an object");
        }
        
        if(!StringUtils.isString(str)){

            throw new Error("str is not a string");
        }
        
        const keys = ObjectUtils.getKeys(object);

        for (const key of keys) {

            if (StringUtils.isString(object[key]) &&
                ((caseSensitive && object[key].indexOf(str) >= 0) ||
                (!caseSensitive && object[key].toLowerCase().indexOf(str.toLowerCase()) >= 0))) {

                return true;
            }
            
            if(ArrayUtils.isArray(object[key]) && ArrayUtils.isStringFound(object[key], str, caseSensitive)){
                
                return true;
            }
            
            if(ObjectUtils.isObject(object[key]) && ObjectUtils.isStringFound(object[key], str, caseSensitive)){
                
                return true;
            }
        }

        return false;
    }
    
    
    /**
     * Combine a source object into a destination one by applying a deep merge.
     * All properties from the source will be replaced into the destination object, without altering the
     * destination properties that are not found on source. 
     * 
     * @param destination The object that will be overriden with the source one. The given instance will be permanently modified.
     * @param source An object to merge into the destination one. This instance will not be modified
     * 
     * @returns The destination object instance after being modified by merging the source object into it
     */
    public static merge(destination:any, source:any) {
     
        if(!ObjectUtils.isObject(destination) || !ObjectUtils.isObject(source)){
            
            throw new Error('destination and source must objects');
        }
        
        let sourceKeys = ObjectUtils.getKeys(source);
        
        // Loop all the source object keys and merge them into the destination
        for(let key of sourceKeys){
            
            if(destination.hasOwnProperty(key) &&
               ObjectUtils.isObject(source[key]) &&
               ObjectUtils.isObject(destination[key])){
                
                destination[key] = ObjectUtils.merge(destination[key], source[key]);
                
            }else{
                
                destination[key] = ObjectUtils.clone(source[key]);
            }
        }
        
        return destination;
    }
    
    
    /**
     * Perform a deep copy of the given object.
     *
     * @param object Any language instance like numbers, strings, arrays, objects, etc.. that we want to duplicate.
     *
     * @returns An exact independent copy of the received object, without any shared reference.
     */
    public static clone(object:any) {
        
        return ObjectUtils.apply(object, (o:any) => {

            return ObjectUtils.isObject(o) ? new object.constructor() : o;
        });
    }
    
    
    /**
     * Apply a given function to each value of the provided object (Recursively through all the object elements). It will also scan
     * inside arrays and sub objects.
     *
     * NOTICE: Original object is not modified
     *
     * @param object Any language instance like numbers, strings, arrays, objects, etc.. that we want to process.
     * @param callableFunction A function that takes a single argument and returns a value. It must always return a value, cause
     *        it will be assigned to the original object
     *
     * @returns An exact independent copy of the received object, without any shared reference, where each value has been processed
     *         by the provided callable function.
     */
    public static apply(object:any, callableFunction:(v:any) => any){

        if(ArrayUtils.isArray(object)){

            let result:any[] = [];

            for(const element of object){

                result.push(ObjectUtils.apply(element, callableFunction));
            }

            return result;
        }

        if(ObjectUtils.isObject(object)){

            let result = new object.constructor();

            for(let key in object) {

                result[key] = ObjectUtils.apply(object[key], callableFunction);
            }

            return result;
        }

        return callableFunction(object);
    }        
}