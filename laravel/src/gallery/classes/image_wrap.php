<div class='wrap' > 
	<input type='hidden' id='current_url' value='<?php echo $_POST['image_url']; ?>' />
	<input type='hidden' id='current_gallery' value='<?php echo $_POST['gallery_id']; ?>' />
	<div class='onerow topRow' >
		<div class='col9 tab col' id='image_view'>
			<div id='img_view_wrap_inner' class='thumb'>
				<img class='main' title='<?php echo $_POST['name']; ?>' src='<?php echo $_POST['image_url']; ?>'/><a class='next'>›</a><a class='prev'>‹</a>
			</div>
			<div class='onerow img_view_tools thumb'>
				<!-- &#x25BC; -->
				<div class='captionback'><div class='gencomment 
					<?php
					$_POST['caption'] = '';
					if(empty($_POST['caption'])){ echo "display_none"; } ?>
					'>“<span id='caption_line'><?php echo $_POST['caption']; ?></span>”</div><div class='photocaption ";
					<?php 
					if(empty($_POST['description'])){ echo "display_none"; } ?>
					'>".<?php echo $_POST['description'] ?>."</div>
				</div>
				<div class='fblike'><a href='javascript:void(0);'><img src='/fb_like.png'></a></div>
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
						echo "<a  class='img_wrap_link' data-url='".$image['url']."' data-info='".json_encode($image)."' href='javascript:void(0);'> <div class='img_wrap ".$image['active']."' style='background-image:url(".$image['url'].")' ></div></a>";
					}
					?>
					<div class='img_wrap_link blank_thumb' ></div></div>
				</div>
			</div>
			<div class='col3 tab col' id='rightSideBar'> 
				<!-- <div style='clear:both;margin-top:40px;'/>
						 <div  class='fb_sh' style='float:left;margin-top:40px;'>
						<a href='javascript:void(0);' class='fb_share_btn' onclick='share_on_fb(\"test\",\"".\Config::ImageViewURL.$this -> imageInfo['path'].$this -> imageInfo['url'].'_t.'.$this -> imageInfo['extension']."\",\"test description\",\"\",\"test caption\",\"default\", \"\" , 0);'>
							<img style='' src='".\Config::imagesPath."/fb-share.png' > 
						</a>
					</div>
					<div class='fb_like' style='float:left;'>
						<div class='fb-like' width='100px'  data-href='".$_SERVER['SCRIPT_URI']." data-layout='button' data-action='like' data-show-faces='false' data-share='false'></div>   
					</div> 
				</div> -->
			</div>
			<div id='closeOverlay'></div>
		</div>
	</div>