<?php

include_once( plugin_dir_path(__FILE__) . 'g-action-table.php' );
include_once( plugin_dir_path(__FILE__) . 'item-actions.php' );

add_action( 'admin_menu', 'register_acpt_menu_page' );

function register_acpt_menu_page(){
    add_menu_page( __( 'ACPT Plugin', 'gamwp' ), __( 'ACPT Plugin', 'gamwp' ), 'manage_options', basename(__FILE__), 'acpt_render_menu_page' );
}

function acpt_render_menu_page(){
        global $acpt_options;

    if ( isset( $_GET['acpt-action'] ) && $_GET['acpt-action'] == 'edit_item' ) {
        require_once plugin_dir_path(__FILE__) . 'edit-item.php';
    } elseif ( isset( $_GET['acpt-action'] ) && $_GET['acpt-action'] == 'add_item' ) {
        require_once plugin_dir_path(__FILE__) . 'add-item.php';
    } else {
        require_once plugin_dir_path(__FILE__) . 'gamify-actions.php';
        $acpt_items_table = new ACPT_Actions_Table();
        $acpt_items_table->prepare_items();
    ?>
    <div class="wrap">
        <h2><?php _e( 'Gamify WP Actions', 'gamwp' ); ?><a href="<?php echo add_query_arg( array( 'acpt-action' => 'add_item' ) ); ?>" class="add-new-h2">Add New</a></h2>
        <form id="acpt-items-filter" method="get" action="<?php echo admin_url( 'admin.php?page=gamify-actions.php&post-type=g_action' ); ?>">
            <?php $acpt_items_table->display() ?>
        </form>
    </div>

    <?php
    }
}
