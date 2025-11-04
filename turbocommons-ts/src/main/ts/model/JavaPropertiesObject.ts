/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


import { HashMapObject } from "./HashMapObject";
import { StringUtils } from '../utils/StringUtils';
import { ArrayUtils } from '../utils/ArrayUtils';
import { ValidationManager } from '../managers/ValidationManager';
import { EncodingUtils } from '../utils/EncodingUtils';
 

/**
 * Object that stores java properties file format data
 */
export class JavaPropertiesObject extends HashMapObject {


    /**
     * Create a JavaPropertiesObject instance. Java properties is a text file format that stores data
     * into text files with information that is arranged as key/value pairs.
     * For example: tag1=value1
     *
     * @param string String containing the contents of a .properties Java file.
     * Note that string must be encoded with ISO-8859-1 and strictly follow the Java
     * properties file format (Otherwise results won't be correct).
     *
     * @see HashMapObject
     * @return The java properties object with data accessible as key/value pairs.
     */
    constructor(string = ''){
        
        super();

        if(!StringUtils.isString(string)){

            throw new Error('value must be a string');
        }

        if(string === ''){

            return;
        }

        // Validate received string
        if(string.length < 2 || string.substr(0, 1) === '=' || string.indexOf('=') < 0 && string.indexOf(':') < 0){

            throw new Error('invalid properties format');
        }

        let key = '';
        let value = '';
        let isWaitingOtherLine = false;

        // Generate an array with the properties lines, ignoring blank lines and comments
        let lines = StringUtils.getLines(string, [/\s+/g, / *#.*| *!.*/g]);

        for (let line of lines) {

            // Remove all blank spaces at the beginning of the line
            line = StringUtils.trimLeft(line);

            if(isWaitingOtherLine) {

                value += EncodingUtils.unicodeEscapedCharsToUtf8(line);

            }else{

                // Find the key/value divider index
                let tmpLine = StringUtils.replace(line, ['\\=', '\\:'], 'xx');
                let keyDividerIndex = Math.min((tmpLine + '=').indexOf('='), (tmpLine + ':').indexOf(':'));

                // Extract the key from the line
                key = line.substring(0, keyDividerIndex).trim();

                // Add a space to the end if the last character is a \
                if(key.substr(key.length - 1, 1) === '\\'){

                    key += ' ';
                }
                
                key = StringUtils.replace(key, ['\\\\', '\\ ', '\\#', '\\!', '\\=', '\\:'], ['\\', ' ', '#', '!', '=', ':']);
                
                // Extract the value from the line
                value = StringUtils.trimLeft(line.substring(keyDividerIndex + 1, line.length));
            }

            // Unescape escaped slashes and spaces on the value
            value = StringUtils.replace(value, ['\\\\', '\\ ', '\\r\\n', '\\n', '\\t'], ['\\u005C', ' ', "\r\n", "\n", "\t"]);
            
            // Check if ends with single '\'
            if(value.substr(value.length - 1) == '\\'){

                // Remove trailing backslash
                value = value.substring(0, value.length - 1);

                isWaitingOtherLine = true;

            }else{

                isWaitingOtherLine = false;

                // Decode unicode characters
                value = EncodingUtils.unicodeEscapedCharsToUtf8(value);
            }

            if(!this._data.hasOwnProperty(key)){
                
                this._keys.push(key);
            }

            this._data[key] = value;
        }

        this._length =  this._keys.length;
    }


    /**
     * Tells if the given value contains valid Java Properties data information or not
     *
     * @param value A value to check (a string or a JavaPropertiesObject instance)
     *
     * @return true if the given value contains valid Java Properties data, false otherwise
     */
    static isJavaProperties(value: any){

        // test that received string contains valid properties info
        try {

            let p = new JavaPropertiesObject(value);

            return p.length() >= 0;

        } catch (e) {

            try {

                return (value != null) && (value instanceof JavaPropertiesObject);

            } catch (e) {

                return false;
            }
        }
    }


    /**
     * Check if the provided java properties is identical to this instance
     * Only data is compared: Any comment that is found on both provided properties will be ignored.
     *
     * @param properties java properties value to compare (a string or a JavaPropertiesObject instance)
     * @param strictOrder If set to true, both properties elements must have the same keys with the same order. Otherwise differences in key sorting will be accepted
     *
     * @return true if both java properties data is exactly the same, false if not
     */
    isEqualTo(properties: any, strictOrder = false){

        let objectToCompare:JavaPropertiesObject|null = null;

        try {

            objectToCompare = new JavaPropertiesObject(properties);

        } catch (e) {

            try {
                
                if(properties instanceof JavaPropertiesObject){

                    objectToCompare = properties;
                }

            } catch (e) {

                // Nothing to do
            }
        }

        if(objectToCompare == null){

            throw new Error('properties does not contain valid java properties data');
        }

        let thisKeys = this.getKeys();
        let keysToCompare = objectToCompare.getKeys();

        if(thisKeys.length != keysToCompare.length || (strictOrder && !ArrayUtils.isEqualTo(thisKeys, keysToCompare))){

            return false;
        }

        let validationManager = new ValidationManager();

        for (let key of thisKeys) {

            if(!strictOrder && !objectToCompare.isKey(key)){

                return false;
            }

            if(!validationManager.isEqualTo(this.get(key), objectToCompare.get(key))){

                return false;
            }
        }

        return true;
    }


    /**
     * Generate the textual representation for the java properties data stored on this object.
     * The output of this method is ready to be stored on a physical .properties file.
     *
     * @return A valid Java .properties string ready to be stored on a .properties file
     */
    toString(){

        let result = [];
        let keys = this.getKeys();
        let keysCount = keys.length;

        for (let i = 0; i < keysCount; i++) {
            
            let key = StringUtils.replace(keys[i], ['\\', ' ', '#', '!', '=', ':'], ['\\\\', '\\ ', '\\#', '\\!', '\\=', '\\:']);
            
            let value = StringUtils.replace(this.get(keys[i]), ['\\', ' ', "\r\n", "\n", "\t"], ['\\\\', '\\ ', '\\r\\n', '\\n', '\\t']);
            
            result.push(key + '=' + EncodingUtils.utf8ToUnicodeEscapedChars(value));
        }

        return result.join("\r\n");
    }
}