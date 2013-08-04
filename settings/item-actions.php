<?php

function acpt_get_acpt_actions() {
    if ( isset( $_GET['acpt-action'] ) ) {
        do_action( 'acpt_' . $_GET['acpt-action'], $_GET );
        }
    }
add_action( 'init', 'acpt_get_acpt_actions' );


function acpt_process_actions() {
    if ( isset( $_POST['acpt-action'] ) ) {
        do_action( 'acpt_' . $_POST['acpt-action'], $_POST );
    }

    if ( isset( $_GET['acpt-action'] ) ) {
        do_action( 'acpt_' . $_GET['acpt-action'], $_GET );
    }
}
add_action( 'admin_init', 'acpt_process_actions' );


function acpt_add_item( $data ) {
	if ( isset( $data['acpt-item-nonce'] ) && wp_verify_nonce( $data['acpt-item-nonce'], 'acpt_item_nonce' ) ) {
		// Setup the action code details
		$posted = array();

		foreach ( $data as $key => $value ) {
			if ( $key != 'acpt-item-nonce' && $key != 'acpt-action' && $key != 'acpt-redirect' ) {
					$posted[ $key ] = $value;
			}
		}
				// Set the action code's default status to active
		if ( acpt_store_item( $posted ) ) {
			wp_redirect( add_query_arg( 'item-message', 'item_added', $data['acpt-redirect'] ) ); die();
		} else {
			wp_redirect( add_query_arg( 'item-message', 'item_add_failed', $data['acpt-redirect'] ) ); die();
		}

	}
}
add_action( 'acpt_add_item', 'acpt_add_item' );


function acpt_store_item( $details, $item_id = null ) {

	$meta = array(
		'field1' => isset( $details['field1'] ) ? $details['field1'] : '',
		'field2' => isset( $details['field2'] ) ? $details['field2'] : '',
	);

	if ( acpt_item_exists( $item_id ) && ! empty( $item_id ) ) {
		// Update an existing Item

		wp_update_post( array(
			'ID'          => $item_id,
			'post_title'  => $details['name'],
		) );

		foreach( $meta as $key => $value ) {
			update_post_meta( $item_id, '_acpt_item_' . $key, $value );
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
			update_post_meta( $item_id, '_acpt_item_' . $key, $value );
		}

		// Item created
		return true;
	}
}

function acpt_edit_item( $data ) {
	if ( isset( $data['acpt-item-nonce'] ) && wp_verify_nonce( $data['acpt-item-nonce'], 'acpt_item_nonce' ) ) {

		$item = array();
		foreach ( $data as $key => $value ) {
			if ( $key != 'acpt-item-nonce' && $key != 'acpt-action' && $key != 'item_id' && $key != 'acpt-redirect' ) {
					$item[ $key ] = strip_tags( addslashes( $value ) );
			}
		}

		if ( acpt_store_item( $item, $data['item_id'] ) ) {
			wp_redirect( add_query_arg( 'acpt-message', 'item_updated', $data['acpt-redirect'] ) ); die();
		} else {
			wp_redirect( add_query_arg( 'acpt-message', 'item_update_failed', $data['acpt-redirect'] ) ); die();
		}
	}
}
add_action( 'acpt_edit_item', 'acpt_edit_item' );



function acpt_item_exists( $item_id ) {
	if ( acpt_get_item( $item_id ) )
		return true;

	return false;
}

function acpt_get_item( $item_id ) {
	$item = get_post( $item_id );

	if ( get_post_type( $item_id ) != 'g_action' )
		return false;

	return $item;
}


/**
 * Listens for when a action delete button is clicked and deletes the
 * action code
 */
function acpt_delete_action( $data ) {
	if ( ! isset( $data['_wpnonce'] ) || ! wp_verify_nonce( $data['_wpnonce'], 'acpt_item_nonce' ) )
		wp_die( __( 'Failed nonce verification', 'acpt' ), __( 'Error', 'acpt' ) );

	$item_id = $data['item_id'];
	wp_delete_post( $item_id, true );
}
add_action( 'acpt_delete_action', 'acpt_delete_action' );


