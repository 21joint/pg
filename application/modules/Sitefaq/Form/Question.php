<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Question.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitefaq_Form_Question extends Engine_Form {

    public function init() {
        $this->setTitle('Ask a Question');
        $this->setDescription("Have a question for us? Ask from below and we will get back to you.");
        $this->setAttrib('class', 'global_form');
        $this->setAttrib('id', 'core_form_contact');
        $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();

        $this->addElement('Hidden', 'user_id', array(
            'order' => 10001,
            'value' => $viewer_id
        ));

        if (empty($viewer_id)) {
            $this->addElement('Text', 'anonymous_name', array(
                'label' => 'Name',
                'allowEmpty' => false,
                'required' => true,
                'filters' => array(
                    'StripTags',
                    new Engine_Filter_Censor(),
                    new Engine_Filter_StringLength(array('max' => '63')),
            )));

            $this->addElement('Text', 'anonymous_email', array(
                'label' => 'Email',
                'required' => true,
                'allowEmpty' => false,
                'validators' => array(
                    array('NotEmpty', true),
                    array('EmailAddress', true))
            ));

            $this->anonymous_email->getValidator('EmailAddress')->getHostnameValidator()->setValidateTld(false);
        }

        $this->addElement('Textarea', 'title', array(
            'label' => 'Question',
            'allowEmpty' => false,
            'required' => true,
            'filters' => array(
                'StripTags',
                new Engine_Filter_Censor(),
        )));

        $show_captcha = Engine_Api::_()->getApi('settings', 'core')->core_spam_contact;
        if ($show_captcha && ($show_captcha > 1 || !Engine_Api::_()->user()->getViewer()->getIdentity() )) {
            if (Engine_Api::_()->hasModuleBootstrap('siterecaptcha')) {
                Zend_Registry::get('Zend_View')->recaptcha($this);
            } else {
                $this->addElement('captcha', 'captcha', array(
                    'description' => 'Please type the characters you see in the image.',
                    'captcha' => 'image',
                    'required' => true,
                    'captchaOptions' => array(
                        'wordLen' => 6,
                        'fontSize' => '30',
                        'timeout' => 300,
                        'imgDir' => APPLICATION_PATH . '/public/temporary/',
                        'imgUrl' => $this->getView()->baseUrl() . '/public/temporary',
                        'font' => APPLICATION_PATH . '/application/modules/Core/externals/fonts/arial.ttf'
                )));
            }
        }

        $this->addElement('Button', 'submit', array(
            'label' => 'Ask',
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

        $this->addDisplayGroup(array('submit', 'cancel'), 'buttons', array(
            'decorators' => array(
                'FormElements',
                'DivDivDivWrapper',
            ),
        ));
    }

}
