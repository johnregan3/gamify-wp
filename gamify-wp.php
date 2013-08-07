<?php

/**
 * Plugin Name: Gamify WP
 * Plugin URI: http://johnregan3.github.io/gamify-wp-plugin
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

//Points Log Admin Page
include_once( plugin_dir_path(__FILE__) . 'settings/gamify-log.php' );

//Save Activity Class
include_once( plugin_dir_path(__FILE__) . 'processors/process-class.php' );

//Action Hook Generator/Processor
include_once( plugin_dir_path(__FILE__) . 'processors/actions.php' );

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
