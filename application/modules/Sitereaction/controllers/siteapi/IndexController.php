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
class Sitereaction_IndexController extends Siteapi_Controller_Action_Standard {

    public function reactionsAction() {
        // Collect params
        $subject_id = $this->_getParam('subject_id');
        $subject_type = $this->_getParam('subject_type', 'activity_action');
        $action_id = $this->_getParam('action_id');
        $reaction = $this->_getParam('reaction');
        $page = $this->_getParam('page');
        $limit = $this->_getParam('limit');

        try {
            if ($subject_type == 'activity_action') {
                $subject = Engine_Api::_()->getDbTable('actions', 'advancedactivity')->getActionById($action_id);
            } else {
                $subject = Engine_Api::_()->getItem($subject_type, $subject_id);
            }

            if (!isset($subject) || empty($subject))
                $this->respondWithError('no_record');

            $likesSelect = $subject->likes()->getLikeSelect();
            if ($reaction) {
                $likesSelect->where('reaction = (?)', $reaction);
            }

            $likes = $subject->likes()->getLikeCount();
            $paginator = Zend_Paginator::factory($likesSelect);
            $paginator->setItemCountPerPage($limit);
            $paginator->count();
            $pages = $paginator->getPageRange();
            $paginator->setCurrentPageNumber($page);
            foreach ($paginator as $user) {
                if (!empty($user['poster_type']) && !empty($user['poster_id']) && $user['poster_type'] == 'user') {
                    $userObj = Engine_Api::_()->getItem($user['poster_type'], $user['poster_id']);
                    if (isset($userObj) && !empty($userObj)) {
                        $tempUserArray = $userObj->toArray();

                        if (isset($tempUserArray['creation_ip']) && !empty($tempUserArray['creation_ip']))
                            unset($tempUserArray['creation_ip']);

                        if (isset($tempUserArray['lastlogin_ip']) && !empty($tempUserArray['lastlogin_ip']))
                            unset($tempUserArray['lastlogin_ip']);

                        if (isset($tempUserArray['password']) && !empty($tempUserArray['password']))
                            unset($tempUserArray['password']);

                        if (isset($tempUserArray['salt']) && !empty($tempUserArray['salt']))
                            unset($tempUserArray['salt']);

                        $tempUserArray['reaction'] = $user->reaction;
                         $tempUserArray['friendship_type'] = Engine_Api::_()->getApi('Siteapi_Core', 'user')->getFriendshipType($userObj);
                        $iconImages = Engine_Api::_()->getApi('Siteapi_Core', 'sitereaction')->getIcons($user->reaction);
                        $tempUserArray = array_merge($tempUserArray, $iconImages);
                        $verification = Engine_Api::_()->getApi('settings', 'core')->getSetting('user.friends.verification');
                $tempUserArray['isVerified'] = $verification;

                        // Add images
                        $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($userObj);
                        $tempUserArray = array_merge($tempUserArray, $getContentImages);
                        $likeUsersArray[] = $tempUserArray;
                    }
                }
            }
            $response['viewAllLikesBy'] = $likeUsersArray;

            $popularity = Engine_Api::_()->getApi('core', 'sitereaction')->getLikesReactionPopularity($subject);

            $iconImages = Engine_Api::_()->getApi('Siteapi_Core', 'sitereaction')->getIcons('like');
            $reactionIconsData[] = array(
                'reaction' => 'all',
                'reaction_count' => $likes,
                'reaction_icon' => $iconImages
            );
            foreach ($popularity as $reaction) {
                $iconImages = Engine_Api::_()->getApi('Siteapi_Core', 'sitereaction')->getIcons($reaction['reaction']);
                $reactionIconsData[] = array(
                    'reaction' => $reaction['reaction'],
                    'reaction_count' => $reaction['reaction_count'],
                    'reaction_icon' => $iconImages,
                );
            }

            if (isset($reactionIconsData) && !empty($reactionIconsData))
                $response['reactionTabs'] = $reactionIconsData;
            $this->respondWithSuccess($response, true);
        } catch (Exception $ex) {
            $this->respondWithValidationError('internal_server_error', $ex->getMessage());
        }
    }

