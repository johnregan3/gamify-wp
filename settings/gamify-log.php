<?php

/*
 *
 * Gamify WP General Settings
 *
 */


//Add To Menu
add_action( 'admin_menu', 'gamwp_add_log_submenu_page' );

function gamwp_add_log_submenu_page() {
	add_submenu_page( 'gamify-general.php', __( 'Gamify Activity Log' ), __( 'Activity Log' ), 'administrator', basename(__FILE__), 'gamwp_points_log' );
}

function gamwp_log_register_settings() {
	register_setting('gamwp_master_log_group', 'gamwp_master_log');
}

add_action('admin_init', 'gamwp_log_register_settings');

function generate_log_fields() {

	$options = get_option('gamwp_master_log');
	$options = array_reverse( $options, true );

	foreach ( $options as $activity_time => $field_array ) {
		ob_start();

		$settings_value =( isset( $options[$activity_time]['delete'] ) ? $options[$activity_time]['delete'] : 'unchecked' );
		echo "<td style='width:5%' ><input type='checkbox' id='gamwp_master_log[" . $activity_time . "][delete]' name='gamwp_master_log[" . $activity_time . "][delete]' value='checked' " . checked( 'checked', isset( $settings_value ) ? $settings_value : 'unchecked', false ) . " /></td>";

		echo "<td>" . $activity_time . " GMT</td>";

		$settings_value =( isset( $options[$activity_time]['userid'] ) ? $options[$activity_time]['userid'] : '' );
		echo "<td><span>";
			$user = get_userdata( $settings_value );
		echo $user->user_login;
		echo "</span></td>";

		$settings_value =( isset( $options[$activity_time]['activity_title'] ) ? $options[$activity_time]['activity_title'] : '' );
		echo "<td><span>" . $settings_value . "</span></td>";

		$settings_value =( isset( $options[$activity_time]['activity_points'] ) ? $options[$activity_time]['activity_points'] : '' );
		echo "<td><span>" . $settings_value . "</span>";

		if ( isset( $options[$activity_time]['delete'] ) && ( 'checked' == $options[$activity_time]['delete'] ) ) {
			$ca_rows[$activity_time] = '';
		} else {
			$ca_rows[$activity_time] = ob_get_contents();
		}

		ob_end_clean();
	}
	return $ca_rows;
}


function gamwp_points_log() {
	?>


<!-- Need to put this in a separate file -->

<script>
	function displayResult() {
	var settingsValue = '';
	var date = new Date();
	date.toUTCString();
	var newDate = Math.floor(date.getTime()/ 1000)

	//newDate NEEDS TO = CURRENT TIME

	var table=document.getElementById("gamwp-log-table");
	var row=table.insertRow(1);
	var cell1=row.insertCell(0);
	var cell2=row.insertCell(1);
	var cell3=row.insertCell(2);
	var cell4=row.insertCell(3);
	var cell5=row.insertCell(4);
	cell1.innerHTML="<td><input type='checkbox' id='gamwp_master_log[" + newDate + "][delete]' name='gamwp_master_log[" + newDate + "][delete]' value='checked' /></td>";
	cell2.innerHTML="<td><input type='hidden' id='gamwp_master_log[" + newDate + "]' name='gamwp_master_log[" + newDate + "]' value='checked' />Now</td>";
	cell3.innerHTML="<td><input type='text' id='gamwp_master_log[" + newDate + "][userid]' name='gamwp_master_log[" + newDate + "][userid]' value='"+ settingsValue + "' title='"+ settingsValue + "' placeholder='Username' /></td>";
	cell4.innerHTML="<td><input type='text' id='gamwp_master_log[" + newDate + "][activity_title]' name='gamwp_master_log[" + newDate + "][activity_title]' value='"+ settingsValue + "' title='"+ settingsValue + "' placeholder='Activity Title' /></td>";
	cell5.innerHTML="<td><input type='text' id='gamwp_master_log[" + newDate + "][activity_points]' name='gamwp_master_log[" + newDate + "][activity_points]' value='"+ settingsValue + "' title='"+ settingsValue + "' placeholder='Points' /></td>";
	}
	</script>


	<div id="gamwp-settings-wrap" class="wrap">
		<div class="icon32" id="icon-options-general">
			<br />
		</div>
		<?php _e( '<h2>Gamify WP Activity Log</h2>', 'gamwp'); ?>
		<?php if( isset($_GET['settings-updated']) ) { ?>
			<div id="message" class="updated fade">
				<p><?php _e('Settings saved.') ?></p>
			</div>
		<?php } ?>
		<p><a href="#">WIKI LINK</a>.</p>
			<form method="post" action="options.php" enctype="multipart/form-data">
				<?php settings_fields('gamwp_master_log_group'); ?>
			<div class="tablenav top">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'gamwp' ); ?>" />
				<input type="button" class="button" onclick="displayResult()" value="<?php esc_attr_e( 'Add New Activity', 'gamwp' ); ?>">
			</div>
			<table class="wp-list-table widefat fixed posts" id="gamwp-log-table">
				<thead>
					<tr>
						<th style='width:5%'><?php _e('Delete', 'gamwp'); ?></th>
						<th><?php _e('Time', 'gamwp'); ?></th>
						<th><?php _e('User', 'gamwp'); ?></th>
						<th><?php _e('Activity', 'gamwp'); ?></th>
						<th><?php _e('Points Earned/Spent', 'gamwp'); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th style='width:5%'><?php _e('Delete', 'gamwp'); ?></th>
						<th><?php _e('Time', 'gamwp'); ?></th>
						<th><?php _e('User', 'gamwp'); ?></th>
						<th><?php _e('Activity', 'gamwp'); ?></th>
						<th><?php _e('Points Earned/Spent', 'gamwp'); ?></th>
					</tr>
				</tfoot>
				<tbody>
						<?php $rows = generate_log_fields();
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
				<input type="button" class="button" onclick="displayResult()" value="<?php esc_attr_e( 'Add New Activity', 'gamwp' ); ?>">
			</div>
		</form>
	</div>

<?php
}


function validate_gamwp_master_log( $input ) {
	$output = array();
	foreach( $input as $key => $value ) {
		if( isset( $input[$key] ) ) {
			$output[$key] = strip_tags( stripslashes( $input[$key] ) );
		}
	}
	return apply_filters( 'validate_gamwp_master_log', $output, $input );
}
