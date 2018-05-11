<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_Plugin_Core
{

  //DELETE USERS BELONGINGS BEFORE THAT USER DELETION
	public function onUserDeleteBefore($event)
  {
    $payload = $event->getPayload();
    if( $payload instanceof User_Model_User ) {
    	
			//GET FEEDBACK TABLE
      $feedbackTable = Engine_Api::_()->getDbtable('feedbacks', 'feedback');
      $feedbackSelect = $feedbackTable->select()->where('owner_id = ?', $payload->getIdentity());
      foreach( $feedbackTable->fetchAll($feedbackSelect) as $feedback ) {

        //DELETE BLOCKED-USER						
				Engine_Api::_()->getDbtable('blockusers', 'feedback')->delete(array('blockuser_id = ?' => $payload->getIdentity()));

				//CALL A FUNCTION TO DELETE FEEDBACK BELONGINGS
        Engine_Api::_()->getItem('feedback', $feedback->feedback_id)->delete();
      }
    }
  }
  
	//RENDER FEEDBACK BUTTON
	public function onRenderLayoutDefault($event)
  {
		global $feedback_handler;
  	$button_color1 = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.button.color1', '#0267cc');
  	$button_color2 = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.button.color2', '#ff0000');
  	$button_position = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.button.position', 1);
		$isActive = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.isActivate', 0);
  	$view = $event->getPayload();
  	$stylecolor = sprintf('%s',$button_color1);
  	$mouseovercolor = sprintf('%s',$button_color2);

		if(empty($button_position) || $button_position == 1) {
	  switch ($button_position) {
	    case 0:
	       $classname = sprintf('%s','smoothbox feedback-button feedback-button-right');
	        break;
	    case 1:
	    default:
	        $classname = sprintf('%s','smoothbox feedback-button-left');
	        break;
		}
  	  	
  	$base_url_feedback = Zend_Controller_Front::getInstance()->getBaseUrl();
  	$base_url_feedback = sprintf('%s', $base_url_feedback);

    if( ($view instanceof Zend_View) && !empty($feedback_handler) && !empty($isActive) ) {
			$feedback_text = Zend_Registry::get('Zend_Translate')->_('Feedback');
      $script = <<<EOF
      
  var feedbackHandler;
  en4.core.runonce.add(function() {
    try {
      feedbackHandler = new FeedbackHandler({
				'feedback_text' : '{$feedback_text}',
        'baseUrl' : '{$base_url_feedback}',
        'enableFeedback' : true,
        'stylecolor' : '{$stylecolor}',
        'mouseovercolor' : '{$mouseovercolor}',
        'classname' : '{$classname}'
      });

        feedbackHandler.start();
      window._feedbackHandler = feedbackHandler;
    } catch( e ) {
      //if( \$type(console) ) console.log(e);
    }
  });
EOF;
      
      $view->headScript()
        ->appendFile($view->layout()->staticBaseUrl . 'application/modules/Feedback/externals/scripts/core_feedbackbutton.js')
        ->appendScript($script);
    }
		}
        
        if(Engine_Api::_()->seaocore()->isMobile()) {
            
      $scriptForMobile = <<<EOF

  en4.core.runonce.add(function() {

    if(document.getElement('.feedback_button_create')) {    
      document.getElement('.feedback_button_create').removeClass('smoothbox');
    }
              
    if(document.getElement('.feedback-button')) {    
      document.getElement('.feedback-button').removeClass('smoothbox');
    }   
        
    if(document.getElement('.feedback-button-left')) {    
      document.getElement('.feedback-button-left').removeClass('smoothbox');
    }        
        
    if(document.getElement('.feedback-button-right')) {    
      document.getElement('.feedback-button-right').removeClass('smoothbox');
    }                
        
    if(document.getElement('.icon_feedback_new')) {    
      document.getElement('.icon_feedback_new').removeClass('smoothbox');
    }                            
                 
  });
EOF;
      
    $front = Zend_Controller_Front::getInstance();
    $module = $front->getRequest()->getModuleName();
    $controller = $front->getRequest()->getControllerName();

    if($module == 'feedback' && $controller == 'index') {
        
      $scriptForMobileOther = <<<EOF

  en4.core.runonce.add(function() {

    if(document.getElement('.tabs ul.navigation .smoothbox')) {
      document.getElement('.tabs ul.navigation .smoothbox').removeClass('smoothbox');
    }                           
                 
  });
EOF;
        
        $view->headScript()->appendScript($scriptForMobileOther);
    }
      
            
            $view->headScript()->appendScript($scriptForMobile);
            
        }
        
  }  
  
  public function onRenderLayoutDefaultSimple($event) {
      // Forward
      return $this->onRenderLayoutDefault($event, 'simple');
  }  
}
