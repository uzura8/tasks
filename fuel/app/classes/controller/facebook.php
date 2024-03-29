<?php
require_once APPPATH.'vendor/facebook-php-sdk/facebook.php';

class Controller_Facebook extends Controller_Site
{
	private $fb;

	public function before()
	{
		parent::before();
		if (!PRJ_FACEBOOK_APP_ID) throw new HttpNotFoundException;

		Config::load('facebook', 'facebook');
		$this->fb = new Facebook(Config::get('facebook.init'));
	}

	public function action_logout()
	{
		$url = $this->fb->getLogoutUrl(Config::get('facebook.logout'));
		$this->fb->destroySession();
		Auth::logout();
		Session::set_flash('message', 'ログアウトしました');
		Response::redirect($url);
	}
}
