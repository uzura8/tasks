<?php
list($list, $is_all_records, $all_comment_count) = \Timeline\Site_Model::get_comments($timeline->type, $timeline->id, $timeline->foreign_table, $timeline->foreign_id);
$comment = array(
	'list' => $list,
	'is_all_records' => $is_all_records,
	'all_comment_count' => $all_comment_count,
	'parent_obj' => $timeline,
);
?>
<div class="timelineBox" id="timelineBox_<?php echo $timeline_cache_id; ?>" data-id="<?php echo $timeline->id; ?>">

<?php
$comment_get_uri = \Timeline\Site_Util::get_comment_api_uri($timeline->type, $timeline->foreign_table, false, $timeline->id, $timeline->foreign_id);
$data = array(
	'member'  => $timeline->member,
	'size'    => 'M',
	'date'    => array('datetime' => $timeline->created_at),
	'content' => \Timeline\Site_Util::get_timeline_body($timeline->type, $timeline->body),
	'images'  => \Timeline\Site_Util::get_timeline_images($timeline->type, $timeline->foreign_table, $timeline->foreign_id),
	'comment' => $comment,
	'model'   => 'timeline',
	'id'      => $timeline->id,
	'public_flag' => $timeline->public_flag,
	'public_flag_view_icon_only' => IS_SP,
	'list_more_box_attrs' => array('data-get_uri' => $comment_get_uri),
	'post_comment_button_attrs' => array(
		'data-get_uri' => $comment_get_uri,
		'data-post_parent_id' => \Timeline\Site_Util::get_comment_parent_id($timeline->type, $timeline->foreign_table, $timeline->id, $timeline->foreign_id),
		'data-post_uri' => \Timeline\Site_Util::get_comment_api_uri($timeline->type, $timeline->foreign_table, true),
	),
);
if (!empty($is_convert_nl2br)) $data['is_convert_nl2br'] = $is_convert_nl2br;
if (!empty($trim_width)) $data['trim_width'] = $trim_width;
if (!empty($truncate_lines))
{
	$data['truncate_lines'] = $truncate_lines;
	$data['read_more_uri']  = 'timeline/'.$timeline->id;
}
$view_member_contents_box = View::forge('_parts/member_contents_box', $data);

$quote_article = \Timeline\Site_Util::get_quote_article($timeline->type, $timeline->foreign_table, $timeline->foreign_id);
if ($quote_article) $view_member_contents_box->set_safe('quote_article', $quote_article);

echo $view_member_contents_box->render();

if (Auth::check() && $timeline->member_id == $u->id && \Timeline\Site_Util::check_is_editable($timeline->type))
{
	$attr = array(
		'id'      => 'btn_timeline_delete_'.$timeline_cache_id,
		'data-id' => $timeline->id,
	);
	if (!empty($delete_uri)) $attr['data-uri'] = $delete_uri;
	echo anchor_button('#', 'ls-icon-delete', '', 'boxBtn btn_timeline_delete', $attr, true, IS_SP);
}
?>
</div><!-- timelineBox -->