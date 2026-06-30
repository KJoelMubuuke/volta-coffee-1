<?php
/**
 * Template: Universal fallback (blog index, search results, archives, 404s).
 *
 * @package Volta_Coffee
 */
get_header();

if ( is_search() ) {
	/* translators: %s: search query */
	$page_title = sprintf( esc_html__( 'Search results for: %s', 'volta-coffee' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
	$page_lede  = '';
} elseif ( is_404() ) {
	$page_title = esc_html__( 'Page not found', 'volta-coffee' );
	$page_lede  = esc_html__( "The page you're looking for has moved or no longer exists.", 'volta-coffee' );
} elseif ( is_post_type_archive( 'coffee_origin' ) ) {
	$page_title = esc_html__( 'Coffee Origins', 'volta-coffee' );
	$page_lede  = esc_html__( 'Where our beans come from.', 'volta-coffee' );
} elseif ( is_archive() ) {
	$page_title = get_the_archive_title();
	$page_lede  = '';
} else {
	$page_title = esc_html__( 'Latest Updates', 'volta-coffee' );
	$page_lede  = '';
}
?>

<main id="primary" class="site-main">
	<section class="page-hero">
		<div class="page-hero-overlay"></div>
		<div class="container">
			<h1><?php echo wp_kses_post( $page_title ); ?></h1>
			<?php if ( $page_lede ) : ?>
				<p><?php echo esc_html( $page_lede ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<section class="fallback-section">
		<div class="container">
			<?php if ( is_404() ) : ?>
				<div class="menu-empty">
					<p><?php esc_html_e( "Here's what you can do instead:", 'volta-coffee' ); ?></p>
					<p>
						<a class="btn btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to Home', 'volta-coffee' ); ?></a>
						<?php $menu_page = get_page_by_path( 'menu' ); ?>
						<?php if ( $menu_page ) : ?>
							<a class="btn btn-secondary" href="<?php echo esc_url( get_permalink( $menu_page ) ); ?>"><?php esc_html_e( 'View Our Menu', 'volta-coffee' ); ?></a>
						<?php endif; ?>
					</p>
				</div>
			<?php elseif ( have_posts() ) : ?>
				<div class="post-list">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
							<?php if ( has_post_thumbnail() ) : ?>
								<a class="post-card-thumb" href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy', 'alt' => the_title_attribute( array( 'echo' => false ) ) ) ); ?>
								</a>
							<?php endif; ?>
							<div class="post-card-body">
								<h2 class="post-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<p class="post-card-meta"><?php echo esc_html( get_the_date() ); ?></p>
								<div class="post-card-excerpt"><?php the_excerpt(); ?></div>
								<a class="post-card-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'volta-coffee' ); ?> &rarr;</a>
							</div>
						</article>
						<?php
					endwhile;
					?>
				</div>

				<nav class="pagination" aria-label="<?php esc_attr_e( 'Posts navigation', 'volta-coffee' ); ?>">
					<?php
					echo wp_kses_post(
						paginate_links(
							array(
								'prev_text' => esc_html__( '&larr; Newer', 'volta-coffee' ),
								'next_text' => esc_html__( 'Older &rarr;', 'volta-coffee' ),
							)
						)
					);
					?>
				</nav>
			<?php else : ?>
				<div class="menu-empty">
					<p><?php esc_html_e( 'Nothing here yet. Please check back shortly.', 'volta-coffee' ); ?></p>
					<?php if ( is_search() ) : ?>
						<div class="fallback-search"><?php get_search_form(); ?></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
