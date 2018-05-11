<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: faq_help.php 2012-31-12 9:40:21Z SocialEngineAddOns $
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
			<a href="javascript:void(0);" onClick="faq_show('faq_1');"><?php echo $this->translate("I want Page Admins of certain Directory Items / Pages to be able to add Listings to their Pages. What should I do?");?></a>
			<div class='faq' style='display: none;' id='faq_1'>
				<?php echo $this->translate("To do so, you can choose to enable adding of listings in certain Packages / Member Levels. Page admins belonging to such Member Levels and of Pages associated with such Packages will be able to add listings to their Directory Items / Pages.");?>
			</div>
		</li>	
		<li>
			<a href="javascript:void(0);" onClick="faq_show('faq_2');"><?php echo $this->translate("I had placed the ‘Page Profile Added Listings (selected content)’ widget on ‘Page Profile’ from Layout Editor, but there is no such widget placed on some Pages on my site. What might be the reason?");?></a>
			<div class='faq' style='display: none;' id='faq_2'>
				<?php echo $this->translate("Page Admins of such Pages might have changed the Layout of their Pages and might have removed the ‘Page Profile Added Listings (selected content)’ widget from the Edit Layout section of their Page Dashboard.");?>
			</div>
		</li>
		<li>
			<a href="javascript:void(0);" onClick="faq_show('faq_3');"><?php echo $this->translate("I have enabled the settings to add listings to directory items / pages, but I am not shown any option to add listings in my Page Dashboard. Why is happening?");?></a>
			<div class='faq' style='display: none;' id='faq_3'>
				<?php echo $this->translate("You might have not enabled the settings for adding listings to directory items / pages in the Packages or Member Level Settings. To enable the settings, go to the ‘Manage Packages’ and ‘Member Level Settings’ sections in the admin panel of “Directory / Pages Plugin” and enable the desired settings.");?>
			</div>
		</li>
		<li>
			<a href="javascript:void(0);" onClick="faq_show('faq_4');"><?php echo $this->translate(" I want to enhance the Pages on my site and provide more features to my users. How can I do it?");?></a>
			<div class='faq' style='display: none;' id='faq_4'>
				<?php echo $this->translate("There are various apps / extensions available for the \"Directory / Pages Plugin\" which can enhance the Pages on your site, by adding valuable functionalities to them. To view the list of available extensions, please visit: <a href='http://www.socialengineaddons.com/catalog/directory-pages-extensions' target='_blank'>http://www.socialengineaddons.com/catalog/directory-pages-extensions</a>.");?>
			</div>
		</li>
		<li>
			<a href="javascript:void(0);" onClick="faq_show('faq_5');"><?php echo $this->translate(" The CSS of this plugin is not coming on my site. What should I do ?");?></a>
			<div class='faq' style='display: none;' id='faq_5'>
				<?php echo $this->translate("Ans: Please enable the 'Development Mode' system mode for your site from the Admin homepage and then check the page which was not coming fine. It should now seem fine. Now you can again change the system mode to 'Production Mode'.");?>
			</div>
		</li>
		<li>
      <a href="javascript:void(0);" onClick="faq_show('faq_6');"><?php echo $this->translate(" I have created various listing types on my site, but same icon is coming for all the listing types on my Page Dashboard. How can I change the icons for various listing types? "); ?></a>
      <div class='faq' style='display: none;' id='faq_6'>
        <?php echo "Ans:  To change the icons for each listing type, please follow the steps below:"; ?><br />
        <p>
          <b><?php echo $this->translate("Step 1:") ?></b>
        </p>
        <div class="code">
	        <?php echo $this->translate(nl2br("a) Make an icon for the listing type with the name: “icon_app_intg_listtype_LISTING_TYPE_ID”. To get the LISTING_TYPE_ID, go to the ‘Admin Panel’ >> ‘PLUGIN_NAME’ >> ‘Manage Listing Types’. For example: if the id of the listing type is 1, then the icon name will be: “icon_app_intg_listtype_1”. <br />b) Now, go to the path: \"/application/modules/Sitepageintegration/externals/images\" and upload the icon.")) ?>
        </div><br />
        <p>
          <b><?php echo $this->translate("Step 2:") ?></b>
        </p>
        <div class="code">
					<?php echo $this->translate(nl2br("a) In the below lines of code, replace ICON_NAME with the name of the icon made in Step 1. <br /><br />.ICON_NAME{background-image:url(~/application/modules/Sitepageintegration/externals/images/ICON_NAME.png);} <br /><br />Copy the lines of code after replacing the ICON_NAME.	<br />b) Now, Open the file:  \"/application/modules/Sitepageintegration/externals/styles/main.css\" and paste the copied lines of code in the last of this file.<br />")) ?>
       </div>
      </div>
    </li>

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_7');"><?php echo $this->translate("How should I enable adding of Listings, Businesses and Multiple Listing Types to the Pages of my site?"); ?></a>
      <div class='faq' style='display: none;' id='faq_7'>
        <?php echo "Ans: To enable adding of Listings, Businesses and Multiple Listing Types to the Pages of your site, please follow the steps below:"; ?><br /><br />
        <?php echo $this->translate("Step 1. Go to the Global Settings of this plugin.") ?>
        <br /><br />
        <?php echo $this->translate("Step 2. Choose ‘Yes’ option for the below mentioned fields:<br />") ?>
        <div class="">
					<?php echo $this->translate(nl2br("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a) Adding Listings (This field will come only when “<a href='http://www.socialengineaddons.com/socialengine-listings-catalog-showcase-plugin' target='_blank'>Listings / Catalog Showcase Plugin</a>” is installed on your site.) <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b) Adding Businesses (This field will come only when “<a href='http://www.socialengineaddons.com/socialengine-directory-businesses-plugin' target='_blank'>Directory / Businesses Plugin</a>” is installed on your site.)<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c) Adding Various Listing Types (This field will come only when “<a href='http://www.socialengineaddons.com/socialengine-multiple-listing-types-plugin-listings-blogs-products-classifieds-reviews-ratings-pinboard-wishlists' target='_blank'>Multiple Listing Types Plugin - Listings, Blogs, Products, Classifieds, Reviews & Ratings, Pinboard, Wishlists, etc All In One</a>” is installed on your site.)<br />")) ?>
        </div><br />
        <?php echo $this->translate("Step 3. Now, if you have enabled Packages for the Pages on your site, then go to the ‘Manage Packages’ section in the admin panel of “Directory / Pages Plugin” and edit the packages to select adding of Listings, Businesses and Multiple Listing Types to be available to the Pages in them by using the “Modules / Apps” field.") ?>
      </div>
    </li>
	</ul>
</div>