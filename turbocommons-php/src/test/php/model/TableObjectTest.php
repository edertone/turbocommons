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

use PHPUnit\Framework\TestCase;
use Throwable;
use stdClass;
use org\turbocommons\src\main\php\model\TableObject;
use org\turbocommons\src\main\php\utils\NumericUtils;


/**
 * TableObjectTest
 *
 * @return void
 */
class TableObjectTest extends TestCase {


    /**
     * @see TestCase::setUpBeforeClass()
     *
     * @return void
     */
    public static function setUpBeforeClass(){

        // Nothing necessary here
    }


    /**
     * @see TestCase::setUp()
     *
     * @return void
     */
    protected function setUp(){

        $this->exceptionMessage = '';

        $this->emptyValues = [null, '', [], new stdClass(), '     ', "\n\n\n"];
        $this->emptyValuesCount = count($this->emptyValues);
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
        $test = new TableObject();
        $this->assertTrue($test->countCells() === 0);
        $this->assertTrue($test->countRows() === 0);
        $this->assertTrue($test->countColumns() === 0);

        $test = new TableObject(0, 0);
        $this->assertTrue($test->countCells() === 0);
        $this->assertTrue($test->countRows() === 0);
        $this->assertTrue($test->countColumns() === 0);

        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            for ($j = 0; $j < $this->emptyValuesCount; $j++) {

                try {
                    new TableObject($this->emptyValues[$i], $this->emptyValues[$j]);
                    $this->exceptionMessage = 'empty value did not cause exception';
                } catch (Throwable $e) {
                    // We expect an exception to happen
                }
            }
        }

        // Test ok values
        for ($i = 1; $i < 5000; $i+=100) {

            for ($j = 1; $j < 5000; $j+=100) {

                $test = new TableObject($i, $j);
                $this->assertEquals($i * $j, $test->countCells());
                $this->assertEquals($i, $test->countRows());
                $this->assertEquals($j, $test->countColumns());
            }
        }

        $test = new TableObject(2, ['c1', 'c2', 'c3']);
        $this->assertTrue($test->countCells() === 6);
        $this->assertTrue($test->countRows() === 2);
        $this->assertTrue($test->countColumns() === 3);

        // Test wrong values
        try {
            new TableObject(0, NumericUtils::generateRandomInteger(10000000));
            $this->exceptionMessage = '0,N value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            new TableObject(NumericUtils::generateRandomInteger(10000000), 0);
            $this->exceptionMessage = 'N,0 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            new TableObject(NumericUtils::generateRandomInteger(10000000) * -1, NumericUtils::generateRandomInteger(10000000) * -1);
            $this->exceptionMessage = 'negative values did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            new TableObject('hello', 'hello');
            $this->exceptionMessage = 'hello did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            new TableObject([], []);
            $this->exceptionMessage = '[] values did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            new TableObject(NumericUtils::generateRandomInteger(10000000) * -1, NumericUtils::generateRandomInteger(10000000));
            $this->exceptionMessage = 'neg pos values did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            new TableObject(NumericUtils::generateRandomInteger(10000000), NumericUtils::generateRandomInteger(10000000) * -1);
            $this->exceptionMessage = 'pos neg values did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test exceptions
        // Tested with wrong values
    }

    /**
     * testSetColumnName
     *
     * @return void
     */
    public function testSetColumnName(){

        // Test empty values
        $test = new TableObject(10, 10);

        $this->assertTrue($test->setColumnName(0, ''));
        $this->assertTrue($test->setColumnName(0, '    '));
        $this->assertTrue($test->setColumnName(0, "\n\n\n"));

        $this->emptyValues = [null, [], new stdClass()];
        $this->emptyValuesCount = count($this->emptyValues);

        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            for ($j = 0; $j < 10; $j++) {

                try {
                    $test->setColumnName($j, $this->emptyValues[$i]);
                    $this->exceptionMessage = 'empty value did not cause exception';
                } catch (Throwable $e) {
                    // We expect an exception to happen
                }
            }
        }

        // Test ok values
        $test = new TableObject(1, 1);
        $this->assertTrue($test->setColumnName(0, 'column1'));
        $this->assertEquals([null], $test->getColumn(0));
        $this->assertTrue($test->getColumn(0) === $test->getColumn('column1'));
        $this->assertTrue($test->getColumnName(0) === 'column1');
        $this->assertTrue($test->getColumnIndex('column1') === 0);

        $test = new TableObject(20, 20);
        $this->assertTrue($test->setColumnName(11, 'column11'));
        $this->assertTrue($test->getColumn(11) === $test->getColumn('column11'));
        $this->assertTrue($test->getColumnName(11) === 'column11');
        $this->assertTrue($test->getColumnIndex('column11') === 11);

        $this->assertTrue($test->setColumnName(12, 'column12'));
        $this->assertTrue($test->setColumnName(11, 'renamed'));
        $this->assertTrue($test->getColumnName(12) === 'column12');
        $this->assertTrue($test->getColumnName(11) === 'renamed');

        $this->assertTrue($test->setColumnName('renamed', 're-renamed'));
        $this->assertTrue($test->getColumnName(11) === 're-renamed');

        $this->assertTrue($test->getColumnName(1) === '');
        $this->assertTrue($test->getColumnName(19) === '');
        $this->assertTrue($test->setColumnName(11, ''));
        $this->assertTrue($test->setColumnName(12, '   '));
        $this->assertTrue($test->setColumnName(13, "\n\n"));
        $this->assertTrue($test->getColumnName(11) === '');
        $this->assertTrue($test->getColumnName(12) === '   ');
        $this->assertTrue($test->getColumnName(13) === "\n\n");

        // Test wrong values
        $this->wrongValues = [null, [], new stdClass(), -1, 50];
        $this->wrongValuesCount = count($this->wrongValues);

