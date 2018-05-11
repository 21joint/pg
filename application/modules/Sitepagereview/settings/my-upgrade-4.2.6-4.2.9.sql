INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES
('sitepagereview_create', 'sitepagereview', '{item:$subject} has created a page review {var:$eventname} in {item:$object}.', 0, '');


INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
("SITEPAGEREVIEW_CREATENOTIFICATION_EMAIL", "sitepagereview", "[host],[email],[recipient_title],[subject],[message],[template_header],[site_title],[template_footer]");