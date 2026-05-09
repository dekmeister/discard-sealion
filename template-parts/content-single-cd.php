<?php
/**
 * Template part for displaying single CD review
 *
 * @package Discard_Sealion
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'cd-single' ); ?>>

	<div class="cd-single-content">

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="cd-single-image">
				<?php
				the_post_thumbnail(
					'large',
					array(
						'alt'           => esc_attr( get_the_title() ),
						'loading'       => 'eager',
						'fetchpriority' => 'high',
						'decoding'      => 'async',
					)
				);
				?>
			</div>
		<?php endif; ?>

		<div class="cd-single-details">

			<header class="cd-single-header">
				<h1 class="cd-single-title"><?php the_title(); ?></h1>

				<?php
				$artist = discard_sealion_get_artist();
				if ( $artist ) :
					?>
					<p class="cd-single-artist"><?php echo esc_html( $artist ); ?></p>
					<?php
				endif;
				?>

				<?php $verdict = discard_sealion_verdict_display(); ?>
				<div class="cd-single-verdict">
					<span class="verdict verdict-<?php echo esc_attr( $verdict['slug'] ); ?>"><?php echo esc_html( $verdict['label'] ); ?></span>
				</div>
			</header>

			<?php
			$content = trim( get_the_content() );
			if ( ! empty( $content ) ) :
				?>
				<div class="cd-single-thoughts">
					<h2 class="cd-thoughts-heading">Thoughts</h2>
					<div class="cd-thoughts-content">
						<?php the_content(); ?>
						<?php
						wp_link_pages(
							array(
								'before' => '<p class="post-pagination">Pages:',
								'after'  => '</p>',
							)
						);
						?>
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
