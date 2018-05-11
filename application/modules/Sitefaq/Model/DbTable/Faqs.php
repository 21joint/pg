<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Sitefaqs.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_Model_DbTable_Faqs extends Engine_Db_Table
{
	protected $_name = 'sitefaq_faqs';
  protected $_rowClass = 'Sitefaq_Model_Faq';

  /**
   * Get sitefaq detail
   * @param array $params : contain desirable sitefaq info
   * @return  object of sitefaq
   */
  public function getSitefaqsPaginator($params = array(), $customParams = array()) {

    $paginator = Zend_Paginator::factory($this->getSitefaqsSelect($params, $customParams));

    if (!empty($params['page'])) {
      $paginator->setCurrentPageNumber($params['page']);
    }
    if (!empty($params['limit'])) {
      $paginator->setItemCountPerPage($params['limit']);
    }
    return $paginator;
  }

  /**
   * Get sitefaqs 
   * @param array $params : contain desirable sitefaq info
   * @return  array of sitefaqs
   */
  public function getSitefaqsSelect($params = array(), $customParams = array()) {

		//GET FAQ TABLE
    $tableSitefaqName = $this->info('name');

		//GET TAG MAPS TABLE NAME
		$tableTagmapsName = Engine_Api::_()->getDbtable('TagMaps', 'core')->info('name');

		//MAKE QUERY
    $select = $this->select();

		//GET COLUMN ACCORDING TO LANGUAGE
		$title_column = Engine_Api::_()->sitefaq()->getLanguageColumn('title');
		$body_column = Engine_Api::_()->sitefaq()->getLanguageColumn('body');

		if(!empty($params['orderby']) && $params['orderby'] == 'title') {
      $select = $this->select();

			if($title_column != 'title') {
				$select->order($tableSitefaqName.'.'.$title_column.' ASC');
			}

			$select->order($tableSitefaqName.'.title ASC');
			$select->order($tableSitefaqName.'.faq_id DESC');
    }
		elseif(!empty($params['orderby']) && $params['orderby'] == 'RAND()') {
      $select = $this->select()
                      ->order($params['orderby']);
    }
		elseif(!empty($params['orderby']) && $params['orderby'] != 'faq_id') {
      $select = $this->select()
                      ->order($tableSitefaqName.'.'.$params['orderby'].' DESC')
											->order($tableSitefaqName.'.faq_id DESC');
    }
		else {
			$select = $this->select()
											->order($tableSitefaqName.'.faq_id DESC');
		}

		if($title_column != 'title') {
			$select = $select->setIntegrityCheck(false)->from($tableSitefaqName, array('faq_id', 'owner_id', 'title', 'body', "$title_column", "$body_column", 'category_id', 'subcategory_id', 'subsubcategory_id', 'modified_date', 'search', 'approved', 'draft', 'featured', 'comment_count', 'view_count', 'like_count', 'rating', 'helpful'));
		}
		else {
			$select = $select->setIntegrityCheck(false)->from($tableSitefaqName, array('faq_id', 'owner_id', 'title', 'body', 'category_id', 'subcategory_id', 'subsubcategory_id', 'modified_date', 'search', 'approved', 'draft', 'featured', 'comment_count', 'view_count', 'like_count', 'rating', 'helpful'));
		}

		if (!empty($params['search'])) {

      $tableTagsName = Engine_Api::_()->getDbtable('Tags', 'core')->info('name');
      $select
              ->setIntegrityCheck(false)
              ->joinLeft($tableTagmapsName, "$tableTagmapsName.resource_id = $tableSitefaqName.faq_id and " . $tableTagmapsName . ".resource_type = 'sitefaq_faq'", null)
              ->joinLeft($tableTagsName, "$tableTagsName.tag_id = $tableTagmapsName.tag_id", array($tableTagsName . ".text"));

			if($title_column != 'title') {
				$select->where($tableSitefaqName.".title LIKE ? OR " .$tableSitefaqName.".body LIKE ? OR " .$tableSitefaqName.".$title_column LIKE ? OR " . $tableSitefaqName.".$body_column LIKE ? OR " .$tableTagsName.".text LIKE ?", '%' . $params['search'] . '%');
			}
			else {
				$select->where($tableSitefaqName.".title LIKE ? OR " .$tableSitefaqName.".body LIKE ? OR " .$tableTagsName.".text LIKE ?", '%' . $params['search'] . '%');
			}
		}

		if (isset($customParams)) {

			//CUSTOM FIELD WORK
			$searchTable = Engine_Api::_()->fields()->getTable('sitefaq_faq', 'search')->info('name');
			$select = $select
												->setIntegrityCheck(false)
												->joinLeft($searchTable, "$searchTable.item_id = $tableSitefaqName.faq_id");

			$coremodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('core');
			$coreversion = $coremodule->version;
			if ($coreversion > '4.1.7') {
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

			$searchParts = Engine_Api::_()->fields()->getSearchQuery('sitefaq_faq', $customParams);
			foreach( $searchParts as $k => $v ) {
				$select->where("`{$searchTable}`.{$k}", $v);
			}
			//END CUSTOM FIELD WORK
		}

    if (!empty($params['tag_id'])) {
			$select
							->setIntegrityCheck(false)	
							->joinLeft($tableTagmapsName, "$tableTagmapsName.resource_id = $tableSitefaqName.faq_id")
							->where($tableTagmapsName.'.resource_type = ?', 'sitefaq_faq')
							->where($tableTagmapsName.'.tag_id = ?', $params['tag_id']);
    }

    if (isset($params['owner_id']) && !empty($params['owner_id'])) {
      $select->where($tableSitefaqName . '.owner_id = ?', $params['owner_id']);
    }

    if (isset($params['approved'])) {
      $select->where($tableSitefaqName . '.approved = ?', $params['approved']);
    }

    if (isset($params['featured'])) {
      $select->where($tableSitefaqName . '.featured = ?', 1);
    }

    if (isset($params['draft'])) {
      $select->where($tableSitefaqName . '.draft = ?', $params['draft']);
    }

    if (isset($params['searchable'])) {
      $select->where($tableSitefaqName . '.search = ?', $params['searchable']);
    }

		if(isset($params['member_levels']) && !empty($params['member_levels'])) {
			foreach($params['member_levels'] as $member_level) {
				$levelSqlArray[] = "$tableSitefaqName.member_levels LIKE '%".'"'.$member_level.'"'."%'";
			}
			$select->where("(".join(") or (", $levelSqlArray).")");
		}

		if(isset($params['profile_types']) && !empty($params['profile_types'])) {
			foreach($params['profile_types'] as $profile_type) {
				$profileSqlArray[] = "$tableSitefaqName.profile_types LIKE '%".'"'.$profile_type.'"'."%'";
			}
			$select->where("(".join(") or (", $profileSqlArray).")");
		}

		if(isset($params['networks']) && !empty($params['networks'])) {
			foreach($params['networks'] as $network) {
				$networkSqlArray[] = "$tableSitefaqName.networks LIKE '%".'"'.$network.'"'."%'";
			}
			$select->where("(".join(") or (", $networkSqlArray).")");
		}

    if (isset($params['category']) && !empty($params['category'])) {
      $select->where($tableSitefaqName.'.category_id LIKE ?', '%"'.$params['category'].'"%');
    }

    if (isset($params['subcategory']) && !empty($params['subcategory'])) {
      $select->where($tableSitefaqName.'.subcategory_id LIKE ?', '%"'.$params['subcategory'].'"%');
    }

    if (isset($params['subsubcategory']) && !empty($params['subsubcategory'])) {
      $select->where($tableSitefaqName.'.subsubcategory_id LIKE ?', '%"'.$params['subsubcategory'].'"%');
    }

		if(isset($params['category_id']) && !empty($params['category_id'])) {
			$select->where($tableSitefaqName.'.category_id LIKE ?', '%"'.$params['category_id'].'"%');
		}

    if (isset($params['subcategory_id']) && !empty($params['subcategory_id'])) {
      $select->where($tableSitefaqName.'.subcategory_id LIKE ?', '%"'.$params['subcategory_id'].'"%');
    }

    if (isset($params['subsubcategory_id']) && !empty($params['subsubcategory_id'])) {
      $select->where($tableSitefaqName.'.subsubcategory_id LIKE ?', '%"'.$params['subsubcategory_id'].'"%');
    }

		$select = $select->group("$tableSitefaqName.faq_id");

		//RETURN QUERY
    return $select;
  }

  /**
   * Get pages based on category
   * @param string $totalFaqs : number of faqs to fetch
   * @param int $category_id : category id
   * @param char $popularity : result sorting based on views, reviews, likes, comments
   * @param char $interval : time interval
   * @param string $sqlTimeStr : Time durating string for where clause 
   * @param int $featured : Show only featured or not
   */
  public function faqsBySettings($category_id, $popularity, $interval, $sqlTimeStr, $totalFaqs, $featured, $params = null) {

		//GET FAQ TABLE NAME
    $sitefaqTableName = $this->info('name');

		//GET COLUMN ACCORDING TO LANGUAGE
		$title_column = Engine_Api::_()->sitefaq()->getLanguageColumn('title');

    if ($interval == 'overall' || $popularity == 'view_count' || $popularity == 'RAND()' || $popularity == 'ratings' || $popularity == 'helpful' || $popularity == 'modified_date') {
      $select = $this->select();

			if($title_column != 'title') {
				$select->from($sitefaqTableName, array("faq_id", "$title_column", "title", "featured", "like_count", "view_count", "comment_count", "rating", "helpful", "category_id", "subcategory_id", "subsubcategory_id"));
			}
			else {
				$select->from($sitefaqTableName, array("faq_id", "title", "featured", "like_count", "view_count", "comment_count", "rating", "helpful", "category_id", "subcategory_id", "subsubcategory_id"));
			}

			if($popularity == 'RAND()') {
				$select->order("$popularity");
			}
			else {
        $select->order("$sitefaqTableName.$popularity DESC");
			}
    } 
		elseif($popularity == 'like_count' || $popularity == 'comment_count') {

      if ($popularity == 'like_count') {
        $popularityType = 'like';
      } else {
        $popularityType = 'comment';
      }

      $id = $popularityType . "_id";

      $popularityTable = Engine_Api::_()->getDbtable("$popularityType" . "s", 'core');
      $popularityTableName = $popularityTable->info('name');

      $select = $this->select()
              ->setIntegrityCheck(false);

			if($title_column != 'title') {
				$select->from($sitefaqTableName, array("faq_id", "$title_column", 'title', "featured", "like_count", "view_count", "comment_count", "rating", "helpful", "category_id", "subcategory_id", "subsubcategory_id", "$popularity AS populirityCount"));
			}
			else {
				$select->from($sitefaqTableName, array("faq_id", 'title', "featured", "like_count", "view_count", "comment_count", "rating", "helpful", "category_id", "subcategory_id", "subsubcategory_id", "$popularity AS populirityCount"));
			}

      $select->joinLeft($popularityTableName, $popularityTableName . '.resource_id = ' . $sitefaqTableName . '.faq_id', array("COUNT($id) as total_count"))
						->where($popularityTableName . "$sqlTimeStr  or " . $popularityTableName . '.creation_date is null')
						->group($sitefaqTableName . '.faq_id')
						->order("total_count DESC");
    }

		if(!empty($category_id)) {
			$select->where($sitefaqTableName.'.category_id LIKE ?', '%"'.$category_id.'"%');
		}

    if (!empty($featured)) {
      $select = $select->where($sitefaqTableName.'.featured = ?', 1);
    }

		if(isset($params['member_levels']) && !empty($params['member_levels'])) {
			foreach($params['member_levels'] as $member_level) {
				$levelSqlArray[] = "$sitefaqTableName.member_levels LIKE '%".'"'.$member_level.'"'."%'";
			}
			$select->where("(".join(") or (", $levelSqlArray).")");
		}

		if(isset($params['profile_types']) && !empty($params['profile_types'])) {
			foreach($params['profile_types'] as $profile_type) {
				$profileSqlArray[] = "$sitefaqTableName.profile_types LIKE '%".'"'.$profile_type.'"'."%'";
			}
			$select->where("(".join(") or (", $profileSqlArray).")");
		}

		if(isset($params['networks']) && !empty($params['networks'])) {
			foreach($params['networks'] as $network) {
				$networkSqlArray[] = "$sitefaqTableName.networks LIKE '%".'"'.$network.'"'."%'";
			}
			$select->where("(".join(") or (", $networkSqlArray).")");
		}

		$select->where($sitefaqTableName.'.approved = ?', 1)->where($sitefaqTableName.'.draft = ?', 0)->where($sitefaqTableName . '.search = ?', 1);

		if($popularity != 'RAND()' && $popularity != 'creation_date') {
			$select->order($sitefaqTableName . ".creation_date DESC");
		}

		if($popularity != 'RAND()' && $popularity != 'creation_date' && empty($featured)) {
			$select = $select->where($popularity . ' > ?', 0);
		}

		if(!empty($totalFaqs)) {
			$select->limit($totalFaqs);
		}

    return $this->fetchAll($select);
  }

 /**
   * Return sitefaq data
   *
   * @param array params
   * @return Zend_Db_Table_Select
   */
  public function widgetSitefaqsData($params = array()) {

		//GET TABLE NAME
		$tableSitefaqName = $this->info('name');

		//GET COLUMN ACCORDING TO LANGUAGE
		$title_column = Engine_Api::_()->sitefaq()->getLanguageColumn('title');

		//MAKE QUERY
		if($title_column != 'title') {
			$select = $this->select()->from($tableSitefaqName, array("faq_id", "$title_column", "title", "category_id", "subcategory_id", "subsubcategory_id", "view_count", "comment_count", "like_count", "rating", "helpful"));
		}
		else {
			$select = $this->select()->from($tableSitefaqName, array("faq_id", "title", "category_id", "subcategory_id", "subsubcategory_id", "view_count", "comment_count", "like_count", "rating", "helpful"));
		}

		//SELECT ONLY AUTHENTICATE FAQs
		$select = $select->where('approved = ?', 1)->where('draft = ?', 0)->where('search = ?', 1);

    if (isset($params['zero_count']) && !empty($params['zero_count'])) {
      $select = $select->where($params['zero_count'] . ' != ?', 0);
    }

    if (isset($params['owner_id']) && !empty($params['owner_id'])) {
      $select = $select->where('owner_id = ?', $params['owner_id']);
    }

    if (isset($params['faq_id']) && !empty($params['faq_id'])) {
      $select = $select->where('faq_id != ?', $params['faq_id']);
    }

    if (isset($params['featured']) && !empty($params['featured'])) {
      $select = $select->where('featured = ?', 1);
    }

		if((isset($params['category_id']) && !empty($params['category_id']))) {
			$select->where('category_id LIKE ?', '%"'.$params['category_id'].'"%');
		}

		if((isset($params['categories']) && !empty($params['categories']))) {
			foreach($params['categories'] as $category_id) {
				$categorySqlArray[] = "category_id LIKE '%".'"'.$category_id.'"'."%'";
			}
			$select->where("(".join(") or (", $categorySqlArray).")");
		}

    if (isset($params['tags']) && !empty($params['tags'])) {

			//GET TAG MAPS TABLE NAME
			$tableTagmapsName = Engine_Api::_()->getDbtable('TagMaps', 'core')->info('name');

			$select
							->setIntegrityCheck(false)	
							->joinLeft($tableTagmapsName, "$tableTagmapsName.resource_id = $tableSitefaqName.faq_id")
							->where($tableTagmapsName.'.resource_type = ?', 'sitefaq_faq');

			foreach($params['tags'] as $tag_id) {
				$tagSqlArray[] = "$tableTagmapsName.tag_id = $tag_id";
			}
			$select->where("(".join(") or (", $tagSqlArray).")");
    }

		if(isset($params['member_levels']) && !empty($params['member_levels'])) {
			foreach($params['member_levels'] as $member_level) {
				$levelSqlArray[] = "$tableSitefaqName.member_levels LIKE '%".'"'.$member_level.'"'."%'";
			}
			$select->where("(".join(") or (", $levelSqlArray).")");
		}

		if(isset($params['profile_types']) && !empty($params['profile_types'])) {
			foreach($params['profile_types'] as $profile_type) {
				$profileSqlArray[] = "$tableSitefaqName.profile_types LIKE '%".'"'.$profile_type.'"'."%'";
			}
			$select->where("(".join(") or (", $profileSqlArray).")");
		}

		if(isset($params['networks']) && !empty($params['networks'])) {
			foreach($params['networks'] as $network) {
				$networkSqlArray[] = "$tableSitefaqName.networks LIKE '%".'"'.$network.'"'."%'";
			}
			$select->where("(".join(") or (", $networkSqlArray).")");
		}

    if (isset($params['orderby']) && !empty($params['orderby'])) {
      $select = $select->order($params['orderby']);
    }

    $select = $select->order('faq_id DESC');

    if (isset($params['limit']) && !empty($params['limit'])) {
      $select = $select->limit($params['limit']);
    }

		$select = $select->group('faq_id');

    return $this->fetchAll($select);
  }

  /**
   * Get sitefaqs
   * @param string $title : search text
   * @param int $limit : result limit
   */
  public function getSuggestList($params = null) {

		//GET LANGUAGE COLUMNS
		$title_column = Engine_Api::_()->sitefaq()->getLanguageColumn('title');
		$body_column = Engine_Api::_()->sitefaq()->getLanguageColumn('body');

		//GET TABLE NAME
		$tableSitefaqName = $this->info('name');

    //MAKE QUERY
    $select = $this->select();

		if($title_column != 'title') {
			$select->from($tableSitefaqName, array("faq_id", "$title_column", "title", "category_id", "subcategory_id", "subsubcategory_id"))
			->where("$title_column LIKE ? OR " ."$body_column LIKE ? OR "."title LIKE ? OR " ."body LIKE ? ", '%' . $params['search_text'] . '%')
			->order("$title_column ASC")
			->order("title ASC");
		}
		else {
			$select->from($this->info('name'), array("faq_id", "title", "category_id", "subcategory_id", "subsubcategory_id"))
			->where("title LIKE ? OR " ."body LIKE ? ", '%' . $params['search_text'] . '%')
			->order("title ASC");
		}

		if(isset($params['member_levels']) && !empty($params['member_levels'])) {
			foreach($params['member_levels'] as $member_level) {
				$levelSqlArray[] = "$tableSitefaqName.member_levels LIKE '%".'"'.$member_level.'"'."%'";
			}
			$select->where("(".join(") or (", $levelSqlArray).")");
		}

		if(isset($params['profile_types']) && !empty($params['profile_types'])) {
			foreach($params['profile_types'] as $profile_type) {
				$profileSqlArray[] = "$tableSitefaqName.profile_types LIKE '%".'"'.$profile_type.'"'."%'";
			}
			$select->where("(".join(") or (", $profileSqlArray).")");
		}

		if(isset($params['networks']) && !empty($params['networks'])) {
			foreach($params['networks'] as $network) {
				$networkSqlArray[] = "$tableSitefaqName.networks LIKE '%".'"'.$network.'"'."%'";
			}
			$select->where("(".join(") or (", $networkSqlArray).")");
		}

		$select->where('approved = ?', 1)
						->where('search = ?', 1)
						->where('draft = ?', 0)
						->limit($params['limit']);

    //FETCH RESULTS
    return $this->fetchAll($select);
  }

  /**
   * Update FAQ categories value on category delete
   *
   * @param int $category_id
   * @param string $type
   */
	public function updateFaqsCategories($category_id, $type, $mapping_category_id = 0) {
	
		//RETURN IF CATEGORY ID IS EMPTY
		if(empty($category_id)) {
			return;
		}

		//IF CATEGORY IS GOING TO DELETE
		if($type == 'category_delete') {

			//MAKE QUERY
			$select = $this->select()
										 ->from($this->info('name'), array('faq_id', 'category_id', 'subcategory_id', 'subsubcategory_id'))
			               ->where('category_id LIKE ?', '%"'.$category_id.'"%');
	
			//FETCH RESULTS
			$category_id_results = $this->fetchAll($select);

			foreach($category_id_results as $sitefaq) {
				$category_ids = Zend_Json_Decoder::decode($sitefaq->category_id);
				$subcategory_ids = Zend_Json_Decoder::decode($sitefaq->subcategory_id);
				$subsubcategory_ids = Zend_Json_Decoder::decode($sitefaq->subsubcategory_id);
				if(Count($category_ids) == 1) {
					$new_category_id = $zero_string = '["0"]';
					if(!empty($mapping_category_id)) {
						$new_category_id =  '["'.$mapping_category_id.'"]';
					}
					$this->update(array('category_id' => $new_category_id, 'subcategory_id' => $zero_string, 'subsubcategory_id' => $zero_string), array('faq_id = ?' => $sitefaq->faq_id));
					//break;
				}
				else {
					foreach($category_ids as $key => $value) {
						if($value == $category_id) {
							$category_ids[$key] = "0";
							$subcategory_ids[$key] = "0";
							$subsubcategory_ids[$key] = "0";

							if(!empty($mapping_category_id)) {
								$category_ids[$key] = "$mapping_category_id";
							}

							$category_ids = Zend_Json_Encoder::encode($category_ids);
							$subcategory_ids = Zend_Json_Encoder::encode($subcategory_ids);
							$subsubcategory_ids = Zend_Json_Encoder::encode($subsubcategory_ids);
							$this->update(array('category_id' => $category_ids, 'subcategory_id' => $subcategory_ids, 'subsubcategory_id' => $subsubcategory_ids), array('faq_id = ?' => $sitefaq->faq_id));
							//break;
						}
					}
					//break;
				}
			}
		}
		else {

			//MAKE QUERY
			$select = $this->select()
										 ->from($this->info('name'), array('faq_id', 'category_id', 'subcategory_id', 'subsubcategory_id'))
										 ->where('subcategory_id LIKE ?', '%"'.$category_id.'"%');

			//FETCH RESULTS
			$subcategory_id_results = $this->fetchAll($select);

			foreach($subcategory_id_results as $sitefaq) {
				$subcategory_ids = Zend_Json_Decoder::decode($sitefaq->subcategory_id);
				$subsubcategory_ids = Zend_Json_Decoder::decode($sitefaq->subsubcategory_id);

				foreach($subcategory_ids as $key => $value) {
					if($value == $category_id) {
						$subcategory_ids[$key] = "0";
						$subsubcategory_ids[$key] = "0";
						$subcategory_ids = Zend_Json_Encoder::encode($subcategory_ids);
						$subsubcategory_ids = Zend_Json_Encoder::encode($subsubcategory_ids);
						$this->update(array('subcategory_id' => $subcategory_ids, 'subsubcategory_id' => $subsubcategory_ids), array('faq_id = ?' => $sitefaq->faq_id));
						//break;
					}
				}
			}

			//MAKE QUERY
			$select = $this->select()
										 ->from($this->info('name'), array('faq_id', 'category_id', 'subcategory_id', 'subsubcategory_id'))
										 ->where('subsubcategory_id LIKE ?', '%"'.$category_id.'"%');

			//FETCH RESULTS
			$subsubcategory_id_results = $this->fetchAll($select);

			foreach($subsubcategory_id_results as $sitefaq) {
				$subsubcategory_ids = $sitefaq->subsubcategory_id;
				$find = '"'.$category_id.'"';
				$replace = '"0"';
				$subsubcategory_ids = str_replace($find, $replace, $subsubcategory_ids);
				$this->update(array('subsubcategory_id' => $subsubcategory_ids), array('faq_id = ?' => $sitefaq->faq_id));
			}
		}
	}

	/**
   * Get faq count based on category
   *
   * @param int $id
   * @param string $column_name
   * @param int $authorization
   * @return faq count
   */
	public function getFaqsCount($id, $column_name, $authorization) {

		//GET FAQ TABLE NAME
		$tableSitefaqName = $this->info('name');

		//RETURN IF ID IS EMPTY
		if(empty($id)) {
			return 0;
		}

		//MAKE ID STRING
		$id = '"'.$id.'"';

		//MAKE QUERY
		$select = $this->select()
										->from($this->info('name'), array('COUNT(*) AS count'))
										->where("$column_name LIKE ?", "%$id%");

		//AUTHORIZATION CHECK
		if(!empty($authorization)) {

			//GET VIEWER
			$viewer = Engine_Api::_()->user()->getViewer();
			$this->view->viewer_id = $viewer_id = $viewer->getIdentity();

			//GET USER LEVEL ID
			if (!empty($viewer_id)) {
				$level_id = $viewer->level_id;
			} else {
				$level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
			}

			if($level_id != 1) {
				$sitefaq_api = Engine_Api::_()->sitefaq();
				$params['networks'] = $sitefaq_api->getViewerNetworks();
				$params['profile_types'] = $sitefaq_api->getViewerProfiles();
				$params['member_levels'] = $sitefaq_api->getViewerLevels();

				foreach($params['networks'] as $network) {
					$networkSqlArray[] = "networks LIKE '%".'"'.$network.'"'."%'";
				}
				$select->where("(".join(") or (", $networkSqlArray).")");

				if(isset($params['member_levels']) && !empty($params['member_levels'])) {
					foreach($params['member_levels'] as $member_level) {
						$levelSqlArray[] = "$tableSitefaqName.member_levels LIKE '%".'"'.$member_level.'"'."%'";
					}
					$select->where("(".join(") or (", $levelSqlArray).")");
				}

				if(isset($params['profile_types']) && !empty($params['profile_types'])) {
					foreach($params['profile_types'] as $profile_type) {
						$profileSqlArray[] = "$tableSitefaqName.profile_types LIKE '%".'"'.$profile_type.'"'."%'";
					}
					$select->where("(".join(") or (", $profileSqlArray).")");
				}
			}
		}

		$select = $select->where('approved = ?', 1)->where('draft = ?', 0)->where('search = ?', 1);

		//GET TOTAL FAQs
		$totalFaqs = $select->query()->fetchColumn();

		//RETURN FAQ COUNT
		return $totalFaqs;
	}

	/**
   * Get FAQs
   *
   * @param int $category_id
   * @param string $column_name
   * @param int $authorization
   * @param int $no_subcategory
   * @return FAQs
   */
	public function getFaqs($category_id, $column_name, $authorization, $no_subcategory, $limit, $faq_limit, $count_only) {

		//GET FAQ TABLE NAME
		$tableSitefaqName = $this->info('name');

	  global $getFaqLimit;
		//RETURN IF ID IS EMPTY
		if(empty($getFaqLimit) || empty($category_id)) {
			return;
		}

		//GET LANGUAGE COLUMNS
		$title_column = Engine_Api::_()->sitefaq()->getLanguageColumn('title');

		//MAKE QUERY
    $select = $this->select();

		$get_id = $category_id;

		//MAKE ID STRING
		$category_id = '"'.$category_id.'"';

		if(empty($count_only)) {
			
			if($title_column != 'title') {
				$select = $select->from($tableSitefaqName, array('faq_id', 'title', "$title_column", 'category_id', 'subcategory_id', 'subsubcategory_id'));
			}
			else {
				$select = $select->from($tableSitefaqName, array('faq_id', 'title', 'category_id', 'subcategory_id', 'subsubcategory_id'));
			}

		}
		else {
			$select = $select->from($tableSitefaqName, array('COUNT(*) AS count'));
		}

		$select->where("$column_name LIKE ?", "%$category_id%");

		if(!empty($no_subcategory)) {
                        
			//MAKE QUERY
			$categoryTable = Engine_Api::_()->getDbtable('categories', 'sitefaq');
			$categoryTableName = $categoryTable->info('name');
			$selectSubcategories = $categoryTable->select()
																					->from($categoryTableName, array('category_id'))
																					->where('cat_dependency = ?', $get_id);
      $subcategories = $selectSubcategories->query()->fetchAll(Zend_Db::FETCH_COLUMN);
      if(Count($subcategories) > 0) {
				$str_arr=array();
        foreach($subcategories as $value){
               $select = $select->where("subcategory_id  NOT LIKE  ?",'%"'.$value.'"%');
				}                      
      }
      $select = $select->where("subcategory_id LIKE ?", '%"0"%');
    }

		//GET VIEWER
    $viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();

    //GET USER LEVEL ID
    if (!empty($viewer_id)) {
      $level_id = $viewer->level_id;
    } else {
      $level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
    }

		if($level_id != 1) {
			$sitefaq_api = Engine_Api::_()->sitefaq();
			$params['networks'] = $sitefaq_api->getViewerNetworks();
			$params['profile_types'] = $sitefaq_api->getViewerProfiles();
			$params['member_levels'] = $sitefaq_api->getViewerLevels();

			foreach($params['networks'] as $network) {
				$networkSqlArray[] = "networks LIKE '%".'"'.$network.'"'."%'";
			}
			$select->where("(".join(") or (", $networkSqlArray).")");

			if(isset($params['member_levels']) && !empty($params['member_levels'])) {
				foreach($params['member_levels'] as $member_level) {
					$levelSqlArray[] = "$tableSitefaqName.member_levels LIKE '%".'"'.$member_level.'"'."%'";
				}
				$select->where("(".join(") or (", $levelSqlArray).")");
			}

			if(isset($params['profile_types']) && !empty($params['profile_types'])) {
				foreach($params['profile_types'] as $profile_type) {
					$profileSqlArray[] = "$tableSitefaqName.profile_types LIKE '%".'"'.$profile_type.'"'."%'";
				}
				$select->where("(".join(") or (", $profileSqlArray).")");
			}
		}

		//AUTHORIZATION CHECK
		if(!empty($authorization)) {
			$select = $select->where('approved = ?', 1)->where('draft = ?', 0)->where('search = ?', 1);
		}

		//LIMIT CHECK
		if(!empty($faq_limit)) {
			$select = $select->limit($faq_limit);
		}

		if(empty($count_only)) {
			$select = $select->order("$tableSitefaqName.weight DESC");
			return $this->fetchAll($select);
		}
		else {
			return $select->query()->fetchColumn();
		}
	}

  /**
   * Return sitefaqs of user
   *
   * @param int $owner_id
   * @return Zend_Db_Table_Select
   */
	public function getOwnersFaqs($owner_id) {
		
		//MAKE QUERY
		$select = $this->select()
									 ->from($this->info('name'), 'faq_id')
									 ->where('owner_id = ?', $owner_id);

		//RETURN RESULTS
		return $this->fetchAll($select);
	}

  /**
   * Create new columns in FAQ table for language support
   *
   * @param array $columns
   */
	public function createColumns($columns = array()) {
		
		//RETURN IF COLUMNS ARRAY IS EMPTY
		if(empty($columns)) {
			return;
		}

		foreach($columns as $label) {

			if($label == 'en') {
				continue;
			}

			$title_column = "'title_$label'";
			$body_column = "'body_$label'";

			$create_title_column = "`title_$label`";
			$create_body_column = "`body_$label`";

			$db = Engine_Db_Table::getDefaultAdapter();

			//CHECK COLUMNS ARE ALREADY EXISTS
			$title_column_exist = $db->query("SHOW COLUMNS FROM engine4_sitefaq_faqs LIKE $title_column")->fetch();
			$body_column_exist = $db->query("SHOW COLUMNS FROM engine4_sitefaq_faqs LIKE $body_column")->fetch();

			//CREATE COLUMNS IF NOT EXISTS
			if (empty($title_column_exist) && empty($body_column_exist)) {
				$db->query("ALTER TABLE `engine4_sitefaq_faqs` ADD $create_title_column TEXT NOT NULL AFTER `body` , ADD $create_body_column TEXT NOT NULL AFTER $create_title_column ");
			}
		}
	}

}