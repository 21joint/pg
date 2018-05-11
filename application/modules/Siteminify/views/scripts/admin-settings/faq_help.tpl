<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteminify
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: faq_help.tpl 2017-01-29 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<script type="text/javascript">
    function faq_show(id) {
        if ($(id).style.display == 'block') {
            $(id).style.display = 'none';
        } else {
            $(id).style.display = 'block';
        }
    }
</script>

<div class="admin_seaocore_files_wrapper">
    <ul class="admin_seaocore_files seaocore_faq">	
        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_1');"><?php echo $this->translate("Will it work with CDN?"); ?></a>
            <div class='faq' style='display: none;' id='faq_1'>
                <?php echo $this->translate("Your website page will not break and will work perfectly fine with CDN. <br/>"); ?>
            </div>
        </li>

        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_2');"><?php echo $this->translate("During minification does it removes relevant code also to speed up website?"); ?></a>
            <div class='faq' style='display: none;' id='faq_2'>
                <?php echo $this->translate('No, it removes only the re-written code and unnecessary spaces & comments from it.'); ?>
            </div>
        </li>
        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_3');"><?php echo $this->translate("How can I check whether minify plugin has really affected by website's performance or not?"); ?></a>
            <div class='faq' style='display: none;' id='faq_3'>
                <?php echo $this->translate("You can change your website's mode from production -> development -> production, to ensure your website's code is not coming from cache and you are checking speed in real. Now open your website >> inspect your website's page >> Go to Networks tab >> Select JS / CSS tab, you can see the number of JS and CSS requests. Now repeat this procedure after disabling this plugin you can see the significant amount of difference."); ?>
            </div>
        </li>
        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_4');"><?php echo $this->translate("Does this plugin requires any changes in Source Code of SocialEngine PHP?"); ?></a>
            <div class='faq' style='display: none;' id='faq_4'>
                <?php echo $this->translate("No, this plugin does not require any changes in Source Code of SocialEngine PHP."); ?>
            </div>
        </li>
        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_5');"><?php echo $this->translate("Which library are we using for this plugin?"); ?></a>
            <div class='faq' style='display: none;' id='faq_5'>
                <?php echo $this->translate("We are using library of Minify from: <a href='http://code.google.com/p/minify' target='_blank'> http://code.google.com/p/minify</a>."); ?>
            </div>
        </li>
    </ul>
</div>




