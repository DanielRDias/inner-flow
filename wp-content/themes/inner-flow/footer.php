<footer>
    <div class="container">
        <div class="footer-content">
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('All rights reserved.', 'inner-flow'); ?></p>
            
            <?php
            wp_nav_menu(array(
                'theme_location' => 'footer',
                'menu_id'        => 'footer-menu',
                'container'      => false,
            ));
            ?>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
