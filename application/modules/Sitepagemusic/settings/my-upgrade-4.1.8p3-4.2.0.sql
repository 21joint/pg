UPDATE `engine4_activity_actiontypes` SET `body` = '{item:$object} created a new playlist {var:$linked_music_title}:', `displayable` = '6',`is_object_thumb` = '1' WHERE `engine4_activity_actiontypes`.`type` = 'sitepagemusic_admin_new' LIMIT 1;

INSERT IGNORE INTO `engine4_core_menuitems` ( `name` , `module` , `label` , `plugin` , `params` , `menu` , `submenu` , `enabled` , `order` )VALUES
 ('sitepage_main_music', 'sitepagemusic', 'Music', 'Sitepagemusic_Plugin_Menus::canViewMusics', '{"route":"sitepagemusic_playlist","action":"musiclist"}', 'sitepage_main', '', 1, '21');