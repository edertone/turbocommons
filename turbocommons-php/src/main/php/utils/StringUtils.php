<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\utils;

use InvalidArgumentException;


/**
 * The most common string processing and modification utilities
 */
class StringUtils {


    /**
     * Defines the sentence case format (Only the first character of the sentence is capitalised,except for
     * proper nouns and other words which are required by a more specific rule to be capitalised).
     * Generally equivalent to the baseline universal standard of formal English orthography
     */
    const FORMAT_SENTENCE_CASE = 'FORMAT_SENTENCE_CASE';


    /**
     * Defines the start case format (The first character in all words capitalised and all the rest
     * of the word lower case). It is also called Title Case
     */
    const FORMAT_START_CASE = 'FORMAT_START_CASE';


    /**
     * Defines the all upper case format (All letters on a string written with Capital letters only)
     */
    const FORMAT_ALL_UPPER_CASE = 'FORMAT_ALL_UPPER_CASE';


    /**
     * Defines the all lower case format (All letters on a string written with lower case letters only)
     */
    const FORMAT_ALL_LOWER_CASE = 'FORMAT_ALL_LOWER_CASE';


    /**
     * Defines the first upper rest lower case format (All letters on a string written
     * with lower case letters except the first one which is Capitalized)
     */
    const FORMAT_FIRST_UPPER_REST_LOWER = 'FORMAT_FIRST_UPPER_REST_LOWER';


    /**
     * Defines the CamelCase format (the practice of writing compound words or phrases such that each
     * word or abbreviation begins with a capital letter)
     */
    const FORMAT_CAMEL_CASE = 'FORMAT_CAMEL_CASE';


    /**
     * Defines the UpperCamelCase format variation that writes first letter as upper case
     *
     * @see StringUtils::FORMAT_CAMEL_CASE
     */
    const FORMAT_UPPER_CAMEL_CASE = 'FORMAT_UPPER_CAMEL_CASE';


    /**
     * Defines the lowerCamelCase format variation that writes first letter as lower case
     *
     * @see StringUtils::FORMAT_CAMEL_CASE
     */
    const FORMAT_LOWER_CAMEL_CASE = 'FORMAT_LOWER_CAMEL_CASE';


    /**
     * Defines the snake_case format (the practice of writing compound words or phrases in which
     * the elements are separated with one underscore character (_) and no spaces)
     */
    const FORMAT_SNAKE_CASE = 'FORMAT_SNAKE_CASE';


    /**
     * Defines the FORMAT_UPPER_SNAKE_CASE format variation that writes all letters as upper case
     *
     * @see StringUtils::FORMAT_SNAKE_CASE
     */
    const FORMAT_UPPER_SNAKE_CASE = 'FORMAT_UPPER_SNAKE_CASE';


    /**
     * Defines the lower_snake_case format variation that writes all letters as lower case
     *
     * @see StringUtils::FORMAT_SNAKE_CASE
     */
    const FORMAT_LOWER_SNAKE_CASE = 'FORMAT_LOWER_SNAKE_CASE';


    /**
     * Tells if the given value is a string or not
     *
     * @param mixed $value A value to check
     *
     * @return boolean true if the given value is a string, false otherwise
     */
    public static function isString($value){

        return is_string($value);
    }


    /**
     * Strictly check that the provided value is a string or throw an exception
     *
     * @param mixed $value A value to check
     * @param string $valueName The name of the value to be shown at the beginning of the exception message
     * @param string $errorMessage The rest of the exception message
     *
     * @throws InvalidArgumentException If the check fails
     *
     * @return void
     */
    public static function forceString($value, string $valueName = '', string $errorMessage = 'must be a string'){

        if(!is_string($value)){

            throw new InvalidArgumentException($valueName.' '.$errorMessage);
        }
    }


    /**
     * Tells if the given string is a valid url or not
     *
     * @param mixed $value The value to check
     *
     * @return boolean False in case the validation fails or true if validation succeeds.
     */
    public static function isUrl($value){

        $res = false;

        if(!self::isEmpty($value) && is_string($value)){

            // This amazingly good solution's been found at https://jkwl.io/php/regex/2015/05/18/url-validation-php-regex.html
            $urlRegex = '#^(?:(?:https?|ftp):\\/\\/)?(?:\\S+(?::\\S*)?@)?(?:(?!(?:10|127)(?:\\.\\d{1,3}){3})(?!(?:169\\.254|192\\.168)(?:\\.\\d{1,3}){2})(?!172\\.(?:1[6-9]|2\\d|3[0-1])(?:\\.\\d{1,3}){2})(?:[1-9]\\d?|1\\d\\d|2[01]\\d|22[0-3])(?:\\.(?:1?\\d{1,2}|2[0-4]\\d|25[0-5])){2}(?:\\.(?:[1-9]\\d?|1\\d\\d|2[0-4]\\d|25[0-4]))|' .
                // host name
            "(?:(?:[a-z\\x{00a1}-\\x{ffff}0-9]-*)*[a-z\\x{00a1}-\\x{ffff}0-9]+)" .
            // domain name
            "(?:\\.(?:[a-z\\x{00a1}-\\x{ffff}0-9]-*)*[a-z\\x{00a1}-\\x{ffff}0-9]+)*" .
            // TLD identifier
            '(?:\\.(?:[a-z\\x{00a1}-\\x{ffff}]{2,})))' .
            // port number
            '(?::\\d{2,5})?' .
            // resource path
            '(?:\\/\\S*)?$#ui';

            $res = (strlen($value) < 2083 && preg_match($urlRegex, $value));
        }

        return $res;
    }


    /**
     * Tells if a specified string is semantically empty, which applies to any string that is comprised of empty spaces, new line characters, tabulations or any other
     * characters without a visually semantic value to the user.
     *
     * Example1: Following strings are considered as empty: "     ", "", "    \n\n\n", "    \t\t\n"
     * Example2: Following strings are not considered as empty: "hello", "   a", "    \n\nB"
     *
     * @param string $string The text to check
     * @param array $emptyChars Custom list of strings that will be also considered as empty characters. For example, we can define 'NULL' and '_' as empty string values by setting this to ['NULL', '_']
     *
     * @return boolean false if the string is not empty, true if the string contains non semantically valuable characters or any other characters defined as "empty" values
     */
    public static function isEmpty($string, array $emptyChars = []){

        // Throw exception if non string value was received
        if(!is_string($string)){

            // Null value is considered empty
            if($string == null){

                return true;
            }

            throw new InvalidArgumentException('value is not a string');
        }

        return str_replace(array_merge($emptyChars, [' ', "\n", "\r", "\t"]), '', $string) === '';
    }


