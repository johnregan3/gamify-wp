<?php

 /**
  * Content of the Edit Action Page
  *
  * @since 1.0
  */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! isset( $_GET['item_id'] ) || ! is_numeric( $_GET['item_id'] ) )
	wp_die( __( 'Error.', 'gamify' ), __( 'Error', 'gamify' ) );


$item_id  = absint( $_GET['item_id'] );
$item     = badges_get_item( $item_id );
$activity_points     = get_post_meta( $item_id, '_badges_item_activity_points', true );

?>
<h2><?php _e( 'Edit Reward', 'gamify' ); ?> - <a href="<?php echo admin_url( 'admin.php?post_type=rew&page=gamify-badges.php' ); ?>" class="button-secondary"><?php _e( 'Go Back', 'gamify' ); ?></a></h2>
<form id="rew-edit-tiem" action="" method="post">
	<table class="form-table">
		<tbody>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="rew-name"><?php _e( 'Name', 'gamify' ); ?></label>
				</th>
				<td>
					<input name="name" id="rew-name" type="text" value="<?php echo esc_attr( $item->post_title ); ?>" style="width: 300px;"/>
					<p class="description"><?php _e( 'The name of this Reward.', 'gamify' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="activity_points"><?php _e( 'Points', 'gamify' ); ?></label>
				</th>
				<td>
					<input name="activity_points" id="rew-points" type="text" value="<?php echo esc_attr( $activity_points ) ?>" style="width: 300px;"/>
					<p class="description"><?php _e( 'Enter a point value Users must earn to gain this reward.', 'gamify' ); ?></p>
				</td>
			</tr>


		</tbody>
	</table>
	<p class="submit">
		<input type="hidden" name="rew-action" value="edit_item"/>
		<input type="hidden" name="item_id" value="<?php echo absint( $_GET['item_id'] ); ?>"/>
		<input type="hidden" name="rew-redirect" value="<?php echo esc_url( admin_url( 'admin.php?post_type=rew&page=gamify-badges.php' ) ); ?>"/>
		<input type="hidden" name="rew-item-nonce" value="<?php echo wp_create_nonce( 'badges_item_nonce' ); ?>"/>
		<input type="submit" value="<?php _e( 'Update Reward', 'gamify' ); ?>" class="button-primary"/>
	</p>
</form>
