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
class Feedback_Widget_SearchBoxFeedbacksController extends Engine_Content_Widget_Abstract
{ 
  public function indexAction()
  {    
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $actionName = $request->getActionName();
    $controllerName = $request->getControllerName();
    $moduleName = $request->getModuleName();
    
    if($moduleName != 'feedback' || $controllerName != 'index' || ($actionName != 'view' && $actionName != 'list')) {
        return $this->setNoRender();
    }
    
    //DON'T RENDER IF SUBJECT IS NOT SET
    if ($actionName == 'view' && Engine_Api::_()->core()->hasSubject('feedback')) {
        $this->view->owner_id = Engine_Api::_()->core()->getSubject()->owner_id;
    }
    elseif($actionName == 'list' && $request->getParam('user_id')) {
        $this->view->owner_id = $request->getParam('user_id');
    }
    
    if(!$this->view->owner_id) {
        return $this->setNoRender();
    }
    
    $this->view->text = $request->getParam('text', null);
  }
}