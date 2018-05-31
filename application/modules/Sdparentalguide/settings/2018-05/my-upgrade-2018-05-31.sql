INSERT INTO `engine4_core_content` (`content_id`, `page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(500, 5, 'container', 'main', NULL, 2, '[\"\"]', NULL),
(511, 5, 'container', 'middle', 500, 6, '[\"\"]', NULL),
(531, 5, 'widget', 'core.container-tabs', 511, 7, '{\"max\":5}', NULL),
(3261, 5, 'container', 'top', NULL, 1, '[\"\"]', NULL),
(3262, 5, 'container', 'middle', 3261, 6, '[\"\"]', NULL),
(3842, 5, 'widget', 'siteusercoverphoto.user-cover-photo', 3262, 4, '{\"title\":\"\",\"titleCount\":\"\",\"showContent\":[\"mainPhoto\",\"title\",\"updateInfoButton\",\"settingsButton\",\"optionsButton\",\"friendShipButton\",\"composeMessageButton\"],\"profile_like_button\":\"1\",\"columnHeight\":\"500\",\"editFontColor\":\"0\",\"nomobile\":\"0\",\"name\":\"siteusercoverphoto.user-cover-photo\"}', NULL),
(3843, 5, 'widget', 'seaocore.scroll-top', 511, 10, '[\"[]\"]', NULL),
(8093, 5, 'widget', 'user.cover-photo', 3262, 3, '[\"[]\"]', NULL),
(8150, 5, 'widget', 'sdparentalguide.profile-landing', 531, 8, '{\"title\":\"Overview\",\"name\":\"sdparentalguide.profile-landing\"}', NULL),
(8151, 5, 'widget', 'user.profile-fields', 531, 9, '{\"title\":\"Info\"}', NULL);