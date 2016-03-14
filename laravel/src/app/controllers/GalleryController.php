<?php
class GalleryController extends \BaseController {

	function __construct()
	{
		if(Input::get('access') == 'hello'){

		}else{
			$this -> beforeFilter("auth");
		}
	}

	public function index(){
		try{
			if (true || Auth::check())
			{
				if(Input::get('raw') == 1){
					$gallery = Gallery::all();
					return Response::JSON($gallery, 200);
				}elseif(is_numeric(Input::get('id')) && is_numeric(Input::get('cat_id'))){
					$gallery = DB::table('gl_gallery') -> where('product_id',Input::get('id')) -> where('pro_cat_id',Input::get('cat_id')) -> get();
					return View::make(Config::get('app.url_path').'gallery.index')
						->with('gallery', $gallery);
				}else{
					$gallery = DB::table('gl_gallery') -> where('type','general')  -> limit('500') -> get();
					return View::make(Config::get('app.url_path').'gallery.index')
						->with('gallery', $gallery);
				}
			}
			else
			{
				return View::make(Config::get('app.url_path').'login');
			}
		}
		catch(\Exception $e){
			Log::error("error occurs while getting all gallery details");
			Log::error($e -> getMessage());
		}
	}
	public function create()
	{
		if (true || Auth::check())
		{
			return View::make(Config::get('app.url_path').'create_gallery');
		}
		else
		{
			return View::make(Config::get('app.url_path').'login');
		}
	}
	public function store(){
		if (true || Auth::check())
		{
			$response = Gallery::create_gallery();
			$productId = Input::get('gl_product_id');
			$elastic = new \App\utils\ElasticUtils();
			$elastic->updateElasticGallery($productId);
			return $response;
		}
		else{
			echo Input::get('access');
			if(Input::get('access') == 'hello'){
				$response = Gallery::create_gallery();
				$productId = Input::get('gl_product_id');
				$elastic = new \App\utils\ElasticUtils();
				$elastic->updateElasticGallery($productId);
				return $response;
			}
			return Redirect::to(Config::get('app.url_path').'login');
		}
	}
	public function show($id){
		try{	
			if (true || Auth::check()){
				if(Input::get('raw') == 1){
					$gallery_data= Gallery::get_gallery_data($id);
					return Response::JSON($gallery_data, 200);
				}
				$gallery= Gallery::get_gallery_data($id);
				if(!empty($gallery)){
					return View::make(Config::get('app.url_path').'gallery.show')
					->with('gallery', $gallery);
				}
				else{
					App::abort(404, 'No such gallery.');
				}
			}
			else{
				return View::make(Config::get('app.url_path').'login');
			}

		}
		catch(\Exception $e){

			Log::error("error occurs while showing galleries");
			Log::error($e -> getMessage());
		}
	}
	public function edit($id)
	{
		try
		{
			if (true || Auth::check())
			{
				$gallery= Gallery::get_gallery_data($id);
				return View::make(Config::get('app.url_path').'edit_gallery')
				->with('gallery', $gallery);
			}
			else
			{
				return View::make(Config::get('app.url_path').'login');
			}
		}
		catch(\Exception $e){

			Log::error("error occurs while getting content to edit gallery");
			Log::error($e -> getMessage());
		}
	}
	public function update($id)
	{
		$response = Gallery::update_gallery_data($id);
		$productId = Input::get('gl_product_id');
		$elastic = new \App\utils\ElasticUtils();
		$elastic->updateElasticGallery($productId);
		return $response;
	}
	public function destroy($id){
		try{
            $prod = DB::table('gl_gallery')->select('product_id', 'pro_cat_id')->where('id', $id)->get();
			$gallery = Gallery::find($id);
			$gallery->delete();
			DB::table('gl_category_gallery') -> where('gallery_id', $id) -> delete();
            $items = Item::deleteAllImages($id);
            AllProductAttributes::DeleteAllByGalleryId ($prod[0]->product_id, $prod[0]->pro_cat_id, $items);
            $elastic = new \App\utils\ElasticUtils();
            $elastic->updateElasticGallery($prod[0]->product_id);
			Session::flash('message', 'Successfully deleted Gallery!');
			return Redirect::to(Config::get('app.url_path').'gallery');
		}
		catch(\Exception $e){

			Log::error("error occurs while deleting gallery");
			Log::error($e -> getMessage());
		}
	}
}