    /**
     * Checks if the given string starts with any of the specified values.
     *
     * @param string $str The string to check
     * @param array $startingValues An array of strings that the input string should start with.
     *
     * @return bool True if the input string starts with any of the specified values, False otherwise.
     */
    public static function isStartingWith($str, array $startingValues = []){

        foreach($startingValues as $startingValue){

            if(substr($str, 0, strlen($startingValue)) === $startingValue){

                return true;
            }
        }

        return false;
    }


    /**
     * Checks if a given string ends with any of the specified values.
     *
     * @param string $str The string to check
     * @param array $endingValues An array of strings that the input string should end with.
     *
     * @return bool True if the input string ends with any of the specified values, False otherwise.
     */
    public static function isEndingWith($str, array $endingValues = []){

        foreach($endingValues as $endingValue){

            if($endingValue === '' || substr($str, -strlen($endingValue)) === $endingValue){

                return true;
            }
        }

        return false;
    }


    /**
     * Strictly check that the provided value is a non empty string or throw an exception
     *
     * Uses the same criteria as the self::isEmpty() method
     *
     * @param mixed $value A value to check
     * @param string $valueName The name of the value to be shown at the beginning of the exception message
     * @param string $errorMessage The rest of the exception message
     *
     * @throws InvalidArgumentException If the check fails
     *
     * @return void
     */
    public static function forceNonEmptyString($value, string $valueName = '', string $errorMessage = 'must be a non empty string'){

        if(!is_string($value) || self::isEmpty($value)){

            throw new InvalidArgumentException($valueName.' '.$errorMessage);
        }
    }


    /**
     * Test if a given string is written using the camel case format or not.
     * 3 variants can be checked: Default one that does not care about the first letter case, and Upper or Lower camel case formats which
     * force it to be upper case and lower case respectively.
     *
     * @param string $string The string to be tested
     * @param string $type The variant of camel case we are testing: StringUtils::FORMAT_UPPER_CAMEL_CASE, StringUtils::FORMAT_LOWER_CAMEL_CASE or StringUtils::FORMAT_CAMEL_CASE (default).
     *
     * @see StringUtils::FORMAT_CAMEL_CASE
     * @see StringUtils::FORMAT_UPPER_CAMEL_CASE
     * @see StringUtils::FORMAT_LOWER_CAMEL_CASE
     *
     * @return boolean True if the given string is accepted as camel case for the specified variant.
     */
    public static function isCamelCase($string, string $type = self::FORMAT_CAMEL_CASE){

        if($string == null || $string == ''){

            return false;
        }

        // Throw exception if non string value was received
        if(!is_string($string)){

            throw new InvalidArgumentException('value is not a string');
        }

        // Single letter is accepted as default camel case
        $isCamelCase = strlen($string) == 1;

        // Single word that is shorter than 45 characters (the longest english word found in a major dictionary)
        // is accepted as default camel case if all except the first letter are lowercase
        $isCamelCase = $isCamelCase || (strlen($string) < 46 && ctype_alpha($string) && strtolower(substr($string, 1)) === substr($string, 1));

        // Apply regex for default camel case validation
        $isCamelCase = $isCamelCase || preg_match('/[A-Z|a-z]([A-Z0-9]*[a-z][a-z0-9]*[A-Z]|[a-z0-9]*[A-Z][A-Z0-9]*[a-z])[A-Za-z0-9]*/', $string) == 1;

        switch ($type) {

            case self::FORMAT_CAMEL_CASE:
                break;

            case self::FORMAT_UPPER_CAMEL_CASE:
                $isCamelCase = ctype_upper($string[0]) && $isCamelCase;

                // Upper camel case type is also valid if all lowercase except the first letter and
                // the whole word does not exceed the 45 characters of the longest english word found in a major dictionary
                $isCamelCase = (strlen($string) < 46 && ctype_alpha($string) && ucfirst(strtolower($string)) == $string) || $isCamelCase;
                break;

            case self::FORMAT_LOWER_CAMEL_CASE:
                $isCamelCase = ctype_lower($string[0]) && $isCamelCase;
                break;

            default:
                throw new InvalidArgumentException('Unknown type specified');
        }

        // Perform a last alphanumeric validation before returning the result
        return ctype_alnum($string) && !ctype_digit($string) && $isCamelCase;
    }


    /**
     * Test if a given string is written using the snake case format or not.
     * 3 variants can be checked: Default one that does not care about the text case, and Upper or Lower snake case formats which
     * force it to be upper case and lower case respectively.
     *
     * @param string $string The string to be tested
     * @param string $type The variant of snake case we are testing: StringUtils::FORMAT_UPPER_SNAKE_CASE, StringUtils::FORMAT_LOWER_SNAKE_CASE or StringUtils::FORMAT_SNAKE_CASE (default).
     *
     * @see StringUtils::FORMAT_SNAKE_CASE
     * @see StringUtils::FORMAT_UPPER_SNAKE_CASE
     * @see StringUtils::FORMAT_LOWER_SNAKE_CASE
     *
     * @return boolean True if the given string is accepted as snake case for the specified variant.
     */
    public static function isSnakeCase($string, string $type = self::FORMAT_SNAKE_CASE){

        if($string == null || $string == ''){

            return false;
        }

        // Throw exception if non string value was received
        if(!is_string($string)){

            throw new InvalidArgumentException('value is not a string');
        }

        // Check that there are only letters, numbers and underscores
        $isSnakeCase = preg_match('/^[a-zA-Z0-9_]*$/', $string) == 1;

        // Check that it does not start or end with underscore
        $isSnakeCase = $isSnakeCase && $string[0] != '_' && $string[strlen($string)-1] != '_';

        // Check that it has at least one letter and no repeated underscores
        $isSnakeCase = $isSnakeCase && preg_match('/[a-z]/i', $string) && strpos($string, '__') === false;

        switch ($type) {

            case self::FORMAT_SNAKE_CASE:
                break;

            case self::FORMAT_UPPER_SNAKE_CASE:
                $isSnakeCase = $isSnakeCase && strtoupper($string) == $string;
                break;

            case self::FORMAT_LOWER_SNAKE_CASE:
                $isSnakeCase = $isSnakeCase && strtolower($string) == $string;
                break;

            default:
                throw new InvalidArgumentException('Unknown type specified');
        }

        return $isSnakeCase;
    }


