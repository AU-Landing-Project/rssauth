<?php
/**
 * 
 *	This plugin allows authentication for RSS feeds through credentials passed via URL
 *	or HTTP authentication
 *
 * 	Default rss feed <url>?view=rss
 * 
 * 	URL authentication <url>?view=rss&username=<username>&password=<password>
 * 
 * 	HTTP authentication <url>?view=rssauth
 * 
 * 	Please be aware of security issues inherent with transmitting usernames/passwords
 * 	via URL.  This is not secure in any way.  Your users are responsible for their own
 * 	usernames and passwords.
 * 
 * 	HTTP authentication sends credentials via plain text.  This is still better than URL
 * 	authentication but not great.  Suggested to use with SSL.
 * 
 */

function rssauth_init() {
	
	$rss = false;
	if($_GET['view'] == "rss" || $_GET['view'] == "rssauth"){
		$rss = true;
	}
	
	//remove this first "if" statement if you don't want $_GET as a viable method of authentication
	if($rss = true && !empty($_GET['username']) && !empty($_GET['password'])){
		$user = authenticate($_GET['username'], $_GET['password']);
		if($user instanceof ElggUser){
			login($user);
		}
	}
	
	// if the server is using FastPCI instead of modPHP this will allow HTTP auth to still work... maybe
	if(isset($_SERVER["AUTHORIZATION"]) && !empty($_SERVER["AUTHORIZATION"])){
    	list ($type, $cred) = split (" ", $_SERVER['AUTHORIZATION']);

    	if ($type == 'Basic') {
        	list ($user, $pass) = explode (":", base64_decode($cred));
        	$_SERVER['PHP_AUTH_USER'] = $user;
        	$_SERVER['PHP_AUTH_PW'] = $pass;
    	}
	}


	//if forcing HTTP authentication headers send back the request for authentication
	if($_GET['view'] == "rssauth" && empty($_SERVER['PHP_AUTH_USER'])){
		header('WWW-Authenticate: Basic realm="ElggHTTPAuthRSS"');
    	header('HTTP/1.0 401 Unauthorized');
    	exit;
	}
	
	// authenticate by HTTP header credentials
	if($rss = true && !empty($_SERVER['PHP_AUTH_USER'])){
		$user = authenticate($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
		if($user instanceof ElggUser){
			login($user);
		}
	}
}

elgg_register_event_handler('init','system','rssauth_init');
?>