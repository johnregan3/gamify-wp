<?php

/*
 *
 * Gamify WP General Settings
 *
 */


//Add To Menu
add_action( 'admin_menu', 'gamwp_add_submenu_page' );

function gamwp_add_submenu_page() {
	add_submenu_page( 'gamify-general.php', __( 'Gamify Actions' ), __( 'Actions' ), 'administrator', basename(__FILE__), 'gamwp_custom_actions' );
}

function gamwp_ca_register_settings() {
	register_setting('gamwp_ca_settings_group', 'gamwp_ca_settings');
}

add_action('admin_init', 'gamwp_ca_register_settings');

function generate_ca_fields() {

	$options = get_option('gamwp_ca_settings');
	if ( empty( $options ) ) {
		$options = array(
			"1" => array(
				"action_title" => "Commented On A Post",
				"action_hook"  => "comment_post",
				"action_points" => 10,
				"daily_limit"  => 'checked',
				"once"         => 'unchecked',
			),
			"2" => array(
				"action_title" => "Registered",
				"action_hook"  => "user_register",
				"action_points" => 150,
				"daily_limit"  => 'checked',
				"once"         => 'unchecked',
			),
			"3" => array(
				"action_title" => "Published A Post",
				"action_hook"  => "draft_to_publish",
				"action_points" => 30,
				"daily_limit"  => 'checked',
				"once"         => 'unchecked',
			),
		);
	}

	foreach ( $options as $action_id => $field_array ) {
		ob_start();

		$settings_value =(isset( $options[$action_id]['delete'] ) ? $options[$action_id]['delete'] : 'unchecked');
		echo "<td style='width:5%' ><input type='checkbox' id='gamwp_ca_settings[" . $action_id . "][delete]' name='gamwp_ca_settings[" . $action_id . "][delete]' value='checked' " . checked( 'checked', isset( $settings_value ) ? $settings_value : 'unchecked', false ) . " /></td>";

		$settings_value =(isset( $options[$action_id]['action_title'] ) ? $options[$action_id]['action_title'] : '');
		echo "<td><input type='text' id='gamwp_ca_settings[" . $action_id . "][action_title]' name='gamwp_ca_settings[" . $action_id . "][action_title]' value='" . esc_html( $settings_value ) . "' title='" . esc_html( $settings_value ) . "'placeholder='Action Title' /></td>";

		$settings_value =(isset( $options[$action_id]['action_hook'] ) ? $options[$action_id]['action_hook'] : '');
		echo "<td><input type='text' id='gamwp_ca_settings[" . $action_id . "][action_hook]' name='gamwp_ca_settings[" . $action_id . "][action_hook]' value='" . esc_html( $settings_value ) . "' title='" . esc_html( $settings_value ) . "' placeholder='Action Hook' /></td>";

		$settings_value =(isset( $options[$action_id]['action_points'] ) ? $options[$action_id]['action_points'] : '');
		echo "<td><input type='text' id='gamwp_ca_settings[" . $action_id . "][action_points]' name='gamwp_ca_settings[" . $action_id . "][action_points]' value='" . esc_html( $settings_value ) . "' placeholder='Points' /></td>";

		$settings_value =(isset( $options[$action_id]['daily_limit'] ) ? $options[$action_id]['daily_limit'] : 'unchecked');
		echo "<td><input type='checkbox' id='gamwp_ca_settings[" . $action_id . "][daily_limit]' name='gamwp_ca_settings[" . $action_id . "][daily_limit]' value='checked' " . checked( 'checked', isset( $settings_value ) ? $settings_value : 'unchecked', false ) ." /></td>";

		$settings_value =(isset( $options[$action_id]['once'] ) ? $options[$action_id]['once'] : 'unchecked');
		echo "<td><input type='checkbox' id='gamwp_ca_settings[" . $action_id . "][once]' name='gamwp_ca_settings[" . $action_id . "][once]' value='checked' " . checked( 'checked', isset( $settings_value ) ? $settings_value : 'unchecked', false ) ." /></td>";

		if ( isset( $options[$action_id]['delete'] ) && ( 'checked' == $options[$action_id]['delete'] ) ) {
			$ca_rows[$action_id] = '';
		} else {
			$ca_rows[$action_id] = ob_get_contents();
		}

	ob_end_clean();
	}
	return $ca_rows;
}


