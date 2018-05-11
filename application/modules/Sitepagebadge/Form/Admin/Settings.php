<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Settings.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagebadge_Form_Admin_Settings extends Engine_Form {

  // IF YOU WANT TO SHOW CREATED ELEMENT ON PLUGIN ACTIVATION THEN INSERT THAT ELEMENT NAME IN THE BELOW ARRAY.
  public $_SHOWELEMENTSBEFOREACTIVATE = array(
      'submit_lsetting', 'environment_mode', 'include_in_package'
  );
    
  public function init() {
    $this
            ->setTitle('Global Settings')
            ->setDescription('These settings affect all members in your community.');

    $this->addElement('Text', 'sitepagebadge_lsettings', array(
        'label' => 'Enter License key',
        'description' => "Please enter your license key that was provided to you when you purchased this plugin. If you do not know your license key, please contact the Support Team of SocialEngineAddOns from the Support section of your Account Area.(Key Format: XXXXXX-XXXXXX-XXXXXX )",
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagebadge.lsettings'),
    ));

    $global_settings_file = APPLICATION_PATH . '/application/settings/general.php';
    if (file_exists($global_settings_file)) {
      $generalConfig = include $global_settings_file;
    } else {
      $generalConfig = array();
    }

    if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
      $this->addElement('Checkbox', 'include_in_package', array(
          'label' => ' Do you want to enable badge for default package in the Pages Plugin which is created during the Page Plugin installation. (If enabled, badges will be enabled for the pages created in the default package.)',
          'description' => 'Enable badges for default Package',
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

    $this->addElement('Radio', 'sitepagebadge_badge_show_menu', array(
        'label' => 'Badges Link',
        'description' => 'Do you want to show the Badges link on Pages Navigation Menu? (You might want to show this if Badges from Pages are an important component on your website. This link will lead to a widgetized page listing all Page Badges, with a search form for Page Badges and multiple widgets.)',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagebadge.badge.show.menu', 1),
    ));

    $this->addElement('Radio', 'sitepagebadge_badgeprofile_widgets', array(
        'label' => 'Details On Badge',
        'description' => 'Select the details you want to show on Badges.',
        'multiOptions' => array(
            2 => 'Both Badge Title and Image',
            1 => 'Only Badge Image',
            0 => 'Only Badge Title'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagebadge.badgeprofile.widgets', 2),
    ));

    $this->addElement('Radio', 'sitepagebadge_seaching_bybadge', array(
        'label' => 'Badge in Search',
        'description' => 'Do you want the Badge field in the search form widget at "Pages Home" and "Browse Pages" pages?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagebadge.seaching.bybadge', 1),
    ));
		
		$this->addElement('Text', 'sitepagebadge_manifestUrl', array(
        'label' => 'Page Badges URL alternate text for "pagebadges"',
        'allowEmpty' => false,
        'required' => true,
        'description' => 'Please enter the text below which you want to display in place of "pagebadges" in the URLs of this plugin.',
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagebadge.manifestUrl', "page-badges"),
    ));

    $this->addElement('Button', 'submit', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'ignore' => true
    ));
  }

}
?>