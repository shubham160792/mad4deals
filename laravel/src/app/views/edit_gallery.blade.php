@extends('layouts.master')
@section('title')
Edit Gallery "{{{ $gallery -> name }}}" | 91Mobiles.com
@stop
@section('style-sheets')
<?php echo HTML::style(asset(Config::get('app.url_path')."css/colorbox.css")); ?>
<?php echo HTML::style(asset(Config::get('app.url_path')."css/jquery-ui.css")); ?>

@stop
@section('content-title')
Edit gallery <strong>{{{ $gallery -> name }}}</strong>
@stop
@section('content-caption')
Edit you gallery here, add / remove images. Add description, tags and more.
@stop
@section('content')
<div class="dropzone-wrapper large-6">

	<input type="hidden" id="edit_gallery_id" value="{{ $gallery -> id }}">
	<div data-url='/create' class='gallery_create_form'>
		@if ($gallery -> author)
		<div class="large-col-6" style="margin-bottom:5px;">
			{{ Form::label('author', 'Author: ', array('style' => 'float:left;'))}}
			{{ Form::label('', $gallery -> author, array())}}
		</div>
		@endif
		<div class="clear both"></div>
		{{ Form::label('name', 'Enter name *', array('for' => 'name'))}}
		{{ Form::text('name', $gallery -> name , array('class' => 'text', 'id' => 'gl_name','placeholder' => 'Choose a name for your gallery'))}}
		{{ Form::label('url', 'Enter URL *', array('for' => 'url'))}}
		{{ Form::text('name', $gallery -> url, array('class' => 'text', 'id' => 'gl_url','placeholder' => 'Choose a url for your gallery'))}}
		{{ Form::label('desc', 'Enter Description ', array('for' => 'desc'))}}
		{{ Form::text('name', $gallery -> description, array('class' => 'text', 'id' => 'gl_description','placeholder' => 'Write something which describes this gallery'))}}
		<div class="row">
			<div class="large-4 columns"> 
				{{ Form::label('name', 'Select Type *', array('for' => 'name'))}}
				{{ Form::select('name', ['product' => 'product','general' => 'general'], array($gallery -> type),array('id' => 'gl_type' ) ) }}
			</div>
			<div id="product_name" class="large-7 large-offset-1 columns">
				{{ Form::label('product_name', 'Enter Product name *', array('for' => 'product_name'))}}
				{{ Form::text('product', $gallery -> product, array('id' =>'gl_product','autocomplete' => 'off')) }}
			</div>
			<div class="large-4 columns">
                {{ Form::label('name', 'Select Item Type *', array('for' => 'name'))}}
                {{ Form::select('name', [\App\constants\ItemType::$ImageType => 'image',\App\constants\ItemType::$YoutubeType => 'youtube',\App\constants\ItemType::$SlideShareType => 'slideshare'], array(),array('id' => 'video_type' ) ) }}
            </div>
			<div class="large-12 columns">
				{{ Form::label('gl_videos', 'Add Videos', array('for' => 'gl_videos'))}}
				@if ($gallery -> items_list)
					@foreach($gallery -> items_list as $i => $video)
                        @if ($video['type'] == \App\constants\ItemType::$YoutubeType || $video['type'] == \App\constants\ItemType::$SlideShareType)
                            <p><a style='color: #0078a0;' class='video_thumb' target='_blank' data-type="{{ $video['type'] }}" data-url='{{ $video['url'] }}' href='{{ $video['url'] }}'>{{ $video['url'] }}</a><span class='videoCross'>x</span></p>
                        @endif
					@endforeach
				@endif
				{{ Form::text('gl_videos', '', array('class' => 'text','id' =>'gl_videos','placeholder' => 'Enter video url to add video & hit enter')) }}
			</div>
		</div>
		<div class="large-12 columns">
		
		{{ Form::hidden('prod_id',$gallery -> product_id, array('id' => 'gl_product_id')) }}
		{{ Form::hidden('cat_name','',array('id' => 'category_name')) }}
		{{ Form::hidden('cat_id',$gallery -> pro_cat_id,array('id' => 'gl_category_id')) }}
		</div>
		<div class="large-12 columns">
		{{ Form::label('name', 'Choose or add a category *', array('for' => 'category','style' => 'clear:both'))}}
		{{ Form::select('category', Category::get_distinct_names(), $gallery -> categories, array('multiple' => 'multiple', 'id' => 'gl_category',  'class' => 'chosen addable select_category chosen-select-width', 'data-placeholder'=>"Add one or more category"))}}
		</div>
		<div class="large-12 columns">
		{{ Form::open(array('url' => '/file-uploader', 'class' => 'gallery_uploader dropzone old_gallery_edit', 'id' => 'create-gallery')) }}
		<div class="fallback">
			{{ Form::file('file', array( 'multiple' => 'multiple')) }}
		</div>
		{{ Form::close()}}
		</div>

		<div class="img_preview_wrapper">
			@if ($gallery -> items_list)
			@foreach($gallery -> items_list as $i => $item)

			<div class="img_wrap">

                    <?php
                    if($item['type'] == \App\constants\ItemType::$ImageType) {
                    ?>
                    <span data-id="{{ $item['id'] }}">
                        <a class="group1" title="{{ $item['name'] }}" href="<?php echo Config::get('app.image_view_url').$item['path'].$item['url'].'.'.$item['extension'];?>">
                            <img data-extension="{{ $item['extension'] }}"  data-path="{{ $item['path'] }}" data-url="{{  $item['url'] }}" data-id="{{ $item['id'] }}" title="{{ $item['name'] }}" src="<?php echo Config::get('app.image_view_url').$item['path'].$item['url'].'_ls.'.$item['extension'];?>"  >
                        </a>
                        {{ Form::radio('thumbnail', '', $i == 0 || $gallery -> thumb_img_url == $item['path'].$item['url'], array('style' => 'display:none;', 'data-id' => $item['id']) ) }}
                    </span>

			<?php }else if($item['type'] == \App\constants\ItemType::$YoutubeType || $item['type'] == \App\constants\ItemType::$SlideShareType) { ?>
                <span data-id="{{ $item['id'] }}">
            			<iframe width="220" height="180" data-id="{{ $item['id'] }}" src="{{ str_replace('watch?v=','embed/',$item['url']) }}" frameborder="0" allowfullscreen="" style="margin: 17px;"></iframe>
            <?php } ?>
			    </span>
            </div>

			@endforeach
			@endif
		</div>
		<div class="clear both"></div>
		<div class="notes">
			{{ HTML::decode(Form::label('note','&#8226; <span class="color-box thumb-img"></span> thumbnail')) }}
			{{ Form::label('note', '&#8226; Double Click an image to select as thumbnail', array('for' => 'category'))}}
			{{ Form::label('note', '&#8226; Click an image to edit image attributes', array('for' => 'category'))}}
			{{ Form::label('note', '&#8226;  Sort images to change display order', array('for' => 'category'))}}
		</div>
		<div class="clear both">
		</div>
		{{ Form::label('', '* Required')}}
		<div class="large-12 columns">
		<button id="save_gal_edit">Save</button>
		<div class="error_wrap">
		</div>
		{{ $errors -> first('name', '<small class="error">:message</small>')}}
		</div>
	</div>
