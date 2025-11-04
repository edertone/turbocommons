/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


import { StringUtils } from "../utils/StringUtils";
import { ArrayUtils } from "../utils/ArrayUtils";
import { NumericUtils } from "../utils/NumericUtils";
import { HashMapObject } from "./HashMapObject";


/**
 * A 2D table structure
 */
export class TableObject {


    /**
     * Stores a list with all the column names on the table.
     * The values are stored as key / value where key is the column index and value the column label
     */
    protected _columnNames: HashMapObject;


    /**
     * Stores all the table cells data.
     * The values are stored as key / value where key is the row and column index (r-c) and value the stored item
     */
    protected _cells: HashMapObject;


    /**
     * Stores the number of columns on the current table instance
     */
    protected _columnsCount = 0;


    /**
     * Stores the number of rows on the current table instance
     */
    protected _rowsCount = 0;

    
    /**
     * TableObject is an abstraction of a 2D table with X columns and Y rows where each cell can be used to store any kind of data.
     *
     * Columns can be labeled with a textual name which can be used to access them anytime (data can be also accessed via numeric row and column indexes).
     *
     * @param rows The number of rows for the created table (Rows can be added or modified anytime later).
     * @param columns The number of columns to create or an array of strings containing the column labels for all of the columns that will be created (Columns can be added or modified anytime later).
     *
     * @return The constructed TableObject
     */
    constructor(rows = 0, columns: number|string[] = 0){

        if(NumericUtils.isInteger(rows) && rows >= 0){

            this._rowsCount = rows;

        }else{

            throw new Error('constructor rows must be a positive integer');
        }

        if(NumericUtils.isInteger(columns) && columns >= 0){

            this._columnsCount = Number(columns);

        }else{

            if(ArrayUtils.isArray(columns)){

                this._columnsCount = (columns as string[]).length;

                this.setColumnNames(columns as string[]);

            }else{

                throw new Error('constructor columns must be an integer or an array of strings');
            }
        }

        if((this._columnsCount + this._rowsCount > 0) && (this._columnsCount == 0 || this._rowsCount == 0)){

            throw new Error('constructor columns cannot be empty if rows are positive and vice versa');
        }

        this._columnNames = new HashMapObject();

        this._cells = new HashMapObject();
    }
    
    
    /**
     * Set the label to an existing table column.
     *
     * @param column An integer or a string containing the index or label for the column to which we want to assign a label
     * @param name The new label that will be assigned to the specified column
     *
     * @return True if the column name was correctly assigned
     */
    setColumnName(column: number|string, name: string){

        let columnIndex = this._validateColumnIndex(column);

        if(!StringUtils.isString(name)){

            throw new Error('name must be a string');
        }

        this._columnNames.set(String(columnIndex), name);

        return true;
    }
    
    
    /**
     * Define the names for the current table columns (Already defined column names will be overriden).
     *
     * @param names List of names that will be applied to the table columns.
     * It must have the same number of items and in the same order as the table columns.
     *
     * @return The list of column names after beign assigned
     */
    setColumnNames(names: string[]){

        if(this._columnsCount == names.length){

            if(ArrayUtils.hasDuplicateElements(names)){

                throw new Error('array must not contain duplicate elements');
            }

            let namesCount = names.length;
            this._columnNames = new HashMapObject();

            for (let i = 0; i < namesCount; i++) {

                if(!StringUtils.isString(names[i])){

                    throw new Error('List of names must be an array of strings');
                }

                this._columnNames.set(String(i), names[i]);
            }

            return names;
        }

        throw new Error('List of names must match number of columns');
    }
    
    
    /**
     * Get a list with all the currently defined column names in the same order as they are assigned to the table.
     * If the table contains columns but no names are defined, a list with empty strings will be returned
     *
     * @return A list of strings with the column names
     */
    getColumnNames(){

        let result: string[] = [];

        for (let i = 0; i < this._columnsCount; i++) {

            let key = String(i);

            result.push(this._columnNames.isKey(key) ? this._columnNames.get(key) : '');
        }

        return result;
    }
    
    
    /**
     * Get the defined column name for a given column index
     *
     * @param columnIndex a numeric column index
     *
     * @return The column label for the specified numeric index
     */
    getColumnName(columnIndex: number){

        let key = String(this._validateColumnIndex(columnIndex));

        if(this._columnNames.isKey(key)){

            return this._columnNames.get(key);

        }else{

            return '';
        }
    }


    /**
     * Get the numeric column index from it's label
     *
     * @param name The label for an existing column
     *
     * @return The numeric index that is related to the given column label
     */
    getColumnIndex(name: string){

        if(!StringUtils.isString(name) || name === ''){

            throw new Error('value must be a non empty string');
        }

        let keys = this._columnNames.getKeys();

        for (let key of keys) {

            if(this._columnNames.get(key) === name){

                return Number(key);
            }
        }

        throw new Error('provided column name does not exist');
    }


