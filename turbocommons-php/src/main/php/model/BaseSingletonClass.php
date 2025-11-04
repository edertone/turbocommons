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


/**
 * A base class that implements the singleton pattern for PHP and can be extended to convert a class to a singleton object
 */
abstract class BaseSingletonClass extends BaseStrictClass{


    /**
     * Contains all the singleton global instances (for all classes that extend this one).
     * In php we must do it this way to avoid singletons from returning wrong class objects.
     */
    private static $_instances = [];


    /**
     * Returns the Singleton instance of this class.
     *
     * @return self The Singleton instance.
     */
    public static function getInstance(){

        $class = get_called_class();

        if(!isset(self::$_instances[$class])) {

            self::$_instances[$class] = new $class();
        }

        return self::$_instances[$class];
    }


    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct(){

    }


    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone(){

    }


    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup(){

    }
}

?>