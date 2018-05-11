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
  <?php echo $this->translate("Manage Stickers Search Options"); ?>
</h3>
<p>
  <?php echo $this->translate('Below, you can manage and order your sticker search options. Drag and drop options to arrange their sequence. You can assign higher positioning to the options that are more important for your community. You can also add new search options and set its search criteria.'); ?> 
</p>
<br />
<div>
  <a href="<?php echo $this->url(array('action' => 'create-search')) ?>" class="buttonlink seaocore_icon_add" title="<?php echo $this->translate('Create Sticker Search Option'); ?>"><?php echo $this->translate('Create Sticker Search Option'); ?></a>
</div>
<br />

<div >
  <form id='saveorder_form' method='post' action='<?php
  echo $this->url(array('controller' => 'collection', 'action' => 'update-search-order'))
  ?>'>
    <input type='hidden'  name='order' id="order" value=''/>

    <div id='order-element' class="sticker_search_list">
      <ul>
        <?php foreach ($this->searchList as $search): ?>
          <li  style="background: <?php echo $search->background_color ?>">
            <input type='hidden'  name='order[]' value='<?php echo $search->stickersearch_id; ?>'>
            <?php
              echo $this->itemPhoto($search, '', '', array(
                'align' => 'center'))
              ?>
            <?php echo $search->title ?>
            <div class="options">
              <a href="<?php
              echo $this->url(array('action' => 'edit-search',
                'search_id' => $search->stickersearch_id))
              ?>" class="buttonlink seaocore_icon_edit" title="<?php echo $this->translate('Edit Search'); ?>"></a>
              <a href="<?php
                 echo $this->url(array('action' => 'delete-search',
                   'search_id' => $search->stickersearch_id))
                 ?>" class="buttonlink smoothbox sitereaction_icon_delete" title="<?php echo $this->translate('Delete Search'); ?>"></a>
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

<style type="text/css">
  .sticker_search_list{
    border: 0;
    border-radius: 2px;
    box-shadow: 0 0 0 1px rgba(0, 0, 0, .1), 0 1px 10px rgba(0, 0, 0, .35);
    background: #fff;
    width: 900px;
    padding: 20px;
  }
  .sticker_search_list li {
    width: 438px;
    font-size: 1.1em;
    font-weight: bold;
    display: inline-table;
    padding: 8px 10px;
    margin: 5px;
    border: none;
    border-radius: 3px;
    box-sizing: border-box;
    color: #fff;
    text-align: left;
    text-shadow: none;
    text-transform: capitalize;
    text-shadow: 0px 1px 1px #222;
    height: 32px;
  }
  .sticker_search_list li .options {
    float: right;
    margin-top:4px;
  }
  .sticker_search_list li .options a.buttonlink {
    margin-left: 5px;
    margin-right:0;
  }
  .sticker_search_list li .options .remove{
    color: #000;
    padding-left: 0;
  }
  .sticker_search_list img{
    vertical-align:middle;
    height:24px;
    width:24px;
    margin-right:4px;
  }
  .sitereaction_icon_delete {
      background-image:url(application/modules/Sitereaction/externals/images/admin/delete.png);
  }
</style>
