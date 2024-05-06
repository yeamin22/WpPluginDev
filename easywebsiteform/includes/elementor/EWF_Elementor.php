<?php
// What are you trying to do?
if (!defined('ABSPATH')) {
    exit;
}

if(!class_exists("EWForm_Elementor")){
    class EWForm_Elementor{

        private static $instance;

        public static function Instance()
        {
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * @return void
         */
        public function init(){
            if ($this->met_requirement() && !empty(get_option('ewform_key'))) {         
                add_action('elementor/widgets/register', [$this, "ewform_elementor_shortcode"]);
            }
        }

        /**
         * @return bool
         */
        public function met_requirement()    {

            if (in_array('elementor/elementor.php', apply_filters('active_plugins', get_option('active_plugins')))) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * @param $widgets_manager
         * @return void
         */
        public function ewform_elementor_shortcode($widgets_manager)
        {
            require_once __DIR__ . '/widgets/ewform_widgets.php';
            $widgets_manager->register(new Ewform_Widgets());
        }    
    }
}
$instance = EWForm_Elementor::Instance();
$instance->init();