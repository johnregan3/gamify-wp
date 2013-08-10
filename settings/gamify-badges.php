<?php

/**
 * Creates Gamify WP Admin Menu Page and Actions view
 *
 * @since  1.0
 */

//Table View
include_once( plugin_dir_path(__FILE__) . '/badges/item-table.php' );

//Process Add/Edit pages
include_once( plugin_dir_path(__FILE__) . '/badges/item-actions.php' );



/**
 * Set up Gamify WP Admin Menu Page
 *
 * @since  1.0
 */

add_action( 'admin_menu', 'register_badges_submenu_page' );

function register_badges_submenu_page() {
	add_submenu_page( 'gamify-general.php', __( 'Gamify WP Badges', 'gamify' ), __( 'Badges', 'gamify' ), 'manage_options', basename(__FILE__), 'badges_render_menu_page' );
}


/**
 * Render Actions Menu Page
 *
 * Detects which page (Edit/Add) is requested, then returns the view.
 *
 * @since  1.0
 */

function badges_render_menu_page(){

	if ( isset( $_GET['rew-action'] ) && $_GET['rew-action'] == 'edit_item' ) {
		require_once plugin_dir_path(__FILE__) . '/badges/edit-item.php';
	} elseif ( isset( $_GET['rew-action'] ) && $_GET['rew-action'] == 'add_item' ) {
		require_once plugin_dir_path(__FILE__) . '/badges/add-item.php';
	} else {
		require_once plugin_dir_path(__FILE__) . 'gamify-badges.php';

		$badges_items_table = new Gamify_Badges_Table();
		$badges_items_table->prepare_items();
		?>

		<div class="wrap">
			<div class="icon32" id="icon-options-general">
				<br />
			</div>
			<h2><?php _e( 'Gamify WP Badges', 'gamify' ); ?><a href="<?php echo add_query_arg( array( 'badges-action' => 'add_item' ) ); ?>" class="add-new-h2">Add New</a></h2>
			<form id="badges-items-filter" method="get" action="<?php echo admin_url( 'admin.php?page=gamify-badges.php&post-type=badge' ); ?>">
				<input type="hidden" name="post_type" value="rew" />
				<input type="hidden" name="page" value="gamify-badges.php" />
				<?php $badges_items_table->display() ?>
			</form>
		</div>

		<?php
	}

}