    /**
     * TODO docs
     * TODO Verify that this version works exactly the same as the TS one, and implement the same unit tests
     */
    public static function replace($string, $search, $replacement, $count = -1){

        return str_replace($search, $replacement, $string, $count);
    }


    /**
     * TODO translate from TS
     */
    public static function replaceMulti(){

        // TODO translate from TS
    }


    /**
     * Remove whitespaces (or any custom set of characters) from both sides of a string
     *
     * @param string $string A string to process
     * @param string $characters A set of characters that will be trimmed from both string sides. By default,
     * empty space and new line characters are defined : " \n\r"
     *
     * @example: StringUtils::trim("abcXXabc", "abc") outputs "XX"
     *
     * @return string The trimmed string
     */
    public static function trim($string, $characters = " \n\r"){

        return StringUtils::trimLeft(StringUtils::trimRight($string, $characters), $characters);
    }


    /**
     * Remove whitespaces (or any custom set of characters) from a string left side
     *
     * @param string $string A string to process
     * @param string $characters A set of characters that will be trimmed from string left side. By default,
     *        empty space and new line characters are defined : " \n\r"
     *
     * @example: StringUtils::trimLeft("abcXXabc", "abc") outputs "XXabc"
     *
     * @return string The trimmed string
     */
    public static function trimLeft($string, $characters = " \n\r"){

        if(!is_string($string)){

            throw new InvalidArgumentException('value is not a string');
        }

        return ltrim($string, $characters);
    }


    /**
     * Remove whitespaces (or any custom set of characters) from a string right side
     *
     * @param string $string A string to process
     * @param string $characters A set of characters that will be trimmed from string right side. By default,
     * empty space and new line characters are defined : " \n\r"
     *
     * @example: StringUtils.trimRight("abcXXabc", "abc") outputs "abcXX"
     *
     * @return string The trimmed string
     */
    public static function trimRight($string, $characters = " \n\r"){

        if(!is_string($string)){

            throw new InvalidArgumentException('value is not a string');
        }

        return rtrim($string, $characters);
    }


    /**
     * Pad a string to a certain length with another string
     *
     * @param string $string The string to which we want to fill the empty spaces
     * @param int $padLength The minimum length that we want for the resulting string to have
     * @param string $padString The character or characters which we want to add to the string to match the target length
     * @param string $mode LEFT to append the padString to the left of the string, RIGHT to append the padString to the right of the string
     *
     * @return string The padded string
     */
    public static function pad($string, $padLength, $padString = '0', $mode = 'LEFT') {

        if(!StringUtils::isString($string) || !StringUtils::isString($padString) || strlen($padString) <= 0){

            throw new InvalidArgumentException('string and padString must be strings');
        }

        if(!NumericUtils::isInteger($padLength)){

            throw new InvalidArgumentException('padLength is not an int');
        }

        if($mode !== 'LEFT' && $mode !== 'RIGHT'){

            throw new InvalidArgumentException('mode must be LEFT or RIGHT');
        }

        $result = $string;

        if($mode === 'RIGHT'){

            while (strlen($result) < $padLength) {

                $result = $result . substr($padString, 0, $padLength - strlen($result));
            }

        }else{

            while (strlen($result) < $padLength) {

                $result = substr($padString, -($padLength - strlen($result))) . $result;
            }
        }

        return $result;
    }


    /**
     * Count the number of times a string is found inside another string
     *
     * @param string $string The string where we want to search
     * @param string $findMe The string that we want to look for
     *
     * @return int The number of times that $findMe appears on $string
     */
    public static function countStringOccurences($string, $findMe){

        if(!is_string($string) || !is_string($findMe)){

            throw new InvalidArgumentException('value is not a string');
        }

        if($findMe === ''){

            throw new InvalidArgumentException('cannot count empty string occurences');
        }

        return substr_count($string, $findMe);
    }


    /**
     * Count the number of characters that match the given letter case on the given string
     *
     * @param string $string The string which case matching characters will be counted
     * @param string $letterCase Defines which letter case are we looking for: StringUtils::FORMAT_ALL_UPPER_CASE or
     *        StringUtils::FORMAT_ALL_LOWER_CASE
     *
     * @return int The number of characters with the specified letter case that are present on the string
     */
    public static function countByCase($string, $letterCase = self::FORMAT_ALL_UPPER_CASE){

        $string = self::removeAccents($string);

        if($letterCase === self::FORMAT_ALL_UPPER_CASE){

            return mb_strlen(preg_replace('![^A-Z]+!', '', $string));
        }

        if($letterCase === self::FORMAT_ALL_LOWER_CASE){

            return mb_strlen(preg_replace('![^a-z]+!', '', $string));
        }

        throw new InvalidArgumentException('invalid case value');
    }


    /**
     * Count the number of words that exist on the given string
     *
     * @param string $string The string which words will be counted
     * @param string $wordSeparator ' ' by default. The character that is considered as the word sepparator
     *
     * @return int The number of words (elements divided by the wordSeparator value) that are present on the string
     */
    public static function countWords($string, string $wordSeparator = ' '){

        $count = 0;
        $lines = self::getLines($string);
        $linesCount = count($lines);

        for ($i = 0; $i < $linesCount; $i++) {

            $words = explode($wordSeparator, $lines[$i]);
            $wordsCount = count($words);

            for ($j = 0; $j < $wordsCount; $j++) {

                if(!self::isEmpty($words[$j])){

                    $count++;
                }
            }
        }

        return $count;
    }


    /**
     * Given a string with a list of elements separated by '/' or '\' that represent some arbitrary path structure,
     * this method will return the number of elements that are listed on the path.
     *
     * @example "c:\\" -> results in 1
     *          "//folder/folder2/folder3/file.txt" -> results in 4
     *
     * @param string $path A string containing some arbitrary path.
     *
     * @return number The number of elements that are listed on the provided path
     */
    public static function countPathElements($path){

        $path = self::formatPath($path, '/');

        $path = (strpos($path, '/') === 0) ? substr($path, 1) : $path;

        return $path == '' ? 0 : count(explode('/', $path));
    }


