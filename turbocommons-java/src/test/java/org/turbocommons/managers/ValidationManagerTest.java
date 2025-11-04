/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */
package org.turbocommons.managers;

import static org.junit.Assert.*;

import java.util.Optional;

import org.junit.Test;

public class ValidationManagerTest {

	@Test
	public void testIsTrue() {
		
		ValidationManager validationManager = new ValidationManager();
		
		assertTrue(validationManager.isTrue(true, Optional.ofNullable(null), Optional.ofNullable(null)));
		assertTrue(validationManager.validationStatus == ValidationManager.VALIDATION_OK);
		
		// TODO - add all missing tests
	}

	
	// TODO - add all missing tests
}
