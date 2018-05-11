<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license http://www.socialengineaddons.com/license/
 * @version $Id: Email.php 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author  SocialEngineAddOns
 */
class Sitebackup_Service_Email
{
  public function backup_store_on_email($backup_filepath, $filename_compressed_form, $getEmail)
  { 
    ini_set("memory_limit", "128M");
    set_time_limit(0);
    $data1 = array();
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $link = $backup_filepath;
    $size = @filesize($link);
    $max = 20971520;
    if( $size > $max ) {
      $view->translate("The backup file could not be emailed as attachment because its size is greater than 20 MB. Please choose a different destination for your backup.");
      return;
    }
    $siteName = Engine_Api::_()->getApi('settings','core')->core_general_site_title;
    $str = str_replace("+0000", "", date('Y-m-d H:i:s'));

    $subject = $view->translate("Database backup of $siteName on $str(GMT)");
    $translate = Zend_Registry::get('Zend_Translate');
    $user_message = $translate->_('_EMAIL_HEADER_BODY');
    $user_message .= $view->translate("\n\n The database backup of $siteName was successfully completed on $str(GMT).\n\n Please find the backup file attached with this email.");
    $to = $getEmail;
    $fileatt_type = $view->translate('application/x-gzip');
    $fileatt_name = $filename_compressed_form;
    $from = $view->translate('Site Admin') . '<' . Engine_Api::_()->getApi('settings', 'core')->core_mail_from . '>';
    $headers = $view->translate("From: ") . $from;
    $data = @file_get_contents($link);
    $semi_rand = md5(time());
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

    $headers .= "\nMIME-Version: 1.0\n" .
      "Content-Type: multipart/mixed;\n" .
      " boundary=\"{$mime_boundary}\"";

    $email_message = "This is a multi-part message in MIME format.\n\n" .
      "--{$mime_boundary}\n" .
      "Content-Type:text/html; charset=\"iso-8859-1\"\n" .
      "Content-Transfer-Encoding: 7bit\n\n" . $user_message . "\n\n";

    $data = chunk_split(base64_encode($data));

    $email_message .= "--{$mime_boundary}\n" .
      "Content-Type: {$fileatt_type};\n" .
      " name=\"{$fileatt_name}\"\n" .
      "Content-Transfer-Encoding: base64\n\n" .
      $data . "\n\n" .
      "--{$mime_boundary}--\n\n";

    $foter_message = $translate->_('_EMAIL_FOOTER_BODY');

    $email_message .= "This is a multi-part message in MIME format.\n\n" .
      "--{$mime_boundary}\n" .
      "Content-Type:text/html; charset=\"iso-8859-1\"\n" .
      "Content-Transfer-Encoding: 7bit\n\n" . $foter_message . "\n\n";

    $mail_sent = @mail($to, $subject, $email_message, $headers);

    if( $mail_sent ) {
      $data1['mail_sent'] = 1;
      $data1['msg'] = $view->translate('Email has been sent successfully.');
      $time_out = 7000;
      @unlink($link);
      $data1['no_form'] = 1;
      $data1['download'] = 0;
    } else {
      $is_error = 1;
      $error_array[] = $view->translate('There was an error in sending your email. Please try again later.');
      $time_out = 50000;
      Engine_Api::_()->sitebackup()->checkDatabaseConnection();
      $update = mysql_query("UPDATE `engine4_sitebackup_backuplogs` SET `status` = 'Fail' WHERE `engine4_sitebackup_backuplogs`.`filename` = '$filename_compressed_form' LIMIT 1;");
      $data1['no_form'] = 1;
      $data1['download'] = 1;
    }
  }

}
