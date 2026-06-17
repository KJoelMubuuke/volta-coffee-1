<?php get_header(); ?>

<main id="primary" class="site-main">

    <section class="page-hero">
        <div class="page-hero-overlay"></div>
        <div class="container">
            <h1><?php esc_html_e('Our Menu', 'volta-coffee'); ?></h1>
            <p><?php esc_html_e('Crafted with care, brewed fresh daily.', 'volta-coffee'); ?></p>
        </div>
    </section>

    <section class="menu-section">
        <div class="container">

            <div class="menu-category">
                <h2><?php esc_html_e('Coffee', 'volta-coffee'); ?></h2>
                <ul class="menu-list">
                    <li class="menu-item">
                        <span class="menu-item-name"><?php esc_html_e('Espresso', 'volta-coffee'); ?></span>
                        <span class="menu-item-price">UGX 4,000</span>
                    </li>
                    <li class="menu-item">
                        <span class="menu-item-name"><?php esc_html_e('Cappuccino', 'volta-coffee'); ?></span>
                        <span class="menu-item-price">UGX 6,000</span>
                    </li>
                    <li class="menu-item">
                        <span class="menu-item-name"><?php esc_html_e('Latte', 'volta-coffee'); ?></span>
                        <span class="menu-item-price">UGX 6,500</span>
                    </li>
                    <li class="menu-item">
                        <span class="menu-item-name"><?php esc_html_e('Cold Brew', 'volta-coffee'); ?></span>
                        <span class="menu-item-price">UGX 7,000</span>
                    </li>
                </ul>
            </div>

            <div class="menu-category">
                <h2><?php esc_html_e('Pastries', 'volta-coffee'); ?></h2>
                <ul class="menu-list">
                    <li class="menu-item">
                        <span class="menu-item-name"><?php esc_html_e('Croissant', 'volta-coffee'); ?></span>
                        <span class="menu-item-price">UGX 3,500</span>
                    </li>
                    <li class="menu-item">
                        <span class="menu-item-name"><?php esc_html_e('Banana Bread', 'volta-coffee'); ?></span>
                        <span class="menu-item-price">UGX 4,000</span>
                    </li>
                </ul>
            </div>

        </div>
    </section>

</main>

<?php get_footer(); ?>
