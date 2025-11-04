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

use Throwable;
use UnexpectedValueException;
use org\turbocommons\src\main\php\utils\StringUtils;
use org\turbocommons\src\main\php\utils\ArrayUtils;


/**
 * CSV data abstraction
 */
class CSVObject extends TableObject{


    /**
     * True if the CSV data was loaded with headers enabled or false if not
     *
     * @var boolean
     */
    private $_hasHeaders = false;


    /**
     * CSVObject stores all the information for a CSV document and provides easy access to all the
     * columns and values and allows us to operate with it's data easily.
     *
     * @param string $string A string containing valid csv data
     * @param boolean $headers Specifies if the first row of the provided csv data contains the column names or not. It is important to correctly set this value to avoid invalid data
     * @param string $delimiter The character that is used as the csv delimiter. ',' is set by default
     * @param string $enclosure The character that is used to escape fields when special characters are found
     *
     * @throws UnexpectedValueException
     *
     * @return CSVObject The constructed CSVObject
     */
    public function __construct($string = '', bool $headers = false, string $delimiter = ',', string $enclosure = '"'){

        parent::__construct();

        if(!StringUtils::isString($string)){

            throw new UnexpectedValueException('constructor expects a string value');
        }

        if(StringUtils::isEmpty($string)){

            return;
        }

        $currentRow = 0;
        $currentColumn = 0;
        $enclosureFound = false;
        $fieldValue = '';
        $stringLen = strlen($string);

        for ($i = 0; $i < $stringLen; $i++) {

            $character = $string[$i];

            if ($character === $delimiter && !$enclosureFound) {

                $this->_insertField($currentRow, $currentColumn, $fieldValue);

                $fieldValue = '';
                $currentColumn ++;

                continue;
            }

            if($character === $enclosure){

                if($enclosureFound && substr($string, $i + 1, 1) === $enclosure){

                    $fieldValue .= $character;
                    $i++;

                }else{

                    $enclosureFound = !$enclosureFound;

                    if($enclosureFound){

                        $fieldValue = '';

                    }else{

                        $i = $this->_findNextDelimiterIndex($string, $i, $delimiter, $stringLen) - 1;
                    }
                }

                continue;
            }

            if($character === "\r" || $character === "\n"){

                if($enclosureFound){

                    $fieldValue .= $character;

                }else{

                    if($currentColumn > 0){

                        $this->_insertField($currentRow, $currentColumn, $fieldValue);

                        $currentRow ++;
                        $fieldValue = '';
                        $currentColumn = 0;
                    }
                }

                if($character === "\r" && substr($string, $i + 1, 1) === "\n"){

                    if($enclosureFound){

                        $fieldValue .= "\n";
                    }

                    $i++;
                }

                continue;
            }

            $fieldValue .= $character;
        }

        if($fieldValue != '' || $currentColumn >= $this->_columnsCount){

            $this->_insertField($currentRow, $currentColumn, $fieldValue);
        }

        if($headers){

            $this->_defineHeaders();
        }
    }


    /**
     * Get the value contained at the specified csv cell
     *
     * @param integer $row An integer containing the index for the row that we want to retrieve
     * @param mixed $column An integer or a string containing the index or label for the column that we want to retrieve
     *
     * @return string The value for the cell that is located at the specified row and column
     */
    public function getCell(int $row, $column){

        $result = parent::getCell($row, $column);

        return $result === null ? '' : $result;
    }


    /**
     * Set the value for a csv cell
     *
     * @param integer $row An integer containing the index for the row that we want to set
     * @param mixed $column An integer or a string containing the index or label for the column that we want to set
     * @param string $value The value we want to set to the specified cell. Only string values are allowed
     *
     * @see TableObject::setCell
     *
     * @return mixed The assigned value after beign stored into the csv cell
     */
    public function setCell(int $row, $column, $value){

        if(!StringUtils::isString($value)){

            throw new UnexpectedValueException('value must be a string');
        }

        return parent::setCell($row, $column, $value);
    }


    /**
     * Check if the provided value contains valid CSV information.
     *
     * @param mixed $value Object to test for valid CSV data. Accepted values are: Strings containing CSV data or CSVObject elements
     *
     * @return boolean True if the received object represent valid CSV data. False otherwise.
     */
    public static function isCSV($value){

        try {

            $c = new CSVObject($value);

            return $c->countCells() >= 0;

        } catch (Throwable $e) {

            try {

                return ($value !== null) && (get_class($value) === 'org\\turbocommons\\src\\main\\php\\model\\CSVObject');

            } catch (Throwable $e) {

                return false;
            }
        }
    }


