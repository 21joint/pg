-- --------------------------------------------------------

--
-- Dumping data for table `engine4_core_modules`
--

INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES
('feedback', 'Feedbacks', 'Feedbacks', '4.9.2', 1, 'extra');

-- --------------------------------------------------------

--
-- Dumping data for table `engine4_activity_actiontypes`
--

INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES
('feedback_new', 'feedback', '{item:$subject} posted a new feedback:', 1, 5, 1, 3, 1, 1),
('comment_feedback', 'feedback', '{item:$subject} commented on {item:$owner}''s {item:$object:feedback}: {body:$body}', 1, 1, 1, 1, 1, 0);
-- --------------------------------------------------------


--
-- Dumping data for table `engine4_seaocore_searchformsetting`
--


INSERT IGNORE INTO `engine4_seaocore_searchformsetting` (`module`, `name`, `display`, `order`, `label`) VALUES
('feedback', 'orderby', 1, 4, 'Browse By'),
('feedback', 'stat', 1, 3, 'Status'),
('feedback', 'category', 1, 2, 'Category'),
('feedback', 'search', 1, 1, 'Search Feedback');