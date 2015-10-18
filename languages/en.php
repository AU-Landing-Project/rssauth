<?php

return array(
    'rssauth' => "RSSAuth",
    'rssauth:option:get_auth' => "Allow authentication through the URL (\$_GET variables)? (note: this is insecure)",
    'rssauth:option:authfail' => "What to do if username/password are incorrect?",
    'rssauth:option:authfail:default' => "Display public rss feed",
    'rssauth:option:authfail:forceauth' => "Re-display login box and force authentication",
	'rssauth:option:token_auth' => "Allow token based authentication? (recommended)",
	'rssauth:option:token_auth:help' => "This allows users to use a cryptographic token in urls instead of username/password.  This keeps the username/password safe when using third party rss feed readers.  Users can generate/regenerate tokens on their tool configuration page.",
	'rssauth:usersetting:token:actions' => "Actions",
	'rssauth:usersettings:option:generate' => "Generate Token",
	'rssauth:usersettings:option:regenerate' => "Regenerate Token",
	'rssauth:usersettings:option:delete' => "Delete Token",
	'rssauth:usersettings:option:default' => "Select an action",
	'rssauth:usersettings:yourtoken' => "RSS Authorization Token",
	'rssauth:usersettings:yourtoken:help' => "Add this token to the end of any rss feed url generated by this site.
		The resulting rss will appear as if you are logged in.
		
eg. %s
Would become
%s		
"
);
