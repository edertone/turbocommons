<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\test\php\model;

use Throwable;
use PHPUnit\Framework\TestCase;
use stdClass;
use org\turbocommons\src\main\php\model\JavaPropertiesObject;
use org\turbodepot\src\main\php\managers\FilesManager;


/**
 * JavaPropertiesObjectTest
 *
 * @return void
 */
class JavaPropertiesObjectTest extends TestCase {


    protected static $basePath;
    protected static $propertiesFiles;
    protected static $propertiesFilesData;


    /**
     * @see TestCase::setUpBeforeClass()
     *
     * @return void
     */
    public static function setUpBeforeClass(){

        self::$basePath = __DIR__.'/../../resources/model/javaPropertiesObject';

        // Load all the properties files data
        self::$propertiesFiles = [];
        self::$propertiesFilesData = [];

        $filesManager = new FilesManager();

        $filesList = $filesManager->getDirectoryList(self::$basePath);

        foreach ($filesList as $file) {

            self::$propertiesFiles[] = $file;
            self::$propertiesFilesData[] = $filesManager->readFile(self::$basePath.'/'.$file);
        }
    }


    /**
     * @see TestCase::setUp()
     *
     * @return void
     */
    protected function setUp(){

        $this->exceptionMessage = '';

        $this->wrongValues = [null, [], 'key', '=', '=key', '=key=', '=key=value', [1, 2], 1234, new stdclass()];
        $this->wrongValuesCount = count($this->wrongValues);
    }


    /**
     * @see TestCase::tearDown()
     *
     * @return void
     */
    protected function tearDown(){

        if($this->exceptionMessage != ''){

            $this->fail($this->exceptionMessage);
        }
    }


    /**
     * @see TestCase::tearDownAfterClass()
     *
     * @return void
     */
    public static function tearDownAfterClass(){

        // Nothing necessary here
    }


