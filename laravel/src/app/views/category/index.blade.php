@extends('layouts.master')
@section('title')
Categories Dashboard| 91Mobiles.com
@stop
@section('style-sheets')
{{ HTML::style(asset(Config::get('app.url_path')."css/jquery.dataTables.min.css")); }}
{{ HTML::style(asset(Config::get('app.url_path')."css/jquery.dataTables.min.res.css")); }}

@stop
@section('content-title')
Categories | Dashboard
@stop
<head>
	
	<style type="text/css">
		.btn
		{
			padding: 1px;
			float: left;

		}
	</style>

</head>
@section('content')
@if (Session::has('message'))
<div data-alert class="alert-box">{{ Session::get('message') }}</div>
@endif
<body>
	<div class="container">

		<a class="tiny button" href="{{ URL::to(Config::get('app.url_path').'category/create') }}" style="float:left;">Create a Category</a>

		<div class="container">
			<table border="2" id="example" style="width:100%;" >
				<thead>
					<tr>
						<td>ID</td>
						<td>Name</td>
						<td>Description</td>
						
						<td>Actions</td>
					</tr>
				</thead>
				<tbody>
					@foreach($category as $key => $value)
					<tr>
						<td>{{ $value->id }}</td>
						<td>{{ $value->name }}</td>
						<td>{{ $value->description }}</td>
						
						<td>
						<div class="row">
							<div class="small-2 large-4 columns" >
							<a class="tiny button button success" href="{{ URL::to(Config::get('app.url_path').'category/' . $value->id) }}" >Show</a>
							</div>
							<div class="small-2 large-4 columns" style="margin-left:20px;">
							<a class="tiny button" href="{{ URL::to(Config::get('app.url_path').'category/' . $value->id . '/edit') }}" >Edit</a>
							</div>
							<div class="small-2 large-4 columns" style="float:left;">
								{{ Form::open(array('url' => Config::get('app.url_path').'category/' . $value->id)) }}
								{{ Form::hidden('_method', 'DELETE') }}
								{{ Form::submit('Delete', array('class' => 'tiny button alert')) }}
								{{ Form::close() }}
							</div>
						</div>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</body>
	@stop
	@section('scripts')
	{{ HTML::script(asset(Config::get('app.url_path')."js/script.js")); }}
	{{ HTML::script(asset(Config::get('app.url_path')."js/jquery.dataTables.min.js")); }}
	{{ HTML::script(asset(Config::get('app.url_path')."js/jquery.dataTables.min.res.js")); }}

	<script>

		$(document).ready(function(){
			$(".alert").click(function(){

				if (!confirm("Do you want to delete")){
					return false;
				}
			});
		});
	</script>
	<script type="text/javascript">

		$(document).ready(function() {
			 $('#example').DataTable( {
        	responsive: true
   		 	});
		});
	</script>
	@stop