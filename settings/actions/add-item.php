<?php

 /**
  * Content of the Add Action Page
  *
  * @since 1.0
  */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<h2><?php _e( 'Add New Action', 'gamify' ); ?> - <a href="<?php echo admin_url( 'admin.php?page=gamify-actions.php&post_type=gact' ); ?>" class="button-secondary"><?php _e( 'Go Back', 'gamify' ); ?></a></h2>
<form id="gact-add-item" action="" method="POST">
	<table class="form-table">
		<tbody>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="gact-name"><?php _e( 'Item Name', 'gamify' ); ?></label>
				</th>
				<td>
					<input name="name" id="gact-name" type="text" value="" style="width: 300px;"/>
					<p class="description"><?php _e( 'The name of this Action.', 'yyy' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="action_hook"><?php _e( 'Action Hook', 'gamify' ); ?></label>
				</th>
				<td>
					<input type="text" id="action_hook" name="action_hook" value="" style="width: 300px;"/>
					<p class="description"><?php _e( 'Enter a valid Action Hook.', 'gamify' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="activity_points"><?php _e( 'Points', 'gamify' ); ?></label>
				</th>
				<td>
					<input type="text" id="activity_points" name="activity_points" value="" style="width: 300px;"/>
					<p class="description"><?php _e( 'Enter a Points value.', 'gamify' ); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<input type="hidden" name="activity_title" value="action">
		<input type="hidden" name="gact-action" value="add_item"/>
		<input type="hidden" name="gact-redirect" value="<?php echo esc_url( admin_url( 'admin.php?page=gamify-actions.php' ) ); ?>"/>
		<input type="hidden" name="gact-item-nonce" value="<?php echo wp_create_nonce( 'gact_item_nonce' ); ?>"/>
		<input type="submit" value="<?php _e( 'Add New Action', 'gamify' ); ?>" class="button-primary"/>
	</p>
</form>