<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: statistics.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2 class="fleft">
  <?php echo $this->translate('Credits, Reward Points and Virtual Currency - User Engagement Plugin');?>
</h2>
<?php if( count($this->navigation) ): ?>
  <div class='seaocore_admin_tabs clr'>
    <?php
    // Render the menu
    //->setUlClass()
    echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>

<?php endif; ?>
      <p>
  <?php echo $this->translate("Use below filters to observe statistics related to credits earned / deducted / redeemed on your website over different time periods.") ?>
      </p>
      <div class="admin_statistics_search" >
  <?php echo $this->formFilterGraph->render($this) ?>
      </div>
<br><br><br><br>
    <div style="clear:both;height:15px;"></div>
  <div class="admin_statistics">
      <div class="admin_statistics_nav">
        <a id="admin_stats_offset_previous"  class='buttonlink icon_previous' onclick="processStatisticsPage(-1);" href="javascript:void(0);" style="float:left;"><?php echo $this->translate("Previous") ?></a>
        <a id="admin_stats_offset_next" class='buttonlink_right icon_next' onclick="processStatisticsPage(1);" href="javascript:void(0);" style="display:none;float:right;"><?php echo $this->translate("Next") ?></a>
      </div>

      <script type="text/javascript" src="<?php echo $this->layout()->staticBaseUrl ?>externals/swfobject/swfobject.js"></script>
      <script type="text/javascript">
          var prev = '<?php echo $this->prev_link ?>';
          var currentArgs = {};
          var processStatisticsFilter = function(formElement) {
            var vals = formElement.toQueryString().parseQueryString();         
            vals.offset = 0;
            buildStatisticsSwiff(vals);
            return false;
          }
          var processStatisticsPage = function(count) {
            var args = $merge(currentArgs);
            args.offset += count;
            buildStatisticsSwiff(args);
          }
          var buildStatisticsSwiff = function(args) {

            var earliest_date = '<?php echo $this->earliest_ad_date ?>';
            var startObject = '<?php echo $this->startObject ?>';

            if (args.offset < 0) {
              switch (args.period) {
                case 'ww':
                  startObject = startObject - (Math.abs(args.offset) * 7 * 86400);
                  break;

                case 'MM':
                  startObject = startObject - (Math.abs(args.offset) * 31 * 86400);
                  break;

                case 'y':
                  startObject = startObject - (Math.abs(args.offset) * 366 * 86400);
                  break;
              }
              $('admin_stats_offset_previous').setStyle('display', (startObject > earliest_date ? '' : 'none'));
            }
            else if (args.offset > 0) {
              $('admin_stats_offset_previous').setStyle('display', 'block');
            }
            else if (args.offset == 0) {
              switch (args.period) {
                case 'ww':
                  if (typeof args.prev_link != 'undefined') {
                    $('admin_stats_offset_previous').setStyle('display', (args.prev_link >= 1 ? '' : 'none'));
                  }
                  else {
                    $('admin_stats_offset_previous').setStyle('display', (startObject > earliest_date ? '' : 'none'));
                  }
                  break;

                case 'MM':
                  startObject = '<?php echo mktime(0, 0, 0, date('m', $this->startObject), 1, date('Y', $this->startObject)) ?>';
                  $('admin_stats_offset_previous').setStyle('display', (startObject > earliest_date ? '' : 'none'));
                  break;

                case 'y':
                  startObject = '<?php echo mktime(0, 0, 0, 1, 1, date('Y', $this->startObject)) ?>';
                  $('admin_stats_offset_previous').setStyle('display', (startObject > earliest_date ? '' : 'none'));
                  break;
              }
            }

            currentArgs = args;

            $('admin_stats_offset_next').setStyle('display', (args.offset < 0 ? '' : 'none'));

            var url = new URI('<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $this->url(array('module' => 'sitecredit', 'controller' => 'settings', 'action' => 'chart-data'), 'admin_default', true) ?>');

            url.setData(args);

            //$('my_chart').empty();
            swfobject.embedSWF(
                    "externals/open-flash-chart/open-flash-chart.swf",
                    "my_chart",
                    "850",
                    "400",
                    "9.0.0",
                    "expressInstall.swf",
                    {
                      "data-file": escape(url.toString()),
                      'id': 'mooo'
                    }
            );
         }

          window.addEvent('load', function() {
            buildStatisticsSwiff({
              'type': 'activity_type',
              'mode': 'all',
              'chunk': 'dd',
              'period': 'ww',
              'start': 0,
              'offset': 0,
              'ad_subject': 'ad',
              'prev_link': prev
            });
            var currentPeriod = $('period').getElement(':selected').value;
            // Remove option here. 
            $('chunk').getElements('option').invoke('remove');
            // Now add options.
            addOption( currentPeriod );
            setLabels();
          });
          function setLabels() {
              var getFormLabels = '<?php echo $this->getFormLabels; ?>';
              var getFormLabelsJson = JSON.parse( getFormLabels );
              for(var propt in getFormLabelsJson){
                $('filter_form').getElement('label.'+ propt).set('html', getFormLabelsJson[propt]);
              }
              
          }
      </script>

      <div id="my_chart">
        <center><img src='<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/core/loading.gif' style='margin:10px 0;' /></center>
      </div>
    </div>  

  <script type="text/javascript">
 function onModeChange() {
    if($('mode') && $('mode').value == 'all'){
      $('type').getParent().show();
    } else {
      $('type').getParent().hide();
    }
 }
  </script> 

<script type="text/javascript">
  // Get Json data of period and chunk.
   var periodOption = JSON.parse( '<?php echo $this->periodOption ?>');
  var chunkOption = JSON.parse( '<?php echo $this->chunkOption ?>' );
  var periodOptionKey = JSON.parse( '<?php echo $this->periodOptionKey ?>');
  var chunkOptionKey = JSON.parse( '<?php echo $this->chunkOptionKey ?>');
  $('period').addEvent('change',function(event) {
    
    var currentPeriod = this.getElement(':selected').value;
    var currentchunk = $('chunk').getElement(':selected').value;
    // Remove option here. 
    $('chunk').getElements('option').invoke('remove');
    // Now add options.
    addOption( currentPeriod, currentchunk );
  });

  function addOption( currentPeriod, currentchunk ) {
    //Add an element   
    for (var chunkKey in chunkOption ) {
      if( periodOptionKey.indexOf( currentPeriod ) >= chunkOptionKey.indexOf( chunkKey ) || chunkOptionKey.indexOf( currentPeriod ) == -1 ) {
        var newOption = new Option( chunkOption[ chunkKey ],chunkKey);
        newOption.inject($('chunk'))
      }
    }
    // Set default value
    $('chunk').value = chunkOptionKey[ 0 ] ;
  }

  </script>

  <style type="text/css">
    a.icon_next{
      padding-right: 20px;
      background-position: top right;
      background-repeat: no-repeat;
      font-weight: bold;
    } 
    .cadmc_statistics_search {
      margin-bottom: 30px;

    }
    .custom-divs {
      float: left;
      padding-left: 17px;
      margin-top: 10px;
    }
    .custom-divs-first {
      float: left;
      margin-top: 10px;
    }
      button#submit{
      margin-left: 10px;
      margin-top: 17px;
    }
    object{
          margin-top: 50px;
    }
    .label-field{
      margin-top: 5px; 
    }
  </style>
