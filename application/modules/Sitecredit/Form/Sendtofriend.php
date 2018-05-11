<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Sendtofriend.php 2017-02-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Form_Sendtofriend extends Engine_Form{

    public function init() {
        $coreSettings = Engine_Api::_()->getApi('settings', 'core');
        $this ->setDescription('You can send '.$GLOBALS['credits'].' to your friends.');

        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;


        $this->addElement('Text', 'friend_name', array(
            'label' => '',
            'placeholder' => 'Start typing the name...',
            'autocomplete' => 'off'));
        $this->addElement('Hidden', 'friend_id', array(
            'order' => 200,
            'filters' => array(
                'HtmlEntities'
                ),
            ));

        $this->addElement('Text', 'credit_point', array(
            'label' => ucfirst($GLOBALS['credit']).' Values',
            'description' => '',
            'allowEmpty' => FALSE,
            'validators'=>array(
                array('NotEmpty', true),
                array('Int', true),
                ),
            ));
        $this->addElement('Text', 'reason', array(
            'label' => 'Note',
            'description' => '',
            'allowEmpty' => true,
            'validators' => array(
                array('NotEmpty', false),
                ),
            'filters' => array(
                'StripTags',
                                //new Engine_Filter_HtmlSpecialChars(),
                new Engine_Filter_EnableLinks(),
                new Engine_Filter_Censor(),
                ),
            ));

        Engine_Form::addDefaultDecorators($this->friend_id);

        $this->addElement('Button', 'sendcredit', array(
            'label' => 'Send '.ucfirst($GLOBALS['credits']),
            'type' => 'submit',
            'ignore' => true
            ));

    }

}
