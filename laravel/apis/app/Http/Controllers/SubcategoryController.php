<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subcategory;
use App\Category;
use App\Http\Requests;
use Validator, Input, Redirect,Session;

class SubcategoryController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all the subcategories
        $subcategories = \DB::table('subcategories')->paginate(5);
        //$subcategories = Subcategory::all();
        $raw=Input::get('raw');
        if($raw == 'true'){
            return $subcategories;
        }else{
            $categories = Category::getAllCategories();
            $data = array(
                'subcategories'  => $subcategories,
                'categories'   => $categories
            );
            // load the view and pass the data array
            return View('subcategories.index')
                ->with($data);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::lists('name', 'id');
        return View('subcategories.create')->with('categories',$categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'name'       => 'required',
            'categoryId' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('subcategories/create')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            //Subcategory
            $subcategory                  = new Subcategory();
            $subcategory->name            = Input::get('name');
            $subcategory->category_id     = Input::get('categoryId');
            $subcategory->save();

            // redirect
            Session::flash('message', 'Successfully created Subcategory!');
            return Redirect::to('subcategories');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // get the Subcategory
        $subcategory = Subcategory::find($id);
        return $subcategory;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // get the Subcategory Id
        $subcategory = Subcategory::find($id);
        $categories  = Category::lists('name', 'id');
        $data = array(
            'subcategory'  => $subcategory,
            'categories'   => $categories
        );
        // show the edit form and pass the dataArray
        return View('subcategories.edit')
            ->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'name'             => 'required',
            'categoryId'       => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('subcategories/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // Subcategory
            $subcategory                 = Subcategory::find($id);
            $subcategory->name           = Input::get('name');
            $subcategory->category_id    = Input::get('categoryId');
            $subcategory->save();

            // redirect
            Session::flash('message', 'Successfully updated Subcategory!');
            return Redirect::to('subcategories');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete
        $subcategory = Subcategory::find($id);
        $subcategory->delete();

        // redirect
        Session::flash('message', 'Successfully deleted Subcategory!');
        return Redirect::to('subcategories');
    }
}
