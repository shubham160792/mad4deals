@extends('layouts.master')
@section('title', 'Create new category')
@section('content')
    <nav class="navbar navbar-inverse">
        <ul class="nav navbar-nav">
            <li><a href="{{ URL::to('categories') }}">View all categories</a></li>
        </ul>
    </nav>

    <h1>Create New category</h1>

    <!-- if there are creation errors, they will show here -->
    {{ Html::ul($errors->all()) }}

    {{ Form::open(array('url' => 'categories')) }}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Create new category!', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
@stop