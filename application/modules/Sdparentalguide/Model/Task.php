<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Model_Task extends Core_Model_Item_Abstract
{
    protected $_searchTriggers = false;
    protected $_log;


  public function run($page = 1,$job_user = null){
        
        try{
            $class = $this->plugin;
            Engine_Loader::loadClass($class);
            $plugin = new $class($this);
            $plugin->setLog($this->getLog());
            $paginator = $plugin->execute($page,$job_user);
        } catch (Exception $ex) {
            $paginator = Zend_Paginator::factory(array());
            $paginator->setCurrentPageNumber($page);
            $paginator->setItemCountPerPage($this->per_page);
            $this->getLog()->log($ex->getMessage(), Zend_Log::ERR);
        }
        
        return $paginator;
  }
  
  public function log($text){
      $this->getLog()->log($text, Zend_Log::INFO);
  }
    
  public function getLog()
  {
    if( null === $this->_log ) {
      $logAdapter = Engine_Api::_()->getDbtable('settings', 'core')
        ->getSetting('core.log.adapter', 'file');
      $log = new Zend_Log();
      $log->setEventItem('domain', 'tasks');
      try {
        switch( $logAdapter ) {
          case 'file': default:
            $log->addWriter(new Zend_Log_Writer_Stream(APPLICATION_PATH . '/temporary/log/AdminJobs.log'));
            break;
          case 'database':
            $log->addWriter(new Zend_Log_Writer_Db($this->getAdapter(), 'engine4_core_log'));
            break;
          case 'none':
            $log->addWriter(new Zend_Log_Writer_Null());
            break;
        }
      } catch( Exception $e ) {
        $log->addWriter(new Zend_Log_Writer_Null());
      }
      $this->_log = $log;
    }
    return $this->_log;
  }
} 




