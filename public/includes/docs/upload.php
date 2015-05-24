<?php redirect(); ?>
<div id="content-head">
	<div class="text">
		<h2>Upload</h2>
		<p>Upload a video</p>
	</div>
</div>
<div class="text">
	<form method="post" enctype="multipart/form-data">
		<input type="file" name="uploaded_file"><br>
		<input type="text" class="intext" name="video_title" placeholder="Title">
		<textarea rows="8" class="textarea" name="video_desc" placeholder="Description"></textarea>
		<input type="text" class="intext" name="video_tags" placeholder="Tags (separated by comma)">
		<input type="submit" class="btn" name="upload_upload" value="Upload">
	</form>
	<?php

		if(isset($_POST['upload_upload'])){
			$file = $_FILES['uploaded_file'];
			$video = new Video(-1);

			$tags = $_POST['video_tags'];
			if(substr_count($tags, ",") > 0){
				$tags = explode(",", $tags);
			}

			$videoData = array(
				"file" => $file,
				"title" => $_POST['video_title'],
				"description" => $_POST['video_desc'],
				"tags" => $tags,
				"userID" => $USER->data->id
			);

			$video->make($videoData);
			if(!$video->upload()){
				echo "<p>".$video->data->error."</p>";
			}else{
				echo "<p>Video has been uploaded</p>";
			}
		}

	?>
</div>