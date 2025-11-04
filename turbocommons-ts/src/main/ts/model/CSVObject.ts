/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */
 

import { TableObject } from './TableObject';
import { StringUtils } from '../utils/StringUtils';
import { ArrayUtils } from '../utils/ArrayUtils';


/**
 * CSV data abstraction
 */
export class CSVObject extends TableObject{

    
    /**
     * True if the CSV data was loaded with headers enabled or false if not
     */
    private _hasHeaders = false;
    
    
    /**
     * CSVObject stores all the information for a CSV document and provides easy access to all the
     * columns and values and allows us to operate with it's data easily.
     *
     * @param string A string containing valid csv data
     * @param headers Specifies if the first row of the provided csv data contains the column names or not. It is important to correctly set this value to avoid invalid data
     * @param delimiter The character that is used as the csv delimiter. ',' is set by default
     * @param enclosure The character that is used to escape fields when special characters are found
     *
     * @return The constructed CSVObject
     */
    constructor(string = '', headers = false, delimiter = ',', enclosure = '"'){
        
        super();
        
        if(!StringUtils.isString(string)){

            throw new Error('constructor expects a string value');
        }
        
        if(StringUtils.isEmpty(string)){

            return;
        }

        let currentRow = 0;
        let currentColumn = 0;
        let enclosureFound = false;
        let fieldValue = '';
        let stringLen = string.length;

        for (let i = 0; i < stringLen; i++) {

            let character = string.charAt(i);

            if (character === delimiter && !enclosureFound) {

                this._insertField(currentRow, currentColumn, fieldValue);

                fieldValue = '';
                currentColumn ++;

                continue;
            }

            if(character === enclosure){

                if(enclosureFound && string.substr(i + 1, 1) === enclosure){

                    fieldValue += character;
                    i++;

                }else{

                    enclosureFound = !enclosureFound;

                    if(enclosureFound){

                        fieldValue = '';

                    }else{

                        i = this._findNextDelimiterIndex(string, i, delimiter, stringLen) - 1;
                    }
                }

                continue;
            }

            if(character === "\r" || character === "\n"){

                if(enclosureFound){

                    fieldValue += character;

                }else{

                    if(currentColumn > 0){

                        this._insertField(currentRow, currentColumn, fieldValue);

                        currentRow ++;
                        fieldValue = '';
                        currentColumn = 0;
                    }
                }

                if(character === "\r" && string.substr(i + 1, 1) === "\n"){

                    if(enclosureFound){

                        fieldValue += "\n";
                    }

                    i++;
                }

                continue;
            }

            fieldValue += character;
        }

        if(fieldValue != '' || currentColumn >= this._columnsCount){

            this._insertField(currentRow, currentColumn, fieldValue);
        }

        if(headers){

            this._defineHeaders();
        }
    }
    
    
    /**
     * Get the value contained at the specified csv cell
     *
     * @param row An integer containing the index for the row that we want to retrieve
     * @param column An integer or a string containing the index or label for the column that we want to retrieve
     *
     * @return The value for the cell that is located at the specified row and column
     */
    getCell(row: number, column: number|string): string{

        let result = super.getCell(row, column);

        return result === null ? '' : result;
    }
    
    
    /**
     * Set the value for a csv cell
     *
     * @param row An integer containing the index for the row that we want to set
     * @param column An integer or a string containing the index or label for the column that we want to set
     * @param value The value we want to set to the specified cell. Only string values are allowed
     *
     * @see TableObject.setCell
     *
     * @return The assigned value after beign stored into the csv cell
     */
    setCell(row: number, column: number|string, value: any){

        if(!StringUtils.isString(value)){

            throw new Error('value must be a string');
        }

        return super.setCell(row, column, value);
    }


    /**
     * Check if the provided value contains valid CSV information.
     *
     * @param value Object to test for valid CSV data. Accepted values are: Strings containing CSV data or CSVObject elements
     *
     * @return True if the received object represent valid CSV data. False otherwise.
     */
    static isCSV(value: any){

        try {

            let c = new CSVObject(value);

            return c.countCells() >= 0;

        } catch (e) {

            try {

                return (value !== null) && (value instanceof CSVObject);

            } catch (e) {

                return false;
            }
        }
    }


