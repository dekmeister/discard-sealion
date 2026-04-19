<?php
/**
 * Discard Sealion Theme Functions
 *
 * @package Discard_Sealion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Theme setup
 */
function discard_sealion_setup() {
	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts
	add_theme_support( 'post-thumbnails' );

	// Enable support for custom logo
	add_theme_support( 'custom-logo', array(
		'height'      => 100,
		'width'       => 100,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	// Enable HTML5 markup support
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );

	// Enable excerpt support for posts
	add_post_type_support( 'post', 'excerpt' );
}
add_action( 'after_setup_theme', 'discard_sealion_setup' );

/**
 * Enqueue scripts and styles
 */
function discard_sealion_scripts() {
	// Enqueue theme stylesheet
	wp_enqueue_style(
		'discard-sealion-style',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'discard_sealion_scripts' );

/**
 * Create verdict categories on theme activation
 */
function discard_sealion_create_verdict_categories() {
	// Create "Keep" category
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

	// Create "Delete" category
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

	return $output;
}
add_filter( 'the_content_feed', 'discard_sealion_add_featured_image_to_feed' );

/**
 * RSS item title: "Album - Artist"
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
 */
function discard_sealion_rss_description( $excerpt ) {
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
