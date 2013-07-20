<?php

/*
 *
 * Get Custom Actions (Custom Post Type) and create action hooks
 *
 */

function get_custom_actions() {

	$master_array = array();

	$args=array(
		'post_type' => 'customactions',
		'post_status' => 'publish'
	);

	$gamwp_ca_query = null;
	$gamwp_ca_query = new WP_Query($args);

	if( $gamwp_ca_query->have_posts() ) {

		while ( $gamwp_ca_query->have_posts() ) {
			$gamwp_ca_query -> the_post();

			$action_id = get_the_ID();
			$action_title = get_the_title();
			$hook_value = get_post_meta ( $action_id, 'gamwp_ca_action_hook', 'single' );
			$hook_array = array("action_id" => $action_id, "action_title" => $action_title );
			$master_array[$hook_value] = $hook_array;


		} // endwhile

	} // endif

	wp_reset_postdata();




	foreach( $master_array as $hook_value => $hook_array ) {
		global $action_id, $action_title;
		$action_id = $hook_array["action_id"];
		$action_title = $hook_array["action_title"];
		function doit() {

			global $action_id, $action_title;
				$user_id = wp_get_current_user();
				$process = New GAMWP_Process;

				$points = get_post_meta( $action_id, 'gamwp_ca_points', 'single' );
				$process->save_process_results( $user_id, $action_title, $action_id );

		};

		add_action( $hook_value, 'doit', 10);

	} // End foreach

}

// Fire on init
add_action( 'init', 'get_custom_actions');
