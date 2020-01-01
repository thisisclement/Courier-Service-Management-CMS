<?php
/**
 * Spacious functions and definitions
 *
 * This file contains all the functions and it's defination that particularly can't be
 * in other files.
 * 
 * @package ThemeGrill
 * @subpackage Spacious
 * @since Spacious 1.0
 */

/****************************************************************************************/

add_action( 'wp_enqueue_scripts', 'spacious_scripts_styles_method' );
/**
 * Register jquery scripts
 */
function spacious_scripts_styles_method() {
   /**
	* Loads our main stylesheet.
	*/
	wp_enqueue_style( 'spacious_style', get_stylesheet_uri() );

	if( of_get_option( 'spacious_color_skin', 'light' ) == 'dark' ) {
		wp_enqueue_style( 'spacious_dark_style', SPACIOUS_CSS_URL. '/dark.css' );
	}

	/**
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/**
	 * Register JQuery cycle js file for slider.
	 */
	wp_register_script( 'jquery_cycle', SPACIOUS_JS_URL . '/jquery.cycle.all.min.js', array( 'jquery' ), '2.9999.5', true );

   wp_register_style( 'google_fonts', 'http://fonts.googleapis.com/css?family=Lato' ); 
	
	/**
	 * Enqueue Slider setup js file.	 
	 */
	if ( is_home() || is_front_page() && of_get_option( 'spacious_activate_slider', '0' ) == '1' ) {
		wp_enqueue_script( 'spacious_slider', SPACIOUS_JS_URL . '/spacious-slider-setting.js', array( 'jquery_cycle' ), false, true );
	}
	wp_enqueue_script( 'spacious-navigation', SPACIOUS_JS_URL . '/navigation.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'spacious-custom', SPACIOUS_JS_URL. '/spacious-custom.js', array( 'jquery' ) );

	wp_enqueue_style( 'google_fonts' );

   $spacious_user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	if(preg_match('/(?i)msie [1-8]/',$spacious_user_agent)) {
		wp_enqueue_script( 'html5', SPACIOUS_JS_URL . '/html5.js', true ); 
	}

}

add_action( 'admin_print_styles-appearance_page_options-framework', 'spacious_admin_styles' );
/**
 * Enqueuing some styles.
 *
 * @uses wp_enqueue_style to register stylesheets.
 * @uses wp_enqueue_style to add styles.
 */
function spacious_admin_styles() {
	wp_enqueue_style( 'spacious_admin_style', SPACIOUS_ADMIN_CSS_URL. '/admin.css' );
}

/****************************************************************************************/

add_filter( 'excerpt_length', 'spacious_excerpt_length' );
/**
 * Sets the post excerpt length to 40 words.
 *
 * function tied to the excerpt_length filter hook.
 *
 * @uses filter excerpt_length
 */
function spacious_excerpt_length( $length ) {
	return 40;
}

add_filter( 'excerpt_more', 'spacious_continue_reading' );
/**
 * Returns a "Continue Reading" link for excerpts
 */
function spacious_continue_reading() {
	return '';
}

/****************************************************************************************/

/**
 * Removing the default style of wordpress gallery
 */
add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * Filtering the size to be medium from thumbnail to be used in WordPress gallery as a default size
 */
function spacious_gallery_atts( $out, $pairs, $atts ) {
	$atts = shortcode_atts( array(
	'size' => 'medium',
	), $atts );

	$out['size'] = $atts['size'];
	 
	return $out;
 
}
add_filter( 'shortcode_atts_gallery', 'spacious_gallery_atts', 10, 3 );

/****************************************************************************************/

add_filter( 'body_class', 'spacious_body_class' );
/**
 * Filter the body_class
 *
 * Throwing different body class for the different layouts in the body tag
 */
function spacious_body_class( $classes ) {
	global $post;

	if( $post ) { $layout_meta = get_post_meta( $post->ID, 'spacious_page_layout', true ); }

	if( is_home() ) {
		$queried_id = get_option( 'page_for_posts' );
		$layout_meta = get_post_meta( $queried_id, 'spacious_page_layout', true ); 
	}

	if( empty( $layout_meta ) || is_archive() || is_search() ) { $layout_meta = 'default_layout'; }
	$spacious_default_layout = of_get_option( 'spacious_default_layout', 'right_sidebar' );

	$spacious_default_page_layout = of_get_option( 'spacious_pages_default_layout', 'right_sidebar' );
	$spacious_default_post_layout = of_get_option( 'spacious_single_posts_default_layout', 'right_sidebar' );

	if( $layout_meta == 'default_layout' ) {
		if( is_page() ) {
			if( $spacious_default_page_layout == 'right_sidebar' ) { $classes[] = ''; }
			elseif( $spacious_default_page_layout == 'left_sidebar' ) { $classes[] = 'left-sidebar'; }
			elseif( $spacious_default_page_layout == 'no_sidebar_full_width' ) { $classes[] = 'no-sidebar-full-width'; }
			elseif( $spacious_default_page_layout == 'no_sidebar_content_centered' ) { $classes[] = 'no-sidebar'; }
		}
		elseif( is_single() ) {
			if( $spacious_default_post_layout == 'right_sidebar' ) { $classes[] = ''; }
			elseif( $spacious_default_post_layout == 'left_sidebar' ) { $classes[] = 'left-sidebar'; }
			elseif( $spacious_default_post_layout == 'no_sidebar_full_width' ) { $classes[] = 'no-sidebar-full-width'; }
			elseif( $spacious_default_post_layout == 'no_sidebar_content_centered' ) { $classes[] = 'no-sidebar'; }
		}
		elseif( $spacious_default_layout == 'right_sidebar' ) { $classes[] = ''; }
		elseif( $spacious_default_layout == 'left_sidebar' ) { $classes[] = 'left-sidebar'; }
		elseif( $spacious_default_layout == 'no_sidebar_full_width' ) { $classes[] = 'no-sidebar-full-width'; }
		elseif( $spacious_default_layout == 'no_sidebar_content_centered' ) { $classes[] = 'no-sidebar'; }
	}
	elseif( $layout_meta == 'right_sidebar' ) { $classes[] = ''; }
	elseif( $layout_meta == 'left_sidebar' ) { $classes[] = 'left-sidebar'; }
	elseif( $layout_meta == 'no_sidebar_full_width' ) { $classes[] = 'no-sidebar-full-width'; }
	elseif( $layout_meta == 'no_sidebar_content_centered' ) { $classes[] = 'no-sidebar'; }


	if( is_page_template( 'page-templates/blog-image-alternate-medium.php' ) ) {
		$classes[] = 'blog-alternate-medium';
	}
	if( is_page_template( 'page-templates/blog-image-medium.php' ) ) {
		$classes[] = 'blog-medium';
	}
	if ( of_get_option( 'spacious_archive_display_type', 'blog_large' ) == 'blog_medium_alternate' ) {
		$classes[] = 'blog-alternate-medium';
	}
	if ( of_get_option( 'spacious_archive_display_type', 'blog_large' ) == 'blog_medium' ) {
		$classes[] = 'blog-medium';
	}
	if( of_get_option( 'spacious_site_layout', 'box_1218px' ) == 'wide_978px' ) {
		$classes[] = 'wide-978';
	}
	elseif( of_get_option( 'spacious_site_layout', 'box_1218px' ) == 'box_978px' ) {
		$classes[] = 'narrow-978';
	}
	elseif( of_get_option( 'spacious_site_layout', 'box_1218px' ) == 'wide_1218px' ) {
		$classes[] = 'wide-1218';
	}
	else {
		$classes[] = '';
	}

	return $classes;
}

/****************************************************************************************/

if ( ! function_exists( 'spacious_sidebar_select' ) ) :
/**
 * Fucntion to select the sidebar
 */
function spacious_sidebar_select() {
	global $post;

	if( $post ) { $layout_meta = get_post_meta( $post->ID, 'spacious_page_layout', true ); }

	if( is_home() ) {
		$queried_id = get_option( 'page_for_posts' );
		$layout_meta = get_post_meta( $queried_id, 'spacious_page_layout', true ); 
	}

	if( empty( $layout_meta ) || is_archive() || is_search() ) { $layout_meta = 'default_layout'; }
	$spacious_default_layout = of_get_option( 'spacious_default_layout', 'right_sidebar' );

	$spacious_default_page_layout = of_get_option( 'spacious_pages_default_layout', 'right_sidebar' );
	$spacious_default_post_layout = of_get_option( 'spacious_single_posts_default_layout', 'right_sidebar' );

	if( $layout_meta == 'default_layout' ) {
		if( is_page() ) {
			if( $spacious_default_page_layout == 'right_sidebar' ) { get_sidebar(); }
			elseif ( $spacious_default_page_layout == 'left_sidebar' ) { get_sidebar( 'left' ); }
		}
		if( is_single() ) {
			if( $spacious_default_post_layout == 'right_sidebar' ) { get_sidebar(); }
			elseif ( $spacious_default_post_layout == 'left_sidebar' ) { get_sidebar( 'left' ); }
		}
		elseif( $spacious_default_layout == 'right_sidebar' ) { get_sidebar(); }
		elseif ( $spacious_default_layout == 'left_sidebar' ) { get_sidebar( 'left' ); }
	}
	elseif( $layout_meta == 'right_sidebar' ) { get_sidebar(); }
	elseif( $layout_meta == 'left_sidebar' ) { get_sidebar( 'left' ); }
}
endif;

/****************************************************************************************/

add_action( 'admin_head', 'spacious_favicon' );
add_action( 'wp_head', 'spacious_favicon' );
/**
 * Fav icon for the site
 */
function spacious_favicon() {
	if ( of_get_option( 'spacious_activate_favicon', '0' ) == '1' ) {
		$spacious_favicon = of_get_option( 'spacious_favicon', '' );
		$spacious_favicon_output = '';
		if ( !empty( $spacious_favicon ) ) {
			$spacious_favicon_output .= '<link rel="shortcut icon" href="'.esc_url( $spacious_favicon ).'" type="image/x-icon" />';
		}
		echo $spacious_favicon_output;
	}
}

/****************************************************************************************/

add_action('wp_head', 'spacious_custom_css');
/**
 * Hooks the Custom Internal CSS to head section
 */
function spacious_custom_css() {	
	$primary_color = of_get_option( 'spacious_primary_color', '#0FBE7C' );
	$spacious_internal_css = '';
	if( $primary_color != '#0FBE7C' ) {
		$spacious_internal_css = ' blockquote { border-left: 3px solid '.$primary_color.'; }
			.spacious-button, input[type="reset"], input[type="button"], input[type="submit"], button { background-color: '.$primary_color.'; }
			.previous a:hover, .next a:hover { 	color: '.$primary_color.'; }
			a { color: '.$primary_color.'; }
			#site-title a:hover { color: '.$primary_color.'; }
			.main-navigation ul li.current_page_item a, .main-navigation ul li:hover > a { color: '.$primary_color.'; }
			.main-navigation ul li ul { border-top: 1px solid '.$primary_color.'; }
			.main-navigation ul li ul li a:hover, .main-navigation ul li ul li:hover > a, .main-navigation ul li.current-menu-item ul li a:hover { color: '.$primary_color.'; }
			.site-header .menu-toggle:hover { background: '.$primary_color.'; }
			.main-small-navigation li:hover { background: '.$primary_color.'; }
			.main-small-navigation ul > .current_page_item, .main-small-navigation ul > .current-menu-item { background: '.$primary_color.'; }
			.main-navigation a:hover, .main-navigation ul li.current-menu-item a, .main-navigation ul li.current_page_ancestor a, .main-navigation ul li.current-menu-ancestor a, .main-navigation ul li.current_page_item a, .main-navigation ul li:hover > a  { color: '.$primary_color.'; }
			.small-menu a:hover, .small-menu ul li.current-menu-item a, .small-menu ul li.current_page_ancestor a, .small-menu ul li.current-menu-ancestor a, .small-menu ul li.current_page_item a, .small-menu ul li:hover > a { color: '.$primary_color.'; }
			#featured-slider .slider-read-more-button { background-color: '.$primary_color.'; }
			#controllers a:hover, #controllers a.active { background-color: '.$primary_color.'; color: '.$primary_color.'; }
			.breadcrumb a:hover { color: '.$primary_color.'; }
			.tg-one-half .widget-title a:hover, .tg-one-third .widget-title a:hover, .tg-one-fourth .widget-title a:hover { color: '.$primary_color.'; }
			.pagination span { background-color: '.$primary_color.'; }
			.pagination a span:hover { color: '.$primary_color.'; border-color: .'.$primary_color.'; }
			.widget_testimonial .testimonial-post { border-color: '.$primary_color.' #EAEAEA #EAEAEA #EAEAEA; }
			.call-to-action-content-wrapper { border-color: #EAEAEA #EAEAEA #EAEAEA '.$primary_color.'; }
			.call-to-action-button { background-color: '.$primary_color.'; }
			#content .comments-area a.comment-permalink:hover { color: '.$primary_color.'; }
			.comments-area .comment-author-link a:hover { color: '.$primary_color.'; }
			.comments-area .comment-author-link span { background-color: '.$primary_color.'; }
			.comment .comment-reply-link:hover { color: '.$primary_color.'; }
			.nav-previous a:hover, .nav-next a:hover { color: '.$primary_color.'; }
			#wp-calendar #today { color: '.$primary_color.'; }
			.widget-title span { border-bottom: 2px solid '.$primary_color.'; }
			.footer-widgets-area a:hover { color: '.$primary_color.' !important; }
			.footer-socket-wrapper .copyright a:hover { color: '.$primary_color.'; }
			a#back-top:before { background-color: '.$primary_color.'; }
			.read-more, .more-link { color: '.$primary_color.'; }
			.post .entry-title a:hover, .page .entry-title a:hover { color: '.$primary_color.'; }
			.post .entry-meta .read-more-link { background-color: '.$primary_color.'; }
			.post .entry-meta a:hover, .type-page .entry-meta a:hover { color: '.$primary_color.'; }
			.single #content .tags a:hover { color: '.$primary_color.'; }
			.widget_testimonial .testimonial-icon:before { color: '.$primary_color.'; }
			a#scroll-up { background-color: '.$primary_color.'; }
			#search-form span { background-color: '.$primary_color.'; }';
	}

	if( !empty( $spacious_internal_css ) ) {
		?>
		<style type="text/css"><?php echo $spacious_internal_css; ?></style>
		<?php
	}

	$spacious_custom_css = of_get_option( 'spacious_custom_css', '' );
	if( !empty( $spacious_custom_css ) ) {
		?>
		<style type="text/css"><?php echo $spacious_custom_css; ?></style>
		<?php
	}
}

/**************************************************************************************/

if ( ! function_exists( 'spacious_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 */
function spacious_content_nav( $nav_id ) {
	global $wp_query, $post;

	// Don't print empty markup on single pages if there's nowhere to navigate.
	if ( is_single() ) {
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous )
			return;
	}

	// Don't print empty markup in archives if there's only one page.
	if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
		return;

	$nav_class = ( is_single() ) ? 'post-navigation' : 'paging-navigation';

	?>
	<nav role="navigation" id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo $nav_class; ?>">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'spacious' ); ?></h1>

	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'spacious' ) . '</span> %title' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'spacious' ) . '</span>' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'spacious' ) ); ?></div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'spacious' ) ); ?></div>
		<?php endif; ?>

	<?php endif; ?>

	</nav><!-- #<?php echo esc_html( $nav_id ); ?> -->
	<?php
}
endif; // spacious_content_nav

