<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header>
    <div class="container header-inner">
        <div class="site-branding">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-link">
                <span class="logo-icon">🏔️</span>
                <div class="logo-text">
                    <h1 class="site-title"><?php bloginfo('name'); ?></h1>
                    <p class="site-tagline"><?php bloginfo('description'); ?></p>
                </div>
            </a>
        </div>

        <button class="mobile-menu-toggle" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav class="main-navigation">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'container'      => false,
            ));
            ?>
            
            <div class="nav-actions">
                <?php inner_flow_language_switcher(); ?>
                
                <?php if (is_user_logged_in()) : ?>
                    <a href="<?php echo wp_logout_url(home_url()); ?>" class="btn btn-outline-light btn-sm">
                        <?php _e('Logout', 'inner-flow'); ?>
                    </a>
                <?php else : ?>
                    <a href="<?php echo wp_login_url(); ?>" class="btn btn-outline-light btn-sm">
                        <?php _e('Login', 'inner-flow'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>
