<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 */
function optionsframework_option_name() {
	return 'plugin-hunt-theme';
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'ph_theme'
 * with the actual text domain for your theme.  Read more:
 * http://codex.wordpress.org/Function_Reference/load_theme_textdomain
 */

function optionsframework_options() {

	// Test data
	$test_array = array(
		'one' => __( 'One', 'ph_theme' ),
		'two' => __( 'Two', 'ph_theme' ),
		'three' => __( 'Three', 'ph_theme' ),
		'four' => __( 'Four', 'ph_theme' ),
		'five' => __( 'Five', 'ph_theme' )
	);

	// Multicheck Array
	$multicheck_array = array(
		'one' => __( 'French Toast', 'ph_theme' ),
		'two' => __( 'Pancake', 'ph_theme' ),
		'three' => __( 'Omelette', 'ph_theme' ),
		'four' => __( 'Crepe', 'ph_theme' ),
		'five' => __( 'Waffle', 'ph_theme' )
	);

	// Multicheck Defaults
	$multicheck_defaults = array(
		'one' => '1',
		'five' => '1'
	);

	// Background Defaults
	$background_defaults = array(
		'color' => '',
		'image' => '',
		'repeat' => 'repeat',
		'position' => 'top center',
		'attachment'=>'scroll' );

	// Typography Defaults
	$typography_defaults = array(
		'size' => '15px',
		'face' => 'georgia',
		'style' => 'bold',
		'color' => '#bada55' );

	// Typography Options
	$typography_options = array(
		'sizes' => array( '6','12','14','16','20' ),
		'faces' => array( 'Helvetica Neue' => 'Helvetica Neue','Arial' => 'Arial' ),
		'styles' => array( 'normal' => 'Normal','bold' => 'Bold' ),
		'color' => false
	);

	// Pull all the categories into an array
	$options_categories = array();
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}

	// Pull all tags into an array
	$options_tags = array();
	$options_tags_obj = get_tags();
	foreach ( $options_tags_obj as $tag ) {
		$options_tags[$tag->term_id] = $tag->name;
	}


	// Pull all the pages into an array
	$options_pages = array();
	$options_pages_obj = get_pages( 'sort_column=post_parent,menu_order' );
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}

	// If using image radio buttons, define a directory path
	$imagepath =  get_template_directory_uri() . '/images/';

	$options = array();

	$options[] = array(
		'name' => __( 'General', 'ph_theme' ),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( 'Site title', 'ph_theme' ),
		'desc' => __( 'Your website title.', 'ph_theme' ),
		'id' => 'ph_site_title',
		'std' =>  get_bloginfo( 'name' ),
		'class' => 'mini',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'Tagline', 'ph_theme' ),
		'desc' => __( 'Website tagline.', 'ph_theme' ),
		'id' => 'ph_site_tagline',
		'std' => 'Just another WordPress website',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'Mailchimp subscribe box', 'ph_theme' ),
		'desc' => __( 'Do you want to use the Mailchimp subscribe box.', 'ph_theme' ),
		'id' => 'mailchimp_showhidden',
		'type' => 'checkbox'
	);

	$options[] = array(
		'name' => __( 'Mailchimp Form Action URL', 'ph_theme' ),
		'desc' => __( 'Enter your mailchimp sign up form action URL.', 'ph_theme' ),
		'id' => 'mailchimp_action_hidden',
		'std' => '',
		'class' => 'hidden',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'Color Scheme', 'ph_theme' ),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( 'Link and Highlight', 'ph_theme' ),
		'desc' => __( 'No color selected by default.', 'ph_theme' ),
		'id' => 'main_color',
		'std' => '',
		'type' => 'color'
	);

	$options[] = array(
		'name' => __( 'Secondary color', 'ph_theme' ),
		'desc' => __( 'No color selected by default.', 'ph_theme' ),
		'id' => 'second_color',
		'std' => '',
		'type' => 'color'
	);

	$options[] = array(
		'name' => __( 'Background color', 'ph_theme' ),
		'desc' => __( 'No color selected by default.', 'ph_theme' ),
		'id' => 'background_color',
		'std' => '',
		'type' => 'color'
	);


	$options[] = array(
		'name' => __( 'Color if voted', 'ph_theme' ),
		'desc' => __( 'No color selected by default.', 'ph_theme' ),
		'id' => 'vote_color',
		'std' => '',
		'type' => 'color'
	);

	$options[] = array(
		'name' => __( 'Color if not voted', 'ph_theme' ),
		'desc' => __( 'No color selected by default.', 'ph_theme' ),
		'id' => 'novote_color',
		'std' => '',
		'type' => 'color'
	);

	$options[] = array(
		'name' => __( 'New post header color', 'ph_theme' ),
		'desc' => __( 'No color selected by default.', 'ph_theme' ),
		'id' => 'newpost_header_color',
		'std' => '',
		'type' => 'color'
	);

	$options[] = array(
		'name' => __( 'Member Options', 'ph_theme' ),
		'type' => 'heading'
	);




	$options[] = array(
		'name' => __( 'Header', 'ph_theme' ),
		'type' => 'heading'
	);


	$options[] = array(
		'name' => __( 'Main logo', 'ph_theme' ),
		'desc' => __( 'What is your main website logo.', 'ph_theme' ),
		'id' => 'main_logo',
		'type' => 'upload'
	);

	$options[] = array(
		'name' => "Header Style",
		'desc' => "Images for layout.",
		'id' => "ph_header_style",
		'std' => "header-1",
		'type' => "images",
		'options' => array(
			'header-1' => $imagepath . 'header-1.png',
			'header-2' => $imagepath . 'header-2.png',
		)
	);


	$options[] = array(
		'name' => __( 'Blog Layout', 'ph_theme' ),
		'type' => 'heading'
	);


	$options[] = array(
		'name' => __( 'Blog Logo', 'ph_theme' ),
		'desc' => __( 'Logo to display on the blog page.', 'ph_theme' ),
		'id' => 'ph_blog_logo',
		'type' => 'upload'
	);

	$options[] = array(
		'name' => "Blog Layout",
		'desc' => "Images for layout.",
		'id' => "ph_blog_layout",
		'std' => "1col-fixed",
		'type' => "images",
		'options' => array(
			'1col-fixed' => $imagepath . '1col.png',
			'2c-l-fixed' => $imagepath . '2cl.png',
			'2c-r-fixed' => $imagepath . '2cr.png'
		)
	);

	$options[] = array(
		'name' =>  __( 'Blog Background', 'ph_theme' ),
		'desc' => __( 'Change the background.', 'ph_theme' ),
		'id' => 'blog_background',
		'std' => $background_defaults,
		'type' => 'background'
	);


	$options[] = array(
		'name' => __( 'Custom CSS', 'ph_theme' ),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( 'Custom CSS', 'ph_theme' ),
		'desc' => __( 'Enter your custom CSS here.', 'ph_theme' ),
		'id' => 'ph_custom_css',
		'std' => '',
		'type' => 'textarea'
	);

	/**
	 * For $settings options see:
	 * http://codex.wordpress.org/Function_Reference/wp_editor
	 *
	 * 'media_buttons' are not supported as there is no post to attach items to
	 * 'textarea_name' is set by the 'id' you choose
	 */



	return $options;
}