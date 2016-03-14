(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
function share_on_fb(title, image, desc, prop, caption, url, ga_event, ga ){
	if(ga == 1){
		ga_event = $('<div />').html(ga_event).text();
		ga_event = JSON.parse(ga_event);
	}
	if(url == 'default'){
		url = 'http://' + 'www.91mobiles.com' + window.location.pathname;
	}
	FB.init({appId: "150997098245063", status: true, cookie: true});
	FB.ui({
		method: 'feed',
		link: url,
		name: title,
		picture: image,
		description: desc,
		properties: prop,
		caption: caption,
	}, function(response){
		if (response && response.post_id) {
			if(ga == 1){
				_gaq.push(['_trackEvent', ga_event['event'] , ga_event['event_submit'] , ga_event['prod_info'], 1,true]);
			}
		} else {
			if( ga == 1){
				_gaq.push(['_trackEvent', ga_event['event'] , ga_event['event_cancel'] , ga_event['prod_info'], 1,true]);
			}
		}
	});
}
var moveScroll = 0;
var changingImage = 0;
var historyCounter = 0;
var showError = function(err){
	alert('Unable to load!');
}
var setMetaData = function(){
	var title = $('#caption_line').html().trim() == '' ? $('img.main').attr('title') : $('#caption_line').html();
	$(document).attr('title', title+' | 91Mobiles ');
}
var setSwipeEvents = function(){
		// $('#image_view img.main').swipe({
		// 	swipeLeft:function(event, direction, distance, duration, fingerCount) {
		// 		if(changingImage == 0 || true){
		// 			changingImage = 1;
		// 			showNextImg();
		// 		}
		// 		showOnImageControls();
		// 	},
		// 	swipeRight:function(event, direction, distance, duration, fingerCount) {
		// 		if(changingImage == 0 || true){
		// 			changingImage = 1;
		// 			showPrevImg();
		// 		}
		// 		showOnImageControls();
		// 	}
		// });
}
var changeImage = function(element, history){
	changingImage = 0;
	var url = element['path']+element['url'];
	$('#image_view img.main').addClass('loader');
	$('#image_view img.main').attr('src', config['images_path']+'713.GIF');
	$('<img class="main" />')
	.attr('src', config['image_path_url']+url+'.'+element['extension'])
	.attr('title', element['name'])
	.load(function(){
		if(changingImage == 0 && $('#current_url').val() != element['url']){
			$('#image_view #img_view_wrap_inner img.main').remove();
			$('#image_view #img_view_wrap_inner').append($(this));
			$('#current_url').val(element['url']);
			if(element['caption'].trim() != ''){
				$('.gencomment #caption_line').html(element['caption']);
				$('.gencomment').removeClass('display_none');
			}
			else{
				$('.gencomment #caption_line').html('');
				$('.gencomment').addClass('display_none');	
			}
			if(element['description'].trim() != ''){
				$('.photocaption').html(element['description']).removeClass('display_none');
			}
			else{
				$('.photocaption').html('').addClass('display_none');	
			}
			$('.img.main').attr('title', element['title']);
			if(history == 1){
				window.history.pushState("", "", config['url_path']+'view/'+element['url']);
				historyCounter++;
			}
			setSwipeEvents();
			setMetaData();
		}
	});
}
var toggleOnImageControls = function(){
	$('#image_view').toggleClass('hidden');
}
var hideOnImageControls = function(){
	$('#image_view').addClass('hidden');
}
var showOnImageControls = function(){
	$('#image_view').removeClass('hidden');
}
var setThumbSight = function(){
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

}
var adjustHeight = function(height){
	$('#image_view').css('height',  height);
	if($(window).width() > 767){
		$('#rightSideBar').css('height',  height + 10);
	}
	else{
		$('#rightSideBar').removeAttr('height');	
	}

}
var checkThumbIndex = function(element){
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
}
var moveThumbRow = function(){
	if($('.thumbnail_row').length > 0 ){
		$('.thumbnail_row').animate({
			scrollLeft: Number($('.thumbnail_row').scrollLeft() + $('.img_wrap.active').offset().left - ($('.img_wrap_link').width()*(($('.thumbnail_row').width() / 2) / $('.img_wrap_link').width() ))) + 'px',
		}, {
			duration: 500,
			complete: function() {
						// moveScroll = 0;
						// setThumbSight();
					}
				});
	}
}
var showPrevImg = function(){
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
}
var showNextImg = function(){
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
}
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
	if($(this).width() - ( e.pageX - posXL) < 70){
		if(changingImage == 0 || true){
			changingImage = 1;
			showNextImg();
		}
	}
	else if(e.pageX - posXL < 70){
		if(changingImage == 0 || true){
			changingImage = 1;
			showPrevImg();
		}
	}
	else{
		toggleOnImageControls();
	}
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
	if($('#overlayMainGallery').length > 0){
		$('#overlayMainGallery').fadeOut("fast", function(){
			$('#overlayMainGallery').removeClass('active');
			$('#overlayMainGallery').css('display', 'none');
			window.history.go(window.history.length - historyCounter);
			historyCounter = 0;
			window.history.replaceState('','',initUrl);
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