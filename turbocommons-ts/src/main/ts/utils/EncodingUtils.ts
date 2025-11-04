/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


import { StringUtils } from "./StringUtils";


/**
 * Utilities related to string and text character encoding,
 * converting between formats, and perform common encoding operations.
 */
export class EncodingUtils {


    /**
     * Convert a string with unicode escaped sequence of characters (\u00ed, \u0110, ....) to an utf8 string.
     *
     * @param string A string containing unicode escaped characters.
     *
     * @returns An utf8 string conversion of the unicode encoded input.
     */
    static unicodeEscapedCharsToUtf8(string: string){

        if(StringUtils.isString(string)){

            return string.replace(/\\u([\d\w]{4})/gi, function (_match, grp) {
                
                return String.fromCharCode(parseInt(grp, 16)); 
            });
        }

        throw new Error('Specified value must be a string');
    }


    /**
     * Convert a utf8 string to a string with unicode escaped sequence of characters (\u00ed, \u0110, ...).
     *
     * @param string A string containing an utf8 valid sequence.
     *
     * @return A string containing escaped sequences for all the original utf8 characters
     */
    static utf8ToUnicodeEscapedChars(string: string){

        if(!StringUtils.isString(string)){

            throw new Error('Specified value must be a string');
        }

        if(StringUtils.isEmpty(string)){

            return string;
        }

        return string.replace(/[^\0-~]/g, function(ch) {
            
            return "\\u" + ("0000" + ch.charCodeAt(0).toString(16)).slice(-4);
        });
    }
}