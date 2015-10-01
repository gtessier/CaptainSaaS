<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>



<div style="clear:both"></div>

<div class="footer-container">
	<div class='footer-wrap col-md-12'>
		<div class='footer-inner col-md-12'>
            <div class='empty'></div>
		</div>
	</div>
</div>

</div> <!-- / col-md-8 maincontent toppad -->


<div id="modal-content" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <p class='phmiddle'>
                    <span class='phbold'>
                    <?php _e('Hey there! Please sign in to do that.','ph_theme'); ?>
                </span>


                    <?php $surl = get_site_url(); ?>
                    <br/>
                    <div class='ph_socials'>

                    <div class='ph-soc-block'>
                    <a href="<?php echo $surl;?>/login/?loginTwitter=1&redirect=<?php echo $surl;?>" onclick="window.location = '<?php echo $surl;?>/login/?loginTwitter=1&redirect='+window.location.href; return false;">
                    <span class='ph-tw'><i class="fa fa-twitter"></i><?php _e('Sign in with Twitter','ph_theme'); ?></span></a>
                    </div>

                    <div class='ph-soc-block'>
                    <a href="<?php echo $surl;?>/login/?loginFacebook=1&redirect=<?php echo $surl;?>" onclick="window.location = '<?php echo $surl;?>/login/?loginFacebook=1&redirect='+window.location.href; return false;">
                    <span class='ph-fb'><i class="fa fa-facebook"></i><?php _e('Sign in with Facebook','ph_theme'); ?></span></a>
                    </div>
                    </div>

                    <div class='ph-or'>
                        <p class='ph-or-p'>Or, login <a href="<?php echo wp_login_url(); ?>" title="Login">normally</a></span></p>
                    </div>


                </p>
            </div>
            <div class="modal-footer"> 
                <a href="#" class="btn" data-dismiss="modal"><?php _e('Close','ph_theme'); ?></a>
            </div>



    </div>
</div>

<?php if($perma){ ?>

  <script type='text/javascript'>
  jQuery(document).ready(function() {
      bindFires();
  });
  </script>
  
<?php } ?>


<?php wp_footer(); ?>
</body>
</html>