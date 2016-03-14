<?php
namespace crons;
use \PDO;
class CreateGallery {
    private $db;
    private $db107;
    private $imgUrl;
    private $galleryUrl;
    private $datalayer;
    private $oldImagearray = array();
    private $oldVideoarray = array();
    public function __construct(){
        try{
            $this -> conn = new \utils\Connection();
            $this -> db = $this -> conn -> getDbConnection111();
            $this -> redis = $this -> conn -> getRedisConnection();
            $this -> db107 = $this -> conn -> getDbConnection();
            $this->batchSize = 500;
            $this -> imgUrl = \Config::uploadImagesPath;
            $this -> galleryUrl = \Config::createGalleryPath;
            $this -> datalayer = \Config::dataLayerAPI;
        }
        catch(\Exception $e){
        }
    }
    public function getQuery($id,$start,$limit){
        if($id == ''){
            $pro_id_cond = '';
        }else{
            $pro_id_cond = "WHERE m.id = $id";
        }
        $Query = "SELECT m.id AS mobid, m.category_id AS category_id, CONCAT( c.display_name, ' ', m.search_name) AS proname,
       m.img_slug, m.noofviews, m.img_count, f.featuresurl_url FROM mobile AS m LEFT
        JOIN company AS c ON c.id = m.Brand LEFT JOIN featuresurl AS f ON f.entityId = m.id
        $pro_id_cond GROUP BY m.id ORDER BY m.id DESC limit $start,$limit";
        return $Query;
    }
    public function getGalleryId($id,$cat){
        $sql = "SELECT id FROM gl_gallery as gl ,
                (SELECT map.gallery_id as gallery_id ,GROUP_CONCAT(c.name) as cat_name
                FROM gl_category_gallery as map , gl_category as c WHERE map.category_id = c.id GROUP BY gallery_id) as cat
                WHERE gl.id = cat.gallery_id AND product_id = $id AND cat_name = '$cat'";
        $Query = $this -> db107 -> prepare($sql);
        $Query -> execute();
        $row =  $Query -> fetch(PDO::FETCH_ASSOC);
        return $row['id'];
    }
    public function deleteGallery($id){
        $sql = "DELETE  FROM `gl_gallery` WHERE `id` = $id";
        $Query = $this -> db107 -> prepare($sql);
        return $Query -> execute();
    }
    public function deleteGalleryImages($id){
        $sql = "UPDATE `gl_items` SET active = 0 WHERE `gallery_id` = $id";
        $Query = $this -> db107 -> prepare($sql);
        return $Query -> execute();
    }
    public function deleteExistingData($id,$cat){
        $gallery_id = $this->getGalleryId($id,$cat);
        if($gallery_id != '' && $gallery_id != null){
            $this->deleteGallery($gallery_id);
            $this->deleteGalleryImages($gallery_id);
        }
        return $gallery_id;
    }
    public function getImages($gallery_id) {
        if($gallery_id != '' && $gallery_id != null) {
            $sql = "SELECT name, url, caption, tag, description, display_order, meta_tags,type FROM `gl_items` WHERE `gallery_id` = $gallery_id AND active = 1";
            $Query = $this->db107->prepare($sql);
            $Query->execute();
            if ($Query->rowCount() > 0) {
                while ($row = $Query->fetch(PDO::FETCH_ASSOC)) {
                    if($row['type'] == 1) {
                        $this->oldImagearray[$row['name']] = array(
                            'name' => $row['name'],
                            'caption' => $row['caption'],
                            'description' => $row['description'],
                            'tag' => $row['tag'],
                            'display_order' => $row['display_order'],
                            'meta_tags' => $row['meta_tags'],
                            'type' => $row['type']
                        );
                    } elseif ($row['type'] == 2 || $row['type'] == 3) {
                        $this->oldVideoarray[] = array(
                            'url' => $row['url'],
                            'display_order' => $row['display_order'],
                            'meta_tags' => $row['meta_tags'],
                            'type' => $row['type']
                        );
                    }
                }
            }
        }
    }
    public function doTask($mob_id = ''){
        echo "creating galleries\n";
        try {
            $count = 0;
            $start = 0;
            if ($mob_id == '') {
            } else {
                $cat = \Config::DESIGN_CATEGORY;
                $gallery_id = $this->getGalleryId($mob_id, $cat);
                if($gallery_id != '' && $gallery_id != null) {
                    $this->getImages($gallery_id);
                }
                $gallery_id = $this->deleteExistingData($mob_id, $cat);
            }
            $Query = $this->getQuery($mob_id, $start, $this->batchSize);
            $Query = $this->db->prepare($Query);
            $Query->execute();
            while ($Query->rowCount() > 0) {
                while ($row = $Query->fetch(PDO::FETCH_ASSOC)) {
                    $id = $row['mobid'];
                    $pro_name = $row['proname'];
                    $pro_views = $row['noofviews'];
                    $pro_url = $row['featuresurl_url'];
                    if ($row['category_id'] == 1) {
                        $ptype = 'mobile';
                        $catId = 553;
                    } elseif ($row['category_id'] == 2) {
                        $ptype = 'tablet';
                        $catId = 579;
                    }
                    $data = $this->getCurlData($id, $ptype);

                    $data = json_decode($data, true);
                    $totalImg = $data[$id]['p_img_count'];
                    $totalHresImg = $data[$id]['hres_img_count'];
                    if ($totalHresImg == 0) {
                        for ($i = 0; $i < count($data[$id]['all_images']['p_largeimg_path']); $i++) {
                            $tempImg = $data[$id]['all_images']['p_largeimg_path'][$i];
                            $tempImg = str_replace('http://www.91-img.com/pictures', 'http://180.179.213.69/pictures_sync/pictures', $tempImg);
                            $files[] = $tempImg;
                        }
                    } else {
                        for ($i = 0; $i < count($data[$id]['all_images']['p_hresimg_path']); $i++) {
                            $tempImg = $data[$id]['all_images']['p_hresimg_path'][$i];
                            $tempImg = str_replace('http://www.91-img.com/pictures', 'http://180.179.213.69/pictures_sync/pictures', $tempImg);
                            $files[] = $tempImg;
                        }
                    }
                    $count = $count + $totalImg;
                    //var_dump($files);
                    $request = curl_init($this->imgUrl);
                    $this->curl_custom_postfields($request, array(), $files);
                    $successResponse = curl_exec($request);
                    //var_dump($successResponse);
                    $successResponse = json_decode($successResponse, true);

                    if(count($this->oldImagearray) > 0) {
                        for($i=0;$i<count($successResponse);$i++){
                            if (array_key_exists($successResponse[$i]['name'], $this->oldImagearray)){
                                $successResponse[$i]['caption']         = $this->oldImagearray[$successResponse[$i]['name']]['caption'];
                                $successResponse[$i]['description']     = $this->oldImagearray[$successResponse[$i]['name']]['description'];
                                $successResponse[$i]['tag']             = $this->oldImagearray[$successResponse[$i]['name']]['tag'];
                                $successResponse[$i]['display_order']   = $this->oldImagearray[$successResponse[$i]['name']]['display_order'];
                                $successResponse[$i]['meta_tags']       = $this->oldImagearray[$successResponse[$i]['name']]['meta_tags'];
                            }
                        }
                    }
                    curl_close($request);
                    $data = array(
                        'access' => "hello",
                        'successResponse' => $successResponse,
                        'gl_name' => $pro_name . ' - Design',
                        'gl_url' => $pro_url . '-' . $id . '-design',
                        'gl_description' => '',
                        'gl_videos' =>$this->oldVideoarray,
                        'gl_type' => 'product',
                        'gl_category' => array(\Config::DESIGN_CATEGORY),
                        'gl_product' => $pro_name,
                        'gl_product_id' => $id,
                        'gl_product_views' => $pro_views,
                        'gl_category_id' => $catId
                    );
                    //var_dump($data);
                    $this->http_build_query_for_curl($data, $post);
                    //var_dump($post);
                    $request = curl_init($this->galleryUrl);
                    $this->curl_custom_postfields($request, $post, array());
                    curl_setopt($request, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                    curl_setopt($request, CURLOPT_USERPWD, "admin:playwithimages");
                    $r = curl_exec($request);
                    var_dump($r);
                    curl_close($request);
                    unset($data);
                    unset($files);
                    unset($post);
                }
                $this->db = $this->conn->getDbConnection111();
                $start = $start + $this->batchSize;
                $Query = $this->getQuery($mob_id, $start, $this->batchSize);
                $Query = $this->db->prepare($Query);
                $Query->execute();
            }
        }catch (\Exception $e){
            echo "Error :".$e;
        }
        echo "Total Images : ".$count."\n";
        echo "created galleries\n";
        if($mob_id == ''){
        }else{
            return $gallery_id;
        }
    }
    public function doTaskVideosToItems(){
        $start = 0;
        $batchSize = 1;
        while(1) {
           $Query = "INSERT INTO gl_items (gallery_id,url,caption,description,author,views,active,display_order,meta_tags,attributes,created_at,updated_at) SELECT gallery_id,url,caption,description,author,views,active,display_order,meta_tags,attributes,created_at,updated_at FROM gl_videos LIMIT ".$start.",".$batchSize;
            $Query = $this -> db107 -> prepare($Query);
            $Query -> execute();
            $start = $start + $batchSize;
            echo "\n".($start+1)." rows inserted.";
            if($Query->rowCount() == 0)
                break;
        }
    }
    public function doTaskForVideos($mob_id=''){
        echo "creating video galleries\n";
        $count = 0;
        $start =0;
        if($mob_id == ''){
        }else{
            $cat = \Config::VIDEOS_CATEGORY;
            $gallery_id = $this->deleteExistingData($mob_id,$cat);
        }
        $Query = $this->getQuery($mob_id,$start,$this->batchSize);
        $Query = $this -> db -> prepare($Query);
        $Query -> execute();
        while($Query->rowCount()>0)
        {
            while($row =  $Query -> fetch(PDO::FETCH_ASSOC)){
                $id = $row['mobid'];
                $pro_name = $row['proname'];
                $pro_views = $row['noofviews'];
                $pro_url = $row['featuresurl_url'];
                if($row['category_id'] == 1){
                    $ptype = 'mobile';
                    $catId = 553;
                }elseif($row['category_id'] == 2){
                    $ptype = 'tablet';
                    $catId = 579;
                }
                $data = $this->getCurlData($id,$ptype);
                $data = json_decode($data,true);
                //var_dump($data);
                $videos = array();
                if (array_key_exists('video1_url', $data[$id])) {
                    $videos[] = array(
                        'url' => $data[$id]['video1_url'],
                        'display_order' => 1,
                        'type' => 2,
                        'meta_tags' => '');
                }
                if (array_key_exists('video2_url', $data[$id])) {
                    $videos[] = array(
                        'url' => $data[$id]['video2_url'],
                        'display_order' => 2,
                        'type' => 2,
                        'meta_tags' => '');
                }
                if (array_key_exists('video3_url', $data[$id])) {
                    $videos[] = array(
                        'url' => $data[$id]['video3_url'],
                        'display_order' => 3,
                        'type' => 2,
                        'meta_tags' => '');
                }
                if (array_key_exists('video4_url', $data[$id])) {
                    $videos[] = array(
                        'url' => $data[$id]['video4_url'],
                        'display_order' => 4,
                        'type' => 2,
                        'meta_tags' => '');
                }
                if (array_key_exists('video5_url', $data[$id])) {
                    $videos[] = array(
                        'url' => $data[$id]['video5_url'],
                        'display_order' => 5,
                        'type' => 2,
                        'meta_tags' => '');
                }
                if (array_key_exists('video6_url', $data[$id])) {
                    $videos[] = array(
                        'url' => $data[$id]['video6_url'],
                        'display_order' => 6,
                        'type' => 2,
                        'meta_tags' => '');
                }
                if (array_key_exists('video7_url', $data[$id])) {
                    $videos[] = array(
                        'url' => $data[$id]['video7_url'],
                        'display_order' => 7,
                        'type' => 2,
                        'meta_tags' => '');
                }
                if (array_key_exists('video8_url', $data[$id])) {
                    $videos[] = array(
                        'url' => $data[$id]['video8_url'],
                        'display_order' => 8,
                        'type' => 2,
                        'meta_tags' => '');
                }
                if (array_key_exists('video9_url', $data[$id])) {
                    $videos[] = array(
                        'url' => $data[$id]['video9_url'],
                        'display_order' => 9,
                        'type' => 2,
                        'meta_tags' => '');
                }
                if (array_key_exists('video10_url', $data[$id])) {
                    $videos[] = array(
                        'url' => $data[$id]['video10_url'],
                        'display_order' => 8,
                        'type' => 2,
                        'meta_tags' => '');
                }
                if(count($videos) == 0){
                    echo "video missing for this product : ".$id."\n";
                    continue;
                }
                $successResponse = array();
                $data =	 array(
                    'access' => "hello",
                    'successResponse' => $successResponse,
                    'gl_name' => $pro_name.' - Videos',
                    'gl_url' => $pro_url.'-'.$id.'-videos',
                    'gl_description' => '',
                    'gl_videos' => $videos,
                    'gl_type' => 'product',
                    'gl_category' => array(\Config::VIDEOS_CATEGORY),
                    'gl_product' => $pro_name,
                    'gl_product_id' => $id,
                    'gl_product_views' => $pro_views,
                    'gl_category_id' => $catId
                );
                //var_dump($data);
                $this->http_build_query_for_curl($data, $post);
                //var_dump($post);
                $request = curl_init($this->galleryUrl);
                $this->curl_custom_postfields($request, $post, array() );
                curl_setopt($request, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($request, CURLOPT_USERPWD, "admin:playwithimages");
                $r = curl_exec($request);
                var_dump($r);
                curl_close($request);
                unset($data);
                unset($files);
                unset($post);
                unset($videos);
            }
            $start=$start+$this->batchSize;
            $this -> db = $this -> conn -> getDbConnection111();
            $Query = $this->getQuery($mob_id,$start,$this->batchSize);
            $Query = $this -> db -> prepare($Query);
            $Query -> execute();
        }
        echo "created video galleries\n";
        if($mob_id == ''){
        }else{
            return $gallery_id;
        }
    }
    public function doTaskForSlideShare($mob_id=''){
        echo "creating slideshare galleries\n";
        $count = 0;
        $start =0;
        if($mob_id == ''){
        }else{
            $cat = \Config::SLIDE_SHARE_CATEGORY;
            $gallery_id = $this->deleteExistingData($mob_id,$cat);
        }
        $Query = $this->getQuery($mob_id,$start,$this->batchSize);
        $Query = $this -> db -> prepare($Query);
        $Query -> execute();
        while($Query->rowCount()>0)
        {
            while($row =  $Query -> fetch(PDO::FETCH_ASSOC)){
                $id = $row['mobid'];
                $pro_name = $row['proname'];
                $pro_views = $row['noofviews'];
                $pro_url = $row['featuresurl_url'];
                if($row['category_id'] == 1){
                    $ptype = 'mobile';
                    $catId = 553;
                }elseif($row['category_id'] == 2){
                    $ptype = 'tablet';
                    $catId = 579;
                }
                $data = $this->getCurlData($id,$ptype);
                $data = json_decode($data,true);
                //var_dump($data);

                $videos = array();
                if (array_key_exists('slideshare_review_url', $data[$id])) {
                    $videos[] = array(
                        'url' => $data[$id]['slideshare_review_url'],
                        'display_order' => 1,
                        'type' => 3,
                        'meta_tags' => '');
                }
                var_dump($videos);
                if(count($videos) == 0){
                    echo "Slideshare missing for this product : ".$id."\n";
                    continue;
                }
                $successResponse = array();
                $data =	 array(
                    'access' => "hello",
                    'successResponse' => $successResponse,
                    'gl_name' => $pro_name.' - Slide Share',
                    'gl_url' => $pro_url.'-'.$id.'-Slide Share',
                    'gl_description' => '',
                    'gl_videos' => $videos,
                    'gl_type' => 'product',
                    'gl_category' => array(\Config::SLIDE_SHARE_CATEGORY),
                    'gl_product' => $pro_name,
                    'gl_product_id' => $id,
                    'gl_product_views' => $pro_views,
                    'gl_category_id' => $catId
                );
                //var_dump($data);
                $this->http_build_query_for_curl($data, $post);
                //var_dump($post);
                $request = curl_init($this->galleryUrl);
                $this->curl_custom_postfields($request, $post, array() );
                curl_setopt($request, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($request, CURLOPT_USERPWD, "admin:playwithimages");
                $r = curl_exec($request);
                var_dump($r);
                curl_close($request);
                unset($data);
                unset($files);
                unset($post);
                unset($videos);
            }
            $start=$start+$this->batchSize;
            $this -> db = $this -> conn -> getDbConnection111();
            $Query = $this->getQuery($mob_id,$start,$this->batchSize);
            $Query = $this -> db -> prepare($Query);
            $Query -> execute();
        }
        echo "created slideshare galleries\n";
        if($mob_id == ''){
        }else{
            return $gallery_id;
        }
    }
    public function getCurlData($id,$ptype){
        try{
            $url_track = $this->datalayer;
            $url_track = str_replace('#pro_id',$id,$url_track);
            $url_track = str_replace('#pro_type',$ptype,$url_track);
            $method='GET';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url_track);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            return $result;
        }catch (\Exception $e){
            return null;
        }
    }
    public function http_build_query_for_curl( $arrays, &$new = array(), $prefix = null ) {

        if ( is_object( $arrays ) ) {
            $arrays = get_object_vars( $arrays );
        }

        foreach ( $arrays AS $key => $value ) {
            $k = isset( $prefix ) ? $prefix . '[' . $key . ']' : $key;
            if ( is_array( $value ) OR is_object( $value )  ) {
                $this->http_build_query_for_curl( $value, $new, $k );
            } else {
                $new[$k] = $value;
            }
        }
    }
    public function curl_custom_postfields($ch, $assoc = array(), $files = array()) {
        // invalid characters for "name" and "filename"
        static $disallow = array("\0", "\"", "\r", "\n");

        // build normal parameters
        foreach ($assoc as $k => $v) {
            $k = str_replace($disallow, "_", $k);
            $body[] = implode("\r\n", array(
                "Content-Disposition: form-data; name=\"{$k}\"",
                "",
                filter_var($v),
            ));
        }
        // build file parameters
        foreach ($files as $k => $v) {
            switch (true) {
                case false === $v:
                case !is_file($v):
                case !is_readable($v):
                    continue; // or return false, throw new InvalidArgumentException
            }
            $data = file_get_contents($v);
            $v = call_user_func("end", explode(DIRECTORY_SEPARATOR, $v));
            $k = str_replace($disallow, "_", $k);
            $v = str_replace($disallow, "_", $v);
            $body[] = implode("\r\n", array(
                "Content-Disposition: form-data; name=\"{$k}\"; filename=\"{$v}\"",
                "Content-Type: application/octet-stream",
                "",
                $data,
            ));
        }

        // generate safe boundary
        do {
            $boundary = "---------------------" . md5(mt_rand() . microtime());
        } while (preg_grep("/{$boundary}/", $body));

        // add boundary for each parameters
        array_walk($body, function (&$part) use ($boundary) {
            $part = "--{$boundary}\r\n{$part}";
        });

        // add final boundary
        $body[] = "--{$boundary}--";
        $body[] = "";

        // set options
        //$body = array('file'=>$body);
        //var_dump($body);
        return @curl_setopt_array($ch, array(
            CURLOPT_POST       => true,
            CURLOPT_RETURNTRANSFER      => true,
            CURLOPT_POSTFIELDS => array('file' => implode("\r\n", $body)),
            CURLOPT_HTTPHEADER => array(
                "Expect: 100-continue",
                "Content-Type: multipart/form-data; boundary={$boundary}", // change Content-Type
            ),
        ));
    }
}