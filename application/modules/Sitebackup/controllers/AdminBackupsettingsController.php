<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license http://www.socialengineaddons.coom/license/
 * @version $Id: AdminBackupsettingsController.php 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author  SocialEngineAddOns
 */
if( version_compare(PHP_VERSION, '7.0.0') >= 0 ) {
  include APPLICATION_PATH . '/application/modules/Sitebackup/mysqli.php';
}

class Sitebackup_AdminBackupsettingsController extends Core_Controller_Action_Admin
{

  protected $database;

  public function indexAction()
  {
    include_once APPLICATION_PATH . '/application/modules/Sitebackup/Api/Core.php';
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('sitebackup_admin_main', array(), 'sitebackup_admin_main_backupsettings');
    $tables_temp = Engine_Api::_()->sitebackup()->fetchtables();

    //DATABASE INFORMATION.
    $dbinfo = Engine_Db_Table::getDefaultAdapter()->getConfig();
    $dbname = $dbinfo['dbname'];
    $dir_name_temp = Engine_Api::_()->getApi('settings', 'core')->sitebackup_directoryname;
    //CREATE A NEW FILE IF FILE DON'T EXISTS
    $filename = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary' . DIRECTORY_SEPARATOR . 'tables_name.txt';
    $file = (file_exists($filename)) ? fopen($filename, "w") : fopen($filename, "w+");
    //WRITE TABLES INTO THE FILE
    fwrite($file, Zend_Json_Encoder::encode($tables_temp));
    fclose($file);
    chmod($filename, 0777);

    $tmpPath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary' . DIRECTORY_SEPARATOR;
    $archiveSourcePath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR;
    $dir_name_temps = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $dir_name_temp;
    $path = $archiveSourcePath;
    $dotFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . '.';
    $doubledotFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . '..';
    $svnFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . '.svn';
    $tempFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'temporary';

    $fileArray = array($dotFile, $doubledotFile, $svnFile, $dir_name_temps, $tempFile);
    $resultsfiles = Engine_Api::_()->sitebackup()->getFiles($archiveSourcePath, $fileArray, 'public');

    $session = new Zend_Session_Namespace('backup');
    $session->resultsfiles = $resultsfiles;
    $archiveSourcePaths = APPLICATION_PATH . DIRECTORY_SEPARATOR;
    $dotRootFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . '.';
    $doubledotRootFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . '..';
    $svnRootFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . '.svn';
    $htaccessfile = APPLICATION_PATH . DIRECTORY_SEPARATOR . '.htaccess';
    $indexfile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'index.php';
    $readfile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'README.html';
    $robotsfile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'robots.txt';
    $xdreceiverfile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'xd_receiver.htm';
    $publicfile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public';
    $tempfile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
    $folder_selected = '';
    $folder_fileselected = '';
    $folder_moduleselected = '';
    $rootFileArray = array($dotRootFile, $doubledotRootFile, $svnRootFile, $htaccessfile, $indexfile, $readfile, $robotsfile, $xdreceiverfile, $tempfile, $publicfile);
    $resultsrootfiles = Engine_Api::_()->sitebackup()->getFiles($archiveSourcePaths, $rootFileArray, 'root');
    $session->resultsrootfiles = $resultsrootfiles;
    $archiveSourcePaths = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR;
    $applicationPaths = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application';
    $dotRootFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . '.';
    $doubledotRootFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . '..';
    $svnRootFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . '.svn';
    $resultsmodulefiles = array();
    $its = new DirectoryIterator($archiveSourcePaths);
    foreach( $its as $file ) {
      $replacedfilename = str_replace(APPLICATION_PATH . DIRECTORY_SEPARATOR, "", $file->getPathname());
      $replacedfilename_array = explode(DIRECTORY_SEPARATOR, $replacedfilename);
      if( $file->getPathname() == $dotRootFile || $file->getPathname() == $doubledotRootFile || $file->getPathname() == $svnRootFile || $file->getPathname() == $applicationPaths ) {
        continue;
      }
      if( isset($replacedfilename_array[2]) )
        $resultsmodulefiles[$replacedfilename_array[2]] = $replacedfilename_array[2];
    }
    sort($resultsmodulefiles);
    $session->resultsmodulefiles = $resultsmodulefiles;
    //SETTING THE FORM OF BACKUP SETTING PROCESS.
    $this->view->form = $form = new Sitebackup_Form_Admin_Backupsetting();
    //GET SETTINGS
    $global_settings_file = APPLICATION_PATH . '/application/settings/general.php';
    if( file_exists($global_settings_file) ) {
      $generalConfig = include $global_settings_file;
    } else {
      $generalConfig = array();
    }
    $this->view->dir_name_temp = $dir_name_temp = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . Engine_Api::_()->getApi('settings', 'core')->sitebackup_directoryname;
    $this->view->filePemissions = 0;
    $this->view->message = 1;
    if( is_dir($dir_name_temp) ) {
      if( !is_writable($dir_name_temp) ) {
        $this->view->filePemissions = 1;
        $this->view->message = 0;
      }
    }
    if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) ) {
      $this->view->message = 1;
      if( $_POST['backup_tables'] == 0 ) {
        $result_tables = Engine_Api::_()->sitebackup()->fetchtables();
        foreach( $result_tables as $value ) {
          $table_name = $value['Tables_in_' . $dbname];
          if( !empty($_POST[$table_name]) ) {
            if( $_POST[$table_name] == 1 ) {
              $table_selected['tables'][]['Tables_in_' . $dbname] = $table_name;
            }
          }
        }
      } else {
        $result_tables = Engine_Api::_()->sitebackup()->fetchtables();
        foreach( $result_tables as $value ) {
          $table_name = $value['Tables_in_' . $dbname];
          $table_selected['tables'][]['Tables_in_' . $dbname] = $table_name;
        }
      }

      if( $_POST['backup_completecode'] == 2 || $_POST['backup_completecode'] == 0 ) {
        if( $_POST['backup_files'] == 0 ) {
          $folder_selected = Engine_Api::_()->sitebackup()->getFolderSelected($session->resultsfiles);
          if( $folder_selected == '' ) {
            $form->addError($this->view->translate("Please choose atleast one folder to exclude from public directory."));
            return;
          }
        }
        if( $_POST['backup_rootfiles'] == 0 ) {
          $folder_fileselected = Engine_Api::_()->sitebackup()->getFolderSelected($session->resultsrootfiles);
          if( $folder_fileselected == '' ) {
            $form->addError($this->view->translate("Please choose atleast one folder to exclude from root directory."));
            return;
          }
        }

        $folder_modulevalueselected = array();
        if( isset($_POST['application']) && $_POST['application'] == 1 ) {
          $folder_modulevalueselected = array();
          if( isset($_POST['backup_modulesfiles']) && $_POST['backup_modulesfiles'] == 0 ) {
            foreach( $session->resultsmodulefiles as $modulevalue ) {
              if( !empty($_POST[$modulevalue]) ) {
                $modulevalue = str_replace(".", "_SITEBACKUP_DOT_", $modulevalue);
                if( $_POST[$modulevalue] == 1 ) {
                  $folder_modulevalueselected[] = $modulevalue;
                }
              }
            }
            if( $folder_modulevalueselected == '' ) {
              $form->addError($this->view->translate("Please choose atleast one folder to exclude from root/application/modules directory."));
              return;
            }
          }
        }
      }
      $this->view->base_url = Zend_Controller_Front::getInstance()->getBaseUrl();
      $values = $form->getValues();
      $backup_optionsettings = $values['backup_optionsettings'];
      if( array_key_exists('sitebackupmaintenance_mode', $values) ) {
        if( !empty($generalConfig['maintenance']['enabled']) && !empty($generalConfig['maintenance']['code']) ) {
          $form->getElement('sitebackupmaintenance_mode')->setValue(1);
        } else {
          $form->getElement('sitebackupmaintenance_mode')->setValue(0);
        }
        $maintenance = $values['sitebackupmaintenance_mode'];
        $session->maintenance = 1;
        if( $maintenance != @$generalConfig['maintenance']['enabled'] ) {
          $generalConfig['maintenance']['enabled'] = (bool) $maintenance;
          if( $generalConfig['maintenance']['enabled'] ) {
            setcookie('en4_maint_code', $generalConfig['maintenance']['code'], time() + (60 * 60 * 24 * 365), $this->view->baseUrl());
          }
          if( (is_file($global_settings_file) && is_writable($global_settings_file)) ||
            (is_dir(dirname($global_settings_file)) && is_writable(dirname($global_settings_file))) ) {
            $file_contents = "<?php defined('_ENGINE') or die('Access Denied'); return ";
            $file_contents .= var_export($generalConfig, true);
            $file_contents .= "; ?>";
            file_put_contents($global_settings_file, $file_contents);
          } else {
            return $form->getElement('sitebackupmaintenance_mode')
                ->addError('Unable to configure this setting due to the file /application/settings/general.php not having the correct permissions.Please CHMOD (change the permissions of) that file to 666, then try again.');
          }
        }
      }
      //GETTING THE CURRENT DIRECTORY NAME IN WHICH WE WANT TO STORE BACKUP FILE.
      $dir_name_temp = Engine_Api::_()->getApi('settings', 'core')->sitebackup_directoryname;
      $backup_file = APPLICATION_PATH . '/public/' . $dir_name_temp;
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
        $password_check_format = $this->view->translate("Congratulations! Your backup directory is PASSWORD PROTECTED.\n\nBackups provide insurance for your site. In the event that something on your site goes wrong, you can restore your site's content with the most recent backup file.\n\n **********   Website Backup and Restore Plugin by SocialEngineAddOns (http://www.socialengineaddons.com)   **********");
        $password_check_path = APPLICATION_PATH . '/public/' . $dir_name_temp . '/password_check.txt';
        $fp = fopen($password_check_path, 'w');
        fwrite($fp, $password_check_format);
        fclose($fp);
      }
      if( $backup_optionsettings == 1 ) {
        $table = Engine_Api::_()->getDbtable('settings', 'sitebackup');
        $select = $table->select();
        $row = $table->fetchRow($select);
        if( $row === null ) {
          foreach( $values as $key => $value ) {
            $table->insert(array('name' => $key, 'value' => $value));
          }
        } else {
          $i = 1;
          foreach( $values as $key => $value ) {
            $table->update(array('name' => $key, 'value' => $value), array('name = ?' => $key));
            $i++;
          }
        }
      }
      $this->view->backup_completecodes = $values['backup_completecode'];
      $this->view->dbname = $dbname;
      $session = new Zend_Session_Namespace('backup');
      $selectedTables = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary' . DIRECTORY_SEPARATOR . 'selectedTables.txt';
      if( !empty($table_selected) ) {
        
        $file = (file_exists($selectedTables)) ? fopen($selectedTables, "w") : fopen($selectedTables, "w+");
        //WRITE TABLES INTO THE FILE
        fwrite($file, Zend_Json_Encoder::encode($table_selected));
        fclose($file);
        chmod($selectedTables, 0777);
      } else {
        $form->addError($this->view->translate("Please choose atleast one table to take database backup."));
        return;
      }
      $this->view->tables = $values['backup_tables'];
      $session->backupfilename = $values['sitebackup_filename'];
      $file = fopen($selectedTables, "r");
      $content = fread($file, filesize($selectedTables));
      $this->view->tables=$tables_selected = Zend_Json_Decoder::decode($content);

      $session->backup_optionsettings = $backup_optionsettings;
      $this->view->values = $values;
      $this->view->lockoption = $values['sitebackup_tablelock'];
      $this->view->destination_id = $_POST['destination_id'];
      if( $_POST['backup_completecode'] == 2 || $_POST['backup_completecode'] == 0 ) {
        unset($session->folderselected);
        unset($session->folderfileselected);
        unset($session->foldermoduleselected);
        $session->folderselected = $folder_selected;
        $session->folderfileselected = $folder_fileselected;
        $session->foldermoduleselected = $folder_modulevalueselected;
      }
      unset($session->backup_files_temp);
      unset($session->backup_rootfiles_temp);
      unset($session->backup_modulefiles_temp);
      $session->backup_files_temp = $values['backup_files'];
      $session->backup_rootfiles_temp = $values['backup_rootfiles'];
      if( isset($values['backup_modulesfiles']) )
        $session->backup_modulefiles_temp = $values['backup_modulesfiles'];
      $this->view->code_destination_id = $_POST['backup_options'];
    }
    $table = Engine_Api::_()->getDbtable('settings', 'sitebackup');
    $select = $table->select();
    $row = $table->fetchAll($select);
    if( !empty($row) ) {
      foreach( $row as $key => $value ) {
        $field_name[$value->name] = $value->value;
      }
      $backup_completecode = $field_name['backup_completecode'];
      $backup_tables = $field_name['backup_tables'];
      $this->view->backup_completecodes = $backup_completecode;
      if( $backup_completecode == 1 ) {
        $this->view->backup_tables = $backup_tables;
        $this->view->backup_files = 1;
        $this->view->backup_rootfiles = 1;
        $this->view->backup_modulesfiles = 1;
      } elseif( $backup_completecode == 2 ) {
        $this->view->backup_tables = $backup_tables;
        $this->view->backup_files = $field_name['backup_files'];
        $this->view->backup_rootfiles = $field_name['backup_rootfiles'];
        $this->view->backup_modulesfiles = $field_name['backup_modulesfiles'];
      } else {
        $this->view->backup_tables = 1;
        $this->view->backup_files = $field_name['backup_files'];
        $this->view->backup_rootfiles = $field_name['backup_rootfiles'];
        $this->view->backup_modulesfiles = $field_name['backup_modulesfiles'];
      }
    }
  }

  //THIS FUNCTION RETURNS THE RANDOM PASSWORD.
  public function createRandomPassword($length = 6)
  {
    $chars = "abcdefghijkmnpqrstuvwxyz23456789";
    srand((double) microtime() * 1000000);
    $i = 0;
    $pass = '';
    while( $i < $length ) {
      $num = rand() % 33;
      $tmp = substr($chars, $num, 1);
      $pass = $pass . $tmp;
      $i++;
    }
    return $pass;
  }

  //THIS FUNCTION RETURN THE CREATE STATEMENT FOR A TABLE START.
  public function createAction()
  {
    //LAYOUT
    $this->_helper->layout->setLayout('admin-simple');
    $memory_size = ini_get('memory_limit');
    $memory_Size_int_array = explode("M", $memory_size);
    $memory_Size_int = $memory_Size_int_array[0];
    ini_set('upload_max_filesize', '100M');
    ini_set('post_max_size', '100M');
    ini_set('max_input_time', 600);
    ini_set('max_execution_time', 600);
    if( $memory_Size_int <= 32 ) {
      ini_set('memory_limit', '64M');
    }
    $currentbase_time = time();
    $check_result_show = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitebackup.check.variable');
    $base_result_time = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitebackup.set.time');
    //GETTING THE CURRENT DIRECTORY NAME IN WHICH WE WANT TO STORE BACKUP FILE.
    $dir_name_temp = Engine_Api::_()->getApi('settings', 'core')->sitebackup_directoryname;
    $fileAdapter = APPLICATION_PATH . '/application/settings/database.php';
    $fileAdapterinfo = include $fileAdapter;
    //SETTING THE SELECTED TABLE INTO THE SESSION.
    $session = new Zend_Session_Namespace('backup');
    $session->fileadapter = $fileAdapterinfo['adapter'];
    $selectedTables = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary' . DIRECTORY_SEPARATOR . 'selectedTables.txt';
    $file = fopen($selectedTables, "r");
    $content = fread($file, filesize($selectedTables));
    $table_selected = Zend_Json_Decoder::decode($content);
    // $table_selected = Zend_Json_Decoder::decode($session->table_selected);
    $folder_selecteds = $session->folderselected;
    $folder_fileselecteds = $session->folderfileselected;
    $folder_moduleselecteds = $session->foldermoduleselected;
    $backup_files_temp = $session->backup_files_temp;
    $backup_rootfiles_temp = $session->backup_rootfiles_temp;
    $backup_modulefiles_temp = '';
    if( isset($session->backup_modulefiles_temp) ) {
      $backup_modulefiles_temp = $session->backup_modulefiles_temp;
    }
    $result = array();

    //GET THE SELECTED TABLES
    if( null !== ($this->_getParam('destination_id')) ) {
      $this->view->destination_id = $destination_id = $this->_getParam('destination_id');
    }

    //GET THE OPTION OF LOCK TABLE DURING DATABASE BACKUP
    if( null !== ($this->_getParam('lockoption')) ) {
      $this->view->lockoption = $lockoption = $this->_getParam('lockoption');
    }
    //Get settings
    $global_settings_file = APPLICATION_PATH . '/application/settings/general.php';
    if( file_exists($global_settings_file) ) {
      $generalConfig = include $global_settings_file;
    } else {
      $generalConfig = array();
    }
    $this->_helper->layout->setLayout('admin-simple');
    //REQUIRE
    require_once 'PEAR.php';
    require_once 'Archive/Tar.php';

    //PROCESS
    set_time_limit(0);
    //INCLUDING THE FILE WHERE WE WRITE FUNCTION WHICH ARE USED IN THIS BACKUP PROCESS.
    include_once APPLICATION_PATH . '/application/modules/Sitebackup/Api/Core.php';
    // $get_result_show = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitebackup.get.path');
    $sitebackup_time_var = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitebackup.time.var');
    //COUNT NUMBER OF TABLE.
    $num_selected_table = count($table_selected['tables']);
    //SETTING THE MAX EXECUTION TIME.
    $max_execution_time = 60;
    $max_execution_time = ($max_execution_time > 0) ? 30 : $max_execution_time;
    //MAINTAINING THE SPEED HOW MANY ROW WE WANT TO FETCH A TIME.
    $min_speed = 100;
    $max_speed = 60000;
    $backupfilename = $session->backupfilename;
    //SETTING THE MAX TIME FOR A SCRIPT HOW MUCH TIME ITS RUN.
    $max_time = (isset($_POST['max_time'])) ? $_POST['max_time'] : intval($max_execution_time * .75);
    //TAKING INITIAL CODE AS 0 BECAUSE 1ST TIME WHEN PAGE SUBMIT WE WANT ONLY RIGHT STATUS LINE.
    $initial_code = (isset($_POST['initial_code'])) ? $_POST['initial_code'] : 0;
    //HERE WE FINDING THE DATABASE INFORMATION.
    $dbinfo = Engine_Db_Table::getDefaultAdapter()->getConfig();
    //DATABASE-NAME
    $dbname = $dbinfo['dbname'];
    $dbhost = $dbinfo['host'];
    $dbuser = $dbinfo['username'];
    $dbpass = $dbinfo['password'];
    //SELECTING THE FILENAME IN WHICH WE WANT  TO RESTORE THE BACKUP
    $filename_compressed_form = (isset($_POST['filename_compressed_form'])) ? $_POST['filename_compressed_form'] : $backupfilename . '_' . 'database_' . date("Y_m_d_H_i_s", time()) . '.sql.gz';
    //SETTING THE CHANGE TABLE (WHICH TABLE IS USE AS A DATABASE BACKUP)
    $change_table = (isset($_POST['change_table'])) ? $_POST['change_table'] : 0;
    //SETTING THE VALUE OF THE BACKUP INITIAL FROM WHERE THE BACKUP PROCESS IS STARTING.
    $backup_initial = (isset($_POST['backup_initial'])) ? $_POST['backup_initial'] : 0;
    //SETTING THE MIN SPEED HOW MANY ROW WE WANT TO SELECT IN A SINGLE TIME
    $speed_up = (isset($_POST['speed_up'])) ? $_POST['speed_up'] : (($min_speed > 0) ? $min_speed : 50);
    //SETTING THE ADDTIONAL TIME HOW MUCH TIME TAKE A SCRIPT TO RUN IN COMPARISION TO BACKING UP PROCESS.
    $addtional_time = (isset($_POST['addtional_time'])) ? $_POST['addtional_time'] : 0;
    //SETTING THE START TIME FOR A SCRIPT.
    $script_time = (isset($_POST['script_time'])) ? $_POST['script_time'] : time();
    //CALCULATING HOW MANY PAGES REFRESH IN WHOLE PROCESS.
    $refresh_page = (isset($_POST['refresh_page'])) ? $_POST['refresh_page'] : 0;
    //CALCULATING HOW MANY PAGES REFRESH IN WHOLE PROCESS.
    $this->view->fileSize = $fileSize = (isset($_POST['file_size'])) ? $_POST['file_size'] : 0;
    //GETTING THE BACKUP OPTIONS HOW TO YOU WANT BACKUP.
    $backup_options = $_GET['backup_options'];
    $backup_time = time();
    //GETTING THE BACKUP OPTIONS HOW TO YOU WANT BACKUP.
    $backup_completecode = $_GET['backup_completecode'];

    //SENDING THE HOW MANY TABLES  TO THE TPL FILE.
    if( null != ($_GET['code_destination_id']) ) {
      $this->view->code_destination_id = $code_destination_id = $_GET['code_destination_id'];
    } else {
      $this->view->code_destination_id = 1;
    }
    //SENDING THE HOW MANY TABLES  TO THE TPL FILE.
    $this->view->num_selected_table = $num_selected_table;
    //SENDING MAXIMUM EXECUTION TIME TO THE TPL FILE.
    $this->view->max_execution_time = $max_execution_time;
    //SENDING MINIMUM SPEED TO THE TPL FILE.
    $this->view->min_speed = $min_speed;
    //SENDING MAXIMUM TIME TO THE TPL FILE.
    $this->view->max_time = $max_time;
    //SENDING MAXIMUM SPEED TO THE TPL FILE.
    $this->view->max_speed = $max_speed;
    //SENDING INITIAL CODE TO THE TPL FILE.
    $this->view->initial_code = $initial_code;
    //SENDING WHICH TABLE IS IN PROGRESS TO THE TPL FILE.
    $this->view->change_table = $change_table;
    //SENDING VALUE OF BACKUP INITIAL WHERE FROM START THE  PROGRESS TO THE TPL FILE.
    $this->view->backup_initial = $backup_initial;
    //SENDING SPEED UP TO THE TPL FILE.
    $this->view->speed_up = $speed_up;
    //SENDING THE ADDTIONAL TIME HOW MUCH TIME TAKEN BY SCRIPT.
    $this->view->addtional_time = $addtional_time;
    //SENDING THE  TIME HOW MUCH TIME TAKEN BY SCRIPT TIME.
    $this->view->script_time = $script_time;
    //COUNT HOW MANY PAGES REFRESH IN THE SCRIPT.
    $this->view->refresh_page = $refresh_page;
    //SENDING THE OPTIONS TYPE WHICH IS USE FOR BACKUP PROCESS.
    $this->view->backup_options = $backup_options;
    //SENDING THE FILENAME IN WHICH THE BACKUP IS STORED.
    $this->view->filename_compressed_form = $filename_compressed_form;
    //SETTING THE PATH OF THE BACKUP FILE.
    $backup_filepath = APPLICATION_PATH . '/public/' . $dir_name_temp . '/' . $filename_compressed_form;

    //SENDING THE FILEPATH  IN WHICH THE BACKUP DIRECTORY IS STORED.
    $this->view->backup_filepath = $backup_filepath;
    $backupLog_Table = Engine_Api::_()->getDbtable('backuplogs', 'sitebackup');
    $log_values = array();

    $files = array();
    $tmpPath_files = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary' . DIRECTORY_SEPARATOR;
    $tmpfiles = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'temporary';
    $tmpfilesArray = array($tmpfiles . DIRECTORY_SEPARATOR . 'index.html');
    $coremodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('core');
    $coreversion = $coremodule->version;
    if( $coreversion < '4.1.0' ) {
      $files = array($tmpPath_files . 'log' . DIRECTORY_SEPARATOR . 'index.html',
        $tmpPath_files . 'log' . DIRECTORY_SEPARATOR . 'scaffold' . DIRECTORY_SEPARATOR . 'index.html',
        $tmpPath_files . 'scaffold' . DIRECTORY_SEPARATOR . 'index.html',
        $tmpPath_files . 'cache' . DIRECTORY_SEPARATOR . 'index.html',
        $tmpPath_files . 'session' . DIRECTORY_SEPARATOR . 'index.html',
        $tmpPath_files . 'package' . DIRECTORY_SEPARATOR . 'sdk' . DIRECTORY_SEPARATOR . 'index.html',
        $tmpPath_files . 'package' . DIRECTORY_SEPARATOR . 'archives' . DIRECTORY_SEPARATOR . 'index.html',
        $tmpPath_files . 'package' . DIRECTORY_SEPARATOR . 'compare' . DIRECTORY_SEPARATOR . 'index.html',
        $tmpPath_files . 'package' . DIRECTORY_SEPARATOR . 'repositories' . DIRECTORY_SEPARATOR . 'index.html',
        $tmpPath_files . 'package' . DIRECTORY_SEPARATOR . 'manifests' . DIRECTORY_SEPARATOR . 'index.html',
        $tmpPath_files . 'package' . DIRECTORY_SEPARATOR . 'index.html',
        $tmpPath_files . 'package' . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'index.html',
        $tmpPath_files . 'backup' . DIRECTORY_SEPARATOR . 'index.html',
      );
    } else {
      $files = array($tmpPath_files . 'log' . DIRECTORY_SEPARATOR . 'index.html',
        $tmpPath_files . 'log' . DIRECTORY_SEPARATOR . 'scaffold' . DIRECTORY_SEPARATOR . 'index.html',
        $tmpPath_files . 'scaffold' . DIRECTORY_SEPARATOR . 'index.html',
        $tmpPath_files . 'cache' . DIRECTORY_SEPARATOR . 'index.html',
        $tmpPath_files . 'session' . DIRECTORY_SEPARATOR . 'index.html',
        $tmpPath_files . 'package' . DIRECTORY_SEPARATOR . 'sdk' . DIRECTORY_SEPARATOR . 'index.html',
        $tmpPath_files . 'package' . DIRECTORY_SEPARATOR . 'archives' . DIRECTORY_SEPARATOR . 'index.html',
        $tmpPath_files . 'package' . DIRECTORY_SEPARATOR . 'repositories' . DIRECTORY_SEPARATOR . 'index.html',
        $tmpPath_files . 'package' . DIRECTORY_SEPARATOR . 'manifests' . DIRECTORY_SEPARATOR . 'index.html',
        $tmpPath_files . 'package' . DIRECTORY_SEPARATOR . 'index.html',
        $tmpPath_files . 'package' . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'index.html',
        $tmpPath_files . 'backup' . DIRECTORY_SEPARATOR . 'index.html',
      );
    }
    $files_array_merge = array_merge($files, $tmpfilesArray);
    if( ($currentbase_time - $base_result_time > $sitebackup_time_var) && empty($check_result_show) ) {
      Engine_Api::_()->getApi('settings', 'core')->setSetting('sitebackup.check.variable', 1);
    }
    //SENDING THE DATABASE NAME.
    $this->view->dbname = $dbname;
    $start_codeBackup = time();
    $this->view->codeDuration = '';
    //SENDING THE PERCENTAGE HOW MUCH BACKUP IS DONE.
    $backup_code = 0;
    $result = array();
    $this->view->download = 1;
    $this->view->backup_completecode = $backup_completecode;
    $this->view->only_code = 0;
    $this->view->start_code = 0;
    if( !($this->view->backup_completecode == 1 || $this->view->backup_completecode == 2) ) {
      $this->view->only_code = 1;
      $this->view->initial_code = 1;
      $this->view->start_code = 1;
    }
    //SENDING THE DIRECTORY NAME.
    $this->view->dir_name_temp = $dir_name_temp;
    //WHICH BACKUP :- DATABSE ONLY OR BOTH CODE AND DATABASE
    $this->view->flage = 0;

    $mysqlPath = '';
    // GET MYSQL DIRECTORY PATH IF MYSQL PATH IS NOT ADDED AS SYSTEM VARIABLES PATH
    if( empty(exec('which mysqldump')) ) {
      $mysqlPath = Engine_Api::_()->sitebackup()->getMysqlDirectoryPath();
    }

    $coderesult = Engine_Api::_()->getItem('sitebackup_destinations', $backup_options);
    $this->view->code_destinationname = $coderesult->destinationname;
    if( !isset($session->post) ) {
      $session->post = 1;
      $this->view->initial = 1;
      if( $destination_id != 0 ) {
        $result = Engine_Api::_()->getItem('sitebackup_destinations', $destination_id);
        $this->view->destination_mode = $result->destination_mode;
        $this->view->destinationname = $result->destinationname;
        if( $result->destination_mode == 3 ) {
          $this->view->backup_code = 4;
          $this->view->initial_code = 1;
          $this->view->start_code = 1;
          $this->view->destination_mode1 = 3;
        }
      }
    } else {
      if( $destination_id != 0 && $this->view->backup_completecode != 0 ) {
        $result = Engine_Api::_()->getItem('sitebackup_destinations', $destination_id);
        if( $result->destination_mode == 3 ) {
          $log_values = array();
          $method = 'Database';
          $log_values = array_merge($log_values, array(
            'type' => 'Database',
            'method' => 'Manual',
            'destination_name' => $result->destinationname,
            'destination_method' => 'Database',
            'filename' => 'N.A.',
            'start_time' => date('Y-m-d H:i:s'),
            'status' => 'Fail'
          ));
          $backuplog_id = $backupLog_Table->setLog($log_values);

          if( $lockoption ) {
            Engine_Api::_()->sitebackup()->lockDatabse();
          }

          $this->view->backup_code = 4;
          $this->database = $result->toarray();
          $link = mysql_connect($this->database['dbhost'], $this->database['dbuser'], $this->database['dbpassword'], '', MYSQL_CLIENT_INTERACTIVE);
          if( !$link ) {
            if( isset($session) && isset($session->post) )
              unset($session->post);
            die('Could not connect to database: ' . mysql_error());
          }
          $this->database['con'] = $link;
          $this->view->database_name = $this->database['dbname'];
          //make foo the current db
          $db_selected = mysql_select_db($this->database['dbname'], $link);
          if( !$db_selected ) {
            if( isset($session) && isset($session->post) )
              unset($session->post);
            die('Could not connect to database: ' . mysql_error());
          }
          set_time_limit(0);
          $initial_code = 0;
          $num_selected_table = count($table_selected['tables']);
          $string = '';
          $dbinfo = Engine_Db_Table::getDefaultAdapter()->getConfig();
          $dbname = $dbinfo['dbname'];
          while( ($num_selected_table - 1 >= $initial_code ) ) {
            $table_name[] = $table_selected['tables'][$initial_code]['Tables_in_' . $dbname];
            $initial_code++;
          }

          // Command for copying data from one databse to another
          $command = $mysqlPath . 'mysqldump -h ' . $dbinfo['host'] . ' -u ' . $dbinfo['username'] . ' -p' . $dbinfo['password'] . ' ' . $dbname . ' ';
          $command .= implode(' ', $table_name);
          $command .= ' | ' . $mysqlPath . 'mysql -h ' . $this->database['dbhost'] . ' -u ' . $this->database['dbuser'] . ' -p' . $this->database['dbpassword'] . ' ' . $this->database['dbname'];
          exec($command);

          if( $this->view->backup_completecode == 2 ) {
            $backup_code = 2;
            $this->view->backup_completecode = 0;
          } else {
            $backup_code = 1;
            $this->view->backup_completecode = 4;
          }

          $this->view->flage = 1;
          if( isset($session) && isset($session->post) )
            unset($session->post);
          $this->view->download = 0;
          if( $lockoption ) {
            Engine_Api::_()->sitebackup()->unlockDatabse();
          }
        }
      }
      $this->view->destination_mode = $result->destination_mode;
      $this->view->destinationname = $result->destinationname;
      $skippedfiles = $folder_selecteds;
      $skippedrootfiles = $folder_fileselecteds;
      $skippedmodulefiles = $folder_moduleselecteds;

      $this->view->initial = 0;
      if( $this->view->backup_completecode == 1 || $this->view->backup_completecode == 2 ) {
        //START OF PREPAREING THE BACKUP FILE.
        if( $this->view->initial_code == 0 ) {
          $sitebackups_create_host = $sitebackup_host_name = str_replace('www.', '', strtolower($_SERVER['HTTP_HOST']));
          $sitebackups_ad_field = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitebackup.view.attempt', 0);
          if( !empty($sitebackups_ad_field) ) {
            $sitebackups_ad_field = convert_uudecode($sitebackups_ad_field);
            $sitebackups_create_host = $sitebackups_ad_field;
          }
          if( $sitebackups_create_host != $sitebackup_host_name ) {
            return;
          }

          $string = '--';
          $generated_date = new Zend_Date();

          $this->view->backup_initial = $this->view->backup_initial + 1;
          $this->view->initial_code = $this->view->initial_code + 1;

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

          if( $lockoption ) {
            Engine_Api::_()->sitebackup()->unlockDatabse();
          }

          $log_values = array_merge($log_values, array(
            'type' => 'Database',
            'method' => 'Manual',
            'destination_name' => $destination_name,
            'destination_method' => $method,
            'filename' => $filename_compressed_form,
            'start_time' => date('Y-m-d H:i:s'),
            'status' => 'Progress'
          ));
          $backuplog_id = $backupLog_Table->setLog($log_values);
        } else {
          while( $this->view->num_selected_table >= $this->view->initial_code ) {
            $initial_code1 = $this->view->initial_code;
            $table_name = $table_selected['tables'][$this->view->initial_code - 1]['Tables_in_' . $dbname];
            if( $this->view->change_table != $this->view->initial_code ) {

              $this->view->backup_initial = $this->view->backup_initial + 1;
              if( $table_name != 'engine4_sitebackup_backuplogs' && $table_name != 'engine4_sitebackup_backups' ) {
                $table_names[] = $table_name;
              }
            }

            $this->view->change_table = $initial_code1;
            $this->view->initial_code = ++$initial_code1;
            $this->view->table_name = $table_name;
          }
          //COMMAND FOR EXPORT THE DATABASE BEFORE THAT CHANGE THE DIRECTORY
          $command = $mysqlPath . 'mysqldump -h ' . $dbhost . ' -u ' . $dbuser . ' -p' . $dbpass . ' ' . $dbname . ' ';
          $command .= implode(' ', $table_names);
          $command .= ' | gzip > ' . $backup_filepath;
          exec($command);

          //HERE WE CHECKING THE INITIAL CODE IS LESS THEN NUMBER OF TABLE AND ALSO IN THIS CONDITION WE INCREASING THE SPEED OF THE BACKING UP PROCESS.
          if( $this->view->initial_code <= $this->view->num_selected_table ) {
            $time_variant = time() - ($this->view->script_time + $this->view->addtional_time);
            $this->view->addtional_time += $time_variant;
            if( $time_variant < $this->view->max_time ) {
              $this->view->speed_up = $this->view->speed_up * 1.2;
              if( $time_variant < $this->view->speed_up / 2 ) {
                $this->view->speed_up = $this->view->speed_up * 1.75;
              }
              if( $this->view->speed_up > $this->view->max_speed ) {
                $this->view->speed_up = $this->view->max_speed;
              }
            } else {
              $this->view->speed_up = $this->view->speed_up * 0.8;
              if( $this->view->$speed_up < $this->view->min_speed ) {
                $this->view->speed_up = $this->view->min_speed;
              }
            }
            $this->view->speed_up = intval($this->view->speed_up);
            $this->view->refresh_page++;
          } else {
            $this->view->start_code = 1;
          }
        }
        //CHECK CONDITION FOR COMPLETE OF DATABASE
        if( $this->view->num_selected_table + 1 == $this->view->initial_code ) {
          if( $lockoption ) {
            Engine_Api::_()->sitebackup()->unlockDatabse();
          }
          if( $session->backup_optionsettings == 1 ) {
            $table = Engine_Api::_()->getDbtable('settings', 'sitebackup');
            $select = $table->select();
            $row = $table->fetchRow($select);
            if( $row !== null ) {
              $table->update(array('name' => 'sitebackupmaintenance_mode', 'value' => 0), array('name = ?' => 'sitebackupmaintenance_mode'));
            }
          }
          //BACKUP OF CODE
          if( $this->view->backup_completecode == 2 ) {
            $backup_completecode1 = $this->view->backup_completecode;
            $dir_name_temp1 = $this->view->dir_name_temp;
            $dataArr = array('backup_options' => $backup_options, 'backupfilename' => $backupfilename, 'backup_files_temp' => $backup_files_temp, 'backup_rootfiles_temp' => $backup_rootfiles_temp, 'backup_modulefiles_temp' => $backup_modulefiles_temp, 'skippedfiles' => $skippedfiles, 'skippedrootfiles' => $skippedrootfiles, 'skippedmodulefiles' => $skippedmodulefiles, 'dir_name_temp' => $dir_name_temp1, 'backup_completecode' => $backup_completecode1, 'log_values' => $log_values, 'files_array_merge' => $files_array_merge);
            $data = Engine_Api::_()->sitebackup()->codeBackup($dataArr);
            $this->view->backup_options = $data['backup_options'];

            $this->view->code_filesize = $data['code_filesize'];
            $this->view->code_filename = $data['code_filename'];
            if( $backup_options == 2 ) {
              $this->view->download = $data['download'];
            }
          }
          $fileSize = @filesize($backup_filepath);
          Engine_Api::_()->sitebackup()->checkDatabaseConnection();
          $query = "SELECT * FROM engine4_sitebackup_destinations WHERE destinations_id = '$destination_id'";
          $select = mysql_query($query);
          $fetch = mysql_fetch_assoc($select);
          $this->view->fileSize = $filesize1 = round($fileSize / 1048576, 3) . ' Mb';
          if( $destination_id == 0 ) {
            $backup_options = 'Download';
            $this->view->backup_options = 1;
          } else {
            $backup_options = 'Server Backup Directory & Download';
            $this->view->backup_options = 0;
          }
          $backup_time = time();
          $this->view->database_filesize = $filesize1;
          $filenametemp = '';
          if( !empty($fetch) ) {
            if( $fetch['destination_mode'] == 1 ) {
              $backup_method = 'Email';
              $this->view->database_filename = $backup_filename = $filename_compressed_form;
              $destination_name = $fetch['destinationname'];
              $filenametemp = '';
              $filesize2 = '';
              $this->view->backup_options = 1;
              $backup_codemethod = 3;
            } else if( $fetch['destination_mode'] == 4 ) {
              $backup_method = 'Amazon S3';
              $this->view->database_filename = $backup_filename = $filename_compressed_form;
              $destination_name = $fetch['destinationname'];
              $filenametemp = '';
              $filesize2 = '';
              $this->view->backup_options = 1;
              $backup_codemethod = 3;
            } else if( $fetch['destination_mode'] == 5 ) {
              $backup_method = 'Google Drive';
              $this->view->database_filename = $backup_filename = $filename_compressed_form;
              $destination_name = $fetch['destinationname'];
              $filenametemp = '';
              $filesize2 = '';
              $this->view->backup_options = 1;
              $backup_codemethod = 3;
            } else if( $fetch['destination_mode'] == 6 ) {
              $backup_method = 'Dropbox';
              $this->view->database_filename = $backup_filename = $filename_compressed_form;
              $destination_name = $fetch['destinationname'];
              $filenametemp = '';
              $filesize2 = '';
              $this->view->backup_options = 1;
              $backup_codemethod = 3;
            } else if( $fetch['destination_mode'] == 2 ) {
              $backup_method = 'FTP';
              $filesize2 = '';
              $this->view->database_filename = $backup_filename = $filename_compressed_form;
              $destination_name = $fetch['destinationname'];
              $this->view->backup_options = 0;
              $backup_codemethod = 3;
            } else if( $fetch['destination_mode'] == 3 ) {
              $backup_method = 'Database';
              $filesize2 = '';
              $this->view->database_filename = $backup_filename = '';
              $this->view->backup_options = 0;
              $backup_codemethod = 3;
              $destination_name = $fetch['destinationname'];
            } else if( $fetch['destination_mode'] == 0 ) {
              $filesize2 = '';
              $this->view->database_filename = $backup_filename = $filename_compressed_form;
              $backup_method = 'Server Backup Directory & Download';
              $destination_name = $fetch['destinationname'];
              $this->view->backup_options = 0;
              $backup_codemethod = 1;
            }
          } else {
            $this->view->database_filename = $backup_filename = $filename_compressed_form;
            $backup_method = 'Download';
            $filesize2 = '';
            $destination_name = 'Download to computer';
            $this->view->backup_options = 1;
            $backup_codemethod = 1;
          }
          $date = date('r');
          Engine_Api::_()->sitebackup()->checkDatabaseConnection();
          $query = "INSERT IGNORE INTO `engine4_sitebackup_backups` (`destination_id`, `backup_filesize`, `backup_method`, `backup_filename`, `backup_time`, `backup_codemethod`, `backup_filesize1`, `backup_filename1`, `backup_timedescription`, `destination_name`, `backup_status`, `backup_auto`) VALUES ('$destination_id', '$filesize1', '$backup_method', '$backup_filename', '$backup_time', '$backup_codemethod', '$filesize2', '$filenametemp','$date', '$destination_name', '1', '0')";
          $insert = mysql_query($query);
          if( !$insert ) {
            die('Can\'t use : ' . mysql_error());
          }
          $id = mysql_insert_id();
          $endtime = date('Y-m-d H:i:s');
          if( empty($destination_id) ) {
            $update = mysql_query("UPDATE `engine4_sitebackup_backuplogs` SET `filename` = '$filename_compressed_form', `size` = '$filesize1', `end_time` = '$endtime', `status` = 'Success' WHERE `engine4_sitebackup_backuplogs`.`filename` = '$filename_compressed_form' LIMIT 1;");
            if( !$update ) {
              die('Can\'t use : ' . mysql_error());
            }
          } else {
            if( $fetch['destination_mode'] !== 3 ) {
              if( $fetch['destination_mode'] == 1 ) {
                $update = mysql_query("UPDATE `engine4_sitebackup_backuplogs` SET `filename` = '$filename_compressed_form', `size` = '$filesize1', `end_time` = '$endtime', `status` = 'Success' WHERE `engine4_sitebackup_backuplogs`.`filename` = '$filename_compressed_form' LIMIT 1;");
              } else {
                $update = mysql_query("UPDATE `engine4_sitebackup_backuplogs` SET `filename` = '$filename_compressed_form', `size` = '$filesize1', `end_time` = '$endtime', `status` = 'Progress' WHERE `engine4_sitebackup_backuplogs`.`filename` = '$filename_compressed_form' LIMIT 1;");
              }
            } else {
              $update = mysql_query("UPDATE `engine4_sitebackup_backuplogs` SET `backuplog_id` = '$backuplog_id', `size` = '0', `end_time` = '$endtime', `status` = 'Success' WHERE `engine4_sitebackup_backuplogs`.`backuplog_id` = '$backuplog_id' LIMIT 1;");
              if( !$update ) {
                die('Can\'t use : ' . mysql_error());
              }
            }
          }
          $this->view->flage = 1;
          if( isset($session) && isset($session->post) )
            unset($session->post);
        }
      }
      //BACKUP OF ONLY CODE 
      if( $this->view->backup_completecode == 0 ) {
        $backup_completecode1 = $this->view->backup_completecode;
        $dir_name_temp1 = $this->view->dir_name_temp;
        $dataArr = array('backup_options' => $backup_options, 'backupfilename' => $backupfilename, 'backup_files_temp' => $backup_files_temp, 'backup_rootfiles_temp' => $backup_rootfiles_temp, 'backup_modulefiles_temp' => $backup_modulefiles_temp, 'skippedfiles' => $skippedfiles, 'skippedrootfiles' => $skippedrootfiles, 'skippedmodulefiles' => $skippedmodulefiles, 'dir_name_temp' => $dir_name_temp1, 'backup_completecode' => $backup_completecode1, 'log_values' => $log_values, 'files_array_merge' => $files_array_merge);
        //CALL THE FUNCTION
        $data = Engine_Api::_()->sitebackup()->codeBackup($dataArr);
        $this->view->backup_options = $data['backup_options'];
        $this->backup_completecode = $data['backup_completecode'];
        $this->view->only_code = $data['only_code'];
        $this->view->code_filesize = $data['code_filesize'];
        $this->view->code_filename = $data['code_filename'];
        // $this->view->codeDuration = $data['codeDuration'];
        if( $backup_options == 2 ) {
          $this->view->download = $data['download'];
        }
        $this->view->flage = 1;
        if( isset($session) && isset($session->post) )
          unset($session->post);
      }
    }
    if( $this->view->flage ) {
      $maintenance = 0;
      if( isset($session->maintenance) ) {
        if( $maintenance != @$generalConfig['maintenance']['enabled'] ) {
          $generalConfig['maintenance']['enabled'] = (bool) $maintenance;
          if( $generalConfig['maintenance']['enabled'] ) {
            setcookie('en4_maint_code', $generalConfig['maintenance']['code'], time() + (60 * 60 * 24 * 365), $this->view->baseUrl());
          }
          if( (is_file($global_settings_file) && is_writable($global_settings_file)) ||
            (is_dir(dirname($global_settings_file)) && is_writable(dirname($global_settings_file))) ) {
            $file_contents = "<?php defined('_ENGINE') or die('Access Denied'); return ";
            $file_contents .= var_export($generalConfig, true);
            $file_contents .= "; ?>";
            file_put_contents($global_settings_file, $file_contents);
          } else {
            return $form->getElement('sitebackupmaintenance_mode')
                ->addError('Unable to configure this setting due to the file /application/settings/general.php not having the correct permissions.Please CHMOD (change the permissions of) that file to 666, then try again.');
          }
        }
      }
      if( isset($session->maintenance) )
        unset($session->maintenance);
      if( $destination_id != 0 ) {
        $query = "SELECT * FROM engine4_sitebackup_destinations WHERE destinations_id = '$destination_id'";
        $select = mysql_query($query);
        $fetch = mysql_fetch_assoc($select);
        if( $fetch['destination_mode'] == 3 && $backup_completecode != 0 ) {
          $this->view->backup_completecode = $backup_code;
          $backup_time = time();
          $this->view->backup_options = 1;
          $destination_name = $fetch['destinationname'];
          $date = date('r');
          Engine_Api::_()->sitebackup()->checkDatabaseConnection();
          $query = "INSERT IGNORE INTO `engine4_sitebackup_backups` ( `destination_id`, `backup_method`, `backup_filename`, `backup_time`, `backup_codemethod`, `backup_timedescription`, `destination_name`, `backup_status`, `backup_auto`) VALUES ( '$destination_id', 'Database', '-', '$backup_time', '3','$date', '$destination_name', '1', '0')";
          $insert = mysql_query($query);
          if( !$insert ) {
            die('Can\'t use : ' . mysql_error());
          }
          $id = mysql_insert_id();
          $endtime = date('Y-m-d H:i:s');
          $update = mysql_query("UPDATE `engine4_sitebackup_backuplogs` SET `backuplog_id` = '$backuplog_id', `size` = '0', `end_time` = '$endtime', `status` = 'Success' WHERE `engine4_sitebackup_backuplogs`.`backuplog_id` = $backuplog_id LIMIT 1;");
          if( !$update ) {
            die('Can\'t use : ' . mysql_error());
          }
        }
      }
      if( $destination_id != 0 ) {
        //Get the configuration data for services
        $config = Engine_Api::_()->getDbtable('destinations', 'sitebackup')->getConfigData($destination_id);
        $tableObject = Engine_Api::_()->getDbTable('backups', 'sitebackup');
        if( $fetch['destination_mode'] == 1 ) {
          //CALL THE FUNCTION WHEN STORE THE DATA ON EMAIL
          $this->view->download = 0;
          $emailid = $fetch['email'];
          $email = new Sitebackup_Service_Email;
          $email_data = $email->backup_store_on_email($backup_filepath, $filename_compressed_form, $emailid);
          $this->view->download = $email_data['download'];
          $this->view->no_from = $email_data['no_form'];

          if( $email_data['mail_sent'] == 1 ) {
            $this->view->msg = $email_data['msg'];
          }
          $update = mysql_query("UPDATE `engine4_sitebackup_backuplogs` SET `filename` = '$filename_compressed_form', `size` = '$filesize1', `end_time` = '$endtime', `status` = 'Success' WHERE `engine4_sitebackup_backuplogs`.`filename` = '$filename_compressed_form' LIMIT 1;");
        } else if( $fetch['destination_mode'] == 2 && $this->view->backup_completecode != 0 ) {
          set_time_limit(0);
          $this->view->download = 1;
          $file = $backup_filepath;
          //CALL THE FUNCTION WHEN STORE THE DATA ON FTP SERVER
          $file_transfer = new Sitebackup_Service_FTP;
          if ($file_transfer->file_transfer_protocol($result, $file, $filename_compressed_form, $id)) {
            $update = mysql_query("UPDATE `engine4_sitebackup_backuplogs` SET `filename` = '$filename_compressed_form', `size` = '$filesize1', `end_time` = '$endtime', `status` = 'Success' WHERE `engine4_sitebackup_backuplogs`.`filename` = '$filename_compressed_form' LIMIT 1;");
          }
        } else if( $fetch['destination_mode'] == 4 && $this->view->backup_completecode != 0 ) {
          //UPLOAD THE DATABASE BACKUP FILE TO AMAZON S3 BUCKET
          $aws_filedata = array();
          $aws_link = $backup_filepath;

          $aws_service = new Sitebackup_Service_S3($config);
          // FULL PATH OF FILE FOR S3 BUCKET AND NEW FILE WILL BE SAVED WITH THIS NAME
          $s3Path = 'backup' . '/' . basename($backup_filepath);
          // DATA FOR THE FILE THAT WOULD BE CREATED
          $aws_filedata = file_get_contents($aws_link);
          // CREATE A FILE WITH $s3Path NAME AND $aws_filedata Data
          if( $aws_service->write($s3Path, $aws_filedata) ) {
            $tableObject->update(array("file_id" => 'backup'), array("backup_filename =?" => $filename_compressed_form));
            $update = mysql_query("UPDATE `engine4_sitebackup_backuplogs` SET `filename` = '$filename_compressed_form', `size` = '$filesize1', `end_time` = '$endtime', `status` = 'Success' WHERE `engine4_sitebackup_backuplogs`.`filename` = '$filename_compressed_form' LIMIT 1;");
            @unlink($aws_link);
          }
        } else if( $fetch['destination_mode'] == 5 ) {
          // UPLOAD THE DATABASE BACKUP FILE TO GOOGLE DRIVE
          $drive_service = new Sitebackup_Service_Drive($config);
          $refreshToken = $drive_service->generateRefreshToken($config['refresh_token']);
          if( isset($refreshToken) ) {
            if( $file_id = $drive_service->uploadFile($backup_filepath) ) {
              $tableObject->update(array("file_id" => $file_id), array("backup_filename =?" => $filename_compressed_form));
              $update = mysql_query("UPDATE `engine4_sitebackup_backuplogs` SET `filename` = '$filename_compressed_form', `size` = '$filesize1', `end_time` = '$endtime', `status` = 'Success' WHERE `engine4_sitebackup_backuplogs`.`filename` = '$filename_compressed_form' LIMIT 1;");
              @unlink($backup_filepath);
            }
          }
        } else if( $fetch['destination_mode'] == 6 ) {
          // UPLOAD THE DATABASE BACKUP FILE TO Dropbox
          $dropbox_service = new Sitebackup_Service_Dropbox($config);
          if( $file_id = $dropbox_service->uploadFile($backup_filepath) ) {
            $tableObject->update(array("file_id" => $file_id), array("backup_filename =?" => $filename_compressed_form));
            $update = mysql_query("UPDATE `engine4_sitebackup_backuplogs` SET `filename` = '$filename_compressed_form', `size` = '$filesize1', `end_time` = '$endtime', `status` = 'Success' WHERE `engine4_sitebackup_backuplogs`.`filename` = '$filename_compressed_form' LIMIT 1;");
            @unlink($backup_filepath);
          }
        }
        $select = $backupLog_Table->select()
                  ->where('filename= ?', $filename_compressed_form)
                  ->limit(1);
        $row = $backupLog_Table->fetchRow($select);
        if ($row->status == 'Progress') {
          $update = mysql_query("UPDATE `engine4_sitebackup_backuplogs` SET `filename` = '$filename1', `size` = '$filesize1', `end_time` = '$endtime', `status` = 'Fail' WHERE `engine4_sitebackup_backuplogs`.`filename` = '$filename1' LIMIT 1;");
        }
        if (!$update) {
          die('Can\'t use : ' . mysql_error());
        }
      }
    }
  }

}
