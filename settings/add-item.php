<?php ?>

<h2><?php _e( 'Add New Action', 'gamwp' ); ?> - <a href="<?php echo admin_url( 'admin.php?page=gamify-actions.php&post_type=g_action' ); ?>" class="button-secondary"><?php _e( 'Go Back', 'gamwp' ); ?></a></h2>
<form id="g_action-add-item" action="" method="POST">
	<table class="form-table">
		<tbody>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="g_action-name"><?php _e( 'Item Name', 'gamwp' ); ?></label>
				</th>
				<td>
					<input name="name" id="g_action-name" type="text" value="" style="width: 300px;"/>
					<p class="description"><?php _e( 'The name of this Action', 'yyy' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="action_hook"><?php _e( 'Action Hook', 'gamwp' ); ?></label>
				</th>
				<td>
					<input type="text" id="action_hook" name="action_hook" value="" style="width: 300px;"/>
					<p class="description"><?php _e( 'Enter a valid Action Hook', 'gamwp' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="action_points"><?php _e( 'Points', 'gamwp' ); ?></label>
				</th>
				<td>
					<input type="text" id="action_points" name="action_points" value="" style="width: 300px;"/>
					<p class="description"><?php _e( 'Enter a Points value', 'gamwp' ); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<input type="hidden" name="g_action-action" value="add_item"/>
		<input type="hidden" name="g_action-redirect" value="<?php echo esc_url( admin_url( 'admin.php?page=gamify-actions.php' ) ); ?>"/>
		<input type="hidden" name="g_action-item-nonce" value="<?php echo wp_create_nonce( 'g_action_item_nonce' ); ?>"/>
		<input type="submit" value="<?php _e( 'Add New Action', 'gamwp' ); ?>" class="button-primary"/>
	</p>
</form>