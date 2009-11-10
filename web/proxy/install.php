<?php 
session_start();
include_once 'Tweetr.php';
error_reporting(E_ERROR);
$steps = 8;

/** ----------------------------
 * HTML Snippets
 *-----------------------------*/
$header = "<html>
<head>
    <title>TweetrProxy Installation Script</title>
    <style type='text/css'>
    	body { background-color: #B2B28F; margin: 0; padding: 0; color: #444; font-size: 10px; margin: 5px; font-family: \"Lucida Grande\", Verdana, Helvetica, Arial, sans-serif; font-size: 11px; line-height: 20px;}
    	#contentBox {position: absolute; z-index: 2; top: 50%; left: 50%; margin: -250px 0px 0px -250px; width: 480px; height: 480px; border: solid 1px #000; padding: 10px; background-color: #F2FFCC;}
        #contentShadow {position: absolute; z-index: 1; top: 50%; left: 50%; margin: -245px 0px 0px -245px; width: 500px; height: 500px; background-color: #464630;}
        #titleBox {position: absolute; z-index: 0; top: 50%; left: 50%; margin: -283px 0px 0px -250px; width: 500px; height: 50px;}
        .failed { color: #AA0000; font-weight: bold; }
        .passed { color: #00AA00; font-weight: bold; }
        .small { font-size: 85%; text-transform: uppercase; letter-spacing: 1px; color: #20AA81; font-size: 10px; font-family: \"Lucida Grande\", Verdana, Helvetica, Arial, sans-serif; font-weight: 100; }
        .headline { font-family: Helvetica, Arial, sans-serif; font-size: 25px; font-style: normal; font-weight: bold; text-transform: normal; letter-spacing: -1px; line-height: 1.2em; text-decoration: none; color: #A6BF00; }
        a { font-family: Helvetica, Arial, sans-serif; font-size: 24px; font-style: normal; font-weight: bold; text-transform: normal; letter-spacing: -1px; line-height: 1.2em; text-decoration: none; color: #8B8B5B; }
        a:hover { color: #000; }
        input[type=\"text\"] {background-color:#FFF; border:solid 1px #DDD; font-family:Arial; font-size:11px; height:20px;}
    </style>
</head>
<body>
    <div id=\"contentShadow\">&nbsp;</div>
    <div id=\"contentBox\">";
$title = "<div class=\"small\" style=\"font-style: italic; text-align: right; float: right;\">(Step $1 of $2)</div>
        <div class=\"headline\" style=\"margin-bottom: 20px;\">$3</div><div style=\"width: 450px; padding: 15px;\">";
$content = "";
$footer = "</div></div><div id=\"titleBox\" class=\"headline\" style=\"font-style: italic; font-size: 37px; color: #959567; text-align: right;\">TweetrProxy Installation</div></body></html>";

/** ---------------------------------
 * Procedural Code (ugly i know ;) )
 *----------------------------------*/

if (!isset($_GET['step']))
{
	$titleArr = array(1,$steps,"And so it begins..");
	$phpCheck = false; $permCheck = false; $fileCheck = false;
	
	$content .= "<div><p>Hi and welcome to the Proxy Setup. Please drop me a line on the <a style=\"font-size: 12px;\" href=\"http://groups.google.com/group/tweetr-as3-library\" target=\"_blank\">Tweetr Library Discussion Group on GoogleGroups</a> if anything goes wrong or breaks during this process.</p></div>";
	$content .= "<h2>Requirements Check</h2>";
	$content .= "<div style=\"float: left; width: 50%; line-height: 30px; margin-bottom: 30px;\">&bull; PHP 5 or newer<br/>&bull; Read/Write access to install directory<br/>&bull; Read/Write access to index.php<br/>";
	$content .= "</div><div style=\"float: left; width: 50%; text-align: center; line-height: 30px; margin-bottom: 30px;\">";
	
	if (phpversion() < 5)
	{
		$content .= "<span class=\"failed\">FAILED</span> (".phpversion().")<br/>";
	}
	else
	{
		$phpCheck = true;
		$content .= "<span class=\"passed\">PASSED</span><br/>";
	}
	
	$fh = @fopen('tmpFile', 'wb');
	if (!$fh)
	{
		$content .= "<span class=\"failed\">FAILED</span><br/>";
	}
	else
	{
		fwrite($fh, 'qwerty');
		fclose($fh);
		if (unlink('tmpFile'))
		{
			$permCheck = true;
			$content .= "<span class=\"passed\">PASSED</span><br/>";
		}
		else
		{
			$content .= "<span class=\"failed\">FAILED</span><br/>";
		}
	}
	
	$fh = @fopen('index.php', 'a+');
	if (!$fh)
	{
		$content .= "<span class=\"failed\">FAILED</span><br/>";
		fclose($fh);
	}
	else
	{
		$fileCheck = true;
		$content .= "<span class=\"passed\">PASSED</span>";
	}
	
	$content .= "</div>";
	
	if (!$phpCheck)
		$content .= "<p><strong>Please upgrade your PHP Installation to PHP5 or newer. <a style=\"font-size: 12px;\" href=\"http://www.php.net/downloads.php\" target=\"_blank\">Offical PHP Website</a></strong></p>";
	if (!$permCheck)
		$content .= "<p><strong>Please ensure that the proxy folder has sufficient rights to be read/written to.</strong></p>";
	if (!$fileCheck)
		$content .= "<p><strong>Please ensure that the index.php can be read and written to.</strong></p>";
	
	$content .= "<div style=\"font-style: italic; color: #888;\">Please also verify that your Server is capable of URL Rewriting <i>(mod_rewrite)</i>, since it is a essential part of the Proxy and without it, it just won't work.</div>";
	if ($phpCheck && $permCheck && $fileCheck)
		$content .= "<div style=\"position: absolute; bottom: 5px; right: 10px;\"><a href=\"?step=2\">next »</a></div>";
}
else
{
	//---------------------------------
	// STEP 2	
	//---------------------------------
	
	if ($_GET['step'] == 2)
	{
		$titleArr = array(2,$steps,"Folders");
		$defaultCache = (isset($_POST['cacheFolder'])) ? $_POST['cacheFolder'] : Tweetr::CACHE_DIRECTORY;
		$defaultTemp = (isset($_POST['tempFolder'])) ? $_POST['tempFolder'] : Tweetr::UPLOAD_DIRECTORY;
		$content .= "<div><p>The Proxy needs two folders. A cache folder and a temp folder (used by the twitter api profile methods).</p><p>If you would like to name them yourself, please rename them now.</p></div>";
		$content .= "<form id=\"pageForm\" method=\"POST\" action=\"?step=2\">\n";
		$content .= "<p><strong>Cache Folder:</strong> &nbsp;&nbsp;&nbsp;".pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME)."/<input style=\"width: 100px;\" name=\"cacheFolder\" type=\"text\" value=\"".$defaultCache."\"></p>";
		$content .= "<p><strong>Temp Folder:</strong> &nbsp;&nbsp;&nbsp;&nbsp;".pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME)."/<input style=\"width: 100px;\" name=\"tempFolder\" type=\"text\" value=\"".$defaultTemp."\"></p>";
		$content .= "</form>\n";
		$content .= "";

		if ($_SERVER['REQUEST_METHOD'] == "POST")
		{
			$titleArr = array(2,$steps,"Folder Creation");
			$content .= "<br/>Creating \"<i>".pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME)."/".$_POST['cacheFolder']."</i>\" ... ";
			
			if (file_exists($_POST['cacheFolder']) && chmod($_POST['cacheFolder'], 0777))
				$content .= "<strong>DONE!</strong>";
			else if (mkdir($_POST['cacheFolder']))
				$content .= "<strong>CREATED!</strong>";
				
			$content .= "<br/>Creating \"<i>".pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME)."/".$_POST['tempFolder']."</i>\" ... ";
			if (file_exists($_POST['tempFolder']) && chmod($_POST['tempFolder'], 0777))
				$content .= "<strong>DONE!</strong>";
			else if (mkdir($_POST['tempFolder']))
				$content .= "<strong>CREATED!</strong>";
				
			$_SESSION['cacheFolder'] = $_POST['cacheFolder'];
			$_SESSION['tempFolder'] = $_POST['tempFolder'];
		}
		
		if ($_SERVER['REQUEST_METHOD'] == "POST")
			$content .= "<div style=\"position: absolute; bottom: 5px; right: 10px;\"><a href=\"?step=3\">next »</a></div>";
		else
			$content .= "<div style=\"position: absolute; bottom: 5px; right: 10px;\"><a href=\"javascript:pageForm.submit();\">next »</a></div>";
		$content .= "<div style=\"position: absolute; bottom: 5px; left: 10px;\"><a href=\"javascript:history.back();\">« back</a></div>";
	}
	
	//---------------------------------
	// STEP 3	
	//---------------------------------
	
	else if ($_GET['step'] == 3)
	{
		$titleArr = array(3,$steps,".htaccess / crossdomain.xml");
		$htaccessPath = $_SERVER['HTTP_HOST'].pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME)."/.htaccess";
		$crossdomainPath = $_SERVER['HTTP_HOST']."/crossdomain.xml";
		
		$content .= "<div><p>The installer will now attempt to write the following files in their respective locations:<pre>
".$htaccessPath."
".$crossdomainPath."</pre><br/> Writing ".$htaccessPath." ... ";
		
		$fh = @fopen('.htaccess', 'wb');
		if (!$fh)
		{
			$content .= "<strong>FAILED! Check write permissions...</strong>";
		}
		else
		{
			$rewriteText = '<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . index.php [L]
</IfModule>';
			fwrite($fh, $rewriteText);
			fclose($fh);
			$content .= "<strong>DONE! </strong>";
		}
		
		$content .= "<br/>Writing ".$crossdomainPath." ... ";
	
		if (!file_exists($_SERVER['DOCUMENT_ROOT']."/crossdomain.xml"))
		{
			$fh = @fopen($_SERVER['DOCUMENT_ROOT']."/crossdomain.xml", 'wb');
			if (!$fh)
			{
				$content .= "<strong>FAILED! Check write permissions...</strong>";
			}
			else
			{
				$crossText = '<?xml version="1.0"?>
<!-- in case the proxy is not on the same (sub)domain
     use this crossdomain file as a template. 
     Has to be put to the root folder of your domain.
-->
<cross-domain-policy>
  <allow-access-from domain="*" />
  <allow-http-request-headers-from domain="*" headers="*"/>
</cross-domain-policy>';
				fwrite($fh, $crossText);
				fclose($fh);
				$content .= "<strong>DONE! </strong>";
			}
		}
		else
		{
			$content .= "<strong>EXISTS.. skipping.. </strong>";
		}
		
		$content .= "</p></div>";
		$content .= "<div style=\"position: absolute; bottom: 5px; right: 10px;\"><a href=\"?step=4\">next »</a></div>";
		$content .= "<div style=\"position: absolute; bottom: 5px; left: 10px;\"><a href=\"javascript:history.back();\">« back</a></div>";
	}
	
	//---------------------------------
	// STEP 4
	//---------------------------------
	
	else if ($_GET[step] == 4)
	{
		$fh = @fopen('index.php', 'wb');
		if (!$fh)
		{
			$content .= "<strong>FAILED! Check write permissions...</strong>";
		}
		else
		{
			$simpleIndex = "<?php 
include_once 'Tweetr.php';
\$tweetrOptions['baseURL'] = \"".pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME)."\";
\$tweetr = new Tweetr(\$tweetrOptions);
?>";
			fwrite($fh, $simpleIndex);
			fclose($fh);
		}
		
		$titleArr = array(4,$steps,"Stop! Test Time!");
		
		$content .= "<div><p>We should now be able to retrieve your <a style=\"font-size: 12px;\" href=\"http://apiwiki.twitter.com/Twitter-REST-API-Method%3A-statuses-friends_timeline\" target=\"_blank\">friends timeline XML</a> containing status elements. To test this, provide your twitter username & password:</p></div>";
		$content .= "<form id=\"pageForm\" method=\"POST\" action=\"?step=4\">\n";
		$content .= "<p><strong>Username:</strong> <input style=\"width: 100px;\" name=\"user\" type=\"text\" >&nbsp;&nbsp;";
		$content .= "<strong>Password:</strong> <input style=\"width: 100px;\" name=\"pass\" type=\"password\" >&nbsp;<input type=\"submit\" value=\"Test it\"/></p>";
		$content .= "</form>\n";
		$content .= "";
		
		if ($_SERVER['REQUEST_METHOD'] == "POST")
			$content .= "<iframe src=\"statuses/friends_timeline.xml?hash=".base64_encode($_POST['user'].":".$_POST['pass'])."\" width=\"100%\" height=\"230\"><p>Your browser does not support iframes.</p></iframe>";
		
		if ($_SERVER['REQUEST_METHOD'] == "POST")
			$content .= "<div style=\"position: absolute; bottom: 5px; right: 10px;\"><a href=\"?step=5\">next »</a></div>";
		else
			$content .= "<div style=\"position: absolute; bottom: 5px; right: 10px;\"><a href=\"javascript:pageForm.submit();\">next »</a></div>";
		$content .= "<div style=\"position: absolute; bottom: 5px; left: 10px;\"><a href=\"javascript:history.back();\">« back</a></div>";
	}
	
	//---------------------------------
	// STEP 5	
	//---------------------------------
	
	else if ($_GET['step'] == 5)
	{
		$titleArr = array(5,$steps,"Have some Options..");
		
		$content .= "<div><p>Hopefully everything went smooth so far? Cool! Last but not least, let's configure some TweetrProxy Options:</p><br/></div>";
		
		$content .= "<h2>Caching</h2>";
		$content .= "<form id=\"pageForm\" method=\"POST\" action=\"?step=6\">\n";
		$content .= "<script language=\"javascript\">
			function toggleInput()
			{
				enabler = document.getElementById('cachingEnabled');
				expiry = document.getElementById('expiry');
				if (enabler.checked == true)
					expiry.disabled = false;
				else
					expiry.disabled = true;
			}
		</script>";
		$content .= "<strong>Enable caching</strong>&nbsp;&nbsp;<input id=\"cachingEnabled\" type=\"checkbox\" onclick=\"toggleInput();\" name=\"cachingEnabled\" value=\"true\" /><br/>";
		$content .= "<strong>Cache expiry: &nbsp;&nbsp;&nbsp;</strong> <input id=\"expiry\" style=\"width: 100px;\" disabled name=\"expiry\" type=\"text\" value=\"".Tweetr::CACHE_TIME."\"> (in seconds)";
		$content .= "</form>\n";
		$content .= "<div style=\"font-style: italic; color: #888;\">Caching will cache incoming requests for the set amount of time. If the same exact request is being made before the cache expires, the cached response will be returned, in order to prevent unnecessary calls.</div>";
		$content .= "<div style=\"position: absolute; bottom: 5px; right: 10px;\"><a href=\"javascript:pageForm.submit();\">next »</a></div>";
		$content .= "<div style=\"position: absolute; bottom: 5px; left: 10px;\"><a href=\"javascript:history.back();\">« back</a></div>";
	}
	
	//---------------------------------
	// STEP 6	
	//---------------------------------
	
	else if ($_GET['step'] == 6 && $_SERVER['REQUEST_METHOD'] == "POST")
	{
		$titleArr = array(6,$steps,"Have some Options..");
		
		$_SESSION['cachingEnabled'] = $_POST['cachingEnabled'];
		$_SESSION['cacheExpiry'] = $_POST['expiry'];
		
		$content .= "<h2>Ghosting</h2>";
		$content .= "<form id=\"pageForm\" method=\"POST\" action=\"?step=7\">\n";
		$content .= "<script language=\"javascript\">
			function toggleInput()
			{
				enabler = document.getElementById('ghostingEnabled');
				if (enabler.checked == true)
				{
					document.getElementById('ghostName').disabled = false;
					document.getElementById('ghostPass').disabled = false;
					document.getElementById('realUser').disabled = false;
					document.getElementById('realPass').disabled = false;
				}
				else
				{
					document.getElementById('ghostName').disabled = true;
					document.getElementById('ghostPass').disabled = true;
					document.getElementById('realUser').disabled = true;
					document.getElementById('realPass').disabled = true;
				}
			}
		</script>";
		$content .= "<strong>Enable Ghosting</strong>&nbsp;&nbsp;&nbsp;&nbsp;<input id=\"ghostingEnabled\" type=\"checkbox\" onclick=\"toggleInput();\" name=\"ghostingEnabled\" value=\"true\" /><br/>";
		$content .= "<p><strong>Ghost Username:</strong> &nbsp;&nbsp;<input id=\"ghostName\" style=\"width: 100px;\" disabled name=\"ghostName\" type=\"text\" value=\"".Tweetr::GHOST_DEFAULT."\"><br/>";
		$content .= "<strong>Ghost Password:</strong> &nbsp;&nbsp;&nbsp;<input id=\"ghostPass\" style=\"width: 100px;\" disabled name=\"ghostPass\" type=\"text\" value=\"".Tweetr::GHOST_DEFAULT."\"></p>";
		$content .= "<strong>Real Username:</strong> &nbsp;&nbsp;&nbsp;&nbsp;<input id=\"realUser\" style=\"width: 100px;\" disabled name=\"realUser\" type=\"text\" value=\"\"><br/>";
		$content .= "<strong>Real Password:</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id=\"realPass\" style=\"width: 100px;\" disabled name=\"realPass\" type=\"text\" value=\"\">";
		$content .= "</form>\n";
		$content .= "<div style=\"font-style: italic; color: #888;\">This allows you to mask/hide the actual username you are going to use.<br/>From your app you pass these ghost credentials and the proxy will recognize that and actually use the userName and userPass supplied instead of the ghost credentials. </div>";
		$content .= "<div style=\"position: absolute; bottom: 5px; right: 10px;\"><a href=\"javascript:pageForm.submit();\">next »</a></div>";
		$content .= "<div style=\"position: absolute; bottom: 5px; left: 10px;\"><a href=\"javascript:history.back();\">« back</a></div>";
	}
	
	//---------------------------------
	// STEP 7	
	//---------------------------------
	
	else if ($_GET['step'] == 7 && $_SERVER['REQUEST_METHOD'] == "POST")
	{
		$titleArr = array(7,$steps,"Have some Options..");
		
		$_SESSION['ghostingEnabled'] = $_POST['ghostingEnabled'];
		$_SESSION['ghostName'] = $_POST['ghostName'];
		$_SESSION['ghostPass'] = $_POST['ghostPass'];
		$_SESSION['realUser'] = $_POST['realUser'];
		$_SESSION['realPass'] = $_POST['realPass'];
		
		$content .= "<h2>Personalization</h2>";
		$content .= "<form id=\"pageForm\" method=\"POST\" action=\"?step=8\">\n";
		$content .= "<p><strong>UserAgent:</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style=\"width: 200px;\" name=\"agentName\" type=\"text\" value=\"".Tweetr::USER_AGENT."\"><br/>";
		$content .= "<strong>UserAgent Link:</strong> &nbsp;&nbsp;&nbsp;<input style=\"width: 200px;\" name=\"agentLink\" type=\"text\" value=\"".Tweetr::USER_AGENT_LINK."\"></p>";
		$content .= "<strong>Custom Index Page:</strong><br/><textarea name=\"customHTML\" style=\"width: 100%; height: 190px; font-size: 11px;\">your html here</textarea>";
		$content .= "</form>\n";
		$content .= "<div style=\"font-style: italic; color: #888;\">This allows you to set a custom UserAgent for the Proxy and a Link. And if you want to go all crazy, you can even define your own custom startpage for the proxy.</div>";
		$content .= "<div style=\"position: absolute; bottom: 5px; right: 10px;\"><a href=\"javascript:pageForm.submit();\">next »</a></div>";
		$content .= "<div style=\"position: absolute; bottom: 5px; left: 10px;\"><a href=\"javascript:history.back();\">« back</a></div>";
	}
	
	//---------------------------------
	// STEP 8 (the end)	
	//---------------------------------
	
	else if ($_GET['step'] == 8 && $_SERVER['REQUEST_METHOD'] == "POST")
	{
		$titleArr = array(8,$steps,"Setup Complete");
		
		$_SESSION['cachingEnabled'] = ($_SESSION['cachingEnabled'] == "true") ? "true" : "false";
		$_SESSION['cacheExpiry'] = (empty($_SESSION['cacheExpiry'])) ? Tweetr::CACHE_TIME : $_SESSION['cacheExpiry'];
		$_SESSION['ghostingEnabled'] = ($_SESSION['ghostingEnabled'] == "true") ? "true" : "false";
		$_SESSION['ghostName'] = (empty($_SESSION['ghostName'])) ? Tweetr::GHOST_DEFAULT : $_SESSION['ghostName'];
		$_SESSION['ghostPass'] = (empty($_SESSION['ghostPass'])) ? Tweetr::GHOST_DEFAULT : $_SESSION['ghostPass'];
		$_SESSION['realUser'] = (empty($_SESSION['realUser'])) ? "your_username" : $_SESSION['realUser'];
		$_SESSION['realPass'] = (empty($_SESSION['realPass'])) ? "your_password" : $_SESSION['realPass'];
		$customHTML = ($_POST['customHTML'] == "your html here") ? null : $_POST['customHTML'];
		
		$fh = @fopen('index.php', 'wb');
		if (!$fh)
		{
			$content .= "<strong>Oops, couldn't wite to index.php! Check write permissions...</strong>";
		}
		else
		{
			$indexText = "<?php 
include_once 'Tweetr.php';

/**
 * Tweetr Proxy Class Startpoint
 * @author Sandro Ducceschi [swfjunkie.com, Switzerland]
 * 
 * see http://code.google.com/p/tweetr/wiki/PHPProxyUsage
 * on how to use the tweetr php proxy and it's options.
 */

\$tweetrOptions['baseURL'] = \"".pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME)."\";
\$tweetrOptions['userAgent'] = \"".$_POST['agentName']."\";
\$tweetrOptions['userAgentLink'] = \"".$_POST['agentLink']."/\";";
if (!empty($customHTML))
{
	$indexText .= "
\$tweetrOptions['indexContent'] = \"".urlencode($customHTML)."\";";
}
else
{
	$indexText .= "
//\$tweetrOptions['indexContent'] = \"my custom index page\";";
}
$indexText .= "
//\$tweetrOptions['debugMode'] = true;";

if ($_SESSION['ghostingEnabled'] == "true")
{
	$indexText .= "
\$tweetrOptions['ghostName'] = \"".$_SESSION['ghostName']."\";
\$tweetrOptions['ghostPass'] = \"".$_SESSION['ghostPass']."\";
\$tweetrOptions['userName'] = \"".$_SESSION['realUser']."\";
\$tweetrOptions['userPass'] = \"".$_SESSION['realPass']."\";";
}
else
{
	$indexText .= "
//\$tweetrOptions['ghostName'] = \"".$_SESSION['ghostName']."\";
//\$tweetrOptions['ghostPass'] = \"".$_SESSION['ghostPass']."\";
//\$tweetrOptions['userName'] = \"".$_SESSION['realUser']."\";
//\$tweetrOptions['userPass'] = \"".$_SESSION['realPass']."\";";
}
if ($_SESSION['cachingEnabled'] == "true")
{
	$indexText .= "
\$tweetrOptions['cache_enabled'] = ".$_SESSION['cachingEnabled'].";
\$tweetrOptions['cache_time'] = ".$_SESSION['cacheExpiry'].";
\$tweetrOptions['cache_directory'] = \"".$_SESSION['cacheFolder']."\";";
}
else
{
	$indexText .= "
//\$tweetrOptions['cache_enabled'] = true;
//\$tweetrOptions['cache_time'] = 120; // 2 minutes
//\$tweetrOptions['cache_directory'] = \"".$_SESSION['cacheFolder']."\";";
}
$indexText .= "
\$tweetrOptions['upload_directory'] = \"".$_SESSION['tempFolder']."\";
\$tweetr = new Tweetr(\$tweetrOptions);
?>";
			fwrite($fh, $indexText);
			fclose($fh);
			$content .= "<p><strong>Everything is setup and you are ready to use the Proxy. Awesome! ;)</strong></p>
						 <p>For security reasons, it would be wise to either delete this install script (<i>install.php</i>) or rename it, 
						 in case you are considering changing some settings later on.</p>
						 <p>Let me leave you with some Links:
						 <ul>
						 	<li><a style=\"font-size: 12px;\" href=\"http://tweetr.googlecode.com/\" target=\"_blank\">Tweetr Library on Googlecode</a></li>
						 	<li><a style=\"font-size: 12px;\" href=\"http://groups.google.com/group/tweetr-as3-library\" target=\"_blank\">Tweetr Library Discussion Group on Google Groups</a></li>
						 	<li><a style=\"font-size: 12px;\" href=\"http://blog.swfjunkie.com\" target=\"_blank\">SWFJunkie (authors blog)</a></li>
						 	<li><a style=\"font-size: 12px;\" href=\"http://twitter.com/_sandro\" target=\"_blank\">Author on Twitter (@_sandro)</a></li>
						 </ul></p>
						 <p>Thanks for using the Tweetr Library and remember, if you build something cool, let me know :)</p>
						 <p>Regards,<br/>Sandro</p>";
		}
		
		$content .= "<div style=\"position: absolute; bottom: 5px; right: 10px;\"><a href=\"".pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME)."/\">yay finished »</a></div>";
	 	$content .= "<div style=\"position: absolute; bottom: 5px; left: 10px;\"><a href=\"javascript:history.back();\">« back</a></div>";
	}
	else
	{
		header("Location: install.php");
	}
}

echo $header;
echo str_replace(array("$1", "$2", "$3"), $titleArr, $title);
echo $content;
echo $footer;
?>