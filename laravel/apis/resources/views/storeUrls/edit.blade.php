@extends('layouts.master')
@section('title', 'Edit StoreUrl')
@section('content')
    <nav class="navbar navbar-inverse">
        <ul class="nav navbar-nav">
            <li><a href="{{ URL::to('storeUrls') }}">View all storeUrls</a></li>
            <li><a href="{{ URL::to('storeUrls/create') }}">Create a storeUrl</a>
        </ul>
    </nav>

    <h3>Edit {{ $storeUrl->product_id }}</h3>

    <!-- if there are creation errors, they will show here -->
    {{ Html::ul($errors->all()) }}

    {{ Form::model($storeUrl, array('route' => array('storeUrls.update', $storeUrl->id), 'method' => 'PUT')) }}

    <div class="form-group">
        {{ Form::label('Product Id', 'Product Id') }}
        {{ Form::text('productId', $storeUrl->product_id, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('Category ', 'Select Category Id') }}
        {{ Form::select('categoryId', $categories, $storeUrl->category_id, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('Subcategory ', 'Select Subcategory Id') }}
        {{ Form::select('subcategoryId', $subcategories, $storeUrl->subcategory_id, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('Product Url', 'Product Url') }}
        {{ Form::text('productUrl', $storeUrl->product_url, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('Store ', 'Select Store') }}
        {{ Form::select('storeId', $stores, $storeUrl->store_id, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('Unique Identifier', 'Product Store Unique Identifier') }}
        {{ Form::text('uniqueIdentifier', $storeUrl->store_product_unique_identifier, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('Is Active ', 'Is Active') }}
        {{ Form::select('isActive', [0,1], $storeUrl->is_active, array('class' => 'form-control')) }}
    </div>


    {{ Form::submit('Edit the storeUrl!', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
@stop