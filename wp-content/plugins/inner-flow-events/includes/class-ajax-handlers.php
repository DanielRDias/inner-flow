<?php
/**
 * AJAX handlers for event registrations
 */

if (!defined('ABSPATH')) {
    exit;
}

class IFE_Ajax_Handlers {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // AJAX actions for logged-in users
        add_action('wp_ajax_ife_register_event', array($this, 'register_for_event'));
        add_action('wp_ajax_ife_unregister_event', array($this, 'unregister_from_event'));
        add_action('wp_ajax_ife_get_participants', array($this, 'get_event_participants'));
    }
    
    public function register_for_event() {
        check_ajax_referer('ife-nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('You must be logged in to register.', 'inner-flow-events')));
        }
        
        $event_id = intval($_POST['event_id']);
        $user_id = get_current_user_id();
        $status = sanitize_text_field($_POST['status']); // 'joining' or 'not_joining'
        $join_stop_id = isset($_POST['join_stop_id']) ? intval($_POST['join_stop_id']) : null;
        $notes = isset($_POST['notes']) ? sanitize_textarea_field($_POST['notes']) : '';
        
        $result = IFE_Database::upsert_registration($event_id, $user_id, $status, $join_stop_id, $notes);
        
        if ($result !== false) {
            wp_send_json_success(array(
                'message' => __('Registration updated successfully.', 'inner-flow-events'),
                'status' => $status
            ));
        } else {
            wp_send_json_error(array('message' => __('Failed to update registration.', 'inner-flow-events')));
        }
    }
    
    public function unregister_from_event() {
        check_ajax_referer('ife-nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('You must be logged in.', 'inner-flow-events')));
        }
        
        global $wpdb;
        $event_id = intval($_POST['event_id']);
        $user_id = get_current_user_id();
        
        $table_registrations = $wpdb->prefix . 'ife_event_registrations';
        
        $result = $wpdb->delete(
            $table_registrations,
            array(
                'event_id' => $event_id,
                'user_id' => $user_id
            ),
            array('%d', '%d')
        );
        
        if ($result !== false) {
            wp_send_json_success(array('message' => __('Unregistered successfully.', 'inner-flow-events')));
        } else {
            wp_send_json_error(array('message' => __('Failed to unregister.', 'inner-flow-events')));
        }
    }
    
    public function get_event_participants() {
        check_ajax_referer('ife-nonce', 'nonce');
        
        $event_id = intval($_POST['event_id']);
        $registrations = IFE_Database::get_event_registrations($event_id);
        
        // Get route and stops for this event
        $route = IFE_Database::get_event_route($event_id);
        $stops = $route ? IFE_Database::get_route_stops($route->id) : array();
        
        // Build a map of stop_id => stop_name
        $stops_map = array();
        $stops_list = array();
        foreach ($stops as $index => $stop) {
            $stops_map[$stop->id] = $stop->stop_name;
            $stops_list[] = array(
                'id' => $stop->id,
                'name' => $stop->stop_name,
                'order' => $index + 1
            );
        }
        
        $participants = array();
        foreach ($registrations as $registration) {
            $user = get_userdata($registration->user_id);
            if ($user) {
                $join_stop_name = null;
                if ($registration->join_stop_id && isset($stops_map[$registration->join_stop_id])) {
                    $join_stop_name = $stops_map[$registration->join_stop_id];
                }
                
                $participants[] = array(
                    'user_id' => $registration->user_id,
                    'name' => $user->display_name,
                    'avatar' => get_avatar_url($user->ID),
                    'status' => $registration->registration_status,
                    'join_stop_id' => $registration->join_stop_id,
                    'join_stop_name' => $join_stop_name,
                    'registration_date' => $registration->registration_date
                );
            }
        }
        
        wp_send_json_success(array(
            'participants' => $participants,
            'stops' => $stops_list
        ));
    }
}
?>
