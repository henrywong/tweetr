# Requirements #

  * >= PHP 5
  * CURL must be available to PHP
  * Use of `.htaccess` / `ModRewrite` must be available/granted

# Installation #

  1. Put the `/proxy` wherever you need it on your server.
  1. Call the proxy in your webbrowser by either directing your browser to the proxy folder or `/proxy/install.php`
  1. Follow the steps ;) !

# Options #

The `index.php` file allows you to configure certain aspects of the Proxy. Syntax is always: `$tweetrOptions['NAME'] = VALUE;`

## Overview ##
| **Name** | **Required** | **Description** | **Default value** |
|:---------|:-------------|:----------------|:------------------|
| baseURL | yes | Base URL of the Proxy | (_string_) `/proxy` |
| userAgent | no | User Agent Name | (_string_) `TweetrProxy/0.92` |
| userAgentLink | no | Link to your User Agent | (_string_) `http://tweetr.googlecode.com` |
| debugMode | no |  Turns Debug Mode ON or OFF. | (_boolean_) `false` |
| ghostName | no |  Ghost Authentication Name | (_string_) `ghost` |
| ghostPass | no |  Ghost Authentication Password | (_string_) `ghost` |
| userName | no |  Username to use instead of Ghost Auth | (_null_) |
| userPass | no |  Password to use instead of Ghost Auth | (_null_) |

## Details ##

<p><b>baseURL</b><br />
As the name says it's the base url where the proxy can find itself. See installation step 3 for more information.<br>
</p>
<p>
<b>userAgent</b><br />
Allows you to set your own User Agent under which the Proxy will do requests to the twitter api<br>
</p>
<p>
<b>userAgentLink</b><br />
Allows you to set a link to a website for your User Agent. When somebody hits <code>index.php</code> he will see the User Agent String which is linked to the website you define here. For an example see <a href='http://labs.swfjunkie.com/tweetr/proxy/'>http://labs.swfjunkie.com/tweetr/proxy/</a>
</p>
<p>
<b>debugMode</b><br />
<code>Tweetr.php</code> allows you to add your own Debugging Routine to it's code, you can enable debugMode by setting this value to true.<br>
</p>
<p>
<b>ghostName & ghostPass</b><br />
This allows you to mask/hide the actual username you are going to use when you also define <code>userName</code> and <code>userPass</code>.<br>
<br />From your app you pass these ghost credentials and the proxy will recognize that and actually use the <code>userName</code> and <code>userPass</code> supplied instead of the ghost credentials.<br>
</p>
<p>
<b>userName & userPass</b><br />
See <code>ghostName</code> & <code>ghostPass</code></p>