jQuery(document).ready(function($){
	dc_registration();
	menu_login_links();
// 	dc_menu_login_dialog();
});


jQuery(window).bind("load", function() { 
	register_on_home();
});
 

function register_on_home(){
	var urlParams = new URLSearchParams(window.location.search);
	if (urlParams.get('action') && (urlParams.get('action') == 'register')){
		jQuery('.new-header #header .right-side .header-widget .login-register-buttons a[href="#signup-dialog"]').click();
	}
}


function menu_login_links(){
	jQuery('.new-header #navigation > ul li a[href="#login-dialog"]').click(function(){
		console.log('works2');
		jQuery('.new-header #header .right-side .header-widget .login-register-buttons a[href="#login-dialog"]').click();
	});
}


function dc_registration(){
	if (jQuery('#workscout_registration_form')){
		jQuery('#workscout_registration_form fieldset .captcha_wrapper').after( '<p class="agree_conditions"><label><input type="checkbox" name="terms_accept" value="1" autocomplete="off"><span>You have read and accept these <a href="/privacy-policy" target="_blank" style="font-weight:bold;">terms &amp; conditions</a>.</span></label></p>' );
		
		jQuery('form#workscout_registration_form p input[type="submit"]').click(function(e){
			console.log(jQuery('#workscout_registration_form fieldset .captcha_wrapper input[name="terms_accept"]').is(':checked'));
			if ( jQuery('#workscout_registration_form fieldset input[name="terms_accept"]').is(':checked') ){
				jQuery('#workscout_registration_form fieldset p.agree_conditions').removeClass('error');
				return true;
			} else {
				e.preventDefault();
				e.stopImmediatePropagation();
				jQuery('#workscout_registration_form fieldset p.agree_conditions').addClass('error');
				return false;
			}
		});
	}
}


// function dc_menu_login_dialog(){
//     console.log('works1');
//     jQuery('ul.menu li.menu-item.open_login a').on('click' , function(e){
// 	    e.preventDefault();
// 	    console.log('works2');
//     	jQuery('#main-header #navigation li a[href^="#login-dialog"]').click();
//     });
// }