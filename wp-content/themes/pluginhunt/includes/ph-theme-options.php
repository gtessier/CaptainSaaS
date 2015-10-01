<?php

/**
 * Theme Options Framework
 */
function ph_theme_menu()
{
  add_theme_page( 'Theme Option', 'Theme Options', 'manage_options', 'ph_theme_options.php', 'ph_theme_page');  
}
add_action('admin_menu', 'ph_theme_menu');

function ph_theme_page(){
	
}


?>