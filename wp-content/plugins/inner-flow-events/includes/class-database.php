<?php
/**
 * Database handler for creating custom tables
 */

if (!defined('ABSPATH')) {
    exit;
}

class IFE_Database {
    
    public static function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Table for event routes
        $table_routes = $wpdb->prefix . 'ife_event_routes';
        $sql_routes = "CREATE TABLE IF NOT EXISTS $table_routes (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            event_id bigint(20) NOT NULL,
            route_name varchar(255) NOT NULL,
            route_polyline longtext,
            total_distance decimal(10,2),
            estimated_duration int(11),
            difficulty_level varchar(50),
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY event_id (event_id)
        ) $charset_collate;";
        
        // Table for stop points
        $table_stops = $wpdb->prefix . 'ife_stop_points';
        $sql_stops = "CREATE TABLE IF NOT EXISTS $table_stops (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            route_id bigint(20) NOT NULL,
            stop_order int(11) NOT NULL DEFAULT 0,
            stop_name varchar(255) NOT NULL,
            stop_description text,
            latitude decimal(10,8),
            longitude decimal(11,8),
            stop_time time,
            duration_minutes int(11),
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY route_id (route_id)
        ) $charset_collate;";
        
        // Table for event registrations
        $table_registrations = $wpdb->prefix . 'ife_event_registrations';
        $sql_registrations = "CREATE TABLE IF NOT EXISTS $table_registrations (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            event_id bigint(20) NOT NULL,
            user_id bigint(20) NOT NULL,
            registration_status varchar(50) NOT NULL DEFAULT 'joining',
            join_stop_id bigint(20) DEFAULT NULL,
            registration_date datetime DEFAULT CURRENT_TIMESTAMP,
            notes text,
            PRIMARY KEY  (id),
            UNIQUE KEY unique_registration (event_id, user_id),
            KEY event_id (event_id),
            KEY user_id (user_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_routes);
        dbDelta($sql_stops);
        dbDelta($sql_registrations);
    }
    
    /**
     * Get route by event ID
     */
    public static function get_event_route($event_id) {
        global $wpdb;
        $table_routes = $wpdb->prefix . 'ife_event_routes';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_routes WHERE event_id = %d",
            $event_id
        ));
    }
    
    /**
     * Get stop points by route ID
     */
    public static function get_route_stops($route_id) {
        global $wpdb;
        $table_stops = $wpdb->prefix . 'ife_stop_points';
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_stops WHERE route_id = %d ORDER BY stop_order ASC",
            $route_id
        ));
    }
    
    /**
     * Get event registrations
     */
    public static function get_event_registrations($event_id) {
        global $wpdb;
        $table_registrations = $wpdb->prefix . 'ife_event_registrations';
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_registrations WHERE event_id = %d",
            $event_id
        ));
    }
    
    /**
     * Add or update registration
     */
    public static function upsert_registration($event_id, $user_id, $status, $join_stop_id = null, $notes = '') {
        global $wpdb;
        $table_registrations = $wpdb->prefix . 'ife_event_registrations';
        
        return $wpdb->replace(
            $table_registrations,
            array(
                'event_id' => $event_id,
                'user_id' => $user_id,
                'registration_status' => $status,
                'join_stop_id' => $join_stop_id,
                'notes' => $notes,
                'registration_date' => current_time('mysql')
            ),
            array('%d', '%d', '%s', '%d', '%s', '%s')
        );
    }
}
?>
