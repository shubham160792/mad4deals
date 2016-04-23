<?php

namespace App\Http\Controllers;

use App\Category;
use App\Footwear;
use App\Subcategory;
use Illuminate\Http\Request;
use App\Product;
use App\Http\Requests;
use Validator, Input, Redirect,Session;

class ProductController extends Controller
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
        $categoryId     = Input::get('category_id');
        $subcategoryId  = Input::get('subcategory_id');
        $tableName      = \DB::table('products')
                            ->where(['category_id' => $categoryId, 'subcategory_id' => $subcategoryId])
                            ->get();
        $tableName      = $tableName[0]->table_name;
        $category       = Category::find($categoryId);
        $subcategory    = Subcategory::find($subcategoryId);
        if(Input::get('pagination') == 'false'){
            // Get all the products
            $products   = \DB::table($tableName)->get();
        }else{
            //Get paginated products
            $products   = \DB::table($tableName)->paginate(5);
        }
        $raw=Input::get('raw');
        if($raw == 'true'){
            return $products;
        }else{
            $data = array(
                'products'        => $products,
                'tableName'       => $tableName,
                'category'        => $category,
                'subcategory'     => $subcategory
            );
            // load the view and pass the products
            return View('products.index')
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
        $categoryId     = Input::get('category_id');
        $subcategoryId  = Input::get('subcategory_id');
        $tableName      = \DB::table('products')
                            ->where(['category_id' => $categoryId, 'subcategory_id' => $subcategoryId])
                            ->get();
        $tableName      = $tableName[0]->table_name;
        $category       = Category::find($categoryId);
        $subcategory    = Subcategory::find($subcategoryId);
        $data = array(
            'tableName'     => $tableName,
            'category'      => $category,
            'subcategory'   => $subcategory
        );
        return View('products.create')->with($data);
    }

    /**
     * product a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $categoryId            = Input::get('category_id');
        $subcategoryId         = Input::get('subcategory_id');
        $tableName      = \DB::table('products')
            ->where(['category_id' => $categoryId, 'subcategory_id' => $subcategoryId])
            ->get();
        $tableName      = $tableName[0]->table_name;
        if($tableName == 'footwear'){
            $rules = array(
                'name'             => 'required',
                'brand'            => 'required',
                'gender'           => 'required',
                'imagesPath'       => 'required'
            );
        }
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::to('products/create?category_id='.$categoryId.'&subcategory_id='.$subcategoryId)
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            if($tableName == 'footwear'){
                $product                                       = new Footwear();
                $product->name                                 = Input::get('name');
                $product->brand                                = Input::get('brand');
                $product->gender                               = Input::get('gender');
                $product->images_path                          = Input::get('imagesPath');
                $product->save();
            }
            // redirect
            Session::flash('message', 'Successfully created product!');
            return Redirect::to('products?category_id='.$categoryId.'&subcategory_id='.$subcategoryId);
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
        $categoryId            = Input::get('category_id');
        $subcategoryId         = Input::get('subcategory_id');
        $tableName             = \DB::table('products')
            ->where(['category_id' => $categoryId, 'subcategory_id' => $subcategoryId])
            ->get();
        $tableName             = $tableName[0]->table_name;
        if($tableName == 'footwear'){
            $product           = Footwear::find($id);
        }
        return $product;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // get the product
        $categoryId            = Input::get('category_id');
        $subcategoryId         = Input::get('subcategory_id');
        $category              = Category::find($categoryId);
        $subcategory           = Subcategory::find($subcategoryId);
        $tableName             = \DB::table('products')
                                    ->where(['category_id' => $categoryId, 'subcategory_id' => $subcategoryId])
                                    ->get();
        $tableName             = $tableName[0]->table_name;
        if($tableName == 'footwear'){
            $product           = Footwear::find($id);
        }
        $data = array(
            'product'         => $product,
            'category'        => $category,
            'subcategory'     => $subcategory,
            'tableName'       => $tableName
        );
        // show the edit form and pass the product
        return View('products.edit')
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
            'categoryId'       => 'required',
            'subcategoryId'    => 'required',
            'name'             => 'required',
            'brand'            => 'required',
            'gender'           => 'required',
            'imagesPath'       => 'required'
        );
        $categoryId            = Input::get('categoryId');
        $subcategoryId         = Input::get('subcategoryId');
        $tableName             = \DB::table('products')
                                    ->where(['category_id' => $categoryId, 'subcategory_id' => $subcategoryId])
                                    ->get();
        $tableName             = $tableName[0]->table_name;
        $validator             = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::to('products/' . $id . '/edit?category_id='.$categoryId.'&subcategory_id='.$subcategoryId)
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // Product
            if($tableName == 'footwear'){
                $product                                       = Footwear::find($id);
                $product->name                                 = Input::get('name');
                $product->brand                                = Input::get('brand');
                $product->gender                               = Input::get('gender');
                $product->images_path                          = Input::get('imagesPath');
                $product->save();
            }
            // redirect
            Session::flash('message', 'Successfully updated product!');
            return Redirect::to('products?category_id='.$categoryId.'&subcategory_id='.$subcategoryId);
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
        $categoryId            = Input::get('category_id');
        $subcategoryId         = Input::get('subcategory_id');
        $tableName             = \DB::table('products')
                                    ->where(['category_id' => $categoryId, 'subcategory_id' => $subcategoryId])
                                    ->get();
        $tableName             = $tableName[0]->table_name;
        if($tableName == 'footwear'){
            $product           = Footwear::find($id);
            $product->delete();
        }
        // redirect
        Session::flash('message', 'Successfully deleted product!');
        return Redirect::to('products?category_id='.$categoryId.'&subcategory_id='.$subcategoryId);
    }
}
