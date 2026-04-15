<?php
/**
 * Global functions
 *
 * @file    functions.php
 * @package Girl_Skate_Cos
 * @since   1.0.0
 */

define( 'TEMPLATE_DIR_PATH', trailingslashit( get_template_directory() ) );
define( 'TEMPLATE_DIR_URI', trailingslashit( get_template_directory_uri() ) );
define( 'THEME_VERSION', wp_get_theme()->get( 'Version' ) );

/* Frontend */
if ( ! is_admin() ) {
	require_once TEMPLATE_DIR_PATH . 'frontend/brand-details.php';
	require_once TEMPLATE_DIR_PATH . 'frontend/breadcrumbs.php';
	require_once TEMPLATE_DIR_PATH . 'frontend/frontend.php';
	require_once TEMPLATE_DIR_PATH . 'frontend/sort-search.php';
	require_once TEMPLATE_DIR_PATH . 'frontend/stickers.php';
}


add_action( 'wp_enqueue_scripts', 'register_stylesheets_scripts' );
/**
 * Register stylesheets & scripts
 */
function register_stylesheets_scripts() {
	wp_enqueue_style( 'normalize', TEMPLATE_DIR_URI . 'assets/css/normalize.css', array(), THEME_VERSION );
	wp_enqueue_style( 'stylesheet', TEMPLATE_DIR_URI . 'assets/css/style.css', array(), THEME_VERSION, 'all' );

	wp_enqueue_script( 'sort-search', TEMPLATE_DIR_URI . 'assets/js/sort-search.js', array(), THEME_VERSION, true );
	wp_enqueue_script( 'rotate-stickers', TEMPLATE_DIR_URI . 'assets/js/rotate-stickers.js', array(), THEME_VERSION, true );
}


add_action( 'init', 'register_menus' );
/**
 * Register footer menu
 */
function register_menus() {
	register_nav_menus( array( 'footer-menu' => 'Footer menu' ) );
}


add_action( 'init', 'unregister_tags' );
/**
 * Unregister post tags
 */
function unregister_tags() {
	register_taxonomy( 'post_tag', array() );
}


add_action( 'after_setup_theme', 'theme_setup' );
/**
 * Theme setup
 */
function theme_setup() {
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 110,
			'width'       => 110,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array( 'site-title', 'site-description' ),
		)
	);
	add_theme_support( 'editor-styles' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'wp-block-styles' );

	add_post_type_support( 'page', 'excerpt' );

	add_editor_style( 'editor-styles.css' );
}
