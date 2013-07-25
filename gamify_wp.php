<?php

/*
	Plugin Name: Gamify WP
	Plugin URI: http://johnregan3.com
	Description: Reward your Users for completing actions on your site (e.g., leaving comments or clicking links).  Supports Rewards such as badges, downloads and levels.
	Author: John Regan
	Author URI: http://johnregan3.com
	Version: 1.0
 */

include_once( plugin_dir_path(__FILE__) . 'settings/settings-class.php' ); 				//Settings Class
include_once( plugin_dir_path(__FILE__) . 'settings/gamify-wp-general.php' ); 			//Settings Page
include_once( plugin_dir_path(__FILE__) . 'settings/gamify-wp-custom-actions.php' ); 	//Settings Page

include_once( plugin_dir_path(__FILE__) . 'process-class.php' ); 				//Processor Class
include_once( plugin_dir_path(__FILE__) . 'process.php' ); 						//Action Processor

include_once( plugin_dir_path(__FILE__) . 'default-actions.php' ); 				//Default Actions
include_once( plugin_dir_path(__FILE__) . 'cpt/custom-actions.php' ); 			//Custom Actions Custom Post Type
include_once( plugin_dir_path(__FILE__) . 'custom-actions.php' ); 				//Custom Actions

include_once( plugin_dir_path(__FILE__) . 'shortcode/shortcode-class.php' ); 	//Shortcode Class
include_once( plugin_dir_path(__FILE__) . 'shortcode/link.php' );				//Link Action Shortcode
include_once( plugin_dir_path(__FILE__) . 'shortcode/user-stats.php' );			//User Stats Shortcode

include_once( plugin_dir_path(__FILE__) . 'cpt/rewards.php' ); 					//Rewards Custom Post Type

/*
 *
 * Add settings link on Plugins page
 *
 */

function gamwp_settings_link( $links ) {
	$settings_page = '<a href="' . admin_url('options-general.php?page=gamify_wp/settings.php' ) .'">Settings</a>';
	array_unshift( $links, $settings_page );
	return $links;
}

$plugin = plugin_basename(__FILE__);

add_filter( "plugin_action_links_$plugin", 'gamwp_settings_link' );



/*
 *
 * Enqueue Scripts
 *
 */

function gamwp_enqueue() {
	wp_register_script( 'gamwp_custom', plugin_dir_url( __FILE__ ) . 'js/script-custom.js', array( 'jquery' ) );
	wp_localize_script( 'gamwp_custom', 'gamwp_custom_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

	wp_register_script( 'gamwp_link', plugin_dir_url( __FILE__ ) . 'js/script-link.js', array( 'jquery' ) );
	wp_localize_script( 'gamwp_link', 'gamwp_link_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'gamwp_custom' );
	wp_enqueue_script( 'gamwp_link' );

	wp_register_style( 'gamwp_style', plugins_url( 'style/style.css', __FILE__ ), array(), '1.0', 'all' );
	wp_enqueue_style( 'gamwp_style' );

	wp_register_style('gamwp_custom_style', plugin_dir_url( __FILE__ ) . 'style/custom_style.php');
	wp_enqueue_style( 'gamwp_custom_style');
}

add_action( 'init', 'gamwp_enqueue' );


/*
 *
 * Enqueue Admin Scripts
 *
 */


function gamwp_enqueue_admin_scripts() {
	wp_register_script( 'gamwp_settings_upload', plugin_dir_url( __FILE__ ) .'js/settings-upload.js', array('jquery','media-upload','thickbox') );
	wp_register_script( 'gamwp_jeditable', plugin_dir_url( __FILE__ ) .'js/jeditable.js', array('jquery') );

	wp_enqueue_script('jquery');

	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox');

	wp_enqueue_script('media-upload');
	wp_enqueue_script('gamwp_settings_upload');

	wp_enqueue_script('gamwp_jeditable');

}

add_action('admin_enqueue_scripts', 'gamwp_enqueue_admin_scripts');



/*
 *
 * Register Activation Hook
 *
 */

function gamwp_activate() {

// Load Default Settings

}

register_activation_hook( __FILE__, 'gamwp_activate' );



/*
 *
 * Register Deactivation Hook
 *
 */


function gamwp_deactivate() {

	//Don't delete all user meta, in case plugin needs to be deactivated for maintenace issues
	//Maybe add button in Settings to Clear out Plugin before deactivation?

}

//register_deactivation_hook( __FILE__, 'gamwp_deactivate' );



/*
 *
 * Register text domain
 *
 */


function gamwp_textdomain() {
	load_plugin_textdomain('gamwp');
}

add_action('init', 'gamwp_textdomain');



/*
 *
 * Taking advice from http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_(action)
 *
 */

function gamwp_ajax() {
	die();
}

add_action( 'wp_ajax_gamwp_process', 'gamwp_ajax' );
