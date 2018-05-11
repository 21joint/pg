<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Sdparentalguide
 * @author     Stars Developer
 */


class Sdparentalguide_Widget_SearchboxSitereviewController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->listingtype_id = $listingtype_id = $this->_getParam('listingtype_id', 0);
    if (empty($listingtype_id)) {
      $this->view->listingtype_id = $listingtype_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('listingtype_id', 0);
    }

    //PREPARE FORM
    $this->view->form = new Sdparentalguide_Form_Searchbox();
    $this->getElement()->setAttrib("class","layout_sitereview_searchbox_sitereview");
    
  }

}
