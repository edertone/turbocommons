/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */
package org.turbocommons.utils;


import java.util.Arrays;

import org.turbocommons.managers.ValidationManager;


/**
 * Utilities to perform common array operations
 */
public class ArrayUtils {

	
	/**
	 * Check if two provided arrays are identical
	 * 
	 * @param {array} array1 First array to compare
	 * @param {array} array2 Second array to compare
	 *
	 * @returns boolean true if arrays are exactly the same, false if not
	 */
	public static <T>Boolean isEqual(T[] array1, T[]  array2){
		
		ValidationManager validationManager = new ValidationManager();
		
		// Both provided values must be arrays or an exception will be launched
		//if(!validationManager.isArray(array1) || !validationManager.isArray(array2)){

		//	throw new Error("ArrayUtils.isEqual: Provided parameters must be arrays");
		//}

		// Compare lengths,can save a lot of time 
		//if(array1.length != array2.length){

		//	return false;
		//}
		
		return Arrays.equals(array1, array2);
	}
}
