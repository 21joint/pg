<?php $db = Engine_Db_Table::getDefaultAdapter(); 

$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("siteseo_admin_main_managemetatags", "siteseo", "Manage Meta Tags","",\'{"route":"admin_default","module":"siteseo","controller":"meta-tags","action":"manage" }\',"siteseo_admin_main","", 20),
("siteseo_admin_main_pagesmetatags", "siteseo", "Pages Meta Tags","",\'{"route":"admin_default","module":"siteseo","controller":"meta-tags","action":"manage" }\',"siteseo_admin_main_metatags","", 30),
("siteseo_admin_main_contentmetatags", "siteseo", "Content Meta Tags","",\'{"route":"admin_default","module":"siteseo","controller":"meta-tags","action":"manage-content" }\',"siteseo_admin_main_metatags","", 40),
("siteseo_admin_main_sitemap", "siteseo", "Sitemap", "",\'{"route":"admin_default","module":"siteseo","controller":"sitemap","action":"index" }\',"siteseo_admin_main","", 50),
("siteseo_admin_main_keywordmonitor", "siteseo", "Track Keywords", "",\'{"route":"admin_default","module":"siteseo","controller":"meta-tags","action":"keywords-ranking" }\',"siteseo_admin_main","", 55),
("siteseo_admin_main_fileeditor", "siteseo", "File Editors","",\'{"route":"admin_default","module":"siteseo","controller":"file-editor","action":"index" }\',"siteseo_admin_main","", 60),
("siteseo_admin_main_schema", "siteseo", "Schema Markup","",\'{"route":"admin_default","module":"siteseo","controller":"settings","action":"schema" }\',"siteseo_admin_main","", 70),
("siteseo_admin_main_seotips", "siteseo", "SEO Tips","",\'{"route":"admin_default","module":"siteseo","controller":"settings","action":"seo-tips"}\',"siteseo_admin_main","", 80),
("siteseo_admin_main_seotools", "siteseo", "SEO Tools","",\'{"route":"admin_default","module":"siteseo","controller":"settings","action":"seo-tools"}\',"siteseo_admin_main","", 90),
("siteseo_admin_main_support", "siteseo", "Support", "",\'{"route":"admin_default","module":"siteseo","controller":"settings","action":"support"}\',"siteseo_admin_main","", 100),
("siteseo_admin_main_faqs", "siteseo", "FAQ", "",\'{"route":"admin_default","module":"siteseo","controller":"settings","action":"faq" }\',"siteseo_admin_main","", 110);');

$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("core_footer_sitemap", "siteseo", "Sitemap","", \'{"route":"siteseo_sitemap","target":"_blank"}\', "core_footer","", 4);');

$db->query('INSERT IGNORE INTO `engine4_core_tasks` (`title`, `module`, `plugin`, `timeout`) VALUES ("Submit Sitemap Files", "siteseo", "Siteseo_Plugin_Task_AutoSubmitSitemap", 604800);');

$db->query('INSERT IGNORE INTO `engine4_siteseo_contenttypes` 
(`type`, `title`, `changefreq`, `priority`, `enabled`, `order`, `max_items`, `sitemap_count`) VALUES
("menu_urls", "Menu Urls", "always", "0.5", 1, 1, 0, 0),
("custom_pages", "Custom Pages", "always", "0.5", 1, 2, 0, 0);');

// CREATE OPEN SEARCH DESCRIPTION DOCUMENT IF IT DOES NOT EXIST
$openSearch = Engine_Api::_()->getApi('openSearch','siteseo');
$openSearch->write();

// CREATE ROBOTS.TXT FILE IF IT DOES NOT EXIST
$fileName = 'robots.txt';
$filePath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'robots.txt';
if (!file_exists($filePath)) {
	$robotsFileContent = '';
	file_put_contents($filePath, $robotsFileContent);
	@chmod($filePath, 0777);
}
?>