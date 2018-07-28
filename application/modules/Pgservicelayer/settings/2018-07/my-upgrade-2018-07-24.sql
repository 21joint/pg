/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Stars Developer
 * Created: Jul 24, 2018
 */

INSERT INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`, `is_grouped`, `is_object_thumb`, `editable`) VALUES
('question_answer',	'ggcommunity',	'{item:$subject} answered a question {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0),
('question_answer_author_comment',	'ggcommunity',	'{item:$subject} commented on {item:$owner}\'s answer {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0),
('question_answer_author_vote',	'ggcommunity',	'{item:$subject} voted for {item:$owner}\'s answer {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0),
('question_answer_chosen',	'ggcommunity',	'{item:$subject} chosen {item:$owner}\'s answer  {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0),
('question_answer_comment',	'ggcommunity',	'{item:$subject} commented on an answer {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0),
('question_answer_vote',	'ggcommunity',	'{item:$subject} voted for an answer {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0),
('question_author_answer',	'ggcommunity',	'{item:$subject} answered {item:$owner}\'s question {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0),
('question_author_comment',	'ggcommunity',	'{item:$subject} commented on {item:$owner}\'s question {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0),
('question_author_vote',	'ggcommunity',	'{item:$subject} voted for {item:$owner}\'s question {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0),
('question_comment',	'ggcommunity',	'{item:$subject} commented on a question {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0),
('question_create',	'ggcommunity',	'{item:$subject} asked a new question {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0),
('question_vote',	'ggcommunity',	'{item:$subject} voted for a question {item:$object}:',	1,	5,	1,	3,	1,	1,	0,	0,	0);