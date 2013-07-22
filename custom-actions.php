<?php

/*
 *
 * Get Custom Actions (Custom Post Type) and create action hooks
 *
 */




/*
 * this line is commented out.
 * cannot run this action twice on the same page "cannot redeclare process_custom_action()".
 * This is going to REQUIRE a variable in the function name.
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

			$action_id = get_the_ID();
			$action_title = get_the_title();
			$hook_value = get_post_meta ( $action_id, 'gamwp_ca_action_hook', 'single' );
			$action_daily_limit = get_post_meta ( $action_id, 'gamwp_ca_limit', 'single' );
			$hook_array = array("action_id" => $action_id, "action_title" => $action_title,
			"action_daily_limit" => $action_daily_limit );
			$master_array[$hook_value] = $hook_array;


		} // endwhile

	} // endif

	wp_reset_postdata();




	foreach( $master_array as $hook_value => $hook_array ) {
		global $action_id, $action_title;
		$action_id = $hook_array['action_id'];
		$action_title = $hook_array['action_title'];
		$action_daily_limit = $hook_array['action_daily_limit'];

		$func_title = "process_custom_action_". $action_id;

		${$func_title} = function() {

			global $action_id, $action_title;
				$user_id = wp_get_current_user();
				$process = New GAMWP_Process;

				$points = get_post_meta( $action_id, 'gamwp_ca_points', 'single' );
				$process->save_process_results( $user_id, $action_title, $action_id, $action_daily_limit );

		};

		add_action( $hook_value, $func_title, 10);

	} // End foreach

}


