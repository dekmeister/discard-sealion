<?php
/**
 * The front page template file
 *
 * Displays the homepage with a grid of CD covers
 *
 * @package Discard_Sealion
 */

get_header();
?>

<div class="cd-grid-container">
	<?php
	// Query for published posts
	$args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => -1, // Show all posts
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	$cd_query = new WP_Query( $args );

	if ( $cd_query->have_posts() ) :
		?>
		<div class="cd-grid">
			<?php
			while ( $cd_query->have_posts() ) :
				$cd_query->the_post();
				get_template_part( 'template-parts/content', 'grid-item' );
			endwhile;
			?>
		</div>
		<?php
		wp_reset_postdata();
	else :
		get_template_part( 'template-parts/content', 'none' );
	endif;
	?>
</div>

<?php
get_footer();
