(function( root, $, undefined ) {
	"use strict";

	$(function () {


//-----------------------------------------------------
	// SCROLL BACK TO TOP BUTTON
//-----------------------------------------------------
		var offset = 1000;
		var speed = 1500;
		var duration = 500;
		 $(window).scroll(function(){
			if ($(this).scrollTop() < offset) {
				$('.topbutton') .fadeOut(duration);
			} else {
			 $('.topbutton') .fadeIn(duration);
			}
		});
		$('.topbutton').on('touchend click', function(){
			$('html, body').animate({scrollTop:0}, speed);
			return false;
		});


			// if ($(window).width() < 1020) {
			// 	$('nav').removeClass('nu__main-nav');
			// 	$('nav').addClass('nu__mobile-nav');
			// }else {
			// 	$('nav').removeClass('nu__mobile-nav');
			// 	$('nav').addClass('nu__main-nav');
			// }



//-----------------------------------------------------
	// FOR TESTING MOBILE RESPONSE SIZES
//-----------------------------------------------------
		// var wi = $(window).width();
		// $("p.testp").text('Initial screen width is currently: ' + wi + 'px.');



//-----------------------------------------------------
	// WINDOW RESIZE FUNCTION
//-----------------------------------------------------
		// $(window).resize(function() {
		//
		// 	var wi = $(window).width();
		//
		//
		//
		//
		//
		//
		//
		//
		// 	$("p.testp").text('Initial screen width is currently: ' + wi + 'px.');
		// 	if (wi <= 576){
		// 		$("p.testp").text('Screen width is less than or equal to 576px. Width is currently: ' + wi + 'px.');
		// 		}
		// 	else if (wi <= 680){
		// 		$("p.testp").text('Screen width is between 577px and 680px. Width is currently: ' + wi + 'px.');
		// 		}
		// 	else if (wi <= 1024){
		// 		$("p.testp").text('Screen width is between 681px and 1024px. Width is currently: ' + wi + 'px.');
		// 		}
		// 	else if (wi <= 1500){
		// 		$("p.testp").text('Screen width is between 1025px and 1499px. Width is currently: ' + wi + 'px.');
		// 		}
		// 	else {
		// 		$("p.testp").text('Screen width is greater than 1500px. Width is currently: ' + wi + 'px.');
		// 		}
		// });

	});

} ( this, jQuery ));
