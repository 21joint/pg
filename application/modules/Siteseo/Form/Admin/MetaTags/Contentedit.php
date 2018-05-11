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
class Siteseo_Form_Admin_MetaTags_Contentedit extends Engine_Form {

    protected $_item;

    public function getItem() {
        return $this->_item;
    }

    public function setItem($item) {
        $this->_item = $item;
        return $this;
    }

    public function init() {

        $item = $this->getItem();
        $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
        $description = 'Note: Here you can set the meta title, meta description and meta keywords for your  specific contents.';
        $title = $item->meta_title ? $item->meta_title : $item->title;
        $this->setTitle(ucwords($title));
        $this->setDescription($description);
        $this->addElement('Text', 'meta_title', array(
            'label' => 'Meta Title',
            'description' => 'Enter the title of the page. This will be shown in title tag of the page.',
            'allowEmpty' => false,
            'required' => true,
            'value' => $item->meta_title ? $item->meta_title : $item->title,
            ));

        $this->addElement('Textarea', 'meta_description', array(
            'label' => 'Meta Description',
            'description' => 'Enter the meta description for this page. [Note : This will be shown in meta description tag of this page.]',
            'allowEmpty' => false,
            'required' => true,
            'value' => $item->meta_description ? $item->meta_description : $item->description,
            ));

        $this->addElement('Textarea', 'meta_keywords', array(
            'label' => 'Meta Keywords',
            'description' => 'Enter the meta keywords for this page separated by comma. [Note : This will be shown in meta keywords tag of this page.]',
            'value' => $item->meta_keywords ? $item->meta_keywords : $item->keywords,
            ));

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
}