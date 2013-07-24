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

	add_settings_section('default_actions_section', __( 'Default Actions', 'gamwp' ), 'default_actions_section_cb', __FILE__ );
	add_settings_field( 'set_default_actions', '', 'set_default_actions', __FILE__, 'default_actions_section' );

	add_settings_section('register_section', __( 'Registration Action Settings', 'gamwp' ), 'register_section_cb', __FILE__ );
/*	add_settings_field( 'register_amount', __( 'Points for <strong>Registering a New User</strong>', 'gamwp' ), 'register_amount', __FILE__, 'register_section' );
	add_settings_field( ${$register_active}, __( 'Activate "Registraion" Action', 'gamwp' ), 'register_active', __FILE__, 'registrer_section' );
	add_settings_field( ${$register_limit}, __( 'Include Registration in Daily Limit', 'gamwp' ), 'register_limit', __FILE__, 'register_section' );
*/
	add_settings_section('comment_section', __( 'Comment Action Settings', 'gamwp' ), 'comment_section_cb', __FILE__ );
/*	add_settings_field( 'comment_amount', __( 'Points for <strong>Posting a Comment</strong>', 'gamwp' ), 'comment_amount', __FILE__, 'comment_section' );
	add_settings_field( ${$comment_active}, __( 'Activate "Comments" Action', 'gamwp' ), 'comment_active', __FILE__, 'comment_section' );
	add_settings_field( ${$comment_limit}, __( 'Include Comments in Daily Limit', 'gamwp' ), 'comment_limit', __FILE__, 'comment_section' );
*/
	add_settings_section('post_action_section', __( 'Comment Action Settings', 'gamwp' ), 'post_action_section_cb', __FILE__ );
/*	add_settings_field( 'post_action_amount', __( 'Points for <strong>Publishing a Post</strong>', 'gamwp' ), 'post_action_amount', __FILE__, 'post_action_section' );
	add_settings_field( ${$post_action_active}, __( 'Activate "Publish Post" Action', 'gamwp' ), 'post_action_active', __FILE__, 'post_action_section' );
	add_settings_field( ${$post_action_limit}, __( 'Include Posts in Daily Limit', 'gamwp' ), 'post_action_limit', __FILE__, 'post_action_section' );
*/
	add_settings_section('notification_section', __( 'Notification Popup', 'gamwp' ), 'notification_section_cb', __FILE__ );
/*	add_settings_field( 'notice_css', __( 'Custom CSS Properties', 'gamwp' ), 'notice_css', __FILE__, 'notification_section' );
	add_settings_field( 'notice_spinner', __( 'Upload Custom Spinner', 'gamwp' ), 'notice_spinner', __FILE__, 'notification_section' );
	add_settings_field('notice_spinner_preview',  __( 'Spinner Preview', 'wptuts' ), 'notice_spinner_preview', __FILE__, 'notification_section');
*/
	$options = get_option( 'gamwp_settings' );
	$fields = array( 'points', 'active', 'limit' );
	if ( isset( $options['action_list']['action_title'] ) ) {
		$action_types = $options['action_list'][$fields];
		foreach ( $fields as $value ) {
			$options['action_list'][$fields][$value] = 0;
		}
	}
}

add_action( 'admin_init', 'gamwp_render_fields' );

function generate_points() {
	$help = NEW GAMWP_SETTINGS;
	$action_array = $help->action_array;
	foreach ( $action_array as $actions => $val ) {
		$checkbox_options = array( "limit", "active" );
		$settings_title = $actions . '_points';
		$settings_title_string = $settings_title;
		$field_text = ucwords( str_replace( '_', ' ', $settings_title_string ) );
		$$settings_title = function() use ( $actions, $settings_title_string ) {
			$help = NEW GAMWP_SETTINGS;
			$settings_value = $help->input_setup( $actions, 'points' );
			echo "<input type='text' id='gamwp_settings[". esc_attr( $settings_title_string ) . "]' name='gamwp_settings[" . esc_attr( $settings_title_string ) . "]' value='" . $settings_value ."' />";
		};
		add_settings_field( $settings_title, __( $field_text, 'gamwp' ), $$settings_title_string, __FILE__, $actions . '_section' );
	}
}

add_action( 'admin_init', 'generate_points' );



function generate_checkboxes() {
	$help = NEW GAMWP_SETTINGS;
	$action_array = $help->action_array;
	foreach ( $action_array as $actions => $val ) {
		$action_title = $actions;
		$checkbox_options = array( "limit", "active" );
		foreach ( $checkbox_options as $checkbox ) {
			$settings_title = $action_title . '_' . $checkbox;
			$settings_title_string = $settings_title;
			$field_text = ucwords( str_replace( '_', ' ', $settings_title_string ) );
			$$settings_title = function() use ( $action_title, $checkbox, $settings_title_string ) {
				$help = NEW GAMWP_SETTINGS;
				$settings_value = $help->input_setup( $action_title, $checkbox );
				echo "<input type='checkbox' id='gamwp_settings[". esc_attr( $settings_title_string ) . "]' name='gamwp_settings[" . esc_attr( $settings_title_string ) . "]' value='1' " . checked( 1, isset( $settings_value ) ? $settings_value : 0, false ) ." />";
			};
			add_settings_field( $settings_title, __( $field_text, 'gamwp' ), $$settings_title_string, __FILE__, $action_title . '_section' );
		}
	}
}

