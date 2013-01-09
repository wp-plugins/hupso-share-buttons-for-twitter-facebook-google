<?php
/*
Plugin Name: Hupso Share Buttons for Twitter, Facebook & Google+
Plugin URI: http://www.hupso.com/share/
Description: Add simple social sharing buttons to your articles. Your visitors will be able to easily share your content on the most popular social networks: Twitter, Facebook, Google Plus, Linkedin, StumbleUpon, Digg, Reddit, Bebo and Delicous. These services are used by millions of people every day, so sharing your content there will increase traffic to your website.
Version: 3.6
Author: kasal
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: share_buttons_hupso
Domain Path: /languages
*/

$hupso_dev = '';
$hupso_state = 'normal';

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

add_action('widgets_init', 'hupso_widget_init');
add_shortcode( 'hupso', 'hupso_shortcodes' );

/* Use shortcodes in text widgets */
$hupso_widget_text = get_option( 'hupso_widget_text', '1');
if ( $hupso_widget_text == '1' ) {
	add_filter('widget_text', 'do_shortcode');
}

function hupso_widget_init() {
    include_once(plugin_dir_path( __FILE__ ) . '/share-buttons-hupso-widget.php');
    register_widget('Hupso_Widget');
}

function hupso_shortcodes( $atts ) {
	global $hupso_state;
	$hupso_state = 'shortcodes';
	if ($atts == '') {
		return hupso_the_content('');
	}
}

if ( function_exists('register_activation_hook') )
	register_activation_hook( __FILE__, 'hupso_plugin_activation' );

if ( function_exists('register_uninstall_hook') )
	register_uninstall_hook( __FILE__, 'hupso_plugin_uninstall' );

function hupso_plugin_uninstall() {
	delete_option( 'hupso_custom' );
	delete_option( 'hupso_button_type' );
	delete_option( 'hupso_button_size' );
	delete_option( 'hupso_toolbar_size' );
	delete_option( 'hupso_share_image' );
	delete_option( 'hupso_share_image_lang' );
	delete_option( 'hupso_menu_type' );
	delete_option( 'hupso_button_position' );
	delete_option( 'hupso_show_posts' );
	delete_option( 'hupso_show_pages' );		
	delete_option( 'hupso_show_frontpage' );
	delete_option( 'hupso_show_category' );
	delete_option( 'hupso_twitter_tweet' );
	delete_option( 'hupso_facebook_like' );
	delete_option( 'hupso_facebook_send' );
	delete_option( 'hupso_google_plus_one' );
	delete_option( 'hupso_linkedin_share' );
	delete_option( 'hupso_counters_lang' );
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
	delete_option( 'hupso_title_text' );
	delete_option( 'hupso_twitter_via' );
	delete_option( 'hupso_css_style' );
	delete_option( 'hupso_widget_text' );
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
	$content = str_ireplace('[hupso_hide]', '', $content);
	$content = str_ireplace('[hupso]', '', $content);
	return $content;
}

