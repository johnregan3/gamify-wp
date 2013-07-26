<?php

/*
 *
 * User Stats Shortcode
 *
 */

function gamwp_stats_shortcode() {

	if ( is_User_logged_in() ) {

		$stats = New GAMWP_Shortcode;

		// Gather Vars

		$time = current_time( 'timestamp', 1 );

		$current_user = wp_get_current_user();
		$username = $current_user->user_login;
		$user_id =  $current_user->ID;

		$score = get_user_meta( $user_id, 'gamwp_score', true );
		$actions = get_user_meta( $user_id, 'gamwp_actions', true );

		//Print Header
		echo sprintf( __( '<h3>Points Totals for %s</h3>', 'gamwp' ), $username );

		//Total Points Earned
		echo sprintf( __( '<p><strong>Total Points:</strong> %s</p>', 'gamwp' ), $score );

		//Calculate Daily Points Total
		$todays_points = $stats->calc_daily_points( $user_id, $time );
		$options = get_option('gamwp_settings');
		$daily_limit = $options['daily_limit'];
		echo sprintf( __('<p><strong>Points Earned in Last 24 Hours:</strong>  %s / %s', 'gamwp' ), esc_html__( $todays_points, 'gamwp' ), esc_html__( $daily_limit, 'gamwp' ) );



		if ( $actions) {

			//Recent Points Earned
			_e( '<p><strong>Recent Activity</strong></p>', 'gamwp' );

			$recent_actions = array_reverse( $actions, true );
			$recent_actions = array_slice( $recent_actions, 0, 10, true );

			echo '<ul>';

			foreach ( $recent_actions as $value => $key ) {

				$offset = human_time_diff( $value, $time );
				echo '<li>' . $key['action_title'] . ' for ' . $key['points'] . ' points (' . $offset . ' ago)</li>';

			}

			echo '</ul>';

		}// if $actions

	} else {

		//Register/Login Link
		_e( '<p><em>You must be logged in to view your stats.</em></p><p>', 'gamwp' );

		wp_register('', '');

		echo ' | <a href="' . wp_login_url() . '" title="Login">Login</a></p>';

}

} //end gamwp_user_page_shortcode

add_shortcode( 'gamwp-stats', 'gamwp_stats_shortcode' );

