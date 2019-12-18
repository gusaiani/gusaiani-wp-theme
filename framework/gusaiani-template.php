<?php

/**
 * Site title
 * Displays the gravatar, site title and description
 * Hooked into highwind_header()
 * @since 1.0
 */
if ( ! function_exists( 'highwind_site_title' ) ) {
	function highwind_site_title() {
		?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home" class="site-intro">
        <?php
          do_action( 'highwind_site_title_link' );
          if ( apply_filters( 'highwind_header_gravatar', true ) ) {
            echo get_avatar( apply_filters( 'highwind_header_gravatar_email', $email = esc_attr( "gusaiani@gmail.com" ) ), 512, '', esc_attr( get_bloginfo( 'name' ) ) );
          }
        ?>

        <div class="site-intro-header-container">
          <h1 class="site-title"><?php esc_attr( bloginfo( 'name' ) ); ?></h1>
          <h2 class="site-description"><?php esc_attr( bloginfo( 'description' ) ); ?></h2>
        </div>
			</a>
		<?php
	}
}

?>
