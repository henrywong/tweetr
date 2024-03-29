package com.swfjunkie.tweetr.utils
{
    import flash.events.Event;
    import flash.events.EventDispatcher;
    import flash.events.IOErrorEvent;
    import flash.net.URLLoader;
    import flash.net.URLRequest;
    
    /**
     * Dispatched when the instance has successfully unshortened an url.
     * @eventType flash.events.COMPLETE
     */
    [Event(name="complete", type="flash.events.Event")]
    /**
     * Dispatched when the instance has encountered an error while trying to unshorten your url.
     * @eventType flash.events.IOErrorEvent.IO_ERROR
     */
    [Event(name="ioError", type="flash.events.IOErrorEvent")]
    
    /**
     * URL Unshortener Class using http://untiny.me/ public service
     * For a list of supported services see <a href="http://untiny.me/api/1.0/services/">http://untiny.me/api/1.0/services/</a>
     * @author Sandro Ducceschi [swfjunkie.com, Switzerland]
     */
    
    public class URLUnshortener extends EventDispatcher
    {
        //--------------------------------------------------------------------------
        //
        //  Class variables
        //
        //--------------------------------------------------------------------------
        private static const UNTINY_ME_URL:String = "http://untiny.me/api/1.0/extract?format=text&url=";
        //--------------------------------------------------------------------------
        //
        //  Initialization
        //
        //--------------------------------------------------------------------------
        /** 
         * Creates a new URLShortener Instance 
         */
        public function URLUnshortener()
        {
            init();
        }
        /**
         * @private
         * Initializes the instance.
         */
        private function init():void
        {
            urlLoader = new URLLoader();
            urlRequest = new URLRequest();   
            urlLoader.addEventListener(Event.COMPLETE,handleComplete);
            urlLoader.addEventListener(IOErrorEvent.IO_ERROR,handleError);
        }
        //--------------------------------------------------------------------------
        //
        //  Variables
        //
        //--------------------------------------------------------------------------
        private var urlLoader:URLLoader;
        private var urlRequest:URLRequest;
        //--------------------------------------------------------------------------
        //
        //  Properties
        //
        //--------------------------------------------------------------------------
        /** @private */
        private var _url:String;
        /**
         * The Unshortened URL
         */ 
        public function get url():String
        {
            return _url;
        }
        //--------------------------------------------------------------------------
        //
        //  Additional getters and setters
        //
        //--------------------------------------------------------------------------
        
        //--------------------------------------------------------------------------
        //
        // Overridden API
        //
        //--------------------------------------------------------------------------
        
        //--------------------------------------------------------------------------
        //
        //  API
        //
        //--------------------------------------------------------------------------
        
        /**
         * Unshortens the given URL
         * @param url   Short URL that should be unshortened
         */  
        public function unshorten(url:String):void
        {
            _url = null;
            urlRequest.url = UNTINY_ME_URL + url;
            urlLoader.load(urlRequest);
        }
        
        
        /**
         * Completely destroys the instance and frees all objects for the garbage
         * collector by setting their references to null.
         */
        public function destroy():void
        {
            _url = null;
            urlLoader = null;
            urlRequest = null;   
            urlLoader.removeEventListener(Event.COMPLETE,handleComplete);
            urlLoader.removeEventListener(IOErrorEvent.IO_ERROR,handleError);
        }
        //--------------------------------------------------------------------------
        //
        //  Overridden methods: _SuperClassName_
        //
        //--------------------------------------------------------------------------
        
        //--------------------------------------------------------------------------
        //
        //  Methods
        //
        //--------------------------------------------------------------------------
        
        //--------------------------------------------------------------------------
        //
        //  Broadcasting
        //
        //--------------------------------------------------------------------------
        //--------------------------------------------------------------------------
        //
        //  Eventhandling
        //
        //--------------------------------------------------------------------------
        /** @private */ 
        private function handleComplete(event:Event):void
        {
            if (String(urlLoader.data).indexOf("error") != -1)
                dispatchEvent(new IOErrorEvent(IOErrorEvent.IO_ERROR, false, false, urlLoader.data.toString()));
            else
            {
                _url = urlLoader.data;
                dispatchEvent(new Event(Event.COMPLETE));
            }
        }
        
        /** @private */ 
        private function handleError(event:IOErrorEvent):void
        {
            dispatchEvent(new IOErrorEvent(IOErrorEvent.IO_ERROR,false,false,"Unshortening failed"));
        }
    }
}