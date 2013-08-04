<?php

function g_action_get_g_action_actions() {
    if ( isset( $_GET['g_action-action'] ) ) {
        do_action( 'g_action_' . $_GET['g_action-action'], $_GET );
        }
    }
add_action( 'init', 'g_action_get_g_action_actions' );


function g_action_process_actions() {
    if ( isset( $_POST['g_action-action'] ) ) {
        do_action( 'g_action_' . $_POST['g_action-action'], $_POST );
    }

    if ( isset( $_GET['g_action-action'] ) ) {
        do_action( 'g_action_' . $_GET['g_action-action'], $_GET );
    }
}
add_action( 'admin_init', 'g_action_process_actions' );


function g_action_add_item( $data ) {
	if ( isset( $data['g_action-item-nonce'] ) && wp_verify_nonce( $data['g_action-item-nonce'], 'g_action_item_nonce' ) ) {
		// Setup the action code details
		$posted = array();

		foreach ( $data as $key => $value ) {
			if ( $key != 'g_action-item-nonce' && $key != 'g_action-action' && $key != 'g_action-redirect' ) {
					$posted[ $key ] = $value;
			}
		}
				// Set the action code's default status to active
		if ( g_action_store_item( $posted ) ) {
			wp_redirect( add_query_arg( 'item-message', 'item_added', $data['g_action-redirect'] ) ); die();
		} else {
			wp_redirect( add_query_arg( 'item-message', 'item_add_failed', $data['g_action-redirect'] ) ); die();
		}

	}
}
add_action( 'g_action_add_item', 'g_action_add_item' );


function g_action_store_item( $details, $item_id = null ) {

	$meta = array(
		'action_hook' => isset( $details['action_hook'] ) ? $details['action_hook'] : '',
		'action_points' => isset( $details['action_points'] ) ? $details['action_points'] : '',
	);

	if ( g_action_item_exists( $item_id ) && ! empty( $item_id ) ) {
		// Update an existing Item

		wp_update_post( array(
			'ID'          => $item_id,
			'post_title'  => $details['name'],
		) );

		foreach( $meta as $key => $value ) {
			update_post_meta( $item_id, '_g_action_item_' . $key, $value );
		}

		// Item updated
		return true;

	} else {
		// Add the Item
		$item_id = wp_insert_post( array(
			'post_type'   => 'g_action',
			'post_title'  => isset( $details['name'] ) ? $details['name'] : '',
			'post_status' => 'publish',
		) );
		foreach( $meta as $key => $value ) {
			update_post_meta( $item_id, '_g_action_item_' . $key, $value );
		}

		// Item created
		return true;
	}
}

function g_action_edit_item( $data ) {
	if ( isset( $data['g_action-item-nonce'] ) && wp_verify_nonce( $data['g_action-item-nonce'], 'g_action_item_nonce' ) ) {

		$item = array();
		foreach ( $data as $key => $value ) {
			if ( $key != 'g_action-item-nonce' && $key != 'g_action-action' && $key != 'item_id' && $key != 'g_action-redirect' ) {
					$item[ $key ] = strip_tags( addslashes( $value ) );
			}
		}

		if ( g_action_store_item( $item, $data['item_id'] ) ) {
			wp_redirect( add_query_arg( 'g_action-message', 'item_updated', $data['g_action-redirect'] ) ); die();
		} else {
			wp_redirect( add_query_arg( 'g_action-message', 'item_update_failed', $data['g_action-redirect'] ) ); die();
		}
	}
}
add_action( 'g_action_edit_item', 'g_action_edit_item' );



function g_action_item_exists( $item_id ) {
	if ( g_action_get_item( $item_id ) )
		return true;

	return false;
}

function g_action_get_item( $item_id ) {
	$item = get_post( $item_id );

	if ( get_post_type( $item_id ) != 'g_action' )
		return false;

	return $item;
}


/**
 * Listens for when a action delete button is clicked and deletes the
 * action code
 */
function g_action_delete_action( $data ) {
	if ( ! isset( $data['_wpnonce'] ) || ! wp_verify_nonce( $data['_wpnonce'], 'g_action_item_nonce' ) )
		wp_die( __( 'Failed nonce verification', 'g_action' ), __( 'Error', 'gamwp' ) );

	$item_id = $data['item_id'];
	wp_delete_post( $item_id, true );
}
add_action( 'g_action_delete_action', 'g_action_delete_action' );


