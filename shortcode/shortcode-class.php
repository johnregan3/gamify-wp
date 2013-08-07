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