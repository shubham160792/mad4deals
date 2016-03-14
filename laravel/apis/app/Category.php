<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public static function getCategoryIdFromCategoryName($categoryName){
        $categoryId = Category::find('id')->where('name', $categoryName);
        if($categoryId !=''){
            return $categoryId;
        }
        return false;
    }
    public static function getCategoryNameFromCategoryId($categoryId){
        $categoryName = Category::find('name')->where('id', $categoryId);
        if($categoryName !=''){
            return $categoryName;
        }
        return false;
    }

    public static function getAllCategories(){
        $array = array();
        $categories =  Category::all();
        foreach ($categories as $category) {
            $array[$category['id']] = $category['name'];
        }
        return $array;
    }
}
