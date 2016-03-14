<?php


class Item extends Eloquent{

    public $table = "gl_items";

    public function __construct(){

    }
    public function Gallery(){
        return $this -> belongsTo('Gallery');
    }
    static function deleteImage($id){
        try{
            return DB::transaction(function() use($id){
                $image = Item::find($id);
                $image -> active = 0;
                $image -> save();
            });

        }catch(\Exception $e){
            return Response::json($e -> getMessage(), 500);
        }
    }
    static function deleteAllImages($id){
        try{
            $image = DB::table('gl_items') -> where('gallery_id', $id) -> get();
            if(count($image) > 0 ){
                    DB::table('gl_items')-> where('gallery_id' , $id)-> update(['active' => 0]);
            }
            return $image;
        }catch(\Exception $e){
            return Response::json($e -> getMessage(), 500);
        }
    }
    static function setImageData($id){
        try{
            return DB::transaction(function() use($id){
                $result = array();
                //$result['tags'] = array();
                $image = Item::find($id);
                if(count($image) == 0){
                    return Response::json('error', 404);
                }
                $image -> tag = Input::get('tag');
                $image -> description = Input::get('description');
                $image -> caption = Input::get('caption');
                $image -> save();
                //$tags = DB::table('gl_tags')->join('gl_image_tag', 'gl_tags.id', '=' , 'gl_image_tag.tag_id') -> where('gl_image_tag.image_id' , $id) -> get();

                /* Tags removed */
                /*$removedTagsFromUser = Input::get('removedTags');
                if(!empty($removedTagsFromUser)){
                    DB::table('gl_image_tag') -> where('image_id', $id) -> whereIn('tag_id', $removedTagsFromUser) -> delete();
                }
                */
                /* Tags created */
                /*$newTagsFromUser = Input::get('newTags');
                if(!empty($newTagsFromUser)){
                    $tempActive = array();
                    foreach ($newTagsFromUser as $activeTag){
                        $tempActive[] = array('tag' => $activeTag);
                    }
                    $nTag = DB::table('gl_tags') -> insert($tempActive);
                    unset($tempActive);
                    $tempActive = array();
                    $last_insert = DB::getPdo() -> lastInsertId();
                    foreach ($newTagsFromUser as $activeTag){
                        $tempActive[] = array('tag_id' => $last_insert--, 'image_id' => $id);
                    }
                    DB::table('gl_image_tag') -> insert($tempActive);
                }*/


                /* Tags added */
                /*$activeTagsFromUser = Input::get('activeTags');
                if(!empty($activeTagsFromUser)){
                    $tempActive = array();
                    foreach ($activeTagsFromUser as $activeTag){
                        $tempActive[] = array('tag_id' => $activeTag, 'image_id' => $id);
                    }
                    DB::table('gl_image_tag') -> insert($tempActive);
                    unset($tempActive);
                }
                */
                $result['name'] = $image -> name;
                $result['description'] = $image -> description;
                $result['url'] = $image -> url;
                return Response::json($result, 200);
            });
        }
        catch(\Exception $e){
            return Response::json($e -> getMessage(), 500);
        }
    }
    static function getImageData($id){
        $result = array();
        try{
            return DB::transaction(function() use($id, $result){
                $image = Item::find($id);
                if(count($image) == 0){
                    return Response::json('error', 404);
                }
                $result = $image -> attributes;
                if(!empty($result['available_sizes'])){
                    $result['available_sizes'] = explode(',', $result['available_sizes']);
                }
                if(!empty($result['attributes'])){
                    $result['attributes'] = json_decode($result['attributes'], true);
                }
                $categoryIds=array();
                $galleryCategory=DB::table('gl_category_gallery')->where('gallery_id',$image->gallery_id)->get();
                foreach($galleryCategory as $gc){
                    array_push($categoryIds,$gc->category_id);
                }
                $tagCategoryCount=DB::table('gl_tag_category')->whereIn('category_id',$categoryIds)->count();
                $tagData=DB::table('gl_tag')->where('name',$image->tag)->get();
                $tagId='';
                foreach($tagData as $t_data){
                    $tagId=$t_data->id;
                }
                if($tagCategoryCount != 0){
                    $tagData=DB::table('gl_tag_category')->whereIn('category_id',$categoryIds)->get();
                    $tagIds=array();
                    foreach($tagData as $t_data){
                        array_push($tagIds,$t_data->tag_id);
                    }
                    $result['tagIds']=$tagIds;
                    $result['alltags'] =array();
                    $result['alltags']=Tag::get_distinct_tags($tagIds);
                }
                if($tagId !=''){
                    $result['tag']=array($tagId=>$image->tag);
                    $result['alltags']=array_diff($result['alltags'], $result['tag']);
                }
                //$result['alltags'] = array();
                //$result['alltags'] = Keyword::get_distinct_tags($tagIds);
                //$tags = DB::table('gl_tags')->join('gl_image_tag', 'gl_tags.id', '=' , 'gl_image_tag.tag_id') -> where('gl_image_tag.image_id' , $id) -> get();
                //foreach ($tags as $key => $tag) {
                //$result['tags'][$tag -> tag_id] = $tag -> tag;
                //}
                //$result['alltags']=array_diff($result['alltags'], $result['tags']);
                $captionData=DB::table('gl_caption')->where('name',$image->caption)->get();
                $captionId='';
                foreach($captionData as $c_data){
                    $captionId=$c_data->id;
                }
                $captionCategoryCount=DB::table('gl_caption_category')->whereIn('category_id',$categoryIds)->count();
                if($captionCategoryCount != 0){
                    $captionData=DB::table('gl_caption_category')->whereIn('category_id',$categoryIds)->get();
                    $captionIds=array();
                    foreach($captionData as $c_data){
                        array_push($captionIds,$c_data->caption_id);
                    }
                    $result['captionIds']=$captionIds;
                    $result['allcaption'] =array();
                    $result['allcaption']=Caption::get_distinct_caption($captionIds);
                }
                if($captionId !=''){
                    $result['captionMap']=array($captionId=>$image->caption);
                    $result['allcaption']=array_diff($result['allcaption'], $result['captionMap']);
                }
                return Response::json($result, 200);
            });
        }
        catch(\Exception $e){
            return Response::json($e -> getMessage(), 500);
        }
    }
    static function workOnImage($image_name, $image_extension){
        $available_sizes = array();
        $avail_sizes = array();
        $prev_size = '';

        if(class_exists('Imagick')){
            $original_image = new Imagick($image_name.'.'.$image_extension);
            $original_image_height = $original_image ->getImageHeight();
            $original_image_width = $original_image ->getImageWidth();
            foreach (Config::get('settings.image_sizes') as $image_size => $image_size_attributes) {
                if($original_image_width >= $image_size_attributes['width'] || $original_image_height >= $image_size_attributes['height']){
                    $temp_image = new Imagick($image_name.'.'.$image_extension);
                    $temp_image->getImageHeight();
                    if($temp_image->scaleImage($image_size_attributes['width'], $image_size_attributes['height'], true))
                    {
                        $temp_image->writeImages($image_name.'_'.$image_size_attributes['suffix'].'.'.$image_extension, true);
                        $available_sizes[] = $image_size_attributes['suffix'];
                        $avail_sizes[$image_size] = $image_size_attributes['suffix'];
                        if($prev_size == '' && count($avail_sizes) > 0){
                            foreach ($avail_sizes as $image_size_avail => $value)
                            {
                                $avail_sizes[$image_size_avail] = $image_size_attributes['suffix'];
                            }
                        }
                        $prev_size = $image_size_attributes['suffix'];
                    }
                    else{
                        $avail_sizes[$image_size] = $prev_size;
                    }
                }
                else{
                    $avail_sizes[$image_size] = $prev_size;
                }
            }
        }
        return array($available_sizes, $avail_sizes);
    }
    static function upload_images(){
        $images = array();
        $files = Input::file('file');
        if(empty($files)){
            $files = Input::file();
        }
        if(empty($files)){
            echo "upload some files ..";
            die;
        }
        $rules = array(
            'file' => array('image')
        );


        $validation = Validator::make($files, $rules);

        if ($validation->fails())
        {
            return Response::json('error', 400);
        }
        $chk = 0;


        foreach($files as $file){

            if($file -> isValid()  ){
                $hash_file= hash_file('md5', $file);
                $image = DB::table('gl_items') -> where('hash', $hash_file)-> limit('1') -> get();
                if(count($image) === 0 ){

                    $temp = array();
                    $extension = $file -> getClientOriginalExtension();
                    $date = date('Y-m-d');
                    $filename = sha1(microtime().$file -> getClientOriginalName());
                    $temp['name']  = $file -> getClientOriginalName();
                    //$image_name=explode(".",$temp['name']);
                    //$temp['name']=$image_name[0];
                    $image_name=pathinfo($temp['name']);
                    $temp['name']=$image_name['filename'];
                    $temp['url'] = $filename;
                    $temp['extension'] = $extension;
                    $ds = DIRECTORY_SEPARATOR;
                    $targetPath = Config::get('app.gallery_upload_folder').$ds;
                    $path = substr($filename, 0, 1).$ds.substr($filename, 1, 1).$ds;
                    $temp['path'] = $path;
                    $temp['hash'] = $hash_file;
                    $targetFile =  $targetPath.$path;
                    $t_result = $file -> move($targetFile, $filename.'.'.$extension);
                    if($t_result){
                        $av_sizes = self::workOnImage($targetFile.$filename, $extension);
                        $temp['available_sizes'] = implode('-', $av_sizes[0]);
                        $temp['avail_sizes'] = json_encode($av_sizes[1]);
                        $temp['caption'] = '';
                        $temp['description'] = '';
                        $temp['author'] = '';
                        $temp['display_order'] = '';
                        $temp['meta_tags'] = '';
                        $temp['attributes'] = '';
                        $temp['type'] = \App\constants\ItemType::$ImageType;
                        $temp['tag'] = '';
                    }
                    else{
                        $temp['error'] = true;
                    }
                    $chk =1;
                }
                else{
                    $chk =1;
                    $temp = array();
                    $image_name = pathinfo($image[0] -> name);
                    // sha1(microtime().$image_name['filename']).".".$extension
                    /*$temp['name']  = $image[0] -> name;
                    $image_name=explode(".",$temp['name']);*/
                    $temp = (array)$image[0];
                    unset($temp['id']);
                    unset($temp['views']);
                    unset($temp['gallery_id']);
                    unset($temp['active']);
                    unset($temp['created_at']);
                    unset($temp['updated_at']);
                    $temp['name']=$image_name['filename'];
                    //$temp['name']=$image_name[0]
                }
                $images[] = $temp;
                unset($temp);

            }
            // $upload_success = Input::upload('file', $directory, $filename);
        }
        if($chk == 1){
            return Response::json($images, 200);
        }
        else{
            return Response::json('error', 400);
        }

    }

}

?>
