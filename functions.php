<?php
/**
 * Discard Sealion Theme Functions
 *
 * @package Discard_Sealion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Theme setup
 */
function discard_sealion_setup() {
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts.
	add_theme_support( 'post-thumbnails' );

	/*
	 * Sizes matching where the theme actually paints images.
	 *
	 * Without these the srcset ladder has no rung near the displayed size, so
	 * the browser is forced up to the next one it has: WordPress's `medium` is
	 * bounded on both axes, which makes it only 225px wide for a portrait
	 * cover, below every grid cell, so `large` (768px) was being fetched for a
	 * cell at most 348px wide. A correct srcset cannot help if the ladder has
	 * no candidate at the right size.
	 *
	 * Hard crop throughout: .cd-cover-image and .rc-thumb img both use
	 * object-fit: cover inside a square, so the pixels outside the square are
	 * discarded at paint time anyway. Cropping at generation stores only what
	 * is painted, which saves again on top of the downscale.
	 */

	// The 1:1 grid cell (style.css:355-390). Widest cell across all
	// breakpoints is 348px (2 columns at 767px), so 360 covers every case.
	add_image_size( 'cd-cover', 360, 360, true );
	add_image_size( 'cd-cover-2x', 720, 720, true );

	// The 72px .rc-thumb img on the Recent Comments feed (style.css:823-829).
	add_image_size( 'cd-comment', 72, 72, true );
	add_image_size( 'cd-comment-2x', 144, 144, true );

	// Enable support for custom logo.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 100,
			'width'       => 100,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Enable HTML5 markup support.
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Enable excerpt support for posts.
	add_post_type_support( 'post', 'excerpt' );
}
add_action( 'after_setup_theme', 'discard_sealion_setup' );

/**
 * Declare the real grid cell width for the square cover sizes.
 *
 * WordPress defaults `sizes` to the candidate's own width, which tells the
 * browser the image fills that many pixels and invites it up a rung. The grid
 * cell is far smaller, and it changes at every breakpoint (style.css:1072-1137,
 * 5/4/3/2 columns inside a 1600px container with 24px gaps and 24px padding).
 *
 * Scoped by matching the candidate's dimensions against the registered cover
 * sizes, because this filter receives a [width, height] array rather than the
 * size name. The single-CD and page templates ask for `large` and legitimately
 * want a large image, so they must not be caught by this.
 *
 * WordPress >= 6.7 prepends `auto` for lazy images, which reports the real
 * laid-out width and beats any guess made here; the list below is what
 * everything else falls back to.
 *
 * @param string       $sizes A source size value for use in a `sizes` attribute.
 * @param int[]|string $size  Requested image size, as [width, height] in pixels.
 * @return string
 */
