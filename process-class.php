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
		$options = get_option( 'gamwp_ca_settings' );
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
		$activity_option_array = get_option( 'gamwp_ca_settings' );
		foreach( $activity_option_array as $activity_option ) {
			if ( $activity_id == $activity_option ) {
				$activity_title = $activity_option['activity_title'];
				$activity_points = $activity_option['activity_points'];
				$activity_daily_limit = $activity_option['action_daily_limit'];
				$once = $activity_option_id['once'];
			}
		}

		$once_used = $this->is_once_and_unused( $user_id, $activity_id);
		if ( $once_used == true ) {
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
		$add_to_master_array = array();
		$time = current_time( 'timestamp', 1 );
		$add_to_master_array[$time]['userid'] = $user_id;
		$add_to_master_array[$time]['activity_title'] = $activity_title;
		$add_to_master_array[$time]['activity_points'] = $activity_points;

		//save activity array to master array
		$master_log_array = get_option( 'gamwp_master_log' );
		$master_log_array = !empty( $master_log_array ) ? $master_log_array : array();
		$new_master_log_array = array_merge( $master_log_array, $add_to_master_array );
		$updated_option = update_option( 'gamwp_master_log', $new_master_log_array );
		print_r($activity_option);

		if( false === $updated_option ) {
			$action_result['actions']['type'] = 'error';
		} else {
			$action_result['actions']['type'] = 'success';
			$action_result['actions']['action'] = $activity_title;
		}
	}


} //End Class Process
