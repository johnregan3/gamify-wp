<?php

/*
 *
 * Gamify WP General Settings
 *
 */


//Add To Menu
add_action( 'admin_menu', 'gamwp_add_menu_pages' );

function gamwp_add_menu_pages() {
	add_menu_page( __( 'Gamify WP Settings' ), __( 'Gamify WP' ), 'administrator', basename(__FILE__), 'gamwp_general_settings' );
}


function gamwp_render_fields() {
	register_setting( 'gamwp_settings', 'gamwp_settings', 'validate_gamwp_settings' );

	add_settings_section('daily_limit_section', __( 'Daily Points Limit', 'gamwp' ), 'daily_limit_section_cb', __FILE__ );
	add_settings_field( 'daily_limit_activate', __( 'Enforce Daily Points Limit', 'gamwp' ), 'daily_limit_activate', __FILE__, 'daily_limit_section' );
	add_settings_field( 'daily_limit', __( 'Daily Limit Amount', 'gamwp' ), 'daily_limit', __FILE__, 'daily_limit_section' );
}

add_action( 'admin_init', 'gamwp_render_fields' );



function gamwp_general_settings() {
	?>
	<div id="gamwp-settings-wrap" class="wrap">
		<div class="icon32" id="icon-options-general">
			<br />
		</div>
		<?php _e( '<h2>Gamify WP General Settings</h2>', 'gamwp'); ?>
		<?php if( isset($_GET['settings-updated']) ) { ?>
			<div id="message" class="updated">
				<p><?php _e('Settings saved.') ?></p>
			</div>
		<?php } ?>
		<p><a href="https://github.com/johnregan3/gamify-wp-plugin/wiki/General-Settings"><?php _e( 'Get help for this page on our Wiki', 'gamwp' ) ?></a>.</p>
		<form method="post" action="options.php" enctype="multipart/form-data">
			<?php settings_fields( 'gamwp_settings' ); ?>
			<?php do_settings_sections( __FILE__ ); ?>
			<p class="submit">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'gamwp' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}


function daily_limit_section_cb() {
	_e('<p>The Daily Limit ensures that easily completed Actions are not overused.  <br />Those Actions selected as "Limit" can be repeated until their combined points reach the Daily Points Limit.</p>', 'gamwp' );
}


function daily_limit_activate() {
	$gamwp_settings = get_option('gamwp_settings');
	$settings_value = isset( $gamwp_settings['daily_limit_activate'] ) ? $gamwp_settings['daily_limit_activate'] : '';
	echo "<input type='checkbox' id='gamwp_settings[daily_limit_activate]' name='gamwp_settings[daily_limit_activate]' value='1' " . checked( $settings_value, 1, false ) . "/>";
}


function daily_limit() {
	$gamwp_settings = get_option('gamwp_settings');
	echo "<input name='gamwp_settings[daily_limit]' type='text' value='" . esc_attr( $gamwp_settings['daily_limit'] ) . "' />";
}


function validate_gamwp_settings( $input ) {
	$gamwp_settings = get_option( 'gamwp_settings' );
	$output = array();
	foreach( $input as $key => $value ) {
		if( isset( $input[$key] ) ) {
			$output[$key] = strip_tags( stripslashes( $input[$key] ) );
		}
	}
	return apply_filters( 'validate_gamwp_settings', $output, $input );
}
