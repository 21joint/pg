<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Global.php 2012-31-12 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageintegration_Form_Admin_Global extends Engine_Form {

  // IF YOU WANT TO SHOW CREATED ELEMENT ON PLUGIN ACTIVATION THEN INSERT THAT ELEMENT NAME IN THE BELOW ARRAY.
  public $_SHOWELEMENTSBEFOREACTIVATE = array(
      'submit_lsetting', 'environment_mode'
  );

  public function init() {

    $this->setTitle('Global Settings')
            ->setDescription('These settings affect all members in your community.');

    $apiSettings = Engine_Api::_()->getApi('settings', 'core');

    $this->addElement('Text', 'sitepageintegration_lsettings', array(
        'label' => 'Enter License key',
        'description' => "Please enter your license key that was provided to you when you purchased this plugin. If you do not know your license key, please contact the Support Team of SocialEngineAddOns from the Support section of your Account Area.(Key Format: XXXXXX-XXXXXX-XXXXXX )",
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageintegration.lsettings'),
    ));

    $global_settings_file = APPLICATION_PATH . '/application/settings/general.php';
    if (file_exists($global_settings_file)) {
      $generalConfig = include $global_settings_file;
    } else {
      $generalConfig = array();
    }
    if ((!empty($generalConfig['environment_mode']) ) && ($generalConfig['environment_mode'] != 'development')) {
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

    $sitestoreproductEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitestoreproduct');
    $sitebusinessEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitebusiness');
    $sitereviewEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitereview');
    $sitereviewlistingtypeEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitereviewlistingtype');
    $listEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('list');

    if (!empty($sitebusinessEnabled) && (!empty($sitereviewEnabled) || !empty($listEnabled))) {
      $title = "Addable Content";
      $options1 = "Their own Content";
      $options2 = "Page Admins’ Content";
      $options3 = "All Content";
      $Description = "Choose the contents that would be available to Page Admins for adding to their Pages.";
    } elseif (!empty($sitereviewEnabled) || !empty($listEnabled)) {
      $title = "Addable Content";
      $options1 = "Their own Content";
      $options2 = "Page Admins’ Content";
      $options3 = "All Content";
      $Description = "Choose the contents that would be available to Page Admins for adding to their Pages.";
    } elseif ($sitebusinessEnabled) {
      $title = "Addable Content";
      $options1 = "Their own Content";
      $options2 = "Page Admins’ Content";
      $options3 = "All Content";
      $Description = "Choose the contents that would be available to Page Admins for adding to their Pages.";
    }

    $this->addElement('Radio', 'sitepageint_listing_view', array(
        'label' => "Apply Directory Item / Page View Privacy",
        'description' => 'Do you want to apply directory item / page’s view privacy on the addable content? [Note: This setting will not work, if you choose "All Content" from the “Addable Content” setting.]',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => $apiSettings->getSetting('sitepageint.listing.view', 0),
    ));

    if (!empty($options1) && !empty($options2) && !empty($options3)) {
      $this->addElement('Radio', 'addable_integration', array(
          'label' => "$title",
          'description' => "$Description",
          'multiOptions' => array(
              1 => "$options1",
              2 => "$options2",
              0 => "$options3"
          ),
          'value' => $apiSettings->getSetting('addable.integration', 1),
      ));
    }

    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('document')) {
      $this->addElement('Radio', 'sitepage_document_integration', array(
          'label' => 'Adding Documents',
          'description' => 'Do you want to enable adding of Documents from "Documents / Scribd iPaper Plugin" to Directory Items / Pages?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'value' => $apiSettings->getSetting('sitepage.document.integration', 0),
      ));
    }

    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitegroup')) {
      $this->addElement('Radio', 'sitepage_group_integration', array(
          'label' => 'Adding Groups',
          'description' => 'Do you want to enable adding of Groups from "Groups / Communities Plugin" to Directory Items / Pages?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'value' => $apiSettings->getSetting('sitepage.group.integration', 0),
      ));
    }

    if (!empty($listEnabled)) {
      $this->addElement('Radio', 'list_integration', array(
          'label' => 'Adding Listings',
          'description' => "Do you want to enable adding of Listings from “Listings / Catalog Showcase Plugin” to Directory Items / Pages?",
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'value' => $apiSettings->getSetting('list.integration', 0),
      ));
    }

    if (!empty($sitebusinessEnabled)) {
      $this->addElement('Radio', 'sitepage_business_integration', array(
          'label' => 'Adding Businesses',
          'description' => 'Do you want to enable adding of Businesses from "Directory / Businesses Plugin" to Directory Items / Pages?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'value' => $apiSettings->getSetting('sitepage.business.integration', 0),
      ));
    }

    if (!empty($sitestoreproductEnabled)) {
      $this->addElement('Radio', 'sitepage_storeproduct_integration', array(
          'label' => 'Adding Products',
          'description' => 'Do you want to enable adding of Store Products from "Stores / Marketplace - Ecommerce Plugin" to Directory Items / Pages?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'value' => $apiSettings->getSetting('sitepage.storeproduct.integration', 0),
      ));
    }

    if (!empty($sitereviewEnabled) && !empty($sitereviewlistingtypeEnabled)) {
      $pluginName = 'Multiple Listing Types Plugin';
    } else {
      $pluginName = 'Multiple Listing Types Plugin Core (Reviews & Ratings Plugin)';
    }

    if (!empty($sitereviewEnabled)) {

      $listingTypeTable = Engine_Api::_()->getDbTable('listingtypes', 'sitereview');
      $listingTypeTableName = $listingTypeTable->info('name');

      $select = $listingTypeTable->select()->from($listingTypeTableName, array('title_singular', 'listingtype_id'));
      $listingTypeDatas = $listingTypeTable->fetchAll($select)->toArray();

      foreach ($listingTypeDatas as $key) {
        $this->addElement('Radio', "sitereview_listing_" . $key['listingtype_id'], array(
            'label' => "Adding " . $key['title_singular'] . " Listings",
            'description' => 'Do you want to enable adding of '.$key['title_singular'].' Listings from "' . $pluginName . '" to Directory Items / Pages?',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No',
            ),
            'value' => $apiSettings->getSetting("sitereview.listing." . $key['listingtype_id'], 0),
        ));
      }
    }

    $this->addElement('Button', 'submit', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'ignore' => true
    ));
  }

}