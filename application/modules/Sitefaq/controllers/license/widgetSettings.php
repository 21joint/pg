<?php

$db = Zend_Db_Table_Abstract::getDefaultAdapter();

$db->query("
  INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
	('SITEFAQ_QUESTION_NOTIFICATION_EMAIL', 'sitefaq', '[host],[email],[site_title],[question]'),
  ('SITEFAQ_QUESTION_ANSWER_EMAIL', 'sitefaq', '[host],[email],[site_title],[question],[answer]');
");

$db->query('
  INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
  ("sitefaq_admin_main_level", "sitefaq", "Member Level Settings", "", \'{"route":"admin_default","module":"sitefaq","controller":"settings", "action":"level"}\', "sitefaq_admin_main", "", 1, 0, 2),
  ("sitefaq_admin_main_categories", "sitefaq", "Categories", "", \'{"route":"admin_default","module":"sitefaq","controller":"settings", "action":"categories"}\', "sitefaq_admin_main", "", 1, 0, 3),
  ("sitefaq_admin_main_fields", "sitefaq", "Custom Fields", "", \'{"route":"admin_default","module":"sitefaq","controller":"fields"}\', "sitefaq_admin_main", "", 1, 0, 4),
  ("sitefaq_admin_main_form_search", "sitefaq", "Search Form Settings", "", \'{"route":"admin_default","module":"sitefaq","controller":"settings","action":"form-search"}\', "sitefaq_admin_main", "", 1, 0, 5),
  ("sitefaq_admin_main_import", "sitefaq", "Import", "", \'{"route":"admin_default","module":"sitefaq","controller":"import"}\', "sitefaq_admin_main", "", 1, 0, 6),
  ("sitefaq_admin_main_helpful", "sitefaq", "Helpful Settings", "", \'{"route":"admin_default","module":"sitefaq","controller":"settings","action":"helpful-report"}\', "sitefaq_admin_main", "", 1, 0, 7),
  ("sitefaq_admin_main_sitefaq", "sitefaq", "Manage FAQs", "", \'{"route":"admin_default","module":"sitefaq","controller":"site-faq", "action":"manage"}\', "sitefaq_admin_main", "", 1, 0, 8),
  ("sitefaq_admin_main_question", "sitefaq", "User Questions", "", \'{"route":"admin_default","module":"sitefaq","controller":"question", "action":"manage"}\', "sitefaq_admin_main", "", 1, 0, 9),
  ("sitefaq_core_main", "sitefaq", "FAQs", \'Sitefaq_Plugin_Menus::canViewSitefaqs\', \'{"route":"sitefaq_general","action":"home","icon":"fa-question-circle-o"}\', "core_footer", "", 1, 0, 3);
');

$isFaqRow = $db->query('SELECT * FROM `engine4_core_menuitems` WHERE `name` LIKE "core_main_sitefaq" LIMIT 1')->fetchAll();
if( !empty($isFaqRow) ) {
  $db->query('UPDATE `engine4_core_menuitems` SET `enabled` = "1" WHERE `engine4_core_menuitems`.`name` ="core_main_sitefaq" LIMIT 1 ;');
}

//UPLOAD CATEGORY ICONS
Engine_Api::_()->getDbTable('categories', 'sitefaq')->iconUpload(0);

//START FAQ HOME PAGE
$page_id = $db->select()
				->from('engine4_core_pages', 'page_id')
				->where('name = ?', 'sitefaq_index_home')
				->limit(1)
				->query()
				->fetchColumn();

//CREATE PAGE IF NOT EXIST
if (!$page_id) {
  //CREATE PAGE
  $db->insert('engine4_core_pages', array(
	  'name' => 'sitefaq_index_home',
	  'displayname' => 'FAQ Home Page',
	  'title' => 'FAQ Home Page',
	  'description' => 'This page lists FAQs.',
	  'custom' => 0,
  ));
  $page_id = $db->lastInsertId();

  //TOP CONTAINER
  $db->insert('engine4_core_content', array(
	  'type' => 'container',
	  'name' => 'top',
	  'page_id' => $page_id,
	  'order' => 1,
  ));
  $top_container_id = $db->lastInsertId();

  //MAIN CONTAINER
  $db->insert('engine4_core_content', array(
	  'type' => 'container',
	  'name' => 'main',
	  'page_id' => $page_id,
	  'order' => 2,
  ));
  $main_container_id = $db->lastInsertId();

  //INSERT TOP-MIDDLE
  $db->insert('engine4_core_content', array(
	  'type' => 'container',
	  'name' => 'middle',
	  'page_id' => $page_id,
	  'parent_content_id' => $top_container_id,
  ));
  $top_middle_id = $db->lastInsertId();

  //MAIN NAVIGATION WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.navigation-sitefaqs',
	  'parent_content_id' => $top_middle_id,
	  'order' => 3,
	  'params' => '',
  ));

  //MAIN-MIDDLE CONTAINER
  $db->insert('engine4_core_content', array(
	  'type' => 'container',
	  'name' => 'middle',
	  'page_id' => $page_id,
	  'parent_content_id' => $main_container_id,
	  'order' => 2,
  ));
  $main_middle_id = $db->lastInsertId();

  //RIGHT CONTAINER
  $db->insert('engine4_core_content', array(
	  'type' => 'container',
	  'name' => 'right',
	  'page_id' => $page_id,
	  'parent_content_id' => $main_container_id,
	  'order' => 1,
  ));
  $right_container_id = $db->lastInsertId();

  //ZERO FAQs MESSAGE WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.zero-sitefaqs',
	  'parent_content_id' => $main_middle_id,
	  'order' => 10,
	  'params' => '',
  ));

  //SEARCH BOX WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.search-box-sitefaqs',
	  'parent_content_id' => $main_middle_id,
	  'order' => 11,
	  'params' => '{"title":"","blur_text":"Enter a keyword or question","titleCount":"true"}',
  ));

  //CATEGORIZED FAQ WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.categories-faqs-sitefaqs',
	  'parent_content_id' => $main_middle_id,
	  'order' => 12,
	  'params' => '{"title":"","titleCount":"true"}',
  ));

  //TOP RATED FAQs WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.faqs-sitefaqs',
	  'parent_content_id' => $right_container_id,
	  'order' => 15,
	  'params' => '{"title":"Top Rated FAQs","popularity":"rating"}',
  ));

  //FEATURED FAQs WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.faqs-sitefaqs',
	  'parent_content_id' => $right_container_id,
	  'order' => 16,
	  'params' => '{"title":"Featured FAQs","popularity":"RAND()","featured":"1"}',
  ));

  //MOST HELPFUL FAQs WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.faqs-sitefaqs',
	  'parent_content_id' => $right_container_id,
	  'order' => 17,
	  'params' => '{"title":"Most Helpful FAQs","popularity":"helpful"}',
  ));

  //MOST LIKED FAQs WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.faqs-sitefaqs',
	  'parent_content_id' => $right_container_id,
	  'order' => 18,
	  'params' => '{"title":"Most Liked FAQs","popularity":"like_count"}',
  ));

  //MOST RECENT FAQs WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.faqs-sitefaqs',
	  'parent_content_id' => $right_container_id,
	  'order' => 19,
	  'params' => '{"title":"Most Recent FAQs","popularity":"creation_date"}',
  ));

  //POPULAR TAGS WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.tagcloud-sitefaqs',
	  'parent_content_id' => $right_container_id,
	  'order' => 20,
  ));
}

