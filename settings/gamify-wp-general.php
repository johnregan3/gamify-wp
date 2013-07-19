<?php

/*
 *
 * Gamify WP General Settings
 *
 */

Class GAMWP_Settings {

		private $action_array = array(
			'register' => array(
				'action_title' => 'Registered',
				'action_hook' => '1'
			),
			'comment' => array(
				'action_title' => 'Commented',
				'action_hook' => '1'
			),
			'post_action' => array(
				'action_title' => 'Posted',
				'action_hook' => '1'
			)
		);

	// input:$action is a string
	// fetch array from within action array
	public function get_action($action) {

		$action_array = $this->action_array;

		$array_list_item = array_keys( $action_array );

		foreach ( $action_array as $array_list_item );

		if ( $action = $action_array[$array_list_item] ) {

			$list_item = $action_array[$array_list_item][$action];

		}

		return $list_item;

	} // get_action;

	public function get_attr( $action_item, $action_item_attr ) {

		$list_item = $this->get_action($action_item);

		$list_item_attrs = array_keys( $action_item );

		$list_item_attr = $action_item[$action_item_attr];

		return $list_item_attr;

	} // get_action;

} // End Class GAMWP_Settings_Helpers




//Add To Menu
add_action( 'admin_menu', 'gamwp_add_menu_pages' );

function gamwp_add_menu_pages() {

	add_menu_page( __( 'Gamify WP Settings' ), __( 'Gamify WP' ), 'administrator', basename(__FILE__), 'gamwp_general_settings' );

}



function gamwp_render_fields() {

	register_setting( 'gamwp_settings', 'gamwp_settings', 'validate_gamwp_settings' );

	add_settings_section('daily_limit_section', __( 'Daily Points Limit', 'gamwp' ), 'daily_limit_section_cb', __FILE__ );
	add_settings_field( 'daily_limit_activate', __( 'Enforce Daily Limit', 'gamwp' ), 'daily_limit_activate', __FILE__, 'daily_limit_section' );
	add_settings_field( 'daily_limit', __( 'Daily Limit Amount', 'gamwp' ), 'daily_limit', __FILE__, 'daily_limit_section' );

	add_settings_field( 'set_default_actions', '', 'set_default_actions', __FILE__, 'default_actions_section' );
	add_settings_section('default_actions_section', __( 'Default Actions', 'gamwp' ), 'default_actions_section_cb', __FILE__ );
	add_settings_field( 'registration_amount', __( 'Points Earned for <strong>Registering a New User</strong>', 'gamwp' ), 'registration_amount', __FILE__, 'default_actions_section' );
	add_settings_field( 'registration_activate', __( 'Activate "Registraion" Action', 'gamwp' ), 'registration_activate', __FILE__, 'default_actions_section' );
	add_settings_field( 'comment_amount', __( 'Points Earned for <strong>Posting a Comment</strong>', 'gamwp' ), 'comment_amount', __FILE__, 'default_actions_section' );
	add_settings_field( 'comment_activate', __( 'Activate "Comments" Action', 'gamwp' ), 'comment_activate', __FILE__, 'default_actions_section' );
	add_settings_field( 'comment_limit', __( 'Include Comments in Daily Points Limit', 'gamwp' ), 'comment_limit', __FILE__, 'default_actions_section' );
	add_settings_field( 'post_amount', __( 'Points Earned for <strong>Publishing a Post</strong>', 'gamwp' ), 'post_amount', __FILE__, 'default_actions_section' );
	add_settings_field( 'post_activate', __( 'Activate "Post" Action', 'gamwp' ), 'post_activate', __FILE__, 'default_actions_section' );
	add_settings_field( 'post_limit', __( 'Include Posts in Daily Points Limit', 'gamwp' ), 'post_limit', __FILE__, 'default_actions_section' );

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

	$options = get_option('gamwp_settings');

	$action_list = array(
		'register' => array(
			'action_title' => 'Registered',
			'action_hook' => ''
		),
		'comment' => array(
			'action_title' => 'Commented',
			'action_hook' => ''
		),
		'post_action' => array(
			'action_title' => 'Posted',
			'action_hook' => ''
		)
	);

	$action_list_keys =  array_keys( $action_list );

		foreach ( $action_list_keys as $key => $list_item ) {

		$list_item_fields = $action_list[$list_item];

			foreach($list_item_fields as $field => $value) {

				echo "<br/>" . $list_item . ' = ' . $field . " = " . $value;
				echo "<input type=\"hidden\" name=\"gamwp_settings[action_list][$list_item][$field][$value]\" id=\"'gamwp_settings[action_list][$list_item][$field][$value]\" />";

			} // End foreach

		} // End foreach

echo '<br />';
$help = NEW GAMWP_SETTINGS;
$register_item = $help->get_action('register');

print_r($register_item);
echo '<br />';
$register_item_attr = $help->get_attr( $register_item, 'points');

print_r($register_item_attr);

}