    /**
     * Method that limits the length of a string and optionally appends informative characters like ' ...'
     * to inform that the original string was longer.
     *
     * @param string $string String to limit
     * @param int $limit Max number of characters
     * @param string $limiterString If the specified text exceeds the specified limit, the value of this parameter will be added to the end of the result. The value is ' ...' by default.
     *
     * @return string The specified string but limited in length if necessary. Final result will never exceed the specified limit, also with the limiterString appended.
     */
    public static function limitLen($string, int $limit = 100, string $limiterString = ' ...'){

        if($limit <= 0 || !NumericUtils::isNumeric($limit)){

            throw new InvalidArgumentException('limit must be a positive numeric value');
        }

        if(!self::isString($string)){

            return '';
        }

        if(strlen($string) <= $limit){

            return $string;
        }

        if(strlen($limiterString) > $limit){

            return substr($limiterString, 0, $limit);

        }else{

            return substr($string, 0, $limit - strlen($limiterString)).$limiterString;
        }
    }


    /**
     * TODO
     */
    public static function getDomainFromUrl($string){

        // TODO translate from TS
    }


    /**
     * TODO
     */
    public static function getHostNameFromUrl($string){

        // TODO translate from TS
    }


    /**
     * Extracts all the lines from the given string and outputs an array with each line as an element.
     * It does not matter which line separator's been used (windows: \r\n, Linux/Unix: \n, Mac: \r). All source lines will be correctly extracted.
     *
     * @param string $string Text containing one or more lines that will be converted to an array with each line on a different element.
     * @param array $filters One or more regular expressions that will be used to filter unwanted lines. Lines that match any of the
     *  filters will be excluded from the result. By default, all empty lines are ignored (those containing only newline, blank, tabulators, etc..).
     *
     * @return array A list with all the string lines sepparated as different array elements.
     */
    public static function getLines($string, array $filters = ['/\s+/']){

        $res = [];

        // Validate we are receiving a string
        if(!is_string($string)){

            return $res;
        }

        $tmp = preg_split("/\r\n|\n|\r/", $string);

        foreach($tmp as $line){

            // Apply specified filters
            if(preg_replace($filters, '', $line) != ''){

                $res[] = $line;
            }
        }

        return $res;
    }


    /**
     * Generates an array with a list of common words from the specified text.
     * The list will be sorted so the words that appear more times on the string are placed first.
     *
     * @param string $string Piece of text that contains the keywords we want to extract
     * @param string $max The maxium number of keywords that will appear on the result. If set to null, all unique words on the given text will be returned
     * @param string $longerThan The minimum number of chars for the keywords to find. This is useful to filter some irrelevant words like: the, it, me, ...
     * @param string $shorterThan The maximum number of chars for the keywords to find
     * @param string $ignoreNumericWords Tells the method to skip words that represent numeric values on the result. False by default
     *
     * @return array The list of keywords that have been extracted from the given text
     */
    public static function getKeyWords($string, int $max = 25, int $longerThan = 3, int $shorterThan = 15, bool $ignoreNumericWords = false){

        if($string == null){

            return [];
        }

        // Convert all the - and _ characters to blank spaces
        $string = str_replace('-', ' ', str_replace('_', ' ', $string));

        // Process the received string to contain only alphanumeric lowercase values
        $string = self::formatForFullTextSearch($string);

        // Remove all the words that are shorter than the specified length
        $string = self::removeWordsShorterThan($string, $longerThan);

        // Remove all the words longher than specified value
        $string = self::removeWordsLongerThan($string, $shorterThan);

          // Count the occurences of each word
        $words = array_count_values(explode(' ', $string));

        // Get the max nuber of times a word is repeated.
        $maxCount = max($words);

        // Generate the result by adding first the most repeated words.
        // Note that we sort in a way that the original words order is preserved when the repeat count is the same, so
        // the text sorting does not get altered when all the words are repeated the same number of times.
        $res = [];

        for($i=$maxCount; $i> 0; $i--){

            foreach($words as $key => $v){

                if($v == $i && (!NumericUtils::isNumeric($key) || (NumericUtils::isNumeric($key) && !$ignoreNumericWords))){

                    $res[] = $key;
                }
            }
        }

        // Get the number of words to return depending on the max parameter
        if($max == ''){

            return $res;

        }else{

            return array_slice($res, 0, $max);
        }
    }


    /**
     * Given a string with a list of elements separated by '/' or '\' that represent some arbitrary path structure,
     * this method will format the specified path and remove the number of requested path elements (from its right
     * side) and return the path without that elements.
     *
     * This method can be used with Operating system file paths, urls, or any other string that uses the 'slash separated'
     * format to encode a path.
     *
     * @example "//folder/folder2/folder3/file.txt" -> results in "/folder/folder2/folder3" if elementsToRemove = 1<br>
     *          "//folder/folder2\folder3\file.txt" -> results in "/folder/folder2" if elementsToRemove = 2
     *
     * @see StringUtils::formatPath
     *
     * @param string $path A string containing some arbitrary path.
     * @param int $elementsToRemove (one by default) The number of elements that we want to remove from the right side of the path.
     * @param string $separator The character to use as the element divider for the returned path. Only slash '/' or backslash '\' are allowed.
     *
     * @return string The received path without the specified number of elements and correctly formatted
     */
    public static function getPath($path, int $elementsToRemove = 1, string $separator = '/'){

        if(StringUtils::isEmpty($path)){

            return '';
        }

        $path = StringUtils::formatPath($path, '/');

        if($path === '/'){

            return $path;
        }

        $processedPath = (strpos($path, '/') === 0) ? substr($path, 1) : $path;

        $elements = explode('/', $processedPath);
        $elementsCount = count($elements);

        if($elementsToRemove > $elementsCount || $elementsToRemove < -1){

            return '';
        }

        $arrayToRemove = [];

        for ($i = $elementsCount - $elementsToRemove; $i < $elementsCount; $i++) {

            $arrayToRemove[] = $elements[$i];
        }

        if(count($arrayToRemove) <= 0){

            return $path;
        }

        return StringUtils::formatPath(substr($path, 0, max(0, strlen($path) - strlen(implode('/', $arrayToRemove)) - 1)), $separator);
    }


