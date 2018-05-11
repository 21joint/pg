<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Append.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagemusic_Form_Song_Append extends Engine_Form {

  public function init() {
    
    $this
            ->setTitle('Add Song To Playlist')
            ->setAttrib('id', 'form-playlist-append')
            ->setAttrib('name', 'playlist_add')
            ->setAttrib('class', '')
            ->setAction($_SERVER['REQUEST_URI'])
    ;

    $playlists = array();
    $playlists[0] = Zend_Registry::get('Zend_Translate')->_('Create New Playlist');
    $this->addElement('Select', 'playlist_id', array(
        'label' => 'Choose Playlist',
        'multiOptions' => $playlists,
        'onchange' => "updateTextFields()",
    ));

    $this->addElement('Text', 'title', array(
        'label' => 'Playlist Name',
        'style' => '',
        'filters' => array(
            new Engine_Filter_Censor(),
        ),
    ));

    $this->addElement('Button', 'execute', array(
        'label' => 'Add Song',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array(
            'ViewHelper',
        ),
    ));
 if (Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
    $this->addElement('Cancel', 'cancel', array(
        'label' => 'cancel',
        'link' => true,
        'prependText' => ' or ',
        'onclick' => 'parent.Smoothbox.close();',
        'decorators' => array(
            'ViewHelper',
        ),
    ));
 }else{ 
   $this->addElement('Cancel', 'cancel', array(
        'label' => 'cancel',
        'link' => true,
        'prependText' => ' or ',
        'data-rel' => 'back',
        'decorators' => array(
            'ViewHelper',
        ),
    ));
 }

    $this->addDisplayGroup(array(
        'execute',
        'cancel',
            ), 'buttons', array(
        'decorators' => array(
            'FormElements',
            'DivDivDivWrapper'
        ),
    ));
  }

}

?>