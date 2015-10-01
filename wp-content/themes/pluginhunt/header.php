<!DOCTYPE html>
<html <?php language_attributes(); ?> xmlns:fb="http://ogp.me/ns/fb#">
  <head>
    <meta charset="utf-8">
    <title><?php wp_title(); ?></title>   
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

<?php  		

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
    <meta property="og:description" content="<?php // echo $desc; ?>"/> 

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
          version    : 'v2.0'
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
  if (!isset($class)) $class = ''; 
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
    <h1 class='pull-left blog-name header--logo--title'><a href="<?php echo home_url(); ?>" class="brand">  <?php bloginfo('name'); ?></a></h1>




    
  </div>

      <form class="navbar-form navbar-left header--search-form" role="search">
        <div class="form-group">
          <label class="header--search-form--label" for="search" data-reactid=".0.0.0.0.0"><span data-reactid=".0.0.0.0.0.0"><svg width="15" height="15" viewBox="0 0 15 15" xmlns="http://www.w3.org/2000/svg"><title>Oval 95</title><path d="M9.383 10.347c-.987.78-2.233 1.244-3.588 1.244C2.595 11.59 0 8.997 0 5.796 0 2.595 2.595 0 5.795 0c3.2 0 5.796 2.595 5.796 5.795 0 1.355-.464 2.6-1.243 3.588L15 14.036l-.964.964-4.653-4.653zm-3.588-.12c2.448 0 4.432-1.984 4.432-4.432 0-2.447-1.984-4.43-4.432-4.43-2.447 0-4.43 1.983-4.43 4.43 0 2.448 1.983 4.432 4.43 4.432z" fill="#BBB" fill-rule="evenodd"></path></svg></span></label>
          <input type="text" class="form-control header--search-form--field" placeholder = "<?php _e('Search..','ph_theme'); ?>" value="<?php the_search_query(); ?>" name="s" id="s">
        </div>
        <button type="submit" class="btn btn-default hide">Submit</button>
      </form>

    <span class="pull-right topnav-options">
      <span class='usermeta'>

                  <span class="inline">

        <nav>
<li class='dropdown pull-left phmenu'>
 <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><svg width="20" height="4" viewBox="0 0 20 4" xmlns="http://www.w3.org/2000/svg"><path d="M2 4c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm8 0c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm8 0c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z" fill="#BBB" fill-rule="evenodd"></path></svg></a>
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


<span class='hopt'>
        	<?php
			if ( is_user_logged_in() ){
			$size='35';
 			$current_user = wp_get_current_user();
 			$email = $current_user->user_email;
      $author_id = $current_user->id;
      ?>

            <span class="dropdown inline ph-drop-arr">
        <a class="dropdown-toggle small" id="account-dropdown" role="button" data-toggle="dropdown" href="#">
          
          <span class="strongest">            
              <?php echo get_avatar( $email, $size ); ?>
          </span>
        </a>
        <ul class="dropdown-menu" id="user-dropdown-menu" role="menu" aria-labelledby="account-dropdown">
          <li><a href="<?php echo get_author_posts_url( $author_id ); ?>"><i class="icon-fixed-width icon-user"></i><?php _e('My Profile','ph_theme'); ?></a></li>
          <li><a href="<?php echo wp_logout_url(); ?>" title="Logout"><?php _e('Logout','ph_theme'); ?></a></li>
        </ul>
      </span>
			<?php
			}else{
        $logged = 'loggedout';
			?>
          <?php $surl = get_home_url(); ?>
          <span class='login-link-header'>
          <a href="#" class='ph-log-new'><?php _e('Log in','ph_theme'); ?></a>
        </span>
          <?php   } ?>
         </span>
       </span>
                <div class='new-post-button <?php echo $logged;?>'><svg width="22" height="22" viewBox="0 0 22 22" xmlns="http://www.w3.org/2000/svg"><path d="M10 12H0v-2h10V0h2v10h10v2H12v10h-2V12z" fill="#BBB" fill-rule="evenodd"></path></svg></div>
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
          
            if (typeof FB != "undefined" && typeof FB.XFBML != "undefined") FB.XFBML.parse();
          });
  </script>

<div style='clear:both'></div>

<!-- new post modal -->

  <?php // } ?>
</div>


