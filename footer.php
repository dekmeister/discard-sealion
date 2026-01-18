</main><!-- .site-main -->

<footer class="site-footer">
	<div class="footer-container">
		<div class="footer-main">
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
		<?php
		$related_sites_options = get_option( 'discard_sealion_related_sites' );
		if ( ! empty( $related_sites_options['sites'] ) ) :
			$related_title = $related_sites_options['section_title'] ?? 'Related Sites';
			$related_max   = $related_sites_options['max_display'] ?? 5;
			$related_sites = array_slice( $related_sites_options['sites'], 0, $related_max );
		?>
		<div class="footer-related-sites">
			<h3><?php echo esc_html( $related_title ); ?></h3>
			<ul>
				<?php foreach ( $related_sites as $related_site ) : ?>
					<li>
						<a href="<?php echo esc_url( $related_site['url'] ); ?>" target="_blank" rel="noopener">
							<?php echo esc_html( $related_site['name'] ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>
	</div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
