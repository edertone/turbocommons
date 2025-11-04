<?php

/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */

namespace org\turbocommons\src\main\php\managers;

use Exception;
use UnexpectedValueException;
use org\turbocommons\src\main\php\model\BaseStrictClass;
use org\turbocommons\src\main\php\utils\StringUtils;
use org\turbocommons\src\main\php\model\HashMapObject;
use org\turbocommons\src\main\php\managers\httpmanager\HTTPManagerBaseRequest;
use org\turbocommons\src\main\php\managers\httpmanager\HTTPManagerGetRequest;


/**
 * Class that contains functionalities related to the HTTP protocol and its most common requests
 */
class HTTPManager extends BaseStrictClass {


    /**
     * If we want to use relative urls on all the requests that are executed by this class, we can define here a root
     * url. All the request urls will then be composed as baseUrl + requestUrl.
     *
     * This property is useful when all the requests in our application share the same root url, which can be defined here.
     */
    public $baseUrl = '';


    /**
     * Defines if the http comunications made by this class will be synchronous (code execution will be stopped while
     * waiting for the response) or asynchronous (execution flow will continue and response will be processed once received)
     * Note: Synchronous requests are normally NOT, NOT a good idea on client side languages
     */
    public $asynchronous = false;


    /**
     * Defines how much miliseconds will the http requests wait before failing with a timeout.
     * If set to 0, no value will be specifically defined, so the platform default will be used.
     */
    public $timeout = 0;


    /**
     * If this flag is enabled, any request that is made by this service which uses http:// instead of https:// will throw
     * an exception. When disabled, non secure http:// requests will be allowed
     */
    public $isOnlyHttps = true;


    /**
     * Defines a list with internet urls that will be used to test network availability by the
     * isInternetAvailable() method. We mainly use globally available CDN urls, cause these are
     * not blocked by cross-orining policies on the browsers and are widely available and replicated.
     * It may be interesting to add your own server side url at the bengining of this list, so it will
     * be the first one to be tested, and you will also check that your server is correctly responding.
     * Note that when an url request is successful, process ends and internet connection is considered
     * to be working.
     */
    public $internetCheckLocations = ['https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js',
                                      'https://ajax.aspnetcdn.com/ajax/modernizr/modernizr-2.8.3.js',
                                      'https://code.jquery.com/jquery-3.2.1.slim.min.js'];


    /**
     * Error message that is used when a timeout happens
     */
    private $ERROR_TIMEOUT = ' ms Timeout reached';


    /**
     * Structure containing all the created request queues and their status
     */
    private $_queues = [];


    /**
     * A list of key value pairs that define post parameters that will be sent ALWAYS with all the requests that are
     * performed by this class. We can use this feature for example to always send a token to web services, or other
     * globally sent post values
     */
    private $_globalPostParams = [];


    /**
     * Class that contains functionalities related to the HTTP protocol and its most common requests
     *
     * @param bool $asynchronous Specify if the HTTP manager instance will work in asynchronous or synchronous mode.
     * (Synchronous mode is NOT recommended on client side languages)
     */
    public function __construct($asynchronous = false){

        if(!is_bool($asynchronous)){

            throw new UnexpectedValueException('asynchronous is not boolean');
        }

        $this->asynchronous = $asynchronous;
    }


    /**
     * Set the value for a POST parameter that will be stored as a global POST parameter which will be always
     * sent with all the http manager requests
     *
     * @param string $parameterName The name of the POST parameter that will be always sent to all the http requests
     * @param string $value The value that the POST parameter will have
     */
    public function setGlobalPostParam($parameterName, $value){

        if(StringUtils::isEmpty($parameterName) || StringUtils::isEmpty($value)){

            throw new UnexpectedValueException('parameterName and value must be non empty strings');
        }

        $this->_globalPostParams[$parameterName] = $value;
    }


    /**
     * Check if the specified parameter name is defined as a global POST parameter
     *
     * @param string $parameterName The name of the POST parameter that we want to check
     *
     * @return boolean True if the parameter exists, false otherwise
     */
    public function isGlobalPostParam($parameterName){

        if(StringUtils::isEmpty($parameterName)){

            throw new UnexpectedValueException('parameterName must be a non empty string');
        }

        return in_array($parameterName, array_keys($this->_globalPostParams));
    }


