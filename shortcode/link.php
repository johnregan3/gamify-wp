<?php

/*
 *
 * Custom Link Shortcode
 *
 */

function gamwp_link_shortcode( $atts, $content = null ) {

	extract( shortcode_atts( array(
		'url' => '',
		'points' => 0,
		'title' => 'Link Action',
		'daily_limit' => 0
	), $atts ) );

	$user_id = get_current_user_id();

	// Create nonce inside post
	ob_start();
		$nonce = wp_create_nonce( 'gamwp_nonce' );
	ob_end_clean();

	$return_string = '<a href="' . esc_attr( $url ) . '" class="gamwp-link" data-action-title="' . esc_attr( $title ) . '" data-nonce="' . esc_attr( $nonce ) . '"  data-points="' . esc_attr($points) . '" data-user-id="' . esc_attr($user_id) . '" data-limit="' . esc_attr( $daily_limit ) . '" >';
	$return_string = $return_string . $content;
	$return_string = $return_string . '</a>';
	return $return_string;

} // End gamwp_link_shortcode

add_shortcode( 'gamwp-link', 'gamwp_link_shortcode' );

/*
 *
 * Add Shortcode Button to TinyMCE editor
 * http://wordpress.stackexchange.com/questions/72394/how-to-add-a-shortcode-button-to-the-tinymce-editor
 *
 */

function gamwp_link_shortcode_button_process() {

	//Abort early if the user will never see TinyMCE
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') && get_user_option('rich_editing') == 'true') {

		return;

	} // endif

	//Add a callback to regiser our tinymce plugin
	add_filter("mce_external_plugins", "gamwp_link_shortcode_register_tinymce_plugin");

	// Add a callback to add our button to the TinyMCE toolbar
	add_filter('mce_buttons', 'gamwp_link_shortcode_button');

}

add_action('init', 'gamwp_link_shortcode_button_process');


// Register the Button
function gamwp_link_shortcode_register_tinymce_plugin($plugin_array) {

	$url = plugins_url() . '/gamify-wp/shortcode/script-tinymce-shortcode.js';
	$plugin_array['gamwp_link_button'] = $url;
	return $plugin_array;

}

//Add Button to Toolbar
function gamwp_link_shortcode_button($buttons) {

	$buttons[] = "gamwp_link_button";
	return $buttons;

}
