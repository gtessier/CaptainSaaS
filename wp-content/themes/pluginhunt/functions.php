<?php

 // ------------------------------------------------------------------
 // Copyright epicplugins limited. www.epicplugins.com www.epicthemes.com
 // ------------------------------------------------------------------
 //

#} Global Version number: For updates and the "settings" page
global $ph_version;
$ph_version = 'v3.6.1';  //latest release


#} filter the code for the media uploader
add_filter('media_upload_default_tab', 'ph_switch_tab');
function ph_switch_tab($tab)
{
  if(!is_admin()){
    return 'type';
  }
}

#} take the code from the release
add_action( 'wp_ajax_nopriv_hackers_vote', 'hackers_vote' );
add_action( 'wp_ajax_hackers_vote', 'hackers_vote' );
function hackers_vote(){
  //we are logging the votes here...
  global $wp_query,$wpdb;

  $wpdb->myo_ip   = $wpdb->prefix . 'epicred';
  $id     = (int)$_POST['id'];  //the post we are voting on...
  $v      =  $_POST['vote'];
  $current_user   = wp_get_current_user();
  $uid            = $current_user->ID;

  $query = "SELECT epicred_option FROM $wpdb->myo_ip WHERE epicred_ip = $uid AND epicred_id = $id";
  $al = $wpdb->get_var($query);
      if($al == NULL){
          $q = $wpdb->prepare("INSERT INTO $wpdb->myo_ip (epicred_id, epicred_option, epicred_ip) VALUES (%d, 1, %d)",$id,$uid);
          $wpdb->query($q);
          $votes = get_post_meta($id, 'epicredvote' ,true);
          if($votes == ''){
            update_post_meta($id,'epicredvote', 1);
          }else{
            $v = $votes + 1;
            update_post_meta($id,'epicredvote', $v);  
          }
          
      }else{
        if($v == 'u'){
          $q = $wpdb->prepare("UPDATE $wpdb->myo_ip SET epicred_option = 1 WHERE epicred_id = %d AND epicred_ip = %d",$id,$uid);
          $wpdb->query($q);
          $votes = get_post_meta($id, 'epicredvote' ,true) + 1;
          update_post_meta($id,'epicredvote', $votes);
        }else if($v == 'd'){
          $q = $wpdb->prepare("UPDATE $wpdb->myo_ip SET epicred_option = 0 WHERE epicred_id = %d AND epicred_ip = %d",$id,$uid);
          $wpdb->query($q);
          $votes = get_post_meta($id, 'epicredvote' ,true) - 1;
          update_post_meta($id,'epicredvote', $votes);
        }
      }



  $r['m'] = 'success';

  echo  json_encode($r);

  die();  //dying...


}



#} Restrict the comment ability - control what is shown using body class filtering as well as PHP code
// Add specific CSS class by filter
add_filter( 'body_class', 'no_ph_invited' );
function no_ph_invited( $classes ) {
  // add 'class-name' to the $classes array
  if(is_user_logged_in()){ 
      if(current_user_can( 'edit_posts' )){ 
        $classes[] = 'ph_invited monkey_dust';
      }else{
        $classes[] = 'no_ph_invited';
      }
  }else{
    $classes[] = 'no_ph_invited';
  }
  // return the $classes array
  return $classes;
}

#} AJAX comments.... sup
add_action('init', 'ajaxcomments_load_js');

function ajaxcomments_load_js() {
//wp_enqueue_script('ajaxcomments', get_stylesheet_directory_uri().'js/ajaxcomments.js');

wp_enqueue_script('ajaxcomments', get_template_directory_uri() . "/js/ajaxcomments.js", array('jquery'));
}

function ajaxify_comments_jaya($comment_ID, $comment_status) {
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
//If AJAX Request Then
switch ($comment_status) {
case '0':
//notify moderator of unapproved comment
wp_notify_moderator($comment_ID);
case '1': //Approved comment
// echo "success";
$commentdata = get_comment($comment_ID, ARRAY_A);
//print_r( $commentdata);
$permaurl = get_permalink( $post->ID );
$url = str_replace('http://', '/', $permaurl);

if($commentdata['comment_parent'] == 0){
$output = '<article class="comment byuser comment-author-mike bypostauthor even depth-2" id="comment-' . $commentdata['comment_ID'] . '" itemprop="comment" itemscope="" itemtype="http://schema.org/Comment"><div class="ph-comment-row">
        <figure class="gravatar">'.get_avatar($commentdata['comment_author_email'],30).'</figure>
        <div class="comment-meta post-meta" role="complementary">
        <span class="comment-author">'.$commentdata['comment_author'].'</span><span class="ph-comment-info"> -' . get_the_author_meta('description',$commentdata['user_id']) .'</span></div>
        <div class="comment-content post-content" itemprop="text">
          <p>'.$commentdata['comment_content'].'</p>
          <div class="ph-comment-meta">
            <div class="pull-left">
              <span class="ph-m reply-comment" data-cid="'.$commentdata['comment_ID'].'" data-un="'.$commentdata['comment_author'].'"><i class="fa fa-mail-reply" data-cid="398"></i> Reply</span>
              <span class="ph-m share-comment"><i class="fa  fa-share-square-o"></i><a class="comment-tweet-link" href="https://twitter.com/intent/tweet?text=" title="share">share</a></span>
            </div>
            <div style="clear:both"></div>
          </div>
        </div>
      </div>
      </article>';

echo $output;

}
else{

//if a reply

$output = '<article class="comment byuser comment-author-mike bypostauthor even depth-2" id="comment-' . $commentdata['comment_ID'] . '" itemprop="comment" itemscope="" itemtype="http://schema.org/Comment"><div class="ph-comment-row">
        <figure class="gravatar">'.get_avatar($commentdata['comment_author_email'],30).'</figure>
        <div class="comment-meta post-meta" role="complementary">
        <span class="comment-author">'.$commentdata['comment_author'].'</span><span class="ph-comment-info"> -' . get_the_author_meta('description',$commentdata['user_id']) .'</span></div>
        <div class="comment-content post-content" itemprop="text">
          <p>'.$commentdata['comment_content'].'</p>
          <div class="ph-comment-meta">
            <div class="pull-left">
              <span class="ph-m reply-comment" data-cid="'.$commentdata['comment_ID'].'" data-un="'.$commentdata['comment_author'].'"><i class="fa fa-mail-reply" data-cid="398"></i> Reply</span>
              <span class="ph-m share-comment"><i class="fa  fa-share-square-o"></i><a class="comment-tweet-link" href="https://twitter.com/intent/tweet?text=" title="share">share</a></span>
            </div>
            <div style="clear:both"></div>
          </div>
        </div>
      </div>
      </article>';

echo $output;
}

/*
<span class="ph-m upvote-comment" data-cid="'.$commentdata['comment_ID'].'"><span class="ph-up-adj"><i class="fa fa-sort-up"></i></span> upvote </span>
*/

$post = get_post($commentdata['comment_post_ID']);
wp_notify_postauthor($comment_ID, $commentdata['comment_type']);
break;
default:
echo "error";
}
exit;
}
}

add_action('comment_post', 'ajaxify_comments_jaya', 25, 2);



#} New Walker Class for Comments
/** COMMENTS WALKER */
class ph_comment_walker extends Walker_Comment {
    var $tree_type = 'comment';
    var $db_fields = array( 'parent' => 'comment_parent', 'id' => 'comment_ID' );
 
    // constructor – wrapper for the comments list
    function __construct() { ?>

      <section class="comments-list">

    <?php }

    // start_lvl – wrapper for child comments list
    function start_lvl( &$output, $depth = 0, $args = array() ) {
      $GLOBALS['comment_depth'] = $depth + 2; ?>
      
      <section class="child-comments comments-list">

    <?php }
  
    // end_lvl – closing wrapper for child comments list
    function end_lvl( &$output, $depth = 0, $args = array() ) {
      $GLOBALS['comment_depth'] = $depth + 2; ?>

      </section>

    <?php }

    // start_el – HTML for comment template
    function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
      $depth++;
      $GLOBALS['comment_depth'] = $depth;
      $GLOBALS['comment'] = $comment;
      $parent_class = ( empty( $args['has_children'] ) ? '' : 'parent' ); 
  
      if ( 'article' == $args['style'] ) {
        $tag = 'article';
        $add_below = 'comment';
      } else {
        $tag = 'article';
        $add_below = 'comment';
      } ?>

      <article <?php comment_class(empty( $args['has_children'] ) ? '' :'parent') ?> id="comment-<?php comment_ID() ?>" itemprop="comment" itemscope itemtype="http://schema.org/Comment">
        <div class='ph-comment-row'>
        <figure class="gravatar"><?php echo get_avatar( $comment, 30, '[default gravatar URL]', 'Author’s gravatar' ); ?></figure>
        <div class="comment-meta post-meta" role="complementary">
          <?php 
          $em =  get_comment_author_email();
          $the_user = get_user_by('email', $em);
          $the_user->ID;
          $username = $the_user->user_nicename;
          $text = wp_trim_words($comment->comment_content,'5');
          $replyuri = home_url('/author/');
          $content = preg_replace('/\B\@([a-zA-Z0-9_]{1,20})/', '<a class="at" href="'.$replyuri.'$1">$0</a>', $comment->comment_content);
          $title = get_the_title($comment->comment_post_ID);
          $perma = get_comment_link($comment);
      //    $perma = get_permalink($comment->comment_post_ID);
          $share = urlencode($username . "'s thoughts on ") . $title . " " . $text;


          ?>
          <span class="comment-author"><?php comment_author(); ?></span><span class='ph-comment-info'> - <?php echo get_the_author_meta('description',$the_user->ID); ?></span>

          <?php // edit_comment_link('<p class="comment-meta-item">Edit this comment</p>','',''); ?>
          <?php if ($comment->comment_approved == '0') : ?>
          <p class="comment-meta-item"><?php _e('Your comment is awaiting moderation.','ph_theme'); ?></p>
          <?php endif; ?>
        </div>
        <div class="comment-content post-content" itemprop="text">
          <?php echo $content ?>
          <div class='ph-comment-meta'>
            <div class='pull-left'>
              <!--
              <span class='ph-m upvote-comment' data-cid='<?php comment_ID();?>'><span class='ph-up-adj'><i class='fa fa-sort-up'></i></span> upvote </span>
            -->
              <span class='ph-m reply-comment' data-cid='<?php comment_ID(); ?>' data-un='<?php echo $username ; ?>'><i class='fa fa-mail-reply' data-cid='<?php comment_ID(); ?>'></i><?php _e(' Reply','ph_theme'); ?></span>
              <span class='ph-m share-comment'><i class='fa  fa-share-square-o'></i><a class='comment-tweet-link' href='https://twitter.com/intent/tweet?text=<?php echo $share; ?>&amp;url=<?php echo urlencode( $perma ); ?>' title=share>share</a></span>
            </div>
            <div class='pull-right'>
              <span class="ph-m comment-meta-item ph-time-comment" datetime="<?php comment_date('Y-m-d') ?>T<?php comment_time('H:iP') ?>" itemprop="datePublished"><?php printf( _x( '%s ago', '%s = human-readable time difference', 'ph_theme' ), human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) ); ?></time>
            </div>
            <div style='clear:both'></div>
          </div>
        </div>

      </div>

    <?php }

    // end_el – closing HTML for comment template
    function end_el(&$output, $comment, $depth = 0, $args = array() ) { ?>

      </article>

    <?php }

    // destructor – closing wrapper for the comments list
    function __destruct() { ?>

      </section>
    
    <?php }

  }

#} Version 3.6 comes with collections. Managed as custom posts.
function ph_collections_init() {
  $labels = array(
    'name'               => _x( 'Collections', 'post type general name', 'ph_theme' ),
    'singular_name'      => _x( 'Collection', 'post type singular name', 'ph_theme' ),
    'menu_name'          => _x( 'Collections', 'admin menu', 'ph_theme' ),
    'name_admin_bar'     => _x( 'Collection', 'add new on admin bar', 'ph_theme' ),
    'add_new'            => _x( 'Add New', 'blog', 'ph_theme' ),
    'add_new_item'       => __( 'Add New Collection', 'ph_theme' ),
    'new_item'           => __( 'New Collection', 'ph_theme' ),
    'edit_item'          => __( 'Edit Collection', 'ph_theme' ),
    'view_item'          => __( 'View Collection', 'ph_theme' ),
    'all_items'          => __( 'All Collections', 'ph_theme' ),
    'search_items'       => __( 'Search Collection', 'ph_theme' ),
    'parent_item_colon'  => __( 'Parent Collection:',  'ph_theme' ),
    'not_found'          => __( 'No collections found.', 'ph_theme' ),
    'not_found_in_trash' => __( 'No collections found in Trash.', 'ph_theme' )
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'collections' ),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
    'menu_icon'          => 'dashicons-feedback'
  );

  register_post_type( 'collections', $args );
}
add_action( 'init', 'ph_collections_init' );


