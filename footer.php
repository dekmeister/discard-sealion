</main><!-- .site-main -->

<footer class="site-footer">
	<div class="footer-container">
		<?php
		$keep_category = get_category_by_slug( 'keep' );
		$delete_category = get_category_by_slug( 'delete' );
		$kept_count = $keep_category ? $keep_category->count : 0;
		$deleted_count = $delete_category ? $delete_category->count : 0;
		$keep_url = $keep_category ? get_category_link( $keep_category->term_id ) : '';
		$delete_url = $delete_category ? get_category_link( $delete_category->term_id ) : '';
		?>
		<p class="footer-stats">
			<?php if ( $keep_url ) : ?>
				<a href="<?php echo esc_url( $keep_url ); ?>"><?php echo esc_html( $kept_count ); ?> Kept</a>
			<?php else : ?>
				<?php echo esc_html( $kept_count ); ?> Kept
			<?php endif; ?>
			and
			<?php if ( $delete_url ) : ?>
				<a href="<?php echo esc_url( $delete_url ); ?>"><?php echo esc_html( $deleted_count ); ?> Deleted</a>
			<?php else : ?>
				<?php echo esc_html( $deleted_count ); ?> Deleted
			<?php endif; ?>
		</p>
		<p class="footer-text">
			&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?>
			<?php bloginfo( 'name' ); ?>
		</p>
	</div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
