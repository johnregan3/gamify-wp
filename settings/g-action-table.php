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
            'name'      => __( 'Name', 'gamwp' ),
            'action_hook'      => __( 'Action Hook', 'gamwp' ),
            'action_points'  => __( 'Points', 'gamwp' ),

        );

        return $columns;
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
        $base         = admin_url( 'admin.php?page=table-test.php&item_id=' . $item['ID'] );
        $row_actions  = array();

        $row_actions['edit'] = '<a href="' . add_query_arg( array( 'g_action-action' => 'edit_item', 'item_id' => $row->ID ) ) . '">' . __( 'Edit', 'gamwp' ) . '</a>';

        $row_actions['delete'] = '<a href="' . wp_nonce_url( add_query_arg( array( 'g_action-action' => 'delete_action', 'item_id' => $row->ID ) ), 'g_action_item_nonce' ) . '">' . __( 'Delete', 'gamwp' ) . '</a>';

        return $item['name'] . $this->row_actions( $row_actions );
    }

    /**
     * Retrieve all the data for all the action 111s
     *
     * @access public
     * @return array $action_111s_data Array of all the data for the action 111s
     */
    public function g_action_table_data() {
        $g_action_table_data = array();

        $args = array(
            'post_type' => 'g_action',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            );

        $items = get_posts( $args );

		if ( $items ) {
			foreach ( $items as $item) {
                $g_action_table_data[] = array(
                    'ID'            => $item->ID,
                    'name'          => get_the_title( $item->ID ),
                    'action_hook'        => get_post_meta( $item->ID, '_g_action_item_action_hook', true ),
                    'action_points'        => get_post_meta( $item->ID, '_g_action_item_action_points', true ),
                );
            }
        }

        return $g_action_table_data;
    }

    public function prepare_items() {

        $columns = $this->get_columns();

        $data = $this->g_action_table_data();

        $hidden = array();

        //right now, we're not using sortabl columns, so we'll leave this blank.
        $sortable = '';

        $this->_column_headers = array( $columns, $hidden, $sortable );

        $this->items = $data;

    }
}
