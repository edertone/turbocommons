/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */
 

import { StringUtils } from '../utils/StringUtils';
import { NumericUtils } from '../utils/NumericUtils';
import { ArrayUtils } from '../utils/ArrayUtils';
import { ObjectUtils } from '../utils/ObjectUtils';
            
        
/**
 * Class that allows us to manage application validation in an encapsulated way.
 * We can create as many instances as we want, and each instance will store the validation history and global validation state,
 * so we can use this class to validate complex forms or multiple elements globally.
 * We can also use tags to sandbox different validation elements or groups togheter.
 */ 
export class ValidationManager{


    /**
     * Constant that defines the correct validation status
     */
    static readonly OK = 0;


    /**
     * Constant that defines the warning validation status
     */
    static readonly WARNING = 1;


    /**
     * Constant that defines the error validation status
     */
    static readonly ERROR = 2;
    

    /**
     * Stores the current validation state for each one of the defined tags.
     * 
     * tag contains the name of the tag for which we are saving the status 
     * status can have 3 different values: OK / WARNING / ERROR
     */
    private _validationStatus: {
        tag:string;
        status: number;
    }[] = [{
        tag: '',
        status: ValidationManager.OK
    }];
    
    
    /**
     * Stores a list of all the validation error or warning messages that have happened
     * since the validation manager was created or since the last reset was performed.
     * 
     * Each message is stored with its associated tag.
     */
    private _failedMessages: {tag:string; message:string}[] = []; 
        
    
    /**
     * Check the current validation state.
     * Possible return values are ValidationManager.OK, ValidationManager.WARNING or ValidationManager.ERROR
     * 
     * @param tags If we want to check the validation state for a specific tag or a list of tags, we can set it here. If we want to
     *        get the global validation state for all the tags we will leave this value empty ''.
     *        
     * @returns ValidationManager.OK, ValidationManager.WARNING or ValidationManager.ERROR 
     */
    getStatus(tags:string|string[] = ''){
    
        let maxStatus = 0;
        
        let tagsList = ArrayUtils.isArray(tags) ? tags as string[] : [tags as string];
        
        for (let status of this._validationStatus) {
            
            if((tags === '' || tagsList.indexOf(status.tag) >= 0) &&
                status.status > maxStatus){
                    
                maxStatus = status.status;
            }
        }
        
        return maxStatus;
    }
    
    
    /**
     * Provides a way to perform a fast validation check. Will return true if validation state is ok, or false if validation
     * manager is in a warning or error state.
     * 
     * @param tags If we want to check the validation state for a specific tag or a list of tags, we can set it here. If we want to
     *        get the global validation state for all the tags we will leave this value empty ''.
     * 
     * @return True if status is ok, false if status is warning or error
     */
    ok(tags:string|string[] = ''){
    
        return this.getStatus(tags) === ValidationManager.OK;
    }


    /**
     * Provides a way to perform a fast validation check. Will return true if validation manager is in a warning or error state, or false
     * if validation state is ok.
     * 
     * @param tags If we want to check the validation state for a specific tag or a list of tags, we can set it here. If we want to
     *        get the global validation state for all the tags we will leave this value empty ''.
     * 
     * @return True if status is warning or error, False if status is ok
     */
    notOk(tags:string|string[] = ''){
    
        return this.getStatus(tags) !== ValidationManager.OK;
    }


