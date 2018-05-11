<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitegifplayer
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Global.php 2017-05-15 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitegifplayer_Form_Admin_Global extends Engine_Form
{

  // IF YOU WANT TO SHOW CREATED ELEMENT ON PLUGIN ACTIVATION THEN INSERT THAT ELEMENT NAME IN THE BELOW ARRAY.
  public $_SHOWELEMENTSBEFOREACTIVATE = array(
    "submit_lsetting", "environment_mode"
  );

  public function init()
  {
    $productType = 'sitegifplayer';
    $this->setTitle('General Settings')
      ->setDescription('These settings affect all users in your community.');

    $coreSettings = Engine_Api::_()->getApi('settings', 'core');


    // ELEMENT FOR LICENSE KEY
    $this->addElement('Text', $productType . '_lsettings', array(
      'label' => 'Enter License key',
      'description' => "Please enter your license key that was provided to you when you purchased this plugin. If you do not know your license key, please contact the Support Team of SocialEngineAddOns from the Support section of your Account Area.(Key Format: XXXXXX-XXXXXX-XXXXXX )",
      'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting($productType . '.lsettings'),
    ));

    if( APPLICATION_ENV == 'production' ) {
      $this->addElement('Checkbox', 'environment_mode', array(
        'label' => 'Your community is currently in "Production Mode". We recommend that you momentarily switch your site to "Development Mode" so that the CSS of this plugin renders fine as soon as the plugin is installed. After completely installing this plugin and visiting few stores of your site, you may again change the System Mode back to "Production Mode" from the Admin Panel Home. (In Production Mode, caching prevents CSS of new plugins to be rendered immediately after installation.)',
        'description' => 'System Mode',
        'value' => 1,
      ));
    } else {
      $this->addElement('Hidden', 'environment_mode', array('order' => 990, 'value' => 0));
    }

    $this->addElement('Button', 'submit_lsetting', array(
      'label' => 'Activate Your Plugin Now',
      'type' => 'submit',
      'ignore' => true
    ));
    //SETTINGS FOR ENABLED AND DISABLED "Play Button" 
    $this->addElement('Radio', 'sitegifplayer_allow_action', array(
      'label' => 'Action for GIF Player',
      'description' => 'On what action GIF Player should start',
      'multiOptions' => array(
        1 => 'On mouse hover',
        0 => 'On click over gif image'
      ),
      'value' => $coreSettings->getSetting('sitegifplayer.allow.action', 1)
    ));

    $this->addElement('Text', 'sitegifplayer_duration', array(
      'label' => 'Duration',
      'description' => 'Please enter the minimum time duration for which gif should play once (in sec).',
      'validators' => array(
        array('NotEmpty', true),
        array('Int', true),
        new Engine_Validate_AtLeast(1),
      ),
      'value' => $coreSettings->getSetting('sitegifplayer.duration'),
    ));

    $this->addElement('Button', 'submit', array(
      'label' => 'Save Changes',
      'type' => 'submit',
      'ignore' => true
    ));
  }

}
