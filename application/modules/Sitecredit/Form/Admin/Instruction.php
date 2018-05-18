<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Instruction.php 2017-02-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Form_Admin_Instruction extends Engine_Form {

  public function init() {
    $coreSettings = Engine_Api::_()->getApi('settings', 'core');
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;

    $editorOptions['plugins'] = array(
      'table', 'fullscreen', 'media', 'preview', 'paste',
      'code',  'textcolor','link'
      );

    $editorOptions['toolbar1'] = array(
      'undo', 'redo', 'removeformat', 'pastetext', '|', 'code',
      'link', 'fullscreen',
      'preview'
      ); 

    
    $this->addElement('Dummy', 'ad_header2', array(
      'label' => 'Guidelines',
      'description' =>"Compose the steps to earn credits on your website. This will help your site users to understand how credit are earned, spend, redeemed etc. These guidelines will be visible on the page where you have placed 'How to Earn Credits' widget."
      ));
    $this->ad_header2->getDecorator('Label')->setOption('style', 'font-weight:bolder;width:100%');
    $this->addElement('TinyMce', 'sitecredit_instruction',array(
      'description'=>'Here, admin can set the instructions for the users, that how can they can earn credits on website. He can use widget “How to earn credits” to display this content.',

     // 'disableLoadDefaultDecorators' => true,
      'required' => false,
      'allowEmpty' => true,
      'decorators' => array(
        'ViewHelper'
        ),
      'editorOptions' => $editorOptions,
      'filters' => array(
        new Engine_Filter_Censor(),
        new Engine_Filter_Html(),
        )));
    $this->addElement('Dummy', 'ad_header1', array(
      'label' => 'Terms & Conditions ',
      'description' => "Compose ‘Terms & Conditions’ a user must keep in mind to earn credits on your site. These Terms & Conditions will be visible on the page where you have placed 'Terms & Conditions' widget."
      ));
    $this->ad_header1->getDecorator('Label')->setOption('style', 'font-weight:bolder;');

    
    $this->addElement('TinyMce', 'sitecredit_terms',array(
      'description'=>'Here, admin can set the terms and conditions for the users to use credits on his website.',
      'label'=>'Terms and conditions',
      'disableLoadDefaultDecorators' => true,
      'required' => false,
      'allowEmpty' => true,
      'decorators' => array(
        'ViewHelper'
        ),
      'editorOptions' => $editorOptions,
      'filters' => array(
        new Engine_Filter_Censor(),
        new Engine_Filter_Html(),
        )));
    
    $this->addElement('Button', 'submit', array(
      'label' => 'Save Changes',
      'type' => 'submit',
      'ignore' => true
      ));

  }

}
