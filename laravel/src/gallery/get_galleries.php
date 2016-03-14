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
        $view360['all_videos'][] = array(
            'thumb'=>\Config::view360ImageURL,
            'small'=>\Config::view360ImageURL,
            'medium'=>$view360Url,
            'large'=>$view360Url,
            'caption'=>"",
            'type'=>4);
    }
    if(false && isset($getProductAttrFromRedis['slideshare_status']) && $getProductAttrFromRedis['slideshare_status'] == 1){
        $slideShare['id'] = '0';
        $slideShare['gallery_name'] = \Config::SLIDE_SHARE_CATEGORY;
        $slideShare['gl_cat_name'] = \Config::SLIDE_SHARE_CATEGORY;
        $slideShare['gl_cat_text'] = $obj->getDisplayTextFromCategory(\Config::SLIDE_SHARE_CATEGORY);
        $slideShare['all_videos'][] = array(
            'thumb'=>\Config::slideShareImageURL,
            'small'=>\Config::slideShareImageURL,
            'medium'=>$getProductAttrFromRedis['slideshare_url'],
            'large'=>$getProductAttrFromRedis['slideshare_url'],
            'caption'=>"",
            'type'=>\Config::SLIDESHARE_TYPE);
    }
}
$decoded = json_decode($decoded,true);
$galleries=array();
$count=0;
$galleryName = '';
$galleries["id"] = $id;
$galleries["pro_name"] = $decoded['hits']['hits'][0]['_source']['pro_name'];
$galleries["pro_cat_id"] = $catId;
$galleries["galleries"]=null;
$availableGalleries = array();
$slide360AddedFlag = true;

