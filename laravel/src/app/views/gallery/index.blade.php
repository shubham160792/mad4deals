@extends('layouts.master')
@section('title')
Galleries Dashboard| 91Mobiles.com
@stop
@section('style-sheets')
{{ HTML::style(asset(Config::get('app.url_path')."css/jquery.dataTables.min.css")); }}
{{ HTML::style(asset(Config::get('app.url_path')."css/jquery.dataTables.min.res.css")); }}
@stop
@section('content-title')
Galleries | Dashboard
@stop
@section('content')
<body>
{{ Form::hidden('prod_id','', array('id' => 'gl_product_id')) }}
{{ Form::hidden('cat_name','',array('id' => 'category_name')) }}
{{ Form::hidden('cat_id','',array('id' => 'gl_category_id')) }}
<div id="product_name"  class="large-7 large-offset-1 columns">
	{{ Form::label('product_name', 'Enter Product name *', array('for' => 'product_name'))}}
	{{ Form::text('product', '', array('id' =>'gl_product','autocomplete' => 'off')) }}
</div>
<button id="go_for_gl" class="gl_get">Go!</button>
<div class="container">
@if (Session::has('message'))
<div data-alert class="alert-box">{{ Session::get('message') }}</div>
@endif
<table class="datatable" border="2"  id="example" style="width:100%;"  >
	<thead>
		<tr>
			<td>ID</td>
			<td>Name</td>
			<td>Description</td>
			<td>Author</td>
			<td>views</td>
			<td>Actions</td>
		</tr>
	</thead>
	<tbody>
	@foreach($gallery as $key => $value)
		<tr>
			<td>{{ $value->id }}</td>
			<td>{{ $value->name }}</td>
			<td>{{ $value->description }}</td>
			<td>{{ $value->author }}</td>
			<td>{{ $value->views }}</td>
			
			<td>

				
				<div class="row"> 
				<div class="small-2 large-4 columns" >
				<a target="_blank" class="tiny button button success" href="{{ URL::to(Config::get('app.url_path').'gallery/' . $value->id) }}" style="float:left;">Show</a>
				</div>
				<div class="small-2 large-4 columns" style="margin-left:20px;">
				<a target="_blank" class="tiny button" href="{{ URL::to(Config::get('app.url_path').'gallery/' . $value->id . '/edit') }}" style="float:left;">Edit</a>
				</div>
				<div class="small-2 large-4 columns" style="float:left;">
				{{ Form::open(array('url' => Config::get('app.url_path').'gallery/' . $value->id, 'class' => 'pull-right')) }}
					{{ Form::hidden('_method', 'DELETE') }}
					{{ Form::submit('Delete', array('class' => 'tiny button alert ')) }}
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

{{ HTML::script(asset(Config::get('app.url_path')."js/jquery.dataTables.min.js")); }}
{{ HTML::script(asset(Config::get('app.url_path')."js/jquery.dataTables.min.res.js")); }}
{{ HTML::script(asset(Config::get('app.url_path')."js/script.js")); }}
{{ HTML::script(asset(Config::get('app.url_path')."js/autocomplete.js")); }}
{{ HTML::script(asset(Config::get('app.url_path')."js/index.js")); }}
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
    } );
} );
</script>
@stop
