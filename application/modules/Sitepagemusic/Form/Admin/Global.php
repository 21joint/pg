<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Global.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagemusic_Form_Admin_Global extends Engine_Form {

  // IF YOU WANT TO SHOW CREATED ELEMENT ON PLUGIN ACTIVATION THEN INSERT THAT ELEMENT NAME IN THE BELOW ARRAY.
  public $_SHOWELEMENTSBEFOREACTIVATE = array(
      'submit_lsetting', 'environment_mode', 'include_in_package'
  );
    
  public function init() {
    
    $this
            ->setTitle('Global Settings')
            ->setDescription('These settings affect all members in your community.');

    $this->addElement('Text', 'sitepagemusic_lsettings', array(
        'label' => 'Enter License key',
        'description' => "Please enter your license key that was provided to you when you purchased this plugin. If you do not know your license key, please contact the Support Team of SocialEngineAddOns from the Support section of your Account Area.(Key Format: XXXXXX-XXXXXX-XXXXXX )",
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.lsettings'),
    ));

    $global_settings_file = APPLICATION_PATH . '/application/settings/general.php';
    if (file_exists($global_settings_file)) {
      $generalConfig = include $global_settings_file;
    } else {
      $generalConfig = array();
    }

    if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
      $this->addElement('Checkbox', 'include_in_package', array(
          'label' => 'Enable music module for the default package that was created upon installation of the "Directory / Pages Plugin". If enabled, Music App will also be enabled for the Pages created so far under the default package.',
          'description' => 'Enable Music Module for Default Package',
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

     // Order of page music page
    $this->addElement('Radio', 'sitepagemusic_order', array(
        'label' => 'Default Ordering in Page Music listing',
        'description' => 'Select the default ordering of playlists in Page Music listing. (This widgetized page will list all Page Playlists. Sponsored playlists are playlists created by paid Pages.)',
        'multiOptions' => array(
            1 => 'All playlists in descending order of creation.',
            2 => 'All playlists in alphabetical order.',
            3 => 'Featured playlists followed by others in descending order of creation.',
            4 => 'Sponsored playlists followed by others in descending order of creation.(If you have enabled packages.)',
            5 => 'Featured playlists followed by sponsored playlists followed by others in descending order of creation.(If you have enabled packages.)',
            6 => 'Sponsored playlists followed by featured playlists followed by others in descending order of creation.(If you have enabled packages.)',
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.order', 1),
    ));

     $this->addElement('Radio', 'sitepagemusic_music_show_menu', array(
        'label' => 'Music Link',
        'description' => 'Do you want to show the Music link on Pages Navigation Menu? (You might want to show this if Music from Pages is an important component on your website. This link will lead to a widgetized page listing all Page Playlists, with a search form for Page Playlists and multiple widgets.',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.music.show.menu', 1),
    ));

    $this->addElement('Text', 'sitepagemusic_truncation_limit', array(
        'label' => 'Title Truncation Limit',
        'description' => 'What maximum limit should be applied to the number of characters in the titles of items in the widgets? (Enter a number between 1 and 999. Titles having more characters than this limit will be truncated. Complete titles will be shown on mouseover.)',
        'required' => true,
        'maxlength' => 3,
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.truncation.limit', 18),
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        ),
    ));
		
    $this->addElement('Radio', 'sitepagemusic_featured', array(
        'label' => 'Making Page Music Featured',
        'description' => 'Allow Page Admins to make music in their Pages as featured.',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.featured', 1),
    ));

		$this->addElement('Text', 'sitepagemusic_manifestUrl', array(
        'label' => 'Page Music URL alternate text for "page-music"',
        'allowEmpty' => false,
        'required' => true,
        'description' => 'Please enter the text below which you want to display in place of "pagemusic" in the URLs of this plugin.',
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.manifestUrl', "page-music"),
    ));

    $this->addElement('Button', 'submit', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'ignore' => true
    ));
  }

}

?>