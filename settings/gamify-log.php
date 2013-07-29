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

		echo "<td>" . gmdate("M d Y H:i:s", $activity_time ) . " GMT</td>";

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

	<div id="gamwp-settings-wrap" class="wrap">
		<div class="icon32" id="icon-options-general">
			<br />
		</div>
		<?php _e( '<h2>Gamify WP Activity Log</h2>', 'gamwp'); ?>
		<?php if( isset($_GET['settings-updated']) ) { ?>
			<div id="message" class="updated">
				<p><?php _e('Settings saved.') ?></p>
			</div>
		<?php } ?>
		<p><a href="#">WIKI LINK</a>.</p>
			<table class="wp-list-table widefat fixed posts" id="gamwp-log-table">
				<thead>
					<tr>
						<th><?php _e('Time', 'gamwp'); ?></th>
						<th><?php _e('User', 'gamwp'); ?></th>
						<th><?php _e('Activity', 'gamwp'); ?></th>
						<th><?php _e('Points Earned/Spent', 'gamwp'); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
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
