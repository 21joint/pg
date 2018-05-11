<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteapi
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    IndexController.php 2015-09-17 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecontentcoverphoto_IndexController extends Siteapi_Controller_Action_Standard {

    public function getCoverPhotoMenuAction() {
        $bodyParams = array();
        if (!$this->_helper->requireUser()->isValid())
            $this->respondWithSuccess($bodyParams, true);

        $viewer = Engine_Api::_()->user()->getViewer();

        $coverPhoto = $this->_getParam('cover_photo');
        $profilePhoto = $this->_getParam('profile_photo');
        $type = $this->_getParam('special', 'cover');

        $subject_id = $this->_getParam('subject_id');
        $subject_type = $this->_getParam('subject_type');
        $subject = Engine_Api::_()->getItem($subject_type, $subject_id);

        if (empty($subject))
            $this->respondWithError('unauthorized');

        try {
            $getUserCoverPhotoMenu = Engine_Api::_()->getApi('Siteapi_Core', 'sitecontentcoverphoto')->getCoverPhotoMenu($subject, $coverPhoto, $profilePhoto, $type);
            if (!empty($getUserCoverPhotoMenu))
                $bodyParams['response'] = $getUserCoverPhotoMenu;
            $this->respondWithSuccess($bodyParams);
        } catch (Exception $e) {
            $this->respondWithValidationError('internal_server_error', $e->getMessage());
        }
    }

    public function uploadCoverPhotoAction() {
        //CHECK USER VALIDATION
        if (!$this->_helper->requireUser()->isValid())
            $this->respondWithError('unauthorized');

        $subject_id = $this->_getParam('subject_id');
        $resource_type = $subject_type = $this->_getParam('subject_type');
        $subject = Engine_Api::_()->getItem($subject_type, $subject_id);
        $photo_id = $this->_getParam('photo_id');

        $special = $this->_getParam('special', 'cover');
        $level_id = $subject->getOwner()->level_id;

        $cover_photo_preview = 0;
        $can_edit = 0;

        if (empty($subject))
            $this->respondWithError('unauthorized');

        $viewer = Engine_Api::_()->user()->getViewer();
        $moduleName = strtolower($subject->getModuleName());

        $fieldName = strtolower($subject->getShortType()) . '_cover';

        //START MANAGE-ADMIN CHECK
        if (Engine_Api::_()->sitecontentcoverphoto()->checkConditionsForAlbum($moduleName)) {
            $can_edit = $isManageAdmin = Engine_Api::_()->$moduleName()->isManageAdmin($subject, 'edit');
            if (empty($isManageAdmin)) {
                $this->respondWithError('unauthorized');
            }
        } else {
            if ($moduleName == 'sitereview') {
                $can_edit = $subject->authorization()->isAllowed($viewer, "edit_listtype_$subject->listingtype_id");
            } else {
                $can_edit = $subject->authorization()->isAllowed($viewer, 'edit');
            }
        }

        //GET FORM
        if ($special == 'cover') {
            if ($can_edit && Engine_Api::_()->sitecontentcoverphoto()->getUploadPermission($subject, $viewer)) {
                $can_edit = 1;
            } else {
                $can_edit = 0;
            }

            if (($subject->getType() === 'sitepage_page') && (!Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitepagealbum') || !Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagealbum.isActivate', 1))) {
                $can_edit = 0;
            } else if (($subject->getType() === 'sitebusiness_business') && (!Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitebusinessalbum') || !Engine_Api::_()->getApi('settings', 'core')->getSetting('sitebusinessalbum.isActivate', 1))) {
                $can_edit = 0;
            }
            if (!$can_edit) {
                $this->respondWithError('unauthorized');
            }
        }

        //CHECK FORM VALIDATION
        $file = '';
        $notNeedToCreate = false;

        $db = Engine_Db_Table::getDefaultAdapter();
        if ($resource_type != 'sitereview_listing') {
            $tableName = Engine_Api::_()->getItemtable($resource_type)->info('name');
            $field = $db->query("SHOW COLUMNS FROM $tableName LIKE '$fieldName'")->fetch();
            if (empty($field)) {
                $db->query("ALTER TABLE `$tableName` ADD `$fieldName` INT( 11 ) NOT NULL DEFAULT '0'");
            }
        } else {
            $tableName = 'engine4_sitereview_otherinfo';
            $field = $db->query("SHOW COLUMNS FROM $tableName LIKE '$fieldName'")->fetch();
            if (empty($field)) {
                $db->query("ALTER TABLE `$tableName` ADD `$fieldName` INT( 11 ) NOT NULL DEFAULT '0'");
            }
        }

        if ($photo_id) {
            $photo = Engine_Api::_()->getItem("$moduleName" . "_photo", $photo_id);
            if ($moduleName != 'album') {
                $album = Engine_Api::_()->getItem("$moduleName" . "_album", $photo->album_id);
            } else {
                $album = Engine_Api::_()->getItem("album", $photo->album_id);
            }

            if (Engine_Api::_()->sitecontentcoverphoto()->checkConditionsForAlbum($moduleName)) {
                if ($special == 'cover' && $album->default_value == 1 && $album->type != 'cover' && $album->photo_id == $subject->photo_id && $subject->$fieldName == 0) {
                    $notNeedToCreate = false;
                } elseif ($special == 'cover' && $album->default_value == 1 && $album->type != 'cover' && $album->photo_id == $subject->photo_id && $subject->$fieldName != 0) {
                    $notNeedToCreate = true;
                } elseif ($special == 'cover' && $album->default_value == 0 && $album->type == 'cover' && $album->photo_id != $subject->photo_id && $subject->$fieldName != 0) {
                    $notNeedToCreate = true;
                }
            }

            if ($moduleName == 'album' || $moduleName == 'siteevent' || $moduleName == 'sitestoreproduct' || $moduleName == 'sitereview' || $moduleName == 'sitevideo') {
                $notNeedToCreate = true;
            }
            if ($photo->file_id && !$notNeedToCreate)
                $file = Engine_Api::_()->getItemTable('storage_file')->getFile($photo->file_id);
        }

        //UPLOAD PHOTO
        if ($_FILES['photo'] !== null || $photo || ($notNeedToCreate && $file)) {

            //PROCESS
            $db = Engine_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            try {
                //CREATE PHOTO
                $tablePhoto = Engine_Api::_()->getItemTable($moduleName . "_photo");
                $getShortType = ucfirst($subject->getShortType());

                $primaryTableKey = Engine_Api::_()->getItemtable($subject->getType())->info('primary');
                $tablePrimaryFieldName = $primaryTableKey[1];
                if (!$notNeedToCreate) {
                    $photo = $tablePhoto->createRow();

                    if (isset($photo->user_id)) {
                        $user_id = 'user_id';
                    } elseif (isset($photo->owner_id)) {
                        $user_id = 'owner_id';
                    }
                    $photo->setFromArray(array(
                        $user_id => Engine_Api::_()->user()->getViewer()->getIdentity(),
                        $tablePrimaryFieldName => $subject->getIdentity()
                    ));
                    $photo->save();

                    if ($file) {
                        if ($special == 'cover') {
                            $this->setCoverPhoto($file, $photo, $cover_photo_preview, null, null, $moduleName);
                        } else {
                            $this->setMainPhoto($file, $photo, $moduleName);
                        }
                    } else {
                        if ($special == 'cover') {
                            $this->setCoverPhoto($_FILES['photo'], $photo, $cover_photo_preview, null, null, $moduleName);
                        } else {
                            $this->setMainPhoto($_FILES['photo'], $photo, $moduleName);
                        }
                    }

                    if ($special == 'cover') {
                        $album = Engine_Api::_()->sitecontentcoverphoto()->getSpecialAlbum($subject, $special, $moduleName);
                        $tablePhotoName = $tablePhoto->info('name');

                        if (isset($tablePhoto->order)) {
                            $photoSelect = $tablePhoto->select()->from($tablePhotoName, 'order')->where('album_id = ?', $album->album_id)->order('order DESC')->limit(1);
                            $photo_rowinfo = $tablePhoto->fetchRow($photoSelect);
                            $order = 0;
                            if (!empty($photo_rowinfo)) {
                                $order = $photo_rowinfo->order + 1;
                            }
                            $photo->order = $order;
                        }

                        if (isset($photo->collection_id))
                            $photo->collection_id = $album->album_id;
                        $photo->album_id = $album->album_id;
                        $photo->save();


                        if (!isset($album->cover_params['fontcolor'])) {
                            $fontcolor = '#FFFFFF';
                        } else {
                            $fontcolor = $album->cover_params['fontcolor'];
                        }
                        $coverParams = Zend_Json_Encoder::encode($this->_getParam('position', array('top' => '0', 'left' => 0)));
                        $coverParams = Zend_Json_Decoder::decode($coverParams);
                        $coverParams['fontcolor'] = $fontcolor;
                        $coverParams = Zend_Json_Encoder::encode($coverParams);
                        $album->cover_params = $coverParams;

                        $album->save();
                        if (!$album->photo_id) {
                            if ($moduleName != 'album') {
                                $album->photo_id = $photo->file_id;
                            } else {
                                $album->photo_id = $photo->getIdentity();
                            }
                            $album->save();
                        }
                    }
                }

                if ($special == 'cover') {
                    if ($moduleName != 'sitereview') {
                        $subject->$fieldName = $photo->photo_id;
                    } else {
                        $tableOtherinfo = Engine_Api::_()->getDbTable('otherinfo', 'sitereview');
                        $row = $tableOtherinfo->getOtherinfo($subject->listing_id);
                        if (empty($row)) {
                            Engine_Api::_()->getDbTable('otherinfo', 'sitereview')->insert(array(
                                'listing_id' => $subject->listing_id,
                                $fieldName => $photo->photo_id
                            )); //COMMIT  
                        } else {
                            $tableOtherinfo->update(array($fieldName => $photo->photo_id), array('listing_id = ?' => $subject->listing_id));
                        }
                    }
                } else {
                    if ($moduleName != 'album') {
                        if (isset($subject->photo_id)) {
                            $subject->photo_id = $photo->file_id;
                        } elseif (isset($subject->file_id)) {
                            $subject->file_id = $photo->file_id;
                        }
                    } else {
                        $subject->photo_id = $photo->getIdentity();
                    }
                    if (Engine_Api::_()->sitecontentcoverphoto()->checkConditionsForAlbum($moduleName)) {
                        if ($moduleName != 'album') {
                            $photo->album_id = $photo->collection_id = Engine_Api::_()->getItemTable($moduleName . "_album")->getDefaultAlbum($subject->getIdentity())->album_id;
                        } else {
                            $photo->album_id = $photo->collection_id = Engine_Api::_()->getItemTable("album")->getDefaultAlbum($subject->getIdentity())->album_id;
                        }

                        $photo->save();
                    } else {

                        if ($moduleName != 'album') {
                            $album_id = Engine_Api::_()->getItemTable($moduleName . "_album")->select()
                                    ->from(Engine_Api::_()->getItemTable($moduleName . "_album")->info('name'), array('album_id'))
                                    ->where("$tablePrimaryFieldName = ?", $subject->getIdentity())
                                    ->query()
                                    ->fetchColumn();
                        } else {
                            $album_id = Engine_Api::_()->getItemTable("album")->select()
                                    ->from(Engine_Api::_()->getItemTable("album")->info('name'), array('album_id'))
                                    ->where("$tablePrimaryFieldName = ?", $subject->getIdentity())
                                    ->query()
                                    ->fetchColumn();
                        }
                        $photo->album_id = $album_id;
                        if (isset($photo->collection_id)) {
                            $photo->collection_id = $album_id;
                        }
                        $photo->save();
                    }
                }

                $subject->save();
                $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
                //ADD ACTIVITY
                if (Engine_Api::_()->sitecontentcoverphoto()->checkConditionsForAlbum($moduleName)) {

                    $activityFeedType = null;
                    $ownerFunction = 'is' . $getShortType . 'Owner';
                    $feedTypeFunction = 'isFeedType' . $getShortType . 'Enable';
                    if (Engine_Api::_()->$moduleName()->$ownerFunction($subject) && Engine_Api::_()->$moduleName()->$feedTypeFunction()) {
                        if ($special == 'cover')
                            $activityFeedType = $moduleName . '_admin_cover_update';
                        elseif ($special == 'profile')
                            $activityFeedType = $moduleName . '_admin_profile_photo';
                    }
                    elseif ($subject->all_post || Engine_Api::_()->$moduleName()->$ownerFunction($subject)) {
                        if ($special == 'cover')
                            $activityFeedType = $moduleName . '_cover_update';
                        elseif ($special == 'profile')
                            $activityFeedType = $moduleName . '_profile_photo_update';
                    }

                    if ($activityFeedType) {
                        $action = $activityApi->addActivity($viewer, $subject, $activityFeedType);
                    }

                    if ($action) {
                        Engine_Api::_()->getApi('subCore', $moduleName)->deleteFeedStream($action);
                        if ($photo)
                            Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $photo);
                    }
                }
                else {
                    if ($moduleName == 'siteevent') {
                        if ($special == 'cover')
                            $activityFeedType = $moduleName . '_cover_update';
                        elseif ($special == 'profile')
                            $activityFeedType = $moduleName . '_change_photo';
                        $action = $activityApi->addActivity($viewer, $subject, Engine_Api::_()->siteevent()->getActivtyFeedType($subject, $activityFeedType));
                        if ($action) {
                            if ($photo)
                                Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $photo);
                        }
                    } else if ($moduleName == 'album') {
                        $activityFeedType = $moduleName . '_cover_update';
                        $action = $activityApi->addActivity($viewer, $subject, $activityFeedType);
                        if ($action) {
                            if ($photo)
                                Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $photo);
                        }
                    }
                }

                $db->commit();
                $this->successResponseNoContent('no_content', true);
            } catch (Exception $e) {
                $db->rollBack();
                $this->respondWithValidationError('internal_server_error', $e->getMessage());
            }
        }
    }

    public function removeCoverPhotoAction() {

        //CHECK USER VALIDATION
        if (!$this->_helper->requireUser()->isValid())
            $this->respondWithError('unauthorized');

        $viewer = Engine_Api::_()->user()->getViewer();
        $subject_id = $this->_getParam('subject_id');
        $subject_type = $this->_getParam('subject_type');
        $subject = Engine_Api::_()->getItem($subject_type, $subject_id);

        $fieldName = strtolower($subject->getShortType()) . '_cover';

        if (empty($subject))
            $this->respondWithError('unauthorized');

        $viewer = Engine_Api::_()->user()->getViewer();
        $moduleName = strtolower($subject->getModuleName());

        $special = $this->_getParam('special', 'cover');

        $level_id = $subject->getOwner()->level_id;

        if ($special == 'cover') {
            $can_edit = 0;
            //START MANAGE-ADMIN CHECK
            if (Engine_Api::_()->sitecontentcoverphoto()->checkConditionsForAlbum($moduleName)) {
                $can_edit = $isManageAdmin = Engine_Api::_()->$moduleName()->isManageAdmin($subject, 'edit');
                if (empty($isManageAdmin)) {
                    $can_edit = 0;
                }
            } else {
                if ($moduleName == 'sitereview') {
                    $can_edit = $subject->authorization()->isAllowed($viewer, "edit_listtype_$subject->listingtype_id");
                } else {
                    $can_edit = $subject->authorization()->isAllowed($viewer, 'edit');
                }
            }

            if ($can_edit && Engine_Api::_()->sitecontentcoverphoto()->getUploadPermission($subject, $viewer)) {
                $can_edit = 1;
            } else {
                $can_edit = 0;
            }

            if (($subject->getType() === 'sitepage_page') && (!Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitepagealbum') || !Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagealbum.isActivate', 1))) {
                $can_edit = 0;
            } else if (($subject->getType() === 'sitebusiness_business') && (!Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitebusinessalbum') || !Engine_Api::_()->getApi('settings', 'core')->getSetting('sitebusinessalbum.isActivate', 1))) {
                $can_edit = 0;
            }
            if (!$can_edit) {
                $this->respondWithError('unauthorized');
            }
        }

        if ($this->getRequest()->isPost()) {
            try {
                if ($special == 'cover') {
                    if ($subject->getType() == 'sitereview_listing') {
                        $tableName = 'engine4_sitereview_otherinfo';
                        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
                        $field = $db->query("SHOW COLUMNS FROM $tableName LIKE '$fieldName'")->fetch();
                        if (empty($field)) {
                            $db->query("ALTER TABLE `$tableName` ADD `$fieldName` INT( 11 ) NOT NULL DEFAULT '0'");
                        }

                        $fieldNameValue = Engine_Api::_()->getDbTable('otherinfo', 'sitereview')->getColumnValue($subject->listing_id, $fieldName);
                        if ($fieldNameValue) {

                            if (Engine_Api::_()->getItem('sitereview_photo', $fieldNameValue))
                                Engine_Api::_()->getItem('sitereview_photo', $fieldNameValue)->delete();
                        }

                        $tableOtherinfo = Engine_Api::_()->getDbTable('otherinfo', 'sitereview');
                        $tableOtherinfo->update(array($fieldName => 0), array('listing_id = ?' => $subject->listing_id));
                    } else
                        $subject->$fieldName = 0;

                    $album = Engine_Api::_()->sitecontentcoverphoto()->getSpecialAlbum($subject, $special, $moduleName);
                    $album->cover_params = Zend_Json_Encoder::encode(array('top' => '0', 'left' => 0, 'fontcolor' => '#FFFFFF'));
                    $album->save();
                } else {
                    $subject->photo_id = 0;
                }
                $subject->save();
                $this->successResponseNoContent('no_content', true);
            } catch (Exception $e) {
                $this->respondWithValidationError('internal_server_error', $e->getMessage());
            }
        }
    }

    /**
     * Set a photo
     *
     * @param array photo
     * @return photo object
     */
    public function setCoverPhoto($photo, $photoObject, $cover_photo_preview, $level_id = null, $sitecontentcoverphoto_setdefault = 0, $moduleName = null, $subject = null) {

        if ($photo instanceof Zend_Form_Element_File) {
            $file = $photo->getFileName();
            $fileName = $file;
        } else if ($photo instanceof Storage_Model_File) {
            $file = $photo->temporary();
            $fileName = $photo->name;
        } else if ($photo instanceof Core_Model_Item_Abstract && !empty($photo->file_id)) {
            $tmpRow = Engine_Api::_()->getItem('storage_file', $photo->file_id);
            $file = $tmpRow->temporary();
            $fileName = $tmpRow->name;
        } else if (is_array($photo) && !empty($photo['tmp_name'])) {
            $file = $photo['tmp_name'];
            $fileName = $photo['name'];
        } else if (is_string($photo) && file_exists($photo)) {
            $file = $photo;
            $fileName = $photo;
        } else {
            throw new User_Model_Exception('invalid argument passed to setPhoto');
        }

        if (!$fileName) {
            $fileName = $file;
        }

        $name = basename($file);
        $extension = ltrim(strrchr($fileName, '.'), '.');
        $base = rtrim(substr(basename($fileName), 0, strrpos(basename($fileName), '.')), '.');
        $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
        $filesTable = Engine_Api::_()->getDbtable('files', 'storage');
        $hasVersion = Engine_Api::_()->seaocore()->usingLessVersion('core', '4.8.9');
        if ($moduleName != 'album') {
            $mainPath = $path . DIRECTORY_SEPARATOR . $base . '_m.' . $extension;
            $image = Engine_Image::factory();

            if (!empty($hasVersion)) {
                $image->open($file)
                        ->resize(720, 720)
                        ->write($mainPath)
                        ->destroy();

                $normalPath = $path . DIRECTORY_SEPARATOR . $base . '_in.' . $extension;
                $image = Engine_Image::factory();
                $image->open($file)
                        ->resize(140, 160)
                        ->write($normalPath)
                        ->destroy();

                $coverPath = $path . DIRECTORY_SEPARATOR . $base . '_c.' . $extension;
                $image = Engine_Image::factory();
                $image->open($file)
                        ->resize(1500, 1500)
                        ->write($coverPath)
                        ->destroy();
            } else {
                $image->open($file)
                        ->autoRotate()
                        ->resize(720, 720)
                        ->write($mainPath)
                        ->destroy();

                $normalPath = $path . DIRECTORY_SEPARATOR . $base . '_in.' . $extension;
                $image = Engine_Image::factory();
                $image->open($file)->autoRotate()
                        ->resize(140, 160)
                        ->write($normalPath)
                        ->destroy();

                $coverPath = $path . DIRECTORY_SEPARATOR . $base . '_c.' . $extension;
                $image = Engine_Image::factory();
                $image->open($file)->autoRotate()
                        ->resize(1500, 1500)
                        ->write($coverPath)
                        ->destroy();
            }
        } else {
            $coreSettings = Engine_Api::_()->getApi('settings', 'core');
            $mainHeight = $coreSettings->getSetting('main.photo.height', 1600);
            $mainWidth = $coreSettings->getSetting('main.photo.width', 1600);

            // Resize image (main)
            $mainPath = $path . DIRECTORY_SEPARATOR . $base . '_m.' . $extension;
            $image = Engine_Image::factory();
            if (!empty($hasVersion)) {
                $image->open($file)
                        ->resize($mainWidth, $mainHeight)
                        ->write($mainPath)
                        ->destroy();

                $normalHeight = $coreSettings->getSetting('normal.photo.height', 375);
                $normalWidth = $coreSettings->getSetting('normal.photo.width', 375);
                // Resize image (normal)
                $normalPath = $path . DIRECTORY_SEPARATOR . $base . '_in.' . $extension;

                $image = Engine_Image::factory();
                $image->open($file)
                        ->resize($normalWidth, $normalHeight)
                        ->write($normalPath)
                        ->destroy();

                $normalLargeHeight = $coreSettings->getSetting('normallarge.photo.height', 720);
                $normalLargeWidth = $coreSettings->getSetting('normallarge.photo.width', 720);
                // Resize image (normal)
                $normalLargePath = $path . DIRECTORY_SEPARATOR . $base . '_inl.' . $extension;

                $image = Engine_Image::factory();
                $image->open($file)
                        ->resize($normalLargeWidth, $normalLargeHeight)
                        ->write($normalLargePath)
                        ->destroy();

                $coverPath = $path . DIRECTORY_SEPARATOR . $base . '_c.' . $extension;
                $image = Engine_Image::factory();
                $image->open($file)
                        ->resize(1500, 1500)
                        ->write($coverPath)
                        ->destroy();
            } else {
                $image->open($file)->autoRotate()
                        ->resize($mainWidth, $mainHeight)
                        ->write($mainPath)
                        ->destroy();

                $normalHeight = $coreSettings->getSetting('normal.photo.height', 375);
                $normalWidth = $coreSettings->getSetting('normal.photo.width', 375);
                // Resize image (normal)
                $normalPath = $path . DIRECTORY_SEPARATOR . $base . '_in.' . $extension;

                $image = Engine_Image::factory();
                $image->open($file)->autoRotate()
                        ->resize($normalWidth, $normalHeight)
                        ->write($normalPath)
                        ->destroy();

                $normalLargeHeight = $coreSettings->getSetting('normallarge.photo.height', 720);
                $normalLargeWidth = $coreSettings->getSetting('normallarge.photo.width', 720);
                // Resize image (normal)
                $normalLargePath = $path . DIRECTORY_SEPARATOR . $base . '_inl.' . $extension;

                $image = Engine_Image::factory();
                $image->open($file)->autoRotate()
                        ->resize($normalLargeWidth, $normalLargeHeight)
                        ->write($normalLargePath)
                        ->destroy();
                $coverPath = $path . DIRECTORY_SEPARATOR . $base . '_c.' . $extension;
                $image = Engine_Image::factory();
                $image->open($file)->autoRotate()
                        ->resize(1500, 1500)
                        ->write($coverPath)
                        ->destroy();
            }
        }

        if (empty($cover_photo_preview)) {
            if (isset($photoObject->user_id)) {
                $user_id = 'user_id';
                $user_id_value = $photoObject->user_id;
            } elseif (isset($photoObject->owner_id)) {
                $user_id = 'owner_id';
                $user_id_value = $photoObject->owner_id;
            }
            $params = array(
                'parent_type' => $photoObject->getType(),
                'parent_id' => $photoObject->getIdentity(),
                $user_id => $user_id_value,
                'name' => basename($fileName),
            );

            try {
                $iMain = $filesTable->createFile($mainPath, $params);
                $iIconNormal = $filesTable->createFile($normalPath, $params);
                $iMain->bridge($iIconNormal, 'thumb.normal');
                if ($moduleName == 'album') {
                    $iIconNormalLarge = $filesTable->createFile($normalLargePath, $params);
                    $iMain->bridge($iIconNormalLarge, 'thumb.large');
                }
                $iCover = $filesTable->createFile($coverPath, $params);
                $iMain->bridge($iCover, 'thumb.cover');
            } catch (Exception $e) {
                @unlink($mainPath);
                @unlink($normalPath);
                @unlink($coverPath);
                if ($moduleName == 'album') {
                    @unlink($normalLargePath);
                }
            }
            @unlink($mainPath);
            @unlink($normalPath);
            @unlink($coverPath);
            if ($moduleName == 'album') {
                @unlink($normalLargePath);
            }
            $photoObject->modified_date = date('Y-m-d H:i:s');
            $photoObject->file_id = $iMain->file_id;
            $photoObject->save();
            if (!empty($tmpRow)) {
                $tmpRow->delete();
            }

            return $photoObject;
        } else {
            try {
                $iMain = $filesTable->createSystemFile($mainPath);
                $iIconNormal = $filesTable->createSystemFile($normalPath);
                $iMain->bridge($iIconNormal, 'thumb.normal');
                $iCover = $filesTable->createSystemFile($coverPath);
                $iMain->bridge($iCover, 'thumb.cover');
            } catch (Exception $e) {
                @unlink($mainPath);
                @unlink($normalPath);
                @unlink($coverPath);
                if ($e->getCode() == Storage_Model_DbTable_Files::SPACE_LIMIT_REACHED_CODE) {
                    throw new Album_Model_Exception($e->getMessage(), $e->getCode());
                } else {
                    throw $e;
                }
            }

            if ($sitecontentcoverphoto_setdefault) {
                $level_ids = Engine_Api::_()->getDbtable('levels', 'authorization')->getLevelsAssoc();
                foreach ($level_ids as $key => $value) {
                    Engine_Api::_()->sitecontentcoverphoto()->setSiteContentDefaultSettingsIds($subject, $moduleName, $key, $iMain->file_id);
                }
            } else {
                Engine_Api::_()->sitecontentcoverphoto()->setSiteContentDefaultSettingsIds($subject, $moduleName, $level_id, $iMain->file_id);
            }
        }
    }

    /**
     * Set a photo
     *
     * @param array photo
     * @return photo object
     */
    public function setMainPhoto($photo, $photoObject, $moduleName) {

        if ($photo instanceof Zend_Form_Element_File) {
            $file = $photo->getFileName();
            $fileName = $file;
        } else if ($photo instanceof Storage_Model_File) {
            $file = $photo->temporary();
            $fileName = $photo->name;
        } else if ($photo instanceof Core_Model_Item_Abstract && !empty($photo->file_id)) {
            $tmpRow = Engine_Api::_()->getItem('storage_file', $photo->file_id);
            $file = $tmpRow->temporary();
            $fileName = $tmpRow->name;
        } else if (is_array($photo) && !empty($photo['tmp_name'])) {
            $file = $photo['tmp_name'];
            $fileName = $photo['name'];
        } else if (is_string($photo) && file_exists($photo)) {
            $file = $photo;
            $fileName = $photo;
        } else {
            throw new User_Model_Exception('invalid argument passed to setPhoto');
        }

        if (!$fileName) {
            $fileName = $file;
        }

        $name = basename($file);
        $extension = ltrim(strrchr($fileName, '.'), '.');
        $base = rtrim(substr(basename($fileName), 0, strrpos(basename($fileName), '.')), '.');
        $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
        if (isset($photoObject->user_id)) {
            $user_id = 'user_id';
            $user_id_value = $photoObject->user_id;
        } elseif (isset($photoObject->owner_id)) {
            $user_id = 'owner_id';
            $user_id_value = $photoObject->owner_id;
        }
        $params = array(
            'parent_type' => $photoObject->getType(),
            'parent_id' => $photoObject->getIdentity(),
            $user_id => $user_id_value,
            'name' => basename($fileName),
        );
        $hasVersion = Engine_Api::_()->seaocore()->usingLessVersion('core', '4.8.9');
        $filesTable = Engine_Api::_()->getDbtable('files', 'storage');
        if ($moduleName != 'album') {

            // Resize image (main)
            $mainPath = $path . DIRECTORY_SEPARATOR . $base . '_m.' . $extension;

            $image = Engine_Image::factory();
            if (!empty($hasVersion)) {
                $image->open($file)
                        ->resize(720, 720)
                        ->write($mainPath)
                        ->destroy();

                // Resize image (profile)
                $profilePath = $path . DIRECTORY_SEPARATOR . $base . '_p.' . $extension;
                $image = Engine_Image::factory();
                $image->open($file)
                        ->resize(200, 400)
                        ->write($profilePath)
                        ->destroy();

                // Resize image (normal)
                $normalPath = $path . DIRECTORY_SEPARATOR . $base . '_in.' . $extension;
                $image = Engine_Image::factory();
                $image->open($file)
                        ->resize(140, 160)
                        ->write($normalPath)
                        ->destroy();
            } else {
                $image->open($file)->autoRotate()
                        ->resize(720, 720)
                        ->write($mainPath)
                        ->destroy();

                // Resize image (profile)
                $profilePath = $path . DIRECTORY_SEPARATOR . $base . '_p.' . $extension;
                $image = Engine_Image::factory();
                $image->open($file)->autoRotate()
                        ->resize(200, 400)
                        ->write($profilePath)
                        ->destroy();

                // Resize image (normal)
                $normalPath = $path . DIRECTORY_SEPARATOR . $base . '_in.' . $extension;
                $image = Engine_Image::factory();
                $image->open($file)->autoRotate()
                        ->resize(140, 160)
                        ->write($normalPath)
                        ->destroy();
            }
            // Resize image (icon)
            $squarePath = $path . DIRECTORY_SEPARATOR . $base . '_is.' . $extension;
            $image = Engine_Image::factory();
            $image->open($file);

            $size = min($image->height, $image->width);
            $x = ($image->width - $size) / 2;
            $y = ($image->height - $size) / 2;

            $image->resample($x, $y, $size, $size, 48, 48)
                    ->write($squarePath)
                    ->destroy();

            // Store
            $iMain = $filesTable->createFile($mainPath, $params);
            $iProfile = $filesTable->createFile($profilePath, $params);
            $iIconNormal = $filesTable->createFile($normalPath, $params);
            $iSquare = $filesTable->createFile($squarePath, $params);

            $iMain->bridge($iProfile, 'thumb.profile');
            $iMain->bridge($iIconNormal, 'thumb.normal');
            $iMain->bridge($iSquare, 'thumb.icon');

            // Remove temp files
            @unlink($mainPath);
            @unlink($profilePath);
            @unlink($normalPath);
            @unlink($squarePath);
        } else {
            $coreSettings = Engine_Api::_()->getApi('settings', 'core');
            $mainHeight = $coreSettings->getSetting('main.photo.height', 1600);
            $mainWidth = $coreSettings->getSetting('main.photo.width', 1600);

            // Resize image (main)
            $mainPath = $path . DIRECTORY_SEPARATOR . $base . '_m.' . $extension;
            $image = Engine_Image::factory();
            if (!empty($hasVersion)) {
                $image->open($file)
                        ->resize($mainWidth, $mainHeight)
                        ->write($mainPath)
                        ->destroy();

                $normalHeight = $coreSettings->getSetting('normal.photo.height', 375);
                $normalWidth = $coreSettings->getSetting('normal.photo.width', 375);
                // Resize image (normal)
                $normalPath = $path . DIRECTORY_SEPARATOR . $base . '_in.' . $extension;

                $image = Engine_Image::factory();
                $image->open($file)
                        ->resize($normalWidth, $normalHeight)
                        ->write($normalPath)
                        ->destroy();

                $normalLargeHeight = $coreSettings->getSetting('normallarge.photo.height', 720);
                $normalLargeWidth = $coreSettings->getSetting('normallarge.photo.width', 720);
                // Resize image (normal)
                $normalLargePath = $path . DIRECTORY_SEPARATOR . $base . '_inl.' . $extension;

                $image = Engine_Image::factory();
                $image->open($file)
                        ->resize($normalLargeWidth, $normalLargeHeight)
                        ->write($normalLargePath)
                        ->destroy();
            } else {
                $image->open($file)->autoRotate()
                        ->resize($mainWidth, $mainHeight)
                        ->write($mainPath)
                        ->destroy();

                $normalHeight = $coreSettings->getSetting('normal.photo.height', 375);
                $normalWidth = $coreSettings->getSetting('normal.photo.width', 375);
                // Resize image (normal)
                $normalPath = $path . DIRECTORY_SEPARATOR . $base . '_in.' . $extension;

                $image = Engine_Image::factory();
                $image->open($file)->autoRotate()
                        ->resize($normalWidth, $normalHeight)
                        ->write($normalPath)
                        ->destroy();

                $normalLargeHeight = $coreSettings->getSetting('normallarge.photo.height', 720);
                $normalLargeWidth = $coreSettings->getSetting('normallarge.photo.width', 720);
                // Resize image (normal)
                $normalLargePath = $path . DIRECTORY_SEPARATOR . $base . '_inl.' . $extension;

                $image = Engine_Image::factory();
                $image->open($file)->autoRotate()
                        ->resize($normalLargeWidth, $normalLargeHeight)
                        ->write($normalLargePath)
                        ->destroy();
            }
            // Store
            try {
                $iMain = $filesTable->createFile($mainPath, $params);
                $iIconNormal = $filesTable->createFile($normalPath, $params);
                $iMain->bridge($iIconNormal, 'thumb.normal');
                $iIconNormalLarge = $filesTable->createFile($normalLargePath, $params);
                $iMain->bridge($iIconNormalLarge, 'thumb.large');
            } catch (Exception $e) {
                // Remove temp files
                @unlink($mainPath);
                @unlink($normalPath);
                @unlink($normalLargePath);
                // Throw
                if ($e->getCode() == Storage_Model_DbTable_Files::SPACE_LIMIT_REACHED_CODE) {
                    throw new Album_Model_Exception($e->getMessage(), $e->getCode());
                } else {
                    throw $e;
                }
            }
        }

        $photoObject->modified_date = date('Y-m-d H:i:s');
        $photoObject->file_id = $iMain->file_id;
        $photoObject->save();
        if (!empty($tmpRow)) {
            $tmpRow->delete();
        }

        return $photoObject;
    }

}
