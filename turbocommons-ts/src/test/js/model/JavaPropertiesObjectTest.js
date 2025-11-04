"use strict";

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


QUnit.module("JavaPropertiesObjectTest", {
    
    before: function(assert) {

        window.StringUtils = org_turbocommons.StringUtils;
        window.JavaPropertiesObject = org_turbocommons.JavaPropertiesObject;
        
        window.basePath = './resources/model/javaPropertiesObject/';

        var httpManager = new org_turbocommons.HTTPManager();
        
        // Load all the properties files
        var done = assert.async();
        
        httpManager.loadResourcesFromList(basePath + '_folder-list.txt', basePath,
            function(resourcesList, resourcesData){
        
                window.propertiesFiles = resourcesList;
                window.propertiesFilesData = resourcesData;
                done();
                
            }, function(errorUrl, errorMsg, errorCode){
                
                assert.ok(false, 'Error loading file ' + errorUrl);
                done();
            });
    },
    
    beforeEach : function(){

        window.wrongValues = [null, [], 'key', '=', '=key', '=key=', '=key=value', [1, 2], 1234, {}];
        window.wrongValuesCount = window.wrongValues.length; 
    },

    afterEach : function(){
        
        delete window.wrongValues;
        delete window.wrongValuesCount;
    },
    
    after : function(){

        delete window.StringUtils;
        delete window.JavaPropertiesObject;

        delete window.basePath;
        
        delete window.propertiesFiles;
        delete window.propertiesFilesData;
    }
});


/**
 * testConstruct
 */
