    //#MJS ADD 31/03/2015 code here for the fetching of URLs using the oembed service. Promote to main theme
    jQuery(document).ready(function() {

        phfetch();  //bind the fetching URL.

    });


function phfetch(){

    //lets run the ph fetch on loss of focus of the .product-url field
    jQuery( ".product-url" ).focusout(function() {
        if (jQuery("#submission_url").val() != "") {
  // something is there so let's fetch it...

        jQuery('.modal-loading-fetch').show();
        
        if ("" == jQuery("#submission_url").val()) return alert("Please enter a URL"), !1;
        nonce = jQuery("#_wpnonce").val(), url = jQuery("#submission_url").val(), title = jQuery("#submission_name").val(), desc = jQuery("#submission_tagline").val();

        //WH Debug
         jQuery.ajax({
             url: HuntAjax.ajaxurl,
             type: 'POST',
            data:{
              action: 'ph_fetch', // this is the function in your functions.php that will be triggered
              url: url
            },
            dataType: "json",
            success: function(data){
              window.phfeatured = data.phsrc;
              //Do something with the result from server
              if(data.phsrc){
                jQuery('#ph-out').html('<img class="grabbed" src="' + data.phsrc + '"/>');
              }
              jQuery('#submission_name').val(data.phtitle);
              jQuery('#submission_tagline').val(data.phdesc);
              jQuery('.modal-loading-fetch').hide();
            }
          });

         }
  })


    jQuery(".new_post_submit_child").bind("click", function(e) {

        //#WHFIX 24/03/2015: 
        // for whatever reason your "again" variable (which is gross btw, use window. and declare properly... any other plugin could use 'again' and mess with your whole system.)
        // commented this out so I could get to post
        //if (e.preventDefault(), again > 0) 
            //#WHFIX 24/03/2015: 
            // added .show() to end of .toosoon2 populator, wasn't ever showing, but was hitting this wall
            //return jQuery(".toosoon2").html('<div class="alert alert-danger" role="alert">Sorry you are doing this too much. Try again in <span id="again">' + again + "</span> seconds</div>").show(), !1;
        if ("" == jQuery("#submission_url").val() || "" == jQuery("#submission_name").val() || "" == jQuery("#submission_tagline").val()) return alert("form invalid"), !1;
        nonce = jQuery("#_wpnonce").val(), url = jQuery("#submission_url").val(), title = jQuery("#submission_name").val(), desc = jQuery("#submission_tagline").val();

        e.preventDefault();
        //WH Debug
        var t = {
                action: "ph_newpost_child",
                security: nonce,
                url: url,
                title: title,
                desc: desc,
                feat: window.phfeatured
            },
            a = jQuery.ajax({
                url: HuntAjax.ajaxurl,
                type: "POST",
                data: t,
                dataType: "json"
            });
        a.done(function() {
            shtml = '<div class="new-post-header">POST SUBMITTED</div><div class="new-post-subheader">Your post will be reviewed by our team and if suitable make it onto the homepage </div>', shtml = window.PHnew.success, jQuery(".new-post-wrapper").hide(), jQuery(".new-post-form").hide(), jQuery(".new-post-success").html(shtml).show(), jQuery("#submission_url").val(""), jQuery("#submission_name").val(""), jQuery("#submission_tagline").val(""), again = 30
        }), a.fail(function() {
            shtml = '<div class="new-post-header">POST SUBMITTED</div><div class="new-post-subheader">Your post will be reviewed by our team and if suitable make it onto the homepage </div>', shtml = window.PHnew.success, jQuery(".new-post-wrapper").hide(), jQuery(".new-post-form").hide(), jQuery(".new-post-success").html(shtml).show(), jQuery("#submission_url").val(""), jQuery("#submission_name").val(""), jQuery("#submission_tagline").val(""), again = 30
        })
    })



}