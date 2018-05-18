<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license http://www.socialengineaddons.com/license/
 * @version $Id: Sitebackup.php 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author  SocialEngineAddOns
 */
if( version_compare(PHP_VERSION, '7.0.0') >= 0 ) {
  include APPLICATION_PATH . '/application/modules/Sitebackup/mysqli.php';
}

class Sitebackup_Plugin_Task_Sitebackup extends Core_Plugin_Task_Abstract
{

  protected $database;

  public function execute()
  {
    $siteName = Engine_Api::_()->getApi('settings','core')->core_general_site_title;
    $autobackup = Engine_Api::_()->getApi('settings', 'core')->sitebackup_backupoptions;
    if( empty($autobackup) ) {
      // fetch that time stamp when the reminder mail was last sent
      $taskstable = Engine_Api::_()->getDbtable('tasks', 'core');
      $rtasksName = $taskstable->info('name');
      $taskstable_result = $taskstable->select()
        ->from($rtasksName, array('started_last'))
        ->where('title = ?', 'Background Automatic Backup')
        ->where('plugin = ?', 'Sitebackup_Plugin_Task_Sitebackup')
        ->limit(1);

      $value = $taskstable->fetchRow($taskstable_result);
      $old_started_last = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitebackup.startedlast', 0);

      $coremodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('core');
      $coreversion = $coremodule->version;
      if( ($coreversion < '4.1.0' ) ) {
        if( !Engine_Api::_()->sitebackup()->canRunTask("sitebackup", "Sitebackup_Plugin_Task_Sitebackup", $old_started_last) ) {
          return;
        }
      }
      Engine_Api::_()->getApi('settings', 'core')->setSetting('sitebackup_startedlast', $value['started_last']);

      $dir_name_temp = Engine_Api::_()->getApi('settings', 'core')->sitebackup_directoryname;
      $backup_file = APPLICATION_PATH . '/public/' . $dir_name_temp;

      $fileAdapter = APPLICATION_PATH . '/application/settings/database.php';
      $fileAdapterinfo = include $fileAdapter;

      //Setting the selected table into the session.
      $session = new Zend_Session_Namespace('backup');

      $session->fileadapter = $fileAdapterinfo['adapter'];
      $code_destination = '';
      if( !is_dir($backup_file) && mkdir($backup_file, 0777, true) ) {
        $table = Engine_Api::_()->getDbtable('backupauthentications', 'sitebackup');
        $select = $table->select();
        $row = $table->fetchRow($select);
        $htusername = $row->htpassword_username;
        $htpassword = $row->htpassword_password;
        $backup_enable = $row->htpasswd_enable;
        $htpasswd_text = "$htusername:" . crypt($htpassword) . "";
        $backup_file1 = APPLICATION_PATH . '/public/' . $dir_name_temp . '/.htpasswd';
        $fp = fopen($backup_file1, 'w');
        fwrite($fp, $htpasswd_text);
        fclose($fp);

        if( $backup_enable == 1 ) {
          $authtication_name_store = $dir_name_temp;
          $authtication_path = APPLICATION_PATH . '/public/' . $dir_name_temp . '/.htpasswd';
          $format = "AuthType Basic \n AuthName  $authtication_name_store \n AuthUserFile $authtication_path \n Require valid-user";
          $backupfilepath = APPLICATION_PATH . '/public/' . $dir_name_temp . '/.htaccess';
          $fp = fopen($backupfilepath, 'w');
          fwrite($fp, $format);
          fclose($fp);
        }

        $password_check_format = "Congratulations! Your backup directory is PASSWORD PROTECTED.\n\nBackups provide insurance for $siteName. In the event that something on $siteName's content with the most recent backup file.\n\n **********   Website Backup and Restore Plugin by SocialEngineAddOns (http://www.socialengineaddons.com)   **********";
        $password_check_path = APPLICATION_PATH . '/public/' . $dir_name_temp . '/password_check.txt';
        $fp = fopen($password_check_path, 'w');
        fwrite($fp, $password_check_format);
        fclose($fp);
      }
      $mailFlage = 0;

      $sitebackup_filename = Engine_Api::_()->getApi('settings', 'core')->sitebackup_autofilename;
      $databaseflage = 1;

      $backupLog_Table = Engine_Api::_()->getDbtable('backuplogs', 'sitebackup');
      $log_values = array();

      $lockoption = Engine_Api::_()->getApi('settings', 'core')->sitebackup_lockoptions;
      $destination_id = Engine_Api::_()->getApi('settings', 'core')->sitebackup_destinations;
      $destinationlocation_options = Engine_Api::_()->getApi('settings', 'core')->sitebackup_locationoptions;
      $result = Engine_Api::_()->getItem('destinations', $destination_id);

      $mysqlPath = '';
      // GET MYSQL DIRECTORY PATH IF MYSQL PATH IS NOT ADDED AS SYSTEM VARIABLES PATH
      if( empty(exec('which mysqldump')) ) {
        $mysqlPath = Engine_Api::_()->sitebackup()->getMysqlDirectoryPath();
      }
      if( $destination_id != 0 ) {

        if( $result->destination_mode == 3 ) {
          $log_values = array();
          $method = 'Database';
          $log_values = array_merge($log_values, array(
            'type' => 'Database',
            'method' => 'Automatic',
            'destination_name' => $result->destinationname,
            'destination_method' => 'Database',
            'filename' => 'N.A.',
            'start_time' => date('Y-m-d H:i:s'),
            'status' => 'Fail'
          ));
          $backuplog_id = $backupLog_Table->setLog($log_values);

          if( $lockoption ) {
            Engine_Api::_()->sitebackup->lockDatabse();
          }
          $databaseList = $result->toarray();
          $link = mysql_connect($databaseList['dbhost'], $databaseList['dbuser'], $databaseList['dbpassword']);
          if( !$link ) {
            $subject = "The automatic backup scheduled for $siteName failed at:" . date('Y-m-d H:i:s') . '.';
            $message = "The automatic backup scheduled for $siteName failed at:" . date('Y-m-d H:i:s') . '.';
            $reason = 'Reason: Could not connect to database:' . mysql_error();

            if( Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailoption ) {
              $mails = Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailsender;
              $mail = explode(',', $mails);

              foreach( $mail as $mail_id ) {
                $email = Engine_Api::_()->getApi('settings', 'core')->core_mail_from;
                Engine_Api::_()->getApi('mail', 'core')->sendSystem($mail_id, 'Backup_Failure_Email_Notification', array(
                  'subject' => $subject,
                  'message' => $message,
                  'reason' => $reason,
                  'backup_type' => 'Database',
                  'moremessage' => '------',
                  'email' => $email,
                  'queue' => false
                ));
              }
            }
            die('Could not connect to database: ' . mysql_error());
          }
          $databaseList['con'] = $link;
          // make foo the current db
          $db_selected = mysql_select_db($databaseList['dbname'], $link);
          if( !$db_selected ) {
            $subject = "The automatic backup scheduled for $siteName failed at:" . date('Y-m-d H:i:s') . '.';
            $message = "The automatic backup scheduled for $siteName failed at:" . date('Y-m-d H:i:s') . '.';
            $reason = 'Reason: Could not connect to database:' . mysql_error();

            if( Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailoption ) {
              $mails = Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailsender;
              $mail = explode(',', $mails);

              foreach( $mail as $mail_id ) {
                $email = Engine_Api::_()->getApi('settings', 'core')->core_mail_from;
                Engine_Api::_()->getApi('mail', 'core')->sendSystem($mail_id, 'Backup_Failure_Email_Notification', array(
                  'subject' => $subject,
                  'message' => $message,
                  'backup_type' => 'Database',
                  'reason' => $reason,
                  'moremessage' => '---------',
                  'email' => $email,
                  'queue' => false
                ));
              }
            }
            die('Could not connect to database: ' . mysql_error());
          }

          set_time_limit(0);
          $initial_code = 0;
          try {
            $string = '';
            $dbinfo = Engine_Db_Table::getDefaultAdapter()->getConfig();
            $dbname = $dbinfo['dbname'];

            // Command for copying data from one databse to another
            $command = $mysqlPath . 'mysqldump -h ' . $dbinfo['host'] . ' -u ' . $dbinfo['username'] . ' -p' . $dbinfo['password'] . ' ' . $dbname . ' ';
            // $command .= implode(' ', $table_name);
            $command .= '| ' . $mysqlPath . 'mysql -h ' . $databaseList['dbhost'] . ' -u ' . $databaseList['dbuser'] . ' -p' . $databaseList['dbpassword'] . ' ' . $databaseList['dbname'];
            if( !exec($command) ) {
              $mailFlage = 1;
            }
          } catch( Exception $e ) {

            $subject = "The automatic backup scheduled for $siteName failed at:" . date('Y-m-d H:i:s') . '.';
            $message = "The automatic backup scheduled for $siteName failed at:" . date('Y-m-d H:i:s') . '.';
            $reason = "Reason: Database to database backup failed.";

            if( Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailoption ) {
              $mails = Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailsender;
              $mail = explode(',', $mails);
              $backup_type = 'Database';
              foreach( $mail as $mail_id ) {
                $email = Engine_Api::_()->getApi('settings', 'core')->core_mail_from;
                Engine_Api::_()->getApi('mail', 'core')->sendSystem($mail_id, 'Backup_Failure_Email_Notification', array(
                  'subject' => $subject,
                  'message' => $message,
                  'backup_type' => $backup_type,
                  'reason' => $reason,
                  'moremessage' => '-------',
                  'email' => $email,
                  'queue' => false
                ));
              }
            }
          }

          $databaseflage = 0;
        }
      }

      if( $databaseflage ) {
        if( $lockoption ) {
          $result_tables = Engine_Api::_()->sitebackup()->fetchtables();
          foreach( $result_tables as $value ) {
            $table_name = $value['Tables_in_' . $dbname];
            $table_selected['tables'][]['Tables_in_' . $dbname] = $table_name;
          }
          Engine_Api::_()->sitebackup()->lockDatabse();
        }

        $dbinfo = Engine_Db_Table::getDefaultAdapter()->getConfig();
        $dbname = $dbinfo['dbname'];
        $dbhost = $dbinfo['host'];
        $dbuser = $dbinfo['username'];
        $dbpass = $dbinfo['password'];
        set_time_limit(0);
        $initial_code = 0;
        $string = '--';
        $generated_date = new Zend_Date();
        $filename_compressed_form = $sitebackup_filename . '_database_' . date("Y_m_d_H_i_s", time()) . '.sql.gz';
        $log_values = array();

        if( !empty($destination_id) ) {
          if( $result->destination_mode == 1 ) {
            $method = 'Email';
            $destination_name = $result->destinationname;
          } else if( $result->destination_mode == 2 ) {
            $method = 'FTP';
            $destination_name = $result->destinationname;
          } else if( $result->destination_mode == 4 ) {
            $method = 'Amazon S3';
            $destination_name = $result->destinationname;
          } else if( $result->destination_mode == 5 ) {
            $method = 'Google Drive';
            $destination_name = $result->destinationname;
          } else if( $result->destination_mode == 6 ) {
            $method = 'Dropbox';
            $destination_name = $result->destinationname;
          } else if( $result->destination_mode == 0 ) {
            $method = 'Server Backup Directory & Download';
            $destination_name = $result->destinationname;
          }
        } else {
          $method = 'Download';
          $destination_name = 'Download to computer';
        }

        $log_values = array_merge($log_values, array(
          'type' => 'Database',
          'method' => 'Automatic',
          'destination_name' => $destination_name,
          'destination_method' => $method,
          'filename' => $filename_compressed_form,
          'start_time' => date('Y-m-d H:i:s'),
          'status' => 'Fail'
        ));
        $backuplog_id = $backupLog_Table->setLog($log_values);

        $dir_name_temp = Engine_Api::_()->getApi('settings', 'core')->sitebackup_directoryname;
        $backup_filepath = APPLICATION_PATH . '/public/' . $dir_name_temp . '/' . $filename_compressed_form;
        $archiveFileName = $backup_filepath;
        $table_selected = Engine_Api::_()->sitebackup()->fetchtables();
        $num_selected_table = count($table_selected);
        $string = '';
        try {
          while( ($num_selected_table - 1 >= $initial_code ) ) {
            $table_name = $table_selected[$initial_code]['Tables_in_' . $dbname];
            $table_names[] = $table_name;
            $initial_code++;
          }
          //COMMAND FOR EXPORT THE DATABASE BEFORE THAT CHANGE THE DIRECTORY
          $command = $mysqlPath . 'mysqldump -h ' . $dbhost . ' -u ' . $dbuser . ' -p' . $dbpass . ' ' . $dbname . ' ';
          $command .= implode(' ', $table_names);
          $command .= ' | gzip > ' . $archiveFileName;
          exec($command);

          $mailFlage = 1;
        } catch( Exception $e ) {
          
        }
      }

      //Here we inserting into the database value of code related row.
      if( !empty($destination_id) ) {
        if( $result->destination_mode != 3 ) {
          $filesize_temp = filesize($backup_filepath);
          $filesize = round($filesize_temp / 1048576, 3) . ' Mb';
        } else {
          $filesize = '-';
        }
        try {
          $db = Engine_Db_Table::getDefaultAdapter();
          $db->beginTransaction();
          $table = Engine_Api::_()->getDbTable('backups', 'sitebackup');
          $backup_time = time();
          $row = $table->createRow();
          $database_filesize = $row->backup_filesize = $filesize;
          $row->backup_time = $backup_time;

          if( !empty($destination_id) ) {
            if( $result->destination_mode == 1 ) {
              $row->backup_method = 'Email';
              $row->destination_name = $result->destinationname;
            } else if( $result->destination_mode == 2 ) {
              $row->backup_method = 'FTP';
              $row->destination_name = $result->destinationname;
            } else if( $result->destination_mode == 3 ) {
              $row->backup_method = 'Database';
              $row->destination_name = $result->destinationname;
            } else if( $result->destination_mode == 4 ) {
              $row->backup_method = 'Amazon S3';
              $row->destination_name = $result->destinationname;
            } else if( $result->destination_mode == 5 ) {
              $row->backup_method = 'Google Drive';
              $row->destination_name = $result->destinationname;
            } else if( $result->destination_mode == 6 ) {
              $row->backup_method = 'Dropbox';
              $row->destination_name = $result->destinationname;
            } else if( $result->destination_mode == 0 ) {
              $row->backup_method = 'Server Backup Directory & Download';
              $row->destination_name = $result->destinationname;
            }
            $database_destination = $row->destination_name;
          }

          $row->backup_timedescription = date('r');
          if( $result->destination_mode != 3 ) {
            $row->backup_filename = $filename_compressed_form;
          } else {
            $row->backup_filename = '-';
          }
          $database_filename = $row->backup_filename;
          $row->backup_status = 1;
          if( !empty($result->destination_mode) ) {
            $row->backup_codemethod = 3;
          } else {
            $row->backup_codemethod = 1;
          }
          $row->backup_auto = 1;
          $id = $row->save();
          $db->commit();

          $log_values = array();
          if( $result->destination_mode !== 3 ) {
            $log_values = array_merge($log_values, array(
              'filename' => $filename_compressed_form,
              'size' => $filesize,
              'end_time' => date('Y-m-d H:i:s'),
              'status' => 'Success',
            ));

            $backupLog_Table->updateLog($log_values);
          } else {
            $log_values = array_merge($log_values, array(
              'backuplog_id' => $backuplog_id,
              'size' => 0,
              'end_time' => date('Y-m-d H:i:s'),
              'status' => 'Success',
            ));

            $backupLog_Table->updateLog($log_values);
          }
        } catch( Exception $e ) {
          $db->rollBack();
          throw $e;
        }

        if( $lockoption ) {
          Engine_Api::_()->sitebackup()->unlockDatabse();
        }
      }

      if( $destination_id != 0 ) {
        $config = Engine_Api::_()->getDbtable('destinations', 'sitebackup')->getConfigData($destination_id);
        if( $result->destination_mode == 1 ) {

          set_time_limit(0);
          $download = 0;
          $link = $backup_filepath;
          $size = filesize($link);
          $max = 20971520;
          if( $size > $max ) {
            $subject = "The automatic backup scheduled for $siteName failed at:" . date('Y-m-d H:i:s') . '.';
            $message = "The automatic backup scheduled for $siteName failed at:" . date('Y-m-d H:i:s') . '.';
            $reason = "Reason: The backup file could not be emailed as attachment because it is larger than 20 MB in size.";
            $moremessage = "This backup file has been saved in the Backup Directory on your server. You may download it from Database Backups listing page.";

            if( Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailoption ) {
              $mails = Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailsender;
              $mail = explode(',', $mails);
              $backup_type = 'Backup Type: Database';
              foreach( $mail as $mail_id ) {
                $email = Engine_Api::_()->getApi('settings', 'core')->core_mail_from;
                Engine_Api::_()->getApi('mail', 'core')->sendSystem($mail_id, 'Backup_Failure_Email_Notification', array(
                  'subject' => $subject,
                  'message' => $message,
                  'backup_type' => $backup_type,
                  'reason' => $reason,
                  'moremessage' => $moremessage,
                  'email' => $email,
                  'queue' => false
                ));
              }
            }

            return;
          }
          $date = date('r');
          $str = str_replace("+0000", "", $date);
          $subject = "Automatic Database backup of $siteName on $str";
          $translate = Zend_Registry::get('Zend_Translate');
          $user_message = $translate->_('_EMAIL_HEADER_BODY');
          $user_message .= "\n\nThe automatic database backup of $siteName was successfully completed on $str.\n\nPlease find the backup file attached with this email.";
          $to = $result->email;
          $fileatt_type = 'application/x-gzip';
          $fileatt_name = $filename_compressed_form;
          $from = 'Site Admin' . '<' . Engine_Api::_()->getApi('settings', 'core')->core_mail_from . '>';
          $headers = "From: " . $from;
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
            "--{$mime_boundary}--\n";

          $mail_sent = mail($to, $subject, $email_message, $headers);
          if( $mail_sent ) {
            $mailFlage = 1;
            unlink($link);

            $view_msg = 'Email has been sent successfully.';
            $time_out = 7000;
            $no_form = 1;
          } else {
            $mailFlage = 0;
            $is_error = 1;

            $table = Engine_Api::_()->getDbTable('backups', 'sitebackup');
            $table->update(array('backup_status' => 0), array('backup_id = ?' => $id));
            $subject = "The automatic backup scheduled for $siteName failed at:" . date('Y-m-d H:i:s') . '.';
            $message = "The automatic backup scheduled for $siteName failed at:" . date('Y-m-d H:i:s') . '.';
            $reason = "Reason: There was an error in sending the email with backup attachment.";
            $moremessage = "This backup file has been saved in the Backup Directory on your server. You may download it from Database Backups listing page.";
            $backup_type = 'Backup Type: Database';
            if( Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailoption ) {
              $mails = Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailsender;
              $mail = explode(',', $mails);
              $backup_type = 'Backup Type: Database';
              foreach( $mail as $mail_id ) {
                $email = Engine_Api::_()->getApi('settings', 'core')->core_mail_from;
                Engine_Api::_()->getApi('mail', 'core')->sendSystem($mail_id, 'Backup_Failure_Email_Notification', array(
                  'subject' => $subject,
                  'message' => $message,
                  'backup_type' => $backup_type,
                  'reason' => $reason,
                  'moremessage' => $moremessage,
                  'email' => $email,
                  'queue' => false
                ));
              }
            }
            $error_array[] = 'There was an error in sending your email. Please try again later.';
            $time_out = 50000;
            $no_form = 1;
            $table = Engine_Api::_()->getDbTable('backups', 'sitebackup');
            $table->update(array('backup_status' => 0), array('backup_id = ?' => $id));
          }
        } else if( $result->destination_mode == 2 ) {
          set_time_limit(0);
          $mailFlage = 0;
          $download = 0;
          $file = $backup_filepath;
          $this->ftp = $result->toarray();

          if( !isset($this->ftp['conn']) )
            $this->backup_ftp_connect($id);
          else {
            $this->backup_ftp_connected($id);
          }

          if( $this->backup_file_transfer($file, $filename_compressed_form, $id) ) {
            $mailFlage = 1;
          }
        } else if( $result->destination_mode == 4 ) {
          set_time_limit(0);
          $mailFlage = 0;
          $download = 0;
          //UPLOAD THE DATABASE BACKUP FILE TO AMAZON S3 BUCKET
          $aws_filedata = array();
          $aws_link = $archiveFileName;

          $aws_service = new Sitebackup_Service_S3($config);
          // FULL PATH OF FILE FOR S3 BUCKET AND NEW FILE WILL BE SAVED WITH THIS NAME
          $s3Path = 'backup' . '/' . basename($archiveFileName);
          // DATA FOR THE FILE THAT WOULD BE CREATED
          $aws_filedata = file_get_contents($aws_link);
          // CREATE A FILE WITH $s3Path NAME AND $aws_filedata Data
          if( $aws_service->write($s3Path, $aws_filedata) ) {
            @unlink($aws_link);
            $mailFlage = 1;
          }
        } else if( $result->destination_mode == 5 ) {
          set_time_limit(0);
          $mailFlage = 0;
          $download = 0;
          //UPLOAD THE DATABASE BACKUP FILE TO DRIVE
          $drive_service = new Sitebackup_Service_Drive($config);
          $refreshToken = $drive_service->generateRefreshToken($config['refresh_token']);
          if( $drive_service->uploadFile($archiveFileName) ) {
            @unlink($archiveFileName);
            $mailFlage = 1;
          }
        } else if( $result->destination_mode == 5 ) {
          set_time_limit(0);
          $mailFlage = 0;
          $download = 0;
          // UPLOAD THE DATABASE BACKUP FILE TO Dropbox
          $dropbox_service = new Sitebackup_Service_Dropbox($config);
          if( $dropbox_service->uploadFile($backup_filepath) ) {
            @unlink($backup_filepath);
            $mailFlage = 1;
          }
        }
      }

      if( $mailFlage ) {
        if( !empty(Engine_Api::_()->getApi('settings', 'core')->sitebackup_backuptype) ) {
          $date = date('r');
          $array = (explode(" ", $date));
          $array[3] = $array[3] . ',';
          $date1 = implode(" ", $array);
          $date1 = str_replace("+0000", "", $date1);
          $subject = "Automatic backup of $siteName on $date1";
          $message = '';
          $message .= "The automatic backup of $siteName's database has completed. Please find below its details:";
          $message .= "\n\nDatabase backup information:";
          $status = 'Successful';

          if( Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailoption ) {
            $mails = Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailsender;
            $mail = explode(',', $mails);
            foreach( $mail as $mail_id ) {
              $email = Engine_Api::_()->getApi('settings', 'core')->core_mail_from;
              Engine_Api::_()->getApi('mail', 'core')->sendSystem($mail_id, 'Database_Backup_Email_Notification', array(
                'subject' => $subject,
                'message' => $message,
                'status' => $status,
                'completion_time' => $date1,
                'destination_name' => $database_destination,
                'backup_filesize' => $database_filesize,
                'backup_filename' => $database_filename,
                'email' => $email,
                'queue' => false
              ));
            }
          }
        } else {

          $date = date('r');
          $array = (explode(" ", $date));
          $array[3] = $array[3] . ',';
          $date1 = implode(" ", $array);
          $date1 = str_replace("+0000", "", $date1);
          $subject = "Automatic backup of $siteName on $date1";
          $message = '';
          $message .= "The automatic backup of $siteName's database and files has completed. Please find below its details:";
          $message .= "\n\nDatabase backup information:";
          $status = 'Successful';
          $date2 = date('r');
          $array2 = (explode(" ", $date2));
          $array2[3] = $array2[3] . ',';
          $date2 = implode(" ", $array2);
          $date2 = str_replace("+0000", "", $date2);
          $message2 = "Files backup information:";


          if( Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailoption ) {

            $mails = Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailsender;
            $mail = explode(',', $mails);
            foreach( $mail as $mail_id ) {
              $email = Engine_Api::_()->getApi('settings', 'core')->core_mail_from;
              $resultf = Engine_Api::_()->getApi('mail', 'core')->sendSystem($mail_id, 'Database_and_Files_Backup_Email_Notification', array(
                'subject' => $subject,
                'message' => $message,
                'status' => $status,
                'completion_time' => $date1,
                'destination_name' => $database_destination,
                'backup_filesize' => $database_filesize,
                'backup_filename' => $database_filename,
                'message2' => $message2,
                'status' => $status,
                'completion_time2' => $date2,
                'destination_name2' => $code_destination,
                'backup_filesize2' => $code_filesize,
                'backup_filename2' => $filename_code,
                'email' => $email,
                'queue' => false
              ));
            }
          }
        }
      }
    }
  }

