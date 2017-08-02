<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>posttoinstagram - WordPress Instagram Widget</title>

	<style>body { margin: 0; }</style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	
	<link href="<?php echo dirname(dirname($_SERVER['REQUEST_URI'])); ?>/assets/posttoinstagram/posttoinstagram-2.0.7.min.css" rel="stylesheet">	
	<script src="<?php echo dirname(dirname($_SERVER['REQUEST_URI'])); ?>/assets/posttoinstagram/posttoinstagram-2.0.7.min.js"></script>
</head>
<body>
	<div data-il
		 data-il-api="<?php echo dirname(dirname($_SERVER['REQUEST_URI'])); ?>/api/"
		 data-il-username="<?php echo !empty($_GET['username']) ? htmlentities($_GET['username']) : ""; ?>" 
		 data-il-hashtag="<?php echo !empty($_GET['hashtag']) ? htmlentities($_GET['hashtag']) : ""; ?>" 
		 data-il-lang="<?php echo !empty($_GET['lang']) ? htmlentities($_GET['lang']) : ""; ?>"
		 data-il-show-heading="<?php echo !empty($_GET['show_heading']) ? htmlentities($_GET['show_heading']) : ""; ?>" 
		 data-il-scroll="<?php echo !empty($_GET['scroll']) ? htmlentities($_GET['scroll']) : ""; ?>"
		 data-il-width="<?php echo !empty($_GET['width']) ? htmlentities($_GET['width']) : ""; ?>" 
		 data-il-height="<?php echo !empty($_GET['height']) ? htmlentities($_GET['height']) : ""; ?>"
		 data-il-image-size="<?php echo !empty($_GET['image_size']) ? htmlentities($_GET['image_size']) : ""; ?>" 
		 data-il-bg-color="<?php echo !empty($_GET['bg_color']) ? htmlentities($_GET['bg_color']) : ""; ?>" 
		 data-il-content-bg-color="<?php echo !empty($_GET['content_bg_color']) ? htmlentities($_GET['content_bg_color']) : ""; ?>" 
		 data-il-font-color="<?php echo !empty($_GET['font_color']) ? htmlentities($_GET['font_color']) : ""; ?>"
		 data-il-ban="<?php echo !empty($_GET['ban']) ? htmlentities($_GET['ban']) : ""; ?>"
		 data-il-cache-media-time="<?php echo !empty($_GET['cache_media_time']) ? htmlentities($_GET['cache_media_time']) : ""; ?>">
	</div>
</body>
</html>