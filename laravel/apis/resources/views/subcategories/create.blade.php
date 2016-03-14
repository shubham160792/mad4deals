@extends('layouts.master')
@section('title', 'Create New Subcategory')
@section('content')
    <nav class="navbar navbar-inverse">
        <ul class="nav navbar-nav">
            <li><a href="{{ URL::to('subcategories') }}">View all Subcategories</a></li>
        </ul>
    </nav>

    <h1>Create New subcategory</h1>

    <!-- if there are creation errors, they will show here -->
    {{ Html::ul($errors->all()) }}

    {{ Form::open(array('url' => 'subcategories')) }}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('Category Name', 'Select Category Name') }}
        {{ Form::select('categoryId', $categories,null,array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Create new subcategory!', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
@stop