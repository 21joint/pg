<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

?>

<!--page hero-->
<div class="page-hero">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 text-center">
        <h1 class="text-primary text-uppercase page-hero--title">Reviews</h1>
        <p class="text-primary page-hero--caption">Share Your Struggle, Provide Your Theories, and Gain Advice</p>
      </div>
      <div class="w-100 mb-5"></div>
      <div class="col-10">
        <form action="">
          <div class="form-row">
            <div class="form-group col-sm-9 mb-0">
              <input type="text" class="form-control form-control-lg" placeholder="Search for a specific review"/>
            </div>
            <div class="form-group col-sm-3 mb-0">
              <button class="btn btn-lg btn-block btn-success text-white px-0">Search Now</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--page hero ends /-->
<pre id="sd-response"></pre>

<script type="text/javascript">
  //Dom ready
  en4.core.runonce.add(function () {
    loadLeaderboardResults();
  });

  function loadLeaderboardResults() {
    //Request data can be linked to form inputs
    var requestData = {};
    // requestData.limit = 10;
    // requestData.page = 1;

    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    console.log(en4.core);
    var url = en4.core.baseUrl + "api/v1/review";

    var request = new Request.JSON({
      url: url,
      method: 'GET',
      data: requestData,
      onRequest: function () {
        loader.inject($("sd-response"), "after");
      }, //When request is sent.
      onError: function () {
        loader.destroy();
      }, //When request throws an error.
      onCancel: function () {
        loader.destroy();
      }, //When request is cancelled.
      onSuccess: function (responseJSON) { //When request is succeeded.
        loader.destroy();
        if (responseJSON.status_code == 200) {
          $("sd-response").set("html", JSON.stringify(responseJSON.body, undefined, 2));
        } else {
          $("sd-response").set("html", responseJSON.message);
        }
      }
    });
    request.send();
  }
</script>