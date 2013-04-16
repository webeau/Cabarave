<?php
/** functions.php
 *
 * @author		Bret Holstein, Konstantin Obenland
 * @package		Cabarave
 * @since		1.0.0 - 05.02.2012
 */

if ( ! function_exists( 'cabarave_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @author	WordPress.org
 * @since	1.0.0 - 05.02.2012
 *
 * @return	void
 */
function cabarave_setup() {
	global $content_width;
	
	if ( ! isset( $content_width ) ) {
		$content_width = 770;
	}
	
	load_theme_textdomain( 'cabarave', get_template_directory() . '/lang' );
	
        add_theme_support( 'woocommerce' );

        add_theme_support( 'automatic-feed-links' );
	
	add_theme_support( 'post-thumbnails' );

	add_theme_support( 'post-formats', array(
		'aside',
		'chat',
		'link',
		'gallery',
		'status',
		'quote',
		'image',
		'video'
	) );
	
	add_theme_support( 'tha_hooks', array( 'all' ) );

	if ( version_compare( get_bloginfo( 'version' ), '3.4', '<' ) )
		// Custom Theme Options
		require_once( get_template_directory() . '/inc/theme-options.php' );
	else
		// Implement the Theme Customizer script
		require_once( get_template_directory() . '/inc/theme-customizer.php' );
	
	/**
	 * Custom template tags for this theme.
	 */
	require_once( get_template_directory() . '/inc/template-tags.php' );
	
	/**
	 * Implement the Custom Header feature
	 */
	require_once( get_template_directory() . '/inc/custom-header.php' );
	
	/**
	 * Custom Nav Menu handler for the Navbar.
	 */
	require_once( get_template_directory() . '/inc/nav-menu-walker.php' );
	
	/**
	 * Theme Hook Alliance
	 */
	require_if_theme_supports( 'tha_hooks', get_template_directory() . '/inc/tha-theme-hooks.php' );
	
	/**
	 * Including three menu (header-menu, primary and footer-menu).
	 * Primary is wrapping in a navbar containing div (wich support responsive variation)
	 * Header-menu and Footer-menu are inside pills dropdown menu
	 * 
	 * @since	1.2.2 - 07.04.2012
	 * @see		http://codex.wordpress.org/Function_Reference/register_nav_menus
	 */
	register_nav_menus( array(
		'primary'		=>	__( 'Main Navigation', 'cabarave' ),
		'header-menu'  	=>	__( 'Header Menu', 'cabarave' ),
		'footer-menu' 	=>	__( 'Footer Menu', 'cabarave' )
	) );
        
        /*
         * Unhook and hook woocommerce themes
         */
        
        remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
        remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
        
        
	
} // cabarave_setup
endif;
add_action( 'after_setup_theme', 'cabarave_setup' );

/*
 * Wordpress SEO Integration
 * 
 * @author      Bret Holstein
 * @since       2.0.1 - 17.04.2013
 * 
 */

function cabarave_wordpress_seo() {
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<nav id="breadcrumb" class="breadcrumb">', '</nav>' );
	}    
}
add_action( 'tha_content_top', 'cabarave_wordpress_seo' );

/*
 * Woocommerce theme hooks for container.
 */
add_action('woocommerce_before_main_content', 'cabarave_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'cabarave_wrapper_end', 10);

function my_theme_wrapper_start() { ?>
  <section id="primary" class="content-area">
    <div id="content" class="site-content" role="main"> <?php
}

function my_theme_wrapper_end() { ?>
		</div><!-- #content -->
	</section><!-- #primary --> <?php
}
/**
 * Returns the options object for Cabarave.
 *
 * @author	Automattic
 * @since	1.3.0 - 06.04.2012
 *
 * @return	stdClass	Theme Options
 */
function cabarave_options() {
	return (object) wp_parse_args(
		get_option( 'cabarave_theme_options', array() ),
		cabarave_get_default_theme_options()
	);
}


/**
 * Returns the default options for Cabarave.
 *
 * @author	Automattic
 * @since	1.3.0 - 06.04.2012
 *
 * @return	void
 */
function cabarave_get_default_theme_options() {
	$default_theme_options	=	array(
		'theme_layout'		=>	'content-sidebar',
		'navbar_site_name'	=>	false,
		'navbar_searchform'	=>	true,
		'navbar_inverse'	=>	true,
		'navbar_position'	=>	'static',
	);

	return apply_filters( 'cabarave_default_theme_options', $default_theme_options );
}


/**
 * Adds Cabarave layout classes to the array of body classes.
 *
 * @author	WordPress.org
 * @since	1.3.0 - 06.04.2012
 *
 * @return	void
 */
function cabarave_layout_classes( $existing_classes ) {
	$classes = array( cabarave_options()->theme_layout );
	$classes = apply_filters( 'cabarave_layout_classes', $classes );

	return array_merge( $existing_classes, $classes );
}
add_filter( 'body_class', 'cabarave_layout_classes' );


/**
 * Adds Custom Background support
 *
 * @author	Konstantin Obenland
 * @since	1.2.5 - 11.04.2012
 *
 * @return	void
 */
function cabarave_custom_background_setup() {
	
	$args = apply_filters( 'cabarave_custom_background_args',  array(
		'default-color'	=>	'EFEFEF',
	) );
	
	add_theme_support( 'custom-background', $args );
	
	if ( ! function_exists( 'wp_get_theme' ) ) {
		// Compat: Versions of WordPress prior to 3.4.
		define( 'BACKGROUND_COLOR', $args['default-color'] );
		add_custom_background();
	}
}
add_action( 'after_setup_theme', 'cabarave_custom_background_setup' );


/**
 * Register the sidebars.
 *
 * @author	Konstantin Obenland
 * @since	1.0.0 - 05.02.2012
 *
 * @return	void
 */
function cabarave_widgets_init() {

	register_sidebar( array(
		'name'			=>	__( 'Main Sidebar', 'cabarave' ),
		'id'			=>	'main',
		'before_widget'	=>	'<aside id="%1$s" class="widget well %2$s">',
		'after_widget'	=>	'</aside>',
		'before_title'	=>	'<h2 class="widget-title">',
		'after_title'	=>	'</h2>',
	) );
	
	register_sidebar( array(
		'name'			=>	__( 'Image Sidebar', 'cabarave' ),
		'description'	=>	__( 'Shown on image attachment pages.', 'cabarave' ),
		'id'			=>	'image',
		'before_widget'	=>	'<aside id="%1$s" class="widget well %2$s">',
		'after_widget'	=>	'</aside>',
		'before_title'	=>	'<h2 class="widget-title">',
		'after_title'	=>	'</h2>',
	) );

	include_once( 'inc/cabarave-image-meta-widget.php' );
	register_widget( 'cabarave_Image_Meta_Widget' );
	
	include_once( 'inc/cabarave-gallery-widget.php' );
	register_widget( 'cabarave_Gallery_Widget' );
}
add_action( 'widgets_init', 'cabarave_widgets_init' );


/**
 * Registration of theme scripts and styles
 *
 * @author	Konstantin Obenland
 * @since	1.0.0 - 05.02.2012
 *
 * @return	void
 */
function cabarave_register_scripts_styles() {

	if ( ! is_admin() ) {
		$theme_version = _cabarave_version();
		$suffix = ( defined('SCRIPT_DEBUG') AND SCRIPT_DEBUG ) ? '' : '.min';
			
		/**
		 * Scripts
		 */
		wp_register_script(
			'tw-bootstrap',
			get_template_directory_uri() . "/js/bootstrap{$suffix}.js",
			array('jquery'),
			'2.0.3',
			true
		);
		
		wp_register_script(
			'cabarave',
			get_template_directory_uri() . "/js/cabarave{$suffix}.js",
			array('tw-bootstrap'),
			$theme_version,
			true
		);
				
		/**
		 * Styles
		 */
		wp_register_style(
			'tw-bootstrap',
			get_template_directory_uri() . "/css/bootstrap{$suffix}.css",
			array(),
			'2.0.3'
		);
		
		wp_register_style(
			'cabarave',
			get_template_directory_uri() . "/style{$suffix}.css",
			array('tw-bootstrap'),
			$theme_version
		);
	}
}
add_action( 'init', 'cabarave_register_scripts_styles' );


/**
 * Properly enqueue frontend scripts
 *
 * @author	Konstantin Obenland
 * @since	1.0.0 - 05.02.2012
 *
 * @return	void
 */
function cabarave_print_scripts() {
	wp_enqueue_script( 'cabarave' );
}
add_action( 'wp_enqueue_scripts', 'cabarave_print_scripts' );


/**
 * Adds IE specific scripts
 * 
 * Respond.js has to be loaded after Theme styles
 *
 * @author	Konstantin Obenland
 * @since	1.7.0 - 11.06.2012
 *
 * @return	void
 */
function cabarave_print_ie_scripts() {
	?>
	<!--[if lt IE 9]>
		<script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.min.js" type="text/javascript"></script>
		<script src="<?php echo get_template_directory_uri(); ?>/js/respond.min.js" type="text/javascript"></script>
	<![endif]-->
	<?php
}
add_action( 'wp_head', 'cabarave_print_ie_scripts', 11 );


/**
 * Properly enqueue comment-reply script
 *
 * @author	Konstantin Obenland
 * @since	1.4.0 - 08.05.2012
 *
 * @return	void
 */
function cabarave_comment_reply() {
	if ( get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'comment_form_before', 'cabarave_comment_reply' );


/**
 * Properly enqueue frontend styles
 *
 * Since 'tw-bootstrap' was registered as a dependency, it'll get enqueued
 * automatically
 *
 * @author	Konstantin Obenland
 * @since	1.0.0 - 05.02.2012
 *
 * @return	void
 */
function cabarave_print_styles() {
	if ( is_child_theme() ) {
		wp_enqueue_style( 'cabarave-child', get_stylesheet_uri(), array( 'cabarave' ) );
	} else {
		wp_enqueue_style( 'cabarave' );
	}
	
	if ( 'static' != cabarave_options()->navbar_position ) {
		$top_bottom	=	str_replace( 'navbar-fixed-', '', cabarave_options()->navbar_position );
		$css		=	"body > .container{margin-{$top_bottom}:68px;}@media(min-width: 980px){body > .container{margin-{$top_bottom}:58px;}}";
	
		if ( is_admin_bar_showing() AND 'top' == $top_bottom )
			$css	.=	'.navbar.navbar-fixed-top{margin-top:28px;}';
	
		if ( function_exists( 'wp_add_inline_style' ) )
			wp_add_inline_style( 'cabarave', $css );
		else
			echo "<style type='text/css'>\n{$css}\n</style>\n";
	}
}
add_action( 'wp_enqueue_scripts', 'cabarave_print_styles' );


if ( ! function_exists( 'cabarave_credits' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author,
 * comment and edit link
 *
 * @author	Konstantin Obenland
 * @since	1.2.2 - 07.04.2012
 *
 * @return	void
 */
function cabarave_credits() {
	printf(
		'<span class="credits alignleft">' . __( '&copy; %1$s <a href="%2$s">%3$s</a>, all rights reserved.', 'cabarave' ) . '</span>',
		date( 'Y' ),
		home_url( '/' ),
		get_bloginfo( 'name' )
	);
}
endif;


/**
 * Returns the blogname if no title was set.
 *
 * @author	Konstantin Obenland
 * @since	1.1.0 - 18.03.2012
 *
 * @param	string	$title
 * @param	string	$sep
 *
 * @return	string
 */
function cabarave_wp_title( $title, $sep ) {
	
	if ( ! is_feed() ) {
		$title .= get_bloginfo( 'name' );
		
		if ( is_front_page() ) {
			$title .= " {$sep} " . get_bloginfo( 'description' );
		}
	}

	return $title;
}
add_filter( 'wp_title', 'cabarave_wp_title', 1, 2 );


/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @author	WordPress.org
 * @since	1.0.0 - 05.02.2012
 *
 * @param	string	$more
 *
 * @return	string
 */
function cabarave_continue_reading_link() {
	return ' <a href="'. esc_url( get_permalink() ) . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'cabarave' ) . '</a>';
}


/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and cabarave_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @author	WordPress.org
 * @since	1.0.0 - 05.02.2012
 *
 * @param	string	$more
 *
 * @return	string
 */
function cabarave_auto_excerpt_more( $more ) {
	return '&hellip;' . cabarave_continue_reading_link();
}
add_filter( 'excerpt_more', 'cabarave_auto_excerpt_more' );


/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @author	WordPress.org
 * @since	1.0.0 - 05.02.2012
 *
 * @param	string	$output
 *
 * @return	string
 */
function cabarave_custom_excerpt_more( $output ) {
	if ( has_excerpt() AND ! is_attachment() ) {
		$output .= cabarave_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'cabarave_custom_excerpt_more' );


/**
 * Get the wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @author	WordPress.org
 * @since	1.0.0 - 05.02.2012
 *
 * @param	array	$args
 *
 * @return	array
 */
function cabarave_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'cabarave_page_menu_args' );


/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 *
 * @author	Automattic
 * @since	1.0.0 - 05.02.2012
 *
 * @param	string	$url
 * @param	int		$id
 *
 * @return	string
 */
function cabarave_enhanced_image_navigation( $url, $id ) {
    
	if ( is_attachment() AND wp_attachment_is_image( $id ) ) {
		$image = get_post( $id );
		if ( $image->post_parent AND $image->post_parent != $id )
			$url .= '#primary';
    }
    
    return $url;
}
add_filter( 'attachment_link', 'cabarave_enhanced_image_navigation', 10, 2 );


/**
 * Displays comment list, when there are any
 *
 * @author	Konstantin Obenland
 * @since	1.7.0 - 16.06.2012
 *
 * @return	void
 */
function cabarave_comments_list() {
	if ( post_password_required() ) : ?>
		<div id="comments">
			<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'cabarave' ); ?></p>
		</div><!-- #comments -->
		<?php
		return;
	endif;
	
	
	if ( have_comments() ) : ?>
		<div id="comments">
			<h2 id="comments-title">
				<?php printf( _n( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'cabarave' ),
						number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' ); ?>
			</h2>
		
			<?php cabarave_comment_nav(); ?>
		
			<ol class="commentlist unstyled">
				<?php wp_list_comments( array( 'callback' => 'cabarave_comment' ) ); ?>
			</ol><!-- .commentlist .unstyled -->
		
			<?php cabarave_comment_nav(); ?>
		
		</div><!-- #comments -->
	<?php endif;
}
add_action( 'comment_form_before', 'cabarave_comments_list', 0 );
add_action( 'comment_form_comments_closed', 'cabarave_comments_list', 1 );


/**
 * Echoes comments-are-closed message when post type supports comments and we're
 * not on a page
 *
 * @author	Konstantin Obenland
 * @since	1.7.0 - 16.06.2012
 *
 * @return	void
 */
function cabarave_comments_closed() {
	if ( ! is_page() AND post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'cabarave' ); ?></p>
	<?php endif;
}
add_action( 'comment_form_comments_closed', 'cabarave_comments_closed' );


/**
 * Filters comments_form() default arguments
 *
 * @author	Konstantin Obenland
 * @since	1.7.0 - 16.06.2012
 *
 * @param	array	$defaults
 *
 * @return	array
 */
function cabarave_comment_form_defaults( $defaults ) {
	return wp_parse_args( array(
		'comment_field'			=>	'<div class="comment-form-comment control-group"><label class="control-label" for="comment">' . _x( 'Comment', 'noun', 'cabarave' ) . '</label><div class="controls"><textarea class="span7" id="comment" name="comment" rows="8" aria-required="true"></textarea></div></div>',
		'comment_notes_before'	=>	'',
		'comment_notes_after'	=>	'<div class="form-allowed-tags control-group"><label class="control-label">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', 'cabarave' ), '</label><div class="controls"><pre>' . allowed_tags() . '</pre></div>' ) . '</div>
									 <div class="form-actions">',
		'title_reply'			=>	'<legend>' . __( 'Leave a reply', 'cabarave' ) . '</legend>',
		'title_reply_to'		=>	'<legend>' . __( 'Leave a reply to %s', 'cabarave' ). '</legend>',
		'must_log_in'			=>	'<div class="must-log-in control-group controls">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'cabarave' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( get_the_ID() ) ) ) ) . '</div>',
		'logged_in_as'			=>	'<div class="logged-in-as control-group controls">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'cabarave' ), admin_url( 'profile.php' ), wp_get_current_user()->display_name, wp_logout_url( apply_filters( 'the_permalink', get_permalink( get_the_ID() ) ) ) ) . '</div>',
	), $defaults );
}
add_filter( 'comment_form_defaults', 'cabarave_comment_form_defaults' );


if ( ! function_exists( 'cabarave_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own cabarave_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @author	Konstantin Obenland
 * @since	1.0.0 - 05.02.2012
 *
 * @param	object	$comment	Comment data object.
 * @param	array	$args
 * @param	int		$depth		Depth of comment in reference to parents.
 *
 * @return	void
 */
function cabarave_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	if ( 'pingback' == $comment->comment_type OR 'trackback' == $comment->comment_type ) : ?>
	
		<li id="li-comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
			<p class="row">
				<strong class="ping-label span1"><?php _e( 'Pingback:', 'cabarave' ); ?></strong>
				<span class="span7"><?php comment_author_link(); edit_comment_link( __( 'Edit', 'cabarave' ), '<span class="sep">&nbsp;</span><span class="edit-link label">', '</span>' ); ?></span>
			</p>
	
	<?php else:
		$offset	=	$depth - 1;
		$span	=	7 - $offset; ?>
		
		<li  id="li-comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
			<article id="comment-<?php comment_ID(); ?>" class="comment row">
				<div class="comment-author-avatar span1<?php if ($offset) echo " offset{$offset}"; ?>">
					<?php echo get_avatar( $comment, 70 ); ?>
				</div>
				<footer class="comment-meta span<?php echo $span; ?>">
					<p class="comment-author vcard">
						<?php
							/* translators: 1: comment author, 2: date and time */
							printf( __( '%1$s <span class="says">said</span> on %2$s:', 'cabarave' ),
								sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
								sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
									esc_url( get_comment_link( $comment->comment_ID ) ),
									get_comment_time( 'c' ),
									/* translators: 1: date, 2: time */
									sprintf( __( '%1$s at %2$s', 'cabarave' ), get_comment_date(), get_comment_time() )
								)
							);
							edit_comment_link( __( 'Edit', 'cabarave' ), '<span class="sep">&nbsp;</span><span class="edit-link label">', '</span>' ); ?>
					</p><!-- .comment-author .vcard -->
	
					<?php if ( ! $comment->comment_approved ) : ?>
					<div class="comment-awaiting-moderation alert alert-info"><em><?php _e( 'Your comment is awaiting moderation.', 'cabarave' ); ?></em></div>
					<?php endif; ?>
	
				</footer><!-- .comment-meta -->
	
				<div class="comment-content span<?php echo $span; ?>">
					<?php
					comment_text();
					comment_reply_link( array_merge( $args, array(
						'reply_text'	=>	__( 'Reply <span>&darr;</span>', 'cabarave' ),
						'depth'			=>	$depth,
						'max_depth'		=>	$args['max_depth']
					) ) ); ?>
				</div><!-- .comment-content -->
			</article><!-- #comment-<?php comment_ID(); ?> .comment -->
			
	<?php endif; // comment_type
}
endif; // ends check for cabarave_comment()


/**
 * Adds markup to the comment form which is needed to make it work with Bootstrap
 * needs
 *
 * @author	Konstantin Obenland
 * @since	1.0.0 - 05.02.2012
 *
 * @param	string	$html
 *
 * @return	string
 */
function cabarave_comment_form_top() {
	echo '<div class="form-horizontal">';
}
add_action( 'comment_form_top', 'cabarave_comment_form_top' );


/**
 * Adds markup to the comment form which is needed to make it work with Bootstrap
 * needs
 *
 * @author	Konstantin Obenland
 * @since	1.0.0 - 05.02.2012
 *
 * @param	string	$html
 *
 * @return	string
 */
function cabarave_comment_form() {
	echo '</div></div>';
}
add_action( 'comment_form', 'cabarave_comment_form' );


/**
 * Custom author form field for the comments form
 *
 * @author	Konstantin Obenland
 * @since	1.0.0 - 05.02.2012
 *
 * @param	string	$html
 *
 * @return	string
 */
function cabarave_comment_form_field_author( $html ) {
	$commenter	=	wp_get_current_commenter();
	$req		=	get_option( 'require_name_email' );
	$aria_req	=	( $req ? " aria-required='true'" : '' );
	
	return	'<div class="comment-form-author control-group">
				<label for="author" class="control-label">' . __( 'Name', 'cabarave' ) . '</label>
				<div class="controls">
					<input id="author" name="author" type="text" value="' . esc_attr(  $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />
					' . ( $req ? '<p class="help-inline"><span class="required">' . __('required', 'cabarave') . '</span></p>' : '' ) . '
				</div>
			</div>';
}
add_filter( 'comment_form_field_author', 'cabarave_comment_form_field_author');


/**
 * Custom HTML5 email form field for the comments form
 *
 * @author	Konstantin Obenland
 * @since	1.0.0 - 05.02.2012
 *
 * @param	string	$html
 *
 * @return	string
 */
function cabarave_comment_form_field_email( $html ) {
	$commenter	=	wp_get_current_commenter();
	$req		=	get_option( 'require_name_email' );
	$aria_req	=	( $req ? " aria-required='true'" : '' );
	
	return	'<div class="comment-form-email control-group">
				<label for="email" class="control-label">' . __( 'Email', 'cabarave' ) . '</label>
				<div class="controls">
					<input id="email" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />
					<p class="help-inline">' . ( $req ? '<span class="required">' . __('required', 'cabarave') . '</span>, ' : '' ) . __( 'will not be published', 'cabarave' ) . '</p>
				</div>
			</div>';
}
add_filter( 'comment_form_field_email', 'cabarave_comment_form_field_email');


/**
 * Custom HTML5 url form field for the comments form
 *
 * @author	Konstantin Obenland
 * @since	1.0.0 - 05.02.2012
 *
 * @param	string	$html
 *
 * @return	string
 */
function cabarave_comment_form_field_url( $html ) {
	$commenter	=	wp_get_current_commenter();
	
	return	'<div class="comment-form-url control-group">
				<label for="url" class="control-label">' . __( 'Website', 'cabarave' ) . '</label>
				<div class="controls">
					<input id="url" name="url" type="url" value="' . esc_attr(  $commenter['comment_author_url'] ) . '" size="30" />
				</div>
			</div>';
}
add_filter( 'comment_form_field_url', 'cabarave_comment_form_field_url');


/**
 * Adjusts an attechment link to hold the class of 'thumbnail' and make it look
 * pretty
 *
 * @author	Konstantin Obenland
 * @since	1.0.0 - 05.02.2012
 *
 * @param	string	$link
 * @param	int		$id			Post ID.
 * @param	string	$size		Default is 'thumbnail'. Size of image, either array or string.
 * @param	bool	$permalink	Default is false. Whether to add permalink to image.
 * @param	bool	$icon		Default is false. Whether to include icon.
 * @param	string	$text		Default is false. If string, then will be link text.
 *
 * @return	string
 */
function cabarave_get_attachment_link( $link, $id, $size, $permalink, $icon, $text ) {
	return ( ! $text ) ? str_replace( '<a ', '<a class="thumbnail" ', $link ) : $link;
}
add_filter( 'wp_get_attachment_link', 'cabarave_get_attachment_link', 10, 6 );


/**
 * Adds the 'hero-unit' class for extra big font on sticky posts
 *
 * @author	Konstantin Obenland
 * @since	1.0.0 - 05.02.2012
 *
 * @param	array	$classes
 *
 * @return	array
 */
function cabarave_post_classes( $classes ) {

	if ( is_sticky() AND is_home() ) {
		$classes[] = 'hero-unit';
	}
	
	return $classes;
}
add_filter( 'post_class', 'cabarave_post_classes' );


/**
 * Callback function to display galleries (in HTML5)
 *
 * @author	Konstantin Obenland
 * @since	1.0.0 - 05.02.2012
 *
 * @param	string	$content
 * @param	array	$attr
 *
 * @return	string
 */
function cabarave_post_gallery( $content, $attr ) {
	global $instance, $post;
	$instance++;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( ! $attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract( shortcode_atts( array(
		'order'			=>	'ASC',
		'orderby'		=>	'menu_order ID',
		'id'			=>	$post->ID,
		'itemtag'		=>	'figure',
		'icontag'		=>	'div',
		'captiontag'	=>	'figcaption',
		'columns'		=>	3,
		'size'			=>	'thumbnail',
		'include'		=>	'',
		'exclude'		=>	''
	), $attr ) );


	$id = intval( $id );
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( $include ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$_attachments = get_posts( array(
			'include'			=>	$include,
			'post_status'		=>	'inherit',
			'post_type'			=>	'attachment',
			'post_mime_type'	=>	'image',
			'order'				=>	$order,
			'orderby'			=>	$orderby
		) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( $exclude ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_children( array(
			'post_parent'		=>	$id,
			'exclude'			=>	$exclude,
			'post_status'		=>	'inherit',
			'post_type'			=>	'attachment',
			'post_mime_type'	=>	'image',
			'order'				=>	$order,
			'orderby'			=>	$orderby
		) );
	} else {
		$attachments = get_children( array(
			'post_parent'		=>	$id,
			'post_status'		=>	'inherit',
			'post_type'			=>	'attachment',
			'post_mime_type'	=>	'image',
			'order'				=>	$order,
			'orderby'			=>	$orderby
		) );
	}

	if ( empty( $attachments ) )
		return;

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link( $att_id, $size, true ) . "\n";
		return $output;
	}
	
	

	$itemtag	=	tag_escape( $itemtag );
	$captiontag	=	tag_escape( $captiontag );
	$columns	=	intval( min( array( 8, $columns ) ) );
	$float		=	(is_rtl()) ? 'right' : 'left';

	if ( 4 > $columns )
		$size = 'full';
	
	$selector	=	"gallery-{$instance}";
	$size_class	=	sanitize_html_class( $size );
	$output		=	"<ul id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class} thumbnails'>";

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		$comments = get_comments( array(
			'post_id'	=>	$id,
			'count'		=>	true,
			'type'		=>	'comment',
			'status'	=>	'approve'
		) );
		
		$link = wp_get_attachment_link( $id, $size, ! ( isset( $attr['link'] ) AND 'file' == $attr['link'] ) );
		$clear_class = ( 0 == $i++ % $columns ) ? ' clear' : '';
		$span = 'span' . floor( 8 / $columns );
		
		$output .= "<li class='{$span}{$clear_class}'><{$itemtag} class='gallery-item'>";
		$output .= "<{$icontag} class='gallery-icon'>{$link}</{$icontag}>\n";
			
		if ( $captiontag AND ( 0 < $comments OR trim( $attachment->post_excerpt ) ) ) {
			$comments	=	( 0 < $comments ) ? sprintf( _n('%d comment', '%d comments', $comments, 'cabarave'), $comments ) : '';
			$excerpt	=	wptexturize( $attachment->post_excerpt );
			$out		=	($comments AND $excerpt) ? " $excerpt <br /> $comments " : " $excerpt$comments ";
			$output		.=	"<{$captiontag} class='wp-caption-text gallery-caption'>{$out}</{$captiontag}>\n";
		}
		$output .= "</{$itemtag}></li>\n";
	}
	$output .= "</ul>\n";
	
	return $output;
}
add_filter( 'post_gallery', 'cabarave_post_gallery', 10, 2 );


/**
 * HTML 5 caption for pictures
 *
 * @author	Konstantin Obenland
 * @since	1.0.0 - 05.02.2012
 *
 * @param	string	$empty
 * @param	array	$attr
 * @param	string	$content
 *
 * @return	string
 */
function cabarave_img_caption_shortcode( $empty, $attr, $content ) {

	extract( shortcode_atts( array(
		'id'		=>	'',
		'align'		=>	'alignnone',
		'width'		=>	'',
		'caption'	=>	''
	), $attr ) );

	if ( 1 > (int) $width OR empty( $caption ) ) {
		return $content;
	}

	if ( $id ) {
		$id = 'id="' . $id . '" ';
	}

	return '<figure ' . $id . 'class="wp-caption thumbnail ' . $align . '" style="width: '.$width.'px;">
				' . do_shortcode( str_replace( 'class="thumbnail', 'class="', $content ) ) . '
				<figcaption class="wp-caption-text">' . $caption . '</figcaption>
			</figure>';
}
add_filter( 'img_caption_shortcode', 'cabarave_img_caption_shortcode', 10, 3 );


/**
 * Returns a password form which dispalys nicely with Bootstrap
 *
 * @author	Konstantin Obenland
 * @since	1.0.0 - 05.02.2012
 *
 * @param	string	$form
 *
 * @return	string	Cabarave password form
 */
function cabarave_the_password_form( $form ) {
	return '<form class="post-password-form form-horizontal" action="' . home_url( 'wp-pass.php' ) . '" method="post"><legend>'. __( 'This post is password protected. To view it please enter your password below:', 'cabarave' ) . '</legend><div class="control-group"><label class="control-label" for="post-password-' . get_the_ID() . '">' . __( 'Password:', 'cabarave' ) .'</label><div class="controls"><input name="post_password" id="post-password-' . get_the_ID() . '" type="password" size="20" /></div></div><div class="form-actions"><button type="submit" class="post-password-submit submit btn btn-primary">' . __( 'Submit', 'cabarave' ) . '</button></div></form>';
}
add_filter( 'the_password_form', 'cabarave_the_password_form' );


/**
 * Modifies the category dropdown args for widgets on 404 pages
 *
 * @author	Konstantin Obenland
 * @since	1.5.0 - 19.05.2012
 *
 * @param	array	$args
 *
 * @return	array
 */
function cabarave_widget_categories_dropdown_args( $args ) {
	if ( is_404() ) {
		$args	=	wp_parse_args( $args, array(
			'orderby'		=>	'count',
			'order'			=>	'DESC',
			'show_count'	=>	1,
			'title_li'		=>	'',
			'number'		=>	10
		) );
	}
	return $args;
}
add_filter( 'widget_categories_dropdown_args', 'cabarave_widget_categories_dropdown_args' );


/**
 * Adds the .thumbnail class when images are sent to editor
 * 
 * @author	Konstantin Obenland
 * @since	2.0.0 - 29.08.2012
 * 
 * @param	string	$html
 * @param	int		$id
 * @param	string	$caption
 * @param	string	$title
 * @param	string	$align
 * @param	string	$url
 * @param	string	$size
 * @param	string	$alt
 * 
 * @return	string	Image HTML
 */
function cabarave_image_send_to_editor( $html, $id, $caption, $title, $align, $url, $size, $alt ) {
	if ( $url ) {
		$html = str_replace( '<a ', '<a class="thumbnail" ', $html );
	} else {
		$html = str_replace( 'class="', 'class="thumbnail ', $html );
	}

	return $html;
}
add_filter( 'image_send_to_editor', 'cabarave_image_send_to_editor', 10, 8 );


/**
 * Adjusts content_width value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @author	WordPress.org
 * @since	2.0.0 - 29.08.2012
 * 
 * @return	void
 */
function cabarave_content_width() {
	if ( is_attachment() ) {
		global $content_width;
		$content_width = 940;
	}
}
add_action( 'template_redirect', 'cabarave_content_width' );


/**
 * Returns the Theme version string
 *
 * @author	Konstantin Obenland
 * @since	1.2.4 - 07.04.2012
 * @access	private
 *
 * @return	string	Cabarave version
 */
function _cabarave_version() {
	
	if ( function_exists( 'wp_get_theme' ) ) {
		$theme_version	=	wp_get_theme()->get( 'Version' );
	}
	else {
		$theme_data		=	get_theme_data( get_template_directory() . '/style.css' );
		$theme_version	=	$theme_data['Version'];
	}
	
	return $theme_version;
}


/* End of file functions.php */
/* Location: ./wp-content/themes/cabarave/functions.php */