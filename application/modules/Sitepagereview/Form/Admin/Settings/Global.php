<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Global.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagereview_Form_Admin_Settings_Global extends Engine_Form {

  // IF YOU WANT TO SHOW CREATED ELEMENT ON PLUGIN ACTIVATION THEN INSERT THAT ELEMENT NAME IN THE BELOW ARRAY.
  public $_SHOWELEMENTSBEFOREACTIVATE = array(
      'submit_lsetting', 'environment_mode'
  );
    
  public function init() {
    $this
            ->setTitle('Global Settings')
            ->setDescription('These settings affect all members in your community.');

    $this->addElement('Text', 'sitepagereview_lsettings', array(
        'label' => 'Enter License key',
        'description' => "Please enter your license key that was provided to you when you purchased this plugin. If you do not know your license key, please contact the Support Team of SocialEngineAddOns from the Support section of your Account Area.(Key Format: XXXXXX-XXXXXX-XXXXXX )",
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.lsettings'),
    ));

    $global_settings_file = APPLICATION_PATH . '/application/settings/general.php';
    if (file_exists($global_settings_file)) {
      $generalConfig = include $global_settings_file;
    } else {
      $generalConfig = array();
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

    $this->addElement('Radio', 'sitepagereview_proscons', array(
        'label' => 'Pros and Cons in Reviews',
        'description' => 'Do you want Pros and Cons fields in Reviews? (If enabled, reviewers will be able to enter Pros and Cons for the Pages that they review, and the same will be shown in their reviews.)',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.proscons', 1),
    ));

    $this->addElement('Text', 'sitepagereview_limit_proscons', array(
        'label' => 'Pros and Cons Character Limit',
        'required' => true,
        'allowEmpty' => false,
        'description' => 'What character limit should be applied to the Pros and Cons fields? (Enter 0 for no character limitation.)',
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.limit.proscons', 75),
    ));

    $this->addElement('Radio', 'sitepagereview_recommend', array(
        'label' => 'Recommended in Reviews',
        'description' => 'Do you want Recommended field in Reviews? (If enabled, reviewers will be able to select if they would recommend that Page to a friend, and the same will be shown in their review.)',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.recommend', 1),
    ));

    $this->addElement('Radio', 'sitepagereview_report', array(
    	'label' => 'Report as inappropriate',
      'description' => 'Allow logged-in users to report reviews as inappropriate.',
      'multiOptions' => array(
        1 => ' 	Yes',
        0 => ' 	No'
      ),
      'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.report', 1),
    ));

    $this->addElement('Radio', 'sitepagereview_review_show_menu', array(
        'label' => 'Reviews Link',
        'description' => 'Do you want to show the Reviews link on Pages Navigation Menu? (You might want to show this if Reviews from Pages are an important component on your website. This link will lead to a widgetized page listing all Page Reviews, with a search form for Page Reviews and multiple widgets.',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.review.show.menu', 1),
    ));

    $this->addElement('Radio', 'sitepagereview_order', array(
        'label' => 'Default Ordering in Page Reviews listing',
        'description' => 'Select the default ordering of reviews in Page Reviews listing. (This widgetized page will list all Page Reviews.)',
        'multiOptions' => array(
            1 => 'All reviews in descending order of creation.',
            2 => 'All reviews in alphabetical order.',
            3 => 'Featured reviews followed by others in descending order of creation.',
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.order', 1),
    ));

    $this->addElement('Radio', 'sitepagereview_photo', array(
        'label' => 'Photo for Reviews',
        'description' => 'Which photo do you want to be shown alongside the review entries in the various reviews widgets, on Page Reviews Home and Browse Reviews?',
        'multiOptions' => array(
            1 => 'Photo of the Reviewer',
            0 => 'Photo of the Page'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.photo', 1),
    ));

    $this->addElement('Text', 'sitepagereview_truncation_limit', array(
        'label' => 'Title Truncation Limit',
        'description' => 'What maximum limit should be applied to the number of characters in the titles of items in the widgets? (Enter a number between 1 and 999. Titles having more characters than this limit will be truncated. Complete titles will be shown on mouseover.)',
        'required' => true,
        'maxLength' => 3,
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.truncation.limit', 13),
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        ),
    ));
	
			$this->addElement('Text', 'sitepagereview_manifestUrl', array(
        'label' => 'Page Reviews URL alternate text for "page-reviews"',
        'allowEmpty' => false,
        'required' => true,
        'description' => 'Please enter the text below which you want to display in place of "pagereviews" in the URLs of this plugin.',
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.manifestUrl', "page-reviews"),
    ));

    $this->addElement('Button', 'submit', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'ignore' => true
    ));
  }

}
?>