<!DOCTYPE html>
<html <?php language_attributes(); ?> xmlns:fb="http://ogp.me/ns/fb#">
  <head>
    <meta charset="utf-8">
<title><?php wp_title('&raquo;','true','right'); ?></title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta name="twitter:widgets:csp" content="on">
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
<script>window.twttr = (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0],
    t = window.twttr || {};
  if (d.getElementById(id)) return;
  js = d.createElement(s);
  js.id = id;
  js.src = "https://platform.twitter.com/widgets.js";
  fjs.parentNode.insertBefore(js, fjs);
 
  t._e = [];
  t.ready = function(f) {
    t._e.push(f);
  };
 
  return t;
}(document, "script", "twitter-wjs"));</script>


  	<?php	wp_enqueue_script("jquery");  
      wp_enqueue_style('wptheme', get_template_directory_uri() . '/style.css' ); 
      if(wp_is_mobile()){
      wp_enqueue_style('wpthememob', get_template_directory_uri() . '/mobile-style.css' ); 
      }
  		

#}code used for throttling of submitted content...
$current_user = wp_get_current_user();
if ( 0 == $current_user->ID ) {
    // Not logged in.
} else {
    $ehacklast = get_user_meta($current_user->ID, 'ehacklast', true);
    $ehacksince = time() - $ehacklast;
    echo '<script>var ehacklast = ' . $ehacksince . '</script>';
}
	
//on the homepage... check for the post URL...

//do we have a custom permalink incoming....
$perma = false; if (isset($wp_query->query_vars['phpost_slug'])) #WHFIX 24/03/2015: 
  $perma = $wp_query->query_vars['phpost_slug'];
if($perma){
  //we don't want to return a 404
  $wp_query->set( 'is_404', false );
  $phid = get_page_by_path($perma, OBJECT, 'post');
  $postvote = get_post_meta($phid->ID, 'epicredvote' ,true);
  $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $phid->ID ), 'single-post-thumbnail' );
 
  $pluginfeat = get_post_meta($phid->ID,'phog',true);
  $desc = get_post($phid->ID)->post_content;
  ?>
    <meta property="og:title" content="<?php wp_title(); ?>"/>
    <meta property="og:image" content="<?php echo $pluginava;?>"/>
    <meta property="og:site_name" content="<?php echo get_bloginfo( 'name' ); ?>"/>
    <meta property="og:description" content="<?php echo $desc; ?>"/> 

  <style>
  .show-post-modal{
    display:block !important;
  }
  .new-post-modal-close{
    display:block !important;
  }
  .drop-target .drop-content {
    display: none;
}
  </style>
  <?php
  //check whether voted or not
  global $wpdb;
  $wpdb->myo_ip   = $wpdb->prefix . 'epicred';
  $fid = $current_user->ID;
  $query = "SELECT epicred_option FROM $wpdb->myo_ip WHERE epicred_ip = $fid AND epicred_id = $phid->ID";



  $al = $wpdb->get_var($query);
      if($al == NULL){
        $al = 0;
      }
      if($al == 1){
        $v = '-voted';
        $a = 'down';
      }else{
        $a = 'up';
      }
}else{
  ?>
    <meta property="og:title" content="<?php wp_title(); ?>"/>
    <meta property="og:site_name" content="<?php echo get_bloginfo( 'name' ); ?>"/>
  <?php
}

if ( ! isset( $content_width ) ) $content_width = 900;
wp_head();   
?>
</head>


<div id="fb-root"></div>
 <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '427029257462972',
          xfbml      : true,
          version    : 'v2.2'
        });
      };

      (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement(s); js.id = id;
         js.src = "//connect.facebook.net/en_US/sdk.js";
         fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));
</script>


<body <?php 
  if (!isset($class)) $class = ''; #WHFIX 24/03/2015: 
  body_class( $class ); ?>>
