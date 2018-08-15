<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

?>

<div class="reviews_component my-3">
    <div class="reviews_component_title border-bottom">
        <h3 class="font-weight-bold m-0"><?php echo $this->translate('Featured Reviews'); ?></h3>
    </div>
    <div id="sd-response" class="container d-flex justify-content-center">
        <!-- Loader goes here -->
    </div>
    <div class="reviews_component_content mt-3 p-0">
        <!-- Content of ajax call goes here -->
    </div>
</div>

<script type="text/javascript">
// Dom ready
en4.core.runonce.add(function(){
    loadReviewResults();
});

// Checking how many Review Cards to Display
var checkWidth = window.innerWidth;

function loadReviewResults(){
    // Request data can be linked to form inputs
    var requestData = {};
    requestData.limit = 10;
    requestData.page = 1;
    
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader mt-5");
    var url = en4.core.baseUrl+"api/v1/review";
    
    var request = new Request.JSON({
        url: url,
        method: 'get',
        data: requestData,
        onRequest: function(){ loader.inject($("sd-response")); }, // When request is sent.
        onError: function(){ loader.destroy(); }, // When request throws an error.
        onCancel: function(){ loader.destroy(); }, // When request is cancelled.
        onSuccess: function(responseJSON){ // When request is succeeded.
            loader.destroy(); 

            var reviewsContent = document.querySelector('.reviews_component_content');

            if(responseJSON.status_code == 200){

                var html = "";
                var results = responseJSON.body.Results;
                // Testing... Delete after done
                console.log(results);

                var j = 0;
                for(var i = 0; i < results.length; i++) {
                // Rating Stars Functionality
                    var star_rating = "";
                    var star_background = "";
                    for(var k = 0; k < results[i].authorRating; k++){
                        if(k > 0){
                            star_background = "bg-white";
                        }else{
                            star_background = "";
                        }
                        star_rating += '<svg width="20px" height="15px" style="margin: 3px 5px 0px 0px;" id="986bffda-3f05-4b93-986d-19fb19c532ee" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 21.06 20"><defs><linearGradient id="7f161091-907f-4bc3-958f-efd13f35ea8f" x1="-246.44" y1="358.88" x2="-245.25" y2="360.17" gradientTransform="matrix(7.2, 0, 0, -5.79, 1787.47, 2087.17)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#ce8f2a"></stop><stop offset="0.17" stop-color="#cf9330"></stop><stop offset="0.38" stop-color="#d39d3f"></stop><stop offset="0.62" stop-color="#d8ae59"></stop><stop offset="0.67" stop-color="#dab360"></stop><stop offset="0.92" stop-color="#edc64f"></stop><stop offset="1" stop-color="#edc64f"></stop></linearGradient><linearGradient id="63fb0eb1-e930-4084-8c62-b5b435aecd0c" x1="-251.71" y1="352.3" x2="-253.09" y2="353.52" gradientTransform="matrix(8.72, 0, 0, -5.31, 2208.99, 1882.17)" xlink:href="#7f161091-907f-4bc3-958f-efd13f35ea8f"></linearGradient><linearGradient id="9dca70f9-c700-4386-bb6d-2ac754ed7336" x1="-263.32" y1="408.59" x2="-264.68" y2="410.09" gradientTransform="matrix(13.39, 0, 0, -20, 3550.34, 8207.19)" xlink:href="#7f161091-907f-4bc3-958f-efd13f35ea8f"></linearGradient></defs><title>golden_star</title><path d="M20.15,4.3l-3.76,5L13.18,5l6.26-1.4C20.35,3.3,20.65,3.65,20.15,4.3Z" transform="translate(0 0)" fill="url(#7f161091-907f-4bc3-958f-efd13f35ea8f)"></path><path d="M8.72,6,.46,8.06c-.5.1-.65.7-.15.9l8.07,2.35Z" transform="translate(0 0)" fill="url(#63fb0eb1-e930-4084-8c62-b5b435aecd0c)"></path><path d="M7.67,19.23,8.73.49c0-.55.55-.65.9-.25l11.22,14c.4.5.3,1.35-.85.9l-6.76-2.6L9,19.53C8.57,20.33,7.62,20,7.67,19.23Z" transform="translate(0 0)" fill="url(#9dca70f9-c700-4386-bb6d-2ac754ed7336)"></path></svg>';
                    }
                // Matching Contribution Level to Contribution Award (Bronze, Silver, Gold, Platinum) 
                var adjust_award = String(results[i].author.expertPlatinumCount)+
                                    String(results[i].author.expertGoldCount)+
                                    String(results[i].author.expertSilverCount)+
                                    String(results[i].author.expertBronzeCount);
                // Number that Will be Displayed
                var adjust_count;
                if(adjust_award >= 1000){
                    adjust_count = results[i].author.expertPlatinumCount;
                }else if(adjust_award >= 100){
                    adjust_count = results[i].author.expertGoldCount;
                }else if(adjust_award >= 10){
                    adjust_count = results[i].author.expertSilverCount;
                }else if(adjust_award >= 1){
                    adjust_count = results[i].author.expertBronzeCount;
                }else{
                    adjust_count = results[i].author.contributionLevel;
                }
                // Time Since Posted Functionality -> start
                var conv_date = "";
                conv_date = results[i].createdDateTime;
                var how_old = new Date(conv_date);
                function timeSince(how_old) {
                    var seconds = Math.floor((new Date() - how_old) / 1000);
                    var interval = Math.floor(seconds / 31536000);
                    if (interval > 1) {
                        return interval + " years ago";
                    }
                    interval = Math.floor(seconds / 31536000);
                    if (interval >= 1) {
                        return interval + " year ago";
                    }
                    interval = Math.floor(seconds / 2592000);
                    if (interval > 1) {
                        return interval + " months ago";
                    }
                    interval = Math.floor(seconds / 2592000);
                    if (interval >= 1) {
                        return interval + " month ago";
                    }
                    interval = Math.floor(seconds / 604800);
                    if (interval > 1) {
                        return interval + " weeks ago";
                    }
                    interval = Math.floor(seconds / 604800);
                    if (interval >= 1) {
                        return interval + " weeks ago";
                    }
                    interval = Math.floor(seconds / 86400);
                    if (interval > 1) {
                        return interval + " days ago";
                    }
                    interval = Math.floor(seconds / 86400);
                    if (interval >= 1) {
                        return interval + " days ago";
                    }
                    interval = Math.floor(seconds / 3600);
                    if (interval > 1) {
                        return interval + " hours ago";
                    }
                     interval = Math.floor(seconds / 3600);
                    if (interval >= 1) {
                        return interval + " hour ago";
                    }
                    interval = Math.floor(seconds / 60);
                    if (interval > 1) {
                        return interval + " minutes ago";
                    }
                    interval = Math.floor(seconds / 60);
                    if (interval >= 1) {
                        return interval + " minute ago";
                    }
                    return Math.floor(seconds) + " seconds ago";
                }
                // Time Since Posted Functionality -> end

                j = i + 1;
                    if(j == 1){
                        html += '<div class="d-flex justify-content-between my-5">';
                    }
                    html += '<div class="review_card d-flex flex-column justify-content-between col-lg-3 col-md-5 col-sm-5 col-xs-6 px-0 border">'+
                                '<div>'+
                                    '<div class="review_card_cover bg-primary position-relative d-flex justify-content-center align-items-center">'+
                                        '<a class="w-100" href="'+
                                            en4.core.baseUrl+"reviews/view/"+results[i].reviewID+
                                        '">'+
                                            '<img class="w-100" src="'+
                                                results[i].coverPhoto.photoURL+
                                            '"/>'+
                                        '</a>'+
                                        '<div class="review_star_ranking position-absolute '+ star_background +' d-flex justify-content-around px-2 py-1">'+
                                            star_rating+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="review_card_info p-2">'+
                                        '<a class="review_card_topic text-primary font-weight-bold" href="'+
                                            en4.core.baseUrl+"reviews/view/"+results[i].reviewID+
                                        '">'+
                                            results[i].reviewCategorization.category+
                                        '</a>'+
                                        '<h2 class="review_card_title font-weight-bold">'+
                                            '<a href="'+
                                                en4.core.baseUrl+"reviews/view/"+results[i].reviewID+
                                            '">'+results[i].title+'</a>'+
                                        '</h2>'+
                                        '<span class="d-block d-md-none">'+
                                            timeSince(how_old)+
                                        '</span>'+
                                        '<p class="review_card_description d-none d-md-block">'+
                                            results[i].shortDescription+
                                        '</p>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="review_card_author position-relative d-flex align-items-center border-top p-2">'+
                                    '<div class="avatar_popup position-absolute bg-white d-none">'+
                                        '<div class="avatar_header d-flex mx-3 mt-3 px-2 pt-2">'+
                                            '<img class="rounded-circle" src="'+
                                                results[i].author.avatarPhoto.photoURLIcon+
                                            '" alt="avatar photo"/>'+
                                            '<div class="avatar_info d-flex flex-column">'+
                                                '<a class="font-weight-bold" href="'+
                                                    en4.core.baseUrl+"profile/"+results[i].author.memberName+
                                                '">'+ 
                                                    results[i].author.displayName+
                                                '</a>'+
                                                '<div class="d-flex justify-content-start align-items-center">'+
                                                    '<svg style="margin: 3px 5px 0px 0px;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 0 42.03 39.91"><defs><linearGradient id="a" x1="26.26" y1="12.68" x2="40.67" y2="12.68" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#51b2b6"></stop><stop offset="1" stop-color="#5bc6cd"></stop></linearGradient><linearGradient id="b" y1="17.32" x2="17.39" y2="17.32" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#5bc6cd"></stop><stop offset="1" stop-color="#51b2b6"></stop></linearGradient></defs><title>star_pg</title><path d="M40.23,8.55,32.7,18.46l-6.44-8.6L38.77,7C40.61,6.57,41.14,7.31,40.23,8.55Z" fill="#5bc6cd"></path><path d="M17.39,12,.93,16.13c-1,.24-1.28,1.35-.32,1.79l16.06,4.7Z" fill="#5bc6cd"></path><path d="M15.31,38.4,17.42,1c0-1.06,1.1-1.31,1.76-.45L41.59,28.45c.83,1,.6,2.71-1.71,1.81L26.36,25.09l-8.44,14A1.36,1.36,0,0,1,15.31,38.4Z" fill="#5bc6cd"></path></svg>'+
                                                    results[i].author.contribution+
                                                '</div>'+
                                            '</div>'+
                                            '<span class="avatar_close">'+
                                                '<svg width="14px" aria-hidden="true" data-prefix="fal" data-icon="times" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="svg-inline--fa fa-times fa-w-10 fa-2x"><path fill="currentColor" d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z" class=""></path></svg>'+
                                            '</span>'+
                                        '</div>'+
                                        '<div class="avatar_badges d-flex justify-content-around align-items-center my-2 px-3">'+
                                            '<div class="avatar_badges_popup badge_bronze position-relative d-flex flex-column justify-content-center align-items-center">'+
                                                '<img src="<?php echo $this->baseUrl(); ?>/application/themes/guidanceguide/assets/images/badges/Bronze.svg"/>'+
                                                '<span class="number_badges position-absolute text-white font-weight-bold">'+
                                                    results[i].author.bronzeCount+
                                                '</span>'+
                                                '<span class="badge_name">Bronze</span>'+
                                            '</div>'+
                                            '<div class="avatar_badges_popup badge_silver position-relative d-flex flex-column justify-content-center align-items-center">'+
                                                '<img src="<?php echo $this->baseUrl(); ?>/application/themes/guidanceguide/assets/images/badges/Silver.svg"/>'+
                                                '<span class="number_badges position-absolute text-white font-weight-bold">'+
                                                    results[i].author.silverCount+
                                                '</span>'+
                                                '<span class="badge_name">Silver</span>'+
                                            '</div>'+
                                            '<div class="avatar_badges_popup badge_gold position-relative d-flex flex-column justify-content-center align-items-center">'+
                                                '<img src="<?php echo $this->baseUrl(); ?>/application/themes/guidanceguide/assets/images/badges/Gold.svg"/>'+
                                                '<span class="number_badges position-absolute text-white font-weight-bold">'+
                                                    results[i].author.goldCount+
                                                '</span>'+
                                                '<span class="badge_name">Gold</span>'+
                                            '</div>'+
                                            '<div class="avatar_badges_popup badge_platinum position-relative d-flex flex-column justify-content-center align-items-center">'+
                                                '<img src="<?php echo $this->baseUrl(); ?>/application/themes/guidanceguide/assets/images/badges/Platinum.svg"/>'+
                                                '<span class="number_badges position-absolute text-white font-weight-bold">'+
                                                    results[i].author.platinumCount+
                                                '</span>'+
                                                '<span class="badge_name">Platinum</span>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="avatar_footer d-flex justify-content-between align-items-center border-top px-1">'+
                                            '<div class="d-flex flex-column-reverse align-items-center justify-content-center py-2 px-0">'+
                                                'Reviews '+
                                                '<span class="text-primary font-weight-bold ml-1">'+
                                                results[i].author.reviewCount+
                                                '</span>'+
                                            '</div>'+
                                            '<div class="d-flex flex-column-reverse align-items-center justify-content-center py-2 px-0">'+
                                                'Guides '+
                                                '<span class="text-primary font-weight-bold ml-1">'+
                                                results[i].author.guideCount+
                                                '</span>'+
                                            '</div>'+
                                            '<div class="d-flex flex-column-reverse align-items-center justify-content-center py-2 px-0">'+
                                                'Struggles '+
                                                '<span class="text-primary font-weight-bold ml-1">'+
                                                results[i].author.questionCount+
                                                '</span>'+
                                            '</div>'+
                                            '<div class="d-flex flex-column-reverse align-items-center justify-content-center px-0 py-2">'+
                                                'Theories '+ 
                                                '<span class="text-primary font-weight-bold ml-1">'+
                                                results[i].author.answerCount+
                                                '</span>'+
                                            '</div>'+ 
                                        '</div>'+
                                    '</div>'+
                                    '<img class="avatar_halo rounded-circle" src="'+
                                        results[i].author.avatarPhoto.photoURLIcon+
                                    '" data-halo="'+ results[i].author.mvp +'"/>'+
                                    '<span class="cont_level position-absolute rounded-circle" data-cont="'+
                                        results[i].author.expertPlatinumCount+
                                        results[i].author.expertGoldCount+
                                        results[i].author.expertSilverCount+
                                        results[i].author.expertBronzeCount+
                                    '">'+
                                        adjust_count+
                                    '</span>'+
                                    '<div class="d-none d-md-flex flex-column justify-content-start ml-2">'+
                                        '<h4 class="font-weight-bold p-0 m-0 border-0"><a href="'+
                                                en4.core.baseUrl+"profile/"+results[i].author.memberName+
                                            '">'+results[i].author.displayName+'</a>'+
                                        '</h4>'+
                                        '<span>'+
                                            timeSince(how_old)+
                                        '</span>'+
                                    '</div>'+
                                    '<div class="d-flex flex-column flex-lg-row justify-content-start align-items-center ml-auto">'+
                                        '<span class="mr-0 mr-lg-1 d-flex align-items-center">'+
                                        '<svg aria-hidden="true" data-prefix="fas" width="13px" style="margin-right:3px;" data-icon="arrow-circle-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#B9CFD1" d="M8 256C8 119 119 8 256 8s248 111 248 248-111 248-248 248S8 393 8 256zm143.6 28.9l72.4-75.5V392c0 13.3 10.7 24 24 24h16c13.3 0 24-10.7 24-24V209.4l72.4 75.5c9.3 9.7 24.8 9.9 34.3.4l10.9-11c9.4-9.4 9.4-24.6 0-33.9L273 107.7c-9.4-9.4-24.6-9.4-33.9 0L106.3 240.4c-9.4 9.4-9.4 24.6 0 33.9l10.9 11c9.6 9.5 25.1 9.3 34.4-.4z"></path></svg>'+
                                        results[i].likesCount+
                                        '</span>'+
                                        '<span class="ml-0 ml-lg-1 d-flex align-items-center">'+
                                        '<svg aria-hidden="true" width="13px" style="margin-right:3px;" data-prefix="fas" data-icon="comments" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="svg-inline--fa fa-comments fa-w-18 fa-9x"><path fill="#B9CFD1" d="M224 358.857c-37.599 0-73.027-6.763-104.143-18.7-31.375 24.549-69.869 39.508-110.764 43.796a8.632 8.632 0 0 1-.89.047c-3.736 0-7.111-2.498-8.017-6.061-.98-3.961 2.088-6.399 5.126-9.305 15.017-14.439 33.222-25.79 40.342-74.297C17.015 266.886 0 232.622 0 195.429 0 105.16 100.297 32 224 32s224 73.159 224 163.429c-.001 90.332-100.297 163.428-224 163.428zm347.067 107.174c-13.944-13.127-30.849-23.446-37.46-67.543 68.808-64.568 52.171-156.935-37.674-207.065.031 1.334.066 2.667.066 4.006 0 122.493-129.583 216.394-284.252 211.222 38.121 30.961 93.989 50.492 156.252 50.492 34.914 0 67.811-6.148 96.704-17 29.134 22.317 64.878 35.916 102.853 39.814 3.786.395 7.363-1.973 8.27-5.467.911-3.601-1.938-5.817-4.759-8.459z" class=""></path></svg>'+
                                        results[i].commentsCount+
                                        '</span>'+
                                    '</div>'+
                                '</div>'+
                            '</div>';
                    // Checking Screen Size and Display Structure
                    if(checkWidth > 992){
                        if(j % 3 == 0){
                            html += '</div>'+
                                    '<div class="d-flex justify-content-between my-5">';
                        }
                    }else{
                       if(j % 2 == 0){
                            html += '</div>'+
                                    '<div class="d-flex justify-content-between my-5">';
                        } 
                    }
                }
                html += '</div>';
                reviewsContent.innerHTML = html;
                // Currently Categories are being Displayed instead of Topics, change the Call accordingly
                // If the Load Time of the page is too slow, lower resolution of pics can be chosen
                
                // Avatar Styling
                // Check the Data Attribute for Mvp Status
                // If Item has Mvp Status Put Halo Around Avatar Change Contribution Level Color
                document.querySelectorAll('.avatar_halo').forEach(function(avatar_halo){
                    if(avatar_halo.dataset.halo == "true"){
                        avatar_halo.addClass('avatar_halo_disp');
                        avatar_halo.style.borderImage = "url('<?php echo $this->baseUrl(); ?>/application/themes/guidanceguide/assets/images/border.png') 20 20 20 20 fill";
                    }
                });
                // Checking Contribution Level on Avatar
                document.querySelectorAll('.cont_level').forEach(function(avatar_cont){
                    if(avatar_cont.dataset.cont >= 1000){
                        avatar_cont.addClass('cont_level_platinum');
                    }else if(avatar_cont.dataset.cont >= 100){
                        avatar_cont.addClass('cont_level_gold');
                    }else if(avatar_cont.dataset.cont >= 10){
                        avatar_cont.addClass('cont_level_silver');
                    }else if(avatar_cont.dataset.cont >= 1){
                        avatar_cont.addClass('cont_level_bronze');
                    }else{
                        avatar_cont.addClass('cont_level_default');
                    }
                });
                // Displaying Avatar Popup
                document.querySelectorAll('.avatar_halo').forEach(function(popup_func){
                    popup_func.addEventListener('click', function(){
                        this.previousSibling.addClass('d-block').removeClass('d-none');
                    });
                });
                window.addEventListener('mouseup', function(){
                    document.querySelectorAll('.avatar_popup').forEach(function(removed){
                        if(removed.hasClass('d-block')){
                            removed.addClass('d-none').removeClass('d-block');
                        }
                    });
                }); 
                // Review Star Ranking Displaying
                
                // Displaying Comments and Likes on "..." click
                document.querySelectorAll('.review_card_dots').forEach(function(dots){
                    dots.addEventListener('click', function(){
                        this.nextSibling.removeClass('d-none').addClass('d-flex');
                        this.removeClass('d-inline').addClass('d-none');
                    });
                });
                window.addEventListener('mouseup', function(){
                    document.querySelectorAll('.review_card_dots_popup').forEach(function(removed){
                        if(removed.hasClass('d-flex')){
                            removed.addClass('d-none').removeClass('d-flex');
                            removed.previousSibling.addClass('d-inline').removeClass('d-none');
                        }
                    });
                });          
            }else{
                console.log(responseJSON);
                reviewsContent.innerHTML = responseJSON.message;
            }
        }
    });
    request.send();
}    
</script>