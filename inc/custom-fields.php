<?php
/**
 * Custom field registration for CD Collection theme
 *
 * Registers custom post meta fields for CD reviews:
 * - cd_artist: Artist/band name
 * - cd_verdict: Keep or Delete verdict
 *
 * @package CD_Sealion
 */

/**
 * Register custom post meta fields
 */
function cd_sealion_register_post_meta() {
	// Register artist field
	register_post_meta(
		'post',
		'cd_artist',
		array(
			'type'              => 'string',
			'description'       => 'Artist or band name for the CD',
			'single'            => true,
			'sanitize_callback' => 'sanitize_text_field',
			'show_in_rest'      => true,
		)
	);

	// Register verdict field
	register_post_meta(
		'post',
		'cd_verdict',
		array(
			'type'              => 'string',
			'description'       => 'Keep or Delete verdict for the CD',
			'single'            => true,
			'sanitize_callback' => 'cd_sealion_sanitize_verdict',
			'show_in_rest'      => true,
		)
	);
}
add_action( 'init', 'cd_sealion_register_post_meta' );

/**
 * Sanitize verdict field value
 *
 * Ensures only valid values (keep/delete) are stored
 *
 * @param string $value The input value to sanitize
 * @return string Sanitized value (keep/delete) or empty string
 */
function cd_sealion_sanitize_verdict( $value ) {
	$value = sanitize_key( $value );

	// Whitelist check - only allow 'keep' or 'delete'
	if ( in_array( $value, array( 'keep', 'delete' ), true ) ) {
		return $value;
	}

	return '';
}
