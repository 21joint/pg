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
  console.log(currentOrder);
  var currentOrderDirection = '<?php echo $this->order_direction ?>';
  var changeOrder = function (order, default_direction) {
    console.log("hii");
        // Just change direction
        if (order == currentOrder) {
          $('order_direction').value = (currentOrderDirection == 'ASC' ? 'DESC' : 'ASC');
        } else {
          $('order').value = order;
          $('order_direction').value = default_direction;
        }

        $('filter_form').submit();
      };
    </script>
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
    <?php if( count($this->navigationSubMenu) ): ?>
      <div class='seaocore_admin_tabs clr'>
        <?php
    // Render the menu
    //->setUlClass()
        echo $this->navigation()->menu()->setContainer($this->navigationSubMenu)->render()
        ?>
      </div>

    <?php endif; ?>

    <div>
      <p> Browse through the transactions made by users with their credits. The search box below will search through the various user and their transaction type. You can also use the filters ‘Time Interval’ and ‘Credit Point’ to filter the transactions. </p>
    </div>
    <div class="search transaction_search">
      <form name="credit_transaction_search_form" id="credit_transaction_search_form" method="post" class="global_form_box" action="">
       <input type="hidden" name="post_search" />
       <div> <label> User Name </label>
          <input type="text" id="username" name="username" value="<?php echo empty($this->username)?'':$this->username; ?>"/>
      </div>
      <div style="display: none;" id="user_id-wrapper" class="form-wrapper">
       <div id="user_id-label" class="form-label">&nbsp;</div>
       <div id="user_id-element" class="form-element">
         <input name="user_id" value="" id="user_id" type="hidden">
       </div>
     </div>
     <div>
      <label>
        Credit Type
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
    <label for="show">Time Interval</label>  
    <div id="show-element" class="form-element">
      <select name="show_time" id="show_time" onchange="onTimeChange()">
        <option value=""></option>
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

    echo '<label>From</label><div>';
    echo $this->FormCalendarDateTime('starttime', $starttime, array_merge(array('label' => 'From'), $attributes), $attributes['options'] );
    echo '</div>';
    ?>
  </div>     
  
  <div>
    <?php 
    $form = new Engine_Form_Element_CalendarDateTime('endtime');
    $attributes['options'] = $form->getMultiOptions();              
    $attributes['id'] = 'endtime';
    echo '<label>To</label><div>';
    echo $this->FormCalendarDateTime('endtime', $endtime, array_merge(array('label' => 'To'), $attributes), $attributes['options'] );
    echo '</div>';
    ?>
  </div> 
</div>   
</div>

<div>
  <label>Credit Values</label>
  <div>
      <input type="text" name="order_min_amount" onkeypress="return isNumberKey(event)" placeholder="Min" value="<?php echo empty($this->order_min_amount)?'':$this->order_min_amount; ?>" style="width: 80px;" />
      <input type="text" name="order_max_amount" onkeypress="return isNumberKey(event)" placeholder="Max" value="<?php echo empty($this->order_max_amount)?'':$this->order_max_amount; ?>" style="width: 80px;" />
  </div>   
</div>

<div style="margin-top:16px;">
  <button type="submit" name="search" ><?php echo $this->translate("Search") ?></button>
</div>

</form>
</div>
<div class='admin_search'>
  <?php echo $this->formFilter->render($this) ?>
</div>
<div class="mbot10">
  <?php $count = $this->paginator->getTotalItemCount() ?>
  <?php echo $this->translate(array("%s transaction found", "%s transactions found", $count), $count) ?>
</div>
<?php if( count($this->paginator) ): ?>
  <form id='credit_form' method="post" action="<?php echo $this->url();?>" >
    <table class='admin_table' width="80%">
      <thead>
        <tr>
          <?php $class = ( $this->order == 'credit_id' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
          <th rowspan="2"  align="center" class="<?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('credit_id', 'ASC');"><?php echo $this->translate("Id") ?></a></th>
          <?php $class = ( $this->order == 'displayname' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
          <th rowspan="2"  align="center" class="<?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('displayname', 'ASC');"><?php echo $this->translate("User Name") ?></a></th> 
          
          <?php $class = ( $this->order == 'type' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
          <th rowspan="2"  align="center" class="<?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('type', 'ASC');"><?php echo $this->translate("Credit Type") ?></a></th>  
          
          
          <?php $class = ( $this->order == 'credit_point' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
          <th colspan="2"  align="center" class="<?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('credit_point', 'ASC');"><?php echo $this->translate("Credit Values") ?></a></th> 
          <?php $class = ( $this->order == 'creation_date' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
          <th width ="15%" rowspan="2"  align="center" class="<?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('creation_date', 'ASC');"><?php echo $this->translate("Date") ?></a></th>
          
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

          <td align="center"><?php echo $item->credit_id ?></td>
          <td align="center"><?php echo $this->htmlLink($item->getOwner()->getHref(), $this->string()->stripTags($item->getOwner()->getTitle()), array('title' => $item->getOwner()->getTitle(), 'target' => '_blank')); ?></td>
          <td align="center">
            <?php if(!empty($this->creditTypeArray[$item->type])) 
                    echo $this->creditTypeArray[$item->type]; 
                  else  
                    echo $item->type;?></td>

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

        if (charCode != 45 && charCode != 173 && charCode > 31 && (charCode < 48 || charCode > 57) || charCode == 46) 
          return false; 
        
        return true; 
      }
    </script>