//START FAQ BROWSE PAGE
$page_id = $db->select()
				->from('engine4_core_pages', 'page_id')
				->where('name = ?', 'sitefaq_index_browse')
				->limit(1)
				->query()
				->fetchColumn();

//CREATE PAGE IF NOT EXIST
if (!$page_id) {
  //CREATE PAGE
  $db->insert('engine4_core_pages', array(
	  'name' => 'sitefaq_index_browse',
	  'displayname' => 'FAQ Browse Page',
	  'title' => 'FAQ Browse Page',
	  'description' => 'This page lists FAQs.',
	  'custom' => 0,
  ));
  $page_id = $db->lastInsertId();

  //TOP CONTAINER
  $db->insert('engine4_core_content', array(
	  'type' => 'container',
	  'name' => 'top',
	  'page_id' => $page_id,
	  'order' => 1,
  ));
  $top_container_id = $db->lastInsertId();

  //MAIN CONTAINER
  $db->insert('engine4_core_content', array(
	  'type' => 'container',
	  'name' => 'main',
	  'page_id' => $page_id,
	  'order' => 2,
  ));
  $main_container_id = $db->lastInsertId();

  //INSERT TOP-MIDDLE
  $db->insert('engine4_core_content', array(
	  'type' => 'container',
	  'name' => 'middle',
	  'page_id' => $page_id,
	  'parent_content_id' => $top_container_id,
  ));
  $top_middle_id = $db->lastInsertId();

  //MAIN NAVIGATION WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.navigation-sitefaqs',
	  'parent_content_id' => $top_middle_id,
	  'order' => 3,
	  'params' => '',
  ));

  //MAIN-MIDDLE CONTAINER
  $db->insert('engine4_core_content', array(
	  'type' => 'container',
	  'name' => 'middle',
	  'page_id' => $page_id,
	  'parent_content_id' => $main_container_id,
	  'order' => 2,
  ));
  $main_middle_id = $db->lastInsertId();

  //BROWSE FAQs WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.browse-sitefaqs',
	  'parent_content_id' => $main_middle_id,
	  'order' => 3,
	  'params' => '',
  ));

  //RIGHT CONTAINER
  $db->insert('engine4_core_content', array(
	  'type' => 'container',
	  'name' => 'right',
	  'page_id' => $page_id,
	  'parent_content_id' => $main_container_id,
	  'order' => 1,
  ));
  $right_container_id = $db->lastInsertId();

  //SEARCH FORM WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.sidebar-categories-sitefaqs',
	  'parent_content_id' => $right_container_id,
	  'order' => 14,
	  'params' => '',
  ));

  //SEARCH FORM WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.search-sitefaqs',
	  'parent_content_id' => $right_container_id,
	  'order' => 15,
	  'params' => '',
  ));

  //MOST COMMENTED FAQs WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.faqs-sitefaqs',
	  'parent_content_id' => $right_container_id,
	  'order' => 18,
	  'params' => '{"title":"Most Commented FAQs","popularity":"comment_count"}',
  ));

  //MOST RECENT FAQs WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.faqs-sitefaqs',
	  'parent_content_id' => $right_container_id,
	  'order' => 18,
	  'params' => '{"title":"Most Recent FAQs","popularity":"creation_date"}',
  ));

  //MOST VIEWED FAQs WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.faqs-sitefaqs',
	  'parent_content_id' => $right_container_id,
	  'order' => 18,
	  'params' => '{"title":"Most Viewed FAQs","popularity":"view_count"}',
  ));

  //POPULAR TAGS WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.tagcloud-sitefaqs',
	  'parent_content_id' => $right_container_id,
	  'order' => 19,
  ));
}
//END FAQ BROWES PAGE

