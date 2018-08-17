<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

?>


<!-- PAGE HERO -->
<div class="page-hero community-hero bg-white">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-10 text-center">
        <h1
          class="text-primary text-uppercase page-hero--title"><?php echo $this->translate(
            'Our Community'
          ); ?></h1>
        <p class="text-primary page-hero--caption"><?php echo $this->translate(
            'You can trust our community of real parents'
          ); ?></p>
      </div>
      <div class="w-100"></div>
      <div class="col-12">
        <a id="seeMore"
           class="btn btn-lg btn-block btn-success text-white px-0">
          <?php echo $this->translate('See More'); ?></a>
      </div>
    </div>
  </div>
</div>
<!-- PAGE HERO ///-->

<!-- MVPs and Experts Component -->
<div class="mvps position-relative">
  <div class="mvps_main d-flex justify-content-around align-items-center">
    <h3 id="meet_mvps"
        class="py-3 d-flex justify-content-center mvps_main_active">
      <span class="d-md-block d-none"><?php echo $this->translate(
          "Meet our MVP's"
        ); ?></span>
      <span class="d-md-none d-block"><?php echo $this->translate(
          "MVP's"
        ); ?></span>
    </h3>
    <h3 id="meet_experts" class="py-3 d-flex justify-content-center">
      <span class="d-md-block d-none"><?php echo $this->translate(
          'Meet our Experts'
        ); ?></span>
      <span class="d-md-none d-block"><?php echo $this->translate(
          'Experts'
        ); ?></span>
    </h3>
    <h3 class="py-3 d-flex justify-content-center">
      <a id="go_to_leaderboard"><?php echo $this->translate(
          'Leaderboard'
        ); ?></a>
    </h3>
  </div>
  <div id="sd-response-mvps" class="mvps_content p-5 d-flex align-items-center">
    <!-- Loader goes here -->
    <!-- Content of ajax call goes here -->
  </div>
  <button id="mvps_left"
          class="btn-lg text-primary rounded-circle position-absolute d-none"><
  </button>
  <button id="mvps_right"
          class="btn-lg text-primary rounded-circle position-absolute">>
  </button>
</div>
<!-- Leaderboard Component -->
<div class="leaderboard">
  <div class="leaderboard_main d-flex justify-content-between">
    <div class="d-flex justify-content-center"><?php echo $this->translate(
        'Rank'
      ); ?></div>
    <div class="d-flex"><?php echo $this->translate('Leader'); ?></div>
    <div id="none_click"
         class="d-flex justify-content-center align-items-center"><?php echo $this->translate(
        'Contribution'
      ); ?></div>
  </div>
  <div id="sd-response"
       class="container d-flex justify-content-center align-items-center">
    <!-- Loader goes here -->
  </div>
  <div class="leaderboard_content">
    <!-- Content of ajax call goes here -->
  </div>
  <!-- Pagination for Leaderboard Component -->
  <!-- <div class="leaderboard_pagination d-flex justify-content-end align-items-center mt-5 mr-5"> -->
  <!-- Content Pagination -->
  <!-- <span id="leaderboard_previous" class="pagination_button"><</span> -->
  <!-- <span id="leaderboard_pageNum" class="mx-5"> -->
  <!-- Displays the current page of Leaderboard Results -->
  <!-- </span> -->
  <!-- <span id="leaderboard_next" class="pagination_button">></span> -->
  <!-- </div> -->
</div>
<div id="findExpert" class="find_expert_main container-fluid py-5 mt-5">
  <div class="row d-flex align-items-center">
    <div
      class="find_expert_badges col-lg position-relative d-flex justify-content-around align-items-center">
      <img class="front_image"
           src="<?php echo $this->baseUrl(); ?>/application/modules/Sdparentalguide/externals/images/mvp_badge.png"/>
      <img class="back_image"
           src="<?php echo $this->baseUrl(); ?>/application/modules/Sdparentalguide/externals/images/badge_baby.png"/>
      <img class="back_image"
           src="<?php echo $this->baseUrl(); ?>/application/modules/Sdparentalguide/externals/images/badge_baby.png"/>
    </div>
    <div class="find_expert_text col-lg">
      <h2>Find an expert. Become an expert.</h2>
      <h3 class="text-capitalize">How to become a contributor to parental
        guidance</h3>
      <p>Showcase your experience and expertise, while collecting fancy badges
        and earning credibility points! Email us at <a href="#"
                                                       class="text-primary">xxxxxxxxxxx</a>
        to apply!</p>
      <h3 class="text-capitalize">How to earn credibility</h3>
      <p>Earn credibility by engaging in various activites on the site: Creating
        Reviews, Writing Stories, Commenting on threads. The more you engage,
        the faster you earn.</p>
      <h3 class="text-capitalize">How to earn badges</h3>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
        veniam, quis nostrud exercitation ullamco.</p>
      <h3 class="text-capitalize">Want to become an MVP?</h3>
      <p>Our MVP program is based on overall excellence! We choose our top
        notch, engaged and community driven parents to join our MVP program on
        quarterly basis. You do NOT have to have certain number of badges.</p>
    </div>
  </div>
