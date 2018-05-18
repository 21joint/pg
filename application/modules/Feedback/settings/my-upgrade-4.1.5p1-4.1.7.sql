-- --------------------------------------------------------

UPDATE `engine4_core_mailtemplates` SET `module` = 'feedback',
`vars` = '[feedback_title], [feedback_owner], [feedback_status], [feedback_date], [email], [link], [browse_link]' WHERE `engine4_core_mailtemplates`.`type` = 'notify_feedback_create' LIMIT 1 ;

-- --------------------------------------------------------

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('mobi_browse_feedback', 'feedback', 'Feedback', '', '{"route":"feedback_browse"}', 'mobi_browse', '', 0, 0, 999);

-- --------------------------------------------------------