//START FAQ VIEW PAGE WORK
$select = new Zend_Db_Select($db);
$select
		->from('engine4_core_pages')
		->where('name = ?', 'sitefaq_index_view')
		->limit(1);

$info = $select->query()->fetch();

if (empty($info)) {
  //CREATE PAGE IF NOT EXIST
  $db->insert('engine4_core_pages', array(
	  'name' => 'sitefaq_index_view',
	  'displayname' => 'FAQ View Page',
	  'title' => 'FAQ View Page',
	  'description' => 'This is the FAQs view page.',
	  'custom' => 0,
	  'layout' => 'default',
  ));
  $page_id = $db->lastInsertId('engine4_core_pages');

  //MAIN CONTAINER
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'container',
	  'name' => 'main',
	  'parent_content_id' => null,
	  'order' => 2,
	  'params' => '',
  ));
  $main_container_id = $db->lastInsertId('engine4_core_content');

  //RIGHT CONTAINER
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'container',
	  'name' => 'right',
	  'parent_content_id' => $main_container_id,
	  'order' => 5,
	  'params' => '',
  ));
  $right_container_id = $db->lastInsertId('engine4_core_content');

  //MAIN MIDDLE CONTAINER
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'container',
	  'name' => 'middle',
	  'parent_content_id' => $main_container_id,
	  'order' => 6,
	  'params' => '',
  ));
  $main_middle_container_id = $db->lastInsertId('engine4_core_content');

  //FAQ VIEWER WINDOW WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.sitefaq-view-sitefaqs',
	  'parent_content_id' => $main_middle_container_id,
	  'order' => 3,
	  'params' => '',
  ));

  //FAQ COMMENT WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'core.comments',
	  'parent_content_id' => $main_middle_container_id,
	  'order' => 4,
	  'params' => '{"title":"","titleCount":"true"}',
  ));

	$order = 5;

  //FAQ RATING WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.featured-view-sitefaqs',
	  'parent_content_id' => $right_container_id,
	  'order' => $order++,
	  'params' => '{"title":"","titleCount":"true"}',
  ));

  //FAQ RATING WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.ratings-sitefaqs',
	  'parent_content_id' => $right_container_id,
	  'order' => $order++,
	  'params' => '{"title":"","titleCount":"true"}',
  ));

  //FAQ OWNER PHOTO WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.options-sitefaqs',
	  'parent_content_id' => $right_container_id,
	  'order' => $order++,
	  'params' => '',
  ));

  //FAQ INFORMATION WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.information-sitefaqs',
	  'parent_content_id' => $right_container_id,
	  'order' => $order++,
	  'params' => '{"title":"","titleCount":"true"}',
  ));

  //CATEGORIES WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.sidebar-categories-view-sitefaqs',
	  'parent_content_id' => $right_container_id,
	  'order' => $order++,
	  'params' => '',
  ));

  //SOCIAL SHARE BUTTONS WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.socialshare-sitefaqs',
	  'parent_content_id' => $right_container_id,
	  'order' => $order++,
	  'params' => '',
  ));

  //RELATED FAQs WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.related-faqs-view-sitefaqs',
	  'parent_content_id' => $right_container_id,
	  'order' => $order++,
	  'params' => '{"title":"Related FAQs","related":"tags","titleCount":"true"}',
  ));

  //SAME CATEGORIES FAQs WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.related-faqs-view-sitefaqs',
	  'parent_content_id' => $right_container_id,
	  'order' => $order++,
	  'params' => '{"title":"Same Categories FAQs","related":"categories","titleCount":"true"}',
  ));

}
//END FAQ VIEW PAGE WORK