function discard_sealion_cover_image_sizes( $sizes, $size ) {
	if ( ! is_array( $size ) || ! isset( $size[0], $size[1] ) ) {
		return $sizes;
	}

	$registered = wp_get_registered_image_subsizes();

	foreach ( array( 'cd-cover', 'cd-cover-2x' ) as $name ) {
		if ( ! isset( $registered[ $name ] ) ) {
			continue;
		}

		if ( (int) $size[0] === (int) $registered[ $name ]['width']
			&& (int) $size[1] === (int) $registered[ $name ]['height'] ) {
			/*
			 * The trailing fixed value, not a vw unit: above 1600px the
			 * container stops growing, so (1552 - 4*24) / 5 = 291px however
			 * wide the viewport gets.
			 */
			return '(max-width: 767px) 50vw, (max-width: 999px) 33vw, (max-width: 1400px) 25vw, (max-width: 1599px) 20vw, 291px';
		}
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'discard_sealion_cover_image_sizes', 10, 2 );

/**
 * Enqueue scripts and styles
 */
function discard_sealion_scripts() {
	// Enqueue theme stylesheet.
	wp_enqueue_style(
		'discard-sealion-style',
		get_stylesheet_uri(),
		array(),
		(string) filemtime( get_stylesheet_directory() . '/style.css' )
	);

	wp_enqueue_script(
		'discard-sealion-nav',
		get_template_directory_uri() . '/assets/js/nav.js',
		array(),
		(string) filemtime( get_template_directory() . '/assets/js/nav.js' ),
		true
	);

	if ( is_page_template( 'page-recent-comments.php' ) ) {
		wp_enqueue_script(
			'discard-sealion-recent-comments',
			get_template_directory_uri() . '/assets/js/recent-comments.js',
			array(),
			(string) filemtime( get_template_directory() . '/assets/js/recent-comments.js' ),
			true
		);
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'discard_sealion_scripts' );

/**
 * Create verdict categories on theme activation
 */
function discard_sealion_create_verdict_categories() {
	// Create "Keep" category.
	if ( ! term_exists( 'Keep', 'category' ) ) {
		wp_insert_term(
			'Keep',
			'category',
			array(
				'slug'        => 'keep',
				'description' => 'CDs to keep in the collection',
			)
		);
	}

	// Create "Delete" category.
	if ( ! term_exists( 'Delete', 'category' ) ) {
		wp_insert_term(
			'Delete',
			'category',
			array(
				'slug'        => 'delete',
				'description' => 'CDs to discard from the collection',
			)
		);
	}
}
add_action( 'after_switch_theme', 'discard_sealion_create_verdict_categories' );

/**
 * Customize category title display
 *
 * @param string $title Raw category title.
 * @return string
 */
function discard_sealion_custom_category_title( $title ) {
	if ( 'Keep' === $title ) {
		return 'Kept';
	}
	if ( 'Delete' === $title ) {
		return 'Deleted';
	}
	return $title;
}
add_filter( 'single_cat_title', 'discard_sealion_custom_category_title' );

/**
 * Show all posts on Keep/Delete category archives instead of paginating.
 *
 * @param WP_Query $query Current query object.
 */
function discard_sealion_show_all_category_posts( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_category( array( 'keep', 'delete' ) ) ) {
		$query->set( 'posts_per_page', -1 );
	}
}
add_action( 'pre_get_posts', 'discard_sealion_show_all_category_posts' );

/**
 * Add featured image and verdict to RSS content:encoded
 *
 * @param string $content Existing feed content.
 * @return string
 */
function discard_sealion_add_featured_image_to_feed( $content ) {
	if ( ! is_feed() ) {
		return $content;
	}

	global $post;
	$output = '';

	$verdict = discard_sealion_get_verdict( $post->ID );
	if ( 'keep' === $verdict ) {
		$output .= '<p><strong>Verdict: Kept</strong></p>';
	} elseif ( 'delete' === $verdict ) {
		$output .= '<p><strong>Verdict: Deleted</strong></p>';
	}

	if ( has_post_thumbnail( $post->ID ) ) {
		$image_url = get_the_post_thumbnail_url( $post->ID, 'large' );
		if ( $image_url ) {
			$image_id = get_post_thumbnail_id( $post->ID );
			$alt_text = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
			$output  .= sprintf(
				'<img src="%s" alt="%s" />',
				esc_url( $image_url ),
				esc_attr( $alt_text )
			);
		}
	}

	$output .= $content;

	return discard_sealion_rss_sanitize( $output );
}
add_filter( 'the_content_feed', 'discard_sealion_add_featured_image_to_feed' );

/**
 * Sanitise feed HTML: replace iframes with a link to their src, strip
 * srcset/sizes from images, and drop aspect-ratio from inline styles.
 *
 * @param string $html Raw HTML to sanitise.
 * @return string
 */
function discard_sealion_rss_sanitize( $html ) {
	$iframe_to_link = static function ( $m ) {
		if ( preg_match( '#\bsrc\s*=\s*["\']([^"\']+)["\']#i', $m[0], $sm ) ) {
			$url = esc_url( $sm[1] );
			if ( $url ) {
				return '<p><a href="' . $url . '">' . esc_html( $url ) . '</a></p>';
			}
		}
		return '';
	};

	$html = preg_replace_callback( '#<iframe\b[^>]*>.*?</iframe>#is', $iframe_to_link, $html );
	$html = preg_replace_callback( '#<iframe\b[^>]*/?>#i', $iframe_to_link, $html );

	$html = preg_replace( '/\s+(srcset|sizes)="[^"]*"/i', '', $html );

	$html = preg_replace( '/\s*aspect-ratio\s*:\s*[^;"\']+;?/i', '', $html );
	$html = preg_replace( '/\s+style=(["\'])\s*\1/', '', $html );

	return $html;
}

/**
 * RSS item title: "Album - Artist"
 *
 * @param string $title Original RSS title.
 * @return string
 */
function discard_sealion_rss_title( $title ) {
	$artist = discard_sealion_get_artist();
	if ( $artist ) {
		return $title . ' - ' . $artist;
	}
	return $title;
}
add_filter( 'the_title_rss', 'discard_sealion_rss_title' );

/**
 * RSS description: lightweight "Album - Artist - Kept/Deleted"
 *
 * @param string $_excerpt Original RSS excerpt (ignored; replaced entirely).
 * @return string
 */
function discard_sealion_rss_description( $_excerpt ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found -- filter replaces excerpt entirely.
	$album   = get_the_title();
	$artist  = discard_sealion_get_artist();
	$verdict = discard_sealion_get_verdict();
	if ( 'keep' === $verdict ) {
		$label = 'Kept';
	} elseif ( 'delete' === $verdict ) {
		$label = 'Deleted';
	} else {
		$label = 'Verdict Pending';
	}
	return esc_html( $album . ' - ' . $artist . ' - ' . $label );
}
add_filter( 'the_excerpt_rss', 'discard_sealion_rss_description' );

/**
 * RSS category labels: Keep→Kept, Delete→Deleted
 *
 * @param string $cat_list Category list markup.
 * @return string
 */
function discard_sealion_rss_category_labels( $cat_list ) {
	$cat_list = str_replace( '<![CDATA[Keep]]>', '<![CDATA[Kept]]>', $cat_list );
	$cat_list = str_replace( '<![CDATA[Delete]]>', '<![CDATA[Deleted]]>', $cat_list );
	return $cat_list;
}
add_filter( 'the_category_rss', 'discard_sealion_rss_category_labels' );

/**
 * Load template tags
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Load theme options
 */
require get_template_directory() . '/inc/theme-options.php';

/**
 * Load recent-comments data helper
 */
require get_template_directory() . '/inc/recent-comments.php';

/**
 * Load NO DISC memorial banner
 */
require get_template_directory() . '/inc/no-disc-banner.php';
