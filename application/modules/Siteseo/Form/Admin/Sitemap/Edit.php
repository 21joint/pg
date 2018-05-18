<?php

/**
* SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: edit.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteseo_Form_Admin_Sitemap_Edit extends Engine_Form {

	protected $_contentType;

	public function getContentType() {
		return $this->_contentType;
	}

	public function setContentType(Core_Model_Item_Abstract $contentType) {
		$this->_contentType = $contentType;
		return $this;
	}

	public function init() {
        $contentType = $this->getContentType();
        $this->setTitle($contentType->title);
        $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
        // $provideSubject = strstr($contentType->provides, "subject=") ? true : false;

        $this->addElement('Select', 'changefreq', array(
            'label' => 'Change Frequency',
            'description' => 'Select frequency for this content.',
            'multiOptions' => array(
                'always' => 'Always',
                'hourly' => 'Hourly',
                'daily' => 'Daily',
                'weekly' => 'Weekly',
                'monthly' => 'Monthly',
                'yearly' => 'Yearly',
                'never' => 'Never'
                ),
            'value' => $contentType->changefreq,
            ));

        $this->addElement('Select', 'priority', array(
            'label' => 'Priority',
            'description' => 'Select Priority for this content.',
            'multiOptions' => array(
                '0.1' => '0.1',
                '0.2' => '0.2',
                '0.3' => '0.3',
                '0.4' => '0.4',
                '0.5' => '0.5',
                '0.6' => '0.6',
                '0.7' => '0.7',
                '0.8' => '0.8',
                '0.9' => '0.9',
                '1.0' => '1.0'
                ),
            'value' => $contentType->priority,
            ));

        $this->addElement('Text', 'max_items', array(
            'label' => 'Sitemap Limit',
            'description' => 'Enter the maximum number of URL you want to add in your sitemap file for this content type. [Note : Enter 0 for no limit.]',
            'allowEmpty' => false,
            'required' => true,
            'value' => $contentType->max_items,
            ));

        $this->addElement('Select', 'enabled', array(
            'label' => 'Enable',
            'description' => 'Do you want to enable this content for Sitemap creation.',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No'
                ),
            'value' => $contentType->enabled,
            ));

        if ($contentType->type != 'menu_urls' && $contentType->type != 'custom_pages') {
            
            $schemaTypeArray = Engine_Api::_()->getDbtable('contenttypes','siteseo')->getSchemaTypeArray();
            //CREATE SCHEMA TYPE ARRAY THAT GOOGLE SUPPORTS
            $this->addElement('Select', 'schema', array(
                'label' => 'Enable schema markup for this Content',
                'description' => 'Do you want to enable schema markup for the view pages of this content type .',
                'multiOptions' => array(
                    1 => 'Yes',
                    0 => 'No'
                    ),
                'value' => $contentType->schema,
                'onchange' => 'toggleSchematypeFields(this.value)',
                ));

            $this->addElement('Select', 'schematype', array(
                'label' => 'Select the Schema Type for this content Type',
                'description' => 'Select the Schema Type for this items of this content Type. The view pages of this content type will have this type of schema.',
                'multiOptions' => $schemaTypeArray,
                'value' => $contentType->schematype,
                ));

        }

        $this->addElement('Button', 'submit', array(
            'label' => 'Save',
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

    public function getSpecificSchemaArray() {
        $specificSchemaArray = array(
            'Article' => array(
                'BlogPosting', 'NewsArticle', 'TechArticle', 'ScholarlyArticle'
                ),
            'LocalBusiness' => array(
                'Restaurant', 'HealthClub', 'ShoppingCenter'
                ),
            );
        return $specificSchemaArray;
    }
}