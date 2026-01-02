<?php
/**
 * Template tags for CD Collection theme
 *
 * Reusable template functions for displaying CD information
 *
 * @package CD_Sealion
 */

/**
 * Get the artist name for the current post
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 * @return string Artist name or empty string
 */
function cd_sealion_get_artist( $post_id = 0 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	return get_the_excerpt( $post_id );
}

/**
 * Get the verdict for the current post
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 * @return string Verdict ('keep' or 'delete') or empty string
 */
function cd_sealion_get_verdict( $post_id = 0 ) {
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
 * Display formatted verdict HTML
 *
 * Outputs the verdict with proper styling and text
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 * @return string HTML for the verdict display
 */
function cd_sealion_verdict_display( $post_id = 0 ) {
	$verdict = cd_sealion_get_verdict( $post_id );

	if ( 'keep' === $verdict ) {
		return '<span class="verdict verdict-keep">Keep It</span>';
	} elseif ( 'delete' === $verdict ) {
		return '<span class="verdict verdict-delete">Delete It</span>';
	} else {
		// No verdict set - show pending
		return '<span class="verdict verdict-pending">Verdict Pending</span>';
	}
}

/**
 * Display the artist name
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 */
function cd_sealion_the_artist( $post_id = 0 ) {
	$artist = cd_sealion_get_artist( $post_id );

	if ( $artist ) {
		echo '<p class="cd-artist">' . esc_html( $artist ) . '</p>';
	}
}

/**
 * Display the verdict
 *
 * @param int $post_id Optional. Post ID. Defaults to current post.
 */
function cd_sealion_the_verdict( $post_id = 0 ) {
	$verdict_html = cd_sealion_verdict_display( $post_id );

	if ( $verdict_html ) {
		echo '<div class="cd-verdict">' . $verdict_html . '</div>';
	}
}
