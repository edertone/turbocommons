/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */
 

import { JavaPropertiesObject } from '../model/JavaPropertiesObject';
import { ArrayUtils } from '../utils/ArrayUtils';
import { ObjectUtils } from '../utils/ObjectUtils';

    
/**
 * Contains methods that allow us to convert data from one complex data structure 
 * format to another complex data structure format
 */  
export class SerializationManager {
    
    
    /**
     * When set to true, the structures that are passed as serialization sources must match the structures 
     * that are passed as serialization targets: All keys or properties that are defined on the serialization sources
     * must exist on the serialization targets, otherwise an exception will be thrown
     */
    strictMode = true;
    
    
    /**
     * Generate a valid JSON string from a given class instance
     * 
     * @param classInstance A class instance
     * 
     * @returns A valid JSON string containing all the data on the provided class
     */
    classToJson(classInstance:any){

        return JSON.stringify(classInstance);
    }
    
    
    // TODO
    classToObject(){

        // TODO
    }
    
    
    /**
     * Copy data from a HashMapObject instance to an arbitrary class instance which contains
     * the same properties as the hashmap keys. Class property values will be set to the same value of the hash map key 
     *
     * @param hashMap An object that contains data which is organized as a hash map. For example: An associative array or an object with key / value pairs
     * @param classInstance A class instance that will be filled with all the values that are found on the hashmap (the instance is modified by this method and all values erased).
     *
     * @return The provided class instance with all its properties filled with the corresponding hashmap values
     */
//    hashMapObjectToClass<T>(hashMap:HashMapObject, classInstance:T): T{
//
//        TODO - implement this and translate it to PHP
//        let keys = hashMap.getKeys(); 
//        
//        for (let key of keys) {
//
//            if(property_exists($classInstance, $key)){
//
//                $classInstance->{$key} = $value;
//            }
//        }
//
//        return classInstance;
//    }
        

    /**
     * Convert a JavaPropertiesObject instance to a string that is valid so it can be saved to a .properties file.
     *
     * @param javaProperties An instance of a JavaPropertiesObject
     *
     * @return An ISO-8859-1 string containing valid properties data, ready to be stored as a .properties java format file.
     */
    javaPropertiesObjectToString(javaProperties: JavaPropertiesObject){

        return javaProperties.toString();
    }
    
    
    /**
     * Copy data from a json string to a class instance. All class properties will be filled with the values from the json
     * For more information on how the conversion is performed, see this class objectToClass method
     * 
     * @see SerializationManager.objectToClass
     * 
     * @param string A string containing valid json data
     * @param classInstance A class instance that will be filled with all the json data (the instance is modified by this method and all values erased).
     *
     * @return The provided class instance with all its properties filled with the corresponding json values
     */
    jsonToClass<T>(string:string, classInstance:T): T{

        return this.objectToClass(JSON.parse(string), classInstance);
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
     * @param object An object containing the source data to serialize
     * @param classInstance An empty class instance that will be filled with all the values from the object
     *
     * @return The provided class instance with all its properties filled with the corresponding object values
     */
    objectToClass(object:Object, classInstance:any): any{

        let objectKeys = ObjectUtils.getKeys(object);
        let classInstanceName = (classInstance.constructor).name;
        let classInstanceKeys = ObjectUtils.getKeys(classInstance);
        
        // On strict mode, verify that both objects have the same number of keys
        if(this.strictMode && objectKeys.length !== classInstanceKeys.length){
                
            throw new Error("(strict mode): [" + objectKeys.join(',') + "] keys do not match " + classInstanceName + " props: [" + classInstanceKeys.join(',') + "]");
        }
        
        // Loop all the received object keys and store each value on the respective class property
        for(let key of objectKeys){
            
            // Check if key exists on class instance
            if(!classInstance.hasOwnProperty(key)){
                
                if(this.strictMode){
                    
                    throw new Error("(strict mode): <" + key + "> not found in " + classInstanceName);
                }
                
                continue;
            }
            
            let value = (object as any)[key];
            
            // A null key value will leave the property value untouched
            if(value === null){
                
                continue;
            }
            
            // If property has an explicit null or undefined default value, any type is allowed.
            if(classInstance[key] !== null && classInstance[key] !== undefined){
                
                let typeErrorMessage = '<' + classInstanceName + '.' + key + '> was ' + (typeof value) + ' but expected to be ';
                
                if (ArrayUtils.isArray(classInstance[key])){
                    
                    if(!ArrayUtils.isArray(value)){
                        
                        throw new Error(typeErrorMessage + 'array');                    
                    }
                       
                    if(classInstance[key].length > 0){
                        
                        if(classInstance[key].length !== 1){
                        
                            throw new Error('To define a typed list, <' + classInstanceName + '.' + key + '> must contain only 1 default typed element');
                        }
                        
                        let defaultElement = classInstance[key][0];
                        let isDefaultElementAClass = (ObjectUtils.isObject(defaultElement) && defaultElement.constructor.name !== 'Object');
                                                  
                        classInstance[key] = [];
                        
                        for(let o of value){
                            
                            if(isDefaultElementAClass){
                                
                                classInstance[key].push(this.objectToClass(o, ObjectUtils.clone(defaultElement)));
                                
                            }else{
                                
                                // Type of array elements must match the default value
                                if(typeof o !== typeof defaultElement){
                                    
                                    throw new Error('<' + classInstanceName + '.' + key + '> is defined as array of ' + (typeof defaultElement) + ' but received ' + typeof o);
                                }
                                                            
                                classInstance[key].push(o);
                            }
                        }
                        
                        continue;
                    }
                }

                if (ObjectUtils.isObject(classInstance[key])){
                    
                    if(!ObjectUtils.isObject(value)){
                        
                        throw new Error(typeErrorMessage + classInstance[key].constructor.name);
                    }
                        
                    if(classInstance[key].constructor.name !== 'Object'){
                        
                        value = this.objectToClass(value, classInstance[key]);
                    }
                }
                
                // Type of both object key and class property must match
                if(typeof classInstance[key] !== typeof value){
                    
                    throw new Error(typeErrorMessage + typeof classInstance[key]);
                }
            }
            
            classInstance[key] = value;
        }
        
        return classInstance;
    }
    
    
    /**
     * Convert a string containing the contents of a Java properties file to a JavaPropertiesObject instance
     * Note that the input string must be encoded with ISO-8859-1 and strictly follow the Java
     * properties file format (Otherwise results may not be correct).
     *
     * @param string String containing the contents of a .properties Java file
     *
     * @return The properties format parsed as an object
     */
    stringToJavaPropertiesObject(string: string){

        return new JavaPropertiesObject(string);
    }
}