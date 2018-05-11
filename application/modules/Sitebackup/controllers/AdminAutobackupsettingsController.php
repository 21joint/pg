<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package	Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license	http://www.socialengineaddons.com/license/
 * @version	$Id: AdminSettingsController.php 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author 	SocialEngineAddOns
 */
if( version_compare(PHP_VERSION, '7.0.0') >= 0 ) {
  include APPLICATION_PATH . '/application/modules/Sitebackup/mysqli.php';
}

class Sitebackup_AdminAutobackupsettingsController extends Core_Controller_Action_Admin
{
  public function indexAction()
  {
    include_once APPLICATION_PATH . '/application/modules/Sitebackup/Api/Core.php';

    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('sitebackup_admin_main', array(), 'sitebackup_admin_main_autobackupsettings');

    if( empty(Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailsender) ) {
      $core_mail_from = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.mail.from', ' ');
      Engine_Api::_()->getApi('settings', 'core')->setSetting('sitebackup.mailsender', $core_mail_from);
    }

    $this->view->form = $form = new Sitebackup_Form_Admin_Autobackupsettings();
    $session = new Zend_Session_Namespace();

    if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) ) {
      $values = $form->getValues();
      foreach( $values as $key => $value ) {
        Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
      }
      //Getting the timeperiod after this you want backup automatically.
      $timeperiod = Engine_Api::_()->getApi('settings', 'core')->sitebackup_dropdowntime;
      //Selecting a option of you want automatic backup or simple backup.
      $autobackupoption = Engine_Api::_()->getApi('settings', 'core')->sitebackup_backupoptions;
      $this->view->autobackupoption = $autobackupoption;
      $table = Engine_Api::_()->getDbtable('tasks', 'core');
      if( $autobackupoption == 1 ) {
        $enabled = 0;
      } else {
        $enabled = 1;
      }
      $coremodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('core');
      $coreversion = $coremodule->version;
      if( $coreversion < '4.1.0' ) {
        $table->update(array('enabled' => $enabled, 'timeout' => $timeperiod,), array('title = ?' => 'Background Automatic Backup', 'plugin = ?' => 'Sitebackup_Plugin_Task_Sitebackup'));
        $table->update(array('enabled' => $enabled, 'timeout' => $timeperiod,), array('title = ?' => 'Background Automatic Backup Maintenance', 'plugin = ?' => 'Sitebackup_Plugin_Task_Maintenance'));

        $table->update(array(
          'state' => 'dormant',
          'executing' => 0,
          'executing_id' => 0,
          ), array('plugin =?' => 'Sitebackup_Plugin_Task_Sitebackup'));
      } else {
        $table->update(array('processes' => $enabled, 'timeout' => $timeperiod,), array('title = ?' => 'Background Automatic Backup', 'plugin = ?' => 'Sitebackup_Plugin_Task_Sitebackup'));
        $table->update(array('processes' => $enabled, 'timeout' => $timeperiod,), array('title = ?' => 'Background Automatic Backup Maintenance', 'plugin = ?' => 'Sitebackup_Plugin_Task_Maintenance'));
      }
      $this->_helper->redirector->gotoRoute(array('route' => 'admin-default'));
      $replace_container_temp = false;
    }
    if( !empty($replace_container_temp) ) {
      $is_error = 1;
      $error = $replace_container_temp;

      $this->view->status = false;
      $error = Zend_Registry::get('Zend_Translate')->_($error);

      $form->getDecorator('errors')->setOption('escape', false);
      $form->addError($error);
      return;
    }

    $this->view->success = '';
    if( isset($session->autoBackup_save_msg) ) {
      unset($session->autoBackup_save_msg);
      $this->view->success = 'Your automatic database backup settings have been saved successfully. Please make sure that the time interval between automatic database backups selected by you is atleast more than 5 times the duration taken for a manual backup. Please refer to the FAQ section for a better understanding.';
    }
    //Selecting the automatic block yes or no.
    $autobackupoption = Engine_Api::_()->getApi('settings', 'core')->sitebackup_backupoptions;
    $this->view->autobackupoption = $autobackupoption;
  }

}