//AJAX function which creates the collection
add_action('wp_ajax_nopriv_ph_create_collection','ph_create_collection');
add_action( 'wp_ajax_ph_create_collection', 'ph_create_collection' );
function ph_create_collection(){
  //get the current users ID (just to be safe)
  $uid    =   get_current_user_id();
  if($uid == 0){
    die();
  }
  $name   =   $_POST['name'];
  $prod   =   $_POST['prod'];   //product ID for the product
  $pname  =   get_the_title( $prod );
  
  $ptype = 'collections';
  $status = 'publish';

    $post = array(
    'post_title'     => $name, 
    'post_status'    => $status,
    'post_type'      => $ptype,
    'post_author'    => $uid
  );  

    $wid = wp_insert_post( $post, $wp_error );

    write_log($wp_error);

    add_post_meta($wid,'ph_collected_posts',$prod); //add the product to this users collection (easy for the first product in a new collection).

    $user_collected       = get_user_meta($uid,'ph_collected_posts',true);
    $user_collected_array = explode(",",$user_collected);

    //this time check if the user has collected this anywhere (useful for marking the index)
    if(!in_array($prod,$user_collected_array)){
      array_push($user_collected_array, $prod);
    }else{
      write_log('in the array');
    }

    $user_collected_new = implode(",",$user_collected_array); // implode back to a collection csv meta.
    update_user_meta($uid,'ph_collected_posts',$user_collected_new);

    $response['user'] = $uid;
    $response['name'] = $name;
    $response['collection'] = $wid;
    $response['html'] = '<div class="collections-html">"'.$pname.'" has been added to your collection <a href="'.get_permalink($wid).'">"'.$name.'"</a></div>';

    echo json_encode($response);
    die();

}

function ph_in_user_collection($id){
  $uid = get_current_user_id();
  $uc = get_user_meta($uid,'ph_collected_posts',true);
  $uca = explode(',',$uc);
  if(in_array($id,$uca)){
    return true;
  }else{
    return false;
  }

}

add_action('wp_ajax_nopriv_ph_delete_collection','ph_delete_collection');
add_action( 'wp_ajax_ph_delete_collection', 'ph_delete_collection' );
function ph_delete_collection(){
  global $wpdb;
  $uid    =   get_current_user_id();
  $cid   =   (int)$_POST['cid'];   

  if($uid == 0){
    die();
  }

  delete_post_meta($cid,'ph_collected_posts'); //delete the meta.
  $query = $wpdb->prepare("DELETE FROM $wpdb->posts WHERE ID = %d AND post_author = %d AND post_type ='collections'", $cid, $uid);
  $wpdb->query($query);

  //check the user array, loop through existing collections create an array with unique elements and re-save the user meta
  $query = $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_author = %d AND post_type ='collections'", $uid);
  $collections = $wpdb->get_results($query);

  //total collection array 
  $total_collected = array();
  foreach($collections as $collection){
    $collected = get_post_meta($collection->ID,'ph_collected_posts',true);
    $collected_array = explode(',',$collected);
    $total_collected = array_merge($collected_array,$total_collected);
  } 

  //make array unique and save down into user meta
  $user_collected_array = array_unique($total_collected);
  $user_collected_new = implode(",",$user_collected_array); // implode back to a collection csv meta.
  update_user_meta($uid,'ph_collected_posts',$user_collected_new);

  $response['total'] = $total_collected;
  $response['collected'] = $user_collected_array;
  $response['html'] = 'Completed';
  echo json_encode($response);
  die();
}

add_filter( 'wp_title', 'ph_hack_wp_title_for_home' );
function ph_hack_wp_title_for_home( $title )
{
  if( empty( $title ) && ( is_home() || is_front_page() ) ) {
    return get_bloginfo( 'title' ) . ' | ' . get_bloginfo( 'description' );
  }
  return $title;
}

//limit search to posts
function phsearchfilter($query) {

    if ($query->is_search && !is_admin() ) {
        $query->set('post_type',array('post','page'));
    }

return $query;
}
add_filter('pre_get_posts','phsearchfilter');

add_action('wp_ajax_nopriv_ph_remove_from_collection','ph_remove_from_collection');
add_action( 'wp_ajax_ph_remove_from_collection', 'ph_remove_from_collection' );
function ph_remove_from_collection(){
  global $wpdb;
  $uid    =   get_current_user_id();
  if($uid == 0){
    die();  //die if not logged in. 
  }
  $pid   =   (int)$_POST['pid'];
  $cid   =   (int)$_POST['cid'];   
  $collected            = get_post_meta($cid,'ph_collected_posts',true);
  $user_collected       = get_user_meta($uid,'ph_collected_posts',true);

  $collected_array      = explode(",",$collected);
  $user_collected_array = explode(",",$user_collected);

  if(($key = array_search($pid, $collected_array)) !== false) {
    unset($collected_array[$key]);    //removes the post from the collected_array();
  }
  $collected_new = implode(",",$collected_array); // implode back to a collection csv meta.
  update_post_meta($cid,'ph_collected_posts',$collected_new);

  //the user array updated from collections
  //check the user array, loop through existing collections create an array with unique elements and re-save the user meta
  $query = $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_author = %d AND post_type ='collections'", $uid);
  $collections = $wpdb->get_results($query);
  $total_collected = array();
  
  foreach($collections as $collection){
    $collected = get_post_meta($collection->ID,'ph_collected_posts',true);
    $collected_array = explode(',',$collected);
    $total_collected = array_merge($collected_array,$total_collected);
  } 


  //make array unique and save down into user meta
  $user_collected_array = array_unique($total_collected);
  $user_collected_new = implode(",",$user_collected_array); // implode back to a collection csv meta.
  update_user_meta($uid,'ph_collected_posts',$user_collected_new);

  $pname  =   get_the_title($pid);
  $cname  =   get_the_title($cid);
  add_post_meta($wid,'ph_collected_posts',$prod); //add the product to this users collection (easy for the first product in a new collection).
  
  $response['uid'] = $uid;
  $response['collections'] = $collections;
  $response['collected_old'] = $collected;
  $response['collected_new'] = $collected_new;
  $response['collected_total'] = $user_collected_new;

  $response['html'] = '<div class="collections-html">"'.$pname.'" was removed from your collection <a href="'.get_permalink($cid).'">"'.$cname.'"</a></div>';
  echo json_encode($response);
  die();
}

//AJAX function which creates the collection header image as featured...
add_action('wp_ajax_nopriv_ph_update_collection_bgimg','ph_update_collection_bgimg');
add_action( 'wp_ajax_ph_update_collection_bgimg', 'ph_update_collection_bgimg' );
function ph_update_collection_bgimg(){
    $aid      = (int)$_POST['aid'];
    $cid      = (int)$_POST['cid'];

    $uid      = get_current_user_id();
    $post_tmp = get_post($cid);
    $author_id = $post_tmp->post_author;

    if($uid == $author_id){
    set_post_thumbnail($cid, $aid);
    $response['msg']     = 'all OK for the title';
    }else{
      $response['msg'] = 'nope';
      wp_mail('mike@epicplugins.com','someone tried to change image who was not collection owner');
    }

    echo json_encode($response);
    die();
}

//AJAX function which adds media to the post
add_action('wp_ajax_nopriv_ph_newmedia','ph_newmedia');
add_action( 'wp_ajax_ph_newmedia', 'ph_newmedia' );
function ph_newmedia(){
    $pid      = (int)$_POST['pid'];
    $vid      = sanitize_text_field($_POST['vid'] );
    $src      = sanitize_text_field( $_POST['src'] );
    $img      = esc_url($_POST['img']);

    $media = get_post_meta($pid,'phmedia',true);
    $media_array = json_decode($media); 



    if($media_array == ''){
      $media_array = array();
    }

    write_log("media array");
    write_log($media_array);

    $item['url']    = esc_url($img);
    $item['source'] = $src;
    $item['id']     = $vid;
  
    $media_array[] = $item;

    write_log("media array pushed");
    write_log($media_array);

    $media = json_encode($media_array);

    

    $uid      = get_current_user_id();
    $post_tmp = get_post($pid);
    $author_id = $post_tmp->post_author;

    if($uid == $author_id){
      update_post_meta($pid,'phmedia',$media);
      $response['msg']     = 'all OK for the title';
      $response['item'] = $item;
      $response['newmedia'] = $media_array;
      $response['src'] = $src;
    }else{
      $response['msg'] = 'nope';
      wp_mail('mike@epicplugins.com','someone tried to add media to the wrong post');
    }

    echo json_encode($response);
    die();
}


//AJAX function which creates the collection
add_action('wp_ajax_nopriv_ph_update_collection_title','ph_update_collection_title');
add_action( 'wp_ajax_ph_update_collection_title', 'ph_update_collection_title' );
function ph_update_collection_title(){
    $slug         = sanitize_text_field($_POST['title']);
    $post_ID      = (int)$_POST['cid'];
    $post_status  = 'publish';
    $post_type    = 'collections';
    $post_parent  = 0;

    $cid = get_current_user_id();
    if($cid == 0){
      die(); //die if logged out (user = 0)
    }

    $new_slug     = wp_unique_post_slug( $slug, $post_ID, $post_status, $post_type, $post_parent );
    write_log('new post slug ' . $new_slug);
    $my_post = array('ID'=> $post_ID,'post_name' => $new_slug,'post_title' => $slug);
    $w = wp_update_post( $my_post);
    $response['msg']     = 'all OK for the title';
    $response['newslug'] = $new_slug;
    $response['title'] = $slug;
    echo json_encode($response);
    die();
}

//AJAX function which creates the collection
add_action('wp_ajax_nopriv_ph_update_collection_desc','ph_update_collection_desc');
add_action( 'wp_ajax_ph_update_collection_desc', 'ph_update_collection_desc' );
function ph_update_collection_desc(){
    $cid = get_current_user_id();
    if($cid == 0){
      die(); //die if logged out (user = 0)
    }
    $slug         = sanitize_text_field($_POST['title']);
    $post_ID      = (int)$_POST['cid'];
    $my_post = array('ID'=> $post_ID, 'post_content' => $slug);
    wp_update_post( $my_post);
    $response['msg'] = 'all OK';
    $response['title'] = $slug;
    $response['ID'] = $post_ID;
    echo json_encode($response);
    die();
}


//AJAX function which creates the collection
add_action('wp_ajax_nopriv_ph_add_collection','ph_add_collection');
add_action( 'wp_ajax_ph_add_collection', 'ph_add_collection' );
function ph_add_collection(){
  $uid    =   get_current_user_id();
  $pid   =   (int)$_POST['pid'];
  $cid   =   (int)$_POST['cid'];  

  if($uid == 0){
    die(); //die if user logged out. 
  }

  $collected            = get_post_meta($cid,'ph_collected_posts',true);
  $user_collected       = get_user_meta($uid,'ph_collected_posts',true);
  $collected_array      = explode(",",$collected);
  $user_collected_array = explode(",",$user_collected);
  if(!in_array($pid,$collected_array)){
    array_push($collected_array, $pid);
  }else{
    write_log('in the array');
  }
  if(!in_array($pid,$user_collected_array)){
    array_push($user_collected_array, $pid);
  }else{
    write_log('in the array');
  }
  $collected_new = implode(",",$collected_array); // implode back to a collection csv meta.
  update_post_meta($cid,'ph_collected_posts',$collected_new);
  $user_collected_new = implode(",",$user_collected_array); // implode back to a collection csv meta.
  update_user_meta($uid,'ph_collected_posts',$user_collected_new);
  $pname  =   get_the_title($pid);
  $cname  =   get_the_title($cid);
  add_post_meta($wid,'ph_collected_posts',$prod); //add the product to this users collection (easy for the first product in a new collection).
  $response['collected_old'] = $collected;
  $response['collected_new'] = $collected_new;
  $response['html'] = '<div class="collections-html">"'.$pname.'" has been added to your collection <a href="'.get_permalink($cid).'">"'.$cname.'"</a></div>';
  echo json_encode($response);
  die();
}

