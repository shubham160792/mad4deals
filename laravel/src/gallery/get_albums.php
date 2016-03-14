<?php
error_reporting(E_ALL);
ini_set('display_errors',0);
require __DIR__.'/config/'.$_SERVER['environment'].'/config.php';
require 'utils/Connection.php';
require 'utils/ElasticSearchUtils.php';
$id = $_REQUEST['id'];
$catId = $_REQUEST['catId'];
$imageHostPath = \Config::imageCdnPath;

$obj = new \utils\ElasticSearchUtils();
$decoded = $obj->getAlbumsData($id,$catId);
$decoded = json_decode($decoded,true);
$galleries=array();
$count=0;
$galleries["id"] = $id;
$galleries["cat_id"] = $catId;
$galleries["pro_name"]=$decoded['hits']['hits'][0]['_source']['pro_name'];
for($i=0;$i<count($decoded['hits']['hits']) ; $i++)
{
    $counter = 0;
    foreach($decoded['hits']['hits'][$i]['_source']['all_items'] as $key => $value){
        if($counter == 6)break;
        $avaSizes = $value['avail_sizes'];
        $avaSizes = json_decode($avaSizes,true);
        $t_image = $imageHostPath.$value['image_url_path'].$value['image_url'].'_'.$avaSizes['Thumbnail'].'.'.$value['image_extension'];
        $galley[$decoded['hits']['hits'][$i]['_source']['gl_cat_name'][0]][] = $t_image;
        unset($t_image);
        $counter++;
    }
    $count++;
}
$galleries['albums'] = $galley;
$galleries["total"]=$count;
if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'json'){
    echo json_encode($galleries);
}
?>