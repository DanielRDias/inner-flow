<?php
/**
 * Google OAuth integration
 */

if (!defined('ABSPATH')) {
    exit;
}

class IFE_Google_Auth {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // This will integrate with a Google OAuth plugin
        // Recommended plugin: "Nextend Social Login" or "WP Social Login"
        
        add_action('wp_footer', array($this, 'add_google_login_button'));
        add_filter('login_redirect', array($this, 'custom_login_redirect'), 10, 3);
    }
    
    public function add_google_login_button() {
        if (!is_user_logged_in() && is_singular('hiking_event')) {
            ?>
            <div id="ife-google-login-prompt" style="display:none;">
                <p><?php _e('Please log in with your Google account to register for this event.', 'inner-flow-events'); ?></p>
                <?php
                // This will be replaced by the actual Google login button from the plugin
                do_action('ife_google_login_button');
                ?>
            </div>
            <?php
        }
    }
    
    public function custom_login_redirect($redirect_to, $request, $user) {
        // Redirect back to the event page after login
        if (isset($_GET['event_id'])) {
            $event_id = intval($_GET['event_id']);
            return get_permalink($event_id);
        }
        return $redirect_to;
    }
}
?>
