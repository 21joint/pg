<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
 ?>
<div class="transaction_search_container">
<div class="search transaction_search">
  <form name="credit_transaction_search_form" id="credit_transaction_search_form" method="post" class="global_form_box" action="">
   <input type="hidden" name="post_search" />
   <div>
    <label>
      <?php echo $this->translate(ucfirst($GLOBALS['credit'])." Type") ?>
    </label>
    <div id="credit-type" class="form-element">
      <select name="credit_type" id="credit_type">
        <option value=""></option>
        <?php foreach ($this->creditTypeArray as $key => $value) : ?>
           <option id="<?php echo $key ?>" value="<?php echo $key ?>"><?php echo $this->translate($value); ?></option>
        <?php endforeach; ?>
      </select>
  </div>
</div>

<div>
  <?php $options=array('day'=>'Today','weekly'=>'Last 7 Days','range'=>'Specific Time Interval')?>
  <div>
  <label for="show"><?php echo  $this->translate("Time Interval") ?></label>     
  <div id="show-element" class="form-element">
  <select name="show_time" id="show_time" onchange="onTimeChange()">
   <option value=" "></option>
  <?php foreach($options as $key => $value): 
  if($this->show_time == $key) :?> 
  <option value="<?php echo $key ?>" selected="selected" ><?php echo $value ?></option>
  <?php else : ?>
  <option value="<?php echo $key ?>"><?php echo $value ?></option>
  <?php endif; endforeach; ?>
  </select>
  </div>
  </div>    

  <div id="custom_time" display="none">
  <div>
<?php 
  //MAKE THE STARTTIME AND ENDTIME FILTER
  $attributes = array();
  $attributes['dateFormat'] = 'ymd';

  $form = new Engine_Form_Element_CalendarDateTime('starttime');
  $attributes['options'] = $form->getMultiOptions();
  $attributes['id'] = 'starttime';

  $starttime['date'] = $this->starttime;
  $endtime['date'] = $this->endtime;

  echo '<label>From</label><div class="form-element">';
  echo $this->FormCalendarDateTime('starttime', $starttime, array_merge(array('label' => 'From'), $attributes), $attributes['options'] );
  echo '</div>';
?>     
</div>
<div>
<?php 
  $form = new Engine_Form_Element_CalendarDateTime('endtime');
  $attributes['options'] = $form->getMultiOptions();              
  $attributes['id'] = 'endtime';
  echo '<label>To</label><div class="form-element">';
  echo $this->FormCalendarDateTime('endtime', $endtime, array_merge(array('label' => 'To'), $attributes), $attributes['options'] );
  echo '</div>';
?>
</div>  
  </div>  
 
</div>
 <div class="credits_points">
 <label><?php echo  $this->translate(ucfirst($GLOBALS['credit'])." Values") ?></label>
  <div class="form-element">
      <input type="text" name="order_min_amount" onkeypress="return isNumberKey(event)" placeholder="Min" value="<?php echo empty($this->order_min_amount)?'':$this->order_min_amount; ?>" />

      <input type="text" name="order_max_amount" onkeypress="return isNumberKey(event)" placeholder="Max" value="<?php echo empty($this->order_max_amount)?'':$this->order_max_amount; ?>" />
  </div> 
</div>

  <div class="form-element">
    <button type="submit" name="search" ><?php echo $this->translate("Search") ?></button>
  </div>

</form>
</div>

<div class='admin_search mbot10'>
  <?php echo $this->formFilter->render($this) ?>
</div>

<div class="mbot10">
  <?php $count = $this->paginator->getTotalItemCount() ?>
  <?php echo $this->translate(array("%s record found", "%s records found", $count), $count) ?>
</div>

