<!DOCTYPE html>
<html <?php language_attributes(); ?> xmlns:fb="http://ogp.me/ns/fb#">
  <head>
    <meta charset="utf-8">
    <title><?php wp_title(); ?></title>   
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" type="text/css">
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
      ?>
  		
    <meta property="og:title" content="<?php wp_title(); ?>"/>
    <meta property="og:site_name" content="<?php echo get_bloginfo( 'name' ); ?>"/>

<?php wp_head(); ?>
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
<style>
.ph-home {
    float: right;
    padding: 7px 20px;
    margin-bottom: 45px;
    border-radius: 4px;
    font-size: 16px;
    font-size: 1.6rem;
    color: #565656;
    background: #e6e6e6;
    padding-top:10px;
    margin-top:10px;
}
.ph-home:hover {
    background: #d7d7d7;
}
.ph-title-blog {
    color: #da552f;
    font-weight: 700;
    font-size: 24px;
    padding-right: 10px;
    padding-left: 10px;
    text-decoration: none;
    width: 80%;
    float: left;
}
</style>

<div class='container'>
<header>
    <div class='site-logo-blog'>
        <span class='pull-left'><a href='<?php echo esc_url( home_url( '/blog/' ) ); ?>' title='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>' rel='home'><img src='<?php echo esc_url( get_theme_mod( 'ph_blog_logo' ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'></span>
    </div>
  <h1 class='ph-title-blog'><?php  echo get_theme_mod( 'ph_blog_title', 'Plugin Hunt Blog' ); ?></a><span class='small ph-tagline'><?php  echo get_theme_mod( 'ph_blog_title', 'A blog for plugin lovers' ); ?></span></h1>
  <a class='ph-home' href="<?php echo home_url(); ?>"><?php _e('home','ph_theme'); ?></a>
  <div class="clear-both"></div>
</header>
</div>


<body class='<?php 
  if (!isset($class)) $class = ''; #WHFIX 24/03/2015: 
  body_class( $class ); ?>'>
<div id='wrapper'>
  <div class='container ph-blog'>