//START FAQ MANAGE PAGE WORK
$select = new Zend_Db_Select($db);
$select
	->from('engine4_core_pages')
	->where('name = ?', 'sitefaq_index_manage')
	->limit(1);
$info = $select->query()->fetch();

if( empty($info) ) {

	//CREATE PAGE IF NOT EXIST
	$db->insert('engine4_core_pages', array(
		'name' => 'sitefaq_index_manage',
		'displayname' => 'FAQ Manage Page',
		'title' => 'FAQ Manage Page',
		'description' => 'This is the manage FAQs page.',
		'custom' => 0,
		'layout' => 'default',
	));
	$page_id = $db->lastInsertId('engine4_core_pages');

	//CONTAINERS
	$db->insert('engine4_core_content', array(
			'page_id' => $page_id,
			'type' => 'container',
			'name' => 'main',
			'parent_content_id' => Null,
			'order' => 2,
			'params' => '',
	));
	$container_id = $db->lastInsertId('engine4_core_content');

	//INSERT MAIN - MIDDLE CONTAINER
	$db->insert('engine4_core_content', array(
			'page_id' => $page_id,
			'type' => 'container',
			'name' => 'middle',
			'parent_content_id' => $container_id,
			'order' => 6,
			'params' => '',
	));
	$middle_id = $db->lastInsertId('engine4_core_content');

	//INSERT MAIN - RIGHT CONTAINER
	$db->insert('engine4_core_content', array(
			'page_id' => $page_id,
			'type' => 'container',
			'name' => 'right',
			'parent_content_id' => $container_id,
			'order' => 5,
			'params' => '',
	));
	$right_id = $db->lastInsertId('engine4_core_content');

	//INSERT TOP CONTAINER
	$db->insert('engine4_core_content', array(
			'page_id' => $page_id,
			'type' => 'container',
			'name' => 'top',
			'parent_content_id' => Null,
			'order' => 1,
			'params' => '',
	));
	$top_id = $db->lastInsertId('engine4_core_content');

	//INSERT TOP- MIDDLE CONTAINER
	$db->insert('engine4_core_content', array(
			'page_id' => $page_id,
			'type' => 'container',
			'name' => 'middle',
			'parent_content_id' => $top_id,
			'order' => 6,
			'params' => '',
	));
	$top_middle_id = $db->lastInsertId('engine4_core_content');

	$db->insert('engine4_core_content', array(
		'page_id' => $page_id,
		'type' => 'widget',
		'name' => 'sitefaq.navigation-sitefaqs',
		'parent_content_id' => $top_middle_id,
		'order' => 3,
		'params' => '',
	));

	$db->insert('engine4_core_content', array(
			'page_id' => $page_id,
			'type' => 'widget',
			'name' => 'sitefaq.search-sitefaqs',
			'parent_content_id' => $right_id,
			'order' => 3,
			'params' => '{"title":"","titleCount":"true"}',
	));

	$db->insert('engine4_core_content', array(
			'page_id' => $page_id,
			'type' => 'widget',
			'name' => 'sitefaq.manage-sitefaqs',
			'parent_content_id' => $middle_id,
			'order' => 2,
			'params' => '',
	));
}
//END FAQ MANAGE PAGE WORK

