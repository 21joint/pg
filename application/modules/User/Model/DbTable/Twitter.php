<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Twitter.php 9747 2012-07-26 02:08:08Z john $
 * @author     John Boehr <j@webligo.com>
 */

/**
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class User_Model_Dbtable_Twitter extends Engine_Db_Table
{
  protected $_api;

  protected $_oauth;

  public function getApi()
  {
    if( null === $this->_api ) {
      $this->_initializeApi();
    }

    return $this->_api;
  }

  public function getOauth()
  {
    if( null === $this->_oauth ) {
      $this->_initializeApi();
    }
    
    return $this->_oauth;
  }

  public function clearApi()
  {
    $this->_api = null;
    $this->_oauth = null;
    return $this;
  }

  public function isConnected()
  {
    // @todo make sure that info is validated
    return ( !empty($_SESSION['twitter_token2']) && !empty($_SESSION['twitter_secret2']) );
  }

  protected function _initializeApi()
  {
    // Load classes
    include_once 'Services/Twitter.php';
    include_once 'HTTP/OAuth/Consumer.php';

    if( !class_exists('Services_Twitter', false) ||
        !class_exists('HTTP_OAuth_Consumer', false) ) {
      throw new Core_Model_Exception('Unable to load twitter API classes');
    }

    // Load settings
    $settings = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.twitter');
    if( empty($settings['key']) ||
        empty($settings['secret']) ||
        empty($settings['enable']) ||
        $settings['enable'] == 'none' ) {

      $this->_api = null;
      Zend_Registry::set('Twitter_Api', $this->_api);
    }

    // Try to log viewer in?
    $viewer = Engine_Api::_()->user()->getViewer();
    if( !isset($_SESSION['twitter_uid']) ||
        @$_SESSION['twitter_lock'] !== $viewer->getIdentity() ) {
      $_SESSION['twitter_lock'] = $viewer->getIdentity();
      if( $viewer && $viewer->getIdentity() ) {
        // Try to get from db
        $info = $this->select()
            ->from($this)
            ->where('user_id = ?', $viewer->getIdentity())
            ->query()
            ->fetch();
        if( is_array($info) &&
            !empty($info['twitter_secret']) &&
            !empty($info['twitter_token']) ) {
          $_SESSION['twitter_uid'] = $info['twitter_uid'];
          $_SESSION['twitter_secret2'] = $info['twitter_secret'];
          $_SESSION['twitter_token2'] = $info['twitter_token'];
        } else {
          $_SESSION['twitter_uid'] = false; // @todo make sure this gets cleared properly
        }
      } else {
        // Could not get
        //$_SESSION['twitter_uid'] = false;
      }
    }
    
    $this->_api = new Services_Twitter();

    // Get oauth
    if( isset($_SESSION['twitter_token2'], $_SESSION['twitter_secret2']) ) {
      $this->_oauth = new HTTP_OAuth_Consumer($settings['key'], $settings['secret'],
          $_SESSION['twitter_token2'], $_SESSION['twitter_secret2']);
    } else if( isset($_SESSION['twitter_token'], $_SESSION['twitter_secret']) ) {
      $this->_oauth = new HTTP_OAuth_Consumer($settings['key'], $settings['secret'],
          $_SESSION['twitter_token'], $_SESSION['twitter_secret']);
    } else {
      $this->_oauth = new HTTP_OAuth_Consumer($settings['key'], $settings['secret']);
    }
    $this->_api->setOAuth($this->_oauth);
  }

  /**
   * Generates the button used for Twitter Connect
   */
  public static function loginButton($connect_text = 'Sign-in with Twitter')
  {
    $href = Zend_Controller_Front::getInstance()->getRouter()
        ->assemble(array('module' => 'user', 'controller' => 'auth',
          'action' => 'twitter'), 'default', true);
    // return '
    //   <a class="btn btn-outline-info display-flex py-2 w-100 px-0 alignitem-center justify-content-center" href="'.$href.'">
    //     <svg width="14" height="13" aria-hidden="true" data-prefix="fab" data-icon="twitter-square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-twitter-square fa-w-14 fa-9x"><path fill="currentColor" d="M400 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zm-48.9 158.8c.2 2.8.2 5.7.2 8.5 0 86.7-66 186.6-186.6 186.6-37.2 0-71.7-10.8-100.7-29.4 5.3.6 10.4.8 15.8.8 30.7 0 58.9-10.4 81.4-28-28.8-.6-53-19.5-61.3-45.5 10.1 1.5 19.2 1.5 29.6-1.2-30-6.1-52.5-32.5-52.5-64.4v-.8c8.7 4.9 18.9 7.9 29.6 8.3a65.447 65.447 0 0 1-29.2-54.6c0-12.2 3.2-23.4 8.9-33.1 32.3 39.8 80.8 65.8 135.2 68.6-9.3-44.5 24-80.6 64-80.6 18.9 0 35.9 7.9 47.9 20.7 14.8-2.8 29-8.3 41.6-15.8-4.9 15.2-15.2 28-28.8 36.1 13.2-1.4 26-5.1 37.8-10.2-8.9 13.1-20.1 24.7-32.9 34z" class=""></path></svg>
    //     Twitter
    //   </a>
    // ';
  }
}
