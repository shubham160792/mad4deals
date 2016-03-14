@extends('layouts.master')
@section('title', 'Create New Store')
@section('content')
    <nav class="navbar navbar-inverse">
        <ul class="nav navbar-nav">
            <li><a href="{{ URL::to('stores') }}">View all stores</a></li>
        </ul>
    </nav>

    <h1>Create New Store</h1>

    <!-- if there are creation errors, they will show here -->
    {{ Html::ul($errors->all()) }}

    {{ Form::open(array('url' => 'stores')) }}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('revenueType', 'Select Revenue Type') }}
        {{ Form::select('revenueType', array('0' => 'Select revenue type', '1' => 'CPC', '2' => 'CPS'), Input::old('revenueType'), array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Create new Store!', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
@stop