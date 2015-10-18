<?php

// use get variables?
$options = array(
    'name' => 'params[get_auth]',
    'value' => $vars['entity']->get_auth ? $vars['entity']->get_auth : 'no',
    'options_values' => array(
        'yes' => elgg_echo('option:yes'),
        'no' => elgg_echo('option:no')
    )
);

echo elgg_echo('rssauth:option:get_auth') . "<br>";
echo elgg_view('input/dropdown', $options) . "<br><br>";

echo elgg_echo('rssauth:option:token_auth') . "<br>";
echo elgg_view('input/dropdown', array(
	'name' => 'params[token_auth]',
	'value' => $vars['entity']->token_auth ? $vars['entity']->token_auth : 'no',
	'options_values' => array(
		'yes' => elgg_echo('option:yes'),
		'no' => elgg_echo('option:no')
	)
));
echo elgg_view('output/longtext', array(
	'value' => elgg_echo('rssauth:option:token_auth:help'),
	'class' => 'elgg-subtext'
));

// what to do if auth fails?
$options = array(
    'name' => 'params[authfail]',
    'value' => $vars['entity']->authfail ? $vars['entity']->authfail : 'default',
    'options_values' => array(
        'default' => elgg_echo('rssauth:option:authfail:default'),
        'forceauth' => elgg_echo('rssauth:option:authfail:forceauth')
    )
);

echo elgg_echo('rssauth:option:authfail') . "<br>";
echo elgg_view('input/dropdown', $options) . "<br><br>";