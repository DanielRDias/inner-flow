<?php
/**
 * Meta boxes for hiking events
 */

if (!defined('ABSPATH')) {
    exit;
}

class IFE_Meta_Boxes {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post_hiking_event', array($this, 'save_meta_boxes'), 10, 2);
    }
    
    public function add_meta_boxes() {
        add_meta_box(
            'ife_event_details',
            __('Event Details', 'inner-flow-events'),
            array($this, 'render_event_details_meta_box'),
            'hiking_event',
            'normal',
            'high'
        );
        
        add_meta_box(
            'ife_route_details',
            __('Route & Stop Points', 'inner-flow-events'),
            array($this, 'render_route_meta_box'),
            'hiking_event',
            'normal',
            'high'
        );
    }
    
    public function render_event_details_meta_box($post) {
        wp_nonce_field('ife_event_details_nonce', 'ife_event_details_nonce');
        
        $event_date = get_post_meta($post->ID, '_ife_event_date', true);
        $event_time = get_post_meta($post->ID, '_ife_event_time', true);
        $event_location = get_post_meta($post->ID, '_ife_event_location', true);
        $event_lat = get_post_meta($post->ID, '_ife_event_latitude', true);
        $event_lng = get_post_meta($post->ID, '_ife_event_longitude', true);
        ?>
        
        <style>
            .ife-meta-box { margin: 15px 0; }
            .ife-meta-box p { margin-bottom: 15px; }
            .ife-meta-box label { 
                display: block;
                font-weight: 600;
                margin-bottom: 5px;
                color: #23282d;
            }
            .ife-meta-box input[type="date"],
            .ife-meta-box input[type="time"],
            .ife-meta-box input[type="text"] {
                width: 100%;
                padding: 8px;
                border: 1px solid #ddd;
                border-radius: 3px;
            }
        </style>
        
        <div class="ife-meta-box">
            <p>
                <label for="ife_event_date"><strong><?php _e('Event Date:', 'inner-flow-events'); ?></strong></label>
                <input type="date" id="ife_event_date" name="ife_event_date" value="<?php echo esc_attr($event_date); ?>" style="width: 100%;">
            </p>
            
            <p>
                <label for="ife_event_time"><strong><?php _e('Event Time:', 'inner-flow-events'); ?></strong></label>
                <input type="time" id="ife_event_time" name="ife_event_time" value="<?php echo esc_attr($event_time); ?>" style="width: 100%;">
            </p>
            
            <p>
                <label for="ife_event_location"><strong><?php _e('Event Location:', 'inner-flow-events'); ?></strong></label>
                <input type="text" id="ife_event_location" name="ife_event_location" value="<?php echo esc_attr($event_location); ?>" placeholder="<?php _e('Search for a location...', 'inner-flow-events'); ?>" style="width: 100%;">
                <small style="color: #666;"><?php _e('Start typing to search for a location', 'inner-flow-events'); ?></small>
            </p>
            
            <?php
            $maps_iframe = get_post_meta($post->ID, '_ife_maps_iframe', true);
            ?>
            <p>
                <label for="ife_maps_iframe"><strong><?php _e('Google Maps Embed Code:', 'inner-flow-events'); ?></strong></label>
                <textarea id="ife_maps_iframe" name="ife_maps_iframe" rows="3" style="width: 100%; font-family: monospace; font-size: 12px;" placeholder="<iframe src=\"https://www.google.com/maps/embed?pb=...\" ...></iframe>"><?php echo esc_textarea($maps_iframe); ?></textarea>
                <small style="color: #666;"><?php _e('Go to Google Maps → Click Share → Embed a map → Copy the entire iframe code and paste it here', 'inner-flow-events'); ?></small>
            </p>
            
            <div id="ife_map_preview_container" style="margin-top: 15px; <?php echo empty($maps_iframe) ? 'display: none;' : ''; ?>">
                <label><strong><?php _e('Map Preview:', 'inner-flow-events'); ?></strong></label>
                <div id="ife_map_preview" style="margin-top: 10px; border: 2px solid #ddd; border-radius: 5px; overflow: hidden;">
                    <?php
                    if (!empty($maps_iframe) && preg_match('/src="([^"]+)"/', $maps_iframe, $matches)) {
                        $embed_url = $matches[1];
                        ?>
                        <iframe 
                            src="<?php echo esc_url($embed_url); ?>"
                            width="100%" 
                            height="400" 
                            style="border:0; display: block;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                        <?php
                    }
                    ?>
                </div>
                <p id="ife_map_error" style="color: #dc3545; margin-top: 10px; display: none;"><?php _e('Invalid embed code. Please make sure you copied the complete iframe code from Google Maps.', 'inner-flow-events'); ?></p>
            </div>
        </div>
        
        <?php
    }
    
    public function render_route_meta_box($post) {
        wp_nonce_field('ife_route_nonce', 'ife_route_nonce');
        
        global $wpdb;
        $route = IFE_Database::get_event_route($post->ID);
        $stops = $route ? IFE_Database::get_route_stops($route->id) : array();
        
        $route_name = $route ? $route->route_name : '';
        $difficulty = $route ? $route->difficulty_level : 'moderate';
        ?>
        
        <div class="ife-route-meta-box">
            <p>
                <label for="ife_route_name"><?php _e('Route Name:', 'inner-flow-events'); ?></label>
                <input type="text" id="ife_route_name" name="ife_route_name" value="<?php echo esc_attr($route_name); ?>" style="width: 100%;">
            </p>
            
            <p>
                <label for="ife_difficulty"><?php _e('Difficulty Level:', 'inner-flow-events'); ?></label>
                <select id="ife_difficulty" name="ife_difficulty" style="width: 100%;">
                    <option value="easy" <?php selected($difficulty, 'easy'); ?>><?php _e('Easy', 'inner-flow-events'); ?></option>
                    <option value="moderate" <?php selected($difficulty, 'moderate'); ?>><?php _e('Moderate', 'inner-flow-events'); ?></option>
                    <option value="hard" <?php selected($difficulty, 'hard'); ?>><?php _e('Hard', 'inner-flow-events'); ?></option>
                    <option value="expert" <?php selected($difficulty, 'expert'); ?>><?php _e('Expert', 'inner-flow-events'); ?></option>
                </select>
            </p>
            
            <h3><?php _e('Stop Points', 'inner-flow-events'); ?></h3>
            <div id="ife_stop_points">
                <?php if (!empty($stops)) : ?>
                    <?php foreach ($stops as $index => $stop) : ?>
                        <?php $this->render_stop_point_row($index, $stop); ?>
                    <?php endforeach; ?>
                <?php else : ?>
                    <?php $this->render_stop_point_row(0); ?>
                <?php endif; ?>
            </div>
            
            <button type="button" class="button" id="ife_add_stop"><?php _e('Add Stop Point', 'inner-flow-events'); ?></button>
        </div>
        
        <?php
    }
    
    private function render_stop_point_row($index, $stop = null) {
        ?>
        <div class="ife-stop-point" data-index="<?php echo $index; ?>">
            <h4><?php printf(__('Stop Point %d', 'inner-flow-events'), $index + 1); ?> <button type="button" class="button ife-remove-stop"><?php _e('Remove', 'inner-flow-events'); ?></button></h4>
            
            <p>
                <label><?php _e('Stop Name:', 'inner-flow-events'); ?></label>
                <input type="text" name="ife_stops[<?php echo $index; ?>][name]" value="<?php echo $stop ? esc_attr($stop->stop_name) : ''; ?>" style="width: 100%;">
            </p>
            
            <p>
                <label><?php _e('Stop Description:', 'inner-flow-events'); ?></label>
                <textarea name="ife_stops[<?php echo $index; ?>][description]" rows="3" style="width: 100%;"><?php echo $stop ? esc_textarea($stop->stop_description) : ''; ?></textarea>
            </p>
            
            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px;"><?php _e('Stop Time:', 'inner-flow-events'); ?></label>
                    <input type="time" name="ife_stops[<?php echo $index; ?>][time]" value="<?php echo $stop ? esc_attr($stop->stop_time) : ''; ?>" style="width: 100%;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px;"><?php _e('Duration (minutes):', 'inner-flow-events'); ?></label>
                    <input type="number" name="ife_stops[<?php echo $index; ?>][duration]" value="<?php echo $stop ? esc_attr($stop->duration_minutes) : ''; ?>" min="0" style="width: 100%;">
                </div>
            </div>
            
            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px;"><?php _e('Latitude:', 'inner-flow-events'); ?></label>
                    <input type="text" name="ife_stops[<?php echo $index; ?>][latitude]" value="<?php echo $stop ? esc_attr($stop->latitude) : ''; ?>" class="ife-stop-lat" style="width: 100%;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px;"><?php _e('Longitude:', 'inner-flow-events'); ?></label>
                    <input type="text" name="ife_stops[<?php echo $index; ?>][longitude]" value="<?php echo $stop ? esc_attr($stop->longitude) : ''; ?>" class="ife-stop-lng" style="width: 100%;">
                </div>
            </div>
            
            <input type="hidden" name="ife_stops[<?php echo $index; ?>][order]" value="<?php echo $index; ?>" class="ife-stop-order">
            <hr>
        </div>
        <?php
    }
    
    public function save_meta_boxes($post_id, $post) {
        // Check nonce
        if (!isset($_POST['ife_event_details_nonce']) || !wp_verify_nonce($_POST['ife_event_details_nonce'], 'ife_event_details_nonce')) {
            return;
        }
        
        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save event details
        if (isset($_POST['ife_event_date'])) {
            update_post_meta($post_id, '_ife_event_date', sanitize_text_field($_POST['ife_event_date']));
        }
        
        if (isset($_POST['ife_event_time'])) {
            update_post_meta($post_id, '_ife_event_time', sanitize_text_field($_POST['ife_event_time']));
        }
        
        if (isset($_POST['ife_event_location'])) {
            update_post_meta($post_id, '_ife_event_location', sanitize_text_field($_POST['ife_event_location']));
        }
        
        if (isset($_POST['ife_event_latitude'])) {
            update_post_meta($post_id, '_ife_event_latitude', sanitize_text_field($_POST['ife_event_latitude']));
        }
        
        if (isset($_POST['ife_event_longitude'])) {
            update_post_meta($post_id, '_ife_event_longitude', sanitize_text_field($_POST['ife_event_longitude']));
        }
        
        if (isset($_POST['ife_maps_iframe'])) {
            // Allow iframe tags with specific attributes
            $allowed_html = array(
                'iframe' => array(
                    'src' => array(),
                    'width' => array(),
                    'height' => array(),
                    'style' => array(),
                    'allowfullscreen' => array(),
                    'loading' => array(),
                    'referrerpolicy' => array(),
                    'frameborder' => array(),
                )
            );
            update_post_meta($post_id, '_ife_maps_iframe', wp_kses($_POST['ife_maps_iframe'], $allowed_html));
        }
        
        // Save route and stop points
        if (isset($_POST['ife_route_nonce']) && wp_verify_nonce($_POST['ife_route_nonce'], 'ife_route_nonce')) {
            $this->save_route_data($post_id);
        }
    }
    
    private function save_route_data($event_id) {
        global $wpdb;
        
        $route_name = isset($_POST['ife_route_name']) ? sanitize_text_field($_POST['ife_route_name']) : '';
        $difficulty = isset($_POST['ife_difficulty']) ? sanitize_text_field($_POST['ife_difficulty']) : 'moderate';
        
        $table_routes = $wpdb->prefix . 'ife_event_routes';
        $table_stops = $wpdb->prefix . 'ife_stop_points';
        
        // Check if route exists
        $route = IFE_Database::get_event_route($event_id);
        
        if ($route) {
            // Update existing route
            $wpdb->update(
                $table_routes,
                array(
                    'route_name' => $route_name,
                    'difficulty_level' => $difficulty
                ),
                array('id' => $route->id),
                array('%s', '%s'),
                array('%d')
            );
            $route_id = $route->id;
        } else {
            // Insert new route
            $wpdb->insert(
                $table_routes,
                array(
                    'event_id' => $event_id,
                    'route_name' => $route_name,
                    'difficulty_level' => $difficulty
                ),
                array('%d', '%s', '%s')
            );
            $route_id = $wpdb->insert_id;
        }
        
        // Delete existing stop points
        $wpdb->delete($table_stops, array('route_id' => $route_id), array('%d'));
        
        // Insert new stop points
        if (isset($_POST['ife_stops']) && is_array($_POST['ife_stops'])) {
            foreach ($_POST['ife_stops'] as $index => $stop) {
                if (!empty($stop['name'])) {
                    $wpdb->insert(
                        $table_stops,
                        array(
                            'route_id' => $route_id,
                            'stop_order' => intval($index),
                            'stop_name' => sanitize_text_field($stop['name']),
                            'stop_description' => sanitize_textarea_field($stop['description']),
                            'stop_time' => sanitize_text_field($stop['time']),
                            'duration_minutes' => intval($stop['duration']),
                            'latitude' => floatval($stop['latitude']),
                            'longitude' => floatval($stop['longitude'])
                        ),
                        array('%d', '%d', '%s', '%s', '%s', '%d', '%f', '%f')
                    );
                }
            }
        }
    }
}
?>
