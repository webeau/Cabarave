<?php
/** content-gallery.php
 *
 * The template for displaying posts in the Gallery Post Format on index and archive pages
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
	
	<header class="page-header">
		<hgroup>
			<?php the_title( '<h2 class="entry-title"><a href="' . get_permalink() .'" title="' . sprintf( esc_attr__( 'Permalink to %s', 'cabarave' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark">', '</a></h2>' ); ?>
			<h3 class="entry-format"><?php echo get_post_format_string(get_post_format()); ?></h3>
		</hgroup>

		<div class="entry-meta">
			<?php cabarave_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	
	<div class="entry-content row">
		<?php
		$cabarave_images = get_children( array(
			'post_parent'		=>	$post->ID,
			'post_type'			=>	'attachment',
			'post_mime_type'	=>	'image',
			'orderby'			=>	'menu_order',
			'order'				=>	'ASC',
			'numberposts'		=>	999
		) );
		if ( $cabarave_images ) :
			$cabarave_total_images	=	count( $cabarave_images );
			$cabarave_images		=	array_slice( $cabarave_images, 0, 10 );
		?>
		
		<div class="span3">
			<?php the_excerpt(); ?>
	
			<p class="gallery-meta">
				<em>
				<?php
				printf(
					_n( 'This gallery contains <strong>%1$s photo</strong>.', 'This gallery contains <strong>%1$s photos</strong>.', $cabarave_total_images, 'cabarave' ),
					number_format_i18n( $cabarave_total_images )
				); ?>
				</em>
			</p>
		</div>
		<div id="gallery-slider" class="carousel slide span5">

			<!-- Carousel items -->
			<div class="carousel-inner">
				<?php foreach ( $cabarave_images as $cabarave_image ) : ?>
				<figure class="item">
					<?php echo wp_get_attachment_image( $cabarave_image->ID, array( 470, 353 ) ); 
					if ( has_excerpt( $cabarave_image->ID ) ) :?>
					<figcaption class="carousel-caption">
						<h4><?php echo get_the_title( $cabarave_image->ID ); ?></h4>
						<p><?php echo apply_filters( 'get_the_excerpt', $cabarave_image->post_excerpt ); ?></p>
					</figcaption>
					<?php endif; ?>
				</figure>
				<?php endforeach; ?>
			</div>
		
			<!-- Carousel nav -->
			<a class="carousel-control left" href="#gallery-slider" data-slide="prev"><?php _ex( '&lsaquo;', 'carousel-control', 'cabarave' ); ?></a>
			<a class="carousel-control right" href="#gallery-slider" data-slide="next"><?php _ex( '&rsaquo;', 'carousel-control', 'cabarave' ); ?></a>
		</div><!-- #gallery-slider -->
				
		<?php endif; /* if images */ ?>
	</div><!-- .entry-content -->
	
	<?php tha_entry_bottom(); ?>
</article><!-- #post-<?php the_ID(); ?> -->
<?php tha_entry_after();


/* End of file content-gallery.php */
/* Location: ./wp-content/themes/cabarave/partials/content-gallery.php */