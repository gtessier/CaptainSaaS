<?php
/**
 * The search template. 
 */

get_header(); 


?>

<div class="container-main-head container">

<?php

if( get_theme_mod( 'use_mailchimp' ) != '') { 
$action = get_theme_mod( 'phmc_url' );
echo ph_mailchimp($action);
}else{
	echo "<div class='nomc'></div>";
}


 ?>


    </div>


<div class='container postlist'>

<div class='row'>


	<div class='col-md-12 maincontent toppad'>

<?php
	global $wp_query,$post,$wpdb, $current_user,$query_string;
    get_currentuserinfo();
	$wpdb->myo_ip   = $wpdb->prefix . 'epicred';

	$args = get_query_var('latest');

	$posttype = get_query_var('post_type');


    $day_check = '';
	if ( have_posts() ) : ?>
 			
			<?php while ( have_posts() ) : the_post();

			$day = get_the_date('j');
			$date = get_the_date('l');


			  if ($day != $day_check) {
			    if ($day_check != '') {
			    }
			    echo "<div class='timing'><span class='day'>". $date ."</span><span class='date'>" . get_the_date() . "</span></div>";
			    $day_check = $day;
			  }

			 ?> 
				
			<?php if(is_page()){
				
			}else{
			
			 $postvote = get_post_meta($post->ID, 'epicredvote' ,true);


			wpeddit_post_ranking($post->ID);

			if($postvote == NULL){
				$postvote = 0;
			}
			
			//again if IP locked set the fid variable to be the IP address.
	// if(get_option('epicred_ip') == 'yes'){
	//	$fid = "'" . $_SERVER['REMOTE_ADDR'] . "'";	
//	}else{
		$fid = $current_user->ID;
//	}
			
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
			if($num_posts > 10 && $paged > 1){
				$blob = 'hidepost hidepost-' . $d . '-'. $m. '-' . $y;
			}else{
				$blob = '';
			}

			if( get_theme_mod( 'pluginhunt_post_layout' ) == 'option2'){ 
				$span = 'col-md-3';
			}
			

            $profileUrl = '#'; if (isset($post->ID)) $profileUrl = get_author_posts_url($post->post_author); 


			global $content_width;
			$content_width = 290;  //for iframe embeds on index page

			//get the outbound URL from the post, check for embeddable, if not embeddable do something else.
			$out =  get_post_meta($post->ID, 'outbound', true);

			$em = wp_oembed_get($out, array('width'=>300));
			$thumbid = get_post_thumbnail_id( $post->ID );
			$image = wp_get_attachment_image_src( $thumbid, '3x-grid' );
			$embed = wp_get_attachment_image_src( $thumbid, 'full' );			
			 ?>
			

			
<div class = 'clickable col-md-4 <?php echo $blob;?> boxed' id='reddit-post-<?php echo $post->ID;?>' data-slug='<?php echo $post->post_name; ?>' data-id='<?php echo $post->ID; ?>' data-url = '<?php echo $url; ?>' data-format = '<?php echo get_post_format( $post->ID ); ?>' data-rajax = '<?php echo $post->ID; ?>' data-rups = '<?php echo $postvote;?>' data-pname='<?php echo $pname;?>' data-profurl="<?php echo $profileUrl; ?>">

<?php if ($em){ ?>
            <div class='thumb'>
            	<img src="<?php echo $image[0]; ?>">
	         </div>
	         <div class='embed hide'>
	         	<?php echo $em; ?>
	         </div>
<?php }else{ ?>
				<div class='thumb'><img src="<?php echo $image[0]; ?>"></div>
				<div class='embed hide'><img src="<?php echo $embed[0]; ?>"></div>
<?php } ?>
				<div class='grid-wrapper' id='reddit-post-<?php echo $post->ID;?>' data-slug='<?php echo $post->post_name; ?>' data-id='<?php echo $post->ID; ?>' data-url = '<?php echo $url; ?>' data-format = '<?php echo get_post_format( $post->ID ); ?>' data-rajax = '<?php echo $post->ID; ?>' data-rups = '<?php echo $postvote;?>' data-pname='<?php echo $pname;?>' data-profurl="<?php echo $profileUrl; ?>">
				<div class = "arrow reddit-voting reddit-voting-<?php echo $redclassu; ?> <?php echo $redclassu;?> arrow-up-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?> data-red-like = "<?php echo $redclassd; ?>" data-red-id = "<?php echo $post->ID;?>" role="button" aria-label="upvote" tabindex="0">
					<ul class="unstyled">
						<div class="fa fa-caret-up  fa-2x <?php echo $redclassu;?>-arrow arrow-up-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?> data-red-like = "<?php echo $redclassd; ?>" data-red-id = "<?php echo $post->ID;?>" role="button" aria-label="upvote" tabindex="0"></div>
						<div class="score <?php echo $redscore;?> score-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?>><?php echo $postvote; ?></div>
					</ul>
				</div>	

				<div class='post-meta-hunt post-grid-info'>
					<?php
						$out =  get_post_meta($post->ID, 'outbound', true);
						$n = parse_url($out);
					?>
					<div class='ph-grid-content'>
						<div class='title title-<?php echo $post->ID;?>'href="<?php echo $out; ?>" data-outbound ="<?php echo $out; ?>" title="<?php the_title_attribute(); ?>" target="_blank"><?php the_title(); ?></div>			
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
			
			<?php } ?>
			
			<?php endwhile; ?>

			<?php else: ?> 
				<p><?php _e('Sorry, no posts matched your criteria.','ph_theme'); ?></p> 
			<?php endif; ?>
			<div id='navigation'>
			    <div class='next-posts-link'>
	            <?php echo get_next_posts_link('More Posts'); ?>
				</div>
			</div>
			
			<?php wp_reset_query(); ?>


<?php get_footer(); ?>
