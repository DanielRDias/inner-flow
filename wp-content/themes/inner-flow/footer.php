<footer>
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="footer-logo">
                    <span class="logo-icon">🏔️</span>
                    <span class="logo-name"><?php bloginfo('name'); ?></span>
                </div>
                <p class="footer-description">
                    <?php _e('Discover the beauty of nature through our guided hiking experiences. Join our community of outdoor enthusiasts!', 'inner-flow'); ?>
                </p>
                <div class="social-links">
                    <a href="#" aria-label="Facebook">📘</a>
                    <a href="#" aria-label="Instagram">📷</a>
                    <a href="#" aria-label="Twitter">🐦</a>
                </div>
            </div>
            
            <div class="footer-links">
                <h4><?php _e('Quick Links', 'inner-flow'); ?></h4>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'menu_id'        => 'footer-menu',
                    'container'      => false,
                ));
                ?>
            </div>
            
            <div class="footer-contact">
                <h4><?php _e('Contact Us', 'inner-flow'); ?></h4>
                <p>📧 info@innerflow.com</p>
                <p>📍 Portugal</p>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('All rights reserved.', 'inner-flow'); ?></p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
