<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! isset( $_GET['item_id'] ) || ! is_numeric( $_GET['item_id'] ) ) {
	wp_die( __( 'Error.', 'gamwp' ), __( 'Error', 'gamwp' ) );
}

$item_id  = absint( $_GET['item_id'] );
$item     = acpt_get_item( $item_id );
$field1     = get_post_meta( $item_id, '_acpt_item_field1', true );
$field2     = get_post_meta( $item_id, '_acpt_item_field2', true );
?>
<h2><?php _e( 'Edit Item', 'gamwp' ); ?> - <a href="<?php echo admin_url( 'admin.php?post_type=g_action&page=gamify-actions.php' ); ?>" class="button-secondary"><?php _e( 'Go Back', 'gamwp' ); ?></a></h2>
<form id="acpt-edit-tiem" action="" method="post">
	<table class="form-table">
		<tbody>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="acpt-name"><?php _e( 'Name', 'gamwp' ); ?></label>
				</th>
				<td>
					<input name="name" id="acpt-name" type="text" value="<?php echo esc_attr( $item->post_title ); ?>" style="width: 300px;"/>
					<p class="description"><?php _e( 'The name of this discount', 'gamwp' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="field1"><?php _e( 'Field1', 'gamwp' ); ?></label>
				</th>
				<td>
					<input name="field1" id="field1" type="text" value="<?php echo esc_attr( $field1 ) ?>" style="width: 300px;"/>
					<p class="description"><?php _e( 'Field 1 Description', 'gamwp' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="field1"><?php _e( 'Field2', 'gamwp' ); ?></label>
				</th>
				<td>
					<input name="field2" id="field2" type="text" value="<?php echo esc_attr( $field2 ) ?>" style="width: 300px;"/>
					<p class="description"><?php _e( 'Field 2 Description', 'gamwp' ); ?></p>
				</td>
			</tr>


		</tbody>
	</table>
	<p class="submit">
		<input type="hidden" name="acpt-action" value="edit_item"/>
		<input type="hidden" name="item_id" value="<?php echo absint( $_GET['item_id'] ); ?>"/>
		<input type="hidden" name="acpt-redirect" value="<?php echo esc_url( admin_url( 'admin.php?post_type=g_action&page=gamify-actions.php' ) ); ?>"/>
		<input type="hidden" name="acpt-item-nonce" value="<?php echo wp_create_nonce( 'acpt_item_nonce' ); ?>"/>
		<input type="submit" value="<?php _e( 'Update Action', 'gamwp' ); ?>" class="button-primary"/>
	</p>
</form>
