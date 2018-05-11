<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
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
	<ul class="admin_sitepage_files sitepage_faq">
		<li>
			<a href="javascript:void(0);" onClick="faq_show('faq_1');"><?php echo $this->translate("How is the Badges module related to Packages?");?></a>
			<div class='faq' style='display: none;' id='faq_1'>
				<?php echo $this->translate("Ans: The enabling of Badges App for a Package is related to the ability of Admins of its Pages to request for a Badge for their Page. In this case, Page Admins can also request for their Badge to be changed. A Page's package is not related to the visibility of its badge. Thus, the Site Admin can assign a badge to a Page, and that badge will be visible on the Page's profile even if the Page's package has not been assigned the Badge App.");?>
			</div>
		</li>	
		<li>
			<a href="javascript:void(0);" onClick="faq_show('faq_2');"><?php echo $this->translate("Can admins of directory items / pages assign themselves a badge?");?></a>
			<div class='faq' style='display: none;' id='faq_2'>
				<?php echo $this->translate('Ans: No, users will have to make a request to you(admin) for a badge that they want for their page from the available badges created by you. Their request will come to you, which you can view from the Manage Badge Requests section. You can then act on such requests. You can also yourself assign a badge to a page from the "Options" column of the "Manage Pages" section in the Admin Panel of Directory / Pages Plugin.');?>
			</div>
		</li>
		<li>
			<a href="javascript:void(0);" onClick="faq_show('faq_7');"><?php echo $this->translate("Can I(site admin) assign a badge to a Page myself even without receiving a request for the same from the Admins of that Page?");?></a>
			<div class='faq' style='display: none;' id='faq_7'>
				<?php echo $this->translate("Ans: Yes, you can do so from the Manage Pages section of the Directory/Pages Plugin. With each each Page entry, there is a link 'Badge'. Clicking on this link will open a list of all the badges available and you can select one out of them and assign to that Page.");?>
			</div>
		</li>
		<li>
			<a href="javascript:void(0);" onClick="faq_show('faq_8');"><?php echo $this->translate("Can I(site admin) remove a badge from a Page which has been once assigned to it?");?></a>
			<div class='faq' style='display: none;' id='faq_8'>
				<?php echo $this->translate("Ans: Yes, you can remove a badge from a Page. If you had received a badge request for that Page, then you can remove the badge from the 'Manage Badge Requests' section of this plugin.<br />1) With each request listing, a link 'change badge' is there. Clicking on this link lists all the badges available.<br />2) Now, at the end of the badges list, there is an icon for 'Remove Badge'. You can select it to remove the current badge assigned to that Page.<br /><br />In case you had not received any badge request for a Page and want to remove the badge assigned to it previously by you, then you can go to the 'Manage Pages' section of the Directory/Pages Plugin and click on the 'Badge' link for that Page and do the same as told above.");?>
			</div>
		</li>
		<li>
			<a href="javascript:void(0);" onClick="faq_show('faq_10');"><?php echo $this->translate("I have installed and activated this plugin at my site successfully. But I am not able to find the 'Badge' section anywhere on the Page Dashboards. What can be the cause ?");?></a>
			<div class='faq' style='display: none;' id='faq_10'>
				<?php echo $this->translate("Ans: This happens when you(site admin) have not created any badges at your site. Once if you create at least one badge for Pages at your site, the 'Badge' section would appear on the Page Dashboard for the pages at your site.<br /><br />To create a badge, go to the 'Manage Badges' section at the admin panel of this plugin and click on the 'Add New Badge' link there.");?>
			</div>
		</li>
		<li>
      <a href="javascript:void(0);" onClick="faq_show('faq_6');"><?php echo $this->translate("I had not selected to 'Enable badges for default Package' in 'Global Settings' section before activating this Plugin. But after activation, I am not able to view this option. Does this mean that this module is permanently disabled for Default Package?"); ?></a>
      <div class='faq' style='display: none;' id='faq_6'>
        <?php echo $this->translate("Ans: No, it does not mean so. You can still enable this module for Default Package from the 'Manage Packages' section of 'Directory/Pages Plugin' by editing the Default Package and selecting the 'Badges' checkbox in the 'Modules / Apps' field."); ?>
      </div>
    </li>
		<li>
			<a href="javascript:void(0);" onClick="faq_show('faq_3');"><?php echo $this->translate("I want to use this plugin for directory of car badges. How can I change the word: 'page badges' to 'car badges' in this plugin?");?></a>
			<div class='faq' style='display: none;' id='faq_3'>
				<?php echo $this->translate("Ans: You can easily use this plugin for creation of any type of directories. You can change the word 'page badges' to your directory type from the 'Layout' > 'Language Manager' section in the Admin Panel.");?>
			</div>
		</li>
		<li>
      <a href="javascript:void(0);" onClick="faq_show('faq_9');"><?php echo $this->translate("I want to change the text 'pagebadges' to 'carbadges' in the URLs of this plugin. How can I do so ?"); ?></a>
      <div class='faq' style='display: none;' id='faq_9'>
        <?php echo $this->translate('Ans: To do so, please go to the Global Settings section of this plugin. Now, search for the field : Page Badges URL alternate text for "pagebadges"<br />Then, enter the text you want to display in place of \'pagebadges\' in the text box there.'); ?>
      </div>
    </li>
		<li>
			<a href="javascript:void(0);" onClick="faq_show('faq_4');"><?php echo $this->translate("The CSS of this plugin is not coming on my site. What should I do ?");?></a>
			<div class='faq' style='display: none;' id='faq_4'>
				<?php echo $this->translate("Ans: Please enable the 'Development Mode' system mode for your site from the Admin homepage and then check the page which was not coming fine. It should now seem fine. Now you can again change the system mode to 'Production Mode'.");?>
			</div>
		</li>	
		<li>
			<a href="javascript:void(0);" onClick="faq_show('faq_5');"><?php echo $this->translate("I want to enhance the Pages on my site and provide more features to my users. How can I do it?");?></a>
			<div class='faq' style='display: none;' id='faq_5'>
				<?php echo $this->translate('Ans: There are various apps / extensions available for the "Directory / Pages Plugin" which can enhance the Pages on your site, by adding valuable functionalities to them. To view the list of available extensions, please visit: %s.', '<a href="http://www.socialengineaddons.com/catalog/directory-pages-extensions" target="_blank">http://www.socialengineaddons.com/catalog/directory-pages-extensions</a>');?>
			</div>
		</li>
	</ul>
</div>