<?php
/**
 * Template overrides for the Gustavo Saiani blog.
 * Mirrors the layout of gustavosaiani.com: a fixed dark bar with the
 * vermilion wordmark, a hero on the index, and a dark footer bar.
 * @package gusaianiblog
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * The one contact row, shared by the hero and the footer.
 */
if ( ! function_exists( 'gusaiani_links' ) ) {
	function gusaiani_links() {
		return array(
			'email'    => 'mailto:gustavo@poe.ma',
			'site'     => 'https://gustavosaiani.com',
			'github'   => 'https://github.com/gusaiani',
			'linkedin' => 'https://linkedin.com/in/gusaiani',
			'x'        => 'https://x.com/gustavosaiani',
		);
	}
}


/**
 * Inline icons, stroked to match the ones on gustavosaiani.com.
 */
if ( ! function_exists( 'gusaiani_icon' ) ) {
	function gusaiani_icon( $name ) {
		$stroke = 'viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"';

		switch ( $name ) {
			case 'email':
				return '<svg ' . $stroke . '><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>';
			case 'github':
				return '<svg ' . $stroke . '><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/></svg>';
			case 'linkedin':
				return '<svg ' . $stroke . '><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>';
			case 'x':
				return '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>';
		}

		return '';
	}
}


/**
 * Post meta, overriding Highwind's.
 *
 * Highwind prints the categories with the_category( ', ' ), which leaves a
 * literal comma between the chips. An empty separator is no help, since
 * core then wraps the links in a <ul>. So the links are printed here with
 * nothing between them, and the chip spacing comes from CSS (flex gap).
 */
if ( ! function_exists( 'highwind_post_meta' ) ) {
	function highwind_post_meta() {
		if ( is_page() ) {
			return;
		}
		?>
		<aside class="post-meta">
			<ul>
				<li class="categories">
					<?php foreach ( get_the_category() as $category ) : ?>
						<a href="<?php echo esc_url( get_category_link( $category ) ); ?>" rel="category tag"><?php echo esc_html( $category->name ); ?></a>
					<?php endforeach; ?>
				</li>
				<li class="comment"><?php comments_popup_link( __( '0 Comments', 'highwind' ), __( '1 Comment', 'highwind' ), __( '% Comments', 'highwind' ) ); ?></li>
				<?php the_tags( '<li class="tags">', ', ', '</li>' ); ?>
				<?php if ( apply_filters( 'highwind_meta_author', true ) ) { ?>
					<li class="author"><?php if ( apply_filters( 'highwind_meta_author_link', true ) ) { the_author_posts_link(); } else { the_author(); } ?></li>
				<?php } // endif ?>
			</ul>
		</aside><!-- /.post-meta -->
		<?php
	}
}


/**
 * The fixed top bar. The wordmark is the one mark carrying pigment and
 * links to the blog home; the links point back at the sections of the
 * main site.
 * Hooked into highwind_body_top().
 */
if ( ! function_exists( 'gusaiani_top_bar' ) ) {
	function gusaiani_top_bar() {
		$site = 'https://gustavosaiani.com';
		?>
		<nav class="nav" role="navigation">
			<div class="nav-inner">
				<a class="nav-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">Gustavo Saiani Blog</a>
				<ul class="nav-links">
					<li><a href="<?php echo esc_url( $site ); ?>/#experience">Experience</a></li>
					<li><a href="<?php echo esc_url( $site ); ?>/#projects">Projects</a></li>
					<li><a href="<?php echo esc_url( $site ); ?>/#skills">Skills</a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="is-current">Blog</a></li>
					<li><a href="<?php echo esc_url( $site ); ?>/#contact">Contact</a></li>
				</ul>
			</div>
		</nav>
		<?php
	}
}


/**
 * The hero. Only on the index: inner pages get the bar and nothing else,
 * the same way the main site carries the hero on its one page.
 * Replaces highwind_site_title(), hooked into highwind_header().
 */
