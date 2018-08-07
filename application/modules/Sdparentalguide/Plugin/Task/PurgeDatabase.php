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
            $types = array();
            foreach ( $listingTypes as $type ) {
                $types[] = $type->gg_topic_id;
            }
            if ( count($types) > 0 ) {
                // Delete Topics
                $topicsTable->delete(array(
                    "topic_id NOT IN(?)" => $types,
                ));
            }
          }
        } catch (Exception $e) {
          //Silent
        }

        try {
          // Delete Categories except for strollers and car seats
          $categoriesTable->delete(array(
            "listingtype_id NOT IN(?)" => array(9, 10),
          ));
        } catch (Exception $e) {
          //Silent
        }
        
        try{
            $qCommentsTable->delete();
        } catch (Exception $ex) {

        }

        // Get Users except from super admins, admins and moderator
        $userSelect = $usersTable->select()->where("level_id NOT IN(?)", array(1, 2, 3));
        if( !empty( $job_user ) ){
            $userSelect->where("user_id = ?", $job_user);
        }
        $userPaginator = Zend_Paginator::factory($userSelect);
        $userPaginator->setCurrentPageNumber($page);
        $userPaginator->setItemCountPerPage($this->_task->per_page);
        try {
          if ( count( $userPaginator ) > 0 ) {
            foreach ( $userPaginator as $user ) {
                // Get User Activity Actions
                $userActionSelect = $actionsTable->select()->where("subject_id = ?", $user->getIdentity());
                $userActions = $actionsTable->fetchAll($userActionSelect);
                if ( count( $userActions ) > 0 ) {
                    foreach ( $userActions as $action ) {
                        // Delete User Activity Action's Attachments
                        $attachmentsTable->delete(array(
                            "action_id = ?" => $action->getIdentity()
                        ));

                        // Delete User Activity Action's Stream
                        $streamsTable->delete(array(
                            "action_id = ?" => $action->getIdentity()
                        ));
                    }
                }

                // Delete User Activity Actions
                $actionsTable->delete(array(
                    "subject_id = ?" => $user->getIdentity()
                ));

                // Delete User Notifications
                $notificationsTable->delete(array(
                    "user_id = ?" => $user->getIdentity()
                ));

                // Delete User Notification Settings
                $notificationsettingsTable->delete(array(
                    "user_id = ?" => $user->getIdentity()
                ));

                // Delete User Search
                $searchsTable->delete(array(
                    "gg_user_created = ?" => $user->getIdentity()
                ));

                // Delete User Search Alias
                $aliassTable->delete(array(
                    "gg_user_created = ?" => $user->getIdentity()
                ));

                // Delete User Activity Comments
                $commentsTable->delete(array(
                    "poster_id = ?" => $user->getIdentity(),
                    "poster_type = ?" => "user"
                ));

                // Delete User Activity Likes
                $likesTable->delete(array(
                    "poster_id = ?" => $user->getIdentity(),
                    "poster_type = ?" => "user"
                ));

                // Delete User Core Comments
                $coreCommentsTable->delete(array(
                    "poster_id = ?" => $user->getIdentity(),
                    "poster_type = ?" => "user"
                ));

                // Delete User Core Likes
                $coreLikesTable->delete(array(
                    "poster_id = ?" => $user->getIdentity(),
                    "poster_type = ?" => "user"
                ));

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
                                // Delete User Answer's Comments
                                $coreCommentsTable->delete(array(
                                    "resource_id = ?" => $answer->answer_id,
                                    "resource_type = ?" => "ggcommunity_answer"
                                ));
                            }
                        }

                        // Delete User Question's Answers
                        $answersTable->delete(array(
                            "parent_id = ?" => $question->question_id,
                            "parent_type = ?" => "ggcommunity_question"
                        ));

                        // Delete User Question's Comments
                        $coreCommentsTable->delete(array(
                            "resource_id = ?" => $question->question_id,
                            "resource_type = ?" => "ggcommunity_question"
                        ));
                    }
                }
                // Delete User Questions
                $questionsTable->delete(array(
                    "user_id = ?" => $user->getIdentity()
                ));
                
                // Delete User Answers
                $answersTable->delete(array(
                    "user_id = ?" => $user->getIdentity()
                ));

                // Get User Badges
                $badgeSelect = $badgesTable->select()->where("owner_id = ?", $user->getIdentity());
                $badges = $badgesTable->fetchAll($badgeSelect);
                if ( count( $badges ) > 0 ) {
                    foreach ( $badges as $badge ) {
                        // Delete User Assigned Badges
                        $assignedTable->delete(array(
                            "badge_id = ?" => $badge->badge_id
                        ));
                    }
                }
                // Delete User Badges
                $badgesTable->delete(array(
                    "owner_id = ?" => $user->getIdentity()
                ));

                // Delete User Credits
                $creditsTable->delete(array(
                    "user_id = ?" => $user->getIdentity()
                ));

                // Delete User Reviews
                $reviewsTable->delete(array(
                    "owner_id = ?" => $user->getIdentity()
                ));

                // Delete User Followers
                $followersTable->delete(array(
                    "user_id = ?" => $user->getIdentity()
                ));
                $followersTable->delete(array(
                    "resource_id = ?" => $user->getIdentity()
                ));
            }
          }
          if( !empty( $job_user ) ){
            // Delete User
            $usersTable->delete(array(
              "user_id = ?" => $job_user
            ));
          }
        } catch (Exception $e) {
          //Silent
        }

        return $userPaginator;
    }
}