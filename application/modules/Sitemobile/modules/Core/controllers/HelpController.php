<?php

/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: HelpController.php 9800 2012-10-17 01:16:09Z richard $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Core_HelpController extends Core_Controller_Action_Standard {

  public function contactAction() {
    // Render
    $this->_helper->content
            //->setNoRender()
            ->setEnabled()
    ;

    $translate = Zend_Registry::get('Zend_Translate');
    $this->view->form = $form = new Sitemobile_modules_Core_Form_Contact();

    if (!$this->getRequest()->isPost() || !$form->isValid($this->getRequest()->getPost())) {
      if (Engine_Api::_()->sitemobile()->isApp()) {
        Zend_Registry::set('setFixedCreationForm', true);
        Zend_Registry::set('setFixedCreationHeaderTitle', $form->getTitle());
        Zend_Registry::set('setFixedCreationHeaderSubmit', 'Send');
        $this->view->form->setAttrib('id', 'form_core_contact');
        Zend_Registry::set('setFixedCreationFormId', '#form_core_contact');
        $this->view->form->removeElement('submit');
        $form->setTitle('');
      }
      return;
    }


    // Success! Process
    // Mail gets logged into database, so perform try/catch in this Controller
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      // the contact form is emailed to the first SuperAdmin by default
      $users_table = Engine_Api::_()->getDbtable('users', 'user');
      $users_select = $users_table->select()
              ->where('level_id = ?', 1)
              ->where('enabled >= ?', 1);
      $super_admin = $users_table->fetchRow($users_select);
      $adminEmail = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.mail.contact');
      if (!$adminEmail) {
        $adminEmail = $super_admin->email;
      }

      $viewer = Engine_Api::_()->user()->getViewer();

      $values = $form->getValues();

      // Check for error report
      $error_report = '';
      $name = $this->_getParam('name');
      $loc = $this->_getParam('loc');
      $time = $this->_getParam('time');
      if ($name && $loc && $time) {
        $error_report .= "\r\n";
        $error_report .= "\r\n";
        $error_report .= "-------------------------------------";
        $error_report .= "\r\n";
        $error_report .= $this->view->translate('The following information about an error was included with this message:');
        $error_report .= "\r\n";
        $error_report .= $this->view->translate('Exception: ') . base64_decode(urldecode($name));
        $error_report .= "\r\n";
        $error_report .= $this->view->translate('Location: ') . base64_decode(urldecode($loc));
        $error_report .= "\r\n";
        $error_report .= $this->view->translate('Time: ') . date('c', base64_decode(urldecode($time)));
        $error_report .= "\r\n";
      }

      // Make params
      $mail_settings = array(
          'host' => $_SERVER['HTTP_HOST'],
          'email' => $adminEmail,
          'date' => time(),
          'recipient_title' => $super_admin->getTitle(),
          'recipient_link' => $super_admin->getHref(),
          'recipient_photo' => $super_admin->getPhotoUrl('thumb.icon'),
          'sender_title' => $values['name'],
          'sender_email' => $values['email'],
          'message' => $values['body'],
          'error_report' => $error_report,
      );

      if ($viewer && $viewer->getIdentity()) {
        $mail_settings['sender_title'] .= ' (' . $viewer->getTitle() . ')';
        $mail_settings['sender_email'] .= ' (' . $viewer->email . ')';
        $mail_settings['sender_link'] = $viewer->getHref();
      }

      // send email
      Engine_Api::_()->getApi('mail', 'core')->sendSystem(
              $adminEmail, 'core_contact', $mail_settings
      );

      // if the above did not throw an exception, it succeeded
      $db->commit();
      $this->view->status = true;
      $this->view->message = $translate->_('Thank you for contacting us!');
    } catch (Zend_Mail_Transport_Exception $e) {
      $db->rollBack();
      throw $e;
    }
  }

  public function termsAction() {
    // to change, edit language variable "_CORE_TERMS_OF_SERVICE"
    // Render
    $this->_helper->content
            //->setNoRender()
            ->setEnabled()
    ;
    
      if (Engine_Api::_()->sitemobile()->isApp() && stripos($_SERVER['HTTP_USER_AGENT'], "iOS")) {
            Zend_Registry::set('setFixedCreationFormBack', 'Back');
    }
  }

  public function privacyAction() {
    // to change, edit language variable "_CORE_PRIVACY_STATEMENT"
    // Render
    $this->_helper->content
            //->setNoRender()
            ->setEnabled()
    ;
  }

}