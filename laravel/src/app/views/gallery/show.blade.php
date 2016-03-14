@extends('layouts.master')
@section('title')
Gallery Display| 91Mobiles.com
@stop
@section('style-sheets')
{{ HTML::style(asset(Config::get('app.url_path')."css/prettyPhoto.css")); }}
@stop
@section('content-title')
{{ $gallery->name }}
@stop
@section('content')
<div class="dropzone-wrapper large-6">
<body>
<div class="container">
		<table border="2" style="width:100%;">
		<tr>
		<th>Name:</th><td> {{ $gallery->name }}</td>
		</tr>
		<tr>
		<th>Description:</th><td> {{ $gallery->description }}</td>
		</tr>
		<tr>
		<th>Author:</th><td>{{ $gallery->author }}</td>
		</tr>
		
		<tr>
		<th>Thumb Image Extension:</th><td> {{ $gallery->thumb_img_extension }}</td>
		</tr>
		<tr>
		<th>Views:</th><td> {{ $gallery->views }}</td>
		</tr>
		<tr>
		<th>URL:</th><td>{{ $gallery->url }}</td>
		</tr>
		<tr>
		<th>Type:</th><td>{{ $gallery->type }}</td>
		</tr>
		

		</table>
		<div class="img_preview_wrapper">
        			@if ($gallery -> items_list)
        			@foreach($gallery -> items_list as $i => $item)

        			<div class="img_wrap">

                            <?php
                            if($item['type'] == 1) {
                            ?>
                            <span data-id="{{ $item['id'] }}">
                                <a class="group1" title="{{ $item['name'] }}" href="<?php echo Config::get('app.image_view_url').$item['path'].$item['url'].'.'.$item['extension'];?>" rel="prettyPhoto">
                                    <img data-extension="{{ $item['extension'] }}"  data-path="{{ $item['path'] }}" data-url="{{  $item['url'] }}" data-id="{{ $item['id'] }}" title="{{ $item['name'] }}" src="<?php echo Config::get('app.image_view_url').$item['path'].$item['url'].'_ls.'.$item['extension'];?>"  >
                                </a>
                                {{ Form::radio('thumbnail', '', $i == 0 || $gallery -> thumb_img_url == $item['path'].$item['url'], array('style' => 'display:none;', 'data-id' => $item['id']) ) }}
                            </span>

        			<?php }else if($item['type'] == 2 || $item['type'] == 3) { ?>
                        <span data-id="{{ $item['id'] }}">
                    			<iframe width="220" height="180" data-id="{{ $item['id'] }}" src="{{ str_replace('watch?v=','embed/',$item['url']) }}" frameborder="0" allowfullscreen="" style="margin: 17px;"></iframe>
                    <?php } ?>
        			    </span>
                    </div>

        			@endforeach
        			@endif
        		</div>



</div>
</body>
</div>
@stop
@section('scripts')
{{ HTML::script(asset(Config::get('app.url_path')."js/jquery.min.js")); }}
{{ HTML::script(asset(Config::get('app.url_path')."js/jquery.prettyPhoto.js")); }}
{{ HTML::script(asset(Config::get('app.url_path')."js/script.js")); }}
<script type="text/javascript" charset="utf-8">
 		 $(document).ready(function(){
    	 $("a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',slideshow:3000
    	 	, social_tools: false});
  		 });
		</script>
@stop