add_action('wp_ajax_nopriv_ph_list_collections','ph_list_collections');
add_action( 'wp_ajax_ph_list_collections', 'ph_list_collections' );
function ph_list_collections(){
   global $wpdb;
  $user = get_current_user_id();

  $post = (int)$_POST['pid'];
  $collectionsHTML = '<ul class="collections-popover--collections popover--scrollable-list">';
  $query = $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_author = %d AND post_type ='collections'", $user);
  $collections = $wpdb->get_results($query);
  foreach($collections as $collection){
    $collected = get_post_meta($collection->ID,'ph_collected_posts',true);
    $collected_array = explode(',',$collected);
    $in = in_array($post,$collected_array);   //is this post in the collection, if so, show the collected icon
    $cid = $collection->ID;
    if($in){
      $collectionsHTML .='<li><span class="collections-popover--collection ph-remove-from-collection popover--scrollable-list--element" href="#" data-cid="'.$collection->ID.'" data-pid="'.$post.'"><span>' . $collection->post_title . '</span><span class="collections-popover--collection--icon v-collected">';
      $collectionsHTML .= ph_in_collection($post,$cid);
    }else{
      $collectionsHTML .='<li><span class="collections-popover--collection ph-add-to-collection popover--scrollable-list--element" href="#" data-cid="'.$collection->ID.'" data-pid="'.$post.'"><span>' . $collection->post_title . '</span><span class="collections-popover--collection--icon v-collect">';
      $collectionsHTML .= ph_out_collection($post,$cid);
    }
    $collectionsHTML .=  '</span></span></li>';
  }
  $collectionsHTML .= '  </ul>';
  $response['html'] = $collectionsHTML;
  $response['message'] = 'this has reached here';
  echo json_encode($response);
  die();  // for when we do this AJAX wise...
  
}


function ph_in_collection($post,$cid){
   ob_start();
  ?>
  <span class="in-collection collections-popover--collection--icon v-collected" data-post = <?php echo $post; ?> data-collect='<?php echo $cid; ?>'>
  <svg width="17" height="14" viewBox="0 0 17 14" xmlns="http://www.w3.org/2000/svg">
      <path d="M11.036 10.864L9.62 9.45c-.392-.394-1.022-.39-1.413 0-.393.393-.39 1.023 0 1.414l2.122 2.12c.193.198.45.295.703.295.256 0 .51-.1.706-.295l4.246-4.246c.385-.385.39-1.02-.002-1.413-.393-.393-1.022-.39-1.412-.002l-3.537 3.538zM0 1c0-.552.447-1 1-1h11c.553 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.553 0-1-.444-1-1zm0 5c0-.552.447-1 1-1h11c.553 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.553 0-1-.444-1-1zm0 5c0-.552.447-1 1-1h4.5c.552 0 1 .444 1 1 0 .552-.447 1-1 1H1c-.552 0-1-.444-1-1z" fill="#DC5425" fill-rule="evenodd"></path>
  </svg>
  </span>
  <?php
  return ob_get_clean();
}
function ph_out_collection($post,$cid){
   ob_start();
  ?>
  <span class="out-collection collections-popover--collection--icon v-collect" data-post = <?php echo $post; ?> data-collect='<?php echo $cid; ?>'>
    <svg width="15" height="14" viewBox="0 0 15 14" xmlns="http://www.w3.org/2000/svg">
      <path d="M13 10V8.99c0-.54-.448-.99-1-.99-.556 0-1 .444-1 .99V10H9.99c-.54 0-.99.448-.99 1 0 .556.444 1 .99 1H11v1.01c0 .54.448.99 1 .99.556 0 1-.444 1-.99V12h1.01c.54 0 .99-.448.99-1 0-.556-.444-1-.99-1H13zM0 1c0-.552.447-1 .998-1h11.004c.55 0 .998.444.998 1 0 .552-.447 1-.998 1H.998C.448 2 0 1.556 0 1zm0 5c0-.552.447-1 .998-1h11.004c.55 0 .998.444.998 1 0 .552-.447 1-.998 1H.998C.448 7 0 6.556 0 6zm0 5c0-.552.453-1 .997-1h6.006c.55 0 .997.444.997 1 0 .552-.453 1-.997 1H.997C.447 12 0 11.556 0 11z" fill="#C8C0B1" fill-rule="evenodd"></path>
    </svg>
  </span>
  <?php
  return ob_get_clean();
}

#} Version 3.3 comes with a built in blog
function ph_blog_init() {
  $labels = array(
    'name'               => _x( 'Blogs', 'post type general name', 'ph_theme' ),
    'singular_name'      => _x( 'Blog', 'post type singular name', 'ph_theme' ),
    'menu_name'          => _x( 'Blog', 'admin menu', 'ph_theme' ),
    'name_admin_bar'     => _x( 'Blog', 'add new on admin bar', 'ph_theme' ),
    'add_new'            => _x( 'Add New', 'blog', 'ph_theme' ),
    'add_new_item'       => __( 'Add New Blog', 'ph_theme' ),
    'new_item'           => __( 'New Blog', 'ph_theme' ),
    'edit_item'          => __( 'Edit Blog', 'ph_theme' ),
    'view_item'          => __( 'View Blog', 'ph_theme' ),
    'all_items'          => __( 'All Blogs', 'ph_theme' ),
    'search_items'       => __( 'Search Blog', 'ph_theme' ),
    'parent_item_colon'  => __( 'Parent Blog:',  'ph_theme' ),
    'not_found'          => __( 'No blogs found.', 'ph_theme' ),
    'not_found_in_trash' => __( 'No blogs found in Trash.', 'ph_theme' )
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'blog' ),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
    'menu_icon'          => 'dashicons-edit'
  );

  register_post_type( 'blog', $args );
}
add_action( 'init', 'ph_blog_init' );


/* New Features for v3.4 - firming up the login page */
function ph_login_logo() { ?>
    <style type="text/css">
        .login h1 a {
            background-image: url(<?php echo get_theme_mod( 'pluginhunt_logo' )?>);
        }
        html{
          background-color:<?php echo pluginhunt_sanitize_hex_color( get_theme_mod( 'pluginhunt_link_color' ) );?>;
        }
        body.login {
          background-color:<?php echo pluginhunt_sanitize_hex_color( get_theme_mod( 'pluginhunt_link_color' ) );?>;
          font-size: 17px;
          display: block;
          font-family: proxima-nova, 'Proxima Nova', sans-serif;
        }
        h3{
          color:white;
          text-align: center;
        }
        .ph-tw {
            background-color: #00c3f3;
            color: #fff;
            padding: 10px;
            font-size:14px;
            font-weight:100;
            width:245px;
            text-align: center;
            margin: auto;
        }
        .ph-fb {
              background-color: #2d609b;
              color: #fff;
              padding: 10px;
              font-size:14px;
              font-weight:100;
              width:245px;
              text-align: center;
              margin: auto;
          }
        .fa {
          color: #fff;
          margin-right: 5px;
          padding: 5px;
        }
        .newsociallogins a{
            text-decoration: none;
        }
        #loginform h3{
          display:none;
        }
        .login #backtoblog a, .login #nav a {
          text-decoration: none;
          color: #fff;
          text-align:center;
        }
        .login #backtoblog a:hover, .login #nav a:hover {
          text-decoration: none;
          color: #fff;
          text-align:center;
        }
        .login #nav {
          margin: 24px 0 0;
          text-align: center;
          color: #fff;
        }
        .login #backtoblog, .login #nav {
          font-size: 13px;
          padding: 0 24px;
          text-align: center;
        }
        #login {
          width: 320px;
          padding: 2% 0 0;
          margin: auto;
        }
        .wp-core-ui .button-group.button-large .button, .wp-core-ui .button.button-large {
          background: none repeat scroll 0 0 <?php echo pluginhunt_sanitize_hex_color( get_theme_mod( 'pluginhunt_link_color' ) );?>;
          color: #FFFFFF;
          border: 0 none;
          border-radius: 0px 0px 0px 0px;
          cursor: pointer;
          display: inline-block;
          font-family: Arial,sans-serif;
          font-size: 12px;
          font-weight: bold;
          line-height: 17px;
          margin-bottom: 0;
          margin-top: 10px;
          padding: 7px 10px;
          text-transform: none;
          transition: all 0.3s ease 0s;
          -moz-transition: all 0.3s ease 0s;
          -webkit-transition: all 0.3s ease 0s;
          box-shadow: none;
        }
        .login form {
          margin-top: 20px;
          margin-left: 0;
          padding: 26px 24px 24px;
          font-weight: 400;
          overflow: hidden;
          background: #fff;
          -webkit-box-shadow: 0 1px 3px rgba(0,0,0,.13);
          box-shadow: 0 1px 3px rgba(0,0,0,.13);
        }
    </style>
<?php
  wp_enqueue_style('ph_font_a', get_template_directory_uri() . '/css/font-awesome.min.css' );
 }
add_action( 'login_enqueue_scripts', 'ph_login_logo' );


function ph_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'ph_login_logo_url' );

function ph_login_logo_url_title() {
    return 'Plugin Hunt';
}
add_filter( 'login_headertitle', 'ph_login_logo_url_title' );

function ph_custom_login_message() {
 $site_description = get_bloginfo( 'description' );
$message = "<h3 class='ph-info'>". $site_description ."</h3>";
return  $message;
}
add_filter('login_message', 'ph_custom_login_message');





#} Theme supports
add_theme_support( 'automatic-feed-links' );

add_action( 'after_switch_theme', 'ph_flush_rewrite_rules' );
function ph_flush_rewrite_rules() {
     flush_rewrite_rules();
}

function ph_theme_settings() {
    if (isset($_POST["update_settings"])) {
      global $wpdb;
      $querystr = "SELECT ID from $wpdb->posts WHERE post_status = 'publish'";
      $pageposts = $wpdb->get_results($querystr, OBJECT);
      foreach($pageposts as $ppost){
          if(get_post_meta($ppost->ID,'epicredvote',true)==''){
            update_post_meta($ppost->ID,'epicredvote',0);
            echo "Post updated " . $ppost->ID;
          }
      }
     ?>
        <div id="message" style="padding:10px" class="updated"><?php _e('History Updated', 'ph_theme'); ?></div>
   <?php }
    ?>
    <div class="wrap">
        <h2><?php _e("Admin Tools", "ph_theme"); ?></h2>
 
        <form method="POST" action="">
            <label for="num_elements">
            <?php _e('Update post records to ensure all posts are displayed','ph_theme'); ?>
              <i><?php _e('Only needed if upgrading from an earlier version','ph_theme'); ?></i>
            </label> 
            <input type="hidden" name="update_settings" value="Y" />
            <p>
            <input type="submit" value="<?php _e('Update History','ph_theme'); ?>" class="button-primary"/>
            </p>
        </form>
    </div>
    <?php
}

#} Version 1.6 fixing the post meta for votes and ranking if not set
function epic_vote_updated( $post_id ) {

  // If this is just a revision, don't update.
  if ( wp_is_post_revision( $post_id ) )
    return;
  $votes = get_post_meta($post_id,'epicredvote',true);
  if($votes == ''){
    update_post_meta($post_id,'epicredvote',0);
  }

}
add_action( 'save_post', 'epic_vote_updated' );


//second page only will use this function...
function findPrevious($y, $m, $d, $dateList){
        foreach ($dateList as $key=>$object){
            if ($object->year == $y && $object->month == $m && $object->dayofmonth == $d){
                $propose = $key + 1;
                if ($propose > count($dateList)-1 ){
                    $propose = NULL;
                }
                return $propose;
            }
        }
        return NULL;
}


function epic_s($array, $y,$m,$d) {
    $array = array_reverse($array);
    $search = new DateTime( "$y-$m-$d" );
    foreach($array as $k=>$v) {
        $current = new DateTime( "{$v->year}-{$v->month}-{$v->dayofmonth}" );
        if ( $current < $search )
          return $k-1;
    }
    // otherwise return false, you didn't find any correct date
    return -1;
}

function ph_widgets_init() {

  register_sidebar( array(
    'name'          => 'Right sidebar',
    'id'            => 'sidebar-1',
    'before_widget' => '<div>',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="rounded">',
    'after_title'   => '</h2>',
  ) );

  register_sidebar( array(
    'name'          => 'Home sidebar',
    'id'            => 'sidebar-2',
    'before_widget' => '<div>',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="rounded">',
    'after_title'   => '</h2>',
  ) );
}
add_action( 'widgets_init', 'ph_widgets_init' );


#} Maker Meta Box
function ph_maker_box_markup()
{
///enter a CSV list of makers (website usernames)   
}
 
function add_ph_maker_meta_box()
{
    add_meta_box("ph-maker-meta-box", "Maker", "ph_maker_box_markup", "post", "side", "high", null);
}
 
// add_action("add_meta_boxes", "add_ph_maker_meta_box");  post poned to v3.7




#} Sticy Posts
/**
 * Adds a meta box to the post editing screen
 */
