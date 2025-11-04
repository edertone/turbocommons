<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


// Register the autoload method that will locate and automatically load the library classes
spl_autoload_register(function($className){

    // Replace all slashes to the correct OS directory separator
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, str_replace('/', DIRECTORY_SEPARATOR, $className));

    // Remove unwanted classname path parts
    $classPath = explode('src'.DIRECTORY_SEPARATOR.'main'.DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR, $classPath);
    $classPath = array_pop($classPath).'.php';

    if(file_exists(__DIR__.DIRECTORY_SEPARATOR.$classPath)){

        require_once __DIR__.DIRECTORY_SEPARATOR.$classPath;
    }
});

?>