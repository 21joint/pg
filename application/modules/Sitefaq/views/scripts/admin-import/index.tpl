<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2><?php echo $this->translate('FAQs, Knowledgebase, Tutorials & Help Center Plugin'); ?></h2>
<?php if (count($this->navigation)): ?>
  <div class='seaocore_admin_tabs'> <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?> </div>
<?php endif; ?>

<div class="admin_faq_import">
	<div>
		<h3><?php echo $this->translate('Import FAQs from a CSV File');?></h3>

		<div class="tip">
			<span>
				<?php echo $this->translate('From this page, you can easily import FAQs for any functionality, or ANY 3rd-party plugin on your website.');  ?>
			</span>
		</div>

		<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitefaq.multilanguage', 0)): ?>
			<div class="tip">
				<span>
					<?php echo $this->translate("Before importing a CSV file with FAQs, please ensure by downloading the CSV template that the format of columns in your CSV file matches the one in the CSV template.");  ?>
				</span>
			</div>
		<?php endif; ?>

		<p>
		<?php echo $this->translate("This tool allows you to import FAQs corresponding to the entries from a .csv file. Before starting to use this tool, please read the following points carefully. (Note: If you have enabled multiple languages on your site, then from here, you can only create FAQs in the default language enabled on your site. To add other languages, you can edit the FAQs from the ‘Manage FAQs’ section of this plugin.)");?>
		</p>

		<ul class="admin_faq_import_list">

			<li>
				<?php echo $this->translate("Don't add any new column in the .csv file from which importing has to be done.");?>
			</li>

			<li>
				<?php echo $this->translate("The data in the files should be pipe('|') separated and in a particular format or ordering. So, there should be no pipe('|') in any individual column of the CSV file . If you want to add comma(',') separated data in the CSV file, then you can select the comma(',') option during the CSV file upload process. Note: There is one drawback of using the comma(',') separated data that you will not be able to use comma in fields like description, price, overview etc. for the entries in the CSV file.");?>
			</li>

			<li>
				<?php echo $this->translate("FAQ question, answer and category fields (faq_question, faq_answer, category_name) are the required fields for all the entries in the file. The value for fields: faq_question and faq_answer should be in the default language of your site.");?>
			</li>

			<li>
				<?php echo $this->translate("Categories and sub-categories name should exactly match with the existing categories and sub-categories, otherwise categories or sub-categories will be considered as null for that FAQ.");?>
			</li>

			<li>
				<?php echo $this->translate("You can import the maximum of 2000 FAQs at a time and if you want to import more, you would have to then repeat the whole process. For example, you have to import 3500 FAQs. Then, you would have to create 2 CSV files - one having 2000 entries and another having 1500 entries corresponding to the FAQs. After that, just import both the files using ‘Import FAQs’ option.");?>
			</li>

			<li>
				<?php echo $this->translate("Files must be in the CSV format to be imported. You can also download the demo template below for your reference.");?>
			</li>

			<li>
				<?php echo $this->translate("After importing the FAQs, you may review them from the FAQs section of your website. You may choose to edit some of them to include appropriate links and specific information of your website. If importing of the FAQs might have created any new categories / sub-categories, then you may edit them from 'Categories' section to upload their icons and make them look attractive.");?>
			</li>

		</ul>
		
		<div class="admin_faq_import_buttons">
			<iframe src="about:blank" style="display:none" name="downloadframe"></iframe>
			<a href=<?php echo $this->url(array('action' => 'download')) ?><?php echo '?path=' . urlencode('example_faqs_import.csv');?> target='downloadframe' class="buttonlink icon_sitefaq_download"><?php echo $this->translate('Download the CSV template')?></a>
			<?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sitefaq', 'controller' => 'admin-import', 'action' => 'import-plugins-faqs'), $this->translate('Import FAQs'), array('class' => 'smoothbox buttonlink icon_sitefaq_import')) ?>
		</div>
	</div>
</div>

<?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('communityad')): ?>
	<div class="admin_faq_import">
		<div>
			<h3><?php echo $this->translate('Import FAQs from Advertisements / Community Ads Plugin');?></h3>

			<p>
				<?php echo $this->translate("This tool allows you to import FAQs from the 'Help & Learn More' section of the Advertisements / Community Ads Plugin. Before starting to use this tool, please read the following points carefully.");?>
			</p>

			<ul class="admin_faq_import_list">

				<li>
					<?php echo $this->translate("Here, you will be able to import only 3 sections from the 'Help & Learn More' of Advertisements / Community Ads Plugin: ‘General FAQ', 'Design your Ad FAQ' and 'Targeting FAQ'. To import other sections, you can create a CSV file for those sections and import that CSV file from the above section: 'Import FAQs from a CSV File'.");?>
				</li>

				<li>
					<?php echo $this->translate("After successful importing, imported FAQs will be available in the sub-categories: ‘General’, ‘Design your Ads’ and ‘Targeting’ of the ‘Advertising’ category. If these sub-categories are not created on your site, then new sub-categories along with ‘Advertising’ category will be created during importing.");?>
				</li>

				<li>
					<?php echo $this->translate("You can edit the categories / sub-categories and their associated icons from the ‘Categories’ section of this plugin.");?>
				</li>

				<li>
					<?php echo $this->translate('You can import only those FAQs here, which were not imported during the last import process, if any.'); ?>
				</li>

				<li>
					<?php echo $this->translate("After importing the FAQs, you may review them from the FAQs section of your website. You may choose to edit some of them to include appropriate links and specific information of your website. If importing of the FAQs might have created any new categories / sub-categories, then you may edit them from 'Categories' section to upload their icons and make them look attractive.");?>
				</li>

			</ul>
			
			<div class="admin_faq_import_buttons">
				<?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sitefaq', 'controller' => 'admin-import', 'action' => 'import'), $this->translate('Import FAQs'), array('class' => 'smoothbox buttonlink icon_sitefaq_import')) ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<div class="admin_faq_import">
	<div>
		<h3><?php echo $this->translate('Import FAQs from respective CSV Files of selected plugins');?></h3>

		<p>
			<?php echo $this->translate("This tool allows you to import FAQs corresponding to the entries from the .csv files of selected plugins from SocialEngine and SocialEngineAddOns installed and enabled on your site. Before starting to use this tool, please read the following points carefully");?>
		</p>

		<ul class="admin_faq_import_list">
			<li>
				<?php echo $this->translate("During importing of FAQs from .csv files, new categories and sub-categories will be created corresponding to every plugin.");?>
			</li>

			<li>
				<?php echo $this->translate("You can edit the categories / sub-categories and their associated icons from the ‘Categories’ section of this plugin.");?>
			</li>

			<li>
				<?php echo $this->translate("After importing the FAQs of selected plugins, the FAQ '.csv' files for those plugins will be deleted automatically.");?>
			</li>

			<li>
				<?php echo $this->translate("After importing the FAQs, you may review them from the FAQs section of your website. You may choose to edit some of them to include appropriate links and specific information of your website. If importing of the FAQs might have created any new categories / sub-categories, then you may edit them from 'Categories' section to upload their icons and make them look attractive.");?>
			</li>
		</ul>

		</br>

		<div class="admin_faq_import_buttons">
			<?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sitefaq', 'controller' => 'admin-import', 'action' => 'import-faqs'), $this->translate('Import FAQs'), array('class' => 'smoothbox buttonlink icon_sitefaq_import')) ?>
		</div>
	</div>
</div>