function ph_featured_meta() {
    add_meta_box( 'ph_meta', __( 'Featured', 'ph_theme' ), 'ph_meta_callback', 'collections', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'ph_featured_meta' );
 
/**
 * Outputs the content of the meta box
 */
 
function ph_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'ph_nonce' );
    $ph_stored_meta = get_post_meta( $post->ID );
    ?>
 
 <p>
    <span class="ph-row-title"><?php _e( 'Check if this is a featured collection: ', 'ph_theme' )?></span>
    <div class="ph-row-content">
        <label for="featured-checkbox">
            <input type="checkbox" name="featured-product" id="featured-product" value="1" <?php if ( isset ( $ph_stored_meta['featured-product'] ) ) checked( $ph_stored_meta['featured-product'][0], '1' ); ?> />
            <?php _e( 'Featured', 'ph_theme' )?>
        </label>
 
    </div>
</p>   
 
    <?php
}
 
/**
 * Saves the custom meta input
 */
function ph_meta_save( $post_id ) {
 
    // Checks save status - overcome autosave, etc.
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'ph_nonce' ] ) && wp_verify_nonce( $_POST[ 'ph_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
 
// Checks for input and saves - save checked as yes and unchecked at no
if( isset( $_POST[ 'featured-product' ] ) ) {
    update_post_meta( $post_id, 'featured-product', '1' );
} else {
    update_post_meta( $post_id, 'featured-product', '0' );
}
 
}
add_action( 'save_post', 'ph_meta_save' );

add_theme_support('html5', array('search-form'));


#} Localisation files
load_theme_textdomain('ph_theme', get_template_directory() . '/languages');

add_action('after_setup_theme', 'ph_theme_setup');
function ph_theme_setup(){
    load_theme_textdomain('ph_theme', get_template_directory() . '/languages');
}

#} add support for thumbmails
add_theme_support('post-thumbnails');

#} lets add a menu (for the ... icon)
add_action( 'after_setup_theme', 'register_my_menu' );
function register_my_menu() {
  register_nav_menu( 'primary', 'Primary Menu' );
  register_nav_menu( 'secondary','Secondary Menu');
}

#} and its associated Walker Class [v2.0 move into /inc/ folder]
class Bootstrap_Walker extends Walker_Nav_Menu 
    {     
     
        /* Start of the <ul> 
         * 
         * Note on $depth: Counterintuitively, $depth here means the "depth right before we start this menu".  
         *                   So basically add one to what you'd expect it to be 
         */         
        function start_lvl(&$output, $depth) 
        {
            $tabs = str_repeat("\t", $depth); 
            // If we are about to start the first submenu, we need to give it a dropdown-menu class 
            if ($depth == 0 || $depth == 1) { //really, level-1 or level-2, because $depth is misleading here (see note above) 
                $output .= "\n{$tabs}<ul class=\"dropdown-menu\">\n"; 
            } else { 
                $output .= "\n{$tabs}<ul>\n"; 
            } 
            return;
        } 
         
        /* End of the <ul> 
         * 
         * Note on $depth: Counterintuitively, $depth here means the "depth right before we start this menu".  
         *                   So basically add one to what you'd expect it to be 
         */         
        function end_lvl(&$output, $depth)  
        {
            if ($depth == 0) { // This is actually the end of the level-1 submenu ($depth is misleading here too!) 
                 
                // we don't have anything special for Bootstrap, so we'll just leave an HTML comment for now 
                $output .= '<!--.dropdown-->'; 
            } 
            $tabs = str_repeat("\t", $depth); 
            $output .= "\n{$tabs}</ul>\n"; 
            return; 
        }
                 
        /* Output the <li> and the containing <a> 
         * Note: $depth is "correct" at this level 
         */         
        function start_el(&$output, $item, $depth, $args)  
        {    
            global $wp_query; 
            $indent = ( $depth ) ? str_repeat( "\t", $depth ) : ''; 
            $class_names = $value = ''; 
            $classes = empty( $item->classes ) ? array() : (array) $item->classes; 

            /* If this item has a dropdown menu, add the 'dropdown' class for Bootstrap */ 
            if ($item->hasChildren) { 
                $classes[] = 'dropdown'; 
                // level-1 menus also need the 'dropdown-submenu' class 
                if($depth == 1) { 
                    $classes[] = 'dropdown-submenu'; 
                } 
            } 

            /* This is the stock Wordpress code that builds the <li> with all of its attributes */ 
            $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ); 
            $class_names = ' class="' . esc_attr( $class_names ) . '"'; 
            $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';             
            $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : ''; 
            $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : ''; 
            $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : ''; 
            $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : ''; 
            #WHFIX 24/03/2015: 
            # This would work if it was an OBJECT, but it's an array... 
            $item_output = ''; if (isset($args)) $item_output = $args->before; 
            # Fixed: 
            #$item_output = ''; if (isset($args)) $item_output = $args['before']; 
        
                         
            /* If this item has a dropdown menu, make clicking on this link toggle it */ 
            if ($item->hasChildren && $depth == 0) { 
                $item_output .= '<a'. $attributes .' class="dropdown-toggle" data-toggle="dropdown">'; 
            } else { 
                $item_output .= '<a'. $attributes .'>'; 
            } 
             
            #WHFIX 24/03/2015: 
            # This would work if it was an OBJECT, but it's an array... 
            if (isset($args)) $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after; 
            # Fixed: 
            # if (isset($args)) $item_output .= $args['link_before'] . apply_filters( 'the_title', $item->title, $item->ID ) . $args['link_after']; 

            /* Output the actual caret for the user to click on to toggle the menu */             
            if ($item->hasChildren && $depth == 0) { 
                $item_output .= '<b class="caret"></b></a>'; 
            } else { 
                $item_output .= '</a>'; 
            } 

            #WHFIX 24/03/2015: 
            # This would work if it was an OBJECT, but it's an array... 
            if (isset($args)) $item_output .= $args->after; 
            # Fixed:
            #if (isset($args)) $item_output .= $args['after']; 

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args ); 
            return; 
        }
        
        /* Close the <li> 
         * Note: the <a> is already closed 
         * Note 2: $depth is "correct" at this level 
         */         
        function end_el (&$output, $item, $depth, $args)
        {
            $output .= '</li>'; 
            return;
        } 
         
        /* Add a 'hasChildren' property to the item 
         * Code from: http://wordpress.org/support/topic/how-do-i-know-if-a-menu-item-has-children-or-is-a-leaf#post-3139633  
         */ 
        function display_element ($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) 
        { 
            // check whether this item has children, and set $item->hasChildren accordingly 
            $element->hasChildren = isset($children_elements[$element->ID]) && !empty($children_elements[$element->ID]); 

            // continue with normal behavior 
            return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output); 
        }         
    } 


  function epic_posts_nav() {

  global $wp_query;

  /** Stop execution if there's only 1 page */
  if( $wp_query->max_num_pages <= 1 )
    return;

  $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
  $max   = intval( $wp_query->max_num_pages );

  /** Add current page to the array */
  if ( $paged >= 1 )
    $links[] = $paged;

  /** Add the pages around the current page to the array */
  if ( $paged >= 3 ) {
    $links[] = $paged - 1;
    $links[] = $paged - 2;
  }

  if ( ( $paged + 2 ) <= $max ) {
    $links[] = $paged + 2;
    $links[] = $paged + 1;
  }

  echo '<div class="navigation" id="nav-below"><ul>' . "\n";

  /** Previous Post Link */
  if ( get_previous_posts_link() )
    printf( '<li>%s</li>' . "\n", get_previous_posts_link() );

  /** Link to first page, plus ellipses if necessary */
  if ( ! in_array( 1, $links ) ) {
    $class = 1 == $paged ? ' class="active"' : '';

    printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

    if ( ! in_array( 2, $links ) )
      echo '<li>...</li>';
  }

  /** Link to current page, plus 2 pages in either direction if necessary */
  sort( $links );
  foreach ( (array) $links as $link ) {
    $class = $paged == $link ? ' class="active"' : '';
    printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
  }

  /** Link to last page, plus ellipses if necessary */
  if ( ! in_array( $max, $links ) ) {
    if ( ! in_array( $max - 1, $links ) )
      echo '<li>...</li>' . "\n";

    $class = $paged == $max ? ' class="active"' : '';
    printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
  }

  /** Next Post Link */
  if ( get_next_posts_link() )
    printf( '<li>%s</li>' . "\n", get_next_posts_link() );

  echo '</ul></div>' . "\n";

}

error_reporting(E_ALL & ~E_NOTICE);

add_action('wp_ajax_nopriv_ph_access_request','ph_access_request');
add_action( 'wp_ajax_ph_access_request', 'ph_access_request' );
function ph_access_request(){
  $uid = $_POST['uid'];
  update_user_meta($uid,'ph_access_request', 1);  
  die();

}



add_action( 'wp_ajax_nopriv_ph_newpost', 'ph_newpost' );
add_action( 'wp_ajax_ph_newpost', 'ph_newpost' );
function ph_newpost(){
  
  //sanitise stuff
  $title          = $_POST['name'];
  $title          = sanitize_post_field( 'post_title', $title,'db' );

  $slug           = sanitize_title($_POST['name']);
  $url            = esc_url($_POST['url']);
  $desc           = sanitize_text_field($_POST['tag']);
  $media          = json_encode($_POST['media']);

  $slug     = wp_unique_post_slug( $slug );

  $image = $_POST['media'][0]['url'];
  if($_POST['media'][0]['source'] == 'yt'){
    $image = $_POST['media'][0]['image'];
    $islug = $_POST['media'][0]['id'];
  }else{
    $islug = '';
  }

  $current_user   = wp_get_current_user();
  $uid            = $current_user->ID;
  $type = get_option('wpedditnewpost', true);
  if($type == 'published'){
    $status = 'publish';
  }else{
    $status = 'pending';
  }
  $ptype = 'post';

  $post = array(
    'post_content'   => $desc, 
    'post_title'     => $title, 
    'post_status'    => $status,
    'post_type'      => $ptype,
    'post_author'    => $uid,
    'post_name'      => $slug
  );  

    $wid = wp_insert_post( $post, $wp_error );
    update_post_meta($wid, 'outbound', $url);


    update_post_meta($wid,'phmedia',$media);

    update_post_meta($wid, 'epicredvote', 0);
    update_post_meta($wid, 'epicredrank',0);

    //set featured image to be from the $featured variable
      if($image){
            
              //extra code to upload the image and set it as the featured image
            $upload_dir = wp_upload_dir();
            $image_data = file_get_contents($image);
            $filename = basename($image);
            if($islug!=''){
              $ext = explode(".",$filename);  
              $filename = $slug . "." . $ext[1]; 
            }

            if(wp_mkdir_p($upload_dir['path']))
                $file = $upload_dir['path'] . '/' . $filename;
            else
                $file = $upload_dir['basedir'] . '/' . $filename;
              file_put_contents($file, $image_data);
              
              $wp_filetype = wp_check_filetype($filename, null );
              $attachment = array(
                  'post_mime_type' => $wp_filetype['type'],
                  'post_title' => sanitize_file_name($filename),
                  'post_content' => '',
                  'post_status' => 'inherit'
              );
              $attach_id = wp_insert_attachment( $attachment, $file, $wid);
              require_once(ABSPATH . 'wp-admin/includes/image.php');
              $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
              wp_update_attachment_metadata( $attach_id, $attach_data );
            
              set_post_thumbnail($wid, $attach_id ); 
              update_post_meta($wid, 'epic_externalURL', $image );
              
          } 

    $current_user = wp_get_current_user();
    update_user_meta($current_user->ID, 'ehacklast', time());

  $response['success'] = 'post added';
  $response['slug'] = $slug;
  $response['featured'] = $image;
  echo json_encode($response); 
  exit;
}

