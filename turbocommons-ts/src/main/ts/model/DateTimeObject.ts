/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


import { StringUtils } from '../utils/StringUtils';
import { NumericUtils } from "../utils/NumericUtils";
 

/**
 * date and time format object abstraction based on ISO 8601 standard
 * TODO - This is a first implementation of this class which must be strictly tested and completed by taking the php version as a reference
 */
export class DateTimeObject {

    
    /**
     * String that defines the ISO 8601 format to be used internally when calling the format method on DateTime Php class.
     */
//    private _iso8601FormatString = 'Y-m-d\\TH:i:s.uP';


    /**
     * The date and time values that are stored on this instance are saved as an ISO 8601 string
     */
    private _dateTimeString = '';


    /**
     * An exploded version of this instance _dateTimeString ISO string, that is used to improve performance when reading
     * some of the date values
     */
    private _dateTimeStringExploded:string[] = [];


    /**
     * Object that represents a date and time value with timezone and its related operations.
     *
     * All the class methods are based and expect values that follow the ISO 8601 format, which is the international standard for the
     * representation of dates and times. Any other date/time format will be considered as invalid.<br><br>
     * Following is an example of a valid ISO 8601 dateTime value:<br><br>yyyy-mm-ddTHH:MM:SS.UUU+TT:TT<br><br>where:<br>
     * - yyyy is a four digits year value<br>
     * - mm is a two digits month value<br>
     * - dd is a two digits day value<br>
     * - HH is a two digits hour value<br>
     * - MM is a two digits minutes value<br>
     * - SS is a two digits seconds value<br>
     * - UUU is an arbitrary number of digits decimal seconds fraction value<br>
     * - +TT:TT is the timezone offset value, like +03:00
     *
     * IMPORTANT: It is highly recommended to always physically store your datetime values as UTC (aka 00 timezone offset).
     * The local timezone offset should be applied only when showing the datetime values to the user. All the other date and time usages of your application
     * should be performed with UTC values.
     *
     * @param dateTimeString A string containing a valid ISO 8601 date/time value that will be used to initialize this instance.
     * If string is empty, the current system date/time WITH UTC TIMEZONE will be used. If string is incomplete, all missing parts will be filled
     * with the lowest possible value. If timezone offset is missing, UTC will be used.
     *
     * @example '1996' Will create a DateTimeObject with the value '1996-01-01T00:00:00.000000+00:00' based on the UTC 00 timezone
     * @example '1996-12' Will create a DateTimeObject with the value '1996-12-01T00:00:00.000000+00:00' based on the UTC 00 timezone
     * @example This is a fully valid ISO 8601 string value: '2017-10-14T17:55:25.163583+02:00'
     *
     * @see https://es.wikipedia.org/wiki/ISO_8601
     *
     * @return The created instance
     */
    constructor(dateTimeString = ''){

        if(StringUtils.isEmpty(dateTimeString)){

            this._dateTimeStringExploded = this._explodeISO8601String((new Date()).toISOString());

            return;
        }
//
//        if(!DateTimeObject::isValidDateTime($dateTimeString)){
//
//            throw new UnexpectedValueException('Provided value is not a valid ISO 8601 date time format');
//        }
//
//        $v = this._explodeISO8601String($dateTimeString);
//
//        $string = $v[0].'-'.$v[1].'-'.$v[2].'T'.$v[3].':'.$v[4].':'.$v[5].'.'.$v[6].$v[7];
//
//        this._dateTimeString = (new DateTime($string, $v[7] === '' ? new DateTimeZone('UTC') : null))->format(this._iso8601FormatString);
//
        this._dateTimeStringExploded = this._explodeISO8601String(this._dateTimeString);
    }


