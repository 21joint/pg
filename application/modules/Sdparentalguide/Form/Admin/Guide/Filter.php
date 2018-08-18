<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Sdparentalguide
 * @author     Stars Developer
 */


class Sdparentalguide_Form_Admin_Guide_Filter extends Engine_Form
{
  public function init()
  {
    $this
      ->clearDecorators()
      ->addDecorator('FormElements')
      ->addDecorator('Form')
      ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'search'))
      ->addDecorator('HtmlTag2', array('tag' => 'div', 'class' => 'clear'))
      ;

    $this
      ->setAttribs(array(
        'id' => 'filter_form',
        'class' => 'global_form_box',
      ))
      ->setMethod('GET');

    $username = new Zend_Form_Element_Text('username');
    $username
      ->setLabel('User Name')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'));

    $displayname = new Zend_Form_Element_Text('displayname');
    $displayname
      ->setLabel('Display Name')
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
    
    $level = new Zend_Form_Element_Select('level');
    $level
      ->setLabel('Level')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'))
      ->setMultiOptions($levelOptions);
    
    $enabled = new Zend_Form_Element_Select('enabled');
    $enabled
      ->setLabel('User Approved')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'))
      ->setMultiOptions(array(
        '-1' => 'Select',
        '0' => 'Not Approved',
        '1' => 'Approved',
      ));
    
    $guide_title = new Zend_Form_Element_Text('guide_title');
    $guide_title
      ->setLabel('Guide Title')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'));
    
    $topic = new Zend_Form_Element_Text('topic');
    $topic
      ->setLabel('Guide Topic')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'));
    
    $featured = new Zend_Form_Element_Select('featured');
    $featured
      ->setLabel('Featured')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'))
      ->setMultiOptions(array(
          '-1' => 'Select',
          '1' => 'Yes',
          '0' => 'No'
      ))->setValue("-1");
    
    $sponsored = new Zend_Form_Element_Select('sponsored');
    $sponsored
      ->setLabel('Sponsored')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'))
      ->setMultiOptions(array(
          '-1' => 'Select',
          '1' => 'Yes',
          '0' => 'No'
      ))->setValue("-1");
    
    $new = new Zend_Form_Element_Select('newlabel');
    $new
      ->setLabel('New')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'))
      ->setMultiOptions(array(
          '-1' => 'Select',
          '1' => 'Yes',
          '0' => 'No'
      ))->setValue("-1");
    
    $approved = new Zend_Form_Element_Select('approved');
    $approved
      ->setLabel('Approved')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'))
      ->setMultiOptions(array(
          '-1' => 'Select',
          '1' => 'Yes',
          '0' => 'No'
      ))->setValue("-1");
    
    $status = new Zend_Form_Element_Select('status');
    $status
      ->setLabel('Status')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'))
      ->setMultiOptions(array(
          '-1' => 'Select',
          '0' => 'Only Open Guides',
          '1' => 'Only Close Guides'
      ))->setValue("-1");
    
    $browse_by = new Zend_Form_Element_Select('order');
    $browse_by
      ->setLabel('Browse By')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'))
      ->setMultiOptions(array(
          '' => 'Select',
          'view_count' => 'Most Viewed',
          'guide_id' => 'Most Recent'
      ));

//    $this->addElement('Hidden', 'order', array(
//      'order' => 10001,
//    ));
//
//    $this->addElement('Hidden', 'order_direction', array(
//      'order' => 10002,
//    ));

    $this->addElement('Hidden', 'user_id', array(
      'order' => 10003,
    ));
    
    $this->addElement('Hidden', 'level_id', array(
      'order' => 10004,
    ));

    $this->addElement('Hidden', 'topic_id', array(
      'order' => 10005,
    ));
    
    $this->addElements(array(
        $username,
        $displayname,
        $email,
        $level,
        $enabled,
        $guide_title,
        $topic,
        $featured,
        $sponsored,
        $new,
        $approved,
        $status,
        $browse_by,
    ));
    
    
    $this->addDisplayGroup(array('username','displayname','email','level',),'grp1');
    $this->addDisplayGroup(array('enabled','guide_title','topic','featured'),'grp2');
    $this->addDisplayGroup(array('sponsored','newlabel','approved','status'),'grp3');
    $this->addDisplayGroup(array('order'),'grp4');

    // Set default action without URL-specified params
    $params = array();
    foreach (array_keys($this->getValues()) as $key) {
      $params[$key] = null;
    }
    $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble($params));
  }
}