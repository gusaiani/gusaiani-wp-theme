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
		wp_enqueue_style( 'gusaiani-styles', get_stylesheet_uri(), array(), '2.0.1' );

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

/**
 * Auto-generated excerpts, with the line breaks left in.
 *
 * Core builds an excerpt by running the content through wp_strip_all_tags(),
 * which deletes <br> and </p> without putting anything in their place. Prose
 * survives that; a poem does not, and you get "bem-aliCaimos inseparaveisApenas".
 * So: turn the breaks into real newlines first, then trim to the word limit
 * without flattening them. wpautop() on the_excerpt turns them back into
 * <br> and <p> on the way out.
 *
 * Manual excerpts are left alone - core already passes those through verbatim.
 */
function gusaiani_excerpt( $excerpt, $post = null ) {
	$post = get_post( $post );

	if ( ! $post || post_password_required( $post ) ) {
		return $excerpt;
	}

	// A manual excerpt is used verbatim, the way core uses it.
	if ( '' !== trim( (string) $post->post_excerpt ) ) {
		return apply_filters( 'wp_trim_excerpt', $post->post_excerpt, $post->post_excerpt );
	}

	$text = get_the_content( '', false, $post );
	$text = strip_shortcodes( $text );
	$text = excerpt_remove_blocks( $text );
	$text = apply_filters( 'the_content', $text );
	$text = str_replace( ']]>', ']]&gt;', $text );

	// Jetpack appends its related-posts container to the_content; its heading
	// would otherwise show up as excerpt text.
	$text = preg_replace( '#<div[^>]+id=[\'"]jp-relatedposts[\'"].*#is', '', $text );

	// Block and line boundaries become newlines before the tags are stripped.
	$text = preg_replace( '#<br\s*/?>#i', "\n", $text );
	$text = preg_replace( '#</(?:p|div|h[1-6]|li|blockquote|figcaption|tr)>#i', "\n\n", $text );
	$text = wp_strip_all_tags( $text );
	$text = preg_replace( '/[ \t]*\n[ \t]*/', "\n", $text );
	$text = preg_replace( '/\n{3,}/', "\n\n", $text );
	$text = trim( $text );

	$length = (int) apply_filters( 'excerpt_length', 55 );
	$more   = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );

	$text = gusaiani_trim_words_keeping_breaks( $text, $length, $more );

	return apply_filters( 'wp_trim_excerpt', $text, $excerpt );
}
// Replaces core's wp_trim_excerpt() rather than running after it, so
// the_content filters fire once per post instead of twice.
remove_filter( 'get_the_excerpt', 'wp_trim_excerpt', 10 );
add_filter( 'get_the_excerpt', 'gusaiani_excerpt', 10, 2 );

/**
 * wp_trim_words() collapses every run of whitespace to a single space.
 * This one counts words the same way but rebuilds the text line by line.
 */
function gusaiani_trim_words_keeping_breaks( $text, $num_words, $more ) {
	$words = preg_split( '/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY );

	if ( count( $words ) <= $num_words ) {
		return $text;
	}

	$out  = array();
	$left = $num_words;

	foreach ( explode( "\n", $text ) as $line ) {
		if ( $left <= 0 ) {
			break;
		}

		if ( '' === trim( $line ) ) {
			$out[] = '';
			continue;
		}

		$line_words = preg_split( '/\s+/', trim( $line ), -1, PREG_SPLIT_NO_EMPTY );
		$take       = array_slice( $line_words, 0, $left );
		$left      -= count( $take );
		$out[]      = implode( ' ', $take );
	}

	return rtrim( implode( "\n", $out ) ) . $more;
}

// Replace Gravatar with custom avatar
add_filter('get_avatar_url', 'gusaiani_custom_avatar', 10, 3);
function gusaiani_custom_avatar($url, $id_or_email, $args) {
    return '/wp-content/uploads/2026/03/header-image.jpeg';
}
