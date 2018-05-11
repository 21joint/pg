<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteseo
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: core.php 2017-04-28 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteseo_Api_Core extends Core_Api_Abstract {

    protected $_pageInfo;

    protected $_subjectInfo;

    // RETURNS FORMATTED PAGE TITLE AS PER THE SETTINGS
    public function formatMetaTitle($content) {
        $siteseoformatMetaTitle = Zend_Registry::isRegistered('siteseoformatMetaTitle') ? Zend_Registry::get('siteseoformatMetaTitle') : null;
        if (empty($siteseoformatMetaTitle))
            return;
        $hasSubject = Engine_Api::_()->core()->hasSubject();
        if ($hasSubject) {
            $subject = Engine_Api::_()->core()->getSubject();
            $searchRow = Engine_Api::_()->getDbtable('search','siteseo')->getSearchRow($subject);
            $subjectTitle = $subject->getTitle();
            $metaTitle = $searchRow && $searchRow->meta_title ? $searchRow->meta_title : $subjectTitle;
            $settings = Engine_Api::_()->getApi('settings', 'core');
            // OVERWRITE META TITLE AS PER THE SELECTED GLOBAL SETTINGS
            if ($settings->getSetting("siteseo.metatags.overwrite", 1)) {

                $siteTitle = $settings->core_general_site_title;
                $pageTitle = $this->getCurrentPageinfo()->getTitle();
                $titleAppend = $settings->getSetting("siteseo.sitetile.append", 'none');
                $separator = ' - ';
                $titleArray = array();
                if (!in_array($titleAppend , array('both', 'page')) || strpos($pageTitle, '[subject_title]') === false) {
                    $titleArray[] = $metaTitle;
                }
                switch ($titleAppend) {
                    case 'page': $titleArray[] = $pageTitle;
                    break;
                    case 'site': $titleArray[] = $siteTitle;
                    break;
                    case 'both': $titleArray[] = $pageTitle; $titleArray[] = $siteTitle;
                    break;
                }
                $titleArray = array_filter($titleArray);
                $content = implode($separator, $titleArray);

                // REPLACE SUBJECT VARIABLES FOR VIEW PAGES
                $subjectInfo = $this->getSubjectInfo($subject);
                $search = array('[subject_owner]', '[subject_title]', '[subject_category]', '[subject_subcategory]', '[subject_subsubcategory]', '[subject_type]', '[subject_location]');
                $replace = array($subjectInfo['owner'], $subjectInfo['title'], $subjectInfo['category'], $subjectInfo['subcategory'], $subjectInfo['subsubcategory'], $subjectInfo['type'], $subjectInfo['location']);
                $content = str_replace($search, $replace, $content);
            } elseif ($subjectTitle != $metaTitle) {
                $content = str_replace($subjectTitle, $metaTitle , $content);
            }
        }
        $content = trim(preg_replace('/\s+/',' ',$content));
        return $content;
    }

    // RETURNS FORMATTED META DESCRIPTION AS PER THE SETTINGS
    public function formatMetaDescription($content) {

        $siteseoformatMetaDescription = Zend_Registry::isRegistered('siteseoformatMetaDescription') ? Zend_Registry::get('siteseoformatMetaDescription') : null;
        if (empty($siteseoformatMetaDescription))
            return;

        $hasSubject = Engine_Api::_()->core()->hasSubject();
        if($hasSubject) {
            $subject = Engine_Api::_()->core()->getSubject();
            $searchRow = Engine_Api::_()->getDbtable('search','siteseo')->getSearchRow($subject);
            $subjectDescription = $subject->getDescription();
            $metaDescription = $searchRow && $searchRow->meta_description ? $searchRow->meta_description : $subjectDescription;

            $settings = Engine_Api::_()->getApi('settings', 'core');
            // OVERWRITE META TITLE AS PER THE SELECTED GLOBAL SETTINGS
            if ($settings->getSetting("siteseo.metatags.overwrite", 1)) {

                $siteDescription = $settings->core_general_site_description;
                $pageDescription = $this->getCurrentPageinfo()->getDescription();
                $descriptionAppend = $settings->getSetting("siteseo.sitedescription.append", 'none');
                $separator = ' - ';
                $descriptionArray = array();
                if (!in_array($descriptionAppend , array('both', 'page')) || strpos($pageDescription, '[subject_description]') === false) {
                    $descriptionArray[] = $metaDescription;
                }
                switch ($descriptionAppend) {
                    case 'page': $descriptionArray[] = $pageDescription;
                    break;
                    case 'site': $descriptionArray[] = $siteDescription;
                    break;
                    case 'both': $descriptionArray[] = $pageDescription; $descriptionArray[] = $siteDescription;
                    break;
                }
                $descriptionArray = array_filter($descriptionArray);
                $content = implode($separator, $descriptionArray);

                // REPLACE SUBJECT VARIABLES FOR VIEW PAGES
                $subjectInfo = $this->getSubjectInfo($subject);

                $search = array('[subject_owner]', '[subject_title]','[subject_description]' , '[subject_category]', '[subject_subcategory]', '[subject_subsubcategory]', '[subject_type]', '[subject_location]');
                $replace = array($subjectInfo['owner'], $subjectInfo['title'], $subjectInfo['description'], $subjectInfo['category'], $subjectInfo['subcategory'], $subjectInfo['subsubcategory'], $subjectInfo['type'], $subjectInfo['location']);
                $content = str_replace($search, $replace, $content);
            } elseif ($subjectDescription != $metaDescription) {
                $content = str_replace($subjectDescription, $metaDescription , $content);
            }
        }
        $content = strip_tags($content);
        $content = trim(preg_replace('/\s+/',' ',$content));
        $content = Engine_String::strlen($content) > 255 ? Engine_String::substr($content, 0, 255) . '...' : $content ;
        return $content;
    }

    // RETURNS FORMATTED KEYWORDS AS PER THE SETTINGS
    public function formatMetaKeywords($content) {

        $siteseoformatMetaKeywords = Zend_Registry::isRegistered('siteseoformatMetaKeywords') ? Zend_Registry::get('siteseoformatMetaKeywords') : null;
        if (empty($siteseoformatMetaKeywords))
            return;

        $hasSubject = Engine_Api::_()->core()->hasSubject();
        if($hasSubject) {

            $page = $this->getCurrentPageinfo();
            $keywordTemplate = '[subject_keywords], [subject_category], [subject_subcategory], [subject_subsubcategory], [subject_type], [subject_location]';
            $content =  empty($page->keywords) ? $content . $keywordTemplate : $content;
            $subject = Engine_Api::_()->core()->getSubject();

            // REPLACE SUBJECT VARIABLES FOR VIEW PAGES
            $subjectInfo = $this->getSubjectInfo($subject);
            $keywords = $subject->getKeywords(',');
            $searchRow = Engine_Api::_()->getDbtable('search','siteseo')->getSearchRow($subject);
            $content = $searchRow ? $content . ', ' . $searchRow->meta_keywords : $content;

            $search = array('[subject_keywords]', '[subject_owner]', '[subject_category]', '[subject_subcategory]', '[subject_subsubcategory]', '[subject_type]', '[subject_location]');
            $replace = array($keywords, $subjectInfo['owner'], $subjectInfo['category'], $subjectInfo['subcategory'], $subjectInfo['subsubcategory'], $subjectInfo['type'], $subjectInfo['location']);
            $content = str_replace($search, $replace, $content);
        }

        // REMOVE DUPLICATE KEYWORDS AND REMOVE EXTRA SPACES
        $content = preg_replace('/\s+/',' ',$content);
        $content = strtolower($content);
        $content = explode(',', $content);
        $content = array_map('trim', $content);
        $content = array_unique($content);
        $content = array_filter($content);
        $content = implode(', ', $content);
        return $content;
    }

    // RETURNS ARRAY OF CATEGORY NAME, SUBCATEGORY NAME AND SUBSUBCATEGORY NAME OF AN ITEM
    public function getCategoriesArray($subject) {
    	$categoryArray = array();
    	$moduleName = strtolower($subject->getModuleName());
    	$categoryType = $moduleName . '_category';

        if (!Engine_Api::_()->hasItemType($categoryType) || !isset($subject->category_id)) {
            return $categoryArray;
        }

    	$categoryObj = Engine_Api::_()->getItem($categoryType, $subject->category_id);
    	$categoryColumn = isset($categoryObj->title) ? 'title' : (isset($categoryObj->category_name) ? 'category_name' : '');

    	if($categoryColumn && isset($subject->category_id) && $subject->category_id) {
    		$categoryArray['category'] = $categoryObj->$categoryColumn;

    		if(isset($subject->subcategory_id) && $subject->subcategory_id) {
    			$subCategoryObj = Engine_Api::_()->getItem($categoryType, $subject->subcategory_id);
    			$categoryArray['subcategory'] = $subCategoryObj->$categoryColumn;

    			if(isset($subject->subsubcategory_id) && $subject->subsubcategory_id) {
    				$subSubCategoryObj = Engine_Api::_()->getItem($categoryType, $subject->subsubcategory_id);
    				$categoryArray['subsubcategory'] = $subSubCategoryObj->$categoryColumn;
    			}
    		}
    	}
    	return $categoryArray;
    }

    // RETURNS PAGE INFORMATION OF CURRENT RENDERING PAGE
    public function getCurrentPageinfo() {
        if (isset($this->_pageInfo)) 
            return $this->_pageInfo;
        $params = array();
        $params['content'] =  Zend_Registry::isRegistered('sitemeta_content_name') ? Zend_Registry::get('sitemeta_content_name') : false;
        $this->_pageInfo = $params['content'] ? Engine_Api::_()->getDbtable('pageinfo', 'siteseo')->getPageinfo($params) : false;
        return $this->_pageInfo;
    }

    // RETURNS REQUIRED SUBJECT INFORMATION OF AN ITEM
    public function getSubjectInfo($subject) {
        if (is_null($this->_subjectInfo)) {
            $subjectInfo = array();
            $categoryArray = $this->getCategoriesArray($subject);
            $subjectInfo['category'] = isset($categoryArray['category']) ? $categoryArray['category'] : '';
            $subjectInfo['subcategory'] = isset($categoryArray['subcategory']) ? $categoryArray['subcategory'] : '';
            $subjectInfo['subsubcategory'] = isset($categoryArray['subsubcategory']) ? $categoryArray['subsubcategory'] : '';
            $subjectInfo['location'] = isset($subject->location) ? $subject->location : '';
            $subjectInfo['owner'] = $subject->getOwner()->getTitle();
            $searchRow = Engine_Api::_()->getDbtable('search','siteseo')->getSearchRow($subject);
            $subjectInfo['title'] = $searchRow && $searchRow->meta_title ? $searchRow->meta_title : $subject->getTitle();
            $subjectInfo['description'] = $searchRow && $searchRow->meta_description ? $searchRow->meta_description : $subject->getDescription();
            $type = strtoupper('ITEM_TYPE_' . $subject->getType());
            $subjectInfo['type'] = Zend_Registry::isRegistered('Zend_Translate') ? Zend_Registry::get('Zend_Translate')->translate($type) : $type;
            $this->_subjectInfo = $subjectInfo;
        }
        return $this->_subjectInfo;
    }
}