    /**
     * Get the value for a previously defined global POST parameter
     *
     * @param string $parameterName The name of the POST parameter that we want to read
     *
     * @return string The parameter value
     */
    public function getGlobalPostParam($parameterName){

        if(!$this->isGlobalPostParam($parameterName)){

            throw new UnexpectedValueException('parameterName does not exist: '.$parameterName);
        }

        return $this->_globalPostParams[$parameterName];
    }


    /**
     * Delete a previously created global POST parameter so it is not sent with all the http manager requests anymore
     *
     * @param string $parameterName The name of the POST parameter that will be deleted
     */
    public function deleteGlobalPostParam($parameterName){

        if($this->getGlobalPostParam($parameterName) !== ''){

            unset($this->_globalPostParams[$parameterName]);
        }
    }


    /**
     * Create a new http queue. Requests can then be added to this queue with the queue() method.
     *
     * @param string $name The name we want to define for this queue
     *
     * @see $this->queue()
     *
     * @return void
     */
    public function createQueue($name){

        if(StringUtils::isEmpty($name)){

            throw new UnexpectedValueException('name must be a non empty string');
        }

        foreach ($this->_queues as $queue) {

            if($queue['name'] === $name){

                throw new UnexpectedValueException('queue '.$name.' already exists');
            }
        }

        array_push($this->_queues, ['name' => $name, 'isRunning' => false, 'pendingRequests' => []]);
    }


    /**
     * Get the number of created queues. Some may be running and some may be not
     *
     * @see $this->queue()
     *
     * @return int The number of existing queues
     */
    public function countQueues(){

        return count($this->_queues);
    }


    /**
     * Check if the specified queue is currently executing http requests
     *
     * @param string $name The name for the queue we want to check
     *
     * @see $this->queue()
     *
     * @return boolean True if the specified queue is actually running its http requests
     */
    public function isQueueRunning(string $name){

        if(StringUtils::isEmpty($name)){

            throw new UnexpectedValueException('name must be a non empty string');
        }

        foreach ($this->_queues as $queue) {

            if($queue['name'] === $name){

                return $queue['isRunning'];
            }
        }

        throw new UnexpectedValueException('queue '.$name.' does not exist');
    }


    /**
     * Remove the specified queue from this manager.
     * Make sure the queue is not running when calling this method, or an exception will happen
     *
     * @param string $name The name for the queue we want to remove
     *
     * @see $this->queue()
     *
     * @return void
     */
    public function deleteQueue($name){

        if(StringUtils::isEmpty($name)){

            throw new UnexpectedValueException('name must be a non empty string');
        }

        for($i = 0, $l = count($this->_queues); $i < $l; $i++){

            if($this->_queues[$i]['name'] === $name){

                if($this->_queues[$i]['isRunning']){

                    throw new UnexpectedValueException('queue '.$name.' is currently running');
                }

                array_splice($this->_queues, $i, 1);

                return;
            }
        }

        throw new UnexpectedValueException('queue '.$name.' does not exist');
    }


