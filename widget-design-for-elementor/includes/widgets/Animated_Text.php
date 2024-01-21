<?php
if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
use \Elementor\Controls_Manager;
use \Elementor\Widget_Base;

/**
 * Elementor List Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */

if ( !class_exists( 'Animated_Text' ) ) {
    class Animated_Text extends Widget_Base {

        public function __construct( $data = [], $args = null ) {
            parent::__construct( $data, $args );

            wp_register_script( 'at_typed_js', WDFE_PLUGIN_URI . "assets/js/lib/typed.umd.js", ['elementor-frontend'], '1.0.0', true );
            wp_register_script( 'wdfe_main', WDFE_PLUGIN_URI . "assets/js/wdfe-main.js", ['elementor-frontend', 'at_typed_js'], '1.0.0', true );

        }

        /**
         * Get widget name.
         *
         * Retrieve list widget name.
         *
         * @since 1.0.0
         * @access public
         * @return string Widget name.
         */
        public function get_name() {
            return 'wdfe_animated_text';
        }

        /**
         * Get widget title.
         *
         * Retrieve list widget title.
         *
         * @since 1.0.0
         * @access public
         * @return string Widget title.
         */
        public function get_title() {
            return esc_html__( 'Animated Text', WDFTEXTDOMAIN );
        }

        /**
         * Get widget icon.
         *
         * Retrieve list widget icon.
         *
         * @since 1.0.0
         * @access public
         * @return string Widget icon.
         */
        public function get_icon() {
            return 'eicon-bullet-list';
        }

        /**
         * Get custom help URL.
         *
         * Retrieve a URL where the user can get more information about the widget.
         *
         * @since 1.0.0
         * @access public
         * @return string Widget help URL.
         */
        public function get_custom_help_url() {
            return 'https://developers.elementor.com/docs/widgets/';
        }

        /**
         * Get widget categories.
         *
         * Retrieve the list of categories the list widget belongs to.
         *
         * @since 1.0.0
         * @access public
         * @return array Widget categories.
         */
        public function get_categories() {
            return ['wdfe_widget_cat'];
        }

        /**
         * Get widget keywords.
         *
         * Retrieve the list of keywords the list widget belongs to.
         *
         * @since 1.0.0
         * @access public
         * @return array Widget keywords.
         */
        public function get_keywords() {
            return ['list', 'lists', 'ordered', 'unordered'];
        }

        public function get_script_depends() {
            return ['at_typed_js', 'wdfe_main'];
        }

        /**
         * Register list widget controls.
         *
         * Add input fields to allow the user to customize the widget settings.
         *
         * @since 1.0.0
         * @access protected
         */
        protected function register_controls() {

            /* Content Tab */
            $this->start_controls_section(
                'animate_text_sec',
                [
                    'label' => esc_html__( 'Animate Text', WDFTEXTDOMAIN ),
                    'tab'   => Controls_Manager::TAB_CONTENT,
                ]
            );
            $this->add_control(
                'wdfe_at_starting_text',
                [
                    'label'       => esc_html__( 'Starting Text', WDFTEXTDOMAIN ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Starting Text', WDFTEXTDOMAIN ),
                    'default'     => esc_html__( 'I am Expert in:', WDFTEXTDOMAIN ),
                    'label_block' => true,
                    'dynamic'     => [
                        'active' => true,
                    ],
                ]
            );

            /* Start repeater */

            $repeater = new \Elementor\Repeater();

            $repeater->add_control(
                'at_typed_string',
                [
                    'label'       => esc_html__( 'Wordpress', WDFTEXTDOMAIN ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'List Item', WDFTEXTDOMAIN ),
                    'label_block' => true,
                    'dynamic'     => [
                        'active' => true,
                    ],
                ]
            );

            /* End repeater */

            $this->add_control(
                'at_typed_strings',
                [
                    'label'       => esc_html__( 'Typed Strings', WDFTEXTDOMAIN ),
                    'type'        => Controls_Manager::REPEATER,
                    'fields'      => $repeater->get_controls(), /* Use our repeater */
                    'default' => [
                        [
                            'at_typed_string' => esc_html__( 'Frist Word!', WDFTEXTDOMAIN ),
                        ],
                        [
                            'at_typed_string' => esc_html__( 'Second Word!', WDFTEXTDOMAIN ),
                        ],
                    ],
                    'title_field' => '{{{ at_typed_string }}}',
                ]
            );

            $this->add_control(
                'wdfe_at_ending_text',
                [
                    'label'       => esc_html__( 'Ending Text', WDFTEXTDOMAIN ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Ending Text', WDFTEXTDOMAIN ),
                    'default'     => esc_html__( 'Ending Text', WDFTEXTDOMAIN ),
                    'label_block' => true,
                    'dynamic'     => [
                        'active' => true,
                    ],
                ]
            );


            $this->end_controls_section();
            /* Contents */

            /* Content Tab */
            $this->start_controls_section(
                'animated_text_settings',
                [
                    'label' => esc_html__( 'Animate Text Settings', WDFTEXTDOMAIN ),
                    'tab'   => Controls_Manager::TAB_CONTENT,
                ]
            );
            $this->add_control(
                'animated_text_alignment',
                [
                    'label'     => esc_html__( 'Alignment', WDFTEXTDOMAIN ),
                    'type'      => Controls_Manager::CHOOSE,
                    'options'   => [
                        'left'   => [
                            'title' => esc_html__( 'Left', WDFTEXTDOMAIN ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', WDFTEXTDOMAIN ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right'  => [
                            'title' => esc_html__( 'Right', WDFTEXTDOMAIN ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'default'   => 'center',
                    'toggle'    => true,
                    'selectors' => [
                        '{{WRAPPER}} .animated_text_wrap' => 'text-align: {{VALUE}};',
                    ],
                ]
            );
            
            $this->add_control(
                'wdfe_at_cursor',
                [
                    'label' => esc_html__( 'Typed Cursor', WDFTEXTDOMAIN ),
                    'type' => Controls_Manager::SELECT,
                    'default' => '|',
                    'options' => [
                        '|' => esc_html__( 'Default', WDFTEXTDOMAIN ),
                        '' => esc_html__( 'None', WDFTEXTDOMAIN ),
                        '...' => esc_html__( '...', WDFTEXTDOMAIN ),
                        '_' => esc_html__( '_', WDFTEXTDOMAIN ),
                        ':-' => esc_html__( ':-', WDFTEXTDOMAIN ),

                    ],
                ]
            );

            
            $this->add_control(
                'wdfe_at_typing_speed_set',
                [
                    'label'       => esc_html__( 'Typing Speed', WDFTEXTDOMAIN ),
                    'type'        => Controls_Manager::NUMBER,                    
                    'default'     => 50,               
                    'dynamic'     => [
                        'active' => true,
                    ],
                ]
            );
            $this->add_control(
                'wdfe_at_delayonchange_set',
                [
                    'label'       => esc_html__( 'Change Delay', WDFTEXTDOMAIN ),
                    'type'        => Controls_Manager::NUMBER,                    
                    'default'     => 2500,               
                    'dynamic'     => [
                        'active' => true,
                    ],
                ]
            );

            $this->end_controls_section();


            /*Starting Style*/
            $this->start_controls_section(
                'at_starting_text_style',
                [
                    'label' => esc_html__( 'Starting Text', WDFTEXTDOMAIN ),
                    'tab'   => Controls_Manager::TAB_STYLE,
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(), [
                    'name'           => 'content_typography',
                    'fields_options' => [
                        'font_size'   => ['default' => ['size' => 22]],
                        'font_weight' => ['default' => 600],
                        'typography'  => ['default' => 'yes'],
                    ],
                    'selector'       => '{{WRAPPER}} .animated_text_wrap',
                ]
            );

            $this->add_control(
                'animated_text_color',
                [
                    'label'     => esc_html__( 'Animated Text Color', WDFTEXTDOMAIN ),
                    'type'      => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .animated_text_wrap' => 'color: {{VALUE}}',
                    ],
                    'default'   => "#2B51BE",
                ]
            );

            $this->end_controls_section();

            /* End Starting Style */

            /*Animated Text Style*/
            $this->start_controls_section(
                'at_strings_style',
                [
                    'label' => esc_html__( 'Animated Strings', WDFTEXTDOMAIN ),
                    'tab'   => Controls_Manager::TAB_STYLE,
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name'     => 'animated_strings_background',
                    'types'    => ['classic', 'gradient', 'video'],
                    'selector' => '{{WRAPPER}} .at_typed_text',
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(), [
                    'name'           => 'at_strings_typo',
                    'fields_options' => [
                        'font_size'   => ['default' => ['size' => 22]],
                        'font_weight' => ['default' => 600],
                        'typography'  => ['default' => 'yes'],
                    ],
                    'selector'       => '{{WRAPPER}} .animated_text_wrap',
                ]
            );

            $this->add_control(
                'at_strings_color',
                [
                    'label'     => esc_html__( 'Animated Text Color', WDFTEXTDOMAIN ),
                    'type'      => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .at_typed_text' => 'color: {{VALUE}}',
                    ],
                    'default'   => "#2B51BC",
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name'     => 'at_strings_border',
                    'selector' => '{{WRAPPER}} .at_typed_text',
                ]
            );
            $this->add_control(
                'at_strings_border_radius',
                [
                    'label'      => esc_html__( 'Border Radius', WDFTEXTDOMAIN ),
                    'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                    'selectors'  => [
                        '{{WRAPPER}} .at_typed_text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_control(
                'at_strings_padding',
                [
                    'label'      => esc_html__( 'Padding', WDFTEXTDOMAIN ),
                    'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                    'selectors'  => [
                        '{{WRAPPER}} .at_typed_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_control(
                'at_strings_margin',
                [
                    'label'      => esc_html__( 'Margin', WDFTEXTDOMAIN ),
                    'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                    'selectors'  => [
                        '{{WRAPPER}} .at_typed_text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_section();

            $this->start_controls_section(
                'at_cursor_style',
                [
                    'label' => esc_html__( 'Typing Cursor', WDFTEXTDOMAIN ),
                    'tab'   => Controls_Manager::TAB_STYLE,
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(), [
                    'name'           => 'at_typing_cursor_typo',
                    'selector'       => '{{WRAPPER}} .typed-cursor',
                ]
            );

            $this->add_control(
                'at_typing_cursor_color',
                [
                    'label'     => esc_html__( 'Cursor Color', WDFTEXTDOMAIN ),
                    'type'      => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .typed-cursor' => 'color: {{VALUE}}',
                    ],
                    'default'   => "#000000",
                ]
            );
            $this->end_controls_section();
            /*Animated Text Style*/

        }

        /**
         * Render list widget output on the frontend.
         *
         * Written in PHP and used to generate the final HTML.
         *
         * @since 1.0.0
         * @access protected
         */
        protected function render() {
            $settings = $this->get_settings_for_display();
            extract( $settings );
            $typedOptions = [
                    'typedCursor' => $wdfe_at_cursor,
                    'typedSpeed' => $wdfe_at_typing_speed_set,
                    'backDelay' => $wdfe_at_delayonchange_set,
            ];

            $encodedOptions = json_encode($typedOptions);

            ?>

<div class="animated_text_wrap"
    data-animated_text="<?php foreach ( $at_typed_strings as $string ) {echo $string['at_typed_string'] . "|";}?>"

    data-typedoptions="<?php echo htmlspecialchars($encodedOptions); ?>"
    >
    <span class="at_starting_text"><?=$wdfe_at_starting_text?></span>
    <span class="at_typed_text" id="at_typed_string"> </span>
    <span class="at_ending_text"><?=$wdfe_at_ending_text?></span>
</div>
<?php

        }

        /**
         * Render list widget output in the editor.
         *
         * Written as a Backbone JavaScript template and used to generate the live preview.
         *
         * @since 1.0.0
         * @access protected
         */
        protected function content_template() {

        }

    }
}