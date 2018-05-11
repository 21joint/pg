<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: faq_help.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<script type="text/javascript">
  function faq_show(id) {
    if($(id).style.display == 'block') {
      $(id).style.display = 'none';
    } else {
      $(id).style.display = 'block';
    }
  }
</script>
<div class="admin_sitepage_files_wrapper">
  <ul class="admin_sitepage_files sitepage_faq" >
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_1');"><?php echo $this->translate("I want Offers to be available to only certain directory items / pages on my site. How can this be done?"); ?></a>
      <div class='faq' style='display: none;' id='faq_1'>
        <?php echo $this->translate("Ans: You can enable packages for pages on your site, and make Offers available to only certain packages. If you have not enabled packages, then from Member Level Settings, you can make Offers to be available for pages of only certain member levels."); ?></a>
      </div>
    </li>	
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_2');"><?php echo $this->translate("What is the difference between Hot Offers and Featured Offers?"); ?></a>
      <div class='faq' style='display: none;' id='faq_2'>
        <?php echo $this->translate('Ans: Featured Offers are confined to a directory item / page. A page admin can mark one offer from their offers as featured. A featured offer is highlighted and shown on top in the Offers section of a directory item / page. Hot Offers correspond to all directory items / pages on a site. Offers are marked by you(admin) as "Hot" from the "Manage Page Offers" section. There is also a "Hot Page Offers" widget showcasing the offers marked as Hot.'); ?></a>
      </div>
    </li>
		<li>
      <a href="javascript:void(0);" onClick="faq_show('faq_7');"><?php echo $this->translate("I am not able to add Offers to the page from Page profile. What should I do?"); ?></a>
      <div class='faq' style='display: none;' id='faq_7'>
        <?php echo $this->translate("Ans: There should be at least one offer for a Page to show Offers tab on its profile. If there are no Offers currently for a Page then 'Offers' tab is not shown at Page Profile. In that case, you can go to the Page Dashboard and add offers from the 'Get Started' and 'Apps' sections there."); ?></a>
      </div>
    </li>

		<li>
      <a href="javascript:void(0);" onClick="faq_show('faq_6');"><?php echo $this->translate("I had not selected to 'Enable Offers Module for Default Package' in 'Global Settings' section before activating this Plugin. But after activation, I am not able to view this option. Does this mean that this module is permanently disabled for Default Package?"); ?></a>
      <div class='faq' style='display: none;' id='faq_6'>
        <?php echo $this->translate("Ans: No, it does not mean so. You can still enable this module for Default Package from the 'Manage Packages' section of 'Directory/Pages Plugin' by editing the Default Package and selecting the 'Offers' checkbox in the 'Modules / Apps' field."); ?>
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_3');"><?php echo $this->translate("I want to use this plugin for directory of car offers. How can I change the word: 'page offers' to 'car offers' in this plugin?"); ?></a>
      <div class='faq' style='display: none;' id='faq_3'>
        <?php echo $this->translate("Ans: You can easily use this plugin for creation of any type of directories. You can change the word 'page offers' to your directory type from the 'Layout' > 'Language Manager' section in the Admin Panel."); ?>
      </div>
    </li>
		<li>
      <a href="javascript:void(0);" onClick="faq_show('faq_8');"><?php echo $this->translate("I want to change the text 'pageoffer' to 'caroffer' in the URLs of this plugin. How can I do so ?"); ?></a>
      <div class='faq' style='display: none;' id='faq_8'>
        <?php echo $this->translate('Ans: To do so, please go to the Global Settings section of this plugin. Now, search for the field : Page Offers URL alternate text for "pageoffer"<br />Then, enter the text you want to display in place of \'pageoffer\' in the text box there.'); ?>
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_4');"><?php echo $this->translate("The CSS of this plugin is not coming on my site. What should I do ?"); ?></a>
      <div class='faq' style='display: none;' id='faq_4'>
        <?php echo $this->translate("Ans: Please enable the 'Development Mode' system mode for your site from the Admin homepage and then check the page which was not coming fine. It should now seem fine. Now you can again change the system mode to 'Production Mode'."); ?></a>
      </div>
    </li>	
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_5');"><?php echo $this->translate("I want to enhance the Pages on my site and provide more features to my users. How can I do it?"); ?></a>
      <div class='faq' style='display: none;' id='faq_5'>
        <?php echo $this->translate('Ans: There are various apps / extensions available for the "Directory / Pages Plugin" which can enhance the Pages on your site, by adding valuable functionalities to them. To view the list of available extensions, please visit: %s.', '<a href="http://www.socialengineaddons.com/catalog/directory-pages-extensions" target="_blank">http://www.socialengineaddons.com/catalog/directory-pages-extensions</a>'); ?></a>
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_9');"><?php echo $this->translate("What is the popularity criteria for the “Most Popular Offers” filter in the Page Offers search form?"); ?></a>
      <div class='faq' style='display: none;' id='faq_9'>
        <?php echo $this->translate("Ans: The number of times that an offer has been claimed is used to determine the offer’s popularity."); ?>
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_10');"><?php echo $this->translate("I want to send Offers with their details to users via attractive emails. How can I do this?"); ?></a>
      <div class='faq' style='display: none;' id='faq_10'>
        <?php echo $this->translate('Ans: You can send Offers to your users via rich, branded, professional and impact-ful emails by using our %1s. To see details, please %2s','<a href="http://www.socialengineaddons.com/socialengine-email-templates-plugin" target="_blank">"Email Templates Plugin"</a>','<a href="http://www.socialengineaddons.com/socialengine-email-templates-plugin" target="_blank">visit here</a>'.'.'); ?>
      </div>
    </li>
  </ul>
</div>