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

	$recent_objs = get_comments(
		array(
			'status'      => 'approve',
			'type'        => 'comment',
			'date_query'  => $date_query,
			'post_status' => 'publish',
			'number'      => 0,
		)
	);

	$posts_30d = count( array_unique( wp_list_pluck( $recent_objs, 'comment_post_ID' ) ) );

	return array(
		'rows'      => $rows,
		'total'     => (int) $total,
		'total_30d' => (int) $total_30d,
		'posts_30d' => $posts_30d,
	);
}
