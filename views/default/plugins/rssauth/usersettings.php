<?php

namespace AU\RSSAuth;

$use_token = elgg_get_plugin_setting('token_auth', PLUGIN_ID);

if ($use_token != 'yes') {
	// use jquery to remove the empty form
	elgg_require_js('rssauth/remove_usersettings');
	return;
}

$user = elgg_get_page_owner_entity();

$token = elgg_get_plugin_user_setting('token', $user->guid, PLUGIN_ID);

if ($token) {
	$value = "&rssguid={$user->guid}&rsstoken={$token}";
	
	echo '<label>' . elgg_echo('rssauth:usersettings:yourtoken') . '</label>';
	echo elgg_view('input/text', array(
		'value' => $value,
		'disabled' => 'disabled'
	));
	
	$url1 = elgg_get_site_url() . 'blog/all?view=rss';
	$url2 = elgg_get_site_url() . 'blog/all?view=rss' . $value;
	echo elgg_view('output/longtext', array(
		'value' => elgg_echo('rssauth:usersettings:yourtoken:help', array($url1, $url2)),
		'class' => 'elgg-subtext'
	));
}

$options_values = array($token => elgg_echo('rssauth:usersettings:option:default'));
if ($token) {
	$newtoken = generate_token($user);
	$options_values[$newtoken] = elgg_echo('rssauth:usersettings:option:regenerate');
	$options_values[''] = elgg_echo('rssauth:usersettings:option:delete');
}
else {
	$newtoken = generate_token($user);
	$options_values[$newtoken] = elgg_echo('rssauth:usersettings:option:generate');
}

echo '<div class="pvm">';
echo '<label>' . elgg_echo('rssauth:usersetting:token:actions') . '</label><br>';
echo elgg_view('input/select', array(
	'name' => 'params[token]',
	'value' => $token,
	'options_values' => $options_values
));
echo '</div>';