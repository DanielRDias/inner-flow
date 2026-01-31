<?php
/**
 * Plugin Name: Inner Flow Hiking Events
 * Plugin URI: https://innerflow.com
 * Description: Custom plugin for managing hiking events with routes, stop points, and participant registration
 * Version: 1.0.0
 * Author: Inner Flow Team
 * Text Domain: inner-flow-events
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('IFE_VERSION', '1.0.0');
define('IFE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('IFE_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once IFE_PLUGIN_DIR . 'includes/class-post-types.php';
require_once IFE_PLUGIN_DIR . 'includes/class-meta-boxes.php';
require_once IFE_PLUGIN_DIR . 'includes/class-ajax-handlers.php';
require_once IFE_PLUGIN_DIR . 'includes/class-google-auth.php';
require_once IFE_PLUGIN_DIR . 'includes/class-database.php';

/**
 * Main plugin class
 */
class Inner_Flow_Events {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Initialize plugin
        add_action('plugins_loaded', array($this, 'init'));
        
        // Activation hook
        register_activation_hook(__FILE__, array($this, 'activate'));
        
        // Deactivation hook
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    public function init() {
        // Load text domain
        load_plugin_textdomain('inner-flow-events', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
        // Initialize components
        IFE_Post_Types::get_instance();
        IFE_Meta_Boxes::get_instance();
        IFE_Ajax_Handlers::get_instance();
        IFE_Google_Auth::get_instance();
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    public function enqueue_scripts() {
        wp_enqueue_style('ife-styles', IFE_PLUGIN_URL . 'assets/css/styles.css', array(), IFE_VERSION);
        wp_enqueue_script('ife-scripts', IFE_PLUGIN_URL . 'assets/js/scripts.js', array('jquery'), IFE_VERSION, true);
        
        // Localize script
        wp_localize_script('ife-scripts', 'ifeData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ife-nonce'),
            'strings' => array(
                'confirmJoin' => __('Are you sure you want to join this event?', 'inner-flow-events'),
                'confirmLeave' => __('Are you sure you want to leave this event?', 'inner-flow-events'),
                'noParticipants' => __('No participants yet.', 'inner-flow-events'),
                'totalParticipants' => __('Total Participants', 'inner-flow-events'),
                'joinAt' => __('Join at', 'inner-flow-events'),
                'startBeginning' => __('Start (Beginning)', 'inner-flow-events'),
            )
        ));
    }
    
    public function enqueue_admin_scripts($hook) {
        global $post_type;
        
        if (('post.php' === $hook || 'post-new.php' === $hook) && 'hiking_event' === $post_type) {
            wp_enqueue_style('ife-admin-styles', IFE_PLUGIN_URL . 'assets/css/admin-styles.css', array(), IFE_VERSION);
            wp_enqueue_script('ife-admin-scripts', IFE_PLUGIN_URL . 'assets/js/admin-scripts.js', array('jquery', 'jquery-ui-sortable'), IFE_VERSION, true);
            
            wp_localize_script('ife-admin-scripts', 'ifeAdminData', array(
                'mapPreviewText' => __('Map Preview:', 'inner-flow-events'),
                'invalidEmbedText' => __('Invalid embed code. Please make sure you copied the complete iframe code from Google Maps.', 'inner-flow-events'),
            ));
        }
    }
    
    public function activate() {
        // Create custom database tables
        IFE_Database::create_tables();
        
        // Register post types
        IFE_Post_Types::register_post_types();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    public function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
    }
}

// Initialize the plugin
function inner_flow_events() {
    return Inner_Flow_Events::get_instance();
}

inner_flow_events();
?>
