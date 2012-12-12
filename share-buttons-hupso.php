<?php
/*
Plugin Name: Hupso Share Buttons for Twitter, Facebook & Google+
Plugin URI: http://www.hupso.com/share/
Description: Add simple social sharing buttons to your articles. Your visitors will be able to easily share your content on the most popular social networks: Twitter, Facebook, Google Plus, Linkedin, StumbleUpon, Digg, Reddit, Bebo and Delicous. These services are used by millions of people every day, so sharing your content there will increase traffic to your website.
Version: 3.2
Author: kasal
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: share_buttons_hupso
Domain Path: /languages
*/

$hupso_dev = '';

$hupso_plugin_url = plugins_url() . '/hupso-share-buttons-for-twitter-facebook-google';
add_filter( 'the_content', 'hupso_the_content', 10 );
add_filter( 'get_the_excerpt', 'hupso_get_the_excerpt', 1);
add_filter( 'the_excerpt', 'hupso_the_content', 100 );

load_plugin_textdomain( 'share_buttons_hupso', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

if ( is_admin() ) {
	add_filter('plugin_action_links', 'hupso_plugin_action_links', 10, 2);
	add_action('admin_menu', 'hupso_admin_menu');
}

add_action( 'admin_head', 'hupso_admin_head' );
add_action( 'wp_head', 'hupso_set_facebook_thumbnail', 1 );

$hupso_all_services = array(
	'Twitter', 'Facebook', 'Google Plus', 'Linkedin', 'StumbleUpon', 'Digg', 'Reddit', 'Bebo', 'Delicious'
);
$hupso_default_services = array(
	'Twitter', 'Facebook', 'Google Plus', 'Linkedin', 'StumbleUpon', 'Digg', 'Reddit', 'Bebo', 'Delicious'
);	


if ( function_exists('register_activation_hook') )
	register_activation_hook( __FILE__, 'hupso_plugin_activation' );

if ( function_exists('register_uninstall_hook') )
	register_uninstall_hook( __FILE__, 'hupso_plugin_uninstall' );

function hupso_plugin_uninstall() {
	delete_option( 'hupso_custom' );
	delete_option( 'hupso_button_type' );
	delete_option( 'hupso_button_size' );
	delete_option( 'hupso_toolbar_size' );
	delete_option( 'hupso_menu_type' );
	delete_option( 'hupso_button_position' );
	delete_option( 'hupso_show_frontpage' );
	delete_option( 'hupso_show_category' );
	delete_option( 'hupso_twitter_tweet' );
	delete_option( 'hupso_facebook_like' );
	delete_option( 'hupso_facebook_send' );
	delete_option( 'hupso_google_plus_one' );
	delete_option( 'hupso_linkedin_share' );
	delete_option( 'hupso_share_buttons_code' );
	delete_option( 'hupso_twitter' );
	delete_option( 'hupso_facebook' );
	delete_option( 'hupso_googleplus' );
	delete_option( 'hupso_linkedin' );
	delete_option( 'hupso_stumbleupon' );
	delete_option( 'hupso_digg' );
	delete_option( 'hupso_reddit' );
	delete_option( 'hupso_bebo' );
	delete_option( 'hupso_delicious' );
}

function hupso_plugin_activation() {

	/* Fix for bug in version 3.0 */
	$size = get_option( 'hupso_button_size', '');
	if ( ($size == 'share_button') or ($size == 'share_toolbar') or ($size == 'counters') ) {
		update_option( 'hupso_button_size', 'button100x23');
	}
}

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

function hupso_set_facebook_thumbnail() {
	global $post;
	if ( !is_singular() )
		return;
	if ( ( function_exists('has_post_thumbnail') ) && ( has_post_thumbnail( $post->ID ) ) ) {
		$thumb_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
		echo '<meta property="og:image" content="' . esc_attr( $thumb_image[0] ) . '"/>';
	}	
}

function hupso_get_the_excerpt($content) {
	return $content;
}

function hupso_admin_settings_show() {
	global $hupso_all_services, $hupso_default_services, $hupso_plugin_url;
	
	$hupso_share_image = __('Share', 'share_buttons_hupso');	
	
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' , 'share_buttons_hupso') );
	}
	
	/* save settings */
	if ( $_POST[ 'button_type' ] != '' ) {	
		hupso_admin_settings_save();
	}

	echo '<div class="wrap" style="padding-bottom:100px;"><div class="icon32" id="icon-users"></div>';
	echo '<h2>'. __('Hupso Share Buttons for Twitter, Facebook & Google+ (Settings)', 'share_buttons_hupso').'</h2>';
	echo '<form name="hupso_settings_form" method="post" action="">'; 	
	
	echo '<div id="right" style="float:right; width:200px; margin-right:10px; margin-left:20px; margin-top:20px;">';
	echo '<div id="button_preview" style="background: #F7FFBF; padding: 10px 10px 10px 10px; "><h3>' . __( 'Preview', 'share_buttons_hupso') . '</h3><br/>';
	echo '<div id="button"></div>';
	echo '<div id="move_mouse"><p style="font-size:13px; padding-top: 15px;"><b>Move your mouse over the button to see the sharing menu.</b></p></div><br/><br/>';
	echo '<div style="padding-left:40px;"><input class="button-primary" name="submit-preview" type="submit" onclick="hupso_create_code()" value="' . __('Save Settings', 'share_buttons_hupso') . '" /></div>';
	echo '</div>';	
	echo '<div id="tips" style="background: #CCCCFF; padding: 10px 10px 10px 10px; margin-top:30px; ">';
	echo '<p><b>Shortcodes</b></p>';
	echo '<p>Use <b>[hupso_hide]</b> anywhere in post\'s text to hide buttons for specific post.</p>';
	echo '<p>Use <b>[hupso]</b> anywhere in post\'s text to show buttons for specific post at custom position.</p>';
	echo '</div>';	
	echo '<div id="feedback" style="background: #C7FFA3; padding: 10px 10px 10px 10px; margin-top:30px; ">';	
	echo '<p><b>Bugs? Comments?</b></p>';
	echo '<p>We value your feedback. Please send comments, bug reports and suggestions, so we can make this plugin the best social sharing plugin for Wordpress.</p>';
	echo '<p><a href="http://www.hupso.com/share/feedback/" target="_blank">Use this form</a> (opens in new window).</p>';
	echo '</div>';
	
	echo '</div>';
	
	
	$start = '<!-- Hupso Share Buttons - http://www.hupso.com/share/ -->';
	$end = '<!-- Hupso Share Buttons -->';
	$class_name = 'hupso_pop';
	$alt = 'Share';
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
		<td style="width:100px;"><?php _e('Button type', 'share_buttons_hupso'); ?>
		</td>
		<?php
			$hupso_button_type = get_option( 'hupso_button_type', 'share_toolbar' );
			$checked = ' checked="checked" ';
			switch ( $hupso_button_type ) {
				case 'share_button': 	$hupso_share_button_checked = $checked; break;
				case 'share_toolbar': 	$hupso_share_toolbar_checked = $checked; break;
				case 'counters': 		$hupso_share_counters_checked = $checked; break;
				default: $hupso_share_toolbar_checked = $checked;
			}			
		?>		
		<td><input type="radio" name="button_type" onclick="hupso_create_code()" value="share_button" <?php echo $hupso_share_button_checked; ?>  /> Share Button<br/><img src="<?php echo  $hupso_plugin_url.'/buttons/button100x23.png';?>" /><br/><br/>
		<input type="radio" name="button_type" onclick="hupso_create_code()" value="share_toolbar" <?php echo $hupso_share_toolbar_checked; ?> /> Share Toolbar <br/><img src="<?php echo $hupso_plugin_url.'/img/share_toolbar_short.png';?>" /><br/><br/>	
		<input type="radio" name="button_type" onclick="hupso_create_code()" value="counters" <?php echo $hupso_share_counters_checked; ?> /> Counters <br/><img src="<?php echo $hupso_plugin_url.'/img/counters.png';?>" /><br/><br/>
		</td>	
	</tr>
	<tr><td></td><td><hr style="height:1px; width:100%;"/></td></tr>
	</table>	
	</div>
	
	<div id="button_style">
	<table border="0">
	<tr>
		<td style="width:100px;"><?php _e('Button size', 'share_buttons_hupso'); ?></td>
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
		<td style="width:100px;"><?php _e('Toolbar size', 'share_buttons_hupso'); ?></td>
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
		<input type="radio" name="select_toolbar_size" value="big" onclick="hupso_create_code()" <?php echo $hupso_toolbar_size_big_checked; ?> /> <?php _e( 'Big', 'share_buttons_hupso');?> <br/>
		<input type="radio" name="select_toolbar_size" value="medium" onclick="hupso_create_code()" <?php echo $hupso_toolbar_size_medium_checked; ?> /> <?php _e( 'Medium', 'share_buttons_hupso');?> <br/>	
		<input type="radio" name="select_toolbar_size" value="small" onclick="hupso_create_code()" <?php echo $hupso_toolbar_size_small_checked; ?> /> <?php _e( 'Small', 'share_buttons_hupso');?> <br/>	
	</tr>		
	</table>
	</div>	
	
	<div id="services">
	<table border="0">
	<tr>
		<td style="width:100px;"><?php _e('Social networks', 'share_buttons_hupso'); ?></td>
		<td><hr style="height:1px; width:200px;"/><?php hupso_settings_print_services(); ?></td>
	</tr>
	</table>
	</div>
		<?php
			$checked = ' checked="checked" ';
			
			$twitter_tweet = get_option( 'hupso_twitter_tweet', '1' );
			if ( $twitter_tweet == 1 ) $twitter_tweet_checked = $checked;
			
			$facebook_like = get_option( 'hupso_facebook_like', '1' );
			if ( $facebook_like == 1 ) $facebook_like_checked = $checked;	
			
			$facebook_send = get_option( 'hupso_facebook_send', '1' );
			if ( $facebook_send == 1 ) $facebook_send_checked = $checked;
			
			$google_plus_one = get_option( 'hupso_google_plus_one', '1' );
			if ( $google_plus_one == 1 ) $google_plus_one_checked = $checked;
			
			$linkedin_share = get_option( 'hupso_linkedin_share', '1' );
			if ( $linkedin_share == 1 ) $linkedin_share_checked = $checked;	
		?>	
	<div id="counters_config" style="display:none;">
	<table border="0">
	<tr>
		<td style="width:100px;"><?php _e('Social networks', 'share_buttons_hupso'); ?></td>
		<td>
			<table>
			<tr>
				<td><input type="checkbox" name="twitter_tweet" onclick="hupso_create_code()" value="1" <?php echo $twitter_tweet_checked;?> /></td>
				<td><img src="<?php echo $hupso_plugin_url; ?>/img/counters/twitter_tweet.png" /></td>
				<td></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="facebook_like" onclicke="hupso_create_code()" value="1" <?php echo $facebook_like_checked;?> /></td>
				<td><img src="<?php echo $hupso_plugin_url; ?>/img/counters/facebook_like.png" /></td>
				<td>
					<table>
						<tr>
							<td><input type="checkbox" name="facebook_send" onclick="hupso_create_code()" value="1" <?php echo $facebook_send_checked;?> /></td>
							<td><img src="<?php echo $hupso_plugin_url; ?>/img/counters/facebook_send.png" /></td>
						</tr>
					</table>
			</tr>
			<tr>
				<td><input type="checkbox" name="google_plus_one" onclick="hupso_create_code()" value="1" <?php echo $google_plus_one_checked;?> /></td>
				<td><img src="<?php echo $hupso_plugin_url; ?>/img/counters/google_plus_one.png" /></td>
				<td></td>
			</tr>	
			<tr>
				<td><input type="checkbox" name="linkedin_share" onclick="hupso_create_code()" value="1" <?php echo $linkedin_share_checked;?> /></td>
				<td><img src="<?php echo $hupso_plugin_url; ?>/img/counters/linkedin_share.png" /></td>
				<td></td>
			</tr>						
			</table>	

		</td>
	</tr>
	</table>
	</div>
	<div id="show_icons">	
	<table border="0">
	<tr>
		<td style="width:100px;"><?php _e('Type of menu', 'share_buttons_hupso'); ?></td>
		<?php
			$menu_type = get_option( 'hupso_menu_type', 'labels' );
			$checked = ' checked="checked" ';
			switch ( $menu_type ) {
				case 'labels': 	$hupso_labels_checked = $checked; break;
				case 'icons' :  $hupso_icons_checked = $checked; break;
				default: $hupso_labels_checked = $checked;
			}			
		
		?>
		<td><hr style="height:1px; width:200px;"/><input type="radio" name="menu_type" value="labels" onclick="hupso_create_code()" <?php echo $hupso_labels_checked; ?> /> <?php _e('Show icons and service names', 'share_buttons_hupso'); ?><br/>
		<input type="radio" name="menu_type" value="icons" onclick="hupso_create_code()" <?php echo $hupso_icons_checked; ?> /> <?php _e('Show icons only', 'share_buttons_hupso'); ?><br/></td>
	</tr>	
	</table>
	</div>
	
	<table border="0">
	<tr>
		<td style="width:100px;"><?php _e('Button position', 'share_buttons_hupso'); ?></td>
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
		<input type="radio" name="hupso_button_position" value="above" <?php echo $hupso_above_checked; ?> /> <?php _e('Above the post', 'share_buttons_hupso'); ?><br/>
		<input type="radio" name="hupso_button_position" value="below" <?php echo $hupso_below_checked; ?> /> <?php _e('Below the post', 'share_buttons_hupso'); ?><br/></td>
	</tr>	
	<tr>
		<td style="width:100px;"><?php _e('Display options', 'share_buttons_hupso'); ?></td>
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
			<input type="checkbox" name="hupso_show_frontpage" value="1" <?php echo $hupso_show_frontpage_checked; ?> /> <?php _e('Front page - show social buttons in posts on front page', 'share_buttons_hupso'); ?><br/>
			<input type="checkbox" name="hupso_show_category" value="1" <?php echo $hupso_show_category_checked; ?> /> <?php _e('Categories - show social buttons in posts when viewing categories, tags or dates', 'share_buttons_hupso'); ?><br/>		
		</td>
	</tr>	
	</table>
	<br/><br/><input class="button-primary" name="submit" type="submit" onclick="hupso_create_code()" value="<?php _e('Save Settings', 'share_buttons_hupso'); ?>" />
	</form>
	</div>
	
	<?php
}

