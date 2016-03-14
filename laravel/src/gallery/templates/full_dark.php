<?php

if (isset($_SERVER['HTTP_ORIGIN'])) {
	header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
		header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

	exit(0);
}

?>
<div class='wrap' >

	<input type='hidden' id='current_url' value='<?php echo $_POST['image_url']; ?>' />
	<input type='hidden' id='current_gallery' value='<?php echo $_POST['gallery_id']; ?>' />
	<div class='onerow topRow' id="image_view">
		<div class='col12 tab col' >
			<div id='img_view_wrap_inner' class='thumb'>
				<img class='main' title='<?php echo $_POST['name']; ?>' src='<?php echo $_POST['image_url']; ?>'/><a class='next'>›</a><a class='prev'>‹</a>
			</div>
			<div class='onerow img_view_tools thumb'>
				<!-- &#x25BC; -->
				<div class='captionback'><div class='gencomment 
					<?php
					if(empty($_POST['caption'])){ echo "display_none"; } ?>
					'>“<span id='caption_line'><?php echo $_POST['caption']; ?></span>”</div><div class='photocaption ";
					<?php  
					if(empty($_POST['description'])){ echo "display_none"; } ?>
					'>".<?php echo $_POST['description'] ?>."</div>
				</div>
				<div class='fblike'><a href='javascript:void(0);'></a></div>
				<div id='thumb_tool' class='hide'>Thumbnails<label class='pin'></label></div>
			</div>
				<div class='onerow thumb_nav'>
					<div class='thumb_move_right'></div>
					<div class='thumb_move_left'></div>
					<div class='thumbnail_row'>
						<div class='img_wrap_link blank_thumb' ></div>
						<?php
						$i = 1;
						foreach ($_POST['more_images'] as $url => $image) {
							$thumb_url = !empty($image['thumb_url']) ? $image['thumb_url'] : $image['url'];
							echo "<a  class='img_wrap_link' data-url='".$image['url']."' data-info='".json_encode($image)."' href='javascript:void(0);'> <div class='img_wrap ".$image['active']."' style='background-image:url(".$thumb_url.")' ></div></a>";
						}
						?>
						<div class='img_wrap_link blank_thumb' ></div>
					</div>
				</div>
			</div>
			<div id='closeOverlay'>✕</div>
		</div>
	</div>