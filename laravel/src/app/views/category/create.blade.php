@extends('layouts.master')
@section('title')
Create a Category | 91Mobiles.com
@stop
@section('style-sheets')
@stop
@section('content-title')
Create a new category 
@stop

@section('content')


<div class="dropzone-wrapper large-6">
	<body>

<div class="container">

{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'category')) }}

	<div class="form-group">
		{{ Form::label('name', 'Name') }}
		{{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
	</div>

	<div class="form-group">
		{{ Form::label('Description', 'Description') }}
		{{ Form::text('description', Input::old('description'), array('class' => 'form-control')) }}
	</div>

	

	{{ Form::submit('Create Category!', array('class' => 'button')) }}

{{ Form::close() }}

</div>
</body>
</div>
@stop
@section('scripts')
{{ HTML::script(asset(Config::get('app.url_path')."js/script.js")); }}
@stop
