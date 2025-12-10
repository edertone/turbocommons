<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\model;

use UnexpectedValueException;
use org\turbocommons\src\main\php\utils\NumericUtils;
use org\turbocommons\src\main\php\utils\ArrayUtils;
use org\turbocommons\src\main\php\utils\StringUtils;


/**
 * A 2D table structure
 */
class TableObject{


    /**
     * Stores a list with all the column names on the table.
     * We use null to represent a column that has no name assigned (default state).
     *
     * @var array
     */
    protected $_columnNames = [];


    /**
     * Stores all the table cells data.
     * The values are stored as a 2D array [row][column]
     *
     * @var array
     */
    protected $_cells = [];


    /**
     * Stores the number of columns on the current table instance
     * @var integer
     */
    protected $_columnsCount = 0;


    /**
     * Stores the number of rows on the current table instance
     * @var integer
     */
    protected $_rowsCount = 0;


    /**
     * TableObject is an abstraction of a 2D table with X columns and Y rows where each cell can be used to store any kind of data.
     *
     * Columns can be labeled with a textual name which can be used to access them anytime (data can be also accessed via numeric row and column indexes).
     *
     * @param int $rows The number of rows for the created table (Rows can be added or modified anytime later).
     * @param mixed $columns The number of columns to create or an array of strings containing the column labels for all of the columns that will be created (Columns can be added or modified anytime later).
     *
     * @return TableObject The constructed TableObject
     */
    public function __construct(int $rows = 0, $columns = 0){

        if(NumericUtils::isInteger($rows) && $rows >= 0){

            $this->_rowsCount = $rows;

        }else{

            throw new UnexpectedValueException('constructor rows must be a positive integer');
        }

        if(NumericUtils::isInteger($columns) && $columns >= 0){

            $this->_columnsCount = $columns;

            // Initialize empty column names with null.
            // Null indicates "no name", ensuring searching for "" (empty string) fails unless explicitly set.
            $this->_columnNames = array_fill(0, $this->_columnsCount, null);

        }else{

            if(is_array($columns)){

                $this->_columnsCount = count($columns);

                $this->setColumnNames($columns);

            }else{

                throw new UnexpectedValueException('constructor columns must be an integer or an array of strings');
            }
        }

        if(($this->_columnsCount + $this->_rowsCount > 0) && ($this->_columnsCount == 0 || $this->_rowsCount == 0)){

            throw new UnexpectedValueException('constructor columns cannot be empty if rows are positive and vice versa');
        }

        // Initialize cells with null values to match expected behavior
        if($this->_rowsCount > 0 && $this->_columnsCount > 0){
            $rowTemplate = array_fill(0, $this->_columnsCount, null);
            $this->_cells = array_fill(0, $this->_rowsCount, $rowTemplate);
        }
    }


    /**
     * Set the label to an existing table column.
     *
     * @param mixed $column An integer or a string containing the index or label for the column to which we want to assign a label
     * @param string $name The new label that will be assigned to the specified column
     *
     * @throws UnexpectedValueException
     *
     * @return boolean True if the column name was correctly assigned
     */
    public function setColumnName($column, $name){

        $columnIndex = $this->_validateColumnIndex($column);

        if(!StringUtils::isString($name)){

            throw new UnexpectedValueException('name must be a string');
        }

        $this->_columnNames[$columnIndex] = $name;

        return true;
    }


    /**
     * Define the names for the current table columns (Already defined column names will be overriden).
     *
     * @param array $names List of names that will be applied to the table columns.
     * It must have the same number of items and in the same order as the table columns.
     *
     * @throws UnexpectedValueException
     * @return array The list of column names after beign assigned
     */
    public function setColumnNames(array $names){

        if($this->_columnsCount == count($names)){

            if(ArrayUtils::hasDuplicateElements($names)){

                throw new UnexpectedValueException('array must not contain duplicate elements');
            }

            $namesCount = count($names);

            // Validate all are strings first
            for ($i = 0; $i < $namesCount; $i++) {
                if(!StringUtils::isString($names[$i])){
                    throw new UnexpectedValueException('List of names must be an array of strings');
                }
            }

            // Assign keys strictly to ensure 0-indexed array
            $this->_columnNames = array_values($names);

            return $names;
        }

        throw new UnexpectedValueException('List of names must match number of columns');
    }


