// detect hupso settings and show share button preview on first load
var hupso_settings_active = document.hupso_settings_form.button_type;
if (typeof hupso_settings_active == "object" ) {
	hupso_create_code();
}

function hupso_create_code() {

	jQuery(document).ready(function($) {
		// $() will work as an alias for jQuery() inside of this function
	
		var bsize = "button120x28";
		var bwidth = "120";
		var bheight = "28";
		var hupso_services = "";
		var icon_type = 'labels';
		var hupso_url = "";
		var hupso_title = "";	
		var button_type = "share_button";
		var button_position = "left";
		var hupso_class = 'hupso_pop';
		var hupso_js = 'share.js';
		var hupso_float_left_f = false;
		var hupso_float_right_f = false;	
		var toolbar_size = 'big';
		var toolbar_share = 'share';
		var counters_preview = '';
		var share_image = 'show';
		var share_image_custom_url = '';
		var share_image_lang = '';		
		var hupso_twitter_via = '';	
		var hupso_counters_lang = 'en_US';		
	
		dir = "";
		cdn = "static";

	
		hupso_float_left_f = false;
		hupso_float_right_f = false;	
		
		button_type = $("input:radio[name=button_type]:checked").val();
		switch ( button_type ) {
			case 'share_button':
				$( "#button_position" ).hide();
				$( "#toolbar_size" ).hide();			
				$( "#button_style" ).show();	
				$( "#button_preview" ).show();
				$( "#move_mouse" ).show();			
				$( "#show_icons" ).show();		
				$( "#show_title" ).show();
				$( "#show_color" ).show();
				$( "#counters_config" ).hide();		
				$( "#services" ).show();
				$( "#share_image" ).hide();
				break;
			case 'share_toolbar':
				$( "#button_position" ).hide();
				$( "#button_style" ).hide();	
				$( "#toolbar_size" ).show();		
				$( "#button_preview" ).show();
				$( "#move_mouse" ).hide();
				$( "#show_icons" ).hide();	
				$( "#show_title" ).show();
				$( "#show_color" ).hide();
				$( "#counters_config" ).hide();		
				$( "#services" ).show();
				$( "#share_image" ).show();
				break;
			case 'floating_toolbar':
				$( "#button_position" ).show();
				$( "#toolbar_size" ).hide();				
				$( "#button_style" ).hide();	
				$( "#button_preview" ).hide();
				$( "#move_mouse" ).hide();
				$( "#show_icons" ).show();				
				$( "#show_title" ).show();
				$( "#show_color" ).show();
				$( "#counters_config" ).hide();	
				$( "#services" ).show();
				$( "#share_image" ).hide();
				break;
			case 'counters':
				$( "#button_position" ).hide();
				$( "#button_style" ).hide();	
				$( "#toolbar_size" ).hide();		
				$( "#button_preview" ).show();
				$( "#move_mouse" ).hide();
				$( "#show_icons" ).hide();	
				$( "#show_title" ).show();
				$( "#show_color" ).hide();
				$( "#counters_config" ).show();
				$( "#services" ).hide();	
				$( "#share_image" ).show();
				break;						
		}
		
		share_image = $("input:radio[name=hupso_share_image]:checked").val();
		share_image_custom_url = $.trim($("input:text[name=hupso_share_image_custom_url]").val());
		hupso_twitter_via = $.trim($("input:text[name=hupso_twitter_via]").val());		
		
		var lang_code = $("#share_image_lang option:selected").val();
		if ( lang_code != 'en' ) {
			share_image_lang = 'lang/' + lang_code + '/';
		}
		else {
			share_image_lang = '';
		}
		
		
		switch ( share_image ) {
			case 'show':
				if ( (lang_code == 'en') || (lang_code == '')) {
					counters_preview = '<img src="http://static.hupso.com/share/buttons/share-small.png"/>';
				}
				else {
					counters_preview = '<img style="margin-right:10px;" src="http://static.hupso.com/share/buttons/lang/'+lang_code+'/share-small.png"/>';	
				}
				break;
			case 'hide':	
				counters_preview = '<img src="http://static.hupso.com/share/buttons/dot.png"/>';
				break;
			case 'custom':
				counters_preview = '<img src="' + share_image_custom_url + '"/>';
				break;
		}		
			
		button_position = $("input:radio[name=button_position]:checked").val();
		toolbar_size = $("input:radio[name=select_toolbar_size]:checked").val();
		icon_type = $("input:radio[name=menu_type]:checked").val();
		
   		bsize = $("input:radio[name=size]:checked").val();
		var values = bsize.split('x');
		bheight = values[1];
		var values2 = values[0].split('n');
		bwidth = values2[1];	
		
		hupso_url = $.trim($("input:text[name=page_url]").val());
		hupso_title = $.trim($("input:text[name=page_title]").val());	
		hupso_counters_lang = $("#hupso_counters_lang option:selected").val();	
		
		hupso_background_color = $.trim($("input:text[name=background_color]").val()).toUpperCase();	
		hupso_border_color = $.trim($("input:text[name=border_color]").val()).toUpperCase();	
		
		switch ( button_type ) {
			case 'share_button':
				hupso_services = '<script type="text/javascript">var hupso_services=new Array(';
				break;
			case 'floating_toolbar':
				hupso_services = '<script type="text/javascript">var hupso_services_f=new Array(';
				break;
			case 'share_toolbar':
				hupso_services = '<script type="text/javascript">var hupso_services_t=new Array(';
				break;
			case 'counters':
				hupso_services = '<script type="text/javascript">var hupso_services_c=new Array(';
				break;					
		}
										
		
		if ( button_type == 'counters' ) {
			
			// twitter
			var twitter_tweet = $("input:checkbox[name=twitter_tweet]:checked").val();
			if (twitter_tweet == 1) {
				hupso_services += '"twitter"';
				counters_preview += '<img src="http://static.hupso.com/share/img/counters/twitter_tweet.png" />';
			}
			
			// facebook
			var facebook_like = $("input:checkbox[name=facebook_like]:checked").val();
			var facebook_send = $("input:checkbox[name=facebook_send]:checked").val();
			if (facebook_like == 1) {
				hupso_services += '"facebook_like"';
				counters_preview += '<span style="padding-left:20px;"><img src="http://static.hupso.com/share/img/counters/facebook_like.png" /></span>';
				if (facebook_send == 1) {
					hupso_services += '"facebook_send"';					
					counters_preview += ' <img src="http://static.hupso.com/share/img/counters/facebook_send.png" />';
				}
			}			

			// google +1
			var google_plus_one = $("input:checkbox[name=google_plus_one]:checked").val();
			if (google_plus_one == 1) {
				hupso_services += '"google"';
				counters_preview += '<span style="padding-left:20px;"><img src="http://static.hupso.com/share/img/counters/google_plus_one.png" /></span>';
			}		
			
			// linkedin
			var linkedin_share = $("input:checkbox[name=linkedin_share]:checked").val();
			if (linkedin_share == 1) {
				hupso_services += '"linkedin"';
				counters_preview += '<span style="padding-left:20px;"><img src="http://static.hupso.com/share/img/counters/linkedin_share.png" /></span>';
			}				
		
			hupso_services = hupso_services.replace(/""/gi, '","');
			hupso_services += ');';
			
		}
		
		else {
					
			if ( $( "input:checkbox[name=twitter]:checked" ).val() == 1 )
				hupso_services += '"Twitter",';
			if ( $( "input:checkbox[name=facebook]:checked" ).val() == 1 )
				hupso_services += '"Facebook",';		
			if ( $( "input:checkbox[name=googleplus]:checked" ).val() == 1 )
				hupso_services += '"Google Plus",';	
			if ( $( "input:checkbox[name=linkedin]:checked" ).val() == 1 )
				hupso_services += '"Linkedin",';
			if ( $( "input:checkbox[name=stumbleupon]:checked" ).val() == 1 )
				hupso_services += '"StumbleUpon",';
			if ( $( "input:checkbox[name=digg]:checked" ).val() == 1 )
				hupso_services += '"Digg",';
			if ( $( "input:checkbox[name=reddit]:checked" ).val() == 1 )
				hupso_services += '"Reddit",';
			if ( $( "input:checkbox[name=bebo]:checked" ).val() == 1 )
				hupso_services += '"Bebo",';
			if ( $( "input:checkbox[name=delicious]:checked" ).val() == 1 )
				hupso_services += '"Delicious",';		
				
		
			var none = hupso_services.substring(hupso_services.length - 1, hupso_services.length);
			hupso_services = hupso_services.substring(0, hupso_services.length - 1);		
			if ( none != ',' ) {
				hupso_services += '();';
			} else {
				hupso_services += ');';
			}
		}


		switch ( button_type ) {
			case 'share_button':
				hupso_services += 'var hupso_icon_type = "'+icon_type+'";';
				break;
			case 'floating_toolbar':
				hupso_services += 'var hupso_icon_type_f = "'+icon_type+'";';
				break;
		}
		
		if (hupso_url != "") {
			if (hupso_url.toLowerCase().indexOf( "http://" ) == -1 )
				hupso_url = "http://" + hupso_url;	
				
			switch ( button_type ) {
				case 'share_button':
					hupso_services += 'var hupso_url="'+encodeURI(hupso_url)+'";';
					break;
				case 'floating_toolbar':
					hupso_services += 'var hupso_url_f="'+encodeURI(hupso_url)+'";';
					break;
				case 'share_toolbar':
					hupso_services += 'var hupso_url_t="'+encodeURI(hupso_url)+'";';
					break;
				case 'counters':
					hupso_services += 'var hupso_url_c="'+encodeURI(hupso_url)+'";';
					break;						
			}
			
		}
		
		if (hupso_title != "") {
			switch ( button_type ) {
				case 'share_button':
					hupso_services += 'var hupso_title="'+encodeURI(hupso_title)+'";';
					break;
				case 'floating_toolbar':
					hupso_services += 'var hupso_title_f="'+encodeURI(hupso_title)+'";';
					break;
				case 'share_toolbar':
					hupso_services += 'var hupso_title_t="'+encodeURI(hupso_title)+'";';
					break;
				case 'counters':
					hupso_services += 'var hupso_title_c="'+encodeURI(hupso_title)+'";';
					break;						
			}
			
		}		
		
		if ( hupso_background_color != '' ) {
			switch ( button_type ) {
				case 'share_button':
					hupso_services += 'var hupso_background="#'+encodeURI(hupso_background_color)+'";';
					break;
				case 'floating_toolbar':
					hupso_services += 'var hupso_background_f="#'+encodeURI(hupso_background_color)+'";';
					break;
				case 'share_toolbar':
					hupso_services += 'var hupso_background_t="#'+encodeURI(hupso_background_color)+'";';
					break;
			}
			
		}
		
		if ( hupso_border_color != '' ) {
			switch ( button_type ) {
				case 'share_button':
					hupso_services += 'var hupso_border="#'+encodeURI(hupso_border_color)+'";';
					break;
				case 'floating_toolbar':
					hupso_services += 'var hupso_border_f="#'+encodeURI(hupso_border_color)+'";';
					break;
				case 'share_toolbar':
					hupso_services += 'var hupso_border_t="#'+encodeURI(hupso_border_color)+'";';
					break;
			}
			
		}
		
		///////////////////////////////////////////
		if (button_type == 'share_button') {
			hupso_class = 'hupso_pop';
			hupso_js = 'share.js';
		}		
		if (button_type == 'floating_toolbar') {
			hupso_class = 'hupso_float';
			hupso_js = 'float.js';
			bwidth = 0;
			bheight = 0;
			if (button_position == 'left') {
				hupso_services += 'var hupso_float_left_f=true;';
			}
			else {
				hupso_services += 'var hupso_float_right_f=true;';
			}
		}
		
		if (button_type == 'share_toolbar') {
			hupso_class = 'hupso_toolbar';
			hupso_js = 'share_toolbar.js';
			
			switch ( toolbar_size ) {
				case 'big':
					hupso_services += 'var hupso_toolbar_size_t="big";';
					toolbar_share = 'share';
					break;
				case 'medium':
					hupso_services += 'var hupso_toolbar_size_t="medium";';
					toolbar_share = 'share-medium';
					break;
				case 'small':
					hupso_services += 'var hupso_toolbar_size_t="small";';
					toolbar_share = 'share-small';
					break;
			}
		}	
		
		if (button_type == 'counters') {
			hupso_class = 'hupso_counters';
			hupso_js = 'counters.js';
		}			

		var code = '<!-- Hupso Share Buttons - http://www.hupso.com/share/ -->';
		code += '<a class="'+hupso_class+'" href="http://www.hupso.com/share/">';  // float:  class="hupso_float"
		
switch ( button_type ) {
			case 'share_button':
				code += '<img src="http://static.hupso.com/share/buttons/'+bsize+'.png" width="'+bwidth+'" height="'+bheight+'" border="0" alt="Share"/>';
				break;
			case 'share_toolbar':
				if ( share_image == 'hide' ) {
					toolbar_share = 'dot';
					share_image_lang = '';
				}
				if ( share_image == 'custom') {
					code += '<img src="' + share_image_custom_url + '" border="0" style="padding-top:5px; float:left;" alt="Share"/>';
				}
				else {
					code += '<img src="http://static.hupso.com/share/buttons/'+share_image_lang+toolbar_share+'.png" border="0" style="padding-top:5px; float:left;" alt="Share"/>';
				}
				break;
			case 'counters':
				var share_url = 'share-small';
				if ( share_image == 'hide' ) {
					share_url = 'dot';
					share_image_lang = '';
				}
				if ( share_image == 'custom') {
					code += '<img src="' + share_image_custom_url + '" border="0" style="padding-top:5px; float:left;" alt="Share"/>';
				}
				else {
					code += '<img src="http://static.hupso.com/share/buttons/'+share_image_lang + share_url + '.png" border="0" style="padding-top:2px; float:left;" alt="Share"/>';
				}			 
				
				break;				
		}				
		
		code += '</a>';
				
		if (hupso_twitter_via != '') {
			hupso_services += 'var hupso_twitter_via = "'+hupso_twitter_via+'";';
		}	
		hupso_services += 'var hupso_counters_lang = "'+hupso_counters_lang+'";';		
		
		code += hupso_services;
		
		
		// save button code
		$("input[name=code]").val(code);
		
		code += '</script>';
		code += '<script type="text/javascript" src="http://'+cdn+'.hupso.com/share/js/'+dir+hupso_js+'"></script>';
		code += "<!-- Hupso Share Buttons -->";
		

		// remove float code
		for (var i = 0; i < 10; i++ ) {
			var el = document.getElementById( 'float_hupso_buttons_' + i );
			if ( el != null) {
				el.parentNode.removeChild(el);
			}
		}
	
		// update preview
		if ( button_type == 'counters') {
			$( "#button" ).html(counters_preview);	
		}
		else {
			$( "#button" ).html(code);	
		}		
		
	
	});		
}

