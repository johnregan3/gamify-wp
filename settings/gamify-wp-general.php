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

	add_settings_field( 'set_default_actions', '', 'set_default_actions', __FILE__, 'default_actions_section' );
	add_settings_section('default_actions_section', __( 'Default Actions', 'gamwp' ), 'default_actions_section_cb', __FILE__ );

	add_settings_field( 'registration_amount', __( 'Points for <strong>Registering a New User</strong>', 'gamwp' ), 'registration_amount', __FILE__, 'default_actions_section' );
	add_settings_field( 'registration_activate', __( 'Activate "Registraion" Action', 'gamwp' ), 'registration_activate', __FILE__, 'default_actions_section' );
	add_settings_field( 'registration_limit', __( 'Include Registration in Daily Limit', 'gamwp' ), 'registration_limit', __FILE__, 'default_actions_section' );

	add_settings_field( 'comment_amount', __( 'Points for <strong>Posting a Comment</strong>', 'gamwp' ), 'comment_amount', __FILE__, 'default_actions_section' );
	add_settings_field( 'comment_activate', __( 'Activate "Comments" Action', 'gamwp' ), 'comment_activate', __FILE__, 'default_actions_section' );
	add_settings_field( 'comment_limit', __( 'Include Comments in Daily Limit', 'gamwp' ), 'comment_limit', __FILE__, 'default_actions_section' );

	add_settings_field( 'post_amount', __( 'Points for <strong>Publishing a Post</strong>', 'gamwp' ), 'post_amount', __FILE__, 'default_actions_section' );
	add_settings_field( 'post_activate', __( 'Activate "Publish Post" Action', 'gamwp' ), 'post_activate', __FILE__, 'default_actions_section' );
	add_settings_field( 'post_limit', __( 'Include Posts in Daily Limit', 'gamwp' ), 'post_limit', __FILE__, 'default_actions_section' );


	add_settings_section('notification_section', __( 'Notification Popup', 'gamwp' ), 'notification_section_cb', __FILE__ );
	add_settings_field( 'notice_css', __( 'Custom CSS Properties', 'gamwp' ), 'notice_css', __FILE__, 'notification_section' );


	//if settings not found, reset it to 0;

	$options = get_option( 'gamwp_settings' );

	$fields = array( 'points', 'active', 'limit' );

	if ( isset( $options['action_list']['action_title'] ) ) {

		$action_types = $options['action_list'][$fields];

		foreach ( $fields as $value ) {

			$options['action_list'][$fields][$value] = 0;

		} // End foreach

	} // Endif

} // gamwp_render_fields

add_action( 'admin_init', 'gamwp_render_fields' );



function gamwp_general_settings() {

?>

	<div id="gamwp-settings-wrap" class="wrap">
		<div class="icon32" id="icon-options-general">
				<br />
		</div>
		<?php _e( '<h2>Gamify WP General Settings</h2>', 'gamwp'); ?>
		<form method="post" action="options.php" enctype="multipart/form-data">
			<?php settings_fields( 'gamwp_settings' ); ?>
			<?php do_settings_sections( __FILE__ ); ?>
			<p class="submit">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
			</p>
		</form>
	</div>

<?php

} // gamwp_general_settings



function daily_limit_section_cb() {

	_e('<p>The Daily Limit ensures that easily completed Actions are not overused.  <br />Those Actions selected as "Include in Daily Limit" can be repeated until their combined points reaches the Daily Points Limit.  <br />!!!!!!Include text about when you won\'t want to use the daily limit<br /></p>', 'gamwp' );

}


// Check for Daily Limit
function daily_limit_activate() {

	$options = get_option('gamwp_settings');

	echo "<input type='checkbox' id='daily_limit_activate' name='gamwp_settings[daily_limit_activate]' value='1' " . checked( $options['daily_limit_activate'], 1, false ) . "/>";

}


// Daily Limit Amount
function daily_limit() {

	$options = get_option('gamwp_settings');

	echo "<input name='gamwp_settings[daily_limit]' type='text' value='{$options['daily_limit']}' />";

}


// Set Default Actions
function set_default_actions() {

	$help = NEW GAMWP_SETTINGS;
	$action_array = $help->action_array;

	foreach( $action_array  as $action => $action_value ) {
		foreach ( $action_value as $field => $field_value ) {
				$settings_title = $action . '_' . $field;
				$settings_value = $help->input_setup( $action, $field );
			echo '<input type="hidden" name="gamwp_settings[' . esc_attr( $settings_title ) . ']" id="gamwp_settings[' . esc_attr( $settings_title ) . ']" value ="' . $field_value. '" >';
		} // end foreach
	} // end foreach

}


function default_actions_section_cb() {

	_e( 'These Actions are set up by default by Gamify WP.  NEED MORE INFO.', 'gamwp' );

}


// Registration Points Amount
function registration_amount() {

	$help = NEW GAMWP_SETTINGS;

	$action = 'register';
	$field = 'points';
	$settings_title = $action . '_' . $field;
	$settings_value = $help->input_setup( $action, $field );

	echo '<input type="text" name="gamwp_settings[' . esc_attr( $settings_title ) . ']" id="gamwp_settings[' . esc_attr( $settings_title ) . ']" value ="' . $settings_value . '" />';

}


