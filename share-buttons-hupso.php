<?php
/*
Plugin Name: Hupso Share Buttons for Twitter, Facebook & Google+
Plugin URI: http://www.hupso.com/share
Description: Add simple social sharing buttons to your articles. Your visitors will be able to easily share your content on the most popular social networks: Twitter, Facebook, Google Plus, Linkedin, StumbleUpon, Digg, Reddit, Bebo and Delicous. These services are used by millions of people every day, so sharing your content there will increase traffic to your website.
Version: 2.3
Author: kasal
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/



$hupso_excerpt = false;
$hupso_code = '';

$hupso_plugin_url = plugins_url() . '/hupso-share-buttons-for-twitter-facebook-google';
add_filter( 'the_content', 'hupso_the_content', 10 );
add_filter( 'get_the_excerpt', 'hupso_get_the_excerpt', 1);
add_filter( 'the_excerpt', 'hupso_the_excerpt', 100 );

load_plugin_textdomain( 'share_buttons_hupso', false, dirname( __FILE__ )  . '/languages' );

if ( is_admin() ) {
	add_filter('plugin_action_links', 'hupso_plugin_action_links', 10, 2);
	add_action('admin_menu', 'hupso_admin_menu');
}

add_action( 'admin_head', 'hupso_admin_head' );

$all_services = array(
	'Twitter', 'Facebook', 'Google Plus', 'Linkedin', 'StumbleUpon', 'Digg', 'Reddit', 'Bebo', 'Delicious'
);
$default_services = array(
	'Twitter', 'Facebook', 'Google Plus', 'Linkedin', 'StumbleUpon', 'Digg', 'Reddit', 'Bebo', 'Delicious'
);	


function hupso_admin_menu() {
	add_options_page('', '', 'manage_options', __FILE__, 'hupso_admin_settings_show', '', 6);
}

function hupso_admin_head() {
	if ( is_admin() ) {
		wp_enqueue_script(
			'hupso_create_button',
			plugins_url('/js/create_button.js', __FILE__ )
		);
	}
}   

function hupso_get_the_excerpt($param) {
	global $hupso_excerpt;
	$hupso_excerpt = true;
}

function hupso_the_excerpt() {
	global $hupso_code;
	
	if ( $hupso_code != '' ) {
		echo $hupso_code;
	}
}

function hupso_admin_settings_show() {
	global $all_services, $default_services, $hupso_plugin_url;
	
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	/* save settings */
	if ( $_POST[ 'size' ] != '' ) {	
		hupso_admin_settings_save();
	}

	echo '<div class="wrap" style="padding-bottom:100px;"><div class="icon32" id="icon-users"></div>';
	echo '<h2>'. __('Hupso Share Buttons for Twitter, Facebook & Google+ (Settings)').'</h2>';
	echo '<form name="hupso_settings_form" method="post" action="">'; 	
	echo '<div id="button_preview" style="float:right; width:405px; padding: 10px 10px 10px 10px; margin-right:10px; margin-left:20px; margin-top:20px; background: #F7FFBF;"><h3>Preview:</h3><br/>';
	echo '<div id="button"></div>';
	echo '<div id="move_mouse"><p style="font-size:13px; padding-top: 15px;"><b>Move your mouse over the button to see the sharing menu.</b></p></div><br/><br/>';
	//echo '<input class="button-primary" name="submit-preview" type="submit" onclick="hupso_create_code()" value="' . __('Save Settings') . '" />';
	echo '</div>';		
	
	$start = '<!-- Hupso Share Buttons - http://www.hupso.com/share/ -->';
	$end = '<!-- Hupso Share Buttons -->';
	$class_name = 'hupso_pop';
	$alt = 'Social Sharing Buttons';
	$class_url = ' href="http://www.hupso.com/share/" ';	
	$style = 'padding-left:5px; padding-top:5px; padding-bottom:5px; margin:0';

	$button_60_img = '<img style="'.$style.'" src="'.$hupso_plugin_url.'/buttons/button60x14.png" width="60" height="14" border="0" alt="'.$alt.'"/>';	
	$button_80_img = '<img style="'.$style.'" src="'.$hupso_plugin_url.'/buttons/button80x19.png" width="80" height="19" border="0" alt="'.$alt.'"/>';
	$button_100_img = '<img style="'.$style.'" src="'.$hupso_plugin_url.'/buttons/button100x23.png" width="100" height="23" border="0" alt="'.$alt.'"/>';
	$button_120_img = '<img style="'.$style.'" src="'.$hupso_plugin_url.'/buttons/button120x28.png" width="120" height="28" border="0" alt="'.$alt.'"/>';	
	$button_160_img = '<img style="'.$style.'" src="'.$hupso_plugin_url.'/buttons/button160x37.png" width="160" height="37" border="0" alt="'.$alt.'"/>';		
	
	$checked = 'checked="checked"';
	$current_button_size = get_option( 'hupso_button_size' , 'button100x23' ); 
	switch ( $current_button_size ) {
		case 'button60x14'  : $button60_checked = $checked; break;
		case 'button80x19'  : $button80_checked = $checked; break;
		case 'button100x23' : $button100_checked = $checked; break;
		case 'button120x28' : $button120_checked = $checked; break;
		case 'button160x37' : $button160_checked = $checked; break;
		default:
			$button100_checked = $checked; break;
	}
	
	?>
	
	<input type="hidden" name="code" value="" />	
	<br/>
	<div id="button_type">	
	<table border="0">
	<tr>
		<td style="width:100px;"><?php _e('Button type'); ?>
		</td>
		<?php
			$hupso_button_type = get_option( 'hupso_button_type', 'share_toolbar' );
			$checked = ' checked="checked" ';
			switch ( $hupso_button_type ) {
				case 'share_button': 	$hupso_share_button_checked = $checked; break;
				case 'share_toolbar': 	$hupso_share_toolbar_checked = $checked; break;
				default: $hupso_share_toolbar_checked = $checked;
			}			
		?>		
		<td><input type="radio" name="button_type" onclick="hupso_create_code()" value="share_button" <?php echo $hupso_share_button_checked; ?>  /> Share Button <br/><img src="<?php echo  $hupso_plugin_url.'/buttons/button100x23.png';?>" /><br/><br/>
		<input type="radio" name="button_type" onclick="hupso_create_code()" value="share_toolbar" <?php echo $hupso_share_toolbar_checked; ?> /> Share Toolbar<br/><img src="<?php echo $hupso_plugin_url.'/img/share_toolbar_short.png';?>" />		
		</td>	
	</tr>
	<tr><td></td><td><hr style="height:1px; width:200px;"/></td></tr>
	</table>	
	</div>
	
	<div id="button_style">
	<table border="0">
	<tr>
		<td style="width:100px;"><?php _e('Button size'); ?></td>
		<td>
			<table border="0">
			<tr><td><input type="radio" name="size" value="button60x14" onclick="hupso_create_code()" <?php echo $button60_checked; ?> /></td><td style="padding-right:10px;"><?php echo $button_60_img ?></td></tr>
			<tr><td><input type="radio" name="size" value="button80x19" onclick="hupso_create_code()" <?php echo $button80_checked; ?>/></td><td style="padding-right:10px;"><?php echo $button_80_img ?></td></tr>
			<tr><td><input type="radio" name="size" value="button100x23" onclick="hupso_create_code()" <?php echo $button100_checked; ?>/></td><td style="padding-right:10px;"><?php echo $button_100_img ?></td></tr>
			<tr><td><input type="radio" name="size" value="button120x28" onclick="hupso_create_code()" <?php echo $button120_checked; ?>/></td><td style="padding-right:10px;"><?php echo $button_120_img ?></td></tr>
			<tr><td><input type="radio" name="size" value="button160x37" onclick="hupso_create_code()" <?php echo $button160_checked; ?>/></td><td style="padding-right:20px;"><?php echo $button_160_img ?></td></tr>
			</table>
		</td>
	</tr>
	</table>
	</div>
	
	<div id="toolbar_size" style="display:none;">
	<table border="0">
	<tr>
		<td style="width:100px;"><?php _e('Toolbar size'); ?></td>
		<td style="width:100px">
		<?php
			$hupso_toolbar_size = get_option( 'hupso_toolbar_size', 'medium' );
			$checked = ' checked="checked" ';
			switch ( $hupso_toolbar_size ) {
				case 'big': 	 $hupso_toolbar_size_big_checked = $checked; break;
				case 'medium' :  $hupso_toolbar_size_medium_checked = $checked; break;
				case 'small' :   $hupso_toolbar_size_small_checked = $checked; break;
				default: $hupso_toolbar_size_medium_checked = $checked;
			}			
		?>
		<input type="radio" name="select_toolbar_size" value="big" onclick="hupso_create_code()" <?php echo $hupso_toolbar_size_big_checked; ?> /> Big<br/>
		<input type="radio" name="select_toolbar_size" value="medium" onclick="hupso_create_code()" <?php echo $hupso_toolbar_size_medium_checked; ?> /> Medium<br/>	
		<input type="radio" name="select_toolbar_size" value="small" onclick="hupso_create_code()" <?php echo $hupso_toolbar_size_small_checked; ?> /> Small<br/>	
	</tr>		
	</table>
	</div>	
	
	
	
	<table border="0">
	<tr>
		<td style="width:100px;"><?php _e('Social networks'); ?></td>
		<td><hr style="height:1px; width:200px;"/><?php hupso_settings_print_services(); ?></td>
	</tr>
	</table>
	
	<div id="show_icons">	
	<table border="0">
	<tr>
		<td style="width:100px;"><?php _e('Type of menu'); ?></td>
		<?php
			$menu_type = get_option( 'hupso_menu_type', 'labels' );
			$checked = ' checked="checked" ';
			switch ( $menu_type ) {
				case 'labels': 	$hupso_labels_checked = $checked; break;
				case 'icons' :  $hupso_icons_checked = $checked; break;
				default: $hupso_labels_checked = $checked;
			}			
		
		?>
		<td><hr style="height:1px; width:200px;"/><input type="radio" name="menu_type" value="labels" onclick="hupso_create_code()" <?php echo $hupso_labels_checked; ?> /> <?php _e('Show icons and service names'); ?><br/>
		<input type="radio" name="menu_type" value="icons" onclick="hupso_create_code()" <?php echo $hupso_icons_checked; ?> /> <?php _e('Show icons only'); ?><br/></td>
	</tr>	
	</table>
	</div>
	
	<table border="0">
	<tr>
		<td style="width:100px;"><?php _e('Button position'); ?></td>
		<?php
			$button_position = get_option( 'hupso_button_position', 'below' );
			$checked = ' checked="checked" ';
			switch ( $button_position ) {
				case 'below': 	$hupso_below_checked = $checked; break;
				case 'above' :  $hupso_above_checked = $checked; break;
				default: $hupso_below_checked = $checked;
			}			
		?>
		<td><hr style="height:1px; width:200px;" align="left"/>
		<input type="radio" name="hupso_button_position" value="above" <?php echo $hupso_above_checked; ?> /> <?php _e('Above the post'); ?><br/>
		<input type="radio" name="hupso_button_position" value="below" <?php echo $hupso_below_checked; ?> /> <?php _e('Below the post'); ?><br/></td>
	</tr>	
	<tr>
		<td style="width:100px;"><?php _e('Display options'); ?></td>
		<td><hr style="height:1px; width:200px;" align="left"/>
			<?php
				$checked = ' checked="checked" ';
				
				$hupso_show_frontpage = get_option( 'hupso_show_frontpage', '1' );
				if ( $hupso_show_frontpage == 1 )
					$hupso_show_frontpage_checked = $checked;	
				else
					$hupso_show_frontpage_checked = '';	
					
				$hupso_show_category = get_option( 'hupso_show_category', '1' );
				if ( $hupso_show_category == 1 )
					$hupso_show_category_checked = $checked;	
				else
					$hupso_show_category_checked = '';						
			?>
			<input type="checkbox" name="hupso_show_frontpage" value="1" <?php echo $hupso_show_frontpage_checked; ?> /> <?php _e('Front page - show social buttons in posts on front page'); ?><br/>
			<input type="checkbox" name="hupso_show_category" value="1" <?php echo $hupso_show_category_checked; ?> /> <?php _e('Categories - show social buttons in posts when viewing categories, tags or dates'); ?><br/>		
		</td>
	</tr>	
	</table>
	<br/><br/><input class="button-primary" name="submit" type="submit" onclick="hupso_create_code()" value="<?php _e('Save Settings'); ?>" />
	</form>
	</div>
	
	<?php
}

