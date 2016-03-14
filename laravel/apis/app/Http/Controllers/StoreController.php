<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Store;
use App\Http\Requests;
use Auth,Validator, Input, Redirect,Session;

class StoreController extends Controller
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
        // get all the stores
        $stores = \DB::table('stores')->paginate(5);
        //$stores = Store::all();

        $raw=Input::get('raw');
        if($raw == 'true'){
            return $stores;
        }else{
            // load the view and pass the stores
            return View('stores.index')
                ->with('stores', $stores);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('stores.create');
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
            'name'             => 'required',
            'revenueType'      => 'required',

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('stores/create')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // store
            $store                    = new Store;
            $store->name              = Input::get('name');
            $store->revenue_type      = Input::get('revenueType');
            $store->save();

            // redirect
            Session::flash('message', 'Successfully created Store!');
            return Redirect::to('stores');
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
        // get the Store
        $store = Store::find($id);
        return $store;
        // show the view and pass the nerd to it
        //        return View::make('nerds.show')
        //            ->with('nerd', $nerd);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // get the store
        $store = Store::find($id);

        // show the edit form and pass the store
        return View('stores.edit')
            ->with('store', $store);
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
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'name'             => 'required',
            'revenueType'      => 'required',
            'isActive'         => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('stores/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // store
            $store                    = Store::find($id);
            $store->name              = Input::get('name');
            $store->revenue_type      = Input::get('revenueType');
            $store->is_active         = Input::get('isActive');
            $store->save();

            // redirect
            Session::flash('message', 'Successfully updated store!');
            return Redirect::to('stores');
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
        $store = Store::find($id);
        $store->delete();

        // redirect
        Session::flash('message', 'Successfully deleted store!');
        return Redirect::to('stores');
    }
}