/* Fire our meta box setup function on the post editor screen. */
add_action( 'load-post.php', 'ph_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'ph_post_meta_boxes_setup' );

/* Meta box setup function. */
function ph_post_meta_boxes_setup() {
  /* Add meta boxes on the 'add_meta_boxes' hook. */
  add_action( 'add_meta_boxes', 'ph_add_post_meta_boxes' );
  /* Save post meta on the 'save_post' hook. */
  add_action( 'save_post', 'ph_save_post_class_meta', 10, 2 );
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
function ph_add_post_meta_boxes() {

  add_meta_box(
    'ph-post-class',      // Unique ID
    esc_html__( 'External Link' ),    // Title
    'ph_post_class_meta_box',   // Callback function
    'post',         // Admin page (or post type)
    'normal',         // Context
    'low'         // Priority
  );
}

/* Display the post meta box. */
function ph_post_class_meta_box( $object, $box ) { ?>

  <?php wp_nonce_field( basename( __FILE__ ), 'ph_post_class_nonce' ); ?>

  <p>
    <label for="ph-post-class"><?php _e( "External link that this post title redirects to",'ph_theme' ); ?></label>
    <br />
    <input class="widefat" type="text" name="ph-post-class" id="ph-post-class" value="<?php echo esc_attr( get_post_meta( $object->ID, 'outbound', true ) ); ?>" size="30" />
  </p>
<?php }

/* Save the meta box's post metadata. */
function ph_save_post_class_meta( $post_id, $post ) {

  /* Verify the nonce before proceeding. */
  if ( !isset( $_POST['ph_post_class_nonce'] ) || !wp_verify_nonce( $_POST['ph_post_class_nonce'], basename( __FILE__ ) ) )
    return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );

  /* Check if the current user has permission to edit the post. */
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;

  /* Get the posted data and sanitize it for use as an HTML class. */
  $new_meta_value = $_POST['ph-post-class'];

  /* Get the meta key. */
  $meta_key = 'outbound';

  /* Get the meta value of the custom field key. */
  $meta_value = get_post_meta( $post_id, $meta_key, true );

  /* If a new meta value was added and there was no previous value, add it. */
  if ( $new_meta_value && '' == $meta_value )
    add_post_meta( $post_id, $meta_key, $new_meta_value, true );

  /* If the new meta value does not match the old value, update it. */
  elseif ( $new_meta_value && $new_meta_value != $meta_value )
    update_post_meta( $post_id, $meta_key, $new_meta_value );

  /* If there is no new meta value but an old value exists, delete it. */
  elseif ( '' == $new_meta_value && $meta_value )
    delete_post_meta( $post_id, $meta_key, $meta_value );
}



function get_day_name($timestamp) {
    $date = date('d/m/Y', $timestamp);
    if($date == date('d/m/Y')) {
      $day_name = 'Today';
    } else if($date == date('d/m/Y',now() - (24 * 60 * 60))) {
      $day_name = 'Yesterday';
    }
    return $date;
}


 add_filter('posts_orderby', 'posts_orderby');

  function posts_orderby($orderby_for_query) {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $orderby_for_query = "LEFT(" . $prefix . "posts.post_date, 10) DESC, " . $orderby_for_query;
        return $orderby_for_query;
    } 


//ajax function for flagging posts.
add_action( 'wp_ajax_nopriv_epicred_ajax_flag', 'epicred_ajax_flag' );
add_action( 'wp_ajax_epicred_ajax_flag', 'epicred_ajax_flag' );
function epicred_ajax_flag(){
  //email admin if someone wants it removed....

  $current_user = wp_get_current_user();
  $n = $current_user->display_name;
  $em = get_bloginfo('admin_email');
  $bn = get_bloginfo('name');
  $pid  = (int)$_POST['post'];
  $mes = $_POST['mail'];
  $t = get_the_title( $pid );

  $hu = home_url();
  $perma = $_POST['perma'];
  $link = $hu . "/posts/" . $perma;

  $subject = __('Someone has flagged', 'ph_theme') . " " . $t;
  $on = '<a href="'. $hu .'" class="brand">'. $bn .'</a>';
  $message = __('This is a note to let you know someone has flagged: ', 'ph_theme') . $link . __(' as inappropriate. The reason is:  ', 'ph_theme') . $mes;
  $headers = 'From:' . $n . " via " . $bn . "\r\n";

  wp_mail($em, $subject, $message, $headers );
  $response['pid'] = $pid;
  $response['em'] = $em;
  $response['t'] = $t;

  echo  json_encode($response);

  die();


}

add_filter( 'wp_mail_from', 'ph_wp_mail_from' );
function ph_wp_mail_from( $original_email_address ) {
  $f = get_theme_mod('ph_from', 'noreply@pluginhunt.com');
  return $f;
}

add_filter( 'wp_mail_from_name', 'ph_wp_mail_from_name' );
function ph_wp_mail_from_name( $original_email_address ) {
  $n = get_bloginfo( 'name' );
  return $n;
}

//ajax function for emailing post to the email submitted...
add_action( 'wp_ajax_nopriv_epicred_ajax_mail', 'epicred_ajax_mail' );
add_action( 'wp_ajax_epicred_ajax_mail', 'epicred_ajax_mail' );
function epicred_ajax_mail(){

  $current_user = wp_get_current_user();
  $n = $current_user->display_name;

  $em   = $_POST['mail'];
  $pid  = (int)$_POST['post'];
  $t = get_the_title( $pid );
  $bn = get_bloginfo('name');
  $hu = home_url();
  $perma = $_POST['perma'];
  $link = $hu . "/posts/" . $perma;

  $subject = __('Check out', 'ph_theme') . " " . $t;
  $on = '<a href="'. $hu .'" class="brand">'. $bn .'</a>';
  $message = __('I thought you might be interested in ', 'ph_theme') . " " . $t . __(' on ', 'ph_theme') . $bn .": ". $link;
  $headers = 'From:' . $n . " via " . $bn . "\r\n";

  wp_mail($em, $subject, $message, $headers );
  
  $response['pid'] = $pid;
  $response['em'] = $em;
  $response['perma'] = $perma;
  $response['sub'] = $subject;
  $response['msg'] = $message;

  echo  json_encode($response);

  die();


}

add_action( 'wp_ajax_nopriv_ph_follows', 'ph_follows' );
add_action( 'wp_ajax_ph_follows', 'ph_follows' );
function ph_follows(){
    global $wpdb;

    $followed      = (int)$_POST['followed']; 
    $crud          = (int)$_POST['crud'];
    $follower =      (int)$_POST['follower'];
    $ph_follows = $wpdb->prefix . "ph_follows";

    if($crud == 1){  //we are following

      $inDB = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $ph_follows WHERE follower = %d AND followed = %d", array($follower, $followed)) );
      

      if(!$inDB){
          $wpdb->insert( $ph_follows, array( 'follower' => $follower, 'followed' => $followed ),  array( '%d', '%d' ) );
        }

    }else{   //we are unfollowing
        $wpdb->delete( $ph_follows, array( 'follower' => $follower,'followed' => $followed ),  array( '%d','%d' ) );
    }


    die();

}

//create the tables for the theme (follows, collections)
function ph_create_tables(){
   global $wpdb;
   $table_name = $wpdb->prefix . "ph_follows";
      
   $sql = "CREATE TABLE IF NOT EXISTS $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    follower mediumint(9) NOT NULL,
    followed mediumint(9) NOT NULL,
    UNIQUE KEY id (id)
      );";

     require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
     dbDelta($sql);
}
add_action("after_switch_theme", "ph_create_tables");


//ajax function for the post grabbing.....

add_action( 'wp_ajax_nopriv_epicred_ajax', 'epicred_ajax' );
add_action( 'wp_ajax_epicred_ajax', 'epicred_ajax' );
function epicred_ajax(){
  global $withcomments, $wp_query,$post,$wpdb, $current_user,$query_string;

  $wpdb->epic   = $wpdb->prefix . 'epicred';
  $withcomments = 1;

  $postid      = (int)$_POST['p'];   //prefix...

  $post = get_post($postid); 
  $args = array(
  'status' => 'approve',
  'post_id' => $postid, 
  );
  $comments = get_comments($args);
  
  $commarr  = array();
  $i = 0;
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
    $i++;

      }

  $query = $wpdb->prepare("SELECT epicred_ip FROM $wpdb->epic WHERE epicred_id = %d", $postid);
  $upvotes = $wpdb->get_results($query);
  foreach($upvotes as $upvote){
    $ava = ph_get_avatar_url(get_avatar( $upvote->epicred_ip , 60 ));
    $href = get_author_posts_url( $upvote->epicred_ip );
    $upv = get_userdata( $upvote->epicred_ip );

    $ups[] = array(
       'user'      => "$upv->display_name",
       'ava'       => "$ava",
       'hr'      => "$href",
      );
  }

  ob_start();
  $args = array();
  comment_form( $args, $postid );
  $commhtml = ob_get_contents();
  ob_end_clean();

  $response['title'] = $post->post_title;
  $response['content'] = $post->post_content;
  $response['comments'] = $commarr;
  $response['commentshtml'] = $commhtml;
  $response['upvotes'] = $ups;  
  echo json_encode($response);
  exit;



}

add_filter('comment_post_redirect', 'redirect_after_comment');
function redirect_after_comment($location, $comment = "")
{

$parent = $_POST['comment_post_ID'];
return $_SERVER["HTTP_REFERER"] . "?comm=" . $parent;


}

function ph_get_avatar_url($get_avatar){
    preg_match("/src='(.*?)'/i", $get_avatar, $matches);
    return $matches[1];
}



function epicred_ajax2() {
  $id = ( isset( $_POST['p'] ) ) ? (int) $_POST['p'] : false;
  query_posts( array( 'p' => $id ) );
  if ( have_posts() ) {
    while( have_posts() ) {
      the_post();
      the_title();
      the_content();
      comments_template( '', true );
    }
  }
  else {
    print '<p>No posts</p>';
  }
  exit;
}
add_action( 'wp_ajax_epicred_ajax2', 'epicred_ajax2' );
add_action( 'wp_ajax_nopriv_epicred_ajax2', 'epicred_ajax2' );





function epic_query_vars_filter( $vars ){
  $vars[] = "latest";
  return $vars;
}
add_filter( 'query_vars', 'epic_query_vars_filter' );


add_filter('get_avatar','change_avatar_css');

function change_avatar_css($class) {
$class = str_replace("class='avatar", "class='author_gravatar alignright_icon img-rounded", $class) ;
return $class;
}

function ph_mailchimp($action){
  $msg = get_theme_mod('epic_mailchimp', 'Get the best new plugin discoveries in your inbox weekly!');
  $output = '
  <div class="newsletter-box well">
      <p>' . $msg  . '</p>

      <form accept-charset="UTF-8" action="'.$action.'" class="new_subscriber" data-remote="true" id="mc-embedded-subscribe-form" method="post"><div style="display:none"><input name="utf8" type="hidden" value=""></div>
        <input class="inputfield" id="mce-EMAIL" name="EMAIL" placeholder="Your email" type="email">
        <input name="subscribe" type="submit" value="Subscribe">
    </form>

  </div>';
  return $output;
}


function ph_the_excerpt($post_id) {
  global $post;  
  $save_post = $post;
  $post = get_post($post_id);
  $output = get_the_excerpt();
  $post = $save_post;
  return $output;
}

