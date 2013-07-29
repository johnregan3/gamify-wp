<?php

/*
 *
 * Gamify WP General Settings
 *
 */


//Add To Menu
add_action( 'admin_menu', 'gamwp_add_menu_pages' );

function gamwp_add_menu_pages() {
	add_menu_page( __( 'Gamify WP Actions' ), __( 'Gamify WP' ), 'administrator', basename(__FILE__), 'gamwp_custom_actions' );
}

function gamwp_action_register_settings() {
	register_setting('gamwp_action_settings_group', 'gamwp_action_settings');
}

add_action('admin_init', 'gamwp_action_register_settings');

function generate_action_fields() {

	$options = get_option('gamwp_action_settings');
	if ( empty( $options ) ) {
		$options = array(
			"1" => array(
				"activity_title" => "Commented On A Post",
				"action_hook"  => "comment_post",
				"activity_points" => 100,
				"daily_limit"  => 'unchecked',
				"once"         => 'unchecked',
			),
			"2" => array(
				"activity_title" => "Registered",
				"action_hook"  => "user_register",
				"activity_points" => 100,
				"daily_limit"  => 'unchecked',
				"once"         => 'unchecked',
			),
			"3" => array(
				"activity_title" => "Published A Post",
				"action_hook"  => "draft_to_publish",
				"activity_points" => 100,
				"daily_limit"  => 'unchecked',
				"once"         => 'unchecked',
			),
		);
	}

	foreach ( $options as $action_id => $field_array ) {
		ob_start();

		$settings_value =(isset( $options[$action_id]['delete'] ) ? $options[$action_id]['delete'] : 'unchecked');
		echo "<td style='width:5%' ><input type='checkbox' id='gamwp_action_settings[" . $action_id . "][delete]' name='gamwp_action_settings[" . $action_id . "][delete]' value='checked' " . checked( 'checked', isset( $settings_value ) ? $settings_value : 'unchecked', false ) . " /></td>";

		$settings_value =(isset( $options[$action_id]['activity_title'] ) ? $options[$action_id]['activity_title'] : '');
		echo "<td><input type='text' id='gamwp_action_settings[" . $action_id . "][activity_title]' name='gamwp_action_settings[" . $action_id . "][activity_title]' value='" . esc_html( $settings_value ) . "' title='" . esc_html( $settings_value ) . "'placeholder='Action Title' /></td>";

		$settings_value =(isset( $options[$action_id]['action_hook'] ) ? $options[$action_id]['action_hook'] : '');
		echo "<td><input type='text' id='gamwp_action_settings[" . $action_id . "][action_hook]' name='gamwp_action_settings[" . $action_id . "][action_hook]' value='" . esc_html( $settings_value ) . "' title='" . esc_html( $settings_value ) . "' placeholder='Action Hook' /></td>";

		$settings_value =(isset( $options[$action_id]['activity_points'] ) ? $options[$action_id]['activity_points'] : '');
		echo "<td><input type='text' id='gamwp_action_settings[" . $action_id . "][activity_points]' name='gamwp_action_settings[" . $action_id . "][activity_points]' value='" . esc_html( $settings_value ) . "' placeholder='Points' /></td>";

		$settings_value =(isset( $options[$action_id]['daily_limit'] ) ? $options[$action_id]['daily_limit'] : 'unchecked');
		echo "<td><input type='checkbox' id='gamwp_action_settings[" . $action_id . "][daily_limit]' name='gamwp_action_settings[" . $action_id . "][daily_limit]' value='checked' " . checked( 'checked', isset( $settings_value ) ? $settings_value : 'unchecked', false ) ." /></td>";

		$settings_value =(isset( $options[$action_id]['once'] ) ? $options[$action_id]['once'] : 'unchecked');
		echo "<td><input type='checkbox' id='gamwp_action_settings[" . $action_id . "][once]' name='gamwp_action_settings[" . $action_id . "][once]' value='checked' " . checked( 'checked', isset( $settings_value ) ? $settings_value : 'unchecked', false ) ." /></td>";

		if ( isset( $options[$action_id]['delete'] ) && ( 'checked' == $options[$action_id]['delete'] ) ) {
			unset( $action_rows[$action_id] );
		} else {
			$action_rows[$action_id] = ob_get_contents();
		}

	ob_end_clean();
	}
	return $action_rows;
}


function gamwp_custom_actions() {
	?>

	<div id="gamwp-settings-wrap" class="wrap">
		<div class="icon32" id="icon-options-general">
			<br />
		</div>
		<?php _e( '<h2>Gamify WP Actions</h2>', 'gamwp'); ?>
		<?php if( isset($_GET['settings-updated']) ) { ?>
			<div id="message" class="updated">
				<p><?php _e('Settings saved.') ?></p>
			</div>
		<?php } ?>
		<p><a href="">WIKI LINK</a>.</p>

			<form method="post" action="options.php" enctype="multipart/form-data">
				<?php settings_fields('gamwp_action_settings_group'); ?>
			<h3>Daily Points Limit</h3>
			<p>The Daily Limit ensures that easily completed Actions are not overused.  <br />Actions checked "Daily Limit" can be repeated until their combined points reach the Daily Points Limit.</p>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Enforce Daily Points Limit</th>
					<td>
						<?php
						$gamwp_action_settings = get_option('gamwp_action_settings');
						$settings_value = isset( $gamwp_action_settings['daily_limit_activate'] ) ? $gamwp_action_settings['daily_limit_activate'] : 0;
						echo "<input type='checkbox' id='gamwp_action_settings[daily_limit_activate]' name='gamwp_action_settings[daily_limit_activate]' value='1' " . checked( 1, $settings_value, false ) . "/>";
						?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Daily Points Limit</th>
					<td>
						<?php
						$settings_value= isset( $gamwp_action_settings['daily_limit'] ) ? $gamwp_action_settings['daily_limit'] : 0 ;
						echo "<input name='gamwp_action_settings[daily_limit]' type='text' value='" . esc_attr( $settings_value ) . "' />";
						?>
					</td>
				</tr>
			</table>
			<h3>Actions List</h3>
			<table class="wp-list-table widefat fixed posts" id="gamwp-action-table">
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
						<?php $rows = generate_action_fields();
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
				<input type="button" class="button" onclick="addActionRows()" value="<?php esc_attr_e( 'Add New Action', 'gamwp' ); ?>">
			</div>
		</form>
	</div>

	<?php
}


function validate_gamwp_action_settings( $input ) {
	$output = array();
	foreach( $input as $key => $value ) {
		if( isset( $input[$key] ) ) {
			$output[$key] = strip_tags( stripslashes( $input[$key] ) );
		}
	}
	return apply_filters( 'validate_gamwp_action_settings', $output, $input );
}
