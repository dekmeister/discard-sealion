<?php
/**
 * Template part: single comment row for the Recent Comments river feed
 *
 * @package Discard_Sealion
 *
 * @var WP_Comment $args['comment']     The comment object.
 * @var WP_Post    $args['parent_post'] The post the comment belongs to.
 */

$rc_comment     = $args['comment'];
$rc_parent_post = $args['parent_post'];
?>

<article class="rc-row" aria-labelledby="rc-meta-<?php echo (int) $rc_comment->comment_ID; ?>">

	<a class="rc-thumb" href="<?php echo esc_url( get_permalink( $rc_parent_post ) ); ?>" aria-hidden="true" tabindex="-1">
		<?php
		echo get_the_post_thumbnail(
			$rc_parent_post,
			'cd-comment',
			array(
				'loading' => 'lazy',
				'alt'     => '',
			)
		);
		?>
	</a>

	<div class="rc-body">
		<p class="rc-meta" id="rc-meta-<?php echo (int) $rc_comment->comment_ID; ?>">
			<?php echo get_avatar( $rc_comment, 22 ); ?>
			<strong class="rc-author"><?php echo esc_html( $rc_comment->comment_author ); ?></strong>
			<span class="rc-sep" aria-hidden="true">&middot;</span>
			<span class="rc-verb"><?php echo $rc_comment->comment_parent ? 'replied on' : 'commented on'; ?></span>
			<a class="rc-cd-link" href="<?php echo esc_url( get_permalink( $rc_parent_post ) ); ?>">
				<span class="rc-cd-title">&ldquo;<?php echo esc_html( get_the_title( $rc_parent_post ) ); ?>&rdquo;</span>
				<?php
				$rc_artist = discard_sealion_get_artist( $rc_parent_post->ID );
				if ( $rc_artist ) {
					echo ' &mdash; <span class="rc-cd-artist">' . esc_html( $rc_artist ) . '</span>';
				}
				?>
			</a>
			<?php
			if ( $rc_comment->comment_parent ) {
				$rc_parent_comment = get_comment( $rc_comment->comment_parent );
				if ( $rc_parent_comment ) {
					echo ' <span class="rc-reply-badge">&#8618; in reply to ' . esc_html( $rc_parent_comment->comment_author ) . '</span>';
				}
			}
			?>
		</p>

		<div class="rc-text"><?php echo wp_kses_post( get_comment_text( $rc_comment ) ); ?></div>
	</div>

	<div class="rc-actions">
		<time class="rc-time"
			datetime="<?php echo esc_attr( get_comment_date( 'c', $rc_comment ) ); ?>"
			data-rc-gmt="<?php echo esc_attr( get_comment_date( 'U', $rc_comment ) ); ?>">
			<?php echo esc_html( get_comment_date( 'M j', $rc_comment ) ); ?>
			<span class="rc-sep" aria-hidden="true">&middot;</span>
			<?php echo esc_html( get_comment_date( 'g:i a', $rc_comment ) ); ?>
		</time>
		<a class="rc-reply-link" href="<?php
			echo esc_url(
				add_query_arg(
					'replytocom',
					$rc_comment->comment_ID,
					get_permalink( $rc_parent_post )
				) . '#respond'
			);
		?>">Reply on CD page &rarr;</a>
	</div>

</article>
