<?php

/**
 * Dynamically adds Actions and their associated callback functions
 *
 * Fetches all saved Actions (from Settings Page), then for each Action, generate
 * an add_action and callback function.
 *
 * @since 1.0
 */

add_action( 'init', 'create_actions');

function create_actions() {

	$items = GAMWP_Process::get_all_actions();

	if ( $items ) {
		foreach ( $items as $item) {
			$action_id = $item->ID;
			$user_id = get_current_user_id();
			$action_hook = get_post_meta( $item->ID, '_gact_item_action_hook', true );

			$$action_id = function() use ( $user_id, $action_id ){
				GAMWP_Process::save_action( $user_id, $action_id );
			};

			if ( isset( $action_hook ) ) {
				add_action( $action_hook, $$action_id, 10, 0);
			}
		}
	}

}


