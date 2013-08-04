<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! isset( $_GET['item_id'] ) || ! is_numeric( $_GET['item_id'] ) ) {
	wp_die( __( 'Error.', 'gamwp' ), __( 'Error', 'gamwp' ) );
}

$item_id  = absint( $_GET['item_id'] );
$item     = g_action_get_item( $item_id );
$action_hook     = get_post_meta( $item_id, '_g_action_item_action_hook', true );
$action_points     = get_post_meta( $item_id, '_g_action_item_action_points', true );
?>
<h2><?php _e( 'Edit Item', 'gamwp' ); ?> - <a href="<?php echo admin_url( 'admin.php?post_type=g_action&page=gamify-actions.php' ); ?>" class="button-secondary"><?php _e( 'Go Back', 'gamwp' ); ?></a></h2>
<form id="g_action-edit-tiem" action="" method="post">
	<table class="form-table">
		<tbody>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="g_action-name"><?php _e( 'Name', 'gamwp' ); ?></label>
				</th>
				<td>
					<input name="name" id="g_action-name" type="text" value="<?php echo esc_attr( $item->post_title ); ?>" style="width: 300px;"/>
					<p class="description"><?php _e( 'The name of this discount', 'gamwp' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="action_hook"><?php _e( 'Action Hook', 'gamwp' ); ?></label>
				</th>
				<td>
					<input name="action_hook" id="action_hook" type="text" value="<?php echo esc_attr( $action_hook ) ?>" style="width: 300px;"/>
					<p class="description"><?php _e( 'Enter a valid Action Hook', 'gamwp' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="action_points"><?php _e( 'Points', 'gamwp' ); ?></label>
				</th>
				<td>
					<input name="action_points" id="action_points" type="text" value="<?php echo esc_attr( $action_points ) ?>" style="width: 300px;"/>
					<p class="description"><?php _e( 'Enter a Points value', 'gamwp' ); ?></p>
				</td>
			</tr>


		</tbody>
	</table>
	<p class="submit">
		<input type="hidden" name="g_action-action" value="edit_item"/>
		<input type="hidden" name="item_id" value="<?php echo absint( $_GET['item_id'] ); ?>"/>
		<input type="hidden" name="g_action-redirect" value="<?php echo esc_url( admin_url( 'admin.php?post_type=g_action&page=gamify-actions.php' ) ); ?>"/>
		<input type="hidden" name="g_action-item-nonce" value="<?php echo wp_create_nonce( 'g_action_item_nonce' ); ?>"/>
		<input type="submit" value="<?php _e( 'Update Action', 'gamwp' ); ?>" class="button-primary"/>
	</p>
</form>
