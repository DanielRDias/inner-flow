<?php
/**
 * Custom Post Types for Hiking Events
 */

if (!defined('ABSPATH')) {
    exit;
}

class IFE_Post_Types {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', array($this, 'register_post_types'));
    }
    
    public static function register_post_types() {
        // Register Hiking Event Post Type
        $labels = array(
            'name'                  => _x('Hiking Events', 'Post Type General Name', 'inner-flow-events'),
            'singular_name'         => _x('Hiking Event', 'Post Type Singular Name', 'inner-flow-events'),
            'menu_name'             => __('Hiking Events', 'inner-flow-events'),
            'name_admin_bar'        => __('Hiking Event', 'inner-flow-events'),
            'archives'              => __('Event Archives', 'inner-flow-events'),
            'attributes'            => __('Event Attributes', 'inner-flow-events'),
            'parent_item_colon'     => __('Parent Event:', 'inner-flow-events'),
            'all_items'             => __('All Events', 'inner-flow-events'),
            'add_new_item'          => __('Add New Event', 'inner-flow-events'),
            'add_new'               => __('Add New', 'inner-flow-events'),
            'new_item'              => __('New Event', 'inner-flow-events'),
            'edit_item'             => __('Edit Event', 'inner-flow-events'),
            'update_item'           => __('Update Event', 'inner-flow-events'),
            'view_item'             => __('View Event', 'inner-flow-events'),
            'view_items'            => __('View Events', 'inner-flow-events'),
            'search_items'          => __('Search Event', 'inner-flow-events'),
            'not_found'             => __('Not found', 'inner-flow-events'),
            'not_found_in_trash'    => __('Not found in Trash', 'inner-flow-events'),
        );
        
        $args = array(
            'label'                 => __('Hiking Event', 'inner-flow-events'),
            'description'           => __('Hiking Events with routes and stops', 'inner-flow-events'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
            'taxonomies'            => array('hiking_category', 'hiking_tag'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-location',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
        );
        
        register_post_type('hiking_event', $args);
        
        // Register Event Category Taxonomy
        $category_labels = array(
            'name'                       => _x('Event Categories', 'Taxonomy General Name', 'inner-flow-events'),
            'singular_name'              => _x('Event Category', 'Taxonomy Singular Name', 'inner-flow-events'),
            'menu_name'                  => __('Categories', 'inner-flow-events'),
            'all_items'                  => __('All Categories', 'inner-flow-events'),
            'parent_item'                => __('Parent Category', 'inner-flow-events'),
            'parent_item_colon'          => __('Parent Category:', 'inner-flow-events'),
            'new_item_name'              => __('New Category Name', 'inner-flow-events'),
            'add_new_item'               => __('Add New Category', 'inner-flow-events'),
            'edit_item'                  => __('Edit Category', 'inner-flow-events'),
            'update_item'                => __('Update Category', 'inner-flow-events'),
            'view_item'                  => __('View Category', 'inner-flow-events'),
            'search_items'               => __('Search Categories', 'inner-flow-events'),
        );
        
        $category_args = array(
            'labels'                     => $category_labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
        );
        
        register_taxonomy('hiking_category', array('hiking_event'), $category_args);
    }
}
?>
