<?php get_header(); ?>

<main id="primary" class="site-main">
    <div class="container" style="padding: 4rem 0;">
        <?php
        while ( have_posts() ) :
            the_post();
            the_title( '<h1>', '</h1>' );
            the_content();
        endwhile;
        ?>
    </div>
</main>

<?php get_footer(); ?>
