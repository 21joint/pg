<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteapi
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    Core.php 2015-09-17 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecontentcoverphoto_Api_Siteapi_Core extends Core_Api_Abstract {
    /*
     * Get coverphoto menus
     * 
     * @param object user
     * @return array
     */

    public function getCoverPhotoMenu($subject, $coverPhoto, $profilePhoto, $type) {
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        $can_edit = 0;
        $moduleName = strtolower($subject->getModuleName());
        //START MANAGE-ADMIN CHECK
        if (Engine_Api::_()->sitecontentcoverphoto()->checkConditionsForAlbum($moduleName)) {
            $can_edit = $isManageAdmin = Engine_Api::_()->$moduleName()->isManageAdmin($subject, 'edit');
            if (empty($isManageAdmin))
                $can_edit = 0;
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

        if ($can_edit) {
            if ($type == 'profile' || $type == 'both') {
                $coverMenu['profilePhotoMenu'][] = array(
                    'label' => $this->_translate('Upload Profile Photo'),
                    'name' => 'upload_photo',
                    'url' => 'coverphoto/upload-cover-photo',
                    'urlParams' => array(
                        'subject_type' => $subject->getType(),
                        'subject_id' => $subject->getIdentity(),
                        'special' => 'profile'
                    )
                );

                $coverMenu['profilePhotoMenu'][] = array(
                    'label' => $this->_translate('Choose from Albums'),
                    'name' => 'choose_from_album',
                    'urlParams' => array(
                    )
                );

                if (isset($profilePhoto) && !empty($profilePhoto)) {
                    $coverMenu['profilePhotoMenu'][] = array(
                        'label' => $this->_translate('View Profile Photo'),
                        'name' => 'view_profile_photo',
                        'urlParams' => array(
                        )
                    );

                    $coverMenu['profilePhotoMenu'][] = array(
                        'label' => $this->_translate('Remove Profile Photo'),
                        'name' => 'remove_photo',
                        'url' => 'coverphoto/remove-cover-photo',
                        'urlParams' => array(
                            'subject_type' => $subject->getType(),
                            'subject_id' => $subject->getIdentity(),
                            'special' => 'profile'
                        )
                    );
                }
            }
            if ($type == 'cover' || $type == 'both') {
                $coverMenu['coverPhotoMenu'][] = array(
                    'label' => $this->_translate('Upload Cover Photo'),
                    'name' => 'upload_cover_photo',
                    'url' => 'coverphoto/upload-cover-photo',
                    'urlParams' => array(
                        'subject_type' => $subject->getType(),
                        'subject_id' => $subject->getIdentity()
                    )
                );

                $coverMenu['coverPhotoMenu'][] = array(
                    'label' => $this->_translate('Choose from Albums'),
                    'name' => 'choose_from_album',
                    'urlParams' => array(
                    )
                );

                if (isset($coverPhoto) && !empty($coverPhoto)) {
                    $coverMenu['coverPhotoMenu'][] = array(
                        'label' => $this->_translate('View Cover Photo'),
                        'name' => 'view_cover_photo',
                        'urlParams' => array(
                        )
                    );

                    $coverMenu['coverPhotoMenu'][] = array(
                        'label' => $this->_translate('Remove Cover Photo'),
                        'name' => 'remove_cover_photo',
                        'url' => 'coverphoto/remove-cover-photo',
                        'urlParams' => array(
                            'subject_type' => $subject->getType(),
                            'subject_id' => $subject->getIdentity()
                        )
                    );
                }
            }
        }


        return $coverMenu;
    }

    /*
     * Get mainphoto menus
     * 
     * @param object user
     * @return array
     */

    public function getMainPhotoMenu($user) {
        $viewer = Engine_Api::_()->user()->getViewer();
        $canEdit = $user->authorization()->isAllowed($viewer, 'edit');
        if (empty($canEdit))
            return;

        if ($viewer->getIdentity() != $user->getIdentity())
            return;

        $mainPhotoMenu[] = array(
            'label' => $this->_translate('Upload Photo'),
            'name' => 'upload_photo',
            'url' => 'user/profilepage/upload-cover-photo/user_id/' . $user->getIdentity() . '/special/profile',
            'urlParams' => array(
            )
        );

        $mainPhotoMenu[] = array(
            'label' => $this->_translate('Choose from Albums'),
            'name' => 'choose_from_album',
            'urlParams' => array(
            )
        );

        if (isset($user->photo_id) && !empty($user->photo_id)) {
            $host = Engine_Api::_()->getApi('Core', 'siteapi')->getHost();
            $getPhotoURL = $user->getPhotoUrl();
            $finalPhotoURL = (strstr($getPhotoURL, 'http')) ? $getPhotoURL : $host . $getPhotoURL;
            $tempInfo = array(
                'label' => $this->_translate('View Profile Photo'),
                'name' => 'view_profile_photo',
                'url' => $finalPhotoURL,
                'urlParams' => array(
                )
            );

            $mainPhotoMenu[] = $tempInfo;

            $mainPhotoMenu[] = array(
                'label' => $this->_translate('Remove'),
                'name' => 'remove_photo',
                'url' => 'user/profilepage/remove-cover-photo/user_id/' . $user->getIdentity() . '/special/profile',
                'urlParams' => array(
                )
            );
        }

        return $mainPhotoMenu;
    }

    /*
     * Translte the language
     * 
     * @param string str
     * @return string or array
     */

    protected function _translate($str) {
        return Engine_Api::_()->getApi('Core', 'siteapi')->translate($str);
    }

}