//START MOBILE FAQ HOME PAGE WORK
$select = new Zend_Db_Select($db);
$select
	->from('engine4_core_pages')
	->where('name = ?', 'sitefaq_index_mobi-home')
	->limit(1);
	;
$info = $select->query()->fetch();

if( empty($info) ) {
	//CREATE PAGE IF NOT EXIST
	$db->insert('engine4_core_pages', array(
		'name' => 'sitefaq_index_mobi-home',
		'displayname' => 'Mobile FAQ Home Page',
		'title' => 'Mobile FAQ Home Page',
		'description' => 'This is the mobile FAQ home page.',
		'custom' => 0,
		'layout' => 'default',
	));
	$page_id = $db->lastInsertId('engine4_core_pages');

	//MAIN CONTAINER
	$db->insert('engine4_core_content', array(
		'page_id' => $page_id,
		'type' => 'container',
		'name' => 'main',
		'parent_content_id' => null,
		'order' => 1,
		'params' => '',
	));
	$main_container_id = $db->lastInsertId('engine4_core_content');

	//MIDDLE CONTAINER
	$db->insert('engine4_core_content', array(
		'page_id' => $page_id,
		'type' => 'container',
		'name' => 'middle',
		'parent_content_id' => $main_container_id,
		'order' => 2,
		'params' => '',
	));
	$middle_container_id = $db->lastInsertId('engine4_core_content');

	//MAIN NAVIGATION WIDGET
	$db->insert('engine4_core_content', array(
		'page_id' => $page_id,
		'type' => 'widget',
		'name' => 'sitefaq.navigation-sitefaqs',
		'parent_content_id' => $middle_container_id,
		'order' => 3,
		'params' => '',
	));

  //SEARCH BOX WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.mobi-search-box-sitefaqs',
	  'parent_content_id' => $middle_container_id,
	  'order' => 4,
	  'params' => '{"title":"","titleCount":"true"}',
  ));

  //ZERO FAQs MESSAGE WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'sitefaq.zero-sitefaqs',
	  'parent_content_id' => $middle_container_id,
	  'order' => 5,
	  'params' => '',
  ));

	//HOME FAQS WIDGET
	$db->insert('engine4_core_content', array(
		'page_id' => $page_id,
		'type' => 'widget',
		'name' => 'sitefaq.mobi-home-sitefaqs',
		'parent_content_id' => $middle_container_id,
		'order' => 6,
		'params' => '',
	));
}
//END MOBILE FAQ HOME PAGE WORK

