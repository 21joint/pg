<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

?>

<div class="leaderboard d-flex flex-column justify-content-center align-items-center">
    <h1 class="mt-5 text-uppercase">Our Community</h1>
    <h5>You can trust our community of real parents</h5>
    <a href="#" id="readmore" class="btn-large btn-success text-white text-capitalize font-weight-bold mt-5 mb-4 px-5 py-3">See More</a>
</div>
<div class="leaderboard">
    <div class="leaderboard_main d-flex justify-content-between">
        <div class="d-flex justify-content-center">Rank</div>
        <div class="d-flex">Leader</div>
        <div id="points" class="order_by contribution_home d-flex justify-content-center align-items-center" data-order="contributionPoints">Contribution</div>
    </div>
    <div id="sd-response" class="container d-flex justify-content-center align-items-center">
        <!-- Loader goes here -->
    </div>
    <div class="leaderboard_content">
        <!-- Content of ajax call goes here -->
    </div>
    <div class="leaderboard_pagination d-flex justify-content-end align-items-center mt-5 mr-5">
        <!-- Content Pagination -->
        <span id="leaderboard_previous" class="pagination_button"><</span>
        <span id="leaderboard_pageNum" class="mx-5">
            <!-- Displays the current page of Leaderboard Results -->
        </span>
        <span id="leaderboard_next" class="pagination_button">></span>
    </div>
</div>
<div class="find_expert_main container-fluid py-5 mt-5">
    <div class="row d-flex align-items-center">
        <div class="find_expert_badges col-lg position-relative d-flex justify-content-around align-items-center">
            <img class="front_image" src="<?php echo $this->baseUrl(); ?>/application/modules/Sdparentalguide/externals/images/mvp_badge.png"/>
            <img class="back_image" src="<?php echo $this->baseUrl(); ?>/application/modules/Sdparentalguide/externals/images/badge_baby.png"/>
            <img class="back_image" src="<?php echo $this->baseUrl(); ?>/application/modules/Sdparentalguide/externals/images/badge_baby.png"/>
        </div>
        <div class="find_expert_text col-lg">
            <h2>Find an expert. Become an expert.</h2>
            <h3 class="text-capitalize">How to become a contributor to parental guidance</h3>
            <p>Showcase your experience and expertise, while collecting fancy badges and earning credibility points! Email us at <a href="#" class="text-primary">xxxxxxxxxxx</a> to apply!</p>
            <h3 class="text-capitalize">How to earn credibility</h3>
            <p>Earn credibility by engaging in various activites on the site: Creating Reviews, Writing Stories, Commenting on threads. The more you engage, the faster you earn.</p>
            <h3 class="text-capitalize">How to earn badges</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.</p>
            <h3 class="text-capitalize">Want to become an MVP?</h3>
            <p>Our MVP program is based on overall excellence! We choose our top notch, engaged and community driven parents to join our MVP program on quarterly basis. You do NOT have to have certain number of badges.</p>
        </div>
    </div>
</div>
<div class="credibility_info_main my-5">
    <div class="row">
        <div class="credibility_info_text col-lg">
            <h2 class="text-uppercase">Credibility info</h2>
            <p class="mt-5">Do not delete the activities which you have performed to earn credibility. If you delete those activities, you will loose the credit earned.</p>
            <p class="mt-5">Credibility is NOT the same as Contribution Badges. You will earn badges for delivering quality content in a specific area. Credibility is what helps you to earn recognition across the site. For example, you maybe a Gold Member Contributor for Car Seats, but only attained a level 2 Credibility because you are not an active user of the site.</p>
            <p class="mt-5">As you engage across the site, your credibility will go up. You will earn "Credibility Level" badges once you cross a certain credibility ranking. This way everyone can see who is the most active and engaged member on the site!</p>
        </div>
        <div class="col-lg"><!-- Keeping up space --></div>
    </div>
