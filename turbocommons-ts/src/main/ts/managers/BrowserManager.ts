/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> https://turboframework.org/en/libs/turbocommons
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */
 
   
import { StringUtils } from '../utils/StringUtils';
import { NumericUtils } from '../utils/NumericUtils';
import { ArrayUtils } from '../utils/ArrayUtils';


/**
 * An abstraction of the browser entity an all its related operations and properties
 * Browser entity is normally available only on client side or front end view applications,
 * but some of its features can also make sense on a server side app. So depending on the
 * implementation language, this class may or may not have some of its methods implemented.
 */
export class BrowserManager{

    
    /**
     * Get the current page full url, including 'https://', domain and any uri get parameters
     *
     * @return A well formed url
     */
    getCurrentUrl(){
        
        return window.location.href;
    }
    
    
    /**
     * Tells if the current html document is fully loaded or not.
     *  
     * @returns True if the current html document is fully loaded (including all frames, objects and images) or false otherwise. 
     */
    isDocumentLoaded(){

        return (document.readyState === "complete");
    }
    
    
    /**
     * Check if the specified cookie exists
     * 
     * @param key the name for the cookie we want to find
     * 
     * @returns True if cookie with specified name exists, false otherwise
     */
    isCookie(key:string){

        return (this.getCookie(key) !== undefined);
    }


    /**
     * Set the value for a cookie or create it if not exist
     * 
     * Adapted from the jquery.cookie plugin by Klaus Hartl: https://github.com/carhartl/jquery-cookie
     * 
     * @param key the name for the cookie we want to create
     * @param value the value we want to set to the new cookie.
     * @param expires The lifetime of the cookie. Value can be a `Number` which will be interpreted as days from time of creation or a `Date` object. If omitted or '' string, the cookie becomes a session cookie.
     * @param path Define the path where the cookie is valid. By default it is the whole domain: '/'. A specific path can be passed (/ca/Home/) or a '' string to set it as the current site http path.
     * @param domain Define the domain where the cookie is valid. Default: domain of page where the cookie was created.
     * @param secure If true, the cookie transmission requires a secure protocol (https). Default: false.
     * 
     * @returns True if cookie was created, false otherwise. An exception may be thrown if invalid parameters are specified
     */
    setCookie(key: string, value: string, expires:any = '', path = "/", domain = '', secure = false){

        // TODO: Should be interesting to detect if we are going to exceed the total available space for 
        // cookies storage before storing the data, to prevent it from silently failing     

        // Empty key means an exception
        if(!StringUtils.isString(key) || StringUtils.isEmpty(key)){

            throw new Error("key must be defined");
        }

        // Empty values mean cookie will be created empty
        if(value === undefined || value === null){

            value = '';
        }

        // Reaching here, non string value means an exception
        if(!StringUtils.isString(value)){

            throw new Error("value must be a string");
        }

        // If the expires parameter is numeric, we will generate the correct date value
        if(NumericUtils.isNumeric(expires)){

            let days = expires;

            expires = new Date();
            expires.setDate(expires.getDate() + days);
        }

        // Generate the cookie value
        let res = encodeURIComponent(key) + '=' + encodeURIComponent(value);
        res += expires ? '; expires=' + expires.toUTCString() : '';
        res += path ? '; path=' + path : '';
        res += domain ? '; domain=' + domain : '';
        res += secure ? '; secure' : '';

        document.cookie = res;

        return true;
    }


    /**
     * Get the value for an existing cookie.
     * 
     * Adapted from the jquery.cookie plugin by Klaus Hartl: https://github.com/carhartl/jquery-cookie
     * 
     * @param key the name of the cookie we want to get
     * 
     * @returns Cookie value or null if cookie does not exist
     */
    getCookie(key:string){

        // Empty key means an exception
        if(!StringUtils.isString(key) || StringUtils.isEmpty(key)){

            throw new Error("key must be defined");
        }

        // Get an array with all the page cookies
        let cookies = document.cookie.split('; ');

        let pluses = /\+/g;

        for(let i = 0, l = cookies.length; i < l; i++){

            const parts:string[] = cookies[i].split('=');

            const part = parts.shift() || '';
        
            if(decodeURIComponent(part.replace(pluses, ' ')) === key){

                return decodeURIComponent(parts.join('=').replace(pluses, ' '));
            }
        }

        return undefined;
    }