/**************************************************************************************/

if ( ! function_exists( 'spacious_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function spacious_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'spacious' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'spacious' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header class="comment-meta comment-author vcard">
				<?php
					echo get_avatar( $comment, 74 );
					printf( '<div class="comment-author-link">%1$s%2$s</div>',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === $post->post_author ) ? '<span>' . __( 'Post author', 'spacious' ) . '</span>' : ''
					);
					printf( '<div class="comment-date-time">%1$s</div>',
						sprintf( __( '%1$s at %2$s', 'spacious' ), get_comment_date(), get_comment_time() )
					);
					printf( __( '<a class="comment-permalink" href="%1$s">Permalink</a>', 'spacious'), esc_url( get_comment_link( $comment->comment_ID ) ) );
					edit_comment_link();
				?>
			</header><!-- .comment-meta -->

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'spacious' ); ?></p>
			<?php endif; ?>

			<section class="comment-content comment">
				<?php comment_text(); ?>
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'spacious' ), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</section><!-- .comment-content -->
			
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;

/**************************************************************************************/

/* Register shortcodes. */
add_action( 'init', 'spacious_add_shortcodes' );
/**
 * Creates new shortcodes for use in any shortcode-ready area.  This function uses the add_shortcode() 
 * function to register new shortcodes with WordPress.
 *
 * @uses add_shortcode() to create new shortcodes.
 */
