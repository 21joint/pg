INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES
('sitepageoffer_create', 'sitepageoffer', '{item:$subject} has created a page offer {var:$eventname}.', 0, '');


INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
("SITEPAGEOFFER_CREATENOTIFICATION_EMAIL", "sitepageoffer", "[host],[email],[recipient_title],[subject],[message],[template_header],[site_title],[template_footer]");