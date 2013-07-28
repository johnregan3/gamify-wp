<?php

/*
 *
 * Get Custom Actions (Custom Post Type) and create action hooks
 *
 */

add_action( 'init', 'get_actions');



function get_actions() {
	$options = get_option('gamwp_ca_settings');
	foreach($options as $action_id => $actions) {
		$user_id = get_current_user_id();
		$action_title = $actions['action_title'];
		$action_hook = $actions['action_hook'];
		$action_points = $actions['action_points'];
		$action_daily_limit = isset( $actions['daily_limit'] ) ? $actions['daily_limit'] : 'unchecked';
		$once = isset( $actions['once'] ) ? $actions['once'] : 'unchecked';
		$func_title = "custom_action_" . $action_id;
		$$func_title = function() use ( $user_id, $action_id, $action_title, $action_points, $action_daily_limit, $once ){
			$process = New GAMWP_Process;
			$process->save_process_results( $user_id, $action_id, $action_title, $action_points, $action_daily_limit, $once );
		};
		if ( isset( $action_hook ) ) {
			add_action( $action_hook, $$func_title, 10, 0);
		}
	}
}

