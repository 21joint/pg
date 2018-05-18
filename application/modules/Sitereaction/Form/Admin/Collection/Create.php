<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Create.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitereaction_Form_Admin_Collection_Create extends Engine_Form {
  public function init() {

    // Init form
    $this
      ->setTitle('Add New Collection')
      ->setDescription('Here, add new collection for your users. You can make this collection available to users by default or in sticker store as per your requirement. 
')
      ->setAttrib('id', 'form-upload')
      ->setAttrib('name', 'admin_collection')
      ->setAttrib('enctype', 'multipart/form-data')
      ->setAttrib('class', 'global_form')
      ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
    ;


    // Init name
    $this->addElement('Text', 'title', array(
      'label' => 'Collection Title',
      'maxlength' => '40',
      'allowEmpty' => false,
      'required' => true,
      'filters' => array(
        new Engine_Filter_HtmlSpecialChars(),
        'StripTags',
        new Engine_Filter_Censor(),
        new Engine_Filter_StringLength(array('max' => '63')),
      )
    ));
    $this->addElement('Textarea', 'body', array(
      'label' => 'Description',
      'allowEmpty' => false,
      'required' => true,
      'filters' => array(
        'StripTags',
        new Engine_Filter_Censor(),
      ),
    ));

    // Start time
    $start = new Engine_Form_Element_CalendarDateTime('start_time');
    $start->setLabel("Start Time");
    $start->setAllowEmpty(false); 
    $date = (new Zend_Date());
    $tempDate = $date->toArray();
    $needToSubMinute = $tempDate['minute'] % 10;
    $date->subMinute($needToSubMinute);
    $start->setValue(date('Y-m-d H:i:s', $date->getTimestamp()));
    $this->addElement($start);
    // End time
    $end = new Engine_Form_Element_CalendarDateTime('end_time');
    $end->setLabel("End Time");
    $this->addElement($end);
    // Default
    $this->addElement('Checkbox', 'include', array(
      'label' => 'Do you want this collection to be available to users in their sticker bucket by default? [If it is not selected then this collection will display in Sticker Store]
',
      'value' => true
    ));

    $this->addElement('Checkbox', 'enabled', array(
      'label' => 'Do you want to enable this sticker collection?',
      'value' => true
    ));

    // Init file
    $this->addElement('FancyUpload', 'file');
    $this->file->addPrefixPath('Sitereaction_Form_Decorator', 'application/modules/Sitereaction/Form/Decorator', 'decorator');

    // Init submit
    $this->addElement('Button', 'submit', array(
      'label' => 'Save',
      'type' => 'submit',
      'order' => 999
    ));
  }

  public function saveValues($collection = null) {
    $values = $this->getValues();
    if (empty($collection)) {
      $params = Array();
      $params['title'] = $values['title'];
      $params['body'] = $values['body'];
      $params['start_time'] = $values['start_time'];
      if ($values['end_time'] === '0000-00-00') {
        $values['end_time'] = '2050-12-31 23:59:59'; 
      }
      $params['end_time'] = $values['end_time'];
      $params['enabled'] = $values['enabled'];
      $params['include'] = $values['include'];
      $collection = Engine_Api::_()->getDbtable('collections', 'sitereaction')->createRow();
      $collection->setFromArray($params);
      $collection->save();
    }

    // Do other stuff
    $count = 0;
    foreach ($values['file'] as $sticker_id) {
      $sticker = Engine_Api::_()->getItem("sitereaction_sticker", $sticker_id);
      if (!($sticker instanceof Core_Model_Item_Abstract) || !$sticker->getIdentity())
        continue;

      if (!$collection->sticker_id) {
        $collection->sticker_id = $sticker_id;
        $collection->save();
      }

      $sticker->collection_id = $collection->collection_id;
      $sticker->order = $sticker_id;
      $sticker->save();
      $count++;
    }

    return $collection;
  }

}
