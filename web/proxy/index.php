<?php 
include_once 'Tweetr.php';
$options['baseURL'] = "/proxy";
$options['debugMode'] = false;
$options['userAgent'] = "TweetrProxy/0.9";
$tweetr = new Tweetr($options);
?>