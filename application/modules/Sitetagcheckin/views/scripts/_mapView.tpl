<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitetagcheckin
 * @copyright  Copyright 2014-2015 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2014-07-20 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php $params = $this->action->params['checkin']; ?>
<?php
if( empty($this->action->params['checkin']) || !empty($this->action->attachment_count) || empty($params["latitude"]) || empty($params["longitude"])) {
  return;
}
?>

<?php
//GET API KEY
$apiKey = Engine_Api::_()->seaocore()->getGoogleMapApiKey();
$this->headScript()->appendFile("https://maps.googleapis.com/maps/api/js?libraries=places&key=$apiKey");
?>
<?php $settings = Engine_Api::_()->getApi("settings", "core"); ?>
<?php $latitude = $params["latitude"]; ?>
<?php $longitude = $params["longitude"]; ?>



<div id="dynamic_app_info_advancedactivity_<?php echo $this->action->getIdentity(); ?>" style="margin-top: 10px;">

  <div id="advancedactivity_map_canvas_view_feed_<?php echo $this->action->getIdentity(); ?>">
    <div class="seaocore_map clr o_hidden">
      <div id="advancedactivity_browse_map_canvas_<?php echo $this->action->getIdentity(); ?>" class="advancedactivity_list_map"> </div>
      <div class="clear mtop10"></div>
      <?php $siteTitle = $settings->core_general_site_title; ?>
      <?php if( !empty($siteTitle) ) : ?>
        <div class="seaocore_map_info"><?php echo $this->translate("Locations on %s", "<a href='' target='_blank'>$siteTitle</a>"); ?></div>
      <?php endif; ?>
    </div>
  </div>
  <div class="clear"></div>
  <div class="seaocore_pagination"></div>
  <div class="clr" id="scroll_bar_height"></div>

  <div class="seaocore_loading" id="seaocore_loading" style="display: none;">
    <img src='<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/core/loading.gif' class='mright5' />
    <?php echo $this->translate("Loading ...") ?>
  </div>

</div>
<script type="text/javascript">


  en4.core.runonce.add(function () {
    if (!!!window.sitetagMapLoader) {
      window.sitetagMapLoader = [];
    }
    window.sitetagMapLoader.push(function () {
      // create the map
      var myLatlng = new google.maps.LatLng(<?php echo $params["latitude"] ?>,<?php echo $params["longitude"] ?>);
      var myOptions = {
        zoom: 10,
        center: myLatlng,
        navigationControl: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      }
      var map = new google.maps.Map(document.getElementById("advancedactivity_browse_map_canvas_<?php echo $this->action->getIdentity(); ?>"), myOptions);
      google.maps.event.addListener(map, 'click', function () {
        infowindow.close();
        google.maps.event.trigger(map, 'resize');
      });
      map.setZoom(16);
      map.setCenter(myLatlng);
      var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        title: "<?php echo $params['label'] ?>",
        draggable: false,
      });
      google.maps.event.addListener(marker, 'click', function () {
        infowindow.open(map, marker);
        infowindow.setContent("<?php echo $params['label'] ?>");
      });
    });
    var loadMaps = function () {
      if (!!google.maps) {
        var fn;
        while ((fn = window.sitetagMapLoader.shift())) {
          $try(function () {
            fn();
          });
        }
        window.sitetagMapLoader = [];
      } else {
        setTimeout(loadMaps, 100);
      }
    };
    loadMaps();
  });
  var infowindow = new google.maps.InfoWindow({
    size: new google.maps.Size(150, 50)
  });
</script>

