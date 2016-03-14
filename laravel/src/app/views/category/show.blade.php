@extends('layouts.master')
@section('title')
Category Display| 91Mobiles.com
@stop
@section('style-sheets')
@stop
@section('content-title')
Displaying Category: {{ $category->name }}
@stop
@section('content')
<div class="dropzone-wrapper large-6">
<body>
<div class="container">


<h3>Showing {{ $category->name }}</h3>

	<div class="jumbotron text-center">
		<table border="2" style="width:100%;">
		<tr>
		<th>Name:</th><td> {{ $category->name }}</td>
		</tr>
		<tr>
		<th>Description:</th><td> {{ $category->description }}</td>
		</tr>
		</table>
	</div>

</div>
</body>
</div>
@stop
@section('scripts')
{{ HTML::script(asset(Config::get('app.url_path')."js/script.js")); }}
@stop