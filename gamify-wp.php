<?php

/*
	Plugin Name: Gamify WP
	Plugin URI: http://johnregan3.github.io/gamify-wp-plugin
	Description: Reward your Users for completing actions on your site (e.g., leaving comments or clicking links).
	Author: John Regan
	Author URI: http://johnregan3.me
	Version: 1.0
 */

include_once( plugin_dir_path(__FILE__) . 'settings/gamify-actions.php' );		//Actions Settings Page
include_once( plugin_dir_path(__FILE__) . 'settings/gamify-log.php' );			//Points Log Settings Page

include_once( plugin_dir_path(__FILE__) . 'process-class.php' );				//Saves Action/Reward Activity
include_once( plugin_dir_path(__FILE__) . 'actions.php' );						//Action Hook Generator

include_once( plugin_dir_path(__FILE__) . 'shortcode/shortcode-class.php' );	//Shortcode Class
include_once( plugin_dir_path(__FILE__) . 'shortcode/user-stats.php' );			//User Stats Shortcode


/*
 *
 * Register text domain
 *
 */

function gamwp_textdomain() {
	load_plugin_textdomain('gamwp');
}

add_action('init', 'gamwp_textdomain');