    /**
     * This method generates a GET url query from a set of key/value pairs
     *
     * A query string is the part of an url that contains the GET parameters. It is placed after
     * the ? symbol and contains a list of parameters and values that are sent to the url.
     *
     * @param array|HashMapObject $keyValuePairs An associative array or a HashMapObject containing key/value pairs that will be used to construct the query string.
     *        Note that when a value is an object or array, it will be encoded as a JSON string on the resulting query
     *
     * @see https://en.wikipedia.org/wiki/Query_string
     * @see HashMapObject
     *
     * @return string A valid query string that can be used with any url: http://www.url.com?query_string (Note that ? symbol is not included)
     */
    public function generateUrlQueryString($keyValuePairs){

        $result = '';
        $keys = [];
        $values = [];

        if(is_array($keyValuePairs) &&
           count(array_filter(array_keys($keyValuePairs), 'is_string')) > 0 &&
           count(array_keys($keyValuePairs)) > 0){

            $keys = array_keys($keyValuePairs);

            for($i = 0, $l = count($keys); $i < $l; $i++){

                array_push($values, $keyValuePairs[$keys[$i]]);
            }

        } else if (is_object($keyValuePairs) && get_class($keyValuePairs) === 'org\\turbocommons\\src\\main\\php\\model\\HashMapObject') {

            $keys = $keyValuePairs->getKeys();
            $values = $keyValuePairs->getValues();

        } else {

            throw new UnexpectedValueException('keyValuePairs must be a HashMapObject or a non empty associative array');
        }

        // Aux method to encode the keys and values
        $encodeURIComponent = function ($s) {

            return strtr(rawurlencode($s), ['%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')']);
        };

        for($i = 0, $l = count($keys); $i < $l; $i++){

            $result .= '&'.$encodeURIComponent($keys[$i]).'='.$encodeURIComponent(
                StringUtils::isString($values[$i]) ? $values[$i] : json_encode($values[$i]));
        }

        return substr($result, 1, strlen($result) - 1);
    }


    /**
     * Tells if there's currently a working internet connection available or not.
     *
     * @param callable $yesCallback Executed if the internet connection is available and working
     * @param callable $noCallback Executed if the internet connection is NOT available
     *
     * @return void
     */
    public function isInternetAvailable($yesCallback, $noCallback){

        // TODO - Adapt from TS
    }


    /**
     * Test if the specified url exists by trying to connect to it.
     * Note that crossdomain security rules may prevent this method from working correctly if you try
     * to check the existence of an url that does not allow CORS outside your application domain.
     *
     * @param string $url A full valid internet address to check
     * @param callable $yesCallback Executed if the url exists
     * @param callable $noCallback Executed if the url does not exist (or is not accessible).
     *
     * @return void
     */
    public function urlExists($url, $yesCallback, $noCallback){

        if(!StringUtils::isString($url)){

            throw new UnexpectedValueException('url must be a string');
        }

        if(!is_callable($yesCallback) || !is_callable($noCallback)){

            throw new UnexpectedValueException('params must be functions');
        }

        $composedUrl = $this->_composeUrl($this->baseUrl, $url);

        if(!StringUtils::isUrl($composedUrl)){

            $noCallback();

            return;
        }

        $request = new HTTPManagerGetRequest($composedUrl);

        $request->successCallback = function () use ($yesCallback) { $yesCallback(); };
        $request->errorCallback = function () use ($noCallback) { $noCallback(); };

        $this->execute($request);
    }


    /**
     * Get the Http headers for a given url.
     * Note that crossdomain security rules may prevent this method from working correctly
     *
     * @param string $url The url for which we want to get the http headers.
     * @param callable $successCallback Executed when headers are read. An array of strings will be passed to this method
     *        containing all the read headers with each header line as an array element.
     * @param callable $errorCallback Executed if headers cannot be read. A string containing the error description and the error
     *        code will be passed to this method.
     *
     * @return void
     */
    public function getUrlHeaders(string $url,
                                  $successCallback,
                                  $errorCallback){

        // TODO - translate from TS
    }


    /**
     * Launch one or more http requests without caring about their execution order.
     *
     * @param string|array|HTTPManagerBaseRequest $requests One or more requests to be inmediately launched (at the same time if possible). Each request can be defined as a string
     *        that will be used as a GET request url, or as an HTTPManagerBaseRequest instance in case we want to define parameters and callbacks.
     * @param callable $finishedCallback A method to be executed once all the http requests have finished (either succesfully or with errors). The callback will
     *        receive two parameters: results (an array with information about each request result in the same order as provided to this method) and
     *        anyError (true if any of the requests has failed)
     * @param callable $progressCallback Executed after each one of the urls finishes (either successfully or with an error). A string with the requested url and
     *        the total requests to perform will be passed to this method.
     *
     * @return void
     */
    public function execute($requests,
                            $finishedCallback = null,
                            $progressCallback = null){

        $requestsList = $this->_generateValidRequestsList($requests);
        $requestsListCount = count($requestsList);

        // Validate callbacks are ok
        if(($finishedCallback !== null && !is_callable($finishedCallback)) ||
            ($progressCallback !== null && !is_callable($progressCallback))){

            throw new UnexpectedValueException('finishedCallback and progressCallback must be functions');
        }

        $finishedCount = 0;
        $finishedAnyError = false;
        $finishedResults = [];

        // A method that will be executed every time a request is finished (even successfully or with errors)
        $processFinishedRequest = function (array $requestWithIndex,
                                            string $response,
                                            bool $isError,
                                            string $errorMsg,
                                            int $code) use ($requestsList, $progressCallback, $finishedCallback, $requestsListCount,
                                                            &$finishedCount, &$finishedAnyError, &$finishedResults) {

            $request = $requestWithIndex['request'];
            $composedUrl = $this->_composeUrl($this->baseUrl, $request->url);

            $formattedResponse = $response;

            if($request->resultFormat === HTTPManagerBaseRequest::JSON){

                $formattedResponse = json_decode($response, true);

                if(json_last_error() !== JSON_ERROR_NONE) {

                    $formattedResponse = $response;

                    if(!$isError){

                        $isError = true;
                        $errorMsg = 'Could not parse request result as a json string';
                    }
                }
            }

            $finishedCount ++;
            $finishedResults[$requestWithIndex['index']] = ['url' => $composedUrl,
                                                            'response' => $formattedResponse,
                                                            'isError' => $isError,
                                                            'errorMsg' => $errorMsg,
                                                            'code' => $code];

            if($isError){

                $finishedAnyError = true;
                call_user_func($request->errorCallback, $errorMsg, $code, $response);

            }else{

                call_user_func($request->successCallback, $formattedResponse);
            }

            call_user_func($request->finallyCallback);

            if($progressCallback !== null){

                $progressCallback($composedUrl, $requestsListCount);
            }

            if($finishedCount >= count($requestsList) && $finishedCallback !== null){

                $finishedCallback($finishedResults, $finishedAnyError);
            }
        };

        // Execute each one of the received requests and process their results
        if(!extension_loaded('curl')){

            throw new UnexpectedValueException('Could not initialize curl. Make sure it is available on your php system');
        }

        $curlInstances = [];
        $curlHandle = curl_multi_init();

        for($i = 0; $i < $requestsListCount; $i++){

            if(!is_object($requestsList[$i]) || !property_exists($requestsList[$i], 'url') ||
               !StringUtils::isString($requestsList[$i]->url) || StringUtils::isEmpty($requestsList[$i]->url)){

                throw new UnexpectedValueException('url '.$i.' must be a non empty string');
            }

            $curlInstances[$i] = curl_init($this->_composeUrl($this->baseUrl, $requestsList[$i]->url));

            curl_setopt($curlInstances[$i], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curlInstances[$i], CURLOPT_FAILONERROR, true);
            curl_setopt($curlInstances[$i], CURLOPT_FOLLOWLOCATION, false);

            // Define the request timeout if specified on the request or the httpmanager class
            if($requestsList[$i]->timeout > 0 || $this->timeout > 0){

                curl_setopt($curlInstances[$i], CURLOPT_CONNECTTIMEOUT, $requestsList[$i]->timeout > 0 ? $requestsList[$i]->timeout : $this->timeout);
            }

            // Detect the request type
            $requestType = get_class($requestsList[$i]) === get_class(new HTTPManagerGetRequest('')) ? 'GET' : 'POST';

            // Encode the GET request parameters if any and run the request
            if($requestType === 'GET'){

                // TODO - implement the GET request params
            }

            // Encode the POST request parameters if any and run the request
            if($requestType === 'POST'|| count(array_keys($this->_globalPostParams)) > 0){

                try {

                    $postParamsToSend = ($requestType === 'POST') ? $requestsList[$i]->parameters : [];

                    // Add the global post parameters if any has been defined
                    if($requestsList[$i]->ignoreGlobalPostParams === false){

                        foreach ($this->_globalPostParams as $globalPostParam => $globalPostParamValue) {

                            if(get_class($postParamsToSend) === 'org\\turbocommons\\src\\main\\php\\model\\HashMapObject'){

                                $postParamsToSend->set($globalPostParam, $globalPostParamValue);

                            }else{

                                $postParamsToSend[$globalPostParam] = $globalPostParamValue;
                            }
                        }
                    }

                    curl_setopt($curlInstances[$i], CURLOPT_POST, true);
                    curl_setopt($curlInstances[$i], CURLOPT_POSTFIELDS, $this->generateUrlQueryString($postParamsToSend));

                } catch (Exception $e) {

                    // Nothing to do
                }
            }

            curl_multi_add_handle($curlHandle, $curlInstances[$i]);
        }

        // Execute all the requests asynchronously
        $stillRunning = 0;

        do {

            curl_multi_exec($curlHandle, $stillRunning);

        } while($stillRunning > 0);

        // Obtain the results for all the requests
        for($i = 0; $i < $requestsListCount; $i++){

            $requestWithIndex = ['index' => $i, 'request' => $requestsList[$i]];

            if(curl_errno($curlInstances[$i]) === 28){

                $processFinishedRequest($requestWithIndex, curl_multi_getcontent($curlInstances[$i]), true, $this->timeout.$this->ERROR_TIMEOUT, 408);

            } elseif (curl_error($curlInstances[$i])) {

                $processFinishedRequest($requestWithIndex, curl_multi_getcontent($curlInstances[$i]), true, curl_error($curlInstances[$i]), curl_getinfo($curlInstances[$i], CURLINFO_RESPONSE_CODE));

            } elseif (curl_getinfo($curlInstances[$i], CURLINFO_HTTP_CODE) === 0){

                $processFinishedRequest($requestWithIndex, curl_multi_getcontent($curlInstances[$i]), true, 'Could not connect with: '.$requestWithIndex['request']->url, 0);

            } else {

                $processFinishedRequest($requestWithIndex, curl_multi_getcontent($curlInstances[$i]), false, '', curl_getinfo($curlInstances[$i], CURLINFO_HTTP_CODE));
            }

            curl_multi_remove_handle($curlHandle, $curlInstances[$i]);
        }

        curl_multi_close($curlHandle);
    }


    /**
     * Auxiliary method to generate a valid list of HTTPManagerBaseRequest instances from multiple sources
     */
    private function _generateValidRequestsList($requests){

        // Convert the received requests to a standarized array of HTTPManagerBaseRequest instances
        $requestsList = [];

        if(is_array($requests)){

            if(count($requests) <= 0){

                throw new UnexpectedValueException('No requests to execute');
            }

            foreach ($requests as $requestItem) {

                if(is_string($requestItem)){

                    array_push($requestsList, new HTTPManagerGetRequest($requestItem));

                }else{

                    array_push($requestsList, $requestItem);
                }
            }

        }else{

            if(is_string($requests) && !StringUtils::isEmpty($requests)){

                $requestsList = [new HTTPManagerGetRequest($requests)];

            } else if (get_parent_class($requests) === 'org\\turbocommons\\src\\main\\php\\managers\\httpmanager\\HTTPManagerBaseRequest'){

                $requestsList = [$requests];

            }else{

                throw new UnexpectedValueException('Invalid requests value');
            }
        }

        return $requestsList;
    }


    public function queue($todo){

        // TODO - translate from TS
    }


    private function _startQueue($todo){

        // TODO - translate from TS
    }


    public function loadResourcesFromList($todo){

        // TODO - translate from TS
    }


    /**
     * Auxiliary method to join two urls: A base one, and a relative one
     *
     * If a full absolute url is passed to the relativeUrl variable, the result of this method will be the relative one, ignoring
     * any possible value on baseUrl.
     */
    private function _composeUrl($baseUrl, $relativeUrl){

        $composedUrl = '';

        if (StringUtils::isEmpty($baseUrl) ||
            substr($relativeUrl, 0, 5) === 'http:' ||
            substr($relativeUrl, 0, 6) === 'https:') {

            $composedUrl = $relativeUrl;

        } else {

            $composedUrl = StringUtils::replace(StringUtils::formatPath($baseUrl.'/'.$relativeUrl, '/'),
                ['http:/', 'https:/'],
                ['http://', 'https://'], 1);
        }

        if($this->isOnlyHttps && strtolower(substr($composedUrl, 0, 5)) === 'http:'){

            throw new UnexpectedValueException('Non secure http requests are forbidden. Set isOnlyHttps=false to allow '.$composedUrl);
        }

        return $composedUrl;
    }
}

?>