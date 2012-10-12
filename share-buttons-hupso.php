<?php
/*
Plugin Name: Hupso Share Buttons for Twitter, Facebook & Google+
Plugin URI: http://www.hupso.com/share
Description: Add simple social sharing buttons to your articles. Your visitors will be able to easily share your content on the most popular social networks: Twitter, Facebook, Google Plus, Linkedin, StumbleUpon, Digg, Reddit, Bebo and Delicous. These services are used by millions of people every day, so sharing your content there will increase traffic to your website.
Version: 1.2
Author: kasal
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

$hupso_plugin_url = plugins_url() . '/hupso-share-buttons-for-twitter-facebook-google';
add_filter( 'the_content', 'hupso_the_content', 100 );
load_plugin_textdomain('share_buttons_hupso', false, dirname( __FILE__ )  . '/languages' );

if ( is_admin() ) {
	add_filter( 'plugin_action_links', 'hupso_plugin_action_links', 10, 2 );
	add_action( 'admin_menu', 'hupso_admin_menu' );
}

$all_services = array(
	'Twitter', 'Facebook', 'Google Plus', 'Linkedin', 'StumbleUpon', 'Digg', 'Reddit', 'Bebo', 'Delicious'
);
$default_services = array(
	'Twitter', 'Facebook', 'Google Plus', 'Linkedin', 'StumbleUpon', 'Digg', 'Reddit', 'Bebo', 'Delicious'
);	


function hupso_admin_menu() {
	add_options_page('', '', 'manage_options', __FILE__, 'hupso_admin_settings_show', '', 6);
}

function hupso_admin_settings_show() {
	global $all_services, $default_services, $hupso_plugin_url;
	
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	$msg = hupso_admin_settings_save();

	echo '<div class="wrap" style="padding-bottom:100px;"><div class="icon32" id="icon-users"></div>';
	echo '<h2>'. __('Hupso Share Buttons for Twitter, Facebook & Google+ (Settings)').'</h2>';
	echo '<form method="post" action="">'; 	
	echo '<div style="float:right; padding: 20px 20px 20px 20px; margin-right:20px; margin-top:20px; background: #F7FFBF;">'.$msg.'</div>';		
	
	$start = '<!-- Hupso Share Buttons (http://www.hupso.com/share) -->';
	$end = '<!-- Hupso Share Buttons -->';
	$class_name = 'hupso_pop';
	$alt = 'Social Sharing Buttons';
	$class_url = ' href="http://www.hupso.com/share" ';	
	$style = 'padding-left:5px; padding-top:5px; padding-bottom:5px; margin:0';

	$button_60_img = '<img style="'.$style.'" src="'.$hupso_plugin_url.'/buttons/button60x14.png" width="60" height="14" border="0" alt="'.$alt.'"/>';
	$button_60 =  $start;	
	$button_60 .= '<a class="'.$class_name.$class_url.'>'.$button_60_img.'</a>';	
	$button_60 .= $js_share;
	$button_60 .= 	$end;	
		
	$button_80_img = '<img style="'.$style.'" src="'.$hupso_plugin_url.'/buttons/button80x19.png" width="80" height="19" border="0" alt="'.$alt.'"/>';
	$button_80 =  $start;	
	$button_80 .= '<a class="'.$class_name.$class_url.'>'.$button_80_img.'</a>';	
	$button_80 .= $js_share;
	$button_80 .= 	$end;	
	
	$button_100_img = '<img style="'.$style.'" src="'.$hupso_plugin_url.'/buttons/button100x23.png" width="100" height="23" border="0" alt="'.$alt.'"/>';
	$button_100 =  $start;	
	$button_100 .= '<a class="'.$class_name.$class_url.'>'.$button_100_img.'</a>';	
	$button_100 .= $js_share;
	$button_100 .= 	$end;		
	
	$button_120_img = '<img style="'.$style.'" src="'.$hupso_plugin_url.'/buttons/button120x28.png" width="120" height="28" border="0" alt="'.$alt.'"/>';
	$button_120 =  $start;	
	$button_120 .= '<a class="'.$class_name.$class_url.'>'.$button_120_img.'</a>';	
	$button_120 .= $js_share;
	$button_120 .= 	$end;
	
	$button_160_img = '<img style="'.$style.'" src="'.$hupso_plugin_url.'/buttons/button160x37.png" width="160" height="37" border="0" alt="'.$alt.'"/>';
	$button_160 =  $start;	
	$button_160 .= '<a class="'.$class_name.$class_url.'>'.$button_160_img.'</a>';	
	$button_160 .= $js_share;
	$button_160 .= 	$end;		
	
	$checked = 'checked="checked"';
	$current_button_size = get_option( 'hupso_button_size' , 'button120x28' ); 
	switch ( $current_button_size ) {
		case 'button60x14'  : $button60_checked = $checked; break;
		case 'button80x19'  : $button80_checked = $checked; break;
		case 'button100x23' : $button100_checked = $checked; break;
		case 'button120x28' : $button120_checked = $checked; break;
		case 'button160x37' : $button160_checked = $checked; break;
		default:
			$button120_checked = $checked; break;
	}
	
	?>
	<table border="0">
	<tr>
		<td style="width:170px;"><?php _e('Button size:'); ?></td>
		<td>
			<table border="0">
			<tr><td><input type="radio" name="hupso_button_size" value="button60x14" <?php echo $button60_checked; ?> /></td><td style="padding-right:10px;"><?php echo $button_60_img ?></td></tr>
			<tr><td><input type="radio" name="hupso_button_size" value="button80x19" <?php echo $button80_checked; ?>/></td><td style="padding-right:10px;"><?php echo $button_80_img ?></td></tr>
			<tr><td><input type="radio" name="hupso_button_size" value="button100x23" <?php echo $button100_checked; ?>/></td><td style="padding-right:10px;"><?php echo $button_100_img ?></td></tr>
			<tr><td><input type="radio" name="hupso_button_size" value="button120x28" <?php echo $button120_checked; ?>/></td><td style="padding-right:10px;"><?php echo $button_120_img ?></td></tr>
			<tr><td><input type="radio" name="hupso_button_size" value="button160x37" <?php echo $button160_checked; ?>/></td><td style="padding-right:20px;"><?php echo $button_160_img ?></td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="width:170px;"><?php _e('Social sharing services'); ?></td>
		<td><hr style="height:1px;"/><?php hupso_settings_print_services(); ?></td>
	</tr>
	<tr>
		<td><?php _e('Type of menu'); ?></td>
		<?php
			$menu_type = get_option( 'hupso_menu_type', 'labels' );
			$checked = ' checked="checked" ';
			switch ( $menu_type ) {
				case 'labels': 	$hupso_labels_checked = $checked; break;
				case 'icons' :  $hupso_icons_checked = $checked; break;
				default: $hupso_labels_checked = $checked;
			}			
		
		?>
		<td><hr style="height:1px;"/><input type="radio" name="hupso_menu_type" value="labels" <?php echo $hupso_labels_checked; ?> /> <?php _e('Show icons and service names'); ?><br/>
		<input type="radio" name="hupso_menu_type" value="icons" <?php echo $hupso_icons_checked; ?> /> <?php _e('Show icons only'); ?><br/></td>
	</tr>	
	<tr>
		<td><?php _e('Button position'); ?></td>
		<?php
			$button_position = get_option( 'hupso_button_position', 'below' );
			$checked = ' checked="checked" ';
			switch ( $button_position ) {
				case 'below': 	$hupso_below_checked = $checked; break;
				case 'above' :  $hupso_above_checked = $checked; break;
				default: $hupso_below_checked = $checked;
			}			
		
		?>
		<td><hr style="height:1px;"/><input type="radio" name="hupso_button_position" value="below" <?php echo $hupso_below_checked; ?> /> <?php _e('Below the post'); ?><br/>
		<input type="radio" name="hupso_button_position" value="above" <?php echo $hupso_above_checked; ?> /> <?php _e('Above the post'); ?><br/></td>
	</tr>	
	</table>
	<br/><br/><input class="button-primary" name="submit" type="submit" value="<?php _e('Save Settings'); ?>" />
	</form>
	</div>
	<?php
}

function hupso_admin_settings_save() {

	global $all_services, $default_services, $hupso_plugin_url;
	$code = '';
	
	if ( $_POST[ 'hupso_button_size' ] != '' )
		$post = true;
	else
		$post = false;	


	/* save button size */
	if ( $post ) {
		$hupso_button_size = $_POST[ 'hupso_button_size' ];
		update_option( 'hupso_button_size', $hupso_button_size );		
	} else {
		$hupso_button_size = get_option ( 'hupso_button_size', 'button120x28');
	}

	$b_size = str_replace( 'button', '', $hupso_button_size);
	list($width, $height) = split('x', $b_size);	
		
	/* save services */	
	$hupso_vars = 'var hupso_services=new Array(';
	foreach ( $all_services as $service_text ) {
		$service_name = strtolower( $service_text );
		$service_name = str_replace( ' ', '', $service_name );
		if ( $post ) {
			$value = $_POST[ $service_name ];
			update_option( 'hupso_' . $service_name, $value );
		}
		else {	
			$value = get_option ( 'hupso_' . $service_name, in_array( $service_text, $default_services ) );
		}
		if ( $value == '1' ) {
			$hupso_vars .= '"' . $service_text .'",';
		}
	}	
	$hupso_vars .= ');';
	$hupso_vars = str_replace(',)', ')', $hupso_vars);	
	
	/* save menu type */
	if ( $post ) {
		$hupso_menu_type = $_POST[ 'hupso_menu_type' ];	
		update_option( 'hupso_menu_type', $hupso_menu_type );		
	}
	else {	
		$hupso_menu_type = get_option ( 'hupso_menu_type', 'labels');	
	}
	$hupso_vars .= 'var hupso_icon_type = "'.$hupso_menu_type.'";';		

	
	/* save button position */
	if ( $post ) {
		$hupso_button_position = $_POST[ 'hupso_button_position' ];	
		update_option( 'hupso_button_position', $hupso_button_position );
	}
	else {
		$hupso_button_position = get_option ( 'hupso_button_position', 'below');		
	}	
	
	/* create and save code */
	/* all images are server from local Wordpress installation */
	/* minified button code is served by Hupso Static Server */
	$static_server = 'http://static.hupso.com/share/js/share.js';	
	$code = '<!-- Hupso Share Buttons (http://www.hupso.com/share) --><a class="hupso_pop" href="http://www.hupso.com/share"><img style="border:0px;" src="'.$hupso_plugin_url.'/buttons/'.$hupso_button_size.'.png" width="'.$width.'" height="'.$height.'" border="0" alt="Share Button"/></a><script type="text/javascript">';
	$code .= $hupso_vars;
	$code .= '</script><script type="text/javascript" src="' . $static_server . '"></script><!-- Hupso Share Buttons -->';
	update_option( 'hupso_share_buttons_code', $code );	
	
	/* create preview */
	$msg .= '<h3>' . __('Button Preview'). '</h3><br/>'; 
	$msg .= $code . '<br/><br/>';
	$msg .= __('Move your mouse over the button above to see the sharing menu.');
	$msg .= '<br/><br/><br/>';
	$msg .= '<input class="button-primary" name="submit" type="submit" value="' . __('Update Preview') . '" /> ';

	return $msg;
}


