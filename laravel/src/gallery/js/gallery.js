(function( $, window, document, undefined ){
	$.fn.gallerypop = function( options ) {
		var self = this,
		_res = 0,
		_loaded = false,
		_element = null,
		_imgResData = null,
		settings = {
			'event': null,
			'data' : {
				'current' : null,
				'source' : null
			},
			'on_begin' : null,
			'on_error' : null,
			'on_finish' : null,
			'template' : 'full_dark',
		},
		config = $.extend( {}, settings, options ),
		moveScroll = 0,
		changingImage = 0,
		historyCounter = 0,
		setDefaultSettings = function(){
			settings.image_path_url = 'http://origin.img.91mobiles.com/gallery_images_uploads/';
			settings.css_js_path = 'http://origin.img.91mobiles.com/image_gallery/gallery/';
			settings.url_path = 'http://origin.img.91mobiles.com/image_gallery/gallery/';
			settings.images_path = 'http://origin.img.91mobiles.com/image_gallery/gallery/images/';
			//settings.image_path_url = 'http://gallery.api.com/images/';
			//settings.css_js_path = 'http://gallery.api.com/';
			//settings.url_path = 'http://gallery.api.com/';
			//settings.images_path = 'http://gallery.api.com/images/';
		},
		showError = function(err){
			console.error('Gallery91: Unable to load!');
		},setMetaData = function(){
			//var title = $('#caption_line').html().trim() == '' ? $('img.main').attr('title') : $('#caption_line').html();
			//$(document).attr('title', title+' | 91Mobiles ');
		},setSwipeEvents = function(){

		},changeImage = function(element, history){
			changingImage = 0;
			var url = element['url'];
			$('#image_view img.main').addClass('loader');
			$('#image_view img.main').attr('src', settings.images_path+'713.GIF');
			$('<img class="main" />')
			.attr('src', url)
			.attr('title', element['name'])
			.load(function(){
				if(changingImage == 0 && $('#current_url').val() != element['url']){
					$('#image_view #img_view_wrap_inner img.main').remove();
					$('#image_view #img_view_wrap_inner').append($(this));
					$('#current_url').val(element['url']);
					$('.gencomment #caption_line').html('');
					$('.gencomment').addClass('display_none');	
					if($.type(element['caption']) != 'undefined'){
						if(element['caption'].trim() != ''){
							$('.gencomment #caption_line').html(element['caption']);
							$('.gencomment').removeClass('display_none');
						}
					}
					$('.photocaption').html('').addClass('display_none');	
					if($.type(element['caption']) != 'undefined'){
						if(element['description'].trim() != ''){
							$('.photocaption').html(element['description']).removeClass('display_none');
						}
					}
					$('.img.main').attr('title', element['title']);
					if(history == 1){
						historyCounter++;
					}
					setSwipeEvents();
					setMetaData();
				}
			});
		},toggleOnImageControls = function(){
			$('#image_view').toggleClass('hidden');
		},hideOnImageControls = function(){
			$('#image_view').addClass('hidden');
		},showOnImageControls = function(){
			$('#image_view').removeClass('hidden');
		},setThumbSight = function(){
			$('.img_wrap_link').each(function(){
				if($(this).position().left < 20){
					$(this).addClass('onLeft');
					$(this).removeClass('onRight');
					$(this).removeClass('inCenter');
				}
				else if($(this).position().left + $(this).width() + 20 > ($('#image_view').width() )){
					$(this).addClass('onRight');
					$(this).removeClass('onLeft');
					$(this).removeClass('inCenter');
				}
				else{
					$(this).addClass('inCenter');
					$(this).removeClass('onLeft');
					$(this).removeClass('onRight');
				}
			});
		},adjustHeight = function(height){
			$('#image_view').css('height',  height);
			if($(window).width() > 767){
				$('#rightSideBar').css('height',  height + 10);
			}
			else{
				$('#rightSideBar').removeAttr('height');	
			}

		},checkThumbIndex = function(element){
			if(element.index('.img_wrap_link') == $('.img_wrap_link').length - 2 ){
				$('#img_view_wrap_inner .next').addClass('disabled');
				$('#img_view_wrap_inner .prev').removeClass('disabled');
			}
			else if(element.index('.img_wrap_link') == 1){
				$('#img_view_wrap_inner .prev').addClass('disabled');
				$('#img_view_wrap_inner .next').removeClass('disabled');
			}
			else{
				$('#img_view_wrap_inner .next').removeClass('disabled');
				$('#img_view_wrap_inner .prev').removeClass('disabled');
			}
		},moveThumbRow = function(duration){
			if($('.thumbnail_row').length > 0 && $('.img_wrap.active').length > 0 ){
				$('.thumbnail_row').animate({
					scrollLeft: Number($('.thumbnail_row').scrollLeft() + $('.img_wrap.active').offset().left - ($('.img_wrap_link').width()*(($('.thumbnail_row').width() / 2) / $('.img_wrap_link').width() ))) + 'px',
				}, {
					duration: duration || 100,
					complete: function() {
						// moveScroll = 0;
						// setThumbSight();
					}
				});
			}
		},showPrevImg = function(){
			var prevSib = $('.img_wrap_link[data-url="'+$('#current_url').val()+'"]');
			prevElement = prevSib.prev('.img_wrap_link');	
			if(prevElement.length > 0){
				if(prevElement.attr('href')){
					try{
						elemData = JSON.parse(prevElement.attr('data-info'));
						if(elemData){
							changeImage(elemData, 1);
						}
						else{
							console.error('Error!');
						}
					}catch(exception){

					}
					$('.img_wrap_link .img_wrap').removeClass('active');
					$(prevElement.find('.img_wrap')[0]).addClass('active');
					checkThumbIndex(prevElement);
					moveThumbRow();
				}
			}
			else{
				console.error('No prev image');
			}
		},showNextImg = function(){
			var nextSib = $('.img_wrap_link[data-url="'+$('#current_url').val()+'"]');
			nextElement = nextSib.next('.img_wrap_link')

			if(nextElement.length > 0){
				if(nextElement.attr('href')){
					try{
						elemData = JSON.parse(nextElement.attr('data-info'));
						if(elemData){
							changeImage(elemData, 1);
						}
					}catch(exception){

					}
					$('.img_wrap_link .img_wrap').removeClass('active');
					$(nextElement.find('.img_wrap')[0]).addClass('active');
					checkThumbIndex(nextElement);
					moveThumbRow();
				}
			}
		},
		bindEvents = function(){
			$('body').keyup(function(e){
				if(e.keyCode == 39){
					if(changingImage == 0 || true){
						changingImage = 1;
						showNextImg();
					}
				}
				else if(e.keyCode == 37){
					if(changingImage == 0 || true){

						changingImage = 1;
						showPrevImg();
					}
				}
			});
			$('body').on('click', '.img_wrap_link', function(){
				try{
					changeImage(JSON.parse($(this).attr('data-info')), 1);
					//moveThumbRow(500);
				}
				catch(exception){

				}
				$('.img_wrap_link .img_wrap').removeClass('active');
				$(this).find('.img_wrap').addClass('active');
				checkThumbIndex($(this));
			});

			$('body').on('click', '.prev', function(){
				if(changingImage == 0 || true){
					changingImage = 1;
					showPrevImg();
				}
			});
			$('body').on('click', '.next', function(){
				if(changingImage == 0 || true){
					changingImage = 1;
					showNextImg();
				}
			});
			$('body').on('click', '#thumb_tool', function(e){
				if($(this).hasClass('show')){
					$(this).removeClass('show');
					$(this).addClass('hide');
					$('.thumb_nav').removeClass('disabled');
					adjustHeight(Number(parseInt($('#image_view').css('height'))) + 'px');
					$('#img_view_wrap_inner').addClass('thumb');
					$('.img_view_tools').addClass('thumb');
				}
				else if($(this).hasClass('hide')){
					$(this).removeClass('hide');
					$(this).addClass('show');
					$('.thumb_nav').addClass('disabled');
					adjustHeight(Number(parseInt($('#image_view').css('height'))) + 'px');
					$('#img_view_wrap_inner').removeClass('thumb');
					$('.img_view_tools').removeClass('thumb');

				}
			});
			$('body').on('click', '#img_view_wrap_inner', function(e){
				e.preventDefault();
				var posXL = $(this).offset().left;
				if(e.pageX - posXL < 70){
					if(changingImage == 0 || true){
						changingImage = 1;
						showPrevImg();
					}
				}
				else{
					if(changingImage == 0 || true){
						changingImage = 1;
						showNextImg();
					}
				}
				//else{
				//	toggleOnImageControls();
				//}
			})

			$('body').on('click', '.thumb_move_right', function(e){
				e.preventDefault();
				if( moveScroll == 0){
					moveScroll = 1;
					$('.thumbnail_row').animate({
						scrollLeft: $('.thumbnail_row').scrollLeft()  + Number($('.thumbnail_row').offset().left + $('#image_view').width() * 0.85) + 'px',
					}, {
						duration: 500,
						complete: function() {
							moveScroll = 0;
							setThumbSight();
						}
					});
				}
			});
			$('body').on('click', '.thumb_move_left', function(e){
				e.preventDefault();
				if($('.thumbnail_row').scrollLeft() > 0 && moveScroll == 0){
					moveScroll = 1;
					$('.thumbnail_row').animate({
						scrollLeft:  Number(($('.thumbnail_row').scrollLeft() - $('#image_view').width() * 0.85)) + 'px',
					}, {
						duration: 500,
						complete: function() {
							moveScroll = 0;
							setThumbSight();
						}
					});
				}
			});
			$('body').on('mouseleave', '#image_view', function(e){
				hideOnImageControls();
			});
			$('body').on('mouseover', '#image_view', function(e){
				showOnImageControls();
			});
			$('body').on('mouseleave', '#img_view_wrap_inner', function(e){
				e.preventDefault();
				$('#img_view_wrap_inner .prev').removeClass('hover');
				$('#img_view_wrap_inner .next').removeClass('hover');
				hideOnImageControls();
			});
			$('body').on('mousemove', '#img_view_wrap_inner', function(e){
				e.preventDefault();
				showOnImageControls();
				var posXL = $(this).offset().left;
				if($(this).width() - ( e.pageX - posXL) < 70){
					showOnImageControls();
					$('#img_view_wrap_inner .next').addClass('hover');
					$('#img_view_wrap_inner .prev').removeClass('hover');
				}
				else if(e.pageX - posXL < 70){
					showOnImageControls();
					$('#img_view_wrap_inner .next').removeClass('hover');
					$('#img_view_wrap_inner .prev').addClass('hover');
				}
				else{
					$('#img_view_wrap_inner .prev').removeClass('hover');
					$('#img_view_wrap_inner .next').removeClass('hover');
				}

			});
			$('body').on('click', '#closeOverlay', function(e){
				e.preventDefault();
				if($('#gallery91_wrap').length > 0){
					$('#gallery91_wrap').fadeOut("fast", function(){
						$('#gallery91_wrap').css('display', 'none');
						//window.history.go(window.history.length - historyCounter);
						historyCounter = 0;
						_loaded = false;
						//window.history.replaceState('','',initUrl);
					});
				}
			});

			$(window).resize(function(){
				showOnImageControls();
				adjustHeight($(window).height()  - 40);

			});
			window.onpopstate = function(event) {
				historyCounter--;
				if(window.location.href != initUrl){
					var full_url = window.location.pathname;
					full_url = full_url.split('/');
					img_url = full_url[full_url.length - 1];
					var element = $('.img_wrap_link[data-url="'+img_url+'"]');
					if(element.length > 0){
						var elemInfo = $(element).attr('data-info');
						try{
							elemInfo = JSON.parse(elemInfo);
							if(elemInfo){
								if($('#overlayMainGallery').length > 0){
									$('#overlayMainGallery').css('display', 'block');
									$('#overlayMainGallery').addClass('active');
								}
								changeImage(elemInfo, 0);
								$('.img_wrap').removeClass('active');
								element.find('.img_wrap').addClass('active');
								checkThumbIndex(element);
							}
						}
						catch(exception){

						}
					}
				}
				else{
					$('#closeOverlay').trigger('click');
				}
			};
		},
		load_res = function(){
			var gCss = $('<link />').attr({ 
				rel : 'stylesheet', 
				type : 'text/css', 
				href : settings.url_path+'templates/css/'+config.template+'.css?v=13'
			}).appendTo($('head'));
			$(gCss).load(function(e){
				_res++;
				if(_res >= 1){
					main();
				}
			});
		},
		loadGallery = function(data){
			$.ajax({
				url : settings.url_path+'templates/'+config.template+'.php',
				data: data,
				type: "POST",
				error:function(){
					console.error('Gallery91: No such template!');
				},
				success: function(data){
					_loaded = true;
					$('#gallery91_wrap').append(data);
					$('#gallery91_wrap .wrap').css({'opacity': '0'});
						// $('#image_view').css('min-height',  $(window).height() - 50);
						adjustHeight($(window).height()  - 40);
						setThumbSight();
						setSwipeEvents();
						checkThumbIndex($('.img_wrap.active').parent());
						moveThumbRow();
						setMetaData();
						bindEvents();
						//$('.fb_like').append("<img src="+settings.images_path+"'fb_like.png'>");
						setTimeout(function(){
							$('#gallery91_wrap #innerWrap').remove();
							$('#gallery91_wrap .wrap').css({'opacity': '1'});
						},300);
					}
				});
		},
		main = function(){
			if(_loaded === true || _loaded == -1){
				return false;
			}
			_loaded = -1;
			if($('#gallery91_wrap').length > 0){
				$('#gallery91_wrap').remove();
			}
			$('body').append('<div id="gallery91_wrap" ></div>');
			$('#gallery91_wrap').css({
				'z-index' : '99999999999999',
				'position': 'fixed',
				'top': '0px',
				'left': '0px',
				'height': '100%',
				'width': '100%',
				'padding': '10px',
				'background-color': 'rgba(0, 0, 0, 0.83)'});
			$('#gallery91_wrap').append('<div id="innerWrap" style=" position: absolute;top: 50%;left: 50%;height: 30%;width: 50%;margin: -15% 0 0 -25%;text-align: center;font-size: 14px;color: #fff;font-weight: bold;"></div>');
			$('#gallery91_wrap div#innerWrap').append('<img src="'+settings.images_path+'loading91.gif'+'">');
			$('#gallery91_wrap div#innerWrap').append('<div>loading...</div>');
			if(_element !== null){
				more_images = new Array();
				url_map = {};
				$(self).each(function(){
					temp = {};
					temp.url = $(this).attr('data-large-src') || $(this).attr('src');
					temp.caption = $(this).attr('data-caption') || $(this).attr('title');
					temp.name = $(this).attr('data-name') || $(this).attr('title');
					temp.thumb_url = $(this).attr('data-thumb-src') || $(this).attr('src');
					temp.active = $(this).attr('src') == $(_element).attr('src') ? 'active' : '';
					if($.type(url_map[temp.url]) == 'undefined'){
						more_images.push(temp);
						url_map[temp.url] = 1;
					}
					delete(temp)
				}).promise().done(function(){
					loadGallery({
						image_url: $(_element).attr('data-large-src') || $(_element).attr('src'),
						name : $(_element).attr('data-name') || $(_element).attr('title'),
						caption : $(_element).attr('data-caption') || $(_element).attr('title'),
						more_images : more_images
					});
				});
			}
			else{
				if(config.data.current == null){
					config.data.current = config.data.source[0];
				}
				loadGallery({
					image_url: config.data.current['url'],
					name : config.data.current['name'],
					caption : config.data.current['caption'],
					description : config.data.current['description'],
					more_images : config.data.source
				});
			}
		};
		function control() {
			setDefaultSettings();
			if( config.on_begin !== null ) {
				config.on_begin();
			}
			if(_res >= 1 && _loaded != -1){
				main();
			}
			else{
				load_res();
			}
			if( config.on_finish !== null ) {
				config.on_finish();
			}
		}
		if( $.type(config.data.source) == 'array' && config.data.source !== null ) {
			if(config.event == null){
				control();
			}
			else{
				$('body').on(config.event, self.selector , function( e ){
					e.preventDefault();
					_element = e.target;
					control();
				});
			}
		} else {
			$('body').on('click', self.selector , function( e ){
				e.preventDefault();
				_element = e.target;
				control();
			});
		}
	}
})( jQuery, window, document );