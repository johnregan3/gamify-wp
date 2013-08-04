<?php ?>

<h2><?php _e( 'Add New Item', 'gamwp' ); ?> - <a href="<?php echo admin_url( 'admin.php?page=gamify-actions.php&post_type=g_action' ); ?>" class="button-secondary"><?php _e( 'Go Back', 'gamwp' ); ?></a></h2>
<form id="acpt-add-item" action="" method="POST">
	<table class="form-table">
		<tbody>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="acpt-name"><?php _e( 'Item Name', 'gamwp' ); ?></label>
				</th>
				<td>
					<input name="name" id="acpt-name" type="text" value="" style="width: 300px;"/>
					<p class="description"><?php _e( 'The name of this Action', 'yyy' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="field1"><?php _e( 'Field1', 'gamwp' ); ?></label>
				</th>
				<td>
					<input type="text" id="field1" name="field1" value="" style="width: 300px;"/>
					<p class="description"><?php _e( 'Enter a Action Hook', 'gamwp' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="field2"><?php _e( 'Field2', 'gamwp' ); ?></label>
				</th>
				<td>
					<input type="text" id="field2" name="field2" value="" style="width: 300px;"/>
					<p class="description"><?php _e( 'Enter a Points value', 'gamwp' ); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<input type="hidden" name="acpt-action" value="add_item"/>
		<input type="hidden" name="acpt-redirect" value="<?php echo esc_url( admin_url( 'admin.php?page=gamify-actions.php' ) ); ?>"/>
		<input type="hidden" name="acpt-item-nonce" value="<?php echo wp_create_nonce( 'acpt_item_nonce' ); ?>"/>
		<input type="submit" value="<?php _e( 'Add New Action', 'gamwp' ); ?>" class="button-primary"/>
	</p>
</form>