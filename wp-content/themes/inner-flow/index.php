<?php
/**
 * The main template file
 */
get_header(); 

// Query hiking events for the homepage
$events_query = new WP_Query(array(
    'post_type' => 'hiking_event',
    'posts_per_page' => 12,
    'orderby' => 'date',
    'order' => 'DESC'
));
?>

<section class="hero-section">
    <div class="container">
        <h1><?php _e('Welcome to Inner Flow', 'inner-flow'); ?></h1>
        <p class="hero-subtitle"><?php _e('Bem-vindo ao Inner Flow', 'inner-flow'); ?></p>
        <p class="hero-description"><?php _e('Discover the beauty of nature through our guided hiking experiences. Join our community of outdoor enthusiasts and explore beautiful trails together!', 'inner-flow'); ?></p>
        <div class="hero-cta">
            <a href="#events" class="btn btn-primary"><?php _e('Explore Events', 'inner-flow'); ?></a>
            <?php if (!is_user_logged_in()) : ?>
                <a href="<?php echo wp_login_url(); ?>" class="btn btn-success"><?php _e('Join Us', 'inner-flow'); ?></a>
            <?php endif; ?>
        </div>
    </div>
</section>

<main class="container" id="events">
    <div style="text-align: center; margin-bottom: 50px;">
        <h2 class="section-title"><?php _e('Upcoming Hiking Events', 'inner-flow'); ?></h2>
        <p class="section-subtitle"><?php _e('Próximas Caminhadas', 'inner-flow'); ?></p>
    </div>

    <?php if ($events_query->have_posts()) : ?>
        <div class="events-grid">
            <?php while ($events_query->have_posts()) : $events_query->the_post(); 
                $event_id = get_the_ID();
                $event_date = get_post_meta($event_id, '_ife_event_date', true);
                $event_time = get_post_meta($event_id, '_ife_event_time', true);
                $event_location = get_post_meta($event_id, '_ife_event_location', true);
                
                $route = IFE_Database::get_event_route($event_id);
                $registrations = IFE_Database::get_event_registrations($event_id);
                $participant_count = count(array_filter($registrations, function($reg) {
                    return $reg->registration_status === 'joining';
                }));
            ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('event-card'); ?>>
                    <div class="event-card-header">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium'); ?>
                        <?php else: ?>
                            <span style="font-size: 3.5em;">🏔️</span>
                        <?php endif; ?>
                    </div>
                    <div class="event-card-body">
                        <?php if ($event_date) : ?>
                            <div class="event-date">
                                📅 <?php echo date_i18n(get_option('date_format'), strtotime($event_date)); ?>
                                <?php if ($event_time) : ?>
                                    &nbsp;•&nbsp; 🕐 <?php echo esc_html($event_time); ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <h2 class="event-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        
                        <?php if ($event_location) : ?>
                            <div class="event-location">
                                📍 <?php echo esc_html($event_location); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($route && $route->difficulty_level) : ?>
                            <div class="event-difficulty">
                                <span class="ife-difficulty-badge <?php echo esc_attr($route->difficulty_level); ?>">
                                    <?php echo esc_html(ucfirst($route->difficulty_level)); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="event-description">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <div class="event-footer">
                            <span class="participant-count">
                                👥 <?php printf(__('%d participants', 'inner-flow'), $participant_count); ?>
                            </span>
                            
                            <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm">
                                <?php _e('View Details', 'inner-flow'); ?>
                            </a>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
        
        <?php wp_reset_postdata(); ?>
        
    <?php else : ?>
        <div class="empty-state">
            <div class="empty-state-icon">🥾</div>
            <p><?php _e('No upcoming hiking events yet. Stay tuned for new adventures!', 'inner-flow'); ?></p>
            <?php if (current_user_can('edit_posts')) : ?>
                <a href="<?php echo admin_url('post-new.php?post_type=hiking_event'); ?>" class="btn btn-success">
                    <?php _e('Create Your First Event', 'inner-flow'); ?>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
