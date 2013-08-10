<?php


/**
 * Set up Gamify WP Admin Menu Page
 *
 * @since  1.0
 */

add_action( 'admin_menu', 'register_gamify_menu_page' );

function register_gamify_menu_page() {
	add_menu_page( __( 'Gamify WP', 'gamify' ), __( 'Gamify WP', 'gamify' ), 'manage_options', basename(__FILE__), 'gamify_general_settings' );
}

function gamify_render_fields() {
	register_setting( 'gamify_settings_group', 'gamify_settings', 'validate_gamify_settings' );

	add_settings_section(
		'daily_limit_section',
		__( 'Daily Points Limit', 'gamify' ),
		'daily_limit_section_cb',
		__FILE__
	);

	add_settings_field(
		'daily_limit',
		__( 'Daily Points Limit', 'gamify' ),
		'daily_limit',
		__FILE__,
		'daily_limit_section'
	);

	add_settings_section(
		'features_section',
		__( 'Activate Features', 'gamify' ),
		'features_section_cb',
		__FILE__
	);
	add_settings_field(
		'Gamify Shortcodes',
		__( 'Gamify Shortcodes', 'gamify' ),
		'shortcodes_check',
		__FILE__,
		'features_section'
	);
	add_settings_field(
		'Gamify Widgets',
		__( 'Gamify Widgets', 'gamify' ),
		'widgets_check',
		__FILE__,
		'features_section'
	);
	add_settings_field(
		'Activity Log',
		__( 'Activity Log', 'gamify' ),
		'log_check',
		__FILE__,
		'features_section'
	);
	add_settings_field( '
		Points Adjuster',
		__( 'Points Adjuster', 'gamify' ),
		'adjust_check',
		__FILE__,
		'features_section'
	);

	add_settings_section(
		'reward_type_section',
		__( 'Reward Types', 'gamify' ),
		'reward_type_section_cb',
		__FILE__
	);
	add_settings_field(
		'Reward Class',
		__( 'Reward Types', 'gamify' ),
		'reward_class',
		__FILE__,
		'reward_type_section'
	);
	add_settings_field(
		'Activate Levels',
		'',
		'reward_levels',
		__FILE__,
		'reward_type_section'
	);
	add_settings_field(
		'Activate Badges',
		'',
		'reward_badges',
		__FILE__,
		'reward_type_section'
	);

}

add_action( 'admin_init', 'gamify_render_fields' );



function gamify_general_settings() {
	?>
	<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('.slidebox').parent().css( 'padding', '0' );
		if(jQuery('#reward_currency').is(':checked')) {
				jQuery('.slidebox').hide();
		};
		jQuery('form input:radio').change(function() {
			if(jQuery('.slidebox').is(':visible') && jQuery('#reward_currency').is(':checked') ) {
				jQuery('.slidebox').fadeOut();
			} else if ( jQuery('#reward_score').is(':checked') ) {
				jQuery('.slidebox').fadeIn();
			};
		});
	});
	</script>
	<div id="gamify-settings-wrap" class="wrap">
		<div class="icon32" id="icon-options-general">
			<br />
		</div>
		<?php _e( '<h2>Gamify WP General Settings</h2>', 'gamify'); ?>
		<?php if( isset($_GET['settings-updated']) ) { ?>
			<div id="message" class="updated">
				<p><?php _e('Settings saved.') ?></p>
			</div>
		<?php } ?>
		<p><a href="https://github.com/johnregan3/gamify-wp-plugin/wiki/General-Settings"><?php _e( 'Get help for this page on our Wiki', 'gamify' ) ?></a>.</p>
		<form method="post" action="options.php" enctype="multipart/form-data">
			<?php settings_fields( 'gamify_settings_group' ); ?>
			<?php do_settings_sections( __FILE__ ); ?>
			<p class="submit">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'gamify' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}


function daily_limit_section_cb() {
	_e('<p>The Daily Limit ensures that easily completed Actions are not overused.  <br />Those Actions selected as "Limited" on the Actions administration page can be repeated until their combined points reach the Daily Points Limit.</p>', 'gamify' );
}

function daily_limit() {
	$options = get_option('gamify_settings');
	$settings_value= isset( $options['daily_limit'] ) ? $options['daily_limit'] : 0 ;
	?>
		<input name="gamify_settings[daily_limit]" type="text" style="width: 40px;" value="<?php echo esc_attr( $settings_value ); ?>" />
		<span class="description">&nbsp;&nbsp;<?php _e( 'Set to 0 to disable the Daily Limit', 'gamify' ); ?></span>
	<?php
}

function features_section_cb() {
	echo sprintf('<p>%s</p>', __(' Description', 'gamify' ) );
}

function widgets_check() {
	$options = get_option('gamify_settings');
	$settings_value = isset( $options['widgets_check'] ) ? $options['widgets_check'] : 0;
	?>
		<input type="checkbox" id="gamify_settings[widgets_check]" name="gamify_settings[widgets_check]" value="1" <?php checked( 1, $settings_value ) ?> />
	<?php
}

