/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */


import { StringUtils } from '../utils/StringUtils';
import { ObjectUtils } from '../utils/ObjectUtils';
import { ArrayUtils } from '../utils/ArrayUtils';
import { HashMapObject } from '../model/HashMapObject';
import { HTTPManagerGetRequest } from './httpmanager/HTTPManagerGetRequest';
import { HTTPManagerPostRequest } from './httpmanager/HTTPManagerPostRequest';
import { HTTPManagerBaseRequest } from './httpmanager/HTTPManagerBaseRequest';

   
/**
 * Class that contains functionalities related to the HTTP protocol and its most common requests
 */ 
export class HTTPManager{
    
    
    /** 
     * If we want to use relative urls on all the requests that are executed by this class, we can define here a root
     * url. All the request urls will then be composed as baseUrl + requestUrl.
     * 
     * This property is useful when all the requests in our application share the same root url, which can be defined here.
     */
    baseUrl = '';
    
    
    /** 
     * Defines if the http comunications made by this class will be synchronous (code execution will be stopped while 
     * waiting for the response) or asynchronous (execution flow will continue and response will be processed once received)
     * Note: Synchronous requests are normally NOT, NOT a good idea on client side languages 
     */
    asynchronous = true;
    
    
    /** 
     * Defines how much miliseconds will the http requests wait before failing with a timeout.
     * If set to 0, no value will be specifically defined, so the platform default will be used.
     */
    timeout = 0;
    
    
    /** 
     * If this flag is enabled, any request that is made by this service which uses http:// instead of https:// will throw
     * an exception. When disabled, non secure http:// requests will be allowed
     */
    isOnlyHttps = true;
    

    /** 
     * Defines a list with internet urls that will be used to test network availability by the 
     * isInternetAvailable() method. We mainly use globally available CDN urls, cause these are 
     * not blocked by cross-orining policies on the browsers and are widely available and replicated.
     * It may be interesting to add your own server side url at the bengining of this list, so it will 
     * be the first one to be tested, and you will also check that your server is correctly responding.
     * Note that when an url request is successful, process ends and internet connection is considered
     * to be working.
     */
    internetCheckLocations = ['https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js',
                              'https://ajax.aspnetcdn.com/ajax/modernizr/modernizr-2.8.3.js',
                              'https://code.jquery.com/jquery-3.2.1.slim.min.js'];
    
    
    /**
     * Error message that is used when a timeout happens
     */
    private static ERROR_TIMEOUT = ' ms Timeout reached';
    
    
    /**
     * Structure containing all the created request queues and their status
     */
    private _queues:{name:string,
                     isRunning: boolean,
                     pendingRequests: HTTPManagerBaseRequest[]}[] = [];
    
    
    /**
     * A list of key value pairs that define post parameters that will be sent ALWAYS with all the requests that are
     * performed by this class. We can use this feature for example to always send a token to web services, or other
     * globally sent post values
     */
    private _globalPostParams:{[key:string] : any} = {};
    

