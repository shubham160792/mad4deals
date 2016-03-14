<?php
use App\utils\ProductInfo;
use App\constants\ItemType;
class Gallery extends Eloquent{

    public $table = "gl_gallery";

    public function __construct(){
    }

    public function Images(){
        return $this -> hasMany('Item');
    }
    public function Videos(){
        return $this -> hasMany('Item');
    }
    public function Keyword(){
        return $this -> hasMany('Keyword');
    }
    public function Category(){
        return $this -> hasMany('Category');
    }
    public function AllProductAttributes(){
        return $this -> hasMany('AllProductAttributes');
    }
    static function validate_inputs($rules){
        $result = array();
        $result['result'] = true;
        $validator = Validator::make(Input::all(), $rules);
        if($validator -> fails()){
            $result['result'] = false;
            $result['errors'] = $validator -> errors();
            return $result;
        }
        return $result;
    }
    static function update_gallery_data($id){

        Input::merge(array('id' => $id));
        $rules = array('gl_name' => array('required' ),
            'gl_url' => array('required' ));
        $validationResult = Gallery::validate_inputs($rules);
        if($validationResult['result'] === false){
            return Response::json($validationResult['errors'], 500);
        }
        else{
            try{
                DB::transaction(/**
                 * @return mixed
                 */
                function() {
                    $gallery = Gallery::find(Input::get('id'));
                    $result = array();
                    if (is_numeric(Input::get('gl_category_id'))) {
                        $data = Config::get('app.category');
                        $ptype = $data[Input::get('gl_category_id')];
                        $id = Input::get('gl_product_id');
                        $data = ProductInfo::getBrandFromProId($id, $ptype);
                        $data = json_decode($data, true);
                        $brand = $data[$id]['brand_name'];
                    } else {
                        $brand = '';
                    }
                    $gallery_id = $gallery->id;
                    $error = false;
                    $gallery->name = Input::get('gl_name');
                    $gallery->url = Input::get('gl_url');
                    $gallery->description = Input::get('gl_description');
                    $gallery->type = Input::get('gl_type');
                    $gallery->product = Input::get('gl_product');
                    $gallery->product_id = Input::get('gl_product_id');
                    $gallery->pro_cat_id = Input::get('gl_category_id');
                    $gallery->brand = $brand;
                    $gallery->thumb_img_url = Input::get('gl_thumb_img_path');
                    $gallery->thumb_img_extension = Input::get('gl_thumb_img_extension');
                    $result[] = $gallery->update();
                    $gallery_image_count = $gallery->images->count();
                    $categories = array();
                    $categories_inp = Input::get('gl_category');

                    $gallery->update();

                    Log::info("gallery updated");
                    if (!empty($categories_inp)) {
                        foreach ($categories_inp as $category) {
                            $category_id = DB::table('gl_category')->where('name', '=', $category)->get();
                            $current_category_id[] = $category_id[0]->id;
                        }
                    }

                    $pre_category_id = DB::table('gl_category_gallery')->where('gallery_id', '=', $gallery_id)->get();
                    $tempActive = array();
                    if (!empty($pre_category_id)) {
                        foreach ($pre_category_id as $pre) {
                            $tempActive[] = $pre->category_id;

                        }
                    }
                    $newCategories = array_diff($current_category_id, $tempActive);
                    unset($tempActive);

                    $tempActive = array();
                    if (!empty($newCategories)) {
                        foreach ($newCategories as $id) {
                            $tempActive[] = array('category_id' => $id, 'gallery_id' => $gallery_id);
                        }
                    }
                    if (count($tempActive) != 0) {
                        DB::table('gl_category_gallery')->insert($tempActive);
                    }
                    DB::table('gl_category_gallery')->where('gallery_id', $gallery_id)->WhereNotIn('category_id', $current_category_id)->delete();


                    $images = Input::get('successResponse');

                    $indexKey = 0;
                    if (!empty($images) && $images != null && $images != 'null') {

                        foreach ($images as $key => $image) {
                            $oldImage = Item::with('gallery')->where('hash', $image['hash'])->where('gallery_id', $gallery->id)->get();
                            if ($oldImage->count() === 0) {
                                unset($image['error']);
                                $temp_image = $image;
                                $temp_image['display_order'] = $indexKey + 1 + $gallery_image_count;
                                $temp_image['gallery_id'] = $gallery->id;
                                $temp_image['active'] = 1;
                                $temp_image['updated_at'] = date('Y-m-d H:i:s');
                                $dataImages[] = $temp_image;
                                unset($temp_image);
                                $indexKey++;
                            } else {
                                unset($image['error']);

                                if ($oldImage[0]->active == 0) {
                                    $oldImage[0]->active = 1;
                                    $oldImage[0]->save();
                                }
                                // $temp_image = $oldImage;
                                // $temp_image['display_order'] = $key+1+$gallery_image_count;
                                // $temp_image['gallery_id'] = $gallery -> id;
                                // $dataImages[] = $temp_image;
                                unset($temp_image);
                            }
                        }

                        if (!empty($dataImages)) {
                            $result[] = Item::insert($dataImages);
                        }
                    }
                    $videos = Input::get('gl_videos');
                    if ($videos == '0') {
                        unset($videos);
                        $videos = array();
                    }
                    if (count($videos)) {
                        DB::table('gl_gallery')->where('id', $gallery->id)->where('thumb_img_extension', '')->update(array('thumb_img_url' => $videos[0]['url']));
                    }

                    $videoChk = 0;
                    $oldVideo = Item::with('gallery')->where('gallery_id', $gallery->id)->where(function($query) {$query->where('type', ItemType::$YoutubeType)->orWhere('type', ItemType::$SlideShareType);})->get();

                    $galleryVideo = array();
                    if ($oldVideo){
                        foreach ($oldVideo as $i => $item) {
                            array_push($galleryVideo,$item['url']);
                        }
                    }
                    if($oldVideo -> count() == 0) {
                        for($i=0;$i<count($videos);$i++){
                            $videoChk = 1;
                            $temp_video = array();
                            $temp_video['gallery_id'] = $gallery->id;
                            $temp_video['url'] = $videos[$i]['url'];
                            $temp_video['display_order'] = $indexKey + 1 + $gallery_image_count;
                            $temp_video['active'] = 1;
                            $temp_video['type'] = is_numeric($videos[$i]['type']) ? $videos[$i]['type'] : ItemType::$YoutubeType;
                            $temp_video['created_at'] = date('Y-m-d H:i:s');
                            $temp_video['updated_at'] = date('Y-m-d H:i:s');
                            $dataVideos[] = $temp_video;
                            unset($temp_video);
                            $indexKey++;

                        }
                    } else if($oldVideo -> count() > 0) {
                        for($i=0;$i<count($videos);$i++){
                            if (in_array($videos[$i]['url'], $galleryVideo)) {
                                if (($key = array_search($videos[$i]['url'], $galleryVideo)) !== false) {
                                    unset($galleryVideo[$key]);
                                }
                            } else {
                                $videoChk = 1;
                                $temp_video = array();
                                $temp_video['gallery_id'] = $gallery->id;
                                $temp_video['url'] = $videos[$i]['url'];
                                $temp_video['display_order'] = $indexKey + 1 + $gallery_image_count;
                                $temp_video['active'] = 1;
                                $temp_video['type'] = is_numeric($videos[$i]['type']) ? $videos[$i]['type'] : ItemType::$YoutubeType;
                                $temp_video['created_at'] = date('Y-m-d H:i:s');
                                $temp_video['updated_at'] = date('Y-m-d H:i:s');
                                $dataVideos[] = $temp_video;
                                unset($temp_video);
                                $indexKey++;
                            }
                        }
                    }
                    if(count($galleryVideo) > 0) {
                        foreach ($galleryVideo as $item) {
                            $videoType = DB::table('gl_items')->select('type')->where('gallery_id', $gallery->id)->where('url', $item)->get();
                            Item::with('gallery') ->where('url', $item)->where('gallery_id', $gallery->id)->delete();
                            AllProductAttributes::DeleteFromProductAttributes($gallery['product_id'],$gallery['pro_cat_id'],$videoType[0]->type, $item);
                        }
                    }
                    if($videoChk == 1){

                        $result['videos'] = Item::insert($dataVideos);
                        //AllProductAttributes::InsertIntoProductAttributes($gallery, $dataVideos);
                    }
                    $gl_imagePos =Input::get('gl_imagePos');
                    if(!empty($gl_imagePos) &&  $gl_imagePos!= 'null' ){
                        foreach ($gl_imagePos as $image_id => $display_order) {
                            DB::table('gl_items')-> where('id' , $image_id)-> update(array('display_order' => $display_order,'updated_at' => date('Y-m-d') ));
                        }
                    }

                    self::getGalleryData($gallery->id, $gallery->product, $gallery->product_id, $gallery->pro_cat_id, 1);

                    return Response::json($result, 200);
                });
            }catch(\Exception $e){
                Log::error("error occurs while Updating gallery");
                Log::error($e -> getMessage());
                return Response::json($e -> getMessage(), 400);

            }
        }
    }
    static function getGalleryData ($galleryId, $productName, $productId, $productCatId,$isEdit = 0) {
        $res = DB::table('gl_items')->select('url', 'display_order', 'type', 'created_at', 'updated_at', 'active')->where('gallery_id', $galleryId)->get();
        if($res[0]->type == ItemType::$SlideShareType || $res[0]->type == ItemType::$YoutubeType)
        AllProductAttributes::InsertIntoProductAttributes($galleryId, $productName, $productId, $productCatId, $res, $isEdit);
    }