if ( ! function_exists( 'highwind_site_title' ) ) {
	function highwind_site_title() {
		if ( ! is_home() && ! is_front_page() ) {
			return;
		}

		$links       = gusaiani_links();
		$description = get_bloginfo( 'description' );
		?>
		<div class="hero-content">
			<div class="hero-top">
				<div class="hero-text">
					<?php if ( $description ) : ?>
						<p class="hero-label"><?php echo esc_html( $description ); ?></p>
					<?php endif; ?>

					<h1 class="visually-hidden"><?php bloginfo( 'name' ); ?></h1>

					<div class="hero-title">
						<p>Personal blog. Main site at <a href="<?php echo esc_url( $links['site'] ); ?>">gustavosaiani.com</a>.</p>
					</div>

					<div class="hero-meta">
						<span class="hero-meta-item"><?php echo gusaiani_icon( 'email' ); ?><a href="<?php echo esc_url( $links['email'] ); ?>">gustavo@poe.ma</a></span>
						<span class="hero-meta-item"><?php echo gusaiani_icon( 'github' ); ?><a href="<?php echo esc_url( $links['github'] ); ?>" rel="noopener noreferrer">GitHub</a></span>
						<span class="hero-meta-item"><?php echo gusaiani_icon( 'linkedin' ); ?><a href="<?php echo esc_url( $links['linkedin'] ); ?>" rel="noopener noreferrer">LinkedIn</a></span>
						<span class="hero-meta-item"><?php echo gusaiani_icon( 'x' ); ?><a href="<?php echo esc_url( $links['x'] ); ?>" rel="noopener noreferrer">X</a></span>
					</div>
				</div>

				<div class="hero-photo-wrap">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<?php
							echo get_avatar(
								apply_filters( 'highwind_header_gravatar_email', 'gusaiani@gmail.com' ),
								512,
								'',
								esc_attr( get_bloginfo( 'name' ) ),
								array( 'class' => 'hero-photo' )
							);
						?>
					</a>
				</div>
			</div>
		</div>
		<?php
	}
}


/**
 * Section header above the post list, the label / title pair used
 * throughout the main site.
 * Hooked into highwind_content_top().
 */
if ( ! function_exists( 'gusaiani_index_section_header' ) ) {
	function gusaiani_index_section_header() {
		if ( is_home() || is_front_page() ) {
			?>
			<div class="section-header">
				<p class="section-label">Writing</p>
				<h2 class="section-title">Latest posts</h2>
			</div>
			<?php
			return;
		}

		// Archives and search print their own title; they only need the kicker.
		if ( is_archive() ) {
			echo '<p class="section-label">Archive</p>';
		} elseif ( is_search() ) {
			echo '<p class="section-label">Search</p>';
		}
	}
}


/**
 * The dark footer bar, bookending the page against the top bar.
 * Hooked into highwind_body_bottom().
 */
if ( ! function_exists( 'gusaiani_site_footer' ) ) {
	function gusaiani_site_footer() {
		$links = gusaiani_links();
		?>
		<footer class="site-footer" role="contentinfo">
			<div class="site-footer-inner">
				<div class="site-footer-contact">
					<h2>Let&rsquo;s work together</h2>
					<div class="site-footer-links">
						<a href="<?php echo esc_url( $links['email'] ); ?>" class="site-footer-link">gustavo@poe.ma</a>
						<a href="<?php echo esc_url( $links['site'] ); ?>" class="site-footer-link">Site</a>
						<a href="<?php echo esc_url( $links['github'] ); ?>" class="site-footer-link" rel="noopener noreferrer">GitHub</a>
						<a href="<?php echo esc_url( $links['linkedin'] ); ?>" class="site-footer-link" rel="noopener noreferrer">LinkedIn</a>
						<a href="<?php echo esc_url( $links['x'] ); ?>" class="site-footer-link" rel="noopener noreferrer">X</a>
					</div>
				</div>
				<p class="site-footer-copy">&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
			</div>
		</footer>
		<?php
	}
}
