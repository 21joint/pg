<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Create.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_Form_Create extends Engine_Form {

    public function init() {

        //GET PAGE ID
        $page_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('page_id', null);

        //GET TAB ID
        $tab_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('tab', null);

        //GET VIEW
        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;

        //GET URL
        $url = $view->item('sitepage_page', $page_id)->getHref(array('tab' => $tab_id));

        $this->setTitle('Add a New Offer')
                ->setAttrib('id', 'submit_form')
                ->setDescription("Enter your offer's details below, click 'Preview' to view your offer.");
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
            'label' => 'Offer Picture',
            'description' => "<span id='loading_image' style='display:none;'></span> ",
            'onchange' => 'imageupload()',
        ));
        $this->photo->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

        $this->photo->addValidator('Extension', false, 'jpg,png,gif');

        $this->addElement('text', 'claim_count', array(
            'label' => 'Claims',
            'description' => 'Enter the maximum number of times this offer can be claimed by members. (Enter 0 for unlimited. Note: You will not be able to edit claims once this offer expires.)',
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
                "1" => "This offer ends on a specific date. (Select the date by clicking on the calendar icon below.)",
            ),
            'value' => 0
        ));
        $date = (string) date('Y-m-d');
        $this->addElement('CalendarDateTime', 'end_time', array(
            'value' => $date . ' 00:00:00',
        ));

        $this->addElement('Button', 'execute', array(
            'label' => 'Preview',
            'onclick' => "showdetail(this)",
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

        $this->addElement('Hidden', 'photo_id_filepath', array(
            'value' => 0,
            'order' => 854
        ));

        $this->addElement('Hidden', 'imageName', array(
            'order' => 992
        ));

        $this->addElement('Hidden', 'imageenable', array(
            'value' => 0,
            'order' => 991
        ));
    }

}

?>