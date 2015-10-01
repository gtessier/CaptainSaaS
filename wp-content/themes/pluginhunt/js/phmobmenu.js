jQuery(document).ready(function() {   
            var sideslider = jQuery('[data-toggle=collapse-side]');
            var sel = sideslider.attr('data-target');
            var sel2 = sideslider.attr('data-target-2');
            sideslider.click(function(event){
                jQuery(sel).toggleClass('in');
                jQuery(sel2).toggleClass('out');
            });
        });