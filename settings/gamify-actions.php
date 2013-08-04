<?php

include_once( plugin_dir_path(__FILE__) . 'g-action-table.php' );
include_once( plugin_dir_path(__FILE__) . 'item-actions.php' );

add_action( 'admin_menu', 'register_g_action_menu_page' );

function register_g_action_menu_page(){
    add_menu_page( __( 'Gamify WP', 'gamwp' ), __( 'Gamify WP', 'gamwp' ), 'manage_options', basename(__FILE__), 'g_action_render_menu_page' );
}

function g_action_render_menu_page(){
        global $g_action_options;

    if ( isset( $_GET['g_action-action'] ) && $_GET['g_action-action'] == 'edit_item' ) {
        require_once plugin_dir_path(__FILE__) . 'edit-item.php';
    } elseif ( isset( $_GET['g_action-action'] ) && $_GET['g_action-action'] == 'add_item' ) {
        require_once plugin_dir_path(__FILE__) . 'add-item.php';
    } else {
        require_once plugin_dir_path(__FILE__) . 'gamify-actions.php';
        $g_action_items_table = new GAMWP_Actions_Table();
        $g_action_items_table->prepare_items();
    ?>
    <div class="wrap">
        <h2><?php _e( 'Gamify WP Actions', 'gamwp' ); ?><a href="<?php echo add_query_arg( array( 'g_action-action' => 'add_item' ) ); ?>" class="add-new-h2">Add New</a></h2>
        <form id="g_action-items-filter" method="get" action="<?php echo admin_url( 'admin.php?page=gamify-actions.php&post-type=g_action' ); ?>">
            <?php $g_action_items_table->display() ?>
        </form>
    </div>

    <?php
    }
}
