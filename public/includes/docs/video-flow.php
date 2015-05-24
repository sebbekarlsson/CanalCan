<?php

	$videos = array();
	$xe = $db->prepare("SELECT * FROM videos");
	$xe->execute();
	while(($row = $xe->fetch()) != false){
		$video = new Video($row['videoID']);
		$video->fetch_build();
		array_push($videos, $video);
	}

?>
<div id="content-head">
	<div class="text">
		<h2>The frontpage</h2>
		<p>Videos with the most upvotes</p>
	</div>
</div>
<div class="video-flow">
	<?php for($i = 0; $i < count($videos); $i++){
		$video = $videos[$i];
		$user = new User($video->data->userID);
		$user->fetch_build();
		$thumb = str_replace(".mp4", ".png", $video->data->filename);
	?>
	<a class="video-href" href="index.php?doc=video.php&id=<?php echo $video->data->id; ?>"><div class="video-item">
		<img src="uploads/videos/thumbs/<?php echo $thumb; ?>">
		<div class="text">
			<strong><?php echo $video->data->title; ?></strong>
			<p><?php echo $video->getShortDescription(); ?></p>
		</div>
		<div class="video-data">
			<ul class="left">
				<li><?php echo $user->data->name; ?></li>
			</ul>
			<ul class="right">
				<li><?php echo $video->data->views; ?> views</li>
			</ul>
		</div>
	</div></a>
	<?php } ?>
</div>