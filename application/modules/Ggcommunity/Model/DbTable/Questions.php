<?php
/**
 * EXTFOX
 *
 * @category   Application_Extensions
 * @package    Questions
 */

class Ggcommunity_Model_DbTable_Questions extends Core_Model_Item_DbTable_Abstract 
{
  protected $_rowClass = "Ggcommunity_Model_Question";

  /**
   * Gets a paginator for questions
   *
   * @param Core_Model_Item_Abstract $user The user to get the messages for
   * @return Zend_Paginator
  */
  public function getQuestionsPaginator($params = array())
  {
     $paginator = Zend_Paginator::factory($this->getQuestionsSelect($params));
     if( !empty($params['page']) )
     {
       $paginator->setCurrentPageNumber($params['page']);
     }
     return $paginator;
  }

  public function getQuestionsSelect($params = array())
  {
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    
    // get questions table
    $questionTable = Engine_Api::_()->getDbtable('questions', 'ggcommunity');
    $questionName = $questionTable->info('name');

    // get answers table
    $answerTable = Engine_Api::_()->getDbtable('answers', 'ggcommunity');
    $answerName = $answerTable->info('name');

    // get user table
    $userTable = Engine_Api::_()->getDbtable('users', 'user');
    $userName = $userTable->info('name');

    if(!empty($params['front'])) {


      if ( $params['front']['param'] == 'trending' ) {
        $select = $questionTable->select()
          ->setIntegrityCheck(false)
          ->from($answerTable, new Zend_Db_Expr($answerName . '.parent_id' .',' .' COUNT(*) AS answers'))
          ->joinLeft($questionName, $questionName.'.question_id =' . $answerName. '.parent_id')
          ->where($answerName . '.creation_date  > ?',new Zend_Db_Expr('DATE_SUB(NOW(), INTERVAL 2 WEEK)') )
          ->where($questionName . '.draft  = ?', 0 )
          ->group($answerName . '.parent_id')
          ->order('answers DESC')
        ;
      } else {
        $select = $questionTable->select()
          ->where($questionName. '.draft = ?', 0)
          ->order($questionName.".question_id DESC")
        ;
      }

      if( !empty($params['front']['query']) )
      {
  
        $select->where($questionName.'.title LIKE ?', '%' . $params['front']['query']. '%');
      }
      
      if( !empty($params['front']['owner']) )
      {
        $select->where($questionName.'.user_id = ?', $viewer_id);
      }

      // For unanswered widget
      if( $params['front']['param'] == 'unanswered' )
      {    
        //  create query with questions that has answers but none of them is choosen as best
        $select 
          ->where($questionName.".question_id >= ?", 1)
          ->where($questionName.".accepted_answer = ?", 0)
        ;
  
      }

      // If is selected to be list by latest
      if( $params['front']['param'] == 'latest' )
      {
        $select->where($questionName.".question_id >= ?", 1);
      }

      // If is selected to be listed only active
      if($params['front']['param'] == 'active') 
      {
        $select 
          ->where($questionName.".question_id >= ?", 1)
          ->where($questionName. ".open = ?", 1)
        ;

      }

      // If is selected to be listed only active
      if($params['front']['param'] == 'closed') 
      {
        $select 
          ->where($questionName.".question_id >= ?", 1)
          ->where($questionName. ".open = ?", 0)
        ;
      }
      
      return $select;
    }

    $select = $questionTable->select()
      ->order( !empty($params['sort_by']) ? $questionName. '.' . $params['sort_by'] .' DESC' : $questionName.'.question_id DESC' )
    ;
   
    // if some of parametars username/first/last/email/member level are added made left join on questions and users
    if( !empty($params['username']) || !empty($params['first_name']) || !empty($params['last_name']) || !empty($params['email']) || !empty($params['member_level']) ) {
      $select
        ->setIntegrityCheck(false)
        ->from($questionName)
        ->joinLeft($userName, "$userName.user_id = $questionName.user_id")
        ->order( !empty($params['sort_by']) ? $questionName. '.' . $params['sort_by'] .' DESC' : $questionName.'.question_id DESC' )
      ;
    }

    // if username is added in search criteria
    if( !empty($params['username']) )
    {
      $select->where($userName.'.username = ?', $params['username']);
    }

    // if first name is added in search criteria
    if( !empty($params['first_name']) )
    {
      $select->where($userName.'.displayname LIKE ?',  $params['first_name'].'%');
    }

    // if last name is added in search criteria
    if( !empty($params['last_name']) )
    {
      $select->where($userName.'.displayname LIKE ?', '%'. $params['last_name']); 
    }

    // if email is added in search criteria
    if( !empty($params['email']) )
    {
      $select->where($userName.'.email = ?',  $params['email']); 
    }

    // if member_level is added in search criteria
    if( !empty($params['member_level']) )
    {
      $select->where($userName.'.level_id = ?',  $params['member_level']); 
    }

    // if question_title is added in search criteria
    if( !empty($params['question_title']) )
    {
      $select->where($questionName.'.title LIKE ?', '%' . $params['question_title']. '%');
    }

    // Search in approved/ non-approved questions
    if( isset($params['approved']) && $params['approved'] != -1 )
    {
      $select->where($questionName.'.approved = ?', $params['approved']);
    }

    // Search in featured/ non-featured questions
    if( isset($params['featured'])  && $params['featured'] != -1  )
    {
      $select->where($questionName.'.featured = ?', $params['featured']);
    }

    // Search in sponsored/ non-sponsored questions
    if( isset($params['sponsored'])  && $params['sponsored'] != -1  )
    {
      $select->where($questionName.'.sponsored = ?', $params['sponsored']);
    }

    // Search in opened/closed questions
    if( isset($params['opened']) && $params['opened'] != -1 )
    {
      $select->where($questionName.'.open = ?', $params['opened']);
    }
   
    return $select;
  }

  public function getQuestionsByTopic($queston_id,$ids) {
    
    // get questions table
    $questionTable = Engine_Api::_()->getDbtable('topicmaps', 'ggcommunity');
    $select = $questionTable->select()
      ->where('parent_id <> ?', $queston_id)
      ->where('topic_id IN (?)', $ids)
      ->group('parent_id')
      ->order('parent_id DESC')
      ->limit(10)
    ;

    $rows = $questionTable->fetchAll($select);
   
    return $rows;

  }
  
 

}