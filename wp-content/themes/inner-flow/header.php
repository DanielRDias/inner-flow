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
                <div class="logo-text">
                    <h1 class="site-title"><?php bloginfo('name'); ?></h1>
                    <p class="site-tagline"><?php bloginfo('description'); ?></p>
                </div>
            </a>
        </div>

        <nav class="main-navigation">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'container'      => false,
            ));
            ?>
            
            <div class="nav-actions-desktop">
                <?php inner_flow_language_switcher(); ?>
            </div>
        </nav>

        <div class="header-right">
            <?php if (is_user_logged_in()) : 
                $current_user = wp_get_current_user();
            ?>
                <div class="user-menu">
                    <a href="<?php echo admin_url('profile.php'); ?>" class="user-avatar-link" title="<?php _e('Edit Profile', 'inner-flow'); ?>">
                        <?php echo get_avatar($current_user->ID, 36); ?>
                        <span class="user-name"><?php echo esc_html($current_user->display_name); ?></span>
                    </a>
                    <a href="<?php echo wp_logout_url(home_url()); ?>" class="logout-btn" title="<?php _e('Logout', 'inner-flow'); ?>">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                    </a>
                </div>
            <?php else : ?>
                <a href="<?php echo wp_login_url(); ?>" class="btn btn-outline-light btn-sm login-btn">
                    <?php _e('Login', 'inner-flow'); ?>
                </a>
            <?php endif; ?>

            <button class="mobile-menu-toggle" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
</header>
