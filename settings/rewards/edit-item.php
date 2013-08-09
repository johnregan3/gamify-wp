<?php

 /**
  * Content of the Edit Action Page
  *
  * @since 1.0
  */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! isset( $_GET['item_id'] ) || ! is_numeric( $_GET['item_id'] ) )
	wp_die( __( 'Error.', 'gamwp' ), __( 'Error', 'gamwp' ) );


$item_id  = absint( $_GET['item_id'] );
$item     = rew_get_item( $item_id );
$rew_points     = get_post_meta( $item_id, '_rew_item_rew_points', true );

?>
<h2><?php _e( 'Edit Item', 'gamwp' ); ?> - <a href="<?php echo admin_url( 'admin.php?post_type=rew&page=gamify-rewards.php' ); ?>" class="button-secondary"><?php _e( 'Go Back', 'gamwp' ); ?></a></h2>
<form id="rew-edit-tiem" action="" method="post">
	<table class="form-table">
		<tbody>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="rew-name"><?php _e( 'Name', 'gamwp' ); ?></label>
				</th>
				<td>
					<input name="name" id="rew-name" type="text" value="<?php echo esc_attr( $item->post_title ); ?>" style="width: 300px;"/>
					<p class="description"><?php _e( 'The name of this discount', 'gamwp' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="rew_points"><?php _e( 'Points', 'gamwp' ); ?></label>
				</th>
				<td>
					<input name="rew_points" id="rew-points" type="text" value="<?php echo esc_attr( $rew_points ) ?>" style="width: 300px;"/>
					<p class="description"><?php _e( 'Enter a Points value', 'gamwp' ); ?></p>
				</td>
			</tr>


		</tbody>
	</table>
	<p class="submit">
		<input type="hidden" name="rew-action" value="edit_item"/>
		<input type="hidden" name="item_id" value="<?php echo absint( $_GET['item_id'] ); ?>"/>
		<input type="hidden" name="rew-redirect" value="<?php echo esc_url( admin_url( 'admin.php?post_type=rew&page=gamify-rewards.php' ) ); ?>"/>
		<input type="hidden" name="rew-item-nonce" value="<?php echo wp_create_nonce( 'rew_item_nonce' ); ?>"/>
		<input type="submit" value="<?php _e( 'Update Action', 'gamwp' ); ?>" class="button-primary"/>
	</p>
</form>
