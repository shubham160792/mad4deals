<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    public static function getSubcategories(){
        $array = array();
        $subcategories =  Subcategory::all();
        foreach ($subcategories as $subcategory) {
            $array[$subcategory['id']] = $subcategory['name'];
        }
        return $array;
    }
}