    /**
     * Get a list with all the currently defined column names in the same order as they are assigned to the table.
     * If the table contains columns but no names are defined, a list with empty strings will be returned
     *
     * @return array A list of strings with the column names
     */
    public function getColumnNames(){

        // Convert internal nulls to empty strings for output
        return array_map(function($name) {
            return $name === null ? '' : $name;
        }, $this->_columnNames);
    }


    /**
     * Get the defined column name for a given column index
     *
     * @param integer $columnIndex a numeric column index
     *
     * @return string The column label for the specified numeric index
     */
    public function getColumnName(int $columnIndex){

        $idx = $this->_validateColumnIndex($columnIndex);

        // Return empty string if value is null (not set)
        return $this->_columnNames[$idx] ?? '';
    }


    /**
     * Get the numeric column index from it's label
     *
     * @param string $name The label for an existing column
     *
     * @return integer The numeric index that is related to the given column label
     *
     * @throws UnexpectedValueException
     */
    public function getColumnIndex($name){

        if(!StringUtils::isString($name) || $name === ''){

            throw new UnexpectedValueException('value must be a non empty string');
        }

        $index = array_search($name, $this->_columnNames, true);

        if($index !== false){
            return $index;
        }

        throw new UnexpectedValueException('provided column name does not exist');
    }


    /**
     * Get all the elements that are located at the specified column index or label.
     *
     * @param mixed $column An integer or a string containing the index or label for the column that we want to retrieve
     *
     * @return array All the table elements that belong to the required column
     */
    public function getColumn($column){

        $result = [];

        $columnIndex = $this->_validateColumnIndex($column);

        for ($i = 0; $i < $this->_rowsCount; $i++) {
            // Direct array access for speed
            $result[] = $this->_cells[$i][$columnIndex] ?? null;
        }

        return $result;
    }


    /**
     * Add the specified amount of columns to the table.
     *
     * @param integer $number The number of columns that will be added to the table
     * @param array $names Optionally we can list all the labels to define for the new columns that will be added
     * @param integer $at Defines the column index where the new columns will be inserted. Old columns that are located at the insertion point will not be deleted, they will be moved to the Right. By default all the new columns will be appended at the end of the table unless a positive value is specified here.
     *
     * @throws UnexpectedValueException
     * @return boolean True if the operation was successful
     */
    public function addColumns($number, array $names = [], int $at = -1){

        if(!NumericUtils::isInteger($number) || $number <= 0){

            throw new UnexpectedValueException('number must be a positive integer');
        }

        if(!NumericUtils::isInteger($at) || $at < -1 || $at >= $this->_columnsCount){

            throw new UnexpectedValueException('at must be a valid column index');
        }

        $namesCount = count($names);
        if($namesCount > 0 && $namesCount != $number){
            throw new UnexpectedValueException('names length must be the same as number');
        }

        // Prepare new column names. Default is null (unset).
        $newNames = ($namesCount > 0) ? $names : array_fill(0, $number, null);

        // Prepare null values for the new cells
        $newCells = array_fill(0, $number, null);

        if($at >= 0){
            // Insert in the middle
            array_splice($this->_columnNames, $at, 0, $newNames);

            for ($i = 0; $i < $this->_rowsCount; $i++) {
                // Check if row exists, if not initialize it (sparse handling)
                if(!isset($this->_cells[$i])) {
                    $this->_cells[$i] = array_fill(0, $this->_columnsCount, null);
                }
                array_splice($this->_cells[$i], $at, 0, $newCells);
            }
        } else {
            // Append to the end
            foreach ($newNames as $name) {
                $this->_columnNames[] = $name;
            }

            for ($i = 0; $i < $this->_rowsCount; $i++) {
                if(!isset($this->_cells[$i])) {
                    $this->_cells[$i] = array_fill(0, $this->_columnsCount, null);
                }
                foreach ($newCells as $val) {
                    $this->_cells[$i][] = $val;
                }
            }
        }

        $this->_columnsCount += $number;

        return true;
    }


