<?php

class WrongPasswordException extends \FuelException {}

class Controller_Member_setting extends Controller_Member
{
	protected $check_not_auth_action = array(
		'change_email',
	);

	public function before()
	{
		parent::before();
	}

	/**
	 * Mmeber setting
	 * 
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		$this->set_title_and_breadcrumbs('設定変更', null, $this->u);
		$this->template->content = View::forge('member/setting/index');
	}

	/**
	 * Mmeber setting password
	 * 
	 * @access  public
	 * @return  Response
	 */
	public function action_password()
	{
		if (!$form = Fieldset::instance('setting_password'))
		{
			$form = $this->form_setting_password();
		}

		if (Input::method() === 'POST')
		{
			$form->repopulate();
		}
		$this->set_title_and_breadcrumbs('パスワード変更', array('/member/setting/' => '設定変更'), $this->u);
		$this->template->content = View::forge('member/setting/password');
		$this->template->content->set_safe('html_form', $form->build('/member/setting/change_password'));// form の action に入る
	}

	public function action_change_password()
	{
		Util_security::check_method('POST');
		Util_security::check_csrf();

		$form = $this->form_setting_password();
		$val  = $form->validation();

		if ($val->run())
		{
			$post = $val->validated();

			$data = array();
			$data['to_name']      = $this->u->name;
			$data['to_address']   = $this->u->member_auth->email;
			$data['from_name']    = \Config::get('site.member_setting_common.from_name');
			$data['from_address'] = \Config::get('site.member_setting_common.from_mail_address');
			$data['subject']      = \Config::get('site.member_setting_password.subject');

			$data['body'] = <<< END
{$data['to_name']} 様

パスワードを変更しました。

================================
新しいパスワード: {$post['password']}
================================
END;

			try
			{
				$this->change_password($post['old_password'], $post['password']);
				Util_toolkit::sendmail($data);
				Session::set_flash('message', 'パスワードを変更しました。再度ログインしてください。');
				Response::redirect(Config::get('site.login_uri.site'));
			}
			catch(EmailValidationFailedException $e)
			{
				$this->display_error('パスワード変更: 送信エラー', __METHOD__.' email validation error: '.$e->getMessage());
			}
			catch(EmailSendingFailedException $e)
			{
				$this->display_error('パスワード変更: 送信エラー', __METHOD__.' email sending error: '.$e->getMessage());
			}
			catch(WrongPasswordException $e)
			{
				Session::set_flash('error', '現在のパスワードが正しくありません。');
				$this->action_password();
			}
		}
		else
		{
			Session::set_flash('error', $val->show_errors());
			$this->action_password();
		}
	}

	/**
	 * Mmeber setting email
	 * 
	 * @access  public
	 * @return  Response
	 */
	public function action_email()
	{
		if (!$form = Fieldset::instance('setting_email'))
		{
			$form = $this->form_setting_email();
		}

		if (Input::method() === 'POST')
		{
			$form->repopulate();
		}
		$this->set_title_and_breadcrumbs('メールアドレス変更', array('/member/setting/' => '設定変更'), $this->u);
		$this->template->content = View::forge('member/setting/email');
		$this->template->content->set_safe('html_form', $form->build('/member/setting/confirm_change_email'));// form の action に入る
	}

	/**
	 * Confirm change email
	 * 
	 * @access  public
	 * @return  Response
	 */
	public function action_confirm_change_email()
	{
		Util_security::check_method('POST');
		Util_security::check_csrf();

		$form = $this->form_setting_email();
		$val  = $form->validation();

		if (!$val->run())
		{
			Session::set_flash('error', $val->show_errors());
			$this->action_email();
			return;
		}
		$post = $val->validated();

		if (Model_MemberAuth::query()->where('email', $post['email'])->get_one())
		{
			Session::set_flash('error', 'そのアドレスは登録できません。');
			$this->action_email();
			return;
		}

		try
		{
			$maildata = array();
			$maildata['to_name']      = $this->u->name;
			$maildata['to_address']   = $post['email'];
			$maildata['from_name']    = \Config::get('site.member_setting_common.from_name');
			$maildata['from_address'] = \Config::get('site.member_setting_common.from_mail_address');
			$maildata['subject']      = \Config::get('site.member_confirm_change_email.subject');
			$maildata['token']        = $this->save_member_email_pre($this->u->id, $post);
			$this->send_confirm_change_email_mail($maildata);

			Session::set_flash('message', '新しいアドレス宛に確認用メールを送信しました。受信したメール内に記載された URL よりアドレスの変更を完了してください。');
			Response::redirect('member/setting');
		}
		catch(EmailValidationFailedException $e)
		{
			$this->display_error('メールアドレス変更: 送信エラー', __METHOD__.' email validation error: '.$e->getMessage());
		}
		catch(EmailSendingFailedException $e)
		{
			$this->display_error('メールアドレス変更: 送信エラー', __METHOD__.' email sending error: '.$e->getMessage());
		}
	}

