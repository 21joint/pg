<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagealbum
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitepagereview_Api_Siteapi_Core extends Core_Api_Abstract {

    /**
     * Returns create a review form 
     *
     * @param array $widgetSettingsReviews
     * @return array
     */
    public function getReviewCreateForm($widgetSettingsReviews) {
        // Get viewer info
        $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $getItemPage = $widgetSettingsReviews['item'];
        $sitepagereview_proscons = $widgetSettingsReviews['settingsReview']['sitepagereview_proscons'];
        $sitepagereview_limit_proscons = $widgetSettingsReviews['settingsReview']['sitepagereview_limit_proscons'];
        $sitepagereview_recommend = $widgetSettingsReviews['settingsReview']['sitepagereview_recommend'];

        if ($sitepagereview_proscons) {
            if ($sitepagereview_limit_proscons) {
                $createReview[] = array(
                    'type' => 'Textarea',
                    'name' => 'pros',
                    'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Pros'),
                    'description' => Zend_Registry::get('Zend_Translate')->_("What do you like about this Page?"),
                    'hasValidator' => 'true'                
                );
            } else {
                $createReview[] = array(
                    'type' => 'Textarea',
                    'name' => 'pros',
                    'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Pros'),
                    'description' => Zend_Registry::get('Zend_Translate')->_("What do you like about this Page?"),
                    'hasValidator' => 'true',
                );
            }


            if ($sitepagereview_limit_proscons) {
                $createReview[] = array(
                    'type' => 'Textarea',
                    'name' => 'cons',
                    'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Cons'),
                    'description' => Zend_Registry::get('Zend_Translate')->_("What do you dislike about this Page?"),
                    'hasValidator' => 'true',
                );
            } else {
                $createReview[] = array(
                    'type' => 'Textarea',
                    'name' => 'cons',
                    'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Cons'),
                    'description' => Zend_Registry::get('Zend_Translate')->_("What do you dislike about this Page?"),
                    'hasValidator' => 'true',
                );
            }
        }

        $createReview[] = array(
            'type' => 'Textarea',
            'name' => 'title',
            'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('One-line summary'),
        );

        $createReview[] = array(
            'type' => 'Textarea',
            'name' => 'body',
            'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Summary'),
        );

        if ($sitepagereview_recommend) {
            $createReview[] = array(
                'type' => 'Radio',
                'name' => 'recommend',
                'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Recommended'),
                'description' => sprintf(Zend_Registry::get('Zend_Translate')->_("Would you recommend this Page to a friend?")),
                'multiOptions' => array(
                    1 => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Yes'),
                    0 => Engine_Api::_()->getApi('Core', 'siteapi')->translate('No')
                ),
            );
        }

        $createReview[] = array(
            'type' => 'Submit',
            'name' => 'submit',
            'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Submit'),
        );
        return $createReview;
    }


    /*
    * Returns review update form 
    *
    * @return array
    */
    public function getReviewUpdateForm() {

        $updateReview[] = array(
            'type' => 'Textarea',
            'name' => 'body',
            'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Summary'),
        );

        $updateReview[] = array(
            'type' => 'Submit',
            'name' => 'submit',
            'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Add your Opinion'),
        );
        return $updateReview;
    }

    /*
    * Returns comments on review form 
    *
    * @return array
    */
    public function getcommentForm($type, $id) {
        $commentform = array();
        $commentform[] = array(
            'type' => "text",
            'name' => 'body',
            'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Comment'),
        );
        return $commentform;
    }

    /*
    *   Adds photo
    *
    *
    */
    public function setPhoto($photo, $subject, $needToUplode = false, $params = array()) {
        try {

            if ($photo instanceof Zend_Form_Element_File) {
                $file = $photo->getFileName();
            } else if (is_array($photo) && !empty($photo['tmp_name'])) {
                $file = $photo['tmp_name'];
            } else if (is_string($photo) && file_exists($photo)) {
                $file = $photo;
            } else {
                throw new Group_Model_Exception('invalid argument passed to setPhoto');
            }
        } catch (Exception $e) {
            
        }

        $imageName = $photo['name'];
        $name = basename($file);
        $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';

        $params = array(
            'parent_type' => 'siteevent_event',
            'parent_id' => $subject->getIdentity()
        );

        // Save
        $storage = Engine_Api::_()->storage();

        // Resize image (main)
        $image = Engine_Image::factory();
        $image->open($file)
                ->resize(720, 720)
                ->write($path . '/m_' . $imageName)
                ->destroy();

        // Resize image (profile)
        $image = Engine_Image::factory();
        $image->open($file)
                ->resize(200, 400)
                ->write($path . '/p_' . $imageName)
                ->destroy();

        // Resize image (normal)
        $image = Engine_Image::factory();
        $image->open($file)
                ->resize(140, 160)
                ->write($path . '/in_' . $imageName)
                ->destroy();

        // Resize image (icon)
        $image = Engine_Image::factory();
        $image->open($file);

        $size = min($image->height, $image->width);
        $x = ($image->width - $size) / 2;
        $y = ($image->height - $size) / 2;

        $image->resample($x, $y, $size, $size, 48, 48)
                ->write($path . '/is_' . $imageName)
                ->destroy();

        // Store
        $iMain = $storage->create($path . '/m_' . $imageName, $params);
        $iProfile = $storage->create($path . '/p_' . $imageName, $params);
        $iIconNormal = $storage->create($path . '/in_' . $imageName, $params);
        $iSquare = $storage->create($path . '/is_' . $imageName, $params);

        $iMain->bridge($iProfile, 'thumb.profile');
        $iMain->bridge($iIconNormal, 'thumb.normal');
        $iMain->bridge($iSquare, 'thumb.icon');

        // Remove temp files
        @unlink($path . '/p_' . $imageName);
        @unlink($path . '/m_' . $imageName);
        @unlink($path . '/in_' . $imageName);
        @unlink($path . '/is_' . $imageName);

        // Update row
        if (empty($needToUplode)) {
            $subject->modified_date = date('Y-m-d H:i:s');
            $subject->save();
        }

        // Add to album
        $viewer = Engine_Api::_()->user()->getViewer();
        $photoTable = Engine_Api::_()->getItemTable('sitepage_photo');
        if (isset($params['album_id']) && !empty($params['album_id'])) {
            $album = Engine_Api::_()->getItem('sitepage_album', $params['album_id']);
            if (!$album->toArray())
            {
                $album = $subject->getSingletonAlbum();
                $album->owner_id = $viewer->getIdentity();
                $album->save();
            }
        } else
        {
            $album = $subject->getSingletonAlbum();
            $album->owner_id = $viewer->getIdentity();
            $album->save();
        }
        $photoItem = $photoTable->createRow();
        $photoItem->setFromArray(array(
            'event_id' => $subject->getIdentity(),
            'album_id' => $album->getIdentity(),
            'user_id' => $viewer->getIdentity(),
            'file_id' => $iMain->getIdentity(),
            'collection_id' => $album->getIdentity()
        ));
        $photoItem->save();

        return $subject;
    }

    /**
     * Review search form
     * 
     * @return array
     */
    public function getReviewSearchForm() {

        $order = 1;
        $reviewForm = array();
        $reviewForm[] = array(
            'type' => 'Text',
            'name' => 'search',
            'label' => $this->translate('Search'),
        );
        
        $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        
        if ($viewer_id) {
            $reviewForm[] = array(
                'type' => 'Select',
                'name' => 'show',
                'label' => $this->translate('Show'),
                'multiOptions' => array('' => $this->translate("Everyone's Reviews"), 
                                        'friends_reviews' => $this->translate("My Friends' Reviews"), 
                                        'self_reviews' => $this->translate("My Reviews"), 
                                        'featured' => $this->translate("Featured Reviews")),
            );
        }

        $reviewForm[] = array(
            'type' => 'Select',
            'name' => 'type',
            'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Reviews Written By'),
            'multiOptions' => array('' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Everyone'), 'editor' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Editors'), 'user' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Users')),
        );


        $reviewForm[] = array(
            'type' => 'Select',
            'name' => 'order',
            'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Browse By'),
            'multiOptions' => array(
                'recent' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Most Recent'),
                'rating_highest' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Highest Rating'),
                'rating_lowest' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Lowest Rating'),
                'helpfull_most' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Most Helpful'),
                'replay_most' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Most Reply'),
                'view_most' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Most Viewed')
            ),
        );
        $reviewForm[] = array(
            'type' => 'Select',
            'name' => 'rating',
            'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Ratings'),
            'multiOptions' => array(
                '' => '',
                '5' => sprintf($this->translate('%1s Star'), 5),
                '4' => sprintf($this->translate('%1s Star'), 4),
                '3' => sprintf($this->translate('%1s Star'), 3),
                '2' => sprintf($this->translate('%1s Star'), 2),
                '1' => sprintf($this->translate('%1s Star'), 1),
            ),
        );

        $reviewForm[] = array(
            'type' => 'Checkbox',
            'name' => 'recommend',
            'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Only Recommended Reviews'),
        );

