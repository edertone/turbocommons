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

use Exception;
use Throwable;
use PHPUnit\Framework\TestCase;
use stdClass;
use org\turbodepot\src\main\php\managers\FilesManager;
use org\turbocommons\src\main\php\model\CSVObject;
use org\turbocommons\src\main\php\utils\StringUtils;
use org\turbocommons\src\main\php\model\JavaPropertiesObject;
use org\turbocommons\src\main\php\utils\NumericUtils;


/**
 * CSVObjectTest
 *
 * @return void
 */
class CSVObjectTest extends TestCase {


    protected static $basePath;
    protected static $csvFiles;
    protected static $csvFilesData;
    protected static $propertiesFiles;
    protected static $propertiesFilesData;


    /**
     * @see TestCase::setUpBeforeClass()
     *
     * @return void
     */
    public static function setUpBeforeClass(){

        self::$basePath = __DIR__.'/../../resources/model/csvObject';

        // Load all the csv and properties files data
        self::$csvFiles = [];
        self::$csvFilesData = [];
        self::$propertiesFiles = [];
        self::$propertiesFilesData = [];

        $filesManager = new FilesManager();

        $filesList = $filesManager->getDirectoryList(self::$basePath);

        foreach ($filesList as $file) {

            if(StringUtils::getPathExtension($file) === 'csv'){

                self::$csvFiles[] = $file;
                self::$csvFilesData[] = $filesManager->readFile(self::$basePath.'/'.$file);
            }

            if(StringUtils::getPathExtension($file) === 'properties'){

                self::$propertiesFiles[] = $file;
                self::$propertiesFilesData[] = $filesManager->readFile(self::$basePath.'/'.$file);
            }
        }
    }


