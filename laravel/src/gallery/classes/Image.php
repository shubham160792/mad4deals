<?php
namespace classes;
use \PDO;

class Image{

	public $imageInfo;

	public function __construct(){
		
	}
	public function getTemplate(){

		$html = "
		<div class='wrap' > 
			<input type='hidden' id='current_url' value='".$this -> image_url."' />
			<input type='hidden' id='current_gallery' value='".$this -> gallery_id."' />
			<div class='onerow topRow' >
				<div class='col9 tab col' id='image_view'>
					<div id='img_view_wrap_inner' class='thumb'>
						<img class='main' title='".$this -> imageInfo['name']."' src='".\Config::ImageViewURL.$this -> imageInfo['path'].$this -> imageInfo['url'].'.'.$this -> imageInfo['extension']."' /><a class='next'>›</a><a class='prev'>‹</a>
					</div>
					<div class='onerow img_view_tools thumb'>
						<!-- &#x25BC; -->
						<div class='captionback'><div class='gencomment ";
						$this -> imageInfo['caption'] = '';
							if(empty($this -> imageInfo['caption'])){ $html .= "display_none"; }
							$html .="'>“<span id='caption_line'>".$this -> imageInfo['caption']."</span>”</div><div class='photocaption ";
							if(empty($this -> imageInfo['description'])){ $html .= "display_none"; }
							$html .= "'>".$this -> imageInfo['description']."</div>";
							$html .="</div>
							<div class='fblike'><a href='javascript:void(0);'><img src='".\Config::imagesPath."/fb_like.png'></a></div>
							<div id='thumb_tool' class='hide'>Thumbnails<label class='pin'></label></div>
						</div>
						<div class='onerow thumb_nav'>
							<div class='thumb_move_right'></div>
							<div class='thumb_move_left'></div>
							<div class='thumbnail_row'>
								<div class='img_wrap_link blank_thumb' ></div>

								";
								$i = 1;
								foreach ($this -> more_images as $url => $image) {
									$html .= "<a  class='img_wrap_link' data-url='".$image['url']."' data-info='".json_encode($image)."' href='javascript:void(0);'> <div class='img_wrap ".$image['active']."' style='background:url(".\Config::ImageViewURL.$image['path'].$image['url'].'_t.'.$image['extension'].")' ></div></a>";
								}
								$html .= "<div class='img_wrap_link blank_thumb' ></div></div>
							</div>
						</div>";
						$html .= "<div class='col3 tab col' id='rightSideBar'> 
						<div style='clear:both;margin-top:40px;'/>
						<!-- <div  class='fb_sh' style='float:left;margin-top:40px;'>
						<a href='javascript:void(0);' class='fb_share_btn' onclick='share_on_fb(\"test\",\"".\Config::ImageViewURL.$this -> imageInfo['path'].$this -> imageInfo['url'].'_t.'.$this -> imageInfo['extension']."\",\"test description\",\"\",\"test caption\",\"default\", \"\" , 0);'>
							<img style='' src='".\Config::imagesPath."/fb-share.png' > 
						</a>
					</div>
					<div class='fb_like' style='float:left;'>
						<div class='fb-like' width='100px'  data-href='".$_SERVER['SCRIPT_URI']." data-layout='button' data-action='like' data-show-faces='false' data-share='false'></div>   
					</div> -->
				</div>
			</div>
			<div id='closeOverlay'></div>
		</div>";
		return $html;

	}
	private function getMoreImagesFromGallery(){
		try{
			$this -> conn = new \utils\Connection();
			$this -> db = $this -> conn -> getDbConnection();
			if($this -> db){
				$images = array();
				$getImagesQuery = $this -> db -> prepare("SELECT * FROM `gl_images` WHERE `gallery_id` = :gallery_id");
				$getImagesQuery -> execute(array(':gallery_id' => $this -> gallery_id));
				$this -> db = null;
				while($row =  $getImagesQuery -> fetch(PDO::FETCH_ASSOC)){
					$row['active'] = $row['url'] == $this -> image_url ? 'active' : '';
					$images[$row['url']] = $row;
				}
				return $images;
			}
		}
		catch(\Exception $e){

		}
	}
	private function getImageDataFromURL(){
		try{
			$this -> conn = new \utils\Connection();
			$this -> db = $this -> conn -> getDbConnection();
			if($this -> db){
				$getImageQuery = $this -> db -> prepare("SELECT * FROM `gl_images` WHERE `url` = :url  LIMIT 1");
				$getImageQuery -> execute(array(':url' => $this -> image_url));
				$this -> db = null;
				$data = $getImageQuery -> fetch(PDO::FETCH_ASSOC);
				return $data;
			}
		}
		catch(\Exception $e){

		}
	}
	public function getImageData($image_url){

		$this -> image_url = $image_url;
		$this -> imageInfo = $this -> getImageDataFromURL();
		$this -> gallery_id = $this -> imageInfo['gallery_id'];
		if(empty($this -> imageInfo)){
			return null;
		}
		$this -> more_images = $this -> getMoreImagesFromGallery();
		
	}
}
?>
