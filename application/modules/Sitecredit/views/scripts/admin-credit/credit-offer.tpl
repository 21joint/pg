<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: credit-offer.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<h2 class="fleft">
  <?php echo $this->translate('Credits, Reward Points and Virtual Currency - User Engagement Plugin');?>
</h2>
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
    <?php if( count($this->navigation) ): ?>
      <div class='seaocore_admin_tabs clr'>
        <?php
    // Render the menu
    //->setUlClass()
        echo $this->navigation()->menu()->setContainer($this->navigation)->render()
        ?>
      </div>

    <?php endif; ?>

    <h3>
      <?php echo $this->translate("Credit Offers") ?>
    </h3>

    <div class="mbot10">
      <p>
        Here, you can view list of offers which can be availed by your site users to purchase the credits. You can also add a new offer from ‘Add an Offer’ pop-up.
      </p>
    </div>
    <div class="mbot10">
      <?php 
      echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitecredit', 'controller' => 'credit', 'action' => 'addoffer'), $this->translate('Add an Offer'), array('class' => 'smoothbox link_button'));
      ?>
    </div>
    <div class="search">
      <form name="credit_upgrade_request_search_form" id="credit_upgrade_request_search_form" method="post" class="global_form_box" action="">
       <input type="hidden" name="post_search" />

       <div>
        <label>Credit Values</label>
        <div>
            <input type="text" name="order_min_amount" onkeypress="return isNumberKey(event)" placeholder="Min" value="<?php echo empty($this->order_min_amount)?'':$this->order_min_amount; ?>" />

            <input type="text" name="order_max_amount" onkeypress="return isNumberKey(event)" placeholder="Max" value="<?php echo empty($this->order_max_amount)?'':$this->order_max_amount; ?>" />

        </div>   
      </div>

      <div>
        <label>Amount</label>
        <div>
            <input type="text" name="min_amount" onkeypress="return isNumberDotKey(event)" placeholder="Min" value="<?php echo empty($this->min_amount)?'':$this->min_amount; ?>" />
            <input type="text" name="max_amount" onkeypress="return isNumberDotKey(event)" placeholder="Max" value="<?php echo empty($this->max_amount)?'':$this->max_amount; ?>" />
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
      <table class='admin_table'>

        <thead>
         <tr>
          <?php $class = ( $this->order == 'offer_id' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
          <th class="<?php echo $class ?>"  align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('offer_id', 'ASC');"><?php echo $this->translate("Id") ?></a></th>

          <?php $class = ( $this->order == 'value' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
          <th class="<?php echo $class ?>"  align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('value', 'ASC');"><?php echo $this->translate("Amount") ?></a></th>
          
          <?php $class = ( $this->order == 'credit_point' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
          <th class="<?php echo $class ?>"  align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('credit_point', 'ASC');"><?php echo $this->translate("Credits") ?></a></th>
          <?php $class = ( $this->order == 'expiry_date' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
          <th class="<?php echo $class ?>"  align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('expiry_date', 'ASC');"><?php echo $this->translate("Expiry Date") ?></a></th>    
          <?php $class = ( $this->order == 'creation_date' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
          <th class="<?php echo $class ?>"  align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('creation_date', 'ASC');"><?php echo $this->translate("Date") ?></a></th>
          
          
          <th>Options</th>
        </tr> 

      </thead>
      <tbody>

        <?php 
        
        foreach ($this->paginator as $item): ?>
        <tr>
          <td><?php echo $item->offer_id ?></td>
          <td><?php echo $item->value." ".Engine_Api::_()->getApi('core', 'sitecredit')->getCurrencySymbol();?></td>
          <td><?php echo $item->credit_point ?></td>
          <?php if($item->end_date) : ?>
            <td>No Specific end date </td>
          <?php else : ?>
            <td><?php echo date('dS F Y ', strtotime($item->expiry_date)); //echo date('d-m-Y', strtotime($item->expiry_date)) ?></td>
          <?php endif; ?>
          <td><?php echo date('dS F Y ', strtotime($item->creation_date)); //echo date('d-m-Y', strtotime($item->creation_date)) ?></td>
          
          <td>  <?php echo $this->htmlLink(
            array('route' => 'default', 'module' => 'sitecredit', 'controller' => 'admin-credit', 'action' => 'edit', 'id' => $item->offer_id),
            $this->translate("Edit"),
            array('class' => 'smoothbox')) ?>  | 
          <?php echo $this->htmlLink(
            array('route' => 'default', 'module' => 'sitecredit', 'controller' => 'admin-credit', 'action' => 'delete', 'id' => $item->offer_id),
            $this->translate("Delete"),
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
<div  style="clear:left;">

  <?php   echo $this->paginationControl($this->paginator, null, null, array(
    'pageAsQuery' => true,
    'query' => $this->formValues,
    ));
    ?>
  </div>
  <script type="text/javascript">

    function isNumberKey(evt) { 
      var charCode = (evt.charCode) ? evt.which : event.keyCode

      if (charCode > 31 && (charCode < 48 || charCode > 57) || charCode == 46) 
        return false; 
      
      return true; 
    } 
    function isNumberDotKey(evt){
      var charCode = (evt.charCode) ? evt.which : event.keyCode

      if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 46) 
       return false; 
     else { 
       var len = document.getElementById("txtChar").value.length; 
       var index = document.getElementById("txtChar").value.indexOf('.'); 

       if (index > 0 && charCode == 46) { 
         return false; 
       } 
       if (index >0 || index==0) { 
         var CharAfterdot = (len + 1) - index; 
         if (CharAfterdot > 3) { 

           return false; 
         } 

       }

     } 
     return true; 
   }    
 </script>