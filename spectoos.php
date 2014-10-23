<?php
/**
 * Plugin Name: Spectoos Testimonials
 * Plugin URI: http://www.spectoos.com
 * Description: The ultimate plugin for creating socially-proofed testimonials.
 * Version: 1.1
 * Author: Spectoos
 * Author URI: http://www.spectoos.com
 * License: GPL2
 */

function spectoos_faceboard($content) {
  $options = get_option('plugin_options');
	$apiKey = $options['faceboard_api_key'];

	if ($apiKey != '') {
		return "<script src='https://app.spectoos.com/api/v1/spectoos.js?apikey=".$apiKey."'></script><script language='JavaScript' type='text/javascript'>faceboard = new spectoos.Faceboard(); faceboard.Render(); </script>";
  }
    
  return "Please first fill the apiKey in the spectoos settings page.";
}

add_shortcode('faceboard', 'spectoos_faceboard' );


if ( is_admin() ){
	// add the admin options page
	add_action('admin_menu', 'spectoos_plugin_admin_add_page');
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'spectoos_plugin_settings' );
}

function spectoos_plugin_settings($links) {
   $mylinks = array('<a href="'. get_admin_url(null, 'options-general.php?page=spectoos') .'">Settings</a>');
   return array_merge( $mylinks, $links);
}

function spectoos_plugin_admin_add_page() {
	 add_options_page('Spectoos', 'Spectoos', 'manage_options', 'spectoos', 'spectoos_plugin_options_page');
}

add_action('admin_init', 'spectoos_plugin_admin_init');
function spectoos_plugin_admin_init(){
	register_setting( 'plugin_options', 'plugin_options', 'spectoos_plugin_options_validate' );
	add_settings_section('plugin_main', 'Spectoos Settings', 'spectoos_plugin_section_text', 'plugin');
	add_settings_field('plugin_faceboard_api_key', 'Paste your API key here:', 'spectoos_plugin_setting_string', 'plugin', 'plugin_main');
}

function spectoos_plugin_section_text() {
	echo '<img style="display:block; margin-top:30px; margin-bottom:70px;" src="' . plugins_url( 'images/strip.png', __FILE__ ) . '" > ';
	echo '<ol><li>If you are already signed up with Spectoos, please go to your <a href="https://app.spectoos.com/partner/index?wordpress=1" target="_blank">Spectoos dashboard</a> to get your API key.</li>';
	echo '<li>Otherwise, please create an account with Spectoos by joining at the <a href="https://app.spectoos.com/users/sign_up" target="_blank">sign up page</a>.</li>';
	echo '<li>After filling the API Key below, please use the shortcode [faceboard] anywhere on your site to embed your Spectoos testimonials widget.</li>';
	echo '</ol>';
}

function spectoos_plugin_setting_string() {
	$options = get_option('plugin_options');
	$apiKey = isset($options[faceboard_api_key]) ? $options[faceboard_api_key] : "";
	echo "<input id='plugin_faceboard_api_key' name='plugin_options[faceboard_api_key]' size='40' type='text' value='{$apiKey}' />";
}

function spectoos_plugin_options_validate($input) {
	$options = get_option('plugin_options');
	$options['faceboard_api_key'] = trim($input['faceboard_api_key']);

	if(!preg_match('/^[a-z0-9\_]+$/i', $options['faceboard_api_key'])) {
		$options['faceboard_api_key'] = '';
	}

	return $options;
}

function spectoos_plugin_options_page() {
?>
	<div>

		<form action="options.php" method="post">
			<?php settings_fields('plugin_options'); ?>
			<?php do_settings_sections('plugin'); ?>

			<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
		</form></div>

<?php
}
?>