<?php

/**
 * Creates Gamify WP Admin Menu Page and Actions view
 *
 * @since  1.0
 */

//Table View
include_once( plugin_dir_path(__FILE__) . 'item-table.php' );

//Process Add/Edit pages
include_once( plugin_dir_path(__FILE__) . 'item-actions.php' );



/**
 * Set up Gamify WP Admin Menu Page
 *
 * @since  1.0
 */

add_action( 'admin_menu', 'register_gact_menu_page' );

function register_gact_menu_page()
	add_menu_page( __( 'Gamify WP', 'gamwp' ), __( 'Gamify WP', 'gamwp' ), 'manage_options', basename(__FILE__), 'gact_render_menu_page' );



/**
 * Render Actions Menu Page
 *
 * Detects which page (Edit/Add) is requested, then returns the view.
 *
 * @since  1.0
 */

function gact_render_menu_page(){

	if ( isset( $_GET['gact-action'] ) && $_GET['gact-action'] == 'edit_item' ) {
		require_once plugin_dir_path(__FILE__) . 'edit-item.php';
	} elseif ( isset( $_GET['gact-action'] ) && $_GET['gact-action'] == 'add_item' ) {
		require_once plugin_dir_path(__FILE__) . 'add-item.php';
	} else {
		require_once plugin_dir_path(__FILE__) . 'gamify-actions.php';

		$gact_items_table = new GAMWP_Actions_Table();
		$gact_items_table->prepare_items();
		?>

		<div class="wrap">
			<div class="icon32" id="icon-options-general">
				<br />
			</div>
			<h2><?php _e( 'Gamify WP Actions', 'gamwp' ); ?><a href="<?php echo add_query_arg( array( 'gact-action' => 'add_item' ) ); ?>" class="add-new-h2">Add New</a></h2>
			<form id="gact-items-filter" method="get" action="<?php echo admin_url( 'admin.php?page=gamify-actions.php&post-type=gact' ); ?>">
				<input type="hidden" name="post_type" value="gcat" />
				<input type="hidden" name="page" value="gamify-actions.php" />
				<!-- search doesn't work because page=gamify-actions.php doesn't get added to URL -->
				<?php $gact_items_table->search_box( __( 'Search', 'gamwp' ), 'gamify-actions.php' ); ?>
				<?php $gact_items_table->display() ?>
			</form>
		</div>

		<?php
	}

}
