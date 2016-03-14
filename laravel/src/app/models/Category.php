<?php

class Category extends Eloquent{

	public $table = "gl_category";

	public function __construct(){

	}
	static function get_distinct(){
		return  Category::get(array(DB::raw('DISTINCT name')));
	}
	static function get_distinct_names(){
		$distinct = array();
		$d_category =  Category::get(array(DB::raw('DISTINCT name')));
		foreach ($d_category as $category) {
			$distinct[$category['name']] = $category['name'];
		}
		unset($d_category);
		return $distinct;
	}
	public function gallery(){
		return $this -> belongsTo('Gallery');
	}

	static function get_all_categories(){
		$distinct = array();
		$d_category =  Category::get(array(DB::raw('*')));
		foreach ($d_category as $category) {
			$distinct[$category['name']] = $category['name'];
		}
		return $d_category;
	}

	static function getCategoryNameByGalleryID($id){

	$category= DB::table('gl_category_gallery')->join('gl_category', 'gl_category_gallery.category_id', '=' , 'gl_category.id') -> where('gl_category_gallery.gallery_id' , $id) -> get();
		
	if($category !=null){
		

	  		foreach ($category as $cat) {
	  		
	  			$temp = $cat->name;

	  			$categories[]=$temp;
	  			unset($temp);
	  		}	
	
		return $categories;
		}

}

}

?>