    /**
     * @see TestCase::setUp()
     *
     * @return void
     */
    protected function setUp(){

        $this->exceptionMessage = '';

        $this->emptyValues = [null, [], new stdClass(), 0];
        $this->emptyValuesCount = count($this->emptyValues);

        $this->wrongValues = [123, [1, 2, 3], ['asdf'], new Exception()];
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
        $sut = new CSVObject();
        $this->assertSame(0, $sut->countColumns());
        $this->assertSame(0, $sut->countRows());

        $sut = new CSVObject('');
        $this->assertSame(0, $sut->countColumns());
        $this->assertSame(0, $sut->countRows());

        $sut = new CSVObject('     ');
        $this->assertSame(0, $sut->countColumns());
        $this->assertSame(0, $sut->countRows());

        $sut = new CSVObject("\n\n\n");
        $this->assertSame(0, $sut->countColumns());
        $this->assertSame(0, $sut->countRows());

        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                new CSVObject($this->emptyValues[$i]);
                $this->exceptionMessage = $this->emptyValues[$i].' empty value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        // Test ok values

        // Single value csv
        $sut = new CSVObject('value');
        $this->assertSame('value', $sut->getCell(0, 0));
        $this->assertSame(1, $sut->countRows());
        $this->assertSame(1, $sut->countColumns());
        $this->assertSame('value', $sut->getCell(0, 0));
        $this->assertTrue($sut->isEqualTo('value'));

        // Simple one row empty csv
        $sut = new CSVObject(',"",');
        $this->assertSame('', $sut->getCell(0, 0));
        $this->assertSame('', $sut->getCell(0, 1));
        $this->assertSame('', $sut->getCell(0, 2));
        $this->assertTrue($sut->isEqualTo(',,'));

        // Simple one row empty csv with headers
        $sut = new CSVObject("c1,c2,c3\r\n,,", true);
        $this->assertSame('', $sut->getCell(0, 'c1'));
        $this->assertSame('', $sut->getCell(0, 'c2'));
        $this->assertSame('', $sut->getCell(0, 'c3'));
        $this->assertSame('', $sut->getCell(0, 0));
        $this->assertSame('', $sut->getCell(0, 1));
        $this->assertSame('', $sut->getCell(0, 2));
        $this->assertTrue($sut->isEqualTo("c1,\"c2\",c3\n,,"));

        // Simple one row csv without headers
        $sut = new CSVObject('a,b,c');
        $this->assertSame('a', $sut->getCell(0, 0));
        $this->assertSame('b', $sut->getCell(0, 1));
        $this->assertSame('c', $sut->getCell(0, 2));
        $this->assertTrue($sut->isEqualTo('a,b,c'));

        // Simple one row csv with headers
        $sut = new CSVObject("c1,c2,c3\n1,2,3", true);
        $this->assertSame('1', $sut->getCell(0, 'c1'));
        $this->assertSame('2', $sut->getCell(0, 'c2'));
        $this->assertSame('3', $sut->getCell(0, 'c3'));
        $this->assertSame('1', $sut->getCell(0, 0));
        $this->assertSame('2', $sut->getCell(0, 1));
        $this->assertSame('3', $sut->getCell(0, 2));
        $this->assertTrue($sut->isEqualTo("c1,c2,c3\r\n1,2,3"));

        // Simple one row csv without headers and scaped fields
        $sut = new CSVObject('"a","b","c"');
        $this->assertSame('a', $sut->getCell(0, 0));
        $this->assertSame('b', $sut->getCell(0, 1));
        $this->assertSame('c', $sut->getCell(0, 2));
        $this->assertTrue($sut->isEqualTo('a,b,c'));

        // Simple one row csv with headers and scaped fields
        $sut = new CSVObject("c1,c2,c3\r\"a\",\"b\",\"c\"", true);
        $this->assertSame('a', $sut->getCell(0, 'c1'));
        $this->assertSame('b', $sut->getCell(0, 'c2'));
        $this->assertSame('c', $sut->getCell(0, 'c3'));
        $this->assertSame('a', $sut->getCell(0, 0));
        $this->assertSame('b', $sut->getCell(0, 1));
        $this->assertSame('c', $sut->getCell(0, 2));
        $this->assertTrue($sut->isEqualTo("c1,c2,c3\na,b,c"));

        // Simple csv without headers and edge cases
        $sut = new CSVObject(' a ,b  ,c  ');
        $this->assertSame(' a ', $sut->getCell(0, 0));
        $this->assertSame('b  ', $sut->getCell(0, 1));
        $this->assertSame('c  ', $sut->getCell(0, 2));
        $this->assertTrue($sut->isEqualTo(' a ,"b  ",c  '));

        // Multiple lines csv with different newline characters (windows: \r\n, Linux/Unix: \n, Mac: \r)
        $sut = new CSVObject("1,2,3\na,b,c\r\n4,5,6\r");
        $this->assertSame('1', $sut->getCell(0, 0));
        $this->assertSame('2', $sut->getCell(0, 1));
        $this->assertSame('3', $sut->getCell(0, 2));
        $this->assertSame('a', $sut->getCell(1, 0));
        $this->assertSame('b', $sut->getCell(1, 1));
        $this->assertSame('c', $sut->getCell(1, 2));
        $this->assertSame('4', $sut->getCell(2, 0));
        $this->assertSame('5', $sut->getCell(2, 1));
        $this->assertSame('6', $sut->getCell(2, 2));
        $this->assertTrue($sut->isEqualTo("1,2,3\na,b,c\r\r4,5,6\r\n"));
        $this->assertTrue($sut->countColumns() === 3);
        $this->assertTrue($sut->countRows() === 3);

        // Simple csv without headers and scaped fields and characters with edge cases
        $sut = new CSVObject(' """"" 1",",,,2",    "3", "4,"   ,  "5 " ');
        $this->assertSame('"" 1', $sut->getCell(0, 0));
        $this->assertSame(',,,2', $sut->getCell(0, 1));
        $this->assertSame('3', $sut->getCell(0, 2));
        $this->assertSame('4,', $sut->getCell(0, 3));
        $this->assertSame('5 ', $sut->getCell(0, 4));
        $this->assertTrue($sut->isEqualTo('""""" 1",",,,2","3","4,","5 "'));

        // Simple two row csv without headers and scaped fields and characters
        $sut = new CSVObject("\"1\",\"2\",\"3\"\r\n\"a\"\"a\",\"b\",\"c\"");
        $this->assertSame('1', $sut->getCell(0, 0));
        $this->assertSame('2', $sut->getCell(0, 1));
        $this->assertSame('3', $sut->getCell(0, 2));
        $this->assertSame('a"a', $sut->getCell(1, 0));
        $this->assertSame('b', $sut->getCell(1, 1));
        $this->assertSame('c', $sut->getCell(1, 2));
        $this->assertTrue($sut->isEqualTo("\"1\",\"2\",\"3\"\r\n\"a\"\"a\",\"b\",\"c\""));

        // Simple two row csv with headers and mixed scaped and non scaped fields and characters
        $sut = new CSVObject("c1,\"c,\"\"2\",c3\r1,\"2\", 3 \r\n\"a \"\",a\",b,\"c\"", true);
        $this->assertSame(['c1', 'c,"2', 'c3'], $sut->getColumnNames());
        $this->assertSame('1', $sut->getCell(0, 'c1'));
        $this->assertSame('2', $sut->getCell(0, 'c,"2'));
        $this->assertSame(' 3 ', $sut->getCell(0, 'c3'));
        $this->assertSame('1', $sut->getCell(0, 0));
        $this->assertSame('2', $sut->getCell(0, 1));
        $this->assertSame(' 3 ', $sut->getCell(0, 2));
        $this->assertSame('a ",a', $sut->getCell(1, 'c1'));
        $this->assertSame('b', $sut->getCell(1, 'c,"2'));
        $this->assertSame('c', $sut->getCell(1, 'c3'));
        $this->assertSame('a ",a', $sut->getCell(1, 0));
        $this->assertSame('b', $sut->getCell(1, 1));
        $this->assertSame('c', $sut->getCell(1, 2));
        $this->assertTrue($sut->isEqualTo("c1,\"c,\"\"2\",c3\r1,\"2\", 3 \r\n\"a \"\",a\",b,\"c\""));

        // test csv files from resources
        // Assertion expected values are stored on a properties file for each one of the csv files.
        // It must have exactly the same name but with .properties extension
        for($i = 0; $i < count(self::$csvFiles); $i++){

            $csvFileName = self::$csvFiles[$i];
            $csvFileData = self::$csvFilesData[$i];

            $sut = new CSVObject($csvFileData, StringUtils::countStringOccurences($csvFileName, 'WithHeader') === 1);

            $propertiesFileName = StringUtils::getPathElementWithoutExt($csvFileName).'.properties';
            $csvFileAssertions = new JavaPropertiesObject(self::$propertiesFilesData[array_search($propertiesFileName, self::$propertiesFiles)]);

            $this->assertSame((int)$csvFileAssertions->get('rows'), $sut->countRows(), 'File: '.$csvFileName);
            $this->assertSame((int)$csvFileAssertions->get('cols'), $sut->countColumns(), 'File: '.$csvFileName);

            foreach ($csvFileAssertions->getKeys() as $key) {

                if($key !== 'rows' && $key !== 'cols'){

                    $rowCol = explode('-', $key, 2);

                    $columnFormatted = NumericUtils::isNumeric($rowCol[1]) ? (int)$rowCol[1] : $rowCol[1];

                    $expected = $csvFileAssertions->get($key);
                    $value = $sut->getCell((int)$rowCol[0], $columnFormatted);

                    $this->assertSame($expected, $value, 'File: '.$csvFileName.' row and col: '.$key);
                }
            }
        }

        // Test wrong values
        for ($i = 0; $i < $this->wrongValuesCount; $i++) {

            try {
                new CSVObject($this->wrongValues[$i]);
                $this->exceptionMessage = $this->wrongValues[$i].' wrong value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        // Test exceptions
        // Already tested
    }


    /**
     * testSetCell
     *
     * @return void
     */
    public function testSetCell(){

        // Test empty values
        $sut = new CSVObject();
        $sut->addColumns(5);
        $sut->addRows(5);

        $this->assertTrue($sut->getCell(0, 0) === '');
        $this->assertTrue($sut->setCell(0, 0, '') === '');
        $this->assertTrue($sut->getCell(0, 0) === '');

        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                $sut->setCell(0, 0, $this->emptyValues[$i]);
                $this->exceptionMessage = $this->emptyValues[$i].' empty value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        // Test ok values
        $this->assertTrue($sut->getCell(0, 2) === '');
        $this->assertTrue($sut->setCell(0, 2, 'somevalue') === 'somevalue');
        $this->assertTrue($sut->getCell(0, 2) === 'somevalue');

        $this->assertTrue($sut->getCell(0, 4) === '');
        $this->assertTrue($sut->setCell(0, 4, 'somevalue4') === 'somevalue4');
        $this->assertTrue($sut->getCell(0, 4) === 'somevalue4');

        $this->assertTrue($sut->getCell(2, 0) === '');
        $this->assertTrue($sut->setCell(2, 0, '2-0') === '2-0');
        $this->assertTrue($sut->getCell(2, 0) === '2-0');

        $this->assertTrue($sut->getCell(2, 2) === '');
        $this->assertTrue($sut->setCell(2, 2, '2-2') === '2-2');
        $this->assertTrue($sut->getCell(2, 2) === '2-2');

        $this->assertTrue($sut->getCell(4, 4) === '');
        $this->assertTrue($sut->setCell(4, 4, '4-4') === '4-4');
        $this->assertTrue($sut->getCell(4, 4) === '4-4');

        // Test wrong values
        try {
            $sut->setCell(-1, 0, '');
            $this->exceptionMessage = '-1,0 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $sut->setCell(10, 0, '');
            $this->exceptionMessage = '10,0 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $sut->setCell(0, -1, '');
            $this->exceptionMessage = '0,-1 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $sut->setCell(0, 10, '');
            $this->exceptionMessage = '0,10 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $sut->setCell(0, 0, 10);
            $this->exceptionMessage = '10 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $sut->setCell(0, 0, new stdClass());
            $this->exceptionMessage = 'new stdClass() value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test exceptions
        // Already tested
    }


    /**
     * testIsCSV
     *
     * @return void
     */
    public function testIsCSV(){

        // Test empty values
        $this->assertFalse(CSVObject::isCSV(null));
        $this->assertTrue(CSVObject::isCSV(''));
        $this->assertFalse(CSVObject::isCSV(0));
        $this->assertFalse(CSVObject::isCSV([]));
        $this->assertFalse(CSVObject::isCSV(new stdClass()));
        $this->assertTrue(CSVObject::isCSV('     '));
        $this->assertTrue(CSVObject::isCSV("\n\n\n"));

        // Test ok values
        $this->assertTrue(CSVObject::isCSV('value'));
        $this->assertTrue(CSVObject::isCSV(',,'));
        $this->assertTrue(CSVObject::isCSV("c1,c2,c3\r\n,,"));
        $this->assertTrue(CSVObject::isCSV('a,b,c'));
        $this->assertTrue(CSVObject::isCSV("c1,c2,c3\n1,2,3"));
        $this->assertTrue(CSVObject::isCSV('"a","b","c"'));
        $this->assertTrue(CSVObject::isCSV("c1,c2,c3\r\"a\",\"b\",\"c\""));
        $this->assertTrue(CSVObject::isCSV(' a ,b  ,c  '));
        $this->assertTrue(CSVObject::isCSV("1,2,3\na,b,c\r\n4,5,6\r"));
        $this->assertTrue(CSVObject::isCSV(' """"" 1",",,,2",    "3", "4,"   ,  "5 " '));
        $this->assertTrue(CSVObject::isCSV("\"1\",\"2\",\"3\"\r\n\"a\"\"a\",\"b\",\"c\""));
        $this->assertTrue(CSVObject::isCSV("c1,\"c,\"\"2\",c3\r1,\"2\", 3 \r\n\"a \"\",a\",b,\"c\""));

        for ($i = 0; $i < count(self::$csvFiles); $i++) {

            $this->assertTrue(CSVObject::isCSV(self::$csvFilesData[$i]));
        }

        // Test wrong values
        $this->assertFalse(CSVObject::isCSV(12));
        $this->assertFalse(CSVObject::isCSV([1,4,5,6]));
        $this->assertFalse(CSVObject::isCSV(['  ']));
        $this->assertFalse(CSVObject::isCSV(new Exception()));
        $this->assertFalse(CSVObject::isCSV(-1909));

        // Test exceptions
        // Not necessary
    }


    /**
     * testIsEqualTo
     *
     * @return void
     */
    public function testIsEqualTo(){

        // Test empty values
        $sut = new CSVObject();

        $this->assertTrue($sut->isEqualTo(''));
        $this->assertTrue($sut->isEqualTo(new CSVObject()));

        try {
            $sut->isEqualTo(null);
            $this->exceptionMessage = 'null value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $sut->isEqualTo([]);
            $exceptionMessage = '[] value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $sut->isEqualTo(new stdClass());
            $exceptionMessage = 'new stdClass() value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $sut->isEqualTo(0);
            $exceptionMessage = '0 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test ok and wrong values
        for ($i = 1; $i < count(self::$csvFiles); $i++) {

            $fileData = '';

            if($i == 1){

                $previousFileData = self::$csvFilesData[$i-1];
                $previousSut = new CSVObject($previousFileData, StringUtils::countStringOccurences(self::$csvFiles[$i-1], 'WithHeader') === 1);

            }else{

                $previousFileData = $fileData;
                $previousSut = $sut;
            }

            $fileData = self::$csvFilesData[$i];
            $sut = new CSVObject($fileData, StringUtils::countStringOccurences(self::$csvFiles[$i], 'WithHeader') === 1);

            // TODO - This is added for performance reasons. If performance is improved on
            // isEqualTo method, this constraint can be removed
            if($sut->countRows() < 1000 && $previousSut->countRows() < 1000){

                $this->assertTrue($sut->isEqualTo($fileData));
                $this->assertTrue($sut->isEqualTo($sut));

                $this->assertFalse($sut->isEqualTo($previousFileData));
                $this->assertFalse($sut->isEqualTo($previousSut));
            }
        }

        // Test exceptions
        try {
            $sut->isEqualTo(123234);
            $exceptionMessage = '123234 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $sut->isEqualTo([1,'dfgdfg']);
            $exceptionMessage = '[1,"dfgdfg"] value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $sut->isEqualTo(new Exception());
            $exceptionMessage = 'new Exception() value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }
    }


    /**
     * testToString
     *
     * @return void
     */
    public function testToString(){

        // Test empty values
        $sut = new CSVObject();
        $this->assertTrue($sut->toString() === '');

        $sut = new CSVObject('');
        $this->assertTrue($sut->toString() === '');

        $sut = new CSVObject('      ');
        $this->assertTrue($sut->toString() === '');

        $sut = new CSVObject("\n\n\n\n");
        $this->assertTrue($sut->toString() === '');

        $sut = new CSVObject("\r\n\r\n\r\n\r\n");
        $this->assertTrue($sut->toString() === '');

        // Test ok values

        // Single value csv
        $sut = new CSVObject('value');
        $this->assertSame('value', $sut->toString());

        // Simple one row empty csv
        $sut = new CSVObject(',,');
        $this->assertSame(',,', $sut->toString());

        // Simple one row empty csv with headers
        $sut = new CSVObject("c1,c2,c3\r\n,,", true);
        $this->assertSame("c1,c2,c3\r\n,,", $sut->toString());

        // Simple one row csv without headers
        $sut = new CSVObject('a,b,c');
        $this->assertSame('a,b,c', $sut->toString());

        // Simple one row csv with headers
        $sut = new CSVObject("c1,c2,c3\n1,2,3", true);
        $this->assertSame("c1,c2,c3\r\n1,2,3", $sut->toString());

        // Simple one row csv without headers and scaped fields
        $sut = new CSVObject('"a","b","c"');
        $this->assertSame('a,b,c', $sut->toString());

        // Simple one row csv with headers and scaped fields
        $sut = new CSVObject("c1,c2,c3\r\"a\",\"b\",\"c\"", true);
        $this->assertSame("c1,c2,c3\r\na,b,c", $sut->toString());

        // Simple csv without headers and edge cases
        $sut = new CSVObject(' a ,b  ,c  ');
        $this->assertSame(' a ,b  ,c  ', $sut->toString());

        // Multiple lines csv with different newline characters (windows: \r\n, Linux/Unix: \n, Mac: \r)
        $sut = new CSVObject("1,2,3\na,b,c\r\n4,5,6\r");
        $this->assertSame("1,2,3\r\na,b,c\r\n4,5,6", $sut->toString());

        // Simple csv without headers and scaped fields and characters with edge cases
        $sut = new CSVObject(' """"" 1",",,,2",    "3", "4,"   ,  "5 " ');
        $this->assertSame('""""" 1",",,,2",3,"4,",5 ', $sut->toString());

        // Simple two row csv without headers and scaped fields and characters
        $sut = new CSVObject("\"1\",\"2\",\"3\"\r\n\"a\"\"a\",\"b\",\"c\"");
        $this->assertSame("1,2,3\r\n\"a\"\"a\",b,c", $sut->toString());

        // Simple two row csv with headers and mixed scaped and non scaped fields and characters
        $sut = new CSVObject("c1,\"c,\"\"2\",c3\r1,\"2\", 3 \r\n\"a \"\",a\",b,\"c\"", true);
        $this->assertSame("c1,\"c,\"\"2\",c3\r\n1,2, 3 \r\n\"a \"\",a\",b,c", $sut->toString());

        for ($i = 0; $i < count(self::$csvFiles); $i++) {

            $sut = new CSVObject(self::$csvFilesData[$i], StringUtils::countStringOccurences(self::$csvFiles[$i], 'WithHeader') === 1);

            // TODO - This is added for performance reasons. If performance is improved on
            // isEqualTo method, this constraint can be removed
            if($sut->countRows() < 1000){

                $this->assertTrue($sut->isEqualTo($sut->toString()), self::$csvFiles[$i].' has a problem');
                $this->assertTrue($sut->isEqualTo($sut), self::$csvFiles[$i].' has a problem');
            }
        }

        // Test wrong values
        // Already tested at constructor test

        // Test exceptions
        // Already tested at constructor test
    }

}

?>