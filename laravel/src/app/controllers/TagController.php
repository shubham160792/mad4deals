<?php

//use Illuminate\Support\Facades\DB;

class TagController extends \BaseController {


    public function index()
    {
        try
        {
            if (Auth::check())
            {
                $tag = Tag::all();
                return View::make('tag.index')
                    ->with('tag', $tag);
            }
            else
            {
                return View::make(Config::get('app.url_path').'login');
            }

        }
        catch(\Exception $e){
            Log::error("error occurs while getting details of all tags");
            Log::error($e -> getMessage());

        }
    }

    public function create(){
        if (Auth::check()){
            return View::make('tag.create');

        }
        else{
            return View::make(Config::get('app.url_path').'login');
        }
    }
    public function store()
    {
        $rules = array(
            'name'       => 'required',
            'category'      => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::to(Config::get('app.url_path').'tag/create')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        }else{
            $result=DB::table('gl_tag')->where('name', '=',Input::get('name'))->count();
            if($result == 0){
                try{
                    $tag = new Tag;
                    $tag->name       = Input::get('name');
                    $tag->category   = Input::get('category');
                    $result=$tag->save();
                    $tagId=DB::getPdo() -> lastInsertId();
                    if($result == 1){
                        $results=DB::table('gl_category')->where('name', '=', $tag->category)->get();
                    }
                    foreach($results as $result){
                        $categoryId=$result->id;
                    }
                    if(isset($categoryId) && isset($tagId)){
                        $tempArray[] = array('tag_id' => $tagId, 'category_id' => $categoryId);
                        DB::table('gl_tag_category') -> insert($tempArray);
                    }
                    Session::flash('message', 'Successfully created Tag!');
                    return Redirect::to(Config::get('app.url_path').'tag');
                }
                catch(\Exception $e){
                    Log::error("error occurs while creating tag");
                    Log::error($e -> getMessage());
                }
            }else{
                Session::flash('message', 'Tag already exists!');
                return Redirect::to(Config::get('app.url_path').'tag/create');
            }
        }
    }


    public function show($id)
    {
        try
        {
            if (Auth::check()){
                $tag = Tag::find($id);

                return View::make('tag.show')
                    ->with('tag', $tag);
            }
            else{
                return View::make(Config::get('app.url_path').'login');
            }

        }
        catch(\Exception $e){
            Log::error("error occurs while showing Tag!");
            Log::error($e -> getMessage());
        }
    }


    public function edit($id)
    {
        try
        {

            if (Auth::check())
            {
                $tag = Tag::find($id);

                return View::make(Config::get('app.url_path').'tag.edit')
                    ->with('tag', $tag);

            }
            else
            {
                return View::make(Config::get('app.url_path').'login');
            }


        }
        catch(\Exception $e){

            Log::error("error occurs while getting content to edit tag");
            Log::error($e -> getMessage());

        }
    }


    public function update($id)
    {


        $rules = array(
            'name'       => 'required',
            'category'      => 'required',

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to(Config::get('app.url_path').'tag/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            try
            {
                $tag = Tag::find($id);
                $tag->name       = Input::get('name');
                $tag->category      = Input::get('category');
                $tag->save();

                // redirect
                Session::flash('message', 'Successfully updated tag!');
                return Redirect::to(Config::get('app.url_path').'tag');
            }
            catch(\Exception $e){

                Log::error("error occurs while updating tag");
                Log::error($e -> getMessage());

            }
        }

    }


    public function destroy($id)
    {
        try{
            $tag = Tag::find($id);
            $tag->delete();
            DB::table('gl_tag_category') -> where('tag_id', $id) -> delete();
            Session::flash('message', 'Successfully deleted Tag!');
            return Redirect::to(Config::get('app.url_path').'tag');
        }
        catch(\Exception $e){
            Log::error("error occurs while deleting tag");
            Log::error($e -> getMessage());
        }
    }


}
