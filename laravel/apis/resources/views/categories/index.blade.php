@extends('layouts.master')
@section('title', 'Categories')
@section('content')
    <nav class="navbar navbar-inverse">
        <ul class="nav navbar-nav">
            <li><a href="{{ URL::to('categories/create') }}">Create new category</a>
        </ul>
    </nav>
    <h3>All the categories</h3>
    <!-- will be used to show any messages -->
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif

    <table id="dt" class="table table-striped table-bordered">
        <thead>
        <tr>
            <td>Category Id</td>
            <td>Name</td>
            <td>Created At</td>
            <td>Updated At</td>
            <td>Show</td>
            <td>Edit</td>
            <td>Delete</td>
        </tr>
        </thead>
        <tbody>
        @foreach($categories as $key => $value)
            <tr>
                <td>{{ $value->id }}</td>
                <td>{{ $value->name }}</td>
                <td>{{ $value->created_at }}</td>
                <td>{{ $value->updated_at }}</td>

                <!-- we will also add show, edit, and delete buttons -->
                <td>
                    <!-- show the nerd (uses the show method found at GET /categories/{id} -->
                    <a  href="{{ URL::to('categories/' . $value->id) }}">
                        <button class="btn-sm btn-success">
                            <i class="glyphicon glyphicon-list"></i>
                        </button>
                    </a>
                </td>
                <td>
                    <!-- edit this category (uses the edit method found at GET /categories/{id}/edit -->
                    <a   href="{{ URL::to('categories/' . $value->id . '/edit') }}">
                        <button class="btn-sm btn-warning">
                            <i class="glyphicon glyphicon-edit"></i>
                        </button>
                    </a>
                </td>
                <td>
                    <!-- we will add this later since its a little more complicated than the other two buttons -->
                    {{ Form::open(array('url' => 'categories/' . $value->id, 'class' => 'pull-left')) }}
                    {{ Form::hidden('_method', 'DELETE') }}
                    {{ Form::submit('x', array('class' => 'btn-danger btn-sm alerts')) }}
                    {{ Form::close() }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {!! $categories->render() !!}
@stop