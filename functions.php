<?php
require_once( get_stylesheet_directory() . '/framework/gusaiani-init.php' );

add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');
function my_theme_enqueue_styles() {
  wp_enqueue_style(
    'highwind',
    get_template_directory_uri() . '/style.css'
  );
}

if ( ! function_exists( 'gusaiani_add_scripts' ) ) {
	function gusaiani_add_scripts() {
		// Enqueue styles
		wp_enqueue_style( 'gusaiani-styles', get_stylesheet_uri(), array(), '2.0.0' );

		// Enqueue Scripts
		wp_enqueue_script( 'gusaiani-plugins', get_template_directory_uri() . '/framework/js/plugins.min.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'gusaiani-script', get_template_directory_uri() . '/framework/js/script.min.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/framework/js/modernizr.min.js', array( 'jquery' ), '2.6.2' );
		wp_enqueue_script( 'fitvids', get_template_directory_uri() . '/framework/js/fitvids.min.js', array( 'jquery' ), '1.0' );
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
}

function remove_highwind_add_scripts() {
  remove_action( 'wp_enqueue_scripts', 'highwind_add_scripts' );
}

add_action('init', 'remove_highwind_add_scripts');
add_action( 'wp_enqueue_scripts', 'gusaiani_add_scripts' );

/**
 * Layout, mirroring gustavosaiani.com.
 *
 * Highwind's own header furniture (the skip link and the slide-out
 * navigation) is dropped: the fixed bar replaces both. The hero stays on
 * highwind_header(), so it keeps rendering inside <header class="header">.
 */
function gusaiani_layout_hooks() {
	remove_action( 'highwind_header', 'highwind_navigation_toggle', 10 );
	remove_action( 'highwind_header', 'highwind_main_navigation', 30 );

	add_action( 'highwind_body_top',    'gusaiani_top_bar', 10 );
	add_action( 'highwind_content_top', 'gusaiani_index_section_header', 10 );
	add_action( 'highwind_body_bottom', 'gusaiani_site_footer', 10 );
}
add_action( 'init', 'gusaiani_layout_hooks' );

// Preload the two variable faces so the first paint is not a fallback.
function gusaiani_preload_fonts() {
	$dir = get_stylesheet_directory_uri() . '/fonts/';
	echo '<link rel="preload" href="' . esc_url( $dir . 'KimuraSansVF.woff2' ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
	echo '<link rel="preload" href="' . esc_url( $dir . 'CarbonaVF.woff2' ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
}
add_action( 'wp_head', 'gusaiani_preload_fonts', 1 );

// Set excerpt length to 20 words
function custom_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 20 );

// Replace Gravatar with custom avatar
add_filter('get_avatar_url', 'gusaiani_custom_avatar', 10, 3);
function gusaiani_custom_avatar($url, $id_or_email, $args) {
    return '/wp-content/uploads/2026/03/header-image.jpeg';
}
