<?php
/**
 * The template for displaying single posts (CD reviews)
 *
 * @package CD_Sealion
 */

get_header();
?>

<?php
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/content', 'single-cd' );

	// If comments are open or there are comments, load the comments template
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
endwhile;
?>

<?php
get_footer();
