<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

?>


<div id="leaderboard">
    <div class="leaderboard_title d-flex justify-content-between align-items-center">
        <h3>Leaderboard</h3>
        <ul class="d-flex align">
            <li class="leaderboard_nav">Overall</li>
            <li class="leaderboard_nav">Week</li>
            <li class="leaderboard_nav">Month</li>
        </ul>
    </div>
    <div class="leaderboard_main d-flex justify-content-between">
        <div class="d-flex justify-content-center">Rank</div>
        <div class="d-flex">Leader</div>
        <!-- Categories Start -->
        <div id="points" class="order_by d-flex justify-content-center align-items-center" data-order="contributionPoints">Points</div>
        <div id="reviews" class="order_by d-none d-md-flex justify-content-center align-items-center" data-order="reviewCount">Reviews</div>
        <div class="d-none d-md-flex justify-content-center align-items-center">Answers<!-- Sort By Answer not supported yet by the service layer --></div>
        <div id="questions" class="order_by d-none d-md-flex justify-content-center align-items-center" data-order="questionCount">Questions</div>
        <div id="followers" class="order_by d-none d-md-flex justify-content-center align-items-center" data-order="followers">Followers</div>
        <!-- Categories End -->
        <div class="d-flex d-md-none justify-content-center align-items-center">
            <button id="order_btn" class="btn-primary">></button>
        </div>
        <!-- Toggle Button End -->
    </div>
    <div id="sd-response"class="container d-flex justify-content-center">
        <!-- Loader goes here -->
    </div>
    <div class="leaderboard_content">
        <!-- Content of ajax call goes here -->
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
        categoryValue = document.getElementById('followers').getAttribute('data-order');
        currentCategory = 1;
        loadLeaderboardResults(timeFrame, categoryValue, currentCategory); 
    }else if(currentCategory == 1){
        categoryValue = document.getElementById('questions').getAttribute('data-order');
        currentCategory = 2;
        loadLeaderboardResults(timeFrame, categoryValue, currentCategory);
    }else if(currentCategory == 2){
        categoryValue = document.getElementById('reviews').getAttribute('data-order');
        currentCategory = 3;
        loadLeaderboardResults(timeFrame, categoryValue, currentCategory);
    }else{
        categoryValue = document.getElementById('points').getAttribute('data-order');
        currentCategory = 0;
        loadLeaderboardResults(timeFrame, categoryValue, currentCategory);
    }
});
// Toggle Categories on Mobile End


// For each Range Nav item on click ajax call is added 
// With an argument (Overall, Week, Month) corresponding to Name of the Nav item
var timeFrame;
document.querySelectorAll('.leaderboard_nav').forEach(function(nav) {
    nav.addEventListener('click', function() {
        timeFrame = this.innerText;
        // Using null and leaving out the previous sorting results
        // Better to always start from contributionPoints on any timeFrame
        loadLeaderboardResults(timeFrame, categoryValue, currentCategory);       
    });
});

// Orders by each category which is clicked on using ajax
// Currently available arguments (contributionPoints, questionCount, reviewCount, followers)
var checkSize = window.innerWidth;
if(checkSize > 768){
    var orderBy;
    document.querySelectorAll('.order_by').forEach(function(order) {
        order.addEventListener('click', function() {
            orderBy = this.getAttribute('data-order');
            loadLeaderboardResults(timeFrame, orderBy, currentCategory);   
        });
    });
}


