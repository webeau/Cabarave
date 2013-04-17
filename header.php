<?php
/** header.php
 *
 * Displays all of the <head> section and everything up till </header>
 *
 * @author		Bret Holstein, Konstantin Obenland
 * @package		Cabarave
 * @since		1.0 - 05.02.2012
 */

?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
	<head>
		<?php tha_head_top(); ?>
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		
		<title><?php wp_title( '&laquo;', true, 'right' ); ?></title>
		
		<?php tha_head_bottom(); ?>
		<?php wp_head(); ?>
	</head>
	
	<body <?php body_class(); ?>>
			<div id="page" class="hfeed">
				<?php tha_header_before(); ?>
				<header id="branding" role="banner" class="span12">
					<?php tha_header_top();
					wp_nav_menu( array(
						'container'			=>	'nav',
						'container_class'	=>	'subnav clearfix',
						'theme_location'	=>	'header-menu',
						'menu_class'		=>	'nav nav-pills pull-right',
						'depth'				=>	3,
						'fallback_cb'		=>	false,
						'walker'			=>	new cabarave_Nav_Walker,
					) ); ?>
					<hgroup>
						<h1 id="site-title">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
								<span><?php bloginfo( 'name' ); ?></span>
							</a>
						</h1>
						<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
					</hgroup>
					
					<?php if ( get_header_image() ) : ?>
					<a id="header-image" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
						<img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" />
					</a>
					<?php endif; // if ( get_header_image() ) ?>

					<nav id="access" role="navigation">
						<h3 class="assistive-text"><?php _e( 'Main menu', 'cabarave' ); ?></h3>
						<div class="skip-link"><a class="assistive-text" href="#content" title="<?php esc_attr_e( 'Skip to primary content', 'cabarave' ); ?>"><?php _e( 'Skip to primary content', 'cabarave' ); ?></a></div>
						<div class="skip-link"><a class="assistive-text" href="#secondary" title="<?php esc_attr_e( 'Skip to secondary content', 'cabarave' ); ?>"><?php _e( 'Skip to secondary content', 'cabarave' ); ?></a></div>
						<?php if ( has_nav_menu( 'primary' ) OR cabarave_options()->navbar_site_name OR cabarave_options()->navbar_searchform ) : ?>
						<div <?php cabarave_navbar_class(); ?>>
							<div class="navbar-inner">
								<div class="container">
									<!-- .btn-navbar is used as the toggle for collapsed navbar content -->
									<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
									</a>
									<?php if ( cabarave_options()->navbar_site_name ) { ?>
									<span class="brand"><?php bloginfo( 'name' ); ?></span>
									<?php } elseif ( get_theme_mod( 'logo_image_position', 'in-nav' ) == 'in-nav' ) {
                                						skematik_logo();
									} ?>
									<div id="menu-right" class="pull-right"><?php
										if ( cabarave_options()->navbar_searchform ) {
											cabarave_navbar_searchform();
										} ?>

										if ( get_theme_mod( 'navbar_cart', 1 ) == 1 ) {
											skematik_cart_dropdown();
										}

										if ( get_theme_mod( 'navbar_account', 1 ) == 1 ) {
											skematik_account_dropdown();
										}
										?>
									</div>
									<div class="nav-collapse">
										<?php wp_nav_menu( array(
											'theme_location'	=>	'primary',
											'menu_class'		=>	'nav',
											'depth'				=>	3,
											'fallback_cb'		=>	false,
											'walker'			=>	new cabarave_Nav_Walker,
										) ); 
                                                                        </div>
								</div>
							</div>
						</div>
						<?php endif; ?>
					</nav><!-- #access -->
					<?php tha_header_bottom(); ?>
				</header><!-- #branding --><?php
				tha_header_after();
				

/* End of file header.php */
/* Location: ./wp-content/themes/cabarave/header.php */