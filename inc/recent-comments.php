<?php
/**
 * Data fetch helper for the Recent Comments page template
 *
 * @package Discard_Sealion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fetch one page of approved comments plus counts for the river-feed header.
 *
 * @param int $page 1-based page number.
 * @return array{rows: WP_Comment[], total: int, total_30d: int, posts_30d: int}
 */
function discard_sealion_get_recent_comments_page( $page ) {
	$per_page = 30;

	$rows = get_comments(
		array(
			'status'      => 'approve',
			'type'        => 'comment',
			'number'      => $per_page,
			'offset'      => ( $page - 1 ) * $per_page,
			'orderby'     => 'comment_date_gmt',
			'order'       => 'DESC',
			'post_status' => 'publish',
		)
	);

	$total = get_comments(
		array(
			'count'       => true,
			'status'      => 'approve',
			'type'        => 'comment',
			'post_status' => 'publish',
		)
	);

	$date_query = array( array( 'after' => '30 days ago' ) );

	$total_30d = get_comments(
		array(
			'count'       => true,
			'status'      => 'approve',
			'type'        => 'comment',
			'date_query'  => $date_query,
			'post_status' => 'publish',
		)
	);

	global $wpdb;
	$since     = gmdate( 'Y-m-d H:i:s', strtotime( '-30 days' ) );
	$posts_30d = (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(DISTINCT c.comment_post_ID)
			 FROM {$wpdb->comments} c
			 INNER JOIN {$wpdb->posts} p ON p.ID = c.comment_post_ID
			 WHERE c.comment_approved = '1'
			   AND c.comment_type = 'comment'
			   AND c.comment_date_gmt >= %s
			   AND p.post_status = 'publish'",
			$since
		)
	);

	return array(
		'rows'      => $rows,
		'total'     => (int) $total,
		'total_30d' => (int) $total_30d,
		'posts_30d' => $posts_30d,
	);
}
