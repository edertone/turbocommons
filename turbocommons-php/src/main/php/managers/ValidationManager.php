<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\managers;

use org\turbocommons\src\main\php\model\BaseStrictClass;
use org\turbocommons\src\main\php\utils\NumericUtils;
use org\turbocommons\src\main\php\utils\StringUtils;
use org\turbocommons\src\main\php\utils\ArrayUtils;
use org\turbocommons\src\main\php\utils\ObjectUtils;


/**
 * Class that allows us to manage application validation in an encapsulated way.
 * We can create as many instances as we want, and each instance will store the validation history and global validation state,
 * so we can use this class to validate complex forms or multiple elements globally.
 * We can also use tags to sandbox different validation elements or groups togheter.
 */
class ValidationManager extends BaseStrictClass{


    /**
     * Constant that defines the correct validation status
     */
    const OK = 0;


    /**
     * Constant that defines the warning validation status
     */
    const WARNING = 1;


    /**
     * Constant that defines the error validation status
     */
    const ERROR = 2;


    /**
     * Stores the current validation state for each one of the defined tags.
     *
     * tag contains the name of the tag for which we are saving the status
     * status can have 3 different values: OK / WARNING / ERROR
     */
    private $_validationStatus = [[
       'tag' => '',
       'status' => ValidationManager::OK
    ]];


    /**
     * Stores a list of all the validation error or warning messages that have happened
     * since the validation manager was created or since the last reset was performed.
     *
     * Each message is stored with its associated tag.
     */
    private $_failedMessages = [];


    /**
     * Check the current validation state.
     * Possible return values are ValidationManager.OK, ValidationManager.WARNING or ValidationManager.ERROR
     *
     * @param string|array $tags If we want to check the validation state for a specific tag or a list of tags, we can set it here. If we want to
     *        get the global validation state for all the tags we will leave this value empty ''.
     *
     * @return int ValidationManager.OK, ValidationManager.WARNING or ValidationManager.ERROR
     */
    public function getStatus($tags = ''){

        $maxStatus = 0;

        $tagsList = ArrayUtils::isArray($tags) ? $tags : [$tags];

        foreach ($this->_validationStatus as $status) {

            if(($tags === '' || in_array($status['tag'], $tagsList)) &&
                $status['status'] > $maxStatus){

                    $maxStatus = $status['status'];
            }
        }

        return $maxStatus;
    }


    /**
     * Provides a way to perform a fast validation check. Will return true if validation state is ok, or false if validation
     * manager is in a warning or error state.
     *
     * @param string|array $tags If we want to check the validation state for a specific tag or a list of tags, we can set it here. If we want to
     *        get the global validation state for all the tags we will leave this value empty ''.
     *
     * @return boolean True if status is ok, false if status is warning or error
     */
    public function ok($tags = ''){

        return $this->getStatus($tags) === ValidationManager::OK;
    }


    /**
     * Provides a way to perform a fast validation check. Will return true if validation manager is in a warning or error state, or false
     * if validation state is ok.
     *
     * @param string|array $tags If we want to check the validation state for a specific tag or a list of tags, we can set it here. If we want to
     *        get the global validation state for all the tags we will leave this value empty ''.
     *
     * @return boolean True if status is warning or error, False if status is ok
     */
    public function notOk($tags = ''){

        return $this->getStatus($tags) !== ValidationManager::OK;
    }


    /**
     * Find the first error or warning message that happened since the validation manager was instantiated or
     * since the last reset
     *
     * @param string|array $tags If we want to filter only the warning / error messages by tag or list of tags, we can set it here. If we want to
     *        get the first of all messages, no matter which tag was applied, we will leave this value empty ''.
     *
     * @return string The first error or warning message or empty string if no message exists
     */
    public function getFirstMessage($tags = ''){

        $tagsList = ArrayUtils::isArray($tags) ? $tags : [$tags];

        foreach ($this->_failedMessages as $message) {

            if($tags === '' || $tags === null ||
                (ArrayUtils::isArray($tags) && count($tags) === 0) ||
                in_array($message['tag'], $tagsList)){

                return $message['message'];
            }
        }

        return '';
    }


