<?php
namespace Note;

class Controller_Api extends \Controller_Site_Api
{
	protected $check_not_auth_action = array(
		'get_list'
	);

	public function before()
	{
		parent::before();
	}

	/**
	 * Api list
	 * 
	 * @access  public
	 * @return  Response (html)
	 */
	public function get_list()
	{
		if ($this->format != 'html') throw new \HttpNotFoundException();

		$page      = (int)\Input::get('page', 1);
		$member_id = (int)\Input::get('member_id', 0);

		$response = '';
		try
		{
			list($is_mypage, $member) = $this->check_auth_and_is_mypage($member_id, true);
			$is_draft = $is_mypage ? \Util_string::cast_bool_int(\Input::get('is_draft', 0)) : 0;
			$is_published = \Util_toolkit::reverse_bool($is_draft, true);
			$data = \Site_Model::get_simple_pager_list('note', $page, array(
				'related'  => 'member',
				'where'    => \Site_Model::get_where_params4list(
					$member_id,
					\Auth::check() ? $this->u->id : 0,
					$is_mypage,
					array(array('is_published', $is_published))
				),
				'limit'    => \Config::get('site.view_params_default.list.limit'),
				'order_by' => array('created_at' => 'desc'),
			), 'Note');
			$data['is_draft'] = $is_draft;

			$response = \View::forge('_parts/list', $data);
			$status_code = 200;

			return \Response::forge($response, $status_code);
		}
		catch(\FuelException $e)
		{
			$status_code = 400;
		}

		$this->response($response, $status_code);
	}

	/**
	 * Note delete
	 * 
	 * @access  public
	 * @return  Response
	 */
	public function post_delete()
	{
		$response = array('status' => 0);
		try
		{
			\Util_security::check_csrf();

			$id = (int)\Input::post('id');
			if (!$id || !$note = Model_Note::check_authority($id, $this->u->id))
			{
				throw new \HttpNotFoundException;
			}

			\DB::start_transaction();
			$deleted_files = $note->delete_with_relations();
			\DB::commit_transaction();
			if (!empty($deleted_files)) \Site_Upload::remove_files($deleted_files);

			$response['status'] = 1;
			$status_code = 200;
		}
		catch(\FuelException $e)
		{
			if (\DB::in_transaction()) \DB::rollback_transaction();
			$status_code = 400;
		}

		$this->response($response, $status_code);
	}

	/**
	 * Note update public_flag
	 * 
	 * @access  public
	 * @return  Response (html)
	 */
	public function post_update_public_flag()
	{
		if ($this->format != 'html') throw new \HttpNotFoundException();
		$response = '0';
		try
		{
			\Util_security::check_csrf();

			$id = (int)\Input::post('id');
			if (!$id || !$note = Model_Note::check_authority($id, $this->u->id))
			{
				throw new \HttpNotFoundException;
			}
			list($public_flag, $model) = \Site_Util::validate_params_for_update_public_flag($note->public_flag);

			\DB::start_transaction();
			$note->update_public_flag_with_relations($public_flag);
			\DB::commit_transaction();

			$data = array('model' => $model, 'id' => $id, 'public_flag' => $public_flag, 'is_mycontents' => true, 'without_parent_box' => true);
			$response = \View::forge('_parts/public_flag_selecter', $data);
			$status_code = 200;

			return \Response::forge($response, $status_code);
		}
		catch(\HttpNotFoundException $e)
		{
			$status_code = 404;
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
}
