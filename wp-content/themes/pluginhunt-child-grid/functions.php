<?php 

//child theme functions

//add some colour to published posts



add_action('admin_head', 'ph_admin_custom_fonts');

function ph_admin_custom_fonts() {
  echo '<style>
    .status-publish{
      background-color:rgb(197, 255, 197) !important;
    } 
  </style>';
}

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('wptheme')
    );
   wp_register_script( 'ph_js_child', get_stylesheet_directory_uri()  . '/js/epictheme-child.js', array( 'jquery' ) ); 
    wp_enqueue_script( 'ph_js_child' );
}

//lets make this a media rich child theme. 
/*
if ( has_post_format( 'video' )) {
  echo 'this is the video format';
}
*/
add_theme_support( 'post-formats', array( 'video', 'image','audio' ) );

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