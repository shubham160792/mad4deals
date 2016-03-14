<?php

//Config::get('app.url_path')
/* IMAGES */
Route::delete(Config::get('app.url_path').'image/{id}', 'ItemController@destroy');

/* To get images data */


Route::get(Config::get('app.url_path').'image/{id}', 'ItemController@get');

/* To update images data */

Route::put(Config::get('app.url_path').'image/{id}', 'ItemController@update');

/* To delete images */

Route::delete(Config::get('app.url_path').'image/{id}', 'ItemController@destroy');

/* To create images data */

Route::post(Config::get('app.url_path').'image', 'ItemController@upload');


/* Category */
Route::resource(Config::get('app.url_path').'category','CategoryController');
/*Tag */
Route::resource(Config::get('app.url_path').'tag','TagController');
/*Caption */
Route::resource(Config::get('app.url_path').'caption','CaptionController');
/* Gallery */
Route::resource(Config::get('app.url_path').'gallery','GalleryController');
/* Top 200 */
Route::get(Config::get('app.url_path').'top200', function()
{
    return View::make(Config::get('app.url_path').'top200/index');
    //return Redirect::to(Config::get('app.url_path').'top200/index');
});

Event::listen('illuminate.query', function($sql) {
    // var_dump($sql);
});

/* MAIN */

Route::get(Config::get('app.url_path'), 'GalleryController@create');





Route::get(Config::get('app.url_path').'logout', function()
{
    Auth::logout();
    return Redirect::to(Config::get('app.url_path').'login');
});
Route::get(Config::get('app.url_path').'login', function()
{
    return View::make(Config::get('app.url_path').'login');
});



Route::post(Config::get('app.url_path').'login', function()
{
    $userdata = array(
        'username' => Input::get('username'),
        'password' => Input::get('password')
        );
    
    $remember = Input::get('remember') == 'on' ? true : false;
    if(Auth::attempt($userdata, $remember))
    {
            //return View::make(Config::get('app.url_path').'create_gallery');
        return Redirect::to(Config::get('app.url_path'));
    }
    else
    {
        return Redirect::to(Config::get('app.url_path').'login');
    }
});

Route::get(Config::get('app.url_path').'allProducts/{page}', ['uses' =>'AllProductsAttributesController@index']);
Route::get(Config::get('app.url_path').'top200Products/{page}', ['uses' =>'AllProductsAttributesController@top200Index']);
Route::get(Config::get('app.url_path').'download/{page}',['uses' =>'AllProductsAttributesController@download']);
Route::get(Config::get('app.url_path').'contentUploadSummary/{week}/{page}',['uses' =>'AllProductsAttributesController@contentSummary']);
?>
