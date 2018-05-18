<?php
/**
* SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Search.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteseo_Model_DbTable_Search extends Engine_Db_Table {

    protected $_name = 'core_search';
    // RETURNS FORMATTED PAGE TITLE AS PER THE SETTINGS

    public function getSearchPaginator($params) {

        $searchTableName = $this->info('name');
        $contentTypeTable = Engine_Api::_()->getDbtable('contenttypes','siteseo');
        $contentTypesArray = array(null);
        $contentTypes = $contentTypeTable->getSearchItemTypes();
        foreach ($contentTypes as $contentType)
            $contentTypesArray[] = $contentType->type;

        $select = $this->select()->from($searchTableName)
            ->where("$searchTableName.type IN ( ? )", $contentTypesArray);

        if(isset($params['type']) && !empty($params['type'])) {
            $select->where('type = ?',$params['type']);
        }

        if(isset($params['order']) && !empty($params['order'])) {
            $orderDirection = isset($params['order_direction']) && $params['order_direction'] ? $params['order_direction'] : 'ASC';
            $select->order($params['order'] . ' ' . $orderDirection);
        }

        if (isset($params['title']) && !empty($params['title'])) {
            $title = '%' . $params['title'] . '%';
            $select->where("title LIKE '$title' or meta_title LIKE '$title'");
        }

        if (isset($params['description']) && !empty($params['description'])) {
            $description = '%' . $params['description'] . '%';
            $select->where("description LIKE '$description' or meta_description LIKE '$description'");
        }

        if (isset($params['keywords']) && !empty($params['keywords'])) {
            $keywords = '%' . $params['keywords'] . '%';
            $select->where("keywords LIKE '$keywords' or meta_keywords LIKE '$keywords'");
        }

        $page = isset($params['page']) ? $params['page'] : 1;
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(30);
        return $paginator;
    }

    // RETURNS CORE SEARCH TABLE ROW OF AN ITEM
    public function getSearchRow($subject) {

        $select = $this->select()->where('id = ? ', $subject->getIdentity())
        ->where('type = ? ', $subject->getType())->limit(1);
        return $this->fetchRow($select);
    }

}