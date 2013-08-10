<?php

/**
 * Class for Generating User Statistics
 *
 * @since 1.0
 */

Class gamify_Shortcode {

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
		$actions = get_user_meta( $user_id, 'gamify_user_log', true );

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


	/**
	 * Display an unordered list of rewards/achievements
	 *
	 * @since  1.0
	 * @param  array $input_array  Array to convert to a list
	 * @return array $list_items   Items to be echoed to produce the list
	 */
	public static function produce_list( $input_array ) {
		$time = current_time( 'timestamp', 1 );

		echo "<ul>";
		foreach ( $input_array as $timestamp => $value ) {
			$offset = human_time_diff( $timestamp, $time );

			if ( $value['activity_type'] == 'action')
				echo "<li>" . esc_html( $value['activity_title'] ) . "&nbsp;" . __( 'for', 'gamify' ) . "&nbsp;" . esc_html( $value['activity_points'] ) . "&nbsp;" . __( 'points', 'gamify' ) . "&nbsp;(" . esc_html( $offset ) . "&nbsp;" . __( 'ago', 'gamify' ) . ")</li>";

			elseif ( $value['activity_type'] == 'reward')
				echo "<li>" . esc_html( $value['activity_title'] ) . "&nbsp;(" . esc_html( $offset ) . "&nbsp;" . __( 'ago', 'gamify' ) . ")</li>";
		}
		echo "</ul>";

	}

}