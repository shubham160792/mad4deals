<?php
define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);
define('ERROR_REPORTING', 0);
$dirPath = isset($_SERVER['environment']) ? '../' : '/';
define('dirPath', $dirPath);
require_once  ROOT.$dirPath.'classMap.php';

class Config{
	const urlPath = 'http://origin.img.91mobiles.com/image_gallery/gallery/';
	const CssJSUrl = 'http://origin.img.91mobiles.com/image_gallery/gallery/';
	const ImageViewURL = 'http://origin.img.91mobiles.com/gallery_images_uploads/';
	const imagesPath = 'http://origin.img.91mobiles.com/image_gallery/gallery/images/';
	const createGalleryPath = 'http://origin.img.91mobiles.com/image_gallery/public/gallery';
	const uploadImagesPath = 'http://origin.img.91mobiles.com/image_gallery/public/image';

	const dataLayerAPI = 'http://180.179.213.68:8099/api.php?pid=#pro_id&ptype=#pro_type&type=getDetailPageData';
	const DATALAYER_UPDATE_API = 'http://180.179.213.68:8099/datalayer_feed.php?pids=#pro_id&cat_id=#cat_id';

	const elasticIndexHost = 'http://172.16.164.102:9200/';

	const imageCdnPath = 'http://www.91-img.com/gallery_images_uploads/';
	const view360Url = 'http://www.91mobiles.com/view360_Iframe.php';
	const view360ImageURL = 'http://origin.img.91mobiles.com/image_gallery/gallery/images/icon_360_t.png';
	const slideShareImageURL = 'http://origin.img.91mobiles.com/image_gallery/gallery/images/slide_share1.png';
	const viewMoreImage = 'http://origin.img.91mobiles.com/image_gallery/gallery/images/view_more.jpg';

	const DESIGN_CATEGORY = 'design';
	const CAMERA_SAMPLES_CATEGORY = 'camera samples';
	const PARTIAL_CAMERA_CATEGORY = 'camera';
	const SCREEN_SHOTS_CATEGORY = 'screenshots';
	const BENCHMARK_CATEGORY = 'benchmarks';
	const ACCESSORIES_CATEGORY = 'accessories';
	const SLIDE_SHARE_CATEGORY = 'Slide Share';
	const VIEW_360_CATEGORY = 'View 360';

	const VIDEOS_CATEGORY = 'videos';

    const IMAGE_TYPE = 1;
    const YOUTUBE_TYPE = 2;
    const SLIDESHARE_TYPE = 3;
    const VIEW360_TYPE = 4;

	const ELASTIC_INDEX_NAME = 'image_gallery';
	const ELASTIC_INDEX_TYPE = 'gl';

	static $connection = array(
		'redis' => array(
			'ip' => '127.0.0.1',
			'port' => 6379
		),

		'_mysql_mobiles' => array(
			'username' => 'appuser',
			'password' => 'mysql18032014!',
			'host' => '172.16.164.107',
			'port' => '3306',
			'db' => 'bazaar_eprice'
        ),
		'_mysql_111' => array(
			'username' => 'appuser',
			'password' => 'mysql18032014!',
			'host' => '172.16.164.111',
			'port' => '3306',
			'db' => 'mobiles_mobiles'));
}


function setErrorReporting(){
	if(ERROR_REPORTING  == 1){
		ini_set("display_errors", "ON");
		error_reporting(E_ALL);
	}
	else{
		ini_set("display_errors", "OFF");
		error_reporting(0);
	}
}
function __autoload($_className)
{
	// global $_CLASSMAP;

	$_nameSpace = substr($_className, 0, strrpos($_className, '\\'));
	$_nameSpace = str_replace('\\', DIRECTORY_SEPARATOR, $_className);

	// if(isset($_CLASSMAP[$_nameSpace])){
	// 	$_classPath = $_CLASSMAP[$_nameSpace];
	// 	if(!empty($_classPath)){
	// 		if(is_readable($_classPath)) {
	// 			require_once $_classPath;
	// 		}
	// 	}
	// }
	// else{
	$dirPath = isset($_SERVER['environment']) ? '../../' : '../';
	$_classPath = ROOT .$dirPath. str_replace('\\', '/', $_nameSpace) . '.php';
	if(is_readable($_classPath)) {
		require_once $_classPath;
	}
	// }
}
setErrorReporting();

?>
