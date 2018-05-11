<?php
/**
 * EXTFOX
 *
 * @category   Application_Extensions
 * @package    Comments
 */

class Ggcommunity_Model_DbTable_Comments extends Core_Model_Item_DbTable_Abstract 
{
  protected $_rowClass = "Ggcommunity_Model_Comment";

  public function getComments($item, $type) {
    $comment_table = Engine_Api::_()->getDbTable('comments', 'ggcommunity');
    $select = $comment_table->select()
      ->where('parent_type = ?', $type)
      ->where('parent_id = ?', $item->getIdentity())
    ;
    $comments = $comment_table->fetchAll($select);
    return  $comments;
  }

  /**
   * Gets a paginator for comments
   *
   * @param Core_Model_Item_Abstract $user The user to get the messages for
   * @return Zend_Paginator
  */
  public function getCommentsPaginator($params = array())
  {
     $paginator = Zend_Paginator::factory($this->getCommentsSelect($params));
     if( !empty($params['page']) )
     {
       $paginator->setCurrentPageNumber($params['page']);
     }
     return $paginator;
  }

  public function getCommentsSelect($params = array())
  {

    // get comment table
    $commentTable = Engine_Api::_()->getDbtable('comments', 'ggcommunity');
    $commentName = $commentTable->info('name');

    $select = $commentTable->select()
      ->where('parent_type = ?', $params['type'])
      ->where('parent_id = ?', $params['id'])
    ;
    return $select;
  }
  
}