    /**
     * Fill the data on all the rows for the given column index or label
     *
     * @param mixed $column An integer or a string containing the index or label for the column that we want to fill
     * @param array $data An array with all the values that will be assigned to the table rows on the specified column. Array length must match rows number
     *
     * @throws UnexpectedValueException
     * @return void
     */
    public function setColumn($column, array $data){

        $dataCount = count($data);

        if($dataCount <= 0){

            throw new UnexpectedValueException('data must not be empty');
        }

        if($this->_rowsCount != $dataCount){

            throw new UnexpectedValueException('data length and number of rows must match');
        }

        $columnIndex = $this->_validateColumnIndex($column);

        for ($i = 0; $i < $this->_rowsCount; $i++) {
            $this->_cells[$i][$columnIndex] = $data[$i];
        }
    }


    /**
     * Delete a whole column and all its related data from the table
     *
     * @param mixed $column An integer or a string containing the index or label for the column that we want to delete
     *
     * @return void
     */
    public function removeColumn($column){

        $columnIndex = $this->_validateColumnIndex($column);

        // Remove column name using splice (re-indexes automatically)
        array_splice($this->_columnNames, $columnIndex, 1);

        // Remove column from each row
        for($i = 0; $i < $this->_rowsCount; $i++){
            if(isset($this->_cells[$i])){
                array_splice($this->_cells[$i], $columnIndex, 1);
            }
        }

        $this->_columnsCount --;

        if($this->_columnsCount <= 0){
            $this->_rowsCount = 0;
            $this->_cells = [];
            $this->_columnNames = [];
        }
    }


    /**
     * Get the value contained at the specified table cell
     *
     * @param integer $row An integer containing the index for the row that we want to retrieve
     * @param mixed $column An integer or a string containing the index or label for the column that we want to retrieve
     *
     * @return mixed The value for the cell located at the specified row and column or null if no data is defined for it
     */
    public function getCell(int $row, $column){

        $rowIndex = $this->_validateRowIndex($row);
        $columnIndex = $this->_validateColumnIndex($column);

        return $this->_cells[$rowIndex][$columnIndex] ?? null;
    }


    /**
     * Set the value for a table cell. Make sure row and column indices are valid (exist) before calling this method.
     *
     * @param integer $row An integer containing the index for the row that we want to set
     * @param mixed $column An integer or a string containing the index or label for the column that we want to set
     * @param mixed $value The value we want to set to the specified cell. Any type is allowed, and different cells can contain values of different types.
     *
     * @return mixed The assigned value after beign stored into the table cell
     */
    public function setCell(int $row, $column, $value){

        $rowIndex = $this->_validateRowIndex($row);
        $columnIndex = $this->_validateColumnIndex($column);

        // Ensure row exists (handling sparse creation logic if necessary, though construction fills them)
        if (!isset($this->_cells[$rowIndex])) {
            $this->_cells[$rowIndex] = array_fill(0, $this->_columnsCount, null);
        }

        $this->_cells[$rowIndex][$columnIndex] = $value;

        return $value;
    }


    /**
     * Get all the elements that are located at the specified row index
     *
     * @param integer $row An integer containing the index for the row that we want to retrieve
     *
     * @return array All the table elements that belong to the required row
     */
    public function getRow(int $row){

        $rowIndex = $this->_validateRowIndex($row);

        $rowArray = $this->_cells[$rowIndex] ?? [];

        // Ensure array size matches columns count by padding with nulls.
        // This is necessary because CSVObject logic might expand columnsCount without backfilling rows.
        if (count($rowArray) < $this->_columnsCount) {
            return array_pad($rowArray, $this->_columnsCount, null);
        }

        return $rowArray;
    }


