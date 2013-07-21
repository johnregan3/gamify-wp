<?php

/*
 *
 * "Rewards" CPT & Action Hooks Generator
 *
 */



function gamwp_rewards_post_type() {

	$labels = array(
		'name'					=> _x( 'Rewards', 'post type general name', 'reward' ),
		'singular_name'			=> _x( 'Reward', 'post type singular name', 'reward' ),
		'add_new'				=> _x( 'Add New', 'reward' ),
		'add_new_item'			=> __( 'Add New Reward' ),
		'edit_item'				=> __( 'Edit Reward' ),
		'new_item'				=> __( 'New Reward' ),
		'all_items'				=> __( 'All Rewards' ),
		'view_item'				=> __( 'View Rewards' ),
		'search_items'			=> __( 'Search Rewards' ),
		'not_found'				=> __( 'No Rewards found' ),
		'not_found_in_trash'	=> __( 'No Rewards found in the Trash' ),
		'menu_name'				=> 'Rewards',

	);

	$args = array(
		'exclude_from_search'	=> true,
		'description'			=> 'Assign Rewards for the Gamify WP Plugin.',
		'has_archive'			=> true,
		'labels'				=> $labels,
		'menu_position'			=> 5,
		'public'				=> true,
		'supports'				=> array( 'title', 'thumbnail' )
	);

	register_post_type( 'Rewards', $args );

}

add_action( 'init', 'gamwp_rewards_post_type' );



//remove unneeded meta boxes
function gamwp_remove_rew_meta_boxes() {

	remove_meta_box( 'postexcerpt' , 'points' , 'normal' );
	remove_meta_box( 'commentsdiv' , 'points' , 'normal' );
	remove_meta_box( 'commentstatusdiv' , 'points' , 'normal' );
	remove_meta_box( 'postimagediv', 'post', 'side' );

}

add_action( 'admin_menu' , 'gamwp_remove_rew_meta_boxes' );



/*
 *
 * Reword Text in Featured Image Meta Box
 *
 */

function gamwp_rew_image_box() {

	remove_meta_box( 'postimagediv', 'rewards', 'side' );
	add_meta_box( 'postimagediv', __('Reward Image'), 'post_thumbnail_meta_box', 'rewards', 'side', 'low' );

}

add_action('do_meta_boxes', 'gamwp_rew_image_box');



function gamwp_rew_image_box_text( $content ) {

	if ( 'rewards' == get_post_type() ) {
		return $content = str_replace( __( 'Set featured image' ), __( 'Set Reward image' ), $content );
	} else {
	return $content = str_replace( __( 'Set featured image' ), __( 'Set featured image' ), $content );
	}

}

add_filter( 'admin_post_thumbnail_html', 'gamwp_rew_image_box_text' );



/*
 *
 * Set Up Rewards Meta Box
 *
 */


function gamwp_rew_meta_box_init() {

	add_meta_box( 'gamwp_rew_meta_box', 'Reward Settings', 'gamwp_rew_meta_box', 'rewards', 'normal', 'high' );

}

add_action( 'add_meta_boxes', 'gamwp_rew_meta_box_init' );


