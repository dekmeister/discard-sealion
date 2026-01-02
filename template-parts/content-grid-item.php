<?php
/**
 * Template part for displaying a CD grid item
 *
 * Displays the featured image as a clickable grid item
 *
 * @package CD_Collection
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'cd-grid-item' ); ?>>
	<a href="<?php the_permalink(); ?>" class="cd-grid-item-link" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
		<div class="cd-cover">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'large', array(
					'class' => 'cd-cover-image',
					'alt'   => esc_attr( get_the_title() ),
				) );
			} else {
				// Placeholder for posts without featured images
				?>
				<div class="cd-cover-placeholder">
					<span class="cd-cover-placeholder-text" aria-hidden="true">?</span>
				</div>
				<?php
			}
			?>
		</div>
		<div class="cd-grid-item-overlay">
			<h2 class="cd-grid-item-title"><?php the_title(); ?></h2>
		</div>
	</a>
</article>