    /**
     * testConstruct
     *
     * @return void
     */
    public function testConstruct(){

        // Test empty values
        $sut = new JavaPropertiesObject();
        $this->assertTrue($sut->length() === 0);

        $sut = new JavaPropertiesObject('');
        $this->assertTrue($sut->length() === 0);

        try {
            new JavaPropertiesObject('       ');
            $this->exceptionMessage = '"        " value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            new JavaPropertiesObject("\n\n\n");
            $this->exceptionMessage = '"\n\n\n" value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test ok values
        $sut = new JavaPropertiesObject('name=Stephen');
        $this->assertTrue($sut->length() === 1);
        $this->assertTrue($sut->get('name') === 'Stephen');

        $sut = new JavaPropertiesObject('name = Stephen');
        $this->assertTrue($sut->length() === 1);
        $this->assertTrue($sut->get('name') === 'Stephen');

        $sut = new JavaPropertiesObject('name    =    Stephen');
        $this->assertTrue($sut->length() === 1);
        $this->assertTrue($sut->get('name') === 'Stephen');

        $sut = new JavaPropertiesObject('      name = Stephen');
        $this->assertTrue($sut->length() === 1);
        $this->assertTrue($sut->get('name') === 'Stephen');

        $sut = new JavaPropertiesObject('name=Stephen      ');
        $this->assertTrue($sut->length() === 1);
        $this->assertTrue($sut->get('name') === 'Stephen      ');

        $sut = new JavaPropertiesObject('path=c:\\\\docs\\\\doc1');
        $this->assertTrue($sut->length() === 1);
        $this->assertTrue($sut->get('path') === 'c:\\docs\\doc1');

        for ($i = 0; $i < count(self::$propertiesFiles); $i++) {

            $file = self::$propertiesFiles[$i];
            $fileData = self::$propertiesFilesData[$i];

            $sut = new JavaPropertiesObject($fileData);

            switch ($file) {

                case '1KeyWithValue.properties':
                    $this->assertTrue($sut->length() === 1);
                    $this->assertTrue($sut->get('keyname') === 'value');
                    break;

                case '2KeysWithValue.properties':
                    $this->assertTrue($sut->length() === 2);
                    $this->assertTrue($sut->get('keyname') === 'value');
                    $this->assertTrue($sut->get('keyname2') === 'value2');
                    break;

                case 'CommentsSlashesAndSpecialChars.properties':
                    $this->assertTrue($sut->length() === 5);
                    $this->assertTrue($sut->get('website') === 'http://en.wikipedia.org/');
                    $this->assertTrue($sut->get('language') === 'English');
                    $this->assertTrue($sut->get('message') === 'Welcome to Wikipedia!');
                    $this->assertTrue($sut->get('key with spaces') === 'This is the value that could be looked up with the key "key with spaces".');
                    $this->assertTrue($sut->get('tab') === "\t");
                    break;

                case 'LotsOfEmptySpacesEveryWhere.properties':
                    $this->assertSame(12, $sut->length());
                    $this->assertTrue($sut->get('k1') === '');
                    $this->assertTrue($sut->get('k2') === ' ');
                    $this->assertTrue($sut->get('k3') === '   ');
                    $this->assertTrue($sut->get('k4') === '   test');
                    $this->assertTrue($sut->get('k5') === '    test   ');
                    $this->assertTrue($sut->get('k6') === "   test  \r\ngo");
                    $this->assertTrue($sut->get('k7') === '  ');
                    $this->assertTrue($sut->get(' k8') === '8');
                    $this->assertTrue($sut->get('  k9') === '9');
                    $this->assertTrue($sut->get('  k10 ') === '10');
                    $this->assertTrue($sut->get('  k11  ') === '11');
                    $this->assertTrue($sut->get('  k12  ') === '12');
                    break;

                case 'LotsOfLatinKeysAndValues.properties':
                    $this->assertTrue($sut->length() === 45);
                    $this->assertTrue($sut->get('period.maintenance.InMillis') === '86400000');
                    $this->assertTrue($sut->get('decontamination.frequency.inDays') === '30');
                    $this->assertTrue($sut->get('decontamination.warningBeforehand.inDays') === '1');
                    $this->assertTrue($sut->get('technicalServiceInspection.frequency.inMonths') === '12');
                    $this->assertTrue($sut->get('technicalServiceInspection.warningBeforehand.inDays') === '7');
                    $this->assertTrue($sut->get('instrument.restartFrequency.inDays') === '7');
                    $this->assertTrue($sut->get('start.purgeFinishedTestsPriorToTheLast.inDays') === '7');
                    $this->assertTrue($sut->get('max.error.count') === '-1');
                    $this->assertTrue($sut->get('error.delimeter') === '(!)');
                    $this->assertTrue($sut->get('log.stdout') === 'N');
                    $this->assertTrue($sut->get('portalrolemembership.bb.controlled.fields') === '');
                    $this->assertTrue($sut->get('membership.datasource.key') === '');
                    $this->assertTrue($sut->get('reconcile') === 'Y');
                    break;

                case 'LotsOfScapedCharacters.properties':
                    $this->assertTrue($sut->length() === 6);
                    $this->assertTrue($sut->get('escaped key!=:# is __good') === 'And must work as "escaped key!=:# is __good"');
                    $this->assertTrue($sut->get('key with spaces') === "This line contains lots ' of \" special # characers \\\\!#'=.::sooo");
                    $this->assertTrue($sut->get('key\\with\\slashes') === 'value');
                    $this->assertTrue($sut->get('multiline.values') === "line 1\nline 2\nline 3\nline 4\\");
                    $this->assertTrue($sut->get('multiplebackslashes') === '\\\\\\value\\\\');
                    $this->assertTrue($sut->get('multiline.backslashes') === "value\n\n\\value");
                    break;

                case 'MidSizeInternationalizedFile7KeysLotsOfText.properties':
                    $this->assertTrue($sut->length() === 7);
                    $this->assertTrue($sut->get('featureName') === 'Spring Dashboard (optional)');
                    $this->assertTrue($sut->get('providerName') === 'Pivotal Software, Inc.');
                    $this->assertTrue($sut->get('updateSiteName') === 'Eclipse Integration Commons Updates');
                    $this->assertTrue($sut->get('description') === 'This feature provides the STS dashboard for displaying RSS feeds and the extensions page');
                    $this->assertTrue($sut->get('copyright') === 'Copyright (c) 2015, 2016 Pivotal Software, Inc.');
                    $this->assertTrue($sut->get('licenseUrl') === 'open_source_licenses.txt');
                    break;

                case 'MultipleKeysWithDifferentSpaces.properties':
                    $this->assertTrue($sut->length() === 4);
                    $this->assertTrue($sut->get('keyname') === 'value');
                    $this->assertTrue($sut->get('keyname2') === 'value2');
                    $this->assertTrue($sut->get('key3') === 'value3');
                    $this->assertTrue($sut->get('key4') === 'value4');
                    break;

                case 'VietnameseAndJapaneseCharacters.properties':
                    $this->assertTrue($sut->length() === 11);
                    $this->assertTrue($sut->get('Currency_Converter') === 'Chuyen doi tien te  ');
                    $this->assertTrue($sut->get('Enter_Amount') === 'Nhập vào số lượng  ');
                    $this->assertTrue($sut->get('Target_Currency') === 'Đơn vị chuyển  ');
                    $this->assertTrue($sut->get('Alert_Mess') === 'Vui lòng nhập một số hợp lệ  ');
                    $this->assertTrue($sut->get('Alert_Title') === 'Thong bao ');
                    $this->assertTrue($sut->get('SOME_CHINESE_TEXT') === '歾炂盵 溛滁溒 藡覶譒 蓪 顣飁, 殟 畟痄笊 鵁麍儱 螜褣 跬 蔏蔍蓪 荾莯袎 衋醾 骱 棰椻楒 鎈巂鞪 櫞氌瀙 磑禠, 扴汥 礛簼繰 荾莯袎 絟缾臮 跠, 獂猺 槶 鬎鯪鯠 隒雸頍 廘榙榾 歅毼毹 皾籈譧 艜薤 煔 峬峿峹 觛詏貁 蛣袹 馺, 凘墈 橀槶澉 儮嬼懫 諃 姛帡恦 嶕憱撏 磝磢 嘽, 妎岓岕 翣聜蒢 潧 娭屔 湹渵焲 艎艑蔉 絟缾臮 緅 婂崥, 萴葂 鞈頨頧 熿熼燛 暕');
                    $this->assertTrue($sut->get('SOME_JAPANESE_TEXT') === '氨䛧 ちゅレ゜頨褤つ 栨プ詞ゞ黨 禺驩へ, なか䤥楯ティ 䨺礨背㛤騟 嶥䰧ツェ餣しょ 查ぴゃ秺 む難 びゃきゃ す鏥䧺来禯 嶥䰧ツェ餣しょ チュ菣じゅ こ䥦杩 そく へが獣儥尤 みゃみ饯䥺愦 り簨と監綩, 夦鰥 う润フ ぱむ難夦鰥 栨プ詞ゞ黨 綩ぞ 苩䋧榧 え礥䏨嫧珣 こ䥦杩みょ奊');
                    $this->assertTrue($sut->get('SOME_JAPANESE_TEXT_WITH_MULTILINES') === "氨䛧 ちゅレ゜頨褤つ 栨プ\n\n詞ゞ黨 禺驩へ, なか䤥楯ティ 䨺礨背㛤騟 嶥䰧ツェ餣\nしょ 查ぴゃ秺 む難 びゃ\nきゃ ");
                    break;

                case 'BigFile-5000Lines.properties':
                    $this->assertTrue($sut->length() === 5000);
                    $this->assertTrue($sut->get('0') === 'value-0');
                    $this->assertTrue($sut->get('789') === 'value-789');
                    $this->assertTrue($sut->get('1240') === 'value-1240');
                    $this->assertTrue($sut->get('3450') === 'value-3450');
                    $this->assertTrue($sut->get('4999') === 'value-4999');
                    break;

                case 'BigFile-15000Lines.properties':
                    $this->assertTrue($sut->length() === 15000);
                    $this->assertTrue($sut->get('0') === 'value-0');
                    $this->assertTrue($sut->get('1789') === 'value-1789');
                    $this->assertTrue($sut->get('5240') === 'value-5240');
                    $this->assertTrue($sut->get('10450') === 'value-10450');
                    $this->assertTrue($sut->get('14999') === 'value-14999');
                    break;

                default:
                    $this->assertTrue(false, $file.' Was not tested');
                    break;
            }
        }

        // Test exceptions
        for ($i = 0; $i < $this->wrongValuesCount; $i++) {

            try {
                new JavaPropertiesObject($this->wrongValues[$i]);
                $this->exceptionMessage = 'wrong value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }
    }


    /**
     * testIsJavaProperties
     *
     * @return void
     */
    public function testIsJavaProperties(){

        $this->assertFalse(JavaPropertiesObject::isJavaProperties(null));
        $this->assertTrue(JavaPropertiesObject::isJavaProperties(''));
        $this->assertFalse(JavaPropertiesObject::isJavaProperties([]));
        $this->assertFalse(JavaPropertiesObject::isJavaProperties(new stdClass()));
        $this->assertFalse(JavaPropertiesObject::isJavaProperties('     '));
        $this->assertFalse(JavaPropertiesObject::isJavaProperties("\n\n\n"));
        $this->assertFalse(JavaPropertiesObject::isJavaProperties(0));

        $this->assertTrue(JavaPropertiesObject::isJavaProperties(new JavaPropertiesObject()));
        $this->assertTrue(JavaPropertiesObject::isJavaProperties(new JavaPropertiesObject('')));

        // Test ok values
        $this->assertTrue(JavaPropertiesObject::isJavaProperties('key='));
        $this->assertTrue(JavaPropertiesObject::isJavaProperties('key:'));
        $this->assertTrue(JavaPropertiesObject::isJavaProperties('key=value'));
        $this->assertTrue(JavaPropertiesObject::isJavaProperties('key:value'));

        for ($i = 0; $i < count(self::$propertiesFiles); $i++) {

            $file = self::$propertiesFiles[$i];
            $fileData = self::$propertiesFilesData[$i];

            $sut = new JavaPropertiesObject($fileData);
            $this->assertTrue(JavaPropertiesObject::isJavaProperties($fileData));
            $this->assertTrue(JavaPropertiesObject::isJavaProperties($sut));
        }

        // Test wrong values
        for ($i = 0; $i < $this->wrongValuesCount; $i++) {

            $this->assertFalse(JavaPropertiesObject::isJavaProperties($this->wrongValues[$i]));
        }

        // Test exceptions
        // Already tested at wrong values
    }


    /**
     * testIsEqualTo
     *
     * @return void
     */
    public function testIsEqualTo(){

        // Test empty values
        $sut = new JavaPropertiesObject();

        $this->assertTrue($sut->isEqualTo(''));
        $this->assertTrue($sut->isEqualTo(new JavaPropertiesObject()));

        try {
            $sut->isEqualTo(null);
            $this->exceptionMessage = 'null value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $sut->isEqualTo([]);
            $this->exceptionMessage = '[] value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $sut->isEqualTo(new stdClass());
            $this->exceptionMessage = 'new stdClass() value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $sut->isEqualTo(0);
            $this->exceptionMessage = '0 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test ok values
        for ($i = 0; $i < count(self::$propertiesFiles); $i++) {

            $file = self::$propertiesFiles[$i];
            $fileData = self::$propertiesFilesData[$i];

            $sut = new JavaPropertiesObject($fileData);

            // TODO - This is added for performance reasons. If performance is improved on
            // isEqualTo method, this constraint can be removed
            if($sut->length() < 1000){

                $this->assertTrue($sut->isEqualTo($fileData));
                $this->assertTrue($sut->isEqualTo($sut));
            }
        }

        // Test wrong values
        $sut = new JavaPropertiesObject();

        for ($i = 0; $i < $this->wrongValuesCount; $i++) {

            try {
                $sut->isEqualTo($this->wrongValues[$i]);
                $this->exceptionMessage = $this->wrongValues[$i].' wrong value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        $sut = new JavaPropertiesObject('key1=v1');
        $this->assertFalse($sut->isEqualTo('key2=v1'));

        $sut = new JavaPropertiesObject('key1=v1');
        $this->assertFalse($sut->isEqualTo('key1=v2'));

        $sut = new JavaPropertiesObject('key1=v1');
        $this->assertFalse($sut->isEqualTo("key1=v1\nkey2=v2"));

        // Test exceptions
        // Already tested at wrong values
    }


    /**
     * testToString
     *
     * @return void
     */
    public function testToString(){

        // Test empty values
        $sut = new JavaPropertiesObject();
        $this->assertTrue($sut->toString() === '');

        $sut = new JavaPropertiesObject('');
        $this->assertTrue($sut->toString() === '');

        // Test ok values
        for ($i = 0; $i < count(self::$propertiesFiles); $i++) {

            $file = self::$propertiesFiles[$i];
            $fileData = self::$propertiesFilesData[$i];

            $sut = new JavaPropertiesObject($fileData);

            // TODO - This is added for performance reasons. If performance is improved on
            // isEqualTo method, this constraint can be removed
            if($sut->length() < 1000){

                $this->assertTrue($sut->isEqualTo($sut->toString(), true), $file.' has a problem');
            }
        }

        // Test wrong values
        // Already tested at constructor test

        // Test exceptions
        // Already tested at constructor test
    }
}

?>