    /**
     * Given a string with a list of elements separated by '/' or '\' that represent some arbitrary path structure,
     * this method will return the element that is located at the requested position. If no position is defined,
     * by default the last element of the path will be returned (the most to the right one).
     *
     * This method can be used with Operating system file paths, urls, or any other string that uses the 'slash separated'
     * format to encode a path.
     *
     * @example "//folder/folder2/folder3/file.txt" -> results in "file.txt" if (-1) position is defined<br>
     *          "//folder/folder2\folder3\file.txt" -> results in "folder" if position 0 is defined<br>
     *          "//folder/folder2\folder3\file.txt" -> results in "folder3" if position 2 is defined<br>
     *          "//folder/folder2\folder3\file.txt" -> results in "folder3" if position -2 is defined<br>
     *          "//folder/folder2\folder3\file.txt" -> results in "folder2" if position -3 is defined
     *
     * @param string $path A string containing some arbitrary path.
     * @param integer $position The index for the element that we want to extract from the path. Positive values will get path elements
     *        starting from the left side, being 0 the first most to the left one. Negative values will get path elements starting from
     *        the right side, being -1 the last path element (or the first most to the right one).
     *        If not specified, the last one will be returned.
     *
     * @return string The element at the specified path position or the last one if no position is defined
     */
    public static function getPathElement($path, int $position = -1){

        if(self::isEmpty($path)){

            return '';
        }

        $path = self::formatPath($path, '/');

        $path = (strpos($path, '/') === 0) ? substr($path, 1) : $path;

        $elements = explode('/', $path);
        $elementsCount = count($elements);

        if($position >= $elementsCount || $position < -$elementsCount){

            throw new InvalidArgumentException('Invalid position specified');
        }

        return $position < 0 ? $elements[$elementsCount + $position] : $elements[$position];
    }


    /**
     * This method works in the same way as getPathElement but it also removes the extension part from the result
     * if it has any.
     *
     * @example "//folder/folder2/folder3/file.txt" -> results in "file" if position = -1. Notice that ".txt" extension is removed<br>
     *          "//folder/folder2\folder3\file.txt" -> results in "folder3" if position = 2. "folder3" has no extension so it does not get modified.
     *
     * @see StringUtils::getPathElement
     *
     * @param string $path A string containing some arbitrary path.
     * @param integer $position The index for the element that we want to extract from the path. If not specified, the
     *                          last one will be returned.
     * @param string $extensionSeparator The character to be used as the extension separator. The most commonly used is '.'
     *
     * @return string The element at the specified path position with it's extension removed or the last one if no position is defined
     */
    public static function getPathElementWithoutExt($path, int $position = -1, $extensionSeparator = '.'){

        $element = self::getPathElement($path, $position);

        if(strpos($element, $extensionSeparator) !== false){

            $element = substr($element, 0, strrpos($element, $extensionSeparator));
        }

        return $element;
    }


    /**
     * This method works in the same way as getPathElement but it only gives the element extension if it has any.
     *
     * @example "//folder/folder2/folder3/file.txt" -> results in "txt" if position = -1. Notice that extension without separator character is returned<br>
     *          "//folder/folder2\folder3\file.txt" -> results in "folder3" if position = 2. "folder3" has no extension so it does not get modified.
     *
     * @see StringUtils::getPathElement
     *
     * @param string $path A string containing some arbitrary path.
     * @param integer $position The index for the element extension that we want to extract from the path. If not specified, the
     *                          last one will be returned.
     * @param string $extensionSeparator The character to be used as the extension separator. The most commonly used is '.'
     *
     * @return string The extension from the element at the specified path position or the extension from the last one if no position is defined
     */
    public static function getPathExtension($path, int $position = -1, $extensionSeparator = '.'){

        $element = self::getPathElement($path, $position);

        if(strpos($element, $extensionSeparator) === false){

            return '';
        }

        // Find the extension by getting the last position of the dot character
        return substr($element, strrpos($element, $extensionSeparator) + 1);
    }


    /**
     * TODO - translate from Ts
     */
    public static function getSchemeFromUrl(){

        // TODO - translate from Ts
    }


    /**
     * Changes the letter case for the given string to the specified format.
     *
     * @param string $string A string that will be processed to match the specified case format.
     * @param string $format The format to which the given string will be converted. Possible values are defined as
     * StringUtils constants that start with <b>FORMAT_</b>, like: StringUtils::FORMAT_ALL_UPPER_CASE
     *
     * @see StringUtils::FORMAT_SENTENCE_CASE
     * @see StringUtils::FORMAT_START_CASE
     * @see StringUtils::FORMAT_ALL_UPPER_CASE
     * @see StringUtils::FORMAT_ALL_LOWER_CASE
     * @see StringUtils::FORMAT_FIRST_UPPER_REST_LOWER
     * @see StringUtils::FORMAT_CAMEL_CASE
     * @see StringUtils::FORMAT_UPPER_CAMEL_CASE
     * @see StringUtils::FORMAT_LOWER_CAMEL_CASE
     * @see StringUtils::FORMAT_SNAKE_CASE
     * @see StringUtils::FORMAT_UPPER_SNAKE_CASE
     * @see StringUtils::FORMAT_LOWER_SNAKE_CASE
     *
     * @return string The given string converted to the specified case format.
     */
    public static function formatCase($string, string $format){

        // Non string values will throw an exception
        if(!is_string($string)){

            throw new InvalidArgumentException('value is not a string');
        }

        // Empty values will return the string itself
        if(self::isEmpty($string)){

            return $string;
        }

        // Generate the sentence case output
        if($format == self::FORMAT_SENTENCE_CASE){

            $result = '';
            $sentences = preg_split('/([.?!]+)/', $string, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);

            foreach ($sentences as $s) {

                $match = null;

                if(preg_match('~[a-z]~i', self::removeAccents($s), $match, PREG_OFFSET_CAPTURE) == 1){

                    $result .= mb_substr($s, 0, $match[0][1]).mb_strtoupper(mb_substr($s, $match[0][1], 1)).mb_substr($s, $match[0][1] + 1);

                }else{

                    $result .= $s;
                }
            }

            return $result;
        }

        // Generate the title case output
        if($format == self::FORMAT_START_CASE){

            return mb_convert_case($string, MB_CASE_TITLE);
        }

        // Generate the all upper case output
        if($format == self::FORMAT_ALL_UPPER_CASE){

            return mb_strtoupper($string);
        }

        // Generate the all lower case output
        if($format == self::FORMAT_ALL_LOWER_CASE){

            return mb_strtolower($string);
        }

        // Generate the first upper rest lower case output
        if($format == self::FORMAT_FIRST_UPPER_REST_LOWER){

            return mb_strtoupper(mb_substr($string, 0, 1)).mb_substr(mb_strtolower($string), 1);
        }

        // Generate the snake case format
        if(strpos($format, 'SNAKE_CASE') !== false){

            $processedString = null;

            // Check if string is accepted as camel case or a raw string
            if(self::isCamelCase($string) && self::countByCase($string) > 0){

                preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $string, $processedString);

                $processedString = $processedString[0];

            }else{

                $processedString = explode(' ', $string);
            }

            if($format == self::FORMAT_UPPER_SNAKE_CASE){

                return mb_strtoupper(implode('_', $processedString));
            }

            if($format == self::FORMAT_LOWER_SNAKE_CASE){

                return mb_strtolower(implode('_', $processedString));
            }

            return implode('_', $processedString);
        }

