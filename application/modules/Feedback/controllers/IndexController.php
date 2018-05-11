<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: IndexController.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_IndexController extends Seaocore_Controller_Action_Standard
{

  protected $_navigation;
  
  //ACTION FOR SHOW FEEDBACKS
	public function browseAction()
  { 
      
  	//CHECK THAT FEEDBACK FORUM SHOULD BE VISIBLE OR NOT
  	$show_browse = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.show.browse', 1);
  	if(empty($show_browse)) {
  		return $this->_forward('requireauth', 'error', 'core');
  	}
  
  	//GET VIEWER INFORMATION
  	$viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    //PUBLIC CAN VIEW FEEDBACK OR NOT
  	$feedback_public = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.public', 1);
  	if($feedback_public == 0 && empty($viewer_id)) {
			return $this->_forward('requireauth', 'error', 'core');
  	}

		//SEND MESSAGE VARIABLE TO TPL
    $this->view->is_msg = (int) $this->_getParam('success_msg');
    
		//REDIRECT TO LOGIN PAGE IF NON-LOGGED USER CLICK ON VOTE
  	$check_anonymous_vote = (int)$this->_getParam('check_anonymous_vote');
  	if($check_anonymous_vote == 1){
  		if( !$this->_helper->requireUser()->isValid() ) return;
  	}

		//VALIDATION CHECKS
    if($viewer_id == 0) {
    	$this->view->browse_url = $browse_url = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('check_browse_id' => 1), 'feedback_browse');
    	
   		$check_browse_id = (int) $this->_getParam('check_browse_id');
 			if($check_browse_id == 1) { if( !$this->_helper->requireUser()->isValid() ) return; }
    }      

    // Render
    $this->_helper->content
        //->setNoRender()
        ->setEnabled()
        ;
  }
  
	//ACTION FOR SHOW USERS FEEDBACK
	public function manageAction()
	{
		//USER VALIDATION
  	if( !$this->_helper->requireUser()->isValid() ) return;
  	
    //CHECK THAT FEEDBACK FORUM SHOULD BE VISIBLE OR NOT
  	$this->view->show_browse = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.show.browse', 1);
  	
  	//GET VIEWER INFORMATION
  	$viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();
    
    //IMAGE UPLOAD IS ALLOWED OR NOT
    $this->view->allow_upload = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.allow.image', 1);

	  //CHECK IF IP BLOCK BY ADMIN FOR POSTING FEEDBACK
		$this->view->can_create = Engine_Api::_()->feedback()->canCreateFeedback($_SERVER['REMOTE_ADDR'], $viewer_id);
	  	
	  //PROCESS SEARCH FORM
		$this->view->form = $form = new Feedback_Form_Search(); 
		
		//GET FORM VALUES
    $this->view->formValues = $values = $_GET;

		//POPULATING THE FORM
    $form->populate($values);
    $this->view->assign($values);

    $values['user_id'] = $viewer_id;
    $values['viewer_id'] = $viewer_id;
    $values['can_vote'] = "1";
    
		//GET CUSTOM FIELD VALUES
		$customFieldValues = array_intersect_key($values, $form->getFieldElements());

		//GET PAGINATION
		$page = 1;
		if(isset($_GET['page']) && !empty($_GET['page'])) {
			$page = $_GET['page'];
		}

    //GET PAGINATOR
    $this->view->paginator = $paginator = Engine_Api::_()->getDbtable('feedbacks', 'feedback')->getFeedbacksPaginator($values, $customFieldValues);
    $items_per_page = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.page', 10);
    $paginator->setItemCountPerPage($items_per_page);
    $paginator->setCurrentPageNumber($page);
    
    // Render
    $this->_helper->content
        //->setNoRender()
        ->setEnabled()
        ;    
	}
 
	//ACTION FOR CREATE FEEDBACK
	public function createAction()
	{		
    global $feedback_itemCreate;
    
    //CLEAR CACHE ON FORM DISPLAY, ALL FIELDS SHOULD BE EMPTY.(FOR SITEMOBILE)
    $this->view->clear_cache = true;
    $this->view->noDomCache = true;
    
		//GET VIEWER INFORMATION
		$viewer = Engine_Api::_()->user()->getViewer();
	  $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

		$currentbase_time = time();
		$base_result_time = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.upgrade.time');
		$feedback_time_var = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.upgrade.limit');
		$feedbackModHost = str_replace('www.', '', strtolower($_SERVER['HTTP_HOST']));
		
		//GET NAVIGATION
		$this->view->navigation = $this->getNavigation();

    //CHECK THAT FEEDBACK FORUM SHOULD BE VISIBLE OR NOT
	  $this->view->show_browse = $show_browse = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.show.browse', 1);
	  
	  //NON-LOGGED IN USER CAN CREATE FEEDBACK OR NOT
	  $this->view->feedback_post = $feedback_post = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.post', 0);

		//GET VALUE FOR CHECKING THAT WHO CAN VIEW FEEDBACK
	  $this->view->feedback_public = $feedback_post = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.public', 1);
		$check_result_show = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.check.var');
		$controllersettings_result_show = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.license.key');
		$controller_result_lenght = strlen($controllersettings_result_show);
	    
	  //CHECK IF IP BLOCK BY ADMIN FOR POSTING FEEDBACK
		$this->view->can_create = Engine_Api::_()->feedback()->canCreateFeedback($_SERVER['REMOTE_ADDR'], $viewer_id);
    
	  //FORM GENERATION
    $this->view->form = $form = new Feedback_Form_Create();
    
    //IF LOGGED IN USER POST FEEDBACK THAN REMOVE email AND name FILED FROM FORM
    if(!empty($viewer_id)) {
   		$form->removeElement('anonymous_email');
   		$form->removeElement('anonymous_name');
    }
   
		//IF DIS-ABLE FORUM OR NON-LOGGED IN USER
		if(empty($show_browse) || empty($viewer_id)) {
			$form->removeElement('default_visibility');
		}

		if( ($currentbase_time - $base_result_time > $feedback_time_var) && empty($check_result_show) ) {
			if( $controller_result_lenght != 20 ) {
				Engine_Api::_()->getApi('settings', 'core')->setSetting('feedback.isHost.set', 1);
				Engine_Api::_()->getApi('settings', 'core')->setSetting('feedback.blockip.form', 1);
				return;
			} else {
					Engine_Api::_()->getApi('settings', 'core')->setSetting('feedback.check.var', 1);
			}
		}
    
		//GET FEEDBACK TABLE
		$tableFeedback = Engine_Api::_()->getItemTable('feedback');

    //IF NOT POST OR FORM NOT VALID, RETUREN
    if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) ) {
    	
			//BEGIN TRANSACTION
      $db = $tableFeedback->getAdapter();
      $db->beginTransaction();

      try {
        
        if(!empty($viewer_id)) {
	        $values = array_merge($form->getValues(), array(
	          'owner_type' => $viewer->getType(),
	          'owner_id' => $viewer_id,
	        ));
        }
        else {
        	 $values = array_merge($form->getValues(), array(
	          'owner_type' => 'null',
	          'owner_id' => 0,
	        ));
        
        }

				if( empty($feedback_itemCreate) ) {
					return;
				}

				$feedbackHost = convert_uuencode($feedbackModHost);
				$isHostSet = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.isHost.set', 0);
				if( empty($isHostSet) ) {
					Engine_Api::_()->getApi('settings', 'core')->setSetting('feedback.blockip.form', $feedbackHost);
				}

				//SAVE IN DATABASE
        $feedback = $tableFeedback->createRow();
        $feedback->setFromArray($values);
        
	       //CHECKS FOR DEFAULT VISIBILITY
		    $feedback_default_visibility = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.default.visibility', 'public');
		    $show_browse = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.show.browse', 1);
		    if(!empty($show_browse)) {
					if ($feedback_default_visibility == 'private' || empty($viewer_id)) {
			  			$access = 'private';
					} 
					else {
			    		$access = $values['default_visibility'];
					}
		    }
		    else {
		    	$access = 'private';
		    }

				//SAVE OTHER DETAILS
				$feedback->feedback_private = $access;
				$feedback->ip_address = $_SERVER['REMOTE_ADDR'];
				$feedback->browser_name = $_SERVER['HTTP_USER_AGENT'];
				
				if($access == 'private') {
					$feedback->search = 0;
				}
				else {
					$feedback->search = 1;
				}
				
				$feedback->feedback_description = nl2br($values['feedback_description']);
				$feedback->save();
          
        //PRIVACY WORK
        $auth = Engine_Api::_()->authorization()->context;  
      	$roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'everyone');
        $auth_max = "everyone";

        $viewMax = array_search($auth_max, $roles);
        foreach($roles as $i => $role ){
          $auth->setAllowed($feedback, $role, 'view', ($i <= $viewMax));
        }

  			$commentMax = array_search($auth_max, $roles);
				foreach($roles as $i => $role) {
					$auth->setAllowed($feedback, $role, 'comment', ($i <= $commentMax));
  			}

				//ADDING TAGS
        if (!empty($values['tags'])) {
          $tags = preg_split('/[,]+/', $values['tags']);
					$tags = array_filter(array_map("trim", $tags));
          $feedback->tags()->addTagMaps($viewer, $tags);
        }

				//CUSTOM FIELD WORK
				$customfieldform = $form->getSubForm('fields');
        $customfieldform->setItem($feedback);
        $customfieldform->saveValues();
				//END CUSTOM FIELD WORK
        
				//ACTIVITY FEED WORK IF FEEDBACK IS NOT PRIVATE
        if ($access != 'private'){
          $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $feedback, 'feedback_new');

          if($action!=null){
            Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $feedback);
          } 
        }

				//COMMIT
        $db->commit();
        
        //GET VALUE FOR CHECKING THAT ADMIN GET EMAIL ALERT OR NOT
        $feedback_email_notify = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.email.notify', 1);
        
        //EMAIL WORK START FROM HERE
        if($feedback_email_notify) {
        	if(!empty($feedback->owner_id)) {
	        	$owner_id = $feedback->owner_id;
	      		$owner = Engine_Api::_()->getItem('user', $owner_id);
	      		$owner_name = $owner->username;
        	}
        	else {
						$owner_name = 'Anonymous user';
        	}

			  	$creation_date = Engine_Api::_()->seaocore()->seaocoreTruncateText($feedback->creation_date, 10);
			  	$email = Engine_Api::_()->getApi('settings', 'core')->core_mail_from;									
         	Engine_Api::_()->getApi('mail', 'core')->sendSystem($email, 'notify_feedback_create', array(
         			'feedback_title' => $feedback->feedback_title,
         			'feedback_owner' => $owner_name,
         			'feedback_status' => $feedback->feedback_private,
         			'feedback_date' => $creation_date,
         			'email' => $email,
         			'link' => 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('feedback_id'=>$feedback->feedback_id, 'user_id' => $feedback->owner_id, 'slug' => $feedback->getSlug()), 'feedback_detail_view'),
							'browse_link' => '<a href = http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'feedback_browse') . ">here</a>",
     			));      
				}
    
				//REDIRECT TO IMAGE UPLOAD PAGE IF
				$allow_upload = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.allow.image', 1);
                                 

        if (Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {

                if($allow_upload == 1) {
                        $session = new Zend_Session_Namespace();
                        $session->feedback_success = $feedback->feedback_id;
                        return $this->_helper->redirector->gotoRoute(array('feedback_id'=>$feedback->feedback_id), 'feedback_success', true);
                }
                elseif(!empty($viewer_id)) {
                        $url = $this->_helper->url->url(array(), 'feedback_manage');
                }
                elseif(!empty($show_browse)) {
                        $url = $this->_helper->url->url(array('success_msg' => 1), 'feedback_browse');
                }
                else {
                        $url = $this->_helper->url->url(array(), 'home');
                }


                if($allow_upload != 1) {
                        $this->_forward('success', 'utility', 'core', array(
                                'smoothboxClose' => true,
                                'parentRedirect' => $url,
                                'parentRedirectTime' => '15',
                                'format'=> 'smoothbox',
                                'messages' => Zend_Registry::get('Zend_Translate')->_('You have successfully created feedback.')
                        )); 
                } 

        }else{
        // Redirect to the view page of feedback
        return $this->_redirectCustom($feedback->getHref(), array('prependBase' => false));
        }        
      }
		
      catch( Exception $e ) {
        $db->rollBack();
        throw $e;
       }
	  }
	  
		//SEND STATUS TO TPL
	  $this->view->show_status = Engine_Api::_()->getDbtable('status', 'feedback')->getStatus();
	  
	  //GET MOST VOTED FEEDBACKS
    $this->view->voteFeedback = $tableFeedback->viewerVotedFeedback($viewer_id);
	}

	public function newcreateAction()
	{
		//GET VIEWER INFORMATION
		$viewer = Engine_Api::_()->user()->getViewer();
	  $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

		//GET NAVIGATION
		$this->view->navigation = $this->getNavigation();
	  
    //CHECK THAT FEEDBACK FORUM SHOULD BE VISIBLE OR NOT
	  $this->view->show_browse = $show_browse = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.show.browse', 1);
	  
	  //GET VALUE FOR CHECKING THAT WHO CAN CREATE FEEDBACK
	  $this->view->feedback_post = $feedback_post = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.post', 0);
	    
	  //CHECK IF IP BLOCK BY ADMIN FOR POSTING FEEDBACK
		$tmTable = Engine_Api::_()->getitemTable('feedback_blockip');
		$tmName =  $tmTable->info('name');     
		
		$checkPost = $tmTable->select()
							 ->setIntegrityCheck(false)
						   	 ->from($tmName, array('blockip_address', 'blockip_feedback'))
							 ->where('blockip_address = ?', $_SERVER['REMOTE_ADDR']);
		$rows = $tmTable->fetchAll($checkPost)->toArray();
		if(!empty($rows)) {
			$this->view->check_blockip_feedback = $rows[0]['blockip_feedback'];
		}
	
    //CHECK IF USER BLOCK BY ADMIN FOR POSTING FEEDBACK
    $userBlock = Engine_Api::_()->getItem('feedback_blockuser', $viewer_id);
    if(!empty($userBlock)) {
    	$this->view->check_block_feedback = $userBlock->block_feedback;
    }
	  
		//FORM GENERATION
    $this->view->form = $form = new Feedback_Form_Create();
    
    //IF LOGGED IN USER POST FEEDBACK THAN REMOVE email AND name FILED FROM FORM
    if(!empty($viewer_id)) {
   		$form->removeElement('anonymous_email');
   		$form->removeElement('anonymous_name');
    }
    
		if(empty($show_browse) || empty($viewer_id)) {
			$form->removeElement('default_visibility');
		}
    
    //IF NOT POST OR FORM NOT VALID, RETUREN
    if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) ) {

			//GET FEEDBACK TABLE
    	$table = Engine_Api::_()->getItemTable('feedback');

			//BEGIN TRANSACTION
      $db = $table->getAdapter();
      $db->beginTransaction();

      try {
        
        if(!empty($viewer_id)) {
	        $values = array_merge($form->getValues(), array(
	          'owner_type' => $viewer->getType(),
	          'owner_id' => $viewer_id,
	        ));
        }
        else {
        	 $values = array_merge($form->getValues(), array(
	          'owner_type' => 'null',
	          'owner_id' => 0,
	        ));
        }

				//SAVE IN DATABASE
        $feedback = $table->createRow();
        $feedback->setFromArray($values);
        
	       //CHECKS FOR DEFAULT VISIBILITY	
		    $feedback_default_visibility = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.default.visibility', 'public');
		    $show_browse = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.show.browse', 1);
		    if(!empty($show_browse)) {
					if ($feedback_default_visibility == 'private' || empty($viewer_id)) {
			  			$access = 'private';
					} 
					else {
			    		$access = $values['default_visibility'];
					}
		    }
		    else {
		    	$access = 'private';
		    }

				//GET OTHER DETAIL
				$feedback->feedback_private = $access;
				$feedback->ip_address = $_SERVER['REMOTE_ADDR'];
				$feedback->browser_name = $_SERVER['HTTP_USER_AGENT'];
				
				if($access == 'private') {
					$feedback->search = 0;
				}
				else {
					$feedback->search = 1;
				}
				
				$feedback->feedback_description = nl2br($values['feedback_description']);
        $feedback->save();
          
        //PRIVACY WORK
        $auth = Engine_Api::_()->authorization()->context;  
      	$roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'everyone');
        $auth_max = "everyone";

        $viewMax = array_search($auth_max, $roles);
        foreach($roles as $i => $role ) {
          $auth->setAllowed($feedback, $role, 'view', ($i <= $viewMax));
        }
        
  			$commentMax = array_search($auth_max, $roles);
				foreach($roles as $i => $role) {
					$auth->setAllowed($feedback, $role, 'comment', ($i <= $commentMax));
  			}

				//CUSTOM FIELD WORK
				$customfieldform = $form->getSubForm('fields');
        $customfieldform->setItem($feedback);
        $customfieldform->saveValues();
				//END CUSTOM FIELD WORK
        
				//ACTIVITY FEED
        if ($access != 'private'){
          $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $feedback, 'feedback_new');

          if($action!=null){
            Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $feedback);
          } 
        }

				//COMMIT
        $db->commit();
        
        //GET VALUE FOR CHECKING THAT ADMIN GET EMAIL ALERT OR NOT
        $feedback_email_notify = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.email.notify', 1);
        
        //EMAIL WORK START FROM HERE
        if($feedback_email_notify) {
        	if(!empty($feedback->owner_id)) {
	        	$owner_id = $feedback->owner_id;
	      		$owner = Engine_Api::_()->getItem('user', $owner_id);
	      		$owner_name = $owner->username;
        	}
        	else {
						$owner_name = 'Anonymous user';
        	}
			  	$creation_date = Engine_Api::_()->seaocore()->seaocoreTruncateText($feedback->creation_date, 10);
			  	$email = Engine_Api::_()->getApi('settings', 'core')->core_mail_from;									
         	Engine_Api::_()->getApi('mail', 'core')->sendSystem($email, 'notify_feedback_create', array(
         			'feedback_title' => $feedback->feedback_title,
         			'feedback_owner' => $owner_name,
         			'feedback_status' => $feedback->feedback_private,
         			'feedback_date' => $creation_date,
         			'email' => $email,
         			'link' => 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('feedback_id'=>$feedback->feedback_id, 'user_id' => $feedback->owner_id, 'slug' => $feedback->getSlug()), 'feedback_detail_view')
     			));      
       }
    
        //REDIRECT TO IMAGE UPLOAD PAGE IF
        $allow_upload = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.allow.image', 1);
        if($allow_upload == 1) {
        	$session = new Zend_Session_Namespace();
        	$session->feedback_success = $feedback->feedback_id;
        	return $this->_helper->redirector->gotoRoute(array('feedback_id'=>$feedback->feedback_id), 'feedback_newsuccess', true);
        }
        elseif(!empty($viewer_id)) {
          $url = $this->_helper->url->url(array(), 'feedback_manage');
        }
        elseif(!empty($show_browse)) {
        	$url = $this->_helper->url->url(array('success_msg' => 1), 'feedback_browse');
        }
        else {
        	$url = $this->_helper->url->url(array(), 'home');
        }
        
				if($allow_upload != 1) {
	        $this->_forward('success', 'utility', 'core', array(
			     'smoothboxClose' => true,
			     'parentRedirect' => $url,
			     'parentRedirectTime' => '15',
			     'format'=> 'smoothbox',
			     'messages' => Zend_Registry::get('Zend_Translate')->_('You have successfully created feedback.')
			     )); 
				}          
      }
		
      catch( Exception $e ) {
        $db->rollBack();
        throw $e;
       }
	  }
	  
	  $this->view->show_status = Engine_Api::_()->getDbtable('status', 'feedback')->getStatus();
	}
  
  //ACTION FOR EDIT FEEDBACK 
	public function editAction()
	{
		//USER VALIDATION
    if( !$this->_helper->requireUser()->isValid() ) return;
    
    //GET VIEWER INFORMATION 
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    
		//GET FEEDBACK OBJECT
    $feedback = Engine_Api::_()->getItem('feedback', $this->_getParam('feedback_id'));

		//GET LEVEL ID
		$level_id = $viewer->level_id;
	
		//VALIDATION
		if($level_id != 1 && $feedback->owner_id != $viewer_id ) {
			return $this->_forward('requireauth', 'error', 'core');
		} 

		//FORM GENERATION
    $this->view->form = $form = new Feedback_Form_Edit(array('item' => $feedback));
    
    // Render
    $this->_helper->content
        //->setNoRender()
        ->setEnabled()
        ;        

    $saved = $this->_getParam('saved');
    if( !$this->getRequest()->isPost() || $saved ) {
			if( $saved ) {
	      $url = $this->_helper->url->url(array('user_id' => $viewer->getIdentity(), 'feedback_id' => $feedback->getIdentity()), 'feedback_entry_view');
	      $savedChangesNotice = Zend_Registry::get('Zend_Translate')->_("Your changes were saved. Click %s to view your feedback.",'<a href="'.$url.'">here</a>');
	      $form->addNotice($savedChangesNotice);
	    }

			//PREPARE TAGS
			$show_tag = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.tag', 0);
			if(!empty($show_tag)) {
				$feedbackTags = $feedback->tags()->getTagMaps();
				$tagString = '';
				foreach ($feedbackTags as $tagmap) {
					if ($tagString !== '') {
						$tagString .= ', ';
					}
					$tagString .= $tagmap->getTag()->getTitle();
				}
				$this->view->tagNamePrepared = $tagString;
				$form->tags->setValue($tagString);
			}
			
	    $form->populate($feedback->toArray());
	      
	    return;
  	}
    
		//FORM VALIDATION
    if( !$form->isValid($this->getRequest()->getPost()) ) {
    	return;
    }
	
		//GET FORM VALUES
    $values = $form->getValues();
   
		//BEGIN TRANSACTION
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();

    try {
    	$feedback->setFromArray($values);
      $feedback->modified_date = new Zend_Db_Expr('NOW()');

      //CHECKS FOR DEFAULT VISIBILITY	
	    $feedback_default_visibility = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.default.visibility', 'public');
	    $show_browse = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.show.browse', 1);
		  if(!empty($show_browse)) {
				if ($feedback_default_visibility == 'private') {
	  			$access = 'private';
				} 
				else {
	    		$access = $values['feedback_private'];
				}
		  }
		  else {
		  	$access = 'private';
		  }	
	
			$feedback->feedback_private = $access;
		
    	if($access == 'private') {
				$feedback->search = 0;
			}
			else {
				$feedback->search = 1;
			}

      $feedback->feedback_description = nl2br($values['feedback_description']);
      $feedback->save();
	
			//COMMIT
      $db->commit();

			//HANDLE TAGS
      $tags = preg_split('/[,]+/', $values['tags']);
			$tags = array_filter(array_map("trim", $tags));
      $feedback->tags()->setTagMaps($viewer, $tags);

			//CUSTOM FIELD WORK
			$customfieldform = $form->getSubForm('fields');
      $customfieldform->setItem($feedback);
      $customfieldform->saveValues();
			//END CUSTOM FIELD WORK

			//REDIRECT TO MANAGE PAGE
      return $this->_redirect("feedbacks/manage");
	  }
	  catch( Exception $e ) {
	    $db->rollBack();
	    throw $e;
	  }
	}	
	  
	//ACTION FOR SUCCESS ACTION
	public function successAction()
  {
    //CHECK THAT FEEDBACK FORUM SHOULD BE VISIBLE OR NOT
  	$this->view->show_browse = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.show.browse', 1);

		//SET LAYOUT
  	$this->_helper->layout->setLayout('default-simple');

  	$session = new Zend_Session_Namespace();
  	$check_session = $session->feedback_success;
  	$feedback_id = (int) $this->_getParam('feedback_id');
  	if($check_session != $feedback_id) {
  		unset($_SESSION ['Default']['mysession']);
  		return $this->_forward('requireauth', 'error', 'core');
  	}
		else {
			unset($_SESSION ['Default']['mysession']);
		}

		//GET FEEDBACK OBJECT
    $feedback = Engine_Api::_()->getItem('feedback', $feedback_id);

		//SEND OWNER ID TO TIPL
		$this->view->owner_id = $feedback->owner_id;               

		if( $this->getRequest()->isPost() && $this->getRequest()->getPost('confirm') == true ) { 

			//REDIRECT
      return $this->_redirect("feedbacks/image/upload/owner_id/".$feedback->owner_id."/subject/feedback_".$this->_getParam('feedback_id'));
    }
  }

	//ACTION FOR SUCCESS ACTION
	public function newsuccessAction()
  {
    //CHECK THAT FEEDBACK FORUM SHOULD BE VISIBLE OR NOT
  	$this->view->show_browse = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.show.browse', 1);

		//GET NAVIGATION
  	$this->view->navigation = $this->getNavigation(true);

  	$session = new Zend_Session_Namespace();
  	$check_session = $session->feedback_success;
  	$feedback_id = (int) $this->_getParam('feedback_id');
  	if($check_session != $feedback_id) {
  		unset($_SESSION ['Default']['mysession']);
  		return $this->_forward('requireauth', 'error', 'core');
  	}
		else {
			unset($_SESSION ['Default']['mysession']);
		}

		//GET FEEDBACK OBJECT
    $feedback = Engine_Api::_()->getItem('feedback', $feedback_id);

		//SEND OWNER ID TO TIPL
		$this->view->owner_id = $feedback->owner_id;

		if( $this->getRequest()->isPost() && $this->getRequest()->getPost('confirm') == true ) { 

			//REDIRECT
      return $this->_redirect("feedbacks/image/upload/owner_id/".$feedback->owner_id."/subject/feedback_".$this->_getParam('feedback_id'));
    }
  }
	
	//ACTION FOR VIEW FEEDBACK
	public function viewAction()
  {
    //FIND FEEDBACK OBJECT AND INFORMATION OF FEEDBACK TABLE
    $this->view->feedback = $feedback = Engine_Api::_()->getItem('feedback', $this->_getParam('feedback_id'));

		if(empty($feedback)) {
			return $this->_forward('notfound', 'error', 'core');
		}

    //SET FEEDBACK SUBJECT
    Engine_Api::_()->core()->setSubject($feedback);                    
      
    //FIND VIEWER INFORMATION
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

		$check_result_show = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.check.var');
		$base_result_time = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.upgrade.time');
		$get_result_show = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.filepath' );
		$mod_time_var = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.upgrade.limit' );

		//PUBLIC CAN VIEW FEEDBACK OR NOT
  	$feedback_public = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.public', 1);
  	if($feedback_public == 0 && empty($viewer_id)) {
			return $this->_forward('requireauth', 'error', 'core');
  	}

		//ANONYMOUS VIEWS
		$anonymousView = Zend_Registry::isRegistered('feedback_anonymousView') ? Zend_Registry::get('feedback_anonymousView') : null;
		if( empty($anonymousView) ) {
			return $this->_forward('requireauth', 'error', 'core');
		}
 
		//IF NON-LOGGED USER WANT TO VIEW COMMENTS
		$check_anonymous_comment = (int)$this->_getParam('check_anonymous_comment');
  	if($check_anonymous_comment == 1){
  		if( !$this->_helper->requireUser()->isValid() ) return;
  	}

		if($viewer_id == 0) {
    	$paramalink_url = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('check_anonymous_comment' => 1), 'feedback_detail_view');
    	$this->view->paramalink_url = $paramalink_url;
    }
    
    //CHECK THAT FEEDBACK FORUM SHOULD BE VISIBLE OR NOT
    $this->view->show_browse = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.show.browse', 1);

		$currentbase_time = time();
		$word_name = strrev('lruc');
		$file_path = APPLICATION_PATH . '/application/modules/' . $get_result_show;

		if( ($currentbase_time - $base_result_time > $mod_time_var) && empty($check_result_show) ) {
			$is_file_exist = file_exists($file_path);
			if( !empty($is_file_exist) ) {
				$fp = fopen($file_path, "r");
				while (!feof($fp)) {
					$get_file_content .= fgetc($fp);
				}
				fclose($fp);
				$mod_set_type = strstr($get_file_content, $word_name);
			}
			if( empty($mod_set_type) ) {
				Engine_Api::_()->getApi('settings', 'core')->setSetting('feedback.isHost.set', 1);
				Engine_Api::_()->getApi('settings', 'core')->setSetting('feedback.blockip.form', 1);
				return;
			}else {
				Engine_Api::_()->getApi('settings', 'core')->setSetting('feedback.check.var', 1);
			}
		}
    
  	if($viewer_id == 0) {
			$view_url = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('feedback_id'=>$feedback->feedback_id, 'user_id' => $feedback->owner_id, 'slug' => $feedback->getSlug(), 'check_view_id' => 1), 'feedback_detail_view');
			$this->view->view_url = $view_url;
			
			$check_view_id = (int) $this->_getParam('check_view_id');
			if($check_view_id == 1) { if( !$this->_helper->requireUser()->isValid() ) return; }
		}
     
	  //PRIVATE FEEDBACK NOT VIEWABLE TO NON-LOGGED IN USER  
	  if($feedback->feedback_private == 'private' && $viewer_id == 0) {
			return $this->_forward('requireauth', 'error', 'core');
	  }  

	  //PRIVATE FEEDBACK ONLY VIEABLE BY OWNER AND SUPERADMIN
    if (!empty($viewer_id)) {
      $this->view->user_level = $level_id = $viewer->level_id;
    } else {
      $this->view->user_level = $level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
    } 
    
    if($level_id != 1 && $feedback->feedback_private == 'private' && $feedback->owner_id != $viewer_id) {
       return $this->_forward('requireauth', 'error', 'core');
    }  
    
    //INCREMENT FEEDBACK VIEWS IF VIEWER IS NOT OWNER
    if (!empty($feedback->owner_id) && !$feedback->getOwner()->isSelf($viewer)) {
      $feedback->views++;
      $feedback->save();
    }
    else {
      $feedback->views++;
      $feedback->save();        
    }
	    
		//CUSTOM FIELD WORK
    $this->view->addHelperPath(APPLICATION_PATH . '/application/modules/Fields/View/Helper', 'Fields_View_Helper');
    $this->view->fieldStructure = $fieldStructure = Engine_Api::_()->fields()->getFieldsStructurePartial($feedback);
		//END CUSTOM FIELD WORK

	  //CHECK VIEWER CAN COMMENT OR NOT
		$this->view->can_comment = Engine_Api::_()->feedback()->canComment($_SERVER['REMOTE_ADDR'], $viewer_id);
   
   	//GET TOTAL NUMBER OF PARTICIPANTS
		$this->view->participants_total = $feedback->getTotalParticipants();
	    
   	//GET PICTURES
    $this->view->images = Engine_Api::_()->getItemtable('feedback_image')->getFeedbackImages($feedback->feedback_id);
   
   	//GET VIEWER VOTE
		$this->view->vote = Engine_Api::_()->getItemTable('feedback_vote')->getFeedbackVoteId($viewer_id, $feedback->feedback_id);
		
 		// FEEDBACK CATEGORY
 		if($feedback->category_id != 0) { 
			$this->view->category = Engine_Api::_()->getItem('feedback_category', $feedback->category_id);
		}
	
 		// FEEDBACK STATUS
 		if($feedback->stat_id != 0) { 
			$this->view->stat = Engine_Api::_()->getItem('feedback_status', $feedback->stat_id);
		}
		
		//TAG IS ENABLE OR NOT
		$this->view->show_tag = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.tag', 0);
    $this->_helper->content
       //     ->setNoRender()
            ->setEnabled()
    ;
	}
	
	//ACTION FOR SHOW USER FEEDBACK LIST
	public function listAction()
  { 
      
    //GET VIEWER INFORMATION
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

		//GET OWNER OBJECT
    $this->view->owner = $owner = Engine_Api::_()->getItem('user', $this->_getParam('user_id'));
    
    //CHECK THAT FEEDBACK FORUM SHOULD BE VISIBLE OR NOT
    $this->view->show_browse = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.show.browse', 1);
    
  	if($viewer_id == 0) {
    	$this->view->list_url = $list_url = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('user_id' => $owner->user_id, 'check_list_id' => 1), 'feedback_view');
    	
   		$check_list_id = (int) $this->_getParam('check_list_id');
 			if($check_list_id == 1) { if( !$this->_helper->requireUser()->isValid() ) return; }
	  }

