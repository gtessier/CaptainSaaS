<?php
get_header(); 

	global $wp_query,$post,$wpdb, $current_user,$query_string;
    get_currentuserinfo();
	$wpdb->myo_ip   = $wpdb->prefix . 'epicred';
    
	if ( have_posts() ) : ?>
 			
			<?php while ( have_posts() ) : the_post();
			
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
				$redclassu = 'up';
				$redscore = "unvoted";
				$c = "";
			}else if($al==1){
				$redclassu = 'upmod';
				$redclassd = 'down';
				$redscore = 'likes';
				$voted = 'yesvote';
				$c = 'blue';
			}else{
				$redclassu = 'upmod';
				$redclassd = 'down';
				$redscore = 'likes';
			}
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
			$out =  get_post_meta($post->ID, 'outbound', true);
			$n = parse_url($out);
			?>

<div class="ph-user-message">
	<i class="fa fa-bell faa-ring animated"></i> 
	<span class='ph-user-message-text'></span>
	<span class='ph-user-close'>x</span>
</div>

<div id='phsf'>
	<div class='row'>
			 <div class='post-header' style="background-image: url('<?php echo $image[0]; ?>')">
			 	<div class='post-header-shadow'>
				 	<div class='container-title'>
				 		<span class='post-title-single'><a class='title-link title-link-html-<?php echo $post->ID;?>' href="<?php echo esc_url($out); ?>" title="<?php the_title_attribute(); ?>" target="_blank" rel="nofollow"><?php the_title(); ?></a></span>
				 		<div class='post-description-short'><?php echo wp_trim_words( get_the_content(), 40, '...' ); ?></div>
				 		<span class='post-description-single hide'><?php the_content(); ?></span>
				 		<div class='reddit-wrapper' style="width:200px">
						 	<div class = 'reddit-voting <?php echo $c; ?>'>
								<ul class="unstyled">
									<div class="arrow fa fa-caret-up  fa-2x <?php echo $c;?> arrow-up-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?> data-red-like = "up" data-red-id = "<?php echo $post->ID;?>" role="button" aria-label="upvote" tabindex="0"></div>
									<div class="score score-<?php echo $post->ID;?>" data-red-current = <?php echo $al;?>><?php echo $postvote; ?></div>
								</ul>
							</div>	
							<a class='get-it' href="<?php echo esc_url($out); ?>" title="<?php the_title_attribute(); ?>" target="_blank" rel="nofollow">GET IT</a>
				 		</div>
				 		<div class="post-detail--header--buttons">
				 			<?php

							$categories = get_the_category($post->ID);
							foreach($categories as $category) {
								$cat_id = $category->cat_ID;
								$cat_name = $category->cat_name;
								if ($cat_id != 1) {
									echo '<a class="post-detail--header--button" href="' . esc_url( get_category_link( $category->term_id ) ) . '">'.$cat_name.'</a>';
								}
							}

				 			?>
				 			
				 			<a class="ph-collect-single post-detail--header--button" href="#" title="Save to your collections" data-pid ='<?php echo $post->ID;?>'>
				 				<span>
				 					<span><svg class='single-collect' width="15" height="14" viewBox="0 0 15 14" xmlns="http://www.w3.org/2000/svg"><path d="M13 10V8.99c0-.54-.448-.99-1-.99-.556 0-1 .444-1 .99V10H9.99c-.54 0-.99.448-.99 1 0 .556.444 1 .99 1H11v1.01c0 .54.448.99 1 .99.556 0 1-.444 1-.99V12h1.01c.54 0 .99-.448.99-1 0-.556-.444-1-.99-1H13zM0 1c0-.552.447-1 .998-1h11.004c.55 0 .998.444.998 1 0 .552-.447 1-.998 1H.998C.448 2 0 1.556 0 1zm0 5c0-.552.447-1 .998-1h11.004c.55 0 .998.444.998 1 0 .552-.447 1-.998 1H.998C.448 7 0 6.556 0 6zm0 5c0-.552.453-1 .997-1h6.006c.55 0 .997.444.997 1 0 .552-.453 1-.997 1H.997C.447 12 0 11.556 0 11z" fill="#C8C0B1" fill-rule="evenodd"></path></svg></span>
				 					<span class='save-collect'>save</span></span></a></div>

				 	</div>
			 	</div>



			 </div>

		<div class = "row post-detail">
			 <div class='full-width-ph'>
			 	<section class="col-md-8 ph-lhs">

			 	<?php if(!is_user_logged_in()){ ?>
			 		<div class='sign-up-cta'>
			 			<h3 class="section--heading"><?php echo get_theme_mod('cta1','Plugin Hunt surfaces the best new WordPress plugins, every day.'); ?></h3>
			 			<h4><?php echo get_theme_mod("cta2","It's a place for WordPress lovers to share great WordPress plugins for sale on CodeCanyon."); ?></h4>
			 		<hr>
			 		  <div class='ph_socials'>
			 		  	<div class='ph-join'>
                           <div class='ph-soc-block'>
                           	<ul class='ps-main'>
                            <li class='tw ph-sm'><a href="<?php echo wp_login_url(); ?>?loginTwitter=1&redirect=<?php echo $surl;?>" onclick="window.location = '<?php echo wp_login_url(); ?>?loginTwitter=1&redirect='+window.location.href; return false;">
                            <i class="fa fa-twitter"></i><?php _e('Log in to vote','ph_theme'); ?></a></li>

                            <li class='fb ph-sm'><a href="<?php echo wp_login_url(); ?>?loginFacebook=1&redirect=<?php echo $surl;?>" onclick="window.location = '<?php echo wp_login_url(); ?>?loginFacebook=1&redirect='+window.location.href; return false;">
                            <i class="fa fa-facebook"></i><?php _e('Log in to vote','ph_theme'); ?></a></li>
                        </ul>
                           </div>
                       </div>
                      </div>
                      <div style="clear:both"></div>
					</div>  <!-- end sign up CTA -->
					<?php } ?>					
					<div class='section section-media'>

						<div class='ph-media-items'>
						<h3 class='h3'><?php _e('media','ph_theme'); ?></h3>
						<div class="carousel--controls">
					    <?php
					    $media = get_post_meta($post->ID,'phmedia',true);

					     if(current_user_can( 'upload_files' ) && $media !=''){ ?> 
							<a class="carousel--controls--button v-add" title="Add content" data-pid="<?php echo $post->ID; ?>">
								<span>
									<svg width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg">
										<path d="M5 5V1.002C5 .456 5.448 0 6 0c.556 0 1 .45 1 1.002V5h3.998C11.544 5 12 5.448 12 6c0 .556-.45 1-1.002 1H7v3.998C7 11.544 6.552 12 6 12c-.556 0-1-.45-1-1.002V7H1.002C.456 7 0 6.552 0 6c0-.556.45-1 1.002-1H5z" fill="#948B88" fill-rule="evenodd"></path>
									</svg>
								</span>
							</a>
					    <?php } ?>

							<a class="carousel--controls--button v-prev m-disabled hide">
								<span>
									<svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
										<path d="M.256 5.498c0-.255.098-.51.292-.703L4.795.548C5.18.163 5.817.16 6.207.55c.393.393.392 1.023.002 1.412L2.67 5.5l3.54 3.538c.384.384.388 1.02-.003 1.412-.393.393-1.023.39-1.412.002L.548 6.205c-.192-.192-.29-.447-.29-.702z" fill="#948B88" fill-rule="evenodd"></path>
									</svg>
								</span>
							</a>
							<a class="carousel--controls--button v-next hide">
								<span>
									<svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
										<path d="M6.744 5.502c0 .255-.098.51-.292.703l-4.247 4.247c-.385.385-1.022.388-1.412-.002C.4 10.057.4 9.427.79 9.038L4.33 5.5.79 1.962C.407 1.578.403.942.794.55 1.186.157 1.816.16 2.204.548l4.248 4.247c.192.192.29.447.29.702z" fill="#948B88" fill-rule="evenodd"></path>
									</svg>
								</span>
							</a>
						</div>

						<?php
						
						$media_array = json_decode($media);
						if($media_array !=''){
						foreach($media_array as $m){
							if($m->source == 'yt'){
								echo "<a href='$m->url' class='phlb' data-yturl='$m->url'><img src='http://img.youtube.com/vi/$m->id/0.jpg' height='210px'/></a>";
							}else{
								echo "<a href='$m->url' class='phlb'><img src='$m->url' height='210px'/></a>";
							}
						}
						}?>
					</div>
					<?php if(current_user_can( 'upload_files' )){ ?>
					<?php if($media==''){ ?>
						<p class="post-detail placeholder media-placeholder"><span><?php _e('No media yet. Be the first one to','ph_theme'); ?>&nbsp;</span><a class='postmedia' data-pid='<?php echo $post->ID;?>' href="#"><?php _e('add media on this product','ph_theme'); ?></a><span>.</span></p>
						<?php } ?>
					</div>
					<?php } ?>

					
					<div class='section section-discussion'>
						<h3 class='h3'>discussion</h3>
						<?php comments_template(); ?> 
					</div>

					<?php if(!is_user_logged_in()){ ?>
					<div class='section can-comment'>
						<p class="post-detail placeholder"><span><?php _e('Commenting is limited to those invited by others in the community','ph_theme');?></span><br/><a class='ph-login-link' href="#"><?php _e(' Login to continue','ph_theme'); ?></a><span> <?php _e('or','ph_theme'); ?><a href="<?php echo esc_url(get_theme_mod('phfaq')); ?>"><?php _e('learn more','ph_theme'); ?></a>.</span></p>
					</div>
					<?php }

					if(current_user_can( 'subscriber' )){  ?>
					<div class='section can-comment'>
						<p class="post-detail placeholder"><span><?php _e('Commenting is limited to those invited by others in the community','ph_theme');?></span><br/>
							<?php 
							$cid = get_current_user_id();
							$access = get_user_meta($cid, 'ph_access_request',true);
							if($access == ''){ ?>
							<span class='ph-request-msg'>
							<a class='ph-request-access' data-uid ='<?php echo $cid; ?>' href="#"><?php _e('request access','ph_theme'); ?></a>
						</span>
						<?php }else{ ?>
						<span><?php _e('You have been added to the waiting list.','ph_theme');?><span class='emo'>&#x1f483;</span></span>
						<?php } ?>
							<br/><span><?php _e('Questions? check out our','ph_theme'); ?><a href='<?php echo esc_url(get_theme_mod('phfaq')); ?>'><?php _e('FAQ','ph_theme');?></a>.</span></p>
					</div>						
				<?php } ?>

					<div class='lhs-bottompad'></div>
                  
				</section>
				<?php 
				if(wp_is_mobile()){
					$c ='-mobile';
				}; ?>

				<div class='col-md-4 aside'>
					<div class='section'>
						<h3 class='h3'><?php _e('share','ph_theme'); ?></h3>
						<ul class='post-share'>
							<a class="share" href="<?php echo get_permalink($post->ID);?>" title="<?php the_title(); ?>" data-action="facebook"><li class='fb ph-s'><i class="fa fa-facebook"></i></li></a>
							<a class="share" href="<?php echo get_permalink($post->ID);?>" title="<?php the_title(); ?>" data-action="twitter"><li class='tw ph-s'><i class="fa fa-twitter"></i></li></a>
							<a class="share" href="<?php echo get_permalink($post->ID);?>" title="<?php the_title(); ?>" data-action="google"><li class='gp ph-s'><i class="fa fa-google-plus"></i></li></a>
							<li class='em ph-s'><i class="fa fa-envelope"></i></li>
						</ul>
					</div>

					<div style="clear:both"></div>

