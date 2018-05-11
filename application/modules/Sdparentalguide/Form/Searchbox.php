<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Sdparentalguide
 * @author     Stars Developer
 */


class Sdparentalguide_Form_Searchbox extends Engine_Form {

  public function init() {
    
    $this->addElement('Text', 'title', array(
        'label' => '',
        'autocomplete' => 'off',
         'filters' => array(
                     'StripTags',
                      new Engine_Filter_Censor(),
                    ),
         ));
    $this->addElement('Hidden', 'listing_id', array('order' => 6001,));
  }

}