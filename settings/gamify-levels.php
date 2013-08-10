<?php

/**
 * Creates Gamify WP Admin Menu Page and Actions view
 *
 * @since  1.0
 */

//Table View
include_once( plugin_dir_path(__FILE__) . '/levels/item-table.php' );

//Process Add/Edit pages
include_once( plugin_dir_path(__FILE__) . '/levels/item-actions.php' );



/**
 * Set up Gamify WP Admin Menu Page
 *
 * @since  1.0
 */

add_action( 'admin_menu', 'register_rew_submenu_page' );

function register_rew_submenu_page() {
	add_submenu_page( 'gamify-general.php', __( 'Gamify WP Levels', 'gamify' ), __( 'Levels', 'gamify' ), 'manage_options', basename(__FILE__), 'rew_render_menu_page' );
}


/**
 * Render Actions Menu Page
 *
 * Detects which page (Edit/Add) is requested, then returns the view.
 *
 * @since  1.0
 */

function rew_render_menu_page(){

	if ( isset( $_GET['rew-action'] ) && $_GET['rew-action'] == 'edit_item' ) {
		require_once plugin_dir_path(__FILE__) . '/levels/edit-item.php';
	} elseif ( isset( $_GET['rew-action'] ) && $_GET['rew-action'] == 'add_item' ) {
		require_once plugin_dir_path(__FILE__) . '/levels/add-item.php';
	} else {
		require_once plugin_dir_path(__FILE__) . 'gamify-levels.php';

		$rew_items_table = new gamify_Rewards_Table();
		$rew_items_table->prepare_items();
		?>

		<div class="wrap">
			<div class="icon32" id="icon-options-general">
				<br />
			</div>
			<h2><?php _e( 'Gamify WP Levels', 'gamify' ); ?><a href="<?php echo add_query_arg( array( 'rew-action' => 'add_item' ) ); ?>" class="add-new-h2">Add New</a></h2>
			<form id="rew-items-filter" method="get" action="<?php echo admin_url( 'admin.php?page=gamify-levels.php&post-type=rew' ); ?>">
				<input type="hidden" name="post_type" value="rew" />
				<input type="hidden" name="page" value="gamify-levels.php" />
				<?php $rew_items_table->display() ?>
			</form>
		</div>

		<?php
	}

}
