<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

<div class='row'>
	<div class='col-md-2' style='padding-right:0px'>
		<?php EpicMainNav(); ?>
	</div>

	<div id="main-sidebar-sub" class="container bg-dark sub-mobile-menu">
		<?php EpicSideSub(); ?>
	</div>

<div class='col-md-8 maincontent toppad'>
	<div class='row author-pad'>

		<h1>Thank You</h2>
			<p>Your post has been submitted.</p>
			<?php if(get_option('wpedditnewpost') == 'pending'){ ?>
				<span>Your post has been submitted for moderation</span>
			<?php }else{ ?>
				<span>Your post has been submitted. Visit the "Latest" page to view</span>
			<?php } ?>

</div>

</div>


<?php
get_footer();