</div>
@stop
@section('scripts')
{{ HTML::script(asset(Config::get('app.url_path')."js/colorbox.js")); }}
{{ HTML::script(asset(Config::get('app.url_path')."js/edit.js?v=1")); }}
{{ HTML::script(asset(Config::get('app.url_path')."js/autocomplete.js")); }}
{{ HTML::script(asset(Config::get('app.url_path')."js/jquery-ui.js")); }}
<script type="text/javascript">
	$('document').ready(function(){
		var value1="<?php echo $gallery -> type; ?>"
		if(value1 == 'general')
		{
			$('#product_name').css("display","none");
			$('#gl_product').val('NA');
			$('#gl_category_id').val('NA');
			$('#gl_product_id').val('NA');
		}
		else
		{
			$('#product_name').css("display","block");
			var pro=$('#gl_product').val();
			var pro_id=$('#gl_product_id').val();
			var cat_id=$('#gl_category_id').val();
		}
		$('#gl_type').change(function(e) {
			var value=$(this).val();
			if(value=='product')
			{
				$('#product_name').css("display","block");
				$('#gl_product').val(pro);
				$('#gl_product_id').val(pro_id);
				$('#gl_category_id').val(cat_id);
			}
			else
			{
				$('#product_name').css("display","none");
				$('#gl_product').val('NA');
				$('#gl_category_id').val('NA');
				$('#gl_product_id').val('NA');
			}
		});
	});
</script>

@stop