function hupso_admin_settings_save() {

	global $all_services, $default_services, $hupso_plugin_url;	
	update_option( 'hupso_custom', '1' );

	if ( $_POST[ 'size' ] != '' )
		$post = true;
	else
		$post = false;	

	/* save button type */
	if ( $post ) {
		$hupso_button_type = $_POST[ 'button_type' ];
		update_option( 'hupso_button_type', $hupso_button_type );		
	} else {
		$hupso_button_type = get_option ( 'hupso_button_type', 'share_toolbar');
	}

	/* save button size */
	if ( $post ) {
		$hupso_button_size = $_POST[ 'size' ];
		update_option( 'hupso_button_size', $hupso_button_size );		
	} else {
		$hupso_button_size = get_option ( 'hupso_button_size', 'button100x23');
	}
	$b_size = str_replace( 'button', '', $hupso_button_size);
	list($width, $height) = split('x', $b_size);	
	
	/* save toolbar size */
	if ( $post ) {
		$hupso_toolbar_size = $_POST[ 'select_toolbar_size' ];
		update_option( 'hupso_toolbar_size', $hupso_toolbar_size );		
	} else {
		$hupso_button_size = get_option ( 'hupso_toolbar_size', 'medium');
	}	
			
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
	$hupso_vars = str_replace( ',)', ')', $hupso_vars );	
	
	/* save menu type */
	if ( $post ) {
		$hupso_menu_type = $_POST[ 'menu_type' ];	
		update_option( 'hupso_menu_type', $hupso_menu_type );		
	}
	else {	
		$hupso_menu_type = get_option ( 'hupso_menu_type', 'labels' );	
	}
	$hupso_vars .= 'var hupso_icon_type = "'.$hupso_menu_type.'";';		

	/* save button position */
	if ( $post ) {
		$hupso_button_position = $_POST[ 'hupso_button_position' ];	
		update_option( 'hupso_button_position', $hupso_button_position );
	}
	else {
		$hupso_button_position = get_option( 'hupso_button_position', 'below' );		
	}	
	
	/* save display options */
	if ( $post ) {
		$hupso_show_frontpage = $_POST[ 'hupso_show_frontpage' ];	
		update_option( 'hupso_show_frontpage', $hupso_show_frontpage );
		
		$hupso_show_category = $_POST[ 'hupso_show_category' ];	
		update_option( 'hupso_show_category', $hupso_show_category );		
	}
	
	/* save button code */
	if ( $post ) {
		$code = stripslashes($_POST[ 'code' ]);
		update_option( 'hupso_share_buttons_code', $code );
	}
	
}


