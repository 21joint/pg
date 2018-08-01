<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

?>


<div class="leaderboard">
    <div class="leaderboard_title d-flex justify-content-between align-items-center">
        <h3><?php echo $this->translate('Leaderboard'); ?></h3>
        <ul class="d-flex align">
            <li id="leaderboard_nav_ovl" class="leaderboard_nav leaderboard_title_active">Overall</li>
            <li id="leaderboard_nav_mth" class="leaderboard_nav">Month</li>
            <li id="leaderboard_nav_wek" class="leaderboard_nav">Week</li>
            <li id="leaderboard_nav_day" class="leaderboard_nav">Day</li>
        </ul><!-- Add back in when the service is provided -->
    </div>
    <div class="leaderboard_main d-flex justify-content-between">
        <div class="d-flex justify-content-center"><?php echo $this->translate('Rank'); ?></div>
        <div class="d-flex"><?php echo $this->translate('Leader'); ?></div>
        <!-- Categories Start -->
        <div id="points" class="order_by d-flex justify-content-center align-items-center" data-order="contributionPoints"><?php echo $this->translate('Contribution'); ?></div>
        <div id="reviews" class="order_by d-none d-md-flex justify-content-center align-items-center" data-order="reviewCount"><?php echo $this->translate('Reviews'); ?></div>
        <!-- <div class="d-none d-md-flex justify-content-center align-items-center">Answers --><!-- Sort By Answer not supported yet by the service layer --><!-- </div> -->
        <div id="questions" class="order_by d-none d-md-flex justify-content-center align-items-center" data-order="questionCount"><?php echo $this->translate('Struggles'); ?></div>
        <!-- <div id="followers" class="order_by d-none d-md-flex justify-content-center align-items-center" data-order="followers"><?php echo $this->translate('Followers'); ?></div> -->
        <!-- Categories End -->
        <div class="d-flex d-md-none justify-content-center align-items-center">
            <button id="order_btn" class="btn-primary rounded-circle">></button>
        </div>
        <!-- Toggle Button End -->
    </div>
    <div id="sd-response" class="container d-flex justify-content-center">
        <!-- Loader goes here -->
    </div>
    <div class="leaderboard_content">
        <!-- Content of ajax call goes here -->
    </div>
    <div class="leaderboard_pagination d-flex justify-content-end align-items-center mt-5 mr-5">
        <!-- Content Pagination -->
        <span id="leaderboard_previous" class="pagination_button"><</span>
        <span id="leaderboard_pageNum" class="mx-3">
            <!-- Displays the current page of Leaderboard Results -->
        </span>
        <span id="leaderboard_next" class="pagination_button">></span>
    </div>
</div>


<script type='text/javascript'>
//Dom ready
en4.core.runonce.add(function(){
    loadLeaderboardResults();
});

// Toggle Categories on Mobile Start
var currentCategory = 0;
var categoryValue;
document.getElementById('order_btn').addEventListener('click', function(){
    if(currentCategory == 0){
        categoryValue = document.getElementById('questions').getAttribute('data-order');
        currentCategory = 1;
        pageNum = 1;
        loadLeaderboardResults(timeFrame, categoryValue, currentCategory, pageNum); 
    }else if(currentCategory == 1){
        categoryValue = document.getElementById('reviews').getAttribute('data-order');
        currentCategory = 2;
        pageNum = 1;
        loadLeaderboardResults(timeFrame, categoryValue, currentCategory, pageNum);
    }
    // else if(currentCategory == 2){
    //     categoryValue = document.getElementById('followers').getAttribute('data-order');
    //     currentCategory = 3;
    //     pageNum = 1;
    //     loadLeaderboardResults(timeFrame, categoryValue, currentCategory, pageNum);
    // }
    else{
        categoryValue = document.getElementById('points').getAttribute('data-order');
        currentCategory = 0;
        pageNum = 1;
        loadLeaderboardResults(timeFrame, categoryValue, currentCategory, pageNum);
    }
});
// Toggle Categories on Mobile End


