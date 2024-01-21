<?php

if ( !defined( "ABSPATH" ) ) {
    exit;
}

if ( !class_exists( "WDFE_Widgets" ) ) {

    class WDFE_Widgets {

        public function __construct() {
            add_action( 'elementor/widgets/register', [$this, 'wdfe_register_widgets'] );
            add_action( 'elementor/elements/categories_registered', [$this, 'wdfe_widget_category'] );

            add_action( 'elementor/controls/register', [$this,'register_wdfe_textemoji'] );
        }

        public function wdfe_register_widgets( $widgets_manager ) {
            require_once 'widgets/Animated_Text.php';
            $widgets_manager->register( new \Animated_Text() );
        }
        public function wdfe_widget_category($category){
            $category->add_category(
                'wdfe_widget_cat',
                [
                    'title' => esc_html__( 'Widget Designs Addons', WDFTEXTDOMAIN),
                    'icon' => 'fa fa-plug',
                ]
            );
        }
        public function register_wdfe_textemoji( $controls_manager ) {

            require_once( __DIR__ . '/widgets/controls/Wdfe_TextEmoji.php' );
        
            $controls_manager->register( new \Wdfe_TextEmoji() );
        
        }

    }
}

