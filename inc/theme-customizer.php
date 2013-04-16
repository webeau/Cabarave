<?php
/** theme-customizer.php
 * 
 * Implementation of the Theme Customizer for Themes
 * @link		http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
 * 
 * @author		Konstantin Obenland
 * @package		Cabarave
 * @since		1.4.0 - 05.05.2012
 */


/**
 * Registers the theme setting controls with the Theme Customizer
 * 
 * @author	Konstantin Obenland
 * @since	1.4.0 - 05.05.2012
 * 
 * @param	WP_Customize	$wp_customize
 * 
 * @return	void
 */
function the_bootstrap_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport	= 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	
	$wp_customize->add_section( 'the_bootstrap_theme_layout', array(
		'title'		=>	__( 'Layout', 'cabarave' ),
		'priority'	=>	99,
	) );
	$wp_customize->add_section( 'the_bootstrap_navbar_options', array(
			'title'		=>	__( 'Navbar Options', 'cabarave' ),
			'priority'	=>	101,
	) );
	
	// Add settings
	foreach ( array_keys( the_bootstrap_get_default_theme_options() ) as $setting ) {
		$wp_customize->add_setting( "the_bootstrap_theme_options[{$setting}]", array(
			'default'		=>	the_bootstrap_options()->$setting,
			'type'			=>	'option',
			'transport'		=>	'postMessage',
		) );
	}
	
	// Theme Layout
	$wp_customize->add_control( 'the_bootstrap_theme_layout', array(
		'label'		=>	__( 'Default Layout', 'cabarave' ),
		'section'	=>	'the_bootstrap_theme_layout',
		'settings'	=>	'the_bootstrap_theme_options[theme_layout]',
		'type'		=>	'radio',
		'choices'	=>	array(
			'content-sidebar'	=>	__( 'Content on left', 'cabarave' ),
			'sidebar-content'	=>	__( 'Content on right', 'cabarave' )
		),
	) );
	
	// Sitename in Navbar
	$wp_customize->add_control( 'the_bootstrap_navbar_site_name', array(
		'label'		=>	__( 'Add site name to navigation bar.', 'cabarave' ),
		'section'	=>	'the_bootstrap_navbar_options',
		'settings'	=>	'the_bootstrap_theme_options[navbar_site_name]',
		'type'		=>	'checkbox',
	) );
	
	// Searchform in Navbar
	$wp_customize->add_control( 'the_bootstrap_navbar_searchform', array(
		'label'		=>	__( 'Add searchform to navigation bar.', 'cabarave' ),
		'section'	=>	'the_bootstrap_navbar_options',
		'settings'	=>	'the_bootstrap_theme_options[navbar_searchform]',
		'type'		=>	'checkbox',
	) );
	
	// Navbar Colors
	$wp_customize->add_control( 'the_bootstrap_navbar_inverse', array(
		'label'		=>	__( 'Use inverse color on navigation bar.', 'cabarave' ),
		'section'	=>	'the_bootstrap_navbar_options',
		'settings'	=>	'the_bootstrap_theme_options[navbar_inverse]',
		'type'		=>	'checkbox',
	) );
	
	// Navbar Position
	$wp_customize->add_control( 'the_bootstrap_navbar_position', array(
		'label'		=>	__( 'Navigation Bar Position', 'cabarave' ),
		'section'	=>	'the_bootstrap_navbar_options',
		'settings'	=>	'the_bootstrap_theme_options[navbar_position]',
		'type'		=>	'radio',
		'choices'	=>	array(
			'static'				=>	__( 'Static.', 'cabarave' ),
			'navbar-fixed-top'		=>	__( 'Fixed on top.', 'cabarave' ),
			'navbar-fixed-bottom'	=>	__( 'Fixed at bottom.', 'cabarave' ),
		),
	) );
}
add_action( 'customize_register', 'the_bootstrap_customize_register' );


/**
 * Adds controls to change settings instantly
 *
 * @author	Konstantin Obenland
 * @since	1.4.0 - 05.05.2012
 *
 * @return	void
 */
function the_bootstrap_customize_enqueue_scripts() {
	wp_enqueue_script( 'cabarave-customize', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), _the_bootstrap_version(), true );
	wp_localize_script( 'cabarave-customize', 'the_bootstrap_customize', array(
		'sitename'		=>	get_bloginfo( 'name', 'display' ),
		'searchform'	=>	the_bootstrap_navbar_searchform( false )
	) );
}
add_action( 'customize_preview_init', 'the_bootstrap_customize_enqueue_scripts' );


/* End of file theme-customizer.php */
/* Location: ./wp-content/themes/cabarave/inc/theme-customizer.php */