<?php

/*
 *
 * "Custom Actions" CPT & Action Hooks Generator
 *
 */



function gamwp_custom_actions_post_type() {

	$labels = array(
		'name'					=> _x( 'Custom Actions', 'post type general name', 'action' ),
		'singular_name'			=> _x( 'Custom Action', 'post type singular name', 'action' ),
		'add_new'				=> _x( 'Add New', 'action' ),
		'add_new_item'			=> __( 'Add New Custom Action' ),
		'edit_item'				=> __( 'Edit Custom Action' ),
		'new_item'				=> __( 'New Custom Action' ),
		'all_items'				=> __( 'All Custom Actions' ),
		'view_item'				=> __( 'View Custom Actions' ),
		'search_items'			=> __( 'Search Custom Actions' ),
		'not_found'				=> __( 'No Custom Actions found' ),
		'not_found_in_trash'	=> __( 'No Custom Actions found in the Trash' ),
		'menu_name'				=> 'Custom Actions',

	);

	$args = array(
		'description'	=> 'Assign Custom Actions for the Gamify WP Plugin.',
		'has_archive'	=> true,
		'labels'		=> $labels,
		'menu_position'	=> 5,
		'public'		=> true,
		'supports'		=> array( 'title' )
	);

	register_post_type( 'Custom Actions', $args );

}

add_action( 'init', 'gamwp_custom_actions_post_type' );



//remove unneeded meta boxes
function gamwp_remove_ca_meta_boxes() {

	remove_meta_box( 'postexcerpt' , 'points' , 'normal' );
	remove_meta_box( 'commentsdiv' , 'points' , 'normal' );
	remove_meta_box( 'commentstatusdiv' , 'points' , 'normal' );
	remove_meta_box( 'postimagediv', 'post', 'side' );

}

add_action( 'admin_menu' , 'gamwp_remove_ca_meta_boxes' );


/*
 *
 * Set Up Custom Actions Meta Box
 *
 */


function gamwp_ca_meta_box_init() {

	add_meta_box( 'gamwp_ca_meta_box', 'Custom Action Settings', 'gamwp_ca_meta_box', 'customactions', 'normal', 'high' );

}

add_action( 'add_meta_boxes', 'gamwp_ca_meta_box_init' );



//contents of the Custom Actions Meta Box

function gamwp_ca_meta_box() {

	global $post;

	//load field meta information

	$gamwp_ca_action_hook = get_post_meta( $post->ID, 'gamwp_ca_action_hook', true );
	$gamwp_ca_points = get_post_meta( $post->ID, 'gamwp_ca_points', true );
	$gamwp_ca_limit = get_post_meta( $post->ID, 'gamwp_ca_limit', true );

	if ( ! isset( $gamwp_ca_action_hook ) ) {

		$gamwp_ca_action_hook = '';

	}

	if ( ! isset( $gamwp_ca_points ) ) {

		$gamwp_ca_points = 0;

	}

	if ( ! isset( $gamwp_ca_limit ) ) {

		$gamwp_ca_limit = 0;

	}

	//display input fields

	wp_nonce_field( basename( __FILE__ ), 'gamwp_nonce' );

	echo '<table class="form-table">';

	echo '<tr><td><label for="gamwp_ca_action_hook">Action Hook</label><br />
	<input type="text" name="gamwp_ca_action_hook" id="gamwp_ca_action_hook" value="' . esc_attr( $gamwp_ca_action_hook ) . '"></td></tr>';

	echo '<tr><td><label for="gamwp_ca_points">Points awarded for this Action</label><br />
	<input type="text" name="gamwp_ca_points" id="gamwp_ca_points" value="' . esc_attr( $gamwp_ca_points ) . '" size="30" />
	</td></tr>';

	echo '<tr><td><label for="gamwp_ca_limit">Include in Daily Points Limit?</label><br /><input type="checkbox" id="gamwp_ca_limit" name="gamwp_ca_limit"" value="1" '. checked( $gamwp_ca_limit, 1, false ) . '/></td></tr>';

    echo '</table>';

}


// save the points information
function gamwp_ca_save( $post_id ) {

	if ( !isset( $_POST['gamwp_nonce'] ) || !wp_verify_nonce( $_POST['gamwp_nonce'], basename( __FILE__ ) ) ) {

		return $post_id;

	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {

		return $post_id;

	}

	//check permissions

	if ( 'Custom Action' == $_POST['post_type'] ) {

		if ( !current_user_can('edit_page', $post_id ) ) {

			return $post_id;

		}

	} elseif ( !current_user_can('edit_post', $post_id) ) {

		return $post_id;

	}


	//save the data for each field

	//action hook value

	$old = get_post_meta( $post_id, 'gamwp_ca_action_hook', true );

	if ( ! isset( $_POST['gamwp_ca_action_hook'] ) ) {

		$new = '';

	} else {

		$new = $_POST['gamwp_ca_action_hook'];

	}

	if ( $new && $new != $old ) {

		update_post_meta( $post_id, 'gamwp_ca_action_hook', $new );

	} elseif ( '' == $new && $old ) {

		delete_post_meta( $post_id, 'gamwp_ca_action_hook', $old );

	}

	//action hook value

	$old = get_post_meta( $post_id, 'gamwp_ca_points', true );

	if ( ! isset( $_POST['gamwp_ca_points'] ) ) {

		$new = '';

	} else {

		$new = $_POST['gamwp_ca_points'];

	}

	if ( $new && $new != $old ) {

		update_post_meta( $post_id, 'gamwp_ca_points', $new );

	} elseif ( '' == $new && $old ) {

		delete_post_meta( $post_id, 'gamwp_ca_points', $old );

	}

	//daily total value

	$check = isset( $_POST['gamwp_ca_limit'] ) ? 1 : 0 ;
	update_post_meta( $post_id, 'gamwp_ca_limit', $check );

} //end gamwp_save_points

add_action( 'save_post', 'gamwp_ca_save' );
