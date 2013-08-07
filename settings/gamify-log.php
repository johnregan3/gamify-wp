<?php

/*
 *
 * Gamify WP General Settings
 *
 */


//Add To Menu
add_action( 'admin_menu', 'gamwp_add_log_submenu_page' );

function gamwp_add_log_submenu_page() {
	add_submenu_page( 'gamify-actions.php', __( 'Gamify WP Activity Log' ), __( 'Activity Log' ), 'administrator', basename(__FILE__), 'gamwp_points_log' );
}

function gamwp_log_register_settings() {
	register_setting('gamwp_master_log_group', 'gamwp_master_log');
}

add_action('admin_init', 'gamwp_log_register_settings');

function generate_log_fields() {

	$options = get_option('gamwp_master_log');
	$options = is_array( $options ) ? $options : array() ;
	$options = array_reverse( $options, true );
	$activity_rows = array();

	foreach ( $options as $activity_time => $field_array ) {

		$activity_rows[$activity_time] = array();

		ob_start();


		$tz = get_option('timezone_string');
		if ( $tz ) {
			$prev_tz = date_default_timezone_get();
			date_default_timezone_set( $tz );
			echo "<td>" . date("M d Y H:i:s", $activity_time ) . "</td>";
			//reset the timezone to original settings so it doesn't mess with other function settings.
			date_default_timezone_set($prev_tz);
		} else {
			echo "<td>" . date("M d Y H:i:s", $activity_time ) . "</td>";
		}


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
			$activity_rows[$activity_time] = '';
		} else {
			$activity_rows[$activity_time] = ob_get_contents();
		}

		ob_end_clean();
	}
	return $activity_rows;
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
						<th><?php _e('Points Earned', 'gamwp'); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th><?php _e('Time', 'gamwp'); ?></th>
						<th><?php _e('User', 'gamwp'); ?></th>
						<th><?php _e('Activity', 'gamwp'); ?></th>
						<th><?php _e('Points Earned', 'gamwp'); ?></th>
					</tr>
				</tfoot>
				<tbody>
						<?php $rows = generate_log_fields();
							if ( $rows ) :
								foreach ( $rows as $row ) : ?>
									<tr>
										<?php echo $row ?>
									</tr>
								<?php endforeach;
							else : ?>
								<tr>
									<td>
										<?php _e( 'No Log activity yet', 'gamwp' ); ?>
									</td>
								</tr>
							<?php endif; ?>
				</tbody>
			</table>
	</div>

<?php
}
