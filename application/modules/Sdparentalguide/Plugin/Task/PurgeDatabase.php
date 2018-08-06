<?php
class Sdparentalguide_Plugin_Task_PurgeDatabase extends Sdparentalguide_Plugin_Task_Abstract
{
    public function execute($page = 1, $job_user = null) {
        // Tables
        $usersTable = Engine_Api::_()->getDbtable("users", "user");
        $actionsTable = Engine_Api::_()->getDbtable("actions", "activity");
        $attachmentsTable = Engine_Api::_()->getDbtable("attachments", "activity");
        $commentsTable = Engine_Api::_()->getDbtable("comments", "activity");
        $likesTable = Engine_Api::_()->getDbtable("likes", "activity");
        $notificationsTable = Engine_Api::_()->getDbtable("notifications", "activity");
        $notificationsettingsTable = Engine_Api::_()->getDbtable("notificationsettings", "activity");
        $streamsTable = Engine_Api::_()->getDbtable("stream", "activity");

        $questionsTable = Engine_Api::_()->getDbtable("questions", "ggcommunity");
        $answersTable = Engine_Api::_()->getDbtable("answers", "ggcommunity");
        $qCommentsTable = Engine_Api::_()->getDbtable("comments", "ggcommunity");

        $coreCommentsTable = Engine_Api::_()->getDbtable("comments", "core");
        $coreLikesTable = Engine_Api::_()->getDbtable("likes", "core");

        $badgesTable = Engine_Api::_()->getDbtable("badges", "sdparentalguide");
        $assignedTable = Engine_Api::_()->getDbtable("assignedBadges", "sdparentalguide");

        $creditsTable = Engine_Api::_()->getDbtable("credits", "sitecredit");

        $reviewsTable = Engine_Api::_()->getDbtable("listings", "sitereview");
        $listingTypesTable = Engine_Api::_()->getDbtable("listingtypes", "sitereview");
        $categoriesTable = Engine_Api::_()->getDbtable("categories", "sitereview");

        $topicsTable = Engine_Api::_()->getDbtable("topics", "sdparentalguide");

        $searchsTable = Engine_Api::_()->getDbTable("search", "sdparentalguide");
        $aliassTable = Engine_Api::_()->getDbtable("searchTermsAliases", "sdparentalguide");

        $followersTable = Engine_Api::_()->getDbtable("membership", "user");

        // Get ListingTypes of strollers and car seats and use it in topics
        $listingTypeSelect = $listingTypesTable->select()->where("listingtype_id IN(?)", array(9, 10));
        $listingTypes = $listingTypesTable->fetchAll($listingTypeSelect);
        try {
          if ( count( $listingTypes ) > 0 ) {
            foreach ( $listingTypes as $type ) {
                // Get Topics
                $topicSelect = $topicsTable->select()->where("topic_id != ?", $type->gg_topic_id);
                $topics = $topicsTable->fetchAll($topicSelect);
                if ( count( $topics ) > 0 ) {
                    foreach ($topics as $topic) {
                        // Delete Question Answer Topics
                        $topic->delete();
                    }
                }
            }
          }
        } catch (Exception $e) {
          echo"<pre>";print_r($e);echo"</pre>";
        }

        // Get Categories except for strollers and car seats
        $categorySelect = $categoriesTable->select()->where("listingtype_id NOT IN(?)", array(9, 10));
        $categories = $categoriesTable->fetchAll($categorySelect);
        try {
          if ( count( $categories ) > 0 ) {
            foreach ($categories as $category) {
                // Delete Sitereviews Category
                $category->delete();
            }
          }
        } catch (Exception $e) {
          echo"<pre>";print_r($e);echo"</pre>";
        }

        // Get Search Alias
        $aliasSelect = $aliassTable->select();
        $aliasPaginator = Zend_Paginator::factory($aliasSelect);
        $aliasPaginator->setCurrentPageNumber($page);
        $aliasPaginator->setItemCountPerPage($this->_task->per_page);
        try {
          if ( count( $aliasPaginator ) > 0 ) {
            foreach ( $aliasPaginator->getIterator() as $alias ) {
                // Delete Search Term Alias
                $alias->delete();
            }
          }
        } catch (Exception $e) {
          echo"<pre>";print_r($e);echo"</pre>";
        }

        // Get Users except from admins
        $userSelect = $usersTable->select()->where("level_id NOT IN(?)", array(1, 2, 3));
        if( !empty( $job_user ) ){
            $userSelect->where("user_id = ?", $job_user);
        }
        $userPaginator = Zend_Paginator::factory($userSelect);
        $userPaginator->setCurrentPageNumber($page);
        $userPaginator->setItemCountPerPage($this->_task->per_page);
        try {
          if ( count( $userPaginator ) > 0 ) {
            foreach ( $userPaginator->getIterator() as $user ) {
                // Get Activity Actions
                $userActionSelect = $actionsTable->select()->where("subject_id = ?", $user->getIdentity());
                $userActions = $actionsTable->fetchAll($userActionSelect);
                if ( count( $userActions ) > 0 ) {
                    foreach ( $userActions as $action ) {
                        // Get Activity Action's Attachments
                        $userAttachmentSelect = $attachmentsTable->select()->where("action_id = ?", $action->getIdentity());
                        $userAttachments = $attachmentsTable->fetchAll($userAttachmentSelect);
                        if ( count( $userAttachments ) > 0 ) {
                            foreach ( $userAttachments as $attachment ) {
                                // Delete Attachment
                                $attachment->delete();
                            }
                        }

                        // Get Activity Action's Stream
                        $userStreamSelect = $streamsTable->select()->where("action_id = ?", $action->getIdentity());
                        $userStreams = $streamsTable->fetchAll($userStreamSelect);
                        if ( count( $userStreams ) > 0 ) {
                            foreach ( $userStreams as $stream ) {
                                // Delete Stream
                                $stream->delete();
                            }
                        }
                        // Delete Action
                        $action->delete();
                    }
                }

                // Get Notifications
                $userNotificationSelect = $notificationsTable->select()->where("user_id = ?", $user->getIdentity());
                $userNotifications = $notificationsTable->fetchAll($userNotificationSelect);
                if ( count( $userNotifications ) > 0 ) {
                    foreach ( $userNotifications as $notification ) {
                        // Delete Notification
                        $notification->delete();
                    }
                }

                // Get Notification Settings
                $userNotifySettingSelect = $notificationsettingsTable->select()->where("user_id = ?", $user->getIdentity());
                $userNotifySettings = $notificationsettingsTable->fetchAll($userNotifySettingSelect);
                if ( count( $userNotifySettings ) > 0 ) {
                    foreach ( $userNotifySettings as $setting ) {
                        // Delete notification settings
                        $setting->delete();
                    }
                }

                // Get Search
                $searchSelect = $searchsTable->select()->where("gg_user_created = ?", $user->getIdentity());
                $searchs = $searchsTable->fetchAll($searchSelect);
                if ( count( $searchs ) > 0 ) {
                    foreach ( $searchs as $search ) {
                        // Delete search
                        $search->delete();
                    }
                }

                // Get Activity Comments
                $commentSelect = $commentsTable->select()->where("poster_id = ?", $user->getIdentity())->where("poster_type = ?", "user");
                $comments = $commentsTable->fetchAll($commentSelect);
                if ( count( $comments ) > 0 ) {
                    foreach ( $comments as $comment ) {
                        // Delete Activity Comment of user
                        $comment->delete();
                    }
                }

                // Get Activity Likes
                $likeSelect = $likesTable->select()->where("poster_id = ?", $user->getIdentity())->where("poster_type = ?", "user");
                $likes = $likesTable->fetchAll($likeSelect);
                if ( count( $likes ) > 0 ) {
                    foreach ( $likes as $like ) {
                        //Delete Activity Like of user
                        $like->delete();
                    }
                }

                // Get Core Comments
                $coreCommentSelect = $coreCommentsTable->select()->where("poster_id = ?", $user->getIdentity())->where("poster_type = ?", "user");
                $coreComments = $coreCommentsTable->fetchAll($coreCommentSelect);
                if ( count( $coreComments ) > 0 ) {
                    foreach ( $coreComments as $coreComment ) {
                        // Delete User Comment
                        $coreComment->delete();
                    }
                }

                // Get Core Likes
                $coreLikeSelect = $coreLikesTable->select()->where("poster_id = ?", $user->getIdentity())->where("poster_type = ?", "user");
                $coreLikes = $coreLikesTable->fetchAll($coreLikeSelect);
                if ( count( $coreLikes ) > 0 ) {
                    foreach ( $coreLikes as $coreLike ) {
                        // Delete User Like
                        $coreLike->delete();
                    }
                }

                // Get Questions
                $questionSelect = $questionsTable->select()->where("user_id = ?", $user->getIdentity());
                $questions = $questionsTable->fetchAll($questionSelect);
                if ( count( $questions ) > 0 ) {
                    foreach ( $questions as $question ) {
                        // Get Question's Answers
                        $answerSelect = $answersTable->select()->where("parent_id = ?", $question->question_id)->where("parent_type = ?", "ggcommunity_question");
                        $answers = $answersTable->fetchAll($answerSelect);
                        if ( count( $answers ) > 0 ) {
                            foreach ( $answers as $answer ) {
                                // Get Question's Comments
                                $aCommentSelect = $qCommentsTable->select()->where("parent_id = ?", $question->question_id)->where("parent_type = ?", "ggcommunity_question");
                                $aComments = $answersTable->fetchAll($aCommentSelect);
                                if ( count( $aComments ) > 0 ) {
                                    foreach ( $aComments as $comment ) {
                                        //Delete Question Comment
                                        $comment->delete();
                                    }
                                }
                                // Delete Answer
                                $answer->delete();
                            }
                        }

                        // Get Question's Comments
                        $qCommentSelect = $qCommentsTable->select()->where("parent_id = ?", $question->question_id)->where("parent_type = ?", "ggcommunity_question");
                        $qComments = $answersTable->fetchAll($qCommentSelect);
                        if ( count( $qComments ) > 0 ) {
                            foreach ( $qComments as $comment ) {
                                //Delete Question Comment
                                $comment->delete();
                            }
                        }
                        //Delete Question
                        $question->delete();
                    }
                }

                // Get Badges
                $badgeSelect = $badgesTable->select()->where("owner_id = ?", $user->getIdentity());
                $badges = $badgesTable->fetchAll($badgeSelect);
                if ( count( $badges ) > 0 ) {
                    foreach ( $badges as $badge ) {
                        $assignedSelect = $assignedTable->select()->where("badge_id = ?", $badge->badge_id);
                        $assignedBadges = $assignedTable->fetchAll($assignedSelect);
                        if ( count( $assignedBadges ) > 0 ) {
                            foreach ( $assignedBadges as $ab ) {
                                // Delete User Assigned Badges
                                $ab->delete();
                            }
                        }
                        // Delete User Badges
                        $badge->delete();
                    }
                }

                // Get Credits
                $creditSelect = $creditsTable->select()->where("user_id = ?", $user->getIdentity());
                $credits = $creditsTable->fetchAll($creditSelect);
                if ( count( $credits ) > 0 ) {
                    foreach ( $credits as $credit ) {
                        //Delete User Credit
                        $credit->delete();
                    }
                }

                // Get Reviews
                $reviewSelect = $reviewsTable->select()->where("owner_id = ?", $user->getIdentity());
                $reviews = $reviewsTable->fetchAll($reviewSelect);
                if ( count( $reviews ) > 0 ) {
                    foreach ( $reviews as $review ) {
                        // Delete user review
                        $review->delete();
                    }
                }

                // Get Followers
                $followerSelect = $followersTable->select()->where("user_id = ".$user->getIdentity()." OR resource_id = ".$user->getIdentity());
                $followers = $followersTable->fetchAll($followerSelect);
                if ( count( $followers ) > 0 ) {
                    foreach ( $followers as $follower ) {
                        // Delete user follower
                        $follower->delete();
                    }
                }

                // Delete User
                $user->delete();
            }
          }
        } catch (Exception $e) {
          echo"<pre>";print_r($e);echo"</pre>";
        }

        return $userPaginator;
    }
}