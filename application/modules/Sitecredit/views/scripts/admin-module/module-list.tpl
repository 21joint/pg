<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: module-list.tpl 2014-05-26 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

?>
<?php
$baseUrl = $this->layout()->staticBaseUrl;
$this->headLink()->appendStylesheet($baseUrl . 'application/modules/Sitecredit/externals/styles/style_sitecredit.css');
?>
<h2><?php echo 'Credits, Reward Points and Virtual Currency - User Engagement Plugin'; ?></h2>
<?php if( count($this->navigation) ): ?>
  <div class='seaocore_admin_tabs clr'>
    <?php
    // Render the menu
    //->setUlClass()
    echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>

<?php endif; ?>
<br />
<h3>
  <?php echo $this->translate("Modules List") ?>
</h3>
<p>
  <?php echo $this->translate('Here, you can manage various modules to be visible on ‘Earn Credits’ page at user’s side. When you select the module you wish to edit, you can change its title and whether you want to merge two module’s activities and show them under one module item. This is beneficial in case you don’t want to show various extensions of a module to your site users instead you want all the extensions and their respective module as a single entity. You can also drag these items up and down to change their order.'); ?>
</p>
<br/>
<?php $info_string = null;?>
<ul class="admin_menus_items sm_admin_menus_items" id='menu_list_1'>
  <?php
    $tempSubMenuCount = 1;

  foreach ($this->menuItems as $menuItemArray): 
    if ($menuItemArray->parent_id != 0){
      continue;
    } else {
      $childArray= Engine_Api::_()->sitecredit()->buildParent($this->menuItems,$menuItemArray);
    }
    
  ?>
  <li class="admin_menus_item" id="admin_menus_item_<?php echo $menuItemArray->name ?>"  >
    <span class="item_wrapper" style="width: 300px !important;">
      <span class="item_options">
        <?php
          $tempURL = $this->url(array('controller'=>'module','action' => 'edit', 'addType' => 'parent', 'id' => $menuItemArray->modulelist_id), "admin_default", false);
            echo "<a href='javascript:void(0);' onClick='openSmoothbox(\"" . $tempURL . "\", $menuItemArray->modulelist_id)'>" . 'edit' . "</a> | ";
            $tempDeleteURL = $this->url(array('action' => 'disable', 'enabled' => $menuItemArray->enabled, 'id' => $menuItemArray->modulelist_id), "admin_default", false);
              if($menuItemArray->enabled)
                echo "<a  href='". $tempDeleteURL ."'>" . 'disable' . "</a>";
              else
                echo "<a  href='". $tempDeleteURL ."'>" . 'enable' . "</a>";?>
      </span>
      <span class="item_label1">
        <?php echo $menuItemArray->label; ?>
      </span>
      <span class="item_url">
        <?php
            echo '<a>(variable)</a>';
        ?>
      </span>
    </span>

<?php if(!empty($childArray)): ?>
 <ul class="admin_menus_item" id='sub_menu_list_<?php echo $tempSubMenuCount++; ?>' style="padding-left: 20px;">
<?php foreach($childArray as $subMenuItem):?>
  <li class="admin_menus_item<?php if (isset($subMenuItem['enabled']) && !$subMenuItem['enabled']): echo ' disabled'; endif; ?>" id="admin_menus_item_<?php echo $subMenuItem['name'] ?>" style="padding-top: 35px" >
    <span class="item_wrapper" style="width: 300px !important;">

      <span class="item_options">
        <?php if (isset($menuItemArray->enabled) && $menuItemArray->enabled): ?>
        <?php
          $childCount = COUNT($subMenuItemArray);
          $tempURL = $this->url(array('action' => 'edit', 'addType' => 'child', 'id' => $subMenuItem['modulelist_id']), "admin_default", false);
          echo "<a href='javascript:void(0);' onClick='openSmoothbox(\"" . $tempURL . "\",".$subMenuItem['modulelist_id'].")'>" . 'edit' . "</a> | ";
        ?>

      <?php $tempDeleteURL = $this->url(array('action' => 'disable', 'enabled' => $subMenuItem['enabled'], 'id' => $subMenuItem['modulelist_id']), "admin_default", false);
            if($subMenuItem['enabled'])
              echo "<a  href='". $tempDeleteURL ."'>" . 'disable' . "</a>";
            else
              echo "<a  href='". $tempDeleteURL ."'>" . 'enable' . "</a>";?>
        <?php else:?>
        <?php echo 'parent disabled';?>
        <?php endif;?>
      </span>
     <span class="item_label2">
        <?php echo $subMenuItem['label']; ?>
      </span>
      <span class="item_url">
      <?php
          echo '<a>(variable)</a>';
      ?>
      </span>
    </span>
</li>
<?php endforeach;?>
</ul>
<?php endif;?>
</li>
<?php 
  endforeach;

?>
</ul>

<script type="text/javascript">
  
    new Sortables('menu_list_1', {
        clone: true,
        constrain: false,
        handle: '.item_label1',
        onComplete: function(e) {
                reorder(e);
        }
    });
    
<?php for ($tempSubVar = 1; $tempSubVar <= $tempSubMenuCount; $tempSubVar++): ?>
          new Sortables('sub_menu_list_<?php echo $tempSubVar; ?>', {
              clone: true,
              constrain: false,
              handle: '.item_label2',
              onComplete: function(e) {
                  reorder(e.parentNode.parentNode);
              }
          });
<?php endfor; ?>

    var SortablesInstance;
    window.addEvent('domready', function() {
        $$('.item_label1').addEvents({
            mouseover: showPreview,
            mouseout: showPreview
        });
        
        $$('.item_label2').addEvents({
            mouseover: showPreview,
            mouseout: showPreview
        });
    });

    var showPreview = function(event) {
        try {
            element = $(event.target);
            element = element.getParent('.admin_menus_item').getElement('.item_url');
            if( event.type == 'mouseover' ) {
                element.setStyle('display', 'block');
            } else if( event.type == 'mouseout' ) {
                element.setStyle('display', 'none');
            }
        } catch( e ) {
        }
    }
    var reorder = function(e) {

        var menuitems = e.parentNode.getElements('li');
        var ordering = {};
        var i = 1;
        for (var menuitem in menuitems)
        {
            var child_id = menuitems[menuitem].id;
            if ((child_id != undefined) && (child_id.substr(0, 5) == 'admin'))
            {
                ordering[child_id] = i;
                i++;
            }
        }
        // Send request
        var url = '<?php echo $this->url(array('module' => 'sitecredit', 'controller' => 'module', 'action' => 'order')) ?>';
        var request = new Request.JSON({
            'url' : url,
            'method' : 'POST',
            'data' : ordering,
            onSuccess : function(responseJSON) {
            }
        });
        
        request.send();
        
    }
    function ignoreDrag()
    {
        event.stopPropagation();
        return false;
    }
  
    function openSmoothbox(url, id){
      Smoothbox.open(url);
    }
</script>