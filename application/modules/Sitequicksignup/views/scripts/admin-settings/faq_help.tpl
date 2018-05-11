<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Communityad
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: faq_help.tpl 2011-02-16 9:40:21Z SocialEngineAddOns $
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

    window.addEvent('domready', function () {
        faq_show('<?php echo $this->faq_id; ?>');
    });

</script>

<div class="admin_seaocore_files_wrapper">
    <ul class="admin_seaocore_files seaocore_faq">
        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_10');"><?php echo $this->translate("How can i configure this plugin?"); ?></a>
            <div class='faq' style='display: none;' id='faq_10'>
                <?php echo $this->translate("You can configure this plugin by following the simple steps as shown in the <a target='_blank' href='https://www.youtube.com/watch?v=EiS9sUDMc6o' > video </a>."); ?>
                <iframe width="560" height="315" src="https://www.youtube.com/embed/EiS9sUDMc6o" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>
            </div>
        </li>
        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_9');"><?php echo $this->translate("How can i enable the Single Step Signup for the users on my website?"); ?></a>
            <div class='faq' style='display: none;' id='faq_9'>
                <?php echo $this->translate("To enable the Single Step Signup for the users on your website, follow the below steps: <br /><br />
        - Firstly, you need to enable Single Step Signup from the “Global Settings” in the admin panel of this plugin.<br />
        - You can decide which form fields you want to show on the signup form and reorder them from “Signup Form Fields”.<br />
        - You can then enable / disable profile types and their fields according to what information required from “Signup Profile Fields”."); ?>
            </div>
        </li>
        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_10');"><?php echo $this->translate("What if i do not want to show description of the fields on Signup page?"); ?></a>
            <div class='faq' style='display: none;' id='faq_10'>
                <?php echo $this->translate("Yes, you can remove the description of the fields from the Signup page. The default field descriptions can be removed from the “Global Settings” in the admin panel of this plugin and custom field descriptions can be removed by editing the profile fields from the link given in the “Signup Profile Fields”."); ?>
            </div>
        </li>
        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_11');"><?php echo $this->translate("If i want to give some description related to my site on the Signup form in order to introduce my website. Can i do that?"); ?></a>
            <div class='faq' style='display: none;' id='faq_11'>
                <?php echo $this->translate("Yes, you can now add any Title and Description of your choice on the Sign Up form from the “Global Settings” tab."); ?>
            </div>
        </li>
        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_12');"><?php echo $this->translate("What will happen to the subscription plans enabled on my website?"); ?></a>
            <div class='faq' style='display: none;' id='faq_12'>
                <?php echo $this->translate("The choose subscription will appear as a second step after Quick Signup form if plans are created and subscription is enabled from Global Settings."); ?>
            </div>
        </li>
        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_13');"><?php echo $this->translate("Will this plugin work smoothly with Advanced Subscription Plugin?"); ?></a>
            <div class='faq' style='display: none;' id='faq_13'>
                <?php echo $this->translate("Yes absolutely! Quick & Single Step Signup will work smoothly with Advanced Subscription plugin. Choose subscription will be the second step after quick signup form and if profile type mapping is enabled in advanced subscription, then profile type field will not show in the signup form."); ?>
            </div>
        </li>
        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_14');"><?php echo $this->translate("I have “Social Login and Signup Plugin” installed on my site. How will that sync with “Quick & Single Step Signup Plugin”?"); ?></a>
            <div class='faq' style='display: none;' id='faq_14'>
                <?php echo $this->translate("Both these plugins are compatible with each other. On the quick signup form, the social login buttons will also appear. So that the user can decide whether to signup by filling a form or using social login options."); ?>
            </div>
        </li>
        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_15');"><?php echo $this->translate("”Home Page Background Videos & Photos Plugin” has a signup and signin form of its own. Is this plugin compatible with that?"); ?></a>
            <div class='faq' style='display: none;' id='faq_15'>
                <?php echo $this->translate("Yes, you can show quick signup form on home page background videos & Photos also. This plugin work absolutely fine in this case."); ?>
            </div>
        </li>
        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_16');"><?php echo $this->translate("I have set the “Popup for Login and Signup” widget in the site header, but the forms are not coming up fine in the popups. What should i do?"); ?></a>
            <div class='faq' style='display: none;' id='faq_16'>
                <?php echo $this->translate("As you have enabled “Quick & Single Step Signup” plugin on your site, so you need to use the “Quick Signup - Popup for Login & Signup” widget in the site header in order to show the popups."); ?>
            </div>
        </li>
        <li>
            <a href="javascript:void(0);" onClick="faq_show('faq_17');"><?php echo $this->translate("What if i need to show the signup form on the landing page itself?"); ?></a>
            <div class='faq' style='display: none;' id='faq_17'>
                <?php echo $this->translate("Yes, that can be done. You just need to add the “Quick Signup Form” widget on the landing page, the signup form will display as you want."); ?>
            </div>
        </li>
    </ul>
</div>