</div>
<div class="credibility_info_main my-5">
  <div class="row">
    <div class="credibility_info_text col-lg">
      <h2 class="text-uppercase">Credibility info</h2>
      <p class="mt-5">Do not delete the activities which you have performed to
        earn credibility. If you delete those activities, you will loose the
        credit earned.</p>
      <p class="mt-5">Credibility is NOT the same as Contribution Badges. You
        will earn badges for delivering quality content in a specific area.
        Credibility is what helps you to earn recognition across the site. For
        example, you maybe a Gold Member Contributor for Car Seats, but only
        attained a level 2 Credibility because you are not an active user of the
        site.</p>
      <p class="mt-5">As you engage across the site, your credibility will go
        up. You will earn "Credibility Level" badges once you cross a certain
        credibility ranking. This way everyone can see who is the most active
        and engaged member on the site!</p>
    </div>
    <div class="col-lg"><!-- Keeping up space --></div>
  </div>
</div>
<div class="faq_main my-5 py-5 d-flex flex-column align-items-center justify-content-center ">
  <h2 class="w-100 text-center">FAQ's</h2>
  <div class="faq_content w-100">
    <div class="faq_item mt-4 pb-4">
      <div class="faq_title d-flex justify-content-between align-items-center">
        <h4>Can anybody post reviews, earn badges and gain credibility
          score?</h4>
        <span class="d-block faq_toggle">+</span>
      </div>
      <p class="faq_text">Lorem ipsum dolor sit amet, consectetur adipiscing
        elit. Vestibulum pretium scelerisque lectus. Nulla tincidunt nulla sed
        est maximus dapibus. Nulla sit amet nibh sed ex scelerisque ultricies.
        Curabitur venenatis moleint nisi id viverra.</p>
    </div>
    <div class="faq_item mt-4 pb-4">
      <div class="faq_title d-flex justify-content-between align-items-center">
        <h4>What are the official hashtags for Guidance Guide and Parental
          Guidance?</h4>
        <span class="d-block faq_toggle">+</span>
      </div>
      <p class="faq_text">Lorem ipsum dolor sit amet, consectetur adipiscing
        elit. Vestibulum pretium scelerisque lectus. Nulla tincidunt nulla sed
        est maximus dapibus. Nulla sit amet nibh sed ex scelerisque ultricies.
        Curabitur venenatis moleint nisi id viverra.</p>
    </div>
    <div class="faq_item mt-4 pb-4">
      <div class="faq_title d-flex justify-content-between align-items-center">
        <h4>What if I don't have enough product to write a review and try to
          earn a badge?</h4>
        <span class="d-block faq_toggle">+</span>
      </div>
      <p class="faq_text">Lorem ipsum dolor sit amet, consectetur adipiscing
        elit. Vestibulum pretium scelerisque lectus. Nulla tincidunt nulla sed
        est maximus dapibus. Nulla sit amet nibh sed ex scelerisque ultricies.
        Curabitur venenatis moleint nisi id viverra.</p>
    </div>
    <div class="faq_item mt-4 pb-4">
      <div class="faq_title d-flex justify-content-between align-items-center">
        <h4>Can Contributors contact brands?</h4>
        <span class="d-block faq_toggle">+</span>
      </div>
      <p class="faq_text">Lorem ipsum dolor sit amet, consectetur adipiscing
        elit. Vestibulum pretium scelerisque lectus. Nulla tincidunt nulla sed
        est maximus dapibus. Nulla sit amet nibh sed ex scelerisque ultricies.
        Curabitur venenatis moleint nisi id viverra.</p>
    </div>
    <div class="faq_item mt-4 pb-4">
      <div class="faq_title d-flex justify-content-between align-items-center">
        <h4>I want to review a product, but it technically fits into multiple
          categories. Where should I put it?</h4>
        <span class="d-block faq_toggle">+</span>
      </div>
      <p class="faq_text">Lorem ipsum dolor sit amet, consectetur adipiscing
        elit. Vestibulum pretium scelerisque lectus. Nulla tincidunt nulla sed
        est maximus dapibus. Nulla sit amet nibh sed ex scelerisque ultricies.
        Curabitur venenatis moleint nisi id viverra.</p>
    </div>
    <div class="faq_item mt-4 pb-4">
      <div class="faq_title d-flex justify-content-between align-items-center">
        <h4>I am getting a ton of notifications that I don't want. Where do I
          find the notification settings?</h4>
        <span class="d-block faq_toggle">+</span>
      </div>
      <p class="faq_text">Lorem ipsum dolor sit amet, consectetur adipiscing
        elit. Vestibulum pretium scelerisque lectus. Nulla tincidunt nulla sed
        est maximus dapibus. Nulla sit amet nibh sed ex scelerisque ultricies.
        Curabitur venenatis moleint nisi id viverra.</p>
    </div>
    <div class="faq_item mt-4 pb-4">
      <div class="faq_title d-flex justify-content-between align-items-center">
        <h4>What browser is preffered for the best website functionality?</h4>
        <span class="d-block faq_toggle">+</span>
      </div>
      <p class="faq_text">Lorem ipsum dolor sit amet, consectetur adipiscing
        elit. Vestibulum pretium scelerisque lectus. Nulla tincidunt nulla sed
        est maximus dapibus. Nulla sit amet nibh sed ex scelerisque ultricies.
        Curabitur venenatis moleint nisi id viverra.</p>
    </div>
    <div class="faq_item mt-4 pb-4">
      <div class="faq_title d-flex justify-content-between align-items-center">
        <h4>I am having trouble receiving emails from Guidance Guide.</h4>
        <span class="d-block faq_toggle">+</span>
      </div>
      <p class="faq_text">Lorem ipsum dolor sit amet, consectetur adipiscing
        elit. Vestibulum pretium scelerisque lectus. Nulla tincidunt nulla sed
        est maximus dapibus. Nulla sit amet nibh sed ex scelerisque ultricies.
        Curabitur venenatis moleint nisi id viverra.</p>
    </div>
  </div>
