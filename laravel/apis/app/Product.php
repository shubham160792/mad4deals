<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    public static function getProducts($categoryId,$subcategoryId){
        $array = array();
        $products =  Product::all();
        foreach ($products as $product) {
            $array[$product['id']] = $product['name'];
        }
        return $array;
    }
}
