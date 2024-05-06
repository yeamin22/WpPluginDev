<?php
// What are you trying to do?
if (!defined('ABSPATH')) {
    exit;
}

if(!class_exists("Yhwcs_Elementor")){
    class Yhwcs_Elementor{

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
            if ($this->met_requirement()) {         
                add_action('elementor/widgets/register', [$this, "yhwcs_register_widget"]);
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
        public function yhwcs_register_widget($widgets_manager)
        {
            require_once __DIR__ . '/widgets/wcs-google-map.php';
            $widgets_manager->register(new Wcs_GoogleMap());
        }    
    }
}
$instance = Yhwcs_Elementor::Instance();
$instance->init();