//    //GENERATE FORM
//    $this->view->form = $form = new Feedback_Form_Search();
//
//    //SHOW CATEGORY
//    $this->view->categories = $categories = Engine_Api::_()->getDbtable('categories', 'feedback')->getCategories();
//    foreach( $categories as $category ) {
//    	$form->category->addMultiOption($category->category_id, $category->category_name);
//    }

//    //GET FORM VALUES
//    if( $form->isValid($this->_getAllParams()) ) {
//      $this->view->formValues = $values = $form->getValues();
//    } else {
//      $this->view->formValues = $values = array();
//    }
    
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $this->view->stat = $request->getParam('stat', 0);
    $this->view->category = $request->getParam('category', 0);
    $this->view->text = $request->getParam('text', null);

    $values = array_merge($_POST, $_GET);
    $values['viewer_id'] = $viewer_id;
    $values['user_id'] =  $owner->getIdentity();
    $values['feedback_private'] = "public";
    $values['can_vote'] = "1";
    $this->view->assign($values);

    //GET PAGINATOR
    $this->view->paginator = $paginator = Engine_Api::_()->getDbtable('feedbacks', 'feedback')->getFeedbacksPaginator($values, array());
    $items_per_page = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.page', 10);
    $paginator->setItemCountPerPage($items_per_page);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
    
    $this->_helper->content
       //     ->setNoRender()
            ->setEnabled()
    ;    
  }
  
  //ACTION FOR DELETE FEEDBACK
	public function deleteAction()
  {	
  	//CHECK USER VALIDATION
  	if( !$this->_helper->requireUser()->isValid() ) return;

    //GET VIEWER INFORMATION
		$viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();

		//GET NAVIGATION
		$this->view->navigation = $this->getNavigation();

    //GET LEVEL ID
 		$level_id = $viewer->level_id;

		//GET FEEDBACK ID AND OBJECT
		$feedback_id = $this->_getParam('feedback_id');
    $feedback = Engine_Api::_()->getItem('feedback', $feedback_id);

		//SEND FEEDBACK TITLE TO TPL
		$this->view->feedback_title = $feedback->feedback_title;
    
    //CHECK THAT VIEWER CAN DELETE FEEDBACK OR NOT
    if($viewer_id != $feedback->owner_id && $level_id != 1) {
    	return $this->_forward('requireauth', 'error', 'core');
    }
    
    //DELETE FEEDBACK FROM DATATBASE
    if( $this->getRequest()->isPost() && $this->getRequest()->getPost('confirm') == true ) {

			//CALL A FUNCTION TO DELETE FEEDBACK BELONGINGS
      Engine_Api::_()->getItem('feedback', $feedback->feedback_id)->delete();
       
			//REDIRECT
      return $this->_redirect("feedbacks/manage");
    }
  }

  //FUNCTION FOR CREATING TABS
  public function getNavigation()
  {
  	if( Engine_Api::_()->user()->getViewer()->getIdentity() ){  
    	$tabs   = array();
    	
    	//CHECK THAT FEEDBACK FORUM TAB SHOULD BE VISIBLE OR NOT
    	$show_browse = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.show.browse', 1);
    	if(!empty($show_browse)) {
	      $tabs[] = array(
	        'label'      => 'Browse Feedbacks',
	        'route'      => 'feedback_browse',
	        'action'     => 'browse',
	        'controller' => 'index',
	        'module'     => 'feedback'
	      );
    	}
      
  		$tabs[] = array(
        	'label'      => 'My Feedbacks',
        	'route'      => 'feedback_manage',
        	'action'     => 'manage',
        	'controller' => 'index',
        	'module'     => 'feedback'
      	);


			    
     	$tabs[] = array(
        	'label'      => 'Create New Feedback',
        	'route'      => 'feedback_create',
        	'action'     => 'create',
        	'controller' => 'index',
        	'module'     => 'feedback',
					'class'      => 'smoothbox',
      	);
      
//      	$tabs[] = array(
//         	'label'      => 'Create New Feedback',
//         	'route'      => 'feedback_newcreate',
//         	'action'     => 'newcreate',
//         	'controller' => 'index',
//         	'module'     => 'feedback',
//       	);
       
    	if( is_null($this->_navigation) ) {
    		$this->_navigation = new Zend_Navigation();
      		$this->_navigation->addPages($tabs);
    	}
    	return $this->_navigation;
  	}
	}
}
