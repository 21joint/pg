<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: ImageController.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_ImageController extends Core_Controller_Action_Standard
{
	//COMMAN FUNCATION WHICH CALLS AUTOMATICALLY BEFORE ALL ACTIONS
	public function init()
  {
		//GET SUBJECT
    if( !Engine_Api::_()->core()->hasSubject() ) {
    	if( 0 !== ($image_id = (int) $this->_getParam('image_id')) &&
				null !== ($image = Engine_Api::_()->getItem('feedback_image', $image_id)) ) {
				Engine_Api::_()->core()->setSubject($image);
      }
			else if( 0 !== ($feedback_id = (int) $this->_getParam('feedback_id')) &&
				null !== ($feedback = Engine_Api::_()->getItem('feedback', $feedback_id)) ) {
				Engine_Api::_()->core()->setSubject($feedback);
			}
    }
	}

	//ACTION FOR IMAGE VIEW
	public function viewAction()
  {
		//IF IMAGE SUBJECT IS NOT SET
		if( !Engine_Api::_()->core()->hasSubject() ) {
			return $this->_forward('requireauth', 'error', 'core');
		}	
		
		//GET IMAGE SUBJECT
		$this->view->image = $image = Engine_Api::_()->core()->getSubject();

		//GET ALBUM OBJECT
    $this->view->album = $album = $image->getCollection();

		//GET VIEWER INFO
		$viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();

		//GET LEVEL ID
		$level_id = 0;
  	if(!empty($viewer_id)) {
	  	$level_id = $viewer->level_id;
		}
	
		//GET FEEDBACK MODEL
		$feedback = Engine_Api::_()->getItem('feedback', $album->feedback_id);
    
		//WHO CAN VIEW PRIVATE IMAGE
		if($feedback->feedback_private == 'private' && empty($viewer_id) && $feedback->owner_id != $viewer_id && $level_id != 1) {
			return $this->_forward('requireauth', 'error', 'core');
		}
  }
	
	//ACTION FOR UPLOADING IMAGES
  public function uploadAction()
  {  
		//GET FEEDBACK SUBJECT
    $feedback = Engine_Api::_()->core()->getSubject();

		//CHECK VALIDATION
    if( isset($_GET['ul']) || isset($_FILES['Filedata']) ) return $this->_forward('upload-image', null, null, array('format' => 'json', 'feedback_id'=>(int) $feedback->getIdentity()));

		//GET VIEWER
    $viewer = Engine_Api::_()->user()->getViewer();
    
		//GET ALBUM
    $album = $feedback->getSingletonAlbum();

		//GET FEEDBACK ID
    $this->view->feedback_id = $feedback->feedback_id;

		//FORM GENERATION
    $this->view->form = $form = new Feedback_Form_Image_Upload();
    $form->file->setAttrib('data', array('feedback_id' => $feedback->getIdentity()));

		//FORM VALIDATION
    if( !$this->getRequest()->isPost() || !$form->isValid($this->getRequest()->getPost())) {
    	return;
    }

		//BEGIN TRANSACTION
    $db = Engine_Api::_()->getItemTable('feedback_image')->getAdapter();
    $db->beginTransaction();

    try {

			//GET FORM VALUES
    	$values = $form->getValues();

      $params = array(
        'feedback_id' => $feedback->getIdentity(),
        'user_id' => $viewer->getIdentity(),
      );

      $count = 0;
      foreach( $values['file'] as $image_id ) {
        $image = Engine_Api::_()->getItem("feedback_image", $image_id);
        if( !($image instanceof Core_Model_Item_Abstract) || !$image->getIdentity() ) continue;
        
        $image->collection_id = $album->album_id;
        $image->album_id = $album->album_id;
        $image->save();

        if ($feedback->image_id == 0) {
          $feedback->image_id = $image->file_id;
          $feedback->save();
        }

        $count++;
      }

			//COMMIT
      $db->commit();
    }

    catch( Exception $e ) {
      	$db->rollBack();
      	throw $e;
    }
    $show_browse = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.show.browse', 1);
		if(empty($feedback->owner_id) && !empty($show_browse)) {
			$browse_url = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('success_msg' => 1), 'feedback_browse');

			//REDIRECT
			$this->_redirectCustom($browse_url);
		}
		elseif(!empty($feedback->owner_id)) {
	
			//REDIRECT
			$this->_redirectCustom($feedback);
		}	
		else{
			$url = $this->_helper->url->url(array(), 'home');
			$this->_redirectCustom($url);
		}
  }

	//ACTION FOR IMAGE UPLOADING
  public function uploadImageAction()
  { 
		//GET FEEDBACK OBJECT
    $feedback = Engine_Api::_()->getItem('feedback', (int) $this->_getParam('feedback_id'));

    //GET VIEWER INFORMATION
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    
		//FILE VALIDATION
    if(!empty($viewer_id)) {
	    if( !$this->_helper->requireUser()->checkRequire() ) {
	    	$this->view->status = false;
	      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Max file size limit exceeded (probably).');
	      return;
	    }
    }

		//FORM VALIDATION
    if( !$this->getRequest()->isPost() ) {
    	$this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
      return;
    }

		//GET FORM VALUES
    $values = $this->getRequest()->getPost();
    if( empty($values['Filename']) ) {
    	$this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('No file');
      return;
    }

    if( !isset($_FILES['Filedata']) || !is_uploaded_file($_FILES['Filedata']['tmp_name']) ) {
    	$this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid Upload');
      return;
    }

		//BEGIN TRANSACTION
    $db = Engine_Api::_()->getDbtable('images', 'feedback')->getAdapter();
    $db->beginTransaction();

    try {

			//START DATABASE WORK
      $album = $feedback->getSingletonAlbum();

      $params = array(
        'collection_id' => $album->getIdentity(),
        'album_id' => $album->getIdentity(),
        'feedback_id' => $feedback->getIdentity(),
        'user_id' => $viewer->getIdentity(),
      );
      
      $image_id = $feedback->createImage($params, $_FILES['Filedata'])->image_id;

      $feedback->total_images++;
			if(!$feedback->image_id) {
        $feedback->image_id = $image_id;     
      }
      $feedback->save();
			$db->commit();

     	$this->view->status = true;
      $this->view->name = $_FILES['Filedata']['name'];
      $this->view->image_id = $image_id;
    }

    catch( Exception $e ) {
				$db->rollBack();
      	$this->view->status = false;
      	$this->view->error = Zend_Registry::get('Zend_Translate')->_('An error occurred.');
      	return;
    }
  }

  //ACTION FOR DELETE IMAGE
  public function removeAction()
  { 

  	//GET VIEWER INFORMATION
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();

		//GET FEEDBACK ID AND OBJECT
    $feedback_id = (int) $this->_getParam('feedback_id');
    $this->view->feedback = $feedback = Engine_Api::_()->getItem('feedback', $feedback_id);
    
    if( $this->getRequest()->isPost() && $this->getRequest()->getPost('confirm') == true ) {
    
			//GET IMAGE ID AND OBJECT
	    $image_id= (int) $this->_getParam('image_id');
	    $image = Engine_Api::_()->getItem('feedback_image', $image_id);
				
			//DELETE IMAGE OBJECT
			$image->delete();

			//SAVE NUMBER OF IMAGES IN DATABASE
			$feedback->total_images--;
			$feedback->save();
      
      $slug = $feedback->getSlug();

			//REDIRECT
	    return $this->_redirect("feedbacks/$feedback->owner_id/$feedback_id/$slug");
		}
  }
}

