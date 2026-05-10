<?php
/**
 * NO DISC Memorial Banner — settings + render.
 *
 * @package Discard_Sealion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Default banner option values.
 *
 * @return array
 */
function discard_sealion_no_disc_banner_defaults() {
	return array(
		'enabled' => false,
		'readout' => 'NO DISC',
		'caption' => 'The unit has stopped responding. Project DISCard continues in its honor.',
	);
}

/**
 * Get banner options merged with defaults.
 *
 * @return array
 */
function discard_sealion_no_disc_banner_get_options() {
	$defaults = discard_sealion_no_disc_banner_defaults();
	$stored   = get_option( 'discard_sealion_no_disc_banner', array() );
	if ( ! is_array( $stored ) ) {
		$stored = array();
	}
	return array_merge( $defaults, $stored );
}

/**
 * Register the banner setting.
 */
function discard_sealion_no_disc_banner_register_settings() {
	register_setting(
		'discard_sealion_no_disc_banner_group',
		'discard_sealion_no_disc_banner',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'discard_sealion_no_disc_banner_sanitize',
			'default'           => discard_sealion_no_disc_banner_defaults(),
		)
	);
}
add_action( 'admin_init', 'discard_sealion_no_disc_banner_register_settings' );

/**
 * Sanitize banner option input. Empty strings fall back to defaults so the LCD never renders blank.
 *
 * @param array $input Raw option input.
 * @return array
 */
function discard_sealion_no_disc_banner_sanitize( $input ) {
	$defaults = discard_sealion_no_disc_banner_defaults();
	if ( ! is_array( $input ) ) {
		$input = array();
	}

	$readout = isset( $input['readout'] ) ? sanitize_text_field( $input['readout'] ) : '';
	$caption = isset( $input['caption'] ) ? sanitize_text_field( $input['caption'] ) : '';

	return array(
		'enabled' => ! empty( $input['enabled'] ),
		'readout' => '' === $readout ? $defaults['readout'] : $readout,
		'caption' => '' === $caption ? $defaults['caption'] : $caption,
	);
}

/**
 * Add the admin options page under Appearance.
 */
function discard_sealion_no_disc_banner_add_options_page() {
	add_theme_page(
		'NO DISC Banner',
		'NO DISC Banner',
		'manage_options',
		'discard-sealion-no-disc-banner',
		'discard_sealion_no_disc_banner_render_options_page'
	);
}
add_action( 'admin_menu', 'discard_sealion_no_disc_banner_add_options_page' );

/**
 * Render the admin options page.
 */
function discard_sealion_no_disc_banner_render_options_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$options = discard_sealion_no_disc_banner_get_options();
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<p>The skeuomorphic CD-player memorial banner shown at the top of every page.</p>

		<form method="post" action="options.php">
			<?php settings_fields( 'discard_sealion_no_disc_banner_group' ); ?>

			<table class="form-table" role="presentation">
				<tr>
					<th scope="row">Display</th>
					<td>
						<label for="nodisc_enabled">
							<input type="checkbox"
									id="nodisc_enabled"
									name="discard_sealion_no_disc_banner[enabled]"
									value="1"
									<?php checked( ! empty( $options['enabled'] ) ); ?> />
							Show the banner on the front end
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="nodisc_readout">Readout text</label>
					</th>
					<td>
						<input type="text"
								id="nodisc_readout"
								name="discard_sealion_no_disc_banner[readout]"
								value="<?php echo esc_attr( $options['readout'] ); ?>"
								maxlength="20"
								class="regular-text" />
						<p class="description">The blinking centre text on the LCD. Short is best — long strings will wrap and break the layout.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="nodisc_caption">Caption</label>
					</th>
					<td>
						<textarea id="nodisc_caption"
								name="discard_sealion_no_disc_banner[caption]"
								rows="2"
								class="large-text"><?php echo esc_textarea( $options['caption'] ); ?></textarea>
						<p class="description">The small uppercase line beneath the LCD.</p>
					</td>
				</tr>
			</table>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/**
 * Render the banner on the front end. Hooked to wp_body_open so it sits above the site header.
 */
function discard_sealion_no_disc_banner_render() {
	if ( is_admin() || is_feed() ) {
		return;
	}

	$options = discard_sealion_no_disc_banner_get_options();
	if ( empty( $options['enabled'] ) ) {
		return;
	}
	?>
	<div class="nodisc-banner" role="region" aria-label="CD player memorial">
		<div class="nodisc-lcd">
			<div class="nodisc-meta">
				<div>DISC 01</div>
				<div>TR 00</div>
			</div>
			<div class="nodisc-readout">
				<span class="nodisc-blink"><?php echo esc_html( $options['readout'] ); ?></span>
			</div>
			<div class="nodisc-meta nodisc-meta--r">
				<div>00:00</div>
				<div>&mdash; REST IN &mdash;</div>
				<div>PIECES</div>
			</div>
		</div>
		<div class="nodisc-caption">
			<?php echo esc_html( $options['caption'] ); ?>
		</div>
	</div>
	<?php
}
add_action( 'wp_body_open', 'discard_sealion_no_disc_banner_render' );
