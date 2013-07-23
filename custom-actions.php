<?php

/*
 *
 * Get Custom Actions (Custom Post Type) and create action hooks
 *
 */

add_action( 'init', 'get_custom_actions');

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
			global $user_id, $action_id, $action_title, $points, $action_daily_limit;
			$action_id = get_the_ID();
			$action_title = get_the_title();
			$hook_value = get_post_meta ( $action_id, 'gamwp_ca_action_hook', 'single' );
			$hook_points = get_post_meta ( $action_id, 'gamwp_ca_points', 'single' );
			$action_daily_limit = get_post_meta ( $action_id, 'gamwp_ca_limit', 'single' );

			// This currently works, but there must be a better way to organize these variables.

			$user_id = get_current_user_id();
			$hook_array = array(
				"action_id" => $action_id,
				"action_title" => $action_title,
				"points" => $hook_points,
				"action_daily_limit" => $action_daily_limit
			);
			$master_array[$hook_value] = $hook_array;

			foreach( $master_array as $hook_value => $hook_array ) {
				global $user_id, $action_id, $action_title, $points, $action_daily_limit;
				$action_id = $hook_array['action_id'];
				$action_title = $hook_array['action_title'];
				$action_points = $hook_array['points'];
				$action_daily_limit = $hook_array['action_daily_limit'];

				$func_title = "process_custom_action_" . $action_id;
				$func_title1 = $func_title;
				${$func_title} = function() {
					global $user_id, $action_id, $action_title, $points, $action_daily_limit;
					$process = New GAMWP_Process;
					$points = get_post_meta( $action_id, 'gamwp_ca_points', 'single' );
					$process->save_process_results( $user_id, $action_title, $points, $action_daily_limit );
				};
				add_action( $hook_value, ${$func_title1}, 10, 0);
			} // End foreach

		} // endwhile
	} // endif

	wp_reset_postdata();

}
