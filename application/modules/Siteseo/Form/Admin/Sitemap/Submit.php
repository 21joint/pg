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
class Siteseo_Form_Admin_Sitemap_Submit extends Engine_Form {

	public function init() {

        $click = '<a href="admin/siteseo/settings/support/target/3" target="_blank"> Click here </a>';
        $description = "Sitemap can be submitted / auto-submitted to Google with or without integrating Google Search Console. But, if you want to keep track of your Sitemap then you should integrate Google Search Console with this plugin. [Note: To integrate Google Search Console, Please $click.";
        $this->setTitle('Submit Your Sitemap');
        $this->setDescription($description);
        $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
        $this->loadDefaultDecorators();
        $this->getDecorator('Description')->setOption('escape', false);
        $searchEnginesArray = array(
            'google' => 'Google',
            'bing' => 'Bing',
            );
        $this->addElement('MultiCheckbox', 'search_engines', array(
            'label' => 'Search Engiens',
            'description' => 'Select the search engines for which you want to submit the Sitemap',
            'multiOptions' => $searchEnginesArray,
            'value' => array('google', 'bing'),
        ));

        $this->addElement('Radio', 'regenerate', array(
            'label' => 'Regenerate Sitemap',
            'description' => 'Do you want to regenerate Sitemap before submitting it to search engine.',
            'multiOptions' => array(
                0 => 'No',
                1 => 'Yes',
                ),
            'value' => 0,
        ));

        $this->addElement('Button', 'submit', array(
            'label' => 'Submit Sitemap',
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