<?php
if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use \Elementor\Controls_Manager;
use \Elementor\Widget_Base;

class Wcs_GoogleMap extends Widget_Base {


    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);      
        wp_register_script( "yhwcs-scripts", WCS_GLMAP_ASSETS . "/js/scripts.js", ['elementor-frontend' ,'jquery','yhwcs-googlemap-api'], time(), true );     
  
     }
  

    /**
     * @return string
     */
    public function get_name() {
        return 'wcs-googlemap';
    }

    /**
     * @return string
     */
    public function get_title() {
        return esc_html__( 'Wcs Google Map', "easywebsiteform" );
    }

    /**
     * @return string
     */
    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    public function get_script_depends()
    {
        return ['yhwcs-scripts'];
    }

    /**
     * @return string[]
     */
    public function get_categories() {
        return ['basic'];
    }

    /**
     * @return void
     */
    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', "easywebsiteform" ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
			'wcs_glmap_marker',
			[
				'label' => esc_html__( 'Marker', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => [

                    [
						'name' => 'wcs_glmap_marker_icon',
                        'label' => esc_html__( 'Choose Image', 'textdomain' ),
                        'type' => \Elementor\Controls_Manager::MEDIA,
                        'default' => [
                            'url' => WCS_GLMAP_ASSETS . "/img/location.png",
                        ],
					],                
                    
                    [
                        'name' => 'wcs_glmap_marker_link',
                        'label' => esc_html__( 'Link', 'textdomain' ),
                        'type' => \Elementor\Controls_Manager::URL,
                        'options' => [ 'url', 'is_external', 'nofollow' ],
                        'default' => [
                            'url' => '',
                            'is_external' => true,
                            'nofollow' => true,
                            // 'custom_attributes' => '',
                        ],
                        'label_block' => true,
                    ] ,                   
					[
						'name' => 'wcs_glmap_marker_title',
						'label' => esc_html__( 'Title', 'textdomain' ),
						'type' => \Elementor\Controls_Manager::TEXT,
						'default' => esc_html__( 'List Title' , 'textdomain' ),
						'label_block' => true,
					],
					[
						'name' => 'wcs_glmap_marker_content',
						'label' => esc_html__( 'Content', 'textdomain' ),
						'type' => \Elementor\Controls_Manager::WYSIWYG,
						'default' => esc_html__( 'List Content' , 'textdomain' ),
						'show_label' => false,
					],
                    [
						'name' => 'wcs_glmap_marker_lat',
						'label' => esc_html__( 'Latitude', 'textdomain' ),
						'type' => \Elementor\Controls_Manager::TEXT,						
						'show_label' => false,
					],
                    [
						'name' => 'wcs_glmap_marker_lng',
						'label' => esc_html__( 'Longitude', 'textdomain' ),
						'type' => \Elementor\Controls_Manager::TEXT,						
						'show_label' => false,
					],
				],
				'default' => [
					[
						'wcs_glmap_marker_title' => esc_html__( 'Title #1', 'textdomain' ),	
					],					
				],
				'title_field' => '{{{ wcs_glmap_marker_title }}}',
			]
		);

        $this->end_controls_section();
    }

    /**
     * @return void
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $marker = $settings['wcs_glmap_marker'];     
        $marker = json_encode($marker);    
        ?>
            <div id="wcs_google_map" data-json="<?php echo htmlspecialchars($marker); ?>" style="height: 600px;width:100%"></div>
        <?php

        

    }
}