<?php

/*
 *
 * Gamify WP General Settings
 *
 */


//Add To Menu
add_action( 'admin_menu', 'gamwp_add_rew_submenu_page' );

function gamwp_add_rew_submenu_page() {
	add_submenu_page( 'gamify-actions.php', __( 'Gamify Rewards' ), __( 'Rewards' ), 'administrator', basename(__FILE__), 'gamwp_rewards' );
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
				"reward_title"   => "n00b",
				"reward_type"   => "Level",
				"reward_points" => 100,
			),
			"2" => array(
				"reward_title"  => "Apprentice",
				"reward_type"   => "Level",
				"reward_points" => 500,
			),
			"3" => array(
				"reward_title"  => "Master",
				"reward_type"   => "Level",
				"reward_points" => 10000,
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
			echo "<option " . selected( $settings_value, 'Level' ) . ">Level</option>";
		echo "</select></td>";

		$settings_value =( isset( $options[$reward_id]['reward_points'] ) ? $options[$reward_id]['reward_points'] : '' );
		echo "<td><input type='text' id='gamwp_rew_settings[" . $reward_id . "][reward_points]' name='gamwp_rew_settings[" . $reward_id . "][reward_points]' value='" . esc_html( $settings_value ) . "' placeholder='Points' /></td>";

		if ( ( isset( $options[$reward_id]['delete'] ) ) && ( 'checked' == $options[$reward_id]['delete'] ) ) {
			unset( $reward_rows[$reward_id] );
		} else {
			$reward_rows[$reward_id] = ob_get_contents();
		}

		ob_end_clean();
	}
	return $reward_rows;
}


function gamwp_rewards() {
	?>
<script>

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
				<input type="button" class="button" onclick="addRewardRows()" value="<?php esc_attr_e( 'Add New Reward', 'gamwp' ); ?>">
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