        // Generate the camel case format
        if(strpos($format, 'CAMEL_CASE') !== false){

            // non-alpha and non-numeric characters become spaces and the whole string is splitted to words
            $stringWords = explode(' ', trim(preg_replace('/[^a-z0-9]+/i', ' ', self::removeAccents($string))));

            // uppercase the first character of each word except the first one
            if(($stringCount = count($stringWords)) > 1){

                for ($i = 1; $i < $stringCount; $i++) {

                    $stringWords[$i] = ucfirst($stringWords[$i]);
                }
            }

            $string = implode('', $stringWords);

            if($format == self::FORMAT_UPPER_CAMEL_CASE){

                return ucfirst($string);
            }

            if($format == self::FORMAT_LOWER_CAMEL_CASE){

                return lcfirst($string);
            }

            return $string;
        }

        throw new InvalidArgumentException('Unknown format specified');
    }


    /**
     * Given a string with a list of elements separated by '/' or '\' that represent some kind of unformatted path,
     * this method will process it to get a standarized one by applying the following rules:
     *
     * - Duplicate separator characters will be removed: "a\\\b\\c" will become "a/b/c"
     * - All separator characters will be unified to the same one: "a\b/c\d" will become "a/b/c/d"
     * - No trailing separator will exist: "a\b\c\" will become "a\b\c"
     *
     * NOTE: This method only applies format to the received string. It does not check if the path is a real
     *       location or a valid url, and won't also fail if the received path contains strange characters or is invalid.
     *
     * @param string $path A raw path to be formatted
     * @param string $separator The character to use as the element divider. Only slash '/' or backslash '\' are allowed.
     *
     * @return string The correctly formatted path without any trailing separator
     */
    public static function formatPath($path, string $separator = '/'){

        if(!is_string($path)){

            throw new InvalidArgumentException('path must be a string');
        }

        if($separator !== '/' && $separator !== '\\'){

            throw new InvalidArgumentException('separator must be a slash or backslash');
        }

        // Standarize all the separator characters
        $path = str_replace('/', $separator, $path);
        $path = str_replace('\\', $separator, $path);

        // Remove duplicate path separator characters
        while(strpos($path, $separator.$separator) !== false) {

            $path = str_replace($separator.$separator, $separator, $path);
        }

        // Remove the last separator only if it exists and is not the only character of the path
        $pathLen = strlen($path);

        if($pathLen > 1 && substr($path, $pathLen - 1) === $separator){

            $path = substr($path, 0, $pathLen - 1);
        }

        return $path;
    }


    /**
     * TODO - copy from Ts
     */
    public static function formatUrl(){

        // TODO - copy from Ts
    }


    /**
     * Full text search is the official name for the process of searching on a big text content based on a string containing some text to find.
     * This method will process a text so it removes all the accents and non alphanumerical characters that are not usefull for searching on strings,
     * convert everything to lower case and remove empty spaces.
     * To perform the search it is important that both search and searched strings are standarized the same way, to maximize possible matches.
     *
     * @param string $string String to process
     * @param string $wordSeparator The character that will be treated as the word separator. By default it is the empty space character ' '
     *
     * @return string The resulting string
     */
    public static function formatForFullTextSearch($string, string $wordSeparator = ' '){

        if(!is_string($string)){

            throw new InvalidArgumentException('value is not a string');
        }

        // Remove all word separators
        $res = static::replace($string, $wordSeparator, '');

        // Remove accents
        $res = static::removeAccents($res);

        // Take only alphanumerical characters
        $res = preg_replace('/[^[:alnum:][:space:]]/u', '', $res);

        // make all lowercase
        return mb_strtolower($res);
    }


    /**
     * Compares two strings and gives the number of character replacements that must be performed to convert one
     * of the strings into the other. A very useful method to use in fuzzy text searches where we want to look for
     * similar texts. This method uses the Levenshtein method for the comparison:
     *
     * The Levenshtein distance is defined as the minimal number of characters you have to replace, insert or delete
     * to transform string1 into string2. The complexity of the algorithm is O(m*n), where n and m are the length
     * of string1 and string2.
     *
     * @example "aha" and "aba" will output 1 cause we need to change the h for a b to transform one string into another.
     *
     * @param string $string1 The first string to compare
     * @param string $string2 The second string to compare
     *
     * @return number The number of characters to replace to convert $string1 into $string2 where 0 means both strings are the same.
     *         The higher the result, the more different the strings are.
     */
    public static function compareByLevenshtein($string1, $string2){

        // This function was found at https://gist.github.com/santhoshtr/1710925

        if(!is_string($string1) || !is_string($string2)){

            throw new InvalidArgumentException('string1 and string2 must be strings');
        }

        $length1 = mb_strlen($string1, 'UTF-8');
        $length2 = mb_strlen($string2, 'UTF-8');

        if($length1 < $length2) {

            return self::compareByLevenshtein($string2, $string1);
        }

        if($length1 == 0) {

            return $length2;
        }

        if($string1 === $string2) {

            return 0;
        }

        $currentRow = [];
        $prevRow = range(0, $length2);

        for ($i = 0; $i < $length1; $i++) {

            $currentRow = [];
            $currentRow[0] = $i + 1;
            $c1 = mb_substr($string1, $i, 1, 'UTF-8');

            for ($j = 0; $j < $length2; $j++) {

                $c2 = mb_substr($string2, $j, 1, 'UTF-8');
                $insertions = $prevRow[$j+1] + 1;
                $deletions = $currentRow[$j] + 1;
                $substitutions = $prevRow[$j] + (($c1 !== $c2) ? 1 : 0);
                $currentRow[] = min($insertions, $deletions, $substitutions);
            }

            $prevRow = $currentRow;
        }

        return $prevRow[$length2];
    }


    /**
     * Compares the percentage of similarity between two strings, based on the Levenshtein method. A very useful method
     * to use in fuzzy text searches where we want to look for similar texts.
     *
     * @param string $string1 The first string to compare
     * @param string $string2 The second string to compare
     *
     * @return number A number between 0 and 100, being 100 if both strings are the same and 0 if both strings are totally different
     */
    public static function compareSimilarityPercent($string1, $string2){

        $levenshtein = self::compareByLevenshtein($string1, $string2);

        if($levenshtein === 0){

            return 100;
        }

        return (1 - $levenshtein / max(mb_strlen($string1), mb_strlen($string2))) * 100;
    }


    /**
     * Generate a path string from an array of elements.
     *
     * @param array $elements An array of elements that will be used to form the path.
     * @param string $separator An optional separator that will be used to join the elements in the path. Defaults to `DIRECTORY_SEPARATOR`.
     *
     * @return string A string representing the path formed from the given elements.
     */
    public static function generatePath(array $elements, string $separator = ''){

        if($separator === ''){

            $separator = DIRECTORY_SEPARATOR;
        }

        return self::formatPath(implode($separator, $elements), $separator);
    }


    /**
     * Generates a random string with the specified length and options
     *
     * @param int $minLength Specify the minimum possible length for the generated string
     * @param int $maxLength Specify the maximum possible length for the generated string
     * @param array $charSet Defines the list of possible characters to be generated. Each element of charSet must be a string containing
     *                the possible characters like 'a1kjuhAO' or a range like 'a-z', 'A-D', '0-5', ... etc.
     *                Note that - character must be escaped \- when not specified as part of a range
     *                Default charset is alphanumeric ['0-9', 'a-z', 'A-Z']
     *
     * @return string A randomly generated string
     */
    public static function generateRandom(int $minLength, int $maxLength, array $charSet = ['0-9', 'a-z', 'A-Z']){

        if($minLength < 0 || !NumericUtils::isInteger($minLength) ||
           $maxLength < 0 || !NumericUtils::isInteger($maxLength)) {

               throw new InvalidArgumentException('minLength and maxLength must be positive numbers');
        }

        if($maxLength < $minLength){

            throw new InvalidArgumentException('Provided maxLength must be higher or equal than minLength');
        }

        if(!ArrayUtils::isArray($charSet) || count($charSet) <= 0){

            throw new InvalidArgumentException('invalid charset');
        }

        // Define the output charset
        $finalCharSet = '';
        $numbers = '0123456789';
        $lowerCaseLetters = 'abcdefghijkmnopqrstuvwxyz';
        $upperCaseLetters = 'ABCDEFGHIJKMNOPQRSTUVWXYZ';

        foreach ($charSet as $chars) {

            if(!self::isString($chars) || self::isEmpty($chars)){

                throw new InvalidArgumentException('invalid charset');
            }

            $firstChar = substr($chars, 0, 1);
            $thirdChar = substr($chars, 2, 1);

            // Check if an interval of characters has been defined
            if(strlen($chars) === 3 && strpos($chars, '-') === 1 && $firstChar !== '\\'){

                // Look for numeric intervals
                if(strpos($numbers, $firstChar) !== false) {

                    $finalCharSet .= substr($numbers, strpos($numbers, $firstChar), strpos($numbers, $thirdChar) + 1 - strpos($numbers, $firstChar));

                    // Look for lower case letter intervals
                } else if (strpos($lowerCaseLetters, $firstChar) !== false) {

                    $finalCharSet .= substr($lowerCaseLetters, strpos($lowerCaseLetters, $firstChar), strpos($lowerCaseLetters, $thirdChar) + 1 - strpos($lowerCaseLetters, $firstChar));

                    // Look for upper case letter intervals
                } else if(strpos($upperCaseLetters, $firstChar) !== false) {

                    $finalCharSet .= substr($upperCaseLetters, strpos($upperCaseLetters, $firstChar), strpos($upperCaseLetters, $thirdChar) + 1 - strpos($upperCaseLetters, $firstChar));
                }

            } else {

                $finalCharSet .= StringUtils::replace($chars, '\\-', '-');
            }
        }

        // Generate as many random characters as required
        $result = '' ;
        $length = ($minLength === $maxLength) ? $maxLength : NumericUtils::generateRandomInteger($minLength, $maxLength);

        for($i=0; $i<$length; $i++){

            $result .= substr($finalCharSet, mt_rand(0, strlen($finalCharSet) - 1), 1);
        }

        return $result;
    }


    /**
     * Find the string that is most similar to a provided one inside an array of strings.
     *
     * NOTE: The strings are compared by using the Levenshtein method.
     *
     * @see StringUtils::compareByLevenshtein
     *
     * @param string $string The string that we want to compare with all of the provided array
     * @param array $listOfStrings An array of strings with all the strings we want to compare
     *
     * @return string The string that was found to be more similar to the provided one
     */
    public static function findMostSimilarString($string, array $listOfStrings){

        return $listOfStrings[StringUtils::findMostSimilarStringIndex($string, $listOfStrings)];
    }


    /**
     * Find the array index that contains the string that is most similar to a provided one inside an array of strings.
     *
     * NOTE: The strings are compared by using the Levenshtein method.
     *
     * @see StringUtils::compareByLevenshtein
     *
     * @param string $string The string that we want to compare with all of the provided array
     * @param array $listOfStrings An array of strings with all the strings we want to compare
     *
     * @return int The array index for the string that was found to be more similar to the provided one
     */
    public static function findMostSimilarStringIndex($string, array $listOfStrings){

        if(!is_string($string)){

            throw new InvalidArgumentException('expected a string');
        }

        if(count($listOfStrings) <= 0){

            throw new InvalidArgumentException('listOfStrings is empty');
        }

        $mostSimilarIndex = 0;
        $mostSimilarPercentage = StringUtils::compareSimilarityPercent($string, $listOfStrings[0]);

        for ($i = 1, $l = count($listOfStrings); $i < $l; $i++) {

            $similarityPercent = StringUtils::compareSimilarityPercent($string, $listOfStrings[$i]);

            if($similarityPercent > $mostSimilarPercentage){

                $mostSimilarIndex = $i;
                $mostSimilarPercentage = $similarityPercent;
            }
        }

        return $mostSimilarIndex;
    }


    /**
     * Deletes all new line characters from the given string
     *
     * @param string $string The string to process
     *
     * @return string The string without any new line character
     */
    public static function removeNewLineCharacters($string){

        if($string === null){

            return '';
        }

        if(!self::isString($string)){

            throw new InvalidArgumentException('Specified value must be a string');
        }

        return str_replace(array("\n","\r"), '', $string);
    }


    /**
     * Converts all accent characters to ASCII characters on a given string.<br>
     * This method is based on the WordPress implementation called remove_Accents
     *
     * @param string $string Text from which accents must be cleaned
     *
     * @see https://core.trac.wordpress.org/browser/tags/3.9/src/wp-includes/formatting.php#L682
     *
     * @return string The given string with all accent and diacritics replaced by the respective ASCII characters.
     */
    public static function removeAccents($string){

        if(!is_string($string)){

            throw new InvalidArgumentException('value is not a string');
        }

        if(!preg_match('/[\x80-\xff]/', $string)){

            return $string;
        }

        $chars = array(
            // Decompositions for Latin-1 Supplement
            chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
            chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
            chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
            chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
            chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
            chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
            chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
            chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
            chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
            chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
            chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
            chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
            chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
            chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
            chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
            chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
            chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
            chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
            chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
            chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
            chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
            chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
            chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
            chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
            chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
            chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
            chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
            chr(195).chr(191) => 'y',
            // Decompositions for Latin Extended-A
            chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
            chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
            chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
            chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
            chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
            chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
            chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
            chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
            chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
            chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
            chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
            chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
            chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
            chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
            chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
            chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
            chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
            chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
            chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
            chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
            chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
            chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
            chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
            chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
            chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
            chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
            chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
            chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
            chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
            chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
            chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
            chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
            chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
            chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
            chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
            chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
            chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
            chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
            chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
            chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
            chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
            chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
            chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
            chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
            chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
            chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
            chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
            chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
            chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
            chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
            chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
            chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
            chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
            chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
            chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
            chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
            chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
            chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
            chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
            chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
            chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
            chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
            chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
            chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
        );

        return strtr($string, $chars);
    }


    /**
     * Deletes all the words that are shorter than the specified length from the provided string
     *
     * @param string $string The string to process
     * @param int $shorterThan The minimum length for the words to be preserved. So any word that is shorther than the specified value will be removed.
     * @param string $wordSeparator The character that will be used as the word separator. By default it is the empty space character ' '
     *
     * @return string The string without the removed words
     */
    public static function removeWordsShorterThan($string, int $shorterThan = 3, string $wordSeparator = ' '){

        return self::_removeWordsByLengthAux($string, $shorterThan, $wordSeparator, false);
    }


    /**
     * Deletes all the words that are longer than the specified length from the provided string
     *
     * @param string $string The string to process
     * @param int $longerThan The maximum length for the words to be preserved. Any word that exceeds the specified length will be removed from the string.
     * @param string $wordSeparator The character that will be used as the word separator. By default it is the empty space character ' '
     *
     * @return string The string without the removed words
     */
    public static function removeWordsLongerThan($string, int $longerThan = 3, string $wordSeparator = ' '){

        return self::_removeWordsByLengthAux($string, $longerThan, $wordSeparator, true);
    }


    /**
     * Aux method that is used by the removeWordsShorterThan and removeWordsLongerThan methods to strip words by length
     */
    private static function _removeWordsByLengthAux($string, int $length = 3, string $wordSeparator = ' ', $longer = true){

        $result = [];

        if($string !== null){

            foreach (explode($wordSeparator, $string) as $word) {

                if($word === '' ||
                   ($longer && strlen($word) <= $length) ||
                   (!$longer && strlen($word) >= $length)){

                    $result[] = $word;
                }
            }
        }

        return implode($wordSeparator, $result);
    }


    /**
     * Remove all urls from The string to process
     *
     * @param string $string The string to process
     * @param string $replacement The replacement string that will be shown when some url is removed
     *
     * @return string The string without the urls
     */
    public static function removeUrls($string, string $replacement = 'xxxx') {

        return preg_replace('/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+\.+[A-Za-z0-9\.\/%&=\?\-_]+/i', $replacement, $string);
    }


    /**
     * Remove all html code and tags from the specified text, so it gets converted to plain text.
     *
     * @param string $string The string to process
     * @param string $allowedTags You can use this optional second parameter to specify tags which should not be stripped. Example: '&lt;p&gt;&lt;a&gt;&lt;b&gt;&lt;li&gt;&lt;br&gt;&lt;u&gt;' To preserve the specified tags
     *
     * @return string The string without the html code
     */
    public static function removeHtmlCode($string, string $allowedTags = ''){

        return strip_tags($string , $allowedTags);
    }


    /**
     * Remove all duplicate consecutive fragments from the provided string and leave only one occurence
     *
     * @param string $string The string to process
     * @param array $set A list with the fragments that will be removed when found consecutive. If this value is
     *        an empty array, all duplicate consecutive characters will be deleted.
     *        We can pass here words or special characters like "\n"
     *
     * @example If we want to remove all duplicate consecutive empty spaces,
     *          we will call removeSameConsecutive('string', [' '])
     * @example If we want to remove all duplicate consecutive new line characters,
     *          we will call removeSameConsecutive("string\n\n\nstring", ["\n"])
     * @example If we want to remove all duplicate "hello" words, we will call
     *          removeSameConsecutive('hellohellohellohello', ['hello'])
     *
     * @return string The string with a maximum of one consecutive sequence for all those matching the provided set
     */
    public static function removeSameConsecutive($string, array $set = []){

        if($string === null){

            return '';
        }

        if(!is_string($string)){

            throw new InvalidArgumentException('string must be a string');
        }

        if($set === []){

            // All possible duplicate characters will be removed from the string
            // Note that \R represents any line ending sequence
            return preg_replace('/(.|\R)\1+/u','$1', $string);
        }

        // Replace all repeated occurences of the provided list of characters
        return preg_replace('/('.implode('|', $set).')\1+/u','$1', $string);
    }
}