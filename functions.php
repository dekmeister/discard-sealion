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
 * Load template tags
 */
require get_template_directory() . '/inc/template-tags.php';
