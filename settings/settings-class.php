<?php

/*
 *
 * Gamify WP Settings Class
 *
 */

Class GAMWP_Settings {

	/**
	* Array of Default Actions and their hooks
	*/

	public $action_array = array(
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
	* Get if option is not set, set it to ''.
	*/

	public function input_setup( $action, $field ){

		$options = get_option('gamwp_settings');
		$settings_title = $action . '_' . $field;
		$value = (isset( $options[$settings_title] ) ? $options[$settings_title] : '');

		return $value;

	} //input_setup

} // End Class GAMWP_Settings_Helpers