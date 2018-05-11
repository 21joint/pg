<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: MobiController.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageevent_MobiController extends Core_Controller_Action_Standard {

  public function init() {
    if (0 !== ($page_id = (int) $this->_getParam('page_id')) &&
            null !== ($sitepage = Engine_Api::_()->getItem('sitepage_page', $page_id))) {
      Engine_Api::_()->core()->setSubject($sitepage);
    }
  }

  //ACTION FOR VIEW THE EVENT
  public function viewAction() {
    
   //CHECK THE VERSION OF THE CORE MODULE
    $coremodule = Engine_Api::_()->getDbtable('modules', 'core')->getModule('core');
    $coreversion = $coremodule->version;
    if ($coreversion < '4.1.0') {
      $this->_helper->content->render();
    } else {
      $this->_helper->content
              ->setNoRender()
              ->setEnabled()
      ;
    }
  }
  
}

?>