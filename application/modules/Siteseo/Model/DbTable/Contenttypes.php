<?php
/**
* SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: pageinfo.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteseo_Model_DbTable_Contenttypes extends Engine_Db_Table {

    protected $_name = 'siteseo_contenttypes';
    protected $_rowClass = 'Siteseo_Model_Contenttype';

    public function getContentType($params = array()) {
        $tableName = $this->info('name');
        $select = $this->select()->from($tableName)->limit(1);
        if(isset($params['contenttype_id']) && $params['contenttype_id'])
            $select->where('contenttype_id = ?',$params['contenttype_id']);
        if(isset($params['type']) && $params['type'])
            $select->where('type = ?',$params['type']);
        return $this->fetchRow($select);
    }

    public function getContentTypes($params = array()) {
        $tableName = $this->info('name');
        $select = $this->select()->from($tableName)->order('priority DESC')->order('order');
        if(isset($params['enabled']) && $params['enabled'])
            $select->where('enabled = ?',$params['enabled']);
        if(isset($params['type']) && $params['type'])
            $select->where('type = ?',$params['type']);
        return $this->fetchAll($select);
    }

    public function getSearchItemTypes() {

        $searchTable = Engine_Api::_()->getDbtable('search', 'siteseo');
        $select = $searchTable->select()
            ->from($searchTable->info('name'),array('type'))
            ->distinct();
        $searchContentTypes = $searchTable->fetchAll($select); 

        $select = $this->select()->order('order');
        $seoContentTypeRow = $this->fetchAll($select);
        $seoContentType = array();
        $flag = 0;
        foreach ($seoContentTypeRow as $type)
            $seoContentType[] = $type->type;
        foreach ($searchContentTypes as $type) {
            if(!in_array($type->type, $seoContentType)) {
                $row = $this->createRow();
                $row->type = $type->type;
                $schemaMapping = $this->getContentSchemaType($type->type);
                $row->schematype = $schemaMapping['schematype'];
                $row->specific_schematype = $schemaMapping['specific_schematype'];
                $row->title = Zend_Registry::get('Zend_Translate')->_(strtoupper('ITEM_TYPE_' . $type->type));

                $row->save();
                $flag = 1;
            }
        }
        if(empty($flag))
            return $seoContentTypeRow;
        return $this->fetchAll($select);
    }

    public function getContentSchemaType($type) {
        $schemaArray = $this->getSchemaTypeArray();
        $schemaMapping = array('schematype' => '', 'specific_schematype' => ''); 
        $schemaArray = array(
            'blog' => 'Article',
            'sitenews_news' => 'Article',
            'event' => 'Event',
            'siteevent_event' => 'Event',
            'video' => 'VideoObject',
            'recipe' => 'Recipe',
            );

        $schemaMapping['schematype'] = isset($schemaArray[$type]) ? $schemaArray[$type] : '';
        if (empty($schemaMapping['schematype']))  
            return $schemaMapping;

        $specificSchemaArray = array(
            'blog' => 'BlogPosting',
            'sitenews_news' => 'NewsArticle',
            );
        $schemaMapping['specific_schematype'] = isset($specificSchemaArray[$type]) ? $specificSchemaArray[$type] : '';
        return $schemaMapping;
    }

    public function getSchemaTypeArray() {
        $schematypeArray = array(
            '' => '',
            'Article' => 'Articles',
            // 'Book' => 'Books',
            // 'Course' => 'Courses',
            // 'Dataset' => 'Datasets',
            'Event' => 'Events',
            // 'ClaimReview' => 'Fact Check',
            // 'JobPosting' => 'Job Postings',
            // 'LocalBusiness' => 'Local Businesses',
            // 'Music' => 'Music',
            // 'Podcasts' => 'Podcasts',
            'Product' => 'Products',
            'Recipe' => 'Recipes',
            // 'Review' => 'Reviews',
            // 'TV and Movies' => 'TV and Movies',
            'VideoObject' => 'Videos',
            );
        return $schematypeArray;
    }
}