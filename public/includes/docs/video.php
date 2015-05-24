<?php

	$video = new Video($_GET['id']);
	$video->fetch_build();
	$thumb = str_replace(".mp4", ".png", $video->data->filename);

?>
<div id="content-head">
	<!-- Chang URLs to wherever Video.js files will be hosted -->
  <link href="video-js/video-js.css" rel="stylesheet" type="text/css">
  <!-- video.js must be in the <head> for older IEs to work. -->
  <script src="video-js/video.js"></script>

  <!-- Unless using the CDN hosted version, update the URL to the Flash SWF -->
  <script>
    videojs.options.flash.swf = "video-js/video-js.swf";
  </script>

	<div class="text">
		<h2><?php echo $video->data->title; ?></h2>
	</div>
</div>
<div class="text">
	<div class="video-area">
		<video id="example_video_1" class="video-js vjs-default-skin" controls preload="none" width="100%" height="420"
	      poster="uploads/videos/thumbs/<?php echo $thumb; ?>"
	      data-setup="{}">
	    <source src="uploads/videos/<?php echo $video->data->filename; ?>" type='video/mp4' />
	    <!--<source src="http://video-js.zencoder.com/oceans-clip.webm" type='video/webm' />
	    <!--<source src="http://video-js.zencoder.com/oceans-clip.ogv" type='video/ogg' />
	    <!--<track kind="captions" src="demo.captions.vtt" srclang="en" label="English"></track><!-- Tracks need an ending tag thanks to IE9 -->
	    <!--<track kind="subtitles" src="demo.captions.vtt" srclang="en" label="English"></track><!-- Tracks need an ending tag thanks to IE9 -->
	    <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
	  </video>
	  <nav id="video-data">
	  	<ul class="right">
	  		<li><?php echo $video->data->views; ?> views</li>
	  	</ul>
	  </nav>
	  <div class="text">
	  	<div id="description">
	 	 <p><?php echo $video->data->description; ?></p>
	 	</div>
	  </div>
	</div>
</div>