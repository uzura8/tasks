<?php foreach ($files as $file): ?>
<?php echo render('filetmp/_parts/upload_image', array('file' => $file, 'thumbnail_size' => $thumbnail_size)); ?>
<?php endforeach; ?>