//START MOBILE FAQ BROWSE PAGE WORK
$select = new Zend_Db_Select($db);
$select
	->from('engine4_core_pages')
	->where('name = ?', 'sitefaq_index_mobi-browse')
	->limit(1);
$info = $select->query()->fetch();

if( empty($info) ) {
	//CREATE PAGE IF NOT EXIST
	$db->insert('engine4_core_pages', array(
		'name' => 'sitefaq_index_mobi-browse',
		'displayname' => 'Mobile FAQ Browse Page',
		'title' => 'Mobile FAQ Browse Page',
		'description' => 'This is the mobile browse FAQs page.',
		'custom' => 0,
		'layout' => 'default',
	));
	$page_id = $db->lastInsertId('engine4_core_pages');

	//MAIN CONTAINER
	$db->insert('engine4_core_content', array(
		'page_id' => $page_id,
		'type' => 'container',
		'name' => 'main',
		'parent_content_id' => null,
		'order' => 1,
		'params' => '',
	));
	$main_container_id = $db->lastInsertId('engine4_core_content');

	//MIDDLE CONTAINER
	$db->insert('engine4_core_content', array(
		'page_id' => $page_id,
		'type' => 'container',
		'name' => 'middle',
		'parent_content_id' => $main_container_id,
		'order' => 2,
		'params' => '',
	));
	$middle_container_id = $db->lastInsertId('engine4_core_content');

	//MAIN NAVIGATION WIDGET
	$db->insert('engine4_core_content', array(
		'page_id' => $page_id,
		'type' => 'widget',
		'name' => 'sitefaq.navigation-sitefaqs',
		'parent_content_id' => $middle_container_id,
		'order' => 3,
		'params' => '',
	));

	//SEARCH FORM WIDGET
	$db->insert('engine4_core_content', array(
		'page_id' => $page_id,
		'type' => 'widget',
		'name' => 'sitefaq.search-sitefaqs',
		'parent_content_id' => $middle_container_id,
		'order' => 4,
		'params' => '',
	));

	//BROWSE FAQS WIDGET
	$db->insert('engine4_core_content', array(
		'page_id' => $page_id,
		'type' => 'widget',
		'name' => 'sitefaq.mobi-browse-sitefaqs',
		'parent_content_id' => $middle_container_id,
		'order' => 5,
		'params' => '',
	));
}
//END MOBILE FAQ BROWSE PAGE WORK

//START MOBILE FAQ VIEW PAGE WORK
$select = new Zend_Db_Select($db);
$select
	->from('engine4_core_pages')
	->where('name = ?', 'sitefaq_index_mobi-view')
	->limit(1);
	;
$info = $select->query()->fetch();

