/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */
package org.turbocommons.utils;

import static org.junit.Assert.*;

import org.junit.Test;

public class ArrayUtilsTest {

	@Test
	public void testIsEqual() {
		
		assertTrue(true);
		
		// Test non array values must launch exception
//		assert.throws(function(){
//
//			utils.ArrayUtils.isEqual(null, null);
//		});
//
//		assert.throws(function(){
//
//			utils.ArrayUtils.isEqual(1, 1);
//		});
//
//		assert.throws(function(){
//
//			utils.ArrayUtils.isEqual("asfasf1", "345345");
//		});
//
//		// Test identic arrays
//		assert.ok(utils.ArrayUtils.isEqual([null], [null]));
//		assert.ok(utils.ArrayUtils.isEqual([], []));
//		assert.ok(utils.ArrayUtils.isEqual([[]], [[]]));
//		assert.ok(utils.ArrayUtils.isEqual([[1]], [[1]]));
//		assert.ok(utils.ArrayUtils.isEqual([1, 2, 3], [1, 2, 3]));
//		assert.ok(utils.ArrayUtils.isEqual([1, 2, 1, 2], [1, 2, 1, 2]));
//		assert.ok(utils.ArrayUtils.isEqual([1, 2, [3, 4]], [1, 2, [3, 4]]));
//		assert.ok(utils.ArrayUtils.isEqual(["hello world"], ["hello world"]));
//
//		// Test different arrays
//		assert.ok(!utils.ArrayUtils.isEqual([null], []));
//		assert.ok(!utils.ArrayUtils.isEqual([1], ["1"]));
//		assert.ok(!utils.ArrayUtils.isEqual([1, 2, 3], [1, 3, 2]));
//		assert.ok(!utils.ArrayUtils.isEqual([1, "2,3"], [1, 2, 3]));
//		assert.ok(!utils.ArrayUtils.isEqual([1, 2, [3, 4]], [1, 2, [3, 2]]));
//		assert.ok(!utils.ArrayUtils.isEqual([1, 2, [3, [4]]], [1, 2, [3, ["4"]]]));
//		assert.ok(!utils.ArrayUtils.isEqual(["hello world"], ["hello worl1d"]));
//
//		// Two diferent object instances are not equal, even if they have same key and values
//		assert.ok(!utils.ArrayUtils.isEqual([{
//			a : 1
//		}], [{
//			a : 1
//		}]));
	}

}