    /**
     * Check if two provided CSV structures represent the same data
     *
     * @param csv A valid string or CSVObject to compare with the current one
     *
     * @return true if the two CSV elements are considered equal, false if not
     */
    isEqualTo(csv: any){

        let objectToCompare = null;

        try {

            objectToCompare = new CSVObject(csv, this._hasHeaders);

        } catch (e) {

            try {

                if(csv instanceof CSVObject){

                    objectToCompare = csv;
                }

            } catch (e) {

                // Nothing to do
            }
        }

        if(objectToCompare == null){

            throw new Error('csv does not contain valid csv data');
        }

        let thisRows = this.countRows();
        let thisColumns = this.countColumns();

        if(this.countCells() === 0 && objectToCompare.countCells() === 0){

            return true;
        }

        if(this._hasHeaders && !ArrayUtils.isEqualTo(this.getColumnNames(), objectToCompare.getColumnNames())){

            return false;
        }

        if(thisRows !== objectToCompare.countRows() || thisColumns !== objectToCompare.countColumns()){

            return false;
        }

        for (let i = 0; i < thisRows; i++) {

            for (let j = 0; j < thisColumns; j++) {

                let thisCell = this.getCell(i, j);

                if(thisCell === null){

                    thisCell = '';
                }

                let cellToCompare = objectToCompare.getCell(i, j);

                if(cellToCompare === null){

                    cellToCompare = '';
                }

                if(thisCell !== cellToCompare){

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
     * @param delimiter The character that is used as the csv delimiter. ',' is set by default
     * @param enclosure The character that is used to escape fields when special characters are found
     *
     * @return A valid csv string ready to be stored on a .csv file
     */
    toString(delimiter = ',', enclosure = '"'){

        let result = '';

        if(this._hasHeaders){

            let row = [];

            for(let columnValue of this.getColumnNames()){

                row.push(this._escapeField(columnValue, delimiter, enclosure));
            }

            result += row.join(delimiter) + "\r\n";
        }

        let rowsCount = this.countRows();
        let columnsCount = this.countColumns();

        for (let i = 0; i < rowsCount; i++) {

            let row = [];

            for (let j = 0; j < columnsCount; j++) {

                let cell = '';

                try {

                    cell = this._escapeField(this._cells.get(i + '-' + j), delimiter, enclosure);

                } catch (e) {

                    // Nothing necessary.
                    // This try chatch is used only to improve performance over $this->_cells->isKey($i.'-'.$j)
                }

                row.push(cell);
            }

            result += row.join(delimiter) + "\r\n";
        }

        return rowsCount > 0 ? result.substring(0, result.length - 2) : result;
    }


    /**
     * Auxiliary method that is used to add a new field to the table at the specified position
     *
     * @param currentRow The row where we want to add the field
     * @param currentColumn The column where we want to add the field
     * @param fieldValue The value we want to add to the field
     *
     * @return void
     */
    private _insertField(currentRow: number, currentColumn: number, fieldValue: string){

        if (currentRow >= this._rowsCount){

            this._rowsCount ++;
        }

        if (currentColumn >= this._columnsCount){

            this._columnsCount ++;
        }

        this._cells.set(currentRow + '-' + currentColumn, fieldValue);
    }


    /**
     * Auxiliary method to correctly format a csv field so it can be stored as a string
     *
     * @param field The field that has to be formatted
     * @param delimiter The character that is used as the csv delimiter. ',' is set by default
     * @param enclosure The character that is used to escape fields when special characters are found
     *
     * @return The field correctly scaped and ready to be stored on a string
     */
    private _escapeField(field: string, delimiter: string, enclosure: string){

        if(field.indexOf("\r") >= 0 || field.indexOf("\n") >= 0 || field.indexOf(enclosure) >= 0 || field.indexOf(delimiter) >= 0){

            field = StringUtils.replace(field, [enclosure], [enclosure + enclosure]);

            field = enclosure + field + enclosure;
        }

        return field;
    }


    /**
     * Auxiliary method that looks for the next delimiter or newline characters on the csv string starting at the specified position.
     *
     * @param string The full csv string to search in.
     * @param currentIndex The csv string starting point for the search
     * @param delimiter The character that is used as the csv delimiter
     *
     * @return The index where the next delimiter or newline character is found
     */
    private _findNextDelimiterIndex(string: string, currentIndex: number, delimiter: string, stringLen: number){

        for (let i = currentIndex + 1; i < stringLen; i++) {

            let char = string.charAt(i);

            if(char === delimiter || char === "\r" || char === "\n"){

                return i;
            }
        }

        return stringLen;
    }


    /**
     * Auxiliary method to load the first csv row as the column names and avoid duplicate column names
     *
     * @return void
     */
    private _defineHeaders() {

        let columnNames = this.getRow(0);

        if(ArrayUtils.hasDuplicateElements(columnNames)){

            let i = 0;
            let result = [];
            let duplicateColumnNames = ArrayUtils.getDuplicateElements(columnNames);

            for(let columnName of columnNames){

                if(columnName === null || columnName === ''){

                    i ++;
                    columnName = '(' + i + ')';

                }else{

                    for(let duplicateColumnName of duplicateColumnNames){

                        if(columnName === duplicateColumnName){

                            i ++;
                            columnName = columnName + '(' + i + ')';
                            break;
                        }
                    }
                }

                result.push(columnName);
            }

            this.setColumnNames(result);

        }else{

            this.setColumnNames(columnNames);
        }

        this.removeRow(0);

        this._hasHeaders = true;
    }
}