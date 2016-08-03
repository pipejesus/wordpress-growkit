<?php

add_action( 'init', 'growkit_disable_junk' );
add_action( 'wp_enqueue_scripts', 'growkit_enqueue_scripts');
add_action( 'wp_footer', 'growkit_add_live_reload' );

// add_action('wp', function(){ echo '<pre>';print_r($GLOBALS['wp_filter']); echo '</pre>';exit; } );

function growkit_disable_junk() {

	/*----------  Remove Emojis, credit: Ryan Hellyer  ----------*/
	
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	add_filter( 'tiny_mce_plugins', 'growkit_disable_emoji_tinymce' );

	/*----------  Remove Admin Bar  ----------*/

	add_filter( 'show_admin_bar', '__return_false' );
	
	/*----------  Remove Generator  ----------*/

	remove_action('wp_head', 'wp_generator');

	/*----------  Remove WP Manifest  ----------*/

	remove_action('wp_head', 'wlwmanifest_link');

	/*----------  Remove XMLRPC link in the head  ----------*/

	remove_action('wp_head', 'rsd_link');

	/*----------  Remove WP API link in the head  ----------*/

	remove_action('wp_head', 'rest_output_link_wp_head');
}

function growkit_disable_emoji_tinymce($plugins) {
	if ( is_array( $plugins ) ) {
	  return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
	  return array();
	}
}

function growkit_enqueue_scripts() {
	wp_register_script( 'main', get_bloginfo( 'template_url' ) . '/js/main.js', array( 'jquery' ), false, true );
	wp_enqueue_style( 'main', get_bloginfo( 'template_url' ) . '/css/main.css' );
}

/**
 *
 * Credit: Peter Wilson, http://peterwilson.cc/
 * Adapted to only print out the script when the theme is not put in production mode
 */

function growkit_add_live_reload() {
	?>
	<script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js"></' + 'script>')</script>
	<?php
}
