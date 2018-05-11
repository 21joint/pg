<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Widget.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepagemusic_Form_Admin_Widget extends Engine_Form {

  public function init() {
    
    $this
            ->setTitle('Widget Settings')
            ->setDescription('Configure the general settings for the various widgets available with this plugin.');

    $this->addElement('Text', 'sitepagemusic_comment_widgets', array(
        'label' => 'Page Profile Most Commented Playlists',
        'maxlength' => '3',
        'description' => 'How many playlists will be shown in the page profile most commented playlists widget (value can not be empty or zero) ?',
        'required' => true,
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.comment.widgets', 3),
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        ),
    ));

    $this->addElement('Text', 'sitepagemusic_recent_widgets', array(
        'label' => 'Page Profile Most Recent Playlists',
        'maxlength' => '3',
        'description' => 'How many playlists should be shown in the page profile most recent playlists widget (value cannot be empty or zero) ?',
        'required' => true,
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.recent.widgets', 3),
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        ),
    ));

    $this->addElement('Text', 'sitepagemusic_like_widgets', array(
        'label' => 'Page Profile Most Liked Playlists',
        'maxlength' => '3',
        'description' => 'How many playlists should be shown in the page profile most liked playlists widget (value cannot be empty or zero) ?',
        'required' => true,
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.like.widgets', 3),
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        ),
    ));

    $this->addElement('Text', 'sitepagemusic_popular_widgets', array(
        'label' => 'Page Profile Most Popular Playlists',
        'maxlength' => '3',
        'description' => 'How many playlists should be shown in the page profile most popular playlists widget (value cannot be empty or zero) ?',
        'required' => true,
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.popular.widgets', 3),
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        ),
    ));

    $this->addElement('Text', 'sitepagemusic_homerecentmusics_widgets', array(
        'label' => 'Recent Page Playlists',
        'maxlength' => '3',
        'description' => 'How many playlists should be shown in the recent page playlists widget (value cannot be empty or zero) ?',
        'required' => true,
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.homerecentmusics.widgets', 3),
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        ),
    ));
    
    $this->addElement('Text', 'sitepagemusic_homepopularmusics_widgets', array(
        'label' => 'Popular Page Playlists',
        'maxlength' => '3',
        'description' => 'How many playlists should be shown in the popular page playlists widget (value cannot be empty or zero) ?',
        'required' => true,
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagemusic.homepopularmusics.widgets', 3),
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        ),
    ));
    $this->addElement('Button', 'submit', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'ignore' => true
    ));
  }

}

?>