<?php

/*
 *
 * Gamify WP General Settings
 *
 */


//Add To Menu
add_action( 'admin_menu', 'gamwp_add_rew_submenu_page' );

function gamwp_add_rew_submenu_page() {
	add_submenu_page( 'gamify-general.php', __( 'Gamify Rewards' ), __( 'Rewards' ), 'administrator', basename(__FILE__), 'gamwp_rewards' );
}

function gamwp_rew_register_settings() {
	register_setting('gamwp_rew_settings_group', 'gamwp_rew_settings');
}

add_action('admin_init', 'gamwp_rew_register_settings');

function generate_rew_fields() {

	$options = get_option('gamwp_rew_settings');
	if ( empty( $options ) ) {
		$options = array(
			"1" => array(
				"reward_name"   => "Commented On A Post",
				"reward_type"   => "Badge",
				"reward_points" => 10,
				"image"         => '',
			),
			"2" => array(
				"reward_title"  => "Registered",
				"reward_type"   => "Level",
				"reward_points" => 11,
				"image"         => '',
			),
			"3" => array(
				"reward_title"  => "Registered",
				"reward_type"   => "Purchase",
				"reward_points" => 10,
				"image"         => '',
			),
		);
	}

	foreach ( $options as $reward_id => $field_array ) {
		ob_start();

		$settings_value =( isset( $options[$reward_id]['delete'] ) ? $options[$reward_id]['delete'] : 'unchecked' );
		echo "<td style='width:5%' ><input type='checkbox' id='gamwp_rew_settings[" . $reward_id . "][delete]' name='gamwp_rew_settings[" . $reward_id . "][delete]' value='checked' " . checked( 'checked', isset( $settings_value ) ? $settings_value : 'unchecked', false ) . " /></td>";

		$settings_value =( isset( $options[$reward_id]['reward_title'] ) ? $options[$reward_id]['reward_title'] : '' );
		echo "<td><input type='text' id='gamwp_rew_settings[" . $reward_id . "][reward_title]' name='gamwp_rew_settings[" . $reward_id . "][reward_title]' value='" . esc_html( $settings_value ) . "' title='" . esc_html( $settings_value ) . "'placeholder='Reward Title' /></td>";

		$settings_value =( isset( $options[$reward_id]['reward_type'] ) ? $options[$reward_id]['reward_type'] : '' );
		echo "<td><select id='gamwp_rew_settings[" . $reward_id . "][reward_type]' name='gamwp_rew_settings[" . $reward_id . "][reward_type]'>";
			echo "<option " . selected( $settings_value, 'Badge' ) . ">Badge</option>";
			echo "<option " . selected( $settings_value, 'Level' ) . ">Level</option>";
			echo "<option " . selected( $settings_value, 'Purchase' ) . ">Purchase</option>";
		echo "</select></td>";

		$settings_value =( isset( $options[$reward_id]['reward_points'] ) ? $options[$reward_id]['reward_points'] : '' );
		echo "<td><input type='text' id='gamwp_rew_settings[" . $reward_id . "][reward_points]' name='gamwp_rew_settings[" . $reward_id . "][reward_points]' value='" . esc_html( $settings_value ) . "' placeholder='Points' /></td>";

		if ( ( isset( $options[$reward_id]['delete'] ) ) && ( 'checked' == $options[$reward_id]['delete'] ) ) {
			$ca_rows[$reward_id] = '';
		} else {
			$ca_rows[$reward_id] = ob_get_contents();
		}

		ob_end_clean();
	}
	return $ca_rows;
}


function gamwp_rewards() {
	?>
<script>
	function displayResult() {
	var settingsValue = '';
	var actionId = Math.floor(Math.random()*9999);
	var table=document.getElementById("gamwp-rew-table");
	var row=table.insertRow(1);
	var cell1=row.insertCell(0);
	var cell2=row.insertCell(1);
	var cell3=row.insertCell(2);
	var cell4=row.insertCell(3);
	cell1.innerHTML="<td><input type='checkbox' id='gamwp_rew_settings[" + actionId + "][once]' name='gamwp_rew_settings[" + actionId + "][once]' value='checked' /></td>";
	cell2.innerHTML="<td><input type='text' id='gamwp_rew_settings[" + actionId + "][reward_title]' name='gamwp_rew_settings[" + actionId + "][reward_title]' value='"+ settingsValue + "' title='"+ settingsValue + "' placeholder='Reward Title' /></td>";
	cell3.innerHTML="<td><select id='gamwp_rew_settings[" + actionId + "][reward_type]' name='gamwp_rew_settings[" + actionId + "][reward_type]'><option>Badge</option><option>Level</option><option>Purchase</option></select></td>";
	cell4.innerHTML="<td><input type='text' id='gamwp_rew_settings[" + actionId + "][reward_points]' name='gamwp_rew_settings[" + actionId + "][reward_points]' value='"+ settingsValue + "' title='"+ settingsValue + "' placeholder='Points' /></td>";
	}
	</script>


	<div id="gamwp-settings-wrap" class="wrap">
		<div class="icon32" id="icon-options-general">
			<br />
		</div>
		<?php _e( '<h2>Gamify WP Rewards</h2>', 'gamwp'); ?>
		<?php if( isset($_GET['settings-updated']) ) { ?>
			<div id="message" class="updated fade">
				<p><?php _e('Settings saved.') ?></p>
			</div>
		<?php } ?>
		<p><a href="#">WIKI LINK</a>.</p>


			<form method="post" action="options.php" enctype="multipart/form-data">
				<?php settings_fields('gamwp_rew_settings_group'); ?>
			<div class="tablenav top">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'gamwp' ); ?>" />
				<input type="button" class="button" onclick="displayResult()" value="<?php esc_attr_e( 'Add New Reward', 'gamwp' ); ?>">
			</div>
			<table class="wp-list-table widefat fixed posts" id="gamwp-rew-table">
				<thead>
					<tr>
						<th style='width:5%'><?php _e('Delete', 'gamwp'); ?></th>
						<th><?php _e('Reward Title', 'gamwp'); ?></th>
						<th><?php _e('Reward Type', 'gamwp'); ?></th>
						<th><?php _e('Points', 'gamwp'); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th style='width:5%'><?php _e('Delete', 'gamwp'); ?></th>
						<th><?php _e('Reward Title', 'gamwp'); ?></th>
						<th><?php _e('Reward Type', 'gamwp'); ?></th>
						<th><?php _e('Points', 'gamwp'); ?></th>
					</tr>
				</tfoot>
				<tbody>
						<?php $rows = generate_rew_fields();
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
				<input type="button" class="button" onclick="displayResult()" value="<?php esc_attr_e( 'Add New Reward', 'gamwp' ); ?>">
			</div>
		</form>
	</div>

<?php
}


function validate_gamwp_rew_settings( $input ) {
	$output = array();
	foreach( $input as $key => $value ) {
		if( isset( $input[$key] ) ) {
			$output[$key] = strip_tags( stripslashes( $input[$key] ) );
		}
	}
	return apply_filters( 'validate_gamwp_rew_settings', $output, $input );
}
