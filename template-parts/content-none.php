<?php
/**
 * Template part for displaying a message when no posts are found
 *
 * @package CD_Sealion
 */

?>

<div class="no-posts">
	<h2 class="no-posts-title"><?php esc_html_e( 'No CDs Yet', 'cd-sealion' ); ?></h2>
	<p class="no-posts-text">
		<?php
		if ( current_user_can( 'publish_posts' ) ) {
			esc_html_e( 'Ready to add your first CD review? Speak to Declan.', 'cd-sealion' );
		} else {
			esc_html_e( 'No CD reviews have been added yet. Check back soon!', 'cd-sealion' );
		}
		?>
	</p>
</div>
