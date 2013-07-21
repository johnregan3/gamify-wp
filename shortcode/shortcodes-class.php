<?php

/*
 *
 * User Page Shortcode
 *
 */

function gamwp_user_page_shortcode() {

	$help = New GAMWP_Process;

	//Start by gathering Vars

	$time = current_time( 'timestamp', 1 );

	//Get current user
	$current_user = wp_get_current_user();

		$username = $current_user->user_login;

		$user_id =  $current_user->ID;

	$score = get_user_meta( $user_id, 'cpjr3_score', true );

	$events = get_user_meta( $user_id, 'cpjr3_events', true );

	//Print Header

	_e( '<h3>Points Totals for ' . $username . '</h3>', 'cpjr3' );

	//Total Points Earned

	_e( '<p><strong>Total Points:</strong> ' . $score . '</p>', 'cpjr3' );

	//Calculate Daily Points Total

	$todays_points = $help->calc_daily_points( $user_id, $time );

	_e( '<p><strong>Points Earned in Last 24 Hours:</strong> ' . $todays_points . ' </p>', 'cpjr3' );

	//Recent Points Earned

	_e( '<p><strong>Recent Activity</strong></p>', 'cpjr3' );

	if ( $events) {

		$recent_events = array_reverse( $events, true );

		$recent_events = array_slice( $recent_events, 0, 10, true );

		echo '<ul>';

		foreach ( $recent_events as $value => $key ) {

			$offset = human_time_diff( $value, $time );

			if( isset( $key['reward'] ) ) {

				echo '<li>' . $key['action'] . ' for ' . $key['points'] . ' points (' . $offset . ' ago)</li>';

			} else {

				echo '<li>' . $key['points'] . ' points for ' . $key['action'] . ' (' . $offset . ' ago)</li>';

			}

		}

		echo '</ul>';

	}// if $events

} //end cpjr3_user_page_shortcode

add_shortcode( 'gamwp-user-stats', 'gamwp_user_page_shortcode' );

