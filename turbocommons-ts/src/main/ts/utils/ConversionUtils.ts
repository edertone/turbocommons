/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


import { StringUtils } from './StringUtils';


/**
 * The most common conversion utilities to convert the data from a simple type to another one.<br>
 * To convert complex classes or structures, use SerializationUtils class.
 * 
 * <pre><code> 
 * This is a static class, so no instance needs to be created.
 * Usage example:
 * 
 * var ns = org_turbocommons_utils;
 * 
 * var result1 = ns.ConversionUtils.stringToBase64('hello');
 * var result2 = ns.ConversionUtils.base64ToString('somebase64text');
 * ...
 * </code></pre>
 */
export class ConversionUtils {


    /**
     * Encode a string to base64 (Which contains only numbers, uppercase, lowercase, forward slash, plus and equal signs) 
     * Found at: http://www.webtoolkit.info/
     *           http://www.webtoolkit.info/javascript-base64.html#.VO3gzjSG9AY
     *  
     * @param {string} string The input string to be converted
     * @returns {string} The input string as base 64 
     */
    public static stringToBase64(string:string):string{

        if(string === null || string === undefined){

            return '';
        }

        if(!StringUtils.isString(string)){

            throw new Error("ConversionUtils.stringToBase64: value is not a string");
        }

        var keyStr:string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';

        var chr1:number;
        var chr2:number;
        var chr3:number;
        var enc1:number;
        var enc2:number;
        var enc3:number;
        var enc4:number;
        var output:string = '';
        
        // Auxiliary method to encode a string as utf 8
        function utf8Encode(string:string){

            var utftext:string = '';
            
            string = string.replace(/\r\n/g, "\n");

            for(var n:number = 0; n < string.length; n++){

                var c:number = string.charCodeAt(n);

                if(c < 128){

                    utftext += String.fromCharCode(c);

                }else if((c > 127) && (c < 2048)){

                    utftext += String.fromCharCode((c >> 6) | 192);
                    utftext += String.fromCharCode((c & 63) | 128);

                }else{
                    utftext += String.fromCharCode((c >> 12) | 224);
                    utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                    utftext += String.fromCharCode((c & 63) | 128);
                }
            }

            return utftext;
        }

        string = utf8Encode(string);

        var i:number = 0;
        
        while(i < string.length){

            chr1 = string.charCodeAt(i++);
            chr2 = string.charCodeAt(i++);
            chr3 = string.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if(isNaN(chr2)){

                enc3 = enc4 = 64;

            }else if(isNaN(chr3)){

                enc4 = 64;
            }

            output = output + keyStr.charAt(enc1) + keyStr.charAt(enc2) + keyStr.charAt(enc3) + keyStr.charAt(enc4);
        }

        return output;
    }


    /**
     * Decode a string from base64 (Which contains only numbers, uppercase, lowercase, forward slash, plus and equal signs)
     * Found at: http://www.webtoolkit.info/
     *           http://www.webtoolkit.info/javascript-base64.html#.VO3gzjSG9AY
     *
     * @param {string} string a base64 string
     * 
     * @returns {string} The base64 decoded as its original string
     */
    public static base64ToString(string:string):string{

        if(string === null || string === undefined){

            return '';
        }

        if(!StringUtils.isString(string)){

            throw new Error('ConversionUtils.stringToBase64: value is not a string');
        }
        
        var keyStr:string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
        
        var chr1:number;
        var chr2:number;
        var chr3:number;
        var enc1:number;
        var enc2:number;
        var enc3:number;
        var enc4:number;
        var output:string = '';

        var i:number = 0;

        string = string.replace(/[^A-Za-z0-9\+\/\=]/g, '');

        while(i < string.length){

            enc1 = keyStr.indexOf(string.charAt(i++));
            enc2 = keyStr.indexOf(string.charAt(i++));
            enc3 = keyStr.indexOf(string.charAt(i++));
            enc4 = keyStr.indexOf(string.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;

            output = output + String.fromCharCode(chr1);

            if(enc3 != 64){

                output = output + String.fromCharCode(chr2);
            }

            if(enc4 != 64){

                output = output + String.fromCharCode(chr3);
            }
        }

        // Auxiliary method for utf8 decoding
        function utf8Decode(utftext:string):string{

            var string:string = '';
            var i:number = 0;
            var c:number = 0;
            var c2:number = 0;

            while(i < utftext.length){

                c = utftext.charCodeAt(i);

                if(c < 128){

                    string += String.fromCharCode(c);
                    i++;

                }else if((c > 191) && (c < 224)){

                    c2 = utftext.charCodeAt(i + 1);
                    string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                    i += 2;

                }else{

                    c2 = utftext.charCodeAt(i + 1);
                    var c3 = utftext.charCodeAt(i + 2);
                    string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                    i += 3;
                }
            }

            return string;
        }

        return utf8Decode(output);
    }
}