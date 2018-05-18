<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Feedbacks.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_Model_DbTable_Feedbacks extends Engine_Db_Table
{
	protected $_name = 'feedbacks';
  protected $_rowClass = 'Feedback_Model_Feedback';

 /**
   * Get feedbacks
   * @param array $params : contain desirable feedback info
   * @param array $customParams : contain desirable feedback info
   * @return  array of feedbacks
  */ 
  public function getFeedbacksPaginator($params = array(), $customParams = array())
  {
    $paginator = Zend_Paginator::factory($this->getFeedbacksSelect($params, $customParams));
    if( !empty($params['page']) ) {
      $paginator->setCurrentPageNumber($params['page']);
    }
    if( !empty($params['limit']) ) {
    	$paginator->setItemCountPerPage($params['limit']);
    }
    return $paginator;
  }
  
  /**
   * Get feedbacks
   * @param array $params : contain desirable feedback info
   * @param array $customParams : contain desirable feedback info
   * @return  array of feedbacks
   */ 
  public function getFeedbacksSelect($params = array(), $customParams = array())
  {  
    $table = Engine_Api::_()->getDbtable('feedbacks', 'feedback');
    $rName = $table->info('name');

		$tmTable = Engine_Api::_()->getDbtable('TagMaps', 'core');
    $tmName = $tmTable->info('name');
  
    if(!empty($params['can_vote'])) {
    	$voteTable = Engine_Api::_()->getItemTable('feedback_vote')->info('name');
      $viewer_id = $params['viewer_id'];
      $select = $table->select()
                      ->setIntegrityCheck(false)
                      ->from($rName)
                      ->joinLeft($voteTable, "$rName.feedback_id = $voteTable.feedback_id  AND $voteTable.voter_id = $viewer_id ", 'vote_id');
    }

		//CUSTOM FIELD WORK
		$searchTable = Engine_Api::_()->fields()->getTable('feedback', 'search')->info('name');
    $select = $select
                      ->setIntegrityCheck(false)
                      ->joinLeft($searchTable, "$searchTable.item_id = $rName.feedback_id");

		$coremodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('core');
    $coreversion = $coremodule->version;
    if ($coreversion > '4.1.7' && isset($customParams)) {
       //PROCESS OPTIONS
      $tmp = array();
      foreach ($customParams as $k => $v) {
        if (null == $v || '' == $v || (is_array($v) && count(array_filter($v)) == 0)) {
          continue;
        } else if (false !== strpos($k, '_field_')) {
          list($null, $field) = explode('_field_', $k);
          $tmp['field_' . $field] = $v;
        } else if (false !== strpos($k, '_alias_')) {
          list($null, $alias) = explode('_alias_', $k);
          $tmp[$alias] = $v;
        } else {
          $tmp[$k] = $v;
        }
      }
      $customParams = $tmp;
    }

    if (isset($customParams)) {
			$searchParts = Engine_Api::_()->fields()->getSearchQuery('feedback', $customParams);
      foreach( $searchParts as $k => $v ) {
        $select->where("`{$searchTable}`.{$k}", $v);
      } 
    }
		//END CUSTOM FIELD WORK

    if(!empty($params['can_vote'])) {
    	$select->order( !empty($params['orderby']) ? $params['orderby'].' DESC' : $rName.'.feedback_id DESC' );
    }
    else {
      $select = $table->select()->order( !empty($params['orderby']) ? $params['orderby'].' DESC' : $rName.'.feedback_id DESC' );
    }

		if(!empty($params['orderby']) && $params['orderby'] != 'feedback_id') {
			$select->order($rName.'.feedback_id DESC');
		}
    
    if(!empty($params['feedback_private'])) {
    	$select->where($rName.'.feedback_private = ?', 'public');
    }

    if( !empty($params['user_id']) && is_numeric($params['user_id']) ) {
      $select->where($rName.'.owner_id = ?', $params['user_id']);
    }

    if( !empty($params['user']) && $params['user'] instanceof User_Model_User ) {
      $select->where($rName.'.owner_id = ?', $params['user_id']->getIdentity());
    }

    if( !empty($params['users']) ) {
    	$str = (string) ( is_array($params['users']) ? "'" . join("', '", $params['users']) . "'" : $params['users'] );
    	$select->where($rName.'.owner_id in (?)', new Zend_Db_Expr($str));
    }

    if( !empty($params['category']) ) {
    	$select->where($rName.'.category_id = ?', $params['category']);
    }

		if (!empty($params['tag'])) {
      $select
              ->setIntegrityCheck(false)	
              ->joinLeft($tmName, "$tmName.resource_id = $rName.feedback_id")
              ->where($tmName . '.resource_type = ?', 'feedback')
              ->where($tmName . '.tag_id = ?', $params['tag']);
    }

    if( !empty($params['stat']) ) {
      $select->where($rName.'.stat_id = ?', $params['stat']);
    }
    
    if(isset($params['text']) && !empty($params['text']) ) {
      $params['search'] = $params['text'];
    }    
	
    if( !empty($params['search']) ) {
      $select->where($rName.".feedback_title LIKE ? OR ".$rName.".feedback_description LIKE ?", '%'.$params['search'].'%');
    }

    if( !empty($params['visible']) ) {
      $select->where($rName.".search = ?", $params['visible']);
    }

    return $select;
  }

	/**
   * Return feedback data
   *
   * @return feedback data for widget
   */
	public function getWidgetFeedbacks($params = array()) {

		//NUMBER OF FEEDBACKS
		$limit = $params['limit'];
    $orderBy = $params['orderby'];
    //Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.recent.widgets', 3);

		//FETCH FEEDBACKS
		$select = $this->select()
						->from($this->info('name'), array('feedback_id', 'feedback_title', 'owner_id', 'comment_count', 'total_votes', 'views'))
      			->where('feedback_private = ?', 'public')
      			->order("$orderBy DESC")
    				->limit($limit);
    return $this->fetchAll($select);
	}

	/**
   * Return statistics
   *
   * @return statistics for feedbacks
   */
	public function getStatistics() {

		$statistics = array();
		$statistics['total_votes'] = 0;
		$statistics['total_comments'] = 0;

		//COUNT TOTAL PUBLIC FEEDBACK 	 
    $tableFeedbackName = $this->info('name');
    $statistics['total_private'] = $this->select()
																				->from($tableFeedbackName, array('COUNT(feedback_id) AS total_private'))
																				->where($tableFeedbackName.'.feedback_private = ?', 'private')
																				//->group($tableFeedbackName.'.feedback_id')
																				->query()
																				->fetchColumn();

    //COUNT TOTAL PRIVATE FEEDBACK  	 
    $statistics['total_public'] = $this->select()
																				->from($tableFeedbackName, array('COUNT(feedback_id) AS total_public'))
																				->where($tableFeedbackName.'.feedback_private = ?', 'public')
																				//->group($tableFeedbackName.'.feedback_id')
																				->query()
																				->fetchColumn();

    //COUNT TOTAL FEEDBACK
    $statistics['total_feedback'] = $statistics['total_private'] + $statistics['total_public'];
    
    //COUNT TOTAL ANONYMOUS FEEDBACK  	 
    $statistics['total_anonymous'] = $this->select()
																					->from($tableFeedbackName, array('COUNT(feedback_id) AS total_anonymous'))
																					->where($tableFeedbackName.'.owner_id = ?', 0)
																					//->group($tableFeedbackName.'.feedback_id')
																					->query()
																					->fetchColumn();
    
    //COUNT TOTAL VOTES AND COMMENTS 	 
    $select = $this->select()
                    ->from($tableFeedbackName, array('SUM(total_votes) AS total_votes', 'SUM(comment_count) AS total_comments'));
    $row = $this->fetchRow($select);
		if(!empty($row)) {
			$statistics['total_votes'] = $row->total_votes;
			$statistics['total_comments'] = $row->total_comments;
		}

		if(empty($statistics['total_public'])) { $statistics['total_public'] = 0; }
		if(empty($statistics['total_private'])) { $statistics['total_private'] = 0; }
		if(empty($statistics['total_anonymous'])) { $statistics['total_anonymous'] = 0; }
		if(empty($statistics['total_feedback'])) { $statistics['total_feedback'] = 0; }

		return $statistics;
	}

	/**
   * Return feedbacks data
   *
	 * @param int feedback_id
	 * @param int category_id 
   * @return feedbacks data for same category
   */
	public function similarFeedbacks($params = array()) {

		$select = $this->select()
										->from($this->info('name'), array('feedback_title','owner_id', 'feedback_description','views','creation_date','modified_date','comment_count','feedback_id', 'total_votes'));
    
    $select->where('feedback_private != ?', 'private');
    
    if(isset($params['feedback_id']) && !empty($params['feedback_id'])) {
        $select->where('feedback_id != ?', $params['feedback_id']);
    }
    
    if(isset($params['category_id']) && !empty($params['category_id'])) {
        $select->where('category_id = ?', $params['category_id']);
    }
				
    if(isset($params['popularity']) && !empty($params['popularity'])) {
        $select->order($params['popularity'].' DESC');
    }
    
    if(isset($params['limit']) && !empty($params['limit'])) {
        $select->limit($params['limit']);
    }				

		return $this->fetchAll($select);
	}
  
	/**
   * Return feedbacks count
   *
	 * @param int owner_id
   * @return feedbacks count for same user
   */
	public function countUserPublicFeedbacks($owner_id) {

		$count = $this->select()
										->from($this->info('name'), array('COUNT(*) AS count'))
                    ->where('owner_id = ?', $owner_id)
										->where('feedback_private = ?', 'public')
                    ->query()
                    ->fetchColumn();
				
		return $count;
	}  

	/**
   * Return feedbacks data
   *
	 * @param int viewer_id 
   * @return feedbacks data for current viewer
   */
	public function viewerVotedFeedback($viewer_id) {

	  //GET FEEDBACK TABLE
    $tableName = $this->info('name');

		//GET FEEDBACK VOTE TABLE
    $tableVote = Engine_Api::_()->getitemtable('feedback_vote')->info('name');
     
		//MAKE QUERY
    $select = $this->select()
    				->setIntegrityCheck(false)
    				->from($tableName, array('feedback_id','feedback_title', 'owner_id', 'total_votes', 'comment_count', 'views', 'stat_id', 'featured'))
    				->joinLeft($tableVote, "$tableVote.feedback_id = $tableName.feedback_id AND $tableVote.voter_id = $viewer_id ", 'vote_id')
    				->where('feedback_private != ?', 'private')
    				->order('total_votes DESC')
    				->order('comment_count DESC')
    				->order('views DESC')
    				->order('feedback_id DESC')
    				->limit(5);

		//FETCH AND RETURN DATA
    return $this->fetchAll($select);
	}

}

