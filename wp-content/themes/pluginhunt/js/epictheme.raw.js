var currentDrop;

var pluginHuntTheme_Global = {

    fbLoadTryCount: 0,
    twLoadTryCount: 0,
    keyset: 0,
    page: 1,
    epicload: 0,
    imgArray: []
}

function bindmedia(){

jQuery('.postmedia , .v-add').unbind("click").bind("click",function(e){
if (0 == HuntAjax.logged) return jQuery("#ph-log-social-new").click(), !1;

        //clicked the collect button. Reveal the collections modal and position it 
        //underneath the current clicked element.
        //while modal is open prevent scrolling
        jQuery('#ph_collections_list').html(''); //clear out the HTML
        jQuery('html').addClass('noscroll');
        pid = jQuery(this).data('pid');

        console.log("post id is " + pid);

        jQuery('.popover--simple-form--actions').attr('data-pid', pid);
        jQuery('.collections-popover--form--submit').attr('data-pid', pid);
        //remember to remove after modal gone...
        e.stopPropagation();  //stop the parent event firing.  
        e.preventDefault();
        popover = jQuery(".ph_popover_media");
        pw = popover.width() / 2;
        var collect = jQuery(this);
        var offset = collect.offset();
        var top = offset.top;
        var bottom = top + collect.height();
        var left = offset.left;
        var right = left + collect.width();
        var middle = left + (right - left)/2;
        middle = Math.max(0,middle - pw);

        //position the popover..
        popover.css({
           position:'absolute',
           top: bottom + 10,
           left: middle,
           zIndex:5000
         });

        jQuery('.ph_popover_media').show();

        return false;

        //grab it's contents via the AJAX call (passing the post ID)
        var t = {
                action: "ph_list_collections",
                pid: pid,
            },
            a = jQuery.ajax({
                url: HuntAjax.ajaxurl,
                type: "POST",
                data: t,
                dataType: "json"
            });

        a.done(function(msg) {
            console.log("ajax successful");
            jQuery('.collections-loading').hide();
            jQuery('#ph_collections_list').html(msg.html);
            pluginhuntbind();  //rebind fires
            console.log(msg);
         }), a.fail(function(msg) {
            console.log("ajax failure");
            console.log(msg);
        });
         
        console.log('this ph element has top,bottom (' +top+ ',' +bottom+ ') and left,middle,right (' +left+ ','+middle+',' +right+ ')');
});
}


function phvalidateEmail(e) {
    var t = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return t.test(e) ? !0 : !1
}

function phdropabble(){
    jQuery('.drop').remove();
    jQuery(".email-drop").each(function() {   
        if (typeof window.emailDrop != "undefined" && typeof window.emailDrop.destroy == "function") window.emailDrop.destroy();
        $example = jQuery(this), $target = $example.find(".drop-target"), window.emailDrop = new Drop({
            target: $target[0],
            content: $example.find(".ph-content").html(),
            position: "bottom center",
            openOn: "click",
            classes: "drop-theme-arrows-bounce"
        });
    })
    jQuery(".flag-drop").each(function() {
        if (typeof window.flagDrop != "undefined" && typeof window.flagDrop.destroy == "function") window.flagDrop.destroy();
        $example = jQuery(this), $target = $example.find(".drop-target"), window.flagDrop = new Drop({
            target: $target[0],
            content: $example.find(".ph-content").html(),
            position: "bottom center",
            openOn: "click",
            classes: "drop-theme-arrows-bounce"
        });
    })
    jQuery(".profile-drop").each(function() {
        if (typeof window.currentDrop != "undefined" && typeof window.currentDrop.destroy == "function") window.currentDrop.destroy();
        $example = jQuery(this), $target = $example.find(".drop-target");
            window.currentDrop = new Drop({
                target: $target[0],
                content: $example.find(".ph-content").html(),
                position: "bottom center",
                openOn: "hover",
                classes: "drop-theme-arrows-bounce",
            });
    })
}




function phflash(url){
    //new function to get the content from the URL and fill the slide out bar
    console.log("yes, I've fired " + url);
    jQuery('.modal-container').load(url +' #phsf', function(resp) {
        jQuery(".modal-loading").hide();
        phdropabble();
        pluginhuntbind();
        console.log('binding clicks');
        jQuery('.modal--close').toggle();
    });
}


function bindFires() {}

function updateTwitterValues(e, t) {
    if (typeof twttr != "undefined" && typeof twttr.widgets != "undefined") 
        jQuery("#twitter-share-section").html("&nbsp;"), jQuery("#twitter-share-section").html('<a href="https://twitter.com/share" class="twitter-share-button" data-url="' + e + '" data-size="medium" data-text="' + t + '" data-count="none" height:"20px" width:"57px">Tweet</a>'), twttr.widgets.load()
    else {
        var maxTwReloadTries = 5;
        if (window.pluginHuntTheme_Global.twLoadTryCount <= maxTwReloadTries){
            window.pluginHuntTheme_Global.twLoadTryCount++;
            setTimeout(function(){
                updateTwitterValues(e,t);
            },300);
        
        }

    }
}

function updateFacebookValues(e) {
    if (typeof FB != "undefined" && typeof FB.XFBML != "undefined") 
       jQuery("#facebook-share-section").html("&nbsp;"), jQuery("#facebook-share-section").html('<fb:like href="' + e + '" layout="button" action="like" show_faces="true" share="false"></fb:like>'), FB.XFBML.parse()
    else {
        var maxFbReloadTries = 5;
        if (window.pluginHuntTheme_Global.fbLoadTryCount <= maxFbReloadTries){
            window.pluginHuntTheme_Global.fbLoadTryCount++;
            setTimeout(function(){
                updateFacebookValues(e);
            },300);

        }

    }
}

function phhtmlEncode(e) {
    return jQuery("<div/>").text(e).html()
}

function epic_infinite_scroll() {
    if (jQuery("#epic_page_end_2").length > 0) return pluginHuntTheme_Global.epicload = 1, !1;
    if (pluginHuntTheme_Global.epicload = 1, msg = window.HuntAjax.epic_more, jQuery("#results").append("<div id='epic_page_end'>" + msg + "</div>"), 0 == pluginHuntTheme_Global.keyset && (key = jQuery("#epic-key").html()), 1 == pluginHuntTheme_Global.keyset, 1 == pluginHuntTheme_Global.keyset) var e = jQuery(".next-posts-link a").attr("href") + "&page=" + pluginHuntTheme_Global.page + "&key=" + key;
    else var e = jQuery(".next-posts-link a").attr("href") + "&page=" + pluginHuntTheme_Global.page;
    jQuery.get(e, function(e) {
        jQuery(e).find(".maincontent").appendTo("#results"), 0 == pluginHuntTheme_Global.keyset && (key = jQuery("#epic-key").html(), pluginHuntTheme_Global.keyset = 1), pluginHuntTheme_Global.page++, key++, pluginHuntTheme_Global.epicload = 0, jQuery("#epic_page_end").remove()
    })
}

