<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: install.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<!--ADD NAVIGATION-->
<?php include APPLICATION_PATH . '/application/modules/Sitereaction/views/scripts/_adminNav.tpl'; ?>

<h3>
  <?php echo $this->translate('Manage Sticker Store'); ?>
</h3>
<p>
  <?php echo $this->translate('Below, you can manage your sticker collection. Drag and drop collections to arrange their sequence. You can assign a higher positioning to the collections who are more important for your community. You can also add a new collection below and set it\'s duration of visibility in Sticker Store.'); ?> 
</p>
<br />
<div>
  <a href="<?php echo $this->url(array('action' => 'manage-search')) ?>" class="buttonlink seaocore_icon_edit" title="<?php echo $this->translate('Manage Search List'); ?>"><?php echo $this->translate('Manage Search List'); ?></a>
  <a href="<?php echo $this->url(array('action' => 'create')) ?>" class="buttonlink seaocore_icon_add" title="<?php echo $this->translate('Create Sticker Collection'); ?>"><?php echo $this->translate('Create Sticker Collection'); ?></a>
</div>
<br />

<div class="seaocore_admin_order_list">
  <div class="list_head">
    <div style="width:10%">
      <?php echo $this->translate("Icon"); ?>
    </div>
    <div style="width:35%">
      <?php echo $this->translate("Title"); ?>
    </div>
    <div style="width:10%">
      <?php echo $this->translate("Count"); ?>
    </div>
    <div style="width:10%">
      <?php echo $this->translate("Default"); ?>
    </div>
    <div style="width:10%">
      <?php echo $this->translate("Enable"); ?>
    </div>
    <div style="width:15%">
      <?php echo $this->translate("Options"); ?>
    </div>
  </div>

  <form id='saveorder_form' method='post' action='<?php echo $this->url(array('action' => 'update-order')) ?>'>
    <input type='hidden'  name='order' id="order" value=''/>
    <div id='order-element'>
      <ul>
        <?php foreach ($this->collections as $item) : ?>
          <li>
            <input type='hidden'  name='order[]' value='<?php echo $item->getIdentity(); ?>'>
            <div style="width:10%;" class='admin_table_bold uploaded_stickers'>
              <?php
              echo $this->itemPhoto($item, 'thumb.small-icon', '', array(
                'align' => 'center'))
              ?>
            </div>
            <div style="width:35%;" class='admin_table_bold'>
              <?php echo $item->getTitle(true); ?>
            </div>
            <div style="width:10%;" class='admin_table_bold'>
              <?php echo $item->count() ?>
            </div>
            <div style="width:10%;" class='admin_table_bold'>
              <?php echo $item->include ? 'Yes' : 'No' ?>
            </div>
            <div style="width:10%;" class='admin_table_bold'>
              <?php echo $item->enabled ? 'Yes' : 'No' ?>
            </div>
            <div style="width:15%;">
              <a href='<?php echo $this->url(array('action' => 'manage', 'collection_id' => $item->getIdentity())) ?>'>
                <?php echo $this->translate("Manage") ?>
              </a>
              |
              <a href='<?php echo $this->url(array('action' => 'edit', 'collection_id' => $item->getIdentity())) ?>'>
                <?php echo $this->translate("Edit") ?>
              </a>
              | <a href='<?php echo $this->url(array('action' => 'delete', 'collection_id' => $item->getIdentity())) ?>' class="smoothbox">
                <?php echo $this->translate("Delete") ?>
              </a>
            </div>
          </li>
        <?php endforeach; ?>	
      </ul>
    </div>
  </form>
  <br />
  <button onClick="javascript:saveOrder(true);" type='submit'>
    <?php echo $this->translate("Save Order") ?>
  </button>
</div>
<script type="text/javascript">

  var saveFlag = false;
  var origOrder;
  var changeOptionsFlag = false;

  function saveOrder(value) {
    saveFlag = value;
    var finalOrder = [];
    var li = $('order-element').getElementsByTagName('li');
    for (i = 1; i <= li.length; i++)
      finalOrder.push(li[i]);
    $("order").value = finalOrder;

    $('saveorder_form').submit();
  }
  window.addEvent('domready', function() {
    //         We autogenerate a list on the fly
    var initList = [];
    var li = $('order-element').getElementsByTagName('li');
    for (i = 1; i <= li.length; i++)
      initList.push(li[i]);
    origOrder = initList;
    var temp_array = $('order-element').getElementsByTagName('ul');
    temp_array.innerHTML = initList;
    new Sortables(temp_array);
  });

  window.onbeforeunload = function(event) {
    var finalOrder = [];
    var li = $('order-element').getElementsByTagName('li');
    for (i = 1; i <= li.length; i++)
      finalOrder.push(li[i]);



    for (i = 0; i <= li.length; i++) {
      if (finalOrder[i] != origOrder[i])
      {
        changeOptionsFlag = true;
        break;
      }
    }

    if (changeOptionsFlag == true && !saveFlag) {
      var answer = confirm("<?php echo $this->string()->escapeJavascript($this->translate("A change in the order of the tabs has been detected. If you click Cancel, all unsaved changes will be lost. Click OK to save change and proceed.")); ?>");
      if (answer) {
        $('order').value = finalOrder;
        $('saveorder_form').submit();

      }
    }
  }
</script>

<style>
    .uploaded_stickers img {
        width:30px;
        vertical-align:middle;
     }
</style>
