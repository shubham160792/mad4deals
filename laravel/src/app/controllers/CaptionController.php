<?php

//use Illuminate\Support\Facades\DB;

class CaptionController extends \BaseController {


    public function index()
    {
        try
        {
            if (Auth::check())
            {
                $caption = Caption::all();
                return View::make('caption.index')
                    ->with('caption', $caption);
            }
            else
            {
                return View::make(Config::get('app.url_path').'login');
            }

        }
        catch(\Exception $e){
            Log::error("error occurs while getting details of all caption");
            Log::error($e -> getMessage());

        }
    }

    public function create(){
        if (Auth::check()){
            return View::make('caption.create');

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
            return Redirect::to(Config::get('app.url_path').'caption/create')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        }
        else{
            $result=DB::table('gl_caption')->where('name', '=',Input::get('name'))->count();
            if($result == 0){
                try{
                    $caption = new Caption;
                    $caption->name       = Input::get('name');
                    $caption->category   = Input::get('category');
                    $result=$caption->save();
                    $captionId=DB::getPdo() -> lastInsertId();
                    if($result == 1){
                        $results=DB::table('gl_category')->where('name', '=', $caption->category)->get();
                    }
                    foreach($results as $result){
                        $categoryId=$result->id;
                    }
                    if(isset($categoryId) && isset($captionId)){
                        $tempArray[] = array('caption_id' => $captionId, 'category_id' => $categoryId);
                        DB::table('gl_caption_category') -> insert($tempArray);
                    }
                    Session::flash('message', 'Successfully created Caption!');
                    return Redirect::to(Config::get('app.url_path').'caption');
                }
                catch(\Exception $e){
                    Log::error("error occurs while creating caption");
                    Log::error($e -> getMessage());
                }
            }else{
                $success = Session::get('success');
                //Session::flash('message', 'Caption already exists!');
                return Redirect::to(Config::get('app.url_path').'caption/create')->with('success', $success);
            }
        }
    }


    public function show($id)
    {
        try
        {
            if (Auth::check()){
                $caption = Caption::find($id);

                return View::make('caption.show')
                    ->with('caption', $caption);
            }
            else{
                return View::make(Config::get('app.url_path').'login');
            }

        }
        catch(\Exception $e){
            Log::error("error occurs while showing Caption!");
            Log::error($e -> getMessage());
        }
    }


    public function edit($id)
    {
        try
        {

            if (Auth::check())
            {
                $caption = Caption::find($id);

                return View::make(Config::get('app.url_path').'caption.edit')
                    ->with('caption', $caption);

            }
            else
            {
                return View::make(Config::get('app.url_path').'login');
            }


        }
        catch(\Exception $e){

            Log::error("error occurs while getting content to edit caption");
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
            return Redirect::to(Config::get('app.url_path').'caption/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            try
            {
                $caption = Caption::find($id);
                $caption->name       = Input::get('name');
                $caption->category      = Input::get('category');
                $caption->save();

                // redirect
                Session::flash('message', 'Successfully updated caption!');
                return Redirect::to(Config::get('app.url_path').'caption');
            }
            catch(\Exception $e){

                Log::error("error occurs while updating caption");
                Log::error($e -> getMessage());

            }
        }

    }


    public function destroy($id)
    {
        try{
            $caption = Caption::find($id);
            $caption->delete();
            DB::table('gl_caption_category') -> where('caption_id', $id) -> delete();
            Session::flash('message', 'Successfully deleted Caption!');
            return Redirect::to(Config::get('app.url_path').'caption');
        }
        catch(\Exception $e){
            Log::error("error occurs while deleting caption");
            Log::error($e -> getMessage());
        }
    }


}
