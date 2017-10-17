/*
 * Override some styles of the AddThis buttons
 *
 * @author Heidi Hunter
 */
jQuery(document).ready(function($) {
    var follow;
    
    function changeAddThisFollowColor(){
        if ($('#social_media_follow .at-icon-wrapper').css('background-color', '#414042').size() > 0) {
            window.clearInterval(follow);
        }
    }
    
    if ($('#social_media_follow').size() > 0) {
        follow = window.setInterval(changeAddThisFollowColor, 500);
    }
    
    var share;
    
    function changeAddThisShareColor() {
        if ($('.addthis_sharing_toolbox .at-icon-wrapper').css('background-color', '#414042').size() > 0) {
            window.clearInterval(share);
        }
    }
    
    if ($('.addthis_sharing_toolbox').size() > 0) {
        share = window.setInterval(changeAddThisShareColor, 500);
    }
    
});