function gamwp_custom_actions() {
	?>
<script>
	function displayResult() {
	var settingsValue = '';
	var actionId = Math.floor(Math.random()*9999);
	var table=document.getElementById("gamwp-ca-table");
	var row=table.insertRow(1);
	var cell1=row.insertCell(0);
	var cell2=row.insertCell(1);
	var cell3=row.insertCell(2);
	var cell4=row.insertCell(3);
	var cell5=row.insertCell(4);
	var cell6=row.insertCell(5);
	cell1.innerHTML="<td><input type='checkbox' id='gamwp_ca_settings[" + actionId + "][once]' name='gamwp_ca_settings[" + actionId + "][once]' value='checked' /></td>";
	cell2.innerHTML="<td><input type='text' id='gamwp_ca_settings[" + actionId + "][action_title]' name='gamwp_ca_settings[" + actionId + "][action_title]' value='"+ settingsValue + "' title='"+ settingsValue + "' placeholder='Action Title' /></td>";
	cell3.innerHTML="<td><input type='text' id='gamwp_ca_settings[" + actionId + "][action_hook]' name='gamwp_ca_settings[" + actionId + "][action_hook]' value='"+ settingsValue + "' title='"+ settingsValue + "' placeholder='Action Hook' /></td>";
	cell4.innerHTML="<td><input type='text' id='gamwp_ca_settings[" + actionId + "][action_points]' name='gamwp_ca_settings[" + actionId + "][action_points]' value='"+ settingsValue + "' title='"+ settingsValue + "' placeholder='Points' /></td>";
	cell5.innerHTML="<td><input type='checkbox' id='gamwp_ca_settings[" + actionId + "][daily_limit]' name='gamwp_ca_settings[" + actionId + "][daily_limit]' value='checked' /></td>";
	cell6.innerHTML="<td><input type='checkbox' id='gamwp_ca_settings[" + actionId + "][once]' name='gamwp_ca_settings[" + actionId + "][once]' value='checked' /></td>";
	}
	</script>


	<div id="gamwp-settings-wrap" class="wrap">
		<div class="icon32" id="icon-options-general">
			<br />
		</div>
		<?php _e( '<h2>Gamify WP Actions</h2>', 'gamwp'); ?>
		<p><a href="">WIKI LINK</a>.</p>


			<form method="post" action="options.php" enctype="multipart/form-data">
				<?php settings_fields('gamwp_ca_settings_group'); ?>
			<div class="tablenav top">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'gamwp' ); ?>" />
				<input type="button" class="button" onclick="displayResult()" value="<?php esc_attr_e( 'Add New Action', 'gamwp' ); ?>">
			</div>
			<table class="wp-list-table widefat fixed posts" id="gamwp-ca-table">
				<thead>
					<tr>
						<th style='width:5%'><?php _e('Delete', 'gamwp'); ?></th>
						<th><?php _e('Action Title', 'gamwp'); ?></th>
						<th><?php _e('Action Hook', 'gamwp'); ?></th>
						<th><?php _e('Points', 'gamwp'); ?></th>
						<th><?php _e('Daily Limit', 'gamwp'); ?></th>
						<th><?php _e('Use Once', 'gamwp'); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th style='width:5%'><?php _e('Delete', 'gamwp'); ?></th>
						<th><?php _e('Action Title', 'gamwp'); ?></th>
						<th><?php _e('Action Hook', 'gamwp'); ?></th>
						<th><?php _e('Points', 'gamwp'); ?></th>
						<th><?php _e('Daily Limit', 'gamwp'); ?></th>
						<th><?php _e('Use Once', 'gamwp'); ?></th>
					</tr>
				</tfoot>
				<tbody>
						<?php $rows = generate_ca_fields();
						foreach ( $rows as $row ) {
							?>
							<tr>
								<?php echo $row ?>
							</tr>
						<?php } ?>
				</tbody>
			</table>
			<div class="tablenav bottom">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'gamwp' ); ?>" />
				<input type="button" class="button" onclick="displayResult()" value="<?php esc_attr_e( 'Add New Action', 'gamwp' ); ?>">
			</div>
		</form>
	</div>

<?php
}


function validate_gamwp_ca_settings( $input ) {
	$output = array();
	foreach( $input as $key => $value ) {
		if( isset( $input[$key] ) ) {
			$output[$key] = strip_tags( stripslashes( $input[$key] ) );
		}
	}
	return apply_filters( 'validate_gamwp_ca_settings', $output, $input );
}
