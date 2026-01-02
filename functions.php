<?php
/**
 * CD Collection Theme Functions
 *
 * @package CD_Collection
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Theme setup
 */
function cd_collection_setup() {
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
}
add_action( 'after_setup_theme', 'cd_collection_setup' );

/**
 * Enqueue scripts and styles
 */
function cd_collection_scripts() {
	// Enqueue theme stylesheet
	wp_enqueue_style(
		'cd-collection-style',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'cd_collection_scripts' );

/**
 * Load custom field registration and meta boxes
 */
require get_template_directory() . '/inc/custom-fields.php';
require get_template_directory() . '/inc/meta-boxes.php';
require get_template_directory() . '/inc/template-tags.php';
