<?php

return array(
	'site' => array(
		'secure_global_head' => array(
			'Top' => '/',
			'Home' => 'member/',
			Config::get('term.timeline') => 'timeline/',
			Config::get('term.note') => 'note/',
			Config::get('term.album') => 'album/',
			//'Sitemap' => 'sitemap/',
			//'About' => 'about/',
		),
		'insecure_global_head' => array(
			'Top' => '/',
			Config::get('term.timeline') => 'timeline/',
			Config::get('term.note') => 'note/',
			Config::get('term.album') => 'album/',
			//'Sitemap' => 'sitemap/',
			//'About' => 'about/',
		),
		'secure_user_dropdown' => array(
			Config::get('term.myhome') => 'member/',
			Config::get('term.profile') => 'member/profile/',
			'Settings' => 'member/setting/',
			'Sign out' => 'auth/logout/',
		),
		'global_side' => array(
			'Top' => '/',
			Config::get('term.timeline') => 'timeline/',
			Config::get('term.note') => 'note/',
			Config::get('term.album') => 'album/',
			'Sitemap' => 'sitemap/',
			'About' => 'about/',
			'Contact' => 'contact/',
		),
		'secure_side' => array(
			Config::get('term.myhome') => 'member/',
			Config::get('term.profile') => 'member/profile/',
			Config::get('term.timeline') => 'timeline/member/',
			Config::get('term.note') => 'note/member/',
			Config::get('term.album') => 'album/member/',
			'Settings' => 'member/setting/',
			'Sign out' => 'auth/logout/',
		),
	),
	'admin' => array(
		'secure_global_head' => array(
			'Top' => 'admin/',
			'Member' => array(
				'Member list' => 'admin/member',
			),
			'SNS settings' => array(
				'Profile setting' => 'admin/profile',
			),
			'Admin settings' => array(
				'Account management' => 'admin/setting/account',
				'Change password' => 'admin/setting/change_password',
			),
			'Site' => '/',
		),
		'insecure_global_head' => array(
			'Top' => 'admin/',
			'Site' => '/',
		),
		'secure_user_dropdown' => array(
			'Sign out' => 'admin/logout',
		),
		'insecure_user_dropdown' => array(
			'Sign in' => 'admin/login',
		),
		'global_side' => array(
			'Top' => 'admin/',
			'Site' => '/',
		),
		'secure_side' => array(
			'Member' => 'admin/member',
			'Settings' => 'admin/setting',
		),
	),
);
