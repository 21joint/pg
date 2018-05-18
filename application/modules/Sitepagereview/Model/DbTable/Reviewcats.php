<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Reviewcats.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagereview_Model_DbTable_Reviewcats extends Engine_Db_Table {

  protected $_rowClass = 'Sitepagereview_Model_Reviewcat';

	/**
   * Return review parameters data according to page category
   *
   * @param Int page_id
	 * @param Int viewer_id
   * @return Zend_Db_Table_Select
   */
	public function reviewParams($category_id) {

		//MAKE QUERY
    $select = $this->select()
                    ->from($this->info('name'), array('reviewcat_id', 'reviewcat_name'))
                    ->where('category_id = ?', $category_id);

		//RETURN RESULTS
    return $this->fetchAll($select);
	}

	/**
   * Return review parameters and category data
   *
   * @return Zend_Db_Table_Select
   */
	public function reviewCatParams() {

		//GET PAGE CATEGORY INFO
    $tablePageCats = Engine_Api::_()->getDbtable('categories', 'sitepage');
    $tablePageCatsName = $tablePageCats->info('name');

		//GET REVIEW PARAMETER TABLE NAME
		$tableReviewCatsName = $this->info('name');

		//MAKE QUERY
    $select = $tablePageCats->select()
                    ->setIntegrityCheck(false)
                    ->from($tablePageCatsName)
                    ->joinLeft($this->info('name'), "$tablePageCatsName.category_id = $tableReviewCatsName.category_id", array('reviewcat_name', 'reviewcat_id'))
                    ->where($tablePageCatsName . ".cat_dependency = ?", 0);

		//RETURN RESULTS
    return $tablePageCats->fetchAll($select);
	}
}
?>