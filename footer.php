<?php
/** footer.php
 *
 * @author		Konstantin Obenland
 * @package		Cabarave
 * @since		1.0.0	- 05.02.2012
 */

				tha_footer_before(); ?>
				<footer id="colophon" role="contentinfo" class="span12">
					<?php tha_footer_top(); ?>
					<div id="page-footer" class="well clearfix">
						<?php wp_nav_menu( array(
							'container'			=>	'nav',
							'container_class'	=>	'subnav',
							'theme_location'	=>	'footer-menu',
							'menu_class'		=>	'credits nav nav-pills pull-left',
							'depth'				=>	3,
							'fallback_cb'		=>	'cabarave_credits',
							'walker'			=>	new cabarave_Nav_Walker,
						) );
						?>
						<div id="site-generator"<?php echo has_nav_menu('footer-menu') ? ' class="footer-nav-menu"' : ''; ?>>
							<a	href="<?php echo esc_url( __( 'http://wordpress.org/', 'cabarave' ) ); ?>"
								title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'cabarave' ); ?>"
								target="_blank"
								rel="generator"><?php printf( _x( 'Proudly powered by %s', 'WordPress', 'cabarave' ), 'WordPress' ); ?></a>
						</div>
					</div><!-- #page-footer .well .clearfix -->
					<?php tha_footer_bottom(); ?>
				</footer><!-- #colophon -->
				<?php tha_footer_after(); ?>
			</div><!-- #page -->
	<!-- <?php printf( __( '%d queries. %s seconds.', 'cabarave' ), get_num_queries(), timer_stop(0, 3) ); ?> -->
	<?php wp_footer(); ?>
	</body>
</html>
<?php


/* End of file footer.php */
/* Location: ./wp-content/themes/cabarave/footer.php */