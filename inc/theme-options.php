<?php
/**
 * Theme Options - Related Sites
 *
 * @package Discard_Sealion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add theme options page under Appearance menu
 */
function discard_sealion_add_options_page() {
	add_theme_page(
		'Related Sites',
		'Related Sites',
		'manage_options',
		'discard-sealion-related-sites',
		'discard_sealion_render_options_page'
	);
}
add_action( 'admin_menu', 'discard_sealion_add_options_page' );

/**
 * Register settings
 */
function discard_sealion_register_settings() {
	register_setting(
		'discard_sealion_related_sites_group',
		'discard_sealion_related_sites',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'discard_sealion_sanitize_related_sites',
			'default'           => array(
				'section_title' => 'Related Sites',
				'max_display'   => 5,
				'sites'         => array(),
			),
		)
	);
}
add_action( 'admin_init', 'discard_sealion_register_settings' );

/**
 * Sanitize settings
 */
function discard_sealion_sanitize_related_sites( $input ) {
	$sanitized = array(
		'section_title' => sanitize_text_field( $input['section_title'] ?? 'Related Sites' ),
		'max_display'   => absint( $input['max_display'] ?? 5 ),
		'sites'         => array(),
	);

	// Clamp max_display between 1 and 20
	$sanitized['max_display'] = max( 1, min( 20, $sanitized['max_display'] ) );

	// Sanitize sites array
	if ( ! empty( $input['sites'] ) && is_array( $input['sites'] ) ) {
		foreach ( $input['sites'] as $site ) {
			$name = sanitize_text_field( $site['name'] ?? '' );
			$url  = esc_url_raw( $site['url'] ?? '' );

			// Only add if both name and URL are provided
			if ( ! empty( $name ) && ! empty( $url ) ) {
				$sanitized['sites'][] = array(
					'name' => $name,
					'url'  => $url,
				);
			}
		}
	}

	return $sanitized;
}

/**
 * Render the options page
 */
function discard_sealion_render_options_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$options = get_option( 'discard_sealion_related_sites', array(
		'section_title' => 'Related Sites',
		'max_display'   => 5,
		'sites'         => array(),
	) );
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<p>Configure the related sites section that appears in the footer.</p>

		<form method="post" action="options.php">
			<?php settings_fields( 'discard_sealion_related_sites_group' ); ?>

			<table class="form-table" role="presentation">
				<tr>
					<th scope="row">
						<label for="section_title">Section Title</label>
					</th>
					<td>
						<input type="text"
						       id="section_title"
						       name="discard_sealion_related_sites[section_title]"
						       value="<?php echo esc_attr( $options['section_title'] ); ?>"
						       class="regular-text" />
						<p class="description">The heading displayed above the links.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="max_display">Maximum Sites to Display</label>
					</th>
					<td>
						<input type="number"
						       id="max_display"
						       name="discard_sealion_related_sites[max_display]"
						       value="<?php echo esc_attr( $options['max_display'] ); ?>"
						       min="1"
						       max="20"
						       class="small-text" />
						<p class="description">Limit how many sites appear in the footer (1-20).</p>
					</td>
				</tr>
			</table>

			<h2>Sites</h2>
			<p>Add links to related sites. Sites without both a name and URL will not be saved.</p>

			<table class="widefat" id="related-sites-table">
				<thead>
					<tr>
						<th>Site Name</th>
						<th>URL</th>
						<th style="width: 80px;">Action</th>
					</tr>
				</thead>
				<tbody id="related-sites-list">
					<?php
					if ( ! empty( $options['sites'] ) ) :
						foreach ( $options['sites'] as $index => $site ) :
							?>
							<tr class="site-row">
								<td>
									<input type="text"
									       name="discard_sealion_related_sites[sites][<?php echo esc_attr( $index ); ?>][name]"
									       value="<?php echo esc_attr( $site['name'] ); ?>"
									       class="regular-text site-name" />
								</td>
								<td>
									<input type="url"
									       name="discard_sealion_related_sites[sites][<?php echo esc_attr( $index ); ?>][url]"
									       value="<?php echo esc_url( $site['url'] ); ?>"
									       class="regular-text site-url" />
								</td>
								<td>
									<button type="button" class="button remove-site">Remove</button>
								</td>
							</tr>
							<?php
						endforeach;
					endif;
					?>
				</tbody>
			</table>

			<p style="margin-top: 10px;">
				<button type="button" class="button" id="add-site">Add Site</button>
			</p>

			<?php submit_button(); ?>
		</form>
	</div>

	<script>
	(function() {
		var siteIndex = <?php echo count( $options['sites'] ); ?>;
		var tableBody = document.getElementById('related-sites-list');
		var addButton = document.getElementById('add-site');

		addButton.addEventListener('click', function() {
			var row = document.createElement('tr');
			row.className = 'site-row';
			row.innerHTML = '<td>' +
				'<input type="text" name="discard_sealion_related_sites[sites][' + siteIndex + '][name]" class="regular-text site-name" />' +
				'</td><td>' +
				'<input type="url" name="discard_sealion_related_sites[sites][' + siteIndex + '][url]" class="regular-text site-url" placeholder="https://" />' +
				'</td><td>' +
				'<button type="button" class="button remove-site">Remove</button>' +
				'</td>';
			tableBody.appendChild(row);
			siteIndex++;
		});

		tableBody.addEventListener('click', function(e) {
			if (e.target.classList.contains('remove-site')) {
				e.target.closest('tr').remove();
			}
		});
	})();
	</script>
	<?php
}
