<?php

/*
 * Plugin Name: Gamify WP
 * Plugin URI: http://johnregan3.github.io/gamify-wp-plugin
 * Description: Reward your Users for completing actions on your site (e.g., leaving comments or clicking links).
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
 * @package SCCSS
 * @author John Regan
 * @version 1.0
 */

include_once( plugin_dir_path(__FILE__) . 'settings/gamify-actions.php' );		//Actions age
include_once( plugin_dir_path(__FILE__) . 'settings/gamify-log.php' );			//Points Log Page

include_once( plugin_dir_path(__FILE__) . 'processors/process-class.php' );				//Saves Activity
include_once( plugin_dir_path(__FILE__) . 'processors/actions.php' );						//Action Hook Generator

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


