<?php
/**
 * Template: Menu page (database-driven, admin-managed).
 *
 * @package Volta_Coffee
 */
get_header();

$menu_data = volta_get_menu_data();
?>

<main id="primary" class="site-main">
	<section class="page-hero">
		<div class="page-hero-overlay"></div>
		<div class="container">
			<span class="eyebrow"><?php esc_html_e( 'Roasted & brewed in-house', 'volta-coffee' ); ?></span>
			<h1><?php esc_html_e( 'Our Menu', 'volta-coffee' ); ?></h1>
			<p><?php esc_html_e( 'Crafted with care, brewed fresh daily.', 'volta-coffee' ); ?></p>
		</div>
	</section>

	<section class="menu-section">
		<div class="container">
		<?php if ( ! empty( $menu_data ) ) : ?>
			<div class="menu-filters" role="tablist" aria-label="<?php esc_attr_e( 'Filter menu by category', 'volta-coffee' ); ?>">
				<button class="menu-filter is-active" data-filter="all" role="tab" aria-selected="true"><?php esc_html_e( 'All', 'volta-coffee' ); ?></button>
				<?php foreach ( $menu_data as $cat ) : ?>
					<button class="menu-filter" data-filter="<?php echo esc_attr( $cat['slug'] ); ?>" role="tab" aria-selected="false"><?php echo esc_html( $cat['name'] ); ?></button>
				<?php endforeach; ?>
			</div>
			<div class="menu-results" aria-live="polite">
			<?php foreach ( $menu_data as $cat ) : ?>
				<div class="menu-category" data-category="<?php echo esc_attr( $cat['slug'] ); ?>">
					<h2><?php echo esc_html( $cat['name'] ); ?></h2>
					<ul class="menu-list">
					<?php foreach ( $cat['items'] as $item ) : ?>
						<li class="menu-item">
							<span class="menu-item-name"><?php echo esc_html( $item['title'] ); ?></span>
							<?php if ( $item['excerpt'] ) : ?><span class="menu-item-desc"><?php echo esc_html( $item['excerpt'] ); ?></span><?php endif; ?>
							<span class="menu-item-price"><?php echo esc_html( $item['price'] ); ?></span>
						</li>
					<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
			</div>
		<?php else : ?>
			<div class="menu-empty">
				<p><?php esc_html_e( 'Our menu is being freshly prepared. Please check back shortly.', 'volta-coffee' ); ?></p>
				<?php if ( current_user_can( 'edit_posts' ) ) : ?>
					<p class="menu-empty-admin"><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=menu_item' ) ); ?>"><?php esc_html_e( 'Admin: add menu items here', 'volta-coffee' ); ?></a></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
