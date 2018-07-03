/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Stars Developer
 * Created: Jun 25, 2018
 */

ALTER TABLE `engine4_storage_files`
ADD `gg_deleted` tinyint NOT NULL DEFAULT '0';

ALTER TABLE `engine4_ggcommunity_answers`
ADD `gg_deleted` tinyint NOT NULL DEFAULT '0';


INSERT INTO `engine4_siteapi_oauth_tokens` (`consumer_id`, `user_id`, `type`, `token`, `secret`, `verifier`, `callback_url`, `revoked`, `authorized`, `num_of_login`, `creation_date`) VALUES
(2,	1,	'access',	'5i8bk1234tmf4qfiljr24ptqcolj40yb',	'3cgqy81annu8ud4rdlnrnqg35lotv98o',	'ox5ythqg7wf9xjvuso3au0yjb8zf1pzl',	'',	0,	1,	2,	'2018-06-30');


INSERT INTO `engine4_siteapi_oauth_tokens` (`consumer_id`, `user_id`, `type`, `token`, `secret`, `verifier`, `callback_url`, `revoked`, `authorized`, `num_of_login`, `creation_date`) VALUES
(2,	1907,	'access',	'jet6u8z8zbjb1234s0b6u7ygp3v1ien',	'r63xeerxhkgy3oxbh2h46785abjm4tfo',	'71sa2lx7w8sq4530xx2pdzs6qnvjn3j6',	'',	0,	1,	2,	'2018-06-30');

