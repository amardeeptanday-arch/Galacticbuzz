<?php
/**
 * Galacticbuzz theme functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GB_VERSION', '1.0.0' );

require_once get_template_directory() . '/inc/content.php';
require_once get_template_directory() . '/inc/team.php';
require_once get_template_directory() . '/inc/leads.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/setup.php';

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_image_size( 'gb-portrait', 800, 1000, true );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'gb-fonts', 'https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&family=Geist+Mono:wght@400;500&display=swap', array(), null );
	wp_enqueue_style( 'gb-site', get_template_directory_uri() . '/assets/css/site.css', array( 'gb-fonts' ), GB_VERSION );
	wp_enqueue_script( 'gb-site', get_template_directory_uri() . '/assets/js/site.js', array(), GB_VERSION, true );
	wp_localize_script( 'gb-site', 'GB', array(
		'ajax'  => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'gb_lead' ),
		'email' => gb_mod( 'contact_email' ),
	) );
} );

add_filter( 'wp_resource_hints', function ( $urls, $relation ) {
	if ( 'preconnect' === $relation ) {
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $urls;
}, 10, 2 );

/**
 * URL of a site page, optionally with an in-page anchor.
 */
function gb_url( $slug = '', $anchor = '' ) {
	$url = home_url( $slug ? '/' . $slug . '/' : '/' );
	return esc_url( $anchor ? $url . '#' . $anchor : $url );
}

/**
 * Inline SVG icon from the sprite in template-parts/sprite.php.
 */
function gb_icon( $id, $size = 0 ) {
	$dims = $size ? ' width="' . (int) $size . '" height="' . (int) $size . '"' : '';
	return '<svg' . $dims . ' aria-hidden="true"><use href="#i-' . esc_attr( $id ) . '"/></svg>';
}

/**
 * Theme setting with its default.
 */
function gb_mod( $key ) {
	$defaults = gb_mod_defaults();
	return get_theme_mod( 'gb_' . $key, isset( $defaults[ $key ] ) ? $defaults[ $key ] : '' );
}

/**
 * Current page slug, used to mark the active menu item.
 */
function gb_current() {
	if ( is_front_page() ) {
		return 'home';
	}
	$post = get_queried_object();
	return ( $post && isset( $post->post_name ) ) ? $post->post_name : '';
}

/* The site uses no emoji images; skip WordPress's emoji script for faster pages. */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/* Sticky header sits under the admin bar when logged in. */
add_action( 'wp_head', function () {
	if ( is_admin_bar_showing() ) {
		echo '<style>.topbar{top:32px}@media (max-width:782px){.topbar{top:46px}}</style>';
	}
} );
