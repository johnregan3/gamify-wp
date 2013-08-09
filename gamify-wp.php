<?php

/**
 * Plugin Name: Gamify WP
 * Plugin URI: http://johnregan3.github.io/gamify-wp
 * Description: Reward your Users for interacting with your WordPress website.
 * Author: John Regan
 * Author URI: http://johnregan3.me
 * Version: 1.0
 * Copyright 2013  John Regan  (email : johnregan3@outlook.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @package Gamify WP
 * @author John Regan
 * @version 1.0
 */


//Actions Admin Page
include_once( plugin_dir_path(__FILE__) . 'settings/gamify-actions.php' );

//Rewards Admin Page
include_once( plugin_dir_path(__FILE__) . 'settings/gamify-rewards.php' );

//Points Log Admin Page
include_once( plugin_dir_path(__FILE__) . 'settings/gamify-log.php' );

//Generate Hooks & Save Activity Class
include_once( plugin_dir_path(__FILE__) . 'process-class.php' );

//Shortcode Class
include_once( plugin_dir_path(__FILE__) . 'shortcode/shortcode-class.php' );

//User Stats Shortcode
include_once( plugin_dir_path(__FILE__) . 'shortcode/user-stats.php' );


/**
 * Register text domain
 *
 * @since 1.0
 */

function gamwp_textdomain() {
	load_plugin_textdomain('gamwp');
}

add_action('init', 'gamwp_textdomain');


/**
 * Plugin activation actions
 *
 * @since 1.0
 */

function gamwp_activation() {

	//Insert User Stats page
	wp_insert_post(
		array(
			'post_title'     => __( 'User Stats', 'gamwp' ),
			'post_content'   => '[user_stats]',
			'post_status'    => 'publish',
			'post_author'    => 1,
			'post_type'      => 'page',
			'comment_status' => 'closed'
		)
	);

}

register_activation_hook( __FILE__, 'gamwp_activation' );
