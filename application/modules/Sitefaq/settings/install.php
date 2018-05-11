<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: install.php 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitefaq_Installer extends Engine_Package_Installer_Module
{

  function onPreInstall() {
	$PRODUCT_TYPE = 'sitefaq';
	$PLUGIN_TITLE = 'Sitefaq'; 
	$PLUGIN_VERSION = '4.9.4'; 
	$PLUGIN_CATEGORY = 'plugin';
	$PRODUCT_DESCRIPTION = 'FAQs, Knowledgebase, Tutorials & Help Center Plugin';
	$_PRODUCT_FINAL_FILE = 0;
	$_BASE_FILE_NAME = 0;
	$PRODUCT_TITLE = 'FAQs, Knowledgebase, Tutorials & Help Center Plugin';
	$SocialEngineAddOns_version = '4.8.6';
	$file_path = APPLICATION_PATH . "/application/modules/$PLUGIN_TITLE/controllers/license/ilicense.php";
	$is_file = file_exists($file_path);
	if (empty($is_file)) {
	  include_once APPLICATION_PATH . "/application/modules/$PLUGIN_TITLE/controllers/license/license3.php";
	} else {
	  if (!empty($_PRODUCT_FINAL_FILE)) {
		include_once APPLICATION_PATH . '/application/modules/' . $PLUGIN_TITLE . '/controllers/license/' . $_PRODUCT_FINAL_FILE;
	  }
	  $db = $this->getDb();
	  $select = new Zend_Db_Select($db);
	  $select->from('engine4_core_modules')->where('name = ?', $PRODUCT_TYPE);
	  $is_Mod = $select->query()->fetchObject();
	  if( empty($is_Mod) ) {
	    include_once $file_path;
	  }
	}
	parent::onPreInstall();
  }

  public function onInstall()
  {
		$db = $this->getDb();

		//UPDATE ICON ENTRIES
		$select = new Zend_Db_Select($db);
		$select
			->from('engine4_core_modules')
			->where('name = ?', 'sitefaq')
			->where('version < ?', '4.2.4p1');
		$is_enabled = $select->query()->fetchObject();

		if($is_enabled) {

			$categories = $db->select()
											->from('engine4_sitefaq_categories', array('category_id', 'file_id'))
											->where('file_id != ?', 0)
											->query()
											->fetchAll();

			foreach($categories as $category)
			{
				$category_id = $category['category_id'];
				$file_id = $category['file_id'];
				$db->query("UPDATE `engine4_storage_files` SET `parent_type` = 'sitefaq_category', parent_id = $category_id WHERE `file_id` = $file_id AND `parent_type` = 'sitefaq_faq'");
			}
		}

		//START MAKE CHANGES IN engine4_communityad_faqs TABLE
		$table_exist = $db->query("SHOW TABLES LIKE 'engine4_communityad_faqs'")->fetch();
		if (!empty($table_exist)) {

			$column_exist = $db->query("SHOW COLUMNS FROM engine4_communityad_faqs LIKE 'import'")->fetch();
			if (empty($column_exist)) {
				$db->query("ALTER TABLE `engine4_communityad_faqs` ADD `import` TINYINT( 1 ) NOT NULL DEFAULT '0'");
			}
		}
    
    $isTableExist = $db->query("SHOW TABLES LIKE 'engine4_sitefaq_categories'")->fetch();
    if (!empty($isTableExist)) {
      $isIndexed = $db->query("SHOW INDEX FROM `engine4_sitefaq_categories` WHERE Key_name = 'cat_dependency'")->fetch();
      if (empty($isIndexed)) {
        $db->query("ALTER TABLE `engine4_sitefaq_categories` ADD INDEX ( `cat_dependency` );");
      }
      
      $isIndexed = $db->query("SHOW INDEX FROM `engine4_sitefaq_categories` WHERE Key_name = 'subcat_dependency'")->fetch();
      if (empty($isIndexed)) {
        $db->query("ALTER TABLE `engine4_sitefaq_categories` ADD INDEX ( `subcat_dependency` );");
      }  
    }     

    $db->query("UPDATE  `engine4_seaocores` SET  `is_activate` =  '1' WHERE  `engine4_seaocores`.`module_name` ='sitefaq';");
    
    $categoriesTable = $db->query('SHOW TABLES LIKE \'engine4_sitefaq_categories\'')->fetch();
		if (!empty($categoriesTable)) {
 
			$catDependencyIndex = $db->query("SHOW INDEX FROM `engine4_sitefaq_categories` WHERE Key_name = 'cat_dependency'")->fetch();     
      if(empty($catDependencyIndex)) {
        $db->query("ALTER TABLE `engine4_sitefaq_categories` ADD INDEX ( `cat_dependency` )");
      }    
      
			$subcatDependencyIndex = $db->query("SHOW INDEX FROM `engine4_sitefaq_categories` WHERE Key_name = 'subcat_dependency'")->fetch();     
      if(empty($subcatDependencyIndex)) {
        $db->query("ALTER TABLE `engine4_sitefaq_categories` ADD INDEX ( `subcat_dependency` )");
      }       
    }

    $faqTable = $db->query('SHOW TABLES LIKE \'engine4_sitefaq_faqs\'')->fetch();
    if(!empty($faqTable)) {
        $featuredIndex = $db->query("SHOW INDEX FROM `engine4_sitefaq_faqs` WHERE Key_name = 'featured'")->fetch();   
        if(empty($featuredIndex)) {
          $db->query("ALTER TABLE `engine4_sitefaq_faqs` ADD INDEX ( `featured` )");
        }   

        $searchIndex = $db->query("SHOW INDEX FROM `engine4_sitefaq_faqs` WHERE Key_name = 'search'")->fetch();
        if(empty($searchIndex)) {
            $db->query("ALTER TABLE `engine4_sitefaq_faqs` ADD INDEX (`search`, `draft` , `approved`)");
        }
    }      
    
    parent::onInstall();
  }

  //SITEMOBILE CODE TO CALL MY.SQL ON POST INSTALL
    public function onPostInstall() {
        $moduleName = 'sitefaq';
        $db = $this->getDb();
        $select = new Zend_Db_Select($db);
        $select
                ->from('engine4_core_modules')
                ->where('name = ?', 'sitemobile')
                ->where('enabled = ?', 1);
        $is_sitemobile_object = $select->query()->fetchObject();
        if (!empty($is_sitemobile_object)) {
            $db->query("INSERT IGNORE INTO `engine4_sitemobile_modules` (`name`, `visibility`) VALUES
('$moduleName','1')");
            $select = new Zend_Db_Select($db);
            $select
                    ->from('engine4_sitemobile_modules')
                    ->where('name = ?', $moduleName)
                    ->where('integrated = ?', 0);
            $is_sitemobile_object = $select->query()->fetchObject();
            if ($is_sitemobile_object) {
                $actionName = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
                $controllerName = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
                if ($controllerName == 'manage' && $actionName == 'install') {
                    $view = new Zend_View();
                    $baseUrl = (!empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"]) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . str_replace('install/', '', $view->url(array(), 'default', true));
                    $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                    $redirector->gotoUrl($baseUrl . 'admin/sitemobile/module/enable-mobile/enable_mobile/1/name/' . $moduleName . '/integrated/0/redirect/install');
                }
            }
        }
    }

}
