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
function rew_get_rew_actions() {
    if ( isset( $_GET['rew-action'] ) ) {
        do_action( 'rew_' . $_GET['rew-action'], $_GET );
        }
    }
add_action( 'init', 'rew_get_rew_actions' );



/**
 * Checks for POST/GET
 *
 * @since  1.0
 */
function rew_process_actions() {
    if ( isset( $_POST['rew-action'] ) ) {
        do_action( 'rew_' . $_POST['rew-action'], $_POST );
    }

    if ( isset( $_GET['rew-action'] ) ) {
        do_action( 'rew_' . $_GET['rew-action'], $_GET );
    }
}
add_action( 'admin_init', 'rew_process_actions' );



/**
 * Fetches array of new item information, then sends it to be saved.
 *
 * @since  1.0
 * @param  array  $data  Data of item to be added
 */
function rew_add_item( $data ) {
	if ( isset( $data['rew-item-nonce'] ) && wp_verify_nonce( $data['rew-item-nonce'], 'rew_item_nonce' ) ) {
		// Setup the action code details
		$posted = array();

		foreach ( $data as $key => $value ) {
			if ( $key != 'rew-item-nonce' && $key != 'rew-action' && $key != 'rew-redirect' ) {
					$posted[ $key ] = $value;
			}
		}
				// Set the action code's default status to active
		if ( rew_store_item( $posted ) ) {
			wp_redirect( add_query_arg( 'item-message', 'item_added', $data['rew-redirect'] ) ); die();
		} else {
			wp_redirect( add_query_arg( 'item-message', 'item_add_failed', $data['rew-redirect'] ) ); die();
		}

	}
}
add_action( 'rew_add_item', 'rew_add_item' );



/**
 * Fetches array of new item information, then saves it and redirects.
 *
 * @since  1.0
 * @param  array  $details  Data of item to be added
 * @param  int    $item_id  Item for which to store the data.
 */
function rew_store_item( $details, $item_id = null ) {

	$meta = array(
		'activity_points' => isset( $details['activity_points'] ) ? $details['activity_points'] : '',
	);

	if ( rew_item_exists( $item_id ) && ! empty( $item_id ) ) {
		// Update an existing Item

		wp_update_post( array(
			'ID'          => $item_id,
			'post_title'  => $details['name'],
		) );

		foreach( $meta as $key => $value ) {
			update_post_meta( $item_id, '_gamify_item_' . $key, $value );
		}

		// Item updated
		return true;

	} else {
		// Add the Item
		$item_id = wp_insert_post( array(
			'post_type'   => 'rew',
			'post_title'  => isset( $details['name'] ) ? $details['name'] : '',
			'post_status' => 'publish',
		) );
		foreach( $meta as $key => $value ) {
			update_post_meta( $item_id, '_gamify_item_' . $key, $value );
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
function rew_edit_item( $data ) {
	if ( isset( $data['rew-item-nonce'] ) && wp_verify_nonce( $data['rew-item-nonce'], 'rew_item_nonce' ) ) {

		$item = array();
		foreach ( $data as $key => $value ) {
			if ( $key != 'rew-item-nonce' && $key != 'rew-action' && $key != 'item_id' && $key != 'rew-redirect' ) {
					$item[ $key ] = strip_tags( addslashes( $value ) );
			}
		}

		if ( rew_store_item( $item, $data['item_id'] ) ) {
			wp_redirect( add_query_arg( 'rew-message', 'item_updated', $data['rew-redirect'] ) ); die();
		} else {
			wp_redirect( add_query_arg( 'rew-message', 'item_update_failed', $data['rew-redirect'] ) ); die();
		}
	}
}
add_action( 'rew_edit_item', 'rew_edit_item' );



/**
 * Checks to see if item exists
 *
 * @since  1.0
 * @param  int  $item_id  Item for which to store the data.
 * @return bool
 */
function rew_item_exists( $item_id ) {
	if ( rew_get_item( $item_id ) )
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
function rew_get_item( $item_id ) {
	$item = get_post( $item_id );

	if ( get_post_type( $item_id ) != 'rew' )
		return false;

	return $item;
}



/**
 * Listens for when a delete link is clicked and deletes the item
 *
 * @since  1.0
 * @param  array  $data
 */
function rew_delete_action( $data ) {
	if ( ! isset( $data['_wpnonce'] ) || ! wp_verify_nonce( $data['_wpnonce'], 'rew_item_nonce' ) )
		wp_die( __( 'Failed nonce verification', 'rew' ), __( 'Error', 'gamify' ) );

	$item_id = $data['item_id'];
	wp_delete_post( $item_id, true );
}
add_action( 'rew_delete_action', 'rew_delete_action' );



/**
 * Deletes an item
 *
 * @since 1.0
 * @param int $item_id Item ID
 */
function rew_remove_item( $item_id = 0 ) {
	wp_delete_post( $item_id, true );
	delete_post_meta($item_id, '_gamify_item_activity_type');
	delete_post_meta($item_id, '_gamify_item_activity_points');
}