    /**
     * Deletes the specified cookie from browser.
     * Note that the cookie will only be deleted if belongs to the same path as specified.
     * 
     * @param key The name of the cookie we want to delete
     * @param path Define the path where the cookie is set. By default it is the whole domain: '/'. If the cookie is not set on this path, we must pass the right one or the delete will fail.
     * 
     * @returns True if cookie was deleted or false if cookie could not be deleted or was not found.
     */
    deleteCookie(key:string, path:string = '/'){

        // Empty key means an exception
        if(!StringUtils.isString(key) || StringUtils.isEmpty(key)){

            throw new Error("key must be defined");
        }

        if(this.getCookie(key) !== undefined){

            this.setCookie(key, '', -1, path);

            return true;
        }

        return false;
    }
    
    
    /**
     * Check if the currently active URL at the browser contains a hash fragment.
     * The fragment is a part of the URL that comes after the # symbol, and can be modified without
     * needing to reload the page. It is important to know that the url fragment is always available
     * at the browser level and will never be sent to server.
     * 
     * An example of a hash:
     * https://someurl.com/home#somehash
     * 
     * @returns True if the active URL has a hash fragment, false otherwise.
     */
    isCurrentUrlWithHashFragment(){

        if(window.location.hash) {
        
            return true;
        }
        
        return false;
    }
    
    
    /**
     * Obtain the value that is found at the current URL hash fragment part. For example:
     * https://someurl.com/home#somehash
     * 
     * Will return 'somehash'
     * 
     * @returns The value of the URL hash fragment but without the # character
     */
    getCurrentUrlHashFragment(){

        if(window.location.hash) {
      
            // Removes the first unwanted # character
            return window.location.hash.substring(1);
        }
    
        return '';  
    }
    
    
    /**
     * TODO
     */
    setCurrentUrlHashFragment(){

        // TODO
    }
    
    
    /**
     * TODO
     */
    deleteCurrentUrlHashFragment(){

        // TODO
    }
    
    
    /**
     * TODO
     */
    isCurrentUrlWithQuery(){

        // TODO
    }
    
    
    /**
     * TODO
     */
    getCurrentUrlQueryValues(){

        // TODO
    }
    