    /**
     * Class that contains functionalities related to the HTTP protocol and its most common requests
     * 
     * @param asynchronous Specify if the HTTP manager instance will work in asynchronous or synchronous mode. 
     * (Synchronous mode is NOT recommended on client side languages)
     */
    constructor(asynchronous = true){
        
        if(typeof asynchronous !== 'boolean'){
            
            throw new Error('asynchronous is not boolean');
        }
        
        this.asynchronous = asynchronous;
    }
    
    
    /**
     * Set the value for a POST parameter that will be stored as a global POST parameter which will be always
     * sent with all the http manager requests
     *
     * @param parameterName The name of the POST parameter that will be always sent to all the http requests
     * @param value The value that the POST parameter will have
     */
    setGlobalPostParam(parameterName:string, value:string){
        
        if(StringUtils.isEmpty(parameterName) || StringUtils.isEmpty(value)){
        
            throw new Error('parameterName and value must be non empty strings');
        }
        
        this._globalPostParams[parameterName] = value;       
    }
    
    
    /**
     * Check if the specified parameter name is defined as a global POST parameter
     *
     * @param parameterName The name of the POST parameter that we want to check
     *
     * @return True if the parameter exists, false otherwise
     */
    isGlobalPostParam(parameterName:string){
        
        if(StringUtils.isEmpty(parameterName)){
        
            throw new Error('parameterName must be a non empty string');
        }
        
        return Object.keys(this._globalPostParams).indexOf(parameterName) >= 0;
    }
    
    
    /**
     * Get the value for a previously defined global POST parameter
     *
     * @param parameterName The name of the POST parameter that we want to read
     *
     * @return The parameter value
     */
    getGlobalPostParam(parameterName:string){
        
        if(!this.isGlobalPostParam(parameterName)){
            
            throw new Error('parameterName does not exist: ' + parameterName);
        }
        
        return this._globalPostParams[parameterName];       
    }
    
    
    /**
     * Delete a previously created global POST parameter so it is not sent with all the http manager requests anymore
     *
     * @param parameterName The name of the POST parameter that will be deleted
     */
    deleteGlobalPostParam(parameterName:string){
        
        if(this.getGlobalPostParam(parameterName) !== ''){

            delete this._globalPostParams[parameterName];
        }
    }
    
    
    /**
     * Create a new http queue. Requests can then be added to this queue with the queue() method.
     * 
     * @param name The name we want to define for this queue
     * 
     * @see this.queue()
     * 
     * @returns void
     */
    createQueue(name: string){
        
        if(StringUtils.isEmpty(name)){
        
            throw new Error('name must be a non empty string');
        }
        
        for (let queue of this._queues) {

            if(queue.name === name){
               
                throw new Error(`queue ${name} already exists`);
            }
        }
        
        this._queues.push({name: name, isRunning: false, pendingRequests: []});
    }
    
    
    /**
     * Get the number of created queues. Some may be running and some may be not
     * 
     * @see this.queue()
     * 
     * @return The number of existing queues
     */
    countQueues(){
        
        return this._queues.length;
    }
    
    
    /**
     * Check if the specified queue is currently executing http requests
     * 
     * @param name The name for the queue we want to check
     * 
     * @see this.queue()
     * 
     * @return boolean True if the specified queue is actually running its http requests
     */
    isQueueRunning(name: string){
        
        if(StringUtils.isEmpty(name)){
            
            throw new Error('name must be a non empty string');
        }
        
        for (let queue of this._queues) {
            
            if(queue.name === name){
               
                return queue.isRunning;
            }
        }
        
        throw new Error(`queue ${name} does not exist`);
    }
    
    
    /**
     * Remove the specified queue from this manager. 
     * Make sure the queue is not running when calling this method, or an exception will happen
     * 
     * @param name The name for the queue we want to remove
     * 
     * @see this.queue()
     * 
     * @return void
     */
    deleteQueue(name: string){
        
        if(StringUtils.isEmpty(name)){
            
            throw new Error('name must be a non empty string');
        }

        for (var i = 0; i < this._queues.length; i++) {

            if(this._queues[i].name === name){
                
                if(this._queues[i].isRunning){
                    
                    throw new Error(`queue ${name} is currently running`);
                }
                
                this._queues.splice(i, 1);
                
                return;
            }
        }
        
        throw new Error(`queue ${name} does not exist`);
    }
    
    
    /**
     * This method generates a GET url query from a set of key/value pairs
     * 
     * A query string is the part of an url that contains the GET parameters. It is placed after
     * the ? symbol and contains a list of parameters and values that are sent to the url.
     * 
     * @param keyValuePairs An object or a HashMapObject containing key/value pairs that will be used to construct the query string.
     *        Note that when a value is an object or array, it will be encoded as a JSON string on the resulting query
     * 
     * @see https://en.wikipedia.org/wiki/Query_string
     * @see HashMapObject
     *
     * @return A valid query string that can be used with any url: http://www.url.com?query_string (Note that ? symbol is not included)
     */
    generateUrlQueryString(keyValuePairs: { [key: string]: any } | HashMapObject){
        
        let result = '';
        let keys:string[] = [];
        let values:string[] = [];
        
        if(ObjectUtils.isObject(keyValuePairs) && ObjectUtils.getKeys(keyValuePairs).length > 0){
        
            if(keyValuePairs instanceof HashMapObject){
                
                keys = (keyValuePairs as HashMapObject).getKeys();
                values = (keyValuePairs as HashMapObject).getValues();
            
            } else {
                
                keys = Object.getOwnPropertyNames(keyValuePairs);

                for(var i = 0; i < keys.length; i++){

                    values.push(keyValuePairs[keys[i]]);
                }
            }
            
            for (var i = 0; i < keys.length; i++) {
                
                result += '&' + encodeURIComponent(keys[i]) + '=' + encodeURIComponent(
                        StringUtils.isString(values[i]) ? values[i] : JSON.stringify(values[i]));
            }

            return result.substring(1, result.length);        
        }

        throw new Error('keyValuePairs must be a HashMapObject or a non empty Object');
    }
        
    
    /**
     * Tells if there's currently a working internet connection available or not.
     *  
     * @param yesCallback Executed if the internet connection is available and working
     * @param noCallback Executed if the internet connection is NOT available
     * 
     * @return void
     */
    isInternetAvailable(yesCallback: () => void, noCallback: () => void){
        
        if(typeof yesCallback  !== 'function' || typeof noCallback !== 'function'){
            
            throw new Error('params must be functions');
        }
        
        if(this.internetCheckLocations.length <= 0){
            
            throw new Error('no check locations specified');
        }
        
        // A recursive function that will loop all the defined list of urls to check
        // And execute the appropiate result callback
        let recursiveUrlTest = (urls: string[]) => {
        
            if(urls.length <= 0){
                
                return noCallback();
            }
            
            let url = urls.shift();
                
            if(!StringUtils.isUrl(url)){
                
                throw new Error("invalid check url : " + url);
            }
            
            // We must prevent the browser cache from giving false positives, so we generate
            // an url containing a random GET parameter
            this.urlExists(String(url + '?r=' + StringUtils.generateRandom(15, 15)),
                yesCallback,
                () => recursiveUrlTest(urls));
        }
        
        if(navigator.onLine === false){
            
            // Navigator.online is only fiable when it returns false. If it returns true, we still need to
            // test the internet connectivity by performing a real check via recursiveUrlTest
            noCallback();
        
        }else{
        
            // Note that we use slice to create a clone of the array to prevent it from being modified
            // by the recursive method
            recursiveUrlTest(this.internetCheckLocations.slice(0));
        }       
    }
    
    
    /**
     * Test if the specified url exists by trying to connect to it.
     * Note that crossdomain security rules may prevent this method from working correctly if you try
     * to check the existence of an url that does not allow CORS outside your application domain.
     * 
     * @param url A full valid internet address to check
     * @param yesCallback Executed if the url exists
     * @param noCallback Executed if the url does not exist (or is not accessible).
     *
     * @return void
     */
    urlExists(url:string, yesCallback: () => void, noCallback: () => void){
    
        if(!StringUtils.isString(url)){

            throw new Error('url must be a string');
        }
        
        if(typeof yesCallback  !== 'function' || typeof noCallback !== 'function'){
            
            throw new Error('params must be functions');
        }

        let composedUrl = this._composeUrl(this.baseUrl, url);
        
        if(!StringUtils.isUrl(composedUrl)){

            noCallback();
            
            return;
        }
        
        let request = new HTTPManagerGetRequest(composedUrl);
        
        request.successCallback = () => yesCallback(); 
        request.errorCallback = () => noCallback();
        
        this.execute(request);        
    }
    
    
    /**
     * Get the Http headers for a given url.
     * Note that crossdomain security rules may prevent this method from working correctly
     *
     * @param url The url for which we want to get the http headers.
     * @param successCallback Executed when headers are read. An array of strings will be passed to this method
     *        containing all the read headers with each header line as an array element.
     * @param errorCallback Executed if headers cannot be read. A string containing the error description and the error
     *        code will be passed to this method.
     * 
     * @return void
     */
    getUrlHeaders(url:string,
                  successCallback: (headersArray:string[]) => void,
                  errorCallback: (errorMsg:string, errorCode:number) => void){
    
        let composedUrl = this._composeUrl(this.baseUrl, url);
        
        if(!StringUtils.isString(composedUrl)){

            throw new Error('url must be a string');
        }
        
        if(typeof successCallback  !== 'function' || typeof errorCallback !== 'function'){
            
            throw new Error('params must be functions');
        }

        if(!StringUtils.isUrl(composedUrl)){

            throw new Error('invalid url ' + composedUrl);
        }
        
        let xmlHttprequest = new XMLHttpRequest();
        
        if(this.timeout > 0){
        
            xmlHttprequest.timeout = this.timeout;
        }
        
        xmlHttprequest.open('GET', composedUrl, this.asynchronous);
        
        xmlHttprequest.onload = () => successCallback(xmlHttprequest.getAllResponseHeaders().split("\n"));

        xmlHttprequest.onerror = () => errorCallback(xmlHttprequest.statusText, xmlHttprequest.status);
        
        xmlHttprequest.ontimeout = () => errorCallback(this.timeout + HTTPManager.ERROR_TIMEOUT, 408);
        
        this._executeXmlHttprequestSend(xmlHttprequest, composedUrl);
    }
       
    
    /**
     * Launch one or more http requests without caring about their execution order.
     * 
     * @param requests One or more requests to be inmediately launched (at the same time if possible). Each request can be defined as a string
     *        that will be used as a GET request url, or as an HTTPManagerBaseRequest instance in case we want to define parameters and callbacks.
     * @param finishedCallback A method to be executed once all the http requests have finished (either succesfully or with errors). The callback will
     *        receive two parameters: results (an array with information about each request result in the same order as provided to this method) and
     *        anyError (true if any of the requests has failed)      
     * @param progressCallback Executed after each one of the urls finishes (either successfully or with an error). A string with the requested url and
     *        the total requests to perform will be passed to this method.
     *        
     * @return void
     */
    execute(requests: string|string[]|HTTPManagerBaseRequest|HTTPManagerBaseRequest[],
            finishedCallback: ((results: {url:string, response:any, isError:boolean, errorMsg:string, code:number}[], anyError:boolean) => void) | null = null,
            progressCallback: null | ((completedUrl: string, totalRequests: number) => void) = null){
        
        let requestsList = this._generateValidRequestsList(requests);
        
        // Validate callbacks are ok
        if((finishedCallback !== null && !(finishedCallback instanceof Function)) ||
           (progressCallback !== null && !(progressCallback instanceof Function))){
        
            throw new Error('finishedCallback and progressCallback must be functions');
        }
        
        let finishedCount = 0;
        let finishedAnyError = false;
        let finishedResults:{url:string, response:string, isError:boolean, errorMsg:string, code:number}[] = [];
        
        // A method that will be executed every time a request is finished (even successfully or with errors)
        const processFinishedRequest = (requestWithIndex:{index:number, request:HTTPManagerBaseRequest},
                                        response:string,
                                        isError:boolean,
                                        errorMsg:string,
                                        code:number) => {
            
            let request = requestWithIndex.request;
            let composedUrl = this._composeUrl(this.baseUrl, request.url);
            
            let formattedResponse = response;
            
            if(request.resultFormat === HTTPManagerBaseRequest.JSON){
                
                try{
                
                    formattedResponse = JSON.parse(response);
                
                } catch (e) {
                    
                    if(!isError){
                        
                        isError = true;
                        errorMsg = 'Could not parse request result as a json string';
                    }
                }
            }
                
            finishedCount ++;
            finishedResults[requestWithIndex.index] = {url:composedUrl,
                                                       response:formattedResponse,
                                                       isError:isError,
                                                       errorMsg:errorMsg,
                                                       code:code};
            
            if(isError){
                
                finishedAnyError = true;
                request.errorCallback(errorMsg, code, response);
            
            }else{
                
                request.successCallback(formattedResponse);
            }
            
            request.finallyCallback();
            
            if(progressCallback !== null){
                
                progressCallback(composedUrl, requestsList.length);
            }
            
            if(finishedCount >= requestsList.length && finishedCallback !== null){
                
                finishedCallback(finishedResults, finishedAnyError);
            }
        };
        
        // Execute each one of the received requests and process their results
        for (let i = 0; i < requestsList.length; i++) {

            let requestWithIndex = {index: i, request: requestsList[i]};
            
            if(!StringUtils.isString(requestsList[i].url) || StringUtils.isEmpty(requestsList[i].url)){
                
                throw new Error(`url ${i} must be a non empty string`);
            }
            
            let xmlHttprequest:XMLHttpRequest; 
                
            try {

                xmlHttprequest = new XMLHttpRequest();

            } catch (e) {

                throw new Error("Could not initialize XMLHttpRequest. If running node, it is not natively available. We recommend npm xhr2 library that emulates XMLHttpRequest on node apps (global.XMLHttpRequest = require('xhr2'))");
            }
            
            // Define the request timeout if specified on the request or the httpmanager class
            if(requestsList[i].timeout > 0 || this.timeout > 0){
                
                xmlHttprequest.timeout = requestsList[i].timeout > 0 ? requestsList[i].timeout : this.timeout;
            }
            
            // Detect the request type
            let composedUrl = this._composeUrl(this.baseUrl, requestsList[i].url);
            let requestType = requestsList[i] instanceof HTTPManagerGetRequest ? 'GET' : 'POST';
            
            xmlHttprequest.open(requestType, composedUrl, this.asynchronous);
            
            xmlHttprequest.onload = () => {
            
                if (xmlHttprequest.status >= 200 && xmlHttprequest.status < 400) {
                
                    processFinishedRequest(requestWithIndex, xmlHttprequest.responseText, false, '', xmlHttprequest.status);
                
                } else {
                
                    processFinishedRequest(requestWithIndex, xmlHttprequest.responseText, true, xmlHttprequest.statusText, xmlHttprequest.status);
                }
            };

            xmlHttprequest.onerror = () => {

                processFinishedRequest(requestWithIndex, xmlHttprequest.responseText, true, xmlHttprequest.statusText, xmlHttprequest.status);
            };
            
            xmlHttprequest.ontimeout = () => {
                
                processFinishedRequest(requestWithIndex, xmlHttprequest.responseText, true, this.timeout + HTTPManager.ERROR_TIMEOUT, 408);
            };
            
            // Encode the GET request parameters if any and run the request
            if(requestType === 'GET'){
                
                // TODO - implement the GET request params
                
                this._executeXmlHttprequestSend(xmlHttprequest, composedUrl);
            }

            // Encode the POST request parameters if any and run the request
            if(requestType === 'POST' || Object.keys(this._globalPostParams).length > 0){

                try {
                    
                    let postParamsToSend = (requestType === 'POST') ? (requestsList[i] as HTTPManagerPostRequest).parameters : {};
                    
                    // Add the global post parameters if any has been defined
                    if(requestsList[i].ignoreGlobalPostParams === false){
                        
                        for (let globalPostParam of Object.keys(this._globalPostParams)) {
    
                            if(postParamsToSend instanceof HashMapObject){
                                
                                postParamsToSend.set(globalPostParam, this._globalPostParams[globalPostParam]);
                                
                            }else{
                                
                                postParamsToSend[globalPostParam] = this._globalPostParams[globalPostParam];                            
                            }
                        }
                    }
        
                    xmlHttprequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xmlHttprequest.send(this.generateUrlQueryString(postParamsToSend));
                    
                } catch (e) {
                    
                    this._executeXmlHttprequestSend(xmlHttprequest, composedUrl);
                }
            }
        }
    }
    
    
    /**
     * Auxiliary method to call the send method for an XMLHttpRequest with more explanatory error checking
     * 
     * NOTE: this method is exclusive for the typescript / javascript versions of turbocommons
     */
    private _executeXmlHttprequestSend(xmlHttprequest: XMLHttpRequest, url: string){
        
        try {

            xmlHttprequest.send();
        
        } catch (e) {
        
            throw new Error('HTTPManager could not execute request to ' + url + '\n' + e.toString());
        }
    }
    
    
    /**
     * Auxiliary method to generate a valid list of HTTPManagerBaseRequest instances from multiple sources
     */
    private _generateValidRequestsList(requests:string|string[]|HTTPManagerBaseRequest|HTTPManagerBaseRequest[]){
        
        // Convert the received requests to a standarized array of HTTPManagerBaseRequest instances
        let requestsList:HTTPManagerBaseRequest[] = [];
        
        if(ArrayUtils.isArray(requests)){
            
            if((requests as Array<any>).length <= 0){
                
                throw new Error('No requests to execute');
            }
            
            for (let requestItem of (requests as Array<any>)) {

                if(StringUtils.isString(requestItem)){

                    requestsList.push(new HTTPManagerGetRequest(requestItem));

                }else{

                    requestsList.push(requestItem);
                }
            }
                        
        }else{
            
            if(StringUtils.isString(requests) && !StringUtils.isEmpty(requests as string)){
                
                requestsList = [new HTTPManagerGetRequest(requests as string)];
                
            } else if (requests instanceof HTTPManagerBaseRequest){
                
                requestsList = [requests as HTTPManagerBaseRequest];
            
            }else{
                
                throw new Error('Invalid requests value');
            }
        }
        
        return requestsList;
    }
    
    
    /**
     * Sequentially launch one or more http requests to the specified queue, one after the other.
     * Each request will start inmediately after the previous one is finished (either succesfully or with an error).
     * We can have several independent queues that run their requests at the same time. 
     * 
     * @param requests One or more requests that must be added to the specified queue. Each request can be defined as a string
     *        that will be used as a GET request url, or as an HTTPManagerBaseRequest instance in case we want to define parameters and callbacks.
     *        Requests will be sequentially executed one after the other in the same order. If the specified queue contains requests
     *        that have not finished yet, they will be executed before the ones provided here.
     * @param queueName The name for an existing queue (created with this.createQueue()) where the specified requests will be added
     * @param finishedAllCallback A method that will be executed once all the queued requests by this method have finished. Note that
     *        if the specified queue already contains running requests, the current ones will be added to be executed after and
     *        when all have finished, this method will be called.
     * 
     * @returns void
     */
    queue(requests: string|string[]|HTTPManagerBaseRequest|HTTPManagerBaseRequest[],
          queueName: string,
          finishedCallback: (() => void) | null = null){
    
        // TODO - this method is almost finished, but some things are pending:
        // 1- FinishedCallback should work the same way as the execute method, and give us the results data and info
        // 2- There should be a progressCallback method
        // 3- Extensive tests must be written: verify requests are sequentially executed one after the other, and all
        //    the rest of the expected behaviour
        
        let requestsList = this._generateValidRequestsList(requests);

        // Validate callbacks are ok
        if((finishedCallback !== null && !(finishedCallback instanceof Function))){
        
            throw new Error('finishedCallback and progressCallback must be functions');
        }
        
        for (let queue of this._queues) {

            if(queue.name === queueName){
                
                // Add all the received requests to the beginning of the queue pending array
                for (var i = 0; i < requestsList.length; i++) {

                    queue.pendingRequests.unshift(requestsList[i]);
                }
                
                // Add a dummy request with a special url, containing the finished callback method
                // to be executed after all the requests are done
                if(finishedCallback !== null){
                    
                    let dummyRequest = new HTTPManagerGetRequest('FINISHED_REQUEST_CALLBACK');
                    
                    dummyRequest.finallyCallback = finishedCallback;
                    
                    queue.pendingRequests.unshift(dummyRequest);
                }
                
                // Run the queue if it is not already processing requests
                if(!this.isQueueRunning(queueName)){
                    
                    this._startQueue(queueName);
                }
                
                return;
            }
        }  
        
        throw new Error(`queue ${queueName} does not exist. Create it with createQueue()`);
    }
    
    
    /**
     * Auxiliary method that is used to begin executing the http requests that are pending on the specified queue.
     * A recursive operation will be used to launch the next http request once the previous has totally finished.
     * 
     * @param name The name for the queue we want to start
     * 
     * @returns void
     */
    private _startQueue(name:string){

        // Recursive method that will perform the calls to the queue requests
        let runRequests = (queue: {name: string,
                                   isRunning: boolean,
                                   pendingRequests: HTTPManagerBaseRequest[]}) => {
            
            if(queue.pendingRequests.length <= 0){
                
                queue.isRunning = false;
            
            } else {
                
                // Check if a finished queue callback must be called
                if(queue.pendingRequests[queue.pendingRequests.length - 1].url === 'FINISHED_REQUEST_CALLBACK'){
                    
                    let finallyCallback = queue.pendingRequests.pop() as HTTPManagerBaseRequest;
                    
                    if(queue.pendingRequests.length <= 0){
                    
                        queue.isRunning = false;
                    }
                    
                    finallyCallback.finallyCallback();
                }
                
                if(queue.pendingRequests.length > 0){
                                        
                    queue.isRunning = true;
    
                    this.execute(queue.pendingRequests.pop() as HTTPManagerBaseRequest, () => runRequests(queue));           
                }                    
            }
        };
        
        // Find the requested queue and start the recursive execution on it
        for (let queue of this._queues) {
            
            if(queue.name === name){
                
                runRequests(queue);
                
                return;
            }            
        }
    }
    
    
    /**
     * Given a url with a list of resources (normally files), this method will perform a request for each one of them and
     * store the whole file contents as an element of a result array. After all the process completes, the array containing all the loaded
     * data will be available by the successCallback method.
     * 
     * This is a technique that allows us to read a big list of files from an http server without needing to
     * write much code. We simply put the files on the server, create a list with all the file names, provide the base url for all the files,
     * and call this method. When the process succeeds, we will have all the files data loaded and ready to be used. We have also a progress callback
     * that will notify us when each one of the files is correctly loaded.
     * 
     * @param urlToListOfResources An url that gives us the list of resources to be loaded (normally a plain list of file names)
     * @param baseUrl A url that will be used as the root for all the files of the list when the load is performed. This usually is the path
     *                 to the url folder that contains the files. Each request to a file will be composed with this baseUrl + the respective entry of the file
     *                 on urlToListOfResources
     * @param successCallback Executed once all the resources have been loaded. Two parameters will be passed to this method: An array with
     *                        The list of resources as they are defined on the urlToListOfResources, and an array containing all the data for each
     *                        one of these resources. 
     * @param errorCallback Executed if a failure happens on any of the requests. The url that caused the error,
     *                      the error description and the error code will be passed to this method.
     * @param progressCallback Executed after each one of the resources is correctly loaded. A string with the correctly
     *                         requested url will be passed to this method.
     * 
     * @returns void
     */
    loadResourcesFromList(urlToListOfResources: string,
                          baseUrl: string,
                          successCallback: (resourcesList: string[], resourcesData: string[]) => void,
                          errorCallback: (errorUrl:string, errorMsg:string, errorCode:number) => void,
                          progressCallback: ((completedUrl: string) => void) | null = null){
        
        if(!StringUtils.isString(urlToListOfResources) || StringUtils.isEmpty(urlToListOfResources)){
            
            throw new Error('urlToListOfResources must be a non empty string');
        }
        
        if(!StringUtils.isString(baseUrl) || StringUtils.isEmpty(baseUrl)){
            
            throw new Error('baseUrl must be a non empty string');
        }
        
        this.execute(urlToListOfResources, (results, _anyError) => {
            
            if(results[0].isError){
                
                return errorCallback(urlToListOfResources, results[0].errorMsg, results[0].code);
            }
            
            let resourcesFullUrls: string[] = [];
            
            var resourcesList = StringUtils.getLines(results[0].response);
            
            for (var resource of resourcesList){
                
                resourcesFullUrls.push(StringUtils.formatPath(this._composeUrl(baseUrl, resource), '/'));  
            }
            
            this.execute(resourcesFullUrls, (results, _anyError) => {

                let resultsData: string[] = [];
                
                for (let result of results) {

                    if(result.isError){
                
                        return errorCallback(result.url, result.errorMsg, result.code);
                    }
                    
                    resultsData.push(result.response);
                }
                    
                successCallback(resourcesList, resultsData);
                
            }, progressCallback);
        });
    }
    
    
    /**
     * Auxiliary method to join two urls: A base one, and a relative one
     * 
     * If a full absolute url is passed to the relativeUrl variable, the result of this method will be the relative one, ignoring
     * any possible value on baseUrl.
     */
    private _composeUrl(baseUrl: string, relativeUrl: string){
        
        let composedUrl = '';
        
        if (StringUtils.isEmpty(baseUrl) ||
            relativeUrl.substr(0, 5) === 'http:' ||
            relativeUrl.substr(0, 6) === 'https:') {
            
            composedUrl = relativeUrl;
        
        } else {
            
            composedUrl = StringUtils.replace(StringUtils.formatPath(baseUrl + '/' + relativeUrl, '/'),
                ['http:/', 'https:/'],
                ['http://', 'https://'], 1);
        }
        
        if(this.isOnlyHttps && composedUrl.substr(0, 5).toLowerCase() === 'http:'){
            
            throw new Error('Non secure http requests are forbidden. Set isOnlyHttps=false to allow ' + composedUrl);
        }
        
        return composedUrl;
    }   
}
