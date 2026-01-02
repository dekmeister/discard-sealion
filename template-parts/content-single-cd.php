<?php
/**
 * Template part for displaying single CD review
 *
 * @package CD_Collection
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'cd-single' ); ?>>

	<div class="cd-single-content">

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="cd-single-image">
				<?php the_post_thumbnail( 'large', array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
			</div>
		<?php endif; ?>

		<div class="cd-single-details">

			<header class="cd-single-header">
				<h1 class="cd-single-title"><?php the_title(); ?></h1>

				<?php
				$artist = cd_collection_get_artist();
				if ( $artist ) :
					?>
					<p class="cd-single-artist"><?php echo esc_html( $artist ); ?></p>
					<?php
				endif;
				?>

				<?php
				$verdict_html = cd_collection_verdict_display();
				if ( $verdict_html ) :
					?>
					<div class="cd-single-verdict">
						<?php echo $verdict_html; ?>
					</div>
					<?php
				endif;
				?>
			</header>

			<?php
			$content = trim( get_the_content() );
			if ( ! empty( $content ) ) :
				?>
				<div class="cd-single-thoughts">
					<h2 class="cd-thoughts-heading">Thoughts</h2>
					<div class="cd-thoughts-content">
						<?php the_content(); ?>
					</div>
				</div>
				<?php
			endif;
			?>

			<footer class="cd-single-footer">
				<p class="cd-single-date">
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
						Reviewed on <?php echo esc_html( get_the_date() ); ?>
					</time>
				</p>
			</footer>

		</div>

	</div>

</article>