function pluginhunt_theme_customizer( $wp_customize ) {

class Epic_Customize_Textarea_Control extends WP_Customize_Control {
    public $type = 'textarea';
 
    public function render_content() {
        ?>
        <label>
        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
        <textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
        </label>
        <?php
    }
}

if ( $wp_customize->is_preview() && ! is_admin() ) {
    add_action( 'wp_footer', 'ph_customize_preview', 21);
}


  //v3.3 addtions - add a section for the blog
    $wp_customize->add_section( 'pluginhunt_blog' , array(
      'title'       => __( 'Blog settings','ph_theme'),
      'priority'    => 30,
      'description' => __('Settings for the blog','ph_theme'),
  ) );

      $wp_customize->add_setting( 'ph_blog_logo', array(
    'sanitize_callback' => 'esc_url_raw',
  ) );
  $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'ph_blog_logo', array(
    'label'    => __( 'Logo', 'pluginhunt' ),
    'section'  => 'pluginhunt_blog',
    'settings' => 'ph_blog_logo',
  ) ) );


    $wp_customize->add_setting( 'ph_blog_title', array(
        'default'   => 'Plugin Hunt Blog',
        'sanitize_callback' => 'pluginhunt_sanitize_text',  
    ) );
     
    $wp_customize->add_control(
        'ph_blog_title',
        array(
            'label' => __('What is the title for your blog','ph_theme'),
            'section' => 'pluginhunt_blog',
            'type' => 'text',
        )
    );

    $wp_customize->add_setting( 'ph_blog_tagline', array(
        'default'   => 'A Blog for Plugin Lovers',
        'sanitize_callback' => 'pluginhunt_sanitize_text',  
    ) );
     
    $wp_customize->add_control(
        'ph_blog_tagline',
        array(
            'label' => __('What is the tagline of your blog','ph_theme'),
            'section' => 'pluginhunt_blog',
            'type' => 'text',
        )
    );

  //v1.2 addtions - collections toggle and other toggles - grouped into general options
    $wp_customize->add_section( 'pluginhunt_general' , array(
      'title'       => __( 'General settings','ph_theme'),
      'priority'    => 30,
      'description' => __('General settings for the theme','ph_theme'),
  ) );


  //v2.3 addtions - collections toggle and other toggles - grouped into general options
    $wp_customize->add_section( 'pluginhunt_social' , array(
      'title'       => __( 'Social settings','ph_theme'),
      'priority'    => 31,
      'description' => __('Social settings for the theme','ph_theme'),
  ) );

    $wp_customize->add_setting( 'ph_tweet_via', array(
        'default'   => 'epicplugins',
        'sanitize_callback' => 'pluginhunt_sanitize_text',  
    ) );
     
    $wp_customize->add_control(
        'ph_tweet_via',
        array(
            'label' => __('What is your tweet handle (without the @)','ph_theme'),
            'section' => 'pluginhunt_social',
            'type' => 'text',
        )
    );

    $wp_customize->add_setting( 'ph_fb_appid', array(
        'default'   => '427029257462972',
        'sanitize_callback' => 'pluginhunt_sanitize_text',  
    ) );
     
    $wp_customize->add_control(
        'ph_fb_appid',
        array(
            'label' => __('What is your Facbook App ID','ph_theme'),
            'section' => 'pluginhunt_social',
            'type' => 'text',
        )
    );


  #} Mailchimp
    $wp_customize->add_setting('use_mailchimp', array(
         'sanitize_callback' => 'pluginhunt_sanitize_text',  
      ));

    $wp_customize->add_control(
        'use_mailchimp',
        array(
            'type' => 'checkbox',
            'label' => 'Use mailchimp',
            'section' => 'pluginhunt_general',
        )
    );

    $wp_customize->add_setting( 'phmc_url', array(
        'default'        => 'http://example.com',
        'sanitize_callback' => 'pluginhunt_sanitize_text',  
    ) );
     
    $wp_customize->add_control(
        'phmc_url',
        array(
            'label' => __('MailChimp form action URL','ph_theme'),
            'section' => 'pluginhunt_general',
            'type' => 'text',
        )
    );

    $wp_customize->add_setting( 'epic_mailchimp', array(
        'default'        => 'Get the best new plugin discoveries in your inbox weekly!',
        'sanitize_callback' => 'pluginhunt_sanitize_text',  
    ) );
     
    $wp_customize->add_control(
        'epic_mailchimp',
        array(
            'label' => __('Mailchimp sign up message','ph_theme'),
            'section' => 'pluginhunt_general',
            'type' => 'text',
        )
    );

    $wp_customize->add_setting( 'ph_keyword_s', array(
        'default'        => 'plugin',
        'sanitize_callback' => 'pluginhunt_sanitize_text',  
    ) );
     
    $wp_customize->add_control(
        'ph_keyword_s',
        array(
            'label' => __('What is your site about (e.g. plugin, product) enter the singluar here','ph_theme'),
            'section' => 'pluginhunt_general',
            'type' => 'text',
        )
    );

    $wp_customize->add_setting( 'ph_keyword_p', array(
        'default'        => 'plugin',
        'sanitize_callback' => 'pluginhunt_sanitize_text',  
    ) );
     
    $wp_customize->add_control(
        'ph_keyword_p',
        array(
            'label' => __('What is your site about (e.g. plugins, products) enter the plural here','ph_theme'),
            'section' => 'pluginhunt_general',
            'type' => 'text',
        )
    );
    $wp_customize->add_setting( 'epic_load', array(
        'default'        => 'Hunting down older plugins...',
        'sanitize_callback' => 'pluginhunt_sanitize_text',  
    ) );
     
    $wp_customize->add_control(
        'epic_load',
        array(
            'label' => __('Message for load more','ph_theme'),
            'section' => 'pluginhunt_general',
            'type' => 'text',
        )
    );

    $wp_customize->add_setting( 'ph_from', array(
        'default'        => 'noreply@pluginhunt.com',
        'sanitize_callback' => 'pluginhunt_sanitize_text',  
    ) );
     
    $wp_customize->add_control(
        'ph_from',
        array(
            'label' => __('The from on your email','ph_theme'),
            'section' => 'pluginhunt_general',
            'type' => 'text',
        )
    );


    $wp_customize->add_setting( 'epic_done', array(
        'default'        => 'No more plugins...',
        'sanitize_callback' => 'pluginhunt_sanitize_text',  
    ) );
     
    $wp_customize->add_control(
        'epic_done',
        array(
            'label' => __('Message when all content loaded','ph_theme'),
            'section' => 'pluginhunt_general',
            'type' => 'text',
        )
    );

    $wp_customize->add_setting( 'epic_more', array(
        'default'        => 'plugin',
        'sanitize_callback' => 'pluginhunt_sanitize_text',  
    ) );
     
    $wp_customize->add_control(
        'epic_more',
        array(
            'label' => __('Message when grouping content','ph_theme'),
            'section' => 'pluginhunt_general',
            'type' => 'text',
        )
    );

  //v1.1 additions - submit form settings... 
    $wp_customize->add_section( 'pluginhunt_new_section' , array(
      'title'       => __( 'Submit new post form','ph_theme'),
      'priority'    => 30,
      'description' => 'Control the submit form',
  ) );

    $wp_customize->add_setting( 'phtitle_setting', array(
        'default'        => 'NEW PLUGIN',
        'sanitize_callback' => 'pluginhunt_sanitize_text',  
    ) );
     
    $wp_customize->add_control(
        'phtitle_setting',
        array(
            'label' => __('Title','ph_theme'),
            'section' => 'pluginhunt_new_section',
            'type' => 'text',
        )
    );

    $wp_customize->add_setting( 'phstitle_setting', array(
        'default'        => 'Found something interesting? Hunt it!',
        'sanitize_callback' => 'pluginhunt_sanitize_text',  
    ) );
     
    $wp_customize->add_control(
        'phstitle_setting',
        array(
            'label' => __('Sub-title','ph_theme'),
            'section' => 'pluginhunt_new_section',
            'type' => 'text',
        )
    );

    $wp_customize->add_setting( 'ph_cta', array(
        'default'        => 'Hunt it',
        'sanitize_callback' => 'pluginhunt_sanitize_text',  
    ) );
     
    $wp_customize->add_control(
        'ph_cta',
        array(
            'label' => __('Call to Action','ph_theme'),
            'section' => 'pluginhunt_new_section',
            'type' => 'text',
        )
    );

    do_action('phytcustom');

  //v1.1 additions - post success message
    $wp_customize->add_section( 'pluginhunt_submit_section' , array(
      'title'       => __( 'Success messages','ph_theme'),
      'priority'    => 30,
      'description' => 'Set the messages to be shown when a new user submits content',
  ) );



    $wp_customize->add_setting( 'phsuccess_setting', array(
        'default'        => '<div class="new-post-header">POST SUBMITTED</div><div class="new-post-subheader">Your post is now live </div>',
        'sanitize_callback' => 'pluginhunt_sanitize_text',  
    ) );
     
    $wp_customize->add_control( new Epic_Customize_Textarea_Control( $wp_customize, 'phsuccess_setting', array(
        'label'   => 'Published message',
        'section' => 'pluginhunt_submit_section',
        'settings'   => 'phsuccess_setting',
    ) ) );

  //v1.1 additions - post pending message message
    $wp_customize->add_setting( 'phpending_setting', array(
        'default'        => '<div class="new-post-header">POST SUBMITTED</div><div class="new-post-subheader">Your post will be reviewed by our team and if suitable make it onto the homepage </div>',
         'sanitize_callback' => 'pluginhunt_sanitize_text',  
    ) );
     
    $wp_customize->add_control( new Epic_Customize_Textarea_Control( $wp_customize, 'phpending_setting', array(
        'label'   => 'Pending message',
        'section' => 'pluginhunt_submit_section',
        'settings'   => 'phpending_setting',
    ) ) );

  // Highlight and link color
    $wp_customize->add_setting( 'pluginhunt_link_color', array(
        'default'           => '#ff0000',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pluginhunt_link_color', array(
        'label'    => 'Link and Highlight Color',
        'section'  => 'colors',
        'settings' => 'pluginhunt_link_color',
    ) ) );

    $wp_customize->add_setting( 'pluginhunt_secondary_color', array(
        'default'           => '#edeceb',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pluginhunt_secondary_color', array(
        'label'    => 'Secondary colour',
        'section'  => 'colors',
        'settings' => 'pluginhunt_secondary_color',
    ) ) );

    $wp_customize->add_setting( 'pluginhunt_background_color', array(
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pluginhunt_background_color', array(
        'label'    => 'Background Color',
        'section'  => 'colors',
        'settings' => 'pluginhunt_background_color',
    ) ) );

    $wp_customize->add_setting( 'pluginhunt_novote_color', array(
        'default'           => '#000000',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pluginhunt_novote_color', array(
        'label'    => 'Color if a member has not voted',
        'section'  => 'colors',
        'settings' => 'pluginhunt_novote_color',
    ) ) );

    $wp_customize->add_setting( 'pluginhunt_vote_color', array(
        'default'           => '#ff0000',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
 
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pluginhunt_vote_color', array(
        'label'    => 'Color if a member has voted',
        'section'  => 'colors',
        'settings' => 'pluginhunt_vote_color',
    ) ) );

    $wp_customize->add_setting( 'pluginhunt_sm_color', array(
        'default'           => '#f7f7f7',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',  
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pluginhunt_sm_color', array(
        'label'    => 'Secondary menu color',
        'section'  => 'colors',
        'settings' => 'pluginhunt_sm_color',
    ) ) );

    $wp_customize->add_setting( 'pluginhunt_sml_color', array(
        'default'           => '#000000',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pluginhunt_sml_color', array(
        'label'    => 'Secondary menu link color',
        'section'  => 'colors',
        'settings' => 'pluginhunt_sml_color',
    ) ) );
    
    $wp_customize->add_setting( 'pluginhunt_np_color', array(
        'default'           => '#fcf5e2',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pluginhunt_np_color', array(
        'label'    => 'New post header color',
        'section'  => 'colors',
        'settings' => 'pluginhunt_np_color',
    ) ) );


    $wp_customize->add_setting( 'pluginhunt_sma_color', array(
        'default'           => '#ff0000',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'pluginhunt_sma_color', array(
        'label'    => 'Secondary menu active color',
        'section'  => 'colors',
        'settings' => 'pluginhunt_sma_color',
    ) ) );


    // Logo upload
    $wp_customize->add_section( 'pluginhunt_logo_section' , array(
      'title'       => __( 'Logo','ph_theme'),
      'priority'    => 30,
      'description' => 'Upload a logo to replace the default site name and description in the header',
  ) );
  $wp_customize->add_setting( 'pluginhunt_logo', array(
    'sanitize_callback' => 'esc_url_raw',
  ) );
  $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'pluginhunt_logo', array(
    'label'    => __( 'Logo', 'pluginhunt' ),
    'section'  => 'pluginhunt_logo_section',
    'settings' => 'pluginhunt_logo',
  ) ) );
    // Choose excerpt or full content on blog
    $wp_customize->add_section( 'pluginhunt_layout_section' , array(
      'title'       => __( 'Layout', 'pluginhunt' ),
      'priority'    => 30,
      'description' => 'Change how plugin hunt displays posts',
  ) );
  $wp_customize->add_setting( 'pluginhunt_post_content', array(
    'default'         => 'option1',
    'sanitize_callback' => 'pluginhunt_sanitize_index_content',
  ) );
  $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'pluginhunt_post_content', array(
    'label'    => __( 'Post content', 'pluginhunt' ),
    'section'  => 'pluginhunt_layout_section',
    'settings' => 'pluginhunt_post_content',
    'type'     => 'radio',
    'choices'  => array(
      'option1' => 'Excerpts',
      'option2' => 'Full content',
      ),
  ) ) );



  // Set site name and description to be previewed in real-time
  $wp_customize->get_setting('blogname')->transport='postMessage';
  $wp_customize->get_setting('blogdescription')->transport='postMessage';

  
  // Enqueue scripts for real-time preview
  wp_enqueue_script( 'pluginhunt-customizer', get_template_directory_uri() . '/js/pluginhunt-customizer.js', array( 'jquery' ) );



 
}
add_action('customize_register', 'pluginhunt_theme_customizer');
/**
 * Sanitizes a hex color. Identical to core's sanitize_hex_color(), which is not available on the wp_head hook.
 *
 * Returns either '', a 3 or 6 digit hex color (with #), or null.
 * For sanitizing values without a #, see sanitize_hex_color_no_hash().
 *
 * @since 1.7
 */
function pluginhunt_sanitize_hex_color( $color ) {
  if ( '' === $color )
    return '';
  // 3 or 6 hex digits, or the empty string.
  if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
    return $color;
  return null;
}
/**
 * Sanitizes our post content value (either excerpts or full post content).
 *
 * @since 1.7
 */
function pluginhunt_sanitize_index_content( $content ) {
  if ( 'option2' == $content ) {
    return 'option2';
  } else {
    return 'option1';
  }
}
/**
 * Sanitizes text inputs
 *
 * @since 2.3
 */
function pluginhunt_sanitize_text( $input ) {
    return wp_kses_post( force_balance_tags( $input ) );
}

/** media uploader  **/
function ph_add_media_upload_scripts() {
    if ( is_admin() ) {
         return;
       }
    wp_enqueue_media();
}
add_action('wp_enqueue_scripts', 'ph_add_media_upload_scripts');


