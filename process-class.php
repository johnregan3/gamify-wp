<?php

/**
 * Initialize Action creation
 *
 * @since 1.0
 */

function initialize_actions() {
	$user_id = get_current_user_id();
	gamify_Process::create_actions( $user_id );
	gamify_Process::achievement_check( $user_id );
}

add_action( 'init', 'initialize_actions' );

/**
 * Class for Processing Actions
 *
 * @since 1.0
 */

Class gamify_Process {

	/**
	 * Get all Actions
	 *
	 * Uses get_posts() to get all Gamify WP Actions
	 *
	 * @since 1.0
	 */

	static function get_all_activities( $post_type ) {

		$args = array(
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		);

		$items = get_posts( $args );

		return $items;

	}


	/**
	 * Dynamically adds Actions and their associated callback functions to Action Hooks
	 *
	 * Fetches all saved Actions (from Settings Page), then for each Action, generate
	 * an add_action and callback function.
	 *
	 * @since 1.0
	 * @param int $user_id Current User ID
	 */
	static function create_actions( $user_id ) {

		$items = self::get_all_activities( 'gact' );

		if ( $items ) {
			foreach ( $items as $item) {
				$action_id = $item->ID;
				$action_hook = get_post_meta( $item->ID, '_gamify_item_action_hook', true );

				$$action_id = function() use ( $user_id, $action_id ){
					self::save_action( $user_id, $action_id );
				};

				if ( isset( $action_hook ) ) {
					add_action( $action_hook, $$action_id, 10, 0);
				}
			}
		}
	}

	/**
	 * Check to see if an Achievement has been reached
	 *
	 * @since  1.0
	 * @param  int $user_id     User ID who completed the Action
	 * @param  int $activity_id The Action/Reward completed
	 */

	public static function achievement_check( $user_id ) {

		/**
		 * So, on every page load, we're cycling through all actions the user has accomplished.
		 * Then we're cycling through every possible reward to see if they've earned it.
		 * There's gotta be a better way to do this.
		 * */

		//Calculate the user's total lifetime points
		$user_log_array = get_user_meta( $user_id, 'gamify_user_log', 'single' );

		if ( $user_log_array) {
			$user_total_points = 0;
			$user_rewards_array = array();
			//loop through activities to get total points for user.
			foreach ( $user_log_array as $entry ) {
				$user_actions_array[] = $entry['activity_id'];
				if ( $entry['activity_type'] == 'action' ) {
					$user_total_points = $user_total_points + $entry['activity_points'];

				}
			}
		}


		//loop through activites to ensure the current reward isn't already there (already earned).
		$rewards = self::get_all_activities( 'rew' );

		foreach ( $rewards as $rew_obj ) {

			$rew_points = get_post_meta( $rew_obj->ID, '_gamify_item_activity_points' , 'single' );
			//Check to ensure the current reward has not been earned (check user_actions_array for matching reward ID).
			if( isset( $user_actions_array ) ) {
				$reward_check = in_array( $rew_obj->ID, $user_actions_array );
				//Award achievment and save.
				if ( ( $user_total_points >= $rew_points ) && ( $reward_check == false ) ) {
					self::save_action( $user_id, $rew_obj->ID );
				}
			}

		}

	}



	/**
	 * Save Point-earning activity to Logs
	 *
	 * Gathers information from completed Action and puts it into an array ($add_to_array), with the current time as its index.
	 * This array is then saved to a Master array used for the Admin Logs, and to the User's Meta for personal stat calculations.
	 *
	 * @since  1.0
	 * @param  int $user_id     User ID who completed the Action
	 * @param  int $activity_id The Action/Reward completed
	 */

	public static function save_action( $user_id, $activity_id) {

		//get activity information
		$post_type = get_post_type( $activity_id );
		$activity_type = ( $post_type == 'rew' ) ? 'reward' : 'action';
		$activity_title  = get_the_title( $activity_id );
		$activity_points = get_post_meta( $activity_id, '_gamify_item_activity_points', true );

		//create entry for master_log
		$time = current_time( 'timestamp', 1 );
		$add_to_array[$time]['userid']          = $user_id;
		$add_to_array[$time]['activity_id']     = $activity_id;
		$add_to_array[$time]['activity_type']   = $activity_type;
		$add_to_array[$time]['activity_title']  = $activity_title;
		$add_to_array[$time]['activity_points'] = $activity_points;

		//Save activity array to two places:  master_log, for tracking sitewide stats, and user_meta for focused stats.

		//Save to master log

		$master_log_array = get_option( 'gamify_master_log' );

		if ( is_array( $master_log_array ) ) {
			$new_master_log_array = $master_log_array + $add_to_array;
		} else {
			$new_master_log_array = $add_to_array;
		}

		$updated_option = update_option( 'gamify_master_log', $new_master_log_array );

		//Save to user log

		$user_log_array = get_user_meta( $user_id, 'gamify_user_log', 'single' );

		if ( is_array( $user_log_array ) ) {
			$new_user_log_array = $user_log_array + $add_to_array;
		} else {
			$new_user_log_array = $add_to_array;
		}

		update_user_meta( $user_id, 'gamify_user_log', $new_user_log_array );

	}

}
