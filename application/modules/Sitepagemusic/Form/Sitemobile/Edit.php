<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http:// www.socialengineaddons.com/license/
 * @version    $Id: Edit.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagemusic_Form_Sitemobile_Edit extends Sitepagemusic_Form_Sitemobile_Create {

  public function init() {
    
    parent::init();
    $this
            ->setDescription('')
            ->setAttrib('id', 'form-upload-music')
            ->setAttrib('name', 'playlist_edit')
            ->setAttrib('enctype', 'multipart/form-data')
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
    ;

    $this->addElement('Hidden', 'playlist_id',array( 'order' => 800,));
    $this->removeElement('submit');
    $this->addElement('Button', 'save', array(
        'label' => 'Save Changes',
        'type' => 'submit',
    ));
  }

  public function populate($playlist) {
    
    $this->setTitle('Edit Playlist');
    foreach (array(
'playlist_id' => $playlist->getIdentity(),
 'title' => $playlist->getTitle(),
 'description' => $playlist->description,
 'search' => $playlist->search,
    ) as $key => $value) {
      $this->getElement($key)->setValue($value);
    }
  }

  public function saveValues() {
    
    $playlist = parent::saveValues();
    $values = $this->getValues();
    if ($playlist) {
      $playlist->title = $values['title'];
      $playlist->description = $values['description'];
      $playlist->search = $values['search'];
      $playlist->save();

      //Multiple music upload
        foreach ($_FILES['Filedata']['name'] as $key => $uploadFile) {
          
          $file = array('name' => $_FILES['Filedata']['name'][$key], 'tmp_name' => $_FILES['Filedata']['tmp_name'][$key], 'type' => $_FILES['Filedata']['type'][$key], 'size' => $_FILES['Filedata']['size'][$key], 'error' => $_FILES['Filedata']['error'][$key]);
          
          if (!is_uploaded_file($file['tmp_name'])) {
            continue;
          }
          
          $song = Engine_Api::_()->getDbTable('playlistSongs', 'sitepagemusic')->createSong($file);
          $playlist->addSong($song->file_id);
        }

      $actionTable = Engine_Api::_()->getDbtable('actions', 'activity');
      foreach ($actionTable->getActionsByObject($playlist) as $action) {
        $actionTable->resetActivityBindings($action);
      }

      return $playlist;
    } else {
      return false;
    }
  }

}

?>