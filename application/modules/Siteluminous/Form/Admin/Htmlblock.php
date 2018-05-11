<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Htmlblock.php 2014-10-09 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteluminous_Form_Admin_Htmlblock extends Engine_Form {

  // IF YOU WANT TO SHOW CREATED ELEMENT ON PLUGIN ACTIVATION THEN INSERT THAT ELEMENT NAME IN THE BELOW ARRAY.
  public $_SHOWELEMENTSBEFOREACTIVATE = array(
      "submit_lsetting", "environment_mode", "landing_page_layout"
  );
  
  public function init() {

    $this->setTitle(sprintf(Zend_Registry::get('Zend_Translate')->_("Global Settings")))
            ->setDescription(sprintf(Zend_Registry::get('Zend_Translate')->_("These settings affect all members in your community.")));


    // ELEMENT FOR LICENSE KEY
    $this->addElement('Text', 'siteluminous_lsettings', array(
        'label' => 'Enter License key',
        'description' => "Please enter your license key that was provided to you when you purchased this plugin. If you do not know your license key, please contact the Support Team of SocialEngineAddOns from the Support section of your Account Area.(Key Format: XXXXXX-XXXXXX-XXXXXX )",
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('siteluminous.lsettings'),
    ));
    
    $this->addElement('Radio', 'landing_page_layout', array(
        'label' => 'Change Landing Page Layout',
        'description' => "Do you want the layout of your home page to be changed as per the default set-up of this theme ? If you choose 'Yes' then your current layout of Home page will be replaced with a new one.",
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => 1,
    ));

    if (APPLICATION_ENV == 'production') {
      $this->addElement('Checkbox', 'environment_mode', array(
          'label' => 'Your community is currently in "Production Mode". We recommend that you momentarily switch your site to "Development Mode" so that the CSS of this plugin renders fine as soon as the plugin is installed. After completely installing this plugin and visiting few stores of your site, you may again change the System Mode back to "Production Mode" from the Admin Panel Home. (In Production Mode, caching prevents CSS of new plugins to be rendered immediately after installation.)',
          'description' => 'System Mode',
          'value' => 1,
      ));
    } else {
      $this->addElement('Hidden', 'environment_mode', array('order' => 990, 'value' => 0));
    }

    //Add submit button
    $this->addElement('Button', 'submit_lsetting', array(
        'label' => 'Activate Your Plugin Now',
        'type' => 'submit',
        'ignore' => true
    ));


    $siteluminousLendingBlockValue = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteluminous.lending.block', null);    
    if(empty($siteluminousLendingBlockValue) || is_array($siteluminousLendingBlockValue)) {
      $siteluminousLendingBlockValue = '<div><div style="float: left; margin: 10px 0; opacity: 1; padding: 56px 0; text-align: center; width: 33.3%;">
  
      <div style="background-color: #EBEBEB; background-position: center 50%; background-repeat: no-repeat; border-radius: 50% 50% 50% 50%; height: 175px; margin: 0 auto; width: 175px; background-image: url(application/themes/luminous/images/discover-events.png); display:block;"></div>
        <a href="/events">
          <span style="color: #282828; float: left; font-family: Ubuntu, sans-serif; font-size: 22px; margin-top: 20px; text-align: center; width: 100%;">Discover Events</span>
          <span style="color: #707070; float: left; font-family: Open Sans, sans-serif; font-size: 15px; margin-top: 10px; padding: 0 11%; text-align: center; width: 71%;">Find out the best parties and events happening around you.</span>
        </a>
    </div>
    <div style="float: left; margin: 10px 0; opacity: 1; padding: 56px 0; text-align: center; width: 33.3%;">
    <div style="background-color: #EBEBEB; background-position: center 50%; background-repeat: no-repeat; border-radius: 50% 50% 50% 50%; height: 175px; margin: 0 auto; width: 175px; background-image: url(application/themes/luminous/images/engage-icon.png); display:block;"></div>
        <a href="/groups">
          <span style="color: #282828; float: left; font-family: Ubuntu, sans-serif; font-size: 22px; margin-top: 20px; text-align: center; width: 100%;">Engage</span>
          <span style="color: #707070; float: left; font-family: Open Sans, sans-serif; font-size: 15px; margin-top: 10px; padding: 0 11%; text-align: center; width: 71%;">Join our interest based groups and share stuff.</span>
        </a>
   </div>
   <div style="float: left; margin: 10px 0; opacity: 1; padding: 56px 0; text-align: center; width: 33.3%;">
     <div style="background-color: #EBEBEB; background-position: center 50%; background-repeat: no-repeat; border-radius: 50% 50% 50% 50%; height: 175px; margin: 0 auto; width: 175px; background-image: url(application/themes/luminous/images/meetpeople.png); display:block;"></div>
    <a href="/members">
      <span style="color: #282828; float: left; font-family: Ubuntu, sans-serif; font-size: 22px; margin-top: 20px; text-align: center; width: 100%;">Meet New People</span>
      <span style="color: #707070; float: left; font-family: Open Sans, sans-serif; font-size: 15px; margin-top: 10px; padding: 0 11%; text-align: center; width: 71%;">Make new friends with common interests, Get your own party buddies.</span>
    </a>
  </div></div>';
    }else {
      $siteluminousLendingBlockValue = @base64_decode($siteluminousLendingBlockValue);
    }

//    $this->addElement('TinyMce', 'siteluminous_lending_page_block', array(
//        'label' => 'Landing Page Block',
//        'description' => 'Enter the content to be displayed in this HTML Block on landing page.',
//        'attribs' => array('rows' => 24, 'cols' => 80, 'style' => 'width:200px; max-width:200px; height:120px;'),
////           'allowEmpty' => false,
////           'required' => true,
//        'value' => $siteluminousLendingBlockValue,
//        'filters' => array(
//            new Engine_Filter_Html(),
//            new Engine_Filter_Censor()),
//        'editorOptions' => Engine_Api::_()->seaocore()->tinymceEditorOptions(),
//    ));

    //WORK FOR MULTILANGUAGES START
    $localeMultiOptions = Engine_Api::_()->siteluminous()->getLanguageArray();
    $coreSettings = Engine_Api::_()->getApi('settings', 'core');
    $defaultLanguage = $coreSettings->getSetting('core.locale.locale', 'en');
    $total_allowed_languages = Count($localeMultiOptions);
    if (!empty($localeMultiOptions)) {
      foreach ($localeMultiOptions as $key => $label) {
        $lang_name = $label;
        if (isset($localeMultiOptions[$label])) {
          $lang_name = $localeMultiOptions[$label];
        }
        
        $page_block_field = "siteluminous_lending_page_block_$key";
        if(!strstr($key, '_')){
              $key = $key.'_default';
        }
        $keyForSettings = str_replace('_', '.', $key);
        $siteluminousLendingBlockValueMulti = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteluminous.lending.block.languages.' . $keyForSettings, null);
        if (empty($siteluminousLendingBlockValueMulti)) {
          $siteluminousLendingBlockValueMulti = $siteluminousLendingBlockValue;
        } else {
          $siteluminousLendingBlockValueMulti = @base64_decode($siteluminousLendingBlockValueMulti);
        }
        $page_block_label = sprintf(Zend_Registry::get('Zend_Translate')->_("Landing Page Block in %s"), $lang_name);

        if ($total_allowed_languages <= 1) {
          $page_block_field = "siteluminous_lending_page_block";
          $page_block_label = "Landing Page Block";
        } elseif ($label == 'en' && $total_allowed_languages > 1) {
          $page_block_field = "siteluminous_lending_page_block";
        }

        $this->addElement('TinyMce', $page_block_field, array(
            'label' => $page_block_label,
            'description' => 'Enter the content to be displayed in this HTML Block on landing page.',
            'attribs' => array('rows' => 24, 'cols' => 80, 'style' => 'width:200px; max-width:200px; height:120px;'),
            'value' => $siteluminousLendingBlockValueMulti,
            'filters' => array(
                new Engine_Filter_Html(),
                new Engine_Filter_Censor()),
            'editorOptions' => Engine_Api::_()->seaocore()->tinymceEditorOptions(),
        ));
      }
    }
//WORK FOR MULTILANGUAGES END


    $this->addElement('Button', 'submit', array(
        'label' => 'Submit',
        'type' => 'submit',
        'decorators' => array(
            'ViewHelper',
        ),
    ));
  }

}