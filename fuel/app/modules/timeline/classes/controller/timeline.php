<?php
namespace Timeline;

class Controller_Timeline extends \Controller_Site
{
	protected $check_not_auth_action = array(
		'index',
		'list',
		'member',
		'detail',
	);

	public function before()
	{
		parent::before();
	}

	/**
	 * Timeline index
	 * 
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		$this->action_list();
	}

	/**
	 * Timeline list
	 * 
	 * @access  public
	 * @return  Response
	 */
	public function action_list()
	{
		list($list, $is_next) = Site_Model::get_list(\Auth::check() ? $this->u->id : 0);
		$this->set_title_and_breadcrumbs(sprintf('最新の%s一覧', \Config::get('term.timeline')));
		$this->template->post_footer = \View::forge('_parts/load_timelines');
		$this->template->content = \View::forge('_parts/list', array('list' => $list, 'is_next' => $is_next));
	}

	/**
	 * Timeline member
	 * 
	 * @access  public
	 * @params  integer
	 * @return  Response
	 */
	public function action_member($member_id = null)
	{
		$member_id = (int)$member_id;
		list($is_mypage, $member) = $this->check_auth_and_is_mypage($member_id);
		list($list, $is_next) = Site_Model::get_list(\Auth::check() ? $this->u->id : 0, $member->id, $is_mypage);

		$this->set_title_and_breadcrumbs(sprintf('%sの%s一覧', $is_mypage ? '自分' : $member->name.'さん', \Config::get('term.timeline')), null, $member);
		$this->template->post_footer = \View::forge('_parts/load_timelines');
		$this->template->content = \View::forge('_parts/list', array('member' => $member, 'list' => $list, 'is_next' => $is_next));
	}

	/**
	 * Mmeber home
	 * 
	 * @access  public
	 * @return  Response
	 */
	public function action_myhome()
	{
		$public_flag = \Model_MemberConfig::get_value($this->u->id, 'timeline_public_flag');
		list($list, $is_next) = Site_Model::get_list($this->u->id, 0, false, true);
		$this->template->post_header = \View::forge('member/_parts/myhome_header');
		$this->template->post_footer = \View::forge('member/_parts/myhome_footer');
		$this->set_title_and_breadcrumbs(\Config::get('term.myhome'));
		$this->template->content = \View::forge('member/myhome', array('list' => $list, 'is_next' => $is_next, 'public_flag' => $public_flag));
	}

	/**
	 * Timeline detail
	 * 
	 * @access  public
	 * @params  integer
	 * @return  Response
	 */
	public function action_detail($id = null)
	{
		if (!$timeline = Model_Timeline::check_authority($id)) throw new \HttpNotFoundException;
		$this->check_public_flag($timeline->public_flag, $timeline->member_id);
		$timeline_cache = Model_TimelineCache::get4timeline_id($id, true);

		$this->set_title_and_breadcrumbs(\Config::get('term.timeline').'詳細', null, $timeline->member, 'timeline', null, false, true);
		$this->template->post_footer = \View::forge('_parts/load_timelines');
		$this->template->content = \View::forge('_parts/article', array(
			'timeline_cache_id' => $timeline_cache->id,
			'timeline' => $timeline,
			'is_convert_nl2br' => true,
		));
	}

	/**
	 * Timeline delete
	 * 
	 * @access  public
	 * @params  integer
	 * @return  Response
	 */
	public function action_delete($id = null)
	{
		\Util_security::check_csrf();
		if (!$timeline = Model_Timeline::check_authority($id, $this->u->id))
		{
			throw new \HttpNotFoundException;
		}

		try
		{
			$id = (int)\Input::post('id');
			if (!$id || !$timeline = Model_Timeline::check_authority($id, $this->u->id))
			{
				throw new \HttpNotFoundException;
			}

			\DB::start_transaction();
			list($result, $deleted_files) = Site_Model::delete_timeline($timeline, $this->u->id);
			\DB::commit_transaction();
			if (!empty($deleted_files)) \Site_Upload::remove_files($deleted_files);

			\Session::set_flash('message', \Config::get('term.timeline').'を削除しました。');
			\Response::redirect('timeline/member');
		}
		catch(\FuelException $e)
		{
			if (\DB::in_transaction()) \DB::rollback_transaction();
			\Session::set_flash('error', $e->getMessage());
		}

		\Response::redirect('timeline/'.$id);
	}
}
