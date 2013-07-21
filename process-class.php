<?php

/*
 *
 * Class for Processing Actions
 *
 */

Class GAMWP_Process {

	/**
	* Reorders array by key value
	* Used to order Rewards Items by price
	*/

	private function reorder_array( &$array, $key ) {

		// Re-sort $rewards_array so rewards appear in order of price
		// From http://stackoverflow.com/questions/2699086/sort-multidimensional-array-by-value-2

		$sorter = array();
		$ret = array();
		reset ( $array );

		foreach ( $array as $ii => $va ) {
			$sorter[$ii] = $va[$key];
		}

		asort ( $sorter );

		foreach ( $sorter as $ii => $va ) {
			$ret[$ii] = $array[$ii];
		}

		$array = $ret;

	}


	/**
	* Retrieves score, then adds new points
	*/

	private function calc_score( $user_id, $points ) {

		//Add Points to User's Total Score
		$total_score = get_user_meta( $user_id, 'gamwp_score', true );

		if ( ! isset( $total_score ) ) {
			$total_score = 0;
		}

		$new_score = $total_score + $points;

		return $new_score;

	}



	/**
	* Saves action data to User Meta
	* Used save actions that occur when points are earned/redeemed
	*/

	private function save_action( $user_id, $action_title, $points ) {

		//Add actions to User's action meta
		$actions_array = get_user_meta( $user_id, 'gamwp_actions', true );
		$time = current_time( 'timestamp', 1 );
		$actions_array[$time]['action_title'] = $action_title;
		$latest_action = $actions_array[$time]['action_title'];
		$actions_array[$time]['points'] = $points;

		// Save to User Meta
		$updated_actions = update_user_meta( $user_id, 'gamwp_actions', $actions_array );

		if( $updated_actions === false ) {
			$action_result['actions']['type'] = 'error';
		}

		else {
			$action_result['actions']['type'] = 'success';
			$action_result['actions']['action'] = $latest_action; //most recent action
		}

		return $action_result;

	} // End save_actions



	/**
	* Saves new total Score to User Meta
	* Used when points are earned/redeemed
	*/

	private function save_score( $user_id, $points, $new_score ) {

		$updated_score = update_user_meta( $user_id, 'gamwp_score', $new_score );

			if( $updated_score === false ) {
				$score_result['score']['type'] = 'error';
			} else {
				$score_result['score']['type'] = 'success';
				$score_result['score']['value'] = $points;
			}

		return $score_result;

	}




	/**
	* Saves new Score and action data
	* Used when points are earned/redeemed
	*/

	public function save_process_results( $user_id, $action_title, $points ) {

		// Retrieve score, then add new points
		$new_score = $this->calc_score( $user_id, $points );

		// Save new score to user meta
		$score_result = $this->save_score( $user_id, $points, $new_score );

		// Save actions to user meta
		$actions_result = $this->save_action( $user_id, $action_title, $points );
		$result = array_merge($score_result, $actions_result);

		return $result;

	} // End save_process_results



	/**
	* Returns the setting for a given field
	* Used in default/custom-actions processing
	*/

	public function get_action_settings( $action, $field ) {

		$options = get_option('gamwp_settings');
		$settings_title = $action . '_' . $field;
		$settings = (isset($options[$settings_title]) ? $options[$settings_title] : '');

		return $settings;

	} // End get_action_settings


} //End Class Process