    /**
     * Add the specified amount of rows to the table.
     *
     * @param integer $number The number of rows that will be added to the table
     * @param integer $at Defines the row index where the new rows will be inserted. Old rows that are located at the insertion point will not be deleted, they will be moved down. By default all the new rows will be appended at the bottom of the table unless a positive value is specified here.
     *
     * @throws UnexpectedValueException
     * @return boolean True if the operation was successful
     */
    public function addRows($number, int $at = -1){

        if(!NumericUtils::isInteger($number) || $number <= 0){

            throw new UnexpectedValueException('number must be a positive integer');
        }

        if(!NumericUtils::isInteger($at) || $at < -1 || $at >= $this->_rowsCount){

            throw new UnexpectedValueException('at must be a valid row index');
        }

        $rowTemplate = array_fill(0, $this->_columnsCount, null);
        $newRows = array_fill(0, $number, $rowTemplate);

        if($at >= 0){
            // Insert in the middle
            array_splice($this->_cells, $at, 0, $newRows);
        } else {
            // Append to the end
            foreach($newRows as $row){
                $this->_cells[] = $row;
            }
        }

        $this->_rowsCount += $number;

        return true;
    }


    /**
     * Fill all the data for the specified row
     *
     * @param integer $row An integer containing the index for the row that we want to set
     * @param array $data An array with all the values that will be assigned to the table row. Array length must match columns number
     *
     * @throws UnexpectedValueException
     * @return void
     */
    public function setRow(int $row, array $data){

        $dataCount = count($data);

        if($dataCount <= 0){

            throw new UnexpectedValueException('data must not be empty');
        }

        if($this->_columnsCount != $dataCount){

            throw new UnexpectedValueException('data length and number of columns must match');
        }

        $rowIndex = $this->_validateRowIndex($row);

        $this->_cells[$rowIndex] = array_values($data);
    }


    /**
     * Delete a whole row and all its related data from the table
     *
     * @param integer $row An integer containing the index for the row that we want to delete
     *
     * @return void
     */
    public function removeRow(int $row){

        $rowIndex = $this->_validateRowIndex($row);

        // Native array splice handles re-indexing automatically
        array_splice($this->_cells, $rowIndex, 1);

        $this->_rowsCount --;

        if($this->_rowsCount <= 0){
            $this->_columnsCount = 0;
            $this->_columnNames = [];
            $this->_cells = [];
        }
    }


    /**
     * Get the total number of rows that are currently available on this table
     *
     * @return integer The total number of rows on the table
     */
    public function countRows(){

        return $this->_rowsCount;
    }


    /**
     * Get the total number of columns that are currently available on this table
     *
     * @return integer The total number of columns on the table
     */
    public function countColumns(){

        return $this->_columnsCount;
    }


    /**
     * Get the total number of cells that are currently available on this table
     *
     * @return integer The total number of cells on the table
     */
    public function countCells(){

        return $this->_rowsCount * $this->_columnsCount;
    }


    /**
     * Auxiliary method to validate that a given column index or label belongs to the current table
     *
     * @param mixed $column An integer or a string containing the index or label for the column that we want to validate
     *
     * @throws UnexpectedValueException
     *
     * @return integer A valid column index based on the specified integer or label.
     */
    private function _validateColumnIndex($column){

        $columnIndex = -1;

        if(NumericUtils::isInteger($column)){
            $columnIndex = $column;
        } elseif (StringUtils::isString($column)) {
            // Fast lookup in native array
            $key = array_search($column, $this->_columnNames, true);
            if($key !== false){
                $columnIndex = $key;
            }
        }

        if($columnIndex < 0 || $columnIndex >= $this->_columnsCount){

            throw new UnexpectedValueException('Invalid column value');
        }

        return $columnIndex;
    }


    /**
     * Auxiliary method to validate that a given row index belongs to the current table
     *
     * @param mixed $row An integer containing the index for the row that we want to validate
     *
     * @throws UnexpectedValueException
     *
     * @return integer A valid row index based on the specified integer
     */
    private function _validateRowIndex(int $row){

        $rowIndex = NumericUtils::isInteger($row) ? $row : -1;

        if($rowIndex < 0 || $rowIndex >= $this->_rowsCount){

            throw new UnexpectedValueException('Invalid row value');
        }

        return $rowIndex;
    }
}
