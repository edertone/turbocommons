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

use DateTime;
use DateTimeZone;
use UnexpectedValueException;
use org\turbocommons\src\main\php\utils\NumericUtils;
use org\turbocommons\src\main\php\utils\StringUtils;


/**
 * date and time format object abstraction based on ISO 8601 standard
 */
class DateTimeObject{


    /**
     * String that defines the ISO 8601 format to be used internally when calling the format method on DateTime Php class.
     *
     * @var string
     */
    private $_iso8601FormatString = 'Y-m-d\\TH:i:s.uP';


    /**
     * The date and time values that are stored on this instance are saved as an ISO 8601 string
     *
     * @var string
     */
    private $_dateTimeString = '';


    /**
     * An exploded version of this instance _dateTimeString ISO string, that is used to improve performance when reading
     * some of the date values
     *
     * @var array
     */
    private $_dateTimeStringExploded = [];


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
     * @param string $dateTimeString A string containing a valid ISO 8601 date/time value that will be used to initialize this instance.
     * If string is empty, the current system date/time WITH UTC TIMEZONE will be used. If string is incomplete, all missing parts will be filled
     * with the lowest possible value. If timezone offset is missing, UTC will be used.
     *
     * @example '1996' Will create a DateTimeObject with the value '1996-01-01T00:00:00.000000+00:00' based on the UTC 00 timezone
     * @example '1996-12' Will create a DateTimeObject with the value '1996-12-01T00:00:00.000000+00:00' based on the UTC 00 timezone
     * @example This is a fully valid ISO 8601 string value: '2017-10-14T17:55:25.163583+02:00'
     *
     * @see https://es.wikipedia.org/wiki/ISO_8601
     *
     * @return DateTimeObject The created instance
     */
    public function __construct($dateTimeString = ''){

        if(StringUtils::isEmpty($dateTimeString)){

            $this->_dateTimeString = (new DateTime(null, new DateTimeZone('UTC')))->format($this->_iso8601FormatString);

            $this->_dateTimeStringExploded = $this->_explodeISO8601String($this->_dateTimeString);

            return;
        }

        if(!DateTimeObject::isValidDateTime($dateTimeString)){

            throw new UnexpectedValueException('Provided value is not a valid ISO 8601 date time format');
        }

        $v = $this->_explodeISO8601String($dateTimeString);

        $string = $v[0].'-'.$v[1].'-'.$v[2].'T'.$v[3].':'.$v[4].':'.$v[5].'.'.$v[6].$v[7];

        $this->_dateTimeString = (new DateTime($string, $v[7] === '' ? new DateTimeZone('UTC') : null))->format($this->_iso8601FormatString);

        $this->_dateTimeStringExploded = $this->_explodeISO8601String($this->_dateTimeString);
    }


    /**
     * DateTimeObject class operates only with ISO 8601 strings, which is the international standard for the representation of dates and times.
     * Therefore, this method considers a dateTime string value to be valid only if it is a string that follows that standard or a DateTimeObject instance.
     *
     * @param mixed $dateTime A string containing a valid ISO 8601 date/time value or a valid DateTimeObject instance.
     *
     * @see DateTimeObject::__construct
     *
     * @return boolean true if the specified value is ISO 8601 or a DateTimeObject instance, false if value contains invalid information.
     */
    public static function isValidDateTime($dateTime){

        // Validate that is a string and ends only with alphanumeric values
        if(is_string($dateTime) && ctype_alnum(substr($dateTime, -1))){

            $regex = '/^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/';

            if (preg_match($regex, $dateTime) > 0){

                // We must also validate that the day, month and year are a correct date value
                $parsedDate = explode('-', $dateTime);

                if(count($parsedDate) >= 3){

                    return checkdate($parsedDate[1], substr($parsedDate[2], 0, 2), $parsedDate[0]);

                }else{

                    return true;
                }
            }
        }

        return (is_object($dateTime) && get_class($dateTime) === 'org\\turbocommons\\src\\main\\php\\model\\DateTimeObject');
    }


