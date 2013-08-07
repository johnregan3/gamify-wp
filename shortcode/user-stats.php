<?php

/*
 *
 * User Stats Shortcode
 * THIS PAGE IS A FREAKIN' MESS
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
		$user_log_array = get_user_meta( $user_id, 'gamwp_user_log', true );

		//Print Header
		echo sprintf( __( '<h3>Points Totals for %s</h3>', 'gamwp' ), $username );

		$todays_points = $shortcode->calc_daily_points( $user_id, $time );
		//Do not include daily point limit.
		echo sprintf( __( '<p><strong>Points Earned in Last 24 Hours:</strong>  %s', 'gamwp' ), esc_html__( $todays_points, 'gamwp' ) );
		if ( $user_log_array ) {

			echo sprintf( '<p><strong>%s</strong></p>', __( 'Recent Activity', 'gamwp' ) );

			$reverse_user_log_array = array_reverse( $user_log_array, true );
			$user_log_array = array_slice( $reverse_user_log_array, 0, 10, true );

			foreach ( $user_log_array as $timestamp => $value ) {
				$offset = human_time_diff( $timestamp, $time );
				echo sprintf( "<li> %s for %s points (%s ago)</li>", esc_html( $value['activity_title'] ), esc_html( $value['activity_points'] ), esc_html( $offset ) );
			}
			echo '</ul>';
		}
	} else {
		echo sprintf( '<p><em>%s</em></p><p>', __( 'You must be logged in to view your stats.', 'gamwp' ) );
		wp_register('', '');
		echo sprintf( " | <a href='%s' title='%s'>%s</a></p>", esc_url( wp_login_url() ), __( 'Login', 'gamwp' ), __( 'Login', 'gamwp' ));
	}
}

add_shortcode( 'gamwp-stats', 'gamwp_stats_shortcode' );

