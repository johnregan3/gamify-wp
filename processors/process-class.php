<?php

/*
 *
 * Class for Processing Actions
 *
 */

Class GAMWP_Process {

	static function get_all_actions() {

		$args = array(
			'post_type'      => 'gact',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		);

		$items = get_posts( $args );

		return $items;

	}

	public static function get_single_action( $action_id ) {

		$items = self::get_all_actions();

		if ( $items ) {
			foreach ( $items as $item ) {
				if ( $action_id == $item->ID ) {
					return $item;
				}
			}
		}

	}


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

	public static function save_action( $user_id, $action_id) {

		//get activity information
		$item = self::get_single_action( $action_id );
		$action_title = get_the_title( $action_id );
		$action_hook = get_post_meta( $item->ID, '_gact_item_action_hook', true );
		$action_points = get_post_meta( $item->ID, '_gact_item_action_points', true );

		//create entry for master_log
		$time = current_time( 'timestamp', 1 );
		$add_to_array[$time]['userid']          = $user_id;
		$add_to_array[$time]['activity_id']     = $action_id;
		$add_to_array[$time]['activity_title']  = $action_title;
		$add_to_array[$time]['activity_points'] = $action_points;

		//Save activity array to two places:  master_log, for tracking sitewide stats, and user_meta for focused stats.
		//This saves looping through all actions ever just to get a single user's stats.

		//Save to master log

		$master_log_array = get_option( 'gamwp_master_log' );

		if ( is_array( $master_log_array ) ) {
			$new_master_log_array = $master_log_array + $add_to_array;
		} else {
			$new_master_log_array = $add_to_array;
		}

		$updated_option = update_option( 'gamwp_master_log', $new_master_log_array );

		//Save to user log

		$user_log_array = get_user_meta( $user_id, 'gamwp_user_log', 'single' );

		if ( is_array( $user_log_array ) ) {
			$new_user_log_array = $user_log_array + $add_to_array;
		} else {
			$new_user_log_array = $add_to_array;
		}

		update_user_meta( $user_id, 'gamwp_user_log', $new_user_log_array );

	}


} //End Class GAMWP_Process
