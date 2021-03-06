<?php
	
	$showtype = $_GET['showtype'];
	$videos = array();
	$flowtitle = "<h2>Videos</h2>";

	if($showtype == 0){
		$flowtitle = "<h2>Recent Videos</h2>
		<p>Videos that were recently uploaded</p>";
		
		$xe = $db->prepare("SELECT * FROM videos ORDER BY videoDate DESC LIMIT 20 OFFSET 0");
		$xe->execute();
		while(($row = $xe->fetch()) != false){
			$video = new Video($row['videoID']);
			$video->fetch_build();
			array_push($videos, $video);
			echo $row['v'];
		}

	}else if($showtype == 1){
		$flowtitle = "<h2>Top Videos</h2>
		<p>Videos ordered by the most views</p>";

		$xe = $db->prepare("SELECT * FROM videos ORDER BY (SELECT count(*) FROM videoViews WHERE videoID=videos.videoID) DESC LIMIT 20 OFFSET 0");
		$xe->execute();
		while(($row = $xe->fetch()) != false){
			$video = new Video($row['videoID']);
			$video->fetch_build();
			array_push($videos, $video);
			echo $row['v'];
		}
	}

?>
<div id="content-head">
	<div class="text">
		<?php echo $flowtitle; ?>
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
				<li><?php echo count($video->getViewers()); ?> views</li>
			</ul>
		</div>
	</div></a>
	<?php } ?>
</div>