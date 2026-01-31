<?php
/**
 * Archive template for hiking events
 */

get_header(); ?>

<main class="container">
    <header class="page-header">
        <h1><?php _e('Hiking Events', 'inner-flow'); ?></h1>
        <p><?php _e('Browse our upcoming hiking events / Navegue pelos nossos próximos eventos de caminhadas', 'inner-flow'); ?></p>
    </header>

    <?php if (have_posts()) : ?>
        <div class="events-grid">
            <?php while (have_posts()) : the_post(); 
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
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="event-card-body">
                        <?php if ($event_date) : ?>
                            <div class="event-date">
                                📅 <?php echo date_i18n(get_option('date_format'), strtotime($event_date)); ?>
                                <?php if ($event_time) : ?>
                                    | 🕐 <?php echo esc_html($event_time); ?>
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
                            
                            <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                                <?php _e('View Details', 'inner-flow'); ?>
                            </a>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
        
        <?php the_posts_navigation(); ?>
        
    <?php else : ?>
        <p><?php _e('No hiking events found.', 'inner-flow'); ?></p>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