</div>


<script type='text/javascript'>
  //Dom ready for Leaderboard Results
  en4.core.runonce.add(function () {
    loadLeaderboardResults();
  });
  // Dom ready for Mvps and Experts Results
  en4.core.runonce.add(function () {
    loadMvpExpertResults();
  });

  // See More Button linking to Find an Expert Part of the Page
  // $(".seeMore").attr('href',  en4.core.baseUrl + "community/home#findExpert");

  // Pagionation Number Change Start
  // var pageNum = 1;
  // $('#leaderboard_previous$(').)aon('click', function(){
  //     if(pageNum >= 2){
  //         pageNum--;
  //         $('#leaderboard_previous').removeClass('pagination_button_diss');
  //         $('#leaderboard_next').removeClass('pagination_button_diss');
  //         loadLeaderboardResults(pageNum);
  //     }else{
  //         $('#leaderboard_previous').addClass('pagination_button_diss');
  //     }
  // });
  // $('#leaderboard_next$(').)aon('click', function(){
  //     if(pageNum <= 2){
  //         pageNum++;
  //         $('#leaderboard_next').removeClass('pagination_button_diss');
  //         $('#leaderboard_previous').removeClass('pagination_button_diss');
  //         loadLeaderboardResults(pageNum);
  //     }else{
  //         $('#leaderboard_next').addClass('pagination_button_diss');
  //     }
  // });
  // Pagination Number Change End

  // Leaderboard Results Ajax Function -> start
  function loadLeaderboardResults(page = 1) {
    //Request data can be linked to form inputs
    var requestData = {};
    requestData.contributionRangeType = "Overall"; //Possible values "Overall", "Week", "Month"
    requestData.orderBy = "contributionPoints"; //Possible values "contributionPoints", "questionCount", "reviewCount", "followers"
    requestData.limit = 20;//Display limit for users
    requestData.page = page;//Place for pagination

    var loader = $(en4.core.loader.clone());
    loader.addClass("sd_loader my-5");
    var url = en4.core.baseUrl + "api/v1/ranking";

    var request = new Request.JSON({
      url: url,
      method: 'get',
      data: requestData,
      onRequest: function () {
        loader.inject($("sd-response"));
      }, //When request is sent.
      onError: function () {
        loader.destroy();
      }, //When request throws an error.
      onCancel: function () {
        loader.destroy();
      }, //When request is cancelled.
      onSuccess: function (responseJSON) { //When request is succeeded.
        loader.destroy();

        var leaderboardContent = $('.leaderboard_content');

        if (responseJSON.status_code == 200) {
          var html = "";
          var results = responseJSON.body.Results;
          for (var i = 0; i < results.length; i++) {
            // Matching Contribution Level to Contribution Award (Bronze, Silver, Gold, Platinum)
            var adjust_award = String(results[i].expertPlatinumCount) +
              String(results[i].expertGoldCount) +
              String(results[i].expertSilverCount) +
              String(results[i].expertBronzeCount);
            // Number that Will be Displayed
            var adjust_count;
            if (adjust_award >= 1000) {
              adjust_count = results[i].expertPlatinumCount;
            } else if (adjust_award >= 100) {
              adjust_count = results[i].expertGoldCount;
            } else if (adjust_award >= 10) {
              adjust_count = results[i].expertSilverCount;
            } else if (adjust_award >= 1) {
              adjust_count = results[i].expertBronzeCount;
            } else {
              adjust_count = results[i].contributionLevel;
            }

            html += '<div class="leaderboard_item d-flex justify-content-between">' +
              '<div class="d-flex justify-content-center align-items-center">' +
              ((page - 1) * 20 + (i + 1)) +
              '</div>' +
              '<div class="d-flex align-items-center leader position-relative">' +
              '<div class="profile-popup position-absolute bg-white d-none">' +
              '<div class="avatar-header d-flex mx-3 mt-3 px-2 pt-2">' +
              '<img src="' +
              results[i].avatarPhoto.photoURLIcon +
              '" alt="avatar photo"/>' +
              '<div class="profile-popup--info d-flex flex-column">' +
              '<a class="font-weight-bold" href="' +
              en4.core.baseUrl + "profile/" + results[i].memberName +
              '">' +
              results[i].displayName +
              '</a>' +
              '<div class="d-flex justify-content-start align-items-center">' +
              '<svg style="margin: 3px 5px 0px 0px;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 0 42.03 39.91"><defs><linearGradient id="a" x1="26.26" y1="12.68" x2="40.67" y2="12.68" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#51b2b6"></stop><stop offset="1" stop-color="#5bc6cd"></stop></linearGradient><linearGradient id="b" y1="17.32" x2="17.39" y2="17.32" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#5bc6cd"></stop><stop offset="1" stop-color="#51b2b6"></stop></linearGradient></defs><title>star_pg</title><path d="M40.23,8.55,32.7,18.46l-6.44-8.6L38.77,7C40.61,6.57,41.14,7.31,40.23,8.55Z" fill="#5bc6cd"></path><path d="M17.39,12,.93,16.13c-1,.24-1.28,1.35-.32,1.79l16.06,4.7Z" fill="#5bc6cd"></path><path d="M15.31,38.4,17.42,1c0-1.06,1.1-1.31,1.76-.45L41.59,28.45c.83,1,.6,2.71-1.71,1.81L26.36,25.09l-8.44,14A1.36,1.36,0,0,1,15.31,38.4Z" fill="#5bc6cd"></path></svg>' +
              results[i].contribution +
              '</div>' +
              '</div>' +
              '<span class="avatar_close">' +
              '<svg width="14px" aria-hidden="true" data-prefix="fal" data-icon="times" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="svg-inline--fa fa-times fa-w-10 fa-2x"><path fill="currentColor" d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z" class=""></path></svg>' +
              '</span>' +
              '</div>' +
              '<div class="prg-badges d-flex justify-content-around align-items-center my-2 px-3">' +
              '<div class="avatar-badge badge_bronze position-relative d-flex flex-column justify-content-center align-items-center">' +
              '<img src="<?php echo $this->baseUrl(); ?>/images/Bronze.svg"/>' +
              '<span class="number_badges position-absolute text-white font-weight-bold">' +
              results[i].bronzeCount +
              '</span>' +
              '<span class="badge_name">Bronze</span>' +
              '</div>' +
              '<div class="avatar-badge badge_silver position-relative d-flex flex-column justify-content-center align-items-center">' +
              '<img src="<?php echo $this->baseUrl(); ?>/images/Silver.svg"/>' +
              '<span class="number_badges position-absolute text-white font-weight-bold">' +
              results[i].silverCount +
              '</span>' +
              '<span class="badge_name">Silver</span>' +
              '</div>' +
              '<div class="avatar-badge badge_gold position-relative d-flex flex-column justify-content-center align-items-center">' +
              '<img src="<?php echo $this->baseUrl(); ?>/images/Gold.svg"/>' +
              '<span class="number_badges position-absolute text-white font-weight-bold">' +
              results[i].goldCount +
              '</span>' +
              '<span class="badge_name">Gold</span>' +
              '</div>' +
              '<div class="avatar-badge badge_platinum position-relative d-flex flex-column justify-content-center align-items-center">' +
              '<img src="<?php echo $this->baseUrl(); ?>/images/Platinum.svg"/>' +
              '<span class="number_badges position-absolute text-white font-weight-bold">' +
              results[i].platinumCount +
              '</span>' +
              '<span class="badge_name">Platinum</span>' +
              '</div>' +
              '</div>' +
              '<div class="profile-popup--footer d-flex justify-content-center align-items-center border-top">' +
              '<div class="d-flex justify-content-center p-3 border-right">' +
              'Reviews ' +
              '<span class="text-primary font-weight-bold ml-1">' +
              results[i].reviewCount +
              '</span>' +
              '</div>' +
              '<div class="d-flex justify-content-center p-3">' +
              'Answers ' +
              '<span class="text-primary font-weight-bold ml-1">' +
              results[i].answerCount +
              '</span>' +
              '</div>' +
              '</div>' +
              '</div>' +
              '<img class="avatar_halo" src="' +
              results[i].avatarPhoto.photoURLIcon +
              '" data-halo="' + results[i].mvp + '"/>' +
              '<span class="cont_level position-absolute rounded-circle" data-cont="' +
              results[i].expertPlatinumCount +
              results[i].expertGoldCount +
              results[i].expertSilverCount +
              results[i].expertBronzeCount +
              '">' +
              adjust_count +
              '</span>' +
              '<h4 class="font-weight-bold"><a href="' +
              en4.core.baseUrl + "profile/" + results[i].memberName +
              '">' + results[i].displayName + '</a>' +
              '</h4>' +
              '</div>' +
              '<div class="points d-flex align-items-center justify-content-center">' +
              '<svg style="margin: 3px 5px 0px 0px;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px" viewBox="0 0 42.03 39.91"><defs><linearGradient id="a" x1="26.26" y1="12.68" x2="40.67" y2="12.68" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#51b2b6"></stop><stop offset="1" stop-color="#5bc6cd"></stop></linearGradient><linearGradient id="b" y1="17.32" x2="17.39" y2="17.32" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#5bc6cd"></stop><stop offset="1" stop-color="#51b2b6"></stop></linearGradient></defs><title>star_pg</title><path d="M40.23,8.55,32.7,18.46l-6.44-8.6L38.77,7C40.61,6.57,41.14,7.31,40.23,8.55Z" fill="#5bc6cd"></path><path d="M17.39,12,.93,16.13c-1,.24-1.28,1.35-.32,1.79l16.06,4.7Z" fill="#5bc6cd"></path><path d="M15.31,38.4,17.42,1c0-1.06,1.1-1.31,1.76-.45L41.59,28.45c.83,1,.6,2.71-1.71,1.81L26.36,25.09l-8.44,14A1.36,1.36,0,0,1,15.31,38.4Z" fill="#5bc6cd"></path></svg>' +
              results[i].contribution +
              '</div>' +
              '</div>';
          }
          leaderboardContent.html(html);
          // Avatar Styling
          // Check the Data Attribute for Mvp Status
          // If Item has Mvp Status Put Halo Around Avatar Change Contribution Level Color
          $('.avatar_halo').each(function (index, avatar_halo) {
            if (avatar_halo.dataset.halo == "true") {
              avatar_halo.addClass('avatar_halo_disp');
              avatar_halo.style.borderImage = "url('/images/gear.svg') 20 20 20 20 fill";
            }
          });
          // Checking Contribution Level on Avatar
          $('.cont_level').each(function (index, avatar_cont) {
            if (avatar_cont.dataset.cont >= 1000) {
              avatar_cont.addClass('cont_level_platinum');
            } else if (avatar_cont.dataset.cont >= 100) {
              avatar_cont.addClass('cont_level_gold');
            } else if (avatar_cont.dataset.cont >= 10) {
              avatar_cont.addClass('cont_level_silver');
            } else if (avatar_cont.dataset.cont >= 1) {
              avatar_cont.addClass('cont_level_bronze');
            } else {
              avatar_cont.addClass('cont_level_default');
            }
          });
          // Displaying Avatar Popup
          $('.avatar_halo').each(function (index, popup_func) {
            $(popup_func).on('click', function () {
              $(popup_func).prev().addClass('d-block').removeClass('d-none');
            });
          });
          $(window).on('mouseup', function () {
            $('.profile-popup').each(function (index, removed) {
              if (removed.hasClass('d-block')) {
                removed.addClass('d-none').removeClass('d-block');
              }
            });
          });

          // Showing current page in pagination section
          // $('#leaderboard_pageNum').innerText = page;
        } else {
          leaderboardContent.html(responseJSON.message);
        }
      }
    });
    request.send();
  }

  // Leaderboard Results Ajax Function -> end

  // Toggle Between MVPs and Experts
  var disp_mvps;
  var disp_experts;
  $("#meet_mvps").on('click', function () {
    $(this).addClass("mvps_main_active");
    $("#meet_experts").removeClass("mvps_main_active");
    disp_mvps = 1;
    disp_experts = 0;
    loadMvpExpertResults(disp_mvps, disp_experts);
  });
  $("#meet_experts").on('click', function () {
    $(this).addClass("mvps_main_active");
    $("#meet_mvps").removeClass("mvps_main_active");
    disp_mvps = 0;
    disp_experts = 1;
    loadMvpExpertResults(disp_mvps, disp_experts);
  });

  // Go to Community Leaderboard Page
  $("#go_to_leaderboard").href = en4.core.baseUrl + "community/leaderboard";

  // MVPs and Experts Results Ajax Function -> start
  // Arguments disp_mvps = 1, disp_experts = 0 When everything get wired
  function loadMvpExpertResults(disp_mvps = 1, disp_experts = 0) {
    //Request data can be linked to form inputs
    var requestData = {};
    requestData.mvp = disp_mvps; //Possible values 1 or 0 -> disp_mvps from arguments
    requestData.expert = disp_experts; //Possible values 1 or 0 -> disp_experts from arguments
    requestData.limit = 20; // Limit to 20 People per Page
    requestData.page = 1;// Limit to 3 Pages

    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    var url = en4.core.baseUrl + "api/v1/member";

    var request = new Request.JSON({
      url: url,
      method: 'get',
      data: requestData,
      onRequest: function () {
        loader.inject($("sd-response-mvps"));
      }, //When request is sent.
      onError: function () {
        loader.destroy();
      }, //When request throws an error.
      onCancel: function () {
        loader.destroy();
      }, //When request is cancelled.
      onSuccess: function (responseJSON) { //When request is succeeded.
        loader.destroy();

        var leaderboardContent = $('.mvps_content');
        if (responseJSON.status_code == 200) {
          var html = "";
          var results = responseJSON.body.Results;
          for (var i = 0; i < results.length; i++) {
            // Matching Contribution Level to Contribution Award (Bronze, Silver, Gold, Platinum)
            var adjust_award = String(results[i].expertPlatinumCount) +
              String(results[i].expertGoldCount) +
              String(results[i].expertSilverCount) +
              String(results[i].expertBronzeCount);
            // Number that Will be Displayed
            var adjust_count;
            if (adjust_award >= 1000) {
              adjust_count = results[i].expertPlatinumCount;
            } else if (adjust_award >= 100) {
              adjust_count = results[i].expertGoldCount;
            } else if (adjust_award >= 10) {
              adjust_count = results[i].expertSilverCount;
            } else if (adjust_award >= 1) {
              adjust_count = results[i].expertBronzeCount;
            } else {
              adjust_count = results[i].contributionLevel;
            }

            html += '<div class="mvps_item d-flex flex-column align-items-center justify-content-center position-relative mr-5">' +
              '<div class="profile-popup position-absolute bg-white d-none">' +
              '<div class="avatar-header d-flex mx-3 mt-3 px-2 pt-2">' +
              '<img src="' +
              results[i].avatarPhoto.photoURLIcon +
              '" alt="avatar photo"/>' +
              '<div class="profile-popup--info d-flex flex-column justify-content-start align-items-start ml-3">' +
              '<a class="font-weight-bold" href="' +
              en4.core.baseUrl + "profile/" + results[i].memberName +
              '">' +
              results[i].displayName +
              '</a>' +
              '<div class="d-flex justify-content-start align-items-center">' +
              '<svg style="margin: 3px 5px 0px 0px;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 0 42.03 39.91"><defs><linearGradient id="a" x1="26.26" y1="12.68" x2="40.67" y2="12.68" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#51b2b6"></stop><stop offset="1" stop-color="#5bc6cd"></stop></linearGradient><linearGradient id="b" y1="17.32" x2="17.39" y2="17.32" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#5bc6cd"></stop><stop offset="1" stop-color="#51b2b6"></stop></linearGradient></defs><title>star_pg</title><path d="M40.23,8.55,32.7,18.46l-6.44-8.6L38.77,7C40.61,6.57,41.14,7.31,40.23,8.55Z" fill="#5bc6cd"></path><path d="M17.39,12,.93,16.13c-1,.24-1.28,1.35-.32,1.79l16.06,4.7Z" fill="#5bc6cd"></path><path d="M15.31,38.4,17.42,1c0-1.06,1.1-1.31,1.76-.45L41.59,28.45c.83,1,.6,2.71-1.71,1.81L26.36,25.09l-8.44,14A1.36,1.36,0,0,1,15.31,38.4Z" fill="#5bc6cd"></path></svg>' +
              results[i].contribution +
              '</div>' +
              '</div>' +
              '<span class="avatar_close">' +
              '<svg width="14px" aria-hidden="true" data-prefix="fal" data-icon="times" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="svg-inline--fa fa-times fa-w-10 fa-2x"><path fill="currentColor" d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z" class=""></path></svg>' +
              '</span>' +
              '</div>' +
              '<div class="prg-badges d-flex justify-content-around align-items-center my-2 px-3">' +
              '<div class="avatar-badge badge_bronze position-relative d-flex flex-column justify-content-center align-items-center">' +
              '<img src="<?php echo $this->baseUrl(); ?>/images/Bronze.svg"/>' +
              '<span class="number_badges position-absolute text-white font-weight-bold">' +
              results[i].bronzeCount +
              '</span>' +
              '<span class="badge_name">Bronze</span>' +
              '</div>' +
              '<div class="avatar-badge badge_silver position-relative d-flex flex-column justify-content-center align-items-center">' +
              '<img src="<?php echo $this->baseUrl(); ?>/images/Silver.svg"/>' +
              '<span class="number_badges position-absolute text-white font-weight-bold">' +
              results[i].silverCount +
              '</span>' +
              '<span class="badge_name">Silver</span>' +
              '</div>' +
              '<div class="avatar-badge badge_gold position-relative d-flex flex-column justify-content-center align-items-center">' +
              '<img src="<?php echo $this->baseUrl(); ?>/images/Gold.svg"/>' +
              '<span class="number_badges position-absolute text-white font-weight-bold">' +
              results[i].goldCount +
              '</span>' +
              '<span class="badge_name">Gold</span>' +
              '</div>' +
              '<div class="avatar-badge badge_platinum position-relative d-flex flex-column justify-content-center align-items-center">' +
              '<img src="<?php echo $this->baseUrl(); ?>/images/Platinum.svg"/>' +
              '<span class="number_badges position-absolute text-white font-weight-bold">' +
              results[i].platinumCount +
              '</span>' +
              '<span class="badge_name">Platinum</span>' +
              '</div>' +
              '</div>' +
              '<div class="avatar-popup--footer d-flex justify-content-center align-items-center border-top">' +
              '<div class="d-flex justify-content-center align-items-center p-3 border-right">' +
              'Reviews ' +
              '<span class="text-primary font-weight-bold ml-1">' +
              results[i].reviewCount +
              '</span>' +
              '</div>' +
              '<div class="d-flex justify-content-center align-items-center p-3">' +
              'Answers ' +
              '<span class="text-primary font-weight-bold ml-1">' +
              results[i].answerCount +
              '</span>' +
              '</div>' +
              '</div>' +
              '</div>' +
              '<img class="mvp_halo" src="' +
              results[i].avatarPhoto.photoURLProfile +
              '" data-halo="' + results[i].mvp + '"/>' +
              '<span class="cont_level position-absolute rounded-circle" data-cont="' +
              results[i].expertPlatinumCount +
              results[i].expertGoldCount +
              results[i].expertSilverCount +
              results[i].expertBronzeCount +
              '">' +
              adjust_count +
              '</span>' +
              '<h4 class="font-weight-bold text-center"><a href="' +
              en4.core.baseUrl + "profile/" + results[i].memberName +
              '">' + results[i].displayName + '</a>' +
              '</h4>' +
              '<div class="mvps_contribution d-flex justify-content-center align-items-center w-100 py-2">' +
              '<svg style="margin: 3px 5px 0px 0px;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px" viewBox="0 0 42.03 39.91"><defs><linearGradient id="a" x1="26.26" y1="12.68" x2="40.67" y2="12.68" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#51b2b6"></stop><stop offset="1" stop-color="#5bc6cd"></stop></linearGradient><linearGradient id="b" y1="17.32" x2="17.39" y2="17.32" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#5bc6cd"></stop><stop offset="1" stop-color="#51b2b6"></stop></linearGradient></defs><title>star_pg</title><path d="M40.23,8.55,32.7,18.46l-6.44-8.6L38.77,7C40.61,6.57,41.14,7.31,40.23,8.55Z" fill="#5bc6cd"></path><path d="M17.39,12,.93,16.13c-1,.24-1.28,1.35-.32,1.79l16.06,4.7Z" fill="#5bc6cd"></path><path d="M15.31,38.4,17.42,1c0-1.06,1.1-1.31,1.76-.45L41.59,28.45c.83,1,.6,2.71-1.71,1.81L26.36,25.09l-8.44,14A1.36,1.36,0,0,1,15.31,38.4Z" fill="#5bc6cd"></path></svg>' +
              results[i].contribution +
              '</div>' +
              '</div>';
          }
          leaderboardContent.html(html);
          // Avatar Styling
          // Check the Data Attribute for Mvp Status
          // If Item has Mvp Status Put Halo Around Avatar Change Contribution Level Color
          $('.mvp_halo').each(function (index, mvp_halo) {
            if (mvp_halo.dataset.halo == "true") {
              mvp_halo.addClass('avatar_halo_disp');
              mvp_halo.style.borderImage = "url(/images/border.png) 10 10 10 10 fill";
            }
          });
          // Checking Contribution Level on Avatar
          $('.cont_level').each(function (index, avatar_cont) {
            if (avatar_cont.dataset.cont >= 1000) {
              avatar_cont.addClass('cont_level_platinum');
            } else if (avatar_cont.dataset.cont >= 100) {
              avatar_cont.addClass('cont_level_gold');
            } else if (avatar_cont.dataset.cont >= 10) {
              avatar_cont.addClass('cont_level_silver');
            } else if (avatar_cont.dataset.cont >= 1) {
              avatar_cont.addClass('cont_level_bronze');
            } else {
              avatar_cont.addClass('cont_level_default');
            }
          });
          // Displaying Avatar Popup
          $('.mvp_halo').each(function (index, popup_func) {
            $(popup_func).on('click', function () {
              this.previousSibling.addClass('d-block').removeClass('d-none');
            });
          });
          $(window).on('mouseup', function () {
            $('.avatar-popup').each(function (index, removed) {
              if (removed.hasClass('d-block')) {
                removed.addClass('d-none').removeClass('d-block');
              }
            });
          });
        } else {
          leaderboardContent.html(responseJSON.message);
        }
      }
    });
    request.send();
  }

  // MVPS and Experts Results Ajax Function -> end
</script>
