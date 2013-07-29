<?php

/*
 *
 * Get Custom Actions (Custom Post Type) and create action hooks
 *
 */

add_action( 'init', 'get_actions');

function get_actions() {
	$options = get_option( 'gamwp_action_settings' );
	if( $options ) {
		foreach( $options as $activity_id => $actions ) {
			$user_id = get_current_user_id();
			$action_hook = isset( $actions['action_hook'] ) ? $actions['action_hook'] : '' ;
			$$activity_id = function() use ( $user_id, $activity_id ){
				$process = New GAMWP_Process;
				$process->save_activity( $user_id, $activity_id );
			};
			if ( isset( $action_hook ) ) {
				add_action( $action_hook, $$activity_id, 10, 0);
			}
		}
	}
}

