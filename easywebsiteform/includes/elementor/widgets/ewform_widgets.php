<?php
if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
use \Elementor\Controls_Manager;
use \Elementor\Widget_Base;

class Ewform_Widgets extends Widget_Base {

    /**
     * @return string
     */
    public function get_name() {
        return 'ewform_shortcode';
    }

    /**
     * @return string
     */
    public function get_title() {
        return esc_html__( 'Easy Website Form', "easywebsiteform" );
    }

    /**
     * @return string
     */
    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    /**
     * @return string[]
     */
    public function get_categories() {
        return ['basic'];
    }

    /**
     * @return string[]
     */
    public function get_keywords() {
        return ['oembed', 'url', 'link'];
    }

    /**
     * @return void
     */
    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Forms', "easywebsiteform" ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'select_ewforms',
            [
                'label'       => esc_html__( 'Select Form', "easywebsiteform" ),
                'label_block' => true,
                'type'        => Controls_Manager::SELECT,
                'default'     => 'select_option',
                'options'     => $this->getEwforms(),
            ]
        );

        $this->end_controls_section();
    }

    /**
     * @return array
     */
    public function getEwforms() {
        $api_key = get_option( "ewform_key" );
        $forms = ewform_get_ewform_datas( $api_key )['data'];
        $selectOption = [];
        $selectOption['select_option'] = esc_html__( "Select Form", "easywebsiteform" );
        foreach ( $forms as $form ) {
            $selectOption[$form['uid']] = $form['title'];
        }
        return $selectOption;
    }

    /**
     * @return void
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $fid = $settings['select_ewforms'];
        if ( "select_option" != $fid ) {
            echo do_shortcode( "[ewform id='$fid' title='']" );
        }else{
            printf("<h4>%s:</h4>",esc_html__("Please select a form",'easywebsiteform'));
        }
    }
}