add_action( 'admin_init', 'generate_checkboxes' );



function gamwp_general_settings() {
	?>
	<div id="gamwp-settings-wrap" class="wrap">
		<div class="icon32" id="icon-options-general">
			<br />
		</div>
		<?php _e( '<h2>Gamify WP General Settings</h2>', 'gamwp'); ?>
		<p><a href="https://github.com/johnregan3/gamify-wp-plugin/wiki/General-Settings">Get help for this page on our Wiki</a>.</p>
		<form method="post" action="options.php" enctype="multipart/form-data">
			<?php settings_fields( 'gamwp_settings' ); ?>
			<?php do_settings_sections( __FILE__ ); ?>
			<p class="submit">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}


function daily_limit_section_cb() {
	_e('<p>The Daily Limit ensures that easily completed Actions are not overused.  <br />Those Actions selected as "Include in Daily Limit" can be repeated until their combined points reaches the Daily Points Limit.</p>', 'gamwp' );
}


function default_actions_section_cb() {}
function register_section_cb() {}
function comment_section_cb() {}
function post_action_section_cb() {}
function notification_section_cb() {
	_e( '<p>Enter custom CSS to style the Notification Popup.', 'gamwp' );
}


function daily_limit_activate() {
	$options = get_option('gamwp_settings');
	if ( ! isset( $options['daily_limit_activate'] ) ) {
		$options['daily_limit_activate'] = 0;
	}
	echo "<input type='checkbox' id='gamwp_settings[daily_limit_activate]' name='gamwp_settings[daily_limit_activate]' value='1' " . checked( $options['daily_limit_activate'], 1, false ) . "/>";
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
			echo "<input type='hidden' name='gamwp_settings[" . esc_attr( $settings_title ) . "]' id='gamwp_settings[". esc_attr( $settings_title ) . "]' value ='" . $settings_value. "'>";
		} // end foreach
	} // end foreach

}

function notice_css() {
	$help = NEW GAMWP_SETTINGS;
	$action = 'notice';
	$field = 'css';
	$settings_title = $action . '_' . $field;
	$settings_value = $help->input_setup( $action, $field );
	echo "<textarea name='gamwp_settings[" . esc_attr( $settings_title ) . "]' rows='5' cols='60' type='textarea'>" . $settings_value . "</textarea>";
}

function notice_spinner() {

	$gamwp_settings = get_option('gamwp_settings');
	$settings_title = "notice_spinner";
	if ( ! isset( $options['notice_spinner'] ) ) {
		$options['notice_spinner'] = '';
	}
	echo "<input type='hidden' id='gamwp_settings_notice_spinner' name='gamwp_settings[notice_spinner]' value='" . $gamwp_settings['notice_spinner'] . "' />";
	echo "<input id='upload_spinner_button' type='button' class='button' value='Upload Custom Spinner' />";
	if ( '' != $gamwp_settings['notice_spinner'] ) {
		echo "<input id='delete_logo' name='gamwp_settings[delete_spinner]' type='submit' class'button' value='Delete Spinner' />";
	}
}

function notice_spinner_preview() {
	$gamwp_settings = get_option( 'gamwp_settings' );  ?>
	<div id="notice_spinner_preview" style="min-height: 100px;">
		<img style="max-width:100%;" src="<?php echo esc_url( $gamwp_settings['notice_spinner'] ); ?>" />
	</div>
	<?php
}


//Change image upload button text from "Insert into Post"
function gamwp_thickbox_text_replace() {
	global $pagenow;
	if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
		// Now we'll replace the 'Insert into Post Button' inside Thickbox
		add_filter( 'gettext', 'replace_thickbox_text'  , 1, 3 );
	}
}

add_action( 'admin_init', 'gamwp_thickbox_text_replace' );

function replace_thickbox_text($translated_text, $text, $domain) {
	if ('Insert into Post' == $text) {
		$referer = strpos( wp_get_referer(), 'gamwp_settings' );
		if ( $referer != '' ) {
			return __('Use as Spinner', 'gamwp' );
		}
	}
	return $translated_text;
}

function delete_image( $image_url ) {
	global $wpdb;
	$query = "SELECT ID FROM wp_posts where guid = '" . esc_url($image_url) . "' AND post_type = 'attachment'";
	$results = $wpdb->get_results($query);
	foreach ( $results as $row ) {
		wp_delete_attachment( $row->ID );
	}
}


function validate_gamwp_settings( $input ) {

	$gamwp_settings = get_option( 'gamwp_settings' );

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

		$delete_logo = ! empty($input['delete_spinner']) ? true : false;

		if ( $delete_logo ) {
			delete_image( $gamwp_settings['notice_spinner'] );
			$output['notice_spinner'] = '';
		}

		if( isset( $input[$key] ) ) {
			$output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
		}
	}

	return apply_filters( 'validate_gamwp_settings', $output, $input );

}
