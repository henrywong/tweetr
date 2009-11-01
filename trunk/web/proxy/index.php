<?php 
include_once 'Tweetr.php';

/**
 * Tweetr Proxy Class Startpoint
 * @author Sandro Ducceschi [swfjunkie.com, Switzerland]
 * 
 * see http://code.google.com/p/tweetr/wiki/PHPProxyUsage
 * on how to use the tweetr php proxy and it's options.
 */

$tweetrOptions['baseURL'] = "/proxy";
//$tweetrOptions['userAgent'] = "TweetrProxy/0.94";
//$tweetrOptions['userAgentLink'] = "http://tweetr.googlecode.com/";
//$tweetrOptions['debugMode'] = true;
//$tweetrOptions['ghostName'] = "your_ghost";
//$tweetrOptions['ghostPass'] = "your_ghost";
//$tweetrOptions['userName'] = "your_username";
//$tweetrOptions['userPass'] = "your_password";
//$tweetrOptions['cache_enabled'] = true;
//$tweetrOptions['cache_time'] = 120; // 2 minutes
//$tweetrOptions['cache_directory'] = "./cache/";

if (phpversion() < 5)
	die("PHP ".phpversion()." not supported. TweetrProxy requires atleast PHP5 or higher.");

$tweetr = new Tweetr($tweetrOptions);
?>