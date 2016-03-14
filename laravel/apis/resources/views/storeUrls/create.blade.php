@extends('layouts.master')
@section('title', 'Create New StoreUrl')
@section('content')
    <nav class="navbar navbar-inverse">
        <ul class="nav navbar-nav">
            <li><a href="{{ URL::to('storeUrls') }}">View all storeUrls</a></li>
        </ul>
    </nav>

    <h3>Create New StoreUrl</h3>

    <!-- if there are creation errors, they will show here -->
    {{ Html::ul($errors->all()) }}

    {{ Form::open(array('url' => 'storeUrls')) }}

    <div class="form-group">
        {{ Form::label('Product Id', 'Product Id') }}
        {{ Form::text('productId', Input::old('productId'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('Category ', 'Select Category Id') }}
        {{ Form::select('categoryId', $categories, null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('Subcategory ', 'Select Subcategory Id') }}
        {{ Form::select('subcategoryId', $subcategories, null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('Product Url', 'Product Url') }}
        {{ Form::text('productUrl', Input::old('productUrl'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('Store ', 'Select Store') }}
        {{ Form::select('storeId', $stores, null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('Unique Identifier', 'Product Store Unique Identifier') }}
        {{ Form::text('uniqueIdentifier', Input::old('uniqueIdentifier'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('Is Active ', 'Is Active') }}
        {{ Form::select('isActive', [0,1], null, array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Create new StoreUrl!', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
@stop