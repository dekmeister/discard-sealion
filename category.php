<?php
/**
 * The category archive template file
 *
 * Displays category archives with a grid of CD covers
 *
 * @package Discard_Sealion
 */

get_header();
?>

<div class="cd-grid-container">
	<header class="archive-header">
		<h1 class="archive-title">
			<?php single_cat_title(); ?>
		</h1>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="cd-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', 'grid-item' );
			endwhile;
			?>
		</div>
	<?php else : ?>
		<div class="no-posts">
			<p class="no-posts-text">
				<?php
				$category = get_queried_object();
				if ( $category && 'keep' === $category->slug ) {
					esc_html_e( 'No CDs kept yet.', 'discard-sealion' );
				} elseif ( $category && 'delete' === $category->slug ) {
					esc_html_e( 'No CDs deleted yet.', 'discard-sealion' );
				} else {
					esc_html_e( 'No CDs in this category yet.', 'discard-sealion' );
				}
				?>
			</p>
		</div>
	<?php endif; ?>
</div>

<?php
get_footer();
