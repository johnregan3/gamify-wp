<?php

/**
 * Initialize Action creation
 *
 * @since 1.0
 */

function initialize_actions() {
	$user_id = get_current_user_id();
	GAMWP_Process::create_actions( $user_id );
}

add_action( 'init', 'initialize_actions' );



/**
 * Class for Processing Actions
 *
 * @since 1.0
 */

Class GAMWP_Process {

	/**
	 * Get all Actions
	 *
	 * Uses get_posts() to get all Gamify WP Actions
	 *
	 * @since 1.0
	 */

	static function get_all_actions() {

		$args = array(
			'post_type'      => 'gact',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		);

		$items = get_posts( $args );

		return $items;

	}



	/**
	 * Get a single Action's attribute array
	 *
	 * @since  1.0
	 * @param  int   $action_id $post->ID of requested Action
	 * @return array Array of attributes of requested Action
	 */

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




	/**
	 * Dynamically adds Actions and their associated callback functions
	 *
	 * Fetches all saved Actions (from Settings Page), then for each Action, generate
	 * an add_action and callback function.
	 *
	 * @since 1.0
	 * @param int $user_id Current User ID
	 */
	static function create_actions( $user_id ) {

		$items = self::get_all_actions();

		if ( $items ) {
			foreach ( $items as $item) {
				$action_id = $item->ID;
				$action_hook = get_post_meta( $item->ID, '_gact_item_action_hook', true );

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
	 * Save Point-earning activity to Logs
	 *
	 * Gathers information from completed Action and puts it into an array ($add_to_array), with the current time as its index.
	 * This array is then saved to a Master array used for the Admin Logs, and to the User's Meta for personal stat calculations.
	 *
	 * @since  1.0
	 * @param  int $user_id   User ID who completed the Action
	 * @param  int $action_id The Action completed
	 */

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

}