<!-- new post modal -->
<div class="modal-overlay-new">

  <a class="modal--close-new v-desktop" href="#" title="Close">
    <span>
      <svg width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg">
        <path d="M6 4.586l4.24-4.24c.395-.395 1.026-.392 1.416-.002.393.393.39 1.024 0 1.415L7.413 6l4.24 4.24c.395.395.392 1.026.002 1.416-.393.393-1.024.39-1.415 0L6 7.413l-4.24 4.24c-.395.395-1.026.392-1.416.002-.393-.393-.39-1.024 0-1.415L4.587 6 .347 1.76C-.05 1.364-.048.733.342.343c.393-.393 1.024-.39 1.415 0L6 4.587z" fill-rule="evenodd"></path>
      </svg>
    </span>
  </a>

  <div class="new-post-modal">  
     <div class='modal-loading'>
        <i class="fa fa-spinner fa-spin"></i>
    </div>
    <div class="new-modal-container">

      <!-- new post form -->

  <?php if(current_user_can( 'edit_posts' )){ ?>
  <form class="post-submission" id="post-submission">
    <header class="modal-post-submission--header ph-newsubmit">
      <h1><span><?php echo get_theme_mod( 'phtitle_setting','Post a new product' ); ?></h1>
      <h2><?php echo get_theme_mod( 'phstitle_setting','Found something new and interesting? Hunt it!'); ?></h2>
    </header>


      <div class="post-submission--body">
        <div class="post-submission--form-row post-submission--form-row-name">
          <label class="form--label" for="name">
            <span class="form--label-icon">
              <svg width="14" height="15" viewBox="0 0 14 15" xmlns="http://www.w3.org/2000/svg">
                <title>Slice 1</title>
                <path d="M0 0v4h1.077s.768-2.005 1.927-2H6.06L6 12c.038 2.042-3 2-3 2v1h8v-1s-3.014.024-3-2l.023-10h2.975c2.057.003 1.925 2 1.925 2H14V0H0z" fill="#BBB" fill-rule="evenodd"></path>
              </svg>
            </span>
            <span>Name</span>
          </label>
          <div class="form--field" data-reactid=".4.2.0.$name">
          <input class="form--input" maxlength="60" name="name" placeholder="Enter the product’s name" type="text" id="name" value="" data-reactid=".4.2.0.$name.0">
        </div>
      </div>
      <div class="post-submission--form-row post-submission--form-row-url">
        <label class="form--label" for="url">
          <span class="form--label-icon">
            <svg width="16" height="8" viewBox="0 0 16 8" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 6c-.86 0-1.783-.936-2.694-2 .91-1.064 1.833-2 2.694-2 1.103 0 2 .897 2 2s-.897 2-2 2zM4 6c-1.103 0-2-.897-2-2s.897-2 2-2c.86 0 1.783.936 2.694 2C5.784 5.064 4.86 6 4 6zm8-6c-1.606 0-2.85 1.137-4 2.453C6.85 1.137 5.606 0 4 0 1.794 0 0 1.794 0 4s1.794 4 4 4c1.606 0 2.85-1.137 4-2.453C9.15 6.863 10.394 8 12 8c2.206 0 4-1.794 4-4s-1.794-4-4-4z" fill="#BBB" fill-rule="evenodd"></path>
            </svg>
          </span>
          <span>Link</span></label>
          <div class="post-submission--form-field-group">
            <div class="form--field" data-reactid=".4.2.1.1.$url">
              <input class="form--input" name="url" placeholder="http://www..." type="text" id="url" value="" data-reactid=".4.2.1.1.$url.0">
            </div>
          </div>
        </div>
        <div class="post-submission--form-row post-submission--form-row-tagline" data-reactid=".4.2.2">
          <label class="form--label" for="tagline">
          <span class="form--label-icon">
            <svg width="16" height="6" viewBox="0 0 16 6" xmlns="http://www.w3.org/2000/svg"><title>Slice 1</title><path d="M0 0h11v2H0V0zm0 4h16v2H0V4z" fill="#BBB" fill-rule="evenodd"></path>
            </svg>
          </span>
          <span>Tagline</span></label><div class="form--field">
          <input class="form--input" maxlength="60" name="tagline" placeholder="Describe the product briefly" type="text" id="tagline" value="">
        </div>
      </div>
      <div class="post-submission--form-media-section post-submission--form-row"><label class="form--label" for="media_url"><span class="form--label-icon" data-reactid=".4.2.3.1.0.0"><svg width="16" height="14" viewBox="0 0 16 14" xmlns="http://www.w3.org/2000/svg"><path d="M14 1.167c0 .397.373.833 1 .833H1c.627 0 1-.436 1-.833v11.666C2 12.436 1.627 12 1 12h14c-.627 0-1 .436-1 .833V1.167zm2 0v11.666c0 .645-.448 1.167-1 1.167H1c-.552 0-1-.522-1-1.167V1.167C0 .522.448 0 1 0h14c.552 0 1 .522 1 1.167zM4 10l2.246-3.935 5.915 3.84L4 10zm6-5c0 .552.448 1 1 1s1-.448 1-1-.448-1-1-1-1 .448-1 1z" fill="#BBB" fill-rule="evenodd"></path></svg></span><span data-reactid=".4.2.3.1.0.1">Media</span>
      </label>
      <div class="post-submission--form-field-group" data-reactid=".4.2.3.1.1">
        <div class="form--field" data-reactid=".4.2.3.1.1.0">
          <input class="form--input" name="media_url" placeholder="Paste a direct link to an image or a YouTube video" type="text" id="media_url">
          <div><a class="trigger-upload" href="#" id="_unique_name_button" data-pid='1'>+ <?php _e('Upload an image','ph_theme');?></a><input accept="image/gif, image/jpeg, image/png" class="uploader" type="file"><span class='invalid'><span></div>
        </div>

      <div class="media-items"></div>


      </div>
    </div>
  </div>


<div class="post-submission--footer" data-reactid=".4.3"><input class="button ph-newsubmit" type="submit" value="Hunt it!" data-reactid=".4.3.0"></div>

</form>
<?php }else{  ?>
<header class="modal--header">
  <h1 class="modal--header--title"><?php _e('Posting on ','ph_theme');?><?php echo get_bloginfo( 'name' ); ?></h1>
  <p class="modal--header--description"><?php _e('An invite is needed to submit products','ph_theme');?></p>
</header>

<div class="p-new-post">
  <p class="s-p"><?php _e('Plugin Hunt is a community of product enthusiasts. Submissions are accepted by our most active members, specifically those that have been invited by others in the community.','ph_theme'); ?></p>



  <p class="s-p"><a href="<?php echo get_theme_mod('faq');?>"><?php _e('Learn more about invites','ph_theme');?></a></p>
</div>

<?php } ?>

      <!-- end new post form -->


    </div>
  </div>
