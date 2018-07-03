<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

?>

<pre id='sd-response'></pre>
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
    requestData.limit = 10;
    requestData.page = 1;

    var loader = en4.core.loader.clone();
    loader.addClass('sd_loader');
    var url = (en4.core.environment == 'development' ? 'http://cors.io/?' + 'https://int-pg.guidanceguide.com' : 'https://int-pg.guidanceguide.com') + ("/api/v1/member");

    var request = new Request.JSON({
      url: url,
      method: 'GET',
      data: requestData,
      headers: {
        'Access-Control-Allow-Origin' : '*',
        'Access-Control-Allow-Methods': 'DELETE, HEAD, GET, OPTIONS, POST, PUT',
        'Access-Control-Allow-Headers': 'Content-Type, Content-Range, Content-Disposition, Content-Description'
      },
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