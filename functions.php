<?php
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');
function my_theme_enqueue_styles() {
  wp_enqueue_style(
    'highwind',
    get_template_directory_uri() . '/style.css'
  );
}

if ( ! function_exists( 'gusaiani_add_scripts' ) ) {
	function gusaiani_add_scripts() {
		// Register styles
		wp_register_style( 'lusitana', 'http://fonts.googleapis.com/css?family=Lusitana:400,700' );

		// Enqueue styles
		wp_enqueue_style( 'gusaiani-styles', get_stylesheet_uri(), array( 'lusitana' ), '1.2.4' );

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
?>