function testMedia(e){
    var url = jQuery('#media_url').val();
    console.log(url + 'url has been pasted into the box');

}


jQuery(document).ready(function($){


function open_media_uploader_coll()
{

var frame = null;
var insertImage = wp.media.controller.Library.extend({
    defaults :  _.defaults({
            id:        'insert-image',
            title:      'Upload media',
            allowLocalEdits: false,
            displaySettings: false,
            displayUserSettings: false,
            multiple : false,
            type : 'image'//audio, video, application/pdf, ... etc
      }, wp.media.controller.Library.prototype.defaults )
});

cid = $('.collection-detail--header--background-uploader').data('pid');
console.log('collection id is ' + cid);


//Setup media frame
var frame = wp.media({
    button : { text : 'Select' },
    state : 'insert-image',
    states : [
        new insertImage()
    ]
});

wp.media.model.settings.post.id = cid;

frame.on('insert',function(){
    //the upload has completed, return attachement URL and close frame
    console.log('file has uploaded');
    frame.close();
})

//on close, if there is no select files, remove all the files already selected in your main frame
frame.on('close',function() {
    var selection = frame.state('insert-image').get('selection');
    var item;
    json = frame.state().get( 'selection' ).first().toJSON();

    //pass json.id to an ajax function which sets the post thumbnail for the collection ID.. (?)

    console.log('aid is ' + json.id + ' cid is ' + cid);

    var t = {
        action: "ph_update_collection_bgimg",
        aid: json.id,
        cid: cid
        },
        a = jQuery.ajax({
            url: HuntAjax.ajaxurl,
            type: "POST",
            data: t,
            dataType: "json"
        });
        a.done(function(msg) {
            console.log("ajax successful");
            console.log(msg);
            jQuery('.collection-detail--header').css('background-image', 'url(' + json.url + ')');
         }), a.fail(function() {
            console.log("ajax failure");
        })
    
    console.log(json);
    if(!selection.length){}
});

frame.open();

}






function open_media_uploader_np()
{

var frame = null;
var insertImage = wp.media.controller.Library.extend({
    defaults :  _.defaults({
            id:        'insert-image',
            title:      'Upload media',
            allowLocalEdits: false,
            displaySettings: false,
            displayUserSettings: false,
            multiple : false,
            type : 'image'//audio, video, application/pdf, ... etc
      }, wp.media.controller.Library.prototype.defaults )
});


//Setup media frame
var frame = wp.media({
    button : { text : 'Select' },
    state : 'insert-image',
    states : [
        new insertImage()
    ]
});

frame.on('insert',function(){
    //the upload has completed, return attachement URL and close frame
    console.log('file has uploaded');
    frame.close();
})

//on close, if there is no select files, remove all the files already selected in your main frame
frame.on('close',function() {
    var selection = frame.state('insert-image').get('selection');
    var item;
    json = frame.state().get( 'selection' ).first().toJSON();
    
    console.log('selection');
    console.log(json);

    console.log('selection length ' + selection.length);

    jQuery(".media-items").append('<div class="media-parent"><div class="media-item" style="background-image:url('+ json.url +');" data-aid=""><a class="remove-media" href="#" data-aid="'+ json.id + '"><i class="fa fa-times"></i></a></div></div>');
    item = {};
    item["url"] = json.url;
    item["source"] = 'med';

    pluginHuntTheme_Global.imgArray.push(item);

    if(!selection.length){
        /* remove file nodes
        #such as: jq("#my_file_group_field").children('div.image_group_row').remove();
        #...
        */
    }
});

frame.open();

}


jQuery('.collection-detail--header--background-uploader').unbind("click").bind("click",function(e){
    //trigger the upload 
    console.log("triggerd collections uploader");
    open_media_uploader_coll();
});


jQuery('.trigger-upload').unbind("click").bind("click",function(e){
    //trigger the upload 
    console.log("triggerd");
    open_media_uploader_np();
});

});

function phisUrlValid(url) {
    return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
}


function getYouTubeVideoImagePM(url, size) {
    if (url === null) {
        return '';
    }

    size = (size === null) ? 'big' : size;
    var vid;
    var results;

var videoid = url.match(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/);
if(videoid != null) {
    console.log("video id = ",videoid[1]);
    // and we have a video URL
    item = {};
    item["url"] = url;
    item["source"] = 'yt';
    item['image'] = 'http://img.youtube.com/vi/' + videoid[1]+ '/0.jpg';
    item['id'] = videoid[1];
    pluginHuntTheme_Global.imgArray[0] = item;
    jQuery('.popover--simple-form--actions').attr('data-vid', item['id']);
    jQuery('.popover--simple-form--actions').attr('data-source', item['source']);
    return item['image'];
    pluginhuntbind();
} else { 
    console.log("The youtube url is not valid.");
    return false;
}

  return true

}


function getYouTubeVideoImage(url, size) {
    if (url === null) {
        return '';
    }

    size = (size === null) ? 'big' : size;
    var vid;
    var results;

var videoid = url.match(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/);
if(videoid != null) {
    console.log("video id = ",videoid[1]);
    // and we have a video URL
    item = {};
    item["url"] = url;
    item["source"] = 'yt';
    item['image'] = 'http://img.youtube.com/vi/' + videoid[1]+ '/0.jpg';
    item['id'] = videoid[1];
    pluginHuntTheme_Global.imgArray.push(item);
    console.log("global array is " + JSON.stringify(pluginHuntTheme_Global.imgArray));
    pluginhuntbind();
} else { 
    console.log("The youtube url is not valid.");
    return false;
}

        iurl = 'http://img.youtube.com/vi/' + videoid[1]+ '/0.jpg';
        jQuery(".media-items").append('<div class="media-parent"><div class="media-item" style="background-image:url('+ iurl +');" data-aid=""><a class="remove-media" href="'+url+'" data-aid="new" data-type="yt"><i class="fa fa-times"></i></a><a href="'+url+'" target="_blank" class="open-video"><span><svg width="35" height="35" viewBox="0 0 35 35" xmlns="http://www.w3.org/2000/svg"><path d="M17.5 35C27.165 35 35 27.165 35 17.5S27.165 0 17.5 0 0 7.835 0 17.5 7.835 35 17.5 35zm-3.71-24.57c-.152 0-.305.038-.444.116-.29.163-.47.472-.47.807l-.015 12.892c0 .336.18.645.472.808.138.077.29.116.445.116.167 0 .335-.047.483-.14l10.54-6.447c.27-.168.433-.465.433-.784 0-.32-.164-.617-.433-.786L14.274 10.57c-.147-.094-.315-.14-.483-.14z" fill="#FFF" fill-rule="evenodd"></path></svg></span></a></div></div>');
        return true

}


