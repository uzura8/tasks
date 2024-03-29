<?php if (IS_API): ?><?php echo Html::doctype('html5'); ?><body><?php endif; ?>
<?php if ($list): ?>
<div class="row">
<div id="main_container">
<?php foreach ($list as $album_image): ?>
	<div class="main_item" id="main_item_<?php echo $album_image->id; ?>">
		<div class="imgBox" id="imgBox_<?php echo $album_image->id ?>"<?php if (!IS_SP): ?> onmouseover="$('#btn_album_image_edit_<?php echo $album_image->id ?>').show();" onmouseout="$('#btn_album_image_edit_<?php echo $album_image->id ?>').hide();"<?php endif; ?>>
			<div><?php echo img($album_image->file, img_size('ai', 'M'), 'album/image/'.$album_image->id); ?></div>
<?php if (!empty($is_simple_view)): ?>
			<div class="description">
				<small><?php echo strim(\Album\Site_Util::get_album_image_display_name($album_image)); ?></small>
			</div>
<?php else: ?>
			<h5><?php echo Html::anchor('album/image/'.$album_image->id, strim(\Album\Site_Util::get_album_image_display_name($album_image), Config::get('album.articles.trim_width.name'))); ?></h5>
<?php endif; ?>

<?php if (empty($is_simple_view)): ?>
<?php if (!empty($is_member_page)): ?>
		<div class="date_box">
			<small><?php echo site_get_time($album_image->created_at) ?></small>
<?php $is_mycontents = Auth::check() && $u->id == $album_image->album->member_id; ?>
<?php echo render('_parts/public_flag_selecter', array(
	'model'          => 'album_image',
	'id'             => $album_image->id,
	'public_flag'    => $album_image->public_flag,
	'is_mycontents'  => $is_mycontents,
	'view_icon_only' => true,
	'disabled_to_update' => \Album\Site_Util::check_album_disabled_to_update($album_image->album->foreign_table),
)); ?>
		</div>
<?php else: ?>
<?php echo render('_parts/member_contents_box', array(
	'member'      => $album_image->album->member,
	'id'          => $album_image->id,
	'public_flag' => $album_image->public_flag,
	'public_flag_view_icon_only' => true,
	'public_flag_disabled_to_update' => \Album\Site_Util::check_album_disabled_to_update($album_image->album->foreign_table),
	'model'       => 'album_image',
	'date'        => array('datetime' => $album_image->album->created_at)
)); ?>
<?php endif; ?>
<?php endif; ?>

<?php if (empty($is_simple_view)): ?>
			<div class="article">
<?php if (empty($album)): ?>
				<div class="subinfo">
					<small><?php echo Config::get('term.album'); ?>: <?php echo Html::anchor('album/'.$album_image->album->id, strim($album_image->album->name, Config::get('album.articles.trim_width.subinfo'))); ?></small>
				</div>
<?php endif; ?>

<?php list($album_image_comment, $is_all_records, $all_comment_count) = \Album\Model_AlbumImageComment::get_comments($album_image->id, \Config::get('album.articles.comment.limit')); ?>
				<div class="comment_info">
					<small><span class="glyphicon glyphicon-comment"></span> <?php echo $all_comment_count; ?></small>
<?php if (Auth::check()): ?>
					<small><?php echo Html::anchor('album/image/'.$album_image->id.'?write_comment=1#comments', 'コメントする'); ?></small>
<?php endif; ?>
				</div>
			</div><!-- article -->
<?php endif; ?>

<?php if (Auth::check()): ?>
<?php
$menus = array();
if (!empty($is_setting_profile_image) && $album_image->album->member_id == $u->id)
{
	$menus[] = Html::anchor(
		'member/profile/image/set/'.$album_image->id.get_csrf_query_str(),
		sprintf('<i class="glyphicon glyphicon-setting"></i> %s写真に設定する', term('profile'))
	);
	$menus[] = Html::anchor(
		'#', '<i class="glyphicon glyphicon-trash"></i> '.term('form.delete'),
		array('onclick' => sprintf("delete_item('member/profile/image/delete/%d');return false;", $album_image->id))
	);
}
elseif (((!empty($album) && $album->member_id == $u->id) || (!empty($member) && $member->id == $u->id)))
{
	$menus[] = Html::anchor('album/image/edit/'.$album_image->id, '<i class="glyphicon glyphicon-pencil"></i> '.term('form.edit'));
	$menus[] = Html::anchor(
		'#', '<i class="glyphicon glyphicon-book"></i> カバーに指定',
		array('class' => 'link_album_image_set_cover', 'id' => 'link_album_image_set_cover_'.$album_image->id)
	);
	$menus[] = Html::anchor(
		'#', '<i class="glyphicon glyphicon-trash"></i> '.term('form.delete'),
		array('onclick' => sprintf("delete_item('album/image/api/delete.json', %d, '#main_item');return false;", $album_image->id))
	);
}
?>
<?php if ($menus): ?>
				<div class="btn_album_image_edit btn-group" id="btn_album_image_edit_<?php echo $album_image->id ?>">
					<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle"><i class="glyphicon glyphicon-edit"></i><i class="caret"></i></button>
					<ul class="dropdown-menu pull-right">
<?php foreach ($menus as $menu): ?>
						<li><?php echo $menu; ?></li>
<?php endforeach; ?>
					</ul>
				</div><!-- btn-group -->
<?php endif; ?>
<?php endif; ?>
		</div><!-- imgBox -->

<?php if (empty($is_simple_view )&& $album_image_comment): ?>
		<div class="list_album_image_comment">
<?php echo render('_parts/comment/list', array(
	'parent' => (!empty($album)) ? $album : $album_image->album,
	'comments' => $album_image_comment,
	'is_all_records' => $is_all_records,
	'uri_for_all_comments' => sprintf('album/image/%d?all_comment=1#comments', $album_image->id),
	'trim_width' => Config::get('album.articles.comment.trim_width'),
)); ?>
		</div>
<?php endif; ?>
	</div><!-- main_item -->
<?php endforeach; ?>
</div><!-- main_container -->
</div><!-- row -->
<?php endif; ?>

<?php if (empty($is_simple_view)): ?>
<nav id="page-nav">
<?php
$uri = sprintf('album/image/api/list.html?page=%d', $page + 1);
if (!empty($album))
{
	$uri .= '&album_id='.$album->id;
}
elseif (!empty($member))
{
	$uri .= '&member_id='.$member->id;
}
if (!empty($is_member_page))
{
	$uri .= '&is_member_page='.$is_member_page;
}
echo Html::anchor($uri, '');
?>
</nav>
<?php endif; ?>

<?php if (IS_API): ?></body></html><?php endif; ?>