    /**
     * Reloads the current url. This will make the browser load all the current html document again
     * and all page state will be lost.
     *  
     * @returns void
     */
    reload(){

        location.reload();
    }
    
    
    /**
     * Tries to detect the language that is set as preferred by the user on the current browser.
     * NOTE: Getting browser language is not accurate. It is always better to use server side language detection
     * 
     * @returns A two digits string containing the detected browser language. For example 'es', 'en', ...
     */
    getPreferredLanguage(){

        let language:any = '';

        // Try to get the language on modern browsers support for HTML 5.1 "navigator.languages"
        if (ArrayUtils.isArray(window.navigator['languages'])) {
            
          for (const element of window.navigator['languages']) {
          
              if(element.length >= 2){
                  
                  language = element;
                  break;
              }
          }
        
        }else{
            
            // support for older browsers
            language = (window.navigator as any)['userLanguage'] || window.navigator.language;    

            language = language.split(',')[0];
        }
        
        return language.trim().substr(0, 2).toLowerCase();        
    }
    
    
    /**
     * Opens the specified url on the browser's current tab or in a new one.
     * 
     * @param url The url that will be loaded
     * @param newWindow Setting it to true will open the url on a new browser tab. False by default
     * @param postData If we want to send POST data to the url, we can set this parameter to an object where 
     *        each property will be translated to a POST variable name, and each property value to the POST variable value
     * 
     * @returns void
     */
    goToUrl(url:string, newWindow = false, postData:Object|null = null){

        if(postData == null){

            // Check if same or new window is required
            if(newWindow){

                window.open(url, '_blank');

            }else{

                window.location.href = url;
            }

        }else{
            
            // We create a dynamic form that will be used to load the url and also send the required POST data
            let form = document.createElement('form');
            
            form.action = url;
            form.method = "POST";
            form.style.display = "none";
            
            if(newWindow){
            
                form.target = "_blank";
            }
            
            let props = Object.getOwnPropertyNames(postData);
            
            for(const element of props){
                
                let input = document.createElement("input");
                input.type = "hidden";
                input.name = element;
                input.value = (postData as any)[element];
                form.appendChild(input);
            }
        
            document.body.appendChild(form);
            
            form.submit();
            
            if(newWindow){
            
                document.body.removeChild(form);
            }
        }
    }
    
    
    /**
     * Disable the hability for the user to navigate back on browser history. This method does not disable the
     * browser back button, but it prevents it from leaving the current page.
     * 
     * @returns void
     */
    disableBackButton(){

        history.pushState(null, '', document.URL);        
        window.addEventListener('popstate', this._onPopStatePreventBackButton);
    }
    
    
    /**
     * Event listener that will prevent the back button when disableBackButton is enabled
     */
    private _onPopStatePreventBackButton(){
        
        history.pushState(null, '', document.URL);
    }
    
    
    /**
     * Restore the back button normal behaviour which was blocked by calling disableBackButton() 
     * 
     * @returns void
     */
    enableBackButton(){

        window.removeEventListener('popstate', this._onPopStatePreventBackButton);
    }
        
    
    /**
     * Totally disables the current page scrolling. Useful when creating popups or elements that have an internal scroll, 
     * and we don't want it to interfere with the main document scroll.<br><br>
     * Can be enabled again with enableScroll.<br><br>
     * 
     * @returns void
     */
    disableScroll(){

        // TODO - find a good crossbrowser non jquery solution
    }
    
    
    /**
     * Restores main document scrolling if has been disabled with HtmlUtils.disableScroll<br><br>
     * 
     * @returns void
     */
    enableScroll(){

        // TODO - find a good crossbrowser non jquery solution
    }
    
    
    /**
     * Gives the current position for the browser scroll
     * 
     * @returns Array with the current x,y position based on the top left corner of the current document
     */
    getScrollPosition(){

        return [window.pageXOffset, window.pageYOffset];
    }
    
    
    /**
     * Obtain the current viewport browser window width value
     * 
     * @see https://stackoverflow.com/questions/1248081/get-the-browser-viewport-dimensions-with-javascript
     * 
     * @returns A numeric value representing the window width in pixels
     */
    getWindowWidth(){
        
        return window.innerWidth ||
            (document.documentElement as HTMLElement).clientWidth ||
            document.getElementsByTagName('body')[0].clientWidth || -1;
    }
    
    
    /**
     * Obtain the current viewport browser window height value
     * 
     * @see https://stackoverflow.com/questions/1248081/get-the-browser-viewport-dimensions-with-javascript
     * 
     * @returns A numeric value representing the window height in pixels
     */
    getWindowHeight(){

        return window.innerHeight ||
            (document.documentElement as HTMLElement).clientHeight ||
            document.getElementsByTagName('body')[0].clientHeight || -1;
    }
    
    
    /**
     * Obtain the current html document width in pixels
     * 
     * @returns Numeric value representing the document width in pixels
     */
    getDocumentWidth(){

        return Math.max(document.body.scrollWidth,
                document.body.offsetWidth,
                (document.documentElement as HTMLElement).clientWidth,
                (document.documentElement as HTMLElement).scrollWidth,
                (document.documentElement as HTMLElement).offsetWidth);
    }
    
    
    /**
     * Obtain the current html document height in pixels
     * 
     * @returns Numeric value representing the document height in pixels
     */
    getDocumentHeight(){

        return Math.max(document.body.scrollHeight,
                document.body.offsetHeight,
                (document.documentElement as HTMLElement).clientHeight,
                (document.documentElement as HTMLElement).scrollHeight,
                (document.documentElement as HTMLElement).offsetHeight);
    }
    

