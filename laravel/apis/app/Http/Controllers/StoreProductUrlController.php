<?php

namespace App\Http\Controllers;

use App\Category;
use App\Subcategory;
use Illuminate\Http\Request;
use App\StoreProductUrl;
use App\Store;
use App\Http\Requests;
use Validator, Input, Redirect,Session;

class StoreProductUrlController extends Controller
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
        if(Input::get('pagination') == 'false'){
            // Get all the stores
            $storeUrls = StoreProductUrl::all();
        }else{
            //Get paginated Stores
            $storeUrls = \DB::table('store_product_url')->paginate(5);
        }
        $raw=Input::get('raw');
        if($raw == 'true'){
            return $storeUrls;
        }else{
            $stores             = Store::getStores();
            $categories         = Category::getAllCategories();
            $subcategories      = Subcategory::getSubcategories();
            $data = array(
                'stores'        => $stores,
                'categories'    => $categories,
                'subcategories' => $subcategories,
                'storeUrls'     => $storeUrls
            );
            // load the view and pass the stores
            return View('storeUrls.index')
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
        $stores         = Store::lists('name','id');
        $categories     = Category::lists('name', 'id');
        $subcategories  = Subcategory::lists('name','id');
        $data = array(
            'stores'        => $stores,
            'categories'    => $categories,
            'subcategories' => $subcategories
        );
        return View('storeUrls.create')->with($data);
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
            'productId'             => 'required',
            'categoryId'            => 'required',
            'subcategoryId'         => 'required',
            'productUrl'            => 'required',
            'storeId'               => 'required',
            'uniqueIdentifier'      => 'required',
            'isActive'              => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('storeUrls/create')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // storeUrl
            $storeUrl                                       = new StoreProductUrl();
            $storeUrl->product_id                           = Input::get('productId');
            $storeUrl->category_id                          = Input::get('categoryId');
            $storeUrl->subcategory_id                       = Input::get('subcategoryId');
            $storeUrl->product_url                          = Input::get('productUrl');
            $storeUrl->store_id                             = Input::get('storeId');
            $storeUrl->store_product_unique_identifier      = Input::get('uniqueIdentifier');
            $storeUrl->is_active                            = Input::get('isActive');
            $storeUrl->save();

            // redirect
            Session::flash('message', 'Successfully created Store Urls!');
            return Redirect::to('storeUrls');
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
        $storeUrl = StoreProductUrl::find($id);
        return $storeUrl;
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
        // get the storeUrl
        $storeUrl           = StoreProductUrl::find($id);
        $stores             = Store::getStores();
        $categories         = Category::getAllCategories();
        $subcategories      = Subcategory::getSubcategories();
        $data = array(
            'stores'        => $stores,
            'categories'    => $categories,
            'subcategories' => $subcategories,
            'storeUrl'      => $storeUrl
        );
        // show the edit form and pass the storeUrl
        return View('storeUrls.edit')
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
        // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'productId'             => 'required',
            'categoryId'            => 'required',
            'subcategoryId'         => 'required',
            'productUrl'            => 'required',
            'storeId'               => 'required',
            'uniqueIdentifier'      => 'required',
            'isActive'              => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('storeUrls/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // storeUrl
            $storeUrl                                       = StoreProductUrl::find($id);
            $storeUrl->product_id                           = Input::get('productId');
            $storeUrl->category_id                          = Input::get('categoryId');
            $storeUrl->subcategory_id                       = Input::get('subcategoryId');
            $storeUrl->product_url                          = Input::get('productUrl');
            $storeUrl->store_id                             = Input::get('storeId');
            $storeUrl->store_product_unique_identifier      = Input::get('uniqueIdentifier');
            $storeUrl->is_active                            = Input::get('isActive');
            $storeUrl->save();

            // redirect
            Session::flash('message', 'Successfully updated StoreUrl!');
            return Redirect::to('storeUrls');
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
        $storeUrl = StoreProductUrl::find($id);
        $storeUrl->delete();

        // redirect
        Session::flash('message', 'Successfully deleted StoreUrl!');
        return Redirect::to('storeUrls');
    }
}