    /**
     * DateTimeObject class operates only with ISO 8601 strings, which is the international standard for the representation of dates and times.
     * Therefore, this method considers a dateTime string value to be valid only if it is a string that follows that standard or a DateTimeObject instance.
     *
     * @param dateTime A string containing a valid ISO 8601 date/time value or a valid DateTimeObject instance.
     *
     * @see DateTimeObject.constructor()
     *
     * @return true if the specified value is ISO 8601 or a DateTimeObject instance, false if value contains invalid information.
     */
    static isValidDateTime(dateTime:string|DateTimeObject){

        // Validate that is a string and ends only with alphanumeric values
        if(StringUtils.isString(dateTime) && (dateTime as string).substr(-1).match(/^[a-z0-9]+$/i)){

            let regex = /^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/;

            if ((dateTime as string).match(regex)){

                // We must also validate that the day, month and year are a correct date value
                let parsedDate = (dateTime as string).split('-');

                if(parsedDate.length >= 3){

                    let testDate = new Date(Number(parsedDate[0]), Number(parsedDate[1]), Number(parsedDate[2].substr(0, 2)));
                    
                    return (testDate.getMonth() + 1 === Number(parsedDate[1])) &&
                           (testDate.getDate() === Number(parsedDate[2].substr(0, 2))) &&
                           (testDate.getFullYear() === Number(parsedDate[0]));

                }else{

                    return true;
                }
            }
        }

        return typeof dateTime === typeof DateTimeObject;
    }


    /**
     * Given two valid dateTime values, this method will check if they represent the same date and time value
     *
     * @param dateTime1 A valid ISO 8601 dateTime string or a DateTimeObject instance.
     * @param dateTime2 A valid ISO 8601 dateTime string or a DateTimeObject instance.
     *
     * @see DateTimeObject.__constructor()
     *
     * @return True if the date and time values on both elements are the same
     */
    static isEqual(dateTime1:string|DateTimeObject, dateTime2:string|DateTimeObject){

        return DateTimeObject.compare(dateTime1, dateTime2) === 0;
    }


    /**
     * Returns the month name from a numeric month value
     *
     * @param month A month number between 1 and 12
     *
     * @return the month name in english and with capital letters, like: JANUARY, FEBRUARY, ...
     */
    static getMonthName(month:number){

        if(!NumericUtils.isNumeric(month) || month > 12 || month < 1){

            throw new Error('Provided value is not a valid month number between 1 and 12');
        }

        let months = ['JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'];

        return  months[month - 1];
    }


