<?php

/**
 * Class for Generating User Statistics
 *
 * @since 1.0
 */

Class GAMWP_Shortcode {

	/**
	 * Calculate a user's points earned in the last 24 hours
	 *
	 * Cycles through all of a user's activity where the timestamp is less than 24 hours ago.
	 * Used by User Stats sortcode.
	 *
	 * @since  1.0
	 * @param  int $user_id             User ID to calculate points for
	 * @param  int $time                Current time
	 * @return int $daily_points_earned Total points earned by user in last 24 hours
	 */

	public function calc_daily_points( $user_id, $time ) {

		$one_day_ago = $time - 86400;
		$daily_points_earned = 0;
		$actions = get_user_meta( $user_id, 'gamwp_user_log', true );

			if ( $actions ) {
				foreach ( $actions as $timestamp => $value ) {
					// If it occurred less than 24 hours ago
					if ( $timestamp >= $one_day_ago ) {
						// Extract points values from action_title Array
						$points = $value['activity_points'];
						// Add to $daily_points_earned
						$daily_points_earned = $daily_points_earned + $points;
					} // endif
				} // End foreach
			} else {
				$daily_points_earned = '0';
			}

		return $daily_points_earned;

	}

}