function loadLeaderboardResults(tm = "Overall", ord = "contributionPoints", disp = 0) {
    //Request data can be linked to form inputs
    var requestData = {};
    requestData.contributionRangeType = tm; //Possible values "Overall", "Week", "Month"
    requestData.orderBy = ord; //Possible values "contributionPoints", "questionCount", "reviewCount", "followers"
    requestData.limit = 10;
    requestData.page = 1;

    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader mt-5");
    var url = en4.core.baseUrl+"api/v1/ranking";

    var request = new Request.JSON({
        url: url,
        method: 'get',
        data: requestData,
        onRequest: function(){ loader.inject($("sd-response")); }, //When request is sent.
        onError: function(){ loader.destroy(); }, //When request throws an error.
        onCancel: function(){ loader.destroy(); }, //When request is cancelled.
        onSuccess: function(responseJSON){ //When request is succeeded.
            loader.destroy(); 

            var leaderboardContent = document.querySelector('.leaderboard_content');

            if(responseJSON.status_code == 200){

                var html = "";
                var results = responseJSON.body.Results;
                for(var i = 0; i < results.length; i++) {
                    html += `<div class="leaderboard_item d-flex justify-content-between">
                                <div class="d-flex justify-content-center align-items-center">
                                    ${i+1}
                                </div>
                                <div class="d-flex align-items-center leader">
                                    <img src="${results[i].avatarPhoto.photoURL}"/>
                                    <h4>${results[i].displayName}</h4>
                                </div>
                                <div class="points d-flex align-items-center justify-content-center">
                                    <svg style="margin: 3px 5px 0px 0px;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px" viewBox="0 0 42.03 39.91"><defs><linearGradient id="a" x1="26.26" y1="12.68" x2="40.67" y2="12.68" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#51b2b6"></stop><stop offset="1" stop-color="#5bc6cd"></stop></linearGradient><linearGradient id="b" y1="17.32" x2="17.39" y2="17.32" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#5bc6cd"></stop><stop offset="1" stop-color="#51b2b6"></stop></linearGradient></defs><title>star_pg</title><path d="M40.23,8.55,32.7,18.46l-6.44-8.6L38.77,7C40.61,6.57,41.14,7.31,40.23,8.55Z" fill="url(#a)"></path><path d="M17.39,12,.93,16.13c-1,.24-1.28,1.35-.32,1.79l16.06,4.7Z" fill="url(#b)"></path><path d="M15.31,38.4,17.42,1c0-1.06,1.1-1.31,1.76-.45L41.59,28.45c.83,1,.6,2.71-1.71,1.81L26.36,25.09l-8.44,14A1.36,1.36,0,0,1,15.31,38.4Z" fill="#5bc6cd"></path></svg>
                                    ${results[i].contribution}
                                </div>
                                <div class="reviews d-none d-md-flex align-items-center justify-content-center">
                                    ${results[i].reviewCount}
                                </div>
                                <div class="d-none d-md-flex align-items-center justify-content-center">
                                    ${results[i].answerCount}
                                </div>
                                <div class="questions d-none d-md-flex align-items-center justify-content-center">
                                    ${results[i].questionCount}
                                </div>
                                <div class="followers d-none d-md-flex align-items-center justify-content-center">
                                    ${results[i].followersCount}
                                </div>
                            </div>`;
                }
                leaderboardContent.innerHTML = html;
                // Adding and Removing Categories in which the items are ordered
                if(disp == 0){
                    document.getElementById('reviews').addClass('d-none').removeClass('d-flex');
                    document.querySelectorAll('.reviews').forEach(function(reviews){
                        reviews.addClass('d-none').removeClass('d-flex');
                    });
                    document.getElementById('points').addClass('d-flex').removeClass('d-none');
                    document.querySelectorAll('.points').forEach(function(points){
                        points.addClass('d-flex').removeClass('d-none');
                    }); 
                }else if(disp == 1){
                    document.getElementById('points').addClass('d-none').removeClass('d-flex');
                    document.querySelectorAll('.points').forEach(function(points){
                        points.addClass('d-none').removeClass('d-flex');
                    });
                    document.getElementById('followers').addClass('d-flex').removeClass('d-none');
                    document.querySelectorAll('.followers').forEach(function(followers){
                        followers.addClass('d-flex').removeClass('d-none');
                    }); 
                }else if(disp == 2){
                    document.getElementById('followers').addClass('d-none').removeClass('d-flex');
                    document.querySelectorAll('.followers').forEach(function(followers){
                        followers.addClass('d-none').removeClass('d-flex');
                    });
                    document.getElementById('points').addClass('d-none').removeClass('d-flex');
                    document.querySelectorAll('.points').forEach(function(points){
                        points.addClass('d-none').removeClass('d-flex');
                    });
                    document.getElementById('questions').addClass('d-flex').removeClass('d-none');
                    document.querySelectorAll('.questions').forEach(function(questions){
                        questions.addClass('d-flex').removeClass('d-none');
                    }); 
                }else if(disp == 3){
                    document.getElementById('questions').addClass('d-none').removeClass('d-flex');
                    document.querySelectorAll('.questions').forEach(function(questions){
                        followers.addClass('d-none').removeClass('d-flex');
                    });
                    document.getElementById('points').addClass('d-none').removeClass('d-flex');
                    document.querySelectorAll('.points').forEach(function(points){
                        points.addClass('d-none').removeClass('d-flex');
                    });
                    document.getElementById('reviews').addClass('d-flex').removeClass('d-none');
                    document.querySelectorAll('.reviews').forEach(function(reviews){
                        reviews.addClass('d-flex').removeClass('d-none');
                    });
                }
                console.log(responseJSON);
            }else{
                leaderboardContent.innerHTML = responseJSON.message;
            }
        }
    });
    request.send();
}    
</script>