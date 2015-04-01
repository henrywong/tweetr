### v0.95 ###

**Fixes / Changes**
  * AS: Minor changes to internal authentication scheme to reflect proxy changes concerning authentication issues
  * AS: Failed requests will dispatch a TweetEvent.FAILED with a HashData object if available
  * AS: Replaced the Social Graph Methods page parameter with the new cursor parameter
  * AS: Replace page parameter with the new cursor parameter for getFriends() and getFollowers()
  * AS: Returntypes RETURN\_TYPE\_EXTENDED\_USER\_INFO and RETURN\_TYPE\_BASIC\_USER\_INFO have been replaced by RETURN\_TYPE\_USER\_INFO since there is no difference between the two anymore.
  * AS: Updated parameter documentation for search().
  * AS: Marked getSingleTweet() as Deprecated. Please use getStatus() from now.
  * AS: Marked destroyTweet() as Deprecated. Please use destroyStatus() from now.
  * AS: Reminder: getReplies() is marked as Deprecated. Please use getMentions() from now.
  * AS: Added CursorData Object to TweetEvent Responses if the response supplied by twitter contains it.

  * PROXY: Fixed Browser Authentication Issues

**New**
  * AS: Added friendships/show methods showFriendshipById() and showFriendshipByName()
  * AS: Added blocks/exists, blocks/blocking and blocks/blocking/ids methods blockExists(), getBlocks() and getBlockIds()
  * AS: Added trends methods currentTrends(), dailyTrends(), weeklyTrends()
  * AS: Added spam\_report method reportSpammer()
  * AS: Added missing profile methods updateProfileImage(), updateProfileBackgroundImage() and updateProfileColors()
  * AS: Added verifyCredentials() to check if the supplied credentials are valid or not.

  * AS: Added the entire barrage of LIST methods. getList(), createList(), deleteList(), getLists(), getListStatuses(), getListMemberships(), getListSubscriptions(), getListMembers(), addListMember(), removeListMember(), hasListMember(), getListSubscribers(), addListSubscription(), removeListSubscription(), hasListSubscription();

  * AS: Added URL Unshortener Class using the public service provided by http://untiny.me/

  * PROXY: Added Install/Configuration Script (install.php)


---


### v0.94 ###

**Fixes / Changes**
  * AS: fixed usertimeline retrieval parameters, removed count (not existent there) added max\_id
  * AS: removed an import within tweetr.as that should have not been there in the first place and caused errors for flash ide projects. ([issue 5](https://code.google.com/p/tweetr/issues/detail?id=5))
  * AS: added max\_id parameter to usertimeline, friendstimeline, getReplies
  * AS: fixed getUserProfile() bug because of one / too many  ([issue 4](https://code.google.com/p/tweetr/issues/detail?id=4))
  * AS: renamed public property browserAuth to useAuthHeaders and set it default to false
  * AS  Fixed twitpocalypse problem by switching id typecasting from int to Number
  * AS: Added getMentions to comply to twitters own "reply" renaming. getReplies still exists but is marked as deprecated.
  * AS: Removed deprecated 'email' arguments
  * AS: Added max\_id argument support to getReceivedDirectMessages() and getSentDirectMessages() for direct message pagination
  * AS: Fixed search so it realizes if you are looking for a phrase or just a word
  * AS: Updated tweetr to always return ExtendedUserData to reflect twitter API changes
  * AS: Updated TweetUtil.returnTweetAge() to reflect wishes and changes submitted by dougrdotnet &  mr.jawright ([issue 3](https://code.google.com/p/tweetr/issues/detail?id=3))
  * AS: Fixed wrong event meta tags in Tweetr.as ([issue 10](https://code.google.com/p/tweetr/issues/detail?id=10))

  * PROXY: couple stability and compatibility fixes (thanks go out to @gladhon for these fixes)

**New**
  * AS: TweetEvent also returns the unparsed data back within the event property "data"
  * AS: Added getDirectMessage() to retrieve single direct message
  * AS: Added endSession() Method that allows you to end a users session
  * AS: Added Social Graph Methods: getFriendIds(), getFollowerIds()
  * AS: Added Saved Search Methods: getSavedSearches(), getSavedSearch(), createSavedSearch(), destroySavedSearch();

  * PROXY: Added charset parameter to headers sent for the xml by the proxy
  * PROXY: Added simple caching of GET Requests. POST is never cached. (thx to @kngfu)

  * CROSSDOMAIN: added request headers tag to xml


---


### v0.93 ###

**Fixes / Changes**
  * PHP Proxy: Improvements from [r23](https://code.google.com/p/tweetr/source/detail?r=23) turned out to introduce some problems with certain calls. Is fixed now.


---


### v0.92 ###

**Fixes / Changes**
  * PHP Proxy: added more options see http://code.google.com/p/tweetr/wiki/PHPProxyUsage

**New**
  * Added another Example (Example #3) to demonstrate new Proxy Options


---


### v0.91 ###

**Fixes / Changes**
  * Updated PHP Proxy Class: Simplified it heavily

**New**
  * Added crossdomain.xml template to the .zip


---


### v0.9 ###

**Fixes / Changes**
  * Removed / fixed some authentication mixups for couple GET Requests
  * added serviceHost variable to define if we call twitter directly or use the PHP Proxy

**New**
  * PHP Proxy Class so Tweetr can be used from web apps
  * Added two examples on how to use tweetr with the proxy