<div id='wrapper'>
<header class="site--header headroom headroom--top" data-auto-hide="true">
<nav class="navbar nav-fixed-top" role="navigation">
<div class="container">
  <div class='brand brand-head'>
    <?php if ( get_theme_mod( 'pluginhunt_logo' ) ){ ?>
    <div class='site-logo'>
        <span class='pull-left'><a href='<?php echo esc_url( home_url( '/' ) ); ?>' title='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>' rel='home'><img src='<?php echo esc_url( get_theme_mod( 'pluginhunt_logo' ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'></a></span>
    </div>
<?php } ?>
    <h1 class='pull-left blog-name'><a href="<?php echo home_url(); ?>" class="brand">  <?php bloginfo('name'); ?></a></h1>
    <br/><h2 class="tag-line"><?php echo get_bloginfo ( 'description' );?></h2>
  </div>

    <span class="pull-right topnav-options">
      <span class='usermeta'>

                  <span class="inline">

        <nav>
<li class='dropdown pull-left phmenu'>
 <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i id="phnav" class="phd fa fa-ellipsis-h"></i></a>
<ul class="dropdown-menu" role="menu"> <?php
          wp_nav_menu( array( 
  'container' => '',
  'container_class' => '',
  'theme_location' => 'primary',
  'menu_class' => 'nav',
  'walker' => new Bootstrap_Walker(),                 
  ) ); ?>
   </ul>
</li>
<!-- v2.0 think about notifications
<span class='phnotify'><i class="fa fa-bolt"></i> 33</span>
-->
          <span class='hopt'>
        	<?php
			if ( is_user_logged_in() ){
			$size='30';
 			$current_user = wp_get_current_user();
 			$email = $current_user->user_email;
      $author_id = $current_user->id;
      ?>
      <?php echo get_avatar( $email, $size ); ?>
            <span class="dropdown inline">
        <a class="dropdown-toggle small" id="account-dropdown" role="button" data-toggle="dropdown" href="#">
          
          <span class="strongest">            
              <i class="phd fa fa-angle-down"></i>
          </span>
        </a>
        <ul class="dropdown-menu" id="user-dropdown-menu" role="menu" aria-labelledby="account-dropdown">
          <li><a href="<?php echo get_author_posts_url( $author_id ); ?>"><i class="icon-fixed-width icon-user"></i><?php _e('My Profile','ph_theme'); ?></a></li>
          <li><a href="<?php echo home_url(); ?>/your-profile"><i class="icon-fixed-width icon-user"></i><?php _e('Your Account','ph_theme'); ?></a></li>
          <li><a href="<?php echo wp_logout_url(); ?>" title="Logout"><?php _e('Logout','ph_theme'); ?></a></li>
        </ul>
      </span>
			<?php
			}else{
        $logged = 'loggedout';
			?>
          <?php $surl = get_home_url(); ?>
          <a href="#" class='ph-log-new'><?php _e('Log in','ph_theme'); ?></a>
          <?php   } ?>
         </span>
       </span>
                <span class='new-post-button <?php echo $logged;?>'><i class='fa fa-plus-circle'></i></span>
        </nav>

      </span>

  </span>

</span>


  </div>
</nav>

</header>


  <script type='text/javascript'>
  jQuery(document).ready(function() {
           t = "<?php bloginfo('name'); ?>";
           slug = '<?php echo $perma; ?>';
            s = HuntAjax.epichome + '/posts/' + slug;
            updateTwitterValues(s,t + ": " + slug);
            updateFacebookValues(s);
            
            //#WHFIX 24/03/2015: 
            // Same happened as with twitter here, ... presume I've not enabled smt... boring though so fixed
            if (typeof FB != "undefined" && typeof FB.XFBML != "undefined") FB.XFBML.parse();
          });
  </script>

<div style='clear:both'></div>

<!-- new secondary menu -->


<!-- new post modal -->
  <span class='new-post-modal-close'><i class="fa fa-times"></i></span>
<div class="new-post-modal">
  <div class="new-post-wrapper">
    <div class="modal-container new ph-modal">
  <div class="new-post-header">
    <?php echo get_theme_mod('phtitle_setting', 'NEW PLUGIN'); ?>
  </div>
  <div class="new-post-subheader">
    <?php echo get_theme_mod('phstitle_setting', 'Found something interesting. Hunt it'); ?>
  </div>
