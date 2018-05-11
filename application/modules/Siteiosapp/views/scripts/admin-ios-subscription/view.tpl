<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteiosapp
 * @copyright  Copyright 2012-2013 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: map.tpl 6590 2013-04-01 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
 ?>

<div class="global_form_popup admin_member_stats">
    <h3>User Subscription Information</h3>
    <ul>
        <li>
            <?php echo $this->translate('Package Id: ');?>
            <span><?php echo (isset($this->receiptInfo->product_id) && !empty($this->receiptInfo->product_id)) ? $this->receiptInfo->product_id : "-"; ?></span>
        </li>

        <li>
            <?php echo $this->translate('Transaction Id: '); ?>
            <span><?php echo (isset($this->receiptInfo->transaction_id) && !empty($this->receiptInfo->transaction_id)) ? $this->receiptInfo->transaction_id : "-"; ?></span>
        </li> 

        <li>
                <?php echo $this->translate('Purchase Date: ');?>
            <span><?php echo (isset($this->receiptInfo->purchase_date) && !empty($this->receiptInfo->purchase_date)) ? $this->receiptInfo->purchase_date : "-"; ?></span>
        </li> 

        <li>
                <?php if (isset($this->receiptInfo->expires_date) && !empty($this->receiptInfo->expires_date)):
                echo $this->translate('Expiry Date: '); ?>
            <span><?php echo (isset($this->receiptInfo->expires_date) && !empty($this->receiptInfo->expires_date)) ? $this->receiptInfo->expires_date : "-";
            endif;?></span>
        </li>

    </ul>
    <br/>
    <button type="submit" onclick="parent.Smoothbox.close();
            return false;" name="close_button" value="Close">Close</button>
</div>