function editTitle(e) {
        if (e.keyCode == 13) {
            console.log('title which is being editied has been clicked');
            newtitle = jQuery('.edit-title-input').val();
            cid = window.phcid;
            console.log("editing collection title for ID " + cid);
            jQuery('#collection-title').show();
            jQuery('.collection-title').html(newtitle);
            jQuery('.edit-title-input').addClass('hide');
            jQuery('input[name=etitle]').val(newtitle);
            
            //send AJAX to the server..
        //grab it's contents via the AJAX call (passing the post ID)
        var t = {
                action: "ph_update_collection_title",
                title: newtitle,
                cid: cid
            },
            a = jQuery.ajax({
                url: HuntAjax.ajaxurl,
                type: "POST",
                data: t,
                dataType: "json"
            });
        a.done(function(msg) {
            console.log("ajax successful");
            console.log(msg);
         }), a.fail(function() {
            console.log("ajax failure");
        })

        }
}

function editDesc(e) {
        if (e.keyCode == 13) {
            console.log('title which is being editied has been clicked');
            newtitle = jQuery('.edit-content-input').val();
            cid = window.phcid;
            console.log("editing collection description for ID " + cid);
            jQuery('.collection-content').show();
            jQuery('.collection-content').html(newtitle);
            jQuery('.edit-content-input').addClass('hide');
            jQuery('input[name=econtent]').val(newtitle);
            
        //grab it's contents via the AJAX call (passing the post ID)
        var t = {
                action: "ph_update_collection_desc",
                title: newtitle,
                cid: cid
            },
            a = jQuery.ajax({
                url: HuntAjax.ajaxurl,
                type: "POST",
                data: t,
                dataType: "json"
            });
        a.done(function(msg) {
            console.log("ajax successful");
            console.log(msg);
         }), a.fail(function() {
            console.log("ajax failure");
        })

        }
}

// solution:
//function to remove a value from the json array
function phremoveItem(obj, prop, val) {
    var c, found=false;
    for(c in obj) {
        if(obj[c][prop] == val) {
            found=true;
            break;
        }
    }
    if(found){
        delete obj[c];
    }
}


function bindreply(){
    jQuery(".reply-comment").unbind("click").bind("click",function(e){
        // replying to a comment. assign it's parent
        window.phcr = jQuery(this).data("cid");
        console.log(window.phcr);
        window.un = jQuery(this).data('un');
        jQuery("#comment_parent").val(window.phcr);
        jQuery('.post-detail--footer--comments-form-toggle--link').val('@' + window.un + ' ');
        jQuery('.post-detail--footer--comments-form-toggle--link').click().focus();
    });
}

