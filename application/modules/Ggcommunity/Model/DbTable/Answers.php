<?php
/**
 * EXTFOX
 *
 * @category   Application_Extensions
 * @package    Answers
 */

class Ggcommunity_Model_DbTable_Answers extends Core_Model_Item_DbTable_Abstract 
{
  protected $_rowClass = "Ggcommunity_Model_Answer";

  public function getBest($question_id) {
    $answer_table = Engine_Api::_()->getDbtable('answers', 'ggcommunity');
    $select = $answer_table->select()
      ->where('parent_id = ?', $question_id)
      ->where('accepted = ?', 1)
    ;
    $row = $answer_table->fetchRow($select);

    if($row) {
      return $row;
    } else {
      return false;
    }
 
  }
  
}