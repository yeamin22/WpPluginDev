<?php
class YHImporter {

    private $file;

    public function __construct( $file ) {
        $this->file = $file;  
    }

    public function import() {
        global $wpdb;
        $table_name = $wpdb->prefix.'posts';       
        $data = $this->parse_file();
        if ( ! empty( $data ) ) {
            foreach ( $data as $post_type => $posts ) {             
                if(empty($this->exists_id($posts['ID']))){
                    $wpdb->insert($table_name,$posts);                 
                }else{
                    echo $this->exists_id($posts['ID']);
                }
            }
        }
    }

    private function parse_file() {
        $file_type = wp_check_filetype( $this->file['name'] );
        if ( 'xml' == $file_type['ext'] ) {
            $data = $this->parse_xml_file();
        } elseif ( 'json' == $file_type['ext'] ) {
            $data = $this->parse_json_file();
        } else {       
            wp_die( 'Invalid file format. Please upload an XML or JSON file.' );
        }
        return $data;
    }

    private function parse_xml_file() {
        $xml = simplexml_load_file( $this->file );
        $data = array();
        foreach ( $xml->post_type as $post_type ) {
            $post_type_name = (string) $post_type['name'];
            if ( ! post_type_exists( $post_type_name ) ) {
                continue;
            }
            foreach ( $post_type->post as $post ) {
                $post_data = array();
                foreach ( $post->children() as $field ) {
                    $post_data[ $field->getName() ] = (string) $field;
                }
                $data[ $post_type_name ][] = $post_data;
            }
        }
        return $data;
    }

    private function parse_json_file() {
        $json = file_get_contents( $this->file['tmp_name'] );
        $data = json_decode( $json, true );
        return $data;
    }


    function exists_id($post_id){
        global $wpdb;
        $table_name = $wpdb->prefix.'posts';
        $row_id = $wpdb->get_var("SELECT ID FROM $table_name WHERE ID = '$post_id' LIMIT 1");
        return $row_id;
        
    }

}
