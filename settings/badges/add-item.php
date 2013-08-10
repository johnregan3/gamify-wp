<?php

 /**
  * Content of the Add Action Page
  *
  * @since 1.0
  */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<h2><?php _e( 'Add New Reward', 'gamify' ); ?> - <a href="<?php echo admin_url( 'admin.php?page=gamify-badges.php&post_type=rew' ); ?>" class="button-secondary"><?php _e( 'Go Back', 'gamify' ); ?></a></h2>
<form id="rew-add-item" action="" method="POST">
	<table class="form-table">
		<tbody>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="rew-name"><?php _e( 'Item Name', 'gamify' ); ?></label>
				</th>
				<td>
					<input name="name" id="rew-name" type="text" value="" style="width: 300px;"/>
					<p class="description"><?php _e( 'The name of this Reward.', 'yyy' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="activity_points"><?php _e( 'Points', 'gamify' ); ?></label>
				</th>
				<td>
					<input type="text" id="activity_points" name="activity_points" value="" style="width: 300px;"/>
					<p class="description"><?php _e( 'Enter a point value Users must earn to gain this reward.', 'gamify' ); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<input type="hidden" name="activity_title" value="badge">
		<input type="hidden" name="rew-action" value="add_item"/>
		<input type="hidden" name="rew-redirect" value="<?php echo esc_url( admin_url( 'admin.php?page=gamify-badges.php' ) ); ?>"/>
		<input type="hidden" name="rew-item-nonce" value="<?php echo wp_create_nonce( 'badges_item_nonce' ); ?>"/>
		<input type="submit" value="<?php _e( 'Add New Reward', 'gamify' ); ?>" class="button-primary"/>
	</p>
</form>