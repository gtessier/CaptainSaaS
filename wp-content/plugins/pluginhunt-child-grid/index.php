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

<div class="container-main-head container  side-collapse-container">

<?php

if( get_theme_mod( 'use_mailchimp' ) != '') { 
$action = get_theme_mod( 'phmc_url' );
echo ph_mailchimp($action);
}else{
	echo "<div class='nomc'></div>";
}

 wp_reset_query();



 ?>



<div class='container postlist'>
	<div class='maincontent'>

<?php
	global $wp_query,$post,$wpdb, $current_user,$query_string;
    get_currentuserinfo();
	$wpdb->myo_ip   = $wpdb->prefix . 'epicred';

	#} check that our paged variable is being passed
	 $paged = ($_GET["page"]) ? $_GET["page"] : 0;


	#} first query for 20 posts and get the post_date of the last post
	if($paged == 0){
		$query = "SELECT post_date, YEAR(post_date) AS `year`, MONTH(post_date) AS `month`,
	        DAYOFMONTH(post_date) AS `dayofmonth` FROM $wpdb->posts WHERE post_status='publish' AND post_type = 'post' ORDER BY post_date DESC LIMIT 10";
		$first = $wpdb->get_results($query);
		$c =  count($first) - 1;
		$f =  $first[$c]->post_date;
		$tf = substr($f,0,10);
		$date = date('U');
		$d =  $first[$c]->dayofmonth;
		$m =  $first[$c]->month;
		$y =  $first[$c]->year;
		$key = '';
		#} extra check - does the day, month and year match for the first and last post from the 10. If so, then get all posts from that day, month and year
		if($d == $first[0]->dayofmonth && $m == $first[0]->month && $y == $first[0]->year){
		$querystr = "
		    SELECT $wpdb->posts.*, YEAR(post_date) AS `year`,
	        MONTH(post_date) AS `month`,
	        DAYOFMONTH(post_date) AS `dayofmonth`
		    FROM $wpdb->posts, $wpdb->postmeta
		    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
		    AND $wpdb->postmeta.meta_key = 'epicredvote' 
		    AND $wpdb->posts.post_status = 'publish' 
		    AND $wpdb->posts.post_type = 'post'
		    AND DAYOFMONTH(post_date) ='$d'
		    AND MONTH(post_date) = '$m'
		    AND YEAR(post_date) = '$y'
		    GROUP BY ID
		    ORDER BY LEFT($wpdb->posts.post_date, 10) DESC, $wpdb->postmeta.meta_value+0 DESC
		 ";
		}else{
		#} build our first query of posts minimum 20 + the full day in which the 20th post is taken
		$querystr = "
		    SELECT $wpdb->posts.*, YEAR(post_date) AS `year`,
	        MONTH(post_date) AS `month`,
	        DAYOFMONTH(post_date) AS `dayofmonth`
		    FROM $wpdb->posts, $wpdb->postmeta
		    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
		    AND $wpdb->postmeta.meta_key = 'epicredvote' 
		    AND $wpdb->posts.post_status = 'publish' 
		    AND $wpdb->posts.post_type = 'post'
		    AND post_date >= '$tf'
		    GROUP BY ID
		    ORDER BY LEFT($wpdb->posts.post_date, 10) DESC, $wpdb->postmeta.meta_value+0 DESC
		 ";
		}
		 $pageposts = $wpdb->get_results($querystr, OBJECT);


	}else if($paged == 1){
		$d = $_GET['day'];
		$m = $_GET['month'];
		$y = $_GET['year'];
		$key = $_GET['key'];

        $query = "SELECT YEAR(post_date) AS `year`,
                  MONTH(post_date) AS `month`,
                  DAYOFMONTH(post_date) AS `dayofmonth`,
                  count(ID) as posts
                  FROM $wpdb->posts
                  WHERE post_type = 'post'
                  AND post_status = 'publish'
                  GROUP BY YEAR(post_date),
                  MONTH(post_date),
                  DAYOFMONTH(post_date)
                  ORDER BY post_date DESC";
 
        $arcresults = $wpdb->get_results($query);    //this gets the posts grouped by year, month, dayofmonth



		$key = findPrevious($y, $m, $d, $arcresults);

		echo "<div id='epic-key' class='hideme'>" . $key . "</div>";

		$d = $arcresults[$key]->dayofmonth;
		$m = $arcresults[$key]->month;
		$y = $arcresults[$key]->year;


		$querystr = "
		    SELECT $wpdb->posts.*, YEAR(post_date) AS `year`,
	        MONTH(post_date) AS `month`,
	        DAYOFMONTH(post_date) AS `dayofmonth`
		    FROM $wpdb->posts, $wpdb->postmeta
		    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
		    AND $wpdb->postmeta.meta_key = 'epicredvote' 
		    AND $wpdb->posts.post_status = 'publish' 
		    AND $wpdb->posts.post_type = 'post'
		    AND DAYOFMONTH(post_date) ='$d'
		    AND MONTH(post_date) = '$m'
		    AND YEAR(post_date) = '$y'
		    GROUP BY (ID)
		    ORDER BY LEFT($wpdb->posts.post_date, 10) DESC, $wpdb->postmeta.meta_value+0 DESC
		 ";


		 $pageposts = $wpdb->get_results($querystr, OBJECT);


	}else{
        $query = "SELECT YEAR(post_date) AS `year`,
                  MONTH(post_date) AS `month`,
                  DAYOFMONTH(post_date) AS `dayofmonth`,
                  count(ID) as posts
                  FROM $wpdb->posts ".$join."
                  WHERE post_type = 'post'
                  AND post_status = 'publish'
                  GROUP BY YEAR(post_date),
                  MONTH(post_date),
                  DAYOFMONTH(post_date)
                  ORDER BY post_date DESC";
 
        $arcresults = $wpdb->get_results($query);  //this gets the posts grouped by year, month, dayofmonth
		$key = $_GET['key'];

		$d = $arcresults[$key]->dayofmonth;
		$m = $arcresults[$key]->month;
		$y = $arcresults[$key]->year;

		$querystr = "
		    SELECT $wpdb->posts.*, YEAR(post_date) AS `year`,
	        MONTH(post_date) AS `month`,
	        DAYOFMONTH(post_date) AS `dayofmonth`
		    FROM $wpdb->posts, $wpdb->postmeta
		    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
		    AND $wpdb->postmeta.meta_key = 'epicredvote' 
		    AND $wpdb->posts.post_status = 'publish' 
		    AND $wpdb->posts.post_type = 'post'
		    AND DAYOFMONTH(post_date) ='$d'
		    AND MONTH(post_date) = '$m'
		    AND YEAR(post_date) = '$y'
		    GROUP BY (ID)
		    ORDER BY LEFT($wpdb->posts.post_date, 10) DESC, $wpdb->postmeta.meta_value+0 DESC
		 ";
		 $pageposts = $wpdb->get_results($querystr, OBJECT);
	}

    $hide = 1;
    $day_check = '';
    $num_posts = 0;


