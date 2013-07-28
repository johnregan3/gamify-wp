<?php

/*
 *
 * User Stats Shortcode
 *
 */

function gamwp_stats_shortcode() {

	if ( is_user_logged_in() ) {

		$shortcode = New GAMWP_Shortcode;

		// Gather Vars
		$time = current_time( 'timestamp', 1 );
		$current_user = wp_get_current_user();
		$username = $current_user->user_login;
		$user_id =  $current_user->ID;
		$score = get_user_meta( $user_id, 'gamwp_score', true );
		$score = isset( $score ) ? $score : '0';
		$actions = get_user_meta( $user_id, 'gamwp_actions', true );

		//Print Header
		echo sprintf( __( '<h3>Points Totals for %s</h3>', 'gamwp' ), $username );

		//Total Points Earned
		echo sprintf( __( '<p><strong>Total Points:</strong> %s</p>', 'gamwp' ), $score );

		$todays_points = $shortcode->calc_daily_points( $user_id, $time );
		//Do not include daily point limit.
		echo sprintf( __( '<p><strong>Points Earned in Last 24 Hours:</strong>  %s', 'gamwp' ), esc_html__( $todays_points, 'gamwp' ) );
		if ( $actions) {

			echo sprintf( '<p><strong>%s</strong></p>', __( 'Recent Activity', 'gamwp' ) );

			$recent_actions = array_reverse( $actions, true );
			$recent_actions = array_slice( $recent_actions, 0, 10, true );
			echo '<ul>';
			foreach ( $recent_actions as $value => $key ) {
				$offset = human_time_diff( $value, $time );
				echo sprintf( "<li> %s for %s points ( %s ago)</li>", esc_html( $key['action_title'] ), esc_html( $key['points'] ), esc_html( $offset ) );
			}
			echo '</ul>';
		}
	} else {
		echo sprintf( '<p><em><%s/em></p><p>', __( 'You must be logged in to view your stats.', 'gamwp' ) );
		wp_register('', '');
		echo sprintf( " | <a href='%s' title='%s'>%s</a></p>", esc_url( wp_login_url() ), __( 'Login', 'gamwp' ), __( 'Login', 'gamwp' ));
	}
}

add_shortcode( 'gamwp-stats', 'gamwp_stats_shortcode' );

