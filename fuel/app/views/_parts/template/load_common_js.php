<?php echo Asset::js('jquery-2.0.3.min.js');?>
<?php echo Asset::js('bootstrap.min.js');?>
<?php echo Asset::js('bootstrap-modal.js');?>
<?php //echo Asset::js('bootstrap-modalmanager.js');?>
<?php echo Asset::js('apprise-1.5.min.js');?>
<?php echo Asset::js('jquery.autogrow-textarea.js');?>
<?php echo Asset::js('jquery.jgrowl.min.js');?>
<?php echo Asset::js('moment.min.js');?>
<?php echo Asset::js('moment.lang_ja.js');?>
<?php echo Asset::js('livestamp.min.js');?>
<?php echo Asset::js('js-url/js-url.min.js');?>

<?php echo Asset::js('util.js');?>
<?php echo Asset::js('site.js');?>
<script>
function get_uid() {return <?php echo Auth::check() ? $u->id : 0; ?>;}
</script>
