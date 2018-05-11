<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_Api_Core extends Core_Api_Abstract
{
  /**
   * Get Truncation String
   *
   * @param string $text
   * @param int $limit
   * @return truncate string
   */
  public function truncateText($string, $limit) {

		//IF LIMIT IS EMPTY
		if (empty($limit)) {
			$limit = 16;
		}

		//RETURN TRUNCATED STRING
		$string = strip_tags($string);
		return ( Engine_String::strlen($string) > $limit ? Engine_String::substr($string, 0, ($limit-3)) . '...' : $string );
  }

  /**
   * Import FAQs from communityad plugin to FAQ plugin
   *
   * @param int $category_id
   * @param int $sub_category_id
   * @param int $type
   * @param string $member_level_visibility
   * @param string $profile_type_visibility
   * @param string $network_type_visibility
   */
	public function importFaqs($category_id, $sub_category_id, $type, $member_level_visibility, $profile_type_visibility, $network_type_visibility) {

		//GET COMMUNITY ADS FAQ TABLE
		$faqTable = Engine_Api::_()->getDbTable('faqs', 'communityad');

		//GET VIEWER DETAILS
 		$viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();
		$level_id = $viewer->level_id;

		//MAKE QUERY
		$select = $faqTable->select()
												->from($faqTable->info('name'), array('faq_id', 'type', 'question', 'answer'))
												->where('type = ?', $type)
												->where('import = ?', 0)
												->order('faq_id DESC');

		//FETCH COMMUNITY ADS FAQs
		$communityadFaqData = $faqTable->fetchAll($select);

		$category_id = '["'.$category_id.'"]';
		$sub_category_id = '["'.$sub_category_id.'"]';

		//GET DEFAULT LANGUAGE COLUMNS
// 		$title_column = Engine_Api::_()->sitefaq()->getLanguageColumn('title');
// 		$body_column = Engine_Api::_()->sitefaq()->getLanguageColumn('body');

		//FAQ PRIVACY WORK
		$auth = Engine_Api::_()->authorization()->context;
		$roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
		$auth_comment = "registered";
		$commentMax = array_search($auth_comment, $roles);
		$auth_view = "everyone";
		$viewMax = array_search($auth_view, $roles);

		foreach($communityadFaqData as $faq) {

			//CREATE FAQ IN SITEFAQ PLUGIN
      $sitefaq = Engine_Api::_()->getDbTable('faqs', 'sitefaq')->createRow();
      $sitefaq->owner_id = $viewer_id;
			$sitefaq->title = Zend_Registry::get('Zend_Translate')->_($faq->question);
			$sitefaq->body = Zend_Registry::get('Zend_Translate')->_($faq->answer);
// 			$sitefaq->$title_column = Zend_Registry::get('Zend_Translate')->_($faq->question);
// 			$sitefaq->$body_column = Zend_Registry::get('Zend_Translate')->_($faq->answer);
			$sitefaq->category_id = $category_id;
			$sitefaq->subcategory_id = $sub_category_id;
			$sitefaq->subsubcategory_id = '["0"]';
			$sitefaq->member_levels = $member_level_visibility;
			$sitefaq->profile_types = $profile_type_visibility;
			$sitefaq->networks = $network_type_visibility;
			$sitefaq->approved = Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'approved');
			$sitefaq->save();

			//FAQ PRIVACY WORK
			foreach ($roles as $i => $role) {
				$auth->setAllowed($sitefaq, $role, 'comment', ($i <= $commentMax));
				$auth->setAllowed($sitefaq, $role, 'view', ($i <= $viewMax));
			}

			//UPDATE THE IMPORT VALUE IN COMMUNITY-AD FAQ TABLE
			$faqTable->update(array('import' => 1), array('faq_id = ?' => $faq->faq_id));
		}
	}

  /**
   * Get sitefaq tags created by users
   * @param int $owner_id : sitefaq owner id
	 * @param int $total_tags : number tags to show
   */
	public function getTags($owner_id = 0, $total_tags = 100, $count_only = 0) {

		//GET TAGMAP TABLE NAME
    $tableTagmaps = 'engine4_core_tagmaps';

		//GET TAG TABLE NAME
    $tableTags = 'engine4_core_tags';

		//GET DOCUMENT TABLE
    $tableSitefaq = Engine_Api::_()->getDbtable('faqs', 'sitefaq');
    $tableSitefaqName = $tableSitefaq->info('name');

		//MAKE QUERY
    $select = $tableSitefaq->select()
                    ->setIntegrityCheck(false)
                    ->from($tableSitefaqName, array(''))
                    ->joinInner($tableTagmaps, "$tableSitefaqName.faq_id = $tableTagmaps.resource_id", array('COUNT(resource_id) AS Frequency'))
                    ->joinInner($tableTags, "$tableTags.tag_id = $tableTagmaps.tag_id",array('text', 'tag_id'));

		if(!empty($owner_id)) {
			$select = $select->where($tableSitefaqName . '.owner_id = ?', $owner_id);
		}

		$select = $select
                    ->where($tableSitefaqName . '.approved = ?', 1)
                    ->where($tableSitefaqName . '.draft = ?', 0)
										->where($tableSitefaqName . '.search = ?', 1)
                    ->where($tableTagmaps . '.resource_type = ?', 'sitefaq_faq')
                    ->group("$tableTags.text")
                    ->order("Frequency DESC");

		if(!empty($total_tags)) {
			$select = $select->limit($total_tags);
		}
	
		if(!empty($count_only)) {
			$total_results = $select->query()->fetchAll(Zend_Db::FETCH_COLUMN);
			return Count($total_results);
		}

		//RETURN RESULTS
    return $select->query()->fetchAll();
	}

  /**
   * Get selected language title column if exist
   *
   * @param string $column_type
   */
	public function getLanguageColumn($column_type) {

		//RETURN IF COLUMN TYPE OR SITEFAQ ARRAY IS EMPTY
		if(empty($column_type)) {
			return;
		}

		//GET LANGUAGE SETTINGS
		$multilanguage_support = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitefaq.multilanguage', 0);
		$languages = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitefaq.languages');

		//GET THE CURRENT LANGUAGE
		$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
		$locale = $view->locale()->getLocale()->__toString();

		//RETURN COLUMN TYPE
		if(empty($multilanguage_support) || (!in_array($locale, $languages) && $locale == 'en')) {
			return $column_type;
		}
		else {
			$column_value = $column_type;
			$column_name = "$column_type"."_$locale";

			$db = Engine_Db_Table::getDefaultAdapter();
			$column_exist = $db->query("SHOW COLUMNS FROM engine4_sitefaq_faqs LIKE '$column_name'")->fetch();

			if(!empty($column_exist)) {
				return $column_name;
			}
		}

		//RETURN VALUE
		return $column_value;
	}

  /**
   * Get viewers networks array
   *
   */
	public function getViewerNetworks() {

		//GET VIEWER
		$viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();

		//MAKE ARRAY
		$viewerNetworksArray = array();

		if(!empty($viewer_id)) {
			//GET MEMBERSHIP TABLES
			$viewerNetworksArray = Engine_Api::_()->getDbtable('membership', 'network')->getMembershipsOfIds($viewer);
		}

		//ADD EVERYONE
		$viewerNetworksArray[] = 0;

		return $viewerNetworksArray;
	}

  /**
   * Get viewers profiles array
   *
   */
	public function getViewerProfiles() {

		//GET VIEWER
		$viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();

		//GET USER PROFILE TYPE ID
		$topLevelValue = array();
		if(!empty($viewer_id)) {
			$aliasedFields = $viewer->fields()->getFieldsObjectsByAlias();
			if( isset($aliasedFields['profile_type']) ) {
				$aliasedFieldValue = $aliasedFields['profile_type']->getValue($viewer);
				$topLevelValue[] = ( is_object($aliasedFieldValue) ? $aliasedFieldValue->value : -99 );
			}
		}

		//ADD EVERYONE
		$topLevelValue[] = 0;

		return $topLevelValue;
	}

  /**
   * Get viewers levels array
   *
   */
	public function getViewerLevels() {

		//GET VIEWER
		$viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();

		//GET USER LEVEL ID
		$level_ids = array();

    //GET USER LEVEL ID
    if (!empty($viewer_id)) {
      $level_ids[] = $viewer->level_id;
    } else {
      $level_ids[] = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
    }

		//ADD EVERYONE
		$level_ids[] = 0;

		return $level_ids;
	}

  /**
   * Check widget is exist or not
   *
   */
	public function existWidget($widget = '', $identity = 0) {

		//GET CONTENT TABLE
		$tablePages = Engine_Api::_()->getDbtable('pages', 'core');

		//GET CONTENT TABLE
		$tableContent = Engine_Api::_()->getDbtable('content', 'core');

		if($widget == 'view_ratings') {

			//GET PAGE ID
			$page_id = $tablePages->select()
										->from($tablePages->info('name'), array('page_id'))
										->where('name = ?', 'sitefaq_index_view')
										->query()
										->fetchColumn();

			if(empty($page_id)) {
				return false;
			}

			//GET CONTENT ID
			$content_id = $tableContent->select()
										->from($tableContent->info('name'), array('content_id'))
										->where('name = ?', 'sitefaq.ratings-sitefaqs')
										->where('type = ?', 'widget')
										->where('page_id = ?', $page_id)
										->query()
										->fetchColumn();

			return $content_id;
		}
		elseif($widget == 'browse_categories') {

			//GET PAGE ID
			$page_id = $tableContent->select()
											->from($tableContent->info('name'), array('page_id'))
											->where('content_id = ?', $identity)
											->where('name = ?', 'sitefaq.sidebar-categories-sitefaqs')
											->query()
											->fetchColumn();

			if(empty($page_id)) {
				return false;
			}

			//GET CONTENT ID
			$content_id = $tableContent->select()
														->from($tableContent->info('name'), array('content_id'))
														->where('page_id = ?', $page_id)
														->where('name = ?', 'sitefaq.search-sitefaqs')
														->query()
														->fetchColumn();

			return $content_id;
		}
	}

  /**
   * Get existing languages array
   *
   */
	public function getLanguageArray() {

		//PREPARE LANGUAGE LIST
    $languageList = Zend_Registry::get('Zend_Translate')->getList();

		//PREPARE DEFAULT LANGUAGE
    $defaultLanguage = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.locale.locale', 'en');
    if( !in_array($defaultLanguage, $languageList)) {
      if( $defaultLanguage == 'auto' && isset($languageList['en']) ) {
        $defaultLanguage = 'en';
      } else {
        $defaultLanguage = null;
      }
    }

		//INIT DEFAULT LOCAL
    $localeObject = Zend_Registry::get('Locale');

    $languages = Zend_Locale::getTranslationList('language', $localeObject);
    $territories = Zend_Locale::getTranslationList('territory', $localeObject);

    $localeMultiOptions = array();
    foreach($languageList as $key ) {
      $languageName = null;
      if( !empty($languages[$key]) ) {
        $languageName = $languages[$key];
      } else {
        $tmpLocale = new Zend_Locale($key);
        $region = $tmpLocale->getRegion();
        $language = $tmpLocale->getLanguage();
        if( !empty($languages[$language]) && !empty($territories[$region]) ) {
          $languageName =  $languages[$language] . ' (' . $territories[$region] . ')';
        }
      }

      if( $languageName ) {
        $localeMultiOptions[$key] = $languageName;
      } else {
        $localeMultiOptions[$key] = Zend_Registry::get('Zend_Translate')->_('Unknown');
      }
    }
    $localeMultiOptions = array_merge(array(
      $defaultLanguage => $defaultLanguage
    ), $localeMultiOptions);

		return $localeMultiOptions;
	}

  /**
   * Get profile types array
   *
   */
	public function getProfileTypes($count = 0) {

		//GET EXISTING PROFILE TYPES
		$fieldType = 'user';
		$optionsData = Engine_Api::_()->getApi('core', 'fields')->getFieldsOptions($fieldType);
		$topLevelField_field_id = 1;
		$topLevelOptions = array();
		$topLevelOptions[0] = Zend_Registry::get('Zend_Translate')->_('Everyone');
		foreach( $optionsData->getRowsMatching('field_id', $topLevelField_field_id) as $option ) {
			$topLevelOptions[$option->option_id] = $option->label;
		}

		if(!empty($count)) {
			return Count($topLevelOptions);
		}

		//RETURN PROFILE TYPES
		return $topLevelOptions;
	}

  /**
   * Get member levels array
   *
   */
	public function getNetworks() {

		//GET NETWORK TABLE
		$tableNetwork = Engine_Api::_()->getDbtable('networks', 'network');

		//MAKE QUERY
		$select = $tableNetwork->select()
										->from($tableNetwork->info('name'), array('network_id', 'title'))                    
										->order('title');
		$result = $tableNetwork->fetchAll($select);

		$everyone = Zend_Registry::get('Zend_Translate')->_('Everyone');

		//MAKE DATA ARRAY
		$networksOptions = array('0' => $everyone);
		foreach ($result as $value) {
			$networksOptions[$value->network_id] = $value->title;
		}

		//RETURN
		return $networksOptions;
	}

  /**
   * Get member levels array
   *
   */
	public function getMemberLevels() {

		//GET AUTHORIZATION TABLE
		$levels = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll();

		$everyone = Zend_Registry::get('Zend_Translate')->_('Everyone');

		$levels_prepared = array('0' => $everyone);
		foreach ($levels as $level) {
			$levels_prepared[$level->getIdentity()] = $level->getTitle();
		}

		reset($levels_prepared);

		//RETURN
		return $levels_prepared;
	}
	
}