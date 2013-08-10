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
$item     = gact_get_item( $item_id );
$action_hook     = get_post_meta( $item_id, '_gamify_item_action_hook', true );
$activity_points     = get_post_meta( $item_id, '_gamify_item_activity_points', true );

?>
<h2><?php _e( 'Edit Action', 'gamify' ); ?> - <a href="<?php echo admin_url( 'admin.php?post_type=gact&page=gamify-actions.php' ); ?>" class="button-secondary"><?php _e( 'Go Back', 'gamify' ); ?></a></h2>
<form id="gact-edit-tiem" action="" method="post">
	<table class="form-table">
		<tbody>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="gact-name"><?php _e( 'Name', 'gamify' ); ?></label>
				</th>
				<td>
					<input name="name" id="gact-name" type="text" value="<?php echo esc_attr( $item->post_title ); ?>" style="width: 300px;"/>
					<p class="description"><?php _e( 'The name of this Action.', 'gamify' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="action_hook"><?php _e( 'Action Hook', 'gamify' ); ?></label>
				</th>
				<td>
					<input name="action_hook" id="action_hook" type="text" value="<?php echo esc_attr( $action_hook ) ?>" style="width: 300px;"/>
					<p class="description"><?php _e( 'Enter a valid Action Hook.', 'gamify' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="activity_points"><?php _e( 'Points', 'gamify' ); ?></label>
				</th>
				<td>
					<input name="activity_points" id="activity_points" type="text" value="<?php echo esc_attr( $activity_points ) ?>" style="width: 300px;"/>
					<p class="description"><?php _e( 'Enter a Points value.', 'gamify' ); ?></p>
				</td>
			</tr>


		</tbody>
	</table>
	<p class="submit">
		<input type="hidden" name="gact-action" value="edit_item"/>
		<input type="hidden" name="item_id" value="<?php echo absint( $_GET['item_id'] ); ?>"/>
		<input type="hidden" name="gact-redirect" value="<?php echo esc_url( admin_url( 'admin.php?post_type=gact&page=gamify-actions.php' ) ); ?>"/>
		<input type="hidden" name="gact-item-nonce" value="<?php echo wp_create_nonce( 'gact_item_nonce' ); ?>"/>
		<input type="submit" value="<?php _e( 'Update Action', 'gamify' ); ?>" class="button-primary"/>
	</p>
</form>
