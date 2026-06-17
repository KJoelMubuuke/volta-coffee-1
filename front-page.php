<?php get_header(); ?>

<main id="primary" class="site-main">

    <section class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-inner">
            <h1><?php esc_html_e('Volta Coffee', 'volta-coffee'); ?></h1>
            <p class="hero-tagline"><?php esc_html_e('Freshly roasted coffee, baked daily pastries, and a warm place to belong.', 'volta-coffee'); ?></p>

            <div class="hero-buttons">
                <?php $menu_page = get_page_by_path( 'menu' ); ?>
                <a href="<?php echo $menu_page ? esc_url( get_permalink( $menu_page ) ) : '#'; ?>" class="btn btn-primary">
                    <?php esc_html_e('View Our Menu', 'volta-coffee'); ?>
                </a>

                <?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-secondary">
                        <?php esc_html_e('Order Online', 'volta-coffee'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="about-preview">
        <div class="container about-grid">
            <div class="about-text">
                <h2><?php esc_html_e('Our Story', 'volta-coffee'); ?></h2>
                <p><?php esc_html_e('Volta Coffee began with a simple idea: great coffee should bring people together. Every cup we serve is roasted locally and brewed with care.', 'volta-coffee'); ?></p>
            </div>
            <div class="about-image">
                <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/about-our-story.jpg" alt="<?php esc_attr_e('Freshly brewed coffee cup at Volta Coffee', 'volta-coffee'); ?>" loading="lazy">
            </div>
        </div>
    </section>

    <section class="hours-location">
        <div class="hours-overlay"></div>
        <div class="container hours-content">
            <h2><?php esc_html_e('Visit Us', 'volta-coffee'); ?></h2>
            <address>
                <p><?php esc_html_e('123 Coffee Lane, Kampala, Uganda', 'volta-coffee'); ?></p>
                <p><?php esc_html_e('Mon–Sat: 7am – 7pm | Sun: 8am – 5pm', 'volta-coffee'); ?></p>
            </address>
        </div>
    </section>

</main>

<?php get_footer(); ?>
