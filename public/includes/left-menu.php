<div id="menu">
	<ul class="left">
		<?php if(!is_loggedin()){ ?>
		<li><a class="btn" href="index.php?doc=login.php">Login</a></li>
		<li><a class="btn" href="index.php?doc=register.php">Register</a></li>
		<?php }else{ ?>
		<li><a class="btn" href="index.php?doc=logout.php">Logout</a></li>
		<?php } ?>
	</ul>
	<ul class="right">
		<li><a class="btn" href="index.php?doc=upload.php">Upload</a></li>
	</ul>
</div>
<div style="clear:both;"></div>
<div id="head">
	<form id="head-form">
		<input type="text" id="search-input" name="q" placeholder="Search">
	</form>
</div>
<div id="body">
	<ul>
		<li><a class="btn" href="index.php?doc=video-flow.php&showtype=0">Most Recent</a></li>
		<li><a class="btn" href="index.php?doc=video-flow.php&showtype=1">Most Viewed</a></li>
	</ul>
</div>