//        $reviewForm[] = array(
//            'type' => 'Submit',
//            'name' => 'done',
//            'label' => Engine_Api::_()->getApi('Core', 'siteapi')->translate('Search'),
//        );

        return $reviewForm;
    }

    public function sendNotificationToFollowers($object, $actionObject, $notificationType, $count = null) {
    $viewer = Engine_Api::_()->user()->getViewer();
    $page_id = $object->page_id;
    $sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);
    //ITEM TITLE AND TILTE WITH LINK.
    $item_title = isset($object->title) ? $object->title : $object->getTitle();
    $item_title_url = $object->getHref();
    $item_title_baseurl = 'http://' . $_SERVER['HTTP_HOST'] . $item_title_url;
    $item_title_link = "<a href='$item_title_baseurl'>" . $item_title . "</a>";
    $followersIds = Engine_Api::_()->getDbTable('follows', 'seaocore')->getFollowers('sitepage_page', $page_id, $viewer->getIdentity());
    $notificationsTable = Engine_Api::_()->getDbtable('notifications', 'activity');
    $notidicationSettings = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.feed.type', 0);
    foreach ($followersIds as $value) {
        
      if (!empty($notidicationSettings)) {
        $notificationsTable->delete(array('type =?' => "$notificationType", 'object_type = ?' => $object->getType(), 'object_id = ?' => $object->getIdentity(), 'subject_id = ?' => $sitepage->getIdentity(), 'subject_type = ?' => $sitepage->getType(), 'user_id =?' => $value['poster_id']));
      }
      else {
         $notificationsTable->delete(array('type =?' => "$notificationType", 'object_type = ?' => $object->getType(), 'object_id = ?' => $object->getIdentity(), 'subject_id = ?' => $viewer->getIdentity(), 'subject_type = ?' => $viewer->getType(), 'user_id =?' => $value['poster_id']));
      }
    
      $user_subject = Engine_Api::_()->user()->getUser($value['poster_id']);
      $row = $notificationsTable->createRow();
      $row->user_id = $user_subject->getIdentity();
      $row->subject_type = $viewer->getType();
      $row->subject_id = $viewer->getIdentity();
      $row->type = $notificationType;
      $row->object_type = $object->getType();
      $row->object_id = $object->getIdentity();
      $row->params = '{"eventname":"' . $item_title_link . '"}';
      $row->date = date('Y-m-d H:i:s');
      $row->save();
    }
  }

    public function sendNotificationEmail($object, $actionObject, $notificationType = null, $emailType = null, $params = null, $count = null) {

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $sitepagememberEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitepagemember');

    $notificationsTable = Engine_Api::_()->getDbtable('notifications', 'activity');
    $notificationSettings = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.feed.type', 0);

    $manageAdminTable = Engine_Api::_()->getDbtable('manageadmins', 'sitepage');

    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();

    $page_id = $object->page_id;

    $subject = Engine_Api::_()->getItem('sitepage_page', $page_id);
    $owner = $subject->getOwner();

    //previous notification is delete.
    $notificationsTable->delete(array('type =?' => "$notificationType", 'object_type = ?' => "sitepage_page", 'object_id = ?' => $page_id, 'subject_id = ?' => $viewer_id));

    //GET PAGE TITLE AND PAGE TITLE WITH LINK.
    $pagetitle = $subject->title;
    //$page_url = Engine_Api::_()->sitepage()->getPageUrl($subject->page_id);
    //$page_baseurl = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('page_url' => $page_url), 'sitepage_entry_view', true);
    //$page_title_link = '<a href="' . $page_baseurl . '"  >' . $pagetitle . ' </a>';
    //ITEM TITLE AND TILTE WITH LINK.
    if ($notificationType == 'sitepagedocument_create') {
        $item_title = $object->sitepagedocument_title;
    }
    else {
        $item_title = $object->title;
    }
    $item_title_url = $object->getHref();
    $item_title_baseurl = 'http://' . $_SERVER['HTTP_HOST'] . $item_title_url;
    $item_title_link = "<a href='$item_title_baseurl' style='text-decoration:none;' >" . $item_title . " </a>";

    //POSTER TITLE AND PHOTO WITH LINK
    $poster_title = $viewer->getTitle();
    $poster_url = $viewer->getHref();
    $poster_baseurl = 'http://' . $_SERVER['HTTP_HOST'] . $poster_url;
    $poster_title_link = "<a href='$poster_baseurl' style='font-weight:bold;text-decoration:none;' >" . $poster_title . " </a>";
    $photos = Engine_Api::_()->getApi('Core', 'siteapi')->getContentImage($viewer);
    $photo = $photos['image_icon'];
    $image = "<img src='$photo' />";
    $posterphoto_link = "<tr><td colspan='2' style='height:20px;'></td></tr><tr></tr><tr><td valign='top' style='font-size:11px;font-family:LucidaGrande,tahoma,verdana,arial,sans-serif;padding-right:15px;text-align:left'><a href='$poster_baseurl'  >" . $image . " </a></td><td valign='top' style='font-size:11px;font-family:LucidaGrande,tahoma,verdana,arial,sans-serif;width:100%;text-align:left'><table cellspacing='0' cellpadding='0' style='border-collapse:collapse;width:100%'><tr><td style='font-size:11px;font-family:LucidaGrande,tahoma,verdana,arial,sans-serif'><span style='color:#333333;'>";

    //MEASSGE WITH LINK.
    if (isset($actionObject)) {
      $post_baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . $actionObject->getHref();
    }
    $created = $post = ' ';
    if ($notificationType == 'sitepagealbum_create') {
      $post = $poster_title . ' created a new album in page: ' . $pagetitle;
      $created = ' created the album ';
    } elseif ($notificationType == 'sitepagedocument_create') {
      $post = $poster_title . ' created a new document in page: ' . $pagetitle;
      $created = ' created the document ';
    } elseif ($notificationType == 'sitepageevent_create') {
      $post = $poster_title . ' created a new event in page: ' . $pagetitle;
      $created = ' created the event ';
    } elseif ($notificationType == 'sitepagemusic_create') {
      $post = $poster_title . ' created a new playlist in page: ' . $pagetitle;
      $created = ' created the music ';
    } elseif ($notificationType == 'sitepagenote_create') {
      $post = $poster_title . ' created a new note in page: ' . $pagetitle;
      $created = ' created the note ';
    } elseif ($notificationType == 'sitepageoffer_create') {
      $post = $poster_title . ' created a new offer in page: ' . $pagetitle;
      $created = ' created the offer ';
    } elseif ($notificationType == 'sitepagepoll_create') {
      $post = $poster_title . ' created a new poll in page: ' . $pagetitle;
      $created = ' created the poll ';
    } elseif ($notificationType == 'sitepagevideo_create') {
      $post = $poster_title . ' posted a new video in page: ' . $pagetitle;
      $created = ' created the video ';
    } elseif ($notificationType == 'sitepagediscussion_create') {
      $post = $poster_title . ' created a new discussion in page: ' . $pagetitle;
      $created = ' created the discussion ';
    }
    if (!empty($post_baseUrl)) {
      if ($params == 'Activity Comment' || $params == 'Activity Reply') {
        $post_link = "<a href='$post_baseUrl'  >" . 'post' . "</a>";
        $post_linkformail = "<table cellspacing='0' cellpadding='0' border='0' style='border-collapse:collapse;' width='90%'><tr><td style='font-size:11px;font-family:LucidaGrande,tahoma,verdana,arial,sans-serif;padding:20px;background-color:#fff;border-left:none;border-right:none;border-top:none;border-bottom:none;'><table cellspacing='0' cellpadding='0' style='border-collapse:collapse;' width='100%'><tr><td colspan='2' style='font-size:11px;font-family:LucidaGrande,tahoma,verdana,arial,sans-serif;border-bottom:1px solid #dddddd;padding-bottom:5px;'><a style='font-weight:bold;margin-bottom:10px;text-decoration:none;' href='$post_baseUrl'>" . 'post' . "</a></td></tr><tr><td valign='top' style='padding:10px 15px 10px 10px;'><a href='$poster_baseurl'  >" . $image . " </a></td><td valign='top' style='padding-top:10px;font-size:11px;font-family:LucidaGrande,tahoma,verdana,arial,sans-serif;width:100%;text-align:left;'><table cellspacing='0' cellpadding='0' style='border-collapse:collapse;width:100%;'><tr><td style='font-size:11px;font-family:LucidaGrande,tahoma,verdana,arial,sans-serif;text-decoration:none;text-decoration:none;'>" . $poster_title_link . 'post' . $item_title_link . '.' . "</td></tr></table></td></tr></table></td></tr></table>";
      } else {
        $post_link = "<a href='$post_baseUrl'  >" . $post . "</a>";
        $post_linkformail = "<table cellspacing='0' cellpadding='0' border='0' style='border-collapse:collapse;' width='90%'><tr><td style='font-size:11px;font-family:LucidaGrande,tahoma,verdana,arial,sans-serif;padding:20px;background-color:#fff;border-left:none;border-right:none;border-top:none;border-bottom:none;'><table cellspacing='0' cellpadding='0' style='border-collapse:collapse;' width='100%'><tr><td colspan='2' style='font-size:11px;font-family:LucidaGrande,tahoma,verdana,arial,sans-serif;border-bottom:1px solid #dddddd;padding-bottom:5px;'><a style='font-weight:bold;margin-bottom:10px;text-decoration:none;' href='$post_baseUrl'>" . $post . "</a></td></tr><tr><td valign='top' style='padding:10px 15px 10px 10px;'><a href='$poster_baseurl'  >" . $image . " </a></td><td valign='top' style='padding-top:10px;font-size:11px;font-family:LucidaGrande,tahoma,verdana,arial,sans-serif;width:100%;text-align:left;'><table cellspacing='0' cellpadding='0' style='border-collapse:collapse;width:100%;'><tr><td style='font-size:11px;font-family:LucidaGrande,tahoma,verdana,arial,sans-serif;text-decoration:none;'>" . $poster_title_link . $created . $item_title_link . '.' . "</td></tr></table></td></tr></table></td></tr></table>";
      }
    }

    //FETCH DATA
    if (empty($sitepagememberEnabled)) {
      $manageAdminsIds = $manageAdminTable->getManageAdmin($page_id, $viewer_id);
      foreach ($manageAdminsIds as $value) {
        $user_subject = Engine_Api::_()->user()->getUser($value['user_id']);
        $action_notification = unserialize($value['action_notification']);
        if (!empty($value['notification']) && in_array('created', $action_notification)) {
          $row = $notificationsTable->createRow();
          $row->user_id = $user_subject->getIdentity();
          if ($notificationSettings == 1) {
            $row->subject_type = $subject->getType();
            $row->subject_id = $subject->getIdentity();
          } else {
            $row->subject_type = $viewer->getType();
            $row->subject_id = $viewer->getIdentity();
          }
          $row->type = "$notificationType";
          $row->object_type = $object->getType();
          $row->object_id = $object->getIdentity();
          $row->date = date('Y-m-d H:i:s');
          
          if($notificationType == 'sitepagealbum_create') {
            $row->params = '{"count":"' . $count . '"}';
          } else {
            $row->params = '{"eventname":"' . $item_title_link . '"}';
          }
          $row->save();
        }

        //EMAIL SEND TO ALL MANAGEADMINS.
        $action_email = json_decode($value['action_email']);
        if (!empty($value['email']) && in_array('created', $action_email)) {
          Engine_Api::_()->getApi('mail', 'core')->sendSystem($user_subject->email, "$emailType", array(
              'page_title' => $pagetitle,
              'item_title' => $item_title,
              'body_content' => $post_linkformail,
          ));
        }
      }
    }

    //START SEND EMAIL TO ALL MEMBER WHO HAVE JOINED THE PAGE INCLUDE MANAGE ADMINS.
    if (!empty($sitepagememberEnabled)) {
      $membersIds = Engine_Api::_()->getDbtable('membership', 'sitepage')->getJoinMembers($page_id, $viewer_id, $viewer_id, 0, 1);
      foreach ($membersIds as $value) {
        $action_email = json_decode($value['action_email']);
        $user_subject = Engine_Api::_()->user()->getUser($value['user_id']);
        if ($params != 'Activity Comment' && $params != 'Activity Reply') {
          if (!empty($value['email_notification']) && $action_email->emailcreated == 1) {
            Engine_Api::_()->getApi('mail', 'core')->sendSystem($user_subject->email, "$emailType", array(
                'page_title' => $pagetitle,
                'item_title' => $item_title,
                'body_content' => $post_linkformail,
            ));
          } elseif (!empty($value['email_notification']) && $action_email->emailcreated == 2) {
            $friendId = Engine_Api::_()->user()->getViewer()->membership()->getMembershipsOfIds();
            if (in_array($value['user_id'], $friendId)) {
              Engine_Api::_()->getApi('mail', 'core')->sendSystem($user_subject->email, "$emailType", array(
                  'page_title' => $pagetitle,
                  'item_title' => $item_title,
                  'body_content' => $post_linkformail,
              ));
            }
          }
        }
      }
    }
    //END SEND EMAIL TO ALL MEMBER WHO HAVE JOINED THE PAGE INCLUDE MANAGE ADMINS.

    if ($params != 'Activity Comment' && $params != 'Pageevent Invite' && $params != 'Activity Reply') {
      $object_type = $subject->getType();
      $object_id = $subject->getIdentity();
      $subject_type = $viewer->getType();
      $subject_id = $viewer->getIdentity();
    } elseif ($params == 'Pageevent Invite') {
      $object_type = $object->getType();
      $object_id = $object->getIdentity();
//      $subject_type = $viewer->getType();
//      $subject_id = $viewer->getIdentity();
       if ($notificationSettings == 1) {
            $subject_type = $subject->getType();
            $subject_id = $subject->getIdentity();
          } else {
            $subject_type = $viewer->getType();
            $subject_id = $viewer->getIdentity();
          }
    }

    if ($params != 'Activity Comment' && $params != 'Activity Reply') {
      $notificationcreated = '%"notificationcreated":"1"%';
      $notificationsinglecodecreated = '%"notificationcreated":1%';
      $notificationfriendcreated = '%"notificationcreated":"2"%';
      $notificationfriendsinglecodecreated = '%"notificationcreated":2%';
      if($count) {
        $countparams = '{"count":"'. $count.'"}';
      } else {
        $countparams = null;  
      }
      if (!empty($sitepagememberEnabled)) {
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $friendId = Engine_Api::_()->user()->getViewer()->membership()->getMembershipsOfIds();
        if (!empty($friendId)) {
          $db->query("INSERT IGNORE INTO `engine4_activity_notifications` (`user_id`, `subject_type`, `subject_id`, `object_type`, `object_id`, `type`,`params`, `date`) SELECT `engine4_sitepage_membership`.`user_id` as `user_id` ,  '" . $subject_type . "' as `subject_type`, " . $subject_id . " as `subject_id`, '" . $object_type . "' as `object_type`, " . $object_id . " as `object_id`, '" . $notificationType . "' as `type`, '" . $countparams . "' as `params`, '" . date('Y-m-d H:i:s') . "' as ` date `  FROM `engine4_sitepage_membership` WHERE (engine4_sitepage_membership.page_id = " . $subject->page_id . ") AND (engine4_sitepage_membership.user_id <> " . $viewer->getIdentity() . ") AND (engine4_sitepage_membership.notification = 1) AND (engine4_sitepage_membership.action_notification LIKE '" . $notificationcreated . "' or engine4_sitepage_membership.action_notification LIKE '" . $notificationsinglecodecreated . "' or (engine4_sitepage_membership.action_notification LIKE '" . $notificationfriendcreated . "' or engine4_sitepage_membership.action_notification LIKE '" . $notificationfriendsinglecodecreated . "' and (engine4_sitepage_membership .user_id IN (" . join(",", $friendId) . "))))");
        } else {
          $db->query("INSERT IGNORE INTO `engine4_activity_notifications` (`user_id`, `subject_type`, `subject_id`, `object_type`, `object_id`, `type`,`params`, `date`) SELECT `engine4_sitepage_membership`.`user_id` as `user_id` ,  '" . $subject_type . "' as `subject_type`, " . $subject_id . " as `subject_id`, '" . $object_type . "' as `object_type`, " . $object_id . " as `object_id`, '" . $notificationType . "' as `type`, '" . $countparams . "' as `params`, '" . date('Y-m-d H:i:s') . "' as ` date `  FROM `engine4_sitepage_membership` WHERE (engine4_sitepage_membership.page_id = " . $subject->page_id . ") AND (engine4_sitepage_membership.user_id <> " . $viewer->getIdentity() . ") AND (engine4_sitepage_membership.notification = 1) AND (engine4_sitepage_membership.action_notification LIKE '" . $notificationcreated . "' OR engine4_sitepage_membership.action_notification LIKE '" . $notificationsinglecodecreated . "')");
        }
      }
    }
  }
    
    /*
    * General string translation function
    *
    */
    private function translate($message)
    {
        return Engine_Api::_()->getApi('Core', 'siteapi')->translate($message);
    }

}
