<?php
if ( !defined( 'ABSPATH' ) ) {die( "Don't try this" );}
require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
require_once ABSPATH . 'wp-admin/includes/template.php';
if ( !class_exists( "Ewform_Tables" ) && class_exists( "WP_List_Table" ) ) {
    class Ewform_Tables extends WP_List_Table {
        // Initialize table columns
        public function __construct() {
            parent::__construct( [
                'singular' => 'item',
                'plural'   => 'items',
                'ajax'     => false,
            ] );
        }
        /**
         * Prepare items for the table
         *
         * @return void
         */
        public function prepare_items() {
            $columns = $this->get_columns();
            $sortable = $this->get_sortable_columns();
            $data = $this->get_data();

            // Set column headers and data
            $this->_column_headers = [$columns, [], $sortable];
            $this->items = $data;
        }
        /**
         * Define table columns
         *
         * @return string[]
         */
        public function get_columns() {
            return [
                'ewf_title'     => esc_html__( 'Title', "easywebsiteform" ),
                'ewf_shortcode' => esc_html__( 'Shortcode', "easywebsiteform" ),
                'ewf_author'    => esc_html__( 'Author', "easywebsiteform" ),
                'ewf_date'      => esc_html__( 'Date', "easywebsiteform" ),
                'ewf_preview'   => esc_html__( 'Preview', "easywebsiteform" ),
            ];
        }
        /**
         * Define sortable columns
         *
         * @return array[]
         */
        public function get_sortable_columns() {
            return [
                'ewf_title'  => ['ewf_title', false],
                'ewf_author' => ['ewf_author', false],
            ];
        }
        /**
         * Get the data for the table
         *
         * @return array
         */
        public function get_data() {
            $data = [];
            $api_key = get_option( 'ewform_key' ) ? get_option( 'ewform_key' ) : '';
            $formData = ewform_get_ewform_datas( $api_key )['data'];
            foreach ( $formData as $form ) {
                $date = strtotime( $form['created_at'] );
                $fid = $form['uid'];
                $title = $form['title'];
                $shortCodeValue = '[ewform id="' . $fid . '" title="' . $title . '"]';
                $data[] = [
                    'ewf_title'     => "<b>$title</b>",
                    'ewf_shortcode' => "<span class='shortcode copiable_wrap'>
                    <input type='text' onfocus='this.select();' readonly='readonly' value='$shortCodeValue' class='copiable_input' >
                    <span class='tooltip'>Click To Copy</span>
                    </span>",
                    'ewf_author'    => $form['client']['name'],
                    'ewf_date'      => gmdate( "jS F Y", $date ),
                    'ewf_preview'   => sprintf( "<a href='" . EWFORM_APPS_URL . "/form/%s' target='_black'>Open</a>", $form['uid'] ),
                ];
            }
            return $data;
        }
        /**
         * Display the default column
         *
         * @param $item
         * @param $column_name
         * @return mixed|void
         */
        public function column_default( $item, $column_name ) {
            return $item[$column_name];
        }
    }
}