function hupso_the_content( $content ) {

	global $hupso_plugin_url, $wp_version, $hupso_excerpt, $hupso_code;

	/* Do not show share buttons in feeds */
	if ( is_feed() ) {
		return $content;
	}
	
	$post_url = get_permalink($GLOBALS['post']->ID);
	
	$hupso_show_frontpage = get_option( 'hupso_show_frontpage' , '1' );
	$hupso_show_category = get_option( 'hupso_show_category' , '1' );	
	
	/* Do not show share buttons if option is disabled */
	if ( is_home() && $hupso_show_frontpage != 1 ) {
		return $content;
	}
	/* Do not show share buttons if option is disabled */
	if ( is_archive() && $hupso_show_category != 1 ) {
		return $content;
	}	


	/* default code */
	$share_code = '<!-- Hupso Share Buttons - http://www.hupso.com/share/ --><a class="hupso_toolbar" href="http://www.hupso.com/share/"><img src="http://static.hupso.com/share/buttons/share-medium.png" border="0" style="padding-top:5px; float:left;" alt="Social Share Toolbar"/></a><script type="text/javascript">var hupso_services_t=new Array("Twitter","Facebook","Google Plus","Linkedin","StumbleUpon","Digg","Reddit","Bebo","Delicious"); var hupso_toolbar_size_t="medium";';
	
    $code = get_option( 'hupso_share_buttons_code', $share_code );		
	$button_type = get_option( 'hupso_button_type', 'share_toolbar' );
	
	/* Check for old saved button code, prior to version 1.3 */
	if ( get_option( 'hupso_custom', '0' ) == 0 ) {
		$old_check = strpos( $code, '</script>' );
		if ( $old_check !== false ) {
			$code = substr( $code, 0, $old_check );
			
			/* Save new code */
			update_option( 'hupso_custom', '1' );
			update_option( 'hupso_share_buttons_code', $code );
		}	
	}
	
	/* Check for old saved button code, prior to version 2.0 */
	$old_check = strpos( $code, 'hupso_pop' );
	if ( $old_check !== false ) {
		$button_type = 'share_button';
	}	
	$old_check = strpos( $code, 'hupso_toolbar' );
	if ( $old_check !== false ) {
		$button_type = 'share_toolbar';
	}	
	
	/* Check for RTL language */
	$rtl = false;
	if ( version_compare($wp_version, '3.0', '<' ) ) {
		if ( get_bloginfo('text_direction') == 'rtl' ) {
			$rtl = true;
		}	
	}
	else {
		$rtl = is_rtl();
	}

	if ( $rtl ) {
		$code = str_replace( 'float:left', 'float:right', $code );
	}

	
	if ( ( is_home() && $hupso_show_frontpage == 1 ) || ( is_archive() && $hupso_show_category == 1 ) ) {
			
		switch ( $button_type ) {
			case 'share_button': 
				$code .= 'var hupso_url="' . $post_url . '";';
				break;
			case 'share_toolbar':
				$code .= 'var hupso_url_t="' . $post_url . '";';
				break;
		}
			
	}
	
	$code .= '</script>';
	
	switch ( $button_type ) {
		case 'share_button': 
			$js_file = 'share.js';
			break;
		case 'share_toolbar':
			$js_file = 'share_toolbar.js';
			break;
	}
	
	$static_server = 'http://static.hupso.com/share/js/' . $js_file;
	$code .= '<script type="text/javascript" src="' . $static_server . '"></script><!-- Hupso Share Buttons -->';	
   
    $position = get_option( 'hupso_button_position', 'below' );
	
	$excerpt = $GLOBALS['post']->post_excerpt;

	if ( $position == 'below' ) {
		$new_content = $content . $code;
		
		if ( $excerpt != '' ) {
			$new_excerpt = '<p>' . $excerpt . '</p>' . $code;
		}
		else {
			$new_excerpt = $code;
		}
	}	   
    else {
		$new_content = $code . '<br/>' . $content;
		$new_excerpt = $code . '<br/>' . $excerpt;		
   }
   
    if ( $hupso_excerpt ) {
		$hupso_code = $new_excerpt;
		return $content;
	}
	else { 
		return $new_content;
	} 
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
		echo '<input type="checkbox" name="' . $service_name . '" value="1" onclick="hupso_create_code()" ' . $checked . ' /> ' . $text . '<br/>';
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