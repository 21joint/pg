<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http:// www.socialengineaddons.com/license/
 * @version    $Id: Create.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagemusic_Form_Create extends Engine_Form {

  protected $_playlist;

  public function init() {

    $auth = Engine_Api::_()->authorization()->context;
    $user = Engine_Api::_()->user()->getViewer();

    $this
            ->setTitle('Add New Songs')
            ->setDescription('Choose music from your computer to add to the playlist of this Page.')
            ->setAttrib('id', 'form-upload-music')
            ->setAttrib('name', 'playlist_create')
            ->setAttrib('enctype', 'multipart/form-data')
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
    ;

    $this->addElement('Text', 'title', array(
        'label' => 'Playlist Name',
        'maxlength' => '63',
        'filters' => array(
            new Engine_Filter_Censor(),
            new Engine_Filter_StringLength(array('max' => '63')),
        )
    ));

    $this->addElement('Textarea', 'description', array(
        'label' => 'Playlist Description',
        'maxlength' => '300',
        'filters' => array(
            'StripTags',
            new Engine_Filter_Censor(),
            new Engine_Filter_StringLength(array('max' => '300')),
            new Engine_Filter_EnableLinks(),
        ),
    ));

    $this->addElement('Checkbox', 'search', array(
        'label' => "Show this playlist in search results.",
        'value' => 1,
        'checked' => true,
    ));


    $this->addElement('File', 'art', array(
        'label' => 'Playlist Artwork',
    ));
    $this->art->addValidator('Extension', false, 'jpg,png,gif,jpeg');

    $fancyUpload = new Engine_Form_Element_FancyUpload('file');
    $fancyUpload->clearDecorators()
            ->addDecorator('FormFancyUpload')
            ->addDecorator('viewScript', array(
                'viewScript' => '_FancyUpload.tpl',
                'placement' => '',
            ));
    Engine_Form::addDefaultDecorators($fancyUpload);
    $this->addElement($fancyUpload);

    $this->addElement('Hidden', 'fancyuploadfileids',array( 'order' => 600,));

    $this->addElement('Button', 'submit', array(
        'label' => 'Save Music to Playlist',
        'type' => 'submit',
    ));
  }

  public function clearUploads() {
    $this->getElement('fancyuploadfileids')->setValue('');
  }

  public function saveValues() { 

    $page_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('page_id', null);
    $playlist = null;
    $values = $this->getValues();
    $translate = Zend_Registry::get('Zend_Translate');
    $sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id);
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $viewer = Engine_Api::_()->user()->getViewer();

    if (!empty($values['playlist_id']))
      $playlist = Engine_Api::_()->getItem('sitepagemusic_playlist', $values['playlist_id']);
    else {
      $playlist = $this->_playlist = Engine_Api::_()->getDbtable('playlists', 'sitepagemusic')->createRow();

      $playlist->title = trim($values['title']);
      if (empty($playlist->title))
        $playlist->title = $translate->_('_SITEPAGEMUSIC_UNTITLED_PLAYLIST');

      $playlist->page_id = $page_id;
      $playlist->owner_id = Engine_Api::_()->user()->getViewer()->getIdentity();
      $playlist->description = trim($values['description']);
      $playlist->search = $values['search'];
      $playlist->view_count = 1;
      $playlist->save();
      $values['playlist_id'] = $playlist->playlist_id;
      $playlist = $this->_playlist = Engine_Api::_()->getItem('sitepagemusic_playlist', $values['playlist_id']);

      $file_ids = array();
      foreach (explode(' ', $values['fancyuploadfileids']) as $file_id) {
        $file_id = trim($file_id);
        if (!empty($file_id))
          $file_ids[] = $file_id;
      }

      if (!empty($file_ids))
        foreach ($file_ids as $file_id)
          $playlist->addSong($file_id);

      if ($playlist->search) {
        $activity = Engine_Api::_()->getDbtable('actions', 'activity');
        $activityFeedType = null;
        $linked_music_title = '<b>' . $view->htmlLink($playlist->getHref(), $playlist->getTitle()) . '</b>';
        if (Engine_Api::_()->sitepage()->isPageOwner($sitepage) && Engine_Api::_()->sitepage()->isFeedTypePageEnable()) {
          $activityFeedType = 'sitepagemusic_admin_new';
        } else {
          $activityFeedType = 'sitepagemusic_playlist_new';
        }
        if ($activityFeedType) {
          $action = $activity->addActivity(Engine_Api::_()->user()->getViewer(), $sitepage, $activityFeedType, null, array('count' => count($file_ids), 'linked_music_title' => $linked_music_title));
          Engine_Api::_()->getApi('subCore', 'sitepage')->deleteFeedStream($action);
        }
        if (null !== $action)
          $activity->attachActivity($action, $playlist);

        foreach ($activity->getActionsByObject($playlist) as $action) {
          $actionTable->resetActivityBindings($action);
        }

				//PAGE Music CREATE NOTIFICATION AND EMAIL WORK
				$sitepageVersion = Engine_Api::_()->getDbtable('modules', 'core')->getModule('sitepage')->version;
				if(!empty($action)) {
					if ($sitepageVersion >= '4.3.0p1') {
						Engine_Api::_()->sitepage()->sendNotificationEmail($playlist, $action, 'sitepagemusic_create', 'SITEPAGEMUSIC_CREATENOTIFICATION_EMAIL', 'Pageevent Invite');
						
						$isPageAdmins = Engine_Api::_()->getDbtable('manageadmins', 'sitepage')->isPageAdmins($viewer->getIdentity(), $page_id);
						if (!empty($isPageAdmins)) {
							//NOTIFICATION FOR ALL FOLLWERS.
							Engine_Api::_()->sitepage()->sendNotificationToFollowers($playlist, $action, 'sitepagemusic_create');
						}
					}
				}

        //SENDING ACTIVITY FEED TO FACEBOOK.
        $enable_Facebooksefeed = $enable_fboldversion = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('facebooksefeed');
        if (!empty($enable_Facebooksefeed)) {
          $musiccreate_array = array();
          $musiccreate_array['type'] = 'sitepagemusic_playlist_new';
          $musiccreate_array['object'] = $playlist;
          $musiccreate_array['description'] = trim($values['description']);
          Engine_Api::_()->facebooksefeed()->sendFacebookFeed($musiccreate_array);
        }
      }
    }

    if (!empty($values['art']))
      $playlist->setPhoto($this->art);

    return $playlist;
  }

}

?>