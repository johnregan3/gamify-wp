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

//General Admin Page
include_once( plugin_dir_path(__FILE__) . 'settings/gamify-general.php' );

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

function gamify_textdomain() {
	load_plugin_textdomain('gamify');
}

add_action('init', 'gamify_textdomain');


/**
 * Plugin activation actions
 *
 * @since 1.0
 */

function gamify_activation() {

	//Insert User Stats page
	wp_insert_post(
		array(
			'post_title'     => __( 'User Stats', 'gamify' ),
			'post_content'   => '[user_stats]',
			'post_status'    => 'publish',
			'post_author'    => 1,
			'post_type'      => 'page',
			'comment_status' => 'closed'
		)
	);

}

register_activation_hook( __FILE__, 'gamify_activation' );


/**
 * Function used to uninstall Post Types on Uninstall of the plugin
 *
 * @param string $post_type  Post Type to be uninstalled
 * @since 1.0
 */
function unregister_post_type( $post_type ) {
    global $wp_post_types;
    if ( isset( $wp_post_types[ $post_type ] ) ) {
        unset( $wp_post_types[ $post_type ] );
        return true;
    }
    return false;
}

/**
 * Delete Options on Uninstall
 *
 * @since 1.0
 */
function gamify_uninstall() {
	//Delete master array of activity
	delete_option('gamify_master_array');
	//Delete Actions
	$wpdb->delete( 'table', array( 'post_type' => 'gact' ) );
	//Delete Rewards
	$wpdb->delete( 'table', array( 'post_type' => 'rew' ) );
	//remove gact post type
	unregister_post_type( 'gact' );
	//remove ew post type
	unregister_post_type( 'rew' );
	//delete all usermeta
	$users = get_users();
	foreach( $users as $user) {
		delete_post_meta( $user->ID, 'gamify_user_array' );
	}
}

register_uninstall_hook( __FILE__, 'gamify_uninstall' );