for($i=0;$i<count($decoded['hits']['hits']) ; $i++)
{
    $source = $decoded['hits']['hits'][$i]['_source'];
    $gallery["id"] = $source['gallery_id'];
    $gallery["gallery_name"] = $source['gallery_name'];
    $gallery["gl_cat_name"] = $source['gl_cat_name'][0];
    $galleryName = $source['gallery_name'];
    array_push($availableGalleries,$gallery["gl_cat_name"]);
    $gallery["gl_cat_text"] = $obj->getDisplayTextFromCategory($source['gl_cat_name'][0]);
    $items = $source['all_items'];
    foreach($items as $key => $value){

        if ($value['type'] == \Config::IMAGE_TYPE){
            $avaSizes = $value['avail_sizes'];
            $avaSizes = json_decode($avaSizes, true);
            $t_image = $imageHostPath . $value['image_url_path'] . $value['image_url'] . '_' . $avaSizes['Thumbnail'] . '.' . $value['image_extension'];
            $s_image = $imageHostPath . $value['image_url_path'] . $value['image_url'] . '_' . $avaSizes['Large Square'] . '.' . $value['image_extension'];
            $m_image = $imageHostPath . $value['image_url_path'] . $value['image_url'] . '_' . $avaSizes['Large Square'] . '.' . $value['image_extension'];
            $l_image = $imageHostPath . $value['image_url_path'] . $value['image_url'] . '.' . $value['image_extension'];
            $caption = $value['caption'];
            $temp = array('thumb' => $t_image, 'small' => $s_image, 'medium' => $m_image, 'large' => $l_image, 'caption' => $caption, 'type' => $value['type']);
            $gallery['all_images'][] = $temp;
            unset($temp);
        }
        elseif ($value['type'] == \Config::YOUTUBE_TYPE) {
            $videoId = explode('?v=',$value['image_url']);
            $videoId = explode('&',$videoId[1]);
            $t_image = 'http://img.youtube.com/vi/'.$videoId[0].'/default.jpg';
            $s_image = $t_image;
            $m_image = $value['image_url'];
            $l_image = $value['image_url'];
            $caption = $value['caption'];
            $temp = array('thumb'=>$t_image,'small'=>$s_image,'medium'=>$m_image,'large'=>$l_image,'caption'=>$caption, 'type' => $value['type']);
            $gallery['all_videos'][] = $temp;
            unset($temp);
        }
        elseif ($value['type'] == \Config::SLIDESHARE_TYPE) {
            $t_image = \Config::slideShareImageURL;
            $s_image = \Config::slideShareImageURL;
            $m_image = $value['image_url'];
            $l_image = $value['image_url'];
            $caption = $value['caption'];
            $temp = array('thumb'=>$t_image,'small'=>$s_image,'medium'=>$m_image,'large'=>$l_image,'caption'=>$caption, 'type' => $value['type']);
            $gallery['all_videos'][] = $temp;
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
    }
    $data = $objCreateGallery->getCurlData($id,$ptype);
    $data =json_decode($data,true);
    $galleries["pro_name"] = $data[$id]['prod_name'];
    $design['id'] = '540';
    $design['gallery_name'] = $data[$id]['prod_name']. ' - ' .$designCat;
    $design['gl_cat_name'] = $designCat;
    $design['gl_cat_text'] = $obj->getDisplayTextFromCategory($designCat);
    $designGalleryImages = $data[$id]['all_images'];
    $largeImgIndex = $data[$id]['hres_img_count'] > 0 ? 4 : 3;
    for ($i =0; $i<count($designGalleryImages['p_thumbimg_path']); $i++) {
        $designImages = array();
        foreach ($designGalleryImages as $images) {
            array_push($designImages,$images[$i]);
        }
        $caption = '';
        $temp = array(
            'thumb' => $designImages[0],
            'small' => $designImages[1],
            'medium' => $designImages[2],
            'large' => $designImages[$largeImgIndex],
            'caption' => $caption,
            'type' => \Config::IMAGE_TYPE
        );
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
if(isset($_REQUEST['brand'])){
    //$decoded = $obj->getMostPopularProduct($_REQUEST['brand'],$id,$catId);
}else{
    //$decoded = $obj->getMostPopularProduct($decoded['hits']['hits'][0]['_source']['pro_brand'],$id,$catId);
}
//$decoded = json_decode($decoded,true);
/*$decoded = array();
for($i=0;$i<count($decoded['hits']['hits']) ; $i++)
{
    $gallery["gallery_id"] = $decoded['hits']['hits'][$i]['_source']['gallery_id'];
    $gallery["pro_id"] = $decoded['hits']['hits'][$i]['_source']['pro_id'];
    $gallery["pro_cat_id"] = $decoded['hits']['hits'][$i]['_source']['pro_cat_id'];
    $gallery["gallery_name"] = $decoded['hits']['hits'][$i]['_source']['gallery_name'];
    $gallery["gl_cat_name"] = 'Most Popular';
    foreach($decoded['hits']['hits'][$i]['_source']['all_items'] as $key => $value){
        if ($value['type'] == 1) {
            $avaSizes = $value['avail_sizes'];
            $avaSizes = json_decode($avaSizes, true);
            $t_image = $imageHostPath . $value['image_url_path'] . $value['image_url'] . '_' . $avaSizes['Thumbnail'] . '.' . $value['image_extension'];
            $s_image = $imageHostPath . $value['image_url_path'] . $value['image_url'] . '_' . $avaSizes['Large Square'] . '.' . $value['image_extension'];
            $m_image = $imageHostPath . $value['image_url_path'] . $value['image_url'] . '_' . $avaSizes['Large Square'] . '.' . $value['image_extension'];
            $l_image = $imageHostPath . $value['image_url_path'] . $value['image_url'] . '.' . $value['image_extension'];
            $caption = $value['caption'];
            $temp = array('thumb' => $t_image, 'small' => $s_image, 'medium' => $m_image, 'large' => $l_image, 'caption' => $caption, 'type' => 'image');
            $gallery['all_items'][] = $temp;
            unset($temp);
        }
        elseif ($value['type'] == 2){
            $videoId = explode('?v=',$value['url']);
            $videoId = explode('&',$videoId[1]);
            $t_image = 'http://img.youtube.com/vi/'.$videoId[0].'/default.jpg';
            $s_image = $t_image;
            $m_image = $value['url'];
            $l_image = $value['url'];
            $caption = $value['caption'];
            $temp = array('thumb'=>$t_image,'small'=>$s_image,'medium'=>$m_image,'large'=>$l_image,'caption'=>$caption, 'type' => 'youtube');
            $gallery['all_items'][] = $temp;
            unset($temp);
        }
        elseif ($value['type'] == 3){

        }
    }
    foreach($decoded['hits']['hits'][$i]['_source']['all_items'] as $key => $value){
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
    }
    $galleries['mostpopular'][] = $gallery;
    $count++;
    unset($gallery);
}
function array_insert_string_keys($src,$ins,$pos) {

    $counter=1;
    foreach($src as $key=>$s){
        if($key==$pos){
            break;
        }
        $counter++;
    }

    $array_head = array_slice($src,0,$counter);
    $array_tail = array_slice($src,$counter);

    $src = array_merge($array_head, $ins);
    $src = array_merge($src, $array_tail);

    return($src);
}*/
if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'json'){
    echo json_encode($galleries);
}
?>