    /**
     * Find the latest error or warning message that happened since the validation manager was instantiated or
     * since the last reset
     *
     * @param string|array $tags If we want to filter only the warning / error messages by tag or list of tags, we can set it here. If we want to
     *        get the latest of all messages, no matter which tag was applied, we will leave this value empty ''.
     *
     * @return string The last error or warning message or empty string if no message exists
     */
    public function getLastMessage($tags = ''){

        $tagsList = ArrayUtils::isArray($tags) ? $tags : [$tags];

        for ($i = count($this->_failedMessages) - 1; $i >= 0; $i--) {

            if($tags === '' || $tags === null ||
                (ArrayUtils::isArray($tags) && count($tags) === 0) ||
                in_array($this->_failedMessages[$i]['tag'], $tagsList)){

                return $this->_failedMessages[$i]['message'];
            }
        }

        return '';
    }


    /**
     * Validation will fail if specified value is not a true boolean value
     *
     * @param mixed $value A boolean expression to validate
     * @param string $errorMessage The error message that will be generated if validation fails
     * @param mixed $tags We can define a tag name or list of tags to group the validation results. We can use this tags later to filter validation state
     * @param boolean $isWarning Tells if the validation fail will be processed as a validation error or a validation warning
     *
     * @return boolean False in case the validation fails or true if validation succeeds.
     */
    public function isTrue($value, string $errorMessage = 'value is not true', $tags = '', bool $isWarning = false){

        return $this->_updateValidationStatus($value === true, $errorMessage, $tags, $isWarning);
    }


    /**
     * Validation will fail if specified value is not a boolean
     *
     * @param mixed $value The boolean to validate
     * @param string $errorMessage The error message that will be generated if validation fails
     * @param mixed $tags We can define a tag name or list of tags to group the validation results. We can use this tags later to filter validation state
     * @param boolean $isWarning Tells if the validation fail will be processed as a validation error or a validation warning
     *
     * @return boolean False in case the validation fails or true if validation succeeds.
     */
    public function isBoolean($value, string $errorMessage = 'value is not a boolean', $tags = '', bool $isWarning = false){

        return $this->_updateValidationStatus(is_bool($value), $errorMessage, $tags, $isWarning);
    }


    /**
     * Validation will fail if specified value is not numeric
     *
     * @param mixed $value The number to validate
     * @param string $errorMessage The error message that will be generated if validation fails
     * @param mixed $tags We can define a tag name or list of tags to group the validation results. We can use this tags later to filter validation state
     * @param boolean $isWarning Tells if the validation fail will be processed as a validation error or a validation warning
     *
     * @return boolean False in case the validation fails or true if validation succeeds.
     */
    public function isNumeric($value, string $errorMessage = 'value is not a number', $tags = '', bool $isWarning = false){

        return $this->_updateValidationStatus(NumericUtils::isNumeric($value), $errorMessage, $tags, $isWarning);
    }


    /**
     * Validation will fail if specified value is not numeric and between the two provided values.
     *
     * @param mixed $value The number to validate
     * @param number min The minimum accepted value (included)
     * @param number max The maximum accepted value (included)
     * @param string $errorMessage The error message that will be generated if validation fails
     * @param mixed $tags We can define a tag name or list of tags to group the validation results. We can use this tags later to filter validation state
     * @param boolean $isWarning Tells if the validation fail will be processed as a validation error or a validation warning
     *
     * @return boolean False in case the validation fails or true if validation succeeds.
     */
    public function isNumericBetween($value, $min, $max, string $errorMessage = 'value is not between min and max', $tags = '', bool $isWarning = false){

        return $this->_updateValidationStatus(NumericUtils::isNumeric($value) && $value >= $min && $value <= $max,
                $errorMessage, $tags, $isWarning);
    }


    /**
     * Validation will fail if specified value is not a string
     *
     * @param mixed $value The element to validate
     * @param string $errorMessage The error message that will be generated if validation fails
     * @param mixed $tags We can define a tag name or list of tags to group the validation results. We can use this tags later to filter validation state
     * @param boolean $isWarning Tells if the validation fail will be processed as a validation error or a validation warning
     *
     * @return boolean False in case the validation fails or true if validation succeeds.
     */
    public function isString($value, string $errorMessage = 'value is not a string', $tags = '', bool $isWarning = false){

        return $this->_updateValidationStatus(is_string($value), $errorMessage, $tags, $isWarning);
    }