function ph_remove_medialibrary_tab($strings) {
        unset($strings["mediaLibraryTitle"]);
        return $strings;
}
// add_filter('media_view_strings','ph_remove_medialibrary_tab');

function ph_delete_attachment(){
  //delete attachement
  $aid = (int)$_POST['aid'];
  wp_delete_attachment( $aid );
  die();
}

// lets use the jQuery form upload instead of media manager
add_action('wp_print_scripts','ph_include_jquery_form_plugin');
function ph_include_jquery_form_plugin(){
        wp_enqueue_script( 'jquery-form',array('jquery'),false,true ); 
}

//hook the Ajax call
//for logged-in users
add_action('wp_ajax_my_upload_action', 'ph_my_ajax_upload');
//for none logged-in users
add_action('wp_ajax_nopriv_my_upload_action', 'ph_my_ajax_upload');

function ph_my_ajax_upload(){
//simple Security check
    check_ajax_referer('upload_thumb');

//get POST data
//    $post_id = $_POST['post_id'];

//require the needed files
    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php');
//then loop over the files that were sent and store them using  media_handle_upload();
    if ($_FILES) {
        foreach ($_FILES as $file => $array) {
            if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
                echo "upload error : " . $_FILES[$file]['error'];
                die();
            }
            $attach_id = media_handle_upload( $file, 0 );  //for new posts do not pass post id. duplicate this for the single post add media link
        }   
    }
//and if you want to set that image as Post  then use:
 //  update_post_meta($post_id,'_thumbnail_id',$attach_id);
  $r['url'] = wp_get_attachment_url( $attach_id );

  echo json_encode($r);
  die();
} 


/**
 * Add CSS in <head> for styles handled by the theme customizer
 *
 * @since 1.5
 */
function pluginhunt_add_customizer_css() {
  $color          = pluginhunt_sanitize_hex_color( get_theme_mod( 'pluginhunt_link_color' ) );
  $scolor         = pluginhunt_sanitize_hex_color( get_theme_mod( 'pluginhunt_secondary_color' ) );
  $bcolor         = pluginhunt_sanitize_hex_color( get_theme_mod( 'pluginhunt_background_color' ) );
  $ncolor         = pluginhunt_sanitize_hex_color( get_theme_mod( 'pluginhunt_novote_color') );
  $ucolor         = pluginhunt_sanitize_hex_color( get_theme_mod( 'pluginhunt_vote_color'));
  $smenucolor     = pluginhunt_sanitize_hex_color( get_theme_mod( 'pluginhunt_sm_color'));
  $smenulcolor    = pluginhunt_sanitize_hex_color( get_theme_mod( 'pluginhunt_sml_color'));
  $smenuacolor    = pluginhunt_sanitize_hex_color( get_theme_mod( 'pluginhunt_sma_color'));
  $newpostheader  = pluginhunt_sanitize_hex_color( get_theme_mod( 'pluginhunt_np_color'));
  ?>
  <!-- pluginhunt customizer CSS -->
  <style>
    body {
      border-color: <?php echo $color; ?>;
    }
    #wrapper, .site--header,.footer-wrap{
      background: <?php echo $bcolor; ?>;
    }
    .comment-icon, .comment-icon .fa{
     color: <?php echo $scolor; ?>; 
    }
    .showmore,.comment-icon .fa, .hunt-comm-count, .day, .brand a, .brand a:hover, .page-title, #respond h3, .side-nav a:hover ,.title a:visited, .company-item-title, .icontext, .score, .unvoted, .comment-reply-title, body a, .fa ,a {
      color: <?php echo $color; ?>;
    }
    #account-dropdown .fa, #phnav, .phnotify .fa, .phnotify,.ph_collections, .ph_collections a{
      color: <?php echo $scolor; ?>;
    }
    .featured-category-image,.new-post-modal-close{
      background: <?php echo $scolor; ?>;
    }
    .main-navigation a:hover,
    .main-navigation .sub-menu a:hover,
    .main-navigation .children a:hover,
    .main-navigation a:focus,
    .main-navigation a:active,
    .main-navigation .current-menu-item > a,
    .main-navigation .current_page_item > a,
    .pluginhunt-lang:hover,
    .new-post-modal-close:hover,
    .reddit-voting-upmod, .reddit-voting-flash.upmod
     {
      background-color: <?php echo $color; ?>;
    }
    input[type=submit], .button{
      background:<?php echo $color; ?>;
    }
    input[type=submit], .phlogin{
      background:<?php echo $color; ?>;
    }
    .up, .unvoted{
      color:<?php echo $ncolor; ?> !important;
    }
    .upmod, .likes{
      color: <?php echo $ucolor; ?>;
    }
    .smenu{
      background-color: <?php echo $smenucolor; ?> !important;
    }
    .smenu a{
      color: <?php echo $smenulcolor; ?> !important;
    }
    .smenu .current-menu-item{
      border-bottom-color: <?php echo $smenuacolor; ?> !important; 
    }
    .new-post-wrapper{
      background: <?php echo $newpostheader; ?> !important;
    }
  </style>
<?php }
add_action( 'wp_head', 'pluginhunt_add_customizer_css' );


function ph_customize_preview() {
    ?>
    <script type="text/javascript">
        ( function( $ ) {
            wp.customize('pluginhunt_link_color',function( value ) {
                value.bind(function(to) {
                    $('.footer-wrap, .day, .brand a, .brand a:hover, .page-title, #respond h3, .side-nav a:hover ,.title a:visited, .company-item-title, .icontext, .score, .unvoted, .comment-reply-title, body a, .fa ,a, a:visited').css('color', to );
                    $('.main-navigation a:hover,.main-navigation .sub-menu a:hover,.main-navigation .children a:hover,.main-navigation a:focus,.main-navigation a:active,.main-navigation .current-menu-item > a,.main-navigation .current_page_item > a,.pluginhunt-lang:hover').css('background-color', to );
                });
            });
            wp.customize('pluginhunt_secondary_color',function( value ) {
                value.bind(function(to) {
                    $('.comment-icon').css('color', to );
                    $('.comment-icon .fa').css('color', to );
                });
            });
            wp.customize('pluginhunt_background_color',function( value ) {
                value.bind(function(to) {
                    $('#wrapper, .site--header').css('background', to );
                    $('input[type=submit], .button').css('background',to);
                    $('body').css('background',to);
                });
            });
            wp.customize('pluginhunt_novote_color',function( value ) {
                value.bind(function(to) {
                    $('.up, .unvoted').css('color', to );
                });
            });
            wp.customize('pluginhunt_vote_color',function( value ) {
                value.bind(function(to) {
                    $('.upmod, .likes').css('color', to );
                });
            });
            wp.customize('pluginhunt_sml_color',function( value ) {
                value.bind(function(to) {
                    $('.smenu a').css('color', to );
                });
            });           
            wp.customize('pluginhunt_sm_color',function( value ) {
                value.bind(function(to) {
                    $('.smenu').css('background-color', to );
                });
            });
            wp.customize('pluginhunt_sma_color',function( value ) {
                value.bind(function(to) {
                    $('.smenu .current-menu-item').css('border-bottom-color', to );
                });
            });
            wp.customize('pluginhunt_np_color',function( value ) {
                value.bind(function(to) {
                    $('.new-post-wrapper').css('background', to );
                });
            });
        } )( jQuery )
    </script>
    <?php
}  // End function example_customize_preview()


add_action('init', 'theme__init');
function theme__init(){}

add_action( 'wp_ajax_nopriv_epicred_vote_t', 'epicred_vote_t' );
add_action( 'wp_ajax_epicred_vote_t', 'epicred_vote_t' );

function epicred_vote_t(){
  global $wpdb, $current_user;
  
    get_currentuserinfo();
  
  $wpdb->myo_ip   = $wpdb->prefix . 'epicred';
    
  $option = (int)$_POST['option'];
  $current = (int)$_POST['current'];
  
  $fid = $current_user->ID;
  $postid = (int)$_POST['poll'];  

  $query = "SELECT epicred_option FROM $wpdb->myo_ip WHERE epicred_ip = $fid AND epicred_id = $postid";
  
  $al = $wpdb->get_var($query);
    
  
  if($al == NULL){
    $query = "INSERT INTO $wpdb->myo_ip ( epicred_id , epicred_ip, epicred_option) VALUES ( $postid, $fid, $option)";
    $wpdb->query($query);
  }else{
    $query = "UPDATE $wpdb->myo_ip SET epicred_option = $option WHERE epicred_ip = $fid AND epicred_id = $postid";
    $wpdb->query($query);
  }
  
    $vote = get_post_meta($postid,'epicredvote',true);
  
    if($option == 1){
      if($al != 1){
        $vote = $vote+1;
      }
    }
     
    if($option == -1){
      if($al != -1){
        $vote = $vote-1;
      } 
    }
    update_post_meta($postid,'epicredvote',$vote);

    $response['poll'] = $postid;
    $response['vote'] = $vote;
    
    echo json_encode($response);
  
  // IMPORTANT: don't forget to "exit"
  exit;
}



add_action( 'wp_ajax_nopriv_epicred_submit', 'epicred_submit' );
add_action( 'wp_ajax_epicred_submit', 'epicred_submit' );
function epicred_submit(){

  $title      = $_POST['title'];
  $content    = $_POST['content'];
  $cat        = $_POST['cat'];
  $posttype   = $_POST['submit_type'];
  $details    = $_POST['new_post_details'];

 
  $status = 'pending';   //make defaul pending. expose in settings in v1.1.

  $author = $current_user->ID;
  $taxonomy = 'category';
  
    $my_post = array(
      'post_title' => $title,
      'post_status' => $status,
      'post_type' => $posttype,
      'post_author' => $author,
      'post_content' => $details,
       );

    //clean up the URL 
                        
    
    $post_id = wp_insert_post( $my_post );
    
    // update_post_meta( $post_id, 'wpedditimage', $image );
    if($posttype == 'post'){
      $content = esc_url($content);
      update_post_meta($post_id,'outbound', $content);
    }
    update_post_meta( $post_id, 'details', $details);
    // update_post_meta( $post_id, 'wpedditimage', $image ); 
    update_post_meta( $post_id, 'epicredvote', 0); 
    update_post_meta( $post_id, 'epicredrank', 0);    
    wp_set_post_terms( $post_id, $cat, $taxonomy);
    $permalink = get_permalink( $post_id );

    //control the pace of posting...
    $current_user = wp_get_current_user();
    update_user_meta($current_user->ID, 'ehacklast', time());


    $response['stat'] = $status;
    $response['perma'] = $permalink;

    
    echo json_encode($response);
  
  // IMPORTANT: don't forget to "exit"
  exit;



}


function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}



// add_filter('the_content', 'epic_nofollow');
// add_filter('the_excerpt', 'epic_nofollow');
function epic_nofollow($content) {
    return preg_replace_callback('/<a[^>]+/', 'epic_nofollow_callback', $content);
}
function epic_nofollow_callback($matches) {
    $link = $matches[0];
    $site_link = home_url();
    if (strpos($link, 'rel') === false) {
        $link = preg_replace("%(href=\S(?!$site_link))%i", 'rel="nofollow" $1', $link);
    } elseif (preg_match("%href=\S(?!$site_link)%i", $link)) {
        $link = preg_replace('/rel=\S(?!nofollow)\S*/i', 'rel="nofollow"', $link);
    }
    return $link;
}



function remove_http($url) {
   $disallowed = array('http://', 'https://');
   foreach($disallowed as $d) {
      if(strpos($url, $d) === 0) {
         return str_replace($d, '', $url);
      }
   }
   $url = rtrim($url," /");
   $url = ltrim($url);
   return $url;
}

