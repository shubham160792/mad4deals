<?php

class CategoryController extends \BaseController {




	public function index()
	{


		try
		{
			if (Auth::check())
			{
				$category = Category::all();
				return View::make('category.index')
				->with('category', $category);
			}
			else
			{
				return View::make(Config::get('app.url_path').'login');
			}

		}
		catch(\Exception $e){

			Log::error("error occurs while getting details of all category");
			Log::error($e -> getMessage());

		}
	}

	public function create(){

		if (Auth::check()){
			return View::make('category.create');

		}
		else{
			return View::make(Config::get('app.url_path').'login');
		}

	}

	public function store()
	{
		
		$rules = array(
			'name'       => 'required|unique:gl_category',  
			'description'      => 'required'
			
			);
		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			return Redirect::to(Config::get('app.url_path').'category/create')
			->withErrors($validator)
			->withInput(Input::except('password'));
		} else {
			try{
				$category = new Category;
				$category->name       = Input::get('name');
				$category->description      = Input::get('description');

				$category->save();

				Session::flash('message', 'Successfully created Category!');
				return Redirect::to(Config::get('app.url_path').'category');
			}
			catch(\Exception $e){

				Log::error("error occurs while creating category");
				Log::error($e -> getMessage());

			}
		}


	}


	public function show($id)
	{
		try
		{
			if (Auth::check()){
				$category = Category::find($id);

				return View::make('category.show')
				->with('category', $category);
			}
			else{
				return View::make(Config::get('app.url_path').'login');
			}

		}
		catch(\Exception $e){

			Log::error("error occurs while showing category");
			Log::error($e -> getMessage());

		}
	}


	public function edit($id)
	{
		try
		{

			if (Auth::check())
			{
				$category = Category::find($id);

				return View::make(Config::get('app.url_path').'category.edit')
				->with('category', $category);

			}
			else
			{
				return View::make(Config::get('app.url_path').'login');
			}


		}
		catch(\Exception $e){

			Log::error("error occurs while getting content to edit category");
			Log::error($e -> getMessage());

		}
	}


	public function update($id)
	{
		

		$rules = array(
			'name'       => 'required',
			'description'      => 'required',
			
			);
		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			return Redirect::to(Config::get('app.url_path').'category/' . $id . '/edit')
			->withErrors($validator)
			->withInput(Input::except('password'));
		} else {
			try
			{
				$category = Category::find($id);
				$category->name       = Input::get('name');
				$category->description      = Input::get('description');
				$category->save();

			// redirect
				Session::flash('message', 'Successfully updated category!');
				return Redirect::to(Config::get('app.url_path').'category');
			}
			catch(\Exception $e){

				Log::error("error occurs while updating category");
				Log::error($e -> getMessage());

			}
		}

	}


	public function destroy($id)
	{
		try
		{
			$category = Category::find($id);
			$category->delete();

			Session::flash('message', 'Successfully deleted Category!');
			return Redirect::to(Config::get('app.url_path').'category');
		}
		catch(\Exception $e){

			Log::error("error occurs while deleting category");
			Log::error($e -> getMessage());

		}
	}


}
