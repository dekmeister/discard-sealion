<?php
/**
 * Template part for displaying a message when no posts are found
 *
 * @package Discard_Sealion
 */

?>

<div class="no-posts">
	<h2 class="no-posts-title"><?php esc_html_e( 'No CDs Yet', 'discard-sealion' ); ?></h2>
	<p class="no-posts-text">
		<?php
		if ( current_user_can( 'publish_posts' ) ) {
			esc_html_e( 'Ready to add your first CD review? Speak to Declan.', 'discard-sealion' );
		} else {
			esc_html_e( 'No CD reviews have been added yet. Check back soon!', 'discard-sealion' );
		}
		?>
	</p>
</div>
