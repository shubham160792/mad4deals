<?php
error_reporting(E_ALL);
ini_set('display_errors',0);
require __DIR__.'/config/'.$_SERVER['environment'].'/config.php';
require 'utils/Connection.php';
require 'utils/ElasticSearchUtils.php';
require 'crons/CreateGallery.php';
$objCreateGallery = new crons\CreateGallery();
$id = $_REQUEST['id'];
$catId = $_REQUEST['catId'];
$urlInfo = parse_url($_SERVER["HTTP_REFERER"]);
$imageHostPath = \Config::imageCdnPath;

$path = $urlInfo['path'];
$path= ltrim($path, '/');
if($_REQUEST['canonical_url'] != ''){
    $path = $_REQUEST['canonical_url'];
}
$obj = new \utils\ElasticSearchUtils();
$decoded = $obj->getElasticData($id,$catId);
$getProductAttrFromRedis = $obj->getDataFromRedis($id,$catId);

$view360 = array();
$slideShare = array();
if(count($getProductAttrFromRedis) > 0){
    if(isset($getProductAttrFromRedis['view360_status']) && $getProductAttrFromRedis['view360_status'] == 1 && $path != ''){
        $view360Url = \Config::view360Url.'?url='.$path;
        $view360['id'] = '0';
        $view360['gallery_name'] = \Config::VIEW_360_CATEGORY;
        $view360['gl_cat_name'] = \Config::VIEW_360_CATEGORY;
        $view360['gl_cat_text'] = $obj->getDisplayTextFromCategory(\Config::VIEW_360_CATEGORY);
        $view360['gallery_do'] = 5;
        $view360['all_images'][] = array(
            'thumb'=>\Config::view360ImageURL,
            );
    }
    if(false && isset($getProductAttrFromRedis['slideshare_status']) && $getProductAttrFromRedis['slideshare_status'] == 1){
        $slideShare['id'] = '0';
        $slideShare['gallery_name'] = \Config::SLIDE_SHARE_CATEGORY;
        $slideShare['gl_cat_name'] = \Config::SLIDE_SHARE_CATEGORY;
        $slideShare['gl_cat_text'] = $obj->getDisplayTextFromCategory(\Config::SLIDE_SHARE_CATEGORY);
        $slideShare['gallery_do'] = 3;
        $slideShare['all_images'][] = array(
            'thumb'=>\Config::slideShareImageURL,
            );
    }
}
$decoded = json_decode($decoded,true);
$galleries=array();
$availableGalleries = array();
$count=0;
$galleries["id"] = $id;
$galleries["pro_name"]=$decoded['hits']['hits'][0]['_source']['pro_name'];
$galleries["pro_cat_id"] = $catId;
$galleries["galleries"]=null;

$slide360AddedFlag = true;
for($i=0;$i<count($decoded['hits']['hits']) ; $i++)
{
    $source = $decoded['hits']['hits'][$i]['_source'];
    $gallery["id"] = $source['gallery_id'];
    $gallery["gallery_name"] = $source['gallery_name'];
    $gallery["gl_cat_name"] = $source['gl_cat_name'][0];
    $gallery["gl_cat_text"] = $obj->getDisplayTextFromCategory($source['gl_cat_name'][0]);
    array_push($availableGalleries,$gallery["gl_cat_name"]);

    switch($gallery["gl_cat_name"]) {
        case 'Camera Samples' : $displayOrder = 1;
            break;
        case 'Screenshots' : $displayOrder = 2;
            break;
        case 'Slide Share' : $displayOrder = 3;
            break;
        case 'Videos' : $displayOrder = 4;
            break;
        case 'Design' : $displayOrder = 0;
            break;
    }
    $gallery["gallery_do"] = $displayOrder;
    $items = $source['all_items'];
    foreach($items as $key => $value){

        if ($value['type'] == \Config::IMAGE_TYPE){
            $avaSizes = $value['avail_sizes'];
            $avaSizes = json_decode($avaSizes, true);
            $t_image = $imageHostPath . $value['image_url_path'] . $value['image_url'] . '_' . $avaSizes['Thumbnail'] . '.' . $value['image_extension'];
            $temp = array('thumb' => $t_image);
            $gallery['all_images'][] = $temp;
            unset($temp);
        }
        elseif ($value['type'] == \Config::YOUTUBE_TYPE) {
            $videoId = explode('?v=',$value['image_url']);
            $videoId = explode('&',$videoId[1]);
            $t_image = 'http://img.youtube.com/vi/'.$videoId[0].'/default.jpg';
            $temp = array('thumb'=>$t_image);
            $gallery['all_images'][] = $temp;
            unset($temp);
        }
        elseif ($value['type'] == \Config::SLIDESHARE_TYPE) {
            $t_image = \Config::slideShareImageURL;
            $temp = array('thumb'=>$t_image);
            $gallery['all_images'][] = $temp;
            unset($temp);
        }
    }
    /* $videos = $source['all_videos'];
     foreach($videos as $key => $value){
         $videoId = explode('?v=',$value['url']);
         $videoId = explode('&',$videoId[1]);
         $t_image = 'http://img.youtube.com/vi/'.$videoId[0].'/default.jpg';
         $s_image = $t_image;
         $m_image = $value['url'];
         $l_image = $value['url'];
         $caption = $value['caption'];
         $temp = array('thumb'=>$t_image,'small'=>$s_image,'medium'=>$m_image,'large'=>$l_image,'caption'=>$caption);
         $gallery['all_videos'][] = $temp;
         unset($temp);
     }*/
    $galleries['galleries'][] = $gallery;
    if($i == 0 && $slide360AddedFlag){
        $slide360AddedFlag = false;
        if(count($view360) > 0){
            $galleries['galleries'][] = $view360;
            $count++;
        }
        if(count($slideShare) > 0){
            $galleries['galleries'][] = $slideShare;
            $count++;
        }
    }
    $count++;
    unset($gallery);
}

$design = array();
$designCat = ucwords(\Config::DESIGN_CATEGORY);
if (!in_array($designCat, $availableGalleries)) {
    switch($catId) {
        case 553 : $ptype = 'mobile';
            break;
        case 579 : $ptype = 'tablet';
            break;
        case 24 : $ptype = 'laptop';
            break;
        case 110 : $ptype = 'television';
            break;
        case 555 : $ptype = 'camera';
            break;
        case 585 : $ptype = 'mobile_accessories';
            break;
    }

    $data = $objCreateGallery->getCurlData($id,$ptype);

    $data =json_decode($data,true);
    $galleries["pro_name"] = $data[$id]['prod_name'];
    $design['id'] = '540';
    $design['gallery_name'] = $data[$id]['prod_name']. ' - ' .$designCat;
    $design['gl_cat_name'] = $designCat;
    $design['gl_cat_text'] = $obj->getDisplayTextFromCategory($designCat);
    $designGalleryImages = $data[$id]['all_images'];
    for ($i =0; $i<count($designGalleryImages['p_thumbimg_path']); $i++) {
        $designImages = array();
        foreach ($designGalleryImages as $images) {
            array_push($designImages,$images[$i]);
        }
        $caption = '';
        $temp = array('thumb' => $designImages[0]);
        $design['all_images'][] = $temp;
        unset($temp);

    }
    if(count($design['all_images']) > 0){
        if(count($galleries['galleries']) > 0){
            array_unshift( $galleries['galleries'], $design);
        }else{
            $galleries['galleries'][] = $design;
        }
        $count++;
    }
}
$galleries["total"]=$count;
if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'json'){
    echo json_encode($galleries);
}

?>