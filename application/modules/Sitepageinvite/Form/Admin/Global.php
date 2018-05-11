<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageinvite
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Global.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageinvite_Form_Admin_Global extends Engine_Form {

  // IF YOU WANT TO SHOW CREATED ELEMENT ON PLUGIN ACTIVATION THEN INSERT THAT ELEMENT NAME IN THE BELOW ARRAY.
  public $_SHOWELEMENTSBEFOREACTIVATE = array(
      'submit_lsetting', 'environment_mode', 'include_in_package'
  );
    
  public function init() {
    $this
            ->setTitle('Global Settings')
            ->setDescription('These settings affect all members in your community.');

    $this->addElement('Text', 'sitepageinvite_lsettings', array(
        'label' => 'Enter License key',
        'description' => "Please enter your license key that was provided to you when you purchased this plugin. If you do not know your license key, please contact the Support Team of SocialEngineAddOns from the Support section of your Account Area.(Key Format: XXXXXX-XXXXXX-XXXXXX )",
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageinvite.lsettings'),
    ));

    $global_settings_file = APPLICATION_PATH . '/application/settings/general.php';
    if (file_exists($global_settings_file)) {
      $generalConfig = include $global_settings_file;
    } else {
      $generalConfig = array();
    }

    if (Engine_Api::_()->sitepage()->hasPackageEnable()) {
      $this->addElement('Checkbox', 'include_in_package', array(
          'label' => 'Enable inviter module for the default package that was created upon installation of the "Directory / Pages Plugin". If enabled, Inviter App will also be enabled for the Pages created so far under the default package.',
          'description' => 'Enable Inviter Module for Default Package',
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
    
    // Add submit button
    $this->addElement('Button', 'submit_lsetting', array(
        'label' => 'Activate Your Plugin Now',
        'type' => 'submit',
        'ignore' => true
    ));

    $this->addElement('Dummy', 'yahoo_settings_temp', array(
        'label' => '',
        'decorators' => array(array('ViewScript', array(
                    'viewScript' => '_formcontactimport.tpl',
                    'class' => 'form element'
            )))
    ));

		$this->addElement('Radio', 'pageinvite_friend_invite_enable', array(
        'label' => 'Web Accounts for Import & Invite',
        'description' => "Do you want users of your site to be able to invite their friends to your site using all the available web accounts on your site? (If you select 'Yes' over here, then you will be able to choose below the various web accounts.)",
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('pageinvite.friend.invite.enable', 1),
        
    ));

    $webmail_values = Engine_Api::_()->getApi('settings', 'core')->getSetting('pageinvite.show.webmail', 0);
    if (!empty($webmail_values)) {
      $webmail_values = unserialize($webmail_values);
    }
    $this->addElement('MultiCheckbox', 'pageinvite_show_webmail', array(
        'label' => 'Web Account services',
        'description' => "Select the web account services that you want to be available to users of your site for inviting their friends.",
        'multiOptions' => array(
            'gmail' => 'Gmail',
            'yahoo' => 'Yahoo',
            'window_mail' => 'Window Live',
            'aol' => 'AOL',
            'facebook_mail' => 'Facebook',
            'linkedin_mail' => 'Linkedin',
            'twitter_mail' => 'Twitter',
        ),
        'value' => $webmail_values
    ));
	
	 $this->addElement('Text', 'sitepageinvite_manifestUrl', array(
        'label' => 'Page Invites URL alternate text for "page-invites"',
        'allowEmpty' => false,
        'required' => true,
        'description' => 'Please enter the text below which you want to display in place of "pageinvites" in the URLs of this plugin.',
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageinvite.manifestUrl', "page-invites"),
    ));

    // Add submit button
    $this->addElement('Button', 'submit', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'ignore' => true
    ));
  }

}

?>