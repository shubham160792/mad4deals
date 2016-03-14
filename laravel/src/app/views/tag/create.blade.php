@extends('layouts.master')
@section('title')
Create a Tag | 91Mobiles.com
@stop
@section('style-sheets')
@stop
@section('content-title')
Create a new tag 
@stop

@section('content')


<div class="dropzone-wrapper large-6">
	<body>

<div class="container">

{{ HTML::ul($errors->all()) }}

{{ Form::open(array('url' => 'tag')) }}

	<div class="form-group">
		{{ Form::label('name', 'Name') }}
		{{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
	</div>

    <div class="form-group">
        {{ Form::label('name', 'Choose a category *', array('for' => 'category', 'style' => 'clear:both'))}}
        {{ Form::select('category', Category::get_distinct_names(), array(), array('name' => 'category', 'class' => 'chosen addable select_category chosen-select-width', 'data-placeholder'=>"Choose a category"))}}
    </div>
<bR><br>
	

	{{ Form::submit('Create Tag!', array('class' => 'button')) }}

{{ Form::close() }}

</div>
</body>
</div>
@stop
@section('scripts')
{{ HTML::script(asset(Config::get('app.url_path')."js/script.js")); }}
@stop
