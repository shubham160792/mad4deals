<?php

namespace crons;

 use \PDO;
 use utils\ElasticSearchUtils;
 use utils\Connection;

class MySQLToElastic{
    private $db;
    private $elasticSearchUtils;
    private $isSuccessful;
    
     function __construct($recreateIndex = false,$batchSize = 100){
         $this->elasticSearchUtils = new ElasticSearchUtils();
         $this -> conn = new Connection();
         $this -> db = $this -> conn -> getDbConnection();
         $this -> db111 = $this -> conn -> getDbConnection111();
         $this->batchSize = $batchSize;
         $this->isSuccessful=true;
     }
    
    
     private function getQuery($sqlConnection,$start,$batchSize,$mob_id, $cat){
         if($mob_id == ''){
             $pro_cond = '';
         }else{
             $pro_cond = " product_id = $mob_id AND ";
         }
         if($cat == ''){
             $pro_cond .= '';
         }else{
             $pro_cond .= "  cat_name = '$cat' AND ";
         }
        $sql = "SELECT * FROM gl_gallery as gl ,
                (SELECT map.gallery_id as gallery_id ,GROUP_CONCAT(c.name) as cat_name
                FROM gl_category_gallery as map , gl_category as c WHERE map.category_id = c.id GROUP BY gallery_id) as cat
                WHERE $pro_cond gl.id = cat.gallery_id ORDER BY `product_id` DESC limit $start,$batchSize";
         return $sql;
    }
    public function getAllItems($gallery_id){
        $img_sql = 'SELECT *  FROM `gl_items` WHERE `gallery_id` = '.$gallery_id.' AND active = 1 ORDER BY `display_order` ASC ';
        $result  = $this->db-> prepare( $img_sql );
        $result->execute();
        $data = array();
        while($row = $result -> fetch( PDO::FETCH_ASSOC )){
            $data[$row['display_order']] =  array('image_name'=>$row['name'],
                'image_url_path'=>$row['path'],
                'image_url'=>$row['url'],
                'image_extension'=>$row['extension'],
                'available_sizes'=>$row['available_sizes'],
                'avail_sizes'=>$row['avail_sizes'],
                'caption'=>$row['caption'],
                'type'=> intval($row['type']),
                'author'=>$row['author'],
                'views'=>intval($row['views']),
                'active'=>intval($row['active']),
                'display_order'=>intval($row['display_order']),
                'meta_tags'=>$row['meta_tags'],
                'attributes'=>$row['attributes'],
            );
        }
        return $data;
    }
    public function getAllImages($gallery_id){
        $img_sql = 'SELECT *  FROM `gl_images` WHERE `gallery_id` = '.$gallery_id.' AND active = 1 ORDER BY `display_order` ASC ';
        $result  = $this->db-> prepare( $img_sql );
        $result->execute();
        $data = array();
        while($row = $result -> fetch( PDO::FETCH_ASSOC )){
            $data[$row['display_order']] =  array('image_name'=>$row['name'],
                                            'image_url_path'=>$row['path'],
                                            'image_url'=>$row['url'],
                                            'image_extension'=>$row['extension'],
                                            'available_sizes'=>$row['available_sizes'],
                                            'avail_sizes'=>$row['avail_sizes'],
                                            'caption'=>$row['caption'],
                                            'author'=>$row['author'],
                                            'views'=>intval($row['views']),
                                            'active'=>intval($row['active']),
                                            'display_order'=>intval($row['display_order']),
                                            'meta_tags'=>$row['meta_tags'],
                                            'attributes'=>$row['attributes'],
                                            );
        }
        return $data;
    }
    public function getAllVideos($gallery_id){
        $sql = 'SELECT *  FROM `gl_videos` WHERE `gallery_id` = '.$gallery_id.' AND active = 1 ORDER BY `display_order` ASC ';
        $result  = $this->db-> prepare( $sql );
        $result->execute();
        $data = array();
        while($row = $result -> fetch( PDO::FETCH_ASSOC )){
            $data[$row['display_order']] =  array(
                'url'=>$row['url'],
                'caption'=>$row['caption'],
                'author'=>$row['author'],
                'views'=>intval($row['views']),
                'active'=>intval($row['active']),
                'display_order'=>intval($row['display_order']),
                'meta_tags'=>$row['meta_tags'],
                'attributes'=>$row['attributes'],
            );
        }
        return $data;
    }
    public function getCategoryBias($cat_name){
        $catBias = 1000;
        if(strtolower($cat_name) == \Config::DESIGN_CATEGORY){
            $catBias = 1000;
        }elseif(strtolower($cat_name) == \Config::CAMERA_SAMPLES_CATEGORY){
            $catBias = 500;
        }elseif(strtolower($cat_name) == \Config::SCREEN_SHOTS_CATEGORY){
            $catBias = 100;
        }elseif(strtolower($cat_name) == \Config::BENCHMARK_CATEGORY){
            $catBias = 10;
        }elseif(strtolower($cat_name) == \Config::ACCESSORIES_CATEGORY){
            $catBias = 1;
        }else{
            $catBias = 0;
        }
        return $catBias;
    }
    
