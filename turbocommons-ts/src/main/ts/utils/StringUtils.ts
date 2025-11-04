/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */
 

import { NumericUtils } from './NumericUtils';
import { ArrayUtils } from './ArrayUtils';
import { ObjectUtils } from './ObjectUtils';

    
/**
 * The most common string processing and modification utilities
 */  
export class StringUtils {
    
    
    /** 
     * Defines the sentence case format (Only the first character of the sentence is capitalised,except for
     * proper nouns and other words which are required by a more specific rule to be capitalised).
     * Generally equivalent to the baseline universal standard of formal English orthography
     */
    static readonly FORMAT_SENTENCE_CASE = 'FORMAT_SENTENCE_CASE';


    /** 
     * Defines the start case format (The first character in all words capitalised and all the rest
     * of the word lower case). It is also called Title Case
     */
    static readonly FORMAT_START_CASE = 'FORMAT_START_CASE';


    /** 
     * Defines the all upper case format (All letters on a string written with Capital letters only)
     */
    static readonly FORMAT_ALL_UPPER_CASE = 'FORMAT_ALL_UPPER_CASE';


    /**
     * Defines the all lower case format (All letters on a string written with lower case letters only)
     */
    static readonly FORMAT_ALL_LOWER_CASE = 'FORMAT_ALL_LOWER_CASE';

    
    /**
     * Defines the first upper rest lower case format (All letters on a string written
     * with lower case letters except the first one which is Capitalized)
     */
    static readonly FORMAT_FIRST_UPPER_REST_LOWER = 'FORMAT_FIRST_UPPER_REST_LOWER';


    /** 
     * Defines the CamelCase format (the practice of writing compound words or phrases such that each
     * word or abbreviation begins with a capital letter)
     */
    static readonly FORMAT_CAMEL_CASE = 'FORMAT_CAMEL_CASE';


    /**
     * Defines the UpperCamelCase format variation that writes first letter as upper case
     *
     * @see StringUtils.FORMAT_CAMEL_CASE
     */
    static readonly FORMAT_UPPER_CAMEL_CASE = 'FORMAT_UPPER_CAMEL_CASE';


    /**
     * Defines the lowerCamelCase format variation that writes first letter as lower case
     *
     * @see StringUtils.FORMAT_CAMEL_CASE
     */
    static readonly FORMAT_LOWER_CAMEL_CASE = 'FORMAT_LOWER_CAMEL_CASE';


    /** 
     * Defines the snake_case format (the practice of writing compound words or phrases in which
     * the elements are separated with one underscore character (_) and no spaces)
     */
    static readonly FORMAT_SNAKE_CASE = 'FORMAT_SNAKE_CASE';


    /**
     * Defines the FORMAT_UPPER_SNAKE_CASE format variation that writes all letters as upper case
     *
     * @see StringUtils.FORMAT_SNAKE_CASE
     */
    static readonly FORMAT_UPPER_SNAKE_CASE = 'FORMAT_UPPER_SNAKE_CASE';