    /**
     * Get all the elements that are located at the specified column index or label.
     *
     * @param column An integer or a string containing the index or label for the column that we want to retrieve
     *
     * @return All the table elements that belong to the required column
     */
    getColumn(column: string|number){

        let result = [];

        let columnIndex = this._validateColumnIndex(column);

        for (let i = 0; i < this._rowsCount; i++) {

            result.push( this.getCell(i, columnIndex));
        }

        return result;
    }


    /**
     * Add the specified amount of columns to the table.
     *
     * @param number The number of columns that will be added to the table
     * @param names Optionally we can list all the labels to define for the new columns that will be added
     * @param at Defines the column index where the new columns will be inserted. Old columns that are located at the insertion point will not be deleted, they will be moved to the Right. By default all the new columns will be appended at the end of the table unless a positive value is specified here.
     *
     * @return True if the operation was successful
     */
    addColumns(number: number, names: string[] = [], at = -1){

        if(!ArrayUtils.isArray(names)){

            throw new Error('names must be an array');
        }
        
        if(!NumericUtils.isInteger(number) || number <= 0){

            throw new Error('number must be a positive integer');
        }

        if(!NumericUtils.isInteger(at) || at < -1 || at >= this._columnsCount){

            throw new Error('at must be a valid column index');
        }

        if(at >= 0){

            for (let i = this._columnsCount - 1; i >= at; i--) {

                if(this._columnNames.isKey(String(i))){

                    this._columnNames.rename(String(i), String(i + number));
                }

                for(let j = 0; j < this._rowsCount; j++){

                    let rowAndCol = String(j) + '-' + String(i);

                    if(this._cells.isKey(rowAndCol)){

                        this._cells.rename(rowAndCol, String(j) + '-' + String(i + number));
                    }
                }
            }
        }

        // Add the new column labels if defined
        let namesCount = names.length;

        if(namesCount > 0){

            if(namesCount != number){

                throw new Error('names length must be the same as number');
            }

            let colIndex = at < 0 ? this._columnsCount : at;

            for (let i = 0; i < namesCount; i++) {

                this._columnNames.set(String(colIndex + i), names[i]);
            }
        }

        this._columnsCount += number;

        return true;
    }


    /**
     * Fill the data on all the rows for the given column index or label
     *
     * @param column An integer or a string containing the index or label for the column that we want to fill
     * @param data An array with all the values that will be assigned to the table rows on the specified column. Array length must match rows number
     *
     * @return void
     */
    setColumn(column: number|string, data: any[]){

        let dataCount = data.length;

        if(dataCount <= 0){

            throw new Error('data must not be empty');
        }

        if(this._rowsCount != dataCount){

            throw new Error('data length and number of rows must match');
        }

        let columnIndex = this._validateColumnIndex(column);

        for (let i = 0; i < this._rowsCount; i++) {

            this.setCell(i, columnIndex, data[i]);
        }
    }


    /**
     * Delete a whole column and all its related data from the table
     *
     * @param column An integer or a string containing the index or label for the column that we want to delete
     *
     * @return void
     */
    removeColumn(column: number|string){

        let columnIndex = this._validateColumnIndex(column);

        // Remove column name if it exists
        if(this._columnNames.isKey(String(columnIndex))){

            this._columnNames.remove(String(columnIndex));
        }

        // Remove all column values if they exist
        for(let i = 0; i < this._rowsCount; i++){

            let rowAndCol = String(i) + '-' + String(columnIndex);

            if(this._cells.isKey(rowAndCol)){

                this._cells.remove(rowAndCol);
            }
        }

        // Update indices for all columns that are after the removed one
        for (let i = columnIndex + 1; i < this._columnsCount; i++) {

            if(this._columnNames.isKey(String(i))){

                this._columnNames.rename(String(i), String(i - 1));
            }

            for(let j = 0; j < this._rowsCount; j++){

                let rowAndCol = String(j) + '-' + String(i);

                if(this._cells.isKey(rowAndCol)){

                    this._cells.rename(rowAndCol, String(j) + '-' + String(i - 1));
                }
            }
        }

        this._columnsCount --;

        if(this._columnsCount <= 0){

            this._rowsCount = 0;
        }
    }


    /**
     * Get the value contained at the specified table cell
     *
     * @param row An integer containing the index for the row that we want to retrieve
     * @param column An integer or a string containing the index or label for the column that we want to retrieve
     *
     * @return The value for the cell located at the specified row and column or null if no data is defined for it
     */
    getCell(row: number, column: number|string){

        let rowIndex = this._validateRowIndex(row);
        let columnIndex = this._validateColumnIndex(column);

        let key = rowIndex + '-' + columnIndex;

        if(this._cells.isKey(key)){

            return this._cells.get(key);

        }else{

            return null;
        }
    }


    /**
     * Set the value for a table cell
     *
     * @param row An integer containing the index for the row that we want to set
     * @param column An integer or a string containing the index or label for the column that we want to set
     * @param value The value we want to set to the specified cell. Any type is allowed, and different cells can contain values of different types.
     *
     * @return The assigned value after beign stored into the table cell
     */
    setCell(row: number, column: number|string, value: any){

        let rowIndex = this._validateRowIndex(row);
        let columnIndex = this._validateColumnIndex(column);

        return this._cells.set(rowIndex + '-' + columnIndex, value);
    }


