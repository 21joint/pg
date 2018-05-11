<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Faqs.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_Form_Admin_Import_Faqs extends Engine_Form {

  public function init() {

    $this->setTitle('Import FAQs for selected plugins')
					->setDescription("Import FAQs from selected plugins by choosing the plugins below, then click 'Submit'. Below, you can also set the availability of those FAQs to users based on various criteria.")
					->setAttrib('enctype', 'multipart/form-data')
					->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));

		$sitefaq_api = Engine_Api::_()->sitefaq();

		//CSV FILE PATH
    $logPath = APPLICATION_PATH . '/application/modules/Sitefaq/settings/import_csvs';
		@chmod($logPath, 0777);

    //GET ALL EXISTING IMPORT HISTORY FILES
    $logFiles = array();

		$existing_plugins = array('activity', 'advancedactivity', 'sitealbum', 'sitelike', 'sitepageoffer', 'sitepagebadge', 'featuredcontent', 'sitepagediscussion', 'sitepagelikebox', 'mobi', 'advancedslideshow', 'birthday', 'birthdayemail', 'communityad', 'dbbackup', 'facebookse', 'facebooksefeed', 'facebooksepage', 'feedback', 'groupdocument', 'eventdocument', 'grouppoll', 'mapprofiletypelevel', 'mcard','poke', 'sitealbum', 'sitepageinvite', 'siteslideshow', 'socialengineaddon','seaocore', 'suggestion', 'userconnection', 'sitepageform', 'sitepageadmincontact', 'sitebusinessbadge', 'sitebusinessoffer', 'sitebusinessdiscussion', 'sitebusinesslikebox', 'sitebusinessinvite', 'sitebusinessform', 'sitebusinessadmincontact', 'album', 'blog', 'classified', 'document', 'event', 'forum', 'poll', 'video', 'list', 'group', 'music', 'recipe', 'user', 'sitepage', 'sitepagenote', 'sitepagevideo','sitepagepoll', 'sitepagemusic','sitepagealbum','sitepageevent', 'sitepagereview', 'sitepagedocument', 'sitebusiness', 'sitebusinessalbum', 'sitebusinessdocument', 'sitebusinessevent', 'sitebusinessnote', 'sitebusinesspoll', 'sitebusinessmusic', 'sitebusinessvideo', 'sitebusinessreview', 'sitefaq', 'siteestore', 'sitereview', 'sitepagemember', 'sitebusinessmember', 'sitestore', 'sitegroupbadge', 'sitegroupoffer', 'sitegroupdiscussion', 'sitegrouplikebox', 'sitegroupinvite', 'sitegroupform', 'sitegroupadmincontact',  'sitegroup', 'sitegroupalbum', 'sitegroupdocument', 'sitegroupevent', 'sitegroupnote', 'sitegrouppoll', 'sitegroupmusic', 'sitegroupvideo', 'sitegroupreview',  'sitegroupmember', 'siteevent', 'siteeventdocument', 'siteeventinvite', 'siteeventrepeat', 'siteeventticket', 'siteforum', 'sitenews','sitecrowdfunding');

		$previous_files = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitefaq_import');
		$previous_files = unserialize($previous_files);

    foreach (scandir($logPath) as $key => $file) { 

			if(strtolower(substr($file, -9)) == '_faqs.csv') {
				$explode = explode('_faqs.csv', $file);
				if(!in_array($explode[0], $previous_files) && in_array($explode[0], $existing_plugins)) {
					$module = Engine_Api::_()->getDbtable('modules', 'core')->getModule($explode[0]);
					if (!empty($module) && !empty($module->enabled)) {

						$file = $module->title;

						$logFiles[$key+1] = $file;
					}
				}
				if(!in_array($explode[0], $existing_plugins) && !in_array($explode[0], $previous_files)) {
					$logFiles[$key+1] = $file;
				}
			}
    }

		if(!empty($logFiles)) {
			$this->addElement('MultiCheckbox', 'files', array(
				'label' => 'Modules / Plugins',
				'required' => true,
				'description' => 'Select the modules / plugins for which you want to import the FAQs.',
				'multiOptions' => $logFiles,
			));
		}

		$level_id = Engine_Api::_()->user()->getViewer()->level_id;

		if(Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'memberlevels')) {

			$levels_prepared = $sitefaq_api->getMemberLevels();

			$this->addElement('Multiselect', 'member_levels', array(
					'label' => 'Member Levels',
					'description' => 'Select the Member Levels to which the imported FAQs should be available. Use CTRL-click to select or deselect multiple Member Levels.',
					'multiOptions' => $levels_prepared,
					'value' => 0,
			));
		}

		$topLevelOptions = $sitefaq_api->getProfileTypes(0);
		$topLevelOptionsCount = Count($topLevelOptions);

		if(Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'profiletypes') && $topLevelOptionsCount > 2) {

			$this->addElement('Multiselect', 'profile_types', array(
					'label' => 'Profile Types',
					'description' => 'Select the Profile Types to which the imported FAQs should be available. Use CTRL-click to select or deselect multiple Profile Types.',
					'multiOptions' => $topLevelOptions,
					'value' => 0,
			));
		}

		if(Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'networks')) {

			$networksOptions = $sitefaq_api->getNetworks();

			$this->addElement('Multiselect', 'networks', array(
					'label' => 'Networks Selection',
					'description' => 'Select the Networks, members of which should be able to see the imported FAQs. Use CTRL-click to select or deselect multiple Networks.',
					'multiOptions' => $networksOptions,
					'value' => 0,
			));
		}

    $this->addElement('Button', 'submit', array(
        'label' => 'Submit',
        'type' => 'submit',
        'decorators' => array(
            'ViewHelper',
        ),
    ));

    $this->addElement('Cancel', 'cancel', array(
        'label' => 'cancel',
        'link' => true,
        'prependText' => ' or ',
        'onclick' => "javascript:parent.Smoothbox.close()",
        'decorators' => array(
            'ViewHelper',
        ),
    ));

    $this->addDisplayGroup(array(
        'submit',
        'cancel',
            ), 'buttons', array(
        'decorators' => array(
            'FormElements',
            'DivDivDivWrapper'
        ),
    ));
  }

}