function spacious_add_shortcodes() {
	/* Add theme-specific shortcodes. */
	add_shortcode( 'the-year', 'spacious_the_year_shortcode' );
	add_shortcode( 'site-link', 'spacious_site_link_shortcode' );
	add_shortcode( 'wp-link', 'spacious_wp_link_shortcode' );
	add_shortcode( 'tg-link', 'spacious_themegrill_link_shortcode' );
}

/**
 * Shortcode to display the current year.
 *
 * @uses date() Gets the current year.
 * @return string
 */
function spacious_the_year_shortcode() {
   return date( 'Y' );
}

/**
 * Shortcode to display a link back to the site.
 *
 * @uses get_bloginfo() Gets the site link
 * @return string
 */
function spacious_site_link_shortcode() {
   return '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" ><span>' . get_bloginfo( 'name', 'display' ) . '</span></a>';
}

/**
 * Shortcode to display a link to WordPress.org.
 *
 * @return string
 */
function spacious_wp_link_shortcode() {
   return '<a href="'.esc_url( 'http://wordpress.org' ).'" target="_blank" title="' . esc_attr__( 'WordPress', 'spacious' ) . '"><span>' . __( 'WordPress', 'spacious' ) . '</span></a>';
}

/**
 * Shortcode to display a link to spacious.com.
 *
 * @return string
 */
