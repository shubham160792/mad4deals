@extends('layouts.master')
@section('title')
Caption Dashboard| 91Mobiles.com
@stop
@section('style-sheets')
{{ HTML::style(asset(Config::get('app.url_path')."css/jquery.dataTables.min.css")); }}
{{ HTML::style(asset(Config::get('app.url_path')."css/jquery.dataTables.min.res.css")); }}

@stop
@section('content-title')
Caption | Dashboard
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

		<a class="tiny button" href="{{ URL::to(Config::get('app.url_path')) }}" style="float:left;">Create new Gallery</a>

		<div class="container">
			<table border="2" id="example" style="width:100%;" >
				<thead>
					<tr>
						<td>ID</td>
						<td>Name</td>
						<td>Category</td>

						<td>Actions</td>
					</tr>
				</thead>

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