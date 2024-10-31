<?php
global $myloyal_case_hooks;
$myloyal_case_hooks = [
	'when_user_visit_site' => 'wp_head',
	'when_user_register' => 'user_register',
	'when_user_gets_comment' => 'wp_insert_comment',
	'when_user_login' => 'wp_login',
	'when_user_creates_post' => 'save_post',
	'when_user_creates_comment' => 'wp_insert_comment',
	'when_user_updates_comment' => 'edit_comment',
	'when_user_create_user' => 'user_register',
];