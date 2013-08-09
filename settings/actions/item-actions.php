<?php

/**
 * Functions used by Gamify WP Actions admin pages
 *
 * @since  1.0
 */


/**
 * Fetches all Actions
 *
 * @since  1.0
 */
function gact_get_gact_actions() {
    if ( isset( $_GET['gact-action'] ) ) {
        do_action( 'gact_' . $_GET['gact-action'], $_GET );
        }
    }
add_action( 'init', 'gact_get_gact_actions' );



/**
 * Checks for POST/GET
 *
 * @since  1.0
 */
function gact_process_actions() {
    if ( isset( $_POST['gact-action'] ) ) {
        do_action( 'gact_' . $_POST['gact-action'], $_POST );
    }

    if ( isset( $_GET['gact-action'] ) ) {
        do_action( 'gact_' . $_GET['gact-action'], $_GET );
    }
}
add_action( 'admin_init', 'gact_process_actions' );



/**
 * Fetches array of new item information, then sends it to be saved.
 *
 * @since  1.0
 * @param  array  $data  Data of item to be added
 */
function gact_add_item( $data ) {
	if ( isset( $data['gact-item-nonce'] ) && wp_verify_nonce( $data['gact-item-nonce'], 'gact_item_nonce' ) ) {
		// Setup the action code details
		$posted = array();

		foreach ( $data as $key => $value ) {
			if ( $key != 'gact-item-nonce' && $key != 'gact-action' && $key != 'gact-redirect' ) {
					$posted[ $key ] = $value;
			}
		}
				// Set the action code's default status to active
		if ( gact_store_item( $posted ) ) {
			wp_redirect( add_query_arg( 'item-message', 'item_added', $data['gact-redirect'] ) ); die();
		} else {
			wp_redirect( add_query_arg( 'item-message', 'item_add_failed', $data['gact-redirect'] ) ); die();
		}

	}
}
add_action( 'gact_add_item', 'gact_add_item' );



/**
 * Fetches array of new item information, then saves it and redirects.
 *
 * @since  1.0
 * @param  array  $details  Data of item to be added
 * @param  int    $item_id  Item for which to store the data.
 */
function gact_store_item( $details, $item_id = null ) {

	$meta = array(
		'action_hook' => isset( $details['action_hook'] ) ? $details['action_hook'] : '',
		'action_points' => isset( $details['action_points'] ) ? $details['action_points'] : '',
	);

	if ( gact_item_exists( $item_id ) && ! empty( $item_id ) ) {
		// Update an existing Item

		wp_update_post( array(
			'ID'          => $item_id,
			'post_title'  => $details['name'],
		) );

		foreach( $meta as $key => $value ) {
			update_post_meta( $item_id, '_gact_item_' . $key, $value );
		}

		// Item updated
		return true;

	} else {
		// Add the Item
		$item_id = wp_insert_post( array(
			'post_type'   => 'gact',
			'post_title'  => isset( $details['name'] ) ? $details['name'] : '',
			'post_status' => 'publish',
		) );
		foreach( $meta as $key => $value ) {
			update_post_meta( $item_id, '_gact_item_' . $key, $value );
		}

		// Item created
		return true;
	}
}



/**
 * Fetches array of new item information, then sends it to be saved.
 *
 * @since  1.0
 * @param  array  $data  Data of item to be added
 */
function gact_edit_item( $data ) {
	if ( isset( $data['gact-item-nonce'] ) && wp_verify_nonce( $data['gact-item-nonce'], 'gact_item_nonce' ) ) {

		$item = array();
		foreach ( $data as $key => $value ) {
			if ( $key != 'gact-item-nonce' && $key != 'gact-action' && $key != 'item_id' && $key != 'gact-redirect' ) {
					$item[ $key ] = strip_tags( addslashes( $value ) );
			}
		}

		if ( gact_store_item( $item, $data['item_id'] ) ) {
			wp_redirect( add_query_arg( 'gact-message', 'item_updated', $data['gact-redirect'] ) ); die();
		} else {
			wp_redirect( add_query_arg( 'gact-message', 'item_update_failed', $data['gact-redirect'] ) ); die();
		}
	}
}
add_action( 'gact_edit_item', 'gact_edit_item' );



/**
 * Checks to see if item exists
 *
 * @since  1.0
 * @param  int  $item_id  Item for which to store the data.
 * @return bool
 */
function gact_item_exists( $item_id ) {
	if ( gact_get_item( $item_id ) )
		return true;

	return false;
}



/**
 * Checks to see if item exists
 *
 * @since  1.0
 * @param  int  item_id  Item for which to store the data.
 * @return object $item  Post object for requested item ID.
 */
function gact_get_item( $item_id ) {
	$item = get_post( $item_id );

	if ( get_post_type( $item_id ) != 'gact' )
		return false;

	return $item;
}



/**
 * Listens for when a delete link is clicked and deletes the item
 *
 * @since  1.0
 * @param  array  $data
 */
function gact_delete_action( $data ) {
	if ( ! isset( $data['_wpnonce'] ) || ! wp_verify_nonce( $data['_wpnonce'], 'gact_item_nonce' ) )
		wp_die( __( 'Failed nonce verification', 'gact' ), __( 'Error', 'gamwp' ) );

	$item_id = $data['item_id'];
	wp_delete_post( $item_id, true );
}
add_action( 'gact_delete_action', 'gact_delete_action' );



/**
 * Deletes an item
 *
 * @since 1.0
 * @param int $item_id Item ID
 */
function gact_remove_item( $item_id = 0 ) {
	wp_delete_post( $item_id, true );
	delete_post_meta($item_id, '_gact_item_action_hook');
	delete_post_meta($item_id, '_gact_item_action_points');
}
