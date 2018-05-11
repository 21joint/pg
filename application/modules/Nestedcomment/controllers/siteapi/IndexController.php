<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    IndexController.php 2015-09-17 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Nestedcomment_IndexController extends Siteapi_Controller_Action_Standard {
    /**
     * Handles HTTP request to like an activity feed item
     *
     * @return array
     */
    public function likeAction() {
        // Validate request methods
        $this->validateRequestMethod('POST');
        Engine_Api::_()->getApi('Core', 'siteapi')->setView();

        // Get Params
        $sendAppNotification = $this->getRequestParam('sendNotification', 1);
        $subject_id = $this->_getParam('subject_id');
        $subject_type = $this->_getParam('subject_type', 'activity_action');
        $subject = Engine_Api::_()->getItem($subject_type, $subject_id);

        $viewer = Engine_Api::_()->user()->getViewer();
        // Start transaction
        $db = Engine_Api::_()->getDbtable('likes', 'activity')->getAdapter();
        $db->beginTransaction();

        try {
            // Action
            // Check authorization
            if ($subject && !Engine_Api::_()->authorization()->isAllowed($subject, null, 'comment'))
                $this->respondWithError('unauthorized');
            //new reaction code start
            $reaction = $this->getRequestParam('reaction');
            $like = $reaction ? $subject->likes()->getLike($viewer) : null;
            $sendNotification = false;
            $shouldAddActivity = false;
            if (empty($like)) {
                $sendNotification = true;
                $like = $subject->likes()->addLike($viewer);
                $shouldAddActivity = $reaction && $reaction !== 'like';
            }

            if ($reaction) {
                $like->reaction = $reaction;
                $like->save();
            }

            // Stats
            Engine_Api::_()->getDbtable('statistics', 'core')->increment('core.likes');

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithValidationError('validation_fail', 'You have already liked this.');
        }

        $this->successResponseNoContent('no_content');
    }

    /**
     * Handles HTTP request to like an activity feed item
     *
     * @return array
     */
    public function sendLikeNotitficationAction() {
        // Validate request methods
        $this->validateRequestMethod('POST');
        Engine_Api::_()->getApi('Core', 'siteapi')->setView();
        $reaction = $this->getRequestParam('reaction');
        $sendAppNotification = $this->getRequestParam('sendNotification', 1);
        $subject_id = $this->_getParam('subject_id');
        $subject_type = $this->_getParam('subject_type', 'activity_action');
        $subject = Engine_Api::_()->getItem($subject_type, $subject_id);
        $viewer = Engine_Api::_()->user()->getViewer();

        // Start transaction
        $db = Engine_Api::_()->getDbtable('likes', 'activity')->getAdapter();
        $db->beginTransaction();

        try {
            $like = $reaction ? $subject->likes()->getLike($viewer) : null;
            $shouldAddActivity = false;
            $sendNotification = true;
            $shouldAddActivity = $reaction && $reaction !== 'like';
            // Add activity
            if ($shouldAddActivity) {
                $api = Engine_Api::_()->getDbtable('actions', 'advancedactivity');
                if ($subject->getTypeInfoCommentable() < 2) {
                    $shouldAddActivity = in_array($subject->getType(), array('status'));
                    $attachment = $subject;
                    $attachmentOwner = Engine_Api::_()->getItemByGuid($subject_type . "_" . $subject_id);
                } else {
                    $attachment = $subject->getCommentObject();
                    $attachmentOwner = $attachment->getOwner();
                }
                // Add activity for owner of activity (if user and not viewer)
                if ($shouldAddActivity && $attachmentOwner->getType() == 'user' && $attachmentOwner->getIdentity() != $viewer->getIdentity()) {
                    $params = array(
                        'type' => $attachment->getMediaType(),
                        'owner' => $attachmentOwner->getGuid(),
                    );
                    $likeAction = $api->addActivity($viewer, $attachment, 'react', '', '', $params);
                    if ($likeAction) {
                        $api->attachActivity($likeAction, $attachment);
                    }
                }
            }

            //reaction code end
            // Add notification for owner of activity (if user and not viewer)
            if ($subject_type == 'user' && $subject_id != $viewer->getIdentity()) {
                $actionOwner = Engine_Api::_()->getItemByGuid($subject_type . "_" . $subject_id);

                $notificationType = isset($like->reaction) && $like->reaction !== 'like' ? 'reacted' : 'liked';
                Engine_Api::_()->getApi('Siteapi_Core', 'activity')->addNotification($actionOwner, $viewer, $subject, $notificationType, array(
                    'label' => 'post'
                ));
            }
        } catch (Exception $e) {
            //Blank Exception 
        }
        $this->successResponseNoContent('no_content');
    }

    /**
     * Handles HTTP POST request to comment on an activity feed item
     *
     * @return array
     */
    public function commentAction() {
        // Validate request methods
        $this->validateRequestMethod('POST');
        Engine_Api::_()->getApi('Core', 'siteapi')->setView();

        $subject_id = $this->_getParam('subject_id');
        $subject_type = $this->_getParam('subject_type', 'activity_action');
        $subject = Engine_Api::_()->getItem($subject_type, $subject_id);

        //TRY ATTACHMENT GETTING STUFF
        $attachment = null;
        $body = '';
        $attachmentData = $this->getRequestParam('attachment_id');
        $attachmentType = $this->getRequestParam('attachment_type');
        // Start transaction
        $db = Engine_Api::_()->getDbtable('actions', 'activity')->getAdapter();
        $db->beginTransaction();
        $send_notification = $this->getRequestParam('send_notification', 1);
        $composerDatas = !empty($this->getRequestParam('composer')) ? Zend_Json::decode($this->getRequestParam('composer', null)) : array();

        try {
            $viewer = Engine_Api::_()->user()->getViewer();

            $postData = $_REQUEST;

            if (isset($postData['body']) && !empty($postData['body']))
                $body = $postData['body'];

            if (empty($body) && !isset($attachmentType) && empty($attachmentType))
                $this->respondWithError('validation_fail');

            // Check authorization
            if (!Engine_Api::_()->authorization()->isAllowed($subject, null, 'comment'))
                $this->respondWithError('unauthorized');

            //Reaction post work start 
            if (isset($attachmentType) && !empty($attachmentType)) {
                if ($attachmentType == 'sticker' && isset($attachmentData))
                    $attachment = Engine_Api::_()->getItemByGuid($attachmentData);
                if ($attachmentType == 'photo' && !empty($_FILES['photo'])) {
                    $table = Engine_Api::_()->getDbtable('albums', 'album');
                    $type = $this->getRequestParam('image_type', 'comment');
                    $album = $this->getSpecialAlbum($viewer, $type);
                    $photoTable = Engine_Api::_()->getDbtable('photos', 'album');
                    $photo = $photoTable->createRow();
                    $photo->owner_type = 'user';
                    $photo->owner_id = $viewer->getIdentity();
                    $photo->save();
// Set the photo
                    $photo = $this->_setPhoto($_FILES['photo'], $photo);
                    $photo->order = $photo->photo_id;
                    $photo->album_id = $album->album_id;
                    $photo->save();
                    if (!$album->photo_id) {
                        $album->photo_id = $photo->getIdentity();
                        $album->save();
                    }
                    $auth = Engine_Api::_()->authorization()->context;
                    $auth->setAllowed($photo, 'everyone', 'view', true);
                    $auth->setAllowed($photo, 'everyone', 'comment', true);
                    $attachment = $photo;
                }
            }

            // Reaction post work end
            // Add the comment
            $comment = $subject->comments()->addComment($viewer, $body);
            //User Tagging work start
            $tagsArray = array();
            if (isset($composerDatas['tag']))
                parse_str($composerDatas['tag'], $tagsArray);
            if (!empty($tagsArray)) {
                if ($subject) {
                    $data = array_merge((array) $subject, array('tags' => $tagsArray));
                    $comment->params = Zend_Json::encode($data);
                }
                $comment->save();
            }
            if (empty($comment))
                $this->respondWithError('unauthorized');

            if ($attachment) {
                if (isset($comment->attachment_type))
                    $comment->attachment_type = ( $attachment ? $attachment->getType() : '' );
                if (isset($comment->attachment_id))
                    $comment->attachment_id = ( $attachment ? $attachment->getIdentity() : 0 );
                $comment->save();
            }

            // Notifications
            $notifyApi = Engine_Api::_()->getApi('Siteapi_Core', 'activity');

            $canComment = Engine_Api::_()->authorization()->isAllowed($subject, null, 'comment');
            $canDelete = Engine_Api::_()->authorization()->isAllowed($subject, null, 'edit');

            Engine_Api::_()->getDbtable('statistics', 'core')->increment('core.comments');
            $commentInfo = array();
            $poster = Engine_Api::_()->getItem($comment->poster_type, $comment->poster_id);
            $commentInfo["subject_id"] = $subject_id;
            $commentInfo["comment_id"] = $comment->comment_id;

            // Add images
            $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($poster);
            $commentInfo = array_merge($commentInfo, $getContentImages);

            //to provide the same image names as in likes-comment response
            $getContentImages = array();
            $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($poster, false, 'author');
            $commentInfo = array_merge($commentInfo, $getContentImages);
            $commentInfo["author_title"] = $poster->getTitle();
            $commentInfo["user_id"] = $poster->getIdentity();
            $commentInfo["comment_body"] = $comment->body;
            $commentInfo["comment_date"] = $comment->creation_date;
            $commentInfo["params"] = isset($comment->params) ? $comment->params : "";

            if (Engine_Api::_()->getApi('Siteapi_Feed', 'advancedactivity')->isSitestickerPluginLive()) {
                if (isset($comment->attachment_type) && !empty($comment->attachment_type) && isset($comment->attachment_id) && !empty($comment->attachment_id)) {
                    $attachment = Engine_Api::_()->getItem($comment->attachment_type, $comment->attachment_id);
                    $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($attachment, false);
                    $commentInfo['attachment'] = $getContentImages;
                    $commentInfo['attachment_type'] = $comment->attachment_type;
                    $commentInfo['attachment_id'] = $comment->attachment_id;
                }
            }

            if (!empty($canDelete) || $poster->isSelf($viewer)) {
                $commentInfo["delete"] = array(
                    "name" => "delete",
                    "label" => $this->translate("Delete"),
                    "url" => "comment-delete",
                    'urlParams' => array(
                        "action_id" => $subject->getIdentity(),
                        "subject_type" => $subject->getType(),
                        "subject_id" => $subject->getIdentity(),
                        "comment_id" => $comment->comment_id
                    )
                );
            } else {
                $commentInfo["delete"] = null;
            }

            if (!empty($canComment)) {
                $isLiked = $comment->likes()->isLike($viewer);
                if (empty($isLiked)) {
                    $likeInfo["name"] = "like";
                    $likeInfo["label"] = $this->translate("Like");
                    $likeInfo["url"] = "like";
                    $likeInfo["urlParams"] = array(
                        "action_id" => $subject->getIdentity(),
                        "subject_type" => $subject->getType(),
                        "subject_id" => $subject->getIdentity(),
                        "comment_id" => $comment->getIdentity()
                    );
                    $likeInfo["isLike"] = 0;
                } else {
                    $likeInfo["name"] = "unlike";
                    $likeInfo["label"] = $this->translate("Unlike");
                    $likeInfo["url"] = "unlike";
                    $likeInfo["urlParams"] = array(
                        "action_id" => $subject->getIdentity(),
                        "subject_type" => $subject->getType(),
                        "subject_id" => $subject->getIdentity(),
                        "comment_id" => $comment->getIdentity()
                    );

                    $likeInfo["isLike"] = 1;
                }
                $commentInfo["like_count"] = $comment->likes()->getLikeCount();
                $commentInfo["like"] = $likeInfo;
            } else {
                $commentInfo["like"] = null;
            }

            $db->commit();
            $this->respondWithSuccess($commentInfo);
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithValidationError('internal_server_error', $e->getMessage());
        }
    }

    public function addCommentNotificationsAction() {
        // Validate request methods
        $this->validateRequestMethod('POST');

        $subject_id = $this->_getParam('subject_id');
        $subject_type = $this->_getParam('subject_type', 'activity_action');
        $subject = Engine_Api::_()->getItem($subject_type, $subject_id);

        // Start transaction
        $db = Engine_Api::_()->getDbtable('actions', 'activity')->getAdapter();
        $db->beginTransaction();

        try {
            $viewer = Engine_Api::_()->user()->getViewer();
            $actionOwner = Engine_Api::_()->getItemByGuid($subject_type . "_" . $subject_id);

            $postData = $_REQUEST;
            $comment_id = $this->getRequestParam('comment_id');

            $comment = $subject->comments()->getComment($comment_id);

            if (empty($comment))
                $this->respondWithError('validation_fail');

            // Check authorization
            if (!Engine_Api::_()->authorization()->isAllowed($subject, null, 'comment'))
                $this->respondWithError('unauthorized');


            // Notifications
            $notifyApi = Engine_Api::_()->getApi('Siteapi_Core', 'activity');


            // Add notification for owner of activity (if user and not viewer)
            if ($subject_type == 'user' && $subject_id != $viewer->getIdentity()) {
                $notifyApi->addNotification($actionOwner, $viewer, $subject, 'commented', array(
                    'label' => 'post'
                ));
            }
//             Add a notification for all users that commented or like except the viewer and poster
//             @todo we should probably limit this
            foreach ($subject->comments()->getAllCommentsUsers() as $notifyUser) {
                if ($notifyUser->getIdentity() != $viewer->getIdentity() && $notifyUser->getIdentity() != $actionOwner->getIdentity()) {
                    $notifyApi->addNotification($notifyUser, $viewer, $subject, 'commented_commented', array(
                        'label' => 'post'
                    ));
                }
            }
//
//            // Add a notification for all users that commented or like except the viewer and poster
//            // @todo we should probably limit this
            foreach ($subject->likes()->getAllLikesUsers() as $notifyUser) {
                if ($notifyUser->getIdentity() != $viewer->getIdentity() && $notifyUser->getIdentity() != $actionOwner->getIdentity()) {
                    $notifyApi->addNotification($notifyUser, $viewer, $subject, 'liked_commented', array(
                        'label' => 'post'
                    ));
                }
            }

            $db->commit();
            $this->successResponseNoContent('no_content');
        } catch (Exception $e) {
            $db->rollBack();
            $this->respondWithValidationError('internal_server_error', $e->getMessage());
        }
    }

    public function getSpecialAlbum(User_Model_User $user, $type) {
        if (!in_array($type, array('comment'))) {
            throw new Album_Model_Exception('Unknown special album type');
        }

        $table = Engine_Api::_()->getDbtable('albums', 'album');
        $select = $table->select()
                ->where('owner_type = ?', $user->getType())
                ->where('owner_id = ?', $user->getIdentity())
                ->where('type = ?', $type)
                ->order('album_id ASC')
                ->limit(1);

        $album = $table->fetchRow($select);

        // Create wall photos album if it doesn't exist yet
        if (null === $album) {
            $translate = Zend_Registry::get('Zend_Translate');
            $album = $table->createRow();
            $album->owner_type = 'user';
            $album->owner_id = $user->getIdentity();
            $album->title = $translate->_(ucfirst(str_replace("_", " ", $type)) . ' Photos');
            $album->type = $type;
            $album->search = 1;
            $album->save();

            // Authorizations
            $auth = Engine_Api::_()->authorization()->context;
            $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
            foreach ($roles as $i => $role) {
                $auth->setAllowed($album, $role, 'view', true);
                $auth->setAllowed($album, $role, 'comment', true);
            }
        }

        return $album;
    }

    /**
     * Set the uploaded photo from activity post.
     *
     * @return object
     */
    private function _setPhoto($photo, $subject) {
        if ($photo instanceof Zend_Form_Element_File) {
            $file = $photo->getFileName();
        } else if (is_array($photo) && !empty($photo['tmp_name'])) {
            $file = $photo['tmp_name'];
        } else if (is_string($photo) && file_exists($photo)) {
            $file = $photo;
        } else {
            throw new Group_Model_Exception('invalid argument passed to setPhoto');
        }
        $fileName = $photo['name'];
        $name = basename($file);
        $extension = ltrim(strrchr($fileName, '.'), '.');
        $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
        $base = rtrim(substr(basename($fileName), 0, strrpos(basename($fileName), '.')), '.');
        $params = array(
            'parent_type' => $subject->getType(),
            'parent_id' => $subject->getIdentity(),
            'user_id' => $subject->owner_id,
            'name' => $fileName,
        );
        $filesTable = Engine_Api::_()->getDbtable('files', 'storage');
// Resize image (main)
        $mainPath = $path . DIRECTORY_SEPARATOR . $base . '_m.' . $extension;
        $image = Engine_Image::factory();
        $image->open($file)
                ->resize(720, 720)
                ->write($mainPath)
                ->destroy();
// Resize image (normal)
        $normalPath = $path . DIRECTORY_SEPARATOR . $base . '_in.' . $extension;
        $image = Engine_Image::factory();
        $image->open($file)
                ->resize(140, 160)
                ->write($normalPath)
                ->destroy();
// Store
        try {
            $iMain = $filesTable->createFile($mainPath, $params);
            $iIconNormal = $filesTable->createFile($normalPath, $params);
            $iMain->bridge($iIconNormal, 'thumb.normal');
        } catch (Exception $e) {
            $this->respondWithValidationError('internal_server_error', $e->getMessage());
        }
// Remove temp files
        @unlink($mainPath);
        @unlink($normalPath);
// Update row
        $subject->modified_date = date('Y-m-d H:i:s');
        $subject->file_id = $iMain->file_id;
        $subject->save();
        return $subject;
    }

    /**
     * Handles HTTP request to get an activity feed item's comment information.
     *
     * @return array
     */
    public function likesCommentsAction() {
// Validate request methods
        $this->validateRequestMethod();
        Engine_Api::_()->getApi('Core', 'siteapi')->setView();

        $subject_id = $this->_getParam('subject_id');
        $subject_type = $this->_getParam('subject_type', 'activity_action');
        $action = Engine_Api::_()->getItem($subject_type, $subject_id);

        $page = $this->getRequestParam('page', null);
        $limit = $this->getRequestParam('limit', null);
        $viewer = Engine_Api::_()->user()->getViewer();
        $bodyParams = $likeUsersArray = $allComments = array();


        $getAllLikesUsers = $action->likes()->getAllLikesUsers();
        $likes = $action->likes()->getLikePaginator();
// Likes
        $isLike = $action->likes()->isLike($viewer);
        $viewAllLikes = $this->getRequestParam('viewAllLikes', $this->getRequestParam('view_all_likes', 0));
        if (!empty($viewAllLikes)) {
            foreach ($getAllLikesUsers as $user) {
                $tempUserArray = Engine_Api::_()->getApi('Core', 'siteapi')->validateUserArray($user);
                // Add images
                $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($user);
                $tempUserArray = array_merge($tempUserArray, $getContentImages);
                $likeUsersArray[] = $tempUserArray;
            }
            $bodyParams['viewAllLikesBy'] = $likeUsersArray;
        }
        $canComment = $action->authorization()->isAllowed($viewer, 'comment');
        $canDelete = $action->authorization()->isAllowed($viewer, 'edit');
// If has a page, display oldest to newest
        if (null !== $page) {
            $commentSelect = $action->comments()->getCommentSelect();
            $commentSelect->order('comment_id ' . $this->getRequestParam('order', 'ASC'));
            $comments = Zend_Paginator::factory($commentSelect);
            $comments->setCurrentPageNumber($page);
            $comments->setItemCountPerPage($limit);
        } else {
// If not has a page, show the
            $commentSelect = $action->comments()->getCommentSelect();
            $commentSelect->order('comment_id DESC');
            $comments = Zend_Paginator::factory($commentSelect);
            $comments->setCurrentPageNumber(1);
            $comments->setItemCountPerPage(4);
        }
// Hide if can't post and no comments
        if (!$canComment && !$canDelete && count($comments) <= 0 && count($likes) <= 0)
            $this->respondWithError('unauthorized');
        $getTotalCommentCount = $comments->getTotalItemCount();
        $viewAllComments = $this->getRequestParam('viewAllComments', $this->getRequestParam('view_all_comments', 0));
        if (!empty($viewAllComments)) {
// Iterate over the comments backwards (or forwards!)
            $comments = $comments->getIterator();
            if ($page) {
                $i = 0;
                $l = count($comments) - 1;
                $d = 1;
                $e = $l + 1;
            } else {
                $i = count($comments) - 1;
                $l = count($comments);
                $d = -1;
                $e = -1;
            }
            for (; $i != $e; $i += $d) {
                $comment = $comments[$i];
                $poster = Engine_Api::_()->getItem($comment->poster_type, $comment->poster_id);
                $commentInfo = array();
                $commentInfo["subject_id"] = $subject_id;
                $commentInfo["comment_id"] = $comment->comment_id;
                // Add images
                $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($poster, false, 'author');
                $commentInfo = array_merge($commentInfo, $getContentImages);
                $commentInfo["author_title"] = $poster->getTitle();
                $commentInfo["user_id"] = $poster->getIdentity();
                $commentInfo["comment_body"] = $comment->body;
                $commentInfo["comment_date"] = $comment->creation_date;
                $commentInfo["params"] = isset($comment->params) ? $comment->params : "";
                if (Engine_Api::_()->getApi('Siteapi_Feed', 'advancedactivity')->isSitestickerPluginLive()) {
                    if (isset($comment->attachment_type) && !empty($comment->attachment_type) && isset($comment->attachment_id) && !empty($comment->attachment_id) && ($comment->attachment_type == 'sitereaction_sticker' || $comment->attachment_type == 'album_photo')) {
                        $attachment = Engine_Api::_()->getItem($comment->attachment_type, $comment->attachment_id);
                        $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($attachment, false);
                        $commentInfo['attachment'] = $getContentImages;
                        $commentInfo['attachment_type'] = $comment->attachment_type;
                        $commentInfo['attachment_id'] = $comment->attachment_id;
                    }
                }
                if (!empty($canDelete) || $poster->isSelf($viewer)) {
                    $commentInfo["delete"] = array(
                        "name" => "delete",
                        "label" => $this->translate('Delete'),
                        "url" => "comment-delete",
                        'urlParams' => array(
                            "action_id" => $subject_id,
                            "subject_type" => $action->getType(),
                            "subject_id" => $action->getIdentity(),
                            "comment_id" => $comment->comment_id
                        )
                    );
                } else {
                    $commentInfo["delete"] = null;
                }
                if (!empty($canComment)) {
                    $isLiked = $comment->likes()->isLike($viewer);
                    if (empty($isLiked)) {
                        $likeInfo["name"] = "like";
                        $likeInfo["label"] = $this->translate('Like');
                        $likeInfo["url"] = "like";
                        $likeInfo['urlParams'] = array(
                            "action_id" => $subject_id,
                            "subject_type" => $action->getType(),
                            "subject_id" => $action->getIdentity(),
                            "comment_id" => $comment->getIdentity()
                        );
                        $likeInfo["isLike"] = 0;
                    } else {
                        $likeInfo["name"] = "unlike";
                        $likeInfo["label"] = $this->translate('Unlike');
                        $likeInfo["url"] = "unlike";
                        $likeInfo['urlParams'] = array(
                            "action_id" => $subject_id,
                            "subject_type" => $action->getType(),
                            "subject_id" => $action->getIdentity(),
                            "comment_id" => $comment->getIdentity()
                        );
                        $likeInfo["isLike"] = 1;
                    }
                    $commentInfo["like_count"] = $comment->likes()->getLikeCount();
                    $commentInfo["like"] = $likeInfo;
                } else {
                    $commentInfo["like"] = null;
                }
                $allComments[] = $commentInfo;
            }
            $bodyParams['viewAllComments'] = $allComments;
        }
// FOLLOWING ARE THE GENRAL INFORMATION OF THE PLUGIN, WHICH WILL RETURN IN EVERY CALLING.
        $bodyParams['isLike'] = !empty($isLike) ? 1 : 0;
        $bodyParams['canComment'] = $canComment;
        $bodyParams['canDelete'] = $canDelete;
        $bodyParams['getTotalComments'] = $getTotalCommentCount;
        $bodyParams['getTotalLikes'] = $likes->getTotalItemCount();
        $this->respondWithSuccess($bodyParams);
    }

}

?>