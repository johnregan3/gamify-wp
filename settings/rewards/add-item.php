<?php

 /**
  * Content of the Add Action Page
  *
  * @since 1.0
  */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<h2><?php _e( 'Add New Reward', 'gamwp' ); ?> - <a href="<?php echo admin_url( 'admin.php?page=gamify-rewards.php&post_type=rew' ); ?>" class="button-secondary"><?php _e( 'Go Back', 'gamwp' ); ?></a></h2>
<form id="rew-add-item" action="" method="POST">
	<table class="form-table">
		<tbody>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="rew-name"><?php _e( 'Item Name', 'gamwp' ); ?></label>
				</th>
				<td>
					<input name="name" id="rew-name" type="text" value="" style="width: 300px;"/>
					<p class="description"><?php _e( 'The name of this Action', 'yyy' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="rew_points"><?php _e( 'Points', 'gamwp' ); ?></label>
				</th>
				<td>
					<input type="text" id="rew_points" name="rew_points" value="" style="width: 300px;"/>
					<p class="description"><?php _e( 'Enter a Points value', 'gamwp' ); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<input type="hidden" name="activity" value="reward">
		<input type="hidden" name="rew-action" value="add_item"/>
		<input type="hidden" name="rew-redirect" value="<?php echo esc_url( admin_url( 'admin.php?page=gamify-rewards.php' ) ); ?>"/>
		<input type="hidden" name="rew-item-nonce" value="<?php echo wp_create_nonce( 'rew_item_nonce' ); ?>"/>
		<input type="submit" value="<?php _e( 'Add New Action', 'gamwp' ); ?>" class="button-primary"/>
	</p>
</form>