    /**
     * Moves the browser scroll to the specified X,Y axis position or DOM element.
     * 
     * @example browserManager.scrollTo(document.querySelector('#myId'), 800);
     * @example browserManager.scrollTo([100,200], 1000);
     * 
     * @see https://pawelgrzybek.com/page-scroll-in-vanilla-javascript/
     * 
     * @param destination The location where the scroll must be moved to. It can be an HTML element instance or an array of two numbers with the [x,y] destination coordinates
     * @param duration The animation duration in miliseconds. Set it to 0 to perform a direct scroll change.
     * @param callback A method that will be executed right after the scroll finishes
     * 
     * @returns void
      */
    scrollTo(destination: HTMLElement|[number, number], duration = 600, callback: Function|null = null){

        // Define an easeOutCubic function for the scroll movement
        const easingFunction = (t:number) => {return (--t)*t*t+1};
        
        // Get the current scrollbar positions and system miliseconds
        const startX = window.pageXOffset;
        const startY = window.pageYOffset;
        const startTime = ('now' in window.performance) ? performance.now() : (new Date()).getTime();

        // Obtain the viewport and document dimensions
        const documentWidth = this.getDocumentWidth();
        const documentHeight = this.getDocumentHeight();
        const windowWidth = this.getWindowWidth();
        const windowHeight = this.getWindowHeight();
        
        // Find the requested destination coordinates depending on the type of the parameter
        const destinationValueX = ArrayUtils.isArray(destination) ? (<[number, number]>destination)[0] : (<HTMLScriptElement>destination).offsetLeft;
        const destinationValueY = ArrayUtils.isArray(destination) ? (<[number, number]>destination)[1] : (<HTMLScriptElement>destination).offsetTop;
        
        // Calculate the real value where scrollbars must move
        let destinationX = startX;
        let destinationY = startY;
            
        if(documentWidth > windowWidth){
            
            destinationX = Math.round(documentWidth - destinationValueX < windowWidth ? documentWidth - windowWidth : destinationValueX);
        }
        
        if(documentHeight > windowHeight){
            
            destinationY = Math.round(documentHeight - destinationValueY < windowHeight ? documentHeight - windowHeight : destinationValueY);
        }

        // If requestAnimationFrame is not available, we will simply perform the scroll without any animation
        if ('requestAnimationFrame' in window === false) {
            
            window.scroll(destinationX, destinationY);
          
            if (callback) {
            
                callback();
            }
            
            return;
        }

        // Define a method that will perform the scroll animation
        function animate() {

            const now = ('now' in window.performance) ? performance.now() : (new Date()).getTime();
            const time = Math.min(1, ((now - startTime) / duration));

            const x = Math.ceil(easingFunction(time) * (destinationX - startX) + startX);
            const y = Math.ceil(easingFunction(time) * (destinationY - startY) + startY);
            
            window.scroll(x, y);

            if (Math.ceil(window.pageXOffset) === destinationX && Math.ceil(window.pageYOffset) === destinationY) {
            
                if (callback !== null) {
              
                    callback();
                }
              
                return;
            }

            requestAnimationFrame(animate);
        }

        animate();
    }
    

    /**
     * Copies the specified text to the clipboard
     * 
     * @param text Some string that will be placed on the system clipboard
     * 
     * @returns Promise A promise to be resolved once the copy is performed
     */
    copyToClipboard(text: string){

        return navigator.clipboard.writeText(text);
    }
    
    
    /**
     * Search for a file or files on the local user machine. Their contents will be loaded into the browser memory and can be used
     * locally without needing to update them to a remote server.
     * 
     * @param event It is mandayory for security reasons that an event from an actual input type='file' element is passed to this method.
     *        We can set here for example the change event that is fired by the input when the user selects a file.<br><br>
     *        Example for single file: <input type='file' accept=".txt" (change)="onFileSelected($event)"> (call browseLocalFiles() inside the change event handler)<br><br>
     *        Example for multi files: <input type='file' multiple="multiple" accept=".txt" (change)="onFileSelected($event)">
     *        of the onFileSelected method.
     * @param mode Specify if the files must be loaded as plain "TEXT" or "BASE64" encoded binary data       
     * @param callback Once the files selected by the user are correctly loaded into the browser, this callback method will be 
     *        called with two parameters containing the name and contents for each one of the loaded files.
     * 
     * @returns Void. (An exception will be thrown if the load fails)
     */
    browseLocalFiles(event: any, mode: 'TEXT' | 'BASE64', callback: (fileNames: string[], fileContents: string[]) => void){
        
        function recursiveLoader(filesLoaded:any, fileNames:string[], fileContents:string[], index:number) {

            if(index >= filesLoaded.length){
                
                callback(fileNames, fileContents);
                return;
            }

            if(filesLoaded[index]){
                    
                fileNames.push(filesLoaded[index].name);
                
                const reader = new FileReader();
                
                reader.onload = () => {
                    
                    if(mode === "TEXT"){
                        
                        fileContents.push(reader.result as string);
                        
                    }else{
                        
                        fileContents.push((reader.result as string).split(',', 2)[1]);
                    }
                    
                    recursiveLoader(filesLoaded, fileNames, fileContents, index + 1);
                };
                
                reader.onerror = () => {
                    
                    throw new Error('Error reading file');
                };
                
                // Read the file contents depending on the selected mode    
                if(mode === "TEXT"){
                    
                    reader.readAsText(filesLoaded[index]);
                            
                }else if(mode === "BASE64"){
                    
                    reader.readAsDataURL(filesLoaded[index]);
                
                }else{
                    
                    throw new Error('Mode must be either "TEXT" or "BINARY"');
                }
                            
            }else{
            
                recursiveLoader(filesLoaded, fileNames, fileContents, index + 1);   
            }
        }
        
        recursiveLoader(event.target.files, [], [], 0);
    }
}