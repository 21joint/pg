
UPDATE `engine4_core_menuitems` SET `params` = '{"route":"sitepagenote_home","action":"home"}' WHERE `engine4_core_menuitems`.`name` = 'sitepage_main_note' LIMIT 1 ;
UPDATE `engine4_core_pages` SET `name` = 'sitepagenote_index_browse' WHERE `engine4_core_pages`.`name` ='sitepagenote_index_notelist' LIMIT 1 ;

INSERT IGNORE INTO `engine4_seaocore_tabs` (`module` ,`type` ,`name` ,`title` ,`enabled` ,`order` ,`limit`)VALUES
('sitepagenote', 'notes', 'recent_pagenotes', 'Recent', '1', '1', '24'),
('sitepagenote', 'notes', 'liked_pagenotes', 'Most Liked', '1', '2', '24'),
('sitepagenote', 'notes', 'viewed_pagenotes', 'Most Viewed', '1', '3', '24'),
('sitepagenote', 'notes', 'commented_pagenotes', 'Most Commented', '0', '4', '24'),
('sitepagenote', 'notes', 'featured_pagenotes', 'Featured', '0', '5', '24'),
('sitepagenote', 'notes', 'random_pagenotes', 'Random', '0', '6', '24');