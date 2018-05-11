<?php
/**
* SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitemetatag
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: pageinfo.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitemetatag_Model_DbTable_Pageinfo extends Engine_Db_Table {

    protected $_name = 'siteseo_pageinfo';
    protected $_rowClass = 'Sitemetatag_Model_Pageinfo';

    public function getPageinfo($params) {
        $corePageTable = Engine_Api::_()->getDbtable('pages','core');
        $corePageTableName = $corePageTable->info('name');
        $select = $corePageTable->select()->from($corePageTableName);
        if(isset($params['page_id']) && $params['page_id'])
            $select->where("$corePageTableName.page_id = ?", $params['page_id']);

        if(isset($params['content']) && $params['content']) {
            $content = $params['content'];
            $select->where("($corePageTableName.page_id = '$content' OR $corePageTableName.name = '$content' )");
        }

        if(isset($params['name']) && $params['name'])
            $select->where('name = ?', $params['name']);

        $pageInfoTableName = $this->info('name');
        $select->setIntegrityCheck(false);
        $select = $select->joinLeft($pageInfoTableName, "$pageInfoTableName.page_id = $corePageTableName.page_id", array('*'));

        return $this->fetchRow($select);
    }

    public function getPageinfoRow($page_id) {
        $tableName = $this->info('name');
        $select = $this->select()->from($tableName)->where('page_id = ?',$page_id)->limit(1);
        return $this->fetchRow($select);
    }

    public function getCorePages($params) {
        $corePageTable = Engine_Api::_()->getDbtable('pages','core');
        $corePageTableName = $corePageTable->info('name');
        $select = $corePageTable->select()->from($corePageTableName);

        //EXCLUDE HEADER AND FOOTER FOR SEO
        $excludePages = array('header','footer');
        $select->where("$corePageTableName.name NOT IN ( ? )",$excludePages);

        if(isset($params['order']) && $params['order'])         	
	        $select->order((!empty($params['order']) ? $params['order'] : 'page_id' ) . ' ' . (!empty($params['order_direction']) ? $params['order_direction'] : 'ASC' ));

        if (isset($params['displayname']) && !empty($params['displayname'])) {
        	$select->where('displayname LIKE ?', '%' . $params['displayname'] . '%');
        }

        if (isset($params['plugin']) && ($params['plugin'] == 'custom')) {
            $select->where('custom = ?', 1);
        } else if (isset($params['plugin']) && !empty($params['plugin'])) {
            $select->where('name LIKE ?', '%' . $params['plugin'] . '_%');
        }

        if (isset($params['title']) && !empty($params['title'])) {
            $select->where('title LIKE ?', '%' . $params['title'] . '%');
        }

        if (isset($params['description']) && !empty($params['description'])) {
        	$select->where('description LIKE ?', '%' . $params['description'] . '%');
        }

        if (isset($params['keywords']) && !empty($params['keywords'])) {
        	$select->where('keywords LIKE ?', '%' . $params['keywords'] . '%');
        }


        $pageInfoTableName = $this->info('name');
        $select->setIntegrityCheck(false);
        $select = $select->joinLeft($pageInfoTableName, $pageInfoTableName . '.page_id = ' . $corePageTableName . '.page_id', array('photo_id', 'enable_opengraph', 'enable_twittercards'));

        if (isset($params['enable_opengraph']) ) {
            if($params['enable_opengraph'] == 0)
                $select->where("$pageInfoTableName.enable_opengraph = ?", 0);
            else
                $select->where("( $pageInfoTableName.enable_opengraph IS NULL or $pageInfoTableName.enable_opengraph = ? )", 1);
        }

        if (isset($params['enable_twittercards']) ) {
            if($params['enable_twittercards'] == 0)
                $select->where("$pageInfoTableName.enable_twittercards = ?", 0);
            else
                $select->where("( $pageInfoTableName.enable_twittercards IS NULL or $pageInfoTableName.enable_twittercards = ? )", 1);
        }

        if (isset($params['paginator'])  && $params['paginator'] == 0 ) {
            return $corePageTable->fetchAll($select);
        }
        $page = isset($params['page']) ? $params['page'] : 1;
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(30);
        return $paginator;
    }
}