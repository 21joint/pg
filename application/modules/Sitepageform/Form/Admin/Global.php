<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageform
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Global.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageform_Form_Admin_Global extends Engine_Form {

  // IF YOU WANT TO SHOW CREATED ELEMENT ON PLUGIN ACTIVATION THEN INSERT THAT ELEMENT NAME IN THE BELOW ARRAY.
  public $_SHOWELEMENTSBEFOREACTIVATE = array(
      'submit_lsetting', 'environment_mode', 'include_in_package'
  );
    
  public function init() {

    $this
            ->setTitle('Global Settings')
            ->setDescription('These settings affect all members in your community.');

    $this->addElement('Text', 'sitepageform_lsettings', array(
        'label' => 'Enter License key',
        'description' => "Please enter your license key that was provided to you when you purchased this plugin. If you do not know your license key, please contact the Support Team of SocialEngineAddOns from the Support section of your Account Area.(Key Format: XXXXXX-XXXXXX-XXXXXX )",
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageform.lsettings'),
    ));

    $global_settings_file = APPLICATION_PATH . '/application/settings/general.php';
    if (file_exists($global_settings_file)) {
      $generalConfig = include $global_settings_file;
    } else {
      $generalConfig = array();
    }

    if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
      $this->addElement('Checkbox', 'include_in_package', array(
          'label' => ' Enable form module for the default package that was created upon installation of the "Directory / Pages Plugin". If enabled, Form App will also be enabled for the Pages created so far under the default package.)',
          'description' => 'Enable Form Module for Default Package',
          'value' => 1,
      ));
    }

    if ( ( !empty($generalConfig['environment_mode']) ) && ($generalConfig['environment_mode'] != 'development') ) {
      $this->addElement('Checkbox', 'environment_mode', array(
          'label' => 'Your community is currently in "Production Mode". We recommend that you momentarily switch your site to "Development Mode" so that the CSS of this plugin renders fine as soon as the plugin is installed. After completely installing this plugin and visiting few pages of your site, you may again change the System Mode back to "Production Mode" from the Admin Panel Home. (In Production Mode, caching prevents CSS of new plugins to be rendered immediately after installation.)',
          'description' => 'System Mode',
//          'value' => 1,
      ));
    } else {
      $this->addElement('Hidden', 'environment_mode', array('order' => 990, 'value' => 0));
    }
    
    $this->addElement('Button', 'submit_lsetting', array(
        'label' => 'Activate Your Plugin Now',
        'type' => 'submit',
        'ignore' => true
    ));

    $this->addElement('Radio', 'sitepageform_formtabseeting', array(
        'label' => 'Non-logged-in Visitors',
        'description' => 'Do you want the Form tab in Pages to be available to non-logged-in visitors? (If yes, then non-logged-in visitors will be able to see the Form tab on the Page and fill the form created by the Page Admin.)',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageform.formtabseeting', 1),
    ));

    $this->addElement('Radio', 'sitepageform_captcha', array(
        'label' => 'CAPTCHA',
        'description' => 'Do you want CAPTCHA in forms of Form App in Pages for non-logged-in visitors?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageform.captcha', 1),
    ));
    
   $this->addElement('Radio', 'sitepageform_add_question', array(
				'label' => 'Allow Adding Custom Fields / Questions',
				'description' => 'Do you want to allow Page owners to add custom fields / questions for their Page Forms?',
				'multiOptions' => array(
						1 => 'Yes',
						0 => 'No'
				),
				'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageform.add.question', 1),
		));
	
	  $this->addElement('Radio', 'sitepageform_edit_name', array(
				'label' => 'Allow Editing Form Tab’s Name',
				'description' => 'Do you want to allow Page owners to edit the name of their Page’s Form tab?',
				'multiOptions' => array(
						1 => 'Yes',
						0 => 'No'
				),
				'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageform.edit.name', 1),
		));
	
	 $this->addElement('Text', 'sitepageform_manifestUrl', array(
        'label' => 'Pages Form URL alternate text for "page-form"',
        'allowEmpty' => false,
        'required' => true,
        'description' => 'Please enter the text below which you want to display in place of "pageform" in the URLs of this plugin.',
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageform.manifestUrl', "page-form"),
    ));

    $this->addElement('Button', 'submit', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'ignore' => true
    ));
  }

}
?>