    /**
     * Get all the elements that are located at the specified row index
     *
     * @param row An integer containing the index for the row that we want to retrieve
     *
     * @return All the table elements that belong to the required row
     */
    getRow(row: number){

        let result = [];

        let rowIndex = this._validateRowIndex(row);

        for (let i = 0; i < this._columnsCount; i++) {

            result.push(this.getCell(rowIndex, i));
        }

        return result;
    }


    /**
     * Add the specified amount of rows to the table.
     *
     * @param number The number of rows that will be added to the table
     * @param at Defines the row index where the new rows will be inserted. Old rows that are located at the insertion point will not be deleted, they will be moved down. By default all the new rows will be appended at the bottom of the table unless a positive value is specified here.
     *
     * @return True if the operation was successful
     */
    addRows(number: number, at = -1){

        if(!NumericUtils.isInteger(number) || number <= 0){

            throw new Error('number must be a positive integer');
        }

        if(!NumericUtils.isInteger(at) || at < -1 || at >= this._rowsCount){

            throw new Error('at must be a valid row index');
        }

        if(at >= 0){

            for (let i = this._rowsCount - 1; i >= at; i--) {

                for(let j = 0; j < this._columnsCount; j++){

                    let rowAndCol = String(i) + '-' + String(j);

                    if(this._cells.isKey(rowAndCol)){

                        this._cells.rename(rowAndCol, (i + number) + '-' + String(j));
                    }
                }
            }
        }

        this._rowsCount += number;

        return true;
    }


    /**
     * Fill all the data for the specified row
     *
     * @param row An integer containing the index for the row that we want to set
     * @param data An array with all the values that will be assigned to the table row. Array length must match columns number
     *
     * @return void
     */
    setRow(row: number, data: any[]){

        let dataCount = data.length;

        if(dataCount <= 0){

            throw new Error('data must not be empty');
        }

        if(this._columnsCount != dataCount){

            throw new Error('data length and number of columns must match');
        }

        let rowIndex = this._validateRowIndex(row);

        for (let i = 0; i < this._columnsCount; i++) {

            this.setCell(rowIndex, i, data[i]);
        }
    }


    /**
     * Delete a whole row and all its related data from the table
     *
     * @param row An integer containing the index for the row that we want to delete
     *
     * @return void
     */
    removeRow(row: number){

        let rowIndex = this._validateRowIndex(row);

        // Remove all row values if they exist
        for(let i = 0; i < this._columnsCount; i++){

            let rowAndCol = String(rowIndex) + '-' + String(i);

            if(this._cells.isKey(rowAndCol)){

                this._cells.remove(rowAndCol);
            }
        }

        // Update indices for all rows that are after the removed one
        for (let i = rowIndex + 1; i < this._rowsCount; i++) {

            for(let j = 0; j < this._columnsCount; j++){

                let rowAndCol = String(i) + '-' + String(j);

                if(this._cells.isKey(rowAndCol)){

                    this._cells.rename(rowAndCol, String(i - 1) + '-' + String(j));
                }
            }
        }

        this._rowsCount --;

        if(this._rowsCount <= 0){

            this._columnsCount = 0;
        }
    }


    /**
     * Get the total number of rows that are currently available on this table
     *
     * @return The total number of rows on the table
     */
    countRows(){

        return this._rowsCount;
    }


    /**
     * Get the total number of columns that are currently available on this table
     *
     * @return The total number of columns on the table
     */
    countColumns(){

        return this._columnsCount;
    }


    /**
     * Get the total number of cells that are currently available on this table
     *
     * @return The total number of cells on the table
     */
    countCells(){

        return this._rowsCount * this._columnsCount;
    }


    /**
     * Auxiliary method to validate that a given column index or label belongs to the current table
     *
     * @param column An integer or a string containing the index or label for the column that we want to validate
     *
     * @return A valid column index based on the specified integer or label.
     */
    private _validateColumnIndex(column: number|string){

        let columnIndex = NumericUtils.isInteger(column) ? Number(column) : -1;
        let columnNames = this._columnNames.getValues();
        let columnNamesKeys = this._columnNames.getKeys();
        let columnNamesCount = columnNames.length;

        if(StringUtils.isString(column)){

            for (let i = 0; i < columnNamesCount; i++) {

                if(column === columnNames[i]){

                    columnIndex = Number(columnNamesKeys[i]);

                    break;
                }
            }
        }

        if(columnIndex < 0 || columnIndex >= this._columnsCount){

            throw new Error('Invalid column value');
        }

        return columnIndex;
    }


    /**
     * Auxiliary method to validate that a given row index belongs to the current table
     *
     * @param row An integer containing the index for the row that we want to validate
     *
     * @return A valid row index based on the specified integer
     */
    private _validateRowIndex(row: number){

        let rowIndex = NumericUtils.isInteger(row) ? row : -1;

        if(rowIndex < 0 || rowIndex >= this._rowsCount){

            throw new Error('Invalid row value');
        }

        return rowIndex;
    }
    
}