<div class="transaction_table">
<?php if( count($this->paginator) ): ?>
  <form id='credit_form' method="post" action="<?php echo $this->url();?>" >
      <table width="100%" class='admin_table'>
        <thead>
         <tr>

          <th width="5%" rowspan="2"><?php echo $this->translate("Id") ?></th>     
          
          
          <th width="50%" rowspan="2"><?php echo $this->translate(ucfirst($GLOBALS['credit'])." Type") ?></th>  
             
          
          <th width="22%" colspan="2"><?php echo $this->translate(ucfirst($GLOBALS['credit'])." Values") ?></th>
          

          <th width ="17%" rowspan="2"><?php echo $this->translate("Date") ?></th>
          
          <th width="6%" rowspan="2"><?php echo $this->translate("Options") ?></th>
          </tr>
         <tr>
           <th><?php echo "<img src =".$this->layout()->staticBaseUrl.'application/modules/Sitecredit/externals/images/addition.png'." />";?></th>
           <th><?php echo "<img src =".$this->layout()->staticBaseUrl.'application/modules/Sitecredit/externals/images/deduction.png'." />";?></th>
         </tr> 
        </thead>
        <tbody id="credit_transaction_div" >
  
          <?php 
          $typeString;
          foreach ($this->paginator as $item): ?>
          <?php if($item->credit_point > 0) : ?>
            <tr>
          <?php else : ?>
            <tr>
          <?php endif; ?>
          
            <td><?php echo $item->credit_id?></td>
            <td>
              <?php switch($item->type) {
                case 'activity_type' : $activity=Engine_Api::_()->getDbtable('activitycredits','sitecredit')->fetchRow(array('activitycredit_id = ?' => $item->type_id));
  
                $column='language_'.$this->language;
  
                if(empty($activity->$column)){
                  $activity_type = $this->translate('ADMIN_ACTIVITY_TYPE_' . strtoupper($activity->activity_type));
                  if(!empty($activity_type)){
                    $activity_type = str_replace("(subject)","",$activity_type);
                    $activity_type = str_replace("(object)","",$activity_type);
                    echo $activity_type;
                    }
                    }
                else {
                  echo $activity->$column;
                }
                
               
               $typeString="Added";
               break;
               default :  if(!empty($this->creditTypeArray[$item->type])) 
                            echo $this->translate($this->creditTypeArray[$item->type]); 
                          else  
                            echo $item->type;               
             }?>
              </td>
              <?php if($item->credit_point >0 ) : ?>
              <td><?php echo $item->credit_point ?></td>
              <td></td>
              <?php else : ?>
              <td></td>
              <td><?php echo (abs($item->credit_point)); ?></td>
              <?php endif; ?>
              <td><?php echo date('dS F Y ', strtotime($item->creation_date));?></td>
                <td><a onclick = "openLinkSmoothbox(<?php echo $item->credit_id ; ?>)" style="cursor:pointer;" ><?php echo "<img src =".$this->layout()->staticBaseUrl.'application/modules/Sitecredit/externals/images/view.png'." />";?></a>       
             </td> 
           </tr>
         <?php endforeach; ?>
       </tbody>
     </table>
   </form>
    <?php if (empty($this->is_ajax)) : ?>
            <div class = "seaocore_view_more mtop10" id="seaocore_view_more">
                <?php echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array('id' => '', 'class' => 'buttonlink icon_viewmore')); ?>
            </div>
            <div class="seaocore_view_more" id="loding_image" style="display: none;">
                <img src='<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/core/loading.gif' style='margin-right: 5px;' />
                <?php echo $this->translate("Loading ...") ?>
            </div>
            <div id="hideResponse_div"> </div>
    <?php endif; ?>   
<?php endif; ?>
</div>

</div>
<script type="text/javascript">
window.addEvent('domready',function () { 

          if(<?php echo empty($this->credit_type)?0:1;?>) {
           $('<?php echo $this->credit_type ?>').selected=true;
          }
            onTimeChange();
         });
function onTimeChange(){
    var e = document.getElementById("show_time");
    var strUser = e.options[e.selectedIndex].value;
    if(strUser=="range"){
        $("custom_time").show();
        } else {
        $("custom_time").hide(); 
    }
}
</script> 
<?php
$dateFormat = $this->locale()->useDateLocaleFormat();
$calendarFormatString = trim(preg_replace('/\w/', '$0/', $dateFormat), '/');
$calendarFormatString = str_replace('y', 'Y', $calendarFormatString);
?>
<script type="text/javascript">
    seao_dateFormat = '<?php echo $this->locale()->useDateLocaleFormat(); ?>';
    var showMarkerInDate = "<?php echo $this->showMarkerInDate ?>";
    en4.core.runonce.add(function()
    {
        en4.core.runonce.add(function init()
        {
            monthList = [];
            myCal = new Calendar({'start_cal[date]': '<?php echo $calendarFormatString; ?>', 'end_cal[date]': '<?php echo $calendarFormatString; ?>'}, {
                classes: ['event_calendar'],
                pad: 0,
                direction: 0
            });
        });
    });

    var cal_starttime_onHideStart = function() {
        if (showMarkerInDate == 0)
            return;
        var cal_bound_start = seao_getstarttime(document.getElementById('startdate-date').value);
        // check end date and make it the same date if it's too
        cal_endtime.calendars[0].start = new Date(cal_bound_start);
        // redraw calendar
        cal_endtime.navigate(cal_endtime.calendars[0], 'm', 1);
        cal_endtime.navigate(cal_endtime.calendars[0], 'm', -1);
    }
    var cal_endtime_onHideStart = function() {
        if (showMarkerInDate == 0)
            return;
        var cal_bound_start = seao_getstarttime(document.getElementById('endtime-date').value);
        // check start date and make it the same date if it's too
        cal_starttime.calendars[0].end = new Date(cal_bound_start);
        // redraw calendar
        cal_starttime.navigate(cal_starttime.calendars[0], 'm', 1);
        cal_starttime.navigate(cal_starttime.calendars[0], 'm', -1);
    }

    en4.core.runonce.add(function() {
        cal_starttime_onHideStart();
        cal_endtime_onHideStart();
    });

    window.addEvent('domready', function() {
        if ($('starttime-minute') && $('endtime-minute')) {
            $('starttime-minute').destroy();
            $('endtime-minute').destroy();
        }
        if ($('starttime-ampm') && $('endtime-ampm')) {
            $('starttime-ampm').destroy();
            $('endtime-ampm').destroy();
        }
        if ($('starttime-hour') && $('endtime-hour')) {
            $('starttime-hour').destroy();
            $('endtime-hour').destroy();
        }

        if ($('calendar_output_span_starttime-date')) {
            $('calendar_output_span_starttime-date').style.display = 'none';
        }

        if ($('calendar_output_span_endtime-date')) {
            $('calendar_output_span_endtime-date').style.display = 'none';
        }

        if ($('starttime-date')) {
            $('starttime-date').setAttribute('type', 'text');
        }

        if ($('endtime-date')) {
            $('endtime-date').setAttribute('type', 'text');
        }

    });

    function isNumberKey(evt) { 
          var charCode = (evt.charCode) ? evt.which : event.keyCode

            if ( charCode != 45 && charCode != 173 && charCode > 31 && (charCode < 48 || charCode > 57) || charCode == 46 ) 
              return false; 
             
         return true; 
      } 
