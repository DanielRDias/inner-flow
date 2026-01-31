<?php
/**
 * Inner Flow Theme Functions
 */

// Theme Setup
function inner_flow_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');
    
    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');
    
    // Enable support for HTML5 markup
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'inner-flow'),
        'footer' => __('Footer Menu', 'inner-flow'),
    ));
    
    // Load text domain for translations
    load_theme_textdomain('inner-flow', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'inner_flow_setup');

// Enqueue Scripts and Styles
function inner_flow_scripts() {
    // Theme stylesheet
    wp_enqueue_style('inner-flow-style', get_stylesheet_uri(), array(), '1.0');
    
    // Google Maps API (you'll need to add your API key)
    wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places', array(), null, true);
    
    // Custom JavaScript
    wp_enqueue_script('inner-flow-script', get_template_directory_uri() . '/js/main.js', array('jquery'), '1.0', true);
    
    // Localize script for AJAX
    wp_localize_script('inner-flow-script', 'innerFlowAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('inner-flow-nonce')
    ));
}
add_action('wp_enqueue_scripts', 'inner_flow_scripts');

// Register Widget Areas
function inner_flow_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'inner-flow'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here.', 'inner-flow'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'inner_flow_widgets_init');

// Custom excerpt length
function inner_flow_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'inner_flow_excerpt_length');

// Add language switcher to menu
function inner_flow_language_switcher() {
    // This will work with Polylang plugin
    if (function_exists('pll_the_languages')) {
        echo '<div class="language-switcher">';
        pll_the_languages(array(
            'show_flags' => 1,
            'show_names' => 1,
            'hide_if_empty' => 0
        ));
        echo '</div>';
    }
}

// Customize login with Google (requires plugin)
function inner_flow_custom_login_message($message) {
    if (empty($message)) {
        return '<p class="login-message">' . __('Login with your Google account to register for hiking events.', 'inner-flow') . '</p>';
    }
    return $message;
}
add_filter('login_message', 'inner_flow_custom_login_message');
?>
