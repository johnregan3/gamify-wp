<?php

/**
 * Creates Gamify WP Admin Menu Page and Actions view
 *
 * @since  1.0
 */


/**
 * Set up Gamify WP Admin Menu Page
 *
 * @since  1.0
 */
add_action( 'admin_menu', 'register_cat_submenu_page' );

function register_cat_submenu_page() {
	add_submenu_page( 'gamify-general.php', __( 'Gamify WP Catalog', 'gamify' ), __( 'Catalog', 'gamify' ), 'manage_options', basename(__FILE__), 'cat_render_menu_page' );
}


/**
 * Render Actions Menu Page
 *
 * Detects which page (Edit/Add) is requested, then returns the view.
 *
 * @since  1.0
 */

function cat_render_menu_page(){
?>

	<div class="wrap">
		<div class="icon32" id="icon-options-general">
			<br />
		</div>
		<h2><?php _e( 'Gamify WP Rewards Catalog', 'gamify' ); ?></h2>
	</div>

	<?php

}