    public function doTask($mob_id = '',$cat=''){
            $start=0;
            $total_records=0;
            try{
                if($mob_id == ''){
                    $this->elasticSearchUtils->createIndex();
                }
                $sql = $this->getQuery($this->db,$start,$this->batchSize,$mob_id,$cat);
                $query  = $this->db-> prepare( $sql );
                $query->execute();
                while($query->rowCount()>0)
                {
                    while($row = $query -> fetch( PDO::FETCH_ASSOC ))
                    {
                        $gl_id = $row['id'];
                        $cat_name = explode(',',$row['cat_name']);
                        $items = $this->getAllItems($gl_id);
                        $catBias = $this->getCategoryBias($cat_name[0]);
                        $params = array(
                            'gallery_id' => $gl_id,
                            'gallery_name' => $row['name'],
                            'gl_cat_name' => $cat_name,
                            'author' => $row['author'],
                            'gl_description' => $row['description'],
                            'index_img_url' => $row['thumb_img_url'].'.'.$row['thumb_img_extension'],
                            'gl_views' => intval($row['views']),
                            'gl_url' => $row['url'],
                            'gl_type' => $row['type'],
                            'pro_name' => $row['product'],
                            'pro_id' => $row['product_id'],
                            'product_views' => intval($row['product_views']),
                            'pro_cat_id' => $row['pro_cat_id'],
                            'pro_brand' => $row['brand'],
                            'cat_bias' => $catBias,
                            'all_items' => $items
                        );
                        $id = $row['id'];
                           if($mob_id == ''){
                               $response = $this->elasticSearchUtils->insertData($id,$params);
                           }else{
                               $response = $this->elasticSearchUtils->insertSingleEntry($id,$params);
                           }
                           if($response == false){
                               $this->isSuccessful=false;
                           }
                    }
                    $total_records=$total_records+$query->rowCount();
                    $start=$start+$this->batchSize;

                    $sql = $this->getQuery($this->db,$start,$this->batchSize,$mob_id,$cat);
                    $query  = $this->db-> prepare( $sql );
                    $query->execute();
                    echo($total_records." records inserted\n");
                }
            }catch(\Exception $exp){
                $this->isSuccessful=false;
                echo("Error indexing data for id = ".$id.$exp);
            }
            if($this->isSuccessful && $mob_id == ''){
                $aliasName = $this->elasticSearchUtils->getAliasName();
                //echo("Alias Name : ".$aliasName);
                $currentIndexName = $this->elasticSearchUtils->getCurrentIndexName();
                //echo("New Index Name : ".$currentIndexName);
                $this->updateMysqlForSuccess($currentIndexName);
                $aliasParams = array('actions' => array(array('add' => array('index' => $currentIndexName,'alias' => $aliasName))));
                //echo("pointing to new index ".$currentIndexName);
                $status = $this->elasticSearchUtils->switchToNewIndex($aliasParams);
                if($status['acknowledged']==true){
                    echo("pointed to new index ".$currentIndexName );
                    $this->deletePreviousIndexes($currentIndexName);
                }else{
                    echo("Some error occur while pointing to new index");
                }
            }
            return $this->isSuccessful;
    }
    public function deletePreviousIndexes($currentIndexName){
        try{
            $sql = 'SELECT index_name FROM gl_elastics
             WHERE index_name != \''.$currentIndexName.'\' AND (is_deleted = 0 OR is_successfully_created = 0)';
            $query  = $this->db-> prepare( $sql );
            $query->execute();
            while($row = $query -> fetch( PDO::FETCH_ASSOC ))
            {
                $this->elasticSearchUtils->deleteIndex($row['index_name']);
                $sql = 'UPDATE gl_elastics SET is_deleted = 1
                ,date_modified = NOW() WHERE index_name = \''.$row['index_name'].'\'';
                $updatequery  = $this->db-> prepare( $sql );
                $updatequery->execute();
            }
        }catch (\Exception $e){
            $this->logger->error(" some error occour while deleting indexes : ",$e);
        }
    }
    public function updateMysqlForSuccess($currentIndexName){
        try{
            //echo("'index successfully created ' flag updating");
            $sql = 'UPDATE gl_elastics SET is_successfully_created = 1
             ,date_modified = NOW() WHERE index_name = \''.$currentIndexName.'\'';
            $query  = $this->db-> prepare( $sql );
            $query->execute();
            //echo("'index successfully created ' flag successfully updated");
        }catch (\Exception $e){
            $this->logger->error(" some error occour while updating 'index successfully created' flag : ",$e);
        }
    }
    public function getNewlyUpdatedProducts($date){
        try{
            $ids = array();
            $sql = "SELECT mobile_id FROM `master_product` WHERE `form_mod_date` > '$date' AND group_cat_id IN (553,579) AND mobile_id > 0
                    ORDER BY `master_product`.`mobile_id`  DESC";
            $query  = $this->db-> prepare( $sql );
            $query->execute();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $ids[] = $row['mobile_id'];
            }
        }catch (\Exception $e){
            return null;
        }
        return $ids;
    }
    public function getNewlyVideoUpdatedProducts($date){
        try {
            $ids = array();
            $sql = "SELECT distinct product_id FROM `product_attributes` WHERE `edit_date` > '$date' AND category_id IN (553,579) AND attr_key like 'video%'
                      ORDER BY `product_attributes`.`product_id`  DESC";
            $query = $this->db111->prepare($sql);
            $query->execute();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $ids[] = $row['product_id'];
            }
        }catch (\Exception $e){
            return null;
        }
        return $ids;
    }
    public function getNewlySlideShareUpdatedProducts($date){
        try {
            $ids = array();
            $sql = "SELECT distinct product_id FROM `product_attributes` WHERE attr_key like 'slideshare_review_url'
                    ORDER BY `product_attributes`.`product_id`  DESC";
            $query = $this->db111->prepare($sql);
            $query->execute();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $ids[] = $row['product_id'];
            }
        }catch (\Exception $e){
            return null;
        }
        return $ids;
    }
    public function insertProductAttribute($id,$name,$catId,$gl_category){
        try {
            $gl_category = str_replace(' ', '_', $gl_category);
            $sql = "SELECT * FROM `product_attributes` WHERE `product_id` = '$id' AND category_id = $catId AND attr_key = '$gl_category'";
            $query = $this->db111->prepare($sql);
            $query->execute();
            if ($query->rowCount() == 0) {
                $sql = "insert into product_attributes (product_id,product_name,category_id,attr_key,attr_value,add_date,edit_date,status) VALUES ('$id','$name',$catId,'$gl_category','yes',NOW(),NOW(),1)";
                $query = $this->db111->prepare($sql);
                return $query->execute();
            }
        }catch (\Exception $e){
            return null;
        }
        return false;
    }
    public function updateDatalayerFeed($prodId,$catId){
        try{
            $url = \Config::DATALAYER_UPDATE_API;
            $url =  str_replace('#pro_id',$prodId,$url);
            $url =  str_replace('#cat_id',$catId,$url);
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $curl_response = curl_exec($curl);
            if($curl_response === false) {
                $info = curl_getinfo($curl);
                curl_close($curl);
                echo('error occured during curl exec. Additioanl info: ' . var_export($info));
            }
            else{
                echo "successfully updated datalayer.\n";
                curl_close($curl);
            }
        }catch (\Exception $e){
            return null;
        }
        return $curl_response;
    }

    public function getGalleryIdsByProductId($productId){
        $galleryIds = array();
        try {
            $sql = "SELECT id FROM `gl_gallery` WHERE `product_id` = '$productId'";
            $query = $this->db->prepare($sql);
            $query->execute();
            while($data = $query->fetch(PDO::FETCH_ASSOC)){
                $galleryIds[] = $data['id'];
            }
        }catch (\Exception $e){
            return null;
        }
        return $galleryIds;
    }
}
    ?>