<?php
defined( 'ABSPATH' ) || exit;

function volta_register_menu_items() {
	$labels = array(
		'name'          => _x( 'Menu Items', 'post type general name', 'volta-coffee' ),
		'singular_name' => _x( 'Menu Item', 'post type singular name', 'volta-coffee' ),
		'menu_name'     => _x( 'Cafe Menu', 'admin menu', 'volta-coffee' ),
		'add_new_item'  => __( 'Add New Menu Item', 'volta-coffee' ),
		'edit_item'     => __( 'Edit Menu Item', 'volta-coffee' ),
		'not_found'     => __( 'No menu items found.', 'volta-coffee' ),
	);
	register_post_type( 'menu_item', array(
		'labels'        => $labels,
		'public'        => true,
		'has_archive'   => false,
		'show_in_rest'  => true,
		'menu_icon'     => 'dashicons-coffee',
		'menu_position' => 25,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
		'rewrite'       => array( 'slug' => 'menu-item' ),
	) );
}
add_action( 'init', 'volta_register_menu_items' );

function volta_register_menu_categories() {
	$labels = array(
		'name'          => _x( 'Menu Categories', 'taxonomy general name', 'volta-coffee' ),
		'singular_name' => _x( 'Menu Category', 'taxonomy singular name', 'volta-coffee' ),
		'add_new_item'  => __( 'Add New Category', 'volta-coffee' ),
		'menu_name'     => __( 'Menu Categories', 'volta-coffee' ),
	);
	register_taxonomy( 'menu_category', array( 'menu_item' ), array(
		'labels'            => $labels,
		'public'            => true,
		'hierarchical'      => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'rewrite'           => array( 'slug' => 'menu-category' ),
	) );
}
add_action( 'init', 'volta_register_menu_categories' );

function volta_add_price_meta_box() {
	add_meta_box( 'volta_price', __( 'Price (UGX)', 'volta-coffee' ), 'volta_render_price_meta_box', 'menu_item', 'side', 'high' );
}
add_action( 'add_meta_boxes', 'volta_add_price_meta_box' );

function volta_render_price_meta_box( $post ) {
	wp_nonce_field( 'volta_save_price', 'volta_price_nonce' );
	$price = get_post_meta( $post->ID, '_volta_price', true );
	echo '<p><input type="number" name="volta_price_field" value="' . esc_attr( $price ) . '" min="0" step="100" style="width:100%;" placeholder="e.g. 6000" /></p>';
	echo '<p class="description">' . esc_html__( 'Enter the amount only. UGX is added automatically.', 'volta-coffee' ) . '</p>';
}

function volta_save_price_meta( $post_id ) {
	if ( ! isset( $_POST['volta_price_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['volta_price_nonce'] ), 'volta_save_price' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['volta_price_field'] ) ) {
		update_post_meta( $post_id, '_volta_price', absint( wp_unslash( $_POST['volta_price_field'] ) ) );
	}
}
add_action( 'save_post_menu_item', 'volta_save_price_meta' );

function volta_menu_item_columns( $columns ) {
	$columns['volta_price'] = __( 'Price (UGX)', 'volta-coffee' );
	return $columns;
}
add_filter( 'manage_menu_item_posts_columns', 'volta_menu_item_columns' );

function volta_menu_item_column_content( $column, $post_id ) {
	if ( 'volta_price' === $column ) {
		$price = get_post_meta( $post_id, '_volta_price', true );
		echo $price ? 'UGX ' . esc_html( number_format( (int) $price ) ) : '-';
	}
}
add_action( 'manage_menu_item_posts_custom_column', 'volta_menu_item_column_content', 10, 2 );

function volta_get_price( $post_id ) {
	$price = get_post_meta( $post_id, '_volta_price', true );
	return $price ? 'UGX ' . number_format( (int) $price ) : '';
}
