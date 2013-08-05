<?php

/*
	Plugin Name: New WP_List_table Plugin
	Plugin URI: http://johnregan3.com
	Description: Quick Table Generation for Plugin Developers
	Author: John Regan
	Author URI: http://johnregan3.com
	Version: 1.0

 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Load WP_List_Table if not loaded
if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class GAMWP_Actions_Table extends WP_List_Table {

    /**
     * Get things started
     *
     * @access public
     * @see WP_List_Table::__construct()
     * @return void
     */
    public function __construct() {

        parent::__construct( array(
            'singular'  => 'Item',    // Singular name of the listed records
            'plural'    => 'Items',        // Plural name of the listed records
            'ajax'      => false                        // Does this table support ajax?
        ) );
    }

    /**
     * Retrieve the table columns
     *
     * @access public
     * @return array $columns Array of all the list table columns
     */
    public function get_columns() {
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'name'      => __( 'Name', 'gamwp' ),
            'action_hook'      => __( 'Action Hook', 'gamwp' ),
            'action_points'  => __( 'Points', 'gamwp' ),

        );

        return $columns;
    }

    /**
     * Retrieve the table's sortable columns
     *
     * @access public
     * @since 1.4
     * @return array Array of all the sortable columns
     */
    public function get_sortable_columns() {
        return array(
            'name'   => array( 'name', true ),
        );
    }

    /**
     * This function renders most of the columns in the list table.
     *
     * @access public
     * @param array $item Contains all the data of the action 111
     * @param string $column_name The name of the column
     *
     * @return string Column Name
     */
    function column_default( $item, $column_name ) {
        switch( $column_name ){
            default:
                return $item[ $column_name ];
        }
    }

    /**
     * Render the Name Column
     *
     * @access public
     * @param array $item Contains all the data of the action 111
     * @return string Data shown in the Name column
     */
    function column_name( $item ) {
        $row     = get_post( $item['ID'] );
        $base         = admin_url( 'admin.php?page=gamify-actions.php&item_id=' . $item['ID'] );
        $row_actions  = array();

        $row_actions['edit'] = '<a href="' . add_query_arg( array( 'gact-action' => 'edit_item', 'item_id' => $row->ID ) ) . '">' . __( 'Edit', 'gamwp' ) . '</a>';

        $row_actions['delete'] = '<a href="' . wp_nonce_url( add_query_arg( array( 'gact-action' => 'delete_action', 'item_id' => $row->ID ) ), 'gact_item_nonce' ) . '">' . __( 'Delete', 'gamwp' ) . '</a>';

        return $item['name'] . $this->row_actions( $row_actions );
    }

    /**
     * Render the checkbox column
     *
     * @access public
     * @since 1.4
     * @param array $item Contains all the data for the checkbox column
     * @return string Displays a checkbox
     */
    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],
            /*$2%s*/ $item['ID']
        );
    }


    /**
     * Show the search field
     *
     * @access public
     * @since 1.4
     *
     * @param string $text Label for the search box
     * @param string $input_id ID of the search box
     *
     * @return svoid
     */
    public function search_box( $text, $input_id ) {
        if ( empty( $_REQUEST['s'] ) && !$this->has_items() )
            return;

        $input_id = $input_id . '-search-input';

        if ( ! empty( $_REQUEST['orderby'] ) )
            echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
        if ( ! empty( $_REQUEST['order'] ) )
            echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';
        ?>
        <p class="search-box">
            <label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
            <input type="search" id="<?php echo $input_id ?>" name="s" value="<?php _admin_search_query(); ?>" />
            <?php submit_button( $text, 'button', false, false, array('ID' => 'search-submit') ); ?>
        </p>
    <?php
    }

    /**
     * Retrieve the bulk actions
     *
     * @access public
     * @since 1.4
     * @return array $actions Array of the bulk actions
     */
    public function get_bulk_actions() {
        $actions = array(
            'delete' => __( 'Delete', 'edd' )
        );

        return $actions;
    }

    /**
     * Process the bulk actions
     *
     * @access public
     * @since 1.4
     * @return void
     */
    public function process_bulk_action() {
        $ids = isset( $_GET['item'] ) ? $_GET['item'] : false;

        if ( ! is_array( $ids ) )
            $ids = array( $ids );

        foreach ( $ids as $id ) {
            if ( 'delete' === $this->current_action() ) {
                gact_remove_item( $id );
            }
        }

    }

    /**
     * Retrieve all the data
     *
     * @access public
     * @return array Array of all the data for the action 111s
     */
    public function gact_table_data() {
        $gact_table_data = array();

        $orderby        = isset( $_GET['orderby'] )  ? $_GET['orderby']                  : 'ID';
        $order          = isset( $_GET['order'] )    ? $_GET['order']                    : 'DESC';

        $args = array(
            'post_type' => 'gact',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => $orderby,
            'order' => $order,
            );

        $items = get_posts( $args );

		if ( $items ) {
			foreach ( $items as $item) {
                $gact_table_data[] = array(
                    'ID'            => $item->ID,
                    'name'          => get_the_title( $item->ID ),
                    'action_hook'        => get_post_meta( $item->ID, '_gact_item_action_hook', true ),
                    'action_points'        => get_post_meta( $item->ID, '_gact_item_action_points', true ),
                );
            }
        }

        return $gact_table_data;
    }

    public function prepare_items() {

        $columns = $this->get_columns();

        $hidden = array();

        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array( $columns, $hidden, $sortable );

        $this->process_bulk_action();

        $data = $this->gact_table_data();

        $this->items = $data;

    }
}
