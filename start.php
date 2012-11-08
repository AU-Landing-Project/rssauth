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
  $view = elgg_get_viewtype();
	
	// use get variables if settings allow
  $allow_get = elgg_get_plugin_setting('get_auth', 'rssauth');
  if($view == 'rssauth' && !empty($_GET['username']) && !empty($_GET['password']) && $allow_get == 'yes'){
    $username = get_input('username');
    $password = get_input('password');

    if(elgg_authenticate($username, $password) === true){
      $user = get_user_by_username($username);
      if($user){
        login($user);
      }
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
  if($view == "rssauth" && empty($_SERVER['PHP_AUTH_USER'])){
	rssauth_send_auth_headers();
  }
	
  // authenticate by HTTP header credentials
  if($view == 'rssauth' && !empty($_SERVER['PHP_AUTH_USER'])){
	$failedauth = elgg_get_plugin_setting('authfail', 'rssauth');
    // set inputs so we can filter them the same way as core
    set_input('username', $_SERVER['PHP_AUTH_USER']);
    set_input('password', $_SERVER['PHP_AUTH_PW']);
    
    $username = get_input('username');
    $password = get_input('password');
    
    if(elgg_authenticate($username, $password) === true){
      $user = get_user_by_username($username);
      
      if($user){
        login($user);
      }
      elseif($failedauth == 'forceauth'){
        rssauth_send_auth_headers();
      }
    }
    elseif($failedauth == 'forceauth'){
      rssauth_send_auth_headers();
    }
  }
}

function rssauth_send_auth_headers(){
  header('WWW-Authenticate: Basic realm="ElggHTTPAuthRSS"');
  header('HTTP/1.0 401 Unauthorized');
  exit;
}

elgg_register_event_handler('init','system','rssauth_init', 0);