    /**
     * Validation will fail if specified value is not an url
     *
     * @param mixed $value The element to validate
     * @param string $errorMessage The error message that will be generated if validation fails
     * @param mixed $tags We can define a tag name or list of tags to group the validation results. We can use this tags later to filter validation state
     * @param boolean $isWarning Tells if the validation fail will be processed as a validation error or a validation warning
     *
     * @return boolean False in case the validation fails or true if validation succeeds.
     */
    public function isUrl($value, string $errorMessage = 'value is not an URL', $tags = '', bool $isWarning = false){

        return $this->_updateValidationStatus(StringUtils::isUrl($value), $errorMessage, $tags, $isWarning);
    }


    /**
     * Validation will fail if specified value is not an array
     *
     * @param mixed $value The array to validate
     * @param string $errorMessage The error message that will be generated if validation fails
     * @param mixed $tags We can define a tag name or list of tags to group the validation results. We can use this tags later to filter validation state
     * @param boolean $isWarning Tells if the validation fail will be processed as a validation error or a validation warning
     *
     * @return boolean False in case the validation fails or true if validation succeeds.
     */
    public function isArray($value, string $errorMessage = 'value is not an array', $tags = '', bool $isWarning = false){

        return $this->_updateValidationStatus(is_array($value), $errorMessage, $tags, $isWarning);
    }


    /**
     * Validation will fail if specified value is not an object
     *
     * @param mixed $value The object to validate
     * @param string $errorMessage The error message that will be generated if validation fails
     * @param mixed $tags We can define a tag name or list of tags to group the validation results. We can use this tags later to filter validation state
     * @param boolean $isWarning Tells if the validation fail will be processed as a validation error or a validation warning
     *
     * @return boolean False in case the validation fails or true if validation succeeds.
     */
    public function isObject($value, string $errorMessage = 'value is not an object', $tags = '', bool $isWarning = false){

        return $this->_updateValidationStatus(is_object($value), $errorMessage, $tags, $isWarning);
    }


    /**
     * Validation will fail if specified text is empty.<br>
     * See Stringutils.isEmpty to understand what is considered as an empty text
     *
     * @param string $value A text that must not be empty.
     * @param array $emptyChars Optional array containing a list of string values that will be considered as empty for the given string. This can be useful in some cases when we want to consider a string like 'NULL' as an empty string.
     * @param string $errorMessage The error message that will be generated if validation fails
     * @param mixed $tags We can define a tag name or list of tags to group the validation results. We can use this tags later to filter validation state
     * @param boolean $isWarning Tells if the validation fail will be processed as a validation error or a validation warning
     *
     * @see Stringutils::isEmpty
     *
     * @return boolean False in case the validation fails or true if validation succeeds.
     */
    public function isFilledIn($value, array $emptyChars = [], string $errorMessage = 'value is required', $tags = '', bool $isWarning = false){

        return $this->_updateValidationStatus(!StringUtils::isEmpty($value, $emptyChars), $errorMessage, $tags, $isWarning);
    }


    /**
     * Validation will fail if specified object contains properties that don't match the ones on a given list
     *
     * @param mixed $value The object to validate.
     * @param array $allowedProperties An array of allowed properties that the object must have.
     * @param bool $strict If true, the object must have exactly the properties specified in the allowedProperties array. If false, it is not mandatory
     *             that object has all the properties, it may have less. But in both cases all object properties must exist on the array of allowed.
     * @param string $errorMessage The error message that will be generated if validation fails.
     * @param string $detailedErrorMessage If set to true, extra information will be appended to the error message with details on which properties failed
     * @param mixed $tags We can define a tag name or list of tags to group the validation results. We can use this tags later to filter validation state
     * @param boolean $isWarning Tells if the validation fail will be processed as a validation error or a validation warning
     *
     * @return bool False in case the validation fails or true if validation succeeds.
     */
    public function isObjectWithValidProperties($value, array $allowedProperties, $strict = true, string $errorMessage = 'Invalid object',
                                                $detailedErrorMessage = true, $tags = '', bool $isWarning = false){

        $res = true;

        $properties = ObjectUtils::getKeys($value);

        // Check if all property names are in the string array
        foreach ($properties as $property) {

            if (!in_array($property, $allowedProperties, true)) {

                $res = false;

                if($detailedErrorMessage){

                    $errorMessage .= ': property '.$property.' not allowed';
                }
            }
        }

        // If strict is true, all properties must be found at the array too
        if($strict && !ArrayUtils::isEqualTo(sort($properties), sort($allowedProperties))){

            $res = false;

            if($detailedErrorMessage){

                $errorMessage .= ': must have exactly these properties -> ['.implode(', ', $allowedProperties).']';
            }
        }

        return $this->_updateValidationStatus($res, $errorMessage, $tags, $isWarning);
    }