// For each Range Nav item on click ajax call is added 
// With an argument (Overall, Week, Month) corresponding to Name of the Nav item
var timeFrame;
document.querySelectorAll('.leaderboard_nav').forEach(function(nav) {
    nav.addEventListener('click', function() {
        timeFrame = this.innerText;
        pageNum = 1;
        // Using null and leaving out the previous sorting results
        // Better to always start from contributionPoints on any timeFrame
        if(checkSize > 768){
            loadLeaderboardResults(timeFrame, orderBy, currentCategory, pageNum);
        }
        else{
            loadLeaderboardResults(timeFrame, categoryValue, currentCategory, pageNum);
        }      
    });
});
// Choosen TimeFrame Styling (needs refactor, hard coded solution)
document.getElementById('leaderboard_nav_ovl').addEventListener('click', function(){
    this.addClass('leaderboard_title_active');
    document.getElementById('leaderboard_nav_mth').removeClass('leaderboard_title_active');
    document.getElementById('leaderboard_nav_wek').removeClass('leaderboard_title_active');
    document.getElementById('leaderboard_nav_day').removeClass('leaderboard_title_active');
});
document.getElementById('leaderboard_nav_mth').addEventListener('click', function(){
    this.addClass('leaderboard_title_active');
    document.getElementById('leaderboard_nav_ovl').removeClass('leaderboard_title_active');
    document.getElementById('leaderboard_nav_wek').removeClass('leaderboard_title_active');
    document.getElementById('leaderboard_nav_day').removeClass('leaderboard_title_active');
});
document.getElementById('leaderboard_nav_wek').addEventListener('click', function(){
    this.addClass('leaderboard_title_active');
    document.getElementById('leaderboard_nav_mth').removeClass('leaderboard_title_active');
    document.getElementById('leaderboard_nav_ovl').removeClass('leaderboard_title_active');
    document.getElementById('leaderboard_nav_day').removeClass('leaderboard_title_active');
});
document.getElementById('leaderboard_nav_day').addEventListener('click', function(){
    this.addClass('leaderboard_title_active');
    document.getElementById('leaderboard_nav_mth').removeClass('leaderboard_title_active');
    document.getElementById('leaderboard_nav_ovl').removeClass('leaderboard_title_active');
    document.getElementById('leaderboard_nav_wek').removeClass('leaderboard_title_active');
});

// Orders by each category which is clicked on using ajax
// Currently available arguments (contributionPoints, questionCount, reviewCount, followers)
var checkSize = window.innerWidth;
if(checkSize > 768){
    var orderBy;
    document.querySelectorAll('.order_by').forEach(function(order) {
            order.addEventListener('click', function() {
            orderBy = this.getAttribute('data-order');
            pageNum = 1;
            loadLeaderboardResults(timeFrame, orderBy, currentCategory, pageNum);   
        });
    });
}
// Styling for Order By Category Start
var activeCategory = 0;
// Starts with being ordered by Contribution Points
document.getElementById('points').addEventListener('click', function(){
    activeCategory = 0;
});
// Being ordered by Questions
document.getElementById('questions').addEventListener('click', function(){
    activeCategory = 1;
});
// Being ordered by Reviews
document.getElementById('reviews').addEventListener('click', function(){
    activeCategory = 2;
});
// Being ordered by Followers
// document.getElementById('followers').addEventListener('click', function(){
//     activeCategory = 3;
// });
// Styling for Order By Category End

// Pagionation Number Change Start
var pageNum = 1;
document.getElementById('leaderboard_previous').addEventListener('click', function(){
    if(pageNum >= 2){
        pageNum--;
        document.getElementById('leaderboard_previous').removeClass('pagination_button_diss');
        document.getElementById('leaderboard_next').removeClass('pagination_button_diss');
        if(checkSize > 768){
            loadLeaderboardResults(timeFrame, orderBy, currentCategory, pageNum);
        }else{
            loadLeaderboardResults(timeFrame, categoryValue, currentCategory, pageNum);
        } 
    }else{
        document.getElementById('leaderboard_previous').addClass('pagination_button_diss');
    }
});
document.getElementById('leaderboard_next').addEventListener('click', function(){
    if(pageNum <= 9){
        pageNum++;
        document.getElementById('leaderboard_next').removeClass('pagination_button_diss');
        document.getElementById('leaderboard_previous').removeClass('pagination_button_diss');
        if(checkSize > 768){
            loadLeaderboardResults(timeFrame, orderBy, currentCategory, pageNum);
        }else{
            loadLeaderboardResults(timeFrame, categoryValue, currentCategory, pageNum);
        }
    }else{
        document.getElementById('leaderboard_next').addClass('pagination_button_diss');
    }
});
// Pagination Number Change End

