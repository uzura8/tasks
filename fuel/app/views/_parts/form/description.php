<?php if (!empty($descriptions)): ?>
<div class="form_description">
<ul>
<?php foreach ($descriptions as $description): ?>
	<li><?php echo $description; ?></li>
<?php endforeach; ?>
<?php if ($exists_required_fields): ?>
	<li><span class="required">*</span>の項目は入力必須</li>
<?php endif; ?>
</ul>
</div>
<?php endif; ?>