</div>
<div class="faq_main my-5 py-5 d-flex flex-column align-items-center justify-content-center ">
    <h2 class="w-100 text-center">FAQ's</h2>
    <div class="faq_content w-100">
        <div class="faq_item mt-4 pb-4">
            <div class="faq_title d-flex justify-content-between align-items-center">
                <h4>Can anybody post reviews, earn badges and gain credibility score?</h4>
                <span class="d-block faq_toggle">+</span>   
            </div>
            <p class="faq_text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum pretium scelerisque lectus. Nulla tincidunt nulla sed est maximus dapibus. Nulla sit amet nibh sed ex scelerisque ultricies. Curabitur venenatis moleint nisi id viverra.</p>
        </div>
        <div class="faq_item mt-4 pb-4">
            <div class="faq_title d-flex justify-content-between align-items-center">
                <h4>What are the official hashtags for Guidance Guide and Parental Guidance?</h4>
                <span class="d-block faq_toggle">+</span>   
            </div>
            <p class="faq_text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum pretium scelerisque lectus. Nulla tincidunt nulla sed est maximus dapibus. Nulla sit amet nibh sed ex scelerisque ultricies. Curabitur venenatis moleint nisi id viverra.</p>
        </div>
        <div class="faq_item mt-4 pb-4">
            <div class="faq_title d-flex justify-content-between align-items-center">
                <h4>What if I don't have enough product to write a review and try to earn a badge?</h4>
                <span class="d-block faq_toggle">+</span>   
            </div>
            <p class="faq_text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum pretium scelerisque lectus. Nulla tincidunt nulla sed est maximus dapibus. Nulla sit amet nibh sed ex scelerisque ultricies. Curabitur venenatis moleint nisi id viverra.</p>
        </div>
        <div class="faq_item mt-4 pb-4">
            <div class="faq_title d-flex justify-content-between align-items-center">
                <h4>Can Contributors contact brands?</h4>
                <span class="d-block faq_toggle">+</span>   
            </div>
            <p class="faq_text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum pretium scelerisque lectus. Nulla tincidunt nulla sed est maximus dapibus. Nulla sit amet nibh sed ex scelerisque ultricies. Curabitur venenatis moleint nisi id viverra.</p>
        </div>
        <div class="faq_item mt-4 pb-4">
            <div class="faq_title d-flex justify-content-between align-items-center">
                <h4>I want to review a product, but it technically fits into multiple categories. Where should I put it?</h4>
                <span class="d-block faq_toggle">+</span>   
            </div>
            <p class="faq_text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum pretium scelerisque lectus. Nulla tincidunt nulla sed est maximus dapibus. Nulla sit amet nibh sed ex scelerisque ultricies. Curabitur venenatis moleint nisi id viverra.</p>
        </div>
        <div class="faq_item mt-4 pb-4">
            <div class="faq_title d-flex justify-content-between align-items-center">
                <h4>I am getting a ton of notifications that I don't want. Where do I find the notification settings?</h4>
                <span class="d-block faq_toggle">+</span>   
            </div>
            <p class="faq_text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum pretium scelerisque lectus. Nulla tincidunt nulla sed est maximus dapibus. Nulla sit amet nibh sed ex scelerisque ultricies. Curabitur venenatis moleint nisi id viverra.</p>
        </div>
        <div class="faq_item mt-4 pb-4">
            <div class="faq_title d-flex justify-content-between align-items-center">
                <h4>What browser is preffered for the best website functionality?</h4>
                <span class="d-block faq_toggle">+</span>   
            </div>
            <p class="faq_text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum pretium scelerisque lectus. Nulla tincidunt nulla sed est maximus dapibus. Nulla sit amet nibh sed ex scelerisque ultricies. Curabitur venenatis moleint nisi id viverra.</p>
        </div>
        <div class="faq_item mt-4 pb-4">
            <div class="faq_title d-flex justify-content-between align-items-center">
                <h4>I am having trouble receiving emails from Guidance Guide.</h4>
                <span class="d-block faq_toggle">+</span>   
            </div>
            <p class="faq_text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum pretium scelerisque lectus. Nulla tincidunt nulla sed est maximus dapibus. Nulla sit amet nibh sed ex scelerisque ultricies. Curabitur venenatis moleint nisi id viverra.</p>
        </div>
    </div>
</div>