    /**
     * Given two valid dateTime values, this method will check if they represent the same date and time value
     *
     * @param mixed $dateTime1 A valid ISO 8601 dateTime string or a DateTimeObject instance.
     * @param mixed $dateTime2 A valid ISO 8601 dateTime string or a DateTimeObject instance.
     *
     * @see DateTimeObject::__construct
     *
     * @return boolean True if the date and time values on both elements are the same
     */
    public static function isEqual($dateTime1, $dateTime2){

        return self::compare($dateTime1, $dateTime2) === 0;
    }


    /**
     * Returns the month name from a numeric month value
     *
     * @param int $month A month number between 1 and 12
     *
     * @return string the month name in english and with capital letters, like: JANUARY, FEBRUARY, ...
     */
    public static function getMonthName($month){

        if(!NumericUtils::isNumeric($month) || $month > 12 || $month < 1){

            throw new UnexpectedValueException('Provided value is not a valid month number between 1 and 12');
        }

        $months = ['JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'];

        return  $months[$month - 1];
    }


    /**
     * Get the english name representing the given numeric value of a week day (between 1 and 7), where
     * Sunday is considered to be the first one: 1 = Sunday, 2 = Monday, 3 = Tuesday, etc ...
     *
     * @param int $day A day number between 1 and 7
     *
     * @return string The day name in english and with capital letters, like for example: MONDAY, SATURDAY...
     */
    public static function getDayName($day){

        if(!NumericUtils::isNumeric($day) || $day > 7 || $day < 1){

            throw new UnexpectedValueException('Provided value is not a valid day number between 1 and 7');
        }

        $days = ['SUNDAY', 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY'];

        return $days[$day - 1];
    }


    /**
     * Get the year based on current system date and timezone
     *
     * @return int A numeric value representing the current year
     */
    public static function getCurrentYear(){

        return (int) (new DateTime())->format('Y');
    }


    /**
     * Get the month based on current system date and timezone as a numeric value from 1 to 12
     *
     * @return int A value between 1 and 12
     */
    public static function getCurrentMonth(){

        return (int) (new DateTime())->format('n');
    }


    /**
     * Get the day based on current system date and timezone as a numeric value from 1 to 31
     *
     * @return int The day of month as a value between 1 and 31
     */
    public static function getCurrentDay(){

        return (int) (new DateTime())->format('j');
    }


    /**
     * Get the numeric day of week (between 1 and 7) based on current system time, where Sunday is considered
     * to be the first one:<br>
     * 1 = Sunday, 2 = Monday, 3 = Tuesday, etc ...
     *
     * @return int A numeric value between 1 and 7  (where Sunday is 1, Monday is 2, ...)
     */
    public static function getCurrentDayOfWeek(){

        return (int) ((new DateTime())->format('w') + 1);
    }


    /**
     * Get the hour based on current system date and timezone as a numeric value from 0 to 23
     *
     * @return int The hour as a value between 0 and 23
     */
    public static function getCurrentHour(){

        return (int) (new DateTime())->format('H');
    }


    /**
     * Get the minute based on current system date and timezone as a numeric value from 0 to 59
     *
     * @return int The minute as a value between 0 and 59
     */
    public static function getCurrentMinute(){

        return (int) (new DateTime())->format('i');
    }


    /**
     * Get the seconds based on current system date and timezone as a numeric value from 0 to 59
     *
     * @return int The seconds as a value between 0 and 59
     */
    public static function getCurrentSecond(){

        return (int) (new DateTime())->format('s');
    }


    /**
     * Get the miliseconds based on current system date and timezone as a numeric value up to 3 digits
     *
     * @return int The miliseconds as a value up to 3 digits
     */
    public static function getCurrentMiliSecond(){

        return round(DateTimeObject::getCurrentMicroSecond() / 1000);
    }


    /**
     * Get the microseconds based on current system date and timezone as a numeric value up to 6 digits
     *
     * @return int The microseconds as a value up to 6 digits
     */
    public static function getCurrentMicroSecond(){

        return (int) (new DateTime())->format('u');
    }


    /**
     * Get the timezone offset based on current system date and timezone as a numeric value (in seconds)
     *
     * @return int The timezone offset as a numeric value in seconds
     */
    public static function getCurrentTimeZoneOffset(){

        return (new DateTime())->getOffset();
    }


    /**
     * This method compares two dateTime values and tells if they are exactly the same or
     * which one represents a later time value than the other.
     * Timezones from the specified dateTime values are taken into consideration for the comparison.
     *
     * @param mixed $dateTime1 A valid ISO 8601 dateTime value or a DateTimeObject instance.
     * @param mixed $dateTime2 A valid ISO 8601 dateTime value or a DateTimeObject instance.
     *
     * @throws UnexpectedValueException
     *
     * @return int 0 If the two dateTime values represent the exact same time, 1 if dateTime1 > dateTime2 or 2 if dateTime2 > dateTime1
     */
    public static function compare($dateTime1, $dateTime2){

        if(self::isValidDateTime($dateTime1) && self::isValidDateTime($dateTime2)){

            if(is_string($dateTime1)){

                $dateTime1 = new DateTimeObject($dateTime1);
            }

            if(is_string($dateTime2)){

                $dateTime2 = new DateTimeObject($dateTime2);
            }

            $dateTime1->setUTC();
            $dateTime2->setUTC();

            $date1 = $dateTime1->toString();
            $date2 = $dateTime2->toString();

            if($date1 === $date2){

                return 0;
            }

            $sortedDates = [$date1, $date2];

            sort($sortedDates, SORT_STRING);

            return ($sortedDates[0] === $date1) ? 2 : 1;
        }

        throw new UnexpectedValueException('Provided value is not a valid ISO 8601 date time format');
    }


    /**
     * Get this instance's defined year as a numeric value
     *
     * @return int A 4 digits numeric value
     */
    public function getYear(){

        return (int) $this->_dateTimeStringExploded[0];
    }


    /**
     * Get this instance's defined month as a numeric value from 1 to 12
     *
     * @return int A value between 1 and 12
     */
    public function getMonth(){

        return (int) $this->_dateTimeStringExploded[1];
    }


    /**
     * Get this instance's defined day as a numeric value from 1 to 31
     *
     * @return int A value between 1 and 31
     */
    public function getDay(){

        return (int) $this->_dateTimeStringExploded[2];
    }


    /**
     * Get this instance's defined day of week as a numeric value from 1 to 7, where Sunday is considered
     * to be the first one:<br>
     * 1 = Sunday, 2 = Monday, 3 = Tuesday, etc ...
     *
     * @return int A numeric value between 1 and 7
     */
    public function getDayOfWeek(){

        $v = $this->_dateTimeStringExploded;

        $dateTimeInstance = new DateTime();

        $dateTimeInstance->setDate($v[0], $v[1], $v[2]);

        return $dateTimeInstance->format('w') + 1;
    }


    /**
     * Get this instance's defined hour as a numeric value from 0 to 23
     *
     * @return int A value between 0 and 23
     */
    public function getHour(){

        return (int) $this->_dateTimeStringExploded[3];
    }


    /**
     * Get this instance's defined minute as a numeric value from 0 to 59
     *
     * @return int A value between 0 and 59
     */
    public function getMinute(){

        return (int) $this->_dateTimeStringExploded[4];
    }


    /**
     * Get this instance's defined second as a numeric value from 0 to 59
     *
     * @return int A value between 0 and 59
     */
    public function getSecond(){

        return (int) $this->_dateTimeStringExploded[5];
    }


    /**
     * Get this instance's defined miliseconds as a numeric value up to 3 digit
     *
     * @return int A value up to 3 digit
     */
    public function getMiliSecond(){

        return round($this->getMicroSecond() / 1000);
    }


    /**
     * Get this instance's defined microseconds as a numeric value up to 6 digit
     *
     * @return int A value up to 6 digit
     */
    public function getMicroSecond(){

        return (int) $this->_dateTimeStringExploded[6];
    }


    /**
     * Get this instance's defined timezone offset as a numeric value (in seconds)
     *
     * @return int The UTC timezone offset in seconds
     */
    public function getTimeZoneOffset(){

        return (new DateTime($this->_dateTimeString))->getOffset();
    }


    /**
     * Get the first day of month for the current dateTime value.
     *
     * @return DateTimeObject A dateTime object representing the first day of month based on the current instance
     */
    public function getFirstDayOfMonth(){

        $dateTime = (new DateTime($this->_dateTimeString))->format('Y-m-01\\TH:i:s.uP');

        return new DateTimeObject($dateTime);
    }


    /**
     * Get the last day of month for the current dateTime value.
     *
     * @return DateTimeObject A dateTime object representing the last day of month based on the current instance
     */
    public function getLastDayOfMonth(){

        $dateTime = (new DateTime($this->_dateTimeString))->format('Y-m-t\\TH:i:s.uP');

        return new DateTimeObject($dateTime);
    }


    /**
     * Convert the current instance date and time values to the specified timezone offset.
     *
     * @param string $offset One of the supported timezone names or an offset value (+0200, +05:00, -0300, -03:00, etc...)
     *
     * @return DateTimeObject This object instance
     */
    public function setTimeZoneOffset(string $offset){

        $dateTime = new DateTime($this->_dateTimeString);

        $dateTime->setTimezone(new DateTimeZone($offset));

        $this->_dateTimeString = $dateTime->format($this->_iso8601FormatString);

        $this->_dateTimeStringExploded = $this->_explodeISO8601String($this->_dateTimeString);

        return $this;
    }


    /**
     * Convert the current instance date and time values to the local timezone offset.
     *
     * @return DateTimeObject This object instance
     */
    public function setLocalTimeZone(){

        return $this->setTimeZoneOffset(date_default_timezone_get());
    }


    /**
     * Check if the current instance timezone is the UTC +00:00 value
     *
     * @return boolean True if the instance timezone is UTC, false if not
     */
    public function isUTC(){

        return $this->getTimeZoneOffset() === 0;
    }


    /**
     * Convert the current instance date and time values to the UTC zero timezone offset.
     *
     * @example If this instance contains a +02:00 timezone offset, after calling this method the offset will be +00:00 (date and time will be updated accordingly)
     *
     * @return DateTimeObject This object instance
     */
    public function setUTC(){

        $dateTimeInstance = new DateTime($this->_dateTimeString);

        $dateTimeInstance->setTimezone(new DateTimeZone('UTC'));

        $this->_dateTimeString = $dateTimeInstance->format($this->_iso8601FormatString);

        $this->_dateTimeStringExploded = $this->_explodeISO8601String($this->_dateTimeString);

        return $this;
    }


    /**
     * Output the current dateTime instance data as a custom formatted string (by default a full ISO 8601 string).
     *
     * @param string $formatString A string containing the output format like 'd/m/Y' or 'm-d-y'
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
     * @return string The dateTime with the specified format.
     */
    public function toString($formatString = 'Y-M-DTH:N:S.UOffset'){

        $exploded = $this->_dateTimeStringExploded;

        // Get the time zone offset
        $formatString = str_replace('Offset', substr($exploded[7], 0), $formatString);

        // Get the year
        $formatString = str_replace('Y', $exploded[0], $formatString);
        $formatString = str_replace('y', substr($exploded[0], 2), $formatString);

        // Get the month
        $formatString = str_replace('M', $exploded[1], $formatString);
        $formatString = str_replace('m', (int)$exploded[1], $formatString);

        // Get the day
        $formatString = str_replace('D', $exploded[2], $formatString);
        $formatString = str_replace('d', (int)$exploded[2], $formatString);

        // Get the hour
        $formatString = str_replace('H', $exploded[3], $formatString);
        $formatString = str_replace('h', (int)$exploded[3], $formatString);

        // Get the minute
        $formatString = str_replace('N', $exploded[4], $formatString);
        $formatString = str_replace('n', (int)$exploded[4], $formatString);

        // Get the second
        $formatString = str_replace('S', $exploded[5], $formatString);
        $formatString = str_replace('s', (int)$exploded[5], $formatString);

        // Get the milisecond
        $formatString = str_replace('u', str_pad(min(999, round($exploded[6] / 1000)), 3, '0', STR_PAD_LEFT), $formatString);

        // Get the microsecond
        return str_replace('U', str_pad($exploded[6], 6, '0', STR_PAD_LEFT), $formatString);
    }


    /**
     * Compares the current datetime instance value to the given one and tells if they
     * are exactly the same or which one represents a later time value than the other.
     * Timezones from the both dateTime values are taken into consideration for the comparison.
     *
     * @param mixed $dateTime A valid ISO 8601 dateTime value or a DateTimeObject instance.
     *
     * @throws UnexpectedValueException
     *
     * @see DateTimeObject::compare
     *
     * @return int 0 If the two dateTime values represent the exact same time, 1 if this instance > dateTime or 2 if dateTime > this instance
     */
    public function compareTo($dateTime){

        return self::compare($this, $dateTime);
    }


    /**
     * Check if the provided ISO 8601 dateTime value is identical to the date and time from this instance
     *
     * @param mixed $dateTime A valid ISO 8601 dateTime string or a DateTimeObject instance.
     *
     * @return boolean True if both dateTime values are equivalent to the exact same date and time
     */
    public function isEqualTo($dateTime){

        return self::compare($this, $dateTime) === 0;
    }


    /**
     * Adds the specified amount of time to the given dateTime value
     * TODO - This method currently only works with years, and PHPDoc is incomplete!
     *
     * @param string $value The numeric amount that will be added to this DateTimeObject instance
     * @param string $type TODO - this paramenter description
     *
     * @return void
     */
    public function add(int $value, $type = 'minutes'){

        $v = $this->_dateTimeStringExploded;

        switch (strtolower($type)) {

            case 'years':
                $v[0]  = (string) $v[0] += $value;
                break;

            case 'months':
                throw new UnexpectedValueException('Months type is not implemented yet');
                break;

            case 'days':
                throw new UnexpectedValueException('Days type is not implemented yet');
                break;

            case 'hours':
                throw new UnexpectedValueException('Hours type is not implemented yet');
                break;

            case 'minutes':
                throw new UnexpectedValueException('Minutes type is not implemented yet');
                break;

            case 'seconds':
                throw new UnexpectedValueException('Seconds type is not implemented yet');
                break;

            case 'miliseconds':
                throw new UnexpectedValueException('Miliseconds type is not implemented yet');
                break;

            case 'microseconds':
                throw new UnexpectedValueException('Microseconds type is not implemented yet');
                break;

            default:
                throw new UnexpectedValueException('Invalid type specified');
        }

        return $v[0].'-'.$v[1].'-'.$v[2].'T'.$v[3].':'.$v[4].':'.$v[5].'.'.$v[6].$v[7];
    }


    /**
     * TODO - this method depends on $this->add for being finished
     */
    public function substract($value, $type = 'minutes'){

        return $this->add(-$value, $type);
    }


    /**
     * Auxiliary method that is used to generate an array with all the values that are defined on an ISO 8601 string
     *
     * @param string $string A valid ISO 8601 string
     *
     * @return array An array with all the date time values extracted
     */
    private function _explodeISO8601String(string $string){

        $result = ['', '01', '01', '00', '00', '00', '000000', ''];

        if(strtolower(substr($string, strlen($string) - 1, 1)) === 'z'){

            $string = substr($string, 0, strlen($string) - 1).'+00:00';
        }

        $splitted = preg_split('/[+-.: TZ]/', $string);

        $i = 0;

        while(count($splitted) > 0 && $i < 6){

            $result[$i] = array_shift($splitted);

            $i++;
        }

        $splittedCount = count($splitted);

        if($splittedCount === 1 || $splittedCount === 3){

            $result[6] = array_shift($splitted);
        }

        if($splittedCount === 2 || $splittedCount === 3){

            $result[7] = substr($string, strlen($string) - 6, 1).$splitted[0].':'.$splitted[1];
        }

        return $result;
    }
}

?>