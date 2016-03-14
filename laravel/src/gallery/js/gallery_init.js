var initUrl = '';
(function($){
	initUrl = window.location.href
	$('body').on('click', '.galleryPop', function(){
		$self = $(this);
		$.ajax({
			url: 'http://gallery.front.com/image/'+$self.attr('data-imgUrl'),
			data: {

			},
			type: 'GET',
			error: function(){

			},
			success: function(msg){
				if($('#overlayMainGallery').length > 0 && msg != ''){
					window.history.pushState('','','http://gallery.front.com/image/'+$self.attr('data-imgUrl'));
					$('#overlayMainGallery').addClass('active');
					$('#overlayMainGallery').html(msg);
					$('#overlayMainGallery').css('display', 'block');
				}
			}
		});
	});
})(jQuery);