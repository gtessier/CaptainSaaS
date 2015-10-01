<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 * Lets enhance this and move the epic_reddit_index($agrs) function into this file.
 */

get_header(); 


?>
<style>
.navbar-ph-second{
	  margin-top: 15px !important;
}
</style>
<div class="container-main-head container">

<div class='container'>

<div class='row'>


	<div class='col-md-12 maincontent'>

<?php
	global $wp_query,$post,$wpdb, $current_user,$query_string;
    get_currentuserinfo();
	$wpdb->myo_ip   = $wpdb->prefix . 'epicred';

	$args = get_query_var('latest');

	$posttype = get_query_var('post_type');


    
	if ( have_posts() ) : ?>
 			
			<?php while ( have_posts() ) : the_post();

			$day = get_the_date('j');
			$date = get_the_date('l');
			
			$stamp = get_the_date('U');
			  if ($stamp >= strtotime("today"))
	        		$date = __("Today",'ph_theme');
	    		else if ($stamp >= strtotime("yesterday"))
	        		 $date = __('Yesterday','ph_theme');

			 ?> 
				
			<?php if(is_page()){
				
			}else{
			
			$postvote = get_post_meta($post->ID, 'epicredvote' ,true);
			wpeddit_post_ranking($post->ID);

			if($postvote == NULL){
				$postvote = 0;
			}
			
			$fid = $current_user->ID;
	
			$query = "SELECT epicred_option FROM $wpdb->myo_ip WHERE epicred_ip = $fid AND epicred_id = $post->ID";
			$al = $wpdb->get_var($query);
			if($al == NULL){
				$al = 0;
			}
			if($al == 1){
				$redclassu = 'upmod';
				$redclassd = 'down';
				$redscore = 'likes';
				$voted = 'yesvote';
			}elseif($al == -1){
				$redclassd = 'downmod';
				$redclassu = 'up';
				$redscore = "dislikes";
				$voted = "yesvote";
			}else{
				$redclassu = "up";
				$redclassd = "down";
				$redscore = "unvoted";
				$voted = "novote";
			}

						if(is_mobile()){
									$mob = '-mob';
								}
		
global $content_width;
			$content_width = 800;  //for iframe embeds on index page

			//get the outbound URL from the post, check for embeddable, if not embeddable do something else.
			$out =  get_post_meta($post->ID, 'outbound', true);
			$em = wp_oembed_get($out, array('width'=>800));
			 ?>

			<div class = 'col-md-12 <?php echo $blob;?>'>

<?php if ($em){ ?>
            <div class='thumb-single'>
			<?php echo $em; ?>
	         </div>
<?php }else{
$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), '3x-grid' );
 ?>
				<div class='thumb'><img src="<?php echo $image[0]; ?>"></div>
<?php } ?>
				<div class='grid-wrapper grid-wrapper-sin reddit-post<?php echo $mob; ?>' id='reddit-post-<?php echo $post->ID;?>' data-slug='<?php echo $post->post_name; ?>' data-id='<?php echo $post->ID; ?>' data-url = '<?php echo $url; ?>' data-format = '<?php echo get_post_format( $post->ID ); ?>' data-rajax = '<?php echo $post->ID; ?>' data-rups = '<?php echo $postvote;?>' data-pname='<?php echo $pname;?>' data-profurl="<?php echo $profileUrl; ?>">
				<div class = "reddit-voting reddit-voting-<?php echo $redclassu; ?>">
					<ul class="unstyled">
						<div class="arrow fa fa-caret-up  fa-2x <?php echo $redclassu;?> arrow-up-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?> data-red-like = "<?php echo $redclassd; ?>" data-red-id = "<?php echo $post->ID;?>" role="button" aria-label="upvote" tabindex="0"></div>
						<div class="score <?php echo $redscore;?> score-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?>><?php echo $postvote; ?></div>
					</ul>
				</div>	

				<div class='post-meta-hunt post-grid-info'>
					<?php
						$out =  get_post_meta($post->ID, 'outbound', true);
						$n = parse_url($out);
					?>
					<div class='ph-grid-content'>
						<a class='title title-<?php echo $post->ID;?>'href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" target="_blank"><?php the_title(); ?></a>			
						<span class='description'>
							<?php
								$s = substr(get_the_excerpt(), 0, (60 - 3));
								$s = preg_replace('/ [^ ]*$/', '...', $s);
								echo $s;
							?>
						</span>
					</div>
			
					<div class='ph-grid-meta'>
						<div class='author-ava'>
							<span class='author-tool' data-toggle="tooltip" data-placement="left" title="<?php echo get_the_author_meta('user_nicename'); ?>">  <?php echo get_avatar( get_the_author_meta( 'ID' ), 40 ); ?></span>
						</div>
						<div class='comment-icon'>
							<i class="fa fa-comment"></i> <span class='hunt-comm-count'><?php $comments = get_comments('post_id=' . $post->ID); echo count($comments); ?></span>
						</div>
					</div>
					</div>

				</div>


			</div>

			
				<div style="clear:both"></div>
			
				<div class = 'span12'>
					<?php comments_template(); ?>
				</div>
			
			</div>
			
			<?php } ?>
			
			<?php endwhile; ?>

			<?php else: ?> 
				<p><?php _e('Sorry, no posts matched your criteria.','ph_theme'); ?></p> 
			<?php endif; ?>
	
		    <div class='next-posts-link'>
            <?php echo get_next_posts_link('More Posts'); ?>
			</div>
			
			<?php wp_reset_query(); ?>


<?php get_footer(); ?>
