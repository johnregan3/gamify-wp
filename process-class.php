<?php

/*
 *
 * Class for Processing Actions
 *
 */

Class GAMWP_Process {


	//Total Daily Points from last 24 hours ($user_id)

	public function daily_points_earned( $user_id ) {

		$time = current_time( 'timestamp', 1 );
		$one_day_ago = $time - 86400;
		$daily_points_earned = 0;

		$master_array = get_option( 'gamwp_master_log' );
		if ( $master_array ) {
			foreach ( $master_array as $timestamp => $value ) {
				if( ( $timestamp >= $one_day_ago ) && ( $user_id == $value['userid'] ) ) {
					$activity_points = $value[ 'activity_points' ];
					$daily_points_earned = $daily_points_earned + $activity_points;
				}
			}
		}
		return $daily_points_earned;
	}

	private function is_once_and_unused( $user_id, $activity_id ) {
		//Check to see if action is a one-time-use action
		$options = get_option( 'gamwp_action_settings' );
		$once = isset( $options[$activity_id]['once'] ) ? $options[$activity_id]['once'] : 'unchecked';

		if ( 'checked' == $once ) {
			$master_array = get_option( 'gamwp_master_log' );
			foreach ( $master_array as $timestamp ) {
				if ( ( $user_id == $timestamp['userid'] ) && ( $activity_id == $timestamp['activity_id'] ) ) {
					return false;
				}
			}
		}
		return true;
	}

	public function save_activity( $user_id, $activity_id) {

		//get activity information
		$activity_options = get_option( 'gamwp_action_settings' );

		foreach( $activity_options as $activity => $value ) {
			if ( $activity_id == $activity ) {
				$activity_points = isset( $value['activity_points'] ) ? $value['activity_points'] : '';
				$activity_title = isset( $value['activity_title'] ) ? $value['activity_title'] : '';
				$activity_daily_limit = isset( $value['activity_daily_limit'] ) ? $value['activity_daily_limit'] : '';
				$once_unused = $this->is_once_and_unused( $user_id, $activity_id);
				if ( $once_unused == false ) {
					return false;
				}

				$general_options = get_option( 'gamwp_settings' );
				$daily_limit_active = isset ( $general_options['daily_limit_activate'] ) ? $general_options['daily_limit_activate'] : 0 ;
				$daily_points_limit = isset( $general_options['daily_limit'] ) ? $general_options['daily_limit'] : 99999 ;
				$daily_points_earned = $this->daily_points_earned( $user_id );
				if ( ( 'checked' == $activity_daily_limit ) && ( 1 == $daily_limit_active ) && ( $daily_points_earned >= $daily_points_limit ) ) {
					return false;
				}

				//create entry for master_log
				$add_to_array = array();
				$time = current_time( 'timestamp', 1 );
				$add_to_array[$time] = array();
				$add_to_array[$time]['userid'] = $user_id;
				$add_to_array[$time]['activity_id'] = $activity_id;
				$add_to_array[$time]['activity_title'] = $activity_title;
				$add_to_array[$time]['activity_points'] = $activity_points;

				//Save activity array to two places:  master_log, for tracking sitewide stats, and user_meta for focused stats.
				//This saves looping through all actions ever just to get a single user's stats.

				$master_log_array = get_option( 'gamwp_master_log' );
				if ( is_array( $master_log_array ) ) {
					$new_master_log_array = $master_log_array + $add_to_array;
				} else {
					$new_master_log_array = $add_to_array;
				}
				$updated_option = update_option( 'gamwp_master_log', $new_master_log_array );

				$user_log_array = get_user_meta( $user_id, 'gamwp_user_log' );
				if ( is_array( $user_log_array ) ) {
					$new_user_log_array = $user_log_array + $add_to_array;
				} else {
					$new_user_log_array = $add_to_array;
				}
				update_user_meta( $user_id, 'gamwp_user_log', $new_user_log_array );
			}
		}
	}


} //End Class Process