</div>
</div>
<div class="new-post-form">
  <div class="new-post hunt">
      <form accept-charset="UTF-8" action="#" class="new_submission" id="new_submission" method="post"><div style="display:none"><input name="utf8" type="hidden" value=""><input name="authenticity_token" type="hidden" value="5PscQDQFiaEadnk9AFiy7dbNx4oZrTks3V6NReotabg="></div>
        <?php 
        wp_nonce_field( 'ph-new-post');
        ?>
        <div id="ph-out"></div>
          <div class='modal-loading-fetch'>
              <i class="fa fa-spinner fa-pulse"></i>
          </div>
        <input class="product-url inputfield" id="submission_url" name="submission[url]" placeholder="<?php _e('URL','ph_theme'); ?>" type="url"><br>
        <input class="product-name inputfield" id="submission_name" name="submission[name]" placeholder="<?php _e('Name','ph_theme'); ?>" type="text"><br>
          <div class="new-post-format">
            <?php
            if ( current_theme_supports( 'post-formats' ) ) {
            $post_formats = get_theme_support( 'post-formats' );
            /*
            if ( is_array( $post_formats[0] ) ) {
              echo "<select>";
              foreach($post_formats[0] as $format){
                echo "<option>" . $format . "</option>";
              }
              echo "</select>";
                // Array( supported_format_1, supported_format_2 ... )
            }
            */
            } ?>
          </div>
        <input class="product-tagline inputfield" id="submission_tagline" name="submission[tagline]" placeholder="<?php _e('Tagline','ph_theme'); ?>" type="text"><br>
        <input class="new_post_submit_child" data-loading-text="Hunting..." name="commit" type="submit" value="<?php echo get_theme_mod( 'ph_cta' ,'Hunt it'); ?>">
</form>  </div>
</div>
<div class='new-post-success'></div>
<div class='toosoon2'></div>

      </div>
    </div>


<!-- post modal -->
<div class="show-post-modal">  
  <div class='modal-loading'>
    <i class="fa fa-spinner fa-pulse"></i>
