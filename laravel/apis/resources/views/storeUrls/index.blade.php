@extends('layouts.master')
@section('title', 'StoreUrls')
@section('content')
    <nav class="navbar navbar-inverse">
        <ul class="nav navbar-nav">
            <li><a href="{{ URL::to('storeUrls/create') }}">Create new storeUrl</a>
        </ul>
    </nav>
    <h3>All the storeUrls</h3>

    <!-- will be used to show any messages -->
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif
    <div style="width:100%;overflow: scroll;border:2px;">
        <table id="dt" style="width:100%;" class="responsive table table-striped table-bordered">
            <thead>
            <tr>
                <td>ID</td>
                <td>Product Id</td>
                <td>Category</td>
                <td>Subcategory</td>
                <td>Product Url</td>
                <td>Store</td>
                <td>Unique Identifier</td>
                <td>Is Active</td>
                <td>created at</td>
                <td>updated at</td>
                <td>Show</td>
                <td>Edit</td>
                <td>Delete</td>
            </tr>
            </thead>
            <tbody>
            @foreach($storeUrls as $key => $value)
                <tr>
                    <td>{{ $value->id }}</td>
                    <td>{{ $value->product_id }}</td>
                    <td>{{ $categories[$value->category_id] }}</td>
                    <td>{{ $subcategories[$value->subcategory_id] }}</td>
                    <td>{{ $value->product_url }}</td>
                    <td>{{ $stores[$value->store_id] }}</td>
                    <td>{{ $value->store_product_unique_identifier }}</td>
                    <td>{{ $value->is_active }}</td>
                    <td>{{ $value->created_at }}</td>
                    <td>{{ $value->updated_at }}</td>



                    <!-- we will also add show, edit, and delete buttons -->
                    <td>
                        <!-- show the nerd (uses the show method found at GET /storeUrls/{id} -->
                        <a  href="{{ URL::to('storeUrls/' . $value->id) }}">
                            <button class="btn-sm btn-success">
                                <i class="glyphicon glyphicon-list"></i>
                            </button>
                        </a>
                    </td>
                    <td>
                        <!-- edit this store (uses the edit method found at GET /storeUrls/{id}/edit -->
                        <a href="{{ URL::to('storeUrls/' . $value->id . '/edit') }}">
                            <button class="btn-sm btn-warning" >
                                <i class="glyphicon glyphicon-edit"></i>
                            </button>
                        </a>
                    </td>
                    <td>
                        <!-- we will add this later since its a little more complicated than the other two buttons -->
                        {{ Form::open(array('url' => 'storeUrls/' . $value->id, 'class' => 'pull-left')) }}
                        {{ Form::hidden('_method', 'DELETE') }}
                        {{ Form::submit('x', array('class' => 'btn-danger btn-sm alerts')) }}
                        {{ Form::close() }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {!! $storeUrls->render() !!}
    </div>
@stop