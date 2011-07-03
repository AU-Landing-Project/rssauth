*********************************
*                               *
 *          RSSauth            *		
*                               *
*********************************

This plugin allows RSS feeds to display content of an Elgg user by HTTP authentication.
The rss feeds may be in the following formats.


PUBLIC RSS FEED
<url>?view=rss



URL AUTHENTICATED RSS FEED
<url>?view=rss&username=<username>&password=<password>

Please note that this method is the most insecure as the
username and password are visible in plain text in the url




HTTP AUTHENTICATED RSS FEED
<url>?view=rssauth

Having view=rssauth in the url will return the headers requesting authentication
Your rss feed can then supply the username and password.

Please note that this is also insecure as the credentials are passed in plain text
but are not explicitly visible (as in the url example).  This should be fine if used
in conjunction with SSL.



ADDITIONALLY
if you can find a way to send the HTTP credentials without requiring the authentication headers
first, you can just use the the default <url>?view=rss and still receive an authenticated feed 