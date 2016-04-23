@extends('layouts.master')
@section('title', 'Edit Product')
@section('content')
    <nav class="navbar navbar-inverse">
        <ul class="nav navbar-nav">
            <li><a href="{{ URL::to('products?category_id='.$category->id.'&subcategory_id='.$subcategory->id) }}">View all Products</a></li>
            <li><a href="{{ URL::to('products/create?category_id='.$category->id.'&subcategory_id='.$subcategory->id) }}">Create a product</a>
        </ul>
    </nav>
    <h3>Edit {{ $product->name }}</h3>
    <!-- if there are creation errors, they will show here -->
    {{ Html::ul($errors->all()) }}

    {{ Form::model($product, array('route' => array('products.update',$product->id), 'method' => 'PUT')) }}

    <div class="form-group">
        {{ Form::label('CategoryId', 'Category Id') }}
        {{ Form::text('categoryId', $category->id, array('class' => 'form-control', 'readonly' => 'true')) }}
    </div>

    <div class="form-group">
        {{ Form::label('Subcategory Id', 'Subcategory Id') }}
        {{ Form::text('subcategoryId', $subcategory->id, array('class' => 'form-control', 'readonly' => 'true')) }}
    </div>

    <div class="form-group">
        {{ Form::label('Product name', 'Product name') }}
        {{ Form::text('name', $product->name, array('class' => 'form-control'),['disabled'=>'disabled']) }}
    </div>

    <div class="form-group">
        {{ Form::label('Product brand', 'Product brand') }}
        {{ Form::text('brand', $product->brand, array('class' => 'form-control'),['disabled'=>'disabled']) }}
    </div>

    <div class="form-group">
        {{ Form::label('Gender ', 'Select Gender ') }}
        {{ Form::select('gender', array('Male' => 'Male', 'Female' => 'Female','Unisex'=>'Unisex'), $product->gender, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('Product Images Path', 'Product Images Path') }}
        {{ Form::text('imagesPath', $product->images_path, array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Edit the Product!', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
@stop