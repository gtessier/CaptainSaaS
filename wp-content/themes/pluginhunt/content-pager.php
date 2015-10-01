<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		the_title( '<header class="entry-header"><h1 class="entry-title">', '</h1></header><!-- .entry-header -->' );
	?>

	<div class="entry-content">
		<div class='col-md-8 pull-left'>
		<?php
			the_content();
		?>
		</div>
	<div class='pull-right col-md-4'>
<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</div>
	<div style='clear:both'></div>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
