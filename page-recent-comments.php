<?php
/**
 * Template Name: Recent Comments
 *
 * River feed of the most recent approved comments across all CD reviews.
 *
 * @package Discard_Sealion
 */

$rc_page = isset( $_GET['cpage'] ) ? absint( $_GET['cpage'] ) : 1; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only pagination
$rc_page = max( 1, $rc_page );

$rc_data      = discard_sealion_get_recent_comments_page( $rc_page );
$rc_rows      = $rc_data['rows'];
$rc_total     = $rc_data['total'];
$rc_total_30d = $rc_data['total_30d'];
$rc_posts_30d = $rc_data['posts_30d'];

// Build parent-post cache to avoid N+1 queries.
$rc_posts_cache = array();
foreach ( $rc_rows as $rc_row ) {
	$rc_row_post_id = (int) $rc_row->comment_post_ID;
	if ( ! isset( $rc_posts_cache[ $rc_row_post_id ] ) ) {
		$rc_posts_cache[ $rc_row_post_id ] = get_post( $rc_row_post_id );
	}
}

get_header();
?>

<div class="rc-page-wrap">

	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Recent Comments', 'discard-sealion' ); ?></h1>
		<?php if ( $rc_total_30d > 0 ) : ?>
			<p class="rc-subtitle">
				<?php
				echo esc_html(
					sprintf(
						/* translators: 1: comment count, 2: CD count */
						_n(
							'%1$d comment across %2$d CD in the last 30 days',
							'%1$d comments across %2$d CDs in the last 30 days',
							$rc_total_30d,
							'discard-sealion'
						),
						$rc_total_30d,
						$rc_posts_30d
					)
				);
				?>
			</p>
		<?php endif; ?>

		<div class="rc-chips" role="group" aria-label="<?php esc_attr_e( 'Filter comments', 'discard-sealion' ); ?>">
			<button class="rc-chip is-active" data-rc-filter="all" aria-pressed="true">All</button>
			<button class="rc-chip" data-rc-filter="unread" aria-pressed="false">Unread</button>
		</div>
	</header>

	<?php if ( empty( $rc_rows ) ) : ?>

		<?php get_template_part( 'template-parts/content', 'none-comments' ); ?>

	<?php else : ?>

		<div class="rc-list">
			<?php foreach ( $rc_rows as $rc_item ) : ?>
				<?php
				$rc_item_post_id = (int) $rc_item->comment_post_ID;
				$rc_item_post    = isset( $rc_posts_cache[ $rc_item_post_id ] ) ? $rc_posts_cache[ $rc_item_post_id ] : null;
				if ( ! $rc_item_post ) {
					continue;
				}
				get_template_part(
					'template-parts/content',
					'recent-comment',
					array(
						'comment'     => $rc_item,
						'parent_post' => $rc_item_post,
					)
				);
				?>
			<?php endforeach; ?>
		</div>

		<?php if ( $rc_page * 30 < $rc_total ) : ?>
			<div class="rc-pagination">
				<a class="button-pill" href="<?php echo esc_url( add_query_arg( 'cpage', $rc_page + 1 ) ); ?>">Show 30 more</a>
			</div>
		<?php endif; ?>

	<?php endif; ?>

</div>

<?php
get_footer();