QUnit.test("testConstruct", function(assert){

    // Test empty values
    var sut = new JavaPropertiesObject();
    assert.ok(sut.length() === 0);

    sut = new JavaPropertiesObject('');
    assert.ok(sut.length() === 0);

    assert.throws(function() {
        new JavaPropertiesObject('       ');
    }, /invalid properties format/);

    assert.throws(function() {
        new JavaPropertiesObject("\n\n\n");
    }, /invalid properties format/);

    // Test ok values
    sut = new JavaPropertiesObject('name=Stephen');
    assert.ok(sut.length() === 1);
    assert.ok(sut.get('name') === 'Stephen');

    sut = new JavaPropertiesObject('name = Stephen');
    assert.ok(sut.length() === 1);
    assert.ok(sut.get('name') === 'Stephen');

    sut = new JavaPropertiesObject('name    =    Stephen');
    assert.ok(sut.length() === 1);
    assert.ok(sut.get('name') === 'Stephen');

    sut = new JavaPropertiesObject('      name = Stephen');
    assert.ok(sut.length() === 1);
    assert.ok(sut.get('name') === 'Stephen');

    sut = new JavaPropertiesObject('name=Stephen      ');
    assert.ok(sut.length() === 1);
    assert.ok(sut.get('name') === 'Stephen      ');

    sut = new JavaPropertiesObject('path=c:\\\\docs\\\\doc1');
    assert.ok(sut.length() === 1);
    assert.ok(sut.get('path') === 'c:\\docs\\doc1');

    for (var i = 0; i < propertiesFiles.length; i++) {

        var file = propertiesFiles[i];
        var fileData = propertiesFilesData[i];

        sut = new JavaPropertiesObject(fileData);

        switch (file) {

            case '1KeyWithValue.properties':
                assert.ok(sut.length() === 1);
                assert.ok(sut.get('keyname') === 'value');
                break;

            case '2KeysWithValue.properties':
                assert.ok(sut.length() === 2);
                assert.ok(sut.get('keyname') === 'value');
                assert.ok(sut.get('keyname2') === 'value2');
                break;

            case 'CommentsSlashesAndSpecialChars.properties':
                assert.ok(sut.length() === 5);
                assert.ok(sut.get('website') === 'http://en.wikipedia.org/');
                assert.ok(sut.get('language') === 'English');
                assert.ok(sut.get('message') === 'Welcome to Wikipedia!');
                assert.ok(sut.get('key with spaces') === 'This is the value that could be looked up with the key "key with spaces".');
                assert.ok(sut.get('tab') === "\t");
                break;

            case 'LotsOfEmptySpacesEveryWhere.properties':
                assert.strictEqual(12, sut.length());
                assert.ok(sut.get('k1') === '');
                assert.ok(sut.get('k2') === ' ');
                assert.ok(sut.get('k3') === '   ');
                assert.ok(sut.get('k4') === '   test');
                assert.ok(sut.get('k5') === '    test   ');
                assert.ok(sut.get('k6') === "   test  \r\ngo");
                assert.ok(sut.get('k7') === '  ');
                assert.ok(sut.get(' k8') === '8');
                assert.ok(sut.get('  k9') === '9');
                assert.ok(sut.get('  k10 ') === '10');
                assert.ok(sut.get('  k11  ') === '11');
                assert.ok(sut.get('  k12  ') === '12');
                break;

            case 'LotsOfLatinKeysAndValues.properties':
                assert.ok(sut.length() === 45);
                assert.ok(sut.get('period.maintenance.InMillis') === '86400000');
                assert.ok(sut.get('decontamination.frequency.inDays') === '30');
                assert.ok(sut.get('decontamination.warningBeforehand.inDays') === '1');
                assert.ok(sut.get('technicalServiceInspection.frequency.inMonths') === '12');
                assert.ok(sut.get('technicalServiceInspection.warningBeforehand.inDays') === '7');
                assert.ok(sut.get('instrument.restartFrequency.inDays') === '7');
                assert.ok(sut.get('start.purgeFinishedTestsPriorToTheLast.inDays') === '7');
                assert.ok(sut.get('max.error.count') === '-1');
                assert.ok(sut.get('error.delimeter') === '(!)');
                assert.ok(sut.get('log.stdout') === 'N');
                assert.ok(sut.get('portalrolemembership.bb.controlled.fields') === '');
                assert.ok(sut.get('membership.datasource.key') === '');
                assert.ok(sut.get('reconcile') === 'Y');
                break;

            case 'LotsOfScapedCharacters.properties':
                assert.ok(sut.length() === 6);
                assert.ok(sut.get('escaped key!=:# is __good') === 'And must work as "escaped key!=:# is __good"');
                assert.ok(sut.get('key with spaces') === "This line contains lots ' of \" special # characers \\\\!#'=.::sooo");
                assert.ok(sut.get('key\\with\\slashes') === 'value');
                assert.ok(sut.get('multiline.values') === "line 1\nline 2\nline 3\nline 4\\");
                assert.ok(sut.get('multiplebackslashes') === '\\\\\\value\\\\');
                assert.ok(sut.get('multiline.backslashes') === "value\n\n\\value");
                break;

            case 'MidSizeInternationalizedFile7KeysLotsOfText.properties':
                assert.ok(sut.length() === 7);
                assert.ok(sut.get('featureName') === 'Spring Dashboard (optional)');
                assert.ok(sut.get('providerName') === 'Pivotal Software, Inc.');
                assert.ok(sut.get('updateSiteName') === 'Eclipse Integration Commons Updates');
                assert.ok(sut.get('description') === 'This feature provides the STS dashboard for displaying RSS feeds and the extensions page');
                assert.ok(sut.get('copyright') === 'Copyright (c) 2015, 2016 Pivotal Software, Inc.');
                assert.ok(sut.get('licenseUrl') === 'open_source_licenses.txt');
                break;

            case 'MultipleKeysWithDifferentSpaces.properties':
                assert.ok(sut.length() === 4);
                assert.ok(sut.get('keyname') === 'value');
                assert.ok(sut.get('keyname2') === 'value2');
                assert.ok(sut.get('key3') === 'value3');
                assert.ok(sut.get('key4') === 'value4');
                break;

            case 'VietnameseAndJapaneseCharacters.properties':
                assert.ok(sut.length() === 11);
                assert.ok(sut.get('Currency_Converter') === 'Chuyen doi tien te  ');
                assert.ok(sut.get('Enter_Amount') === 'Nhập vào số lượng  ');
                assert.ok(sut.get('Target_Currency') === 'Đơn vị chuyển  ');
                assert.ok(sut.get('Alert_Mess') === 'Vui lòng nhập một số hợp lệ  ');
                assert.ok(sut.get('Alert_Title') === 'Thong bao ');
                assert.ok(sut.get('SOME_CHINESE_TEXT') === '歾炂盵 溛滁溒 藡覶譒 蓪 顣飁, 殟 畟痄笊 鵁麍儱 螜褣 跬 蔏蔍蓪 荾莯袎 衋醾 骱 棰椻楒 鎈巂鞪 櫞氌瀙 磑禠, 扴汥 礛簼繰 荾莯袎 絟缾臮 跠, 獂猺 槶 鬎鯪鯠 隒雸頍 廘榙榾 歅毼毹 皾籈譧 艜薤 煔 峬峿峹 觛詏貁 蛣袹 馺, 凘墈 橀槶澉 儮嬼懫 諃 姛帡恦 嶕憱撏 磝磢 嘽, 妎岓岕 翣聜蒢 潧 娭屔 湹渵焲 艎艑蔉 絟缾臮 緅 婂崥, 萴葂 鞈頨頧 熿熼燛 暕');
                assert.ok(sut.get('SOME_JAPANESE_TEXT') === '氨䛧 ちゅレ゜頨褤つ 栨プ詞ゞ黨 禺驩へ, なか䤥楯ティ 䨺礨背㛤騟 嶥䰧ツェ餣しょ 查ぴゃ秺 む難 びゃきゃ す鏥䧺来禯 嶥䰧ツェ餣しょ チュ菣じゅ こ䥦杩 そく へが獣儥尤 みゃみ饯䥺愦 り簨と監綩, 夦鰥 う润フ ぱむ難夦鰥 栨プ詞ゞ黨 綩ぞ 苩䋧榧 え礥䏨嫧珣 こ䥦杩みょ奊');
                assert.ok(sut.get('SOME_JAPANESE_TEXT_WITH_MULTILINES') === "氨䛧 ちゅレ゜頨褤つ 栨プ\n\n詞ゞ黨 禺驩へ, なか䤥楯ティ 䨺礨背㛤騟 嶥䰧ツェ餣\nしょ 查ぴゃ秺 む難 びゃ\nきゃ ");
                break;

            case 'BigFile-5000Lines.properties':
                assert.ok(sut.length() === 5000);
                assert.ok(sut.get('0') === 'value-0');
                assert.ok(sut.get('789') === 'value-789');
                assert.ok(sut.get('1240') === 'value-1240');
                assert.ok(sut.get('3450') === 'value-3450');
                assert.ok(sut.get('4999') === 'value-4999');
                break;

            case 'BigFile-15000Lines.properties':
                assert.ok(sut.length() === 15000);
                assert.ok(sut.get('0') === 'value-0');
                assert.ok(sut.get('1789') === 'value-1789');
                assert.ok(sut.get('5240') === 'value-5240');
                assert.ok(sut.get('10450') === 'value-10450');
                assert.ok(sut.get('14999') === 'value-14999');
                break;

            default:
                assert.ok(false, file + ' Was not tested');
                break;
        }
    }

    // Test exceptions
    for (var i = 0; i < wrongValuesCount; i++) {

        assert.throws(function() {
            new JavaPropertiesObject(wrongValues[i]);
        }, /value must be a string|invalid properties format/);
    }
});