//front end scripts and styles 
function ph_frontend_scripts(){
    wp_enqueue_script("jquery");  
    /*  Styles */

    if(wp_is_mobile()){
      wp_enqueue_style('wpthememob', get_template_directory_uri() . '/mobile-style.css' );   //lets think about re-working this one...
    }
    wp_enqueue_style('eStoreboot', get_template_directory_uri() . '/css/bootstrap.css' );
    wp_enqueue_style('ph_font_a', get_template_directory_uri() . '/css/font-awesome.min.css' );
    wp_enqueue_style('ph_tip', get_template_directory_uri() . '/css/drop-theme-arrows-bounce.css' );    
    wp_enqueue_style('ph_main', get_template_directory_uri() . '/css/style.css' );   
    wp_enqueue_style('ph_animate_css', get_template_directory_uri() . '/css/animate.min.css' ); 

    /* Scripts */
    wp_register_script( 'ph_drop_js', get_template_directory_uri() . '/js/drop.min.js', array( 'jquery' ) ); 
    wp_enqueue_script( 'ph_drop_js' );
    wp_register_script( 'ph_main', get_template_directory_uri() . '/js/main.js', array( 'jquery' ) ); 
    wp_enqueue_script( 'ph_main' );
    wp_register_script( 'ph_modernizr', get_template_directory_uri() . '/js/modernizr.js', array( 'jquery' ) ); 
    wp_enqueue_script( 'ph_modernizr' );
    wp_register_script( 'eStoreboot', get_template_directory_uri() . '/js/bootstrap.js', array( 'jquery' ) ); 
    wp_enqueue_script( 'eStoreboot' );
    wp_register_script( 'phmobmenu', get_template_directory_uri() . '/js/phmobmenu.js', array( 'jquery' ) ); 
    wp_enqueue_script( 'phmobmenu' );
    wp_register_script( 'ph_animate_js', get_template_directory_uri() . '/js/animatedModal.min.js', array( 'jquery' ) ); 
    wp_enqueue_script( 'ph_animate_js' );

    //scripts with variables
    $fid = get_current_user_id();
    $type = get_option('wpedditnewpost', true);
    if($type == 'published'){
      $msg = get_theme_mod( 'phsuccess_setting', '<div class="new-post-header">POST SUBMITTED</div><div class="new-post-subheader">Your post is now live </div>' );
    }else{
      $msg= get_theme_mod( 'phpending_setting', '<div class="new-post-header">POST SUBMITTED</div><div class="new-post-subheader">Your post will be reviewed by our team and if suitable make it onto the homepage </div>' );
    }
    $home = home_url();
    $lm =  get_theme_mod( 'epic_load', 'Hunting down older plugins...' ); 
    wp_localize_script( 'eStoreboot', 'HuntAjax', array('epic_more' => $lm, 'epichome' => $home, 'logged' => $fid ,'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    wp_localize_script( 'eStoreboot', 'PHnew', array('success' => $msg ));
    wp_register_script( 'ph-hunt-main', get_template_directory_uri() . '/js/epictheme.js', array( 'jquery' ) ); 
    wp_enqueue_script( 'ph-hunt-main' );
}
add_action( 'wp_enqueue_scripts', 'ph_frontend_scripts' );


//back end scripts and styles
function ph_backend_scripts(){}
add_action( 'admin_enqueue_scripts', 'ph_backend_scripts' );



/**
 * Include the TGM_Plugin_Activation class.
 */
require_once dirname( __FILE__ ) . '/includes/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'ph_grid_theme_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function ph_grid_theme_register_required_plugins() {

    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        // This is an example of how to include a plugin pre-packaged with a theme.
        array(
            'name'               => 'WPeddit Plugin Theme Edition', // The plugin name.
            'slug'               => 'wpeddit-plugin', // The plugin slug (typically the folder name).
            'source'             => get_stylesheet_directory() . '/plugins/wpeddit-plugin.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
        ),

        array(
            'name'               => 'Screets Live Chat Plugin', // The plugin name.
            'slug'               => 'screets-chat', // The plugin slug (typically the folder name).
            'source'             => get_stylesheet_directory() . '/plugins/screets-live-chat.zip', // The plugin source.
            'required'           => false, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
        ),

        array(
            'name'      => 'Nextend Facebook Connect',
            'slug'      => 'nextend-facebook-connect',
            'required'  => true,
        ),

        array(
            'name'      => 'Nextend Twitter Connect',
            'slug'      => 'nextend-twitter-connect',
            'required'  => true,
        ),
        
        array(
            'name'      => 'Hide Admin Bar',
            'slug'      => 'hide-admin-bar-2013',
            'required'  => true,
        ),

        array(
            'name'      => 'Custom Category Image Plugin',
            'slug'      => 'wpcustom-category-image',
            'required'  => true,
        ),
        


    );

    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'default_path' => '',                      // Default absolute path to pre-packaged plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
        'strings'      => array(
            'page_title'                      => __( 'Install Required Plugins', 'tgmpa' ),
            'menu_title'                      => __( 'Install Plugins', 'tgmpa' ),
            'installing'                      => __( 'Installing Plugin: %s', 'tgmpa' ), // %s = plugin name.
            'oops'                            => __( 'Something went wrong with the plugin API.', 'tgmpa' ),
            'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s).
            'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s).
            'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s).
            'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s).
            'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
            'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins' ),
            'return'                          => __( 'Return to Required Plugins Installer', 'tgmpa' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', 'tgmpa' ),
            'complete'                        => __( 'All plugins installed and activated successfully. %s', 'tgmpa' ), // %s = dashboard link.
            'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
        )
    );

    tgmpa( $plugins, $config );

  }

/***********************Parent Theme**************/
if(function_exists('wp_get_theme')){
    $theme_data = wp_get_theme(get_option('template'));
    $theme_version = $theme_data->Version;  
} else {
    $theme_data = wp_get_theme();
    $theme_version = $theme_data['Version'];
}    
$theme_base = get_option('template');
/**************************************************/

//Uncomment below to find the theme slug that will need to be setup on the api server
//var_dump($theme_base);

add_filter('pre_set_site_transient_update_themes', 'ph_check_for_update');

function ph_check_for_update($checked_data) {
  global $wp_version, $theme_version, $theme_base, $api_url;

  $request = array(
    'slug' => $theme_base,
    'version' => $theme_version 
  );
  // Start checking for an update
  $send_for_check = array(
    'body' => array(
      'action' => 'theme_update', 
      'request' => serialize($request),
      'api-key' => md5(home_url())
    ),
    'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url()
  );
  $raw_response = wp_remote_post($api_url, $send_for_check);
  if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200))
    $response = unserialize($raw_response['body']);

  // Feed the update data into WP updater
  if (!empty($response)) 
    $checked_data->response[$theme_base] = $response;

  return $checked_data;
}

// Take over the Theme info screen on WP multisite
add_filter('themes_api', 'pluginhunt_theme_api_call', 10, 3);

function pluginhunt_theme_api_call($def, $action, $args) {
  global $theme_base, $api_url, $theme_version, $api_url;
  
  if ($args->slug != $theme_base)
    return false;
  
  // Get the current version

  $args->version = $theme_version;
  $request_string = prepare_request($action, $args);
  $request = wp_remote_post($api_url, $request_string);

  if (is_wp_error($request)) {
    $res = new WP_Error('themes_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>','ph_theme'), $request->get_error_message());
  } else {
    $res = unserialize($request['body']);
    
    if ($res === false)
      $res = new WP_Error('themes_api_failed', __('An unknown error occurred','ph_theme'), $request['body']);
  }
  
  return $res;
}

if (is_admin())
  $current = get_transient('update_themes');



//#WHFIX 24/03/2015: 
#} see http://wordpress.stackexchange.com/questions/59442/how-do-i-get-the-avatar-url-instead-of-an-html-img-tag-when-using-get-avatar
#} MODIFIED A BIT...
function pluginHuntTheme_get_avatarurl($authorid){
    if (isset($authorid) && !empty($authorid)) {
      $get_avatar = get_avatar($authorid);
      preg_match("/src='(.*?)'/i", $get_avatar, $matches);
      return $matches[1];
    }

    return '';
}

#DEVELOPMENT DEBUG
if (!function_exists('write_log')) {
    function write_log ( $log )  {
   
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
      
    }
}

#Moving child theme functions into main theme
// add support for the 3x grid and 4x grid featured images
add_action( 'after_setup_theme', 'ph_grid_theme_setup' );
function ph_grid_theme_setup() {
  add_image_size( '3x-grid', 300, 190, true ); // 300 pixels wide (and unlimited height)
  add_image_size( '4x-grid', 220, 180, true ); // (cropped)
  add_image_size( '3x-grid-thin', 275, 177, true ); //  this will become a new cropped size. Use link 2 featured codebase to turn URL into a featured image on the server.
}

// show the featured image in the post list (and use the Social Video code to set the thumbnail as a featured image for videos)

function ph_youtube($url) {
    $pattern = 
        '%^# Match any youtube URL
        (?:https?://)?  # Optional scheme. Either http or https
        (?:www\.)?      # Optional www subdomain
        (?:             # Group host alternatives
          youtu\.be/    # Either youtu.be,
        | youtube\.com  # or youtube.com
          (?:           # Group path alternatives
            /embed/     # Either /embed/
          | /v/         # or /v/
          | /watch\?v=  # or /watch\?v=
          )            # End path alternatives.
        )               # End host alternatives.
        ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
        $%x'
        ;
    $result = preg_match($pattern, $url, $matches);
    if (false !== $result) {
        return $matches[1];
    }
    return false;
}


function ph_custom_columns( $columns ) {
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'featured_image' => 'Image',
        'title' => 'Title',
        'comments' => '<span class="vers"><div title="Comments" class="comment-grey-bubble"></div></span>',
        'date' => 'Date'
     );
    return $columns;
}
add_filter('manage_posts_columns' , 'ph_custom_columns');

function ph_custom_columns_data( $column, $post_id ) {
    switch ( $column ) {
    case 'featured_image':
        echo the_post_thumbnail( 'thumbnail' );
        break;
    }
}
add_action( 'manage_posts_custom_column' , 'ph_custom_columns_data', 10, 2 ); 

//moving the fetch code into the child theme.


//function which takes the URL and returns embeddable content.
//this may well be good enough as is. Although image URLs may need expanding.
//allow the option in customiser settings(?)
add_action( 'wp_ajax_nopriv_ph_fetch', 'ph_fetch' );
add_action( 'wp_ajax_ph_fetch', 'ph_fetch' );
function ph_fetch(){
  $url = $_POST['url'];

require_once dirname( __FILE__ ) . '/includes/OpenGraph.php';
    $graph = OpenGraph::fetch($url);
    $response['phsrc'] = $graph->image;
    $response['phtitle'] = $graph->title;
    $response['phdesc'] = $graph->description;

    if(ph_image($url)){
      //we have an image URL
      $response['phsrc'] = $url; // set to URL
    }

  echo json_encode($response);

  die();
}
add_shortcode('phfetch','ph_fetch');

add_action( 'wp_ajax_nopriv_ph_newpost_child', 'ph_newpost_child' );
add_action( 'wp_ajax_ph_newpost_child', 'ph_newpost_child' );
function ph_newpost_child(){
  
  $title          = $_POST['title'];
  $url            = $_POST['url'];
  $desc           = $_POST['desc'];
  $image          = $_POST['feat'];

  $current_user   = wp_get_current_user();
  $uid            = $current_user->ID;
  $type = get_option('wpedditnewpost', true);
  if($type == 'published'){
    $status = 'publish';
  }else{
    $status = 'pending';
  }
  $ptype = 'post';

  $post = array(
    'post_content'   => $desc, 
    'post_title'     => $title, 
    'post_status'    => $status,
    'post_type'      => $ptype,
    'post_author'    => $uid
  );  

    $wid = wp_insert_post( $post, $wp_error );

    update_post_meta($wid, 'outbound', $url);

    update_post_meta($wid, 'epicredvote', 0);
    update_post_meta($wid, 'epicredrank',0);
    //set the featured image from the URL

  if($image){
  
    //extra code to upload the image and set it as the featured image
  $upload_dir = wp_upload_dir();
  $image_data = file_get_contents($image);
  $filename = basename($image);
  if(wp_mkdir_p($upload_dir['path']))
      $file = $upload_dir['path'] . '/' . $filename;
  else
      $file = $upload_dir['basedir'] . '/' . $filename;
    file_put_contents($file, $image_data);
    
    $wp_filetype = wp_check_filetype($filename, null );
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment( $attachment, $file, $wid );
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    wp_update_attachment_metadata( $attach_id, $attach_data );
  
    set_post_thumbnail($wid, $attach_id ); 
    update_post_meta($wid, 'epic_externalURL', $image );
    
  }


    //control the pace of posting...
    $current_user = wp_get_current_user();
    update_user_meta($current_user->ID, 'ehacklast', time());

  $response = 'post added';
  echo $response; 
  exit;
}

function ph_image($url){
//checks to see if an image is a URL
     $url_headers=get_headers($url, 1);

    if(isset($url_headers['Content-Type'])){

        $type=strtolower($url_headers['Content-Type']);

        $valid_image_type=array();
        $valid_image_type['image/png']='';
        $valid_image_type['image/jpg']='';
        $valid_image_type['image/jpeg']='';
        $valid_image_type['image/jpe']='';
        $valid_image_type['image/gif']='';
        $valid_image_type['image/tif']='';
        $valid_image_type['image/tiff']='';
        $valid_image_type['image/svg']='';
        $valid_image_type['image/ico']='';
        $valid_image_type['image/icon']='';
        $valid_image_type['image/x-icon']='';

        if(isset($valid_image_type[$type])){

            return true;

        }
    }
  }


?>