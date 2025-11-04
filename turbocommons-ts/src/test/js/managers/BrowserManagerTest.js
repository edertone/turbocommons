"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


QUnit.module("BrowserManagerTest", {
    beforeEach : function() {

        window.StringUtils = org_turbocommons.StringUtils;
        window.NumericUtils = org_turbocommons.NumericUtils;
        window.sut = new org_turbocommons.BrowserManager();
    },

    afterEach : function() {

        delete window.StringUtils;
        delete window.NumericUtils;
        delete window.sut;
    }
});


/**
 * getCurrentUrl
 */
QUnit.test("getCurrentUrl", function(assert){
    
    assert.ok(StringUtils.isUrl(sut.getCurrentUrl()));
});


/**
 * isDocumentLoaded
 */
QUnit.test("isDocumentLoaded", function(assert){
    
    assert.ok(sut.isDocumentLoaded());
});


/**
 * isCookie
 */
QUnit.test("isCookie", function(assert){
    
    // Define some cookies
    assert.ok(sut.setCookie('1', 'a'));
    assert.ok(sut.setCookie('2', 'b'));
    assert.ok(sut.setCookie('3', 'c'));
    assert.ok(sut.setCookie('4', 'd'));
    
    // Verify cookies do exist
    assert.ok(sut.isCookie('1'));
    assert.ok(sut.isCookie('2'));
    assert.ok(sut.isCookie('3'));
    assert.ok(sut.isCookie('4'));
    
    // Delete created cookies
    assert.ok(sut.deleteCookie('1'));
    assert.ok(sut.deleteCookie('2'));
    assert.ok(sut.deleteCookie('3'));
    assert.ok(sut.deleteCookie('4'));
});


/**
 * setCookie
 */
QUnit.test("setCookie", function(assert){

    // Test several empty value combinations
    assert.throws(function(){

        sut.setCookie(undefined, 'value');
    });

    assert.throws(function(){

        sut.setCookie(null, 'value');
    });

    assert.throws(function(){

        sut.setCookie('', 'value');
    });

    assert.throws(function(){

        sut.setCookie('user1', []);
    });

    assert.throws(function(){

        sut.setCookie('user1', sut);
    });

    // Verify cookies do not exist
    assert.ok(!sut.isCookie('user0'));
    assert.ok(!sut.isCookie('user1'));
    assert.ok(!sut.isCookie('user2'));
    assert.ok(!sut.isCookie('user3'));

    // Define some cookies
    assert.ok(sut.setCookie('user1'));
    assert.ok(sut.setCookie('user1', undefined));
    assert.ok(sut.setCookie('user1', null));
    assert.ok(sut.setCookie('user2', '       ', 200));
    assert.ok(sut.setCookie('user3', 'value 3'));

    // Get values
    assert.ok(sut.getCookie('user0') === undefined);
    assert.ok(sut.getCookie('user1') === '');
    assert.ok(sut.getCookie('user2') === '       ');
    assert.ok(sut.getCookie('user3') === 'value 3');

    // Verify expiration is correct


    // Delete created cookies
    assert.ok(sut.deleteCookie('user0') === false);
    assert.ok(sut.deleteCookie('user1'));
    assert.ok(sut.deleteCookie('user2'));
    assert.ok(sut.deleteCookie('user3'));

    // Verify cookies do not exist
    assert.ok(sut.getCookie('user0') === undefined);
    assert.ok(sut.getCookie('user1') === undefined);
    assert.ok(sut.getCookie('user2') === undefined);
    assert.ok(sut.getCookie('user3') === undefined);
});


/**
 * getCookie
 */
QUnit.test("getCookie", function(assert){

    // Test several empty value combinations
    assert.throws(function(){

        sut.getCookie(null);
    });

    assert.throws(function(){

        sut.getCookie(undefined);
    });

    assert.throws(function(){

        sut.getCookie('');
    });

    // Verify cookies do not exist
    assert.ok(!sut.isCookie('user1'));
    assert.ok(!sut.isCookie('user2'));
    assert.ok(!sut.isCookie('user3'));

    // Define some cookies
    assert.ok(sut.setCookie('user1', null));
    assert.ok(sut.setCookie('user2', ''));
    assert.ok(sut.setCookie('user3', 'value 3'));

    // Get values
    assert.ok(sut.getCookie('user1') === '');
    assert.ok(sut.getCookie('user2') === '');
    assert.ok(sut.getCookie('user3') === 'value 3');

    // Modify value for a cookie and test that it has changed
    assert.ok(sut.setCookie('user3', 'new value now'));
    assert.ok(sut.getCookie('user3') === 'new value now');

    // Delete created cookies
    assert.ok(sut.deleteCookie('user1'));
    assert.ok(sut.deleteCookie('user2'));
    assert.ok(sut.deleteCookie('user3'));

    // Verify cookies do not exist
    assert.ok(!sut.isCookie('user1'));
    assert.ok(!sut.isCookie('user2'));
    assert.ok(!sut.isCookie('user3'));
});


/**
 * deleteCookie
 */
QUnit.test("deleteCookie", function(assert){

    // Test several empty value combinations
    assert.throws(function(){

        sut.deleteCookie(null);
    });

    assert.throws(function(){

        sut.deleteCookie(undefined);
    });

    assert.throws(function(){

        sut.deleteCookie('');
    });

    // Verify cookie do not exist
    assert.ok(!sut.isCookie('user1'));

    // Define a cookie
    assert.ok(sut.setCookie('user1', 'go to the cookies'));

    // Verify cookie exists
    assert.ok(sut.isCookie('user1'));

    // Delete cookie
    assert.ok(sut.deleteCookie('user1'));

    // Verify deleted
    assert.ok(sut.getCookie('user1') === undefined);
});


/**
 * reload
 */
// Can't be tested cause it calls location.reload()


/**
 * getPreferredLanguage
 */
QUnit.test("getPreferredLanguage", function(assert){
    
    assert.ok(sut.getPreferredLanguage().length === 2);
});


/**
 * goToUrl
 */
//Can't be tested cause it calls window.location.href


/**
 * disableScroll
 */
//Can't be tested cause it involves visual behaviours


/**
 * enableScroll
 */
//Can't be tested cause it involves visual behaviours


/**
 * getScrollPosition
 */
QUnit.test("getScrollPosition", function(assert){
    
    var pos = sut.getScrollPosition();
    
    assert.ok(NumericUtils.isNumeric(pos[0]));
    assert.ok(NumericUtils.isNumeric(pos[1]));
    assert.ok(pos.length === 2);
});


/**
 * getWindowWidth
 */
QUnit.test("getWindowWidth", function(assert){
    
    assert.ok(sut.getWindowWidth() > 0);
});


/**
 * getWindowHeight
 */
QUnit.test("getWindowHeight", function(assert){
    
    assert.ok(sut.getWindowHeight() > 0);
});


/**
 * getDocumentWidth
 */
QUnit.test("getDocumentWidth", function(assert){
    
    assert.ok(sut.getDocumentWidth() > 0);
});


/**
 * getDocumentHeight
 */
QUnit.test("getDocumentHeight", function(assert){
    
    assert.ok(sut.getDocumentHeight() > 0);
});


/**
 * scrollTo
 */
//Can't be tested cause it involves visual behaviours


/**
 * copyToClipboard
 */
//Can't be tested cause it requires user permision to get the clipboard contents