    /**
     * Defines the lower_snake_case format variation that writes all letters as lower case
     *
     * @see StringUtils.FORMAT_SNAKE_CASE
     */
    static readonly FORMAT_LOWER_SNAKE_CASE = 'FORMAT_LOWER_SNAKE_CASE';
    
    
    /**
     * Tells if the given value is a string or not
     *
     * @param value A value to check
     *
     * @returns true if the given value is a string, false otherwise
     */
    public static isString(value:any):boolean {
        
        return (typeof value === 'string' || value instanceof String);
    }
    
    
    /**
     * Strictly check that the provided value is a string or throw an exception
     *
     * @param value A value to check
     * @param valueName The name of the value to be shown at the beginning of the exception message
     * @param errorMessage The rest of the exception message
     *
     * @throws Error If the check fails
     *
     * @return void
     */
    public static forceString(value:any, valueName = '', errorMessage = 'must be a string'){

        if(!StringUtils.isString(value)){

            throw new Error(valueName + ' ' + errorMessage);
        }
    }
    
    
    /**
     * Tells if the given string is a valid url or not
     *
     * @param value The value to check
     *
     * @return False in case the validation fails or true if validation succeeds.
     */
    public static isUrl(value:any) {
        
        var res = false;
    
        if(!StringUtils.isEmpty(value) && StringUtils.isString(value)){
    
            // This amazingly good solution's been found at http://stackoverflow.com/questions/3809401/what-is-a-good-regular-expression-to-match-a-url
            var urlRegex = '^(?!mailto:)(?:(?:http|https|ftp)://)(?:\\S+(?::\\S*)?@)?(?:(?:(?:[1-9]\\d?|1\\d\\d|2[01]\\d|22[0-3])(?:\\.(?:1?\\d{1,2}|2[0-4]\\d|25[0-5])){2}(?:\\.(?:[0-9]\\d?|1\\d\\d|2[0-4]\\d|25[0-4]))|(?:(?:[a-z\\u00a1-\\uffff0-9]+-?)*[a-z\\u00a1-\\uffff0-9]+)(?:\\.(?:[a-z\\u00a1-\\uffff0-9]+-?)*[a-z\\u00a1-\\uffff0-9]+)*(?:\\.(?:[a-z\\u00a1-\\uffff]{2,})))|localhost)(?::\\d{2,5})?(?:(/|\\?|#)[^\\s]*)?$';
    
            res = !(value.length < 2083 && (new RegExp(urlRegex, 'i')).test(value)) ? false : true;
        }
    
        return res;
    }
    
    
    /**
     * Tells if a specified string is semantically empty, which applies to any string that is comprised of empty spaces, new line characters, tabulations or any other
     * characters without a visually semantic value to the user.
     *
     * Example1: Following strings are considered as empty: "     ", "", "    \n\n\n", "    \t\t\n"
     * Example2: Following strings are not considered as empty: "hello", "   a", "    \n\nB"
     *
     * @param string The text to check
     * @param emptyChars Custom list of strings that will be also considered as empty characters. For example, we can define 'NULL' and '_' as empty string values by setting this to ['NULL', '_']
     *
     * @return false if the string is not empty, true if the string contains non semantically valuable characters or any other characters defined as "empty" values
     */
    public static isEmpty(string:string, emptyChars:string[] = []) {
    
        // Throw exception if non string value was received
        if(!StringUtils.isString(string)){

            // Empty or null value is considered empty
            if(string == null || string == ''){
    
                return true;
            }
            
            throw new Error("value is not a string");
        }

        return  StringUtils.replace(string, emptyChars.concat([' ', "\n", "\r", "\t"]), '') === '';
    }
    
    
    /**
     * Checks if the given string starts with any of the specified values.
     *
     * @param str The string to check
     * @param startingValues An array of strings that the input string should start with.
     *
     * @return True if the input string starts with any of the specified values, False otherwise.
     */
    public static isStartingWith(str:string, startingValues:string[] = []){
        
        if(!ArrayUtils.isArray(startingValues)){
            
            throw new Error("startingValues is not an array");
        }
        
        for (let startingValue of startingValues) {
            
            if (str.lastIndexOf(startingValue, 0) === 0) {
                
                return true;
            }
        }
        
        return false;
    }

    
    /**
     * Checks if a given string ends with any of the specified values.
     *
     * @param str The string to check
     * @param endingValues An array of strings that the input string should end with.
     *
     * @return True if the input string ends with any of the specified values, False otherwise.
     */
    public static isEndingWith(str:string, endingValues:string[] = []){
        
        if(!ArrayUtils.isArray(endingValues)){
            
            throw new Error("endingValues is not an array");
        }
        
        for (let endingValue of endingValues) {
            
            if (endingValue === '' || str.indexOf(endingValue, str.length - endingValue.length) !== -1) {
                
                return true;
            }
        }
        
        return false;
    }
    
    
    /**
     * Strictly check that the provided value is a non empty string or throw an exception
     *
     * Uses the same criteria as the StringUtils.isEmpty() method
     *
     * @param value A value to check
     * @param valueName The name of the value to be shown at the beginning of the exception message
     * @param errorMessage The rest of the exception message
     *
     * @throws Error If the check fails
     *
     * @return void
     */
    public static forceNonEmptyString(value:any, valueName = '', errorMessage = 'must be a non empty string'){

        if(!StringUtils.isString(value) || StringUtils.isEmpty(value)){

            throw new Error(valueName + ' ' + errorMessage);
        }
    }
    
    
    public static isCamelCase() {
        
        // TODO - translate from php
    }
    
    
    public static isSnakeCase() {
        
        // TODO - translate from php
    }
    
    
    /**
     * Replace all occurrences of the search string with the replacement string
     *  
     * @param string The string or array being searched and replaced on, otherwise known as the haystack.
     * @param search The value being searched for, otherwise known as the needle. An array may be used to designate multiple needles
     *        (if we use an array, the order of replacement will be the same of the array: First element will be the first one to be replaced,
     *         second element the second, etc..) 
     * @param replacement The value being searched for, otherwise known as the needle. An array may be used to designate multiple needles. 
     * @param count [optional] If passed and > 0, this will define the maximum number of replacements to perform
     * 
     * @returns The string with all the replaced values
     */
    public static replace(string: string, search: string|string[], replacement: string|string[], count: number = -1) {
        
        if(!StringUtils.isString(string)){
            
            throw new Error("string is not valid");
        }
        
        if(!StringUtils.isString(search) && !ArrayUtils.isArray(search)){
            
            throw new Error("search is not a string or array");
        }
        
        if(!StringUtils.isString(replacement) && !ArrayUtils.isArray(replacement)){
            
            throw new Error("replacement is not a string or array");
        }
        
        if(!NumericUtils.isInteger(count)){
            
            throw new Error("count must be a positive integer or 0");
        }
        
        if(count === 0){
            
            return string;
        }
        
        let result = string;
        let searchArray = StringUtils.isString(search) ? [String(search)] : search;
        let replacementArray = StringUtils.isString(replacement) ? [String(replacement)] : replacement;
        
        if(replacementArray.length > 1 && searchArray.length !== replacementArray.length){
            
            throw new Error("search and replacement arrays must have the same length");
        }
        
        for (let i = 0; i < searchArray.length; i++) {
            
            if(searchArray[i] !== ''){
                
                let valueToReplace = (replacementArray.length === 1) ? replacementArray[0] : replacementArray[i];
                
                if(valueToReplace === undefined || valueToReplace === null){
                    
                    valueToReplace = '';
                }
                
                let resultArray:string[] = [];
                
                let splittedArray = result.split(searchArray[i]);
                
                for (let j = 0; j < splittedArray.length; j++) {
                    
                    resultArray.push(splittedArray[j]);
                    
                    if(j < splittedArray.length - 1){
                    
                        if(count < 0 || j < count){
                            
                            resultArray.push(valueToReplace);
                        
                        } else {
                            
                            resultArray.push(searchArray[i]);
                        }
                    }
                }
                
                result = resultArray.join('');
            }
        }
        
        return result;
    }
    
    
    /**
     * This metod performs the same string replacement that replace() does, but instead of searching on a single string it will search on all the strings inside
     * a given array or object.
     *
     * The search is totally recursive and will be performed inside any arrays, objects, and combination of any of them. Any value that is not a string which is
     * found inside the provided structure will be ignored
     *
     * Method is non destructive: The provided structure is not altered, a copy is given
     *
     * @see StringUtils.replace
     *
     * @param string see StringUtils.replace
     * @param search see StringUtils.replace
     * @param replacement see StringUtils.replace
     * @param count see StringUtils.replace
     * 
     * @returns A copy of the provided object or array with all the values replaced on all its strings
     */
    public static replaceMulti(object:any, search:string|string[], replacement:string|string[], count:number = -1) {
    
        if (StringUtils.isString(object)){
            
            return StringUtils.replace(object, search, replacement, count);
        }
        
        let clone = ObjectUtils.clone(object);
        
        if(ArrayUtils.isArray(clone)){
             
            for (let i = 0; i < clone.length; i++){
                
                clone[i] = StringUtils.replaceMulti(clone[i], search, replacement, count);               
            }
        
        }else if(ObjectUtils.isObject(clone)){
            
            for (let key of ObjectUtils.getKeys(clone)){
                
                clone[key] = StringUtils.replaceMulti(clone[key], search, replacement, count);
            }
        }
        
        return clone;
    }

    
    /**
     * Remove whitespaces (or any custom set of characters) from both sides of a string
     * 
     * @param string A string to process
     * @param characters A set of characters that will be trimmed from both string sides. By default,
     * empty space and new line characters are defined : " \n\r"
     * 
     * @example: StringUtils.trim("abcXXabc", "abc") outputs "XX"
     * 
     * @returns The trimmed string
     */
    public static trim(string: string, characters = " \n\r") {
        
        return StringUtils.trimLeft(StringUtils.trimRight(string, characters), characters);
    }
    
    
    /**
     * Remove whitespaces (or any custom set of characters) from a string left side
     * 
     * @param string A string to process
     * @param characters A set of characters that will be trimmed from string left side. By default,
     *        empty space and new line characters are defined : " \n\r"
     * 
     * @example: StringUtils.trimLeft("abcXXabc", "abc") outputs "XXabc"
     *  
     * @returns The trimmed string
     */
    public static trimLeft(string: string, characters = " \n\r") {
    
        if(!StringUtils.isString(string)){
            
            throw new Error('value is not a string');
        }

        return string.replace(new RegExp("^[" + characters + "]+"), "");
    }
    
    
    /**
     * Remove whitespaces (or any custom set of characters) from a string right side
     * 
     * @param string A string to process
     * @param characters A set of characters that will be trimmed from string right side. By default,
     * empty space and new line characters are defined : " \n\r"
     *
     * @example: StringUtils.trimRight("abcXXabc", "abc") outputs "abcXX"
     * 
     * @returns The trimmed string
     */
    public static trimRight(string: string, characters = " \n\r") {
        
        if(!StringUtils.isString(string)){
            
            throw new Error('value is not a string');
        }

        return string.replace(new RegExp("[" + characters + "]+$"), "");
    }
    
    
    /**
     * Pad a string to a certain length with another string
     *
     * @param string The string to which we want to fill the empty spaces
     * @param padLength The minimum length that we want for the resulting string to have
     * @param padString The character or characters which we want to add to the string to match the target length
     * @param mode LEFT to append the padString to the left of the string, RIGHT to append the padString to the right of the string
     *
     * @returns The padded string
     */
    public static pad(string: string, padLength: number, padString = '0', mode = 'LEFT') {
        
        if(!StringUtils.isString(string) || !StringUtils.isString(padString) || padString.length <= 0){
            
            throw new Error('string and padString must be strings');
        }
        
        if(!NumericUtils.isInteger(padLength)){
            
            throw new Error('padLength is not an int');
        }
        
        if(mode !== 'LEFT' && mode !== 'RIGHT'){
            
            throw new Error('mode must be LEFT or RIGHT');
        }
        
        let result = string;
        
        if(mode === 'RIGHT'){
            
            while (result.length < padLength) {
                
                result = result + padString.substr(0, padLength - result.length);
            }
            
        }else{
            
            while (result.length < padLength) {
                
                result = padString.substr(-(padLength - result.length)) + result;
            }
        }
                
        return result;
    }
    
    
    /**
     * Count the number of times a string is found inside another string
     *
     * @param string The string where we want to search
     * @param findMe The string that we want to look for
     *
     * @returns The number of times that findMe appears on string
     */
    public static countStringOccurences(string: string, findMe: string) {
        
        if(!StringUtils.isString(string) || !StringUtils.isString(findMe)){
            
            throw new Error('value is not a string');
        }
        
        if(findMe === ''){
            
            throw new Error('cannot count empty string occurences');
        }

        return string.split(findMe).length - 1;
    }
    
    
    /**
     * Count the number of characters that match the given letter case on the given string
     *
     * @param string The string which case matching characters will be counted
     * @param letterCase Defines which letter case are we looking for: StringUtils.FORMAT_ALL_UPPER_CASE or
     *        StringUtils.FORMAT_ALL_LOWER_CASE
     *
     * @return The number of characters with the specified letter case that are present on the string
     */
    public static countByCase(string: string, letterCase = StringUtils.FORMAT_ALL_UPPER_CASE){

        string = StringUtils.removeAccents(string);

        if(letterCase === StringUtils.FORMAT_ALL_UPPER_CASE){

            return string.replace(/[^A-Z]+/g, '').length;
        }

        if(letterCase === StringUtils.FORMAT_ALL_LOWER_CASE){

            return string.replace(/[^a-z]+/g, '').length;
        }

        throw new Error('invalid case value');
    }
    
    
    /**
     * Count the number of words that exist on the given string
     *
     * @param string The string which words will be counted
     * @param wordSeparator ' ' by default. The character that is considered as the word sepparator
     *
     * @returns The number of words (elements divided by the wordSeparator value) that are present on the string
     */
    public static countWords(string:string, wordSeparator:string = ' ') {
        
        var count:number = 0;
        var lines:string[] = StringUtils.getLines(string);

        for(var i = 0; i < lines.length; i++){

            var words:string[] = lines[i].split(wordSeparator);

            for(var j = 0; j < words.length; j++){

                if(!StringUtils.isEmpty(words[j])){

                    count++;
                }
            }
        }

        return count;
    }
    
    
    /**
     * Given a string with a list of elements separated by '/' or '\' that represent some arbitrary path structure,
     * this method will return the number of elements that are listed on the path.
     *
     * @example "c:\\" -> results in 1
     *          "//folder/folder2/folder3/file.txt" -> results in 4
     *
     * @param path A string containing some arbitrary path.
     *
     * @return The number of elements that are listed on the provided path
     */
    public static countPathElements(path: string){

        path = StringUtils.formatPath(path, '/');

        path = (path.indexOf('/') === 0) ? path.substr(1) : path;

        return path == '' ? 0 : path.split('/').length;
    }
    
    
    /**
     * Method that limits the length of a string and optionally appends informative characters like ' ...'
     * to inform that the original string was longer.
     * 
     * @param string String to limit
     * @param limit Max number of characters
     * @param limiterString If the specified text exceeds the specified limit, the value of this parameter will be added to the end of the result. The value is ' ...' by default.
     *
     * @returns The specified string but limited in length if necessary. Final result will never exceed the specified limit, also with the limiterString appended.
     */
    public static limitLen(string:string, limit:number = 100, limiterString:string = ' ...') {
        
        if(limit <= 0 || !NumericUtils.isNumeric(limit)){

            throw new Error('limit must be a positive numeric value');
        }

        if(!StringUtils.isString(string)){

            return '';
        }

        if(string.length <= limit){

            return string;
        }

        if(limiterString.length > limit){

            return limiterString.substring(0, limit);

        }else{

            return string.substring(0, limit - limiterString.length) + limiterString;
        }
    }
            
            
    /**
     * Extracts the domain name from a given url (excluding subdomain). 
     * For example: http://subdomain.google.com/test/ will result in 'google.com'
     * 
     * @param url A string containing an URL
     * 
     * @returns The domain from the given string (excluding the subdomain if exists)
     */
    public static getDomainFromUrl(url:string) {
        
        var hostName:any = StringUtils.getHostNameFromUrl(url);

        hostName = hostName.split('.');

        if(hostName.length > 2){

            hostName.shift();
        }

        return hostName.join('.');
    }
    
    
    /**
     * Extracts the hostname (domain + subdomain) from a given url.
     * For example: http://subdomain.google.com/test/ will result in 'subdomain.google.com'
     * 
     * @param url A string containing an URL
     * 
     * @returns The domain and subdomain from the given string (subdomain.domain.com)
     */
    public static getHostNameFromUrl(url:string) {
        
        if(StringUtils.isEmpty(url) || !StringUtils.isUrl(url)){

            return '';
        }

        // TODO - This should be improved by avoiding the use of an anchor element,
        // cause it will only work on browsers and Explorer / Edge generate wrong results
        var tmp:HTMLAnchorElement = document.createElement('a');

        tmp.href = url;

        // Validate domain contains a valid number of dots
        var dotsCount:number = (tmp.host.match(/\./g) || []).length;

        if(dotsCount <= 0 || dotsCount > 2){

            return '';
        }

        return tmp.host;
    }
    
    
    /**
     * Extracts all the lines from the given string and outputs an array with each line as an element.
     * It does not matter which line separator's been used (\n, \r, Windows, linux...). All source lines will be correctly extracted.
     * 
     * @param string Text containing one or more lines that will be converted to an array with each line on a different element.
     * @param filters One or more regular expressions that will be used to filter unwanted lines. Lines that match any of the
     *  filters will be excluded from the result. By default, all empty lines are ignored (those containing only newline, blank, tabulators, etc..).
     *
     * @returns A list with all the string lines sepparated as different array elements.
     */
    public static getLines(string:string, filters:RegExp[] = [/\s+/g]) {
    
        var res:string[] = [];

        // Validate we are receiving a string
        if(!StringUtils.isString(string)){

            return res;
        }

        var tmp:string[] = string.split(/\r?\n|\n|\r/);

        for(var i = 0; i < tmp.length; i++){

            // Apply specified filters
            if(StringUtils.isString(tmp[i])){

                let replacedFilters = tmp[i];
                
                for (var j = 0; j < filters.length; j++) {

                    replacedFilters = replacedFilters.replace(filters[j], '');
                }
                
                if(replacedFilters != ''){

                    res.push(tmp[i]);
                }
            }
        }

        return res;
    }
    
            
    public static getKeyWords() {
    
        // TODO: translate from php
    }
    
    
    /**
     * Given a string with a list of elements separated by '/' or '\' that represent some arbitrary path structure,
     * this method will format the specified path and remove the number of requested path elements (from its right
     * side) and return the path without that elements.
     *
     * This method can be used with Operating system file paths, urls, or any other string that uses the 'slash separated'
     * format to encode a path.
     *
     * @example "//folder/folder2/folder3/file.txt" -> results in "/folder/folder2/folder3" if elementsToRemove = 1<br>
     *          "//folder/folder2\folder3\file.txt" -> results in "/folder/folder2" if elementsToRemove = 2
     * 
     * @see StringUtils.formatPath
     * 
     * @param path A string containing some arbitrary path.
     * @param elementsToRemove (one by default) The number of elements that we want to remove from the right side of the path.
     * @param separator The character to use as the element divider for the returned path. Only slash '/' or backslash '\' are allowed.
     *
     * @return The received path without the specified number of elements and correctly formatted
     */
    public static getPath(path:string, elementsToRemove = 1, separator = '/'){
        
        if(StringUtils.isEmpty(path)){

            return '';
        }

        path = StringUtils.formatPath(path, '/');

        if(path === '/'){

            return path;
        }
        
        let processedPath = (path.indexOf('/') === 0) ? path.substring(1) : path;

        let elements = processedPath.split('/');

        if(elementsToRemove > elements.length || elementsToRemove < -1){

            return '';
        }
        
        let arrayToRemove:string[] = [];
        
        for (let i = elements.length - elementsToRemove; i < elements.length; i++) {

            arrayToRemove.push(elements[i]);
        }
        
        if(arrayToRemove.length <= 0){
         
            return path;
        }
        
        return StringUtils.formatPath(path.substring(0, path.length - arrayToRemove.join('/').length - 1), separator);
    }
    
    
    /**
     * Given a string with a list of elements separated by '/' or '\' that represent some arbitrary path structure,
     * this method will return the element that is located at the requested position. If no position is defined,
     * by default the last element of the path will be returned (the most to the right one).
     *
     * This method can be used with Operating system file paths, urls, or any other string that uses the 'slash separated'
     * format to encode a path.
     *
     * @example "//folder/folder2/folder3/file.txt" -> results in "file.txt" if (-1) position is defined<br>
     *          "//folder/folder2\folder3\file.txt" -> results in "folder" if position 0 is defined<br>
     *          "//folder/folder2\folder3\file.txt" -> results in "folder3" if position 2 is defined<br>
     *          "//folder/folder2\folder3\file.txt" -> results in "folder3" if position -2 is defined<br>
     *          "//folder/folder2\folder3\file.txt" -> results in "folder2" if position -3 is defined
     *
     * @param path A string containing some arbitrary path.
     * @param position The index for the element that we want to extract from the path. Positive values will get path elements
     *        starting from the left side, being 0 the first most to the left one. Negative values will get path elements starting from
     *        the right side, being -1 the last path element (or the first most to the right one).
     *        If not specified, the last one will be returned.
     *
     * @return The element at the specified path position or the last one if no position is defined
     */
    public static getPathElement(path:string, position = -1){
    
        if(StringUtils.isEmpty(path)){

            return '';
        }

        path = StringUtils.formatPath(path, '/');

        path = (path.indexOf('/') === 0) ? path.substring(1) : path;

        let elements = path.split('/');

        if(position >= elements.length || position < -elements.length){

            throw new Error('Invalid position specified');
        }
        
        return position < 0 ? elements[elements.length + position] : elements[position];
    }
    
    
    /**
     * This method works in the same way as getPathElement but it also removes the extension part from the result
     * if it has any.
     *
     * @example "//folder/folder2/folder3/file.txt" -> results in "file" if position = -1. Notice that ".txt" extension is removed<br>
     *          "//folder/folder2\folder3\file.txt" -> results in "folder3" if position = 2. "folder3" has no extension so it does not get modified.
     *
     * @see StringUtils.getPathElement
     *
     * @param path A string containing some arbitrary path.
     * @param position The index for the element that we want to extract from the path. If not specified, the
     *                 last one will be returned.
     * @param extensionSeparator The character to be used as the extension separator. The most commonly used is '.'
     *
     * @return The element at the specified path position with it's extension removed or the last one if no position is defined
     */
    public static getPathElementWithoutExt(path:string, position = -1, extensionSeparator = '.') {
    
        let element = StringUtils.getPathElement(path, position);

        if(element.indexOf(extensionSeparator) >= 0){

            element = element.substr(0, element.lastIndexOf(extensionSeparator));
        }

        return element;
    }
    
    
    /**
     * This method works in the same way as getPathElement but it only gives the element extension if it has any.
     *
     * @example "//folder/folder2/folder3/file.txt" -> results in "txt" if position = -1. Notice that extension without separator character is returned<br>
     *          "//folder/folder2\folder3\file.txt" -> results in "folder3" if position = 2. "folder3" has no extension so it does not get modified.
     *
     * @see StringUtils.getPathElement
     *
     * @param path A string containing some arbitrary path.
     * @param position The index for the element extension that we want to extract from the path. If not specified, the
     *                 last one will be returned.
     * @param extensionSeparator The character to be used as the extension separator. The most commonly used is '.'
     *
     * @return The extension from the element at the specified path position or the extension from the last one if no position is defined
     */
    public static getPathExtension(path:string, position = -1, extensionSeparator = '.') {
    
        let element = StringUtils.getPathElement(path, position);

        if(element.indexOf(extensionSeparator) < 0){

            return '';
        }

        // Find the extension by getting the last position of the dot character
        return element.substr(element.lastIndexOf(extensionSeparator) + 1);
    }
    
    
    /**
     * Given an internet URL, this method extracts only the scheme part.
     * Example: "http://google.com" -> results in "http"
     * 
     * @see StringUtils.formatUrl
     * 
     * @param url A valid internet url
     *
     * @returns ('ftp', 'http', ...) if the url is valid or '' if the url is invalid
     */
    public static getSchemeFromUrl(url:string) {
    
        if(url == null || url == undefined){

            return '';
        }

        if(!StringUtils.isString(url)){

            throw new Error("Specified value must be a string");
        }

        if(!StringUtils.isUrl(url)){

            return '';
        }

        let res:string[] = url.split('://');

        return (res.length === 2) ? res[0] : '';
    }
    
    
    /**
     * Changes the letter case for the given string to the specified format.
     *
     * @param string A string that will be processed to match the specified case format.
     * @param format The format to which the given string will be converted. Possible values are defined as
     * StringUtils constants that start with <b>FORMAT_</b>, like: StringUtils.FORMAT_ALL_UPPER_CASE
     *
     * @see StringUtils.FORMAT_SENTENCE_CASE
     * @see StringUtils.FORMAT_START_CASE
     * @see StringUtils.FORMAT_ALL_UPPER_CASE
     * @see StringUtils.FORMAT_ALL_LOWER_CASE
     * @see StringUtils.FORMAT_FIRST_UPPER_REST_LOWER
     * @see StringUtils.FORMAT_CAMEL_CASE
     * @see StringUtils.FORMAT_UPPER_CAMEL_CASE
     * @see StringUtils.FORMAT_LOWER_CAMEL_CASE
     * @see StringUtils.FORMAT_SNAKE_CASE
     * @see StringUtils.FORMAT_UPPER_SNAKE_CASE
     * @see StringUtils.FORMAT_LOWER_SNAKE_CASE
     *
     * @returns The given string converted to the specified case format.
     */
    public static formatCase(string: string, format: string) {

        // Non string values will throw an exception
        if(!StringUtils.isString(string)){

            throw new Error('value is not a string');
        }

        // Empty values will return the string itself
        if(StringUtils.isEmpty(string)){

            return string;
        }
        
        // Generate the sentence case output
        // TODO - translate from PHP
     
        // Generate the title case output
        if(format === StringUtils.FORMAT_START_CASE){

            return string.split(' ').map((word:any) => word ? word[0].toUpperCase() + word.slice(1).toLowerCase() : '').join(' ');
        }

        // Generate the all upper case output
        if(format === StringUtils.FORMAT_ALL_UPPER_CASE){

            return string.toUpperCase();
        }
        
        // Generate the all lower case output
        if(format === StringUtils.FORMAT_ALL_LOWER_CASE){

            return string.toLowerCase();
        }
        
        // Generate the first upper rest lower case output
        if(format === StringUtils.FORMAT_FIRST_UPPER_REST_LOWER){

            return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
        }
        
        // Generate the snake case format
        // TODO - translate from PHP
        
        // Generate the camel case format
        if(format.indexOf('CAMEL_CASE') > 0){

            // non-alpha and non-numeric characters become spaces and the whole string is splitted to words
            let stringWords = StringUtils.removeAccents(string).replace(/[^a-z0-9]+/ig, ' ').trim().split(' ');
            
            // uppercase the first character of each word except the first one
            if(stringWords.length > 1){

                for(let i=1; i<stringWords.length; i++){

                    stringWords[i] = stringWords[i].charAt(0).toUpperCase() + stringWords[i].slice(1);
                }
            }

            string = stringWords.join('');

            if(format == StringUtils.FORMAT_UPPER_CAMEL_CASE){

                return string.charAt(0).toUpperCase() + string.slice(1);
            }

            if(format == StringUtils.FORMAT_LOWER_CAMEL_CASE){

                return string.charAt(0).toLowerCase() + string.slice(1);
            }

            return string;
        }
        
        throw new Error('Unknown format specified');
    }
    
    
    /**
     * Given a string with a list of elements separated by '/' or '\' that represent some kind of unformatted path,
     * this method will process it to get a standarized one by applying the following rules:
     *
     * - Duplicate separator characters will be removed: "a\\\b\\c" will become "a/b/c"
     * - All separator characters will be unified to the same one: "a\b/c\d" will become "a/b/c/d"
     * - No trailing separator will exist: "a\b\c\" will become "a\b\c"
     *
     * NOTE: This method only applies format to the received string. It does not check if the path is a real
     *       location or a valid url, and won't also fail if the received path contains strange characters or is invalid.
     *
     * @param path A raw path to be formatted
     * @param separator The character to use as the element divider. Only slash '/' or backslash '\' are allowed.
     *
     * @return The correctly formatted path without any trailing separator
     */
    public static formatPath(path:any, separator = '/'):string {
    
        if(!StringUtils.isString(path)){

            throw new Error('path must be a string');
        }

        if(separator !== '/' && separator !== '\\'){

            throw new Error('separator must be a slash or backslash');
        }
        
        // Standarize all the separator characters
        path = path.replace(/\//g, separator);
        path = path.replace(/\\/g, separator);

        // Remove duplicate path separator characters
        while(path.indexOf(separator + separator) >= 0) {

            path = path.replace(separator + separator, separator);
        }

        // Remove the last separator only if it exists and is not the only character of the path
        if(path.length > 1 && path.substr(path.length - 1) === separator){

            path = path.substr(0, path.length - 1);
        }     

        return path;
    }
    
    
    /**
     * Given a raw string containing an internet URL, this method will process it to obtain a URL that is 100% format valid.
     * 
     * A Uniform Resource Locator (URL), commonly informally termed a web address is a reference to a web resource that specifies 
     * its location on a computer network and a mechanism for retrieving it. URLs occur most commonly to reference web pages (http), 
     * but are also used for file transfer (ftp), email (mailto), database access (JDBC), and many other applications.
     * 
     * Every HTTP URL conforms to the syntax of a generic URI. A generic URI is of the form: scheme:[//[user:password@]host[:port]][/]path[?query][#fragment]
     * 
     * @see https://en.wikipedia.org/wiki/Uniform_Resource_Locator#Syntax
     * 
     * @returns The formated url string or the original string if it was not a valid url
     */
    public static formatUrl(url:string) {
        
        let urlSeparator:string = '/';
        
        if(url == ''){

            return '';
        }

        if(!StringUtils.isString(url)){

            throw new Error("url must be a string");
        }

        if(StringUtils.isEmpty(url)){

            return url;
        }

        // Trim and replace all slashes on the url with the correct url slash
        url = url.trim();
        url = url.replace(/\//g, urlSeparator);
        url = url.replace(/\\/g, urlSeparator);
        
        // Remove duplicate path separator characters. We replace :// with @@
        // to prevent the first two // from being replaced with a single one
        url = url.replace(/\:\/\//g, '@@@');
        
        while(url.indexOf(urlSeparator + urlSeparator) >= 0) {

            url = url.replace(urlSeparator + urlSeparator, urlSeparator);
        }

        url = url.replace(/@@@/g, '://');
        
        // get the url scheme
        let scheme:string = this.getSchemeFromUrl(url);

        if(scheme === ''){

            if(StringUtils.isUrl('http://' + url)){

                return 'http://' + url;
            }
        }

        return url;
    }


    /**
     * Full text search is the official name for the process of searching on a big text content based on a string containing some text to find.
     * This method will process a text so it removes all the accents and non alphanumerical characters that are not usefull for searching on strings,
     * convert everything to lower case and remove empty spaces.
     * To perform the search it is important that both search and searched strings are standarized the same way, to maximize possible matches.
     *
     * @param string String to process
     * @param wordSeparator The character that will be treated as the word separator. By default it is the empty space character ' '
     *
     * @return The resulting string
     */
    public static formatForFullTextSearch(string: string, wordSeparator = ' ') {

        if(!StringUtils.isString(string)){

            throw new Error('value is not a string');
        }

        // Remove all word separators
        let res = StringUtils.replace(string, wordSeparator, '');

        // Remove accents
        res = StringUtils.removeAccents(res);

        // Take only alphanumerical characters
        res = res.replace(/[^\p{L}\p{N}]/ug, '');

        // make all lowercase
        return res.toLowerCase();
    }


    /**
     * Compares two strings and gives the number of character replacements that must be performed to convert one
     * of the strings into the other. A very useful method to use in fuzzy text searches where we want to look for
     * similar texts. This method uses the Levenshtein method for the comparison:
     *
     * The Levenshtein distance is defined as the minimal number of characters you have to replace, insert or delete
     * to transform string1 into string2. The complexity of the algorithm is O(m*n), where n and m are the length
     * of string1 and string2.
     *
     * @example "aha" and "aba" will output 1 cause we need to change the h for a b to transform one string into another.
     *
     * @param string1 The first string to compare
     * @param string2 The second string to compare
     *
     * @return The number of characters to replace to convert $string1 into $string2 where 0 means both strings are the same.
     *         The higher the result, the more different the strings are.
     */
    public static compareByLevenshtein(string1: string, string2: string): number {

        // This function was found at https://gist.github.com/santhoshtr/1710925

        if(!StringUtils.isString(string1) || !StringUtils.isString(string2)){

            throw new Error('string1 and string2 must be strings');
        }

        let length1 = string1.length;
        let length2 = string2.length;

        if(length1 < length2) {

            return StringUtils.compareByLevenshtein(string2, string1);
        }

        if(length1 == 0) {

            return length2;
        }

        if(string1 === string2) {

            return 0;
        }

        let currentRow: number[] = [];

        // This code is the equivalent to the range(0, $length2) in php version
        let prevRow: number[] = [];
        for (let i = 0; i <= length2; i++) {

            prevRow.push(i);
        }

        for (let i = 0; i < length1; i++) {

            currentRow = [];
            currentRow[0] = i + 1;
            let c1 = string1.substr(i, 1);

            for (let j = 0; j < length2; j++) {

                let c2 = string2.substr(j, 1);
                let insertions = prevRow[j+1] + 1;
                let deletions = currentRow[j] + 1;
                let substitutions = prevRow[j] + ((c1 !== c2) ? 1 : 0);
                currentRow.push(Math.min(insertions, deletions, substitutions));
            }

            prevRow = currentRow;
        }

        return prevRow[length2];
    }


    /**
     * Compares the percentage of similarity between two strings, based on the Levenshtein method. A very useful method
     * to use in fuzzy text searches where we want to look for similar texts.
     *
     * @param string1 The first string to compare
     * @param string2 The second string to compare
     *
     * @return A number between 0 and 100, being 100 if both strings are the same and 0 if both strings are totally different
     */
    public static compareSimilarityPercent(string1: string, string2: string) {

        const levenshtein = StringUtils.compareByLevenshtein(string1, string2);

        if(levenshtein === 0){

            return 100;
        }

        return (1 - levenshtein / Math.max(string1.length, string2.length)) * 100;
    }
    
    
    /**
     * Generate a path string from an array of elements.
     *
     * @param elements An array of elements that will be used to form the path.
     * @param separator An optional separator that will be used to join the elements in the path. Defaults to `/`.
     *
     * @return A string representing the path formed from the given elements.
     */
    public static generatePath(elements: string[], separator = ''){

        if(!ArrayUtils.isArray(elements)){
            
            throw new Error("elements is not an array");
        }
        
        if(separator === ''){

            separator = '/';
        }

        return StringUtils.formatPath(elements.join(separator), separator);
    }


    /**
     * Generates a random string with the specified length and options
     *
     * @param minLength Specify the minimum possible length for the generated string
     * @param maxLength Specify the maximum possible length for the generated string
     * @param charSet Defines the list of possible characters to be generated. Each element of charSet must be a string containing
     *                the possible characters like 'a1kjuhAO' or a range like 'a-z', 'A-D', '0-5', ... etc.
     *                Note that - character must be escaped \- when not specified as part of a range.
     *                Default charset is alphanumeric ['0-9', 'a-z', 'A-Z']
     *
     * @return A randomly generated string
     */
    public static generateRandom(minLength: number, maxLength: number, charSet = ['0-9', 'a-z', 'A-Z']) {

        if(minLength < 0 || !NumericUtils.isInteger(minLength) ||
           maxLength < 0 || !NumericUtils.isInteger(maxLength)) {

            throw new Error('minLength and maxLength must be positive numbers');
        }

        if(maxLength < minLength){

            throw new Error('Provided maxLength must be higher or equal than minLength');
        }

        if(!ArrayUtils.isArray(charSet) || charSet.length <= 0){

            throw new Error('invalid charset');
        }

        // Define the output charset
        let finalCharSet = '';
        let numbers = '0123456789';
        let lowerCaseLetters = 'abcdefghijkmnopqrstuvwxyz';
        let upperCaseLetters = 'ABCDEFGHIJKMNOPQRSTUVWXYZ';

        for (let chars of charSet) {

            if(!StringUtils.isString(chars) || StringUtils.isEmpty(chars)){

                throw new Error('invalid charset');
            }

            let firstChar = chars.substr(0, 1);
            let thirdChar = chars.substr(2, 1);

            // Check if an interval of characters has been defined
            if(chars.length === 3 && chars.indexOf('-') === 1 && firstChar !== '\\'){

                // Look for numeric intervals
                if(numbers.indexOf(firstChar) >= 0) {

                    finalCharSet += numbers.substring(numbers.indexOf(firstChar), numbers.indexOf(thirdChar) + 1); 

                // Look for lower case letter intervals
                } else if (lowerCaseLetters.indexOf(firstChar) >= 0) {

                    finalCharSet += lowerCaseLetters.substring(lowerCaseLetters.indexOf(firstChar), lowerCaseLetters.indexOf(thirdChar) + 1); 

                // Look for upper case letter intervals
                } else if(upperCaseLetters.indexOf(firstChar) >= 0) {

                    finalCharSet += upperCaseLetters.substring(upperCaseLetters.indexOf(firstChar), upperCaseLetters.indexOf(thirdChar) + 1);
                }

            } else {

                finalCharSet += StringUtils.replace(chars, '\\-', '-');
            }
        }

        // Generate as many random characters as required
        let result = '' ;
        const length = (minLength === maxLength) ? maxLength : NumericUtils.generateRandomInteger(minLength, maxLength);

        for(let i=0; i<length; i++){

            result += finalCharSet.charAt(Math.floor(Math.random() * finalCharSet.length));
        }

        return result;
    }


    public static findMostSimilarString() {

        // TODO: translate from php
    }


    public static findMostSimilarStringIndex() {
        
        // TODO: translate from php
    }
    
    
    public static removeNewLineCharacters() {
        
        // TODO: translate from php
    }


    /**
     * Converts all accent characters to ASCII characters on a given string.<br>
     * This method is based on a stack overflow implementation called removeDiacritics
     *
     * @see http://stackoverflow.com/questions/990904/remove-accents-diacritics-in-a-string-in-javascript
     * 
     * @param string Text from which accents must be cleaned
     *
     * @returns The given string with all accent and diacritics replaced by the respective ASCII characters.
     */
    public static removeAccents(string:string) {

        if(!StringUtils.isString(string)){

            throw new Error('value is not a string');
        }

        let defaultDiacriticsRemovalMap:any[] = [{
            'b' : 'A',
            'l' : '\u0041\u24B6\uFF21\u00C0\u00C1\u00C2\u1EA6\u1EA4\u1EAA\u1EA8\u00C3\u0100\u0102\u1EB0\u1EAE\u1EB4\u1EB2\u0226\u01E0\u00C4\u01DE\u1EA2\u00C5\u01FA\u01CD\u0200\u0202\u1EA0\u1EAC\u1EB6\u1E00\u0104\u023A\u2C6F'
        }, {
            'b' : 'AA',
            'l' : '\uA732'
        }, {
            'b' : 'AE',
            'l' : '\u00C6\u01FC\u01E2'
        }, {
            'b' : 'AO',
            'l' : '\uA734'
        }, {
            'b' : 'AU',
            'l' : '\uA736'
        }, {
            'b' : 'AV',
            'l' : '\uA738\uA73A'
        }, {
            'b' : 'AY',
            'l' : '\uA73C'
        }, {
            'b' : 'B',
            'l' : '\u0042\u24B7\uFF22\u1E02\u1E04\u1E06\u0243\u0182\u0181'
        }, {
            'b' : 'C',
            'l' : '\u0043\u24B8\uFF23\u0106\u0108\u010A\u010C\u00C7\u1E08\u0187\u023B\uA73E'
        }, {
            'b' : 'D',
            'l' : '\u0044\u24B9\uFF24\u1E0A\u010E\u1E0C\u1E10\u1E12\u1E0E\u0110\u018B\u018A\u0189\uA779'
        }, {
            'b' : 'DZ',
            'l' : '\u01F1\u01C4'
        }, {
            'b' : 'Dz',
            'l' : '\u01F2\u01C5'
        }, {
            'b' : 'E',
            'l' : '\u0045\u24BA\uFF25\u00C8\u00C9\u00CA\u1EC0\u1EBE\u1EC4\u1EC2\u1EBC\u0112\u1E14\u1E16\u0114\u0116\u00CB\u1EBA\u011A\u0204\u0206\u1EB8\u1EC6\u0228\u1E1C\u0118\u1E18\u1E1A\u0190\u018E'
        }, {
            'b' : 'F',
            'l' : '\u0046\u24BB\uFF26\u1E1E\u0191\uA77B'
        }, {
            'b' : 'G',
            'l' : '\u0047\u24BC\uFF27\u01F4\u011C\u1E20\u011E\u0120\u01E6\u0122\u01E4\u0193\uA7A0\uA77D\uA77E'
        }, {
            'b' : 'H',
            'l' : '\u0048\u24BD\uFF28\u0124\u1E22\u1E26\u021E\u1E24\u1E28\u1E2A\u0126\u2C67\u2C75\uA78D'
        }, {
            'b' : 'I',
            'l' : '\u0049\u24BE\uFF29\u00CC\u00CD\u00CE\u0128\u012A\u012C\u0130\u00CF\u1E2E\u1EC8\u01CF\u0208\u020A\u1ECA\u012E\u1E2C\u0197'
        }, {
            'b' : 'J',
            'l' : '\u004A\u24BF\uFF2A\u0134\u0248'
        }, {
            'b' : 'K',
            'l' : '\u004B\u24C0\uFF2B\u1E30\u01E8\u1E32\u0136\u1E34\u0198\u2C69\uA740\uA742\uA744\uA7A2'
        }, {
            'b' : 'L',
            'l' : '\u004C\u24C1\uFF2C\u013F\u0139\u013D\u1E36\u1E38\u013B\u1E3C\u1E3A\u0141\u023D\u2C62\u2C60\uA748\uA746\uA780'
        }, {
            'b' : 'LJ',
            'l' : '\u01C7'
        }, {
            'b' : 'Lj',
            'l' : '\u01C8'
        }, {
            'b' : 'M',
            'l' : '\u004D\u24C2\uFF2D\u1E3E\u1E40\u1E42\u2C6E\u019C'
        }, {
            'b' : 'N',
            'l' : '\u004E\u24C3\uFF2E\u01F8\u0143\u00D1\u1E44\u0147\u1E46\u0145\u1E4A\u1E48\u0220\u019D\uA790\uA7A4'
        }, {
            'b' : 'NJ',
            'l' : '\u01CA'
        }, {
            'b' : 'Nj',
            'l' : '\u01CB'
        }, {
            'b' : 'O',
            'l' : '\u004F\u24C4\uFF2F\u00D2\u00D3\u00D4\u1ED2\u1ED0\u1ED6\u1ED4\u00D5\u1E4C\u022C\u1E4E\u014C\u1E50\u1E52\u014E\u022E\u0230\u00D6\u022A\u1ECE\u0150\u01D1\u020C\u020E\u01A0\u1EDC\u1EDA\u1EE0\u1EDE\u1EE2\u1ECC\u1ED8\u01EA\u01EC\u00D8\u01FE\u0186\u019F\uA74A\uA74C'
        }, {
            'b' : 'OI',
            'l' : '\u01A2'
        }, {
            'b' : 'OO',
            'l' : '\uA74E'
        }, {
            'b' : 'OU',
            'l' : '\u0222'
        }, {
            'b' : 'OE',
            'l' : '\u008C\u0152'
        }, {
            'b' : 'oe',
            'l' : '\u009C\u0153'
        }, {
            'b' : 'P',
            'l' : '\u0050\u24C5\uFF30\u1E54\u1E56\u01A4\u2C63\uA750\uA752\uA754'
        }, {
            'b' : 'Q',
            'l' : '\u0051\u24C6\uFF31\uA756\uA758\u024A'
        }, {
            'b' : 'R',
            'l' : '\u0052\u24C7\uFF32\u0154\u1E58\u0158\u0210\u0212\u1E5A\u1E5C\u0156\u1E5E\u024C\u2C64\uA75A\uA7A6\uA782'
        }, {
            'b' : 'S',
            'l' : '\u0053\u24C8\uFF33\u1E9E\u015A\u1E64\u015C\u1E60\u0160\u1E66\u1E62\u1E68\u0218\u015E\u2C7E\uA7A8\uA784'
        }, {
            'b' : 'T',
            'l' : '\u0054\u24C9\uFF34\u1E6A\u0164\u1E6C\u021A\u0162\u1E70\u1E6E\u0166\u01AC\u01AE\u023E\uA786'
        }, {
            'b' : 'TZ',
            'l' : '\uA728'
        }, {
            'b' : 'U',
            'l' : '\u0055\u24CA\uFF35\u00D9\u00DA\u00DB\u0168\u1E78\u016A\u1E7A\u016C\u00DC\u01DB\u01D7\u01D5\u01D9\u1EE6\u016E\u0170\u01D3\u0214\u0216\u01AF\u1EEA\u1EE8\u1EEE\u1EEC\u1EF0\u1EE4\u1E72\u0172\u1E76\u1E74\u0244'
        }, {
            'b' : 'V',
            'l' : '\u0056\u24CB\uFF36\u1E7C\u1E7E\u01B2\uA75E\u0245'
        }, {
            'b' : 'VY',
            'l' : '\uA760'
        }, {
            'b' : 'W',
            'l' : '\u0057\u24CC\uFF37\u1E80\u1E82\u0174\u1E86\u1E84\u1E88\u2C72'
        }, {
            'b' : 'X',
            'l' : '\u0058\u24CD\uFF38\u1E8A\u1E8C'
        }, {
            'b' : 'Y',
            'l' : '\u0059\u24CE\uFF39\u1EF2\u00DD\u0176\u1EF8\u0232\u1E8E\u0178\u1EF6\u1EF4\u01B3\u024E\u1EFE'
        }, {
            'b' : 'Z',
            'l' : '\u005A\u24CF\uFF3A\u0179\u1E90\u017B\u017D\u1E92\u1E94\u01B5\u0224\u2C7F\u2C6B\uA762'
        }, {
            'b' : 'a',
            'l' : '\u0061\u24D0\uFF41\u1E9A\u00E0\u00E1\u00E2\u1EA7\u1EA5\u1EAB\u1EA9\u00E3\u0101\u0103\u1EB1\u1EAF\u1EB5\u1EB3\u0227\u01E1\u00E4\u01DF\u1EA3\u00E5\u01FB\u01CE\u0201\u0203\u1EA1\u1EAD\u1EB7\u1E01\u0105\u2C65\u0250'
        }, {
            'b' : 'aa',
            'l' : '\uA733'
        }, {
            'b' : 'ae',
            'l' : '\u00E6\u01FD\u01E3'
        }, {
            'b' : 'ao',
            'l' : '\uA735'
        }, {
            'b' : 'au',
            'l' : '\uA737'
        }, {
            'b' : 'av',
            'l' : '\uA739\uA73B'
        }, {
            'b' : 'ay',
            'l' : '\uA73D'
        }, {
            'b' : 'b',
            'l' : '\u0062\u24D1\uFF42\u1E03\u1E05\u1E07\u0180\u0183\u0253'
        }, {
            'b' : 'c',
            'l' : '\u0063\u24D2\uFF43\u0107\u0109\u010B\u010D\u00E7\u1E09\u0188\u023C\uA73F\u2184'
        }, {
            'b' : 'd',
            'l' : '\u0064\u24D3\uFF44\u1E0B\u010F\u1E0D\u1E11\u1E13\u1E0F\u0111\u018C\u0256\u0257\uA77A'
        }, {
            'b' : 'dz',
            'l' : '\u01F3\u01C6'
        }, {
            'b' : 'e',
            'l' : '\u0065\u24D4\uFF45\u00E8\u00E9\u00EA\u1EC1\u1EBF\u1EC5\u1EC3\u1EBD\u0113\u1E15\u1E17\u0115\u0117\u00EB\u1EBB\u011B\u0205\u0207\u1EB9\u1EC7\u0229\u1E1D\u0119\u1E19\u1E1B\u0247\u025B\u01DD'
        }, {
            'b' : 'f',
            'l' : '\u0066\u24D5\uFF46\u1E1F\u0192\uA77C'
        }, {
            'b' : 'g',
            'l' : '\u0067\u24D6\uFF47\u01F5\u011D\u1E21\u011F\u0121\u01E7\u0123\u01E5\u0260\uA7A1\u1D79\uA77F'
        }, {
            'b' : 'h',
            'l' : '\u0068\u24D7\uFF48\u0125\u1E23\u1E27\u021F\u1E25\u1E29\u1E2B\u1E96\u0127\u2C68\u2C76\u0265'
        }, {
            'b' : 'hv',
            'l' : '\u0195'
        }, {
            'b' : 'i',
            'l' : '\u0069\u24D8\uFF49\u00EC\u00ED\u00EE\u0129\u012B\u012D\u00EF\u1E2F\u1EC9\u01D0\u0209\u020B\u1ECB\u012F\u1E2D\u0268\u0131'
        }, {
            'b' : 'j',
            'l' : '\u006A\u24D9\uFF4A\u0135\u01F0\u0249'
        }, {
            'b' : 'k',
            'l' : '\u006B\u24DA\uFF4B\u1E31\u01E9\u1E33\u0137\u1E35\u0199\u2C6A\uA741\uA743\uA745\uA7A3'
        }, {
            'b' : 'l',
            'l' : '\u006C\u24DB\uFF4C\u0140\u013A\u013E\u1E37\u1E39\u013C\u1E3D\u1E3B\u017F\u0142\u019A\u026B\u2C61\uA749\uA781\uA747'
        }, {
            'b' : 'lj',
            'l' : '\u01C9'
        }, {
            'b' : 'm',
            'l' : '\u006D\u24DC\uFF4D\u1E3F\u1E41\u1E43\u0271\u026F'
        }, {
            'b' : 'n',
            'l' : '\u006E\u24DD\uFF4E\u01F9\u0144\u00F1\u1E45\u0148\u1E47\u0146\u1E4B\u1E49\u019E\u0272\u0149\uA791\uA7A5'
        }, {
            'b' : 'nj',
            'l' : '\u01CC'
        }, {
            'b' : 'o',
            'l' : '\u006F\u24DE\uFF4F\u00F2\u00F3\u00F4\u1ED3\u1ED1\u1ED7\u1ED5\u00F5\u1E4D\u022D\u1E4F\u014D\u1E51\u1E53\u014F\u022F\u0231\u00F6\u022B\u1ECF\u0151\u01D2\u020D\u020F\u01A1\u1EDD\u1EDB\u1EE1\u1EDF\u1EE3\u1ECD\u1ED9\u01EB\u01ED\u00F8\u01FF\u0254\uA74B\uA74D\u0275'
        }, {
            'b' : 'oi',
            'l' : '\u01A3'
        }, {
            'b' : 'ou',
            'l' : '\u0223'
        }, {
            'b' : 'oo',
            'l' : '\uA74F'
        }, {
            'b' : 'p',
            'l' : '\u0070\u24DF\uFF50\u1E55\u1E57\u01A5\u1D7D\uA751\uA753\uA755'
        }, {
            'b' : 'q',
            'l' : '\u0071\u24E0\uFF51\u024B\uA757\uA759'
        }, {
            'b' : 'r',
            'l' : '\u0072\u24E1\uFF52\u0155\u1E59\u0159\u0211\u0213\u1E5B\u1E5D\u0157\u1E5F\u024D\u027D\uA75B\uA7A7\uA783'
        }, {
            'b' : 's',
            'l' : '\u0073\u24E2\uFF53\u00DF\u015B\u1E65\u015D\u1E61\u0161\u1E67\u1E63\u1E69\u0219\u015F\u023F\uA7A9\uA785\u1E9B'
        }, {
            'b' : 't',
            'l' : '\u0074\u24E3\uFF54\u1E6B\u1E97\u0165\u1E6D\u021B\u0163\u1E71\u1E6F\u0167\u01AD\u0288\u2C66\uA787'
        }, {
            'b' : 'tz',
            'l' : '\uA729'
        }, {
            'b' : 'u',
            'l' : '\u0075\u24E4\uFF55\u00F9\u00FA\u00FB\u0169\u1E79\u016B\u1E7B\u016D\u00FC\u01DC\u01D8\u01D6\u01DA\u1EE7\u016F\u0171\u01D4\u0215\u0217\u01B0\u1EEB\u1EE9\u1EEF\u1EED\u1EF1\u1EE5\u1E73\u0173\u1E77\u1E75\u0289'
        }, {
            'b' : 'v',
            'l' : '\u0076\u24E5\uFF56\u1E7D\u1E7F\u028B\uA75F\u028C'
        }, {
            'b' : 'vy',
            'l' : '\uA761'
        }, {
            'b' : 'w',
            'l' : '\u0077\u24E6\uFF57\u1E81\u1E83\u0175\u1E87\u1E85\u1E98\u1E89\u2C73'
        }, {
            'b' : 'x',
            'l' : '\u0078\u24E7\uFF58\u1E8B\u1E8D'
        }, {
            'b' : 'y',
            'l' : '\u0079\u24E8\uFF59\u1EF3\u00FD\u0177\u1EF9\u0233\u1E8F\u00FF\u1EF7\u1E99\u1EF5\u01B4\u024F\u1EFF'
        }, {
            'b' : 'z',
            'l' : '\u007A\u24E9\uFF5A\u017A\u1E91\u017C\u017E\u1E93\u1E95\u01B6\u0225\u0240\u2C6C\uA763'
        }];

        let diacriticsMap:any = {};

        for(let i:number = 0; i < defaultDiacriticsRemovalMap.length; i++){

            let letters:string = defaultDiacriticsRemovalMap[i].l;

            for(let j:number = 0; j < letters.length; j++){

                diacriticsMap[letters[j]] = defaultDiacriticsRemovalMap[i].b;
            }
        }

        return string.replace(/[^\u0000-\u007E]/g, function(a) {

            return diacriticsMap[a] || a;
        });
    }
    
    
    public static removeWordsShorterThan() {
        
        // TODO: translate from php
    }
    
    
    public static removeWordsLongerThan() {
        
        // TODO: translate from php
    }
    
    
    public static removeUrls() {

        // TODO: translate from php
    }


    public static removeHtmlCode() {

        // TODO: translate from php
    }


    /**
     * Remove all duplicate consecutive fragments from the provided string and leave only one occurence
     *
     * @param string The string to process
     * @param set A list with the fragments that will be removed when found consecutive. If this value is
     *        an empty array, all duplicate consecutive characters will be deleted.
     *        We can pass here words or special characters like "\n"
     *
     * @example If we want to remove all duplicate consecutive empty spaces,
     *          we will call removeSameConsecutive('string', [' '])
     * @example If we want to remove all duplicate consecutive new line characters,
     *          we will call removeSameConsecutive("string\n\n\nstring", ["\n"])
     * @example If we want to remove all duplicate "hello" words, we will call
     *          removeSameConsecutive('hellohellohellohello', ['hello'])
     *
     * @returns The string with a maximum of one consecutive sequence for all those matching the provided set
     */
    public static removeSameConsecutive(string: string, set:string[] = []) {

        if(string === null){

            return '';
        }

        if(!StringUtils.isString(string)){

            throw new Error('string must be a string');
        }

        if(!ArrayUtils.isArray(set)){

            throw new Error('set must be of the type array');
        }

        if(set.length === 0){

            // All possible duplicate characters will be removed from the string
            return string.replace(/(.|\r\n|[\r\n])\1+/ug, '$1');
        }

        // Replace all repeated occurences of the provided list of characters
        return string.replace(new RegExp(`(${set.join('|')})\\1+`, 'ug'), '$1');
    }
}