// Activate Registration Action
function registration_activate() {

	$help = NEW GAMWP_SETTINGS;

	$action = 'register';
	$field = 'active';
	$settings_title = $action . '_' . $field;
	$settings_value = $help->input_setup( $action, $field );

	echo '<input type="checkbox" id="gamwp_settings[' . esc_attr( $settings_title ) . ']" name="gamwp_settings[' . esc_attr( $settings_title ) . ']" value="1" '. checked( 1, isset( $settings_value ) ? $settings_value : 0, false ) .'/>';

}


// Check for Points Limit
function registration_limit() {

	$help = NEW GAMWP_SETTINGS;

	$action = 'register';
	$field = 'limit';
	$settings_title = $action . '_' . $field;
	$settings_value = $help->input_setup( $action, $field );

	echo '<input type="checkbox" id="gamwp_settings[' . esc_attr( $settings_title ) . ']" name="gamwp_settings[' . esc_attr( $settings_title ) . ']" value="1" '. checked( 1, isset( $settings_value ) ? $settings_value : 0, false ) .'/>';

}


// Comment Amount
function comment_amount() {

	$help = NEW GAMWP_SETTINGS;

	$action = 'comment';
	$field = 'points';
	$settings_title = $action . '_' . $field;
	$settings_value = $help->input_setup( $action, $field );

	echo '<input type="text" name="gamwp_settings[' . esc_attr( $settings_title ) . ']" id="gamwp_settings[' . esc_attr( $settings_title ) . ']" value ="' . $settings_value . '" />';

}


// Activate Comments Action
function comment_activate() {

	$help = NEW GAMWP_SETTINGS;

	$action = 'comment';
	$field = 'active';
	$settings_title = $action . '_' . $field;
	$settings_value = $help->input_setup( $action, $field );

	echo '<input type="checkbox" id="gamwp_settings[' . esc_attr( $settings_title ) . ']" name="gamwp_settings[' . esc_attr( $settings_title ) . ']" value="1" '. checked( 1, isset( $settings_value ) ? $settings_value : 0, false ) .'/>';

}


// Check for Points Limit
function comment_limit() {

	$help = NEW GAMWP_SETTINGS;

	$action = 'comment';
	$field = 'limit';
	$settings_title = $action . '_' . $field;
	$settings_value = $help->input_setup( $action, $field );

	echo '<input type="checkbox" id="gamwp_settings[' . esc_attr( $settings_title ) . ']" name="gamwp_settings[' . esc_attr( $settings_title ) . ']" value="1" '. checked( 1, isset( $settings_value ) ? $settings_value : 0, false ) .'/>';

}


// Post Action Amount
function post_amount() {

	$help = NEW GAMWP_SETTINGS;

	$action = 'post';
	$field = 'points';
	$settings_title = $action . '_' . $field;
	$settings_value = $help->input_setup( $action, $field );

	echo '<input type="text" name="gamwp_settings[' . esc_attr( $settings_title ) . ']" id="gamwp_settings[' . esc_attr( $settings_title ) . ']" value ="' . $settings_value . '" />';

}


// Activate Post Action
function post_activate() {

	$help = NEW GAMWP_SETTINGS;

	$action = 'post_action';
	$field = 'active';
	$settings_title = $action . '_' . $field;
	$settings_value = $help->input_setup( $action, $field );

	echo '<input type="checkbox" id="gamwp_settings[' . esc_attr( $settings_title ) . ']" name="gamwp_settings[' . esc_attr( $settings_title ) . ']" value="1" '. checked( 1, isset( $settings_value ) ? $settings_value : 0, false ) .'/>';

}


// Check for Daily Limit for Post
function post_limit() {

	$help = NEW GAMWP_SETTINGS;

	$action = 'post_action';
	$field = 'limit';
	$settings_title = $action . '_' . $field;
	$settings_value = $help->input_setup( $action, $field );

	echo '<input type="checkbox" id="gamwp_settings[' . esc_attr( $settings_title ) . ']" name="gamwp_settings[' . esc_attr( $settings_title ) . ']" value="1" '. checked( 1, isset( $settings_value ) ? $settings_value : 0, false ) .'/>';

}

function notification_section_cb() {

	_e( '<p>Enter custom CSS to style the Notification Popup.<br />!!!!!MOVE THIS TO DOCS!!!!!!<br />You do not need to include the CSS Selector, just Properties.</p><p>Note how your CSS will be inserted:</p>  <pre>#spinner {

&lt;Custom CSS&gt;

}</pre>', 'gamwp' );

}

// Custom Notice CSS Textarea
function notice_css() {

	$help = NEW GAMWP_SETTINGS;

	$action = 'notice';
	$field = 'css';
	$settings_title = $action . '_' . $field;
	$settings_value = $help->input_setup( $action, $field );

	echo '<textarea name="gamwp_settings[' . esc_attr( $settings_title ) . ']" rows="10" cols="60"type="textarea">' . $settings_value . '</textarea>';
}


function validate_gamwp_settings( $input ) {

	/*
	 * Validation script by Tom McFarlin
	 * https://github.com/tommcfarlin/WordPress-Settings-Sandbox
	 *
	 * This isn't the best way to vaildate checkboxes, obviously.
	 * Need some work here.
	 */


	//Need to check if some fields are int

	$output = array();

	foreach( $input as $key => $value ) {
		if ( $input['action_list'][$key]['points'] ) {
			$points = $input['action_list'][$key]['points'];
			if ( !is_int ( $points ) ) {
				$points == 0;
			}
		}
		if( isset( $input[$key] ) ) {
			$output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
		}
	}

	return apply_filters( 'validate_gamwp_settings', $output, $input );

}
