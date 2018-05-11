<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitegroupalbum
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Global.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitegroupalbum_Form_Admin_Global extends Engine_Form {
 
  public function init() {

    $this
            ->setTitle('General Settings')
            ->setDescription('These settings affect all members in your community.');

    $this->addElement('Text', 'sitegroupalbum_manifestUrl', array(
			'label' => 'Group Albums URL alternate text for "group-albums"',
			'allowEmpty' => false,
			'required' => true,
			'description' => 'Please enter the text below which you want to display in place of "groupalbums" in the URLs of this plugin.',
			'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroupalbum.manifestUrl', "group-albums"),
    ));
    
		$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $URL = $view->url(array('module' => 'seaocore', 'controller' => 'settings', 'action' => 'lightbox'), 'admin_default', true);
    $description = sprintf(Zend_Registry::get('Zend_Translate')->_('The settings for the Advanced Lightbox Viewer have been moved to the SocialEngineAddOns Core Plugin. Please %1svisit here%2s to see and configure these settings.'),
    "<a href='" . $URL ."' target='_blank'>", "</a>");
    $this->addElement('Dummy', 'sitegroupalbum_photolightbox_show', array(
        'label' => 'Photos Lightbox Viewer',
        'description' => $description,
    ));

    $this->getElement('sitegroupalbum_photolightbox_show')->getDecorator('Description')->setOptions(array('placement', 'APPEND', 'escape' => false));

    $this->addElement('Radio', 'sitegroupalbum_album_show_menu', array(
        'label' => 'Albums Link',
        'description' => 'Do you want to show the Albums link on Groups Navigation Menu? (You might want to show this if Albums from Groups are an important component on your website. This link will lead to a widgetized group listing all Group Albums, with a search form for Group Albums and multiple widgets.)',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroupalbum.album.show.menu', 1),
    ));

     // Order of group album group
    $this->addElement('Radio', 'sitegroupalbum_order', array(
        'label' => 'Default Ordering in Group Albums listing',
        'description' => 'Select the default ordering of albums in Group Albums listing. (This widgetized group will list all Group Albums. Sponsored albums are albums created by paid Groups.)',
        'multiOptions' => array(
            1 => 'All albums in descending order of creation.',
            2 => 'All albums in alphabetical order.',
            3 => 'Featured albums followed by others in descending order of creation.',
            4 => 'Sponsored albums followed by others in descending order of creation.(If you have enabled packages.)',
            5 => 'Featured albums followed by sponsored albums followed by others in descending order of creation.(If you have enabled packages.)',
            6 => 'Sponsored albums followed by featured albums followed by others in descending order of creation.(If you have enabled packages.)',
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroupalbum.order', 1),
    ));

    $this->addElement('Radio', 'sitegroupalbum_hide_autogenerated', array(
        'label' => 'Show / Hide Default Albums',
        'description' => "Do you want to show default albums of groups and their various extensions on your site which are created by the system automatically? (If you select No, then albums like 'Overview Photos', 'Note Photos', 'Discussion Photos', etc will not be displayed anywhere on the site.)",
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroupalbum.hide.autogenerated', 1),
    ));

    $this->addElement('Radio', 'sitegroupalbum_albumsorder', array(
        'label' => 'Choose Order',
        'description' => "Select the order below to display the albums on your site.",
        'multiOptions' => array(
            1 => 'Newer to older',
            0 => 'Older to newer'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroupalbum.albumsorder', 1),
    ));

    $this->addElement('Text', 'sitegroupalbum_truncation_limit', array(
        'label' => 'Title Truncation Limit',
        'allowEmpty' => false,
        'maxlength' => '3',
        'required' => true,
        'description' => 'What maximum limit should be applied to the number of characters in the title of items in the widgets? (Enter a number between 1 and 999. Titles having more characters than this limit will be truncated. Complete titles will be shown on mouseover.)',
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitegroupalbum.truncation.limit', 13),
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