        for ($i = 0; $i < $this->wrongValuesCount; $i++) {

            try {
                $test->setColumnName($this->wrongValues[$i], 'name');
                $this->exceptionMessage = 'empty value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        for ($i = 0; $i < $this->wrongValuesCount; $i++) {

            try {
                $test->setColumnName(1, $this->wrongValues[$i]);
                $this->exceptionMessage = 'empty second parameter value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        try {
            $test->setColumnName(40, 'name');
            $this->exceptionMessage = '40 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test exceptions
        // Already tested
    }


    /**
     * testSetColumnNames
     *
     * @return void
     */
    public function testSetColumnNames(){

        // Test empty values
        $test = new TableObject(10, 10);

        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                $test->setColumnNames($this->emptyValues[$i]);
                $this->exceptionMessage = 'empty value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        // Test ok values
        $test = new TableObject(5, 3);
        $this->assertEquals(['', '  ', "\n\n\n"], $test->setColumnNames(['', '  ', "\n\n\n"]));
        $this->assertTrue($test->getColumnNames() === ['', '  ', "\n\n\n"]);

        for ($i = 1; $i < 50; $i+=10) {

            for ($j = 1; $j < 50; $j+=10) {

                $test = new TableObject($i, $j);

                $columns = [];

                for ($k = 0; $k < $j; $k++) {

                    $columns[] = 'column'.$k;
                }

                $this->assertEquals($columns, $test->setColumnNames($columns));
                $this->assertTrue($test->getColumnNames() === $columns);
            }
        }

        // Test wrong values
        $test = new TableObject(0, 0);

        try {
            $test->setColumnNames(['column1']);
            $this->exceptionMessage = '["column1"] value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        $test = new TableObject(2, 2);

        try {
            $test->setColumnNames(['column', 'column']);
            $this->exceptionMessage = '["column", "column"] array with duplicate values did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->setColumnNames(['column', 1]);
            $this->exceptionMessage = '["column", 1] array with non string value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test setting wrong number of column names
        for ($i = 1; $i < 10; $i++) {

            for ($j = 1; $j < 10; $j++) {

                $test = new TableObject($i, $j);

                $columns = [];

                for ($k = 0; $k < ($j * 2); $k++) {

                    $columns[] = 'column'.$k;
                }

                try {
                    $test->setColumnNames($columns);
                    $this->exceptionMessage = '$columns value did not cause exception';
                } catch (Throwable $e) {
                    // We expect an exception to happen
                }
            }
        }

        // Test exceptions
        // Tested with wrong values
    }


    /**
     * testGetColumnNames
     *
     * @return void
     */
    public function testGetColumnNames(){

        // Test empty values
        $test = new TableObject();
        $this->assertTrue($test->getColumnNames() === []);

        // Test ok values
        $test = new TableObject(1, 1);
        $this->assertTrue($test->getColumnNames() === ['']);
        $test->setColumnNames(['column1']);
        $this->assertTrue($test->getColumnNames() === ['column1']);

        $test = new TableObject(10, 4);
        $this->assertTrue($test->getColumnNames() === ['', '', '', '']);
        $test->setColumnNames(['column1', 'column2', 'column3', 'column4']);
        $this->assertTrue($test->getColumnNames() === ['column1', 'column2', 'column3', 'column4']);

        $test = new TableObject(10, 4);
        $this->assertTrue($test->getColumnNames() === ['', '', '', '']);
        $test->setColumnName(0, 'col0');
        $this->assertEquals(['col0', '', '', ''], $test->getColumnNames());

        $test = new TableObject(10, 4);
        $this->assertTrue($test->getColumnNames() === ['', '', '', '']);
        $test->setColumnName(2, 'col2');
        $this->assertEquals(['', '', 'col2', ''], $test->getColumnNames());

        $test = new TableObject(10, 4);
        $this->assertTrue($test->getColumnNames() === ['', '', '', '']);
        $test->setColumnName(3, 'col3');
        $this->assertEquals(['', '', '', 'col3'], $test->getColumnNames());

        $test = new TableObject(1, 8);
        $this->assertTrue($test->getColumnNames() === ['', '', '', '', '', '', '', '']);
        $test->setColumnNames(['column1', 'column2', 'column3', 'column4', 'column5', 'column6', 'column7', 'column8']);
        $this->assertTrue($test->getColumnNames() === ['column1', 'column2', 'column3', 'column4', 'column5', 'column6', 'column7', 'column8']);

        // Test wrong values
        // Not necessary

        // Test exceptions
        // Already tested at constructor test
    }


    /**
     * testGetColumnName
     *
     * @return void
     */
    public function testGetColumnName(){

        // Test empty values
        $test = new TableObject();

        try {
            $test->getColumnName(0);
            $this->exceptionMessage = '0 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                $test->getColumnName($this->emptyValues[$i]);
                $this->exceptionMessage = 'empty value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        // Test ok values
        $test = new TableObject(10, 10);
        $this->assertTrue($test->getColumnName(1) === '');
        $this->assertTrue($test->getColumnName(5) === '');
        $this->assertTrue($test->getColumnName(7) === '');

        $this->assertTrue($test->setColumnName(1, ''));
        $this->assertTrue($test->setColumnName(2, '   '));
        $this->assertTrue($test->setColumnName(3, "\n\n"));
        $this->assertTrue($test->setColumnName(4, 'name 1'));
        $this->assertTrue($test->setColumnName(5, '  name 2'));

        $this->assertTrue($test->getColumnName(1) === '');
        $this->assertTrue($test->getColumnName(2) === '   ');
        $this->assertTrue($test->getColumnName(3) === "\n\n");
        $this->assertTrue($test->getColumnName(4) === 'name 1');
        $this->assertTrue($test->getColumnName(5) === '  name 2');

        $this->assertTrue($test->setColumnName(1, 'newName'));
        $this->assertTrue($test->setColumnName('   ', 'noEmpty'));
        $this->assertTrue($test->setColumnName(3, 'newName2'));
        $this->assertTrue($test->setColumnName('name 1', 'newName 4'));
        $this->assertTrue($test->setColumnName(5, 'newName3'));

        $this->assertTrue($test->getColumnName(1) === 'newName');
        $this->assertTrue($test->getColumnName(2) === 'noEmpty');
        $this->assertTrue($test->getColumnName(3) === 'newName2');
        $this->assertTrue($test->getColumnName(4) === 'newName 4');
        $this->assertTrue($test->getColumnName(5) === 'newName3');

        // Test wrong values
        try {
            $test->getColumnName(-1);
            $this->exceptionMessage = '-1 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->getColumnName(111);
            $this->exceptionMessage = '111 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->getColumnName('nonexistantkey');
            $this->exceptionMessage = 'nonexistantkey value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test exceptions
        // Already tested
    }


    /**
     * testGetColumnIndex
     *
     * @return void
     */
    public function testGetColumnIndex(){

        // Test empty values
        $test = new TableObject();

        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                $test->getColumnIndex($this->emptyValues[$i]);
                $this->exceptionMessage = 'empty value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        // Test ok values
        $test = new TableObject(10, 10);

        $this->assertTrue($test->setColumnName(2, '   '));
        $this->assertTrue($test->setColumnName(3, "\n\n"));
        $this->assertTrue($test->setColumnName(4, 'name 1'));
        $this->assertTrue($test->setColumnName(5, '  name 2'));

        $this->assertTrue($test->getColumnIndex('   ') === 2);
        $this->assertTrue($test->getColumnIndex("\n\n") === 3);
        $this->assertTrue($test->getColumnIndex('name 1') === 4);
        $this->assertTrue($test->getColumnIndex('  name 2') === 5);

        // Test wrong values
        try {
            $test->getColumnIndex(123);
            $this->exceptionMessage = 'empty value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->getColumnIndex('non existant key');
            $this->exceptionMessage = 'empty value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test exceptions
        // Already tested
    }


    /**
     * testGetColumn
     *
     * @return void
     */
    public function testGetColumn(){

        // Test empty values
        $test = new TableObject();

        try {
            $test->getColumn(0);
            $this->exceptionMessage = '0 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                $test->getColumn($this->emptyValues[$i]);
                $this->exceptionMessage = 'empty value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        // Test ok values
        $test = new TableObject(1, 1);
        $this->assertTrue($test->getColumn(0) === [null]);

        $test = new TableObject(5, 5);
        $this->assertTrue($test->getColumn(0) === [null, null, null, null, null]);
        $test->setColumn(2, [1, 2, 3, 4, 5]);
        $this->assertTrue($test->getColumn(0) === [null, null, null, null, null]);
        $this->assertTrue($test->getColumn(2) === [1, 2, 3, 4, 5]);
        $test->setColumn(4, [1, 2, 3, 4, 5]);
        $this->assertTrue($test->getColumn(3) === [null, null, null, null, null]);
        $this->assertTrue($test->getColumn(4) === [1, 2, 3, 4, 5]);

        $this->assertTrue($test->setColumnName(2, 'column2'));
        $this->assertTrue($test->getColumn('column2') === [1, 2, 3, 4, 5]);

        // Test wrong values
        try {
            $test->getColumn(-1);
            $this->exceptionMessage = '-1 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->getColumn(11);
            $this->exceptionMessage = '-1 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->getColumn('non existant');
            $this->exceptionMessage = 'non existant value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test exceptions
        // Already tested
    }


    /**
     * testAddColumns
     *
     * @return void
     */
    public function testAddColumns(){

        // Test empty values
        $test = new TableObject();

        try {
            $test->addColumns(0);
            $this->exceptionMessage = 'empty value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                $test->addColumns($this->emptyValues[$i]);
                $this->exceptionMessage = 'empty value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $test->addColumns(1, ['col'], $this->emptyValues[$i]);
                $this->exceptionMessage = 'empty value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            if($this->emptyValues[$i] !== []){

                try {
                    $test->addColumns(1, $this->emptyValues[$i]);
                    $this->exceptionMessage = 'empty value did not cause exception';
                } catch (Throwable $e) {
                    // We expect an exception to happen
                }
            }
        }

        // Test ok values
        $test = new TableObject();
        $this->assertTrue($test->addColumns(1));
        $this->assertTrue($test->countColumns() === 1);
        $this->assertTrue($test->getColumnNames() === ['']);
        $this->assertTrue($test->getColumn(0) === []);

        $test = new TableObject(4, 4);
        $this->assertTrue($test->addColumns(2, ['col1', 'col2']));
        $this->assertTrue($test->countColumns() === 6);
        $this->assertEquals(['', '', '', '', 'col1', 'col2'], $test->getColumnNames());
        $this->assertTrue($test->getColumn(3) === [null, null, null, null]);

        $this->assertTrue($test->addColumns(1, ['col3']));
        $this->assertTrue($test->countColumns() === 7);
        $this->assertEquals(['', '', '', '', 'col1', 'col2', 'col3'], $test->getColumnNames());

        $this->assertTrue($test->addColumns(1, ['col4'], 2));
        $this->assertTrue($test->countColumns() === 8);
        $this->assertEquals(['', '', 'col4', '', '', 'col1', 'col2', 'col3'], $test->getColumnNames());

        $this->assertTrue($test->addColumns(1, [], 0));
        $this->assertTrue($test->countColumns() === 9);
        $this->assertEquals(['', '', '', 'col4', '', '', 'col1', 'col2', 'col3'], $test->getColumnNames());

        $test = new TableObject(3, 1);
        $test->setColumn(0, [1, 2, 3]);
        $this->assertEquals(1, $test->countColumns());

        $this->assertTrue($test->addColumns(1, [], 0));
        $this->assertEquals(2, $test->countColumns());
        $this->assertEquals([null, null, null], $test->getColumn(0));
        $this->assertEquals([1, 2, 3], $test->getColumn(1));
        $this->assertTrue($test->addColumns(1, [], 0));
        $this->assertEquals([null, null, null], $test->getColumn(0));
        $this->assertEquals([null, null, null], $test->getColumn(1));
        $this->assertEquals([1, 2, 3], $test->getColumn(2));
        $this->assertEquals(3, $test->countColumns());

        $this->assertTrue($test->addColumns(1, ['col'], 2));
        $this->assertEquals([null, null, null], $test->getColumn(0));
        $this->assertEquals([null, null, null], $test->getColumn(1));
        $this->assertEquals([null, null, null], $test->getColumn(2));
        $this->assertEquals([1, 2, 3], $test->getColumn(3));
        $this->assertEquals([null, null, null], $test->getColumn('col'));
        $this->assertTrue($test->countColumns() === 4);

        $test = new TableObject();
        $test->addRows(3);
        $test->addColumns(3, ['c0', 'c1', 'c2']);
        $test->setColumn(0, [1, 2, 3]);
        $test->setColumn(1, [4, 5, 6]);
        $test->setColumn(2, [7, 8, 9]);
        $this->assertTrue($test->countRows() === 3);
        $this->assertTrue($test->countColumns() === 3);
        $this->assertEquals([1, 2, 3], $test->getColumn(0));
        $this->assertEquals([1, 2, 3], $test->getColumn('c0'));
        $this->assertEquals([4, 5, 6], $test->getColumn(1));
        $this->assertEquals([4, 5, 6], $test->getColumn('c1'));
        $this->assertEquals([7, 8, 9], $test->getColumn(2));
        $this->assertEquals([7, 8, 9], $test->getColumn('c2'));
        $test->addColumns(1, ['letters'], 2);
        $test->setColumn(2, ['x', 'y', 'z']);
        $this->assertEquals([1, 2, 3], $test->getColumn(0));
        $this->assertEquals([1, 2, 3], $test->getColumn('c0'));
        $this->assertEquals([4, 5, 6], $test->getColumn(1));
        $this->assertEquals([4, 5, 6], $test->getColumn('c1'));
        $this->assertEquals(['x', 'y', 'z'], $test->getColumn(2));
        $this->assertEquals(['x', 'y', 'z'], $test->getColumn('letters'));
        $this->assertTrue($test->getColumnIndex('letters') === 2);
        $this->assertEquals([7, 8, 9], $test->getColumn(3));
        $this->assertEquals([7, 8, 9], $test->getColumn('c2'));
        $this->assertTrue($test->getColumnIndex('c2') === 3);
        $this->assertTrue($test->countRows() === 3);
        $this->assertTrue($test->countColumns() === 4);

        // Test wrong values
        $test = new TableObject(3, 3);

        $this->wrongValues = [0, -1, 1.1, [1, 2, 3, 4]];
        $this->wrongValuesCount = count($this->wrongValues);

        for ($i = 0; $i < $this->wrongValuesCount; $i++) {

            try {
                $test->addColumns($this->wrongValues[$i]);
                $this->exceptionMessage = $this->wrongValues[$i].'wrong value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        try {
            $test->addColumns(1, [], 4);
            $this->exceptionMessage = 'wrong column index did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->addColumns(1, ['a', 'b']);
            $this->exceptionMessage = 'different names number did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test exceptions
        // Already tested
    }


    /**
     * testSetColumn
     *
     * @return void
     */
    public function testSetColumn(){

        // Test empty values
        $test = new TableObject();

        try {
            $test->setColumn(0, []);
            $this->exceptionMessage = 'empty value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        $test = new TableObject(4, 4);

        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                $test->setColumn($this->emptyValues[$i]);
                $this->exceptionMessage = $this->emptyValues[$i].' empty value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $test->setColumn(0, $this->emptyValues[$i]);
                $this->exceptionMessage = $this->emptyValues[$i].' empty parameter 2 did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        // Test ok values
        $test = new TableObject(1, 1);

        $test->setColumn(0, ['a']);
        $this->assertTrue($test->countRows() === 1);
        $this->assertTrue($test->countColumns() === 1);
        $this->assertTrue($test->getColumn(0) === ['a']);

        $test = new TableObject(4, 4);

        $test->setColumn(1, [1, 2, 3, 4]);
        $test->setColumn(3, [1, 2, 3, 4]);
        $this->assertTrue($test->countRows() === 4);
        $this->assertTrue($test->countColumns() === 4);
        $this->assertTrue($test->getColumn(0) === [null, null, null, null]);
        $this->assertTrue($test->getColumn(1) === [1, 2, 3, 4]);
        $this->assertTrue($test->getColumn(2) === [null, null, null, null]);
        $this->assertTrue($test->getColumn(3) === [1, 2, 3, 4]);

        $test->setColumn(1, ['a', 'b', 'c', 'd']);
        $test->setColumn(2, [5, 6, 7, 8]);
        $this->assertEquals([null, null, null, null], $test->getColumn(0));
        $this->assertEquals(['a', 'b', 'c', 'd'], $test->getColumn(1));
        $this->assertEquals([5, 6, 7, 8], $test->getColumn(2));
        $this->assertEquals([1, 2, 3, 4], $test->getColumn(3));

        // Test wrong values
        try {
            $test->setColumn(-1, ['a', 'b', 'c', 'd']);
            $this->exceptionMessage = '-1 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->setColumn(4, ['a', 'b', 'c', 'd']);
            $this->exceptionMessage = '4 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->setColumn(5, ['a', 'b', 'c', 'd']);
            $this->exceptionMessage = '5 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->setColumn('ooo', ['a', 'b', 'c', 'd']);
            $this->exceptionMessage = 'ooo did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->setColumn(0, ['a', 'b', 'c']);
            $this->exceptionMessage = 'array did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->setColumn(0, ['a', 'b', 'c', 'd', 'e']);
            $this->exceptionMessage = 'array did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test exceptions
        // already tested
    }


    /**
     * testRemoveColumn
     *
     * @return void
     */
    public function testRemoveColumn(){

        // Test empty values
        $test = new TableObject();

        try {
            $test->removeColumn(0);
            $this->exceptionMessage = '0 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        $test = new TableObject(4, 4);

        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                $test->removeColumn($this->emptyValues[$i]);
                $this->exceptionMessage = $this->emptyValues[$i].' empty value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        // Test ok values
        $test = new TableObject(1, 1);
        $test->setColumn(0, [1]);
        $test->removeColumn(0);
        $this->assertTrue($test->countRows() === 0);
        $this->assertTrue($test->countColumns() === 0);
        $this->assertTrue($test->countCells() === 0);

        $test = new TableObject(50, 50);

        for ($i = $test->countColumns() - 1; $i >= 0; $i--) {

            $test->removeColumn($i);

            $this->assertTrue($test->countRows() === ($i == 0 ? 0 : 50));
            $this->assertTrue($test->countColumns() === $i);
            $this->assertTrue($test->countCells() === 50 * $i);
        }

        $test = new TableObject(4, 4);
        $test->setColumnNames(['c1', 'c2', 'c3', 'c4']);
        $test->removeColumn('c2');
        $this->assertTrue($test->countRows() === 4);
        $this->assertTrue($test->countColumns() === 3);
        $this->assertTrue($test->countCells() === 12);
        $test->removeColumn('c4');
        $this->assertTrue($test->countRows() === 4);
        $this->assertTrue($test->countColumns() === 2);
        $this->assertTrue($test->countCells() === 8);
        $test->removeColumn('c3');
        $this->assertTrue($test->countRows() === 4);
        $this->assertTrue($test->countColumns() === 1);
        $this->assertTrue($test->countCells() === 4);

        $test = new TableObject(3, 3);
        $test->setColumnNames(['c1', 'c2', 'c3']);
        $test->setColumn(0, ['1', '2', '3']);
        $test->setColumn(1, [4, 5, 6]);
        $test->setColumn(2, ['7', 8, '9']);
        $test->removeColumn(1);
        $this->assertEquals($test->getColumn(0), ['1', '2', '3']);
        $this->assertEquals($test->getColumn(1), ['7', 8, '9']);
        $this->assertTrue($test->countRows() === 3);
        $this->assertTrue($test->countColumns() === 2);
        $this->assertTrue($test->countCells() === 6);
        $test->removeColumn(1);
        $this->assertEquals($test->getColumn(0), ['1', '2', '3']);
        $this->assertTrue($test->countRows() === 3);
        $this->assertTrue($test->countColumns() === 1);
        $this->assertTrue($test->countCells() === 3);
        $test->removeColumn(0);
        $this->assertTrue($test->countRows() === 0);
        $this->assertTrue($test->countColumns() === 0);
        $this->assertTrue($test->countCells() === 0);

        // Test wrong values
        $test = new TableObject(50, 50);

        try {
            $test->removeColumn(-1);
            $this->exceptionMessage = '-1 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->removeColumn(50);
            $this->exceptionMessage = '50 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->removeColumn('col');
            $this->exceptionMessage = 'col value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test exceptions
        // Already tested
    }


    /**
     * testGetCell
     *
     * @return void
     */
    public function testGetCell(){

        // Test empty values
        $test = new TableObject();

        try {
            $test->getCell(0, 0);
            $this->exceptionMessage = '0, 0 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        $test = new TableObject(4, 4);

        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                $test->getCell($this->emptyValues[$i], 0);
                $this->exceptionMessage = $this->emptyValues[$i].' empty parameter 1 did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $test->getCell(0, $this->emptyValues[$i]);
                $this->exceptionMessage = $this->emptyValues[$i].' empty parameter 2 did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        // Test ok values
        $test = new TableObject(3, 3);
        $test->setColumnNames(['c1', 'c2', 'c3']);
        $test->setColumn(0, ['1', '2', '3']);
        $test->setColumn(1, [4, 5, 6]);
        $test->setColumn(2, ['7', 8, '9']);
        $this->assertTrue($test->getCell(0, 0) === '1');
        $this->assertTrue($test->getCell(0, 'c1') === '1');
        $this->assertTrue($test->getCell(2, 0) === '3');
        $this->assertTrue($test->getCell(2, 'c1') === '3');
        $this->assertTrue($test->getCell(1, 1) === 5);
        $this->assertTrue($test->getCell(1, 'c2') === 5);
        $this->assertTrue($test->getCell(0, 2) === '7');
        $this->assertTrue($test->getCell(0, 'c3') === '7');
        $this->assertTrue($test->getCell(2, 2) === '9');
        $this->assertTrue($test->getCell(2, 'c3') === '9');

        $test->removeColumn('c2');
        $this->assertTrue($test->getCell(0, 0) === '1');
        $this->assertTrue($test->getCell(0, 'c1') === '1');
        $this->assertTrue($test->getCell(2, 0) === '3');
        $this->assertTrue($test->getCell(2, 'c1') === '3');
        $this->assertTrue($test->getCell(1, 1) === 8);
        $this->assertTrue($test->getCell(1, 'c3') === 8);
        $this->assertTrue($test->getCell(0, 1) === '7');
        $this->assertTrue($test->getCell(0, 'c3') === '7');

        $test->removeRow(0);
        $this->assertTrue($test->getCell(0, 0) === '2');
        $this->assertTrue($test->getCell(0, 'c1') === '2');
        $this->assertTrue($test->getCell(1, 1) === '9');
        $this->assertTrue($test->getCell(1, 'c3') === '9');
        $this->assertTrue($test->countRows() === 2);
        $this->assertTrue($test->countColumns() === 2);
        $this->assertTrue($test->countCells() === 4);

        $test->removeRow(0);
        $this->assertTrue($test->getCell(0, 0) === '3');
        $this->assertTrue($test->getCell(0, 'c1') === '3');
        $this->assertTrue($test->getCell(0, 1) === '9');
        $this->assertTrue($test->getCell(0, 'c3') === '9');
        $this->assertTrue($test->countRows() === 1);
        $this->assertTrue($test->countColumns() === 2);
        $this->assertTrue($test->countCells() === 2);

        // Test wrong values
        try {
            $test->getCell(0, -1);
            $this->exceptionMessage = '0 -1 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->getCell(-1, 0);
            $this->exceptionMessage = '-1 0 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->getCell(0, 3);
            $this->exceptionMessage = '0 3 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->getCell(3, 0);
            $this->exceptionMessage = '3 0 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
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
        $test = new TableObject();

        try {
            $test->setCell(0, 0, null);
            $this->exceptionMessage = '0, 0, null did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        $test = new TableObject(4, 4);

        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                $test->setCell($this->emptyValues[$i], 0, null);
                $this->exceptionMessage = $this->emptyValues[$i].' empty parameter 1 did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $test->setCell(0, $this->emptyValues[$i], null);
                $this->exceptionMessage = $this->emptyValues[$i].' empty parameter 2 did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            $this->assertTrue($test->setCell(0, 0, $this->emptyValues[$i]) === $this->emptyValues[$i]);
            $this->assertTrue($test->getCell(0, 0) === $this->emptyValues[$i]);
        }

        // Test ok values
        $test = new TableObject(10, 10);
        $this->assertTrue($test->setCell(0, 0, 'value') === 'value');
        $this->assertTrue($test->getCell(0, 0) === 'value');
        $this->assertTrue($test->setCell(5, 5, 'value5') === 'value5');
        $this->assertTrue($test->getCell(5, 5) === 'value5');
        $this->assertEquals([null, null, null, null, null, 'value5', null, null, null, null], $test->getColumn(5));

        $this->assertTrue($test->setCell(0, 9, '1') === '1');
        $this->assertTrue($test->setCell(1, 9, '2') === '2');
        $this->assertTrue($test->setCell(2, 9, '3') === '3');
        $this->assertTrue($test->setCell(3, 9, '4') === '4');
        $this->assertTrue($test->setCell(4, 9, '5') === '5');
        $this->assertEquals(['1', '2', '3', '4', '5', null, null, null, null, null], $test->getColumn(9));

        $test->setColumnName(7, 'col7');
        $this->assertTrue($test->setCell(3, 'col7', '3 value') === '3 value');
        $this->assertTrue($test->setCell(8, 'col7', '8 value') === '8 value');
        $this->assertTrue($test->getCell(3, 7) === '3 value');
        $this->assertTrue($test->getCell(3, 'col7') === '3 value');
        $this->assertTrue($test->getCell(8, 7) === '8 value');
        $this->assertTrue($test->getCell(8, 'col7') === '8 value');
        $this->assertEquals([null, null, null, '3 value', null, null, null, null, '8 value', null], $test->getColumn(7));
        $this->assertEquals([null, null, null, '3 value', null, null, null, null, '8 value', null], $test->getColumn('col7'));

        // Test wrong values
        try {
            $test->setCell(-1, 0, 'v');
            $this->exceptionMessage = '-1 0 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->setCell(11, 0, 'v');
            $this->exceptionMessage = '11 0 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->setCell(0, -1, 'v');
            $this->exceptionMessage = '0 -1 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->setCell(0, 11, 'v');
            $this->exceptionMessage = '0 11 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->setCell(0, 'no', 'v');
            $this->exceptionMessage = '0 no did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->setCell('no', 0, 'v');
            $this->exceptionMessage = 'no 0 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test exceptions
        // Already tested
    }


    /**
     * testGetRow
     *
     * @return void
     */
    public function testGetRow(){

        // Test empty values
        $test = new TableObject();

        try {
            $test->getRow(0);
            $this->exceptionMessage = '0 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                $test->getRow($this->emptyValues[$i]);
                $this->exceptionMessage = $i.' empty value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        // Test ok values
        $test = new TableObject(1, 1);
        $this->assertTrue($test->getRow(0) === [null]);

        $test = new TableObject(5, 5);
        $test->setCell(1, 0, 1);
        $test->setCell(1, 1, 2);
        $test->setCell(1, 2, 3);
        $this->assertTrue($test->getRow(0) === [null, null, null, null, null]);
        $this->assertTrue($test->getRow(1) === [1, 2, 3, null, null]);

        $test = new TableObject(5, 5);
        $this->assertTrue($test->getRow(0) === [null, null, null, null, null]);
        $test->setRow(2, [1, 2, 3, 4, 5]);
        $this->assertTrue($test->getRow(0) === [null, null, null, null, null]);
        $this->assertTrue($test->getRow(2) === [1, 2, 3, 4, 5]);
        $test->setRow(4, [1, 2, 3, 4, 5]);
        $this->assertTrue($test->getRow(3) === [null, null, null, null, null]);
        $this->assertTrue($test->getRow(4) === [1, 2, 3, 4, 5]);

        // Test wrong values
        try {
            $test->getRow(-1);
            $this->exceptionMessage = '-1 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->getRow(11);
            $this->exceptionMessage = '11 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->getRow('string');
            $this->exceptionMessage = 'string value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test exceptions
        // Already tested
    }


    /**
     * testAddRows
     *
     * @return void
     */
    public function testAddRows(){

        // Test empty values
        $test = new TableObject();

        try {
            $test->addRows(0);
            $this->exceptionMessage = 'empty value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                $test->addRows($this->emptyValues[$i]);
                $this->exceptionMessage = 'empty value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            if($this->emptyValues[$i] !== []){

                try {
                    $test->addRows(1, $this->emptyValues[$i]);
                    $this->exceptionMessage = 'empty param 2 did not cause exception';
                } catch (Throwable $e) {
                    // We expect an exception to happen
                }
            }
        }

        // Test ok values
        $test = new TableObject();
        $this->assertTrue($test->addRows(1));
        $this->assertTrue($test->countRows() === 1);
        $this->assertTrue($test->getRow(0) === []);

        $test = new TableObject(4, 4);
        $this->assertTrue($test->addRows(2));
        $this->assertTrue($test->countRows() === 6);
        $this->assertTrue($test->getRow(3) === [null, null, null, null]);

        $this->assertTrue($test->addRows(1));
        $this->assertTrue($test->countRows() === 7);

        $this->assertTrue($test->addRows(1, 2));
        $this->assertTrue($test->countRows() === 8);

        $this->assertTrue($test->addRows(1, 0));
        $this->assertTrue($test->countRows() === 9);

        $test = new TableObject(1, 3);
        $test->setRow(0, [1, 2, 3]);
        $this->assertEquals(1, $test->countRows());

        $this->assertTrue($test->addRows(1, 0));
        $this->assertEquals(2, $test->countRows());
        $this->assertEquals([null, null, null], $test->getRow(0));
        $this->assertEquals([1, 2, 3], $test->getRow(1));
        $this->assertTrue($test->addRows(1, 0));
        $this->assertEquals([null, null, null], $test->getRow(0));
        $this->assertEquals([null, null, null], $test->getRow(1));
        $this->assertEquals([1, 2, 3], $test->getRow(2));
        $this->assertEquals(3, $test->countRows());

        $this->assertTrue($test->addRows(1, 2));
        $this->assertEquals([null, null, null], $test->getRow(0));
        $this->assertEquals([null, null, null], $test->getRow(1));
        $this->assertEquals([null, null, null], $test->getRow(2));
        $this->assertEquals([1, 2, 3], $test->getRow(3));
        $this->assertTrue($test->countRows() === 4);

        $test = new TableObject();
        $test->addRows(3);
        $test->addColumns(3);
        $test->setRow(0, [1, 2, 3]);
        $test->setRow(1, [4, 5, 6]);
        $test->setRow(2, [7, 8, 9]);
        $this->assertTrue($test->countRows() === 3);
        $this->assertTrue($test->countColumns() === 3);
        $this->assertEquals([1, 2, 3], $test->getRow(0));
        $this->assertEquals([4, 5, 6], $test->getRow(1));
        $this->assertEquals([7, 8, 9], $test->getRow(2));
        $test->addRows(1, 2);
        $test->setRow(2, ['x', 'y', 'z']);
        $this->assertEquals([1, 2, 3], $test->getRow(0));
        $this->assertEquals([4, 5, 6], $test->getRow(1));
        $this->assertEquals(['x', 'y', 'z'], $test->getRow(2));
        $this->assertEquals([7, 8, 9], $test->getRow(3));
        $this->assertTrue($test->countRows() === 4);
        $this->assertTrue($test->countColumns() === 3);

        // Test wrong values
        $test = new TableObject(3, 3);

        $this->wrongValues = [0, -1, 1.1, [1, 2, 3, 4]];
        $this->wrongValuesCount = count($this->wrongValues);

        for ($i = 0; $i < $this->wrongValuesCount; $i++) {

            try {
                $test->addRows($this->wrongValues[$i]);
                $this->exceptionMessage = $this->wrongValues[$i].'wrong value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        try {
            $test->addRows(1, 4);
            $this->exceptionMessage = 'wrong row index did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test exceptions
        // Already tested
    }


    /**
     * testSetRow
     *
     * @return void
     */
    public function testSetRow(){

        // Test empty values
        $test = new TableObject();

        try {
            $test->setRow(0, []);
            $this->exceptionMessage = 'empty value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        $test = new TableObject(4, 4);

        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                $test->setRow($this->emptyValues[$i]);
                $this->exceptionMessage = $this->emptyValues[$i].' empty value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }

            try {
                $test->setRow(0, $this->emptyValues[$i]);
                $this->exceptionMessage = $this->emptyValues[$i].' empty parameter 2 did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        // Test ok values
        $test = new TableObject(1, 1);

        $test->setRow(0, ['a']);
        $this->assertTrue($test->countRows() === 1);
        $this->assertTrue($test->countColumns() === 1);
        $this->assertTrue($test->getRow(0) === ['a']);
        $this->assertTrue($test->getColumn(0) === ['a']);

        $test = new TableObject(4, 4);

        $test->setRow(1, [1, 2, 3, 4]);
        $test->setRow(3, [1, 2, 3, 4]);
        $this->assertTrue($test->countRows() === 4);
        $this->assertTrue($test->countColumns() === 4);
        $this->assertTrue($test->getRow(0) === [null, null, null, null]);
        $this->assertTrue($test->getRow(1) === [1, 2, 3, 4]);
        $this->assertTrue($test->getRow(2) === [null, null, null, null]);
        $this->assertTrue($test->getRow(3) === [1, 2, 3, 4]);

        $test->setRow(1, ['a', 'b', 'c', 'd']);
        $test->setRow(2, [5, 6, 7, 8]);
        $this->assertEquals([null, null, null, null], $test->getRow(0));
        $this->assertEquals(['a', 'b', 'c', 'd'], $test->getRow(1));
        $this->assertEquals([5, 6, 7, 8], $test->getRow(2));
        $this->assertEquals([1, 2, 3, 4], $test->getRow(3));

        // Test wrong values
        try {
            $test->setRow(-1, ['a', 'b', 'c', 'd']);
            $this->exceptionMessage = '-1 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->setRow(4, ['a', 'b', 'c', 'd']);
            $this->exceptionMessage = '4 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->setRow(5, ['a', 'b', 'c', 'd']);
            $this->exceptionMessage = '5 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->setRow('ooo', ['a', 'b', 'c', 'd']);
            $this->exceptionMessage = 'ooo did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->setRow(0, ['a', 'b', 'c']);
            $this->exceptionMessage = 'array did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->setRow(0, ['a', 'b', 'c', 'd', 'e']);
            $this->exceptionMessage = 'array did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test exceptions
        // already tested
    }


    /**
     * testRemoveRow
     *
     * @return void
     */
    public function testRemoveRow(){

        // Test empty values
        $test = new TableObject();

        try {
            $test->removeRow(0);
            $this->exceptionMessage = '0 did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        $test = new TableObject(4, 4);

        for ($i = 0; $i < $this->emptyValuesCount; $i++) {

            try {
                $test->removeRow($this->emptyValues[$i]);
                $this->exceptionMessage = $this->emptyValues[$i].' empty value did not cause exception';
            } catch (Throwable $e) {
                // We expect an exception to happen
            }
        }

        // Test ok values
        $test = new TableObject(1, 1);
        $test->setRow(0, [1]);
        $test->removeRow(0);
        $this->assertTrue($test->countRows() === 0);
        $this->assertTrue($test->countColumns() === 0);
        $this->assertTrue($test->countCells() === 0);

        $test = new TableObject(50, 50);

        for ($i = $test->countRows() - 1; $i >= 0; $i--) {

            $test->removeRow($i);

            $this->assertTrue($test->countColumns() === ($i == 0 ? 0 : 50));
            $this->assertTrue($test->countRows() === $i);
            $this->assertTrue($test->countCells() === 50 * $i);
        }

        $test = new TableObject(3, 3);
        $test->setRow(0, ['1', '2', '3']);
        $test->setRow(1, [4, 5, 6]);
        $test->setRow(2, ['7', 8, '9']);
        $test->removeRow(1);
        $this->assertEquals($test->getRow(0), ['1', '2', '3']);
        $this->assertEquals($test->getRow(1), ['7', 8, '9']);
        $this->assertTrue($test->countRows() === 2);
        $this->assertTrue($test->countColumns() === 3);
        $this->assertTrue($test->countCells() === 6);
        $test->removeRow(1);
        $this->assertEquals($test->getRow(0), ['1', '2', '3']);
        $this->assertTrue($test->countRows() === 1);
        $this->assertTrue($test->countColumns() === 3);
        $this->assertTrue($test->countCells() === 3);
        $test->removeRow(0);
        $this->assertTrue($test->countRows() === 0);
        $this->assertTrue($test->countColumns() === 0);
        $this->assertTrue($test->countCells() === 0);

        // Test wrong values
        $test = new TableObject(50, 50);

        try {
            $test->removeRow(-1);
            $this->exceptionMessage = '-1 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->removeRow(50);
            $this->exceptionMessage = '50 value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        try {
            $test->removeRow('col');
            $this->exceptionMessage = 'col value did not cause exception';
        } catch (Throwable $e) {
            // We expect an exception to happen
        }

        // Test exceptions
        // Already tested
    }


    /**
     * testCountRows
     *
     * @return void
     */
    public function testCountRows(){

        // Test empty values
        $test = new TableObject();
        $this->assertTrue($test->countRows() === 0);

        $test = new TableObject(0, 0);
        $this->assertTrue($test->countRows() === 0);

        // Test ok values
        $test = new TableObject();
        $test->addColumns(4);
        $test->addRows(4);
        $this->assertTrue($test->countRows() === 4);
        $test->removeRow(0);
        $this->assertTrue($test->countRows() === 3);
        $test->removeRow(1);
        $test->removeColumn(1);
        $this->assertTrue($test->countRows() === 2);
        $test->addRows(5, 1);
        $this->assertTrue($test->countRows() === 7);
        $test->removeRow(6);
        $test->removeColumn(2);
        $this->assertTrue($test->countRows() === 6);

        // Test wrong values
        // Not necessary

        // Test exceptions
        // Not necessary
    }


    /**
     * testCountColumns
     *
     * @return void
     */
    public function testCountColumns(){

        // Test empty values
        $test = new TableObject();
        $this->assertTrue($test->countColumns() === 0);

        $test = new TableObject(0, 0);
        $this->assertTrue($test->countColumns() === 0);

        // Test ok values
        $test = new TableObject();
        $test->addColumns(4);
        $test->addRows(4);
        $this->assertTrue($test->countColumns() === 4);
        $test->removeColumn(0);
        $this->assertTrue($test->countColumns() === 3);
        $test->removeRow(1);
        $test->removeColumn(1);
        $this->assertTrue($test->countColumns() === 2);
        $test->addColumns(5, [], 1);
        $this->assertTrue($test->countColumns() === 7);
        $test->removeRow(2);
        $test->removeColumn(6);
        $this->assertTrue($test->countColumns() === 6);

        // Test wrong values
        // Not necessary

        // Test exceptions
        // Not necessary
    }


    /**
     * testCountCells
     *
     * @return void
     */
    public function testCountCells(){

        // Test empty values
        $test = new TableObject();
        $this->assertTrue($test->countCells() === 0);

        $test = new TableObject(0, 0);
        $this->assertTrue($test->countCells() === 0);

        // Test ok values
        $test = new TableObject();
        $test->addColumns(4);
        $test->addRows(4);
        $this->assertTrue($test->countCells() === 16);
        $test->removeColumn(0);
        $this->assertTrue($test->countCells() === 12);
        $test->removeRow(1);
        $test->removeColumn(1);
        $this->assertTrue($test->countCells() === 6);
        $test->addColumns(5, [], 1);
        $this->assertTrue($test->countCells() === 21);
        $test->removeRow(2);
        $test->removeColumn(6);
        $this->assertTrue($test->countCells() === 12);

        // Test wrong values
        // Not necessary

        // Test exceptions
        // Not necessary
    }

}

?>