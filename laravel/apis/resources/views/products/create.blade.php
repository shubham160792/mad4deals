@extends('layouts.master')
@section('title', 'Create New Product')
@section('content')
    <nav class="navbar navbar-inverse">
        <ul class="nav navbar-nav">
            <li><a href="{{ URL::to('products?category_id='.$category->id.'&subcategory_id='.$subcategory->id) }}">View all Products</a></li>
        </ul>
    </nav>
    <h3>Create New Product</h3>

    <!-- if there are creation errors, they will show here -->
    {{ Html::ul($errors->all()) }}
    @if ($tableName === 'footwear')

        {{ Form::open(array('url' => 'products?category_id='.$category->id.'&subcategory_id='.$subcategory->id)) }}

        <div class="form-group">
            {{ Form::label('Product Name', 'Product Name') }}
            {{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
        </div>

        <div class="form-group">
            {{ Form::label('Product Brand', 'Product Brand') }}
            {{ Form::text('brand', Input::old('brand'), array('class' => 'form-control')) }}
        </div>

        <div class="form-group">
            {{ Form::label('Gender', 'Gender') }}
            {{ Form::select('gender', array('1' => 'Male', '2' => 'Female','3'=>'Unisex'), Input::old('gender'), array('class' => 'form-control')) }}
        </div>

        <div class="form-group">
            {{ Form::label('Images Paths', 'Images Paths') }}
            {{ Form::text('imagesPath', Input::old('imagesPath'), array('class' => 'form-control')) }}
        </div>

            {{ Form::submit('Create new Product!', array('class' => 'btn btn-primary')) }}

        {{ Form::close() }}
    @endif
@stop