    /**
     * Check if two provided CSV structures represent the same data
     *
     * @param mixed $csv A valid string or CSVObject to compare with the current one
     *
     * @return boolean true if the two CSV elements are considered equal, false if not
     */
    public function isEqualTo($csv){

        $objectToCompare = null;

        try {

            $objectToCompare = new CSVObject($csv, $this->_hasHeaders);

        } catch (Throwable $e) {

            try {

                if(get_class($csv) === 'org\\turbocommons\\src\\main\\php\\model\\CSVObject'){

                    $objectToCompare = $csv;
                }

            } catch (Throwable $e) {

                // Nothing to do
            }
        }

        if($objectToCompare == null){

            throw new UnexpectedValueException('csv does not contain valid csv data');
        }

        $thisRows = $this->countRows();
        $thisColumns = $this->countColumns();

        if($this->countCells() === 0 && $objectToCompare->countCells() === 0){

            return true;
        }

        if($this->_hasHeaders && !ArrayUtils::isEqualTo($this->getColumnNames(), $objectToCompare->getColumnNames())){

            return false;
        }

        if($thisRows !== $objectToCompare->countRows() || $thisColumns !== $objectToCompare->countColumns()){

            return false;
        }

        for ($i = 0; $i < $thisRows; $i++) {

            for ($j = 0; $j < $thisColumns; $j++) {

                $thisCell = $this->getCell($i, $j);

                if($thisCell === null){

                    $thisCell = '';
                }

                $cellToCompare = $objectToCompare->getCell($i, $j);

                if($cellToCompare === null){

                    $cellToCompare = '';
                }

                if($thisCell !== $cellToCompare){

                    return false;
                }
            }
        }

        return true;
    }


    /**
     * Generate the textual representation for the csv data stored on this object.
     * The output of this method is ready to be stored on a physical .csv file.
     *
     * @param string $delimiter The character that is used as the csv delimiter. ',' is set by default
     * @param string $enclosure The character that is used to escape fields when special characters are found
     *
     * @return string A valid csv string ready to be stored on a .csv file
     */
    public function toString(string $delimiter = ',', string $enclosure = '"'){

        $result = '';

        if($this->_hasHeaders){

            $row = [];

            foreach ($this->getColumnNames() as $columnValue) {

                $row[] = $this->_escapeField($columnValue, $delimiter, $enclosure);
            }

            $result .= implode($delimiter, $row)."\r\n";
        }

        $rowsCount = $this->countRows();
        $columnsCount = $this->countColumns();

        for ($i = 0; $i < $rowsCount; $i++) {

            $row = [];

            for ($j = 0; $j < $columnsCount; $j++) {

                $cell = '';

                try {

                    $cell = $this->_escapeField($this->_cells->get($i.'-'.$j), $delimiter, $enclosure);

                } catch (Throwable $e) {

                    // Nothing necessary.
                    // This try chatch is used only to improve performance over $this->_cells->isKey($i.'-'.$j)
                }

                $row[] = $cell;
            }

            $result .= implode($delimiter, $row)."\r\n";
        }

        return $rowsCount > 0 ? substr($result, 0, strlen($result) - 2) : $result;
    }


    /**
     * Auxiliary method that is used to add a new field to the table at the specified position
     *
     * @param integer $currentRow The row where we want to add the field
     * @param integer $currentColumn The column where we want to add the field
     * @param string $fieldValue The value we want to add to the field
     *
     * @return void
     */
    private function _insertField(int $currentRow, int $currentColumn, string $fieldValue){

        if ($currentRow >= $this->_rowsCount){

            $this->_rowsCount ++;
        }

        if ($currentColumn >= $this->_columnsCount){

            $this->_columnsCount ++;
        }

        $this->_cells->set($currentRow.'-'.$currentColumn, $fieldValue);
    }


    /**
     * Auxiliary method to correctly format a csv field so it can be stored as a string
     *
     * @param string $field The field that has to be formatted
     * @param string $delimiter The character that is used as the csv delimiter. ',' is set by default
     * @param string $enclosure The character that is used to escape fields when special characters are found
     *
     * @return string The field correctly scaped and ready to be stored on a string
     */
    private function _escapeField(string $field, string $delimiter, string $enclosure){

        if(strpos($field, "\r") !== false || strpos($field, "\n") !== false || strpos($field, $enclosure) !== false || strpos($field, $delimiter) !== false){

            $field = str_replace([$enclosure], [$enclosure.$enclosure], $field);

            $field = $enclosure.$field.$enclosure;
        }

        return $field;
    }


    /**
     * Auxiliary method that looks for the next delimiter or newline characters on the csv string starting at the specified position.
     *
     * @param string $string The full csv string to search in.
     * @param integer $currentIndex The csv string starting point for the search
     * @param string $delimiter The character that is used as the csv delimiter
     *
     * @return integer The index where the next delimiter or newline character is found
     */
    private function _findNextDelimiterIndex(string $string, int $currentIndex, string $delimiter, int $stringLen){

        for ($i = $currentIndex + 1; $i < $stringLen; $i++) {

            $char = $string[$i];

            if($char === $delimiter || $char === "\r" || $char === "\n"){

                return $i;
            }
        }

        return $stringLen;
    }


    /**
     * Auxiliary method to load the first csv row as the column names and avoid duplicate column names
     *
     * @return void
     */
    private function _defineHeaders() {

        $columnNames = $this->getRow(0);

        if(ArrayUtils::hasDuplicateElements($columnNames)){

            $i = 0;
            $result = [];
            $duplicateColumnNames = ArrayUtils::getDuplicateElements($columnNames);

            foreach ($columnNames as $columnName) {

                if($columnName === null || $columnName === ''){

                    $i ++;
                    $columnName = '('.$i.')';

                }else{

                    foreach ($duplicateColumnNames as $duplicateColumnName) {

                        if($columnName === $duplicateColumnName){

                            $i ++;
                            $columnName = $columnName.'('.$i.')';
                            break;
                        }
                    }
                }

                $result[] = $columnName;
            }

            $this->setColumnNames($result);

        }else{

            $this->setColumnNames($columnNames);
        }

        $this->removeRow(0);

        $this->_hasHeaders = true;
    }
}

?>