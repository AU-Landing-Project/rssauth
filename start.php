<?php

namespace AU\RSSAuth;

const PLUGIN_ID = 'rssauth';

elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\init', 0);
elgg_register_viewtype('rssauth');

function init() {

	$view = elgg_get_viewtype();

	if ($view != 'rss' && $view != 'rssauth') {
		// doesn't affect us then
		return true;
	}
	
	if ($view == 'rssauth') {
		elgg_set_viewtype('rss');
	}
	
	$token_auth = elgg_get_plugin_setting('token_auth', PLUGIN_ID);
	$token = get_input('rsstoken', false);
	$user = get_user(get_input('rssguid', false));
	if ($token_auth == 'yes' && $token && $user) {
		$check = elgg_get_plugin_user_setting('token', $user->guid, PLUGIN_ID);
		if ($token === $check) {
			login_rss_user($user);
			return true;
		}
	}

	// use get variables if settings allow
	$allow_get = elgg_get_plugin_setting('get_auth', PLUGIN_ID);
	if (!empty($_GET['username']) && !empty($_GET['password']) && $allow_get == 'yes') {
		$username = get_input('username');
		$password = get_input('password');

		if (elgg_authenticate($username, $password) === true) {
			$user = get_user_by_username($username);
			if ($user) {
				login_rss_user($user);
				return true;
			}
		}
	}

	// if the server is using FastCGI instead of modPHP this will allow HTTP auth to still work... maybe
	if (isset($_SERVER["AUTHORIZATION"]) && !empty($_SERVER["AUTHORIZATION"])) {
		list ($type, $cred) = split(" ", $_SERVER['AUTHORIZATION']);

		if ($type == 'Basic') {
			list ($user, $pass) = explode(":", base64_decode($cred));
			$_SERVER['PHP_AUTH_USER'] = $user;
			$_SERVER['PHP_AUTH_PW'] = $pass;
		}
	}


	//if forcing HTTP authentication headers send back the request for authentication
	if ($view == "rssauth" && empty($_SERVER['PHP_AUTH_USER'])) {
		send_auth_headers();
	}

	// authenticate by HTTP header credentials
	if ($view == 'rssauth' && !empty($_SERVER['PHP_AUTH_USER'])) {
		$failedauth = elgg_get_plugin_setting('authfail', PLUGIN_ID);
		// set inputs so we can filter them the same way as core
		set_input('username', $_SERVER['PHP_AUTH_USER']);
		set_input('password', $_SERVER['PHP_AUTH_PW']);

		$username = get_input('username');
		$password = get_input('password');

		if (elgg_authenticate($username, $password) === true) {
			$user = get_user_by_username($username);

			if ($user) {
				login_rss_user($user);
				return true;
			} elseif ($failedauth == 'forceauth') {
				send_auth_headers();
			}
		} elseif ($failedauth == 'forceauth') {
			send_auth_headers();
		}
	}
}

/**
 * send HTTP authentication headers
 */
function send_auth_headers() {
	header('WWW-Authenticate: Basic realm="ElggHTTPAuthRSS"');
	header('HTTP/1.0 401 Unauthorized');
	exit;
}

/**
 * 
 * @param type $user
 */
function login_rss_user($user) {
	login($user);
	elgg_register_event_handler('shutdown', 'system', 'logout', 1000);
}

/**
 * 
 * @param type $user
 */
function generate_token($user) {
	if (!elgg_instanceof($user, 'user')) {
		return false;
	}
	
	return sha1($user->guid . time() . get_site_secret() . uniqid());
}