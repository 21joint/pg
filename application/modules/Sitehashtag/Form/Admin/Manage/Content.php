<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    Content.php 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitehashtag_Form_Admin_Manage_Content extends Engine_Form
{
  public function init()
  {

    $this
      ->setTitle('Add Content Module')
      ->setDescription('Use the below form to add a content module, that can be used with Hashtags Plugin.');
    $modules_notInclude = array('sitemember', 'sitemenu', 'Sitecoupon', 'sitecontentcoverphoto',
      'siteeventdocument', 'sitereviewpaidlisting', 'siteevent', 'sitefaq', 'sitestaticpage',
      'sitereview', 'siteadvsearch', 'sitestoreurl', 'sitestoreintegration', 'sitestoremember',
      'sitestorebadge', 'sitestorediscussion', 'sitestorelikebox', 'sitestoreinvite',
      'sitestoreform', 'sitestoreadmincontact', 'sitegroupurl', 'sitegroupintegration',
      'sitegroupmember', 'sitepagemember', 'siteusercoverphoto', 'sitemobile', 'sitemobileapp',
      'sitemailtemplates', 'sitegroupbadge', 'sitegroupdiscussion', 'sitegrouplikebox',
      'sitegroupinvite', 'sitegroupform', 'sitegroupadmincontact', 'communityadsponsored',
      'sitevideoview', 'sitevideoview', 'sitetagcheckin', 'sitereviewlistingtype',
      'sitepageintegration', 'sitepageurl', 'forum', 'sitebusinessdiscussion', 'sitebusinesslikebox',
      'sitebusinessinvite', 'sitebusinessform', 'sitebusinessadmincontact', 'mapprofiletypelevel',
      'mcard', 'poke', 'sitealbum', 'sitepageinvite', 'siteslideshow', 'seaocore',
      'suggestion', 'userconnection', 'sitepageform', 'sitepageadmincontact', 'sitebusinessbadge',
      'sitepagebadge', 'featuredcontent', 'sitepagediscussion', 'sitepagelikebox',
      'mobi', 'advancedslideshow', 'birthday', 'birthdayemail', 'communityad', 'dbbackup',
      'facebookse', 'facebooksefeed', 'facebooksepage', 'feedback', 'sitelike', 'activity',
      'advancedactivity', 'album', 'blog', 'classified', 'document', 'event', 'poll',
      'video', 'list', 'group', 'music', 'recipe', 'user', 'groupdocument', 'grouppoll',
      'sitepage', 'sitepagenote', 'sitepagevideo', 'sitepagepoll', 'sitepagemusic',
      'sitepagealbum', 'sitepageevent', 'sitepagereview', 'sitepagedocument', 'sitepageoffer',
      'sitebusiness', 'sitebusinessnote', 'sitebusinessvideo', 'sitebusinesspoll',
      'sitebusinessmusic', 'sitebusinessalbum', 'sitebusinessevent', 'sitebusinessreview',
      'sitebusinessdocument', 'sitebusinessoffer', 'sitegroup', 'sitegroupnote',
      'sitegroupvideo', 'sitegrouppoll', 'sitegroupmusic', 'sitegroupalbum', 'sitegroupevent',
      'sitegroupreview', 'sitegroupdocument', 'sitegroupoffer', 'sitestore', 'sitestoreproduct',
      'sitestorevideo', 'sitestorealbum', 'sitestorereview', 'sitestoredocument',
      'sitestoreoffer', 'siteestore', 'eventdocument', 'sitehashtag', 'siteforum',
      'sitegateway', 'siteverify', 'spectacular', 'chat', 'nestedcomment', 'siteeventticket');
    $contentTable = Engine_Api::_()->getItemTable('sitehashtag_content');
    $select = $contentTable->select();
    $modules = $contentTable->fetchAll($select);
    foreach ($modules as $module) {
      $modules_notInclude = array_merge($modules_notInclude, array($module->module_name));
    }
    $module_table = Engine_Api::_()->getDbTable('modules', 'core');
    $moduleName = $module_table->info('name');
    $select = $module_table->select()
      ->from($moduleName, array('name', 'title'))
      ->where($moduleName . '.type =?', 'extra')
      ->where($moduleName . '.name not in(?)', $modules_notInclude)
      ->where($moduleName . '.enabled =?', 1);

    $moduleResults = $select->query()->fetchAll();
    $moduleArray = array();
    if (!empty($moduleResults)) {
      	$moduleArray[] = '';
      foreach ($moduleResults as $modules) {
        $contentItem = $this->getContentItem($modules['name']);
        if (empty($contentItem))
          continue;
        $moduleArray[$modules['name']] = $modules['title'];
      }
    }
    
    
    if (!empty($moduleArray)) {
      $this->addElement('Select', 'module_name', array(
        'label' => 'Content Module',
        'allowEmpty' => false,
        'onchange' => 'setModuleName(this.value)',
        'multiOptions' => $moduleArray,
      ));
      
      $module = Zend_Controller_Front::getInstance()->getRequest()->getParam('module_name', null);
    $contentItem = array();
    if (!empty($module)) {
      $this->module_name->setValue($module);
      $contentItem = $this->getContentItem($module);
      if (empty($contentItem))
        $this->addElement('Dummy', 'dummy_title', array(
            'description' => 'For this module, there is  no item defined in the manifest file.',

        ));
    }
    if (!empty ($contentItem)) {
      $this->addElement('Select', 'resource_type', array(
          'label' => 'Database Table Item',
          'description' => "This is the value of 'items' key in the manifest file of this plugin. To view this value for a desired module, go to the directory of this module, and open the file 'settings/manifest.php'. In this file, search for 'items', and view its value. [Ex in case of blog module: Open file 'application/modules/Blog/settings/manifest.php', and go to around line 62. You will see the 'items' key array with value 'blog'. Thus, the Database Table Item for blog module is: 'blog']",
        //  'required' => true,
          'multiOptions' => $contentItem,
      ));
    
      // Element: execute
      $this->addElement('Button', 'execute', array(
        'label' => 'Add',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array('ViewHelper'),
      ));

      // Element: cancel
      $this->addElement('Cancel', 'cancel', array(
        'label' => 'cancel',
        'prependText' => ' or ',
        'ignore' => true,
        'link' => true,
        'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array(
          'module' => 'sitehashtag', 'controller' => 'manage'), 'admin_default', true),
        'decorators' => array('ViewHelper'),
      ));
      $this->addDisplayGroup(array('execute', 'cancel'), 'buttons');
      $this->getDisplayGroup('buttons');
    } 
    
    } else {
      $description = "<div class='tip'><span>" . "There are no modules available on your website that can be used with Hashtags Plugin." . "</span></div>";
      $this->addElement('Dummy', 'module', array(
        'description' => $description,
      ));
      $this->module->addDecorator('Description', array('placement' =>
        Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
    }
    
  }

  public function getContentItem($moduleName)
  {

    $file_path = APPLICATION_PATH . "/application/modules/" . ucfirst($moduleName) . "/settings/manifest.php";
    $items = array();
    if (@file_exists($file_path)) {
      $include_file = include $file_path;
      if (isset($include_file['items'])) {

        foreach ($include_file['items'] as $item)
          $items[$item] = $item . " ";
      }
    }
    return $items;
  }

}
