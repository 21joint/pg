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
  <?php echo $this->translate("Manage Stickers of: " . $this->collection->getTitle()); ?>
</h3>
<p>
  <?php echo $this->translate('Below, you can manage and order the stickers of this collection. Drag and drop stickers to arrange their sequence. You can also add new stickers to this collection.
'); ?> 
</p>
<br />
<div class="collection_info">
  <div class="photo">
    <?php
    echo $this->itemPhoto($this->collection, 'thumb.small-icon', '', array(
      'align' => 'center'))
    ?>
  </div>
  <div class="avatar_details">
    <div class="av_title">
      <?php echo $this->collection->getTitle() ?>
    </div>
    <div class="av_des">
      <?php echo $this->collection->body ?>
    </div>
  </div>
 <div>
    <a href="<?php echo $this->url(array('action' => 'add-more', 'collection_id' => $this->collection->getIdentity())) ?>" class="buttonlink seaocore_icon_add" title="<?php echo $this->translate('Add more Stickers'); ?>"><?php echo $this->translate('Add more Stickers'); ?></a> |
    <a href="<?php echo $this->url(array('action' => 'edit', 'collection_id' => $this->collection->getIdentity())) ?>" class="buttonlink seaocore_icon_edit" title="<?php echo $this->translate('Edit'); ?>"><?php echo $this->translate('Edit'); ?></a>
  </div>
</div>
 
 

<div class="stickers_collection">
  <form id='saveorder_form' method='post' action='<?php
  echo $this->url(array('controller' => 'sticker',
    'action' => 'update-order'))
  ?>'>
    <input type='hidden'  name='order' id="order" value=''/>
    <input type='hidden'  name='collection_id' id="collection_id" value='<?php echo $this->collection->getIdentity(); ?>'/>

    <div id='order-element'>
      <ul>
        <?php foreach ($this->collection->getStickers() as $sticker): ?>
          <li>
            <input type='hidden'  name='order[]' value='<?php echo $sticker->getIdentity(); ?>'>
            <div class="image">
              <?php
              echo $this->itemPhoto($sticker, '', '', array(
                'align' => 'center'))
              ?>
            </div>
            <div class="info">
              <?php echo $sticker->getTitle() ?>
              <a href="<?php echo $this->url(array('controller' => 'sticker', 'action' => 'edit',
              'sticker_id' => $sticker->getIdentity()))
              ?>" class="buttonlink seaocore_icon_edit smoothbox" title="<?php echo $this->translate('Edit'); ?>"></a>
            </div>
            <a href="<?php echo $this->url(array('controller' => 'sticker', 'action' => 'delete',
                 'sticker_id' => $sticker->getIdentity()))
               ?>" class="remove_sticker smoothbox" title="<?php echo $this->translate('Remove'); ?>"><?php echo $this->translate('x'); ?></a>
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

<style type="text/css">
  .stickers_collection ul {
    display: inline-block;
  }
  .stickers_collection ul li{
    float:left;
    width: 123px;
    height: 120px;
    margin: 0 5px 15px 5px;
    border: 1px solid #ccc;
    position: relative;
    display:table;
  }
  .stickers_collection ul li .image {
    width: 100%;
    text-align: center;
    display:table-cell;
    vertical-align:middle;
  }
  .stickers_collection ul li .info {
    position: absolute;
    bottom: 0;
    left:0;
    right:0;
    background: black;
    color: #fff;
    width: 100%;
    padding: 3px 5px;
    box-sizing:border-box;
    display:none;
  }
 .stickers_collection  .seaocore_icon_edit {
     float:right;
     height:16px;
     margin-right:0;
 }
  .stickers_collection ul li:hover .info, .stickers_collection ul li:hover .remove_sticker{
    display:block;
  }
  .stickers_collection ul li .image img {
    width: 100px;
    height: 100px;
  }
  .stickers_collection ul li .remove_sticker {
    position: absolute;
    top: 5px;
    right: 10px;
    display:none;
  }
  /* Collections*/
  .collection_info {
     margin-top:10px;
  }
  .photo {
     width:64px;
     float:left;
     margin-right:10px;
  }
  .avatar_details {
     overflow:hidden;
  }
  .avatar_details + div{
     clear:both;
     padding-top:15px;
     padding-bottom:10px;
  }
  .av_title {
     font-weight:bold;
     text-transform:capitalize;
     margin-bottom:5px;
  }
</style>
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
 
