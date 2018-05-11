<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Global.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_Form_Admin_Global extends Engine_Form {

  // IF YOU WANT TO SHOW CREATED ELEMENT ON PLUGIN ACTIVATION THEN INSERT THAT ELEMENT NAME IN THE BELOW ARRAY.
  public $_SHOWELEMENTSBEFOREACTIVATE = array(
      'submit_lsetting', 'environment_mode', 'include_in_package'
  );
    
  public function init() {
    $this
            ->setTitle('Global Settings')
            ->setDescription('These settings affect all members in your community.');

    $this->addElement('Text', 'sitepageoffer_lsettings', array(
        'label' => 'Enter License key',
        'description' => "Please enter your license key that was provided to you when you purchased this plugin. If you do not know your license key, please contact the Support Team of SocialEngineAddOns from the Support section of your Account Area.(Key Format: XXXXXX-XXXXXX-XXXXXX )",
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.lsettings'),
    ));

    $global_settings_file = APPLICATION_PATH . '/application/settings/general.php';
    if (file_exists($global_settings_file)) {
      $generalConfig = include $global_settings_file;
    } else {
      $generalConfig = array();
    }

    if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
      $this->addElement('Checkbox', 'include_in_package', array(
          'label' => ' Enable offers module for the default package that was created upon installation of the "Directory / Pages Plugin". If enabled, Offers App will also be enabled for the Pages created so far under the default package.',
          'description' => 'Enable Offers Module for Default Package',
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

    $this->addElement('Radio', 'sitepageoffer_offer_show_menu', array(
        'label' => 'Offers Link',
        'description' => 'Do you want to show the Offers link on Pages Navigation Menu? (You might want to show this if Offers from Pages are an important component on your website. This link will lead to a widgetized page listing all Page Offers, with a search form for Page Offers and multiple widgets.',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.offer.show.menu', 1),
    ));
    
    $this->addElement('Radio', 'sitepageoffer_getofferlink', array(
        'label' => '"Get Offer" Link',
        'description' => 'Do you want to show "Get Offer" link in various widgets and pages?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.getofferlink', 1),
    ));    


    // Order of page offer page
    $this->addElement('Radio', 'sitepageoffer_order', array(
        'label' => 'Default Ordering in Page Offers listing',
        'description' => 'Select the default ordering of offers in Page Offers listing. (This widgetized page will list all Page Offers. Sponsored offers are offers created by paid Pages.)',
        'multiOptions' => array(
            1 => 'All offers in descending order of creation.',
            2 => 'All offers in alphabetical order.',
            3 => 'Hot offers followed by others in descending order of creation.',
            4 => 'Sponsored offers followed by others in descending order of creation.(If you have enabled packages.)',
            5 => 'Hot offers followed by sponsored offers followed by others in descending order of creation.',
            6 => 'Sponsored offers followed by hot offers followed by others in descending order of creation.(If you have enabled packages.)',
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.order', 1),
    ));


    $this->addElement('Text', 'sitepageoffer_truncation_limit', array(
        'label' => 'Title Truncation Limit',
        'description' => 'What maximum limit should be applied to the number of characters in the titles of items in the widgets? (Enter a number between 1 and 999. Titles having more characters than this limit will be truncated. Complete titles will be shown on mouseover.)',
        'required' => true,
        'maxlength' => 3,
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.truncation.limit', 13),
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        ),
    ));
	
		$this->addElement('Text', 'sitepageoffer_manifestUrl', array(
        'label' => 'Page Offers URL alternate text for "page-offers"',
        'allowEmpty' => false,
        'required' => true,
        'description' => 'Please enter the text below which you want to display in place of "pageoffer" in the URLs of this plugin.',
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.manifestUrl', "page-offers"),
    ));

    $this->addElement('Button', 'submit', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'ignore' => true
    ));
  }

}
?>