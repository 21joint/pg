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
  <a href="#"
     id="readmore"
     class="btn-large btn-success text-white text-capitalize font-weight-bold mt-5 mb-4 px-5 py-3">Read More</a>
</div>
<div class="leaderboard">
  <div class="leaderboard_main d-flex justify-content-between">
    <div class="d-flex justify-content-center">Rank</div>
    <div class="d-flex">Leader</div>
    <div id="points"
         class="order_by contribution_home d-flex justify-content-center align-items-center"
         data-order="contributionPoints">Contribution
    </div>
  </div>
  <div id="sd-response" class="container d-flex justify-content-center align-items-center">
    <!-- Loader goes here -->
  </div>
  <div class="leaderboard_content">
    <!-- Content of ajax call goes here -->
  </div>
</div>

<!-- Add the loader in place -->

<script type='text/javascript'>
  //Dom ready
  en4.core.runonce.add(function () {
    loadLeaderboardResults();
  });

  function loadLeaderboardResults() {
    //Request data can be linked to form inputs
    var requestData = {};
    requestData.mvp = null; //Possible values 1 or 0
    requestData.expert = null; //Possible values 1 or 0
    requestData.limit = 20;
    requestData.page = 3;

    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader my-5");
    var url = en4.core.baseUrl + "api/v1/member";

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

        var leaderboardContent = document.querySelector('.leaderboard_content');

        if (responseJSON.status_code == 200) {

          var html = "";
          var results = responseJSON.body.Results;
          for (var i = 0; i < results.length; i++) {
            html += '<div class="leaderboard_item d-flex justify-content-between">' +
              '<div class="d-flex justify-content-center align-items-center">' +
              (i + 1) +
              '</div>' +
              '<div class="d-flex align-items-center leader position-relative">' +
              '<img src="' + results[i].avatarPhoto.photoURL + '"/>' +
              '<span class="cont_level position-absolute">' +
              results[i].contributionLevel + '</span>' +
              '<h4>' + results[i].displayName + '</h4>' +
              '</div>' +
              '<div class="points d-flex align-items-center justify-content-center">' +
              '<svg style="margin: 3px 5px 0px 0px;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px" viewBox="0 0 42.03 39.91"><defs><linearGradient id="a" x1="26.26" y1="12.68" x2="40.67" y2="12.68" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#51b2b6"></stop><stop offset="1" stop-color="#5bc6cd"></stop></linearGradient><linearGradient id="b" y1="17.32" x2="17.39" y2="17.32" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#5bc6cd"></stop><stop offset="1" stop-color="#51b2b6"></stop></linearGradient></defs><title>star_pg</title><path d="M40.23,8.55,32.7,18.46l-6.44-8.6L38.77,7C40.61,6.57,41.14,7.31,40.23,8.55Z" fill="url(#a)"></path><path d="M17.39,12,.93,16.13c-1,.24-1.28,1.35-.32,1.79l16.06,4.7Z" fill="url(#b)"></path><path d="M15.31,38.4,17.42,1c0-1.06,1.1-1.31,1.76-.45L41.59,28.45c.83,1,.6,2.71-1.71,1.81L26.36,25.09l-8.44,14A1.36,1.36,0,0,1,15.31,38.4Z" fill="#5bc6cd"></path></svg>' +
              results[i].contribution +
              '</div>' +
              '</div>';
          }
          leaderboardContent.innerHTML = html;
        } else {
          leaderboardContent.innerHTML = responseJSON.message;
        }
      }
    });
    request.send();
  }
</script>