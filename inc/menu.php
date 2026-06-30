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
	$price              = get_post_meta( $post->ID, '_volta_price', true );
	$linked_product_id  = (int) get_post_meta( $post->ID, '_volta_linked_product_id', true );

	echo '<p><label for="volta_price_field"><strong>' . esc_html__( 'Manual price', 'volta-coffee' ) . '</strong></label><br />';
	echo '<input type="number" id="volta_price_field" name="volta_price_field" value="' . esc_attr( $price ) . '" min="0" step="100" style="width:100%;" placeholder="e.g. 6000" /></p>';
	echo '<p class="description">' . esc_html__( 'Enter the amount only. UGX is added automatically. Used when no shop product is linked below.', 'volta-coffee' ) . '</p>';

	if ( function_exists( 'wc_get_products' ) ) {
		$products = wc_get_products( array( 'limit' => -1, 'status' => 'publish', 'orderby' => 'title', 'order' => 'ASC' ) );

		echo '<p><label for="volta_linked_product_field"><strong>' . esc_html__( 'Linked shop product', 'volta-coffee' ) . '</strong></label><br />';
		echo '<select id="volta_linked_product_field" name="volta_linked_product_field" style="width:100%;">';
		echo '<option value="0">' . esc_html__( '— None, use manual price —', 'volta-coffee' ) . '</option>';
		foreach ( $products as $product ) {
			printf(
				'<option value="%1$d" %2$s>%3$s</option>',
				absint( $product->get_id() ),
				selected( $linked_product_id, $product->get_id(), false ),
				esc_html( $product->get_name() )
			);
		}
		echo '</select></p>';
		echo '<p class="description">' . esc_html__( 'When set, the live shop price is shown here instead — so the Menu page and Shop page never disagree.', 'volta-coffee' ) . '</p>';
	}
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
	if ( isset( $_POST['volta_linked_product_field'] ) ) {
		update_post_meta( $post_id, '_volta_linked_product_id', absint( wp_unslash( $_POST['volta_linked_product_field'] ) ) );
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
		$price  = volta_get_price( $post_id );
		$linked = (int) get_post_meta( $post_id, '_volta_linked_product_id', true );
		echo $price ? esc_html( $price ) : '-';
		if ( $price && $linked ) {
			echo ' <span class="dashicons dashicons-admin-links" style="font-size:14px;width:14px;height:14px;vertical-align:middle;" title="' . esc_attr__( 'Linked to shop product', 'volta-coffee' ) . '"></span>';
		}
	}
}
add_action( 'manage_menu_item_posts_custom_column', 'volta_menu_item_column_content', 10, 2 );

/**
 * Resolve a menu item's display price.
 * A linked WooCommerce product (if set and still valid) always wins, so the
 * Menu page and Shop page can never show two different prices for the same item.
 */
function volta_get_price( $post_id ) {
	$linked_product_id = (int) get_post_meta( $post_id, '_volta_linked_product_id', true );

	if ( $linked_product_id && function_exists( 'wc_get_product' ) ) {
		$product = wc_get_product( $linked_product_id );
		if ( $product ) {
			$price = $product->get_price();
			if ( '' !== $price ) {
				return 'UGX ' . number_format( (float) $price );
			}
		}
	}

	$price = get_post_meta( $post_id, '_volta_price', true );
	return $price ? 'UGX ' . number_format( (int) $price ) : '';
}

/**
 * Fetch all published menu items grouped by category, in a single query.
 * Cached for an hour; invalidated whenever a menu item, a menu category,
 * or a linked WooCommerce product's price changes.
 */
function volta_get_menu_data() {
	$cached = get_transient( 'volta_menu_items_v1' );
	if ( false !== $cached ) {
		return $cached;
	}

	$categories = get_terms( array( 'taxonomy' => 'menu_category', 'hide_empty' => true ) );
	if ( empty( $categories ) || is_wp_error( $categories ) ) {
		set_transient( 'volta_menu_items_v1', array(), HOUR_IN_SECONDS );
		return array();
	}

	$all_items = new WP_Query( array(
		'post_type'      => 'menu_item',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
	) );

	$items_by_slug = array();
	foreach ( $all_items->posts as $item_post ) {
		$terms = get_the_terms( $item_post, 'menu_category' );
		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			continue;
		}
		foreach ( $terms as $term ) {
			$items_by_slug[ $term->slug ][] = array(
				'id'      => $item_post->ID,
				'title'   => get_the_title( $item_post ),
				'excerpt' => has_excerpt( $item_post ) ? get_the_excerpt( $item_post ) : '',
				'price'   => volta_get_price( $item_post->ID ),
			);
		}
	}

	$data = array();
	foreach ( $categories as $cat ) {
		if ( empty( $items_by_slug[ $cat->slug ] ) ) {
			continue;
		}
		$data[] = array(
			'slug'  => $cat->slug,
			'name'  => $cat->name,
			'items' => $items_by_slug[ $cat->slug ],
		);
	}

	set_transient( 'volta_menu_items_v1', $data, HOUR_IN_SECONDS );
	return $data;
}

function volta_clear_menu_cache() {
	delete_transient( 'volta_menu_items_v1' );
}
add_action( 'save_post_menu_item', 'volta_clear_menu_cache' );
add_action( 'wp_trash_post', 'volta_clear_menu_cache' );
add_action( 'untrashed_post', 'volta_clear_menu_cache' );
add_action( 'deleted_post', 'volta_clear_menu_cache' );
add_action( 'edited_menu_category', 'volta_clear_menu_cache' );
add_action( 'create_menu_category', 'volta_clear_menu_cache' );
add_action( 'delete_menu_category', 'volta_clear_menu_cache' );
// A linked product's price can change from the Shop side too — keep the Menu page in sync.
add_action( 'save_post_product', 'volta_clear_menu_cache' );