if ($pageposts): ?>
 <?php global $post; ?>
 <?php foreach ($pageposts as $post): ?>
 <?php setup_postdata($post); 

			$num_posts++;
			$day = get_the_date('j');
			$date = get_the_date('l');
			
			$stamp = get_the_date('U');
			  if ($stamp >= strtotime("today"))
	        		$date = __("Today",'ph_theme');
	    		else if ($stamp >= strtotime("yesterday"))
	        		 $date = __('Yesterday','ph_theme');


			  if ($day != $day_check) {
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
				$redclassd = 'up';
				$redclassu = 'up';
				$redscore = "unvoted";
				$voted = "yesvote";
			}else{
				$redclassu = "up";
				$redclassd = "up";
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
			
			<?php } 


			?>
			
 <?php endforeach; ?>
 <?php else : ?>

 <div id="epic_page_end_2">No more <?php echo get_theme_mod('ph_keyword_p', 'plugins');?>...</div>


 <?php endif; ?>

 			<?php if($num_posts > 10 && $paged >1){ 
 			$more = $num_posts - 10;
 			echo "<div class='unhide show-hidden-posts'><span class='showmore' data-d=$d data-m=$m data-y=$y><i class='hp fa fa-chevron-down'></i> ";
 			$text = sprintf( _n( 'Show 1 more ' . get_theme_mod('ph_keyword_s', 'plugin'), 'Show %s more ' . get_theme_mod('ph_keyword_p', 'plugins'), $more, 'ph_theme' ), $more );
			echo $text;
 			echo "</span></div>";

 			 } ?>



			<div class='container'>
			<div id='navigation'>
			    <div class='next-posts-link'>
					<a href="<?php echo esc_url( home_url( '/' ) )?>?day=<?php echo $d;?>&month=<?php echo $m;?>&year=<?php echo $y;?>">next</a>
				</div>
			</div>
			</div>

			<div id="results"></div>
			<div id = "error"></div>


			<div class='hide'>
				<?php wp_link_pages(); ?>
			</div>

			

		</div>

	</div>
			
			<?php wp_reset_query(); ?>






<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>

<?php get_footer(); ?>
