<?php
/** content-image.php
 *
 * The template for displaying posts in the Image Post Format on index and archive pages
 *
 * Learn more: http://codex.wordpress.org/Post_Formats
 *
 * @author		Konstantin Obenland
 * @package		Cabarave
 * @since		1.0.0 - 07.02.2012
 */


tha_entry_before(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php tha_entry_top(); ?>
	
	<div class="entry-content clearfix">
		<?php
		the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'cabarave' ) );
		the_bootstrap_link_pages(); ?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php the_bootstrap_posted_on(); ?>
	</footer><!-- .entry-footer -->
	
	<?php tha_entry_bottom(); ?>
</article><!-- #post-<?php the_ID(); ?> -->
<?php tha_entry_after();


/* End of file content-image.php */
/* Location: ./wp-content/themes/cabarave/partials/content-image.php */