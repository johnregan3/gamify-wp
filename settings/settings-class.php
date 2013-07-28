<?php

/**
 * Settings Class
 *
 * The contents of GAMWP_Settings provide information and processing for the plugin's Settings Pages.
 * @package Gamify_WP_Plugin
 */

Class GAMWP_Settings {


	/**
	* Returns Setting value
	*
	* Combines two input strings to create a value for the Options array, then
	* checks if that value exists.  If so, return the Settings value.  If not, set it to blank.
	*
	* @since 1.0
	*
	* @param string $action action name to be combined with $field
	* @param string $field field name to be combined with $action to produce output
	* @return string Setting value
	*/

	public static function input_setup( $action, $field ){

		$options = get_option('gamwp_settings');
		$settings_title = $action . '_' . $field;
		$value = (isset( $options[$settings_title] ) ? $options[$settings_title] : '');

		return $value;

	}

}