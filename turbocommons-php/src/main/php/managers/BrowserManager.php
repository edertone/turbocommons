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

use UnexpectedValueException;
use org\turbocommons\src\main\php\model\BaseStrictClass;
use org\turbocommons\src\main\php\utils\NumericUtils;
use org\turbocommons\src\main\php\utils\StringUtils;


/**
 * An abstraction of the browser entity an all its related operations and properties
 * Browser entity is normally available only on client side or front end view applications,
 * but some of its features can also make sense on a server side app. So depending on the
 * implementation language, this class may or may not have some of its methods implemented.
 */
class BrowserManager extends BaseStrictClass{


    /**
     * Get the current page full url, including 'https://', domain and any uri get parameters
     *
     * @return string A well formed url
     */
    public function getCurrentUrl(){

        return (isset($_SERVER['HTTPS']) ? "https" : "http").'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }


    /**
     * Check if the specified cookie exists
     *
     * @param string $key the name for the cookie we want to find
     *
     * @returns boolean True if cookie with specified name exists, false otherwise
     */
    public function isCookie(string $key){

        return isset($_COOKIE[$key]);
    }


    /**
     * Set the value for a cookie or create it if not exist
     *
     * Adapted from the jquery.cookie plugin by Klaus Hartl: https://github.com/carhartl/jquery-cookie
     *
     * @param string $key the name for the cookie we want to create
     * @param string $value the value we want to set to the new cookie.
     * @param mixed $expires The lifetime of the cookie. Value can be a `Number` which will be interpreted as days from time of creation or a `Date` object. If omitted or '' string, the cookie becomes a session cookie.
     * @param string $path Define the path where the cookie is valid. By default it is the whole domain: '/'. A specific path can be passed (/ca/Home/) or a '' string to set it as the current site http path.
     * @param string $domain Define the domain where the cookie is valid. Default: domain of page where the cookie was created.
     * @param boolean $secure If true, the cookie transmission requires a secure protocol (https). Default: false.
     *
     * @returns boolean True if cookie was created, false otherwise. An exception may be thrown if invalid parameters are specified
     */
    public function setCookie(string $key, string $value, $expires = '', $path = "/", $domain = '', $secure = false){

        // Empty key means an exception
        if(!StringUtils::isString($key) || StringUtils::isEmpty($key)){

            throw new UnexpectedValueException("key must be defined");
        }

        // Empty values mean cookie will be created empty
        if($value === null){

            $value = '';
        }

        // Reaching here, non string value means an exception
        if(!StringUtils::isString($value)){

            throw new UnexpectedValueException("value must be a string");
        }

        // If the expires parameter is numeric, we will generate the correct date value
        if(NumericUtils::isNumeric($expires)){

            $expires = time() + $expires * 86400;
        }

        // This is a trick to make sure the cookie value is inmediately available. When setting a cookie,
        // we won't be able to read it till the next page reload, so if we want to get its defined value
        // later on the current script we need to directly set it on the $_COOKIE global data.
        $_COOKIE[$key] = $value;

        return setcookie($key, $value, $expires, '/');
    }


    /**
     * Get the value for an existing cookie.
     *
     * @param string $key the name of the cookie we want to get
     *
     * @returns string Cookie value or null if cookie does not exist
     */
    public function getCookie(string $key){

        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : '';
    }


    /**
     * Deletes the specified cookie from browser. Note that the cookie will only be deleted if belongs to the same path as specified.
     *
     * @param string $key The name of the cookie we want to delete
     * @param string $path Define the path where the cookie is set. By default it is the whole domain: '/'. If the cookie is not set on this path, we must pass the right one or the delete will fail.
     *
     * @returns boolean True if cookie was deleted or false if cookie could not be deleted or was not found.
     */
    public function deleteCookie(string $key, string $path = '/'){

        if(isset($_COOKIE[$key])){

            setcookie($key, '', null, $path);
            return true;
        }

        return false;
    }


    /**
     * TODO
     */
    public function isCurrentUrlWithQuery(){

        // TODO
    }


    /**
     * TODO
     */
    public function getCurrentUrlQueryValues(){

        // TODO
    }


    /**
     * Tries to detect the language that is set as preferred by the user on the current browser.
     * NOTE: Getting browser language is not accurate. It is always better to use server side language detection
     *
     * @returns A two digits string containing the detected browser language. For example 'es', 'en', ...
     */
    public function getPreferredLanguage(){

        $result = '';

        if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){

            $result = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
            $result = strtolower(substr(trim($result[0]), 0, 2));
        }

        return $result;
    }
}

?>