</div>



<!-- post modal -->
<div class="modal-overlay">

  <a class="modal--close v-desktop" href="#" title="Close">
    <span>
      <svg width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg">
        <path d="M6 4.586l4.24-4.24c.395-.395 1.026-.392 1.416-.002.393.393.39 1.024 0 1.415L7.413 6l4.24 4.24c.395.395.392 1.026.002 1.416-.393.393-1.024.39-1.415 0L6 7.413l-4.24 4.24c-.395.395-1.026.392-1.416.002-.393-.393-.39-1.024 0-1.415L4.587 6 .347 1.76C-.05 1.364-.048.733.342.343c.393-.393 1.024-.39 1.415 0L6 4.587z" fill-rule="evenodd"></path>
      </svg>
    </span>
  </a>
  <div class="post-detail--navigation hide">
    <button class="post-detail--navigation--button" data-action="open-modal" data-href="/tech/ultimate-startup-decision-maker" >
      <span>
        <svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
          <path d="M.256 5.498c0-.255.098-.51.292-.703L4.795.548C5.18.163 5.817.16 6.207.55c.393.393.392 1.023.002 1.412L2.67 5.5l3.54 3.538c.384.384.388 1.02-.003 1.412-.393.393-1.023.39-1.412.002L.548 6.205c-.192-.192-.29-.447-.29-.702z" fill="#948B88" fill-rule="evenodd"></path>
        </svg>
      </span>
    </button>
    <button class="post-detail--navigation--button"  data-action="open-modal" data-modal-replace="true" title="Ultimate Startup Decision Maker">
      <span data-reactid=".6.1.1.0">
        <svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
          <path d="M6.744 5.502c0 .255-.098.51-.292.703l-4.247 4.247c-.385.385-1.022.388-1.412-.002C.4 10.057.4 9.427.79 9.038L4.33 5.5.79 1.962C.407 1.578.403.942.794.55 1.186.157 1.816.16 2.204.548l4.248 4.247c.192.192.29.447.29.702z" fill="#948B88" fill-rule="evenodd"></path>
        </svg>
      </span>
    </button>
  </div>


  <div class="show-post-modal">  
     <div class='modal-loading'>
        <i class="fa fa-spinner fa-spin"></i>
    </div>
    <div class="modal-container"></div>
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


    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

</div>
<a id="ph-log-social-new" href="#animatedModal" style="display:none">.</a>

