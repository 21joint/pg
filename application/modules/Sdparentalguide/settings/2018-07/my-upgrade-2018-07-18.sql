INSERT INTO `engine4_gg_tasks` (`task_id`, `title`, `module`, `plugin`, `per_page`, `enabled`) VALUES (NULL, 'Recalculate Answers Count', 'Sdparentalguide', 'Sdparentalguide_Plugin_Task_Answers', '50', '1');

ALTER TABLE `engine4_users`
ADD `gg_answer_count` int NOT NULL DEFAULT 0 AFTER `gg_guide_count`;