function default_actions_section_cb() {

	_e( 'These Actions are set up by default by Gamify WP.  NEED MORE INFO.', 'gamwp' );

}


// Registration Points Amount
function registration_amount() {

	global $action_list;
	global $registration;
	global $points;

	$options = get_option('gamwp_settings');

	echo "<input name='gamwp_settings[action_list][registration][points]' type='text' value='{$options[$action_list][$registration][$points]}' />";

 print_r($options[$action_list][$registration][$points]);
}

// Activate Registration Action
function registration_activate() {

	$options = get_option('gamwp_settings');

	echo "<input type='checkbox' id='gamwp_settings[action_list][registration][active]' name='gamwp_settings[action_list][registration][active]' value='1' " . checked( $options['action_list']['registration']['active'], 1, false ) . "/>";

}

// Comment Amount
function comment_amount() {

	$options = get_option('gamwp_settings');

	echo "<input name='gamwp_settings[action_list][comment][points]' type='text' value='{$options['action_list']['comment']['points']}' />";

}

// Activate Comments Action
function comment_activate() {

	$options = get_option('gamwp_settings');

	echo "<input type='checkbox' id='gamwp_settings[action_list][comment][active]' name='gamwp_settings[action_list][comment][activ']' value='1' " . checked( $options['action_list']['comment']['active'], 1, false ) . "/>";

}

// Check for Points Limit
function comment_limit() {

	$options = get_option('gamwp_settings');

	echo "<input type='checkbox' id='gamwp_settings[action_list][comment'][limit]' name='gamwp_settings[action_list][comment][limit]' value='1' " . checked( $options['action_list']['comment']['limit'], 1, false ) . "/>";

}



// Post Action Amount
function post_amount() {

	$options = get_option('gamwp_settings');

	echo "<input name='gamwp_settings[action_list][post][points]' type='text' value='{$options['action_list']['post']['points']}' />";

}

// Activate Post Action
function post_activate() {

	$options = get_option('gamwp_settings');

	echo "<input type='checkbox' id='gamwp_settings[action_list][post][active]' name='gamwp_settings[action_list][post][active]' value='1' " . checked( $options['action_list']['comment']['active'], 1, false ) . "/>";

}

// Check for Daily Limit for Post
function post_limit() {

	$options = get_option('gamwp_settings');

	echo "<input type='checkbox' id='gamwp_setttings[action_list][post][limit]' name='gamwp_settings[action_list][post][limit]' value='1' " . checked( $options['action_list']['comment']['limit'], 1, false ) . "/>";

}


function notification_section_cb() {

	_e( '<p>Enter custom CSS to style the Notification Popup.<br />!!!!!MOVE THIS TO DOCS!!!!!!<br />You do not need to include the CSS Selector, just Properties.</p><p>Note how your CSS will be inserted:</p>  <pre>#spinner {

&lt;Custom CSS&gt;

}</pre>', 'gamwp' );

}

// Custom Notice CSS Textarea
function notice_css() {

	$options = get_option('gamwp-settings');

	if ( ! isset( $options['notice_css'] ) ) {

		$options['notice_css'] = '';

	}

	echo "<textarea name='gamwp_options[notice_css]' rows='10' cols='60' type='textarea'>{$options['notice_css']}</textarea>";
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
