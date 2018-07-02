<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class User_Form_Login extends Engine_Form_Email
{
  protected $_mode;
  
  public function setMode($mode)
  {
    $this->_mode = $mode;
    return $this;
  }
  
  public function getMode()
  {
    if( null === $this->_mode ) {
      $this->_mode = 'page';
    }
    return $this->_mode;
  }
  
  public function init()
  {
    $tabindex = rand(100, 9999);
    $this->_emailAntispamEnabled = (Engine_Api::_()->getApi('settings', 'core')
          ->getSetting('core.spam.email.antispam.login', 1) == 1);
    
    // Used to redirect users to the correct page after login with Facebook
    $_SESSION['redirectURL'] = Zend_Controller_Front::getInstance()->getRequest()->getRequestUri();

    $description = Zend_Registry::get('Zend_Translate')->_("Join Parental Guidance today.");
    $description= sprintf($description, Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_signup', true));

   
    // Init form
    $this->setTitle('Share Your Struggle. Provide Your Theories. Gain Advice.');
    $this->setDescription($description);
    $this->setAttrib('id', 'user_form_login');
    $this->setAttrib('class', 'extfox-auth');
    $this->loadDefaultDecorators();
    $this->getDecorator('Description')->setOption('escape', false);

    $email = Zend_Registry::get('Zend_Translate')->_('Email Address');
    // Init email
    $emailElement = $this->addEmailElement(array(
      'label' => $email,
      'required' => true,
      'allowEmpty' => false,
      'filters' => array(
        'StringTrim',
      ),
      'validators' => array(
        'EmailAddress'
      ),

      // Fancy stuff
      'tabindex' => $tabindex++,
      'autofocus' => 'autofocus',
      'inputType' => 'email',
      'class' => 'text',
    ));

    $emailElement->getValidator('EmailAddress')->getHostnameValidator()->setValidateTld(false);

    $password = Zend_Registry::get('Zend_Translate')->_('Password');
    // Init password
    $this->addElement('Password', 'password', array(
      'label' => $password,
      'required' => true,
      'allowEmpty' => false,
      'tabindex' => $tabindex++,
      'filters' => array(
        'StringTrim',
      ),
    ));

    $content = Zend_Registry::get('Zend_Translate')->_("<p><a href='%s'>Forgot Password?</a></p>");
    $content= sprintf($content, Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'user', 'controller' => 'auth', 'action' => 'forgot'), 'default', true));


    // Init forgot password link
    $this->addElement('Dummy', 'forgot', array(
      'content' => $content,
    ));

     // Init remember me
    $this->addElement('Checkbox', 'remember', array(
      'label' => 'Remember Me',
      'tabindex' => $tabindex++,
    ));

    $this->addDisplayGroup(array(
      'forgot',
      'remember'
    ), 'buttons-fileds',array('class' => 'border-0 mb-4'));



    $this->addElement('Hidden', 'return_url', array(

    ));

    $settings = Engine_Api::_()->getApi('settings', 'core');
    if( $settings->core_spam_login ) {
      $this->addElement('captcha', 'captcha', Engine_Api::_()->core()->getCaptchaOptions(array(
        'tabindex' => $tabindex++,
        'size' => ($this->getMode() == 'column') ? 'compact' : 'normal',
      )));
    }

    // Init submit
    $this->addElement('Button', 'submit', array(
      'label' => 'Sign In',
      'type' => 'submit',
      'ignore' => true,
      'tabindex' => $tabindex++,
    ));
    
    $this->addElement('Cancel', 'cancel', array(
      'label' => 'Sign Up',
      'link' => true,
      'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('controller' => 'signup', 'action' => 'index'), 'user_signup', true),
    ));

    $this->addDisplayGroup(array(
      'submit',
      'cancel'
    ), 'buttons',array ('class' => 'mb-5'));


    // Init facebook login link
    if( 'none' != $settings->getSetting('core_facebook_enable', 'none')
        && $settings->core_facebook_secret ) {
      $this->addElement('Dummy', 'facebook', array(
        'content' => User_Model_DbTable_Facebook::loginButton(),
      ));
    }

    // Init twitter login link
    //if( 'none' != $settings->getSetting('core_twitter_enable', 'none')
        //&& $settings->core_twitter_secret ) {
      $this->addElement('Dummy', 'twitter', array(
        'content' => User_Model_DbTable_Twitter::loginButton(),
      ));
   // }
    
    // Init janrain login link
    if( 'none' != $settings->getSetting('core_janrain_enable', 'none')
        && $settings->core_janrain_key ) {
      $mode = $this->getMode();
      $this->addElement('Dummy', 'janrain', array(
        'content' => User_Model_DbTable_Janrain::loginButton($mode),
      ));
    }

    $this->addDisplayGroup(array(
      'twitter',
      'facebook'
    ), 'facebook-button',array('class' => ' border-0 p-0 m-0'));

    // Set default action
    $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login'));
  }
}
