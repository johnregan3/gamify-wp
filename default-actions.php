<?php

/*
 *
 * Preset Actions Hooks
 *
 */


/*
 *
 * Register New User
 *
 */

function gamwp_register_user( $user_id ) {

	$process = New GAMWP_Process;
	$action = 'register';
	$action_title = $process->get_action_settings($action, 'action_title');
	$points = $process->get_action_settings($action, 'points');
	$process->save_process_results( $user_id, $action_title, $points );

} // End gamwp_user_register

add_action('user_register', 'gamwp_register_user');


/*
 *
 * Add Comment
 *
 */

function gamwp_comment( $comment_id, $status ) {

	$process = New GAMWP_Process;

	if ( $status == 1 ) {
		$user_id = get_current_user_id();
		$process = New GAMWP_Process;
		$action = 'comment';
		$action_title = $process->get_action_settings($action, 'action_title');
		$points = $process->get_action_settings($action, 'points');
		$process->save_process_results( $user_id, $action_title, $points );
	}

} // End gamwp_comment

add_action('comment_post', 'gamwp_comment', 10, 2);



/*
 *
 * Publish Post
 *
 */

function gamwp_publish_post( $post_id ) {

	$process = New GAMWP_Process;
	$user_id = $post->post_author;
	$action = 'post_action';
	$action_title = $process->get_action_settings($action, 'action_title');
	$points = $process->get_action_settings($action, 'points');
	$process->save_process_results( $user_id, $action_title, $points );

} // End gamwp_publish_post

add_action('publish_post', 'gamwp_publish_post');