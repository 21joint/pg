<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageevent
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Edit.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageevent_Form_Photo_EditPhoto extends Engine_Form {

  protected $_isArray = true;

  public function init() {

    $this->clearDecorators()
            ->addDecorator('FormElements');

    $this->addElement('Text', 'title', array(
        'label' => 'Title',
        'filters' => array(
            'StripTags',
            new Engine_Filter_Censor(),
            new Engine_Filter_HtmlSpecialChars(),
        ),
        'decorators' => array(
            'ViewHelper',
            array('HtmlTag', array('tag' => 'div', 'class' => 'sitepageevents_editphotos_title_input')),
            array('Label', array('tag' => 'div', 'placement' => 'PREPEND', 'class' => 'sitepageevents_editphotos_title')),
        ),
    ));

    $this->addElement('Textarea', 'description', array(
        'label' => 'Image Description',
        'rows' => 2,
        'cols' => 120,
        'filters' => array(
            'StripTags',
            new Engine_Filter_Censor(),
        ),
        'decorators' => array(
            'ViewHelper',
            array('HtmlTag', array('tag' => 'div', 'class' => 'sitepageevents_editphotos_caption_input')),
            array('Label', array('tag' => 'div', 'placement' => 'PREPEND', 'class' => 'sitepageevents_editphotos_caption_label')),
        ),
    ));

    $this->addElement('Checkbox', 'delete', array(
        'label' => "Delete Photo",
        'decorators' => array(
            'ViewHelper',
            array('Label', array('placement' => 'APPEND')),
            array('HtmlTag', array('tag' => 'div', 'class' => 'photo-delete-wrapper')),
        ),
    ));

    $this->addElement('Hidden', 'photo_id', array(
         'order' => 900,
        'validators' => array(
            'Int',
        )
    ));
  }

}

?>