    static function get_gallery_data($id){
        $gallery_data = array();
        $gallery = Gallery::find($id);
        if(empty($gallery)){
            return null;
        }
        $categories = array();
        /*foreach ($gallery -> category() -> get() as $category) {
            $categories[] = $category['name'];
        }*/

        $categories=Category::getCategoryNameByGalleryID($id);
        $gallery -> items_list = $gallery -> Images() -> where('active', 1) -> orderby('display_order', 'asc') -> get();
        //$gallery -> videosss_list = $gallery -> Videos() -> where('active', 1) ->where('type', 2) -> orderby('display_order', 'asc') -> get();
        $gallery -> categories = $categories;
        return $gallery;
    }
    static function create_gallery(){
        $rules = array('gl_name' => array('required' , 'unique:gl_gallery,name'),
            'gl_url' => array('required' , 'unique:gl_gallery,url'));
        $validationResult = Gallery::validate_inputs($rules);
        if($validationResult['result'] === false){
            return Response::json($validationResult['errors'], 500);
        }
        else{
            try{
                Log::info("Validation result true while creating gallery data ");
                return DB::transaction(function(){
                    if(is_numeric(Input::get('gl_category_id'))){
                        $data = Config::get('app.category');
                        $ptype = $data[Input::get('gl_category_id')];
                        $id = Input::get('gl_product_id');
                        $data = ProductInfo::getBrandFromProId($id,$ptype);
                        $data = json_decode($data,true);
                        $brand = $data[$id]['brand_name'];
                    }else{
                        $brand = '';
                    }
                    $result = array();
                    $error = false;
                    $dataImages = array();
                    $gallery = new Gallery();
                    $images = Input::get('successResponse');
                    $videos = Input::get('gl_videos');
                    $gallery -> name = Input::get('gl_name');
                    $gallery -> url = Input::get('gl_url');
                    $gallery -> type = Input::get('gl_type');
                    $gallery -> description = Input::get('gl_description');
                    $gallery -> type = Input::get('gl_type');
                    $gallery -> product = Input::get('gl_product');
                    $gallery -> product_id = Input::get('gl_product_id');
                    $gallery -> pro_cat_id = Input::get('gl_category_id');
                    $gallery -> brand = $brand;
                    if(Input::get('access') == 'hello'){
                        $gallery ->author = 'root';
                        $gallery ->product_views = Input::get('gl_product_views');

                    }else{
                        $gallery -> author = Auth::user() -> username;
                        $gallery ->product_views = 0;
                    }
                    if(count($images) > 0){
                        $gallery -> thumb_img_url = $images[0]['path'].$images[0]['url'];
                        $gallery -> thumb_img_extension = $images[0]['extension'];
                    }else{
                        $gallery -> thumb_img_url = $videos[0]['url'];
                        $gallery -> thumb_img_extension = '';
                    }

                    $result['gallery'] = $gallery -> save();
                    Log::info("gallery created successfully ");
                    $gallery_id=DB::getPdo() -> lastInsertId();
                    $categories = array();
                    $categories_inp = Input::get('gl_category');
                    foreach ($categories_inp as $category) {
                        $temp = new Category;

                        $cat_check=DB::table('gl_category')->where('name', '=', $category)->get();

                        if(count($cat_check)===0)
                        {
                            $temp -> name = $category;
                            $categories[] = $temp;
                            unset($temp);
                        }
                        else{
                            $insert_id[]=$cat_check[0]->id;
                            // var_dump($insert_id[]);

                        }
                    }

                    if(count($images) > 0){
                        foreach($images as $key => $image){
                            unset($image['error']);
                            $temp_image = $image;
                            $temp_image['display_order'] = is_numeric($image['display_order']) ? $image['display_order'] : $key+1;
                            $temp_image['gallery_id'] = $gallery -> id;
                            $temp_image['active'] = 1;
                            $temp_image['created_at'] = date('Y-m-d H:i:s');
                            $dataImages[] = $temp_image;
                            unset($temp_image);
                        }
                    }

                    $img_count = count($images);
                    for($i=0;$i<count($videos);$i++){
                        $temp_video = array();
                        $temp_video['gallery_id'] = $gallery -> id;
                        $temp_video['url'] = $videos[$i]['url'];
                        $temp_video['display_order'] = is_numeric($videos[$i]['display_order']) ? $videos[$i]['display_order'] : $img_count + $i;
                        $temp_video['active'] = 1;
                        $temp_video['type'] = is_numeric($videos[$i]['type']) ? $videos[$i]['type'] : ItemType::$YoutubeType;
                        $temp_video['created_at'] = date('Y-m-d H:i:s');
                        $dataVideos[] = $temp_video;
                        unset($temp_video);
                    }

                    if(count($videos)){
                        $result['videos'] = Item::insert($dataVideos);
                        self::getGalleryData($gallery->id, $gallery->product, $gallery->product_id, $gallery->pro_cat_id);
                        //AllProductAttributes::InsertIntoProductAttributes($gallery, $dataVideos);
                    }
                    //$gallery->category()->saveMany($categories);

                    $tempActive = array();
                    $last_insert = DB::getPdo() -> lastInsertId();
                    foreach ($categories as $category){
                        $tempActive[] = array('category_id' => $last_insert--, 'gallery_id' => $gallery_id);
                    }
                    unset($tempActive);
                    $tempActive=array();

                    foreach ($insert_id as $id){
                        $tempActive[] = array('category_id' => $id, 'gallery_id' => $gallery_id);
                    }
                    DB::table('gl_category_gallery') -> insert($tempActive);
                    if(count($dataImages)){
                        $result['images'] = Item::insert($dataImages);
                    }
                    $result['id'] = $gallery -> id;
                    return Response::json($result, 200);
                });
            }catch(\Exception $e){
                Log::error("error occurs while creating gallery");
                Log::error($e -> getMessage());
                return Response::json($e -> getMessage(), 400);
            }
        }
    }


}

?>