function hupso_admin_settings_show() {
	global $hupso_all_services, $hupso_default_services, $hupso_plugin_url;
	
	$hupso_lang_code = __('en_US', 'share_buttons_hupso');
	$hupso_language = __('English', 'share_buttons_hupso');	
	$hupso_share_image = __('Share', 'share_buttons_hupso');
	$hupso_excerpts = __('Excerpts', 'share_buttons_hupso');
	$hupso_feeds = __('Feeds', 'share_buttons_hupso');	
	
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' , 'share_buttons_hupso') );
	}
	
	/* save settings */
	if ( @$_POST[ 'button_type' ] != '' ) {	
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
	echo '<p><b>' . __('Shortcodes', 'share_buttons_hupso') . '</b></p>';
	echo '<p>Use <b>[hupso_hide]</b> anywhere in post\'s text to hide buttons for specific post.</p>';
	echo '<p>Use <b>[hupso]</b> anywhere in post\'s text to show buttons for specific post at custom position.</p>';
	echo '<p>Use <b>Hupso Share Buttons Widget</b> to show share buttons in sidebar or footer.</p>';	
	echo '<p>Use <b>echo do_shortcode( \'[hupso]\' ); </b> to show share buttons anywhere inside template files.</p>';	
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
	$button60_checked = '';
	$button80_checked = '';
	$button100_checked = '';
	$button120_checked = '';
	$button160_checked = '';
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
			$hupso_share_button_checked = '';
			$hupso_share_toolbar_checked = '';
			$hupso_share_counters_checked = '';
			switch ( $hupso_button_type ) {
				case 'share_button': 	$hupso_share_button_checked = $checked; break;
				case 'share_toolbar': 	$hupso_share_toolbar_checked = $checked; break;
				case 'counters': 		$hupso_share_counters_checked = $checked; break;
				default: $hupso_share_toolbar_checked = $checked;
			}			
		?>		
		<td><input type="radio" name="button_type" onclick="hupso_create_code()" onchange="hupso_create_code()" value="share_button" <?php echo $hupso_share_button_checked; ?>  /> Share Button<br/><img src="<?php echo  $hupso_plugin_url.'/buttons/button100x23.png';?>" /><br/><br/>
		<input type="radio" name="button_type" onclick="hupso_create_code()" onchange="hupso_create_code()" value="share_toolbar" <?php echo $hupso_share_toolbar_checked; ?> /> Share Toolbar <br/><img src="<?php echo $hupso_plugin_url.'/img/share_toolbar_big.png';?>" /><br/><br/>	
		<input type="radio" name="button_type" onclick="hupso_create_code()" onchange="hupso_create_code()" value="counters" <?php echo $hupso_share_counters_checked; ?> /> Counters <br/><img src="<?php echo $hupso_plugin_url.'/img/counters.png';?>" /><br/><br/>
		</td>	
	</tr>
	<tr><td style="width:100px;"></td><td><hr style="height:1px; width:500px; float:left;"/></td></tr>
	</table>	
	</div>
	
	<div id="button_style">
	<table border="0">
	<tr>
		<td style="width:100px;"><?php _e('Button size', 'share_buttons_hupso'); ?></td>
		<td>
			<table border="0">
			<tr><td><input type="radio" name="size" value="button60x14" onclick="hupso_create_code()" onchange="hupso_create_code()" <?php echo $button60_checked; ?> /></td><td style="padding-right:10px;"><?php echo $button_60_img ?></td></tr>
			<tr><td><input type="radio" name="size" value="button80x19" onclick="hupso_create_code()" onchange="hupso_create_code()" <?php echo $button80_checked; ?>/></td><td style="padding-right:10px;"><?php echo $button_80_img ?></td></tr>
			<tr><td><input type="radio" name="size" value="button100x23" onclick="hupso_create_code()" onchange="hupso_create_code()" <?php echo $button100_checked; ?>/></td><td style="padding-right:10px;"><?php echo $button_100_img ?></td></tr>
			<tr><td><input type="radio" name="size" value="button120x28" onclick="hupso_create_code()" onchange="hupso_create_code()" <?php echo $button120_checked; ?>/></td><td style="padding-right:10px;"><?php echo $button_120_img ?></td></tr>
			<tr><td><input type="radio" name="size" value="button160x37" onclick="hupso_create_code()" onchange="hupso_create_code()" <?php echo $button160_checked; ?>/></td><td style="padding-right:20px;"><?php echo $button_160_img ?></td></tr>
			</table>
<hr style="height:1px; width:500px;"/>			
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
			$hupso_toolbar_size_big_checked = '';
			$hupso_toolbar_size_medium_checked = '';
			$hupso_toolbar_size_small_checked = '';
			$checked = ' checked="checked" ';
			switch ( $hupso_toolbar_size ) {
				case 'big': 	 $hupso_toolbar_size_big_checked = $checked; break;
				case 'medium' :  $hupso_toolbar_size_medium_checked = $checked; break;
				case 'small' :   $hupso_toolbar_size_small_checked = $checked; break;
				default: $hupso_toolbar_size_medium_checked = $checked;
			}			
		?>
		<input type="radio" name="select_toolbar_size" value="big" onclick="hupso_create_code()" onchange="hupso_create_code()" <?php echo $hupso_toolbar_size_big_checked; ?> /> <?php _e( 'Big', 'share_buttons_hupso');?> <br/>
		<input type="radio" name="select_toolbar_size" value="medium" onclick="hupso_create_code()" onchange="hupso_create_code()" <?php echo $hupso_toolbar_size_medium_checked; ?> /> <?php _e( 'Medium', 'share_buttons_hupso');?> <br/>	
		<input type="radio" name="select_toolbar_size" value="small" onclick="hupso_create_code()" onchange="hupso_create_code()" <?php echo $hupso_toolbar_size_small_checked; ?> /> <?php _e( 'Small', 'share_buttons_hupso');?> <br/>
		<hr style="height:1px; width:500px;"/>	
		</td>
	</tr>		
	</table>
	</div>	
	
	
	<div id="share_image" style="padding-top:10px;">
	<table border="0">
		<tr>
		<td style="width:100px;"><?php _e('Share image', 'share_buttons_hupso'); ?></td>
		<td style="width:500px">
			<?php
			
				/* hupso_share_image */
				$checked = ' checked="checked" ';
				$hupso_share_image = get_option( 'hupso_share_image', 'normal' );
				$hupso_share_image_show_checked = '';
				$hupso_share_image_hide_checked = '';
				$hupso_share_image_lang_checked = '';
				switch ( $hupso_share_image ) {
					case '':
					case 'show':	$hupso_share_image_show_checked = $checked; break;
					case 'hide':	$hupso_share_image_hide_checked = $checked;	break;
					case 'lang':	$hupso_share_image_lang_checked = $checked;	break;	
				}
				
				$hupso_share_image_lang = get_option ( 'hupso_share_image_lang', '');
			
			?>
		<input type="radio" name="hupso_share_image" onclick="hupso_create_code()" onchange="hupso_create_code()" value="show" <?php echo $hupso_share_image_show_checked; ?>/> <?php _e('Show in language', 'share_buttons_hupso');?>:  
			<select id="share_image_lang" name="share_image_lang" onclick="hupso_create_code()" onchange="hupso_create_code()">
			  <option value="en" <?php if ( ($hupso_share_image_lang == 'en') || ($hupso_share_image_lang == '') ) echo ' selected ';?>>English</option>		
			  <option value="fr" <?php if ($hupso_share_image_lang == 'fr') echo ' selected ';?>>French</option>
			  <option value="de" <?php if ($hupso_share_image_lang == 'de') echo ' selected ';?>>German</option>
			  <option value="it" <?php if ($hupso_share_image_lang == 'it') echo ' selected ';?>>Italian</option>	  		  		  
			  <option value="pt" <?php if ($hupso_share_image_lang == 'pt') echo ' selected ';?>>Portuguese</option>
			  <option value="es" <?php if ($hupso_share_image_lang == 'es') echo ' selected ';?>>Spanish</option>
			  <option value="id" <?php if ($hupso_share_image_lang == 'id') echo ' selected ';?>>Indonesian</option>
			  <option value="da" <?php if ($hupso_share_image_lang == 'da') echo ' selected ';?>>Danish</option>	
			  <option value="nl" <?php if ($hupso_share_image_lang == 'nl') echo ' selected ';?>>Dutch</option>	
			  <option value="sv" <?php if ($hupso_share_image_lang == 'sv') echo ' selected ';?>>Swedish</option>	
			  <option value="no" <?php if ($hupso_share_image_lang == 'no') echo ' selected ';?>>Norwegian</option>	
			  <option value="sr" <?php if ($hupso_share_image_lang == 'sr') echo ' selected ';?>>Serbian</option>
			  <option value="hr" <?php if ($hupso_share_image_lang == 'hr') echo ' selected ';?>>Croatian</option>
			  <option value="et" <?php if ($hupso_share_image_lang == 'et') echo ' selected ';?>>Estonian</option>
			  <option value="ro" <?php if ($hupso_share_image_lang == 'ro') echo ' selected ';?>>Romanian</option>
			  <option value="ga" <?php if ($hupso_share_image_lang == 'ga') echo ' selected ';?>>Irish</option>
			  <option value="af" <?php if ($hupso_share_image_lang == 'af') echo ' selected ';?>>Afrikaans</option>
			  <option value="sl" <?php if ($hupso_share_image_lang == 'sl') echo ' selected ';?>>Slovenian</option>
			  <option value="pl" <?php if ($hupso_share_image_lang == 'pl') echo ' selected ';?>>Polish</option>
			  <option value="bs" <?php if ($hupso_share_image_lang == 'bs') echo ' selected ';?>>Bosnian</option>
			  <option value="ms" <?php if ($hupso_share_image_lang == 'ms') echo ' selected ';?>>Malay</option>
			  <option value="zh" <?php if ($hupso_share_image_lang == 'zh') echo ' selected ';?>>Chinese</option>	
			  <option value="cs" <?php if ($hupso_share_image_lang == 'cs') echo ' selected ';?>>Czech</option>			  		  
			</select><br/>
		<input type="radio" name="hupso_share_image" onclick="hupso_create_code()" onchange="hupso_create_code()" value="hide" <?php echo $hupso_share_image_hide_checked; ?>/> <?php _e('Hide', 'share_buttons_hupso'); ?><br/>
		<hr style="height:1px; width:500px;"/>			
		</td>	
		</tr>	
		</table>
	</div>	
	
	
	<div id="services">
	<table border="0">
	<tr>
		<td style="width:100px;"><?php _e('Social networks', 'share_buttons_hupso'); ?></td>
		<td><?php hupso_settings_print_services(); ?></td>
	</tr>
	</table>
	</div>
		<?php
			$checked = ' checked="checked" ';
			$twitter_tweet_checked = '';
			$facebook_like_checked = '';
			$facebook_send_checked = '';
			$google_plus_one_checked = '';
			$linkedin_share_checked = '';
			
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
	<tr>
	<td style="padding-top:70px;">&nbsp;</td>
	<td><? _e('Show counters in language', 'share_buttons_hupso');?>: 
	<select id="hupso_counters_lang" name="hupso_counters_lang" onchange="hupso_create_code()" onclick="hupso_create_code()">
	<?php hupso_counters_lang_list(); ?>
	</select><br/><br/>
	(<?php _e('Language changes will not show in preview', 'share_buttons_hupso');?>)
	</td><td><?php _e('Select which language to use for Counters (Tweet, Facebook Like, Facebook Share...)', 'share_buttons_hupso');?>.<?php _e('Some social networks support more languages than others, so some buttons might get translated, while some might stay in English', 'share_buttons_hupso');?>.</td>
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
			$hupso_labels_checked = '';
			$hupso_icons_checked = '';
			switch ( $menu_type ) {
				case 'labels': 	$hupso_labels_checked = $checked; break;
				case 'icons' :  $hupso_icons_checked = $checked; break;
				default: $hupso_labels_checked = $checked;
			}			
		
		?>
		<td><hr style="height:1px; width:500px;"/><input type="radio" name="menu_type" value="labels" onclick="hupso_create_code()" <?php echo $hupso_labels_checked; ?> /> <?php _e('Show icons and service names', 'share_buttons_hupso'); ?><br/>
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
			$hupso_below_checked = '';
			$hupso_above_checked = '';
			$hupso_both_checked = '';
			switch ( $button_position ) {
				case 'below': 	$hupso_below_checked = $checked; break;
				case 'above' :  $hupso_above_checked = $checked; break;
				case 'both':	$hupso_both_checked = $checked; break;
				default: $hupso_below_checked = $checked;
			}			
		?>
		<td><hr style="height:1px; width:500px;" align="left"/>
		<input type="radio" name="hupso_button_position" value="above" <?php echo $hupso_above_checked; ?> /> <?php _e('Above the post', 'share_buttons_hupso'); ?><br/>
		<input type="radio" name="hupso_button_position" value="below" <?php echo $hupso_below_checked; ?> /> <?php _e('Below the post', 'share_buttons_hupso'); ?><br/>
		<input type="radio" name="hupso_button_position" value="both" <?php echo $hupso_both_checked; ?> /> <?php _e('Above and below the post', 'share_buttons_hupso'); ?><br/></td>
	</tr>	
	<tr>
		<td style="width:100px;"><?php _e('Show buttons on', 'share_buttons_hupso'); ?></td>
		<td><hr style="height:1px; width:500px;" align="left"/>
			<?php
				$checked = ' checked="checked" ';
				$hupso_show_posts_checked = '';
				$hupso_show_pages_checked = '';
				$hupso_show_frontpage_checked = '';
				$hupso_show_category_checked = '';
				
				/* posts */
				$hupso_show_posts = get_option( 'hupso_show_posts', '1' );
				if ( $hupso_show_posts == 1 )
					$hupso_show_posts_checked = $checked;	
				else
					$hupso_show_posts_checked = '';		
					
				/* pages */	
				$hupso_show_pages = get_option( 'hupso_show_pages', '1' );
				if ( $hupso_show_pages == 1 )
					$hupso_show_pages_checked = $checked;	
				else
					$hupso_show_pages_checked = '';									
				
				/* frontpage */
				$hupso_show_frontpage = get_option( 'hupso_show_frontpage', '1' );
				if ( $hupso_show_frontpage == 1 )
					$hupso_show_frontpage_checked = $checked;	
				else
					$hupso_show_frontpage_checked = '';	
					
				/* categories */	
				$hupso_show_category = get_option( 'hupso_show_category', '1' );
				if ( $hupso_show_category == 1 )
					$hupso_show_category_checked = $checked;	
				else
					$hupso_show_category_checked = '';						
			?>
			<input type="checkbox" name="hupso_show_posts" value="1" <?php echo $hupso_show_posts_checked; ?> /> <?php _e('Posts', 'share_buttons_hupso'); ?><br/>
			<input type="checkbox" name="hupso_show_pages" value="1" <?php echo $hupso_show_pages_checked; ?> /> <?php _e('Pages', 'share_buttons_hupso'); ?><br/>
			<input type="checkbox" name="hupso_show_frontpage" value="1" <?php echo $hupso_show_frontpage_checked; ?> /> <?php _e('Front page', 'share_buttons_hupso'); ?><br/>
			<input type="checkbox" name="hupso_show_category" value="1" <?php echo $hupso_show_category_checked; ?> /> <?php _e('Categories (categories, tags, dates, authors)', 'share_buttons_hupso'); ?><br/>		
		</td>
	</tr>	
	<tr>
		<td style="width:100px;"><?php _e('Hide buttons for specific categories', 'share_buttons_hupso'); ?></td>
		<td><hr style="height:1px; width:500px;" align="left"/>
			<?php
				/* hidden categories */
				$hupso_hide_categories = get_option( 'hupso_hide_categories', array() );
			?>
			<select multiple size="8" name="hupso_hide_categories[]"> 
			 <?php 
			  $categories = get_categories(); 
			  foreach ($categories as $category) {
				$option = '<option value="'.$category->category_nicename.'"';
				if ( in_array($category->category_nicename, $hupso_hide_categories ) ) {
					$option .= ' selected ';
				}			
				$option .= '>';
				$option .= $category->cat_name;
				$option .= ' ('.$category->category_count.')';
				$option .= '</option>';
				echo $option;
			  }
			 ?> 
			 <option value="hupso-option-always_show">--- <?php _e('Always show', 'share_buttons_hupso');?> ---</option>
			</select>
			<p><?php _e('Select categories where you want to hide share buttons.', 'share_buttons_hupso'); ?><br>
			   <?php _e('To select multiple categories, you need to hold down the Control Key for each selected category after the first one.', 'share_buttons_hupso');?><br />
			   <?php _e('Leave all options unselected or select just the last option to show buttons inside every category.', 'share_buttons_hupso');?>
			</p>
		</td>
	</tr>			
	<tr>
		<td style="width:100px;"><?php _e('Get share text from', 'share_buttons_hupso'); ?></td>
		<td><hr style="height:1px; width:500px;" align="left"/>
			<?php
				$checked = ' checked="checked" ';
				$hupso_title_text_page_checked = '';
				$hupso_title_text_post_checked = '';
				
				/* posts */
				$hupso_title_text = get_option( 'hupso_title_text', 'post' );
				if ( $hupso_title_text == 'page' )
					$hupso_title_text_page_checked = $checked;	
				else
					$hupso_title_text_post_checked = $checked;			
			?>
			<input type="radio" name="hupso_title_text" value="post" <?php echo $hupso_title_text_post_checked; ?> /> <?php _e('Title of post in Wordpress', 'share_buttons_hupso'); ?><br/>	
			<input type="radio" name="hupso_title_text" value="page" <?php echo $hupso_title_text_page_checked; ?> /> <?php _e('Title of current web page', 'share_buttons_hupso'); ?>
		</td>
	</tr>	
	
	<tr>
		<td style="width:100px;"><?php _e('Twitter via', 'share_buttons_hupso'); ?></td>
		<td><hr style="height:1px; width:500px;" align="left"/>
			<?php
				
				/* Twitter via */
				$hupso_twitter_via = get_option( 'hupso_twitter_via', '' );
			
			?>
			@<input type="text" name="hupso_twitter_via" onclick="hupso_create_code()" onchange="hupso_create_code()" onmouseout="hupso_create_code()" value="<?php echo $hupso_twitter_via; ?>" /> <span style="padding-left:30px;"><?php _e('Add "via @yourprofile" to tweets', 'share_buttons_hupso');?>.</span><br/>
		</td>
	</tr>
	
	<tr>
		<td style="width:100px;"><?php _e('CSS style', 'share_buttons_hupso'); ?></td>
		<td><hr style="height:1px; width:400px;" align="left"/>
			<?php
				
				/* CSS Style */
				$hupso_css_style = get_option( 'hupso_css_style', 'padding-bottom:20px; padding-top:10px;');
				
			?>
			<input type="text" name="hupso_css_style" style="width:400px;" value="<?php echo $hupso_css_style;?>" /><br/><span><?php _e('Use CSS to style share buttons. For example: you can increase padding to have more free space above or below the buttons', 'share_buttons_hupso');?>.</span><br/>
		</td>
	</tr>	
	
	<tr>
		<td style="width:100px;"><?php _e('Widget Text', 'share_buttons_hupso'); ?></td>
		<td><hr style="height:1px; width:400px;" align="left"/>
			<?php
				
				/* Widget Text */
				$checked = ' checked="checked" ';
				$hupso_widget_text = get_option( 'hupso_widget_text', '1');
				if ( $hupso_widget_text == '1' )
					$hupso_widget_text_checked = $checked;	
				else
					$hupso_widget_text_checked = '';							
				
			?>
			<input type="checkbox" name="hupso_widget_text" value="1" <?php echo $hupso_widget_text_checked; ?> /> <?php _e('Use shortcodes in text widgets', 'share_buttons_hupso'); ?><br/><?php _e('If this is checked, you can use [hupso] shortcode inside text widgets and it will be replaced by share buttons', 'share_buttons_hupso'); ?>
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

	if ( @$_POST[ 'button_type' ] != '' )
		$post = true;
	else
		$post = false;	

	/* save button type */
	if ( $post ) {
		$hupso_button_type = @$_POST[ 'button_type' ];
		update_option( 'hupso_button_type', $hupso_button_type );		
	} else {
		$hupso_button_type = get_option ( 'hupso_button_type', 'share_toolbar');
	}

	/* save button size */
	if ( $post ) {
		$hupso_button_size = @$_POST[ 'size' ];
		update_option( 'hupso_button_size', $hupso_button_size );		
	} else {
		$hupso_button_size = get_option ( 'hupso_button_size', 'button100x23');
	}
	$b_size = str_replace( 'button', '', $hupso_button_size);
	list($width, $height) = split('x', $b_size);	
	
	/* save toolbar size */
	if ( $post ) {
		$hupso_toolbar_size = @$_POST[ 'select_toolbar_size' ];
		update_option( 'hupso_toolbar_size', $hupso_toolbar_size );		
	} else {
		$hupso_button_size = get_option ( 'hupso_toolbar_size', 'medium');
	}	
			
	/* save share_image */
	if ( $post ) {
		$hupso_share_image = @$_POST[ 'hupso_share_image' ];
		update_option( 'hupso_share_image', $hupso_share_image );		
	} else {
		$hupso_share_image = get_option ( 'hupso_share_image', 'normal');
	}				
	
	/* save share_image_lang */
	if ( $post ) {
		$hupso_share_image_lang = @$_POST[ 'share_image_lang' ];
		update_option( 'hupso_share_image_lang', $hupso_share_image_lang );		
	} else {
		$hupso_share_image_lang = get_option ( 'hupso_share_image_lang', '');
	}	
			
	/* save services */	
	$hupso_vars = 'var hupso_services=new Array(';
	foreach ( $hupso_all_services as $service_text ) {
		$service_name = strtolower( $service_text );
		$service_name = str_replace( ' ', '', $service_name );
		if ( $post ) {
			$value = @$_POST[ $service_name ];
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
	
	/* save hupso_counters_lang*/
	if ( $post ) {
		$hupso_counters_lang = @$_POST[ 'hupso_counters_lang' ];	
		update_option( 'hupso_counters_lang', $hupso_counters_lang );		
	}	
	
	/* save menu type */
	if ( $post ) {
		$hupso_menu_type = @$_POST[ 'menu_type' ];	
		update_option( 'hupso_menu_type', $hupso_menu_type );		
	}
	else {	
		$hupso_menu_type = get_option ( 'hupso_menu_type', 'labels' );	
	}
	$hupso_vars .= 'var hupso_icon_type = "'.$hupso_menu_type.'";';		

	/* save button position */
	if ( $post ) {
		$hupso_button_position = @$_POST[ 'hupso_button_position' ];	
		update_option( 'hupso_button_position', $hupso_button_position );
	}
	else {
		$hupso_button_position = get_option( 'hupso_button_position', 'below' );		
	}	
	
	/* save display options */
	if ( $post ) {
		$hupso_show_posts = @$_POST[ 'hupso_show_posts' ];	
		update_option( 'hupso_show_posts', $hupso_show_posts );
		
		$hupso_show_pages = @$_POST[ 'hupso_show_pages' ];	
		update_option( 'hupso_show_pages', $hupso_show_pages );			
	
		$hupso_show_frontpage = @$_POST[ 'hupso_show_frontpage' ];	
		update_option( 'hupso_show_frontpage', $hupso_show_frontpage );
		
		$hupso_show_category = @$_POST[ 'hupso_show_category' ];	
		update_option( 'hupso_show_category', $hupso_show_category );		
	}
	
	/* save options for counters */
	if ( $post ) {
		$twitter_tweet = @$_POST[ 'twitter_tweet' ];	
		update_option( 'hupso_twitter_tweet', $twitter_tweet );	
	
		$facebook_like = @$_POST[ 'facebook_like' ];	
		update_option( 'hupso_facebook_like', $facebook_like );	
		
		$facebook_send = @$_POST[ 'facebook_send' ];	
		update_option( 'hupso_facebook_send', $facebook_send );	
	
		$google_plus_one = @$_POST[ 'google_plus_one' ];	
		update_option( 'hupso_google_plus_one', $google_plus_one );	
		
		$linkedin_share = @$_POST[ 'linkedin_share' ];	
		update_option( 'hupso_linkedin_share', $linkedin_share );	
	}
	
	/* Get title for sharing from */
	if ( $post ) {
		$hupso_title_text = @$_POST[ 'hupso_title_text' ];	
		update_option( 'hupso_title_text', $hupso_title_text );		
	}
	
	/* Save twitter_via */
	if ( $post ) {
		$hupso_twitter_via = @$_POST[ 'hupso_twitter_via' ];	
		update_option( 'hupso_twitter_via', $hupso_twitter_via );		
	}	
	
	/* Save CSS style */
	if ( $post ) {
		$hupso_css_style = @$_POST[ 'hupso_css_style' ];	
		update_option( 'hupso_css_style', $hupso_css_style );		
	}		
	
	/* Save hupso_widget_text */
	if ( $post ) {
		$hupso_widget_text = @$_POST[ 'hupso_widget_text' ];	
		update_option( 'hupso_widget_text', $hupso_widget_text );		
	}	
	
	/* save hupso_hide_categories */
	if ( $post ) {
		$hupso_hide_categories = @$_POST['hupso_hide_categories'];
		update_option( 'hupso_hide_categories', $hupso_hide_categories );	
	}
	
	/* save button code */
	if ( $post ) {
		$code = stripslashes(@$_POST[ 'code' ]);
		update_option( 'hupso_share_buttons_code', $code );
	}
	
}


function hupso_the_widget( $content ) {
	global $hupso_state;
	$hupso_state = 'widget';
	return hupso_the_content ( $content );
}

function hupso_the_content( $content ) {

	global $hupso_plugin_url, $wp_version, $hupso_dev, $hupso_state;
	
	/* Do now show share buttons when [hupso_hide] is used */
	if ( stripos($content, '[hupso_hide]') !== false ) {
		$content = str_ireplace('[hupso_hide]', '', $content);
		$content = str_ireplace('[hupso]', '', $content);
		return $content;
	}

	/* Do not show share buttons in feeds */
	if ( is_feed() ) {
		$content = str_ireplace('[hupso_hide]', '', $content);
		$content = str_ireplace('[hupso]', '', $content);		
		return $content;
	}
	
	/* Do not show share buttons on password protected pages, but show it inside widget */
	$pass = $GLOBALS['post']->post_password;
	if ( ($hupso_state == 'normal') && ( ($pass != '') || (post_password_required()) ) ) {
		return $content;
	}
	
	$hupso_show_posts = get_option( 'hupso_show_posts' , '1' );
	if ( is_single() && $hupso_show_posts != 1 ) {
		$content = str_ireplace('[hupso_hide]', '', $content);
		$content = str_ireplace('[hupso]', '', $content);
		return $content;
	}
		
	$hupso_show_pages = get_option( 'hupso_show_pages' , '1' );	
	if ( is_page() && $hupso_show_pages != 1 ) {
		$content = str_ireplace('[hupso_hide]', '', $content);
		$content = str_ireplace('[hupso]', '', $content);	
		return $content;
	}	

	$hupso_show_frontpage = get_option( 'hupso_show_frontpage' , '1' );
	$hupso_show_category = get_option( 'hupso_show_category' , '1' );	
	
	/* Do not show share buttons if option is disabled */
	if ( is_home() && $hupso_show_frontpage != 1 ) {
		$content = str_ireplace('[hupso_hide]', '', $content);
		$content = str_ireplace('[hupso]', '', $content);		
		return $content;
	}
	/* Do not show share buttons if option is disabled */
	if ( is_archive() && $hupso_show_category != 1 ) {
		$content = str_ireplace('[hupso_hide]', '', $content);
		$content = str_ireplace('[hupso]', '', $content);		
		return $content;
	}	
	
	/* Check if we are inside category where buttons are hidden */
	$cats = get_the_category();
	$current_category = @$cats[0]->slug;	
	$hupso_hide_categories = get_option( 'hupso_hide_categories' , array() );
	if ( $hupso_hide_categories == '' ) {
		$hupso_hide_categories = array();
	}
	if ( @in_array($current_category, $hupso_hide_categories) ) {
		$content = str_ireplace('[hupso_hide]', '', $content);
		$content = str_ireplace('[hupso]', '', $content);		
		return $content;
	}	

	$hupso_title_text = get_option( 'hupso_title_text' , 'post' );
	$hupso_twitter_via = get_option( 'hupso_twitter_via', '' );
	$hupso_counters_lang = get_option( 'hupso_counters_lang', 'en_US' );
	$post_url = get_permalink($GLOBALS['post']->ID);
	$post_title = @$GLOBALS['post']->post_title;
	
	
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


	/* hupso_counters_lang */
	$code .= 'var hupso_counters_lang="' . $hupso_counters_lang . '";';

	/* Twitter via @ */
	if ( $hupso_twitter_via != '') {
		$code .= 'var hupso_twitter_via="' . $hupso_twitter_via . '";';
	}
	
	if ( ( is_home() && $hupso_show_frontpage == 1 ) || ( is_archive() && $hupso_show_category == 1 ) )  {
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
	
	if ( $hupso_title_text == 'post' ) {
		$ptitle = strip_tags($post_title);
		switch ( $button_type ) {
			case 'share_button': 
				$code .= 'var hupso_title="' . str_replace('"', '&quot;', $ptitle) . '";';
				break;
			case 'share_toolbar':
				$code .= 'var hupso_title_t="' . str_replace('"', '&quot;', $ptitle) . '";';
				break;
			case 'counters':
				$code .= 'var hupso_title_c="' . str_replace('"', '&quot;', $ptitle) . '";';
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
		case 'counters':
			$js_file = 'counters.js';
			break;			
	}
	
	$static_server = 'http://static.hupso.com/share' . $hupso_dev . '/js/' . $js_file;
	$code .= '<script type="text/javascript" src="' . $static_server . '"></script><!-- Hupso Share Buttons -->';	
   
    $position = get_option( 'hupso_button_position', 'below' );
	
	$hupso_css_style = get_option( 'hupso_css_style', 'padding-bottom:20px; padding-top:10px;');
	if ($hupso_css_style != '') {
		$hupso_css_out = ' style="' . $hupso_css_style . '" ';
	}
	else {
		$hupso_css_out = '';
	}
	
	if ( stripos($content, '[hupso]') !== false) {
		$new_content = str_ireplace('[hupso]', '<div' . $hupso_css_out. '>' . $code . '</div>', $content);
	}
	else {
		switch ( $position ) {
			case 'below':
				$new_content = $content . '<div' . $hupso_css_out. '>' . $code . '</div>'; 
				break;
			case 'above':
				$new_content = '<div' . $hupso_css_out. '>' . $code . '</div>' . $content;
				break;
			case 'both':
				if ( $hupso_state == 'normal' ) {
					/* article */
					$new_content = '<div' . $hupso_css_out. '>' . $code . '</div>' . $content . '<div' . $hupso_css_out. '>' . $code . '</div>';
				}
				else {
					/* widget, shortcodes */
					$new_content = '<div' . $hupso_css_out. '>' . $code . '</div>' . $content;
				}
				break;
			default:
				$new_content = $content . '<div' . $hupso_css_out. '>' . $code . '</div>';			
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


function hupso_counters_lang_list() {
	$languages = array (
		'af_ZA' => 'Afrikaans',
		'ar_AR' => 'Arabic',
		'az_AZ' => 'Azerbaijani',
		'be_BY' => 'Belarusian',
		'bg_BG' => 'Bulgarian',
		'bn_IN' => 'Bengali',
		'bs_BA' => 'Bosnian',
		'ca_ES' => 'Catalan',
		'cs_CZ' => 'Czech',
		'cy_GB' => 'Welsh',
		'da_DK' => 'Danish',
	  	'de_DE' => 'German',		
	  	'el_GR' => 'Greek',
	  	'en_GB' => 'English (UK)',
	  	'eo_EO' => 'Esperanto',	
	  	'es_ES' => 'Spanish (Spain)',	
	  	'es_LA' => 'Spanish',	
	  	'et_EE' => 'Estonian',		
	  	'eu_ES' => 'Basque',
	  	'fa_IR' => 'Persian',
	  	'fi_FI' => 'Finnish',
	  	'fo_FO' => 'Faroese',
	  	'fr_CA' => 'French (Canada)',
	  	'fr_FR' => 'French (France)',
	  	'fy_NL' => 'Frisian',
	  	'ga_IE' => 'Irish',
	  	'gl_ES' => 'Galician',
	  	'he_IL' => 'Hebrew',
	  	'hi_IN' => 'Hindi',
	  	'hr_HR' => 'Croatian',
	  	'hu_HU' => 'Hungarian',
	  	'hy_AM' => 'Armenian',
	  	'id_ID' => 'Indonesian',
	  	'is_IS' => 'Icelandic',
	  	'it_IT' => 'Italian',
	  	'ja_JP' => 'Japanese',
	  	'ka_GE' => 'Georgian',
	  	'km_KH' => 'Khmer',
	  	'ko_KR' => 'Korean',
	  	'ku_TR' => 'Kurdish',
	  	'la_VA' => 'Latin',
	  	'lt_LT' => 'Lithuanian',
	  	'lv_LV' => 'Latvian',
	  	'mk_MK' => 'Macedonian',
	  	'ml_IN' => 'Malayalam',
	  	'ms_MY' => 'Malay',
	  	'nb_NO' => 'Norwegian (bokmal)',
	  	'ne_NP' => 'Nepali',
	  	'nl_NL' => 'Dutch',
	  	'nn_NO' => 'Norwegian (nynorsk)',
	  	'pa_IN' => 'Punjabi',
	  	'pl_PL' => 'Polish',
	  	'ps_AF' => 'Pashto',
	  	'pt_BR' => 'Portuguese (Brazil)',
	  	'pt_PT' => 'Portuguese (Portugal)',
	  	'ro_RO' => 'Romanian',
	  	'ru_RU' => 'Russian',
	  	'sk_SK' => 'Slovak',
	  	'sl_SI' => 'Slovenian',
	  	'sq_AL' => 'Albanian',
	  	'sr_RS' => 'Serbian',
	  	'sv_SE' => 'Swedish',
	  	'sw_KE' => 'Swahili',
	  	'ta_IN' => 'Tamil',
	  	'te_IN' => 'Telugu',
	  	'th_TH' => 'Thai',
	  	'tl_PH' => 'Filipino',
	  	'tr_TR' => 'Turkish',
	  	'uk_UA' => 'Ukrainian',
	  	'vi_VN' => 'Vietnamese',
	  	'zh_CN' => 'Chinese - Simplified (China)',
	  	'zh_HK' => 'Chinese - Traditional (Hong Kong)',
	  	'zh_TW' => 'Chinese - Traditional (Taiwan)',
	);
		
	asort($languages);
	echo '<option value="en_US">English (US)</option>';		
	$hupso_counters_lang = get_option( 'hupso_counters_lang', 'en_US' );
	if ($hupso_counters_lang == '') {
		$hupso_counters_lang = 'en_US';
	}
	
	foreach ($languages as $lang_code => $lang_name ) {
		if ($lang_code == $hupso_counters_lang)
			$sel_lang = ' selected ';
		else
			$sel_lang = '';	
		echo '<option value="' . $lang_code . '"'. $sel_lang .'>' . $lang_name . '</option>';
	}
  		  		  		  			
}	




?>