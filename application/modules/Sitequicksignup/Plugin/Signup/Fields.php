<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitequicksignup
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Fields.php 2017-03-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitequicksignup_Plugin_Signup_Fields extends Core_Plugin_FormSequence_Abstract
{

  protected $_name = 'fields';
  protected $_formClass = 'Sitequicksignup_Form_Signup_Fields';
  protected $_script = array('signup/form/fields.tpl', 'sitequicksignup');
  protected $_otpPlugin;

//  protected $_adminFormClass = 'Seaocore_Form_Admin_Signup_Account';

  public function init()
  {
    if( Engine_Api::_()->hasModuleBootstrap("siteotpverifier") ) {
      $this->_otpPlugin = new Siteotpverifier_Plugin_Signup_Account();
      $this->_otpPlugin->setPlugin($this);
    }
  }

  public function getForm()
  {
    if( !is_null($this->_form) ) {
      return $this->_form;
    }
    $formArgs = array();

    // Preload profile type field stuff
    $profileTypeField = $this->getProfileTypeField();
    if( $profileTypeField ) {
      $accountSession = new Zend_Session_Namespace('Sitequicksignup_Plugin_Signup_Fields');
      $profileTypeValue = @$accountSession->data['profile_type'];
      if( $profileTypeValue ) {
        $formArgs = array(
          'topLevelId' => $profileTypeField->field_id,
          'topLevelValue' => $profileTypeValue,
        );
      } else {
        $topStructure = Engine_Api::_()->fields()->getFieldStructureTop('user');
        if( count($topStructure) == 1 && $topStructure[0]->getChild()->type == 'profile_type' ) {
          $profileTypeField = $topStructure[0]->getChild();
          $options = $profileTypeField->getOptions();
          if( count($options) == 1 ) {
            $formArgs = array(
              'topLevelId' => $profileTypeField->field_id,
              'topLevelValue' => $options[0]->option_id,
            );
          }
        }
      }
    }

    // Create form
    Engine_Loader::loadClass($this->_formClass);
    $class = $this->_formClass;
    $this->_form = new $class($formArgs);
    $data = $this->getSession()->data;
    $form = $this->getForm();

    if( !empty($_SESSION['facebook_signup']) ) {
      try {
        $facebookTable = Engine_Api::_()->getDbtable('facebook', 'user');
        $facebook = $facebookTable->getApi();
        $settings = Engine_Api::_()->getDbtable('settings', 'core');
        if( $facebook && $settings->core_facebook_enable ) {
          // Load Faceboolk data
          $apiInfo = $facebook->api('/me?fields=first_name,last_name,birthday,picture,name,gender,email,locale');
          if( ($emailEl = $form->getElement('email')) && !$emailEl->getValue() ) {
            $emailEl->setValue($apiInfo['email']);
          }
          if( ($usernameEl = $form->getElement('username')) && !$usernameEl->getValue() ) {
            $usernameEl->setValue(preg_replace('/[^A-Za-z]/', '', $apiInfo['name']));
          }

          // Locale
          $localeObject = new Zend_Locale($apiInfo['locale']);
          if( ($localeEl = $form->getElement('locale')) && !$localeEl->getValue() ) {
            $localeEl->setValue($localeObject->toString());
          }
          if( ($languageEl = $form->getElement('language')) && !$languageEl->getValue() ) {
            if( isset($languageEl->options[$localeObject->toString()]) ) {
              $languageEl->setValue($localeObject->toString());
            } else if( isset($languageEl->options[$localeObject->getLanguage()]) ) {
              $languageEl->setValue($localeObject->getLanguage());
            }
          }

          $fb_data = array();
          $fb_keys = array('first_name', 'last_name', 'birthday', 'birthdate');
          foreach( $fb_keys as $key ) {
            if( isset($apiInfo[$key]) ) {
              $fb_data[$key] = $apiInfo[$key];
            }
          }
          if( isset($apiInfo['birthday']) && !empty($apiInfo['birthday']) ) {
            $fb_data['birthdate'] = date("Y-m-d", strtotime($fb_data['birthday']));
          }

          // populate fields, using Facebook data
          $struct = $this->_form->getFieldStructure();
          foreach( $struct as $fskey => $map ) {
            $field = $map->getChild();
            if( $field->isHeading() )
              continue;

            if( isset($field->type) && in_array($field->type, $fb_keys) ) {
              $el_key = $map->getKey();
              $el_val = $fb_data[$field->type];
              $el_obj = $this->_form->getElement($el_key);
              if( $el_obj instanceof Zend_Form_Element &&
                !$el_obj->getValue() ) {
                $el_obj->setValue($el_val);
              }
            }
          }
        }
      } catch( Exception $e ) {
        // Silence?
      }
    }

    // Attempt to preload information
    if( !empty($_SESSION['janrain_signup']) &&
      !empty($_SESSION['janrain_signup_info']) ) {
      try {
        $settings = Engine_Api::_()->getDbtable('settings', 'core');
        if( $settings->core_janrain_enable ) {
          $jr_info = $_SESSION['janrain_signup_info'];
          $jr_poco = @$_SESSION['janrain_signup_info']['merged_poco'];
          $jr_data = array();
          if( !empty($jr_info['displayName']) ) {
            if( false !== strpos($jr_info['displayName'], ' ') ) {
              list($jr_data['first_name'], $jr_data['last_name']) = explode(' ', $jr_info['displayName']);
            } else {
              $jr_data['first_name'] = $jr_info['displayName'];
            }
          }
          if( !empty($jr_info['name']['givenName']) ) {
            $jr_data['first_name'] = $jr_info['name']['givenName'];
          }
          if( !empty($jr_info['name']['familyName']) ) {
            $jr_data['last_name'] = $jr_info['name']['familyName'];
          }
          if( !empty($jr_info['email']) ) {
            $jr_data['email'] = $jr_info['email'];
          }
          if( !empty($jr_info['url']) ) {
            $jr_data['website'] = $jr_info['url'];
          }
          if( !empty($jr_info['birthday']) ) {
            $jr_data['birthdate'] = date("Y-m-d", strtotime($jr_info['birthday']));
          }

          if( !empty($jr_poco['url']) && false !== stripos($jr_poco['url'], 'www.facebook.com/profile.php?id=') ) {
            list($null, $jr_data['facebook']) = explode('www.facebook.com/profile.php?id=', $jr_poco['url']);
          } else if( !empty($jr_data['url']) && false !== stripos($jr_poco['url'], 'http://www.facebook.com/') ) {
            list($null, $jr_data['facebook']) = explode('http://www.facebook.com/', $jr_data['url']);
          }
          if( !empty($jr_poco['currentLocation']['formatted']) ) {
            $jr_data['location'] = $jr_poco['currentLocation']['formatted'];
          }
          if( !empty($jr_poco['religion']) ) {
            // Might not match any values
            $jr_data['religion'] = str_replace(' ', '_', strtolower($jr_poco['religion']));
          }
          if( !empty($jr_poco['relationshipStatus']) ) {
            // Might not match all values
            $jr_data['relationship_status'] = str_replace(' ', '_', strtolower($jr_poco['relationshipStatus']));
          }
          if( !empty($jr_poco['politicalViews']) ) {
            // Only works if text
            $jr_data['political_views'] = $jr_poco['politicalViews'];
          }

          // populate fields, using janrain data
          $struct = $this->_form->getFieldStructure();
          foreach( $struct as $fskey => $map ) {
            $field = $map->getChild();
            if( $field->isHeading() )
              continue;

            if( !empty($field->type) && !empty($jr_data[$field->type]) ) {
              $val = $jr_data[$field->type];
            } else if( !empty($field->alias) && !empty($jr_data[$field->alias]) ) {
              $val = $jr_data[$field->alias];
            } else {
              continue;
            }

            $el_key = $map->getKey();
            $el_val = $val;
            $el_obj = $this->_form->getElement($el_key);
            if( $el_obj instanceof Zend_Form_Element &&
              !$el_obj->getValue() ) {
              $el_obj->setValue($el_val);
            }
          }
        }
      } catch( Exception $e ) {
        echo $e;
        // Silence?
      }
    }

    if( Engine_Api::_()->getDbtable("modules", "core")->isModuleEnabled("sitelogin") ) {

      if( !empty($_SESSION['linkedin_signup']) ) {
        $loginEnable = Engine_Api::_()->getDbtable('linkedin', 'sitelogin')->linkedinIntegrationEnabled();
        if( empty($loginEnable) ) {
          return;
        }
        try {

          $settings = Engine_Api::_()->getDbtable('settings', 'core');
          $linkedinTable = Engine_Api::_()->getDbtable('linkedin', 'sitelogin');
          if( isset($_SESSION['access_token']) && !empty($_SESSION['access_token']) ) {
            $userDetails = Engine_Api::_()->getDbtable('linkedin', 'sitelogin')->fetch();
          }
          if( ($emailEl = $form->getElement('email')) && !$emailEl->getValue() ) {
            $emailEl->setValue($userDetails->emailAddress);
          }

          if( ($usernameEl = $form->getElement('username')) && !$usernameEl->getValue() ) {
            $usernameEl->setValue(preg_replace('/[^A-Za-z]/', '', $userDetails->firstName . $userDetails->lastName));
          }

          if( ($locationEl = $form->getElement('location')) && !$locationEl->getValue() ) {
            $locationEl->setValue(preg_replace('/[^A-Za-z]/', '', $userDetails->location->name));
          }
          $fb_data = array();
          $apiInfo['last_name'] = isset($userDetails->lastName) ? $userDetails->lastName : "";
          $apiInfo['first_name'] = isset($userDetails->firstName) ? $userDetails->firstName : "";
          $fb_data = array();
          $fb_keys = array('first_name', 'last_name', 'birthday', 'birthdate');
          foreach( $fb_keys as $key ) {
            if( isset($apiInfo[$key]) ) {
              $fb_data[$key] = $apiInfo[$key];
            }
          }
          if( isset($apiInfo['birthday']) && !empty($apiInfo['birthday']) ) {
            $fb_data['birthdate'] = date("Y-m-d", strtotime($fb_data['birthday']));
          }

          // populate fields, using Facebook data
          $struct = $this->_form->getFieldStructure();
          foreach( $struct as $fskey => $map ) {
            $field = $map->getChild();
            if( $field->isHeading() )
              continue;

            if( isset($field->type) && in_array($field->type, $fb_keys) ) {
              $el_key = $map->getKey();
              $el_val = $fb_data[$field->type];
              $el_obj = $this->_form->getElement($el_key);
              if( $el_obj instanceof Zend_Form_Element &&
                !$el_obj->getValue() ) {
                $el_obj->setValue($el_val);
              }
            }
          }
        } catch( Exception $e ) {
          // Silence?
        }
      }

      if( !empty($_SESSION['google_signup']) ) {
        try {
          $settings = Engine_Api::_()->getDbtable('settings', 'core');

          $loginEnable = Engine_Api::_()->getDbtable('google', 'sitelogin')->googleIntegrationEnabled();
          if( empty($loginEnable) ) {
            return;
          }
          $googleTable = Engine_Api::_()->getDbtable('google', 'sitelogin');
          $apiInfoObj = $googleTable->getGoogleInstance();

          if( ($emailEl = $form->getElement('email')) && !$emailEl->getValue() ) {
            $emailEl->setValue($apiInfoObj->email);
          }
          if( ($usernameEl = $form->getElement('username')) && !$usernameEl->getValue() ) {
            $usernameEl->setValue(preg_replace('/[^A-Za-z]/', '', $apiInfoObj->name));
          }

          // Locale
          $localeObject = new Zend_Locale($apiInfoObj->locale);
          if( ($localeEl = $form->getElement('locale')) && !$localeEl->getValue() ) {
            $localeEl->setValue($localeObject->toString());
          }
          if( ($languageEl = $form->getElement('language')) && !$languageEl->getValue() ) {
            if( isset($languageEl->options[$localeObject->toString()]) ) {
              $languageEl->setValue($localeObject->toString());
            } else if( isset($languageEl->options[$localeObject->getLanguage()]) ) {
              $languageEl->setValue($localeObject->getLanguage());
            }
          }
          $fb_data = array();
          $apiInfo['last_name'] = isset($apiInfoObj->familyName) ? $apiInfoObj->familyName : "";
          $apiInfo['first_name'] = isset($apiInfoObj->givenName) ? $apiInfoObj->givenName : "";
          $fb_data = array();
          $fb_keys = array('first_name', 'last_name', 'birthday', 'birthdate');
          foreach( $fb_keys as $key ) {
            if( isset($apiInfo[$key]) ) {
              $fb_data[$key] = $apiInfo[$key];
            }
          }
          if( isset($apiInfo['birthday']) && !empty($apiInfo['birthday']) ) {
            $fb_data['birthdate'] = date("Y-m-d", strtotime($fb_data['birthday']));
          }

          // populate fields, using Facebook data
          $struct = $this->_form->getFieldStructure();
          foreach( $struct as $fskey => $map ) {
            $field = $map->getChild();
            if( $field->isHeading() )
              continue;

            if( isset($field->type) && in_array($field->type, $fb_keys) ) {
              $el_key = $map->getKey();
              $el_val = $fb_data[$field->type];
              $el_obj = $this->_form->getElement($el_key);
              if( $el_obj instanceof Zend_Form_Element &&
                !$el_obj->getValue() ) {
                $el_obj->setValue($el_val);
              }
            }
          }
        } catch( Exception $e ) {
          // Silence?
        }
      }
    }
    if( !empty($data) ) {
      foreach( $data as $key => $val ) {
        $el = $this->_form->getElement($key);
        if( $el instanceof Zend_Form_Element ) {
          $el->setValue($val);
        }
      }
    }
    if( $this->_otpPlugin ) {
      $this->_form = $this->_otpPlugin->addFields($this->_form);
    }
    return $this->_form;
  }

  public function onView()
  {
    
  }

  public function getRegistry()
  {
    return $this->_registry;
  }

  public function onSubmit(Zend_Controller_Request_Abstract $request)
  {
    if( $this->_otpPlugin ) {
      $this->_otpPlugin->onSubmitBefore($request);
    }

    // Form was valid
    if( $this->getForm()->isValid($request->getPost()) ) {
      $formValues = array();
      foreach( $this->getForm()->getValues() as $key => $element ) {
        if( count(explode('_', $key)) == 3 ) {
          continue;
        } else {
          $formValues[$key] = $element;
        }
      }

      $this->getSession()->data = $this->getForm()->getProcessedValues();
      $this->getSession()->data['otherValues'] = $formValues;
      if( $this->_otpPlugin ) {
        $this->_otpPlugin->onSubmitAfter($request);
      }
      $this->getSession()->active = false;
      $this->onSubmitIsValid();
      return true;
    }

    // Form was not valid
    else {
      $this->getSession()->active = true;
      $this->onSubmitNotIsValid();
      return false;
    }
  }

  public function onAccountProcess()
  {
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $random = ($settings->getSetting('user.signup.random', 0) == 1);
    $emailadmin = ($settings->getSetting('user.signup.adminemail', 0) == 1);
    if( $emailadmin ) {
      // the signup notification is emailed to the first SuperAdmin by default
      $users_table = Engine_Api::_()->getDbtable('users', 'user');
      $users_select = $users_table->select()
        ->where('level_id = ?', 1)
        ->where('enabled >= ?', 1);
      $super_admin = $users_table->fetchRow($users_select);
    }
    $data = $this->getSession()->data['otherValues'];

    // Add email and code to invite session if available
    $inviteSession = new Zend_Session_Namespace('invite');
    if( isset($data['email']) ) {
      $inviteSession->signup_email = $data['email'];
    }
    if( isset($data['code']) ) {
      $inviteSession->signup_code = $data['code'];
    }

    if( $random ) {
      $data['password'] = Engine_Api::_()->user()->randomPass(10);
    }

    if( !empty($data['language']) ) {
      $data['locale'] = $data['language'];
    }

    // Create user
    // Note: you must assign this to the registry before calling save or it
    // will not be available to the plugin in the hook
    $this->_registry->user = $user = Engine_Api::_()->getDbtable('users', 'user')->createRow();
    $user->setFromArray($data);
    $user->save();

    Engine_Api::_()->user()->setViewer($user);

    // Increment signup counter
    Engine_Api::_()->getDbtable('statistics', 'core')->increment('user.creations');

    if( $user->verified && $user->enabled ) {
      // Create activity for them
      Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($user, $user, 'signup');
      // Set user as logged in if not have to verify email
      Engine_Api::_()->user()->getAuth()->getStorage()->write($user->getIdentity());
    }

    $mailType = null;
    $mailParams = array(
      'host' => $_SERVER['HTTP_HOST'],
      'email' => $user->email,
      'date' => time(),
      'recipient_title' => $user->getTitle(),
      'recipient_link' => $user->getHref(),
      'recipient_photo' => $user->getPhotoUrl('thumb.icon'),
      'object_link' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login', true),
    );

    // Add password to email if necessary
    if( $random ) {
      $mailParams['password'] = $data['password'];
    }

    // Mail stuff
    switch( $settings->getSetting('user.signup.verifyemail', 0) ) {
      case 0:
        // only override admin setting if random passwords are being created
        if( $random ) {
          $mailType = 'core_welcome_password';
        }
        if( $emailadmin ) {
          $mailAdminType = 'notify_admin_user_signup';
          $siteTimezone = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.locale.timezone', 'America/Los_Angeles');
          $date = new DateTime("now", new DateTimeZone($siteTimezone));
          $mailAdminParams = array(
            'host' => $_SERVER['HTTP_HOST'],
            'email' => $user->email,
            'date' => $date->format('F j, Y, g:i a'),
            'recipient_title' => $super_admin->displayname,
            'object_title' => $user->displayname,
            'object_link' => $user->getHref(),
          );
        }
        break;

      case 1:
        // send welcome email
        $mailType = ($random ? 'core_welcome_password' : 'core_welcome');
        if( $emailadmin ) {
          $mailAdminType = 'notify_admin_user_signup';
          $siteTimezone = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.locale.timezone', 'America/Los_Angeles');
          $date = new DateTime("now", new DateTimeZone($siteTimezone));
          $mailAdminParams = array(
            'host' => $_SERVER['HTTP_HOST'],
            'email' => $user->email,
            'date' => $date->format('F j, Y, g:i a'),
            'recipient_title' => $super_admin->displayname,
            'object_title' => $user->getTitle(),
            'object_link' => $user->getHref(),
          );
        }
        break;

      case 2:
        // verify email before enabling account
        $verify_table = Engine_Api::_()->getDbtable('verify', 'user');
        $verify_row = $verify_table->createRow();
        $verify_row->user_id = $user->getIdentity();
        $verify_row->code = md5($user->email
          . $user->creation_date
          . $settings->getSetting('core.secret', 'staticSalt')
          . (string) rand(1000000, 9999999));
        $verify_row->date = $user->creation_date;
        $verify_row->save();

        $mailType = ($random ? 'core_verification_password' : 'core_verification');

        $mailParams['object_link'] = Zend_Controller_Front::getInstance()->getRouter()->assemble(array(
          'action' => 'verify',
          'email' => $user->email,
          'verify' => $verify_row->code
          ), 'user_signup', true);

        if( $emailadmin ) {
          $mailAdminType = 'notify_admin_user_signup';

          $mailAdminParams = array(
            'host' => $_SERVER['HTTP_HOST'],
            'email' => $user->email,
            'date' => date("F j, Y, g:i a"),
            'recipient_title' => $super_admin->displayname,
            'object_title' => $user->getTitle(),
            'object_link' => $user->getHref(),
          );
        }
        break;

      default:
        // do nothing
        break;
    }

    if( !empty($mailType) ) {
      $this->_registry->mailParams = $mailParams;
      $this->_registry->mailType = $mailType;
      // Moved to User_Plugin_Signup_Fields
      // Engine_Api::_()->getApi('mail', 'core')->sendSystem(
      //   $user,
      //   $mailType,
      //   $mailParams
      // );
    }

    if( !empty($mailAdminType) ) {
      $this->_registry->mailAdminParams = $mailAdminParams;
      $this->_registry->mailAdminType = $mailAdminType;
      // Moved to User_Plugin_Signup_Fields
      // Engine_Api::_()->getApi('mail', 'core')->sendSystem(
      //   $user,
      //   $mailType,
      //   $mailParams
      // );
    }

    // Attempt to connect facebook
    if( !empty($_SESSION['facebook_signup']) ) {
      try {
        $facebookTable = Engine_Api::_()->getDbtable('facebook', 'user');
        $facebook = $facebookTable->getApi();
        $settings = Engine_Api::_()->getDbtable('settings', 'core');
        if( $facebook && $settings->core_facebook_enable ) {
          $facebookTable->insert(array(
            'user_id' => $user->getIdentity(),
            'facebook_uid' => $facebook->getUser(),
            'access_token' => $facebook->getAccessToken(),
            //'code' => $code,
            'expires' => 0, // @todo make sure this is correct
          ));
        }
      } catch( Exception $e ) {
        // Silence
        if( 'development' == APPLICATION_ENV ) {
          echo $e;
        }
      }
    }

    // Attempt to connect twitter
    if( !empty($_SESSION['twitter_signup']) ) {
      try {
        $twitterTable = Engine_Api::_()->getDbtable('twitter', 'user');
        $twitter = $twitterTable->getApi();
        $twitterOauth = $twitterTable->getOauth();
        $settings = Engine_Api::_()->getDbtable('settings', 'core');
        if( $twitter && $twitterOauth && $settings->core_twitter_enable ) {
          $accountInfo = $twitter->account->verify_credentials();
          $twitterTable->insert(array(
            'user_id' => $user->getIdentity(),
            'twitter_uid' => $accountInfo->id,
            'twitter_token' => $twitterOauth->getToken(),
            'twitter_secret' => $twitterOauth->getTokenSecret(),
          ));
        }
      } catch( Exception $e ) {
        // Silence?
        if( 'development' == APPLICATION_ENV ) {
          echo $e;
        }
      }
    }

    // Attempt to connect twitter
    if( !empty($_SESSION['janrain_signup']) ) {
      try {
        $janrainTable = Engine_Api::_()->getDbtable('janrain', 'user');
        $settings = Engine_Api::_()->getDbtable('settings', 'core');
        $info = $_SESSION['janrain_signup_info'];
        if( $settings->core_janrain_enable ) {
          $janrainTable->insert(array(
            'user_id' => $user->getIdentity(),
            'identifier' => $info['identifier'],
            'provider' => $info['providerName'],
            'token' => (string) @$_SESSION['janrain_signup_token'],
          ));
        }
      } catch( Exception $e ) {
        // Silence?
        if( 'development' == APPLICATION_ENV ) {
          echo $e;
        }
      }
    }
    if( Engine_Api::_()->getDbtable("modules", "core")->isModuleEnabled("sitelogin") ) {
      // Attempt to connect google
      if( !empty($_SESSION['google_signup']) ) {
        try {
          $googleTable = Engine_Api::_()->getDbtable('google', 'sitelogin');
          $google = $googleTable->getApi();

          $settings = Engine_Api::_()->getDbtable('settings', 'core');
          $tokens = Zend_Json::decode($_SESSION['access_token']);

          $loginEnable = Engine_Api::_()->getDbtable('google', 'sitelogin')->googleIntegrationEnabled();

          if( !empty($loginEnable) && isset($google->id) && !empty($google->id) ) {
            $googleTable->insert(array(
              'user_id' => $user->getIdentity(),
              'google_id' => $google->id,
              'access_token' => $tokens['access_token'],
              'expires' => 0,
            ));
          }
        } catch( Exception $e ) {
          // Silence
          if( 'development' == APPLICATION_ENV ) {
            echo $e;
          }
        }
      }

      // Attempt to connect linkedin
      if( !empty($_SESSION['linkedin_signup']) ) {
        try {
          $loginEnable = Engine_Api::_()->getDbtable('linkedin', 'sitelogin')->linkedinIntegrationEnabled();
          $linkedinTable = Engine_Api::_()->getDbtable('linkedin', 'sitelogin');
          if( isset($_SESSION['access_token']) && !empty($_SESSION['access_token']) ) {
            $userDetails = Engine_Api::_()->getDbtable('linkedin', 'sitelogin')->fetch();
          }

          if( !empty($loginEnable) && $userDetails->id ) {
            $linkedinTable->insert(array(
              'user_id' => $user->getIdentity(),
              'linkedin_id' => $userDetails->id,
              'access_token' => $_SESSION['access_token'],
              'expires' => 0,
            ));
          }
        } catch( Exception $e ) {
          // Silence
          if( 'development' == APPLICATION_ENV ) {
            echo $e;
          }
        }
      }
    }
  }

  public function onProcess()
  {
    // In this case, the step was placed before the account step.
    // Register a hook to this method for onUserCreateAfter
    if( !$this->_registry->user ) {
      $this->onAccountProcess();
      if( $this->_otpPlugin ) {
        $this->_otpPlugin->onProcess();
      }
    }
    $user = $this->_registry->user;

    // Preload profile type field stuff
    $profileTypeField = $this->getProfileTypeField();
    if( $profileTypeField ) {
      $accountSession = new Zend_Session_Namespace('Sitequicksignup_Plugin_Signup_Fields');
      $profileTypeValue = @$accountSession->data['profile_type'];
      if( $profileTypeValue ) {
        $values = Engine_Api::_()->fields()->getFieldsValues($user);
        $valueRow = $values->createRow();
        $valueRow->field_id = $profileTypeField->field_id;
        $valueRow->item_id = $user->getIdentity();
        $valueRow->value = $profileTypeValue;
        $valueRow->save();
      } else {
        $topStructure = Engine_Api::_()->fields()->getFieldStructureTop('user');
        if( count($topStructure) == 1 && $topStructure[0]->getChild()->type == 'profile_type' ) {
          $profileTypeField = $topStructure[0]->getChild();
          $options = $profileTypeField->getOptions();
          if( count($options) == 1 ) {
            $values = Engine_Api::_()->fields()->getFieldsValues($user);
            $valueRow = $values->createRow();
            $valueRow->field_id = $profileTypeField->field_id;
            $valueRow->item_id = $user->getIdentity();
            $valueRow->value = $options[0]->option_id;
            $valueRow->save();
          }
        }
      }
    }

    // Save them values
    $form = $this->getForm()->setItem($user);
    $form->setProcessedValues($this->getSession()->data);
    $form->saveValues();

    $aliasValues = Engine_Api::_()->fields()->getFieldsValuesByAlias($user);
    $user->setDisplayName($aliasValues);
    $user->save();

    // Send Welcome E-mail
    if( isset($this->_registry->mailType) && $this->_registry->mailType ) {
      $mailType = $this->_registry->mailType;
      $mailParams = $this->_registry->mailParams;
      Engine_Api::_()->getApi('mail', 'core')->sendSystem(
        $user, $mailType, $mailParams
      );
    }

    // Send Notify Admin E-mail
    if( isset($this->_registry->mailAdminType) && $this->_registry->mailAdminType ) {
      $mailAdminType = $this->_registry->mailAdminType;
      $mailAdminParams = $this->_registry->mailAdminParams;
      Engine_Api::_()->getApi('mail', 'core')->sendSystem(
        $user, $mailAdminType, $mailAdminParams
      );
    }
  }

  public function getProfileTypeField()
  {
    $topStructure = Engine_Api::_()->fields()->getFieldStructureTop('user');
    if( count($topStructure) == 1 && $topStructure[0]->getChild()->type == 'profile_type' ) {
      return $topStructure[0]->getChild();
    }
    return null;
  }

}
