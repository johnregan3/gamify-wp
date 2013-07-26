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


	//Total Daily Points from last 24 hours ($user_id)

	public function daily_points_earned( $user_id ) {

		//calculate time 24 hours ago
		$time = current_time( 'timestamp', 1 );

		//subract 1 day/24 hours
		$one_day_ago = $time - 86400;

		//Calculate new $daily_points earned
		$daily_points_earned == 0;

		//fetch Events Array from user meta
		$actions_array = get_user_meta( $user_id, 'gamwp_actions', true );

		if ( $actions_array ) {
			foreach ( $actions_array as $timestamp => $value ) {
				//if it occurred less than 24 hours ago
				if( $timestamp >= $one_day_ago ) {
					//extract points values from Action Array
					$action_points = $value[ 'points' ];
					//Add to $daily_points_total
					$daily_points_earned = $daily_points_earned + $action_points;
				} //endif
			} //end foreach
		} //end if $events_array

		return $daily_points_earned;

	} // end daily_points_earned

	/**
	* Retrieves score, then adds new points
	*/

	private function calc_score( $user_id, $action_id, $action_title, $points, $action_daily_limit ) {

		//Add Points to User's Total Score
		$total_score = get_user_meta( $user_id, 'gamwp_score', true );

		if ( ! isset( $total_score ) ) {
			$total_score = 0;
		}

		$options = get_option( 'gamwp_settings' );
		$daily_points_limit = $options['daily_limit'];
		if ( array_key_exists( 'daily_limit_activate', $options ) ) {
			$daily_points_limit_activate = $options['daily_limit_activate'];
		} else {
			$daily_points_limit_activate = '0';
		}

		//Check to see if action is a one-time-use action
		$options = get_option( 'gamwp_ca_settings' );
		$once = $options[$action_id]['once'];

		if ( $once == 'checked' ) {
			//check to see if action already extists in user actions array
			$user_actions = get_user_meta( $user_id, 'gamwp_actions', true );
			$action_titles = wp_list_pluck( $user_actions, 'action_title' );
			foreach ($action_titles as $title ) {
				if ( $action_title == $title ) {
					$new_score = $total_score;
				} else {
					// Check to see if Daily Limit has been reached
					if ( ( $action_daily_limit == 'checked' ) && ( $daily_points_limit_activate == '1' ) ) {
						//check daily total field to see if total points from last 24 hours, plus the new amount, exceeds daily limit
						//get all points earned in the last 24 hours from daily totals
						$daily_points_earned = $this->daily_points_earned( $user_id );
						//compare the daily points earned to pre-set daily limit total limit.
						//if daily points total < daily limit, simply add current points to the total and save.
						if ( $daily_points_earned < $daily_points_limit ) {
							//Add Action Array (with Points) to Event Array, then save
							$new_score = $total_score + $points;
						} else {
							$new_score = $total_score;
						// Maybe return a message on User Profile Page
							// "You've reached our your daily points limit for the last 24 hours."
						}
					} else {
						$new_score = $total_score + $points;
					}
				}
			}
		}
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

	public function save_process_results( $user_id, $action_id, $action_title, $action_points, $action_daily_limit, $once ) {

		// Retrieve score, then add new points
		$new_score = $this->calc_score( $user_id, $action_id, $action_title, $action_points, $action_daily_limit, $once );

		// Save new score to user meta
		$score_result = $this->save_score( $user_id, $action_points, $new_score );

		// Save actions to user meta
		$actions_result = $this->save_action( $user_id, $action_title, $action_points );
		$result = array_merge($score_result, $actions_result);

		return $result;

	} // End save_process_results


} //End Class Process
