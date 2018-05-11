<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitequicksignup
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: form_order.tpl 2017-03-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2>
    <?php echo $this->translate('Quick & Single Step Signup Plugin'); ?>
</h2>
<?php if (count($this->navigation)): ?>
    <div class='tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
    </div>
<?php endif; ?>


<h3>
    <?php echo $this->translate("Signup Form Fields") ?>
</h3>

<p>
    <?php echo $this->translate('You can hide / display form fields from here. Also, the order of the form fields can be changed by clicking on their names and dragging them up or down.<br />[ <strong>Note:</strong> The three mandatory fields Profile Type, Email and Password cannot be disabled.]') ?>
</p>

<br />

<form id='saveorder_form' method='post' action='<?php echo $this->url(array('action' => 'form-order')) ?>' style="overflow:hidden;">
    <input type='hidden'  name='order' id="order" value=''/>
    <div class="seaocore_admin_order_list" style="width:50%;">
        <div class="list_head">     
            <div style="width:45%;">
                <?php echo $this->translate("Item Label") ?>
            </div>
            <div style="width:45%;" class="admin_table_centered">
                <?php echo $this->translate("Hide / Display") ?>
            </div>
        </div>
        <div id='order-element'>
            <ul>
                <?php foreach ($this->orderForm as $item) : ?>

                    <li>
                        <input type='hidden'  name='order[]' value='<?php echo $item->fieldorder_id; ?>'>
                        <div style="width:45%;">
                            <?php echo $this->translate($item->label) ?>
                        </div>
                        <div style="width:45%;" class="admin_table_centered">
                            <?php if (($item->name == 'email' || $item->name == 'password' || $item->name == 'profiletypes')): ?>
                                <?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/approved.gif', '', array('title' => $this->translate('Hide'))); ?>

                            <?php elseif (Engine_Api::_()->getApi('settings', 'core')->getSetting($item->name)): ?>
                                <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sitequicksignup', 'controller' => 'admin-settings', 'action' => 'diplay-form', 'id' => $item->fieldorder_id, 'name' => $item->name, 'display' => 0), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/approved.gif', '', array('title' => $this->translate('Hide')))); ?>
                            <?php elseif (!Engine_Api::_()->getApi('settings', 'core')->getSetting($item->name)): ?>
                                <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sitequicksignup', 'controller' => 'admin-settings', 'action' => 'diplay-form', 'id' => $item->fieldorder_id, 'name' => $item->name, 'display' => 1), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/disapproved.gif', '', array('title' => $this->translate('Display')))); ?>
                            <?php endif; ?>

                        </div>
                    </li>

                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</form>
<br />

<button onClick="javascript:saveOrder(true);" type='submit' class="clear">
    <?php echo $this->translate("Save Order") ?>
</button>

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

    window.addEvent('domready', function () {
        var initSiteevent = [];
        var li = $('order-element').getElementsByTagName('li');
        for (i = 1; i <= li.length; i++)
            initSiteevent.push(li[i]);
        origOrder = initSiteevent;
        var temp_array = $('order-element').getElementsByTagName('ul');
        temp_array.innerHTML = initSiteevent;
        new Sortables(temp_array);
    });

    window.onbeforeunload = function (event) {
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
    }
</script>