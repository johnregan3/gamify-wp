<?php

/*
 *
 * THIS WHOLE THING NEEDS TO BE REWRITTEN
 *
 */

add_action( 'wp_ajax_gamwp_processor', 'gamwp_process' );
add_action( 'wp_ajax_nopriv_gamwp_processor', 'gamwp_must_login' );

function gamwp_process( ) {

	$process = New GAMWP_Process;

	if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'gamwp_nonce' ) ) {

		exit( "Failed nonce verification." );

	}

	//Get variables
	$user_id = $_REQUEST['user_id'];
	$points = $_REQUEST['points'];
	$action_title = $_REQUEST['action_title'];
	$daily_limit = $_REQUEST['daily_limit'];

	//Save new score and events to user meta
	$result = $process->save_process_results( $user_id, $action_title, $points, $daily_limit );

	echo json_encode( $result );

	die();


}

function gamwp_must_login() {

	echo "Want to earn Rewards?  Log in or Sign up!";

	die();

}
