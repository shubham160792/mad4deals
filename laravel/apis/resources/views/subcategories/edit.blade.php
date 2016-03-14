@extends('layouts.master')
@section('title', 'Edit Subcategory')
@section('content')
    <nav class="navbar navbar-inverse">
        <ul class="nav navbar-nav">
            <li><a href="{{ URL::to('subcategories') }}">View all subcategories</a></li>
            <li><a href="{{ URL::to('subcategories/create') }}">Create a subcategory</a>
        </ul>
    </nav>

    <h1>Edit {{ $subcategory->name }}</h1>

    <!-- if there are creation errors, they will show here -->
    {{ Html::ul($errors->all()) }}

    {{ Form::model($subcategory, array('route' => array('subcategories.update', $subcategory->id), 'method' => 'PUT')) }}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('Select a Category', 'Select Category') }}
        {{ Form::select('categoryId',$categories,$subcategory->category_id, array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Edit the subcategory!', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
@stop