<!--  MAKER UX IN V3.7 - see epicplugins.com for more info 
					<div class='section'>
						<h3 class='h3'>0 makers</h3>
						<p class="post-detail placeholder v-white">
							<span>No maker yet.</span><br>
							<span>Be the first to&nbsp;</span>
							<a data-popover="click" data-popover-href="/posts/28453/maker_suggestions/new" href="#">suggest a maker</a>
						</p>
					</div>
-->
					<div class='section'>

					<?php
					$wpdb->epic   = $wpdb->prefix . 'epicred';
					$query = $wpdb->prepare("SELECT epicred_ip FROM $wpdb->epic WHERE epicred_id = %d", $post->ID);
					$upvotes = $wpdb->get_results($query);
					$u = count($upvotes);
					if($u == 0){
					  $uc = 'hide';
					}
					?>
					  
					  <div class="title upvotes upvotes-modal <?php echo $uc; ?>">
					  	<h3 class='h3'><?php echo $uc; ?><?php _e("Upvotes",'ph_theme'); ?></h3>
					  </div>

					  <div data-user-carousel="true" class="user-votes">
					  <?php
					  $ui = 0;
					  foreach($upvotes as $upvote){

					    $ava = ph_get_avatar_url(get_avatar( $upvote->epicred_ip , 60 ));
					    $href = get_author_posts_url( $upvote->epicred_ip );
					    $upv = get_userdata( $upvote->epicred_ip );

					  ?>
					  <div class="who-by-v example votes-inner">
					    <a class="drop-target drop-theme-arrows-bounce"><img class='img-rounded flash-ava' src='<?php echo $ava; ?>'/></a>
					     <div class="ph-content pop-ava-v">
					        <img class='modal-img' id='modal-img-<?php echo $ui;?>' src='<?php echo $ava; ?>'/>
					        <div class='user-info'>
					          <span class='user-name'><?php echo $upv->display_name; ?></span>
					          <div class='user-desc'>
					              <?php echo $upv->description; ?>
					          </div>
					          <div class='view-profile'>
					            <div class='btn btn-success primary ph_vp'><a class='vp' href='<?php echo $href;?>'><?php _e("View Profile","ph_theme"); ?></a></div>
					          </div>
					        </div>
					    </div>
					  </div>

					  <?php
					  $ui++;
					   }
					   ?>
					  </div>

					  <div style="clear:both"></div>

					</div>

					<div class='section'>
						<h3 class='h3'><?php _e('similar products','ph_theme'); ?></h3>
						<p class="post-detail">
							<?php // similar products run from tags taxonomies 
						    $orig_post = $post;
						    $tags = wp_get_post_tags($post->ID);
						    if ($tags) {
						    $tag_ids = array();
						    foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
						    $args=array(
						    'tag__in' => $tag_ids,
						    'post__not_in' => array($post->ID),
						    'posts_per_page'=>4, // Number of related posts to display.
						    'caller_get_posts'=>1
						    );
						     
						    $my_query = new wp_query( $args );
						 
						    while( $my_query->have_posts() ) {
						    $my_query->the_post();
						    ?>
						     
						    <div class="relatedthumb similar-product">
						        <a rel="external" href="<? the_permalink()?>">
						        	<?php the_post_thumbnail(array(150,100)); ?><br />
						          <span class='heading'><?php the_title(); ?></span>
						        </a>
						    </div>
						     
						    <? } 
						    }else{ ?>
						    <p class="post-detail placeholder v-white">
						    <?php
						    	echo "<span>";
						    	_e('No similar products yet.','ph_theme');
						    	echo "&nbsp;</span>";
						    }
						    $post = $orig_post;
						    wp_reset_query();
						    ?>
						</p>
					</div>
				</div>
			 </div>
		</div>


			
			<?php endwhile; ?>

			<?php else: ?> 
			<?php endif; ?>
	
			<div class='row'>
				<?php if(current_user_can( 'edit_posts' )){ ?>
                <div class="col-md-8 post-detail--footer-2">
                    <main class="">
                    	
                        <div class="post-detail--footer--comments-form-toggle">
                        	<form action="<?php echo home_url(); ?>/wp-comments-post.php" method="post" id="phcommentform">
                            <input class="post-detail--footer--comments-form-toggle--link comment-post" name="comment" id="comment" placeholder="What do you think of this product" />
                        	<div class='comment-actions'><span class='comment-cancel'><?php _e('Cancel','ph_theme'); ?></span> <span class='comment-submit'><?php _e('Submit','ph_theme'); ?></span></div>
                        	<input type="hidden" name="comment_post_ID" value="<?php echo $post->ID;?>" id="comment_post_ID">
                        	<input type="hidden" name="comment_parent" id="comment_parent" value="0">
                        	</form>
                        </div>
                      
                    </main>
                </div>
                  <?php } ?>

                    <!-- aside footer for single page -->
                    <div class="post-detail--footer v-no-access col-md-4">
                        <div class="aside-foot">
                            <section class="post-detail--footer--meta">
                                <a class="user-image post-detail--footer--meta--user-image" href="#">
                                    <span class="user-image">
                                        <div class="user-image--badge v-hunter">H</div>
                                        <?php 
                                        global $post;  $aid=$post->post_author;
                                        $pluginava = pluginHuntTheme_get_avatarurl($post->post_author);
                                        $pname = get_the_author_meta('user_nicename',$post->post_author);
                                        $auth = 'yes';
                                        $profileUrl = get_author_posts_url($post->post_author); 
                                        ?>
                                      <div class="who-by profile-drop">
                                        <a class="drop-target drop-theme-arrows-bounce-dark"><img class='img-rounded flash-ava' src='<?php echo $pluginava; ?>'/></a>
                                         <div class="ph-content pop-ava">
                                            <img id='modal-img' class='poster-ava' src='<?php echo $pluginava; ?>'/>
                                            <div class='user-info'>
                                              <span class='user-name'><?php // echo $pname; ?></span>
                                              <div class='view-profile'>
                                                <div class='btn btn-success primary ph_vp'><a class='vp submitter-vp' href='<?php echo $profileUrl; ?>'><?php _e("View Profile","ph_theme"); ?></a></div>   
                                              </div>
                                            </div>
                                        </div>
                                      </div>
                                    </span>
                                </a>
                                <a class="post-detail--footer--meta--time" href="<?php echo get_post_permalink($post->ID); ?>">
                                    <span>Posted</span>
                                    <span> </span>
                                    <time><?php printf( _x( '%s ago', '%s = human-readable time difference', 'ph_theme' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></time>
                                </a>

						      <span class='ph_em ph_ft'>
						          <div class="who-by-e flag-drop votes-inner">
						            <span class="drop-target drop-theme-arrows-bounce">        
						            <i class="fa fa-flag"></i>
						            </span>
						            <div class="ph-content pop-ava-v">
						                <div class='user-info-e ph-flag'>
						                  <div class='user-desc-em'>
						                      <span class='ph_title_em'><?php _e("Flag","ph_theme");?> </span><span class='ph_red' id ="ph_red_title_flag"><?php echo $phid->post_title; ?></span><span class='ph_title_em'></span>
						                  </div>
						        
						                  <textarea name="flag" id="body-flag-ph" class="textarea tooltip-field tooltip-textarea textarea-flag" placeholder="<?php _e('Why should this be removed ?','ph_theme'); ?>"></textarea><br>
						                  <span class='ph-flag-done'><strong><?php _e("Thank you ", "ph_theme"); ?></strong><?php _e(" we have received your feedback", "ph_theme"); ?></span>
						                  <div class='view-profile flag-post-ph'>
						                    <button class='btn btn-cancel primary ph_vp ph_cancel'><?php _e("Cancel","ph_theme"); ?></button>
						                    <button class='btn btn-success primary ph_vp ph_vp_flag' data-perma ="<?php echo $perma; ?>" data-id ="<?php echo $phid->ID; ?>"><?php _e("Send","ph_theme"); ?></button>
						                  </div>

						           
						              </div>
						            </div>
						          </div>
						      </span>


                            </section>
                        </aside>
                    </div>
                </div>

    </div>
</div> <!-- ph-sing-flash -->
			
			<?php wp_reset_query(); ?>


<?php get_footer(); ?>
