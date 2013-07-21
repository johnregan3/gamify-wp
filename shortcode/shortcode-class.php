<?php

/*
 *
 * Class for Generating User Statistics
 *
 */

Class GAMWP_Shortcode {

	/**
	* Calculates Points earned during the last 24 hour period.
	* Used in shortcode-user.php
	*/

	public function calc_daily_points( $user_id, $time ) {

		$one_day_ago = $time - 86400;
		$daily_points_earned = 0;
		$actions = get_user_meta( $user_id, 'gamwp_actions', true );

			if ( isset( $actions ) ) {
				foreach ( $actions as $timestamp => $value ) {
					// If it occurred less than 24 hours ago
					if ( $timestamp >= $one_day_ago ) {
						// Extract points values from action_title Array
						$points = $value['points'];
						// if reward
						if ( isset( $value['reward'] ) ) {
							// Make points negative
							$points = -1 * abs($points);
						}
						// Add to $daily_points_earned
						$daily_points_earned = $daily_points_earned + $points;
					} // endif
				} // End foreach
			} // End if $actions

		return $daily_points_earned;

	} // End calc_daily_points


	public function get_custom_actions() {

	// Fetch all Custom Actions to include in the array
	$ca_array = array();

	$args=array(
		'post_type' => 'customactions',
		'post_status' => 'publish'
	);

	$gamwp_ca_query = null;
	$gamwp_ca_query = new WP_Query($args);

	if( $gamwp_ca_query->have_posts() ) {
		while ( $gamwp_ca_query->have_posts() ) {
			$gamwp_ca_query -> the_post();
			$action_title = get_the_title();
			$ca_array[$action_title]['action_title'] = $action_title;
		} // endwhile
	} // endif

	wp_reset_postdata();

	return $ca_array;

	} // End get_custom_actions


	public function get_all_actions($postid) {

		$settings = New GAMWP_Settings;

		// Fetch all Custom Actions
		$custom_actions = $this->get_custom_actions();

		// Fetch Default Actions from Settings
		$default_actions_array = $settings->action_array;
		$default_action_titles = wp_list_pluck( $default_actions_array, 'action_title' );

		// Zero them out
		foreach ($default_action_titles as $title => $val ) {
			$new_default_action_titles[$title]['action_title'] = $val;
		}

		// Combine array of Custom Actions with Default Actions
		$ca_default_array = array_merge($new_default_action_titles, $custom_actions);

		// Get Custom Actions previously saved
		$gamwp_rew_action_types = get_post_meta( $postid, 'gamwp_rew_action_types', true );
		if ( $gamwp_rew_action_types == '' ) {
			$gamwp_rew_action_types = array();
		}

	/* If Action previously saved no longer exists (isn't in $ca_default_array), then remove it.*/
	/////////////////////////////////////////////////////////////////////////////////////////////

		// Overwrite default actions with saved data where actions (keys) don't already exist
		$action_types = array_merge( $ca_default_array, $gamwp_rew_action_types );


		//prep action_titles for action_aliases
		function gamwp_alias($string) {
			//lower case everything
			$string = strtolower($string);
			//make alphaunermic
			$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
			//Clean multiple dashes or whitespaces
			$string = preg_replace("/[\s-]+/", " ", $string);
			//Convert whitespaces and underscore to dash
			$string = preg_replace("/[\s_]/", "-", $string);
			return $string;
		}

		//create computer-friendly alias for each action_title
		foreach ($action_types as $key => $val) {

			$action_title = $action_types[$key]['action_title'];

			$alias = gamwp_alias($action_title);

			$action_types[$key]['action_alias'] = $alias;

		}

		return $action_types;

	} // get_all_actions




} // End GAMWP_Stats Class