function pluginhuntbind() {
    function e() {
        jQuery(".upvote").bind("click", function() {
            return id = jQuery(this).data("id"), alert("comment with id " + id + " clicked"), !1
        })
    }
    jQuery(".ph-log-new").bind("click", function(e) {
        return e.preventDefault(), 0 == HuntAjax.logged ? (jQuery("#ph-log-social-new").click(), !1) : void 0
    })

    jQuery(".modal-overlay").unbind("click").bind("clik",function(e){
        jQuery("body").addClass("showing-discussion");
        jQuery(".modal-overlay").show();
        jQuery(".show-post-modal").show();
        jQuery(".modal-container").show();
        jQuery(".modal-loading").show();
        jQuery(".ph_popover").hide();
    })

    bindreply();
    bindmedia(); 

    jQuery(".comment-cancel").unbind("click").bind("click",function(e){
        jQuery(".comment-actions").hide();
        jQuery(".comment-post").val("");
        jQuery('.post-detail--footer-2').removeClass('high');
    });

    jQuery('body').unbind("click").bind("click",function(e){
        jQuery('html').removeClass('noscroll');
    });

    jQuery('.edit-title').unbind("click").bind("click",function(e){
        window.phcid = jQuery(this).data("cid");
        jQuery('.edit-title-input').removeClass('hide');
        jQuery('#collection-title').hide();
    });

    jQuery('.edit-content').unbind("click").bind("click",function(e){
        window.phcid = jQuery(this).data("cid"); 
        jQuery('.edit-content-input').removeClass('hide');
        jQuery('.collection-content').hide();
    });

var commentform=jQuery('#phcommentform'); // find the comment form
commentform.prepend('<div id="comment-status" ></div>'); // add info panel before the form to provide feedback or errors
var statusdiv=jQuery('#comment-status'); // define the info panel
var list ;
var reply = false;

jQuery('.comment-submit').unbind("click").bind("click",function(){
//serialize and store form data in a variable
var formdata=commentform.serializeArray();
if(window.phcr){
    // Find and replace `content` if there
    for (index = 0; index < formdata.length; ++index) {
        if (formdata[index].name == "comment_parent") {
            formdata[index].value = window.phcr;
            reply = true;
            break;
        }
    }
}

// Convert to URL-encoded string
values = jQuery.param(formdata);

//Add a status message
jQuery('.comment-submit').addClass('disabled').val('Processing..');

//Extract action URL from commentform
var formurl=commentform.attr('action');
//Post Form with data

jQuery.ajax({
type: 'post',
url: formurl,
data: formdata,
error: function(XMLHttpRequest, textStatus, errorThrown)
{ },
success: function(data, textStatus){
if(data == "success" || textStatus == "success"){


console.log(data); //lets see what's kicked out...

jQuery('.comment-submit').removeClass('disabled').val('Submit');
//alert(data);

console.log(reply);

    if(reply){
    //we are replying to post with window.phcr
        cparent = window.phcr;
        console.log('cparent is ' + cparent);
        if(jQuery("#comment-"+cparent).has(".child-comments").length>0){
            jQuery("#comment-"+cparent + " .child-comments").first().append(data);
        }else{
            jQuery("#comment-"+cparent).append(data);
        }
    
    }else{
        if(jQuery("#comments").has("ol.comment-list").length > 0){
            jQuery('ol.comment-list').append(data);
        }else{
            jQuery("#comments").html('<ol class="comment-list"> </ol>');
            jQuery('ol.comment-list').html(data);
        }
    }

    //kill the controls
    jQuery(".comment-actions").hide();
    jQuery(".comment-post").val("");
    //sort the form out
    jQuery(".comment-actions").hide();
    jQuery(".comment-post").val("");
    jQuery(".post-detail--footer-2").removeClass("high");
    jQuery("#comments").animate({ scrollTop: jQuery("#comments")[0].scrollHeight}, 1000); 
    bindreply();  //bind the replies

}else{
    statusdiv.html('<p class="ajax-error" >Please wait a while before posting your next comment</p>');
    commentform.find('textarea[name=comment]').val('');
}
}
});
return false;
});





    jQuery('.ph-remove-from-collection').unbind("click").bind("click",function(e){
        //adding to collections UI. need to pass to an AJAX function passing the collection ID and the post ID
        e.stopPropagation();
        e.preventDefault();
        cid = jQuery(this).data('cid');
        pid = jQuery(this).data('pid');
        console.log("this will remove the post with id " + pid + " from the collection with collection id " + cid)

        //grab it's contents via the AJAX call (passing the post ID)
        var t = {
                action: "ph_remove_from_collection",
                pid: pid,
                cid: cid
            },
            a = jQuery.ajax({
                url: HuntAjax.ajaxurl,
                type: "POST",
                data: t,
                dataType: "json"
            });
        a.done(function(msg) {
            console.log("ajax successful");
            jQuery('.collections-loading').hide();
            jQuery('#ph_collections_list').html(msg.html);
            jQuery('.popover--footer').hide();
            jQuery(".popover--header--title").html("Removed!");
            jQuery('.popover--header--icon').hide();
            pluginhuntbind();  //rebind fires
            console.log(msg);
         }), a.fail(function() {
            console.log("ajax failure");
        })

    });


    

    jQuery(".collection-detail--header--delete-button").unbind("click").bind("click",function(e){
        cid = jQuery(this).data('cid');
        console.log(cid);

        var r = confirm('Are you sure you want to delete this collection? There is no way back. This cannot be undone');
        if (r == true) {
            //pass AJAX function to delete collection.
        var t = {
                action: "ph_delete_collection",
                cid: cid
            },
            a = jQuery.ajax({
                url: HuntAjax.ajaxurl,
                type: "POST",
                data: t,
                dataType: "json"
            });
        a.done(function(msg) {
            console.log('collection deleted');
            console.log(msg);
            window.location.replace(window.HuntAjax.epichome);
         }), a.fail(function() {
            console.log("ajax failure");
        })
        } else {
            return false;
        }
    });


    jQuery('.ph-add-to-collection').unbind("click").bind("click",function(e){
        //adding to collections UI. need to pass to an AJAX function passing the collection ID and the post ID
        e.stopPropagation();
        e.preventDefault();
        cid = jQuery(this).data('cid');
        pid = jQuery(this).data('pid');
        console.log("this will add the post with id " + pid + " to the collection with collection id " + cid)

        //grab it's contents via the AJAX call (passing the post ID)
        var t = {
                action: "ph_add_collection",
                pid: pid,
                cid: cid
            },
            a = jQuery.ajax({
                url: HuntAjax.ajaxurl,
                type: "POST",
                data: t,
                dataType: "json"
            });
        a.done(function(msg) {
            console.log("ajax successful");
            jQuery('.collections-loading').hide();
            jQuery('#ph_collections_list').html(msg.html);
            jQuery('.popover--footer').hide();
            jQuery(".popover--header--title").html("Nice work!");
            jQuery('.popover--header--icon').hide();
            pluginhuntbind();  //rebind fires
            console.log(msg);
         }), a.fail(function() {
            console.log("ajax failure");
        })

    })

    jQuery('.ph-collect,.ph-collect-single,.collect-button--icon').unbind("click").bind("click",function(e){

if (0 == HuntAjax.logged) return jQuery("#ph-log-social-new").click(), !1;

        jQuery('.collections-loading').show();
        //clicked the collect button. Reveal the collections modal and position it 
        //underneath the current clicked element.
        //while modal is open prevent scrolling
        jQuery('#ph_collections_list').html(''); //clear out the HTML
        jQuery('html').addClass('noscroll');
        pid = jQuery(this).data('pid');

        jQuery('.collections-popover--form--submit').attr('data-pid', pid);
        //remember to remove after modal gone...
        e.stopPropagation();  //stop the parent event firing.  
        e.preventDefault();
        popover = jQuery(".ph_popover");
        pw = popover.width() / 2;
        var collect = jQuery(this);
        var offset = collect.offset();
        var top = offset.top;
        var bottom = top + collect.height();
        var left = offset.left;
        var right = left + collect.width();
        var middle = left + (right - left)/2;
        middle = Math.max(0,middle - pw);

        //position the popover..
        popover.css({
           position:'absolute',
           top: bottom + 10,
           left: middle,
           zIndex:5000
         });

        jQuery('.ph_popover').show();

        //grab it's contents via the AJAX call (passing the post ID)
        var t = {
                action: "ph_list_collections",
                pid: pid,
            },
            a = jQuery.ajax({
                url: HuntAjax.ajaxurl,
                type: "POST",
                data: t,
                dataType: "json"
            });
        a.done(function(msg) {
            console.log("ajax successful");
            jQuery('.collections-loading').hide();
            jQuery('#ph_collections_list').html(msg.html);
            pluginhuntbind();  //rebind fires
            console.log(msg);
         }), a.fail(function() {
            console.log("ajax failure");
        })

        

        console.log('this ph element has top,bottom (' +top+ ',' +bottom+ ') and left,middle,right (' +left+ ','+middle+',' +right+ ')');
    });

    jQuery('.collections-popover--form--field,.collections-popover--form--submit').unbind("click").bind("click",function(e){
        e.stopPropagation();
    })

    jQuery(".collections-popover--form--submit").unbind("click").bind("click",function(e){
        e.preventDefault();
        e.stopPropagation();
        name = jQuery('.collections-popover--form--field').val();
        if(name ==''){
            console.log('name is blank');
            return false;
        }
        console.log('new collection clicked with collection ' + name);
        //creates a collection AND then adds the post to it.
        var uid = HuntAjax.logged;
        var prod = jQuery(this).data('pid'); //our test product replace with Actual.. 
        var pname ='White Label Login for WordPress'; //our test product..         
        var t = {
                action: "ph_create_collection",
                name: name,
                prod: prod
            },
            a = jQuery.ajax({
                url: HuntAjax.ajaxurl,
                type: "POST",
                data: t,
                dataType: "json"
            });
        a.done(function(msg) {
            console.log("ajax successful");
            jQuery('.popover--header--title').html("Nice work!");
            jQuery('.popover--header--icon').toggle();
            jQuery('.cmsg').html(msg.html);
            jQuery('.popover--footer').toggle();
            console.log(msg);
         }), a.fail(function() {
            console.log("ajax failure");
        })

        console.log('current user is ' + logged);


        return false;
    })

    jQuery(".comment-post").bind("click",function(e){
        jQuery(".comment-actions").show();
        jQuery('.post-detail--footer-2').addClass('high');
    });

    jQuery(".ph-collect").unbind("click").bind("click",function(e){
        e.preventDefault();
    });
    jQuery(".collections-popover--form-trigger").unbind("click").bind("click",function(e){
        e.preventDefault();
        e.stopPropagation();
        jQuery('.collections-form, .collections-popover--form-trigger').toggle();
    })

    jQuery(".ph-request-access").unbind("click").bind("click",function(e){
        e.preventDefault();
        var uid = jQuery(this).data('uid');
        // post an AJAX from the front end to admin to update the user meta.
        console.log('user is ' + uid);
            var t = {
                action: "ph_access_request",
                uid: uid,
            },
            a = jQuery.ajax({
                url: HuntAjax.ajaxurl,
                type: "POST",
                data: t,
                dataType: "json"
            });
        a.done(function(msg) {
            console.log("ajax successful");
            console.log(msg);
         }), a.fail(function() {
            console.log("ajax failure");
        })
        jQuery(".ph-user-message-text").html('You have been added to the waiting list.')
        jQuery(".ph-user-message").slideDown( "slow", function() {
                //animation complete
        });
        jQuery(".ph-request-msg").html("You have been added to the waiting list. <span class='emo'>&#x1f483;</span>")
    });

    jQuery(".ph-user-close").unbind("click").bind("click",function(e){
        jQuery(".ph-user-message").slideUp( "slow", function() {
                //animation complete
        });
    })

    jQuery(".page-header--navigation--tab").bind("click",function(e){
        e.preventDefault();
        jQuery(".page-header--navigation--tab").removeClass('m-active');
        jQuery(this).addClass('m-active');
        var id = jQuery(this).attr('id');
        jQuery('.ph-tabbed').hide();
        jQuery('#' + id + '-tab').show();
    })

    jQuery(".new_post_submit").bind("click", function(e) {
        e.preventDefault();
     if ("" == jQuery("#submission_url").val() || "" == jQuery("#submission_name").val() || "" == jQuery("#submission_tagline").val()) return alert("form invalid"), !1;
        nonce = jQuery("#_wpnonce").val(), url = jQuery("#submission_url").val(), title = jQuery("#submission_name").val(), desc = jQuery("#submission_tagline").val();

        var t = {
                action: "ph_newpost",
                security: nonce,
                url: url,
                title: title,
                desc: desc
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

    jQuery('.ph-follow').bind("click",function(e){
        e.preventDefault();
        if (0 == HuntAjax.logged) return jQuery("#ph-log-social-new").click(), !1;
        var d = jQuery(this).data();
        var followed = d.follow;
        var follower = d.follower;
        var crud = d.crud;
        var o = {
                action: "ph_follows",
                followed: followed,
                follower: follower,
                crud: crud,
            },
            n = jQuery.ajax({
                url: EpicAjax.ajaxurl,
                type: "POST",
                data: o,
                dataType: "json"
            });
        return n.done(function(f) {
            if(crud == 0){
                jQuery(".ph-follow").attr('data-crud', '1');
                jQuery(".ph-follow").html("Follow");
                jQuery(".ph-follow").removeClass('v-red').addClass('v-green');
                d.crud = 1;
                crud = d.crud;
            }else{
                jQuery(".ph-follow").attr('data-crud', '0');
                jQuery(".ph-follow").html("Unfollow");
                jQuery(".ph-follow").removeClass('v-green').addClass('v-red');
                d.crud = 0;
                crud = d.crud;
            }
            }), n.fail(function(e, t) {
            alert("Request failed: " + t)
        }), !0
    });
   


function IsValidImageUrlPM(url) {
     var item;
    //test for duplicates, if none then proceed..


    jQuery("<img>", {
        src: url,
        error: function() { 
            // is not an image - try youtube, else "invalide"
            iurl = getYouTubeVideoImagePM(url);
            jQuery('.img-prev').html("<img src='"+iurl+"'/>");

        },
        load: function() { 
            //if it's an image, apend to the media items html
            item = {}
            item["url"] = url;
            item["source"] = 'ei';
            pluginHuntTheme_Global.imgArray[0] = item;
            jQuery('.popover--simple-form--actions').attr('data-source', item['source']);
            jQuery('.img-prev').html("<img src='"+url+"'/>");
        }
    });
}


function IsValidImageUrl(url) {
     var item;
    //test for duplicates, if none then proceed..


    jQuery("<img>", {
        src: url,
        error: function() { 
            // is not an image - try youtube, else "invalide"
            getYouTubeVideoImage(url);

        },
        load: function() { 
            //if it's an image, apend to the media items html
            jQuery(".media-items").append('<div class="media-parent"><div class="media-item" style="background-image:url('+ url +');" data-aid=""><a class="remove-media" href="'+url+'" data-aid="0"><i class="fa fa-times"></i></a></div></div>');
            item = {}
            item["url"] = url;
            item["source"] = 'ei';
            pluginHuntTheme_Global.imgArray.push(item);
            console.log("global array is " + JSON.stringify(pluginHuntTheme_Global.imgArray));
        }
    });
}


jQuery(document).ready(function ($) {

    //social button sharing
    $('.share', this).bind('click', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    var loc = $(this).attr('href');
    var action = $(this).attr('data-action');
    
    if( action == 'twitter' )
    {
        var title  = $(this).attr('title');
        
        window.open('http://twitter.com/share?url=' + loc + '&text=' + title + ' - ' + loc + ' - via @twitter', 'twitterwindow', 'height=255, width=550, top='+($(window).height()/2 - 225) +', left='+($(window).width()/2 - 275 ) +', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
    }
    else if( action == 'facebook' )
    {
        var t = document.title;
    
        window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(loc)+'&t='+encodeURIComponent(t),'sharer','status=0,width=626,height=436, top='+($(window).height()/2 - 225) +', left='+($(window).width()/2 - 313 ) +', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
    }
    else if( action == 'google' )
    {
        window.open('https://plus.google.com/share?url='+encodeURIComponent(loc),'Share','status=0,width=626,height=436, top='+($(window).height()/2 - 225) +', left='+($(window).width()/2 - 313 ) +', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0'); 
    }
});
    

    jQuery('form#popover--simple-form--input :input[name="media_url"]').off('input').on('input',function(e){ 
        var str = this.value;
        console.log('the url pasted is ' + str);
        var dup = false;
        //check for duplicate
          //first check if URL is valid
        if(phisUrlValid(str)){
            iurl = IsValidImageUrlPM(str); // there is a console log in this function            
        }else{
                console.log("invalid url");
        }
        return true;
        
    });

    jQuery('form#post-submission :input[name="media_url"]').off('input').on('input',function(e){ 
        var str = this.value;
        console.log('the url pasted is ' + str);
        var dup = false;
        //check for duplicate
        jQuery('.remove-media').each(function(index){
            if(jQuery(this).attr('href') == str){
                dup = true;
                return false;
            }
            
        });
        if(dup){
            return false;
        }else{
          if(phisUrlValid(str)){
          IsValidImageUrl(str);
            }else{
                    console.log("invalid url");
            }

          return true;
        }
    });


    jQuery(document).on('click','.remove-media',function(e){
        e.preventDefault();
        aid = jQuery(this).data('aid');
        url = jQuery(this).attr('href');
        console.log("attachment with ID " + aid + " removed from media library " + url);
        jQuery(this).attr('href','#');
        jQuery(this).parents('.media-parent').html("");


        //example: call the 'remove' function to remove an item by id.
        phremoveItem(pluginHuntTheme_Global.imgArray,'url',url);

    });

    $('.popover--simple-form--actions').unbind("click").bind("click",function(e){
            //media url entered - handle PHP side if avaialble

            e.preventDefault();
            
            imgurl = $('.popover--simple-form--input').val();
            pid = $(this).data('pid');
            vid = $(this).data('vid');
            src = $(this).data('source');

            if(phisUrlValid(imgurl)){
                jQuery('.urlerror').html('<i class="fa fa-spinner fa-spin"></i>');
                console.log(imgurl);
                if(src == 'yt'){
                phm = '<a href="'+src+'" class="phlb" data-tp='+src+' data-yturl="https://www.youtube.com/watch?v='+vid+'"><img src="http://img.youtube.com/vi/'+vid+'/0.jpg" height="210px"></a>';
                }else{
                phm = '<a href="'+src+'" class="phlb" data-tp='+src+'><img src="'+imgurl+'" height="210px"></a>';
                }
                $('.carousel--controls').after(phm);
                $('.ph_popover_media').hide();
                $('.media-placeholder').addClass('hide');
      
                //ajax add the media and 
                var t = {
                        action: "ph_newmedia",
                        pid: pid,
                        vid: vid,
                        src: src,
                        img: imgurl
                    }

                a = jQuery.ajax({
                        url: EpicAjax.ajaxurl,
                        type: "POST",
                        data: t,
                        dataType: "json"
                });
                a.done(function(data) {
                    console.log("luke.. I am your father..");
                    console.log(data);
                }), a.fail(function() {
                    console.log("we have failed you, master..");
                }); 

            }else{
                jQuery('.urlerror').html("invalid");
                return false;
            }
    });



    $('.new-post-button').unbind("click").bind("click",function(e){
        if(window.HuntAjax.logged == 0){
            jQuery("#ph-log-social-new").click();
            return false;
        }
        $('.modal--close-new').toggle();
        console.log("new post button clicked");

        jQuery('html').css('overflow','hidden');

        //kill the current content
        jQuery('.modal-container').html("");

        jQuery("body").addClass("showing-discussion");
        jQuery(".modal-overlay-new").show();
        jQuery(".new-post-modal").show();
        jQuery(".new-modal-container").show();
        jQuery(".new-modal-loading").show();
        jQuery(".new-post-modal").removeClass("hide");




    });


    //our new post content....
    $('.ph-newsubmit').unbind("click").bind("click",function(e){

        e.preventDefault();

        //submit the content and create a new post. published or pending depending on settings (in localised array)
        name = $('#name').val();
        url = $('#url').val();
        tag = $('#tagline').val();
        media = pluginHuntTheme_Global.imgArray;

        jQuery('.ph-newsubmit').html("Hunting..");
        var t = {
                action: "ph_newpost",
                name: name,
                url: url,
                tag: tag,
                media: media
            }
            
        console.log(t);

        a = jQuery.ajax({
                url: EpicAjax.ajaxurl,
                type: "POST",
                data: t,
                dataType: "json"
        });
        a.done(function(data) {
            console.log("luke.. I am your father..");
            console.log(data);
            //on success redirect to the slug returned by the new post window
            window.location.replace(window.HuntAjax.epichome +'/' + data.slug);


        }), a.fail(function() {
            console.log("we have failed you, master..");
        });      


    });


    $('.ph-login-link').unbind("click").bind("click",function(e){
        if(window.HuntAjax.logged == 0){
            jQuery("#ph-log-social-new").click();
            return false;
        }
    });

    $('.modal--close').unbind("click").bind("click",function(e){
        $('.modal-overlay').toggle();
        $('.modal--close').toggle();
        $('html').removeClass('noscroll');
        console.log('modal close clicked');
        $('.ph_popover').hide();
    });    

    $('.phlb').unbind("click").bind("click",function(e){
        e.preventDefault();


    });

    $('.modal--close-new').unbind("click").bind("click",function(e){
        $('.new-post-modal').toggle();
        $('.modal-overlay-new').toggle();
        $('.modal--close-new').toggle();
        $("body").removeClass("showing-discussion");
        $('html').removeClass('noscroll');
        console.log('modal close new clicked');
        $('.ph_popover').hide();
        jQuery('html').css('overflow','visible');
    });  

//lets re-write the voting code based on the epic hackers version I wrote (reddit-voting click event)
  $(".reddit-voting").unbind("click").bind("click",function(e){

        if(window.HuntAjax.logged == 0){
            jQuery("#ph-log-social-new").click();
            return false;
        }

    console.log('our new arrow click button');

    id = $('.arrow', this).data('red-id');

    console.log("this id is " + id);

    if($('.arrow',this).hasClass('blue')){
      vote = 'd';
      console.log("the arrow has a class of blue");
    }else{
      vote = 'u';
      console.log("no class of blue here");
    }

    parent = $(this);
    elem = $('.arrow', this);

    console.log("the score class is arrow-" + id);
  
    score = $(".score-" + id);
    console.log("the score without parsing is " + score);
    scoreval = Number($(".score-" + id).html());

    //lets change the behaviour straight away (to improve UI)
              if(vote == 'u'){
                 score.html(scoreval + 1);
                 parent.addClass('blue');
                 elem.addClass('blue');
                 $(".arrow-up-" + id).parents('.reddit-voting').addClass('blue');
              }else if(vote == 'd'){
                  score.html(scoreval -1);
                  parent.removeClass('blue');
                  elem.removeClass('blue');
                  $(".arrow-up-" + id).parents('.reddit-voting').removeClass('blue');
              }
    //now lets update the DB (throttling needed to prevent negative pushing)
        var t = {
                action: "hackers_vote",
                id: id,
                vote: vote
            }
        a = jQuery.ajax({
                url: EpicAjax.ajaxurl,
                type: "POST",
                data: t,
                dataType: "json"
        });
        a.done(function() {
              if(vote == 'u'){
                console.log("old score is " + scoreval);
                newscore = scoreval + 1;
                console.log("new score is " + newscore);
                score.html(newscore);
                parent.addClass('blue');
                elem.addClass('blue');
                //the same arrows will need updating in the list too

                 $('.score-'+id).addClass('blue');
              }else if(vote == 'd'){
                  console.log("old score down is " + scoreval);
                  newscore = scoreval - 1;
                  console.log("new score down is " + newscore);
                  score.html(newscore);
                  parent.removeClass('blue');
                  elem.removeClass('blue');
              }
        }), a.fail(function() {
            console.log("we have failed you, master..");
        });
  });

})

    jQuery(".showmore").bind("click", function() {
        jQuery(this).hide(), ud = jQuery(this).data("d"), um = jQuery(this).data("m"), uy = jQuery(this).data("y"), jQuery(".hidepost-" + ud + "-" + um + "-" + uy).show()
    })

        
    //this is the function which grabs the information from the single post ... .
    jQuery(".reddit-post").unbind("click").bind("click", function() {
        jQuery('.drop').remove();
        
        jQuery('html').addClass('noscroll');        
        //kill the current content
        jQuery('.modal-container').html("");

        jQuery("body").addClass("showing-discussion");
        jQuery(".modal-overlay").show();
        jQuery(".show-post-modal").show();
        jQuery(".modal-container").show();
        jQuery(".modal-loading").show();
        jQuery(".show-post-modal").removeClass("hide");

        //test the new function coming out
        var url = jQuery(this).attr("data-ph-url");
        console.log("got url " + url);
        phflash(url);
        jQuery('.ph_popover').hide();
    })
}

function phTheme_bindPopBar(t,rajaxid,slug,upvotes,ava){

            jQuery("body").addClass("showing-discussion");
            jQuery(".show-post-modal").show();
            jQuery(".modal-container").show();

             
            jQuery(".post-url").html(t.title);
            jQuery("#ph_red_title").html(t.title);
            jQuery("#ph_red_title_flag").html(t.title);

            s = HuntAjax.epichome + "/posts/" + slug;
            updateTwitterValues(s, t.title);
            updateFacebookValues(s);

            // MURDER ALL DROPS!
            jQuery('.drop').remove();


            jQuery(".email-drop").each(function() {   

                    if (typeof window.emailDrop != "undefined" && typeof window.emailDrop.destroy == "function") window.emailDrop.destroy();

                    $example = jQuery(this), $target = $example.find(".drop-target"), window.emailDrop = new Drop({
                        target: $target[0],
                        content: $example.find(".ph-content").html(),
                        position: "bottom center",
                        openOn: "click",
                        classes: "drop-theme-arrows-bounce"
                    });

            })
            jQuery(".flag-drop").each(function() {

                    if (typeof window.flagDrop != "undefined" && typeof window.flagDrop.destroy == "function") window.flagDrop.destroy();

                    $example = jQuery(this), $target = $example.find(".drop-target"), window.flagDrop = new Drop({
                        target: $target[0],
                        content: $example.find(".ph-content").html(),
                        position: "bottom center",
                        openOn: "click",
                        classes: "drop-theme-arrows-bounce"
                    });
            })
            jQuery(".profile-drop").each(function() {

                    if (typeof window.currentDrop != "undefined" && typeof window.currentDrop.destroy == "function") window.currentDrop.destroy();

                    $example = jQuery(this), $target = $example.find(".drop-target");
                    window.currentDrop = new Drop({
                        target: $target[0],
                        content: $example.find(".ph-content").html(),
                        position: "bottom center",
                        openOn: "hover",
                        classes: "drop-theme-arrows-bounce",

                    });

            })

                jQuery(".can-comment").html(t.commentshtml);
                var a = "";for (i = 0; i < t.comments.length; i++) a += "<hr class='comments-rule'><div class='comment' data-comment-id=" + t.comments[i].id + "><div class='comment-body'><h2 class='comment-user-name'><a href=''>" + t.comments[i].author + "</a></h2><div class='maker'></div><div class='user-image-container'><a class='user-image-container' href='#'><img src='" + t.comments[i].ava + "'/></a></div><div class='comment-user-info'></div><div class='actual-comment'>" + t.comments[i].content + "</div></div></div>";
                jQuery(".comments").html(a);
                jQuery(".comnum").html(t.comments.length);


            jQuery(".post-tagline").html(t.content);
            
            if (null == t.upvotes) jQuery(".upvotes-modal").hide();

            jQuery(".scoremodal").html(upvotes), 
            jQuery(".arrow-modal").attr("class", function(e, t) {
                return t.replace(/\barrow-up\S+/g, "")
            })

            jQuery(".scoremodal").attr("class", function(e, t) {
                return t.replace(/\bscore-\S+/g, "")
            })

            jQuery(".arrow-modal").addClass("arrow-up-" + rajaxid), jQuery(".scoremodal").addClass("score-" + rajaxid);
            if (vp = jQuery(".vp").html(), null != t.upvotes) {
                var r = "";
                for (i = 0; i < t.upvotes.length; i++) r += '<div class="who-by-v votes-inner"><a class="drop-target drop-theme-arrows-bounce"><img class="img-rounded flash-ava" src="' + t.upvotes[i].ava + '"/></a><div class="ph-content pop-ava-v"><img id="modal-img" src="' + t.upvotes[i].ava + '"/><div class="user-info"><span class="user-name">' + t.upvotes[i].user + '</span><div class="view-profile"><div class="btn btn-success primary ph_vp"><a href="' + t.upvotes[i].hr + '">' + vp + "</a></div></div></div></div></div>"
            }
            jQuery(".user-votes").html(r)
            jQuery(".modal-loading").hide()
            jQuery(".modal-container").show()
            jQuery(".new-post-modal-close").show()
            jQuery(".show-post-modal").removeClass("hide")
            jQuery(".new-post-modal").removeClass("hide")
            jQuery(".new-post-modal-close").removeClass("hide")
            jQuery(".drop-content img#modal-img.poster-ava").attr("src", ava)
            prof = jQuery(".profile-drop").html()
            jQuery(".drop-content img#modal-img.poster-ava").html(prof)


}

function validateURL(e) {
    var t = new RegExp("^(http|https|ftp)://([a-zA-Z0-9.-]+(:[a-zA-Z0-9.&%jQuery-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]).(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0).(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0).(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|([a-zA-Z0-9-]+.)*[a-zA-Z0-9-]+.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(:[0-9]+)*(/(jQuery|[a-zA-Z0-9.,?'\\+&%jQuery#=~_-]+))*jQuery");
    return t.test(e)
}

function phf() {
    jQuery("#phf").slideDown("slow")
}

function hasScrolled() {
    var e = jQuery(this).scrollTop();
  //  Math.abs(lastScrollTop - e) <= delta || (e > lastScrollTop && e > navbarHeight ? jQuery(".nav-fixed-top").hide() : e + jQuery(window).height() < jQuery(document).height() && jQuery(".nav-fixed-top").show(), lastScrollTop = e)
}

function start() {
    jQuery(".example").each(function() {
        $example = jQuery(this), $target = $example.find(".drop-target"), drop = new Drop({
            target: $target[0],
            content: $example.find(".ph-content").html(),
            position: "bottom center",
            openOn: "hover",
            classes: "drop-theme-arrows-bounce"
        })
    }), jQuery(".example-v").each(function() {
        $example = jQuery(this), $target = $example.find(".drop-target"), drop = new Drop({
            target: $target[0],
            content: $example.find(".ph-content").html(),
            position: "bottom center",
            openOn: "click",
            classes: "drop-theme-arrows-bounce"
        })
    })
}

jQuery(document).keyup(function(e) {
  if (e.keyCode == 27) { 
            jQuery(".show-post-modal").hide();
            jQuery(".new-post-modal").hide();
            jQuery("body").removeClass("showing-discussion");
            jQuery(".new-post-modal-close").hide();
            jQuery(".modal-container").hide();
            jQuery('.drop').remove();
     }  
});



jQuery(window).scroll(function() {
    jQuery(window).scrollTop() + jQuery(window).height() >= jQuery(document).height() && 0 == pluginHuntTheme_Global.epicload && 0 == jQuery("#epic_page_end_2").length && (epic_infinite_scroll(), pluginhuntbind())
}), "loading" != document.readyState ? (start(), jQuery("[id]").each(function() {
    var e = jQuery('[id="' + this.id + '"]');
    e.length > 1 && e[0] == this 
})) : document.addEventListener("DOMContentLoaded", start), jQuery(document).ready(function() {
    jQuery(document).on("click", ".new-post-modal-close", function() {
            jQuery(".show-post-modal").hide();
            jQuery(".modal-container").hide();
            jQuery(".show-post-modal, .new-post-modal-close").addClass('hide');
            jQuery(".new-post-modal").hide();
            jQuery("body").removeClass("showing-discussion");
            jQuery(".new-post-modal-close").hide();
    })
    
    jQuery(document).on("click", ".email-post-ph .ph_cancel", function() {
        window.emailDrop.close();
    })


    jQuery(document).on("click",".show-post-modal",function(e){
        e.stopPropagation();
    });


    jQuery(document).on("click",".modal-overlay",function(){

            jQuery(".show-post-modal").hide();
            jQuery(".modal-container").hide();
            jQuery(".show-post-modal, .new-post-modal-close").addClass('hide');
            jQuery(".new-post-modal").hide();
            jQuery("body").removeClass("showing-discussion");
            jQuery(".new-post-modal-close").hide();
            jQuery(".modal-overlay").hide();
            jQuery('html').removeClass('noscroll');
    })

    jQuery(document).on("click", ".flag-post-ph .ph_cancel", function() {
        window.flagDrop.close();
    })

    
    jQuery(document).on("click", ".ph_vp_email", function() {
        if (0 == HuntAjax.logged) return jQuery("#ph-log-social-new").click(), !1;
        var e = jQuery(".drop-content input#ph_email").val(),
            t = jQuery(this).data("id"),
            a = jQuery(this).data("perma");
        if (!phvalidateEmail(e)) return jQuery(".alert-ph").show(), !1;
        if (jQuery(".alert-ph").hide(), "undefined" == typeof r) var t = jQuery(this).data("id"),
            r = t;
        else var t = r;
        if ("undefined" == typeof o) var a = jQuery(this).data("perma"),
            o = a;
        else var a = o;

        var n = {
                action: "epicred_ajax_mail",
                mail: e,
                post: t,
                perma: a
            },
            s = jQuery.ajax({
                url: EpicAjax.ajaxurl,
                type: "POST",
                data: n,
                dataType: "json"
            });
        s.done(function(e) {
            jQuery(".ph-email").addClass("sent");
            jQuery(".ph-email").html(jQuery(".email-success").html())
        }), s.fail(function(e, t) {
            alert("Request failed: " + t)
        })
    });



    jQuery(document).on("click", ".email-drop", function() {
        emsg = jQuery(".email-success").html(), jQuery(".ph-email").is(".sent") ? (jQuery(".ph-email").html(econtent), jQuery(".ph-email").removeClass("sent")) : econtent = jQuery(".ph-email").html()
    })

    jQuery(document).on("click", ".ph_vp_flag", function() {
        if (0 == HuntAjax.logged) return jQuery("#ph-log-social-new").click(), !1;
        var e = jQuery(".drop-content textarea#body-flag-ph").val(),
            e = phhtmlEncode(e);
        if ("undefined" == typeof a) var t = jQuery(this).data("id"),
            a = t;
        else var t = a;
        if ("undefined" == typeof o) var r = jQuery(this).data("perma"),
            o = r;
        else var r = o;
        var n = {
                action: "epicred_ajax_flag",
                mail: e,
                post: t,
                perma: r
            },
            s = jQuery.ajax({
                url: EpicAjax.ajaxurl,
                type: "POST",
                data: n,
                dataType: "json"
            });
        s.done(function(e) {
            msg = jQuery(".ph-flag-done").html(), jQuery(".ph-flag").html(msg)
        }), s.fail(function(e, t) {
            alert("Request failed: " + t)
        })
    })


    jQuery(".post-meta-flash").show(), jQuery(".modal-loading").hide(), jQuery(".modal-container").show(), again = 30 - window.ehacklast, window.setInterval(function() {
        again = 1
        jQuery("#again").html(again), 1 == again && jQuery(".toosoon2").fadeOut(1e3)
    }, 1e3)
    jQuery(window).scroll(function() {
        jQuery(window).scrollTop() == jQuery(document).height() - jQuery(window).height() && 0 == pluginHuntTheme_Global.epicload && 0 == jQuery("#epic_page_end_2").length && (epic_infinite_scroll(), pluginhuntbind())
    }), setTimeout(function() {
        phf()
    }, 2e3)

    jQuery(".icon-x").click(function() {
        jQuery("#phf").slideUp("slow")
    }), showing = !1, pluginhuntbind()
});

var didScroll, lastScrollTop = 0,
    delta = 5,
     navbarHeight = jQuery(".site--header-d").outerHeight();
jQuery(window).scroll(function() {
    didScroll = !0
}), setInterval(function() {
    didScroll && (hasScrolled(), didScroll = !1)
}, 250);    