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
function cabarave_customize_register( $wp_customize ) {      
	$wp_customize->get_setting( 'blogname' )->transport	= 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	
	$wp_customize->add_section( 'cabarave_theme_layout', array(
		'title'		=>	__( 'Layout', 'cabarave' ),
		'priority'	=>	99,
	) );
	$wp_customize->add_section( 'cabarave_navbar_options', array(
			'title'		=>	__( 'Navbar Options', 'cabarave' ),
			'priority'	=>	101,
	) );
	
	// Add settings
	foreach ( array_keys( cabarave_get_default_theme_options() ) as $setting ) {
		$wp_customize->add_setting( "cabarave_theme_options[{$setting}]", array(
			'default'		=>	cabarave_options()->$setting,
			'type'			=>	'option',
			'transport'		=>	'postMessage',
		) );
	}
        
        // Navbar Width
	$wp_customize->add_setting( 'navbar_width', array(
                'default'       => 'full-width',
	) );
          
	// Theme Layout
	$wp_customize->add_control( 'cabarave_theme_layout', array(
		'label'		=>	__( 'Default Layout', 'cabarave' ),
		'section'	=>	'cabarave_theme_layout',
		'settings'	=>	'cabarave_theme_options[theme_layout]',
		'type'		=>	'radio',
		'choices'	=>	array(
			'content-sidebar'	=>	__( 'Content on left', 'cabarave' ),
			'sidebar-content'	=>	__( 'Content on right', 'cabarave' )
		),
	) );
	
	// Sitename in Navbar
	$wp_customize->add_control( 'cabarave_navbar_site_name', array(
		'label'		=>	__( 'Add site name to navigation bar.', 'cabarave' ),
		'section'	=>	'cabarave_navbar_options',
		'settings'	=>	'cabarave_theme_options[navbar_site_name]',
		'type'		=>	'checkbox',
	) );
	
	// Searchform in Navbar
	$wp_customize->add_control( 'cabarave_navbar_searchform', array(
		'label'		=>	__( 'Add searchform to navigation bar.', 'cabarave' ),
		'section'	=>	'cabarave_navbar_options',
		'settings'	=>	'cabarave_theme_options[navbar_searchform]',
		'type'		=>	'checkbox',
	) );
        
	// Navbar Colors
	$wp_customize->add_control( 'cabarave_navbar_inverse', array(
		'label'		=>	__( 'Use inverse color on navigation bar.', 'cabarave' ),
		'section'	=>	'cabarave_navbar_options',
		'settings'	=>	'cabarave_theme_options[navbar_inverse]',
		'type'		=>	'checkbox',
	) );
	
	// Navbar Position
	$wp_customize->add_control( 'cabarave_navbar_position', array(
		'label'		=>	__( 'Navigation Bar Position', 'cabarave' ),
		'section'	=>	'cabarave_navbar_options',
		'settings'	=>	'cabarave_theme_options[navbar_position]',
		'type'		=>	'radio',
		'choices'	=>	array(
			'static'				=>	__( 'Static.', 'cabarave' ),
			'navbar-fixed-top'		=>	__( 'Fixed on top.', 'cabarave' ),
			'navbar-fixed-bottom'	=>	__( 'Fixed at bottom.', 'cabarave' ),
		),
	) );
		
	$wp_customize->add_control( 'navbar_width', array(
		'label'         => 'Navigation Bar Width:',
		'section'       => 'container_settings',
		'type'          => 'select',
		'priority'      => 10,
		'choices'    => array(
                        'full-width' => null,
			1200 => '1200px',
			980 => '980px',
		),
	) );
}
add_action( 'customize_register', 'cabarave_customize_register' );


function cabarave_customizer() { 
        class Cabarave_Customize_Textarea_Control extends WP_Customize_Control {
            
            public $type = 'textarea';
 
            public function render_content() {
                ?>
                <label>
                    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                    <textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
                </label>
                <?php
            }
        } 
}
add_action( 'customize_register', 'cabarave_customizer');

/**
 * Adds controls to change settings instantly
 *
 * @author	Konstantin Obenland
 * @since	1.4.0 - 05.05.2012
 *
 * @return	void
 */
function cabarave_customize_enqueue_scripts() {
	wp_enqueue_script( 'cabarave-customize', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), _cabarave_version(), true );
	wp_localize_script( 'cabarave-customize', 'cabarave_customize', array(
		'sitename'		=>	get_bloginfo( 'name', 'display' ),
		'searchform'	=>	cabarave_navbar_searchform( false )
	) );
}
add_action( 'customize_preview_init', 'cabarave_customize_enqueue_scripts' );


/* End of file theme-customizer.php */
/* Location: ./wp-content/themes/cabarave/inc/theme-customizer.php */