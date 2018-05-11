INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES
('sitepagemusic_create', 'sitepagemusic', '{item:$subject} has created a page music {var:$eventname}.', 0, '');


INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
("SITEPAGEMUSIC_CREATENOTIFICATION_EMAIL", "sitepagemusic", "[host],[email],[recipient_title],[subject],[message],[template_header],[site_title],[template_footer]");