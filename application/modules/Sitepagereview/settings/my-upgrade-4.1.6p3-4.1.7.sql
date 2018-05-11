ALTER TABLE `engine4_sitepagereview_reviews` ADD INDEX ( `page_id` );
ALTER TABLE `engine4_sitepagereview_reviews` ADD INDEX ( `owner_id` );
ALTER TABLE `engine4_sitepagereview_ratings` ADD INDEX ( `page_id` );
ALTER TABLE `engine4_sitepagereview_reviewcats` ADD INDEX ( `category_id` );