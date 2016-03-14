<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    //
    public static function getStores(){
        $array = array();
        $stores =  Store::all();
        foreach ($stores as $store) {
            $array[$store['id']] = $store['name'];
        }
        return $array;
    }
}
