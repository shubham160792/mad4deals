<?php

class Caption extends Eloquent{

    public $table = "gl_caption";

    public function __construct(){

    }

    static function get_distinct_caption($captionIds){
        $distinct = array();
        $d_caption=Caption::whereIn('id', $captionIds)->get();
        foreach ($d_caption as $caption) {
            $distinct[$caption['id']] = $caption['name'];
        }
        unset($d_caption);
        return $distinct;
    }



}

?>