    public function stickersAction() {
        $viewer = Engine_Api::_()->user()->getViewer();
        //Get sticker search keyword
        $stickerSearch = $this->_getParam('sticker_search');
        // Get stickers of a collection
        $collection_id = $this->_getParam('collectionId', $this->_getParam('collection_id'));

        $stickersSearch = array();
        $stickersCollection = array();
        try {
            $table = Engine_Api::_()->getDbtable('collections', 'sitereaction');
            $colleaction_ids = Engine_Api::_()->getDbtable('userscollections', 'sitereaction')->getCollectinIds($viewer->getIdentity());

            $colleaction_ids = array_merge($colleaction_ids, $table->getCollectinIds($viewer->getIdentity()));
            $collections = $table->getCollectinos($colleaction_ids);
            $searchList = Engine_Api::_()->getDbtable('stickersearch', 'sitereaction')->getList();

            // Set the array of stickers search List
            foreach ($searchList as $search) {
                $tempResponse = $search->toArray();
                $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($search);
                $tempResponse = array_merge($tempResponse, $getContentImages);
                $searchResponse[] = $tempResponse;
            }

            // List of collections for a particular user
            foreach ($collections as $collection) {
                $id = $collection->getIdentity();

                //To List of stickers of a particular collection only
                if (isset($collection_id) && !empty($collection_id) && $collection_id != $id)
                    continue;

                $tempResponse = $collection->toArray();
                $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($collection);
                $tempResponse = array_merge($tempResponse, $getContentImages);
                $stickersCollection = array();

                // Get in stickers loop only if sticker searching is being done or list of stickers of a collection as required. 
                if ((isset($stickerSearch) && !empty($stickerSearch)) || (isset($collection_id) && !empty($collection_id))) {
                    foreach ($collection->getStickers() as $sticker) {
                        if (isset($stickerSearch) && !empty($stickerSearch)) {
                            $keywords = str_replace(',', '|', $stickerSearch);
                            $stickerSearch = '(' . $keywords . ')';
                            if (preg_match("/$stickerSearch/i", $sticker->getTitle())) {
                                $tempSticker = $sticker->toArray();
                                $tempSticker['guid'] = $sticker->getGuid();
                                $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($sticker);
                                $tempSticker = array_merge($tempSticker, $getContentImages);
                                $stickersSearch[] = $tempSticker;
                            }
                        } else if (isset($collection_id) && !empty($collection_id)) {
                            $tempSticker = $sticker->toArray();
                            $tempSticker['guid'] = $sticker->getGuid();
                            $getContentImages = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($sticker);
                            $tempSticker = array_merge($tempSticker, $getContentImages);
                            $stickersCollection[] = $tempSticker;
                        }
                    }
                }

                // Set collection in response array
                if (isset($tempResponse) && !empty($tempResponse))
                    $collectionResponse[] = $tempResponse;
            }

            // Set stickers after searching as response
            if (isset($stickerSearch) && !empty($stickerSearch)) {
                $response['totalItemCount'] = count($stickersSearch);
                $response['stickers'] = $stickersSearch;
            }

            // Set stickers of collection as response 
            else if (isset($collection_id) && !empty($collection_id)) {
                $response['collection'] = $tempResponse;
                $response['totalItemCount'] = count($stickersCollection);
                $response['stickers'] = $stickersCollection;
            }

            // Set search list & collection tabs as response
            else {
                //To show store icon or not
                if (Engine_Api::_()->getApi('Siteapi_Core', 'sitereaction')->hasStoreCollections()) {
                    $response['isStoreEnabled'] = 1;
                } else {
                    $response['isStoreEnabled'] = 0;
                }
                if (isset($collectionResponse) && !empty($collectionResponse)) {
                    $response['collectionList'] = $collectionResponse;
                    $response['isEmojiEnabled'] = 1;
                }
                if (isset($searchResponse) && !empty($searchResponse))
                    $response['searchList'] = $searchResponse;
            }

            $this->respondWithSuccess($response, true);
        } catch (Exception $ex) {
            $this->respondWithValidationError('internal_server_error', $ex->getMessage());
        }
    }

    public function contentReactionAction() {
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();

        // Collect params
        $subject_id = $this->_getParam('subject_id');
        $getReaction = $this->_getParam('getReaction', 0);
        $subject_type = $this->_getParam('subject_type', 'activity_action');

        $subject = Engine_Api::_()->getItem($subject_type, $subject_id);

        if (!isset($subject) || empty($subject))
            $this->respondWithError('no_record');


        $getBodyResponse['reactionsEnabled'] = 0;
        $getBodyResponse['stickersEnabled'] = 0;
        $getBodyResponse['emojiEnabled'] = 0;
        try {
            if (Engine_Api::_()->getApi('Siteapi_Feed', 'advancedactivity')->isSitereactionPluginLive() && $getReaction) {
                if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitereaction') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereaction.reaction.active', 1)) {
                    $getBodyResponse['reactionsEnabled'] = 1;
                    $getBodyResponse['reactions'] = Engine_Api::_()->getApi('Siteapi_Core', 'sitereaction')->getAllReactionIcons();
                }
            }
            if (Engine_Api::_()->getApi('Siteapi_Feed', 'advancedactivity')->isSitestickerPluginLive()) {
                if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('nestedcomment') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereaction.collection.active', 1)) {
                    $getBodyResponse['stickersEnabled'] = 1;
                    $getBodyResponse['emojiEnabled'] = 1;
                }
            }
            $response['reactions'] = $getBodyResponse;
            $response['totalLikes'] = $subject->likes()->getLikeCount();
            $response['isLike'] = $subject->likes()->isLike($viewer);
            $response['totalComments'] = $subject->comments()->getCommentCount();
            $response['canComment'] = $subject->authorization()->isAllowed($viewer, 'comment');
        } catch (Exception $ex) {
            //Blank Exception
        }

        try {

            //Sitereaction Plugin work start here
            if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitereaction') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sitereaction.reaction.active', 1)) {
                $popularity = Engine_Api::_()->getApi('core', 'sitereaction')->getLikesReactionPopularity($subject);
                $feedReactionIcons = Engine_Api::_()->getApi('Siteapi_Core', 'sitereaction')->getLikesReactionIcons($popularity, 1);
                $response['feed_reactions'] = $feedReactionIcons;

                if (isset($viewer_id) && !empty($viewer_id)) {
                    $myReaction = $subject->likes()->getLike($viewer);
                    if (isset($myReaction) && !empty($myReaction) && isset($myReaction->reaction) && !empty($myReaction->reaction)) {
                        $myReactionIcon = Engine_Api::_()->getApi('Siteapi_Core', 'sitereaction')->getIcons($myReaction->reaction, 1);
                        $response['my_feed_reaction'] = $myReactionIcon;
                    }
                }
            }
            $this->respondWithSuccess($response, true);
        } catch (Exception $ex) {
            $this->respondWithValidationError('internal_server_error', $ex->getMessage());
        }
        //Sitereaction Plugin work end here
    }

}