  function backup_file_transfer($file, $dest_filename, $id)
  {
    $siteName = Engine_Api::_()->getApi('settings','core')->core_general_site_title;
    $backup_type = "Backup Type: Database";

    set_time_limit(0);

    if( $this->ftp['ftpportno'] == 21 ) {
      if( !(@ftp_chdir($this->ftp['conn'], $this->ftp['ftppath'] . "/" . $this->ftp['ftpdirectoryname'])) ) {
        @ftp_mkdir($this->ftp['conn'], $this->ftp['ftppath'] . "/" . $this->ftp['ftpdirectoryname']);
      }
    } else {
      $sftp = ssh2_sftp($this->ftp['conn']);
      if( !(ssh2_sftp_stat($sftp, $this->ftp['ftppath'] . "/" . $this->ftp['ftpdirectoryname'])) ) {
        ssh2_sftp_mkdir($sftp, $this->ftp['ftppath'] . "/" . $this->ftp['ftpdirectoryname']);
      }
    }

    $source = $file;

    $dest_complete_path = $this->ftp['ftppath'] . "/" . $this->ftp['ftpdirectoryname'] . "/" . $dest_filename;

    if( file_exists($source) ) {
      if( $this->ftp['ftpportno'] == 21 ) {
        $put_file = @ftp_put($this->ftp['conn'], $dest_complete_path, $source, FTP_BINARY);
        if( !$put_file ) {
          //turn passive mode on
          @ftp_pasv($this->ftp['conn'], true);
          $put_file = @ftp_put($this->ftp['conn'], $dest_complete_path, $source, FTP_BINARY);
        }
      } else {
        $put_file = ssh2_scp_send($this->ftp['conn'], $source, $dest_complete_path, 0744);
      }

      if( !$put_file ) {
        $subject = "The automatic backup scheduled for $siteName failed at:" . date('Y-m-d H:i:s') . '.';
        $message = "The automatic backup scheduled for $siteName failed at:" . date('Y-m-d H:i:s') . '.';
        $reason = "Reason: FTP Error: Could not save the backup file - $file on the FTP server.";
        $moremessage = "This backup file has been saved in the Backup Directory on your server. You may download it from Database Backups listing page.";

        if( Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailoption ) {
          $mails = Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailsender;
          $mail = explode(',', $mails);

          foreach( $mail as $mail_id ) {
            $email = Engine_Api::_()->getApi('settings', 'core')->core_mail_from;
            Engine_Api::_()->getApi('mail', 'core')->sendSystem($mail_id, 'Backup_Failure_Email_Notification', array(
              'subject' => $subject,
              'message' => $message,
              'backup_type' => $backup_type,
              'reason' => $reason,
              'moremessage' => $moremessage,
              'email' => $email,
              'queue' => false
            ));
          }
        }
        $table = Engine_Api::_()->getDbTable('backups', 'sitebackup');

        $table->update(array('backup_status' => 0), array('backup_id = ?' => $id));
        return FALSE;
      } else {
        @unlink($source);
      }

      // Everything worked OK
      return TRUE;
    } else {
      $subject = "The automatic backup scheduled for $siteName failed at:" . date('Y-m-d H:i:s') . '.';
      $message = "The automatic backup scheduled for $siteName failed at:" . date('Y-m-d H:i:s') . '.';
      $reason = "FTP Error: Could not find the backup file on $siteName\'s server.";

      if( Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailoption ) {
        $mails = Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailsender;
        $mail = explode(',', $mails);

        foreach( $mail as $mail_id ) {
          $email = Engine_Api::_()->getApi('settings', 'core')->core_mail_from;
          Engine_Api::_()->getApi('mail', 'core')->sendSystem($mail_id, 'Backup_Failure_Email_Notification', array(
            'subject' => $subject,
            'message' => $message,
            'backup_type' => $backup_type,
            'reason' => $reason,
            'moremessage' => '  ',
            'email' => $email,
            'queue' => false
          ));
        }
      }


      $table = Engine_Api::_()->getDbTable('backups', 'sitebackup');

      $table->update(array('backup_status' => 0), array('backup_id = ?' => $id));
      return FALSE;
    }
  }