function loadLeaderboardResults(tm, ord, disp = 0, page = 1) {
    // Request data can be linked to form inputs
    var requestData = {};
    requestData.contributionRangeType = tm; // Possible values "Overall", "Week", "Month"
    requestData.orderBy = ord; // Possible values "contributionPoints", "questionCount", "reviewCount", "followers"
    requestData.limit = 20; // Display limit for users
    requestData.page = page; // Place for pagination

    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader my-5");
    var url = en4.core.baseUrl+"api/v1/ranking";

    // Testing... Delete after done
    console.log(tm, ord, disp, page);

    var request = new Request.JSON({
        url: url,
        method: 'get',
        data: requestData,
        onRequest: function(){ loader.inject($("sd-response")); }, //When request is sent.
        onError: function(){ loader.destroy(); }, // When request throws an error.
        onCancel: function(){ loader.destroy(); }, // When request is cancelled.
        onSuccess: function(responseJSON){ // When request is succeeded.
            loader.destroy(); 

            var leaderboardContent = document.querySelector('.leaderboard_content');

            if(responseJSON.status_code == 200){

                var html = "";
                var results = responseJSON.body.Results;

                // Testing... Delete after done
                console.log(results);

                for(var i = 0; i < results.length; i++) {
                    // Matching Contribution Level to Contribution Award (Bronze, Silver, Gold, Platinum) 
                    var adjust_award = String(results[i].expertPlatinumCount)+
                                        String(results[i].expertGoldCount)+
                                        String(results[i].expertSilverCount)+
                                        String(results[i].expertBronzeCount);
                    // Number that Will be Displayed
                    var adjust_count;
                    if(adjust_award >= 1000){
                        adjust_count = results[i].expertPlatinumCount;
                    }else if(adjust_award >= 100){
                        adjust_count = results[i].expertGoldCount;
                    }else if(adjust_award >= 10){
                        adjust_count = results[i].expertSilverCount;
                    }else if(adjust_award >= 1){
                        adjust_count = results[i].expertBronzeCount;
                    }else{
                        adjust_count = results[i].contributionLevel;
                    }

                    html += '<div class="leaderboard_item d-flex justify-content-between">'+
                                '<div class="d-flex justify-content-center align-items-center">'+
                                    ((page-1)*20+(i+1))+
                                '</div>'+
                                '<div class="d-flex align-items-center leader position-relative">'+
                                    '<div class="avatar_popup position-absolute bg-white d-none">'+
                                        '<div class="avatar_header d-flex mx-3 mt-3 px-2 pt-2">'+
                                            '<img src="'+
                                                results[i].avatarPhoto.photoURLIcon+
                                            '" alt="avatar photo"/>'+
                                            '<div class="avatar_info d-flex flex-column">'+
                                                '<a class="font-weight-bold" href="'+
                                                    en4.core.baseUrl+"profile/"+results[i].memberName+
                                                '">'+ 
                                                    results[i].displayName+
                                                '</a>'+
                                                '<div class="d-flex justify-content-start align-items-center">'+
                                                    '<svg style="margin: 3px 5px 0px 0px;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 0 42.03 39.91"><defs><linearGradient id="a" x1="26.26" y1="12.68" x2="40.67" y2="12.68" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#51b2b6"></stop><stop offset="1" stop-color="#5bc6cd"></stop></linearGradient><linearGradient id="b" y1="17.32" x2="17.39" y2="17.32" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#5bc6cd"></stop><stop offset="1" stop-color="#51b2b6"></stop></linearGradient></defs><title>star_pg</title><path d="M40.23,8.55,32.7,18.46l-6.44-8.6L38.77,7C40.61,6.57,41.14,7.31,40.23,8.55Z" fill="#5bc6cd"></path><path d="M17.39,12,.93,16.13c-1,.24-1.28,1.35-.32,1.79l16.06,4.7Z" fill="#5bc6cd"></path><path d="M15.31,38.4,17.42,1c0-1.06,1.1-1.31,1.76-.45L41.59,28.45c.83,1,.6,2.71-1.71,1.81L26.36,25.09l-8.44,14A1.36,1.36,0,0,1,15.31,38.4Z" fill="#5bc6cd"></path></svg>'+
                                                    results[i].contribution+
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
                                                    results[i].bronzeCount+
                                                '</span>'+
                                                '<span class="badge_name">Bronze</span>'+
                                            '</div>'+
                                            '<div class="avatar_badges_popup badge_silver position-relative d-flex flex-column justify-content-center align-items-center">'+
                                                '<img src="<?php echo $this->baseUrl(); ?>/application/themes/guidanceguide/assets/images/badges/Silver.svg"/>'+
                                                '<span class="number_badges position-absolute text-white font-weight-bold">'+
                                                    results[i].silverCount+
                                                '</span>'+
                                                '<span class="badge_name">Silver</span>'+
                                            '</div>'+
                                            '<div class="avatar_badges_popup badge_gold position-relative d-flex flex-column justify-content-center align-items-center">'+
                                                '<img src="<?php echo $this->baseUrl(); ?>/application/themes/guidanceguide/assets/images/badges/Gold.svg"/>'+
                                                '<span class="number_badges position-absolute text-white font-weight-bold">'+
                                                    results[i].goldCount+
                                                '</span>'+
                                                '<span class="badge_name">Gold</span>'+
                                            '</div>'+
                                            '<div class="avatar_badges_popup badge_platinum position-relative d-flex flex-column justify-content-center align-items-center">'+
                                                '<img src="<?php echo $this->baseUrl(); ?>/application/themes/guidanceguide/assets/images/badges/Platinum.svg"/>'+
                                                '<span class="number_badges position-absolute text-white font-weight-bold">'+
                                                    results[i].platinumCount+
                                                '</span>'+
                                                '<span class="badge_name">Platinum</span>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="avatar_footer d-flex justify-content-center align-items-center border-top">'+
                                            '<div class="d-flex justify-content-center p-3 border-right">'+
                                                'Reviews '+
                                                '<span class="text-primary font-weight-bold ml-1">'+
                                                results[i].reviewCount+
                                                '</span>'+
                                            '</div>'+
                                            '<div class="d-flex justify-content-center p-3">'+
                                                'Answers '+ 
                                                '<span class="text-primary font-weight-bold ml-1">'+
                                                results[i].answerCount+
                                                '</span>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                    '<img class="avatar_halo" src="'+
                                        results[i].avatarPhoto.photoURLIcon+
                                    '" data-halo="'+ results[i].mvp +'"/>'+
                                    '<span class="cont_level position-absolute rounded-circle" data-cont="'+
                                        results[i].expertPlatinumCount+
                                        results[i].expertGoldCount+
                                        results[i].expertSilverCount+
                                        results[i].expertBronzeCount+
                                    '">'+
                                        adjust_count+
                                    '</span>'+
                                    '<h4 class="font-weight-bold"><a href="'+
                                            en4.core.baseUrl+"profile/"+results[i].memberName+
                                        '">'+results[i].displayName+'</a>'+
                                    '</h4>'+
                                '</div>'+
                                '<div class="points d-flex align-items-center justify-content-center">'+
                                    '<svg style="margin: 3px 5px 0px 0px;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px" viewBox="0 0 42.03 39.91"><defs><linearGradient id="a" x1="26.26" y1="12.68" x2="40.67" y2="12.68" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#51b2b6"></stop><stop offset="1" stop-color="#5bc6cd"></stop></linearGradient><linearGradient id="b" y1="17.32" x2="17.39" y2="17.32" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#5bc6cd"></stop><stop offset="1" stop-color="#51b2b6"></stop></linearGradient></defs><title>star_pg</title><path d="M40.23,8.55,32.7,18.46l-6.44-8.6L38.77,7C40.61,6.57,41.14,7.31,40.23,8.55Z" fill="#5bc6cd"></path><path d="M17.39,12,.93,16.13c-1,.24-1.28,1.35-.32,1.79l16.06,4.7Z" fill="#5bc6cd"></path><path d="M15.31,38.4,17.42,1c0-1.06,1.1-1.31,1.76-.45L41.59,28.45c.83,1,.6,2.71-1.71,1.81L26.36,25.09l-8.44,14A1.36,1.36,0,0,1,15.31,38.4Z" fill="#5bc6cd"></path></svg>'+
                                    results[i].contribution+
                                '</div>'+
                                '<div class="reviews d-none d-md-flex align-items-center justify-content-center">'+
                                    results[i].reviewCount+
                                '</div>'+
                                '<!-- <div class="d-none d-md-flex align-items-center justify-content-center">'+
                                    results[i].answerCount+
                                '</div> -->'+
                                '<div class="questions d-none d-md-flex align-items-center justify-content-center">'+
                                    results[i].questionCount+
                                '</div>'+
                                '<!-- <div class="followers d-none d-md-flex align-items-center justify-content-center">'+
                                    results[i].followersCount+
                                '</div> -->'+
                            '</div>';
                }
                leaderboardContent.innerHTML = html;
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
                window.addEventListener('mousedown', function(){
                    document.querySelectorAll('.avatar_popup').forEach(function(removed){
                        if(removed.hasClass('d-block')){
                            removed.addClass('d-none').removeClass('d-block');
                        }
                    });
                });
                
                // Showing current pages in pagination section
                var lastpage = 10;
                // First 4 Pages You can Skip to
                if(pageNum <= 3){
                    document.getElementById('leaderboard_pageNum').innerHTML = '<span class="skip_page mx-2">1</span>' +
                                                                            '<span class="mx-2 d-none">...</span>'+
                                                                            '<span class="skip_page mx-2">2</span>'+
                                                                            '<span class="skip_page mx-2">3</span>'+
                                                                            '<span class="skip_page mx-2">4</span>'+
                                                                            '<span class="mx-2">...</span>'+
                                                                            '<span class="skip_page mx-2">'+ lastpage +'</span>';
                }else if(pageNum > 3 && pageNum < 8){
                    document.getElementById('leaderboard_pageNum').innerHTML = '<span class="skip_page mx-2">'+(pageNum-3)+'</span>'+
                                                                            '<span class="skip_page mx-2">'+
                                                                                (pageNum-2)+
                                                                            '</span>'+
                                                                            '<span class="skip_page mx-2">'+
                                                                                (pageNum-1)+
                                                                            '</span>'+
                                                                            '<span class="skip_page mx-2">'+
                                                                                pageNum+
                                                                            '</span>'+
                                                                            '<span class="mx-2">...</span>'+
                                                                            '<span class="skip_page mx-2">'+ lastpage +'</span>';
                }else{
                    document.getElementById('leaderboard_pageNum').innerHTML = '<span class="skip_page mx-2">1</span>'+
                                                                            '<span class="mx-2">...</span>'+
                                                                            '<span class="skip_page mx-2">'+
                                                                                (lastpage-3)+
                                                                            '</span>'+
                                                                            '<span class="skip_page mx-2">'+
                                                                                (lastpage-2)+
                                                                            '</span>'+
                                                                            '<span class="skip_page mx-2">'+
                                                                                (lastpage-1)+
                                                                            '</span>'+
                                                                            '<span class="skip_page mx-2">'+ lastpage +'</span>';
                }
                // Skipping Pages Functionality
                document.querySelectorAll('.skip_page').forEach(function(page){
                    if(page.innerText == pageNum){
                        page.addClass("current_pagination");
                    }
                    page.addEventListener('click', function(){
                        pageNum = Number(this.innerText);
                        loadLeaderboardResults(timeFrame, orderBy, currentCategory, pageNum);
                    });
                });
                // Adding and Removing Categories in which the items are ordered
                // Displaying Categories on Mobile
                switch(disp){
                // Displaying Contribution Points
                    case 0:
                        document.getElementById('reviews').addClass('d-none').removeClass('d-flex');
                        document.querySelectorAll('.reviews').forEach(function(reviews){
                            reviews.addClass('d-none').removeClass('d-flex');
                        });
                        document.getElementById('points').addClass('d-flex').removeClass('d-none');
                        document.querySelectorAll('.points').forEach(function(points){
                            points.addClass('d-flex').removeClass('d-none');
                        }); 
                        break;
                // Displaying Number of Questions
                    case 1:
                        document.getElementById('points').addClass('d-none').removeClass('d-flex');
                        document.querySelectorAll('.points').forEach(function(points){
                            points.addClass('d-none').removeClass('d-flex');
                        });
                        document.getElementById('questions').addClass('d-flex').removeClass('d-none');
                        document.querySelectorAll('.questions').forEach(function(followers){
                            followers.addClass('d-flex').removeClass('d-none');
                        });
                        break;
                // Displaying Number of Reviews
                    case 2:
                        document.getElementById('questions').addClass('d-none').removeClass('d-flex');
                        document.querySelectorAll('.questions').forEach(function(followers){
                            followers.addClass('d-none').removeClass('d-flex');
                        });
                        document.getElementById('points').addClass('d-none').removeClass('d-flex');
                        document.querySelectorAll('.points').forEach(function(points){
                            points.addClass('d-none').removeClass('d-flex');
                        });
                        document.getElementById('reviews').addClass('d-flex').removeClass('d-none');
                        document.querySelectorAll('.reviews').forEach(function(questions){
                            questions.addClass('d-flex').removeClass('d-none');
                        });
                        break;
                // Displaying Number of Reviews
                    // case 3:
                    //     document.getElementById('questions').addClass('d-none').removeClass('d-flex');
                    //     document.querySelectorAll('.questions').forEach(function(questions){
                    //         followers.addClass('d-none').removeClass('d-flex');
                    //     });
                    //     document.getElementById('points').addClass('d-none').removeClass('d-flex');
                    //     document.querySelectorAll('.points').forEach(function(points){
                    //         points.addClass('d-none').removeClass('d-flex');
                    //     });
                    //     document.getElementById('reviews').addClass('d-flex').removeClass('d-none');
                    //     document.querySelectorAll('.reviews').forEach(function(reviews){
                    //         reviews.addClass('d-flex').removeClass('d-none');
                    //     });
                    //     break;
                }
                // Displays Highlighted Order By Main Category
                switch(activeCategory){
                    // Highlight Contribution
                    case 0:
                        document.getElementById('points').addClass('active_category');
                        // document.getElementById('followers').removeClass('active_category');
                        document.getElementById('questions').removeClass('active_category');
                        document.getElementById('reviews').removeClass('active_category');
                        break;
                    // Highlight Questions
                    case 1:
                        document.getElementById('points').removeClass('active_category');
                        // document.getElementById('followers').addClass('active_category');
                        document.getElementById('questions').addClass('active_category');
                        document.getElementById('reviews').removeClass('active_category');
                        break;
                    // Highlight Reviews
                    case 2:
                        document.getElementById('points').removeClass('active_category');
                        // document.getElementById('followers').removeClass('active_category');
                        document.getElementById('questions').removeClass('active_category');
                        document.getElementById('reviews').addClass('active_category');
                        break;
                    // Highlight Followers
                    // case 3:
                    //     document.getElementById('points').removeClass('active_category');
                    //     document.getElementById('followers').removeClass('active_category');
                    //     document.getElementById('questions').removeClass('active_category');
                    //     document.getElementById('reviews').addClass('active_category');
                    //     break;
                }
            }else{
                leaderboardContent.innerHTML = responseJSON.message;
            }
        }
    });
    request.send();
}    
</script>