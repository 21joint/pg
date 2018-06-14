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
<?php

$this->headScript()
->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Observer.js')
->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.js')
->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.Local.js')
->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.Request.js');
?>

<style type="text/css">
    form#credit_transaction_search_form div {
        display: inline-block;
    }
    div#global_content{
        width: 1180px; 
    }
    h2.fleft {
        float: none !important;
    }
    ul.form-errors {
        display: none;
    }
    .sd-description {
        margin-bottom: 5px;
    }
</style>

<script type="text/javascript">
  var contentAutocomplete;
  var maxRecipients = 10;

  function removeFromToValue(id, elmentValue,element) {
    // code to change the values in the hidden field to have updated values
    // when recipients are removed.
    var toValues = $(elmentValue).value;
    var toValueArray = toValues.split(",");
    var toValueIndex = "";

    var checkMulti = id.search(/,/);

    // check if we are removing multiple recipients
    if (checkMulti!=-1) {
      var recipientsArray = id.split(",");
      for (var i = 0; i < recipientsArray.length; i++){
        removeToValue(recipientsArray[i], toValueArray, elmentValue);
      }
    } else {
      removeToValue(id, toValueArray, elmentValue);
    }

    // hide the wrapper for element if it is empty
    if ($(elmentValue).value==""){
      $(elmentValue+'-wrapper').setStyle('height', '0');
      $(elmentValue+'-wrapper').setStyle('display', 'none');
    }
    $(element).disabled = false;
  }
  
  function removeToValue(id, toValueArray, elmentValue) {
    for (var i = 0; i < toValueArray.length; i++){
      if (toValueArray[i]==id) toValueIndex =i;
    }
    toValueArray.splice(toValueIndex, 1);
    $(elmentValue).value = toValueArray.join();
  }
  en4.core.runonce.add(function()
  {

   contentAutocomplete = new Autocompleter.Request.JSON('username', '<?php echo $this->url(array('module' => 'sitecredit', 'controller' => 'user', 'action' => 'getallusers'), 'admin_default', true) ?>', {
    'postVar' : 'search',
    'postData' : {'user_ids': $('user_id').value},
    'minLength': 1,
    'delay' : 250,
    'selectMode': 'pick',
    'autocompleteType': 'tag',
    'className': 'seaocore-autosuggest',
    'filterSubset' : true,
    'multiple' : false,
    'injectChoice': function(token){
      var choice = new Element('li', {
        'class': 'autocompleter-choices',
        'html': token.photo,
        'id':token.label
      });

      new Element('div', {
        'html': this.markQueryValue(token.label),
        'class': 'autocompleter-choice'
      }).inject(choice);

      this.addChoiceEvents(choice).inject(this.choices);
      choice.store('autocompleteChoice', token);
    },
    onPush : function() {
      if ($('user_id-wrapper')) {
        $('user_id-wrapper').style.display='block';
      }
      
      if( $(this.options.elementValues).value.split(',').length >= maxRecipients ) {
        this.element.disabled = true;
      }
      contentAutocomplete.setOptions({
        'postData' : {'user_ids': $('user_id').value}
      });
      
    }

  });
   contentAutocomplete.addEvent('onSelection', function(element, selected, value, input) {
    $('user_id').value = selected.retrieve('autocompleteChoice').id;
  });

 });

</script>

<script type="text/javascript">
  var currentOrder = '<?php echo $this->order ?>';
  var currentOrderDirection = '<?php echo $this->order_direction ?>';
  var changeOrder = function (order, default_direction) {
        // Just change direction
        if (order == currentOrder) {
            var test = (currentOrderDirection == 'ASC' ? 'DESC' : 'ASC');
          $('order_direction').value = (currentOrderDirection == 'ASC' ? 'DESC' : 'ASC');
        } else {
          $('order').value = order;
          $('order_direction').value = default_direction;
        }

        $('credit_transaction_search_form').submit();
      };
    </script>
    <h2>
      <?php echo $this->translate("Parental Guidance Customizations") ?>
    </h2>
    <?php if( count($this->navigation) ): ?>
      <div class='tabs'>
        <?php
    // Render the menu
    //->setUlClass()
        echo $this->navigation()->menu()->setContainer($this->navigation)->render()
        ?>
      </div>
    <?php endif; ?>
    <div class='sd_layout_left'>
    <?php if( count($this->navigationSubMenu) ): ?>
      <div class='tabs_left'>
        <?php
    // Render the menu
    //->setUlClass()
        echo $this->navigation()->menu()->setContainer($this->navigationSubMenu)->render()
        ?>
      </div>
    <?php endif; ?>
    </div>

    
    <div class="admin_table_form sd_layout_middle">       
        <div class="sd-description">
          <p> Browse through the transactions made by users with their credits. The search box below will search through the various user and their transaction type. You can also use the filters ‘Time Interval’ and ‘Credit Point’ to filter the transactions. </p>
        </div>
<div class='admin_search'>
  <?php echo $this->formFilter->render($this) ?>
</div>
<div class="mbot10">
  <?php 
  $count = $this->paginator->getTotalItemCount();
    echo $this->translate(array("%s transaction found", "%s transactions found", $count), $count); ?>