if( empty($info) ) {
	//CREATE PAGE IF NOT EXIST
	$db->insert('engine4_core_pages', array(
		'name' => 'sitefaq_index_mobi-view',
		'displayname' => 'Mobile FAQ View Page',
		'title' => 'Mobile FAQ View Page',
		'description' => 'This is the mobile view FAQ page.',
		'custom' => 0,
		'layout' => 'default',
	));
	$page_id = $db->lastInsertId('engine4_core_pages');

	//MAIN CONTAINER
	$db->insert('engine4_core_content', array(
		'page_id' => $page_id,
		'type' => 'container',
		'name' => 'main',
		'parent_content_id' => null,
		'order' => 1,
		'params' => '',
	));
	$main_container_id = $db->lastInsertId('engine4_core_content');

	//MIDDLE CONTAINER
	$db->insert('engine4_core_content', array(
		'page_id' => $page_id,
		'type' => 'container',
		'name' => 'middle',
		'parent_content_id' => $main_container_id,
		'order' => 2,
		'params' => '',
	));
	$middle_container_id = $db->lastInsertId('engine4_core_content');

	//VIEW FAQS WIDGET
	$db->insert('engine4_core_content', array(
		'page_id' => $page_id,
		'type' => 'widget',
		'name' => 'sitefaq.sitefaq-view-sitefaqs',
		'parent_content_id' => $middle_container_id,
		'order' => 3,
		'params' => '',
	));

  //FAQ COMMENT WIDGET
  $db->insert('engine4_core_content', array(
	  'page_id' => $page_id,
	  'type' => 'widget',
	  'name' => 'core.comments',
	  'parent_content_id' => $main_middle_container_id,
	  'order' => 4,
	  'params' => '{"title":"","titleCount":"true"}',
  ));
}
//END MOBILE FAQ VIEW PAGE WORK

//START FAQ MANAGE PAGE WORK
$select = new Zend_Db_Select($db);
$select
	->from('engine4_core_pages')
	->where('name = ?', 'sitefaq_index_mobi-manage')
	->limit(1);
$info = $select->query()->fetch();

if( empty($info) ) {
	//CREATE PAGE IF NOT EXIST
	$db->insert('engine4_core_pages', array(
		'name' => 'sitefaq_index_mobi-manage',
		'displayname' => 'Mobile FAQ Manage Page',
		'title' => 'Mobile FAQ Manage Page',
		'description' => 'This is the mobile manage FAQs page.',
		'custom' => 0,
		'layout' => 'default',
	));
	$page_id = $db->lastInsertId('engine4_core_pages');

	//MAIN CONTAINER
	$db->insert('engine4_core_content', array(
		'page_id' => $page_id,
		'type' => 'container',
		'name' => 'main',
		'parent_content_id' => null,
		'order' => 1,
		'params' => '',
	));
	$main_container_id = $db->lastInsertId('engine4_core_content');

	//MIDDLE CONTAINER
	$db->insert('engine4_core_content', array(
		'page_id' => $page_id,
		'type' => 'container',
		'name' => 'middle',
		'parent_content_id' => $main_container_id,
		'order' => 2,
		'params' => '',
	));
	$middle_container_id = $db->lastInsertId('engine4_core_content');

	//MAIN NAVIGATION WIDGET
	$db->insert('engine4_core_content', array(
		'page_id' => $page_id,
		'type' => 'widget',
		'name' => 'sitefaq.navigation-sitefaqs',
		'parent_content_id' => $middle_container_id,
		'order' => 3,
		'params' => '',
	));

	//SEARCH FORM WIDGET
	$db->insert('engine4_core_content', array(
		'page_id' => $page_id,
		'type' => 'widget',
		'name' => 'sitefaq.search-sitefaqs',
		'parent_content_id' => $middle_container_id,
		'order' => 4,
		'params' => '',
	));

	//BROWSE FAQS WIDGET
	$db->insert('engine4_core_content', array(
		'page_id' => $page_id,
		'type' => 'widget',
		'name' => 'sitefaq.manage-sitefaqs',
		'parent_content_id' => $middle_container_id,
		'order' => 5,
		'params' => '',
	));
}
//END MOBILE FAQ BROWSE PAGE WORK