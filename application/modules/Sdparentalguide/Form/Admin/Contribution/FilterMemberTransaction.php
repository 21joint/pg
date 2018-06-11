<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Sdparentalguide
 * @author     Stars Developer
 */


class Sdparentalguide_Form_Admin_Contribution_FilterMemberTransaction extends Engine_Form
{
  public function init() {

        $this->setAttribs(array(
            'id' => 'credit_transaction_member_search_form',
            'class' => 'global_form_box',
            'name' => 'credit_transaction_member_search_form',
            ))->setMethod('POST');

        $username = new Zend_Form_Element_Text('username');
        $username
          ->setLabel('User Name')
          ->clearDecorators()
          ->addDecorator('ViewHelper')
          ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
          ->addDecorator('HtmlTag', array('tag' => 'div'));

        $firstname = new Zend_Form_Element_Text('firstname');
        $firstname
          ->setLabel('First Name')
          ->clearDecorators()
          ->addDecorator('ViewHelper')
          ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
          ->addDecorator('HtmlTag', array('tag' => 'div'));
        
        $lastname = new Zend_Form_Element_Text('lastname');
        $lastname
          ->setLabel('Last Name')
          ->clearDecorators()
          ->addDecorator('ViewHelper')
          ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
          ->addDecorator('HtmlTag', array('tag' => 'div'));

        $email = new Zend_Form_Element_Text('email');
        $email
          ->setLabel('Email')
          ->clearDecorators()
          ->addDecorator('ViewHelper')
          ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
          ->addDecorator('HtmlTag', array('tag' => 'div'))
          ->setAttrib("inputType","email");
    
        $levelOptions = array('' => 'Select');
        foreach( Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll() as $level ) {
          $levelOptions[$level->level_id] = $level->getTitle();
        }
        
        $member_level = new Zend_Form_Element_Select('member_level');
        $member_level
          ->setLabel('Member Level')
          ->clearDecorators()
          ->addDecorator('ViewHelper')
          ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
          ->addDecorator('HtmlTag', array('tag' => 'div'))
          ->setMultiOptions($levelOptions);
        
        $topic = new Zend_Form_Element_Text('topic');
        $topic
          ->setLabel('Topic')
          ->clearDecorators()
          ->addDecorator('ViewHelper')
          ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
          ->addDecorator('HtmlTag', array('tag' => 'div'));
        
        $this->addElement('Button', 'search', array(
            'label' => 'Search',
            'type' => 'submit',
            'ignore' => true,
        ));
        
        $this->addElement('Hidden', 'order', array(
            'order' => 10001,
            ));

        $this->addElement('Hidden', 'order_direction', array(
            'order' => 10002,
            ));
        
        $this->addElements(array( $username, $firstname, $lastname, $email, $member_level, $topic));
        
        $this->addDisplayGroup(array('username','firstname','lastname','email'),'grp1');
        $this->addDisplayGroup(array('member_level', 'topic','search'),'grp2');
        
        $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
    }
}