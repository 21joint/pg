<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Facebook.php 9747 2012-07-26 02:08:08Z john $
 * @author     Steve
 */

/**
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class User_Model_DbTable_Facebook extends Engine_Db_Table
{
  protected $_api;

  public static function getFBInstance()
  {
    return Engine_Api::_()->getDbtable('facebook', 'user')->getApi();
  }

  public function getApi()
  {
    // Already initialized
    if( null !== $this->_api ) {
      return $this->_api;
    }

    // Need to initialize
    $settings = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.facebook');
    if( empty($settings['secret']) ||
        empty($settings['appid']) ||
        empty($settings['enable']) ||
        $settings['enable'] == 'none' ) {
      $this->_api = null;
      Zend_Registry::set('Facebook_Api', $this->_api);
      return false;
    }

    $this->_api = new Facebook_Api(array(
      'appId'  => $settings['appid'],
      'secret' => $settings['secret'],
      'cookie' => false, // @todo make sure this works
      'baseDomain' => $_SERVER['HTTP_HOST'],
    ));
    Zend_Registry::set('Facebook_Api', $this->_api);

    // Try to log viewer in?
    $viewer = Engine_Api::_()->user()->getViewer();
    if( !isset($_SESSION['facebook_uid']) ||
        @$_SESSION['facebook_lock'] !== $viewer->getIdentity() ) {
      $_SESSION['facebook_lock'] = $viewer->getIdentity();
      if( $this->_api->getUser() ) {
        $_SESSION['facebook_uid'] = $this->_api->getUser();
      } else if( $viewer && $viewer->getIdentity() ) {
        // Try to get from db
        $info = $this->select()
            ->from($this)
            ->where('user_id = ?', $viewer->getIdentity())
            ->query()
            ->fetch();
        if( is_array($info) && !empty($info['facebook_uid']) &&
            !empty($info['access_token']) && !empty($info['code']) ) {
          $_SESSION['facebook_uid'] = $info['facebook_uid'];
          $this->_api->setPersistentData('code', $info['code']);
          $this->_api->setPersistentData('access_token', $info['access_token']);
        } else {
          // Could not get
          $_SESSION['facebook_uid'] = false;
        }
      } else {
        // Could not get
        //$_SESSION['facebook_uid'] = false;
      }
    }
    
    return $this->_api;
  }

  public function isConnected()
  {
    if( ($api = $this->getApi()) ) {
      return (bool) $api->getUser();
    } else {
      return false;
    }
  }

  public function checkConnection(User_Model_User $user = null)
  {
    if( null === $user ) {
      $user = Engine_Api::_()->user()->getViewer();
    }
    try {
      $this->getApi()->api('/me');
      $fb_uid = Engine_Api::_()->getDbtable('facebook', 'user')
          ->fetchRow(array('user_id = ?' => $user->getIdentity()));
    } catch( Exception $e ) {
      return false;
    }
    
    if( !$fb_uid || !$fb_uid->facebook_uid || $fb_uid->facebook_uid != $this->getApi()->getUser() ) {
      return false;
    } else {
      return true;
    }
  }
  
  /**
   * Generates the button used for Facebook Connect
   *
   * @param mixed $fb_params A string or array of Facebook parameters for login
   * @param string $connect_with_facebook The string to display inside the button
   * @return String Generates HTML code for facebook login button
   */
  public static function loginButton($connect_text = 'Connect with Facebook')
  {
    $settings  = Engine_Api::_()->getApi('settings', 'core');
    $facebook  = self::getFBInstance();

    if( !$facebook ) {
      return;
    }

    $href = Zend_Controller_Front::getInstance()->getRouter()
        ->assemble(array('module' => 'user', 'controller' => 'auth',
          'action' => 'facebook'), 'default', true);
    /* return '
      <a class="btn btn-outline-info display-flex py-2 px-0 w-100 alignitem-center justify-content-center"  href="'.$href.'">
        <svg width="14" height="13" aria-hidden="true" data-prefix="fab" data-icon="facebook-square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-facebook-square fa-w-14 fa-9x"><path fill="currentColor" d="M448 80v352c0 26.5-21.5 48-48 48h-85.3V302.8h60.6l8.7-67.6h-69.3V192c0-19.6 5.4-32.9 33.5-32.9H384V98.7c-6.2-.8-27.4-2.7-52.2-2.7-51.6 0-87 31.5-87 89.4v49.9H184v67.6h60.9V480H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h352c26.5 0 48 21.5 48 48z" class=""></path></svg>
        Facebook
      </a>
    '; */
  }
  
  public static function signup(User_Form_Account $form)
  {
    
  }
}
