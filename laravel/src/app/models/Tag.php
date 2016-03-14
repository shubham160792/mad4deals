<?php

class Tag extends Eloquent{

    public $table = "gl_tag";

    public function __construct(){

    }

    static function get_distinct_tags($tagIds){
        $distinct = array();
        $d_tag =  Tag::whereIn('id', $tagIds)->get();
        foreach ($d_tag as $tag) {
            $distinct[$tag['id']] = $tag['name'];
        }
        unset($d_tag);
        return $distinct;
    }

}

?>