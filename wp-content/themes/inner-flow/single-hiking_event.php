<?php
/**
 * Single template for hiking events
 */

get_header();

while (have_posts()) : the_post();
    
    $event_id = get_the_ID();
    $event_date = get_post_meta($event_id, '_ife_event_date', true);
    $event_time = get_post_meta($event_id, '_ife_event_time', true);
    $event_location = get_post_meta($event_id, '_ife_event_location', true);
    $event_lat = get_post_meta($event_id, '_ife_event_latitude', true);
    $event_lng = get_post_meta($event_id, '_ife_event_longitude', true);
    $maps_iframe = get_post_meta($event_id, '_ife_maps_iframe', true);
    
    $route = IFE_Database::get_event_route($event_id);
    $stops = $route ? IFE_Database::get_route_stops($route->id) : array();
    
    $user_id = get_current_user_id();
    $is_registered = false;
    $user_registration = null;
    
    if ($user_id) {
        global $wpdb;
        $table_registrations = $wpdb->prefix . 'ife_event_registrations';
        $user_registration = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_registrations WHERE event_id = %d AND user_id = %d",
            $event_id, $user_id
        ));
        $is_registered = !empty($user_registration);
    }
    ?>
    
    <div class="ife-event-single" data-event-id="<?php echo esc_attr($event_id); ?>">
        
        <div class="ife-event-header">
            <h1 class="ife-event-title"><?php the_title(); ?></h1>
            
            <div class="ife-event-meta">
                <?php if ($event_date) : ?>
                    <div class="ife-meta-item">
                        <i>📅</i>
                        <strong><?php _e('Date:', 'inner-flow-events'); ?></strong>
                        <span><?php echo date_i18n(get_option('date_format'), strtotime($event_date)); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($event_time) : ?>
                    <div class="ife-meta-item">
                        <i>🕐</i>
                        <strong><?php _e('Time:', 'inner-flow-events'); ?></strong>
                        <span><?php echo esc_html($event_time); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($event_location) : ?>
                    <div class="ife-meta-item">
                        <i>📍</i>
                        <strong><?php _e('Location:', 'inner-flow-events'); ?></strong>
                        <span><?php echo esc_html($event_location); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($route && $route->difficulty_level) : ?>
                    <div class="ife-meta-item">
                        <i>⚡</i>
                        <strong><?php _e('Difficulty:', 'inner-flow-events'); ?></strong>
                        <span class="ife-difficulty-badge <?php echo esc_attr($route->difficulty_level); ?>">
                            <?php echo esc_html(ucfirst($route->difficulty_level)); ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="ife-event-content">
            <?php the_content(); ?>
        </div>
        
        <?php if ($maps_iframe) : ?>
            <div class="ife-event-map-embed">
                <h3><?php _e('Event Location', 'inner-flow-events'); ?></h3>
                <?php
                // Extract src from iframe code
                if (preg_match('/src="([^"]+)"/', $maps_iframe, $matches)) {
                    $embed_url = $matches[1];
                    ?>
                    <iframe 
                        src="<?php echo esc_url($embed_url); ?>"
                        width="100%" 
                        height="450" 
                        style="border:0; border-radius: 8px; margin: 20px 0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                    <?php
                } else {
                    echo '<p>' . __('Invalid embed code', 'inner-flow-events') . '</p>';
                }
                ?>
            </div>
        <?php elseif ($event_lat && $event_lng) : ?>
            <div id="ife-event-map" 
                 data-lat="<?php echo esc_attr($event_lat); ?>" 
                 data-lng="<?php echo esc_attr($event_lng); ?>"
                 data-stops='<?php echo json_encode($stops); ?>'>
            </div>
        <?php endif; ?>
        
        <?php if ($route && !empty($stops)) : ?>
            <div class="ife-route-info">
                <h3><?php _e('Route & Stop Points', 'inner-flow-events'); ?></h3>
                
                <?php if ($route->route_name) : ?>
                    <p><strong><?php _e('Route Name:', 'inner-flow-events'); ?></strong> <?php echo esc_html($route->route_name); ?></p>
                <?php endif; ?>
                
                <div class="ife-stop-points-list">
                    <?php foreach ($stops as $index => $stop) : ?>
                        <div class="ife-stop-item" id="stop-<?php echo $stop->id; ?>">
                            <div class="ife-stop-header">
                                <span class="ife-stop-number"><?php echo $index + 1; ?></span>
                                <span class="ife-stop-name"><?php echo esc_html($stop->stop_name); ?></span>
                            </div>
                            
                            <?php if ($stop->stop_time) : ?>
                                <div class="ife-stop-time">
                                    ⏰ <?php echo esc_html($stop->stop_time); ?>
                                    <?php if ($stop->duration_minutes) : ?>
                                        (<?php printf(__('%d minutes', 'inner-flow-events'), $stop->duration_minutes); ?>)
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($stop->stop_description) : ?>
                                <div class="ife-stop-description">
                                    <?php echo wp_kses_post(nl2br($stop->stop_description)); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="ife-registration-section">
            <h3><?php _e('Event Registration', 'inner-flow-events'); ?></h3>
            
            <?php if (!is_user_logged_in()) : ?>
                <p><?php _e('Please log in with your Google account to register for this event.', 'inner-flow-events'); ?></p>
                <a href="<?php echo wp_login_url(get_permalink()); ?>" class="btn btn-primary">
                    <?php _e('Login with Google', 'inner-flow-events'); ?>
                </a>
            <?php elseif ($is_registered) : ?>
                <p><?php _e('You are registered for this event!', 'inner-flow-events'); ?></p>
                <p><strong><?php _e('Status:', 'inner-flow-events'); ?></strong> <?php echo esc_html($user_registration->registration_status); ?></p>
                
                <?php if ($user_registration->join_stop_id && !empty($stops)) : ?>
                    <?php 
                    $join_stop = null;
                    foreach ($stops as $stop) {
                        if ($stop->id == $user_registration->join_stop_id) {
                            $join_stop = $stop;
                            break;
                        }
                    }
                    if ($join_stop) :
                    ?>
                        <p><strong><?php _e('Joining at:', 'inner-flow-events'); ?></strong> <?php echo esc_html($join_stop->stop_name); ?></p>
                    <?php endif; ?>
                <?php endif; ?>
                
                <button class="ife-unregister-btn" data-event-id="<?php echo esc_attr($event_id); ?>">
                    <?php _e('Unregister', 'inner-flow-events'); ?>
                </button>
            <?php else : ?>
                <form class="ife-registration-form">
                    <?php if (!empty($stops)) : ?>
                        <div class="ife-form-group">
                            <label><?php _e('Join at stop point:', 'inner-flow-events'); ?></label>
                            <select id="ife_join_stop" name="join_stop_id">
                                <option value=""><?php _e('Start (Beginning)', 'inner-flow-events'); ?></option>
                                <?php foreach ($stops as $index => $stop) : ?>
                                    <option value="<?php echo esc_attr($stop->id); ?>">
                                        <?php printf(__('Stop %d: %s', 'inner-flow-events'), $index + 1, $stop->stop_name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <button type="button" class="ife-register-btn" data-event-id="<?php echo esc_attr($event_id); ?>" data-status="joining">
                        <?php _e('Register for Event', 'inner-flow-events'); ?>
                    </button>
                </form>
            <?php endif; ?>
        </div>
        
        <div class="ife-participants-section">
            <h3><?php _e('Participants', 'inner-flow-events'); ?></h3>
            <div id="ife-participants-list"></div>
        </div>
        
    </div>
    
<?php endwhile;

get_footer();
?>