function spacious_themegrill_link_shortcode() {
   return '<a href="'.esc_url( 'http://themegrill.com' ).'" target="_blank" title="'.esc_attr__( 'ThemeGrill', 'spacious' ).'" ><span>'.__( 'ThemeGrill', 'spacious') .'</span></a>';
}

add_action( 'spacious_footer_copyright', 'spacious_footer_copyright', 10 );
/**
 * function to show the footer info, copyright information
 */
if ( ! function_exists( 'spacious_footer_copyright' ) ) :
function spacious_footer_copyright() {
	$spacious_footer_copyright = '<div class="copyright">'.__( 'Copyright &copy; ', 'spacious' ).'[the-year] [site-link] '.__( 'Theme by: ', 'spacious' ).'[tg-link] '.__( 'Powered by: ', 'spacious' ).'[wp-link]'.'</div>';
	echo do_shortcode( $spacious_footer_copyright );
}
endif;

/**************************************************************************************/

if ( ! function_exists( 'spacious_posts_listing_display_type_select' ) ) :
/**
 * Function to select the posts listing display type
 */
function spacious_posts_listing_display_type_select() {			
	if ( of_get_option( 'spacious_archive_display_type', 'blog_large' ) == 'blog_large' ) {
		$format = 'blog-image-large';
	}
	elseif ( of_get_option( 'spacious_archive_display_type', 'blog_large' ) == 'blog_medium' ) {
		$format = 'blog-image-medium';
	}
	elseif ( of_get_option( 'spacious_archive_display_type', 'blog_large' ) == 'blog_medium_alternate' ) {
		$format = 'blog-image-medium';
	}
	elseif ( of_get_option( 'spacious_archive_display_type', 'blog_large' ) == 'blog_full_content' ) {
		$format = 'blog-full-content';
	}
	else {
		$format = get_post_format();
	}

	return $format;
}
endif;

