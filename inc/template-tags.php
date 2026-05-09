<?php
/**
 * Template tags for Discard Sealion theme
 *
 * Reusable template functions for displaying CD information
 *
 * @package Discard_Sealion
 */

/**
 * Get the artist name for the current post
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 * @return string Artist name or empty string
 */
function discard_sealion_get_artist( $post_id = 0 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	return get_post_field( 'post_excerpt', $post_id, 'raw' );
}

/**
 * Get the verdict for the current post
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 * @return string Verdict ('keep' or 'delete') or empty string
 */
function discard_sealion_get_verdict( $post_id = 0 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	if ( has_category( 'keep', $post_id ) ) {
		return 'keep';
	}
	if ( has_category( 'delete', $post_id ) ) {
		return 'delete';
	}
	return '';
}

/**
 * Get the verdict slug and human-readable label for display.
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 * @return array{slug:string,label:string} Slug ('keep'|'delete'|'pending') and label.
 */
function discard_sealion_verdict_display( $post_id = 0 ) {
	$verdict = discard_sealion_get_verdict( $post_id );

	if ( 'keep' === $verdict ) {
		return array(
			'slug'  => 'keep',
			'label' => 'Kept',
		);
	} elseif ( 'delete' === $verdict ) {
		return array(
			'slug'  => 'delete',
			'label' => 'Deleted',
		);
	}
	return array(
		'slug'  => 'pending',
		'label' => 'Verdict Pending',
	);
}

/**
 * Display the artist name
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 */
function discard_sealion_the_artist( $post_id = 0 ) {
	$artist = discard_sealion_get_artist( $post_id );

	if ( $artist ) {
		echo '<p class="cd-artist">' . esc_html( $artist ) . '</p>';
	}
}

/**
 * Display the verdict
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 */
function discard_sealion_the_verdict( $post_id = 0 ) {
	$verdict = discard_sealion_verdict_display( $post_id );
	?>
	<div class="cd-verdict">
		<span class="verdict verdict-<?php echo esc_attr( $verdict['slug'] ); ?>"><?php echo esc_html( $verdict['label'] ); ?></span>
	</div>
	<?php
}
