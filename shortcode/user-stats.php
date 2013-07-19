<?php

/*
 *
 * User Stats Shortcode
 *
 */

function gamwp_stats_shortcode() {

	if ( is_User_logged_in() ) {

		$stats = New GAMWP_Stats;

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
		echo sprintf( __( '<p><strong>Points Earned in Last 24 Hours:</strong> (need to add Daily Limit from Settings) %s', $todays_points, 'gamwp' ), $todays_points );

		//Recent Points Earned
		_e( '<p><strong>Recent Activity</strong></p>', 'gamwp' );

		if ( $actions) {

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

		//Rename Register/Login Link
		function register_replacement( $link ){

			if ( get_option('users_can_register') ) {

				$link = __('Sign Up');

			} else {

				$link =  __('Log In');

			}

			return $link;

		}

		add_action( 'register' , 'register_replacement' );

		_e( '<p><em>You must be logged in to view your stats.</p>', 'gamwp' );

		wp_register('', '');

}

} //end gamwp_user_page_shortcode

add_shortcode( 'gamwp-stats', 'gamwp_stats_shortcode' );