// Contents of the Rewards Meta Box
function gamwp_rew_meta_box() {

	$settings = New GAMWP_Settings;
	$shortcode = New GAMWP_Shortcode;

	global $post;
	$postid = $post->ID;

	//load field meta information
	$gamwp_rew_type = get_post_meta( $postid, 'gamwp_rew_type', true );
	$gamwp_rew_goal_points = get_post_meta( $postid, 'gamwp_rew_goal_points', true );

	if ( ! isset( $gamwp_rew_goal_points ) ) {
		$gamwp_rew_goal_points = '';
	}

	if ( ! isset( $gamwp_rew_points ) ) {
		$gamwp_rew_points = 0;
	}


	// Generate Nonce
	wp_nonce_field( basename( __FILE__ ), 'gamwp_nonce' );

	?>

	<table class="form-table">

	<tr>
		<td>
			<span>Reward Type</span><br />
			<select name="gamwp_rew_type" id="gamwp_rew_type">
				<option value="level" <?php selected( $gamwp_rew_type, 'level' ); ?> ><?php _e( 'Level', 'gamwp'); ?></option>
				<option value="badge" <?php selected( $gamwp_rew_type, 'badge' ); ?> ><?php _e( 'Badge', 'gamwp'); ?></option>
			</select>
		</td>
	</tr>

	<tr>
		<td>
			<label for="gamwp_rew_goal_points">Points required to earn this Reward</label><br />
			<input type="text" name="gamwp_rew_goal_points" id="gamwp_rew_goal_points" value="<?php _e( $gamwp_rew_goal_points, 'gamwp' ) ?>" size="30" />
		</td>
	</tr>



	<tr>
		<td>
		<?php
			_e( '<p>Select which points apply toward this reward</p>', 'gamwp');

			$gamwp_rew_action_types = $shortcode->get_all_actions($postid);

			foreach( $gamwp_rew_action_types as $key => $val) {

				$action_title = $val['action_title'];
				$action_alias = $val['action_alias'];
				// Is "not empty" the best way to check this?
				if ( ! isset( $val['value'] ) ) {
					$val['value'] = 0;
			}

			?>

			<input type="checkbox" name="<?php _e( $action_alias, 'gamwp' ); ?>" id="<?php _e( $action_alias, 'gamwp' ); ?>" value="1"<?php checked( $val['value'], 1, true ) ?> />

			<label for="<?php _e( $action_alias, 'gamwp' ); ?>"><?php _e( $action_title, 'gamwp' ); ?></label><br />

			<?php } ?>

		</td>
	</tr>

	</table>

	<?php

}



/*
 *
 * Save Reward Data
 *
 */


function gamwp_rew_save( $post_id ) {

	$shortcode = New GAMWP_Shortcode;

	if ( !isset( $_POST['gamwp_nonce'] ) || !wp_verify_nonce( $_POST['gamwp_nonce'], basename( __FILE__ ) ) ) {
		return $post_id;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	//check permissions

	if ( 'Reward' == $_POST['post_type'] ) {
		if ( !current_user_can('edit_page', $post_id ) ) {
			return $post_id;
		}
	} elseif ( !current_user_can('edit_post', $post_id) ) {
		return $post_id;
	}

	//save the data for each field

	//action hook value
	$old = get_post_meta( $post_id, 'gamwp_rew_type', true );
	if ( ! isset( $_POST['gamwp_rew_type'] ) ) {
		$new = '';
	} else {
		$new = $_POST['gamwp_rew_type'];
	}

	if ( $new && $new != $old ) {
		update_post_meta( $post_id, 'gamwp_rew_type', $new );
	} elseif ( '' == $new && $old ) {
		delete_post_meta( $post_id, 'gamwp_rew_type', $old );
	}

	//action hook value

	$old = get_post_meta( $post_id, 'gamwp_rew_goal_points', true );

	if ( ! isset( $_POST['gamwp_rew_goal_points'] ) ) {
		$new = '';
	} else {
		$new = $_POST['gamwp_rew_goal_points'];
	}

	if ( $new && $new != $old ) {
		update_post_meta( $post_id, 'gamwp_rew_goal_points', $new );
	} elseif ( '' == $new && $old ) {
		delete_post_meta( $post_id, 'gamwp_rew_goal_points', $old );
	}

	//Checkboxes

	$gamwp_rew_action_types = $shortcode->get_all_actions( $post_id );
	foreach( $gamwp_rew_action_types as $key => $val ) {
		$action_alias = $val['action_alias'];
		if ( isset( $_POST[$action_alias] ) ) {
			$gamwp_rew_action_types[$key]['value'] = 1;
		} else {
			$gamwp_rew_action_types[$key]['value'] = 0;
		}// End if
	} // End foreach

	update_post_meta( $post_id, 'gamwp_rew_action_types',  $gamwp_rew_action_types );

} //end gamwp_save_points

add_action( 'save_post', 'gamwp_rew_save' );
