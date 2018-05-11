<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.tpl 6590 2010-08-11 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_Widget_OwnerTagsFeedbacksController extends Engine_Content_Widget_Abstract
{ 
  public function indexAction()
  {
    //DON'T RENDER IF SUBJECT IS NOT SET
    if (!Engine_Api::_()->core()->hasSubject('feedback')) {
        return $this->setNoRender();
    }
    
    //GET FEEDBACK OWNER
    $this->view->feedback = $feedback = Engine_Api::_()->core()->getSubject(); 
    
		//TAG IS ENABLE OR NOT
		if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.tag', 0) || !$feedback->owner_id) {
      return $this->setNoRender();  
    }
    
    //FIND FEEDBACK OWNER TAGS
    $this->view->feedbackTags = $feedback->tags()->getTagMaps();

    //USER TAG ARRAY
    $tag_cloud_array = Engine_Api::_()->feedback()->getOwnerTags($feedback->owner_id);

    $tag_id_array = array();
    foreach ($tag_cloud_array as $vales) {
      $tag_id_array[$vales['text']] = $vales['tag_id'];
    }

    $this->view->tag_id_array = $tag_id_array;
    
    if(Count($tag_id_array) <= 0) {
        return $this->setNoRender();  
    }
    
    $element = $this->getElement();
    $element->setTitle(sprintf($this->getElement()->getTitle(), $this->view->htmlLink($feedback->getOwner()->getHref(), $feedback->getOwner()->getTitle(), array('title' => $feedback->getOwner()->getTitle()))));
  }
}