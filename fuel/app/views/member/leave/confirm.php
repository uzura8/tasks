<?php echo alert(sprintf('本当に%sしますか？', term('member_leave')), 'danger'); ?>
<div class="well">
<?php echo form_open(false, false, array('action' => 'member/leave')); ?>
<?php echo Form::hidden('password', $input['password'], array('dont_prep' => true)); ?>
	<?php echo form_button('<i class="glyphicon glyphicon-arrow-left"></i> 戻る', 'submit', 'submit_back', array('class' => 'btn btn-default')); ?>
<?php echo form_close(); ?>

<?php echo form_open(false, false, array('action' => 'member/leave/delete')); ?>
<?php echo Form::hidden(Config::get('security.csrf_token_key'), Util_security::get_csrf()); ?>
<?php echo Form::hidden('password', $input['password'], array('dont_prep' => true)); ?>
	<?php echo form_button(term('member_leave').'する', 'submit', 'submit', array('class' => 'btn btn-default btn-danger')); ?>
<?php echo form_close(); ?>
</div>
