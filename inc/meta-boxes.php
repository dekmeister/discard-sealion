<?php
/**
 * Meta box UI for CD Collection custom fields
 *
 * Provides admin interface for editing CD artist and verdict
 *
 * @package CD_Collection
 */

/**
 * Add meta box to post editor
 */
function cd_collection_add_meta_boxes() {
	add_meta_box(
		'cd_collection_details',
		'CD Details',
		'cd_collection_meta_box_callback',
		'post',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'cd_collection_add_meta_boxes' );

/**
 * Render meta box content
 *
 * @param WP_Post $post Current post object
 */
function cd_collection_meta_box_callback( $post ) {
	// Add nonce for security
	wp_nonce_field( 'cd_collection_save_meta', 'cd_collection_meta_nonce' );

	// Get current values
	$artist  = get_post_meta( $post->ID, 'cd_artist', true );
	$verdict = get_post_meta( $post->ID, 'cd_verdict', true );

	?>
	<p>
		<label for="cd_artist">
			<strong>Artist/Band:</strong>
		</label>
		<input
			type="text"
			id="cd_artist"
			name="cd_artist"
			value="<?php echo esc_attr( $artist ); ?>"
			class="widefat"
			placeholder="Enter artist or band name"
		/>
	</p>

	<p>
		<label for="cd_verdict">
			<strong>Verdict:</strong>
		</label>
		<select id="cd_verdict" name="cd_verdict" class="widefat">
			<option value="">-- Select Verdict --</option>
			<option value="keep" <?php selected( $verdict, 'keep' ); ?>>Keep It</option>
			<option value="delete" <?php selected( $verdict, 'delete' ); ?>>Delete It</option>
		</select>
	</p>
	<?php
}

/**
 * Save meta box data
 *
 * @param int $post_id Post ID
 */
function cd_collection_save_meta_box( $post_id ) {
	// Verify nonce
	if ( ! isset( $_POST['cd_collection_meta_nonce'] ) ||
	     ! wp_verify_nonce( $_POST['cd_collection_meta_nonce'], 'cd_collection_save_meta' ) ) {
		return;
	}

	// Check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check user permissions
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Save artist field
	if ( isset( $_POST['cd_artist'] ) ) {
		$artist = sanitize_text_field( $_POST['cd_artist'] );
		update_post_meta( $post_id, 'cd_artist', $artist );
	} else {
		delete_post_meta( $post_id, 'cd_artist' );
	}

	// Save verdict field
	if ( isset( $_POST['cd_verdict'] ) ) {
		$verdict = sanitize_key( $_POST['cd_verdict'] );

		// Whitelist check - only save if valid
		if ( in_array( $verdict, array( 'keep', 'delete' ), true ) ) {
			update_post_meta( $post_id, 'cd_verdict', $verdict );
		} else {
			// Invalid value - clear the field
			delete_post_meta( $post_id, 'cd_verdict' );
		}
	} else {
		delete_post_meta( $post_id, 'cd_verdict' );
	}
}
add_action( 'save_post', 'cd_collection_save_meta_box' );