</div>
<div class="modal-container">
  <div class="close-persistent-tooltip-body" id="close-persistent-tooltip"></div>
  <div class="comments-header">

    <div class="bottom">
      <div class="product-top">

      <div clas='ph-voting-flash'>
        <div class="reddit-voting-flash<?php echo $v;?>">
            <ul class="unstyled <?php echo $v;?>">
              <div class="arrow arrow-modal fa fa-caret-up  fa-2x up" data-red-current="<?php echo $postvote;?>" data-red-like="<?php echo $a;?>" data-red-id="<?php echo $phid->ID;?>" role="button" aria-label="upvote" tabindex="0"></div>
              <div class="score scoremodal" data-red-current="<?php echo $postvote;?>"><?php echo $postvote;?></div>
            </ul>
        </div>
      </div>
      <?php
                if($phid){


                  //#WHFIX 24/03/2015: 
                  #} This is for your autohackery example, not for peoples theme, which needs submitter, not plugin avatar
                  /*
                  $plugina = get_post_meta($phid->ID,'pluginauthor', true);
                  if($plugina ==''){
                    $pname = get_the_author_meta('user_nicename');
                    $auth = 'yes';
                  }else{
                    $pname = $plugina;
                    $auth = 'no';
                  }

                  #$pluginava = get_post_meta($phid->ID,'pluginavatar',true);
                  */

                  #} Actually found that the whole of that was off.. it's not using the meta surely? Isn't it using wordpress users who submitted? If I'm wrong take that out from above, but not sure how to get avatars... depends how you set I guess.
                 

                  if(isset($phid->post_author)){
                    $pname = get_the_author_meta('user_nicename',$phid->post_author);
                    $auth = 'yes';
                    $profileUrl = get_author_posts_url($phid->post_author); //#WHFIX 24/03/2015: 
                  }else{
                    $pname = 'User'; #} MIKE TO CHANGE (DEFAULT)
                    $auth = 'no';
                    $profileUrl = '#'; //#WHFIX 24/03/2015: 
                  }


                  #} added the func pluginHuntTheme_get_avatarurl to bottom of functions.php (see http://wordpress.stackexchange.com/questions/59442/how-do-i-get-the-avatar-url-instead-of-an-html-img-tag-when-using-get-avatar)
                  $pluginHuntTheme_submitterAvatar = pluginHuntTheme_get_avatarurl($phid->post_author);
                  $pluginava = $pluginHuntTheme_submitterAvatar;

                  #}comments...
                    $args = array(
                    'status' => 'approve',
                    'post_id' => $phid->ID, 
                    );
                    $comments = get_comments($args);
                    
                    $commarr  = array();
                    $i = 0;

                  //#WHFIX 24/03/2015: 

                    #} e.g. is this right for the slug?
                    $pluginHuntTheme_slug = $phid->post_name; #get_post_meta($phid->ID,'outbound',true);

                  $pluginHuntTheme_jsExtra = 'phTheme_bindPopBar({title:"'.$phid->post_title.'",content:"'.$phid->post_content.'",commentshtml:"preload",comments:[],upvotes:""},'.$phid->ID.',"'.$pluginHuntTheme_slug.'",'.$postvote.',"'.$pluginava.'")';




                  #} Output this in-line, for now :)
                  if (!empty($pluginHuntTheme_jsExtra)) echo '<script type="text/javascript">jQuery(document).ready(function(){'.$pluginHuntTheme_jsExtra.'});</script>';

                }

      ?>

      <div class="post-info">
        <div class="grid-child-play"></div>
        <h1 class='post-info-h1'><a class="post-url" href="#" target="_blank"><?php echo $phid->post_title;?></a></h1>
        <span class="post-tagline description"><?php echo $phid->post_content;?></span>
        <div class='ph-outbound'>
          <span class='outlink'><a href='<?php echo parse_url(get_post_meta($phid->ID,'outbound',true), PHP_URL_HOST); ?>'>Get it</a></span>
        </div>
      </div>

    </div>

    </div>


  </div>

  <div class="post-meta-flash">
  <span class="posted-by">

  <div class="who-by profile-drop">
    <a class="drop-target drop-theme-arrows-bounce-dark"><img class='img-rounded flash-ava' src='<?php echo $pluginava; ?>'/></a>
     <div class="ph-content pop-ava">
        <div class='ph-hunted-this'>hunted this <?php echo get_theme_mod('ph_keyword_s', 'plugin');?></div>
        <img id='modal-img' class='poster-ava' src='<?php echo $pluginava; ?>'/>
        <div class='user-info'>
          <span class='user-name'><?php echo $pname; ?></span>
          <div class='view-profile'>
            <button class='btn btn-success primary ph_vp'><a class='vp' href='<?php echo $profileUrl; //#WHFIX 24/03/2015:  ?>'><?php _e("View Profile","ph_theme"); ?></a></button>   
          </div>
        </div>
    </div>
  </div>

  </span>

<div class='modal-list'>
  <ul>
      <li>
      <span class='ph_em'>
          <div class="who-by-e email-drop votes-inner">
            <span class="drop-target drop-theme-arrows-bounce">
              <i class="fa fa-envelope-o"></i> <?php _e('Email','ph_theme'); ?>
            </span>
            <div class="ph-content ph-content-email pop-ava-v">
                <span class='email-success'><strong><?php _e("Sent ", "ph_theme"); ?></strong><?php _e(" your message is travelling through the interweb", "ph_theme"); ?></span>
                <div class='user-info-e ph-email'>
                  <div class='user-desc-em'>
                      <span class='ph_title_em'><?php _e("Forward ", "ph_theme");?></span><span class='ph_red' id ="ph_red_title"><?php echo $phid->post_title; ?></span><span class='ph_title_em'> <?php _e("via email","ph_theme");?></span>
                  </div>
                  <span class='alert-ph'><i class="fa fa-times-circle"></i> <?php _e("not a valid email", "ph_theme"); ?></span>
                  <input class="tooltip-field" id="ph_email" name="ph_email" placeholder="<?php _e('Email address','ph_theme'); ?>" type="email"><br>
                  <div class='view-profile email-post-ph'>
                    <button class='btn btn-cancel primary ph_vp ph_cancel'><?php _e("Cancel","ph_theme"); ?></button>
                    <button class='btn btn-success primary ph_vp ph_vp_email' data-perma ="<?php echo $perma; ?>" data-id ="<?php echo $phid->ID; ?>"><?php _e("Send","ph_theme"); ?></button>
                  </div>
              </div>
            </div>
          </div>
      </span>
      </li>
      <li>
      <span class='ph_em'>
          <div class="who-by-e flag-drop votes-inner">
            <span class="drop-target drop-theme-arrows-bounce">        
            <i class="fa fa-flag-o"></i>
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
      </li>
      <li><div id="twitter-share-section"></div></li>
      <li class='fb-last'><div id="facebook-share-section"></div></li>
  </ul>
