<?php
/*
Plugin Name: Login Form for Elementor
Plugin URI: https://wptechmaster.com
Description: This plugin adds a Elemento widget to display a custom login form.
Version: 1.1
Author: Khawaja Awais - WpTechmaster
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Enqueue the CSS file
function wptech_login_form_widget_styles()
{
    wp_register_style(
        'wptech-login-form-style',
        plugins_url('style.css', __FILE__),
        array(),
        filemtime(plugin_dir_path(__FILE__) . 'style.css')
    );
    wp_enqueue_style('wptech-login-form-style');
}
add_action('wp_enqueue_scripts', 'wptech_login_form_widget_styles');

// Check if Elementor plugin is active
if (defined('ELEMENTOR_VERSION')) {
    // Include the Elementor widget base file
    add_action('elementor/widgets/widgets_registered', 'wptech_register_elementor_login_form_widget');
    function wptech_register_elementor_login_form_widget()
    {
        // Define the Elementor widget class
        class Wptech_Custom_Login_Form_Widget extends \Elementor\Widget_Base
        {

            // Widget name
            public function get_name()
            {
                return 'custom-login-form-widget';
            }

            // Widget title
            public function get_title()
            {
                return __('Custom Login Form', 'login-form-elementor');
            }

            // Widget icon
            public function get_icon()
            {
                return 'eicon-lock-user';
            }

            // Widget categories
            public function get_categories()
            {
                return ['general']; // Change the category as needed
            }

            // Define the controls for the widget
            protected function _register_controls()
            {
                $this->start_controls_section(
                    'section_content',
                    [
                        'label' => __('Content', 'login-form-elementor'),
                    ]
                );

                $this->add_control(
                    'title',
                    [
                        'label' => __('Title', 'login-form-elementor'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => __('Login', 'login-form-elementor'),
                    ]
                );

                $this->add_control(
                    'username_label',
                    [
                        'label' => __('Username Label', 'login-form-elementor'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => __('Username or Email', 'login-form-elementor'),

                    ]
                );

                $this->add_control(
                    'password_label',
                    [
                        'label' => __('Password Label', 'login-form-elementor'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => __('Password', 'login-form-elementor'),
                    ]
                );

                $this->add_control(
                    'username_placeholder',
                    [
                        'label' => __('Username Placeholder', 'login-form-elementor'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => __('Enter your username or email', 'login-form-elementor'),
                    ]
                );

                $this->add_control(
                    'password_placeholder',
                    [
                        'label' => __('Password Placeholder', 'login-form-elementor'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => __('Enter your password', 'login-form-elementor'),
                    ]
                );
                $this->add_control(
                    'button_text',
                    [
                        'label' => __('Button Text', 'login-form-elementor'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => __('Log In', 'login-form-elementor'),
                    ]
                );

                $this->add_control(
                    'remember_me',
                    [
                        'label' => __('Remember Me', 'login-form-elementor'),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'label_on' => __('Yes', 'login-form-elementor'),
                        'label_off' => __('No', 'login-form-elementor'),
                        'default' => 'yes',
                    ]
                );

                $this->end_controls_section();
            }
            // Render the widget output
            protected function render()
            {
                $settings = $this->get_settings_for_display();

                $shortcode_atts = array(
                    'remember_me' => $settings['remember_me'],
                    'username_label' => !empty($settings['username_label']) ? $settings['username_label'] : __('Username or Email', 'login-form-elementor'),
                    'password_label' => !empty($settings['password_label']) ? $settings['password_label'] : __('Password', 'login-form-elementor'),
                    'username_placeholder' => !empty($settings['username_placeholder']) ? $settings['username_placeholder'] : __('Enter your username or email', 'login-form-elementor'),
                    'password_placeholder' => !empty($settings['password_placeholder']) ? $settings['password_placeholder'] : __('Enter your password', 'login-form-elementor'),
                    'button_text' => !empty($settings['button_text']) ? $settings['button_text'] : __('Login', 'login-form-elementor'),
                    'title' => !empty($settings['title']) ? $settings['title'] : '',
                );

                $shortcode_attributes = '';
                foreach ($shortcode_atts as $key => $value) {
                    $shortcode_attributes .= ' ' . $key . '="' . esc_attr($value) . '"';
                }

                echo do_shortcode('[wptech_custom_login_form' . $shortcode_attributes . ']');
            }
        }
        // Get the Elementor instance
        $elementor_instance = \Elementor\Plugin::instance();

        // Get the widgets manager from the Elementor instance
        $widgets_manager = $elementor_instance->widgets_manager;

        // Register the Custom Login Form Widget with the widgets manager
        $widgets_manager->register_widget_type(new Wptech_Custom_Login_Form_Widget());
    }
} else {
    // Display a notice indicating that Elementor is required but not available
    add_action('admin_notices', 'wptech_elementor_login_form_widget_elementor_notice');
    function wptech_elementor_login_form_widget_elementor_notice()
    {
        ?>
        <div class="error">
            <p><?php esc_html_e('The Elementor Login Form widget requires Elementor plugin to be active.', 'login-form-elementor'); ?>
            </p>
        </div>
        <?php
    }
}

function wptech_elementor_login_form_shortcode_function($atts)
{
    $atts = shortcode_atts(
        array(
            'redirect' => home_url(),
            'form_id' => 'loginform-' . wp_rand(1000, 9999),
            'username_label' => __('Username or Email Address', 'login-form-elementor'),
            'password_label' => __('Password', 'login-form-elementor'),
            'label_remember' => __('Remember Me', 'login-form-elementor'),
            'button_text' => __('Log In', 'login-form-elementor'),
            'remember_me' => 'yes',
            'username_placeholder' => __('Enter your username or email', 'login-form-elementor'),
            'password_placeholder' => __('Enter your password', 'login-form-elementor'),
            'title' => '',
        ),
        $atts
    );

    if (is_user_logged_in()) {
        return '<div class="logged-in-message">' . esc_html__('You are already logged in.', 'login-form-elementor') . '</div>';
    }

    // Enqueue script
    wp_enqueue_script('wptech-login-ajax', plugins_url('login-ajax.js', __FILE__), array('jquery'), '1.0', true);
    wp_localize_script(
        'wptech-login-ajax',
        'wptech_login_ajax',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'redirect_url' => $atts['redirect'],
            'nonce' => wp_create_nonce('wptech_login_nonce'),
        )
    );

    $form = '';
    if (!empty($atts['title'])) {
        $form .= '<h2>' . esc_html($atts['title']) . '</h2>';
    }
    $form .= '<div id="wptech-login-message"></div>';
    $form .= '<form id="' . esc_attr($atts['form_id']) . '" class="wptech-login-form">';
    $form .= '<p>';
    $form .= '<label for="user_login">' . esc_html($atts['username_label']) . '</label>';
    $form .= '<input type="text" name="log" id="user_login" class="input" value="" size="20" placeholder="' . esc_attr($atts['username_placeholder']) . '" />';
    $form .= '</p>';
    $form .= '<p>';
    $form .= '<label for="user_pass">' . esc_html($atts['password_label']) . '</label>';
    $form .= '<input type="password" name="pwd" id="user_pass" class="input" value="" size="20" placeholder="' . esc_attr($atts['password_placeholder']) . '" />';
    $form .= '</p>';
    if ($atts['remember_me'] === 'yes') {
        $form .= '<p class="login-remember">';
        $form .= '<label><input name="rememberme" type="checkbox" id="rememberme" value="forever" /> ' . esc_html($atts['label_remember']) . '</label>';
        $form .= '</p>';
    }
    $form .= '<p class="login-submit">';
    $form .= '<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary" value="' . esc_attr($atts['button_text']) . '" />';
    $form .= '</p>';
    $form .= '</form>';

    return $form;
}
add_shortcode('wptech_custom_login_form', 'wptech_elementor_login_form_shortcode_function');

// AJAX login handler
function wptech_ajax_login()
{
    check_ajax_referer('wptech_login_nonce', 'security');

    $info = array();
    $info['user_login'] = sanitize_text_field($_POST['username']);
    $info['user_password'] = $_POST['password'];
    $info['remember'] = $_POST['remember'];

    $user_signon = wp_signon($info, false);
   if (is_wp_error($user_signon)) {
        echo wp_json_encode(array(
            'loggedin' => false, 
            'message' => __('Wrong username or password.', 'login-form-elementor')
        ));
    } else {
        echo wp_json_encode(array(
            'loggedin' => true, 
            'message' => __('Login successful, redirecting...', 'login-form-elementor')
        ));
                
    }
    
    wp_die();
}
add_action('wp_ajax_nopriv_wptech_ajax_login', 'wptech_ajax_login');