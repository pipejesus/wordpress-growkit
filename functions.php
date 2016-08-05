<?php

add_action( 'init', 'growkit_disable_junk' );
add_action( 'wp_enqueue_scripts', 'growkit_enqueue_css_and_js');
add_action( 'wp_footer', 'growkit_add_live_reload' );
add_action( 'admin_notices', 'growkit_show_missing_plugins' );

// add_action('wp', function(){ echo '<pre>';print_r($GLOBALS['wp_filter']); echo '</pre>';exit; } );

/**
 *
 * Adds a warning message if one of the plugins required by the theme is missing / deactivated
 * Credit: https://paulund.co.uk/theme-users-required-plugins
 *
 */

function growkit_show_missing_plugins() {

	$plugin_messages = array();
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	if(!is_plugin_active( 'cmb2/init.php' )) {
		$plugin_messages[] = 'This theme requires you to install the CMB2 plugin, <a href="http://wordpress.org/extend/plugins/cmb2/">download it from here</a>.';
	}

	if(count($plugin_messages) > 0) {
		echo '<div id="message" class="error">';
		foreach($plugin_messages as $message) {
			echo '<p><strong>'.$message.'</strong></p>';
		}
		echo '</div>';
	}

}

/**
 *
 * Removes various junk from your Wordpress output.
 * Credit: Ryan Hellyer for completely removing Emojis
 */

function growkit_disable_junk() {
	
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	add_filter( 'tiny_mce_plugins', 'growkit_disable_emoji_tinymce' );
	add_filter( 'show_admin_bar', '__return_false' );
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'rest_output_link_wp_head');

}

/**
 *
 * Removes emojis from Tinymce (backend)
 * Credit: Ryan Hellyer
 *
 */

function growkit_disable_emoji_tinymce($plugins) {

	if ( is_array( $plugins ) ) {
	  return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
	  return array();
	}

}

/**
 *
 * Enqueues theme's CSS and JS files. 
 *
 */

function growkit_enqueue_css_and_js() {

	wp_register_script( 'main_js', get_bloginfo( 'template_url' ) . '/js/main.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'main_js' );
	wp_enqueue_style( 'main_css', get_bloginfo( 'template_url' ) . '/css/main.css' );

}

/**
 * Adds a live reload capability
 * Credit: Peter Wilson, http://peterwilson.cc/
 * Adapted to only print out the script when the theme is not put in production mode
 */

function growkit_add_live_reload() {
	?>
	<script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js"></' + 'script>')</script>
	<?php
}
