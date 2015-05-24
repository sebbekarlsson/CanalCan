<?php

	$video = new Video($_GET['id']);
	$video->fetch_build();

?>
<div id="content-head">
	<div class="text">
		<h2><?php echo $video->data->title; ?></h2>
		<p><?php echo substr($video->data->description, 0, 60); ?></p>
	</div>
</div>
<div class="text">
	<?php var_dump($video); ?>
</div>