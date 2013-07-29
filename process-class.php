<?php

/*
 *
 * Class for Processing Actions
 *
 */

Class GAMWP_Process {


	//Total Daily Points from last 24 hours ($user_id)

	public function daily_points_earned( $user_id ) {

		//calculate time 24 hours ago
		$time = current_time( 'timestamp', 1 );

		//subract 1 day/24 hours
		$one_day_ago = $time - 86400;

		//Calculate new $daily_points earned
		$daily_points_earned = 0;

		//fetch Events Array from user meta
		$actions_array = get_user_meta( $user_id, 'gamwp_actions', true );

		if ( $actions_array ) {
			foreach ( $actions_array as $timestamp => $value ) {
				if( $timestamp >= $one_day_ago ) {
					$action_points = $value[ 'points' ];
					$daily_points_earned = $daily_points_earned + $action_points;
				}
			}
		}

		return $daily_points_earned;

	} // end daily_points_earned

	private function is_once_and_used( $user_id, $action_id, $action_title ) {
		//Check to see if action is a one-time-use action
		$options = get_option( 'gamwp_ca_settings' );
		$once = isset( $options[$action_id]['once'] ) ? $options[$action_id]['once'] : 'unchecked';

		if ( 'checked' == $once ) {
			//check to see if action already extists in user actions array
			$user_actions = get_user_meta( $user_id, 'gamwp_actions', true );
			if ( !empty( $user_actions ) ) {
				foreach ($user_actions as $row) {
					if ( !empty( $row ) ) {
						if ( array_key_exists('action_title', $row) ) {
							$action_ids = wp_list_pluck( $user_actions, 'action_title' );
							$action_ids = isset( $action_ids ) ? $action_ids : array() ;
							foreach ( $action_ids as $action_id => $title ) {
								if ( $action_title == $title ) {
									return true;
								}
							}
						}
					}
				}
			}
		}
		return false;
	}


	/**
	* Retrieves score, then adds new points
	*/

	private function calc_score( $user_id, $action_id, $action_title, $points, $action_daily_limit ) {

		$total_score = get_user_meta( $user_id, 'gamwp_score', true );
		$total_score = isset( $total_score ) ? $total_score : 0;
		$once_used = $this->is_once_and_used( $user_id, $action_id, $action_title );
		$options_action= get_option( 'gamwp_ca_settings' );
		$action_daily_limit = isset( $options_action[$action_id]['daily_limit'] ) ? $options_action[$action_id]['daily_limit'] : 'unchecked';

		$options_general = get_option( 'gamwp_settings' );
		$daily_limit_activate = isset( $options_general['daily_limit_activate'] ) ? $options_general['daily_limit_activate'] : 0;
		$daily_points_limit = $options_general['daily_limit'];
		$daily_points_earned = $this->daily_points_earned( $user_id );
		if ( $once_used == true ) {
			return $total_score;
		}
		if ( ( 'checked' == $action_daily_limit ) && ( 1 == $daily_limit_activate ) && ( $daily_points_earned >= $daily_points_limit ) ) {
			return $total_score;
		} else {
			$new_score = $total_score + $points;
			return $new_score;
		}
	}



	/**
	* Saves action data to User Meta
	* Used save actions that occur when points are earned/redeemed
	*/

	private function save_action( $user_id, $action_id, $action_title, $action_points ) {

		$once_used = $this->is_once_and_used($user_id, $action_id, $action_title);
		if ( true !== $once_used ) {

			//Add actions to User's action meta
			$actions_array = get_user_meta( $user_id, 'gamwp_actions', true );
			$time = current_time( 'timestamp', 1 );
			$actions_array[$time]['activity_title'] = $action_title;
			$latest_action = $actions_array[$time]['activity_title'];
			$actions_array[$time]['points'] = $action_points;
			$actions_array[$time]['username'] = $user_id;

			$updated_actions = update_user_meta( $user_id, 'gamwp_actions', $actions_array );

			$master_array = get_option( 'gamwp_master_log');
			$master_array = array();
			//array merge $actions_array with gamwp_master_array
			$master_log_add = $master_array + $actions_array;
			//update options(gamwp_master_array)
			update_option( 'gamwp_master_log', $master_log_add );

			if( false === $updated_actions ) {
				$action_result['actions']['type'] = 'error';
			} else {
				$action_result['actions']['type'] = 'success';
				$action_result['actions']['action'] = $latest_action;
			}
		} else {
			$action_result = array();
		}

		return $action_result;

	} // End save_actions



	/**
	* Saves new total Score to User Meta
	* Used when points are earned/redeemed
	*/

	private function save_score( $user_id, $action_points, $new_score ) {
		$updated_score = update_user_meta( $user_id, 'gamwp_score', $new_score );
			if( false === $updated_score ) {
				$score_result['score']['type'] = 'error';
			} else {
				$score_result['score']['type'] = 'success';
				$score_result['score']['value'] = $action_points;
			}
		return $score_result;
	}




	/**
	* Saves new Score and action data
	* Used when points are earned/redeemed
	*/

	public function save_process_results( $user_id, $action_id, $action_title, $action_points, $action_daily_limit, $once ) {

		// Retrieve score, then add new points
		$new_score = $this->calc_score( $user_id, $action_id, $action_title, $action_points, $action_daily_limit, $once );

		// Save new score to user meta
		$score_result = $this->save_score( $user_id, $action_points, $new_score );

		// Save actions to user meta
		$actions_result = $this->save_action( $user_id, $action_id, $action_title, $action_points );
		$result = array_merge($score_result, $actions_result);

		return $result;

	} // End save_process_results


} //End Class Process