function log_check() {
	$options = get_option('gamify_settings');
	$settings_value = isset( $options['log_check'] ) ? $options['log_check'] : 0;
	?>
		<input type="checkbox" id="gamify_settings[log_check]" name="gamify_settings[log_check]" value="1" <?php checked( 1, $settings_value ) ?> />
	<?php
}

function shortcodes_check() {
	$options = get_option('gamify_settings');
	$settings_value = isset( $options['shortcodes_check'] ) ? $options['shortcodes_check'] : 0;
	?>
		<input type="checkbox" id="gamify_settings[shortcodes_check]" name="gamify_settings[shortcodes_check]" value="1" <?php checked( 1, $settings_value ) ?> />
	<?php
}

function adjust_check() {
	$options = get_option('gamify_settings');
	$settings_value = isset( $options['adjust_check'] ) ? $options['adjust_check'] : 0;
	?>
		<input type="checkbox" id="gamify_settings[adjust_check]" name="gamify_settings[adjust_check]" value="1" <?php checked( 1, $settings_value ) ?> />
	<?php
}

function reward_type_section_cb() {
/*	$line_1 = __( "Choose how the points on your website will be used.", "gamify" );
	$line_2 = __( "If you \"Use Points as Currency,\" the Rewards Catalog will be activated, but you will be unable to use Levels and Badges.", "gamify" );
	$line_3 = __( "If you choose to \"Use Points to Keep Score,\" you will be able to choose if you want to reward users with Levels and/or Badges.", "gamify" );
	echo sprintf( '<p>%s</p><ul><li>%s</li><li>%s</li></ul>', esc_html( $line_1 ), esc_html( $line_2 ), esc_html( $line_3 ) );
*/}

function reward_class() {
	$options = get_option('gamify_settings');
	//if reward_types isset and is an array, return it, otherwise create a blank array.
	$settings_value = isset( $options['reward_class'] ) ? $options['reward_class'] : 'reward_score';
	?>

	<input type="radio" id="reward_currency" name="gamify_settings[reward_class]" value="reward_currency" <?php checked( 'reward_currency', $settings_value ) ?> />
	<span class="description">&nbsp;&nbsp;<?php _e( 'Use Points as Currency and Activate Rewards Catalog', 'gamify' ) ?></span><br />

	<input type="radio" id="reward_score" name="gamify_settings[reward_class]" value="reward_score" <?php checked( 'reward_score', $settings_value ) ?> />
	<span class="description">&nbsp;&nbsp;<?php _e( 'Use Points to Keep Score', 'gamify' ) ?></span>
	<?php
}

function reward_levels() {
	$options = get_option('gamify_settings');
	$settings_value = isset( $options['reward_levels'] ) ? $options['reward_levels'] : 0;
	?>
		<div class="slidebox" style="margin-left: 40px;">
			<input type="checkbox" id="reward_levels" name="gamify_settings[reward_levels]" value="1" <?php checked( 1, $settings_value ) ?> />
			<span class="description">&nbsp;&nbsp;Activate Levels</span>
		</div>
	<?php
}

function reward_badges() {
	$options = get_option('gamify_settings');
	$settings_value = isset( $options['reward_badges'] ) ? $options['reward_badges'] : 0;
	?>
		<div class="slidebox" style="margin-left: 40px;">
			<input type="checkbox" id="reward_badges" name="gamify_settings[reward_badges]" value="1" <?php checked( 1, $settings_value ) ?> />
			<span class="description">&nbsp;&nbsp;Activate Badges</span>
		</div>
	<?php
}



function validate_gamify_settings( $input ) {
	$options = get_option( 'gamify_settings' );
	$output = array();
	foreach( $input as $key => $value ) {
		if( isset( $input[$key] ) ) {
			$output[$key] = strip_tags( stripslashes( $input[$key] ) );
		}
	}
	return apply_filters( 'validate_gamify_settings', $output, $input );
}


add_action('init', 'gamify_submenus' );

function gamify_submenus() {
	$options = get_option( 'gamify_settings' );

	//Actions Admin Page
	include_once( plugin_dir_path(__FILE__) . 'gamify-actions.php' );

	//If Using Score and Badges is not set, use Levels at a minimum. Of course, if  Levels are set, then set levels.
	if ( ( 'reward_score' == $options['reward_class'] ) && !isset( $options['reward_badges'] ) || ( 'reward_score' == $options['reward_class'] ) && isset( $options['reward_levels'] ) ) {
		//Rewards Admin Page
		include_once( plugin_dir_path(__FILE__) . 'gamify-levels.php' );
	}

	if ( ( 'reward_score' == $options['reward_class'] ) && isset( $options['reward_badges'] ) ) {
		//Rewards Admin Page
		include_once( plugin_dir_path(__FILE__) . 'gamify-badges.php' );
	}

		if ( 'reward_currency' == $options['reward_class'] ) {
		//Catalog Admin Page
		include_once( plugin_dir_path(__FILE__) . 'gamify-catalog.php' );
	}

	if ( isset( $options['log_check'] ) ) {
		//Points Log Admin Page
		include_once( plugin_dir_path(__FILE__) . 'gamify-log.php' );
	}








}