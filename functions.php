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
    // SEO: let WordPress manage <title> tags dynamically
    add_theme_support( 'title-tag' );

    // Allow custom logo upload
    add_theme_support( 'custom-logo' );

    // Featured images (important for SEO + social sharing previews)
    add_theme_support( 'post-thumbnails' );

    // HTML5 semantic markup for search forms, comments, galleries
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ) );

    // Responsive embeds (YouTube, etc.)
    add_theme_support( 'responsive-embeds' );

    // Register navigation menu
    register_nav_menus( array(
        'primary' => esc_html__( 'Primary Menu', 'volta-coffee' ),
        'footer'  => esc_html__( 'Footer Menu', 'volta-coffee' ),
    ) );
}
add_action( 'after_setup_theme', 'volta_coffee_setup' );

/**
 * Enqueue Styles & Scripts
 */
function volta_coffee_scripts() {
    wp_enqueue_style(
        'volta-coffee-style',
        get_stylesheet_uri(),
        array(),
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
 * Helps Google show Volta Coffee in local search & maps results
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
