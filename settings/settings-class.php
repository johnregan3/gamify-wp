<?php

/**
 * Settings Class
 *
 * The contents of GAMWP_Settings provide information and processing for the plugin's Settings Pages.
 * @package Gamify_WP_Plugin
 */

Class GAMWP_Settings {

	/**
	* Array of Default Actions and their hooks
	*
	* @since 1.0
	* @var array $action_array
	*/

	public static $action_array = array(
		'register' => array(
			'action_title'  => 'Registered',
			'action_hook'   => 'user_register',
		),
		'comment' => array(
			'action_title'  => 'Commented',
			'action_hook'   => 'comment_post',
		),
		'post_action' => array(
			'action_title'  => 'Posted',
			'action_hook'   => 'publish_post',
		)
	);


	/**
	* Returns Setting value
	*
	* Combines two input strings to create a value for the Options array, then
	* checks if that value exists.  If not, set it to blank.  If so, return the Settings value.
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