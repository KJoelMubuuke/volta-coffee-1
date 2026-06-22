<?php
/**
 * Volta Coffee Theme Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Theme Setup
 */
function volta_coffee_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ) );
    add_theme_support( 'responsive-embeds' );

    register_nav_menus( array(
        'primary' => esc_html__( 'Primary Menu', 'volta-coffee' ),
        'footer'  => esc_html__( 'Footer Menu', 'volta-coffee' ),
    ) );
}
add_action( 'after_setup_theme', 'volta_coffee_setup' );

/**
 * Enqueue Styles & Scripts
 * Depend on WooCommerce's stylesheet so ours loads AFTER and overrides it.
 */
function volta_coffee_scripts() {
    $deps = array();

    if ( class_exists( 'WooCommerce' ) ) {
        $deps[] = 'woocommerce-general';
        $deps[] = 'woocommerce-layout';
        $deps[] = 'woocommerce-smallscreen';
    }

    wp_enqueue_style(
        'volta-coffee-style',
        get_stylesheet_uri(),
        $deps,
        wp_get_theme()->get( 'Version' )
    );
}
add_action( 'wp_enqueue_scripts', 'volta_coffee_scripts' );

/**
 * SEO: Add meta description dynamically per page
 */
function volta_coffee_meta_description() {
    if ( is_front_page() ) {
        $description = 'Volta Coffee — your neighborhood coffee shop. Order online, browse our menu, and visit us in store for freshly roasted coffee and pastries.';
    } elseif ( is_singular() ) {
        $description = wp_trim_words( get_the_excerpt(), 30, '...' );
    } else {
        $description = get_bloginfo( 'description' );
    }
    echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
}
add_action( 'wp_head', 'volta_coffee_meta_description', 1 );

/**
 * SEO: Local Business Structured Data (Schema.org JSON-LD)
 */
function volta_coffee_schema_markup() {
    if ( ! is_front_page() ) {
        return;
    }
    $schema = array(
        '@context'   => 'https://schema.org',
        '@type'      => 'CafeOrCoffeeShop',
        'name'       => get_bloginfo( 'name' ),
        'url'        => home_url( '/' ),
        'description'=> 'Volta Coffee — your neighborhood coffee shop offering freshly roasted coffee, pastries, and online ordering.',
        'servesCuisine' => 'Coffee',
    );
    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
}
add_action( 'wp_head', 'volta_coffee_schema_markup' );

/**
 * Custom Post Type: Coffee Origins
 */
function volta_register_coffee_origins() {
    register_post_type( 'coffee_origin', array(
        'labels' => array(
            'name'          => __( 'Coffee Origins', 'volta-coffee' ),
            'singular_name' => __( 'Coffee Origin', 'volta-coffee' ),
            'add_new_item'  => __( 'Add New Origin', 'volta-coffee' ),
            'edit_item'     => __( 'Edit Origin', 'volta-coffee' ),
        ),
        'public'       => true,
        'has_archive'  => true,
        'show_in_rest' => true,
        'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'menu_icon'    => 'dashicons-coffee',
        'rewrite'      => array( 'slug' => 'coffee-origins' ),
    ) );
}
add_action( 'init', 'volta_register_coffee_origins' );

/**
 * WooCommerce Theme Support
 */
function volta_coffee_woocommerce_support() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'volta_coffee_woocommerce_support' );

/**
 * Remove WooCommerce default sidebar entirely (cleaner than CSS hiding)
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar' );
