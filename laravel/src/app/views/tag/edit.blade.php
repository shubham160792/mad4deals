@extends('layouts.master')
@section('title')
Edit a Tag | 91Mobiles.com
@stop
@section('style-sheets')
@stop
@section('content-title')
Edit Tag: {{ $tag->name }}
@stop

@section('content')


<div class="dropzone-wrapper large-6">

<body>

<div class="container">
{{ HTML::ul($errors->all()) }}

{{ Form::model($tag, array('route' => array('tag.update', $tag->id), 'method' => 'PUT')) }}

	<div class="form-group">
		{{ Form::label('name', 'Name') }}
		{{ Form::text('name', null, array('class' => 'form-control')) }}
	</div>

	<div class="form-group">
		{{ Form::label('Category', 'Category') }}
		{{ Form::text('category', null, array('class' => 'form-control')) }}
	</div>

	

	{{ Form::submit('Edit the Tag!', array('class' => 'button')) }}

{{ Form::close() }}

</div>
</body>
</div>
@stop
@section('scripts')
{{ HTML::script(asset(Config::get('app.url_path')."js/script.js")); }}
@stop