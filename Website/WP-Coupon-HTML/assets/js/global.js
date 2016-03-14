jQuery(document).ready(function() {
	"use strict";

	var $ = jQuery,
	html = $('html');

	// IE<8 Warning
	if (html.hasClass("ie6") || html.hasClass("ie7")) {
		$("body").empty().html('UPDATE YOUR BROWSER');
	}

	// Superfish Menu Toggle
	( function() {
		$('#nav-toggle').on(
            'click',
			function () {
				$('.primary-navigation .wpc-menu').toggleClass("st-menu-mobile");
			}
		);
		$('.nav-toggle-subarrow, .nav-toggle-subarrow .nav-toggle-subarrow').on( 'click',
			function () {
				$(this).parent().toggleClass("nav-toggle-dropdown");
			}
		);
	} )();

	// Show more content
	( function() {
		$('.coupon-des-full').hide();
		$('.des-more').on( 'click', function () {
			//$('.coupon-des-full').toggleClass("des-show");
			$(this).parent().find('.coupon-des-full').show();
			$(this).hide();
			return false;
		});
		$('.des-less').on( 'click', function() {
			$('.coupon-des-full').hide();
			$('.des-more').show();
			return false;
		});
	} )();

	// Coupon Filter
	( function() {
		$('.coupon-filter a').on( 'click', function() {
			$('.coupon-filter a').removeClass('active');
			$(this).addClass('active');
		});
	} )();

	// Listing Item
	( function() {
		var store_listing_item = $('.store-listing-item');
		store_listing_item.each( function(){
			// Open Modal
			var coupon_modal = $(this).find('.coupon-modal');
			var coupon_id = coupon_modal.attr('id');
			$(this).find('.coupon-button-type .coupon-button').click( function () {
				var aff_url = $(this).attr('data-aff-url');
				var current_url = $(location).attr('href');
				window.open(aff_url, '_self');
				window.open('#'+ coupon_id,'_blank');
				$(coupon_modal).modal('show');
				return false;
			} );
			var coupon_hash = $(location).attr('hash');
			if ( coupon_hash == '#'+coupon_id ) {
				$(coupon_modal).modal('show');
			}

			// Reveal box
			var reveal_link = $(this).find('.coupon-footer li a');
			var reveal_content = $(this).find('.reveal-content');
			reveal_link.each( function() {
				$(this).click( function(){

					reveal_link.removeClass('active');
					$(this).addClass('active');
					//$(this).toggleClass('active');

					var reveal_link_data = $(this).attr('data-reveal');
					reveal_content.each( function() {
						if( $(this).hasClass(reveal_link_data) ) {
							reveal_content.removeClass('active');
							$(this).addClass('active');
							
							$(this).find('.close').on( 'click', function() {
								$(this).parent().removeClass('active');
								reveal_link.removeClass('active');
							});
						}
					});
					return false;
				});
			} );
		});
	} )();

	// Initializing Popup on hover
	( function() {

		$('.icon-popup, .coupon-save').popup({
            inline: true,
            variation: 'inverted',
            position: 'bottom left',
            offset: 0
        });

		// Share popup on modal
		$('.modal-share').popup({
			popup : $('.share-modal-popup'),
			on    : 'click',
			hoverable: true,
			position : 'top right'
		});

	} )();

	// Initializing Form Elements
	( function() {
		$('.dropdown').dropdown();
		$('.ui.checkbox').checkbox();
	} )();

	// Initializing Search Loading
    ( function() {
        // submit search form when click button
        $( 'form#header-search .button').on( 'click', function(){
            $( this ).closest('form').submit();
        } );

        $('.ui.search').search({
            //source: content
            apiSettings: {
                url: ST.ajax_url+'?s={query}',
                method : 'POST',

                data: {
                    action: 'st_coupon_ajax',
                    st_doing: 'ajax_search',
                    _wpnonce: ST._wpnonce,
                    //name: 'Joe Henderson'
                },

                onSuccess: function(response) {

                    var r;
                    if ( $( this).find( '.results').length > 0 ) {

                    } else {
                        $( this).append( '<div class="results"></div>' );
                    }

                    r =  $( this).find( '.results');
                    var html = '';
                    if ( response.results.length > 0  ) {
                        $( response.results ).each( function( index, result ){
                            html+=  '<div class="result">' +
                                '<a href="' + result.url + '"></a>' +
                                '<div class="image">'+result.image+'</div>'+
                                '<div class="content">' +
                                '<div class="title">'+result.title+'</div>' +
                                '<div class="description">'+result.description+'</div>' +
                                '</div>' +
                                '</div>';
                        } );

                        if ( html !== '' ) {

                            var w =  $( this).find( '.prompt').outerWidth();
                            if( typeof response.action !== "undefined" ) {
                                html+='<a class="action" href="'+response.action.url+'">'+response.action.text+'</a>';
                            }
                            r.html(html);
                            r.css( { 'width': w+'px' } ).addClass('items ui transition visible');
                        }

                    } else {
                        r.removeClass('items ui transition visible');
                    }

                    // transition visible
                },

                onResponse: function(response) {
                    // make some adjustments to response
                    //console.log( 'onResponse' );
                    // console.log( response );
                    return response;
                },
                successTest: function(response) {
                    // test whether a json response is valid
                    //console.log( 'successTest' );
                    // console.log( response.success );
                    return response.success || false;
                }
            },
            //minCharacters: 3,
            searchFullText: false,
            cache: false,
            debug: false,
            verbose: false
            //type: 'sta',
        });
    } )();


	
	// Show coupon detail on modal
	( function() {
		$('.coupon-modal').each( function() {
			var coupon_popup_detail = $(this).find('.coupon-popup-detail');
			var show_detail = $(this).find('.show-detail a');
			coupon_popup_detail.hide();
			
			$(show_detail).on( 'click', function() {
				if ( $(show_detail).hasClass('show-detail-on') ) {
					coupon_popup_detail.hide();
					$(this).removeClass('show-detail-on');
					$(this).find('i').removeClass('up').addClass('down');
				} else {
					coupon_popup_detail.show();
					$(this).addClass('show-detail-on');
					$(this).find('i').removeClass('down').addClass('up');
				}
				return false;
			} );

		} );
	} )();


    // Add to favorite
    $('.add-favorite').on( 'click', function() {
        $('.stuser-login-btn').eq(0).click();
        return false;
    });

    // Save Coupon
    $( '.save-coupon, .coupon-save').on( 'click', function(){
        $( '.stuser-login-btn').eq( 0).click();
        return false;
    } );

});

jQuery(document).mouseup(function (e){
	var $ = jQuery;
    var container = $(".reveal-content");
    if (!container.is(e.target) && container.has(e.target).length === 0){
        container.removeClass('active');
        $('.coupon-footer li a').removeClass('active');
    }
});