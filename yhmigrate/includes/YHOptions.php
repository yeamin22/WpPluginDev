<?php
require_once "YHImporter.php";
class YHOptions
{

    function __construct()
    {
        add_action('admin_menu', [$this, 'yhexport_options']);
        add_action('admin_post_custom_post_type_export', [$this, 'custom_post_type_export']);
    }

    function yhexport_options()
    {
        add_menu_page(
            'Export Post Type',
            'Export Post Type',
            'manage_options',
            'yh_options',
            array($this, 'custom_post_type_export_form'),
            'dashicons-download',
            100
        );

        add_submenu_page(
            'yh_options',
            'Yh Import',
            'Yh Import',
            'manage_options',
            'yh_options_import',
            array($this,'custom_post_type_import_form')

        );
    }

    function custom_post_type_export_form()
    {
?>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="custom_post_type_export">
            <label for="post-type">Select Post Type:</label>
            <select name="post-type" id="post-type">
                <?php
                $post_types = get_post_types();
                foreach ($post_types as $post_type) {
                    echo '<option value="' . $post_type . '">' . $post_type . '</option>';
                }
                ?>
            </select>
            <br>
            <input type="submit" name="submit" value="Export">
        </form>
<?php
    }


    function custom_post_type_export(){
        if (!current_user_can('manage_options')) {
            return;
        }
        if (!isset($_POST['post-type']) || empty($_POST['post-type'])) {
            return;
        }
        $post_type = sanitize_text_field($_POST['post-type']);
        $args = array(
            'post_type' => $post_type,
            'posts_per_page' => -1,
        );
        $posts = get_posts($args);
        $export_data = array();
        foreach ($posts as $post) {
            $post_data = array(
                'ID' => $post->ID,
                'post_author' => $post->post_author,
                'post_date' => $post->post_date,
                'post_title' => $post->post_title,
                'post_content' => $post->post_content,
                'post_status' => $post->post_status,
                'post_name' => $post->post_name,                
                'post_type' => $post->post_type,                
            );
            $export_data[] = $post_data;
        }
        $export_data_json = json_encode($export_data);
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $post_type . '_export.json"');
        header('Pragma: no-cache');
        echo $export_data_json;
        exit;
    }

    /* YH Importer */
    function custom_post_type_import_form() {
        if ( isset( $_POST['custom_post_type_import'] ) && $_POST['custom_post_type_import'] == 'import' ) {
            if ( ! wp_verify_nonce( $_POST['custom_post_type_import_nonce'], 'custom_post_type_import' ) ) {
                wp_die( 'Security check failed. Please try again.' );
            }
            $file = $_FILES['import-file'];
            if ( ! $file ) {
                wp_die( 'Please select a file to import.' );
            }
            $importer = new YHImporter( $file );
            $importer->import();
            echo '<div class="notice notice-success"><p>Custom post type data imported successfully!</p></div>';
        }
        ?>
        <form method="post" enctype="multipart/form-data" action="">
            <?php wp_nonce_field( 'custom_post_type_import', 'custom_post_type_import_nonce' ); ?>
            <input type="hidden" name="custom_post_type_import" value="import">
            <label for="import-file">Select File:</label>
            <input type="file" name="import-file" id="import-file">
            <br>
            <input type="submit" name="submit" value="Import">
        </form>
        <?php
    }
    



}

new YHOptions();
