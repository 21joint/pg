<?php
    $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Ggcommunity/externals/scripts/swiper.min.js');
    $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl
        . 'application/modules/Ggcommunity/externals/styles/swiper.min.css');
?>



<div class="container extfox-widgets">
    <div class="row my-4">

        <div class="col-sm-4 col-12 left-side">
            
            <div class="contribution bg-white mb-4 widget">
                <div class="holder p-4">
                    <div class="top border-bottom border-gray pb-3">
                        <h5 class="text-primary font-weight-bold">
                            <?php echo $this->subject->gg_contribution; ?> 
                        </h5>
                        <p class="desc text-dark">
                            <?php echo $this->translate('Contribution points'); ?>
                        </p>
                    </div>

                    <div class="bottom row pt-4">
                        <div class="col-2  text-center">
                            <h5 class="text-primary font-weight-bold"> 
                                <?php echo $this->subject->gg_review_count;?>
                            </h5>
                            <p class="desc text-dark">
                                <?php echo $this->translate('Reviews'); ?>
                            </p>
                        </div>
                        <div class="col-2 text-center">
                            <h5 class="text-primary font-weight-bold"> 
                                <?php echo $this->subject->gg_guide_count;?>
                            </h5>
                            <p class="desc text-dark">
                                <?php echo $this->translate('Guides'); ?>
                            </p>
                        </div>
                        <div class="col-2 text-center">
                            <h5 class="text-primary font-weight-bold"> 
                                <?php echo $this->subject->gg_question_count; ?>
                            </h5>
                            <p class="desc text-dark">
                                <?php echo $this->translate('Struggles'); ?>
                            </p>
                        </div>
                        <div class="col-2 text-center">
                            <h5 class="text-primary font-weight-bold"> 
                                <?php echo $this->subject->gg_answer_count;?>
                            </h5>
                            <p class="desc text-dark">
                                <?php echo $this->translate('Theories'); ?>
                            </p>
                        </div>
                    </div>

                </div>
            </div> <!-- end of contribution -->

            <div class="badges-earned bg-white mb-4 widget">
                <div class="holder p-4">

                    <div class="d-flex justify-content-between border-bottom pb-3">
                        <div class="holder text-left">
                            <p class="desc title text-dark">
                                <?php echo $this->translate('Badges Earned'); ?>
                            </p>
                        </div>
                        <div class="holder text-right">
                            <a href="javascript:void(0)"  class="badges-ern text-primary">
                                <?php echo $this->translate('view all'); ?>
                            </a>
                        </div>
                    </div>

                    <div class="bottom row pt-4">
                        <div class="col-2 text-center">
                            <div class="col-sm px-0 bronze text-center">
                                <div class="badge-holder d-flex align-items-center justify-content-center font-weight-bold text-white">
                                    <?php echo $this->subject->gg_bronze_count; ?>
                                </div>
                            
                            </div>
                            <span class="text-muted small text-center w-100">Bronze</span>
                        </div>
                        <div class="col-2 text-center">
                            <div class="col-sm px-0 silver text-center">
                                <div class="badge-holder d-flex align-items-center justify-content-center font-weight-bold text-white">
                                    <?php echo $this->subject->gg_silver_count; ?>
                                </div>
                            </div>
                            <span class="text-muted small  w-100">Silver</span>
                        </div>
                        <div class="col-2 text-center">
                            <div class="col-sm px-0 gold ">
                                <div class="badge-holder d-flex align-items-center justify-content-center font-weight-bold text-white">
                                    <?php echo $this->subject->gg_gold_count; ?>
                                </div>
                            </div>
                            <span class="text-muted small text-center w-100">Gold</span>
                        </div>
                        <div class="col-2 text-center">
                            <div class="col-sm px-0 platinium">
                                <div class="badge-holder d-flex align-items-center justify-content-center font-weight-bold text-white">
                                    <?php echo $this->subject->gg_platinum_count; ?>
                                </div>
                            </div>
                            <span class="text-muted small text-center w-100">Platinium</span>
                        </div>
                    </div>
                </div>
            </div> <!-- end of badges-earned -->
            <?php if($this->specialBadges->getTotalItemCount() >= 1):?>
            <div class="badges-slide mb-4 bg-white widget">
                <div class="holder p-4">

                    <div class="d-flex justify-content-between border-bottom pb-3">
                        <div class="holder text-left">
                            <p class="desc title text-dark">
                                <?php echo $this->translate('Badges Earned'); ?>
                            </p>
                        </div>
                    </div>

                    <div class="contributor slider pt-4">

                        <!-- Swiper -->
                        <div class="swiper-container">

                            <div class="swiper-wrapper">
                                <?php foreach($this->specialBadges as $special):?>
                                <div class="swiper-slide mb-0">
                                    <a href="javascript:void(0)">
                                        <?php echo $this->itemPhoto($special, 'thumb.normal'); ?>
                                    </a>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <!-- Add Arrows -->
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <!-- Add Pagination -->
                            <div class="swiper-pagination"></div>
                            
                        </div>

                    </div>
                </div>
            </div> <!-- end of badges slide -->
            <?php endif;?>
          
        </div> <!-- left side -->

        <div class="col-sm-7 col-12 right-side">
                
            <div class="latest-reviews bg-white mb-4 widget">
                <div class="holder p-4">
                    
                    <div class="d-flex justify-content-between border-bottom pb-3">
                        <div class="holder text-left">
                            <p class="desc title text-dark">
                                <?php echo $this->translate('Latest Reviews'); ?>
                            </p>
                        </div>
                        <div class="holder text-right">
                            <a href="javascript:void(0)" class="text-primary"> <?php echo $this->translate('view all'); ?> </a>
                        </div>
                    </div>

                    <!-- <div id="sd-response" class="container d-flex justify-content-center"> -->
                        <!-- Loader goes here -->
                    <!-- </div> -->
                    <div class="reviews_component_content mt-3 p-0">
                        <!-- Content of ajax call goes here -->
                    </div>

                </div>
            </div> <!-- end of latest-review -->

            <div class="latest-guides bg-white mb-4 widget">
                <div class="holder p-4">
                    
                    <div class="d-flex justify-content-between border-bottom pb-3">
                        <div class="holder text-left">
                            <p class="desc title text-dark">
                                <?php echo $this->translate('Latest Guides'); ?>
                            </p>
                        </div>
                        <div class="holder text-right">
                            <a href="javascript:void(0)" class="text-primary"> <?php echo $this->translate('view all'); ?> </a>
                        </div>
                    </div>

                    <div class="bottom small text-muted pt-4">
                        <?php echo $this->translate('“Coming Soon”'); ?>
                    </div>

                </div>
            </div> <!-- end of latest-guides -->

            <div class="latest-struggles bg-white widget">
                <div class="holder p-4">
                    
                    <div class="d-flex justify-content-between border-bottom pb-3">
                        <div class="holder text-left">
                            <p class="desc title text-dark">
                                <?php echo $this->translate('Latest Struggles'); ?>
                            </p>
                        </div>
                        <div class="holder text-right">
                            <a class="text-primary" href="javascript:void(0)" onclick="showStruggle()">
                                <?php echo $this->translate('view all'); ?>
                            </a>
                        </div>
                    </div>

                    <div class="struggles_content">

                    </div>

                </div>
            </div> <!-- end of latest-strugles -->
            
        </div> <!-- right side -->

    </div>
