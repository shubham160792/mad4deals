@extends('layouts.master')
@section('title')
Edit a Category | 91Mobiles.com
@stop
@section('style-sheets')
@stop
@section('content-title')
Edit Category: {{ $category->name }}
@stop

@section('content')


<div class="dropzone-wrapper large-6">

<body>

<div class="container">
{{ HTML::ul($errors->all()) }}

{{ Form::model($category, array('route' => array('category.update', $category->id), 'method' => 'PUT')) }}

	<div class="form-group">
		{{ Form::label('name', 'Name') }}
		{{ Form::text('name', null, array('class' => 'form-control')) }}
	</div>

	<div class="form-group">
		{{ Form::label('Description', 'Description') }}
		{{ Form::text('description', null, array('class' => 'form-control')) }}
	</div>

	

	{{ Form::submit('Edit the Category!', array('class' => 'button')) }}

{{ Form::close() }}

</div>
</body>
</div>
@stop
@section('scripts')
{{ HTML::script(asset(Config::get('app.url_path')."js/script.js")); }}
@stop