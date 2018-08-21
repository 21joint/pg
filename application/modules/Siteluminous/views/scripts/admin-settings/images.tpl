<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: images.tpl 2014-10-09 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<script type="text/javascript">

  var SortablesInstance;
  $(window).on('load', function() {
    SortablesInstance = new Sortables('menu_list', {
      clone: true,
      constrain: false,
      handle: '.item_label',
      onComplete: function(e) {
        reorder(e);
      }
    });
  });

  var reorder = function(e) {
    var menuitems = e.parentNode.childNodes;
    var ordering = {};
    var i = 1;
    for (var menuitem in menuitems)
    {
      var child_id = menuitems[menuitem].id;

      if ((child_id != undefined))
      {
        ordering[child_id] = i;
        i++;
      }
    }
    ordering['format'] = 'json';

    // Send request
    var url = '<?php echo $this->url(array('action' => 'order')) ?>';
    var request = new Request.JSON({
      'url': url,
      'method': 'POST',
      'data': ordering,
      onSuccess: function(responseJSON) {
      }
    });

    request.send();
  }
</script>

<h2>
  <?php echo $this->translate('Responsive Luminous Theme'); ?>
</h2>

<div class='tabs'>
  <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
</div>


<h3><?php echo $this->translate('Landing Page Image Rotator'); ?></h3>
<p class="form-description">
  <?php echo $this->translate('Below you can upload / delete / manage your images. You can also set the sequence of images by dragging-and-dropping them vertically, in the order in which they should appear to members on the landing page of your website. Multiple images can be added to display them in circular manner i.e one after another.') ?>
</p>

<br />
<p>
  <a href='<?php echo $this->url(array("module" => "siteluminous", "controller" => "settings", "action" => 'add-images'), "admin_default", true) ?>' class="smoothbox buttonlink seaocore_icon_add"><?php echo $this->translate("Add New Image"); ?></a>
</p>

<br />
<?php if (COUNT($this->list)): ?>

  <div class="seaocore_admin_order_list">
    <div class="list_head">
      <div style="width:40%">
        <?php echo "Images"; ?>
      </div>

      <div style="width:20%">
        <?php echo "Enabled"; ?>
      </div>

      <div style="width:14%">
        <?php echo "Order"; ?>
      </div>

      <div style="width:20%">
        <?php echo "Options"; ?>
      </div>
    </div>
    <ul id='menu_list'>
      <?php foreach ($this->list as $item): ?>
        <li id="content_<?php echo $item->getIdentity(); ?>" class="admin_table_bold item_label">
          <input type='hidden'  name='order[]' value='<?php echo $item->getIdentity(); ?>'>

          <div style="width:40%;" class=''>
            <?php $iconSrc = Engine_Api::_()->siteluminous()->displayPhoto($item->icon_id, 'thumb.icon');
              if (!empty($iconSrc)): ?>
                <img src="<?php echo $iconSrc; ?>" />
            <?php endif; ?>
          </div>

          <div style="width:20%;" class=''>
            <a href='<?php echo $this->url(array("module" => "siteluminous", "controller" => "settings", "action" => 'enabled', 'id' => $item->getIdentity()), "admin_default", true) ?>' >
              <?php if (!empty($item->enabled)): ?>
                <img src="<?php echo $this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/approved.gif' ?>" alt="" title="Make Disabled">
              <?php else: ?>
                <img src="<?php echo $this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/disapproved.gif' ?>" alt="" title="Make Enabled">
              <?php endif; ?></a>
          </div>

          <div style="width:14%;" class=''>
            <?php echo $item->order; ?>
          </div>

          <div style="width:20%;" class=''>
            <a href='<?php echo $this->url(array("module" => "siteluminous", "controller" => "settings", "action" => 'edit-images', 'id' => $item->getIdentity()), "admin_default", true) ?>' class="smoothbox"><?php echo "Edit"; ?></a> |
            <a href='<?php echo $this->url(array("module" => "siteluminous", "controller" => "settings", "action" => 'delete', 'id' => $item->getIdentity()), "admin_default", true) ?>' class="smoothbox"><?php echo "Delete"; ?></a>
          </div>

        <?php endforeach; ?>
    </ul>
  </div>
<?php else: ?>
  <div class="tip" style="width: 100%">
    <span style="width: 100%"><?php echo $this->translate('No images Found!') ?></span>
  </div>
<?php endif; ?>
