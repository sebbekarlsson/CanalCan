<?php 
	redirect(); 
	$videos = array();

	$xe = $db->prepare("SELECT * FROM videos WHERE userID=".$USER->data->id);
	$xe->execute();
	while(($row = $xe->fetch()) != false){
		$video = new Video($row['videoID']);
		$video->fetch_build();

		array_push($videos, $video);
	}
?>
<div id="content-head">
	<div class="text">
		<h2>Video Manager</h2>
		<p>Manage your videos</p>
	</div>
</div>
<div class="text">
	<?php

		for($i = 0; $i < count($videos); $i++){
			$video = $videos[$i];
			$thumb = str_replace(".mp4", ".png", $video->data->filename);
			?>
				<div class="video-plank">
					<div class="col icon-col">
						<img src="uploads/videos/thumbs/<?php echo $thumb; ?>">
					</div> 
					<div class="col content-col">
						<h3><?php echo $video->data->title; ?></h3>
						<p><?php echo $video->getShortDescription(); ?></p>
						<nav class="video-data">
							<form method="post">
								<input type="hidden" name="video_id" value="<?php echo $video->data->id; ?>">
								<ul class="right">
									<li><input class="btn" type="submit" name="video_delete" value="Delete"></li>
								</ul>
							</form>
							<?php

								if(isset($_POST['video_delete'])){
									$v = new Video($_POST['video_id']);
									$v->fetch_build();
									$v->delete();

									?>
										<script>window.location.href=window.location.href;</script>
									<?php
								}

							?>
						</nav>
					</div>

				</div>
			<?php
		}

	?>
</div>