</script>



<?php if (empty($this->is_ajax)) : ?>
        <script type="text/javascript">
            function viewMoreTransactions()
            {
                var totalCount = '<?php echo $this->paginator->count(); ?>';
                var currentPageNumber = '<?php echo $this->paginator->getCurrentPageNumber(); ?>';
                if(totalCount == currentPageNumber) return false;

                $('seaocore_view_more').style.display = 'none';
                $('loding_image').style.display = '';
                var params = {
                    requestParams:<?php echo json_encode($this->params) ?>
                };
                en4.core.request.send(new Request.HTML({
                    method: 'get',
                    'url': en4.core.baseUrl + 'widget/index/mod/sitecredit/name/browse-transaction',
                    data: $merge(params.requestParams, {
                        format: 'html',
                        subject: en4.core.subject.guid,
                        page: getNextPage(),
                        isajax: 1,
                        loaded_by_ajax: true
                    }),
                    evalScripts: true,
                    onSuccess: function (responseTree, responseElements, responseHTML, responseJavaScript) {
                        $('hideResponse_div').innerHTML = responseHTML;
                        var videocontainer = $('hideResponse_div').getElement('#credit_transaction_div').innerHTML;
                        $('credit_transaction_div').innerHTML = $('credit_transaction_div').innerHTML + videocontainer;
                        $('loding_image').style.display = 'none';
                        $('hideResponse_div').innerHTML = "";
                    }
                }));
                return false;
            }
        </script>
    <?php endif; ?>

    <?php if ($this->showContent == 3): ?>
        <script type="text/javascript">
            en4.core.runonce.add(function () {
                hideViewMoreLink('<?php echo $this->showContent; ?>');
            });
        </script>
    <?php elseif ($this->showContent == 2): ?>
        <script type="text/javascript">
            en4.core.runonce.add(function () {
                hideViewMoreLink('<?php echo $this->showContent; ?>');
            });
        </script>
    <?php else: ?>
        <script type="text/javascript">
            en4.core.runonce.add(function () {
                $('seaocore_view_more').style.display = 'none';
            });
        </script>
          <?php echo $this->paginationControl($this->paginator, null, null, array(
        'pageAsQuery' => true,
        'query' => $this->formValues,
      )); ?>
    <?php endif; ?>
    <script type="text/javascript">

        function getNextPage() {
            return <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() + 1) ?>
        }

        function hideViewMoreLink(showContent) {
            if (showContent == 3) {
                $('seaocore_view_more').style.display = 'none';
                var totalCount = '<?php echo $this->paginator->count(); ?>';
                var currentPageNumber = '<?php echo $this->paginator->getCurrentPageNumber(); ?>';
                function doOnScrollLoadChannel()
                { 
                   currentPageNumber = '<?php echo $this->paginator->getCurrentPageNumber(); ?>';
                    if (typeof ($('seaocore_view_more').offsetParent) != 'undefined') {
                        var elementPostionY = $('seaocore_view_more').offsetTop;
                    } else {
                        var elementPostionY = $('seaocore_view_more').y;
                    }
                    if (elementPostionY <= window.getScrollTop() + (window.getSize().y - 40)) {

                        if ((totalCount != currentPageNumber) && (totalCount != 0))
                            viewMoreTransactions();
                    }
                }
                window.onscroll = doOnScrollLoadChannel;

            } else if (showContent == 2) {
                var view_more_content = $('seaocore_view_more');
                view_more_content.setStyle('display', '<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() || $this->totalCount == 0 ? 'none' : '' ) ?>');
                view_more_content.removeEvents('click');
                view_more_content.addEvent('click', function () {
                    viewMoreTransactions();
                });
            }
        }

        function openLinkSmoothbox(id){
          var url = en4.core.baseUrl+'sitecredit/index/view/id/'+id;
          Smoothbox.open(url);
        }
        
</script>
