<?php
/**
 * The page template file
 */
get_header(); 
?>

<main class="container page-content">
    <?php 
    if (have_posts()) : 
        while (have_posts()) : the_post(); 
    ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="page-header">
                <h1><?php the_title(); ?></h1>
            </header>

            <div class="page-body">
                <?php the_content(); ?>
            </div>

            <?php
            wp_link_pages(array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'inner-flow'),
                'after'  => '</div>',
            ));
            ?>
        </article>

    <?php 
        endwhile; 
    endif; 
    ?>
</main>

<?php get_footer(); ?>
