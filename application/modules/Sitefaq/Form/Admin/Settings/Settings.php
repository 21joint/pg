<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Settings.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_Form_Admin_Settings_Settings extends Engine_Form
{
  public function init()
  {
    $this
    ->setTitle('Global Settings')
    ->setDescription('These settings affect all members in your community.');

		$settings = Engine_Api::_()->getApi('settings', 'core');

    $this->addElement('Text', 'sitefaq_lsettings', array(
        'label' => 'Enter License key',
        'description' => "Please enter your license key that was provided to you when you purchased this plugin. If you do not know your license key, please contact the Support Team of SocialEngineAddOns from the Support section of your Account Area.(Key Format: XXXXXX-XXXXXX-XXXXXX )",
        'required' => true,
        'value' => $settings->getSetting('sitefaq.lsettings'),
    ));

    if (APPLICATION_ENV == 'production') {
      $this->addElement('Checkbox', 'environment_mode', array(
          'label' => 'Your community is currently in "Production Mode". We recommend that you momentarily switch your site to "Development Mode" so that the CSS of this plugin renders fine as soon as the plugin is installed. After completely installing this plugin and visiting few pages of your site, you may again change the System Mode back to "Production Mode" from the Admin Panel Home. (In Production Mode, caching prevents CSS of new plugins to be rendered immediately after installation.)',
          'description' => 'System Mode',
          'value' => 1,
      ));
    } else {
      $this->addElement('Hidden', 'environment_mode', array('order' => 990, 'value' => 0));
    }

    // Add submit button
    $this->addElement('Button', 'submit_lsetting', array(
        'label' => 'Activate Your Plugin Now',
        'type' => 'submit',
        'ignore' => true
    ));
    
    $this->addElement('Radio', 'sitefaq_redirection', array(
        'label' => 'Redirection of FAQs link',
        'description' => 'Please select the redirection page for FAQs, when user click on "FAQs" link at Main Navigation Menu / Mini Navigation Menu / Footer Menu / Member Home Page Left side Navigation.',
        "multiOptions" => array(
            'home' => 'FAQs Home Page',
            'browse' => 'FAQs Browse Page'
        ),
        'value' => $settings->getSetting('sitefaq.redirection', 'home'),
    ));            
    

  if(!Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitemenu')){
    $this->addElement('Radio', 'sitefaq_link', array(
        'label' => 'FAQs Link',
        'description' => 'Select the location of the main link for FAQs.',
        'multiOptions' => array(
            3 => 'Main Navigation Menu',
            2 => 'Mini Navigation Menu',
            1 => 'Footer Menu',
            0 => 'Member Home Page Left side Navigation'
        ),
        'value' => $settings->getSetting('sitefaq.link', 1),
    ));
  }
		$this->addElement('Radio', 'sitefaq_tag', array(
    	'label' => 'Tag Field',
      'description' => 'Do you want the Tag field to be displayed in the FAQ creation form? (Assigning tags to FAQs will enable the tags to appear in the Tag Cloud. Tags of FAQs will be used in the SocialEngine Global Search for returning results and to return results in the Related FAQs widget.)',
      'multiOptions' => array(
        1 => ' 	Yes',
        0 => ' 	No'
      ),
      'value' => $settings->getSetting('sitefaq.tag', 1),
    ));

		$this->addElement('Radio', 'sitefaq_search', array(
    	'label' => 'Show This FAQ in Search Results',
      'description' => 'Do you want creators to be able to choose from the FAQ creation form if their FAQ should be searchable? (If you select ‘No’ over here, then users will not be able to choose whether their FAQs should be searchable or not and the FAQs created by them will be searchable in this plugin.)',
      'multiOptions' => array(
        1 => ' 	Yes',
        0 => ' 	No'
      ),
      'value' => $settings->getSetting('sitefaq.search', 1),
    ));

    $this->addElement('Radio', 'sitefaq_editor', array(
    	'label' => 'WYSIWYG Editor',
			'description' => 'Allow WYSIWYG editor for the answers of FAQs.',
      'multiOptions' => array(
				1 => 'Yes',
				0 => 'No'
      ),
      'value' => $settings->getSetting('sitefaq.editor', 1),
    ));

    $this->addElement('Text', 'sitefaq_categories', array(
      'label' => 'Maximum Categories',
      'description' => 'Enter the maximum number of categories to be associated with an FAQ.',
			'required' => true,
			'validators' => array(
					array('Int', true),
					array('GreaterThan', true, array(0)),
			),
      'value' => $settings->getSetting('sitefaq.categories', 2),
    ));

		$this->addElement('Radio', 'sitefaq_email', array(
			'label' => 'Notification for User Questions',
			'description' => 'Do you want to receive an email notification every time a question is posted for you by a user? (From Member Level Settings, you can choose who all should be able to ask questions.)',
			'multiOptions' => array(
				1 => 'Yes',
				0 => 'No'
			),
			'value' => $settings->getSetting('sitefaq.email', 1),
		));

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $field = 'sitefaq_code_share';
    $this->addElement('Dummy', "$field", array(
        'label' => 'Social Share Widget Code',
        'description' => "<a class='smoothbox' href='". $view->url(array('module' => 'seaocore', 'controller' => 'settings', 'action' => 'social-share', 'field' => "$field"), 'admin_default', true) ."'>Click here</a> to add your social share code.",
        'ignore' => true,
    ));
    $this->$field->addDecorator('Description', array('placement' => 'PREPEND', 'class' => 'description', 'escape' => false));

		$this->addElement('Radio', 'sitefaq_multilanguage', array(
    	'label' => 'Multiple Languages for FAQs',
			'description' => "Do you want to enable multiple languages for the FAQs while creation? (Select ‘Yes’, only if you have installed multiple languages from the 'Language Manager' section of the Admin Panel. Selecting ‘Yes’ over here will enable users to create their FAQs in multiple languages installed on your site. If you select ‘No’ over here, then the pack you've marked as your 'default' pack, will be the language displayed to members.)",
      'multiOptions' => array(
				1 => 'Yes',
				0 => 'No'
      ),
      'value' => $settings->getSetting('sitefaq.multilanguage', 0),
    ));

		//GET EXISTING LANGUAGES ARRAY
		$localeMultiOptions = Engine_Api::_()->sitefaq()->getLanguageArray();

		$this->addElement('MultiCheckbox', 'sitefaq_languages', array(
			'label' => 'Languages',
			'description' => 'Select the languages for which you want users to be able to create FAQs.',
			'multiOptions' => $localeMultiOptions,
			'value' => $settings->getSetting('sitefaq.languages'),
		));

    $this->addElement('Button', 'submit', array(
      'label' => 'Save Changes',
      'type' => 'submit',
      'ignore' => true
    ));
  }

}