/**
 * testIsJavaProperties
 */
QUnit.test("testIsJavaProperties", function(assert){

    assert.notOk(JavaPropertiesObject.isJavaProperties(null));
    assert.ok(JavaPropertiesObject.isJavaProperties(''));
    assert.notOk(JavaPropertiesObject.isJavaProperties([]));
    assert.notOk(JavaPropertiesObject.isJavaProperties({}));
    assert.notOk(JavaPropertiesObject.isJavaProperties('     '));
    assert.notOk(JavaPropertiesObject.isJavaProperties("\n\n\n"));
    assert.notOk(JavaPropertiesObject.isJavaProperties(0));

    assert.ok(JavaPropertiesObject.isJavaProperties(new JavaPropertiesObject()));
    assert.ok(JavaPropertiesObject.isJavaProperties(new JavaPropertiesObject('')));

    // Test ok values
    assert.ok(JavaPropertiesObject.isJavaProperties('key='));
    assert.ok(JavaPropertiesObject.isJavaProperties('key:'));
    assert.ok(JavaPropertiesObject.isJavaProperties('key=value'));
    assert.ok(JavaPropertiesObject.isJavaProperties('key:value'));

    for (var i = 0; i < propertiesFiles.length; i++) {

        var file = propertiesFiles[i];
        var fileData = propertiesFilesData[i];

        var sut = new JavaPropertiesObject(fileData);
        assert.ok(JavaPropertiesObject.isJavaProperties(fileData));
        assert.ok(JavaPropertiesObject.isJavaProperties(sut));
    }

    // Test wrong values
    for (var i = 0; i < wrongValuesCount; i++) {

        assert.notOk(JavaPropertiesObject.isJavaProperties(wrongValues[i]));
    }

    // Test exceptions
    // Already tested at wrong values
});


