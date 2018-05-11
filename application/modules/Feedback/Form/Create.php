<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Create.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_Form_Create extends Engine_Form {

    public $_error = array();

    public function init() {

        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        if (!empty($viewer_id)) {
            $this->setTitle('Post a Feedback')
                    ->setDescription('Share with us your ideas, questions, problems and feedback.')
                    ->setAttrib('name', 'feedback_create');
        } else {
            $description = Zend_Registry::get('Zend_Translate')->_("To be able to display your Feedback publicly, please <a href='%s' target='_parent'>login</a> first.");
            $description = sprintf($description, Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login', true));
            $this->setTitle('Post a Feedback');
            $this->setDescription($description);
            $this->setAttrib('name', 'feedback_create');
            $this->loadDefaultDecorators();
            $this->getDecorator('Description')->setOption('escape', false);
        }
        $this->setAttrib('class', 'global_form seaocore_form_comment');
        $this->addElement('Text', 'feedback_title', array(
            'label' => 'Title*',
            'allowEmpty' => false,
            'required' => true,
            'filters' => array(
                'StripTags',
                new Engine_Filter_Censor(),
        )));

        $this->addElement('Textarea', 'feedback_description', array(
            'label' => 'Description*',
            'required' => true,
            'filters' => array(
                'StripTags',
                new Engine_Filter_HtmlSpecialChars(),
                new Engine_Filter_EnableLinks(),
                new Engine_Filter_Censor(),
            ),
        ));

        $feedback_tag = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.tag', 0);
        if (!empty($feedback_tag)) {
            $this->addElement('Text', 'tags', array(
                'label' => 'Tags',
                'autocomplete' => 'off',
                'description' => 'Separate tags with commas.',
                'filters' => array(
                    new Engine_Filter_Censor(),
                ),
            ));
            $this->tags->getDecorator("Description")->setOption("placement", "append");
        }

        //CUSTOM FIELD WORK
        if (!$this->_item) {
            $customFields = new Feedback_Form_Custom_Fields();
        } else {
            $customFields = new Feedback_Form_Custom_Fields(array(
                'item' => $this->getItem()
            ));
        }
        if (get_class($this) == 'Feedback_Form_Create') {
            $customFields->setIsCreation(true);
        }

        $this->addSubForms(array(
            'fields' => $customFields
        ));
        //END CUSTOM FIELD WORK

        $categories = Engine_Api::_()->getDbtable('categories', 'feedback')->getCategories();
        if (count($categories) != 0) {
            $categories_prepared[0] = "";
            foreach ($categories as $category) {
                $categories_prepared[$category->category_id] = $category->category_name;
            }

            $this->addElement('Select', 'category_id', array(
                'label' => 'Category',
                'multiOptions' => $categories_prepared
            ));
        }

        $feedback_severity = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.severity', 1);
        if ($feedback_severity) {
            $severities = Engine_Api::_()->getItemTable('feedback_severity')->getSeverities();
            if (count($severities) != 0) {
                $severities_prepared[0] = "";
                foreach ($severities as $severity) {
                    $severities_prepared[$severity->severity_id] = $severity->severity_name;
                }

                $this->addElement('Select', 'severity_id', array(
                    'label' => 'Severity',
                    'multiOptions' => $severities_prepared
                ));
            }
        }

        $feedback_default_visibility = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.default.visibility', 'public');
        if ($feedback_default_visibility == 'public') {
            $this->addElement('Select', 'default_visibility', array(
                'label' => 'Feedback Visibility',
                'multiOptions' => array('public' => "Public", 'private' => "Private"),
                'description' => 'Only public feedback will be visible to others.'
            ));
            $this->default_visibility->getDecorator('Description')->setOption('placement', 'append');
        }

        $this->addElement('Text', 'anonymous_email', array(
            'label' => 'Email*',
            'required' => true,
            'allowEmpty' => false,
            'validators' => array(
                array('NotEmpty', true),
                array('EmailAddress', true))
        ));
        $this->anonymous_email->getValidator('EmailAddress')->getHostnameValidator()->setValidateTld(false);

        $this->addElement('Text', 'anonymous_name', array(
            'label' => 'Full Name*',
            'allowEmpty' => false,
            'required' => true,
            'filters' => array(
                'StripTags',
                new Engine_Filter_Censor(),
                new Engine_Filter_StringLength(array('max' => '63')),
        )));

        $feedback_post = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.post', 0);
        $feedback_option_post = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.option.post', 0);
        if ($feedback_post == 0 && $feedback_option_post == 1 && $viewer_id == 0) {


            if (Engine_Api::_()->hasModuleBootstrap('siterecaptcha')) {
                Zend_Registry::get('Zend_View')->recaptcha($this);
            } else {
                $this->addElement('captcha', 'captcha', Engine_Api::_()->feedback()->getCaptchaOptions());
            }
        }

        $this->addElement('Button', 'submit', array(
            'label' => 'Post Feedback',
            'type' => 'submit',
            'ignore' => true,
            'decorators' => array('ViewHelper')
        ));

        $this->addElement('Cancel', 'cancel', array(
            'label' => 'cancel',
            'link' => true,
            'prependText' => ' or ',
            'href' => '',
            'onClick' => 'javascript:parent.Smoothbox.close();',
            'decorators' => array(
                'ViewHelper'
            )
        ));
        $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
        $button_group = $this->getDisplayGroup('buttons');
    }

}
