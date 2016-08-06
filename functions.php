<?php

add_action( 'init', 'growkit_disable_junk' );
add_action( 'wp_enqueue_scripts', 'growkit_enqueue_css_and_js');
add_action( 'wp_footer', 'growkit_add_live_reload' );
add_action( 'admin_notices', 'growkit_show_missing_plugins' );
add_action( 'after_setup_theme', 'growkit_setup' );
add_action( 'widgets_init', 'growkit_widgets_init' );
// add_action('wp', function(){ echo '<pre>';print_r($GLOBALS['wp_filter']); echo '</pre>';exit; } );

/**
 *
 * Sets up basic theme functionality
 *
 */

function growkit_setup() {

	load_theme_textdomain( 'wordpress-growkit', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'wordpress-growkit' ),
	));
	
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	));
	
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'status',
		'audio',
		'chat',
	) );
	
	include_once( 'optionspage/growkit-options-page.php' );

}

function growkit_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'wordpress-growkit' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'wordpress-growkit' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}

/**
 *
 * Adds a warning message if one of the plugins required by the theme is missing / deactivated
 * Credit: https://paulund.co.uk/theme-users-required-plugins
 *
 */

function growkit_show_missing_plugins() {

	$plugin_messages = array();
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$site_url = get_site_url();

	if(!is_plugin_active( 'cmb2/init.php' )) {
		$plugin_messages[] = __( 'Growkit needs the CMB2 plugin.', 'wordpress-growkit' ) . ' <a href="' . $site_url . '/wp-admin/plugin-install.php?tab=plugin-information&plugin=cmb2">' . __( 'Install now', 'wordpress-growkit' ) . '</a>.';
	}
	if(!is_plugin_active( 'regenerate-thumbnails/regenerate-thumbnails.php' )) {
		$plugin_messages[] = __( 'Growkit needs the Regenerate Thumbnails plugin.', 'wordpress-growkit' ) . ' <a href="' . $site_url . '/wp-admin/plugin-install.php?tab=plugin-information&plugin=regenerate-thumbnails">' . __( 'Install now', 'wordpress-growkit' ) . '</a>.';
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
	// wp_enqueue_style( 'growgrid_css', get_bloginfo( 'template_url' ) . '/css/growgrid.css' );
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
