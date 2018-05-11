<?php

/**
* SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: submit.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteseo_Form_Admin_Sitemap_AutoSubmit extends Engine_Form {

	public function init() {

        $click = '<a href="admin/siteseo/settings/support/target/3" target="_blank"> Click here </a>';
        $description = "Enable auto submission of Sitemap to selected search engines. If you have selected Google and want to keep track of your Sitemap then please integrate Google Search Console with the plugin first. [Note: $click to integrate your Google Search Console.]";
        $this->setTitle('Auto Submit Your Sitemap');
        $this->setDescription($description);
        $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
        $this->loadDefaultDecorators();
        $this->getDecorator('Description')->setOption('escape', false);
        $searchEnginesArray = array(
            'google' => 'Google',
            'bing' => 'Bing',
            );
        $setting = Engine_Api::_()->getApi('settings', 'core');
        $default = $setting->getSetting("siteseo.sitemap.submit.searchengines", array_keys($searchEnginesArray));

        $this->addElement('MultiCheckbox', 'search_engines', array(
            'label' => 'Search Engines',
            'description' => 'Select the search engines for which you want to auto submit the Sitemap',
            'multiOptions' => $searchEnginesArray,
            'value' => array_values($default),
        ));

        $coreTaskTable = Engine_Api::_()->getDbtable('tasks', 'core');
        $select = $coreTaskTable->select()->where('plugin = ? ', 'Siteseo_Plugin_Task_AutoSubmitSitemap');
        $row = $coreTaskTable->fetchRow($select);
        $default = isset($row->timeout) ? intval($row->timeout / 86400) : 7;
        $default = 'day' . $default;
        $submitIntervals = array(
            'day1000' => 'Never',
            'day1' => '1 Day',
            'day2' => '2 Days',
            'day3' => '3 Days',
            'day4' => '4 Days',
            'day5' => '5 Days',
            'day7' => '1 Week',
            'day14' => '2 Weeks',
            'day30' => '1 Month',
            );
        $this->addElement('Select', 'submit_interval', array(
            'label' => 'Auto Submit Time Interval',
            'description' => 'Select the time interval at which you want to submit your sitemap automatically to the selected search engines.',
            'multiOptions' => $submitIntervals,
            'value' => $default,
        ));

        $this->addElement('Button', 'submit', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'ignore' => true,
            'decorators' => array(
                'ViewHelper',
                ),
        ));
        
        // Element: cancel
        $this->addElement('Cancel', 'cancel', array(
            'label' => 'cancel',
            'link' => true,
            'prependText' => ' or ',
            'onclick' => "javascript:parent.Smoothbox.close();",
            'href' => "javascript:void(0);",
            'decorators' => array(
                'ViewHelper',
                ),
        ));

        // DisplayGroup: buttons
        $this->addDisplayGroup(array(
            'submit',
            'cancel',
            ), 'buttons', array(
            'decorators' => array(
                'FormElements',
                'DivDivDivWrapper'
                ),
        ));
        $button_group = $this->getDisplayGroup('buttons');
        $button_group->setOrder('999');
    }
}