/****************************************************************************************/

add_action( 'spacious_before_body_content', 'spacious_update_transition');
/**
 * checks for blog page templates for making transition
 */
if ( ! function_exists( 'spacious_update_transition' ) ) :
function spacious_update_transition() {
	if( is_page_template( 'page-templates/blog-image-alternate-medium.php' ) || is_page_template( 'page-templates/blog-image-large.php' ) || is_page_template( 'page-templates/blog-image-medium.php' ) || is_page_template( 'page-templates/blog-full-content.php' ) ) {
		global $post;
		if( !get_option('page_for_posts') && 'page' == get_option( 'show_on_front') && 'publish' == get_post_status() ) {
			$template_meta = basename( get_post_meta( $post->ID, '_wp_page_template', 'default' ) );
			spacious_update_option( $template_meta );
			if( get_option( 'page_on_front' ) == get_the_ID() ) {
				update_option ( 'page_on_front', 0 );
			}
			update_option( 'page_for_posts', get_the_ID() );
			update_post_meta( $post->ID, '_wp_page_template', 'default' );
		}
	}
}
endif;

/**************************************************************************************/

/**
 * Updates the blog display in options framework as per the template selection.
 */
if ( ! function_exists( 'spacious_update_option' ) ) :
function spacious_update_option( $template_meta ) {
	$config = get_option( 'optionsframework' );
	$optionname = get_option( $config['id'] );
	if ( $optionname ) {
		if( $template_meta == 'blog-image-large.php') {
	  		$optionname['spacious_archive_display_type'] = 'blog_large';
	  	}
	  	elseif( $template_meta == 'blog-image-medium.php') {
	  		$optionname['spacious_archive_display_type'] = 'blog_medium';
	  	}
	  	elseif( $template_meta == 'blog-image-alternate-medium.php') {
	  		$optionname['spacious_archive_display_type'] = 'blog_medium_alternate';
	  	}
	  	elseif( $template_meta == 'blog-full-content.php') {
	  		$optionname['spacious_archive_display_type'] = 'blog_full_content';
	  	}
		update_option( $config['id'], $optionname );
	}
}
endif;
?>