</div>

<div style="clear:both"></div>

  </div>


  <div class="post-show" data-id="<?php echo $phid->ID; ?>">

<?php
$wpdb->epic   = $wpdb->prefix . 'epicred';
$query = $wpdb->prepare("SELECT epicred_ip FROM $wpdb->epic WHERE epicred_id = %d", $phid->ID);
$upvotes = $wpdb->get_results($query);
$u = count($upvotes);
if($u == 0){
  $uc = 'hide';
}
?>
  
  <div class="title upvotes upvotes-modal <?php echo $uc; ?>">
      <h2>
        <span class='rups'></span><?php _e("Upvotes",'ph_theme'); ?>
      </h2>
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
            <button class='btn btn-success primary ph_vp'><a class='vp' href='<?php echo $href;?>'><?php _e("View Profile","ph_theme"); ?></a></button>
          </div>
        </div>
    </div>
  </div>

  <?php
  $ui++;
   }
   ?>
  </div>


  <div class='clearfix'></div>


<?php if($comments){ ?>   
<h2 class="subhead" data-first-comment="false"><span class='comnum'></span> <?php _e('Comments','ph_theme'); ?></h2>
<?php } ?>
<?php
  if($phid){
    foreach($comments as $comment){
     $ava = ph_get_avatar_url(get_avatar( $comment->user_id , 60 ));
     $commarr[] = array(
       'author'      =>   "$comment->comment_author" , 
       'id'          =>   "$comment->comment_ID", 
       'content'     =>   "$comment->comment_content", 
       'ava'         =>   "$ava", 
       'authorpage'  =>   "$comment->comment_author_url",
       'parent'      =>   "$comment->comment_parent"
      );
      echo  "<hr class='comments-rule'><div class='comment' data-comment-id=" .$comment->comment_ID. "><div class='comment-body'><h2 class='comment-user-name'><a href=''>" . $comment->comment_author . "</a></h2><div class='maker'></div><div class='who-by-c user-image-container'><a class='who-by-c user-image-container' href='#'><img class='flash-ava-c' src='" . $ava . "'/></a></div><div class='comment-user-info'></div><div class='actual-comment'>" . $comment->comment_content . "</div></div></div>";
   }
  }
?>
 
<div class="comments">
  <?php 
        $args = array(
        'status' => 'approve',
        'post_id' => $phid->ID, 
        ); 
        comment_form( $args, $phid->ID );
                    ?>
</div>
<!-- #WHFIX 27/03/2015 - tut tut!
  <div class="well cant-comment">
    Commenting is limited for the demo (to prevent comment spam). The theme supports WordPress comments 100% <a href="/about#why-cant-i-comment">Watch the Video</a>
  </div>
-->

  <div class="can-comment"></div>


  </div>


</div>

</div>

 <!-- end header -->
<nav class="navbar navbar-default navbar-ph-second">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

      <!-- code here to  output the menu -->
      <?php    $menu_name = 'secondary';

    if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
  $menu = wp_get_nav_menu_object( $locations[ $menu_name ] );

  $menu_items = wp_get_nav_menu_items($menu->term_id);

  $menu_list = '<ul class="nav navbar-nav" id="menu-' . $menu_name . '">';

  foreach ( (array) $menu_items as $key => $menu_item ) {
      $title = $menu_item->title;
      $url = $menu_item->url;
      $menu_list .= '<li><a href="' . $url . '">' . $title . '</a></li>';
  }
  $menu_list .= '</ul>';
    } else {
  $menu_list = '';
    }

    echo $menu_list;
    ?>

      <form class="navbar-form navbar-right" role="search">
        <div class="form-group">
          <input type="text" class="form-control" placeholder = "<?php _e('Search..','ph_theme'); ?>" value="<?php the_search_query(); ?>" name="s" id="s">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

</div>