    /**
     * TODO - translate from TS
     */
    public function isDate(){

        // TODO - translate from TS
    }


    /**
     * TODO - translate from TS
     */
    public function isMail(){

        // TODO - translate from TS
    }


    /**
     * Validation will fail if specified elements are not identical.
     *
     * @param mixed $value First of the two objects to compare. Almost any type can be provided: ints, strings, arrays...
     * @param mixed $value2 Second of the two objects to compare. Almost any type can be provided: ints, strings, arrays...
     * @param string $errorMessage The error message that will be generated if validation fails
     * @param mixed $tags We can define a tag name or list of tags to group the validation results. We can use this tags later to filter validation state
     * @param boolean $isWarning Tells if the validation fail will be processed as a validation error or a validation warning
     *
     * @return boolean False in case the validation fails or true if validation succeeds.
     */
    public function isEqualTo($value, $value2, string $errorMessage = 'values are not equal', $tags = '', bool $isWarning = false){

        $res = false;

        // Compare elements depending on its type
        if(is_array($value) && is_array($value2)){

            $res = ArrayUtils::isEqualTo($value, $value2);

        }else{

            if(is_object($value) && is_object($value2)){

                $res = ObjectUtils::isEqualTo($value, $value2);

            }else{

                if($value === $value2){

                    $res = true;
                }
            }
        }

        return $this->_updateValidationStatus($res, $errorMessage, $tags, $isWarning);
    }


    /**
     * TODO - translate from TS
     */
    public function isMinimumWords(){

        // TODO - translate from TS
    }


    /**
     * TODO - translate from TS
     */
    public function isNIF(){

        // TODO - translate from TS
    }


    /**
     * TODO - translate from TS
     */
    public function isMinimumLength(){

        // TODO - translate from TS
    }


    /**
     * TODO - translate from TS
     */
    public function isMaximumLength(){

        // TODO - translate from TS
    }


    /**
     * TODO - translate from TS
     */
    public function isPostalCode(){

        // TODO - translate from TS
    }


    /**
     * TODO - translate from TS
     */
    public function isPhone(){

        // TODO - translate from TS
    }


    /**
     * TODO - translate from TS
     */
    public function isHtmlFormValid(){

        // TODO - translate from TS
    }


    /**
     * Reinitialize the validation status.
     *
     * This is normally called at the beginning of every global validation we perform. It will reset all the validation
     * errors on this class and for all tags, so we can re validate whatever we need to.
     *
     * @return void
     */
    public function reset(){

        $this->_validationStatus = [[
            'tag' => '',
            'status' => ValidationManager::OK
        ]];

        $this->_failedMessages = [];
    }


    /**
     * Update the class validation Status depending on the provided error message.
     *
     * @param boolean $result the result of the validation
     * @param string $errorMessage The error message that's been generated from a previously executed validation method
     * @param mixed $tags The tag or list of tags that have been defiend for the validation value
     * @param boolean $isWarning Tells if the validation fail will be processed as a validation error or a validation warning
     *
     * @return boolean True if received errorMessage was '' (validation passed) or false if some error message was received (validation failed)
     */
    private function _updateValidationStatus(bool $result, string $errorMessage, $tags, bool $isWarning){

        if(!$result){

            // If specified tags do not exist, we will create them
            $tagsList = ArrayUtils::isArray($tags) ? $tags : [$tags];

            foreach ($tagsList as $t) {

                $tagFound = false;

                foreach ($this->_validationStatus as $status) {

                    if($status['tag'] === $t){

                        $tagFound = true;
                        break;
                    }
                }

                if(!$tagFound){

                    $this->_validationStatus[] = [
                        'tag' => $t,
                        'status' => ValidationManager::OK
                    ];
                }
            }

            // We must find the specified tags and change their validation status
            foreach ($tagsList as $t) {

                for ($i = 0, $l = count($this->_validationStatus); $i < $l; $i++) {

                    if($this->_validationStatus[$i]['tag'] === $t){

                        $this->_failedMessages[] = ['tag' => $t, 'message' => $errorMessage];

                        $this->_validationStatus[$i]['status'] =
                           ($isWarning && $this->_validationStatus[$i]['status'] != ValidationManager::ERROR) ?
                               ValidationManager::WARNING :
                               ValidationManager::ERROR;

                       break;
                    }
                }
            }
        }

        return $result;
    }
}

?>