</div>

<!-- Initialize Swiper -->
<script>
    var swiper = new Swiper('.swiper-container', {
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      pagination: {
        el: '.swiper-pagination',
      },
      loop: false,
    });

    en4.core.runonce.add(function () {
        let buttonFriend = document.getElementById('user-friendship-cover');
        if(!buttonFriend) return;
        let buttonFriendLink = buttonFriend.getElement('a');
        buttonFriendLink.removeClass('buttonlink');
        buttonFriendLink.addClass('btn btn-success px-sm-5 px-3 py-2 text-white');

        <?php if($this->profileSettings):?>
            var tabs = document.getElementsByClassName('tabs_alt'); 
            let liElements = tabs[1].getChildren('ul').getChildren()[0];
            for(var i = 0; i < liElements.length; i++) {
                let getParam = '<?php echo $this->profileSettings; ?>';
                let textContent = liElements[i].getChildren()[0].textContent;
            
                if( ((textContent == 'Personal Info') && ( getParam === 'general' )) || ((textContent == 'User Preferences') && ( getParam === 'preference' )) ){  
                    var tabToOpen = liElements[i].getChildren()[0];
                }
            }
            setTimeout(function () {
                tabToOpen.click();
                showEditContent();
            }, 300);
        <?php endif; ?>
    });

    $$('.badges-ern').addEvent('click',function(event){
        $$('.tabs_alt ').each(function(elements){
            elements.getElements('ul > li').each(function(el){
                if(el.classList[1] === 'tab_layout_sdparentalguide_ajax_badges'){
                    el.getChildren()[0].click(); 
                }
            });
        });
    });

    function showStruggle() {
        $$('.tabs_alt ').each(function(elements){
            elements.getElements('ul > li').each(function(el){
                if(el.classList[1] === 'tab_layout_sdparentalguide_ajax_struggles'){
                    el.getChildren()[0].click(); 
                }
            });
        });
    }

    // Components Ajax Call
    en4.core.runonce.add(function () {
        loadReviewResults();
        loadQuestionResults();
    });

    // LATEST REVIEWS FUNCTION
    // Checking how many Review Cards to Display
    var checkWidth = window.innerWidth;
    function loadReviewResults(){
        // Request data can be linked to form inputs
        var requestData = {};
        requestData.limit = 2;
        requestData.page = 1;

        // For Particular User Profile
        <?php if(Engine_Api::_()->core()->hasSubject("user")): ?>
           requestData.authorID = "<?php echo $this->subject()->getIdentity(); ?>";
        <?php endif; ?>
        
        var loader = en4.core.loader.clone();
        loader.addClass("sd_loader mt-5");
        var url = en4.core.baseUrl+"api/v1/review";
        
        var request = new Request.JSON({
            url: url,
            method: 'get',
            data: requestData,
            onRequest: function(){ /* loader.inject($("sd-response")); */ }, // When request is sent.
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

                    // var j = 0;
                    html += '<div class="d-flex justify-content-between mt-4 mb-2">';
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

                    // j = i + 1;
                    //     if(j == 1){
                            // html += '<div class="d-flex justify-content-between my-5">';
                    //     }
                        html += '<div class="review_card d-flex flex-column justify-content-between col-sm-6 col-md-5 px-0 border">'+
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
                                            '<div class="avatar_footer d-flex justify-content-center align-items-center border-top px-1">'+
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
                        // if(checkWidth > 992){
                        //     if(j % 3 == 0){
                        //         html += '</div>'+
                        //                 '<div class="d-flex justify-content-between my-5">';
                        //     }
                        // }else{
                        // if(j % 2 == 0){
                        //         html += '</div>'+
                        //                 '<div class="d-flex justify-content-between my-5">';
                        //     } 
                        // }
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
                    reviewsContent.innerHTML = responseJSON.message;
                }
            }
        });
        request.send();
    }


    // LATEST STRUGGLES FUNCTION
    function loadQuestionResults(){
        // Request data can be linked to form inputs
        var requestData = {};
        requestData.limit = 5;// Display limit for users
        requestData.page = 1;// Place for pagination

        // For user profile
        <?php if(Engine_Api::_()->core()->hasSubject("user")): ?>
           requestData.authorID = "<?php echo $this->subject()->getIdentity(); ?>";
        <?php endif; ?>

        var loader = en4.core.loader.clone();
        loader.addClass("sd_loader my-5");
        var url = en4.core.baseUrl+"api/v1/question";

        var request = new Request.JSON({
            url: url,
            method: 'get',
            data: requestData,
            onRequest: function(){ /*loader.inject($("sd-response"));*/ }, // When request is sent.
            onError: function(){ loader.destroy(); }, // When request throws an error.
            onCancel: function(){ loader.destroy(); }, // When request is cancelled.
            onSuccess: function(responseJSON){ // When request is succeeded.
                loader.destroy();

                var strugglesContent = document.querySelector('.struggles_content');

                if(responseJSON.status_code == 200){
                    var html = "";
                    var results = responseJSON.body.Results;
                    for(var i = 0; i < results.length ; i++) {
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

                        html += '<div class="struggle_holder my-3 d-flex flex-wrap border-bottom pb-3">'+
                                    '<div class="struggle_box_left d-flex align-items-center large-9 medium-12 small-12">'+
                                        '<div class="struggle_left_side d-inline-block">'+
                                            '<div class="item-photo-guidance position-relative">'+
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
                                                    '<div class="avatar_footer d-flex justify-content-center align-items-center border-top px-1">'+
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
                                            '</div>'+
                                        '</div>'+
                                        '<div class="struggle_right-side d-inline-block">'+
                                            '<a href="'+
                                                en4.core.baseUrl+"struggles/question/"+results[i].questionID+
                                            '" class="struggle_title">'+
                                                results[i].title+
                                            '</a>'+
                                            '<ul class="struggle_info d-flex">'+
                                                '<li class="struggle_time_created">'+
                                                timeSince(how_old)+
                                                '</li>'+
                                                '<li class="list-inline edit-list-item">'+
                                                    '<a href="'+
                                                        en4.core.baseUrl+"ggcommunity/edit/"+results[i].questionID+
                                                    '" class="edit-item option-item display-flex">'+
                                                        '<svg aria-hidden="true" data-prefix="fas" data-icon="edit" role="img" width="12px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="svg-inline--fa fa-edit fa-w-18 fa-9x"><path fill="#5CC7CE" d="M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zm162-22.9l-48.8-48.8c-15.2-15.2-39.9-15.2-55.2 0l-35.4 35.4c-3.8 3.8-3.8 10 0 13.8l90.2 90.2c3.8 3.8 10 3.8 13.8 0l35.4-35.4c15.2-15.3 15.2-40 0-55.2zM384 346.2V448H64V128h229.8c3.2 0 6.2-1.3 8.5-3.5l40-40c7.6-7.6 2.2-20.5-8.5-20.5H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V306.2c0-10.7-12.9-16-20.5-8.5l-40 40c-2.2 2.3-3.5 5.3-3.5 8.5z" class=""></path></svg>'+
                                                        'Edit'+
                                                    '</a>'+
                                                '</li>'+
                                                '<li class="list-inline delete-list-item">'+
                                                    '<a href="'+
                                                        en4.core.baseUrl+"ggcommunity/delete/"+results[i].questionID+
                                                    '" class="delete-item smoothbox option-item display-flex">'+
                                                        '<svg aria-hidden="true" data-prefix="fal" data-icon="times-circle" role="img" width="12px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#5CC7CE" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 464c-118.7 0-216-96.1-216-216 0-118.7 96.1-216 216-216 118.7 0 216 96.1 216 216 0 118.7-96.1 216-216 216zm94.8-285.3L281.5 256l69.3 69.3c4.7 4.7 4.7 12.3 0 17l-8.5 8.5c-4.7 4.7-12.3 4.7-17 0L256 281.5l-69.3 69.3c-4.7 4.7-12.3 4.7-17 0l-8.5-8.5c-4.7-4.7-4.7-12.3 0-17l69.3-69.3-69.3-69.3c-4.7-4.7-4.7-12.3 0-17l8.5-8.5c4.7-4.7 12.3-4.7 17 0l69.3 69.3 69.3-69.3c4.7-4.7 12.3-4.7 17 0l8.5 8.5c4.6 4.7 4.6 12.3 0 17z"></path></svg>'+
                                                        'Delete'+
                                                    '</a>'+
                                                '</li>'+
                                            '</ul>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="struggle_box_right large-3 medium-6 small-6">'+
                                        '<ul class="struggle_count_info d-flex justify-content-end primary">'+
                                            '<li class="count_info d-flex flex-column-reverse align-items-center justify-content-center px-1" id="vote_count">'+
                                                '<svg class="d-lg-none" aria-hidden="true" data-prefix="fas" width="12px" style="margin-right:3px;" data-icon="arrow-circle-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#17becb" d="M8 256C8 119 119 8 256 8s248 111 248 248-111 248-248 248S8 393 8 256zm143.6 28.9l72.4-75.5V392c0 13.3 10.7 24 24 24h16c13.3 0 24-10.7 24-24V209.4l72.4 75.5c9.3 9.7 24.8 9.9 34.3.4l10.9-11c9.4-9.4 9.4-24.6 0-33.9L273 107.7c-9.4-9.4-24.6-9.4-33.9 0L106.3 240.4c-9.4 9.4-9.4 24.6 0 33.9l10.9 11c9.6 9.5 25.1 9.3 34.4-.4z"></path></svg>'+
                                                '<p class="count_title title_active d-none d-lg-inline">votes</p>'+
                                                '<span>'+results[i].upVoteCount+'</span>'+
                                            '</li>'+
                                            '<li class="count_info d-flex flex-column-reverse align-items-center justify-content-center px-1 answered_true" id="answer_count"'+
                                            'data-answered="'+ results[i].answerChosen +'">'+
                                                '<span class="answer_star"></span>'+
                                                '<p class="count_title title_active d-none d-lg-inline">answers</p>'+
                                                '<span>'+results[i].answerCount+'</span>'+
                                            '</li>'+
                                            '<li class="count_info d-flex flex-column-reverse align-items-center justify-content-center px-1" id="comment_count">'+
                                                '<svg class="d-lg-none" aria-hidden="true" width="12px" style="margin-right:3px;" data-prefix="fas" data-icon="comments" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="#17becb" d="M224 358.857c-37.599 0-73.027-6.763-104.143-18.7-31.375 24.549-69.869 39.508-110.764 43.796a8.632 8.632 0 0 1-.89.047c-3.736 0-7.111-2.498-8.017-6.061-.98-3.961 2.088-6.399 5.126-9.305 15.017-14.439 33.222-25.79 40.342-74.297C17.015 266.886 0 232.622 0 195.429 0 105.16 100.297 32 224 32s224 73.159 224 163.429c-.001 90.332-100.297 163.428-224 163.428zm347.067 107.174c-13.944-13.127-30.849-23.446-37.46-67.543 68.808-64.568 52.171-156.935-37.674-207.065.031 1.334.066 2.667.066 4.006 0 122.493-129.583 216.394-284.252 211.222 38.121 30.961 93.989 50.492 156.252 50.492 34.914 0 67.811-6.148 96.704-17 29.134 22.317 64.878 35.916 102.853 39.814 3.786.395 7.363-1.973 8.27-5.467.911-3.601-1.938-5.817-4.759-8.459z" class=""></path></svg>'+
                                                '<p class="count_title title_active d-none d-lg-inline">comments</p>'+
                                                '<span>'+results[i].commentsCount+'</span>'+
                                            '</li>'+
                                        '</ul>'+
                                    '</div>'+
                                '</div>';
                    }
                    strugglesContent.innerHTML = html;
                    // Testing...Delete when Finished
                    console.log(responseJSON);
                    // Structure of the Component
                    // Only works in real situation, doesn't work when shrinking window manually
                    var checkSize = window.innerWidth;
                     if(checkSize < 992){
                        document.querySelectorAll('.struggle_count_info').forEach(function(align_struggle){
                            align_struggle.addClass('justify-content-around').removeClass('justify-content-end');
                        });
                        document.querySelectorAll('.count_info').forEach(function(transform_item){
                            transform_item.removeClass('flex-column-reverse');
                        });
                    }
                    // Adding Styles to Answered Struggles
                    document.querySelectorAll('.answered_true').forEach(function(answered_true){
                        if(answered_true.dataset.answered == "true"){
                            console.log(answered_true);
                            answered_true.lastChild.addClass('answered_question');
                            answered_true.querySelector('.answer_star').innerHTML = '<svg class="d-lg-none" xmlns="http://www.w3.org/2000/svg" style="margin-right:3px;" width="13px" height="13px" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 40 38"><defs><linearGradient id="z" x1="-173.22" y1="1009.42" x2="-172.4" y2="1010.06" gradientTransform="matrix(13.72, 0, 0, -11.03, 2403.25, 11146.77)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#5BC7CE"></stop><stop offset="1" stop-color="#5BC7CE"></stop></linearGradient><linearGradient id="x" x1="-304.1" y1="1050.64" x2="-303.1" y2="1050.64" gradientTransform="matrix(16.54, 0, 0, -10.11, 5029.61, 10635.32)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#5BC7CE"></stop><stop offset="1" stop-color="#5BC7CE"></stop></linearGradient></defs><title>star_pg</title><path d="M38.29,8.11l-7.17,9.44L25,9.36,36.9,6.64C38.65,6.23,39.15,6.93,38.29,8.11Z" fill="url(#z)"></path><path d="M16.54,11.4.87,15.33c-1,.23-1.22,1.29-.3,1.7L15.86,21.5Z" fill="url(#x)"></path><path d="M14.56,36.53l2-35.61c0-1,1-1.25,1.68-.43L39.58,27.06c.79,1,.57,2.58-1.63,1.72L25.08,23.86,17,37.19a1.3,1.3,0,0,1-2.49-.66Z" fill="#5BC7CE"></path></svg>';
                        }else{
                           answered_true.querySelector('.answer_star').innerHTML = '<svg class="d-lg-none" viewBox="0 0 42 40" style="margin-right:3px;" width="13px" height="13px" preserveAspectRatio="xMidYMid meet" x="0" y="0" xmlns="http://www.w3.org/2000/svg"><path d="M32.52 18.024l8.06 10.036c.79 1 .57 2.58-1.63 1.72l-12.87-4.92L18 38.19a1.3 1.3 0 0 1-2.49-.66h.05l.852-15.161L1.57 18.03c-.92-.41-.7-1.47.3-1.7l15.093-3.785.597-10.625c0-1 1-1.25 1.68-.43l7.068 8.8L37.9 7.64c1.75-.41 2.25.29 1.39 1.47l-6.77 8.914z" fill-rule="nonzero" stroke="#5BC7CE" stroke-width="4" fill="none"></path></svg>'; 
                        }
                    });
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
                }else{
                    strugglesContent.innerHTML = responseJSON.message;
                }
            }
        });
        request.send();
    }

</script>