    /**
     * Get the english name representing the given numeric value of a week day (between 1 and 7), where
     * Sunday is considered to be the first one: 1 = Sunday, 2 = Monday, 3 = Tuesday, etc ...
     *
     * @param day A day number between 1 and 7
     *
     * @return The day name in english and with capital letters, like for example: MONDAY, SATURDAY...
     */
    static getDayName(day:number){

        if(!NumericUtils.isNumeric(day) || day > 7 || day < 1){

            throw new Error('Provided value is not a valid day number between 1 and 7');
        }

        let days = ['SUNDAY', 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY'];

        return days[day - 1];
    }


    /**
     * Get the year based on current system date and timezone
     *
     * @return A numeric value representing the current year
     */
    static getCurrentYear(){

        return (new Date()).getFullYear();
    }


    /**
     * Get the month based on current system date and timezone as a numeric value from 1 to 12
     *
     * @return A value between 1 and 12
     */
    static getCurrentMonth(){

        return (new Date()).getMonth() + 1;
    }


    /**
     * Get the day based on current system date and timezone as a numeric value from 1 to 31
     *
     * @return The day of month as a value between 1 and 31
     */
    static getCurrentDay(){

        return (new Date()).getDate();
    }


    /**
     * Get the numeric day of week (between 1 and 7) based on current system time, where Sunday is considered
     * to be the first one:<br>
     * 1 = Sunday, 2 = Monday, 3 = Tuesday, etc ...
     *
     * @return A numeric value between 1 and 7  (where Sunday is 1, Monday is 2, ...)
     */
    static getCurrentDayOfWeek(){

//        return (int) ((new DateTime())->format('w') + 1);
    }


    /**
     * Get the hour based on current system date and timezone as a numeric value from 0 to 23
     *
     * @return The hour as a value between 0 and 23
     */
    static getCurrentHour(){

        return (new Date()).getHours();
    }


    /**
     * Get the minute based on current system date and timezone as a numeric value from 0 to 59
     *
     * @return The minute as a value between 0 and 59
     */
    static getCurrentMinute(){

        return (new Date()).getMinutes();
    }


    /**
     * Get the seconds based on current system date and timezone as a numeric value from 0 to 59
     *
     * @return The seconds as a value between 0 and 59
     */
    static getCurrentSecond(){

        return (new Date()).getSeconds();
    }


    /**
     * Get the miliseconds based on current system date and timezone as a numeric value up to 3 digits
     *
     * @return The miliseconds as a value up to 3 digits
     */
    static getCurrentMiliSecond(){

        return Math.round((new Date()).getMilliseconds() / 1000);
    }


    /**
     * Get the microseconds based on current system date and timezone as a numeric value up to 6 digits
     *
     * @return The microseconds as a value up to 6 digits
     */
    static getCurrentMicroSecond(){
    
        // TODO
        // return (new Date()).getm->format('u');
    }


    /**
     * Get the timezone offset based on current system date and timezone as a numeric value (in seconds)
     *
     * @return The timezone offset as a numeric value in seconds
     */
    static getCurrentTimeZoneOffset(){

        return (new Date()).getTimezoneOffset();
    }


    /**
     * This method compares two dateTime values and tells if they are exactly the same or
     * which one represents a later time value than the other.
     * Timezones from the specified dateTime values are taken into consideration for the comparison.
     *
     * @param dateTime1 A valid ISO 8601 dateTime value or a DateTimeObject instance.
     * @param dateTime2 A valid ISO 8601 dateTime value or a DateTimeObject instance.
     *
     * @throws UnexpectedValueException
     *
     * @return 0 If the two dateTime values represent the exact same time, 1 if dateTime1 > dateTime2 or 2 if dateTime2 > dateTime1
     */
    static compare(dateTime1:string|DateTimeObject, dateTime2:string|DateTimeObject){

        if(DateTimeObject.isValidDateTime(dateTime1) && DateTimeObject.isValidDateTime(dateTime2)){

            if(StringUtils.isString(dateTime1)){

                dateTime1 = new DateTimeObject(dateTime1 as string);
            }

            if(StringUtils.isString(dateTime2)){

                dateTime2 = new DateTimeObject(dateTime2 as string);
            }

            (dateTime1 as DateTimeObject).setUTC();
            (dateTime2 as DateTimeObject).setUTC();

            let date1 = dateTime1.toString();
            let date2 = dateTime2.toString();

            if(date1 === date2){

                return 0;
            }

            let sortedDates = [date1, date2];

            sortedDates.sort();

            return (sortedDates[0] === date1) ? 2 : 1;
        }

        throw new Error('Provided value is not a valid ISO 8601 date time format');
    }


    /**
     * Get this instance's defined year as a numeric value
     *
     * @return A 4 digits numeric value
     */
    getYear(){

        return Number(this._dateTimeStringExploded[0]);
    }


    /**
     * Get this instance's defined month as a numeric value from 1 to 12
     *
     * @return A value between 1 and 12
     */
    getMonth(){

        return Number(this._dateTimeStringExploded[1]);
    }


    /**
     * Get this instance's defined day as a numeric value from 1 to 31
     *
     * @return A value between 1 and 31
     */
    getDay(){

        return Number(this._dateTimeStringExploded[2]);
    }


    /**
     * Get this instance's defined day of week as a numeric value from 1 to 7, where Sunday is considered
     * to be the first one:<br>
     * 1 = Sunday, 2 = Monday, 3 = Tuesday, etc ...
     *
     * @return A numeric value between 1 and 7
     */
    getDayOfWeek(){
// TODO
//        let v = this._dateTimeStringExploded;
//
//        let dateTimeInstance = new DateTime();
//
//        $dateTimeInstance->setDate($v[0], $v[1], $v[2]);
//
//        return $dateTimeInstance->format('w') + 1;
    }


    /**
     * Get this instance's defined hour as a numeric value from 0 to 23
     *
     * @return A value between 0 and 23
     */
    getHour(){

        return Number(this._dateTimeStringExploded[3]);
    }


    /**
     * Get this instance's defined minute as a numeric value from 0 to 59
     *
     * @return A value between 0 and 59
     */
    getMinute(){

        return Number(this._dateTimeStringExploded[4]);
    }


    /**
     * Get this instance's defined second as a numeric value from 0 to 59
     *
     * @return A value between 0 and 59
     */
    getSecond(){

        return Number(this._dateTimeStringExploded[5]);
    }


    /**
     * Get this instance's defined miliseconds as a numeric value up to 3 digit
     *
     * @return A value up to 3 digit
     */
    getMiliSecond(){

        return Math.round(this.getMicroSecond() / 1000);
    }


    /**
     * Get this instance's defined microseconds as a numeric value up to 6 digit
     *
     * @return A value up to 6 digit
     */
    getMicroSecond(){

        return Number(this._dateTimeStringExploded[6]);
    }


    /**
     * Get this instance's defined timezone offset as a numeric value (in seconds)
     *
     * @return The UTC timezone offset in seconds
     */
    getTimeZoneOffset(){

        return (new Date(this._dateTimeString)).getTimezoneOffset();
    }


    /**
     * Get the first day of month for the current dateTime value.
     *
     * @return A dateTime object representing the first day of month based on the current instance
     */
    getFirstDayOfMonth(){
// TODO
//        $dateTime = (new DateTime(this._dateTimeString))->format('Y-m-01\\TH:i:s.uP');
//
//        return new DateTimeObject($dateTime);
    }


    /**
     * Get the last day of month for the current dateTime value.
     *
     * @return A dateTime object representing the last day of month based on the current instance
     */
    getLastDayOfMonth(){
// TODO
//        $dateTime = (new DateTime(this._dateTimeString))->format('Y-m-t\\TH:i:s.uP');
//
//        return new DateTimeObject($dateTime);
    }


    /**
     * Convert the current instance date and time values to the specified timezone offset.
     *
     * @param offset One of the supported timezone names or an offset value (+0200, +05:00, -0300, -03:00, etc...)
     *
     * @return This object instance
     */
//    setTimeZoneOffset(offset:string){
// TODO
//        $dateTime = new DateTime(this._dateTimeString);
//
//        $dateTime->setTimezone(new DateTimeZone($offset));
//
//        this._dateTimeString = $dateTime->format(this._iso8601FormatString);
//
//        this._dateTimeStringExploded = this._explodeISO8601String(this._dateTimeString);
//
//        return $this;
//    }


    /**
     * Convert the current instance date and time values to the local timezone offset.
     *
     * @return This object instance
     */
    setLocalTimeZone(){
// TODO
//        return this.setTimeZoneOffset(date_default_timezone_get());
    }


    /**
     * Check if the current instance timezone is the UTC +00:00 value
     *
     * @return True if the instance timezone is UTC, false if not
     */
    isUTC(){

        return this.getTimeZoneOffset() === 0;
    }


    /**
     * Convert the current instance date and time values to the UTC zero timezone offset.
     *
     * @example If this instance contains a +02:00 timezone offset, after calling this method the offset will be +00:00 (date and time will be updated accordingly)
     *
     * @return This object instance
     */
    setUTC(){
// TODO
//        let dateTimeInstance = new Date(this._dateTimeString);

//        dateTimeInstance->setTimezone(new DateTimeZone('UTC'));
//
//        this._dateTimeString = $dateTimeInstance->format(this._iso8601FormatString);

        this._dateTimeStringExploded = this._explodeISO8601String(this._dateTimeString);

        return this;
    }


    /**
     * Output the current dateTime instance data as a custom formatted string (by default a full ISO 8601 string).
     *
     * @param formatString A string containing the output format like 'd/m/Y' or 'm-d-y'
     * where the following characters will be automatically replaced:<br><br>
     * - Y with a four digit year value<br>
     * - y with a one or two digit year value<br>
     * - M with a two digit month value<br>
     * - m with a one or two digit month value<br>
     * - D with a two digit day value<br>
     * - d with a one or two digit day value<br>
     * - H with a two digit hour value<br>
     * - h with a one or two digit hour value<br>
     * - N with a two digit minutes value<br>
     * - n with a one or two digit minutes value<br>
     * - S with a two digit seconds value<br>
     * - s with a one or two digit seconds value<br>
     * - U with a 6 digit microseconds value<br>
     * - u with a 3 digit miliseconds value<br>
     * - Offset with the timezone offset value (including the + or - symbol)
     *
     * @return The dateTime with the specified format.
     */
    toString(formatString = 'Y-M-DTH:N:S.UOffset'){

        let exploded = this._dateTimeStringExploded;

        // Get the time zone offset
        formatString = StringUtils.replace(formatString, 'Offset', exploded[7].substr(0));

        // Get the year
        formatString = StringUtils.replace(formatString, 'Y', exploded[0]);
        formatString = StringUtils.replace(formatString, 'y', exploded[0].substr(2));

        // Get the month
        formatString = StringUtils.replace(formatString, 'M', exploded[1]);
        formatString = StringUtils.replace(formatString, 'm', String(Number(exploded[1])));

        // Get the day
        formatString = StringUtils.replace(formatString, 'D', exploded[2]);
        formatString = StringUtils.replace(formatString, 'd', String(Number(exploded[2])));

        // Get the hour
        formatString = StringUtils.replace(formatString, 'H', exploded[3]);
        formatString = StringUtils.replace(formatString, 'h', String(Number(exploded[3])));

        // Get the minute
        formatString = StringUtils.replace(formatString, 'N', exploded[4]);
        formatString = StringUtils.replace(formatString, 'n', String(Number(exploded[4])));

        // Get the second
        formatString = StringUtils.replace(formatString, 'S', exploded[5]);
        formatString = StringUtils.replace(formatString, 's', String(Number(exploded[5])));

        // Get the milisecond
        formatString = StringUtils.replace(formatString, 'u', StringUtils.pad(String(Math.min(999, Math.round(Number(exploded[6]) / 1000))), 3, '0'));

        // Get the microsecond
        return StringUtils.replace(formatString, 'U', StringUtils.pad(exploded[6], 6, '0', 'RIGHT'));
    }


    /**
     * Compares the current datetime instance value to the given one and tells if they
     * are exactly the same or which one represents a later time value than the other.
     * Timezones from the both dateTime values are taken into consideration for the comparison.
     *
     * @param dateTime A valid ISO 8601 dateTime value or a DateTimeObject instance.
     *
     * @throws Error
     *
     * @see DateTimeObject.compare
     *
     * @return 0 If the two dateTime values represent the exact same time, 1 if this instance > dateTime or 2 if dateTime > this instance
     */
    compareTo(dateTime:string|DateTimeObject){

        return DateTimeObject.compare(this, dateTime);
    }


    /**
     * Check if the provided ISO 8601 dateTime value is identical to the date and time from this instance
     *
     * @param dateTime A valid ISO 8601 dateTime string or a DateTimeObject instance.
     *
     * @return True if both dateTime values are equivalent to the exact same date and time
     */
    isEqualTo(dateTime:string|DateTimeObject){

        return DateTimeObject.compare(this, dateTime) === 0;
    }


    /**
     * Adds the specified amount of time to the given dateTime value
     * TODO - This method currently only works with years, and PHPDoc is incomplete!
     *
     * @param value The numeric amount that will be added to this DateTimeObject instance
     * @param type TODO - this paramenter description
     *
     * @return void
     */
//    add(value: Number, type = 'minutes'){

        // TODO - copy from php when finished there
//    }


    /**
     * TODO - this method depends on this.add for being finished
     */
//    substract(value: Number, type = 'minutes'){

//        return this.add(-value, type);
//    }


    /**
     * Auxiliary method that is used to generate an array with all the values that are defined on an ISO 8601 string
     *
     * @param string A valid ISO 8601 string
     *
     * @return An array with all the date time values extracted
     */
    private _explodeISO8601String(string:string){

        let result = ['', '01', '01', '00', '00', '00', '000000', ''];

        if(string.substr(string.length - 1, 1).toLowerCase() === 'z'){

            string = string.substr(0, string.length - 1) + '+00:00';
        }

        let splitted = string.split(/[+-.: TZ]/);

        let i = 0;

        while(splitted.length > 0 && i < 6){

            result[i] = splitted.shift() as string;

            i++;
        }

        let splittedCount = splitted.length;

        if(splittedCount === 1 || splittedCount === 3){

            // NOTE: Javascript cannot provide microseconds, so we add three zeros here to correctly format it
            result[6] = splitted.shift() as string + '000';
        }

        if(splittedCount === 2 || splittedCount === 3){

            result[7] = string.substr(string.length - 6, 1) + splitted[0] + ':' + splitted[1];
        }

        return result;
    }
}