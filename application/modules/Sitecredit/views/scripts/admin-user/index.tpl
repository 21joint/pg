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
  <p>
    Here, you can view the user's’ credits and their validity. The search box below will search through the various users, to help you get their credit information.
  </p>
</div>

<div class="search">
  <form name="credit_transaction_search_form" id="credit_transaction_search_form" method="post" class="global_form_box" action="">
   <input type="hidden" name="post_search" />

   <div>
    <label>
      <?php echo  $this->translate("User Name") ?>
    </label>
      <input type="text" id="username" name="username" value="<?php echo empty($this->username)?'':$this->username; ?>"/>
  </div>
  <div style="display: none;" id="user_id-wrapper" class="form-wrapper">
   <div id="user_id-label" class="form-label">&nbsp;</div>
   <div id="user_id-element" class="form-element">
     <input name="user_id" value="" id="user_id" type="hidden">
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
  <?php echo $this->translate(array("%s record found", "%s records found", $count), $count) ?>
</div>

<?php if( count($this->paginator) ): ?>
  <form id='credit_form' method="post" action="<?php echo $this->url();?>" >
    <table class='admin_table' width="80%">

      <thead>
        <tr>
         
          <?php $class = ( $this->order == 'displayname' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
          <th class="<?php echo $class ?>"  align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('displayname', 'ASC');"><?php echo $this->translate("User Name") ?></a></th>                  
          <?php $class = ( $this->order == 'credit' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
          <th class="<?php echo $class ?>"  align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('credit', 'ASC');"><?php echo $this->translate("Credit Values") ?></a></th>                                
          <?php $class = ( $this->order == 'start_date' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
          <th class="<?php echo $class ?>"  align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('start_date', 'ASC');"><?php echo $this->translate("Validity") ?></a></th>
          <th width="15%">Options</th>
        </tr> 

      </thead>
      <tbody>
        <?php 
                //$k=($this->pageno-1)*($this->pagecount);
        $typeString;
        foreach ($this->paginator as $item): ?>
        <tr>
          
          <td><?php echo $this->htmlLink($item->getOwner()->getHref(),$this->string()->stripTags($item->getOwner()->getTitle()), array('title' => $item->getOwner()->getTitle(), 'target' => '_blank')); ?></td>
          <td><?php echo $item->credit ?> </td>
          <td><?php 
            echo date('dS F Y ', strtotime("+$this->validity months", strtotime($item->start_date)));
            ?></td>
            <td>   <?php echo $this->htmlLink(
              array('route' => 'admin_default', 'module' => 'sitecredit', 'controller' => 'user', 'action' => 'view', 'id' => $item->user_id),
              $this->translate("View"),
              array('class' => 'smoothbox')) ?>         
            </td> 
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <br />
  </form>

  <br/>


<?php endif; ?>
<div style="clear:left;">

 <?php   echo $this->paginationControl($this->paginator, null, null, array(
  'pageAsQuery' => true,
  'query' => $this->formValues,
  ));
  ?>
</div>

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
  var currentOrderDirection = '<?php echo $this->order_direction ?>';
  var changeOrder = function (order, default_direction) {
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