  function backup_ftp_connect($id)
  {
    $backup_type = "Backup Type: Database";
    $siteName = Engine_Api::_()->getApi('settings','core')->core_general_site_title;
    set_time_limit(0);

    //Connect to theserver
    if( $this->ftp['ftpportno'] == 21 ) {
      $this->ftp['conn'] = ftp_connect($this->ftp['ftphost'], $this->ftp['ftpportno']);
    } else {
      $this->ftp['conn'] = ssh2_connect($this->ftp['ftphost'], $this->ftp['ftpportno']);
    }
    if( !$this->ftp['conn'] ) {
      $subject = "The automatic backup scheduled for $siteName failed at: " . date('Y-m-d H:i:s') . '.';
      $message = "The automatic backup scheduled for $siteName failed at: " . date('Y-m-d H:i:s') . '.';
      $reason = "Reason: FTP Error: Could not connect to FTP server.";
      $moremessage = "This backup file has been saved in the Backup Directory on your server. You may download it from Database Backups listing page.";

      if( Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailoption ) {
        $mails = Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailsender;
        $mail = explode(',', $mails);

        foreach( $mail as $mail_id ) {
          $email = Engine_Api::_()->getApi('settings', 'core')->core_mail_from;
          Engine_Api::_()->getApi('mail', 'core')->sendSystem($mail_id, 'Backup_Failure_Email_Notification', array(
            'subject' => $subject,
            'message' => $message,
            'backup_type' => $backup_type,
            'reason' => $reason,
            'moremessage' => $moremessage,
            'email' => $email,
            'queue' => false
          ));
        }
      }
      $table = Engine_Api::_()->getDbTable('backups', 'sitebackup');
      $table->update(array('backup_status' => 0), array('backup_id = ?' => $id));
      return FALSE;
    }
    //LOGIN TO THE SERVER
    if( $this->ftp['ftpportno'] == 21 ) {
      $this->ftp['login'] = ftp_login($this->ftp['conn'], $this->ftp['ftpuser'], $this->ftp['ftppassword']);
    } else {
      $this->ftp['login'] = ssh2_auth_password($this->ftp['conn'], $this->ftp['ftpuser'], $this->ftp['ftppassword']);
    }
    if( !$this->ftp['login'] ) {
      $subject = "The automatic backup scheduled for $siteName failed at:" . date('Y-m-d H:i:s') . '.';
      $message = "The automatic backup scheduled for $siteName failed at:" . date('Y-m-d H:i:s') . '.';
      $reason = "Reason: FTP Error: Could not login as user FTP_USER on the FTP server.";
      $moremessage = "This backup file has been saved in the Backup Directory on your server. You may download it from Database Backups listing page.";
      if( Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailoption ) {
        $mails = Engine_Api::_()->getApi('settings', 'core')->sitebackup_mailsender;
        $mail = explode(',', $mails);

        foreach( $mail as $mail_id ) {
          $email = Engine_Api::_()->getApi('settings', 'core')->core_mail_from;
          Engine_Api::_()->getApi('mail', 'core')->sendSystem($mail_id, 'Backup_Failure_Email_Notification', array(
            'subject' => $subject,
            'messgae' => $message,
            'backup_type' => $backup_type,
            'reason' => $reason,
            'moremessage' => $moremessage,
            'email' => $email,
            'queue' => false
          ));
        }
      }
      $table = Engine_Api::_()->getDbTable('backups', 'sitebackup');
      $table->update(array('backup_status' => 0), array('backup_id = ?' => $id));
      return FALSE;
    }
  }

  function backup_ftp_connected($id)
  {
    set_time_limit(0);

    if( $this->ftp['ftpportno'] == 21 ) {
      if( !@ftp_systype($this->ftp['conn']) ) {
        return $this->ftp['conn'] = $this->backup_ftp_connect($id);
      } else {
        return $this->ftp['conn'];
      }
    } else {
      if( !@ssh2_sftp($this->ftp['conn']) ) {
        return $this->ftp['conn'] = $this->backup_ftp_connect($id);
      } else {
        return $this->ftp['conn'];
      }
    }
  }

}
