<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */

class Sdparentalguide_Widget_MainphotoSitereviewController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    //DONT RENDER IF SUBJECT IS NOT SET
    if (!Engine_Api::_()->core()->hasSubject('sitereview_listing')) {
      return $this->setNoRender();
    }
    $this->getElement()->setAttrib("class", "layout_sitereview_mainphoto_sitereview");

    //GET LISTING TYPE
    $this->view->listingtype_id = $listingtype_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('listingtype_id', null);
    $this->view->listingType = $listingType = Engine_Api::_()->getItem('sitereview_listingtype', $listingtype_id);

    //GET SUBJECT AND OTHER SETTINGS
    $this->view->sitereview = $sitereview = Engine_Api::_()->core()->getSubject('sitereview_listing');
    $this->view->show_featured = $listingType->featured;
    $this->view->featured_color = $listingType->featured_color;
    $this->view->show_sponsered = $listingType->sponsored;
    $this->view->sponsored_color = $listingType->sponsored_color;
    
    $this->view->ownerName = $this->_getParam('ownerName', 0);
    $this->view->show_buttons = $this->_getParam('show_buttons', array("wishlist", "compare", "comment", "like", 'share', 'facebook', 'twitter', 'pinit'));
    $this->view->gutterNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation("sitereview_gutter_listtype_$listingType->listingtype_id");

    //GET VIEWER AND CHECK VIEWER CAN EDIT PHOTO OR NOT
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->can_edit = $sitereview->authorization()->isAllowed($viewer, 'edit_listtype_' . $sitereview->listingtype_id);
    $this->view->listingtype_id = $sitereview->listingtype_id;
  }

}