	/**
	 * Execute change email.
	 * 
	 * @access  public
	 * @return  Response
	 */
	public function action_change_email()
	{
		$member_email_pre = Model_MemberEmailPre::query()->where('token', Input::param('token'))->get_one();
		if (!$member_email_pre || (Auth::check() && $member_email_pre->member_id != $this->u->id))
		{
			$this->display_error(null, null, 'error/403', 403);
			return;
		}

		$val = Validation::forge('change_email');
		$val->add('password', 'パスワード', array('type'=>'password'))
			->add_rule('trim')
			->add_rule('required')
			->add_rule('min_length', 6)
			->add_rule('max_length', 20);
		$val->add('token', '', array('type'=>'hidden'))
			->add_rule('required');

		if (Input::method() == 'POST')
		{
			Util_security::check_csrf();
			$auth = Auth::instance();
			if ($val->run() && $auth->check_password())
			{
				try
				{
					if (!$auth->update_user(array('email' => $member_email_pre->email)))
					{
						throw new Exception('change email error.');
					}
					$member = Model_Member::find($member_email_pre->member_id);

					$maildata = array();
					$maildata['from_name']    = \Config::get('site.member_setting_common.from_name');
					$maildata['from_address'] = \Config::get('site.member_setting_common.from_mail_address');
					$maildata['subject']      = \Config::get('site.member_change_email.subject');
					$maildata['to_address']   = $member_email_pre->email;
					$maildata['to_name']      = $member->name;
					$this->send_change_email_mail($maildata);

					// 仮登録情報の削除
					$member_email_pre->delete();

					Session::set_flash('message', 'メールアドレスを変更しました。');
					Response::redirect('member');
				}
				catch(EmailValidationFailedException $e)
				{
					$this->display_error('メンバー登録: 送信エラー', __METHOD__.' email validation error: '.$e->getMessage());
					return;
				}
				catch(EmailSendingFailedException $e)
				{
					$this->display_error('メンバー登録: 送信エラー', __METHOD__.' email sending error: '.$e->getMessage());
					return;
				}
				catch(Auth\SimpleUserUpdateException $e)
				{
					Session::set_flash('error', 'そのアドレスは登録できません');
				}
			}
			else
			{
				if ($val->show_errors())
				{
					Session::set_flash('error', $val->show_errors());
				}
				else
				{
					Session::set_flash('error', 'パスワードが正しくありません');
				}
			}
		}

		if (Auth::check())
		{
			$this->set_title_and_breadcrumbs('メールアドレス変更確認', array('/member/setting/' => '設定変更', '/member/setting/email' => 'メールアドレス変更'), $this->u);
		}
		else
		{
			$this->set_title_and_breadcrumbs('メールアドレス変更確認');
		}
		$this->template->content = View::forge('member/setting/change_email', array('val' => $val, 'member_email_pre' => $member_email_pre));
	}

	public function form_setting_password()
	{
		$add_fields = array(
			'old_password' => array(
				'label' => '現在のパスワード',
				'attributes' => array('type'=>'password', 'class' => 'form-control input-xlarge'),
				'rules' => array('trim', 'required', array('min_length', 6),  array('max_length', 20)),
			),
			'password' => array(
				'label' => '新しいパスワード',
				'attributes' => array('type'=>'password', 'class' => 'form-control input-xlarge'),
				'rules' => array('trim', 'required', array('min_length', 6),  array('max_length', 20), array('unmatch_field', 'old_password')),
			),
			'password_confirm' => array(
				'label' => '新しいパスワード(確認)',
				'attributes' => array('type'=>'password', 'class' => 'form-control input-xlarge'),
				'rules' => array('trim', 'required', array('match_field', 'password')),
			),
		);
		$form = \Site_Util::get_form_instance('setting_password', null, true, $add_fields, array('value' => '変更'));

		return $form;
	}

	public function form_setting_email()
	{
		$add_fields = array(
			'email' => array(
				'label' => 'メールアドレス',
				'attributes' => array('type'=>'email', 'class' => 'form-control input-xlarge'),
				'rules' => array('trim', 'required', 'valid_email'),
			),
			'email_confirm' => array(
				'label' => 'メールアドレス(確認)',
				'attributes' => array('type'=>'email', 'class' => 'form-control input-xlarge'),
				'rules' => array('trim', 'required', array('match_field', 'email')),
			),
		);
		$form = \Site_Util::get_form_instance('setting_email', null, true, $add_fields, array('value' => '変更'));

		return $form;
	}

	protected function change_password($old_password, $password)
	{
		$auth = Auth::instance();
		if (!$auth->change_password($old_password, $password))
		{
			throw new WrongPasswordException('change password error.');
		}

		return $auth->logout();
	}

	private function save_member_email_pre($member_id, $data)
	{
		$member_email_pre = Model_MemberEmailPre::find($member_id);
		if (!$member_email_pre) $member_email_pre = new Model_MemberEmailPre;

		$member_email_pre->member_id = $member_id;
		$member_email_pre->email     = $data['email'];
		$member_email_pre->token     = Util_toolkit::create_hash();
		$member_email_pre->save();

		return $member_email_pre->token;
	}

	private function send_confirm_change_email_mail($data)
	{
		if (!is_array($data)) $data = (array)$data;

		$register_url = sprintf('%s?token=%s', uri::create('member/setting/change_email'), $data['token']);

		$data['body'] = <<< END
こんにちは、{$data['to_name']}さん

まだメールアドレスの変更は完了しておりません。

以下のアドレスをクリックすることにより、メールアドレスの変更が完了します。
{$register_url}

END;

		util_toolkit::sendmail($data);
	}

	private function send_change_email_mail($data)
	{
		if (!is_array($data)) $data = (array)$data;

		$data['body'] = <<< END
こんにちは、{$data['to_name']}さん

メールアドレスの変更が完了しました。

====================
新しいメールアドレス:
{$data['to_address']}
====================

END;

		util_toolkit::sendmail($data);
	}
}