/**
 * testIsEqualTo
 */
QUnit.test("testIsEqualTo", function(assert){

    // Test empty values
    var sut = new JavaPropertiesObject();

    assert.ok(sut.isEqualTo(''));
    assert.ok(sut.isEqualTo(new JavaPropertiesObject()));

    assert.throws(function() {
        sut.isEqualTo(null);
    }, /properties does not contain valid java properties data/);

    assert.throws(function() {
        sut.isEqualTo([]);
    }, /properties does not contain valid java properties data/);

    assert.throws(function() {
        sut.isEqualTo({});
    }, /properties does not contain valid java properties data/);

    assert.throws(function() {
        sut.isEqualTo(0);
    }, /properties does not contain valid java properties data/);

    // Test ok values
    for (var i = 0; i < propertiesFiles.length; i++) {

        var file = propertiesFiles[i];
        var fileData = propertiesFilesData[i];

        sut = new JavaPropertiesObject(fileData);

        // TODO - This is added for performance reasons. If performance is improved on
        // isEqualTo method, this constraint can be removed
        if(sut.length() < 1000){

            assert.ok(sut.isEqualTo(fileData));
            assert.ok(sut.isEqualTo(sut));
        }
    }

    // Test wrong values
    sut = new JavaPropertiesObject();

    for (var i = 0; i < wrongValuesCount; i++) {

        assert.throws(function() {
            sut.isEqualTo(wrongValues[i]);
        }, /properties does not contain valid java properties data/);
    }

    sut = new JavaPropertiesObject('key1=v1');
    assert.notOk(sut.isEqualTo('key2=v1'));

    sut = new JavaPropertiesObject('key1=v1');
    assert.notOk(sut.isEqualTo('key1=v2'));

    sut = new JavaPropertiesObject('key1=v1');
    assert.notOk(sut.isEqualTo("key1=v1\nkey2=v2"));

    // Test exceptions
    // Already tested at wrong values
});


/**
 * testToString
 */
QUnit.test("testToString", function(assert){

    // Test empty values
    var sut = new JavaPropertiesObject();
    assert.ok(sut.toString() === '');

    sut = new JavaPropertiesObject('');
    assert.ok(sut.toString() === '');

    // Test ok values
    for (var i = 0; i < propertiesFiles.length; i++) {

        var file = propertiesFiles[i];
        var fileData = propertiesFilesData[i];

        sut = new JavaPropertiesObject(fileData);

        // TODO - This is added for performance reasons. If performance is improved on
        // isEqualTo method, this constraint can be removed
        if(sut.length() < 1000){

            assert.ok(sut.isEqualTo(sut.toString(), true), file + ' has a problem');
        }
    }

    // Test wrong values
    // Already tested at constructor test

    // Test exceptions
    // Already tested at constructor test
});