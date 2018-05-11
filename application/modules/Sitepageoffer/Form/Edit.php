<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Edit.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_Form_Edit extends Engine_Form {

    protected $_item;

    public function getItem() {
        return $this->_item;
    }

    public function setItem(Core_Model_Item_Abstract $item) {
        $this->_item = $item;
        return $this;
    }

    public function init() {

        //GET PAGE ID
        $offer_page = Zend_Controller_Front::getInstance()->getRequest()->getParam('offer_page', null);

        //GET PAGE ID
        $page_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('page_id', null);

        //GET TAB ID
        $tab_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('tab', null);

        //GET VIEW
        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;

        //GET URL
        $url = $view->item('sitepage_page', $page_id)->getHref(array('tab' => $tab_id));
        $this->setTitle('Edit Offer')
                ->setDescription("Edit your offer's details below, then click 'Save Changes' to publish it on your Page.");

        $this->addElement('text', 'title', array(
            'label' => 'Offer Title',
            'required' => true,
            'maxlength' => 100,
            'filters' => array(
                'StripTags',
                new Engine_Filter_Censor(),
                new Engine_Filter_StringLength(array('max' => '100'))
            ),
        ));

        $this->addElement('textarea', 'description', array(
            'label' => 'Description',
            'description' => 'To make this offer more relevant for users and to mention its terms and conditions, enter its description. Terms and conditions can contain information such as coupon code used by staff at the store etc.',
            'required' => true,
            'filters' => array(
                'StripTags',
                new Engine_Filter_HtmlSpecialChars(),
                new Engine_Filter_EnableLinks(),
                new Engine_Filter_Censor(),
            ),
        ));

        $this->addElement('Text', 'url', array(
            'label' => 'Offer URL', 'style' => 'width:200px;',
            'description' => 'If your offer is online or has a URL, then please enter it here.',
            'filters' => array(
              new Engine_Filter_Censor(),
              'StripTags',
               array('PregReplace', array('/\s*[a-zA-Z0-9]{2,5}:\/\//', '')),
            )
        ));
        // Add subforms
        if (!$this->_item) {
            $customFields = new Sitepageoffer_Form_Custom_Fields();
        } else {
            $customFields = new Sitepageoffer_Form_Custom_Fields(array(
                'item' => $this->getItem()
            ));
        }
        if (get_class($this) == 'Sitepageoffer_Form_Create') {
            $customFields->setIsCreation(true);
        }

        $this->addSubForms(array(
            'fields' => $customFields
        ));
        $this->addElement('Text', 'coupon_code', array(
            'label' => 'Coupon Code',
            'description' => 'If your offer requires a coupon code for redemption, then please add it here. (Note: Coupon code should be in between 4 and 16 characters in length and can contain alphabets, numbers, or a combination of both. Special characters other than hyphen (-) are not allowed.)',
            'maxlength' => 16,
            'filters' => array(
              new Engine_Filter_Censor(),
              'StripTags',
            ),
            'validators' => array(
                array('StringLength', true, array(4, 16)),
                array('Regex', true, array('/^[a-zA-Z0-9-_ ]+$/')),
            ),
        ));
        $this->coupon_code->getValidator('Regex')->setMessage('Please enter valid coupon code.', 'regexNotMatch');

        $this->addElement('File', 'photo', array(
            'label' => 'Main Photo'
        ));

        $this->addElement('text', 'claim_count', array(
            'label' => 'Claims',
            'description' => 'Enter the maximum number of times the offer can be claimed by members. This count will start from the time you save this form. (Enter 0 for unlimited. Note: You will not be able to edit claims once the offer is expired.)',
            'required' => true,
            'filters' => array(
              new Engine_Filter_Censor(),
              'StripTags',
            ),
        ));

        $this->addElement('Radio', 'end_settings', array(
            'id' => 'end_settings',
            'label' => 'End Date',
            'description' => 'When will this offer end?',
            'onclick' => "updateTextFields(this.value)",
            'multiOptions' => array(
                "0" => "Never. This offer does not have an end date.",
                "1" => "End offer on a specific date.",
            ),
            'value' => 0
        ));

        $date = (string) date('Y-m-d');
        $this->addElement('CalendarDateTime', 'end_time', array(
            'description' => 'Select a date by clicking on the calendar icon below.',
            'value' => $date . ' 00:00:00',
        ));
        if (empty($offer_page)) {
            $this->addElement('Button', 'execute', array(
                'label' => 'Save Changes',
                'type' => 'submit',
                'ignore' => true,
                'decorators' => array(
                    'ViewHelper',
                ),
            ));

            $this->addElement('Cancel', 'cancel', array(
                'label' => 'cancel',
                'link' => true,
                'prependText' => ' or ',
                'href' => $url,
                'decorators' => array(
                    'ViewHelper',
                ),
            ));

            $this->addDisplayGroup(array(
                'execute',
                'cancel',
                    ), 'buttons', array(
                'decorators' => array(
                    'FormElements',
                    'DivDivDivWrapper'
                ),
            ));
        } else {
            $this->addElement('Button', 'submit', array(
                'label' => 'Save Changes',
                'type' => 'submit',
                'decorators' => array(array('ViewScript', array(
                            'viewScript' => '_formButtonCancel.tpl',
                            'class' => 'form element')))
            ));
            $this->addDisplayGroup(array('submit', 'cancel'), 'buttons', array(
                'decorators' => array(
                    'FormElements',
                    'DivDivDivWrapper',
                ),
            ));
        }
    }

}

?>