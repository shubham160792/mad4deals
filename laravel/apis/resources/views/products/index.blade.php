@extends('layouts.master')
@section('title', 'Products')
@section('content')
    <nav class="navbar navbar-inverse">
        <ul class="nav navbar-nav">
            <li><a href="{{ URL::to('products/create?category_id='.$category->id.'&subcategory_id='.$subcategory->id) }}">Create new Product</a>
        </ul>
    </nav>
    <h3>All the products</h3>

    <!-- will be used to show any messages -->
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif
    <div style="width:100%;overflow: scroll;border:2px;">
        <h3>Category: {{$category->name }} </h3>
        <h3>Subcategory: {{$subcategory->name }} </h3>
        @if ($tableName === 'footwear')
            <table id="dt" style="width:100%;" class="responsive table table-striped table-bordered">
                <thead>
                <tr>
                    <td>ID</td>
                    <td>Name</td>
                    <td>Brand</td>
                    <td>Gender</td>
                    <td>Images Path</td>
                    <td>Created at</td>
                    <td>Updated at</td>
                    <td>Show</td>
                    <td>Edit</td>
                    <td>Delete</td>
                </tr>
                </thead>
                <tbody>
                @foreach($products as $key => $value)
                    <tr>
                        <td>{{ $value->id }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->brand }}</td>
                        <td>{{ $value->gender }}</td>
                        <td>{{ $value->images_path }}</td>
                        <td>{{ $value->created_at }}</td>
                        <td>{{ $value->updated_at }}</td>


                        <!-- we will also add show, edit, and delete buttons -->
                        <td>
                            <!-- show the nerd (uses the show method found at GET /products/{id} -->
                            <a  href="{{ URL::to('products/' . $value->id.'?category_id='.$category->id.'&subcategory_id='.$subcategory->id) }}">
                                <button class="btn-sm btn-success">
                                    <i class="glyphicon glyphicon-list"></i>
                                </button>
                            </a>
                        </td>
                        <td>
                            <!-- edit this product (uses the edit method found at GET /products/{id}/edit -->
                            <a href="{{ URL::to('products/' . $value->id . '/edit?category_id='.$category->id.'&subcategory_id='.$subcategory->id) }}">
                                <button class="btn-sm btn-warning" >
                                    <i class="glyphicon glyphicon-edit"></i>
                                </button>
                            </a>
                        </td>
                        <td>
                            <!-- we will add this later since its a little more complicated than the other two buttons -->
                            {{ Form::open(array('url' => 'products/' . $value->id.'?category_id='.$category->id.'&subcategory_id='.$subcategory->id, 'class' => 'pull-left')) }}
                            {{ Form::hidden('_method', 'DELETE') }}
                            {{ Form::submit('x', array('class' => 'btn-danger btn-sm alerts')) }}
                            {{ Form::close() }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
        {!! $products->appends(Input::except('page'))->render() !!}
    </div>
@stop