function hupso_the_content( $content ) {

	global $hupso_plugin_url;

	/* default code */
	$share_code = '<!-- Hupso Share Buttons (http://www.hupso.com/share) --><a class="hupso_pop" href="http://www.hupso.com/share"><img style="border:0px;" src="'.$hupso_plugin_url.'/buttons/button120x28.png" width="100" height="23" border="0" alt="Share Button"/></a><script type="text/javascript">var hupso_services=new Array("Twitter","Facebook","Google Plus","Linkedin","StumbleUpon","Digg","Reddit","Bebo","Delicious"); var hupso_icon_type = "labels";</script>';
	$static_server = 'http://static.hupso.com/share/js/share.js';	
	$share_code .= '<script type="text/javascript" src="' . $static_server . '"></script><!-- Hupso Share Buttons -->';	
   
    $code = get_option ( 'hupso_share_buttons_code', $share_code );
  
    $position = get_option( 'hupso_button_position', 'below' );
	if ($position == 'below')
		$new_content = $content . $code;   
    else
		$new_content = $code . $content;
   
	return $new_content; 
}  

function hupso_settings_print_services() {
	
	global $all_services, $default_services, $hupso_plugin_url;
	
	foreach ( $all_services as $service_text ) {
		$service_name = strtolower( $service_text );
		$service_name = str_replace( ' ', '', $service_name );
		
		$checked = '';
		$value = get_option( 'hupso_' . $service_name , in_array( $service_text, $default_services ) );
		if ( $value == "1" ) {
			$checked = 'checked="checked"';	 
		} 
		$text =' <img src="' . $hupso_plugin_url . '/img/services/' . $service_name . '.png"/> ' . $service_text;
		echo '<input type="checkbox" name="' . $service_name . '" value="1" ' . $checked . ' /> ' . $text . '<br/>';
	}		
}

function hupso_plugin_action_links( $links, $file ) {
    static $this_plugin;
    if ( !$this_plugin ) {
        $this_plugin = plugin_basename( __FILE__ );
    }
 
    // check to make sure we are on the correct plugin
    if ( $file == $this_plugin ) {
         $settings_link = '<a href="options-general.php?page=hupso-share-buttons-for-twitter-facebook-google/share-buttons-hupso.php">' . __('Settings') . '</a>';
        array_unshift( $links, $settings_link );
    }
 
    return $links;
}


?>