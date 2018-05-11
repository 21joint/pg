<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminSettingsController.php 2014-10-09 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteluminous_AdminSettingsController extends Core_Controller_Action_Admin {

  public function __call($method, $params) {
    /*
     * YOU MAY DISPLAY ANY ERROR MESSAGE USING FORM OBJECT.
     * YOU MAY EXECUTE ANY SCRIPT, WHICH YOU WANT TO EXECUTE ON FORM SUBMIT.
     * REMEMBER:
     *    RETURN TRUE: IF YOU DO NOT WANT TO STOP EXECUTION.
     *    RETURN FALSE: IF YOU WANT TO STOP EXECUTION.
     */

    if ( !empty($method) && $method == 'Siteluminous_Form_Admin_Htmlblock' ) {
      
    }
    return true;
  }

  public function indexAction() {

    $pluginName = 'siteluminous';
    if ( !empty($_POST[$pluginName . '_lsettings']) )
      $_POST[$pluginName . '_lsettings'] = @trim($_POST[$pluginName . '_lsettings']);

    $tempLanguageDataArray = array();
    if ( $this->getRequest()->isPost() ) {
      //WORK FOR MULTILANGUAGES START
      $localeMultiOptions = Engine_Api::_()->siteluminous()->getLanguageArray();
      $coreSettings = Engine_Api::_()->getApi('settings', 'core');
      $defaultLanguage = $coreSettings->getSetting('core.locale.locale', 'en');
      $total_allowed_languages = Count($localeMultiOptions);

      if ( !empty($localeMultiOptions) ) {
        foreach ( $localeMultiOptions as $key => $label ) {
          $lang_name = $label;
          if ( isset($localeMultiOptions[$label]) ) {
            $lang_name = $localeMultiOptions[$label];
          }

          $page_block_field = "siteluminous_lending_page_block_$key";
          if ( $total_allowed_languages <= 1 ) {
            $page_block_field = "siteluminous_lending_page_block";
            $page_block_label = "Landing Page Block";
          } elseif ( $label == 'en' && $total_allowed_languages > 1 ) {
            $page_block_field = "siteluminous_lending_page_block";
          }
          
          if(!strstr($key, '_')){
              $key = $key.'_default';
          }

          $tempLanguageDataArray[$key] = @base64_encode($_POST[$page_block_field]);
        }

        Engine_Api::_()->getApi('settings', 'core')->setSetting('siteluminous.lending.block.languages', $tempLanguageDataArray);
      }
      //WORK FOR MULTILANGUAGES END
    }

    $this->view->isModsSupport = Engine_Api::_()->siteluminous()->isModulesSupport();
    include_once APPLICATION_PATH . '/application/modules/Siteluminous/controllers/license/license1.php';
  }

  public function imagesAction() {
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('siteluminous_admin_main', array(), 'siteluminous_admin_settings_images');

    include_once APPLICATION_PATH . '/application/modules/Siteluminous/controllers/license/license2.php';
  }

  public function orderAction() {
    if ( !empty($_POST) ) {
      foreach ( $_POST as $key => $value ) {
        if ( strstr($key, "content_") ) {
          $keyArray = explode("content_", $key);

          if ( !empty($keyArray) )
            $image_id = end($keyArray);

          if ( !empty($image_id) ) {
            $obj = Engine_Api::_()->getItem('siteluminous_image', $image_id);
            $obj->order = $value;
            $obj->save();
          }
        }
      }
    }
  }

  public function addImagesAction() {
    $this->view->form = $form = new Siteluminous_Form_Admin_Images_Add();
    $table = Engine_Api::_()->getItemTable('siteluminous_image');
    //CHECK POST
    if ( !$this->getRequest()->isPost() ) {
      return;
    }

    //CHECK VALIDITY
    if ( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

    //PROCESS
    $values = $form->getValues();

    $db = $table->getAdapter();
    $db->beginTransaction();
    try {
      include_once APPLICATION_PATH . '/application/modules/Siteluminous/controllers/license/license2.php';
      //COMMIT
      $db->commit();
      return $this->_forward('success', 'utility', 'core', array(
                  'smoothboxClose' => true,
                  'parentRefresh' => true,
                  'messages' => array(Zend_Registry::get('Zend_Translate')->_('Images successfully add.'))
      ));
    } catch ( Exception $e ) {
      $db->rollBack();
      throw $e;
    }
  }

  public function editImagesAction() {

    $id = $this->_getParam('id');

    $this->view->item = $item = Engine_Api::_()->getItem('siteluminous_image', $id);
    $this->view->form = $form = new Siteluminous_Form_Admin_Images_Edit();
    $form->populate($item->toarray());

    //CHECK POST
    if ( !$this->getRequest()->isPost() ) {
      return;
    }

    //CHECK VALIDITY
    if ( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

    //PROCESS
    $values = $form->getValues();
    $table = Engine_Api::_()->getItemTable('siteluminous_image');

    $db = $table->getAdapter();
    $db->beginTransaction();
    try {
      // Delete Previous Icon
      if ( !empty($item->icon_id) ) {
        $file = Engine_Api::_()->getItem('storage_file', $item->icon_id);
        if ( !empty($file) )
          $file->delete();
      }

      // Delete Previous File
      if ( !empty($item->file_id) ) {
        $file = Engine_Api::_()->getItem('storage_file', $item->file_id);
        if ( !empty($file) )
          $file->delete();
      }

      //SET PERMISSION
      include_once APPLICATION_PATH . '/application/modules/Siteluminous/controllers/license/license2.php';

      //COMMIT
      $db->commit();
      //LAYOUT
      if ( null === $this->_helper->ajaxContext->getCurrentContext() ) {
        $this->_helper->layout->setLayout('default-simple');
      } else {
        $this->_helper->layout->disableLayout(true);
      }
      return $this->_forward('success', 'utility', 'core', array(
                  'smoothboxClose' => true,
                  'parentRefresh' => true,
                  'smoothboxClose' => 1000,
                  'parentRefresh' => 1000,
                  'messages' => array(Zend_Registry::get('Zend_Translate')->_('Changes saved successfully.'))
      ));
    } catch ( Exception $e ) {
      $db->rollBack();
      throw $e;
    }
  }

  public function deleteAction() {

    $this->_helper->layout->setLayout('admin-simple');

    $this->view->id = $id = $this->_getParam('id');

    if ( $this->getRequest()->isPost() ) {
      $item = Engine_Api::_()->getItem('siteluminous_image', $id);

      $item->delete();
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('Deleted Succesfully.')
      ));
    }
  }

  public function enabledAction() {
    $id = $this->_getParam('id');
    if ( !empty($id) ) {
      $item = Engine_Api::_()->getItem('siteluminous_image', $id);
      $item->enabled = !$item->enabled;
      $item->save();
    }

    $this->_redirect('admin/siteluminous/settings/images');
  }

  public function faqAction() {
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('siteluminous_admin_main', array(), 'siteluminous_admin_settings_faq');
  }

  public function footerMenuAction() {
    $this->_redirect('admin/menus/index?name=siteluminous_footer');
  }

  public function placeHtaccessFileAction() {
    if ( $this->getRequest()->isPost() ) {
      $successfullyAdded = false;
      $getFileContent = '<FilesMatch ".(ttf|otf|woff)$">
    Header set Access-Control-Allow-Origin "*"
</FilesMatch>';

      $global_directory_name = APPLICATION_PATH . '/application/themes/luminous';
      $global_settings_file = $global_directory_name . '/.htaccess';
      $is_file_exist = @file_exists($global_settings_file);

      // IF FILE NOT EXIST THEN CREATE NEW .HTACCESS FILE THERE.
      if ( empty($is_file_exist) ) {
        if ( is_dir($global_directory_name) ) {
          @mkdir($global_directory_name, 0777);

          $fh = @fopen($global_settings_file, 'w') or die('Unable to create .htaccess file; please give the CHMOD 777 recursive permission to the directory "' . APPLICATION_PATH . '/application/themes/luminous' . '" and then try again.');
          @fwrite($fh, $getFileContent);
          @fclose($fh);

          @chmod($global_settings_file, 0777);
          $successfullyAdded = true;
        }
      } else {
        if ( !is_writable($global_settings_file) ) {
          @chmod($global_settings_file, 0777);
          if ( !is_writable($global_settings_file) ) {
            $form->addError('Unable to create .htaccess file; please give the CHMOD 777 recursive permission to the directory "' . APPLICATION_PATH . '/application/themes/luminous' . '" and then try again.');
            return;
          }
        }
        $successfullyAdded = @file_put_contents($global_settings_file, $getFileContent);
      }

      if ( !empty($successfullyAdded) ) {
        $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => 10,
            'parentRefresh' => 10,
            'messages' => array('File Succesfully Created.')
        ));
      }
    }
  }

  public function placeCustomizationFileAction() {
    if ( $this->getRequest()->isPost() ) {
      $global_directory_name = APPLICATION_PATH . '/application/themes/luminous';
      @chmod($global_directory_name, 0777);

      if ( !is_readable($global_directory_name) ) {
        $this->view->error_message = "<span style='color:red'>Note: You do not have readable permission on the path below, please give 'chmod 777 recursive permission' on it to continue with the installation process : <br /> 
Path Name: <b>" . $global_directory_name . "</b></span>";
        return;
      }

      $global_settings_file = $global_directory_name . '/customization.css';
      $is_file_exist = @file_exists($global_settings_file);
      if ( empty($is_file_exist) ) {
        @chmod($global_directory_name, 0777);
        if ( !is_writable($global_directory_name) ) {
          $this->view->error_message = "<span style='color:red'>Note: You do not have writable permission on the path below, please give 'chmod 777 recursive permission' on it to continue with the installation process : <br /> 
Path Name: " . $global_directory_name . "</span>";
          return;
        }

        $fh = @fopen($global_settings_file, 'w');
        @fwrite($fh, '/* ADD CUSTOM STYLE */');
        @fclose($fh);

        @chmod($global_settings_file, 0777);
      }

      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('File Succesfully Created.')
      ));
    }
  }

}
