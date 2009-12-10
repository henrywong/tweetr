<?php
/**
 * Tweetr Proxy Class
 * @author Sandro Ducceschi [swfjunkie.com, Switzerland]
 */
class Tweetr
{   
    //--------------------------------------------------------------------------
    //
    //  Class variables
    //
    //--------------------------------------------------------------------------
    const USER_AGENT = 'TweetrProxy/0.95';
    const USER_AGENT_LINK = 'http://tweetr.googlecode.com/';
    const BASEURL = '/proxy';
    const DEBUGMODE = false;
    const GHOST_DEFAULT = 'ghost';
    const CACHE_ENABLED = false;
	const CACHE_TIME = 120;	// 2 minutes
	const CACHE_DIRECTORY = 'cache';
	const UPLOAD_DIRECTORY = 'tmp';
    //--------------------------------------------------------------------------
    //
    //  Initialization
    //
    //--------------------------------------------------------------------------
    
    /**
     * Creates a Tweetr Proxy Instance
     * @param (array) $options  Associative Array containing optional values see http://code.google.com/p/tweetr/wiki/PHPProxyUsage
     */
    public function Tweetr($options = null)
    {
        // set the options
        $this->baseURL = (isset($options['baseURL'])) ? $options['baseURL'] : self::BASEURL;
        $this->userAgent = (isset($options['userAgent'])) ? $options['userAgent'] : self::USER_AGENT;
        $this->userAgentLink = (isset($options['userAgentLink'])) ? $options['userAgentLink'] : self::USER_AGENT_LINK;
        $this->indexContent = (isset($options['indexContent'])) ? $options['indexContent'] : '<html><head><title>'.$this->userAgent.'</title></head><body><a href="'.$this->userAgentLink.'" title="Go to Website">'.$this->userAgent.'</a></body></html>';
        $this->debug = (isset($options['debugMode'])) ? $options['debugMode'] : self::DEBUGMODE;
        
        $this->ghostName = (isset($options['ghostName'])) ? $options['ghostName'] : self::GHOST_DEFAULT;
        $this->ghostPass = (isset($options['ghostPass'])) ? $options['ghostPass'] : self::GHOST_DEFAULT;
        $this->userName = (isset($options['userName'])) ? $options['userName'] : null;
        $this->userPass = (isset($options['userPass'])) ? $options['userPass'] : null;
        
        $this->cacheEnabled = (isset($options['cache_enabled'])) ? $options['cache_enabled'] : self::CACHE_ENABLED;
        $this->cacheTime = (isset($options['cache_time'])) ? $options['cache_time'] : self::CACHE_TIME;
        $this->cacheDirectory = "./".((isset($options['cache_directory'])) ? $options['cache_directory'] : self::CACHE_DIRECTORY)."/";
        $this->uploadDirectory = "./".((isset($options['upload_directory'])) ? $options['upload_directory'] : self::UPLOAD_DIRECTORY)."/";

        
        // set the current url and parse it
        $this->url = parse_url($_SERVER['REQUEST_URI']);
        $this->parseRequest();
    }

