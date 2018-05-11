<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manage.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<h2 class="fleft">
  Credits, Reward Points and Virtual Currency - User Engagement Plugin
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
  <div>
    <p>Here, you can manage the various badges available on your website. You can also add a new badge via ‘Add a Badges’ pop-up.</p>
  </div>  
  <?php if(empty($this->badgeAvailable)): ?>
    <div class="tip">
      <span>
        There is no badge added by you yet.
      </span>
    </div>
    <div>
      <?php 
      echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitecredit', 'controller' => 'badge', 'action' => 'add-badge'),'Add a Badge', array('class' => 'smoothbox link_button'));
      ?>
    </div>
    <?php 
    return;
    endif; 
    ?>
<br/>
  <div class="mbot10">
  <?php 
  echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitecredit', 'controller' => 'badge', 'action' => 'add-badge'), 'Add a Badge', array('class' => 'smoothbox link_button'));
  ?>
</div>  
    <div class="search">
      <form name="credit_upgrade_request_search_form" id="credit_upgrade_request_search_form" method="post" class="global_form_box" action="">
       <input type="hidden" name="post_search" />
       <div>
        <label>
          Title
        </label>
        <?php if( empty($this->username)):?>
          <input type="text" id="username" name="username" /> 
        <?php else: ?>
          <input type="text" id="username" name="username" value="<?php echo $this->username ?>"/>
        <?php endif;?>
      </div>
      <div>
        <label>Credit Values</label>
        <div>
          <?php if( $this->order_min_amount == ''):?>
            <input type="text" name="order_min_amount" onkeypress="return isNumberKey(event)" placeholder="min"  /> 
          <?php else: ?>
            <input type="text" name="order_min_amount" onkeypress="return isNumberKey(event)" placeholder="min" value="<?php echo $this->order_min_amount ?>" />
          <?php endif;?>
          
          <?php if( $this->order_max_amount == ''):?>
            <input type="text" name="order_max_amount" onkeypress="return isNumberKey(event)" placeholder="max"  /> 
          <?php else: ?>
            <input type="text" name="order_max_amount" onkeypress="return isNumberKey(event)" placeholder="max" value="<?php echo $this->order_max_amount ?>" />
          <?php endif;?>
        </div>   
      </div>
      <div style="margin-top:16px;">
        <button type="submit" name="search" >Search</button>
      </div>
    </form>
  </div>

<?php endif; ?>
<div class='admin_search'>
  <?php echo $this->formFilter->render($this) ?>
</div>

<div class="mbot10">
  <?php $count = $this->paginator->getTotalItemCount() ?>
  <?php echo $this->translate(array("%s record found", "%s records found", $count), $count) ?>
</div>
<?php if( count($this->paginator) ): ?>
  <form id='badge_form' method="post" action="<?php echo $this->url();?>">
    <table class='admin_table'>

      <thead>
       <tr>
        <?php $class = ( $this->order == 'badge_id' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
        <th class="<?php echo $class ?>"  align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('badge_id', 'ASC');">Id</a></th>
        
        <th class="<?php echo $class ?>"  align="center" ></th>
        
        <?php $class = ( $this->order == 'title' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
        <th class="<?php echo $class ?>"  align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('title', 'ASC');">Title</a></th>
        
        <?php $class = ( $this->order == 'credit_count' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
        <th class="<?php echo $class ?>"  align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('credit_count', 'ASC');">Credit Values</a></th>
        
        <?php $class = ( $this->order == 'creation_date' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
        <th class="<?php echo $class ?>" align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('creation_date', 'ASC');">Date</a>   </th>
        
        <th align="center">Options</th>
        
      </tr> 
    </thead>
    <tbody>

      <?php 
      $k=0;
      foreach ($this->paginator as $item): ?>
      <tr>
        <td><?php echo $item->badge_id ?></td>
        <td>   <div class="photo">
          <?php echo $this->itemPhoto($item, 'thumb.icon')?>
        </div> </td>
        <td><?php echo $item->title ?></td>
        <td><?php echo $item->credit_count ?></td>
        <td><?php echo date('dS F Y ', strtotime($item->creation_date));//date('d-m-Y', strtotime($item->creation_date)) ?></td>
        <td>   
          <?php echo $this->htmlLink(
            array('route' => 'default', 'module' => 'sitecredit', 'controller' => 'admin-badge', 'action' => 'view-detail', 'id' => $item->badge_id),
            "Details",
            array('class' => 'smoothbox')) ?>  | 
          <?php echo $this->htmlLink(
            array('route' => 'default', 'module' => 'sitecredit', 'controller' => 'admin-badge', 'action' => 'edit', 'id' => $item->badge_id),
            "Edit",
            array('class' => 'smoothbox')) ?>   |
          <?php echo $this->htmlLink(
            array('route' => 'default', 'module' => 'sitecredit', 'controller' => 'admin-badge', 'action' => 'delete', 'id' => $item->badge_id),
            "Delete",
            array('class' => 'smoothbox')) ?>        
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
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
      function isNumberKey(evt) { 
        var charCode = (evt.charCode) ? evt.which : event.keyCode

        if (charCode > 31 && (charCode < 48 || charCode > 57) || charCode == 46) 
          return false; 
        
        return true; 
      } 
    </script>