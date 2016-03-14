@extends('layouts.master')
@section('title', 'Edit Store')
@section('content')
    <nav class="navbar navbar-inverse">
        <ul class="nav navbar-nav">
            <li><a href="{{ URL::to('stores') }}">View all stores</a></li>
            <li><a href="{{ URL::to('stores/create') }}">Create a store</a>
        </ul>
    </nav>

    <h1>Edit {{ $store->name }}</h1>
    <!-- if there are creation errors, they will show here -->
    {{ Html::ul($errors->all()) }}

    {{ Form::model($store, array('route' => array('stores.update', $store->id), 'method' => 'PUT')) }}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('Revenue Type', 'Revenue Type') }}
        {{ Form::select('revenueType',['CPC'=>'CPC','CPS'=>'CPS'], $store->revenue_type, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('Is Active Store', 'Is Active Store') }}
        {{ Form::select('isActive',[0,1], $store->is_active, array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Edit the store!', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
@stop
