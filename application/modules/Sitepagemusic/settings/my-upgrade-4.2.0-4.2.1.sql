UPDATE `engine4_core_menuitems` SET `params` = '{"route":"sitepagemusic_home","action":"home"}' WHERE `engine4_core_menuitems`.`name` = 'sitepage_main_music' LIMIT 1 ;

UPDATE `engine4_core_pages` SET `name` = 'sitepagemusic_playlist_browse' WHERE `engine4_core_pages`.`name` ='sitepagemusic_playlist_musiclist' LIMIT 1 ;


INSERT IGNORE INTO `engine4_seaocore_tabs` (`module` ,`type` ,`name` ,`title` ,`enabled` ,`order` ,`limit`)VALUES
('sitepagemusic', 'musics', 'recent_pagemusics', 'Recent', '1', '1', '24'),
('sitepagemusic', 'musics', 'liked_pagemusics', 'Most Liked', '1', '2', '24'),
('sitepagemusic', 'musics', 'viewed_pagemusics', 'Most Viewed', '1', '3', '24'),
('sitepagemusic', 'musics', 'commented_pagemusics', 'Most Commented', '0', '4', '24'),
('sitepagemusic', 'musics', 'featured_pagemusics', 'Featured', '0', '5', '24'),
('sitepagemusic', 'musics', 'random_pagemusics', 'Random', '0', '6', '24');