<?php

class Controller_Member_Api extends Controller_Site_Api
{
	protected $check_not_auth_action = array(
	);

	public function before()
	{
		parent::before();
	}

	/**
	 * Note update public_flag
	 * 
	 * @access  public
	 * @return  Response (html)
	 */
	public function post_update_config($name = null)
	{
		$response = '0';
		try
		{
			self::check_name_format_for_update_config($name);
			$this->check_response_format_for_update_config($name);
			\Util_security::check_csrf();

			$member_id = (int)\Input::post('id');
			if (!$member_id || !$member = Model_Member::check_authority($member_id, $this->u->id))
			{
				throw new \HttpNotFoundException;
			}
			if (!$member_config = Model_MemberConfig::get_from_member_id_and_name($member_id, $name))
			{
				$member_config = Model_MemberConfig::forge();
				$member_config->member_id = $member_id;
				$member_config->name = $name;
			}
			$current_value = isset($member_config->value) ? $member_config->value : null;
			$value = self::validate_posted_value($name, $current_value);

			\DB::start_transaction();
			$member_config->value = $value;
			$member_config->save();
			\DB::commit_transaction();

			$response = self::get_response_for_update_config($name, array('id' => $member_id, $name => $value));
			$status_code = 200;

			return \Response::forge($response, $status_code);
		}
		catch(\HttpInvalidInputException $e)
		{
			$status_code = 400;
		}
		catch(\FuelException $e)
		{
			if (\DB::in_transaction()) \DB::rollback_transaction();
			$status_code = 400;
		}

		$this->response($response, $status_code);
	}

	private function check_response_format_for_update_config($name)
	{
		switch ($name)
		{
			case 'timeline_public_flag':
				if ($this->format != 'html') throw new \HttpNotFoundException();
				break;
			default :
				break;
		}
	}

	private static function check_name_format_for_update_config($name)
	{
		$accept_names = array('timeline_public_flag');
		if (!in_array($name, $accept_names)) throw new \HttpNotFoundException();
	}

	private static function validate_posted_value($name, $posted_key = null, $curret_value = null)
	{
		if (!$posted_key) $posted_key = $name;
		switch ($name)
		{
			case 'timeline_public_flag':
				$value = Site_Util::validate_posted_public_flag($curret_value);
				break;
			default :
				break;
		}

		return $value;
	}

	private static function get_response_for_update_config($name, $data = array())
	{
		switch ($name)
		{
			case 'timeline_public_flag':
				$data['public_flag'] = $data[$name];
				$data['view_icon_only'] = (bool)Input::post_get('icon_only_flag');
				$data['is_mycontents'] = true;
				$data['without_parent_box'] = true;
				$data['is_use_in_form'] = true;
				$data['parent_box_additional_class'] = 'pull-right';
				$data['post_uri'] = 'member/api/update_config/timeline_public_flag.html';
				unset($data[$name]);

				return View::forge('_parts/public_flag_selecter', $data);
				break;
			default :
				break;
		}
	}
}
