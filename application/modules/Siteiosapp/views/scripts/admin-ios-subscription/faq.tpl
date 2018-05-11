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
<script type="text/javascript">
    function faq_show(id) {
        if ($(id)) {
            if ($(id).style.display == 'block') {
                $(id).style.display = 'none';
            } else {
                $(id).style.display = 'block';
            }
        }
    }
<?php if ($this->faq_id): ?>
    window.addEvent('domready', function () {
        faq_show('<?php echo $this->faq_id; ?>');
    });
<?php endif; ?>
</script>

<?php $i = 1; ?>
<h2>
    <?php echo $this->translate('iOS Mobile Application - iPhone and iPad'); ?>
</h2>

<?php if (count($this->navigation)): ?>
<div class='seaocore_admin_tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
</div>
<?php endif; ?>

<?php if (count($this->subnavigation)): ?>
<div class='seaocore_admin_tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->subnavigation)->render() ?>
</div>
<?php endif; ?>
<h3><?php echo $this->translate("Frequently Asked Questions") ?> </h3>
<div class="admin_seaocore_files_wrapper">
    <ul class="admin_seaocore_files seaocore_faq">     
        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_<?php echo $i; ?>');"><?php echo $this->translate(' How to create plans on iTunes?'); ?></a>
            <div class='faq' style='display: none;' id='faq_<?php echo $i++; ?>'>
                <div class="code">
            <?php 
                echo $this->translate('Please go to the provided link to read the step-by-step process of creating plans on iTunes for In-App Purchase: <a href = https://support.apple.com/en-us/HT202023"> https://support.apple.com/en-us/HT202023 </a>.</p>');
                 ?>
                </div></div>
        </li>
        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_<?php echo $i; ?>');"><?php echo $this->translate('I have created packages on iTunes but they are not getting listed on Sign Up process. What might be the reason?'); ?></a>
            <div class='faq' style='display: none;' id='faq_<?php echo $i++; ?>'>
                <div class="code">
            <?php echo $this->translate('It might be possible that you have not filled correct information of your packages. You need to fill the same information which is available at this URL: <a href="' . $this->url(array('module' => 'siteiosapp', 'controller' => 'ios-subscription', 'action' => 'ios-packages'), 'admin_default', true) . '">Plan Details</a> i.e Product Id, Package Title, Duration, Recurrence should match with the packages you have created on iTunes.</p>'); ?>
                </div></div>
        </li>
        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_<?php echo $i; ?>');"><?php echo $this->translate('I want my users to subscribe free subscriptions for my community but I am unable to create free packages on iTunes. How can I do so?'); ?></a>
            <div class='faq' style='display: none;' id='faq_<?php echo $i++; ?>'>
                <div class="code">
            <?php echo $this->translate(
'iTunes does not allow to create free subscription packages, so you need not have to create those on itunes. So, to make it available to users on Sign Up process, you can simply create free packages on your website, and they will get displayed in iOS app for your users on Signup Process automatically.</p>'); ?>
                    </p>

                </div></div></li>
    </ul></div>
</div>
</div>