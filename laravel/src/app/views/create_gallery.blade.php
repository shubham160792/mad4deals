@extends('layouts.master')
@section('title')
Create a Gallery | 91Mobiles.com
@stop
@section('style-sheets')
<?php echo HTML::style(asset(Config::get('app.url_path')."css/jquery-ui.css")); ?>

@stop
@section('content-title')
Create a new gallery 
@stop
@section('content-caption')
Simply start by adding some images/videos. You can add or delete gallery content by updating  gallery.
@stop
@section('content')
<div class="dropzone-wrapper large-6">
	<div data-url='/create' class='gallery_create_form'>
		{{ Form::label('name', 'Enter name *', array('for' => 'name'))}}
		{{ Form::text('name', '', array('class' => 'text', 'id' => 'gl_name','placeholder' => 'Choose a name for your gallery'))}}
		{{ Form::label('url', 'Enter URL *', array('for' => 'url'))}}
		{{ Form::text('name', '', array('class' => 'text', 'id' => 'gl_url','placeholder' => 'Choose a url for your gallery'))}}
		{{ Form::label('desc', 'Enter Description ', array('for' => 'desc'))}}
		{{ Form::text('name', '', array('class' => 'text', 'id' => 'gl_description','placeholder' => 'Write something which describes this gallery'))}}
		<div class="row">
			<div class="large-4 columns"> 
				{{ Form::label('name', 'Select Type *', array('for' => 'name'))}}
				{{ Form::select('name', ['product' => 'product','general' => 'general'], array(),array('id' => 'gl_type' ) ) }}
			</div>
			<div id="product_name"  class="large-7 large-offset-1 columns">
				{{ Form::label('product_name', 'Enter Product name *', array('for' => 'product_name'))}}
				{{ Form::text('product', '', array('id' =>'gl_product','autocomplete' => 'off')) }}
			</div>
			<div class="large-4 columns">
                {{ Form::label('name', 'Select Item Type *', array('for' => 'name'))}}
                {{ Form::select('name', [\App\constants\ItemType::$ImageType => 'image',\App\constants\ItemType::$YoutubeType => 'youtube',\App\constants\ItemType::$SlideShareType => 'slideshare'], array(),array('id' => 'video_type' ) ) }}
            </div>
			<div class="large-12 columns">
				{{ Form::label('gl_videos', 'Add Videos', array('for' => 'gl_videos'))}}
				{{ Form::text('gl_videos', '', array('class' => 'text','id' =>'gl_videos','placeholder' => 'Enter video url to add video & hit enter')) }}
			</div>
			<div class="large-12 columns">
				
				{{ Form::hidden('prod_id','', array('id' => 'gl_product_id')) }}
				{{ Form::hidden('cat_name','',array('id' => 'category_name')) }}
				{{ Form::hidden('cat_id','',array('id' => 'gl_category_id')) }}
			</div>
			<div class="large-12 columns">
				{{ Form::label('name', 'Choose a category *', array('for' => 'category', 'style' => 'clear:both'))}}
				{{ Form::select('category', Category::get_distinct_names(), array(), array('multiple' => 'multiple', 'id' => 'gl_category', 'class' => 'chosen addable select_category chosen-select-width', 'data-placeholder'=>"Choose one or more category"))}}
			</div>
			<div class="large-12 columns">
				{{ Form::open(array('url' => '/file-uploader', 'class' => 'dropzone gallery_uploader new_gallery_create', 'id' => 'create-gallery')) }}
				<div class="fallback">
					{{ Form::file('file', array( 'multiple' => 'multiple')) }}
				</div>
				{{ Form::close()}}
			</div>
			<div class="large-12 columns">
				<button id="submit-all">Go!</button>
				{{ Form::label('', '* Required')}}
				<div class="error_wrap">
				</div>
				{{ $errors -> first('name', '<small class="error">:message</small>')}}
			</div>
		</div>
	</div>
	@stop
	@section('scripts')
	{{ HTML::script(asset(Config::get('app.url_path')."js/script.js")); }}
	{{ HTML::script(asset(Config::get('app.url_path')."js/autocomplete.js")); }}
	{{ HTML::script(asset(Config::get('app.url_path')."js/jquery-ui.js")); }}
	@stop
