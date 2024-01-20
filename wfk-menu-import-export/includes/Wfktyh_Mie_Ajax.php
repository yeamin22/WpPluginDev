<?php
namespace WFKTYH_MIE;

/* Ajax Controler */
class Wfktyh_Mie_Ajax {

    public $exportmodel = null;

    public function __construct() {
        add_action( "wp_ajax_select_menu_import_export", [__CLASS__, "func_select_menu_import_export" ]);
        add_action( "wp_ajax_wfktyh_download_export_file", [__CLASS__, "func_wfktyh_download_export_file"] );
        add_action( "wp_ajax_wfktyh_import_file", [__CLASS__, "func_wfktyh_import_file"] );
    }

    /* Menu Select for export ajax */
    public function func_select_menu_import_export() {
        if(! empty($_POST['menuId'])){
            $menu_id = (int) $_POST['menuId'];  
            $menu_items = wp_get_nav_menu_items( $menu_id );
            ob_start();
            foreach ( $menu_items as $menu_item ) {
                ?>
                   <li><a href='<?php echo esc_url($menu_item->url); ?>'> <?php esc_html_e ($menu_item->title); ?> </a></li>
                <?php       
            }
            $html = ob_get_clean();
            wp_send_json_success( wp_kses_post ($html) );            
        }
    }

    /* Download Export File */
    public function func_wfktyh_download_export_file() {        
        $menu_id = (int) $_POST['menu_id'];
        if ( isset( $menu_id ) && $menu_id != '' && is_numeric( $menu_id ) ) {
            $menuid = $menu_id;
            $menuobj = get_term_by( 'id', $menuid, 'nav_menu' );
            if ( isset( $menuobj->slug ) && !empty( $menuobj ) ) {
                $menuname = $menuobj->slug;
            } else {
                $menuname = $menu_id;
            }
            $navitems = wp_get_nav_menu_items( $menuid );
            if ( is_array( $navitems ) && !empty( $navitems ) ) {
                if ( !isset( $data ) ) {
                    $data = [];
                }
                $count = 0;
                foreach ( $navitems as $singlenav ) {
                    $navmetas = get_post_meta( $singlenav->ID );
                    $data[$count]['post'] = $singlenav;
                    $data[$count]['post_metas'] = $navmetas;
                    $count++;
                }
                $data = json_encode( $data, JSON_PRETTY_PRINT);
                ob_clean();
                
                header( 'Content-Type: application/json; charset=utf-8' );
                header( 'Content-Disposition: attachment; filename="wp_menus_backup.json"' );
                header('Content-Length: ' . strlen($data));
            
               echo wp_kses_post($data);
               exit();
            } else {
                $this->exportmodel = 2; //No menu items were found.
            }
            } else {
                $this->exportmodel = 3; //Please select navigation.
            }
              
    }

    /* Import Menus File */

    public function func_wfktyh_import_file(){
        check_ajax_referer("security",'import_nonce');
        
       $message = self::formFileValidate($_FILES['menu_file']);

       if(!empty($message)){
            wp_send_json_error($message);
       }

       if ( empty( $_FILES['fileurl'] ) ) {
       
                $upload_dir = \wp_get_upload_dir();

                $upload_path = $upload_dir["basedir"] . "/menu-import-export/";

                if ( ! \file_exists( $upload_path ) ) {
                    if ( ! \mkdir( $upload_path, 0777, true ) ) {         
                        $message = __("You don't have permission to make folder","wfktyh-menu-import-export");
                        wp_send_json_error($message);
                    }
                }
                if ( ! \function_exists( 'wp_handle_upload' ) ) {
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                }
                $upload_overrides = array( 'test_form' => false,'action'=> 'wfktyh_import_file' );


                add_filter( 'upload_mimes', [__CLASS__, 'customMimeTypes'], 1, 1 );
                add_filter( 'upload_dir', [__CLASS__, 'menusExportImportDir'] );
                add_filter( 'map_meta_cap', [__CLASS__, 'menusUnfilteredUpload'], 0, 2 );
                $movefile = \wp_handle_upload( $_FILES['menu_file'], $upload_overrides);
                remove_filter( 'upload_mimes', [__CLASS__, 'customMimeTypes'], 1, 1 );
                remove_filter( 'upload_dir', [__CLASS__, 'menusExportImportDir'] );
                remove_filter( 'map_meta_cap', [__CLASS__, 'menusUnfilteredUpload'] );

                $menuname = ucfirst(sanitize_text_field( $_POST['menuName'] ));


                $curntMenuPos = ! empty( $requested_vars['curntmenupos'] ) ? sanitize_text_field( $requested_vars['curntmenupos'] ) : 0;
                $uploadFileUrl = '';
                if ( $movefile && !isset( $movefile['error'] ) ) { 
                    $uploadFileUrl =  esc_url( $movefile['url']);  
                } else {            
                    wp_send_json_error([$movefile['error'],"error"]);
                }
                $menuId = 0;
                if ( $menuId == 0 ) {
                    $menuexists = \wp_get_nav_menu_object( $menuname );
                } else {
                    $menuexists = '';
                }

                if(! $menuexists ){
                    $file_content = json_decode( self::getFileContent($uploadFileUrl));

                    if( is_array($file_content) && ! empty($file_content) ){
                        $navMenuItemNum = count($file_content);
                        if ( $menuId == 0 ) {
                             $menuId = wp_create_nav_menu( $menuname );
                          }
                        $oldPostIds = [];
                        foreach($file_content as $postMetas){                                                 
                             array_push($oldPostIds, $postMetas->post->ID);                               
                            $postMetas->post->ID = '';                            
                            $post = (array) $postMetas->post;
                            $custom_meta_datas =  self::updatePostMeta($postMetas->post_metas); 
                            $custom_meta_datas['menu-item-title'] = $postMetas->post->post_title;
                            $post_id = wp_insert_post( $post, true );
                           $menuUpdatestatus = wp_update_nav_menu_item($menuId,$post_id,$custom_meta_datas);
                        }          
                        $args  = [
                            'action' => 'edit',
                            'menu' => $menuId
                        ];
                        $url = add_query_arg( $args, site_url("/wp-admin/nav-menus.php"));

                        self::deleteMenuFile($uploadFileUrl);
                        
                        wp_send_json_success($url);

                    }else{
                        wp_send_json_error(__("There was something wrong.","wfktyh-menu-import-export"));
                    }
                   
                }else{
                    wp_send_json_error(__("Please select a unique menu, the given menu name exists.","wfktyh-menu-import-export"));
                }

       
        }


      

     //Ajax Request End
    }

