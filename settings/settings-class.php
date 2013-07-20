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
			'action_title' => 'Registered',
			'action_hook' => 'user_register'
		),
		'comment' => array(
			'action_title' => 'Commented',
			'action_hook' => 'comment_post'
		),
		'post_action' => array(
			'action_title' => 'Posted',
			'action_hook' => 'publish_post'
		)
	);



	/**
	* Get the array of action names to dynamically generate hidden fields for default actions
	*/

	public function get_action_name_array( $action_name ) {

		$action_array = $this->action_array;
		$actions = array_keys( $action_array );

		foreach ( $actions as $action => $value ) {
			if ( $action_name = $action_array[$value] ) {
				$value = $action_array[$value];
			} else {
				$value = '';
			} // endif
		} // endforeach

		return $value;

	} // get_action_name_array



	/**
	* Get the array of field names to dynamically generate hidden fields for default actions
	*/

	public function get_field_value( $action_name, $field_name ) {

		$action_array = $this->action_array;
		$action_fields = $this->get_action_name_array( $action_name );
		$fields = array_keys( $action_fields );

		foreach ( $fields as $field => $value ) {
			if ( $field_name = $action_array[$action_name][$value] ) {
				$value = $action_array[$action_name][$value];
			} else {
				$value = '';
			} // endif
		} // endforeach

		return $value;

	} //get_name_attr_field


	/**
	* Get if option is not set, set it to ''.
	*/

	public function input_setup( $action, $field ){

		$options = get_option('gamwp_settings');
		$settings_title = $action . '_' . $field;
		$settings_name = (isset($options[$settings_title]) ? $options[$settings_title] : '');

		return $settings_name;

	} //input_setup

} // End Class GAMWP_Settings_Helpers