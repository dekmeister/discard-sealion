<?php
/**
 * The template for displaying pages
 *
 * @package Discard_Sealion
 */

get_header();
?>

<?php
while ( have_posts() ) :
	the_post();
	?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>

		<header class="page-header">
			<h1 class="page-title"><?php the_title(); ?></h1>
		</header>

		<div class="page-body">
			<?php
			if ( has_post_thumbnail() ) :
				?>
				<div class="page-featured-image">
					<?php the_post_thumbnail( 'large' ); ?>
				</div>
				<?php
			endif;
			?>

			<div class="page-content-text">
				<?php the_content(); ?>
			</div>
		</div>

	</article>

	<?php
endwhile;
?>

<?php
get_footer();
