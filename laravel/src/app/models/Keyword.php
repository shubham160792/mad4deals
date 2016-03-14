<?php


class Keyword extends Eloquent{

	public $table = "gl_tags";

	public function __construct(){

	}
	public function gallery(){
		return $this -> belongsTo('Gallery');
	}
	static function get_distinct_tags(){
		$distinct = array();
		$d_tags =  Keyword::get(array(DB::raw('DISTINCT tag')));
		foreach ($d_tags as $tag) {
			$distinct[$tag['tag']] = $tag['tag'];
		}
		unset($d_tags);
		return $distinct;
	}
}

?>