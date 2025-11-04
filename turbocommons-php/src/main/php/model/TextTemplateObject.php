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

use UnexpectedValueException;
use org\turbocommons\src\main\php\utils\StringUtils;


/**
 * Text template abstraction
 */
class TextTemplateObject{


    /**
     * Stores the template text with all the applied replacements
     *
     * @var string
     */
    private $_text = '';


    /**
     * This object represents a predefined string that contains one or more keywords that need to be replaced at runtime.
     * It contains all the most common operations that will help us with the replacement and get a ready to use result.
     *
     * This kind of template object has lots of uses inside an application, but for example we could use it to send emails with
     * predefined texts where some parts must be customized (like the receiver name or username, etc..)
     *
     * @param string $text A string containing the template to be replaced
     *
     * @return TextTemplateObject The constructed TextTemplateObject
     */
    public function __construct($text){

        if(!StringUtils::isString($text)){

            throw new UnexpectedValueException('TextTemplateObject->constructor expected a string value');
        }

        $this->_text = $text;
    }


    /**
     * Replaces all the keyword occurences on the current template text with the specified value
     *
     * @param string $keyword A value to find on the template text that will be replaced
     * @param string $replacement A value that will replace all the occurences of the specified keyword
     */
    public function replace(string $keyword, string $replacement){

        $this->_text = str_replace($keyword, $replacement, $this->_text);
    }


    /**
     * TODO
     */
    public function getText(){

        return $this->_text;
    }
}

?>