    /* Update Created post meta */

    public static function updatePostMeta( $post_metas){       
        $custom_meta_datas = [];    
        foreach($post_metas as $key => $value){
            $pos = stripos( $key, '_' );
            if ( $pos === 0 ) {
                $custom_key = substr( $key, 1 );
            } else {
                $custom_key = $key;
            }          
            $custom_key = str_replace( '_', '-', $custom_key );

            if ( $custom_key == 'menu-item-classes' ) {
                if ( is_serialized( $value[0] ) && ! empty( unserialize( $value[0] ) ) ) {
                    $temp = unserialize( $value[0] );
                    if ( is_array( $temp ) && ! empty( $temp ) ) {
                        $temp = implode( " ", $temp );
                    } else {
                        $temp = $value[0];
                    }
                    $custom_meta_datas[$custom_key] = $temp;
                } else {
                    $custom_meta_datas[$custom_key] = $value[0];
                }               
            }else{
                $custom_meta_datas[$custom_key] = $value[0];
            }      
        }        
        return $custom_meta_datas;

    }
    /**     
     * @param $file accepts as the arguments
     * the validate the file
     */

    public static function formFileValidate($file){
        $message = '';
        if ( ! \current_user_can( 'upload_files' ) ) {
                $message = __("You don't have permission to upload a file.","wfktyh-menu-import-export");  
        }        
        if ( isset( $file['name'] ) ) {
            $wp_filetype = \wp_check_filetype( $file['name'], [ 'json' => 'application/json' ] );
            if ( ! \wp_match_mime_types( 'application/json', $wp_filetype['type'] ) ) {
                $message = __("Please upload your menu json file!","wfktyh-menu-import-export");   
            }
        }
        return $message;
    }
    /*
    * Delete the upoaded json file after menu creation
    */
    public function deleteMenuFile( $url ) {
        if ( $url != '' ) {
            $menu_name = wp_basename( $url );
            $uploadsdir = wp_get_upload_dir();
            $menu_url = $uploadsdir['basedir'] . '/menu-import-export/' . $menu_name;
            wp_delete_file( $menu_url );
        }
    }


    /**
     *
     * @param $param accepts as the arguments
     * returns the upload directory
     */
    public function menusExportImportDir( $param ) {
        $mydir = '/menu-import-export';
        $param['subdir'] = $mydir;
        $param['path'] = $param['basedir'] . $mydir;
        $param['url'] = $param['baseurl'] . $mydir;
        return $param;
    }

    /* 
        Get content from file
    */

    public static function getFileContent($fileUrl){

        if ( function_exists( 'file_get_contents' ) ) {
            $file_content = file_get_contents( $fileUrl );

            if ( empty( $file_content ) ) {
                $file_content = file_get_contents( \str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $file_content ) );
            }
            return $file_content;
        }
    }

  
    /*
    *Assign JSON upload capability to the logged in user
    */
    public function menusUnfilteredUpload( $caps, $cap ) {
        if ( $cap == 'unfiltered_upload' ) {
            $caps = [];
            $caps[] = $cap;
        }
        return $caps;
    }

    /**
    *
    * returns the array of the json type
    */
    public function customMimeTypes( $mimeTypes ) {
        $new_mime_type = [ 'json' => 'application/json' ];
        return $new_mime_type;
    }

}
