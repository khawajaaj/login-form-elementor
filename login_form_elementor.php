<?php
/*
Plugin Name: Login Form Elementor
Plugin URI: https://wptechmaster.com
Description: This plugin adds a Elemento widget to display a custom login form.
Version: 1.0
Author: Khawaja Awais - WpTechmaster
*/

// Check if Elementor plugin is active
if ( defined( 'ELEMENTOR_VERSION' ) ) {
    // Include the Elementor widget base file
    add_action( 'elementor/widgets/widgets_registered', 'register_wptech_elementor_login_form_widget' );
    function register_wptech_elementor_login_form_widget() {
        // Define the Elementor widget class
        class Custom_Login_Form_Widget extends \Elementor\Widget_Base {

            // Widget name
            public function get_name() {
                return 'custom-login-form-widget';
            }

            // Widget title
            public function get_title() {
                return __( 'Custom Login Form', 'text-domain' );
            }

            // Widget icon
            public function get_icon() {
                return 'eicon-lock-user';
            }

            // Widget categories
            public function get_categories() {
                return [ 'general' ]; // Change the category as needed
            }

            // Define the controls for the widget
            protected function _register_controls() {
                $this->start_controls_section(
                    'section_content',
                    [
                        'label' => __( 'Content', 'text-domain' ),
                    ]
                );
        
                $this->add_control(
                    'title',
                    [
                        'label' => __( 'Title', 'text-domain' ),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => __( 'Login', 'text-domain' ),
                    ]
                );
                
                $this->add_control(
                'username_label',
                [
                    'label' => __( 'Username Label', 'text-domain' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => __( 'Username or Email', 'text-domain' ),

                ]
            );
    
                $this->add_control(
                'password_label',
                [
                    'label' => __( 'Password Label', 'text-domain' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => __( 'Password', 'text-domain' ),
                ]
            );
    
                $this->add_control(
                'username_placeholder',
                [
                    'label' => __( 'Username Placeholder', 'text-domain' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => __( 'Enter your username or email', 'text-domain' ),
                ]
            );
    
                $this->add_control(
                'password_placeholder',
                [
                    'label' => __( 'Password Placeholder', 'text-domain' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => __( 'Enter your password', 'text-domain' ),
                ]
            );
                 $this->add_control(
                'button_text',
                [
                    'label' => __( 'Button Text', 'text-domain' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => __( 'Log In', 'text-domain' ),
                ]
            );
                
                $this->add_control(
                'remember_me',
                [
                    'label' => __( 'Remember Me', 'text-domain' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __( 'Yes', 'text-domain' ),
                    'label_off' => __( 'No', 'text-domain' ),
                    'default' => 'yes',
                ]
            );
        
                $this->end_controls_section();
            }
            // Render the widget output
            protected function render() {
                $settings = $this->get_settings_for_display();
                $username_label = !empty($settings['username_label']) ? esc_html($settings['username_label']) : __( 'Username or Email', 'text-domain' );
                $password_label = !empty($settings['password_label']) ? esc_html($settings['password_label']) : __( 'Password', 'text-domain' );
                $username_placeholder = !empty($settings['username_placeholder']) ? esc_attr($settings['username_placeholder']) : __( 'Enter your username or email', 'text-domain' );
                $password_placeholder = !empty($settings['password_placeholder']) ? esc_attr($settings['password_placeholder']) : __( 'Enter your password', 'text-domain' );
                $button_text = !empty($settings['button_text']) ? esc_attr($settings['button_text']) : __( 'Login', 'text-domain' );

                echo '<h2>' . esc_html( $settings['title'] ) . '</h2>';
                echo do_shortcode( '[wptech_custom_login_form remember_me="' . $settings['remember_me'] . '" username_label="' . $username_label . '" password_label="' . $password_label . '" username_placeholder="' . $username_placeholder . '" password_placeholder="' . $password_placeholder . '" button_text="' . $button_text . '"]' );
            }
        }
               // Get the Elementor instance
        $elementor_instance = \Elementor\Plugin::instance();
        
        // Get the widgets manager from the Elementor instance
        $widgets_manager = $elementor_instance->widgets_manager;
        
        // Register the Custom Login Form Widget with the widgets manager
        $widgets_manager->register_widget_type(new Custom_Login_Form_Widget());
    }
} else {
    // Display a notice indicating that Elementor is required but not available
    add_action( 'admin_notices', 'wptech_elementor_login_form_widget_elementor_notice' );
    function wptech_elementor_login_form_widget_elementor_notice() {
        ?>
        <div class="error">
            <p><?php esc_html_e( 'The Elementor Login Form widget requires Elementor plugin to be active.', 'text-domain' ); ?></p>
        </div>
        <?php
    }
}

// Shortcode handler function for the custom login form
function wptech_elementor_login_form_shortcode_function( $atts ) {
    $atts = shortcode_atts( array(
        'remember_me' => 'yes', // Default value is 'yes'
        'username_label' => __( 'Username or Email', 'text-domain' ),
        'password_label' => __( 'Password', 'text-domain' ),
        'username_placeholder' => __( 'Enter your username or email', 'text-domain' ),
        'password_placeholder' => __( 'Enter your password', 'text-domain' ),
        'button_text' => __( 'Login', 'text-domain' ),

    ), $atts );

    // Convert the attribute value to a boolean
    $remember_me = filter_var( $atts['remember_me'], FILTER_VALIDATE_BOOLEAN );

    // Generate the login form HTML
    $form_html = '<form class="wp-cl-login" action="" method="post">';
    $form_html .= '<p>';
    $form_html .= '<label for="user_login">' . esc_html( $atts['username_label'] ) . '</label>';
    $form_html .= '<input type="text" name="log" id="user_login" class="input" value="" size="20" placeholder="' . esc_attr( $atts['username_placeholder'] ) . '" />';
    $form_html .= '</p>';
    $form_html .= '<p>';
    $form_html .= '<label for="user_pass">' . esc_html( $atts['password_label'] ) . '</label>';
    $form_html .= '<input type="password" name="pwd" id="user_pass" class="input" value="" size="20" placeholder="' . esc_attr( $atts['password_placeholder'] ) . '" />';
    $form_html .= '</p>';

    // Add the "Remember Me" checkbox if enabled
    if ( $remember_me ) {
        $form_html .= '<p class="remember-me">';
        $form_html .= '<input name="rememberme" type="checkbox" id="rememberme" value="forever" />';
        $form_html .= '<label for="rememberme">' . esc_html__( 'Remember Me', 'text-domain' ) . '</label>';
        $form_html .= '</p>';
    }

    $form_html .= '<p class="submit">';
    $form_html .= '<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary" value="' .esc_html( $atts['button_text'] ) . '" />';
    $form_html .= '</p>';
    $form_html .= '</form>';

    return $form_html;
}
add_shortcode( 'wptech_custom_login_form', 'wptech_elementor_login_form_shortcode_function' );


function wptech_elementor_login_form_widget_styles() {
    ?>
    <style>
        /* Add custom CSS styles for the login form widget */
               .wp-cl-login p.remember-me {
               display: inline-flex;
               gap: 5px;
                }
    </style>
    <?php
}
add_action( 'wp_head', 'wptech_elementor_login_form_widget_styles' );