    /**
     * Find the first error or warning message that happened since the validation manager was instantiated or
     * since the last reset 
     * 
     * @param tag If we want to filter only the warning / error messages by tag or list of tags, we can set it here. If we want to
     *        get the first of all messages, no matter which tag was applied, we will leave this value empty ''.
     *        
     * @return The first error or warning message or empty string if no message exists
     */
    getFirstMessage(tags:string|string[] = ''){
        
        let tagsList = ArrayUtils.isArray(tags) ? tags as string[] : [tags as string];

        for (let message of this._failedMessages) {

            if(tags === '' || tags === null ||
                (ArrayUtils.isArray(tags) && tags.length === 0) ||
                tagsList.indexOf(message.tag) >= 0){

                return message.message;
            }
        }

        return '';
    }
    
    
    /**
     * Find the latest error or warning message that happened since the validation manager was instantiated or
     * since the last reset 
     * 
     * @param tag If we want to filter only the warning / error messages by tag or list of tags, we can set it here. If we want to
     *        get the latest of all messages, no matter which tag was applied, we will leave this value empty ''.
     *        
     * @return The last error or warning message or empty string if no message exists
     */
    getLastMessage(tags:string|string[] = ''){
        
        let tagsList = ArrayUtils.isArray(tags) ? tags as string[] : [tags as string];

        for (let i = this._failedMessages.length - 1; i >= 0; i--) {
            
            if(tags === '' || tags === null ||
                (ArrayUtils.isArray(tags) && tags.length === 0) ||
                tagsList.indexOf(this._failedMessages[i].tag) >= 0){

                return this._failedMessages[i].message;
            }
        }

        return '';
    }
          
    
    /**
     * Validation will fail if specified value is not a true boolean value
     *
     * @param value A boolean expression to validate
     * @param errorMessage The error message that will be generated if validation fails
     * @param tags We can define a tag name or list of tags to group the validation results. We can use this tags later to filter validation state
     * @param isWarning Tells if the validation fail will be processed as a validation error or a validation warning
     *
     * @return False in case the validation fails or true if validation succeeds.
     */
    isTrue(value:any, errorMessage = 'value is not true', tags:string|string[] = '', isWarning = false){

        return this._updateValidationStatus(value === true, errorMessage, tags, isWarning);
    }
    
    
    /**
     * Validation will fail if specified value is not a boolean
     *
     * @param value The boolean to validate
     * @param errorMessage The error message that will be generated if validation fails
     * @param tags We can define a tag name or list of tags to group the validation results. We can use this tags later to filter validation state
     * @param isWarning Tells if the validation fail will be processed as a validation error or a validation warning
     * 
     * @return False in case the validation fails or true if validation succeeds.
     */
    isBoolean(value:any, errorMessage = 'value is not a boolean', tags:string|string[] = '', isWarning = false){

        return this._updateValidationStatus((typeof (value) === 'boolean'), errorMessage, tags, isWarning);
    }
    
    
    /**
     * Validation will fail if specified value is not numeric
     *
     * @param value The number to validate
     * @param errorMessage The error message that will be generated if validation fails
     * @param tags We can define a tag name or list of tags to group the validation results. We can use this tags later to filter validation state
     * @param isWarning Tells if the validation fail will be processed as a validation error or a validation warning
     *
     * @return False in case the validation fails or true if validation succeeds.
     */
    isNumeric(value:any, errorMessage = 'value is not a number', tags:string|string[] = '', isWarning = false){

        return this._updateValidationStatus(NumericUtils.isNumeric(value), errorMessage, tags, isWarning);
    }
    
    
    /**
     * Validation will fail if specified value is not numeric and between the two provided values.
     *
     * @param value The number to validate
     * @param min The minimum accepted value (included)
     * @param max The maximum accepted value (included)
     * @param errorMessage The error message that will be generated if validation fails
     * @param tags We can define a tag name or list of tags to group the validation results. We can use this tags later to filter validation state
     * @param isWarning Tells if the validation fail will be processed as a validation error or a validation warning
     *
     * @return False in case the validation fails or true if validation succeeds.
     */
    isNumericBetween(value:any, min:number, max:number, errorMessage = 'value is not between min and max', tags:string|string[] = '', isWarning = false){
        
        return this._updateValidationStatus(NumericUtils.isNumeric(value) && value >= min && value <= max,
                errorMessage, tags, isWarning);
    }
    
    
    /**
     * Validation will fail if specified value is not a string
     *
     * @param $value The element to validate
     * @param errorMessage The error message that will be generated if validation fails
     * @param tags We can define a tag name or list of tags to group the validation results. We can use this tags later to filter validation state
     * @param isWarning Tells if the validation fail will be processed as a validation error or a validation warning
     *
     * @return False in case the validation fails or true if validation succeeds.
     */
    isString(value:any, errorMessage = 'value is not a string', tags:string|string[] = '', isWarning = false){

        return this._updateValidationStatus(StringUtils.isString(value), errorMessage, tags, isWarning);
    }
            
            
    /**
     * Validation will fail if specified value is not an url
     *
     * @param value The element to validate
     * @param errorMessage The error message that will be generated if validation fails
     * @param tags We can define a tag name or list of tags to group the validation results. We can use this tags later to filter validation state
     * @param isWarning Tells if the validation fail will be processed as a validation error or a validation warning
     *
     * @return False in case the validation fails or true if validation succeeds.
     */
    isUrl(value:any, errorMessage = 'value is not an URL', tags:string|string[] = '', isWarning = false){

        return this._updateValidationStatus(StringUtils.isUrl(value), errorMessage, tags, isWarning);
    }
    
    
    /**
     * Validation will fail if specified value is not an array
     *
     * @param value The array to validate
     * @param errorMessage The error message that will be generated if validation fails
     * @param tags We can define a tag name or list of tags to group the validation results. We can use this tags later to filter validation state
     * @param isWarning Tells if the validation fail will be processed as a validation error or a validation warning
     *
     * @return False in case the validation fails or true if validation succeeds.
     */
    isArray(value:any, errorMessage = 'value is not an array', tags:string|string[] = '', isWarning = false){

        return this._updateValidationStatus(ArrayUtils.isArray(value), errorMessage, tags, isWarning);
    }
    
    
    /**
     * Validation will fail if specified value is not an object
     *
     * @param value The object to validate
     * @param errorMessage The error message that will be generated if validation fails
     * @param tags We can define a tag name or list of tags to group the validation results. We can use this tags later to filter validation state
     * @param isWarning Tells if the validation fail will be processed as a validation error or a validation warning
     *
     * @return False in case the validation fails or true if validation succeeds.
     */
    isObject(value:any, errorMessage = 'value is not an object', tags:string|string[] = '', isWarning = false){

        return this._updateValidationStatus(ObjectUtils.isObject(value), errorMessage, tags, isWarning);
    }
    
    
    /**
     * Validation will fail if specified text is empty.<br>
     * See Stringutils.isEmpty to understand what is considered as an empty text
     *
     * @param value A text that must not be empty.
     * @param emptyChars Optional array containing a list of string values that will be considered as empty for the given string. This can be useful in some cases when we want to consider a string like 'NULL' as an empty string.
     * @param errorMessage The error message that will be generated if validation fails
     * @param tags We can define a tag name or list of tags to group the validation results. We can use this tags later to filter validation state
     * @param isWarning Tells if the validation fail will be processed as a validation error or a validation warning
     *
     * @see Stringutils.isEmpty
     *
     * @return False in case the validation fails or true if validation succeeds.
     */
    isFilledIn(value:any, emptyChars = [], errorMessage = 'value is required', tags:string|string[] = '', isWarning = false){

        return this._updateValidationStatus(!StringUtils.isEmpty(value, emptyChars), errorMessage, tags, isWarning);
    }
    
    
    /**
     * TODO - translate from PHP
     */
    isObjectWithValidProperties() {
    
        // TODO
        return false;
    }
            
    
    isDate() {
    
        // TODO
        return false;
    }
    
    
    isMail() {
    
        // TODO
        return false;
    }
    
    
    /**
     * Validation will fail if specified elements are not identical.
     *
     * @param value First of the two objects to compare. Almost any type can be provided: ints, strings, arrays...
     * @param value2 Second of the two objects to compare. Almost any type can be provided: ints, strings, arrays...
     * @param errorMessage The error message that will be generated if validation fails
     * @param tags We can define a tag name or list of tags to group the validation results. We can use this tags later to filter validation state
     * @param isWarning Tells if the validation fail will be processed as a validation error or a validation warning
     *
     * @return False in case the validation fails or true if validation succeeds.
     */
    isEqualTo(value:any, value2:any, errorMessage = 'values are not equal', tags:string|string[] = '', isWarning = false){

        let res = false;

        // Compare elements depending on its type
        if(ArrayUtils.isArray(value) && ArrayUtils.isArray(value2)){

            res = ArrayUtils.isEqualTo(value, value2);

        }else{

            if(ObjectUtils.isObject(value) && ObjectUtils.isObject(value2)){

                res = ObjectUtils.isEqualTo(value, value2);

            }else{

                if(value === value2){

                    res = true;
                }
            }
        }

        return this._updateValidationStatus(res, errorMessage, tags, isWarning);
    }
    
    
    isMinimumWords(string:string):boolean {
    
        // TODO
        return string === '';
    }
    
    
    isNIF(string:string):boolean {
    
        // TODO
        return string === '';
    }
    
    
    isMinimumLength(string:string):boolean {
    
        // TODO
        return string === '';
    }
    
    
    isMaximumLength(string:string):boolean {
        
        // TODO
        return string === '';
    }
    
    
    isPostalCode(string:string):boolean {
    
        // TODO
        return string === '';
    }
    
    
    isPhone(string:string):boolean {
    
        // TODO
        return string === '';
    }
    
    
    isHtmlFormValid(string:string):boolean {
    
        // TODO
        return string === '';
    }
    
    
    /** 
     * Reinitialize the validation status.
     *
     * This is normally called at the beginning of every global validation we perform. It will reset all the validation
     * errors on this class and for all tags, so we can re validate whatever we need to.
     * 
     * @returns void
     */
    reset() {
    
        this._validationStatus = [{
            tag: '',
            status: ValidationManager.OK
        }];
        
        this._failedMessages = [];
    }
    
    
    /**
     * Update the class validation Status depending on the provided error message.
     *
     * @param result the result of the validation
     * @param errorMessage The error message that's been generated from a previously executed validation method
     * @param tags The tag or list of tags that have been defiend for the validation value
     * @param isWarning Tells if the validation fail will be processed as a validation error or a validation warning
     *
     * @return True if received errorMessage was '' (validation passed) or false if some error message was received (validation failed)
     */
    private _updateValidationStatus(result:boolean, errorMessage: string, tags:string|string[] = '', isWarning: boolean){
        
        if(!result){
            
            // If specified tags do not exist, we will create them
            let tagsList = ArrayUtils.isArray(tags) ? tags as string[] : [tags as string];
            
            for (let t of tagsList) {

                let tagFound = false;
                
                for (let status of this._validationStatus) {
                    
                    if(status.tag === t){
                        
                        tagFound = true;
                        break;
                    }
                }
                
                if(!tagFound){
                    
                    this._validationStatus.push({
                        tag: String(t),
                        status: ValidationManager.OK
                    });
                }
            }
            
            // We must find the specified tags and change their validation status
            for (let t of tagsList) {
            
                for (let i = 0; i < this._validationStatus.length; i++) {
                    
                    if(this._validationStatus[i].tag === t){
                        
                        this._failedMessages.push({tag: t, message: errorMessage});

                        this._validationStatus[i].status =
                            (isWarning && this._validationStatus[i].status != ValidationManager.ERROR) ?
                                ValidationManager.WARNING :
                                ValidationManager.ERROR;
                        
                        break;
                    }
                }
            }
        }

        return result;
    }
}