    //--------------------------------------------------------------------------
    //
    //  Variables
    //
    //--------------------------------------------------------------------------
    private $url;
    private $debug;
    private $baseURL;
    private $userAgent;
    private $userAgentLink;
    private $indexContent;
    private $userName;
    private $userPass;
    private $ghostName;
    private $ghostPass;
    private $cacheEnabled;
    private $cacheTime;
    private $cacheDirectory;
    private $uploadDirectory;
    //--------------------------------------------------------------------------
    //
    //  Methods
    //
    //--------------------------------------------------------------------------
    /**
     * Pre-Parses the received request to see if we need authentication or not
     */
    private function parseRequest()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $this->checkCredentials();
        }
        else
        {
            if($this->url['path'] == $this->baseURL.'/')
            	die(urldecode($this->indexContent));
            else
            	$this->checkCredentials();
        }
    }
    
    
    /**
     * Makes a request to twitter with the data provided and returns the result to the screen
     */
    private function twitterRequest($authentication = false)
    {   
    	/* caching - begin */
		if($_SERVER['REQUEST_METHOD'] != 'POST' && $this->cacheEnabled && $this->cacheExists())
		{
			header('Content-type: text/xml; charset=utf-8');
			echo $this->cacheRead();
			return;
		}
		/* caching - end */
    	
        $twitterURL = 'http://api.twitter.com/1'.str_replace($this->baseURL,'',$this->url['path']);
        
        if($_SERVER['REQUEST_METHOD'] == 'GET')
        	$twitterURL .= '?'.$this->url['query'];
       	
        $opt[CURLOPT_URL] = $twitterURL;
        $opt[CURLOPT_USERAGENT] = $this->userAgent;
        $opt[CURLOPT_RETURNTRANSFER] = true;
        $opt[CURLOPT_TIMEOUT] = 60;

        if($authentication)
        {   
            $opt[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
            
            $creds = (isset($_GET['hash'])) ? $_GET['hash'] : $_POST['hash'];
            $credsArr = explode(':', base64_decode($creds));
            $credUser = $credsArr[0];
            $credPass = $credsArr[1];
            
            if( isset($this->ghostName) && isset($this->ghostPass) && 
                isset($this->userName) && isset($this->userPass) &&
                $this->ghostName == $credUser && $this->ghostPass == $credPass)
            {
                $opt[CURLOPT_USERPWD] = $this->userName .':'. $this->userPass;
            }
            else
            {
                $opt[CURLOPT_USERPWD] = $credUser .':'. $credPass;
            }
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
        	$postFields = array();
            if (isset($_FILES['image']))
            {
            	$imagePath = $this->uploadDirectory.$_FILES['image']['name'];
            	if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath))
            		$postFields['image'] = '@'.realpath($imagePath);
            	else
            		die('Upload failed, check upload directory permissions/path...');
            }
            
            foreach($_POST as $key => $val)
            	$postFields[$key] = stripslashes($val);
            	
            $opt[CURLOPT_POST] = true;
            $opt[CURLOPT_HTTPHEADER] = array('Expect:');
            $opt[CURLOPT_POSTFIELDS] = $postFields;
        }
        
        //do the request
        $curl = curl_init();
        curl_setopt_array($curl, $opt);

        
        ob_start();
        $response = curl_exec($curl);
        
        if(strlen(''.$response) < 2 )
            $response = ob_get_contents();
        
        ob_end_clean();

        if (isset($imagePath))
			unlink($imagePath);

        if($this->debug)
        {
            $headers = curl_getinfo($curl);
            $errorNumber = curl_errno($curl);
            $errorMessage = curl_error($curl);  
        }
        curl_close($curl);
        
        if($this->debug)
        {
            $this->log($headers);
            $this->log($errorNumber);
            $this->log($errorMessage);
        }

        header('Content-type: text/xml; charset=utf-8');
        
        /* caching - begin */
		if ($this->cacheEnabled)
			$this->cacheInit();
		
		echo $response;
		
		if ($this->cacheEnabled)
			$this->cacheEnd();
    }
    
    
    /**
     * Requests Basic Authentication
     */
    private function checkCredentials()
    {
    	if (!isset($_GET['hash']) && !isset($_POST['hash']))
    		$this->twitterRequest();
        else
            $this->twitterRequest(true);
    }
    
    /**
     * Log Method that you can overwrite with your own logging stuff.
     * FirePHP is my personal recommendation though ;)
     * @param $obj  Whatever you are trying to log
     */
    private function log($obj)
    {
        //require_once('fire/fb.php');
        //FB::log($obj);
    }
    
	//-------------------------------
    //  Caching
    //-------------------------------
	
    /**
     * Start Caching Process
     */
    private function cacheInit()
	{
		if($this->cacheExists())
		{
			echo $this->cacheRead();
			exit();
		} 
		else
		{
			ob_start();
		}
	}
	
	/**
	 * End Caching Process
	 */
	private function cacheEnd()
	{
		$data = ob_get_clean();
		$this->cacheSave($data);
		echo $data;
	}
	
	/**
	 * Create cache key
	 */
	private function cacheKey()
	{
		foreach($_POST as $key => $value) 	$keys[] = $key . '=' . urlencode($value) ;
		foreach($_GET as $key => $value) 		$keys[] = $key . '=' . urlencode($value) ;
		
		$keys[] = 'app=tweetr';
		$keys[] = 'requrl='.urlencode($this->url['path']);
		sort($keys);
		return md5(implode('&', $keys));
	}
	
	/**
	 * Returns filename
	 */
	private function cacheFilename()
	{
		return $this->cacheDirectory. $this->cacheKey() . '.cache';
	}
	
	/**
	 * Checks if a specific cached file exists
	 */
	private function cacheExists()
	{
		if(@file_exists($this->cacheFilename()) && (time() - $this->cacheTime) < @filemtime($this->cacheFilename()))
	  		return true;
		else
	  		return false;
	}
	
	/**
	 * Reads the Cache
	 */
	private function cacheRead()
	{
		return file_get_contents($this->cacheFilename());
	}
	
	/**
	 * Destroy uneccessary cache files
	 */
	private function cachePurge()
	{
		$dir_handle = @opendir($this->cacheDirectory) or die('Unable to open '.$this->cacheDirectory);
		while ($file = readdir($dir_handle))
		{
			if ($file!='.' && $file!='..' && (time() - $this->cacheTime) > @filemtime($this->cacheDirectory.$file))
				unlink($this->cacheDirectory.$file) or die('Error. Could not erase file : '.$file);
		}
	}
	
	/**
	 * Save a cache file
	 */
	private function cacheSave($value)
	{
		$this->cachePurge();
		$fp = @fopen($this->cacheFilename(), 'w') or die('Error opening file: '.$this->cacheFilename());
		@fwrite($fp, $value) or die('Error writing file.');
		@fclose($fp);
	}
}
?>