</div>
<?php if( count($this->paginator) ): ?>
  <?php $creditTable = Engine_Api::_()->getDbTable('credits', 'sdparentalguide'); ?>
  <form id='credit_form' method="post" action="<?php echo $this->url();?>" >
    <table class='admin_table' width="80%">
      <thead>
        <tr>
          <?php $class = ( $this->order == 'username' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
          <th width="5%" rowspan="2"  align="center" class="<?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('username', 'ASC');"><?php echo $this->translate("User Name") ?></a></th> 
          <?php $class = ( $this->order == 'firstname' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
          <th width="5%" rowspan="2"  align="center" class="<?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('displayname', 'ASC');"><?php echo $this->translate("First Name") ?></a></th> 
          <?php $class = ( $this->order == 'lastname' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
          <th width="5%" rowspan="2"  align="center" class="<?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('lastname', 'ASC');"><?php echo $this->translate("Last Name") ?></a></th> 
          
          <?php $class = ( $this->order == 'email' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
          <th width="5%" rowspan="2"  align="center" class="<?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('email', 'ASC');"><?php echo $this->translate("Email") ?></a></th> 
          <?php $class = ( $this->order == 'memberlevel' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
          <th width="5%" rowspan="2"  align="center" class="<?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('level_id', 'ASC');"><?php echo $this->translate("Member Level") ?></a></th> 
          
          <?php $class = ( $this->order == 'type' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
          <th width="5%" rowspan="2"  align="center" class="<?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('type', 'ASC');"><?php echo $this->translate("Credit Type") ?></a></th>  
          
          <?php $class = ( $this->order == 'topic' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
          <th width="5%" rowspan="2"  align="center" class="<?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('topic', 'ASC');"><?php echo $this->translate("Custom_Topic") ?></a></th> 
          
          <?php $class = ( $this->order == 'credit_point' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
          <th width="5%" colspan="2"  align="center" class="<?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('credit_point', 'ASC');"><?php echo $this->translate("Credit Values") ?></a></th> 
          <?php $class = ( $this->order == 'creation_date' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
          <th width ="5%" rowspan="2"  align="center" class="<?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('creation_date', 'ASC');"><?php echo $this->translate("Date") ?></a></th>
          
          <th width="5%" rowspan="2" align="center">Options</th>
        </tr>
        <tr>
          <th class="<?php echo $class ?>"  align="center">Addition</th>
          <th class="<?php echo $class ?>"  align="center">Deduction</th>
        </tr> 

      </thead>
      <tbody>

        <?php 
        
        foreach ($this->paginator as $item): ?>
        <?php if($item->credit_point > 0) : ?>
          <tr>
          <?php else : ?>
            <tr>
            <?php endif; ?>

          <td align="center"><?php echo $this->htmlLink($item->getOwner()->getHref(), $this->string()->stripTags($item->getOwner()->getTitle()), array('title' => $item->getOwner()->getTitle(), 'target' => '_blank')); ?></td>
          <td align="center">
            <?php $firstName = $creditTable->getFieldValue($item->user_id, 3);
                  if($firstName){ echo $firstName; }
            ?>
          </td>
          <td align="center">
            <?php $lastName = $creditTable->getFieldValue($item->user_id, 4);
                  if($lastName){ echo $lastName; }
            ?>
          </td>
          <td align="center">
            <?php echo $item->email; ?>
          </td>
          <td align="center">
            <?php $memberLevel = $creditTable->getUserLevel($item->level_id);
                  if($memberLevel){ echo $memberLevel; }
            ?>
          </td>
          <td align="center">
            <?php if(!empty($this->creditTypeArray[$item->type])) 
                    echo $this->creditTypeArray[$item->type]; 
                  else  
                    echo $item->type;?></td>

          <td align="center">
              <?php if(($topic = Engine_Api::_()->getItem('sdparentalguide_topic', $item->gg_topic_id))): ?>
                <?php echo $topic->getTitle(); ?>
              <?php endif; ?>
          </td>
            <?php if($item->credit_point >0 ) : ?>
              <td align="center"><?php echo $item->credit_point ?></td>
              <td align="center"></td>
            <?php else : ?>
              <td align="center"></td>
              <td align="center"><?php echo (abs($item->credit_point)); ?></td>
            <?php endif; ?>
            <td align="center"><?php
              echo date('dS F Y ', strtotime($item->creation_date)); 
             // echo date('d-m-Y', strtotime($item->creation_date)) ?>
           </td>
           <td align="center"><?php echo $this->htmlLink(
            array('route' => 'admin_default', 'module' => 'sitecredit', 'controller' => 'transaction', 'action' => 'view', 'id' => $item->credit_id),
            $this->translate("View"),
            array('class' => 'smoothbox')) ?>         
          </td> 
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</form>

<br/>

<?php endif; ?>
<div  style="clear:left;">

  <?php   echo $this->paginationControl($this->paginator, null, null, array(
    'pageAsQuery' => true,
    'query' => $this->formValues,
    ));
    ?>
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
        $("custom_time-wrapper").show();
      } else {
        $("custom_time-wrapper").hide(); 
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
        var charCode = (evt.charCode) ? evt.which : event.keyCode;

        if (charCode != 45 && charCode != 173 && charCode > 31 && (charCode < 48 || charCode > 57) || charCode == 46) 
          return false; 
        
        return true; 
      }
     
    </script>
