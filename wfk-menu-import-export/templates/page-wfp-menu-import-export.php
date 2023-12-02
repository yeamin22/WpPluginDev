<?php
// Retrieve menu locations.
if ( current_theme_supports( 'menus' ) ) {
    $locations = get_registered_nav_menus();
    $menu_locations = get_nav_menu_locations();
}
$nav_menus = wp_get_nav_menus();
?>
<div class="wrap">
    <h1></h1>
</div>
<div class="wrap">
    <div class="wfp_menu_import_export_container">
        <h1><?php _e("Menu Import Export","wfk-menu-import-export"); ?></h1>

        <div class="wfk_vertical_tabs_container">
            <div class="tabs-btn mie-lg-4">
                <ul class="nav nav-pills">
                    <li class="tab-button active" >
                        <a href="#"  data-tab="wfk-export-content"><?php _e("Export","wfk-menu-import-export") ?></a>
                    </li>
                    <li class="tab-button">
                        <a href="#"  data-tab="wfk-import-content"><?php _e("Import","wfk-menu-import-export") ?></a>
                    </li>
                </ul>
            </div>

            <div class="mfk-tabconten_wrap mie-lg-8">
                <div id="wfk-export-content" class="mfk-mie_tabcontent default">
                    <div class="wfp_importexport card">
                        <label for="select-menu-import-export" class="selected-menu"
                            style="display: block;"><?php _e( 'Select a menu to Export:' );?></label>

                        <?php 
                        if(is_array($nav_menus) && !empty($nav_menus)){
                        ?>
                        <form action="" method="post">

                     
                            <select name="menu" id="select-menu-import-export" class="wfp-mie-select">
                                <option value=""><?php esc_html_e( "Select Menu", "wfk-menu-import-export" )?></option>
                                <?php if(is_array($nav_menus)){ foreach (  $nav_menus as $_nav_menu ): ?>
                                <option value="<?php echo esc_attr( $_nav_menu->term_id ); ?>">
                                    <?php
                                        echo esc_html( $_nav_menu->name );
                                        if ( !empty( $menu_locations ) && in_array( $_nav_menu->term_id, $menu_locations, true ) ) {
                                            $locations_assigned_to_this_menu = [];

                                            foreach ( array_keys( $menu_locations, $_nav_menu->term_id, true ) as $menu_location_key ) {
                                                if ( isset( $locations[$menu_location_key] ) ) {
                                                    $locations_assigned_to_this_menu[] = $locations[$menu_location_key];
                                                }
                                            }
                                            $locations_listed_per_menu = absint( apply_filters( 'wp_nav_locations_listed_per_menu', 3 ) );
                                            $assigned_locations = array_slice( $locations_assigned_to_this_menu, 0, $locations_listed_per_menu );
                                            // Adds ellipses following the number of locations defined in $assigned_locations.
                                            if ( !empty( $assigned_locations ) ) {
                                                printf(
                                                    ' (%1$s%2$s)',
                                                    implode( ', ', $assigned_locations ),
                                                    count( $locations_assigned_to_this_menu ) > count( $assigned_locations ) ? ' &hellip;' : ''
                                                );
                                            }
                                        }
                                    ?>
                                </option>
                                <?php endforeach; }?>
                            </select>
                            <?php
                                $menu_items = wp_get_nav_menu_items( "main-menu" );
                            ?>
                            <div class="wfk_menuie_lists">
                                <ul id="export-menu_item" class="wfk-menu-item">
                                <?php 
                                if(is_array($menu_items)){
                                    foreach ( $menu_items as $menu_item ) {
                                        printf( "<li><a href='%s'>%s</a></li>", $menu_item->url, $menu_item->title );
                                    }
                                }
                                ?>
                                </ul>
                                <a href="#" class="wfk_menu_export_btn" id="wfk_menu_export_btn"><?php _e("Export","wfk-menu-import-export") ?></a>
                            </div>
                        </form>
                        <?php }else{
                            printf("<p>There is no nav menu for export!</p>");
                        } ?>
                    </div>
                </div>
                <div id="wfk-import-content" class="mfk-mie_tabcontent">
                    <div class="container">
                        <div class="card">
                            <h3><?php _e("Upload Menu Json File","wfk-menu-import-export") ?></h3>
                            <div class="warning"></div>
                            <form  id="wfk_mie_import_form" method="post" enctype="multipart/form-data">
                                     <div class="drop_box">
                                     <input type="file" hidden accept=".json" name="mie_import_file" id="menuJsonFile" style="display:none;">
                                        <header id="fileHeaderText">
                                            <h4><?php _e("Select File here","wfk-menu-import-export") ?></h4>
                                        </header>
                                        <div class="menu_name_input">
                                             <p><?php _e("Files Supported Only Json","wfk-menu-import-export") ?></p>    
                                        </div>
                                        <button class="btn chooseMenuFile"><?php _e("Choose File","wfk-menu-import-export") ?></button>
                                    </div>
                                <button type="submit" class="wfk_menu_import_btn" id="wfk_menu_import_btn"><?php _e("Import","wfk-menu-import-export") ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>