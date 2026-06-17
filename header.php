<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php bloginfo('description'); ?> Order coffee online, view our menu, and visit us in store.">
    <meta name="robots" content="index, follow">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'volta-coffee'); ?></a>

<header id="masthead" class="site-header" role="banner">
    <div class="header-inner">
        <div class="site-branding">
            <?php if (has_custom_logo()) : the_custom_logo(); else : ?>
                <p class="site-title">
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <?php bloginfo('name'); ?>
                    </a>
                </p>
            <?php endif; ?>
        </div>

        <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Primary Menu', 'volta-coffee'); ?>">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'container'      => false,
                'fallback_cb'    => false,
            ));
            ?>
        </nav>
    </div>
</header>
