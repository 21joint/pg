<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitegroup
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Photoedit.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitegroup_Form_Photo_Photoedit extends Engine_Form {

  public function init() {

    $this
            ->setTitle('Edit Photo');

    $this->addElement('Text', 'title', array(
        'label' => 'Title',
         'filters' => array(
            'StripTags',
            new Engine_Filter_Censor(),
           ),
    ));

    $this->addElement('Textarea', 'description', array(
        'label' => 'Caption',
         'filters' => array(
            'StripTags',
            new Engine_Filter_Censor(),
           ),
    ));

    $this->addElement('Button', 'submit', array(
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array('ViewHelper'),
        'label' => 'Save Changes',
    ));

    $this->addElement('Cancel', 'cancel', array(
        'prependText' => ' or ',
        'label' => 'cancel',
        'link' => true,
        'href' => '',
        'onclick' => 'parent.Smoothbox.close();',
        'decorators' => array(
            'ViewHelper'
        ),
    ));

    $this->addDisplayGroup(array(
        'submit',
        'cancel'
            ), 'buttons');
  }

}

?>