function hupso_admin_settings_save() {

	global $hupso_all_services, $hupso_default_services, $hupso_plugin_url;	
	update_option( 'hupso_custom', '1' );

	if ( $_POST[ 'button_type' ] != '' )
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
	foreach ( $hupso_all_services as $service_text ) {
		$service_name = strtolower( $service_text );
		$service_name = str_replace( ' ', '', $service_name );
		if ( $post ) {
			$value = $_POST[ $service_name ];
			update_option( 'hupso_' . $service_name, $value );
		}
		else {	
			$value = get_option ( 'hupso_' . $service_name, in_array( $service_text, $hupso_default_services ) );
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
	
	/* save options for counters */
	if ( $post ) {
		$twitter_tweet = $_POST[ 'twitter_tweet' ];	
		update_option( 'hupso_twitter_tweet', $twitter_tweet );	
	
		$facebook_like = $_POST[ 'facebook_like' ];	
		update_option( 'hupso_facebook_like', $facebook_like );	
		
		$facebook_send = $_POST[ 'facebook_send' ];	
		update_option( 'hupso_facebook_send', $facebook_send );	
	
		$google_plus_one = $_POST[ 'google_plus_one' ];	
		update_option( 'hupso_google_plus_one', $google_plus_one );	
		
		$linkedin_share = $_POST[ 'linkedin_share' ];	
		update_option( 'hupso_linkedin_share', $linkedin_share );	
	}
	
	/* save button code */
	if ( $post ) {
		$code = stripslashes($_POST[ 'code' ]);
		update_option( 'hupso_share_buttons_code', $code );
	}
	
}


function hupso_the_content( $content ) {

	global $hupso_plugin_url, $wp_version, $hupso_dev;

	/* Do not show share buttons in feeds */
	if ( is_feed() ) {
		return $content;
	}
	
	/* Do now show share buttons when [hupso_hide] is used */
	if ( stripos($content, '[hupso_hide]') !== false ) {
		$content = str_ireplace('[hupso_hide]', '', $content);
		$content = str_ireplace('[hupso]', '', $content);
		return $content;
	}
	
	$post_url = get_permalink($GLOBALS['post']->ID);
	$post_title = $GLOBALS['post']->post_title;
	
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
	$share_code = '<!-- Hupso Share Buttons - http://www.hupso.com/share/ --><a class="hupso_toolbar" href="http://www.hupso.com/share/"><img src="http://static.hupso.com/share' . $hupso_dev . '/buttons/share-medium.png" border="0" style="padding-top:5px; float:left;" alt="Share"/></a><script type="text/javascript">var hupso_services_t=new Array("Twitter","Facebook","Google Plus","Linkedin","StumbleUpon","Digg","Reddit","Bebo","Delicious"); var hupso_toolbar_size_t="medium";';
	
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
			case 'counters':
				$code .= 'var hupso_url_c="' . $post_url . '";';
				break;
		}
			
	}
	
	switch ( $button_type ) {
		case 'share_button': 
			$code .= 'var hupso_title="' . $post_title . '";';
			break;
		case 'share_toolbar':
			$code .= 'var hupso_title_t="' . $post_title . '";';
			break;
		case 'counters':
			$code .= 'var hupso_title_c="' . $post_title . '";';
			break;
	}	
	
	$code .= '</script>';
	
	switch ( $button_type ) {
		case 'share_button': 
			$js_file = 'share.js';
			break;
		case 'share_toolbar':
			$js_file = 'share_toolbar.js';
			break;
		case 'counters':
			$js_file = 'counters.js';
			break;			
	}
	
	$static_server = 'http://static.hupso.com/share' . $hupso_dev . '/js/' . $js_file;
	$code .= '<script type="text/javascript" src="' . $static_server . '"></script><!-- Hupso Share Buttons -->';	
   
    $position = get_option( 'hupso_button_position', 'below' );
	
	if ( stripos($content, '[hupso]') !== false) {
		$new_content = str_ireplace('[hupso]', '<p>' . $code . '</p>', $content);
	}
	else {
		if ( $position == 'below' ) {
			$new_content = $content . '<p>' . $code . '</p>';   
    	}
		else {
			$new_content = '<p>' . $code . '</p>' . $content;
		}
	}	
		
	return $new_content;
		
}  

function hupso_settings_print_services() {
	
	global $hupso_all_services, $hupso_default_services, $hupso_plugin_url;
	
	foreach ( $hupso_all_services as $service_text ) {
		$service_name = strtolower( $service_text );
		$service_name = str_replace( ' ', '', $service_name );
		
		$checked = '';
		$value = get_option( 'hupso_' . $service_name , in_array( $service_text, $hupso_default_services ) );
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
         $settings_link = '<a href="options-general.php?page=hupso-share-buttons-for-twitter-facebook-google/share-buttons-hupso.php">' . __('Settings', 'share_buttons_hupso') . '</a>';
        array_unshift( $links, $settings_link );
    }
 
    return $links;
}





?>