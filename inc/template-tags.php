<?php
/**
 * Template tags for Discard Sealion theme
 *
 * Reusable template functions for displaying CD information
 *
 * @package Discard_Sealion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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

/**
 * Close a comment <li>, first explaining why there is no Reply link when the
 * comment sits at the maximum nesting depth.
 *
 * Used as the `end-callback` for wp_list_comments(). Core returns before
 * running the `comment_reply_link` filter at max depth, so the notice has to
 * be output here instead. A comment at max depth has no children, so the
 * notice lands directly beneath the comment.
 *
 * @param WP_Comment $comment Comment being closed.
 * @param array      $args    wp_list_comments() arguments.
 * @param int        $depth   Zero-based depth of the comment.
 */
function discard_sealion_comment_end( $comment, $args, $depth ) {
	$max_depth = isset( $args['max_depth'] ) ? (int) $args['max_depth'] : 0;
	$is_plain  = in_array( $comment->comment_type, array( '', 'comment' ), true );

	if (
		$is_plain
		&& get_option( 'thread_comments' )
		&& $max_depth > 0
		&& ( (int) $depth + 1 ) >= $max_depth
		&& '1' === (string) $comment->comment_approved
		&& comments_open( $comment->comment_post_ID )
	) {
		?>
		<div class="reply reply-limit"><span class="reply-limit-note"><?php esc_html_e( 'Reply limit reached for this thread', 'discard-sealion' ); ?></span></div>
		<?php
	}

	echo "</li>\n";
}
