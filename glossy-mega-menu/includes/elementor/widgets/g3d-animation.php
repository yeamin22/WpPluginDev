<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use \Elementor\Controls_Manager;
use \Elementor\Widget_Base;

class G3d_Animation_Widget extends Widget_Base
{
    
    /**
     * @return string
     */
    public function get_name()
    {
        return 'g3d_animation';
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('3d Images', "easywebsiteform");
    }

    /**
     * @return string
     */
    public function get_icon()
    {
        return 'eicon-form-horizontal';
    }

    /**
     * @return string
     */
    public function get_custom_help_url()
    {
        return 'https://developers.elementor.com/docs/widgets/';
    }
    public function get_style_depends() {
		return [ 'g3d-style'];
	}
    public function get_script_depends() {
		return [ 'g3d-script',"g3d-simpleparallax"];
	}
    /**
     * @return string[]
     */
    public function get_categories()
    {
        return ['basic'];
    }

    /**
     * @return string[]
     */
    public function get_keywords()
    {
        return ['oembed', 'url', 'link'];
    }

    /**
     * @return void
     */
    protected function register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', "easywebsiteform"),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

    

        $this->end_controls_section();
    }

  
   // style="will-change: transform; transform: translate3d(0px, -300.134px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(6deg) skew(0deg, 0deg); transform-style: preserve-3d;"
    /**
     * @return void
     */
    protected function render(){
        $settings = $this->get_settings_for_display();
        ?>
        <div class="product-integration-logo-container">
            <div class="w-layout-grid grid-1x2 integration-logos absolute left-row scroll-up" id="g3d_implement" style="transform-style: preserve-3d;">
                <div class="integration-logo-card large round"><img
                        src="https://www.easywebsiteform.com/wp-content/uploads/2024/04/wordpress.webp" alt=""></div>
                <div class="integration-logo-card large round"><img
                        src="https://www.easywebsiteform.com/wp-content/uploads/2024/04/shopify.webp" alt=""></div>
                <div class="integration-logo-card large round"><img
                        src="https://www.easywebsiteform.com/wp-content/uploads/2024/04/joomla.webp" alt=""></div>
                <div class="integration-logo-card large round"><img
                        src="https://www.easywebsiteform.com/wp-content/uploads/2024/04/squarespace.webp" alt=""></div>
                <div class="integration-logo-card large round"><img
                        src="https://www.easywebsiteform.com/wp-content/uploads/2024/04/laravel.webp" alt=""></div>
                <div class="integration-logo-card large round"><img
                        src="https://www.easywebsiteform.com/wp-content/uploads/2024/04/drupal.webp" alt=""></div>
                <div class="integration-logo-card large round"><img
                        src="https://www.easywebsiteform.com/wp-content/uploads/2024/04/elementor.webp" alt=""></div>
                
                        
            </div>
            
            
            <div class="w-layout-grid grid-1x2 integration-logos absolute left-row scroll-down" id="g3d_implement2" style="transform-style: preserve-3d;">
                <div class="integration-logo-card large round"><img
                        src="https://www.easywebsiteform.com/wp-content/uploads/2024/04/elementor.webp" alt=""></div>
                <div class="integration-logo-card large round"><img
                        src="https://www.easywebsiteform.com/wp-content/uploads/2024/04/divi_thame.webp" alt=""></div>
                <div class="integration-logo-card large round"><img
                        src="https://www.easywebsiteform.com/wp-content/uploads/2024/04/shopify.webp" alt=""></div>
                <div class="integration-logo-card large round"><img
                        src="https://www.easywebsiteform.com/wp-content/uploads/2024/04/elementor.webp" alt=""></div>
                
                <div class="integration-logo-card large round"><img
                        src="https://www.easywebsiteform.com/wp-content/uploads/2024/04/squarespace.webp" alt=""></div>
                <div class="integration-logo-card large round"><img
                        src="https://www.easywebsiteform.com/wp-content/uploads/2024/04/joomla.webp" alt=""></div>
                <div class="integration-logo-card large round"><img
                        src="https://www.easywebsiteform.com/wp-content/uploads/2024/04/wordpress.webp" alt=""></div>
                <!-- <div class="integration-logo-card large round"><img
                        src="https://www.easywebsiteform.com/wp-content/uploads/2024/04/bootstrap.webp" alt=""></div> -->
                
            </div>
            
            
        </div>
<?php
    }
}