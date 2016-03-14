<?php
namespace classes;
use \PDO;
class Gallery{
	public function __construct(){
		
	}

	public function gallery()
	{ 
		try{
			$this -> conn = new \utils\Connection();
			$this -> db = $this -> conn -> getDbConnection();
			$gallery=array();
			if($this -> db){
				if($this -> gallery_count != 0  )
				{
					$getGalleryQuery = $this -> db -> prepare("SELECT DISTINCT gl_gallery.id,name,description,thumb_img_url,thumb_img_extension,url,type,product,product_id,gl_gallery.category_id,gl_gallery.created_at,gl_gallery.updated_at FROM gl_category_gallery JOIN gl_gallery ON  gl_category_gallery.gallery_id =gl_gallery.id WHERE gl_category_gallery.category_id IN (".$this -> category_id.") LIMIT :start, :results");
					$getGalleryQuery->bindParam(':start', $this -> start, PDO::PARAM_INT);
					$getGalleryQuery->bindParam(':results',$this -> gallery_count , PDO::PARAM_INT);
				}
				else 
				{
					$getGalleryQuery = $this -> db -> prepare("SELECT DISTINCT gl_gallery.id,name,description,thumb_img_url,thumb_img_extension,url,type,product,product_id,gl_gallery.category_id,gl_gallery.created_at,gl_gallery.updated_at FROM gl_category_gallery JOIN gl_gallery ON  gl_category_gallery.gallery_id =gl_gallery.id WHERE gl_category_gallery.category_id IN (".$this -> category_id.") LIMIT :start, 20");
					$getGalleryQuery->bindParam(':start',$this -> start, PDO::PARAM_INT);
				}
				$getGalleryQuery -> execute();
				$this -> db = null;
				while($row =  $getGalleryQuery -> fetch(PDO::FETCH_ASSOC)){
					$gallery[] = $row;
				}	
				return $gallery;
			}
		}
		catch(\Exception $e){
		}
	}

	public function allgallery()
	{ 
		try{
			$this -> conn = new \utils\Connection();
			$this -> db = $this -> conn -> getDbConnection();
			$gallery=array();
			if($this -> db){
				if($this -> gallery_count != 0  )
				{
					$getGalleryQuery = $this -> db -> prepare("SELECT * FROM gl_gallery LIMIT :start, :results");
					$getGalleryQuery->bindParam(':start', $this -> start, PDO::PARAM_INT);
					$getGalleryQuery->bindParam(':results',$this -> gallery_count , PDO::PARAM_INT);
				}
				else 
				{
					$getGalleryQuery = $this -> db -> prepare("SELECT * FROM gl_gallery LIMIT :start, 20");
					$getGalleryQuery->bindParam(':start',$this -> start, PDO::PARAM_INT);
				}
				$getGalleryQuery -> execute();
				$this -> db = null;
				while($row =  $getGalleryQuery -> fetch(PDO::FETCH_ASSOC)){
					$gallery[] = $row;
				}	
				return $gallery;
			}
		}
		catch(\Exception $e){
		}
	}
	public function categories()
	{   
		try{
			$this -> conn = new \utils\Connection();
			$this -> db = $this -> conn -> getDbConnection();
			$getcategories=$this -> db -> prepare("SELECT * FROM gl_category ");
			$getcategories -> execute();
			$this -> db = null;
			while($row =  $getcategories -> fetch(PDO::FETCH_ASSOC)){
				$categories[] = $row;
			}	
			return $categories;
		}
		catch(\Exception $e){
		}
	}

	public function getGalleryData($category_id,$gallery_count,$page){
		$this -> gallery_count = $gallery_count;
		$this -> category_id = $category_id;
		$this -> start = $page; 
		if($category_id == null)
		{
			$this -> gallery= json_encode($this -> allgallery() );
		}
		else 
		{
			$this -> gallery= json_encode($this -> gallery() );
		}
		return $this -> gallery;
	}

	public function gettags()
	{
		try{
			$this -> conn = new \utils\Connection();
			$this -> db = $this -> conn -> getDbConnection();
			$tags=array();
			if($this -> db){
				
				$gettags = $this -> db -> prepare("SELECT DISTINCT tag FROM gl_tags");
				$gettags -> execute();
				$this -> db = null;
				while($row =  $gettags -> fetch(PDO::FETCH_ASSOC)){
					$tags[] = $row;
				}	
				$tags=json_encode($tags);
				return $tags;
			}
		}
		catch(\Exception $e){
		}
	}
}
?>