<script type='text/javascript'>
//Dom ready
en4.core.runonce.add(function(){
    loadLeaderboardResults();
});

// FAQ on click display question and transform plus to close
document.querySelectorAll(".faq_toggle").forEach(function(toggle){
    toggle.addEventListener('click', function(event){
        event.target.parentNode.parentNode.querySelector(".faq_text").toggleClass("faq_text_disp");
        event.target.parentNode.toggleClass("mb-3");
        event.target.toggleClass("faq_transform");
    });
});

// Pagionation Number Change Start
var pageNum = 1;
document.getElementById('leaderboard_previous').addEventListener('click', function(){
    if(pageNum >= 2){
        pageNum--;
        document.getElementById('leaderboard_previous').removeClass('pagination_button_diss');
        document.getElementById('leaderboard_next').removeClass('pagination_button_diss');
        loadLeaderboardResults(pageNum);
    }else{
        document.getElementById('leaderboard_previous').addClass('pagination_button_diss');
    }
});
document.getElementById('leaderboard_next').addEventListener('click', function(){
    if(pageNum <= 2){
        pageNum++;
        document.getElementById('leaderboard_next').removeClass('pagination_button_diss');
        document.getElementById('leaderboard_previous').removeClass('pagination_button_diss');
        loadLeaderboardResults(pageNum);
    }else{
        document.getElementById('leaderboard_next').addClass('pagination_button_diss');
    }
});
// Pagination Number Change End

// Leaderboard Results Ajax Function
function loadLeaderboardResults(page = 1){
    //Request data can be linked to form inputs
    var requestData = {};
    requestData.mvp = null; //Possible values 1 or 0
    requestData.expert = null; //Possible values 1 or 0
    requestData.limit = 20; // Limit to 20 People per Page
    requestData.page = page;// Limit to 3 Pages
    
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader my-5");
    var url = en4.core.baseUrl+"api/v1/member";
    
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
                for(var i = 0; i < results.length; i++){
                    html += '<div class="leaderboard_item d-flex justify-content-between">'+
                                '<div class="d-flex justify-content-center align-items-center">'+
                                    ((page-1)*20+(i+1))+
                                '</div>'+
                                '<div class="d-flex align-items-center leader position-relative">'+
                                    '<img src="'+results[i].avatarPhoto.photoURL+'"/>'+
                                    '<span class="cont_level position-absolute">'+
                                        results[i].contributionLevel+'</span>'+
                                    '<h4>'+results[i].displayName+'</h4>'+
                                '</div>'+
                                '<div class="points d-flex align-items-center justify-content-center">'+
                                    '<svg style="margin: 3px 5px 0px 0px;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px" viewBox="0 0 42.03 39.91"><defs><linearGradient id="a" x1="26.26" y1="12.68" x2="40.67" y2="12.68" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#51b2b6"></stop><stop offset="1" stop-color="#5bc6cd"></stop></linearGradient><linearGradient id="b" y1="17.32" x2="17.39" y2="17.32" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#5bc6cd"></stop><stop offset="1" stop-color="#51b2b6"></stop></linearGradient></defs><title>star_pg</title><path d="M40.23,8.55,32.7,18.46l-6.44-8.6L38.77,7C40.61,6.57,41.14,7.31,40.23,8.55Z" fill="url(#a)"></path><path d="M17.39,12,.93,16.13c-1,.24-1.28,1.35-.32,1.79l16.06,4.7Z" fill="url(#b)"></path><path d="M15.31,38.4,17.42,1c0-1.06,1.1-1.31,1.76-.45L41.59,28.45c.83,1,.6,2.71-1.71,1.81L26.36,25.09l-8.44,14A1.36,1.36,0,0,1,15.31,38.4Z" fill="#5bc6cd"></path></svg>'+
                                    results[i].contribution+
                                '</div>'+
                            '</div>';
                }
                leaderboardContent.innerHTML = html;
                // Showing current page in Pagination Section
                document.getElementById('leaderboard_pageNum').innerText = page;                          
            }else{
                leaderboardContent.innerHTML = responseJSON.message;
            }
        }
    });
    request.send();
}    
</script>