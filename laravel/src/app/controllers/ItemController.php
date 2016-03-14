<?php

class ItemController extends BaseController {

    public function destroy($id){
        return $image = Item::deleteImage($id);
    }
    public function get($id){
        return $image = Item::getImageData($id);
    }

    public function update($id){
        return Item::setImageData($id);
    }

    public function upload(){
        return Item::upload_images();
    }

}

?>
